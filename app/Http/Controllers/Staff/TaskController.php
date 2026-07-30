<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $status = $request->input('status', 'pending');
        $departmentId = $request->input('department_id', '');
        
        $tasks = Task::with(['user', 'department','files'])
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('department_id', $departmentId);
            })->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        return Inertia::render('Staff/Tasks', [
            'tasks' => $tasks,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'department_id' => $departmentId,
                'per_page' => $perPage,
            ],
            'departments' => auth()->user()->departments()->get(),
            //'users' => User::select('id', 'name')->get(),
        ]);
    }

    public function create()
    {
        $departments=auth()->user()->departments()->get();
        return Inertia::render('Staff/TaskForm', [
            'task' => (object) [
                'department_id' => $departments->first()?->id,
            ],
            'departments' => $departments
            //'users' => User::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'action' => 'nullable|string',
            'result' => 'nullable|string',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'files.*' => 'nullable|file|max:10240', // Max 10MB per file
            'files' => 'nullable|array|max:10', // Max 10 files            
        ]);
        $validated['user_id']=auth()->user()->id;

        $task = Task::create($validated);
        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $isPrimary = $index === 0; // First file is primary
                TaskFile::uploadFile($file, $task, $isPrimary, $index);
            }
        }

        return redirect()->route('staff.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['user', 'department','files']);
        
        return Inertia::render('Staff/TaskForm', [
            'task' => $task,
            'departments' => auth()->user()->departments()->get(),
            'users' => User::select('id', 'name')->get(),
            'readonly' => true,
        ]);
    }

    public function edit(Task $task)
    {
        $task->load(['user', 'department','files']);
        
        return Inertia::render('Staff/TaskForm', [
            'task' => $task,
            'departments' => auth()->user()->departments()->get(),
            'users' => User::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Task $task)
    {
       
            $validated = $request->validate([
                'department_id' => 'nullable|exists:departments,id',
                'user_id' => 'required|exists:admin_users,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'action' => 'nullable|string',
                'result' => 'nullable|string',
                'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
                'files.*' => 'nullable|file|max:10240',
                'files' => 'nullable|array|max:10',
                'delete_files' => 'nullable|array',                
            ]);


            $task->update($validated);
            // Delete specified files
            if ($request->has('delete_files')) {
                $deleteFiles = $request->input('delete_files', []);
                TaskFile::whereIn('id', $deleteFiles)
                    ->where('task_id', $task->id)
                    ->each(function ($file) {
                        $file->deleteFile();
                    });
            }
            // Handle new file uploads
            if ($request->hasFile('files')) {
                $currentFileCount = $task->files()->count();
                $maxFiles = 10;
                $allowedToUpload = $maxFiles - $currentFileCount;

                if ($allowedToUpload > 0) {
                    $files = array_slice($request->file('files'), 0, $allowedToUpload);
                    foreach ($files as $index => $file) {
                        $sortOrder = $currentFileCount + $index;
                        $isPrimary = $task->files()->count() === 0 && $index === 0;
                        TaskFile::uploadFile($file, $task, $isPrimary, $sortOrder);
                    }
                }
            }            
            return redirect()->route('staff.tasks.index')
                ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('staff.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

        // Download a specific file
    public function downloadFile(TaskFile $file)
    {
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }
    // Set primary image
    public function setPrimaryFile(Request $request, TaskFile $file)
    {
        $request->validate([
            'is_primary' => 'required|boolean',
        ]);

        // Remove primary from all files of this task
        TaskFile::where('task_id', $file->task_id)
            ->update(['is_primary' => false]);

        // Set this file as primary
        $file->update(['is_primary' => true]);

        return back()->with('success', 'Primary file updated.');
    }

    // Reorder files
    public function reorderFiles(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*.id' => 'required|exists:task_files,id',
            'files.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->input('files') as $fileData) {
            TaskFile::where('id', $fileData['id'])
                ->update(['sort_order' => $fileData['sort_order']]);
        }

        return response()->json(['message' => 'Files reordered successfully.']);
    }    
}