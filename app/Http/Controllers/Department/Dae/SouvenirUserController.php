<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SouvenirUser;
use App\Imports\SouvenirUserImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\IdValidatorController;

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
            'users' => $users->paginate()
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
        //
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
        //
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

}
