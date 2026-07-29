<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $departmentId = $request->input('department_id', '');
        
        $tasks = Task::with(['user', 'department'])
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
        ]);
        $validated['user_id']=auth()->user()->id;

        $task = Task::create($validated);

        return redirect()->route('staff.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['user', 'department']);
        
        return Inertia::render('Staff/TaskForm', [
            'task' => $task,
            'departments' => auth()->user()->departments()->get(),
            'users' => User::select('id', 'name')->get(),
            'readonly' => true,
        ]);
    }

    public function edit(Task $task)
    {
        $task->load(['user', 'department']);
        
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
            ]);

            $task->update($validated);

            return redirect()->route('staff.tasks.index')
                ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('staff.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}