<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IdValidatorController extends Controller
{
    	public function test(){
		return 'ok';
	}

	public function index(Request $request)
	{
        //birm, stdnum, china, ipm_student
		if (isset($request->birm)){
			if ($this->_birm_version_1_0($request->birm)){
				return  true;
			}else{
				return  false;
			}
		}
		if (isset($request->stdnum)){
			if ($this->_student_id_version_1_0($request->stdnum)){
				return  true;
			}else{
				return  false;
			}
		}

		if (isset($request->china)){
			if ($this->_china_id($request->china)){
				return  true;
			}else{
				return  false;
			}
		}

		if ($request->ipm_student){
			if ($this->_ipm_student_id_version_1_1($request->ipm_student)){
				return  true;
			}else{
				return  false;
			}
		}
        dd($request->all(), $request->ipm_student, $request->has('ipm_student'));
        return false;

	}
	function _china_id($id){
		if(strlen($id)!=18){
			return false;
		}
		$card=strtoupper($id);//身份证号码
		$map=array('1','0','X','9','8','7','6','5', '4','3','2');
		$sum = 0;
		for($i = 17; $i > 0; $i--){
		    $s=pow(2, $i) % 11;
		    $sum += $s * $card[17-$i];
		}
		return substr($card,-1)==$map[$sum % 11];//这里显示最后一位校验码
	}

	function _fulltohalf($no){
		$full=array("０","１","２","３","４","５","６","７","８","９","／","（","）","Ｘ","ｘ","Ｐ","ｐ","Ｃ","ｃ","－");
		$half=array("0","1","2","3","4","5","6","7","8","9","/","(",")","X","x","P","p","C","c","-");
		return str_replace($full,$half,$no);
	}

	function _student_id_version_1_0($no)
	{
		$tmpno= $this->_fulltohalf($no);
		if(!preg_match("/[Cc][-][0-9]{2}[-][0-9]{4}[-][0-9]/",$tmpno))
			return false;

		$onlynum=preg_replace("/[^0-9]/","",$tmpno);
		$strsplit=str_split($onlynum);
		$num=0;
		for($i=1;$i<count($strsplit);$i++)
			$num += $strsplit[$i-1]*$i;
		$num = 11 - $num % 11;
		if($num>9)
			$num=0;


		if(strtoupper($strsplit[6]) == $num)
			return true;
		return false;
	}
	function _birm_version_1_0($no)
	{
		$tmpno= $this->_fulltohalf($no);
		//if(!preg_match("/([1|5|7][0-9]{6}[(][0-9][)])|([1|5|7][\/][0-9]{6}[\/][0-9])/",$tmpno))
		if(!preg_match("/([1|5|7][0-9]{6}[(][0-9][)])|([1|5|7][0-9]{6}[0-9])/",$tmpno))
			return FALSE;

		$onlynum=preg_replace("/[^0-9]/","",$tmpno);

		$strsplit=str_split($onlynum);

		$num1=$strsplit[0].$strsplit[2].$strsplit[4].$strsplit[6];
		$num2=$strsplit[1].$strsplit[3].$strsplit[5];

		$num1m2=$num1*2;

		$num1split=str_split($num1m2);
		$num2split=str_split($num2);

		$dsum1=0;
		$dsum2=0;
		foreach ($num1split as $key => $value)
			$dsum1 +=$value; 
		foreach ($num2split as $key => $value) 
			$dsum2 +=$value;
		
		$dsum = $dsum1+$dsum2;
		$cd = (10 - ($dsum%10))%10;
		if($cd == $strsplit[7])
			return TRUE;
		return FALSE;
	}
	function _china_id_version_1_0($no)
	{
  		$table = array("1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2");
		$wi = array( 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		$tmpno= $this->_fulltohalf($no);
		if(!preg_match("/[0-9]{17}[0-9Xx]/",$tmpno))
			return false;

		$strsplit=str_split($tmpno);
		$num=0;
		for($i=0;$i<count($strsplit)-1;$i++){
			$num += $strsplit[$i]*$wi[$i];
		}
		$num = $num % 11;
		if(strtoupper($strsplit[17]) == $table[$num])
			return true;
		return false;
	}

	function _ipm_student_id_version_1_0($no)
	{
		$table = array("1","3","7","1","3","7");
		$tmpno= $this->_fulltohalf($no);
		if(!preg_match("/[Pp][-][0-9]{2}[-][0-9]{4}[-][0-9]/",$tmpno))
			return false;

		$onlynum=preg_replace("/[^0-9]/","",$tmpno);
		$strsplit=str_split($onlynum);
		$num=0;
		for($i=0;$i<count($strsplit)-1;$i++)
			$num += $strsplit[$i]*$table[$i];
		$num = 13 - ($num % 13);

		if($num>9)
			$num=$num-10;

		if(strtoupper($strsplit[6]) == $num)
			return true;
		return false;	
	}	

	function _ipm_student_id_version_1_1($no)
	{

		if(strlen($no)!=8) return false;
		$table = array("1","3","7","1","3","7");
		$tmpno= $this->_fulltohalf($no);
		// if(!preg_match("/[Pp]{7}[0-9]/",$tmpno))
		// 	return false;
		$onlynum=preg_replace("/[^0-9]/","",$tmpno);
		$strsplit=str_split($onlynum);
		$num=0;
		for($i=0; $i<count($strsplit)-1; $i++){
            // echo $i.'-'.$num.'::'.$strsplit[$i].'-'.$table[$i].'<br>';
			$num += $strsplit[$i]*$table[$i];
        }

		$num = 13 - ($num % 13);
		if($num>9)
			$num=$num-10;
        
		if(strtoupper($strsplit[6]) == $num)
			return true;
		return false;	
	}	

}
