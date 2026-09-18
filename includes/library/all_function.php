<?php
function generalCode($length=5)
{
	$data = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for($i=0; $i<$length; $i++)
        $r .= substr($data,rand(0,61),1);
	return $r;
}





/******************* Function for fetching the description agains the give code **************************/



function shDiceCodeFormat($stuid)
{	
	$compete_length = (int) strlen($stuid);
	
	if($compete_length == 20)	
	{
		$showid = substr($stuid,0,11)." ".substr($stuid,11,4)." ".substr($stuid,15);
	}
	else
		$showid = $stuid;
		
	return $showid;
}

function dom($tagname,$tag)
{
	global $xml;
	$description=htmlentities(trim($xml->Table[$tag]->$tagname),ENT_QUOTES);
	return $description;
}

function dateformat($dateval)
{	
	$date=explode('-',$dateval);
	$dob= $date['2'].$date['1'].$date['0'];
	return $dob;
}
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

function find_des($e_code) {
	global $conn; 
	$code_array=explode(",",$e_code); 
	foreach($code_array as $t => $k) { 
		$des_sql="select edescription from ehrms_enrollment_code_master where ecode = '".$code_array[$t]."'"; 
		$show_des = pg_query($conn,$des_sql); 
		$des_row=pg_fetch_array($show_des); 
		if(count($code_array)>1) { 
			$e_des.=$des_row['edescription'].","; 
		} else { 
			$e_des=$des_row['edescription']!=''?$des_row['edescription']:'NOT AVAILABLE'; 
		} 
	} 
	return $e_des; 
}

function delayTimeString($timeval)
{		
	$ret_string = '';
	$hour_dl = 0;
	$min_dl = 0;
	$sec_dl = 0;
	
	if($timeval >= 3600)
	{
		$hour_dl = intval($timeval/3600);  // hour taken
		$timeval = intval($timeval%3600);  // for remaining minute		
		$ret_string = $hour_dl.' hour(s) ';
	}	
	if($timeval<3600 && $timeval >= 60)
	{
		$min_dl = intval($timeval/60);  // minute taken
		$timeval = intval($timeval%60);  // for remaining second		
		$ret_string .= $min_dl.' minute(s) ';
	}	
	if($timeval<60)
	{		
		$sec_dl = (int)$timeval;  // second taken
		$ret_string .= $sec_dl.' seconds';
	}	
	return $ret_string;
}

function getMyTimeDiff($t1,$t2)
{
$a1 = explode(":",$t1);
$a2 = explode(":",$t2);
$time1 = (($a1[0]*60*60)+($a1[1]*60)+($a1[2]));
$time2 = (($a2[0]*60*60)+($a2[1]*60)+($a2[2]));
$diff = abs($time1-$time2);
$hours = floor($diff/(60*60));
$mins = floor(($diff-($hours*60*60))/(60));
$secs = floor(($diff-(($hours*60*60)+($mins*60))));
//$result = $hours.":".$mins.":".$secs;
$result = ($hours*3600)+($mins*60)+$secs;
return $result;
}




/********** Function for sorting the associative array ***************************************/
function sortByOneKey(array $array, $key, $asc = true) 
{
    $result = array(); $values = array();
    foreach ($array as $id => $value)
        $values[$id] = isset($value[$key]) ? $value[$key] : '';              
	$asc==true?asort($values):arsort($values);		       
    foreach ($values as $key => $value)
        $result[$key] = $array[$key];       
    
	return $result;
}
/********** Function for sorting the associative array ***************************************/
function getFinancialYear()
{
	$date_cd=date('d-m-Y');
	if(substr($date_cd,3,2)>=1 && substr($date_cd,3,2)<=3)
	{
		$financial_year_start_month=(substr($date_cd,6,4)-1);
		$financial_year_end_month=substr($date_cd,6,4);		
	}
	else
	{
		$financial_year_start_month=substr($date_cd,6,4);
		$financial_year_end_month=(substr($date_cd,6,4)+1);			
	}
	$temp_start_year=$financial_year_start_month;
	$temp_end_year=$financial_year_end_month;
	return $temp_start_year."#".$temp_end_year;
}

function find_salsource($code)
{
	global $conn;
	$db = new database();
	 $arr11 = $db->fetch_table("select edescription from ehrms_enrollment_code_master where ecode = '".$code."'");
	 foreach($arr11 as $key)
	$sal_source=$key['edescription'];
	return $sal_source;
}
function showdesignation($code)
{
	$design_code=(strlen($code) == '1')?'110'.$code : '11'.$code;
	$db = new database();
	 $arr1 = $db->fetch_table("select description from ehrms_dise_code_master where code='".$design_code."'");
    foreach($arr1 as $key)
	$design=$key['description'];
	return($design);	
}	


	function districtCode($val)
	{

		$db=new database();
		$Query = "SELECT district_name FROM prd_location_master_district
		 WHERE district_code='".$val."'";
		$arr =$db->fetch_table($Query);
		return $arr[0]['district_name'];																															
	}
	function blockCode($val)
	{

		$db=new database();
		$Query = "SELECT block_name FROM prd_location_master_block
		 WHERE block_code='".$val."'";
		$arr =$db->fetch_table($Query);
		return $arr[0]['block_name'];																															
	}

?>