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
use Exception; // Import the Exception class



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
        $request->validate([
            'model' => 'required|in:staff,relative', // model must be either 'staff' or 'relative'
            'id' => 'required|integer'               // id must be an integer
        ]);

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
            $this->getIssueDate($staff, $request->issue_date);
            $this->printStaffCard($pdf,(object)$staff);	
        }
        if($request->has('model') && $request->model=='relative'){
            $relative=StaffRelative::find($request->id);
            $this->getIssueDate($relative, $request->issue_date);
            $this->printRelativeCard($pdf,(object)$relative);	
        }

		// foreach($cards as $card){
		// 	$this->print_card($pdf,(object)$card);	
		// }
		
		//Close and output PDF document
		$pdf->Output('example.pdf', 'I');
    }
    private function getIssueDate($model, $issueDate){
        try {
            // Try to parse the provided date
            $date = Carbon::parse($issueDate);
        } catch (Exception $e) {
            // If parsing fails, set $date to the current date in 'd-m-Y' format
            $date = Carbon::now(); // You can format it as needed later
        }

        $model->issue_date=$date->format('Y-m-d');
        $model->save();
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
                    //$this->fetchRemoteStaffRecords();
                    Staff::fetchRemoteStaffRecords();
                    break;
                case 'copy_avatars':
                    $this->migrateAvatars();
                    break;
                case 'create_notices':
                    //$this->createNotices();
                    Staff::createNotices();
                    break;
                case 'staff':
                    $data = Staff::get_remote_data('list', 'super', $request->staff_num);
                    dd($data);
                    break;
                default:
                    return inertia('Department/Personnel/StaffMigration');
            }
        }
        return redirect()->back();
        //return inertia('Department/Personnel/StaffMigration');
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


}
