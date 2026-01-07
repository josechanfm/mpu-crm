<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SouvenirUser;
use App\Imports\SouvenirUserImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\IdValidatorController;
use App\Exports\SouvenirUserExport;

class SouvenirUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->all());
        $users = SouvenirUser::query();
        if ($request->has('filter_column') && !is_null($request->filter_column && $request->has('filter_value') && !is_null($request->filter_value))) {
            $users = $users->where($request->filter_column, $request->filter_value);
        }else{
            $users = $users->where('can_buy', true);
        }
        if($request->search_column && $request->search_column=='buyer' && $request->search_text){
            $users = $users->where('netid', 'LIKE', '%'.$request->search_text.'%');
        }

        return Inertia::render('Department/Dae/SouvenirUsers',[
            'users' => $users->paginate($request->per_page)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'netid' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'faculty_code' => 'nullable|string|max:10',
            'degree_code' => 'nullable|string|max:10',
            'can_buy' => 'required|boolean',
        ]);
        SouvenirUser::upsert(
            $request->all(),
            ['netid'],
            ['name_zh','name_en','email','phone','faculty_code','degree_code','can_buy']
        );
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SouvenirUser $souvenirUser)
    {
        $idValidator = new IdValidatorController();
        $isvalid=$idValidator->_ipm_student_id_version_1_1('p2500012');
        dd($isvalid, $souvenirUser);
        return Inertia::render('Department/Dae/SouvenirUserShow', [
            'user' => $souvenirUser
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'netid' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'faculty_code' => 'nullable|string|max:10',
            'degree_code' => 'nullable|string|max:10',
            'can_buy' => 'required|boolean',
        ]);
        
        //dd(SouvenirUser::find($id), $request->all());
        SouvenirUser::find($id)->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        try {
            // Import the file into an array
            $data = Excel::toArray(new SouvenirUserImport, $request->file('file'));
            $idValidator = new IdValidatorController();
            $wrongs=[];
            $updates=[];
            $creates=[];
            $validFaculties = ['FAC', 'FHSS', 'FLT', 'FAD', '', 'FB', 'AE'];
            $validDegrees = ['BACHALOR', 'MASTER', 'PHD'];
            // Loop through the imported data if needed
            // $data[0] is sheet 1
            foreach ($data[0] as $row) {
                //echo $row[0];
                // Define the allowed values for each row index

                // Check if $row[5] is not in the valid faculties
                if (!in_array($row[5], $validFaculties)) {
                    $row[5] = null; // Set to null if not valid
                }

                // Check if $row[6] is not in the valid degrees
                if (!in_array($row[6], $validDegrees)) {
                    $row[6] = null; // Set to null if not valid
                }
                if($idValidator->_ipm_student_id_version_1_1($row[0])) {
                    $user=SouvenirUser::where('netid', $row[0])->first();
                    //dd($user, $row, $row[0]);
                    if($user) {
                        $updates[]=$row;
                    }else{
                        $creates[]=$row;
                    }
                    // Process each row as needed
                    // You can access columns via $row['column_name'] based on your Excel structure
                }else{
                    $wrongs[]=$row;
                }
            }
            // Optionally, return a success response
            return Inertia::render('Department/Dae/SouvenirUserImport', [
                'records' => [
                    'wrongs' => $wrongs,
                    'updates' => $updates,
                    'creates' => $creates
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the import
            return response()->json(['error' => 'Failed to import file: ' . $e->getMessage()], 500);
        }
    }
    public function importConfirm(Request $request){
        $request->validate([
            'records' => 'required|array',
        ]);
        $records = $request->input('records');
        //dd($records, isset($records['updates']), isset($records['creates']));
        // Process the confirmed import
        // Check for 'updates' and process them
        if (isset($records['updates'])) {
            foreach ($records['updates'] as $update) {
                // Assuming 'id' is the identifier for the souvenir user
                SouvenirUser::where('netid', $update[0])->update([
                    'name_zh'=>$update[1],
                    'name_en'=>$update[2],
                    'email'=>$update[3],
                    'phone'=>$update[4],
                    'faculty_code'=>$update[5],
                    'degree_code'=>$update[6],
                    'can_buy'=>$update[7]==1?true:false
                ]);
            }
        }
        // Check for 'creates' and process them
        if (isset($records['creates'])) {
            foreach ($records['creates'] as $create) {
                SouvenirUser::create([
                    'netid'=>$create[0],
                    'name_zh'=>$create[1],
                    'name_en'=>$create[2],
                    'email'=>$create[3],
                    'phone'=>$create[4],
                    'faculty_code'=>$create[5],
                    'degree_code'=>$create[6],
                    'can_buy'=>$create[7]==1?true:false
                ]);
            }
        }
        return redirect()->route('dae.souvenir.users.index')->with('success', 'Import confirmed and processed successfully.');

    }

    public function batchDelete(Request $request){
        SouvenirUser::whereIn('id',$request->ids)->delete();
        return redirect()->back();
        //return SouvenirUser::whereIn('id',$request->ids)->get();
    }
    public function batchExport(Request $request){
        // dd('batchExport', $request->all());
        // return SouvenirUser::whereIn('id',$request->ids)->get();
        // return redirect()->back();
        // Get IDs from request, default to empty array
        $ids = $request->ids ?: [];
        
        // Convert string to array if needed
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        // Generate filename
        $filename = 'souvenir_users_' . now()->format('Ymd_His') . '.xlsx';
        // Export
        return Excel::download(new SouvenirUserExport($ids), $filename);
        

    }
    public function batchUpdate(Request $request){
        SouvenirUser::whereIn('id',$request->ids)->update(['can_buy'=>$request->can_buy]);
        return redirect()->back();

    }
}
