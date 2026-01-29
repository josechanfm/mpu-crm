<?php

namespace App\Http\Controllers\Department\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffRelative;
use App\Models\StaffNotice;
use App\Models\StaffUpload;
use Carbon\Carbon;
use TCPDF;



class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $cnt=0;

    public function index(Request $request)
    {
        // dd($request->all());
        $staffs=Staff::query();
        if($request->has('search_field') && $request->has('search_value') && $request->search_field!=null && $request->search_value!=null){
            $staffs->where($request->search_field, 'like', '%'.$request->search_value.'%');
        }
        return inertia('Department/Personnel/Staffs',[
            'staffs'=>$staffs->paginate($request->get('per_page',$request->per_page)),
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
     * @param  int  $i d
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {

        //dd($staff->load('relatives'));
        return inertia('Department/Personnel/StaffCard',[
            'staff'=>$staff->load('relatives')

        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        $data=Staff::get_remote_data('family', 'super', $staff->staff_num);
        return inertia('Department/Personnel/StaffEdit',[
            'staff'=>$staff->load('uploads'),
            'families'=>$data->family
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff)
    {
        dd($staff, $request->all());
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




    public function avatarUpload(Request $request, Staff $staff)
    {
        if ($request->has('model') && $request->model === 'staff' && $request->id == $staff->id) {
            return $this->handleAvatarUpload($request->upload, $staff);
        }

        if ($request->has('model') && $request->model === 'relative') {
            $relative = StaffRelative::find($request->id);
            return $this->handleAvatarUpload($request->upload, $relative);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
    }

    private function handleAvatarUpload($base64Image, $model)
    {
        // Define the storage path for the existing avatar
        $existingAvatarPath = public_path('/images/staffs/' . $model->avatar);

        // Check if the existing avatar exists
        if ($model->avatar && file_exists($existingAvatarPath)) {
            // Remove the existing avatar
            unlink($existingAvatarPath);
        }

        // Extract the base64 data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $data = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]); // jpeg, png, gif

            // Determine the file extension
            $extension = $type === 'jpg' ? 'jpeg' : $type;

            // Generate a unique filename
            $fileName = uniqid() . '.' . $extension;

            // Path to save the new avatar
            $newAvatarPath = public_path('/images/staffs/' . $fileName);

            // Save the new avatar
            if (file_put_contents($newAvatarPath, base64_decode($data))) {
                // Update the avatar in the database
                $model->avatar = $fileName;
                $model->save();


                // Redirect back with a success message
                return redirect()->back()->with('success', 'Avatar uploaded successfully!');
            } else {
                // Redirect back with an error message
                return redirect()->back()->with('error', 'Failed to upload the image.');
            }
        }

        // Redirect back with an error message for invalid format
        return redirect()->back()->with('error', 'Invalid image format.');
    }



    public function cardPrint(Request $request, $staffId)
    {
		$pdf = new TCPDF('L', 'mm', array(54,85)); 
        // Set custom font directory
        // $pdf->setFontSubsetting(true);
        // $pdf->setFontDir(storage_path('fonts/')); // Set path to your custom fonts
		// ---------------------------------------------------------
		//Basic setup
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, 0);
		// set font
		$pdf->SetFont('kozminproregular', '', 12);
        
        if($request->has('model') && $request->model=='staff'){
            $staff=Staff::find($request->id);
            $this->printStaffCard($pdf,(object)$staff);	
        }
        if($request->has('model') && $request->model=='relative'){
            $relative=StaffRelative::find($request->id);
            $this->printRelativeCard($pdf,(object)$relative);	
        }
		// foreach($cards as $card){
		// 	$this->print_card($pdf,(object)$card);	
		// }
		
		//Close and output PDF document
		$pdf->Output('example.pdf', 'I');

    }

    private function printStaffCard($pdf, $card){
		$pdf->addPage();
		$pdf->SetXY(54,3);
		$pdf->SetFont('genjyuugothicb', 'B', 8);
		$pdf->MultiCell(28,16,'衛生護理證受益人',1,'C');
		$pdf->SetXY(55,3);
		$pdf->SetFont('antonio', 'B', 7);
		$pdf->MultiCell(27,12,chr(10).'Beneficiário do Acesso'.chr(10).'a Cuidados de Saúde'.chr(10),0,'C');

		$pdf->SetFont('genjyuugothicb', 'B', 7);
		$pdf->text(54,12, '編號/n');
		$pdf->SetFont('dejavusansb', 'B', 7);
		$pdf->text(61,12, '°:'.$card->medical_num);
		$pdf->SetFont('genjyuugothicb', 'B', 7);
		$pdf->text(55,15.3, '方式/Modalidade:');
		$pdf->SetFont('dejavusansb', 'B', 8);
		$pdf->text(77,15, $card->medical_type);

		$pdf->SetFont('dejavusansb', '', 10);
		$pdf->text(7,34, $card->staff_num);
		$pdf->text(7,45, $card->issue_date);

		
		// if(config('app.APP_ENV','local')=='production'){
		// 	$image_file='http://172.25.5.26/wms/images/staffs/'.$card->avata;
		// }else{
		// 	$image_file=url('/images/staffs/'.$card->avata);
		// }
        $image_file=public_path('images/staffs/'.$card->avatar);        
		$pdf->StartTransform();
		$pdf->image($image_file, 62.5, 30.6, 18, 0, '', '', '', true, 300);
		$pdf->StopTransform();

   		if(empty($card->name_zh)){
   			$pdf->SetFont('kozgopromedium', 'B', 10);
			$printName=$card->name_pt;
			$pdf->setXY(7,25);
			$pdf->MultiCell(74, 10, $printName, 0, 'L');
	    }else{
	   		$pdf->SetFont('DroidSansFallback', 'B', 10);
			$pdf->text(7,25, $card->name_zh);
			
			$pdf->SetFont('kozgopromedium', 'B', 10);
			$cnt=strlen(trim($card->name_zh));
			$pdf->setXY(($cnt+9),25);
			$pdf->MultiCell((74-$cnt), 10, '/ '.$card->name_pt, 0, 'L');
   		}
		
        $pdf->SetFont('helvetica', '', 10);
		$style = array(
		    'position' => '',
		    'align' => 'C',
		    'stretch' => false,
		    'fitwidth' => true,
		    'cellfitalign' => '',
		    'border' => false,
		    'hpadding' => 'auto',
		    'vpadding' => 'auto',
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255),
		    'text' => false,
		    'font' => 'helvetica',
		    'fontsize' => 10,
		    'stretchtext' => 4
		);

		$pdf->addPage();
		$pdf->text(48,31.5, $card->library_num);
		$pdf->write1DBarcode($card->library_num, 'C39', 15, 36, '', 12, 0.4, $style, 'N');
    }

    private function printRelativeCard($pdf, $card){
		// ---------------------------------------------------------
		//Basic setup
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, 0);
		// set font
		$pdf->SetFont('kozminproregular', '', 12);

			$pdf->AddPage();
			$pdf->SetFont('dejavusansb', '', 10);
			$pdf->text(7,35, $card->medical_num);
			
			$pdf->SetFont('dejavusansb', '', 10);
			$pdf->text(45,35, $card->medical_type);
			$pdf->text(7,45, $card->issue_date);

			$image_file=url('/images/staffs/'.$card->avatar);
			// if(config('app.APP_ENV','local')=='production'){
			// 	$image_file='http://172.25.5.26/wms/images/staffs/'.$card->avatar;
			// }else{
			// 	$image_file=public_path('images/staffs/'.$card->avatar);
			// }
            $image_file=public_path('images/staffs/'.$card->avatar);
            //dd($image_file);
			$pdf->StartTransform();
			$pdf->image($image_file, 61.5, 30.5, 18, 0, '', '', '', true, 300);
			$pdf->StopTransform();

       		if(empty($card->name_zh)){
       			$pdf->SetFont('kozgopromedium', 'B', 10);
				$printName=$card->name_pt;
				$pdf->setXY(7,25);
				$pdf->MultiCell(74, 10, $printName, 0, 'L');
			}else{
				if(preg_match('/\p{Han}+/u',$card->name_pt)){
					$pdf->SetFont('DroidSansFallback', 'B', 10);
					$pdf->text(7,25, $card->name_zh);
				}else{
					$pdf->SetFont('DroidSansFallback', 'B', 10);
					$pdf->text(7,25, $card->name_zh);
					
					$pdf->SetFont('kozgopromedium', 'B', 10);
					$cnt=strlen(trim($card->name_zh));
					$pdf->setXY(($cnt+9),25);
					$pdf->MultiCell((74-$cnt), 10, '/ '.$card->name_pt, 0, 'L');
	
				}
       		}

		//Close and output PDF document
		$pdf->Output('example.pdf', 'I');
         
    }

    /** 
    * 1. import staff, staff_sgroups, staff_uploads from wms exported sql 
    * 2. 
    */
    public function migration(Request $request){
        if($request->has('action')){
            switch ($request->action) {
                case 'fetch_remote_data':
                    $this->fetchRemoteStaffRecords();
                    break;
                case 'copy_avatars':
                    $this->migrateAvatars();
                    break;
                case 'staff':
                    $this->fetchStaff($request->staff_num);
                    break;
                case 'create_notices':
                    $this->createNotices();
                    break;
                default:
            }
        }
    }
    public function fetchStaff($staffNum){
        $data = Staff::get_remote_data('list', 'super', $staffNum);
        dd($data);
    }

    //copy StaffUpload avatar filename to Staff and Staff_relatives table accordingly
    public function migrateAvatars(){
        $uploads=StaffUpload::where('reference_table','staffs')->get();
        foreach($uploads as $upload){
            //dd(Staff::where('id',$upload->reference_id)->first());
            Staff::where('id',$upload->reference_id)->update(['avatar'=>$upload->file_name]);
        }

        $uploads=StaffUpload::where('reference_table','relatives')->get();
        foreach($uploads as $upload){
            //dd(Staff::where('id',$upload->reference_id)->first());
            StaffRelative::where('id_num',$upload->reference_id)->update(['avatar'=>$upload->file_name]);
        }

    }

    //get remote data sna save to Staff and Staff_relative table
    public function fetchRemoteStaffRecords()
    {
        //Staff::where('active',true)->update(['active' => false]);
        $data = Staff::get_remote_data('list-full', 'super');

        foreach ($data->allstaffinfo as $item) {
            // Assuming $item is now an associative array

            $staff = Staff::updateOrCreate(
                ['staff_num' => $item->staffinfo->staff_num, 'username'=>$item->staffinfo->netid],
                [
                    'username'=>$item->staffinfo->netid,
                    'name_zh'=>$item->staffinfo->name_zh, 
                    'name_pt'=>$item->staffinfo->name_pt, 
                    'email' => $item->staffinfo->netid.'@mpu.edu.mo', 
                    'staff_num'=>$item->staffinfo->staff_num,
                    'phone'=>$item->staffinfo->office_tel,
                    'employment'=>$item->staffinfo->fullpart,
                    'cat_group'=>$item->staffinfo->cat_group,
                    'lecturer'=>$item->staffinfo->lecturer,
                    'medical_num'=>$item->staffinfo->medical_num,
                    'medical_type'=>$item->staffinfo->medical_type,
                    'library_num'=>$item->staffinfo->library_num,
                    'register_date' => isset($item->staffinfo->register_date) && $item->staffinfo->register_date !== '' ? $item->staffinfo->register_date : null,
                    'dept'=>$item->staffinfo->dept_code,
                    'active'=>true,
                    //'register_date'=>$item->register_date
                ]
            );

            if(isset($item->familylist->family)){
                foreach($item->familylist?->family as $item){
                    $staff->relatives()->updateOrCreate(
                        ['staff_num'=>$item->staff_num, 'id_num'=>$item->id_num],
                        [
                            'has_allowance'=>$item->has_allowance,
                            'has_medical'=>$item->has_medical,
                            'mecical_num'=>$item->medical_num,
                            //'staff_num'=>$item->staff_num,
                            'name_zh'=>$item->name_zh,
                            'name_pt'=>$item->name_pt,
                            'relationship'=>$item->relationship,
                            'allowaance_type'=>$item->allowance_type,
                            'dob'=>date('Y-m-d', strtotime($item->dob)),
                            //'dob'=>$dobDateTime->format('Y-m-d'),
                            //'id_num'=>$item->id_num,
                            'medical_type'=>$item->medical_type,
                        ]
                    );
                }
            }
        }
        //dd(Staff::all());
        return count($data->allstaffinfo);
    }


    public function createNotices(){
        $relatives = StaffRelative::with(['staff:id,email'])
            ->where('relationship', '親生子女')
            ->where('dob', '>=', Carbon::now()->subYears(18)) // Filter DOB for 18 years old or younger
            ->get(); // Optionally, specify other columns
        $now = Carbon::now();
        foreach ($relatives as $item) {
            // Create a Carbon instance for the dob
            $dob = Carbon::parse($item->dob);

            // Calculate the age
            $age = $dob->age; // Carbon provides the age property directly
            // Check if the age is under 18
            if ($dob->year >= ($now->year-18)) {
               //dd($age,$dob->year, $now->year, ($now->year-18));
                // Calculate the date when they turn 18
                $turningDate = $dob->addYears(18);
                //dd($turningDate, $turningDate->format('Y-m-d'), $item);
                // Create a new StaffNotice record
                $item->notices()->firstOrCreate([
                    'staff_relative_id' => $item->id,
                    'date' => $turningDate->format('Y-m-d'), 
                ],[
                    'email'=>$item->staff->email,
                    'age'=>'18',
                    'date' => $turningDate->format('Y-m-d'), // Format to 'Y-m-d'
                    'status'=>'N'
                ]);
                // Calculate the date when they turn 22
                $turningDate = $dob->addYears(4);
                // Create a new StaffNotice record
                $item->notices()->firstOrCreate([
                    'staff_relative_id' => $item->id,
                    'date' => $turningDate->format('Y-m-d'),
                ],[
                    'email'=>$item->staff->email,
                    'age'=>'22',
                    'date' => $turningDate->format('Y-m-d'), // Format to 'Y-m-d'
                    'status'=>'N'
                ]);
                // Calculate the date when they turn 24
                $turningDate = $dob->addYears(2);
                // Create a new StaffNotice record
                $item->notices()->firstOrCreate([
                    'staff_relative_id' => $item->id,
                    'date' => $turningDate->format('Y-m-d'),
                ],[
                    'email'=>$item->staff->email,
                    'age'=>'24',
                    'date' => $turningDate->format('Y-m-d'), // Format to 'Y-m-d'
                    'status'=>'N'
                ]);

            
            }
        }
    }



}
