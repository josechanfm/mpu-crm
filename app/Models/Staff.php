<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table='staffs';
    protected $fillable=[
        'first_name',
        'last_name',
        'other_name',
    ];
    protected $hidden=['password'];
    
    public function relatives()
    {
        return $this->hasMany(StaffRelative::class);
    }

    public function uploads(){
        return $this->hasMany(StaffUpload::class);
    }

    public static function get_remote_data($role='super',$staffNum=null){
		$ch = curl_init();
		$url = "https://wapps2-local.ipm.edu.mo/banner/staffInfoService.ashx";
		//echo $url;
		
		$dataArray=array(
			'service'=>'family',
			'token'=>self::getToken($role),
			//if super user, use 'super' as getToken netid, other use self netid
			//'dept'=> 'IT', //only 'super' user
		);
		if(!is_null($staffNum)){
			$dataArray['id']=$staffNum;
		}

		$data = http_build_query($dataArray);

		$options = array(
			CURLOPT_URL            => $url."?".$data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST		   =>false,
			CURLOPT_TIMEOUT		   =>30
		);

		curl_setopt_array( $ch, $options );


		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ( $httpCode != 200 ){
		    echo "Return code is {$httpCode} <br/>"
		        .curl_error($ch);
		}
		 //echo "<p>".htmlspecialchars($response)."</p>";
		curl_close($ch);
		//echo "<p>".htmlspecialchars($response)."</p>";
		dd($response);
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


}
