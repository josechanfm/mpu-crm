<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Mddels\Scheduler;

class Staff extends Model
{
    use HasFactory;
    protected $table='staffs';
    protected $fillable=[
		'username',
		'email',
        'first_name',
        'last_name',
        'other_name',
		'phone',
		'name_zh',
		'name_pt',
		'staff_num',
		'medical_type',
		'medical_num',
		'library_num',
		'issue_date',
		'employment',
		'lecturer',
		'dept',
		'register_date',
		'cat_group',
		'avatar',
		'active'
    ];

					
    protected $hidden=['password'];
    
    public function relatives()
    {
        return $this->hasMany(StaffRelative::class);
    }

    public function uploads(){
        return $this->hasMany(StaffUpload::class);
    }

    public static function get_remote_data($service='list', $role='super', $staffNum=null){
	    set_time_limit(0); 
		$ch = curl_init();
		$url = "https://wapps2-local.ipm.edu.mo/banner/staffInfoService.ashx";
		$dataArray=array(
			'service'=>$service,
			'token'=>self::getToken($role),
		);
		if(!is_null($staffNum)){
			$dataArray['id']=$staffNum;
		}
		$data = http_build_query($dataArray);
        $options = array(
			CURLOPT_URL            => $url."?".$data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST		   =>false,
			CURLOPT_TIMEOUT		   =>30,
            CURLOPT_SSL_VERIFYPEER => false,
		);
		curl_setopt_array( $ch, $options );
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ( $httpCode != 200 ){
			echo "Return code is {$httpCode} <br/>"
				.curl_error($ch);
		}
		curl_close($ch);
		return json_decode($response);
	}

    public static function  getToken($netid){
        $key= "YxIs2d62wScSqpL0";
        date_default_timezone_set("UTC");
        $datetime = time()*1000;
        $plaintext = $datetime.",".$netid;
        $data =   openssl_encrypt ( $plaintext , 'aes-128-ecb' ,  $key , OPENSSL_RAW_DATA ); 
        return base64_encode ( $data );
    }

    public static function fetchRemoteStaffRecords($action='Manual')
    {
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
                            'name_zh'=>$item->name_zh,
                            'name_pt'=>$item->name_pt,
                            'relationship'=>$item->relationship,
                            'allowaance_type'=>$item->allowance_type,
                            'dob'=>date('Y-m-d', strtotime($item->dob)),
                            'medical_type'=>$item->medical_type,
                        ]
                    );
                }
            }
        }
        Scheduler::create([
            'action' => $action,
            'result' => 'SUCCESS',
            'remark' => count($data->allstaffinfo)
        ]);

        return count($data->allstaffinfo);
    }

    public static function createNotices($action='Manual'){
        $now = Carbon::now();
        $relatives = StaffRelative::with(['staff:id,email'])
            ->where('relationship', '親生子女')
            ->where('dob', '>=', Carbon::now()->subYears(18))
            ->get();
        foreach ($relatives as $item) {
            // Create a Carbon instance for the dob
            $dob = Carbon::parse($item->dob);
            // Calculate the age
            $age = $dob->age; // Carbon provides the age property directly
            // Check if the age is under 18
            if ($dob->year >= ($now->year-18)) {
                // Calculate the date when they turn 18
                $turningDate = $dob->addYears(18);
                // Create a new StaffNotice record
                $item->notices()->firstOrCreate([
                    'staff_relative_id' => $item->id,
                    'date' => $turningDate->format('Y-m-d'), 
                ],[
                    'email'=>$item->staff->email,
                    'age'=>'18',
                    'date' => $turningDate->format('Y-m-d'), 
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
                    'date' => $turningDate->format('Y-m-d'),
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
                    'date' => $turningDate->format('Y-m-d'),
                    'status'=>'N'
                ]);
            }
        }
        Scheduler::create([
            'action' => $action,
            'result' => 'SUCCESS',
            'remark' => count($relatives)
        ]);

    }


}
