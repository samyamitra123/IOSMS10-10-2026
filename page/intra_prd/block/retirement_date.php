<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../index.php");
}
	function isleap($yr){
		if ((intval($yr)%4) == 0){
			if (intval($yr)%100 == 0){
				if (intval($yr)%400 != 0){
					return false;
				}
				if (intval($yr)%400 == 0){
					return true;
				}
			}
			if (intval($yr)%100 != 0){
				return true;
			}
		}
		if ((intval($yr)%4) != 0){
			return false;
		} 
	}
	/*$employee_dob = $_POST['employee_dob'];
	$timezone = new DateTimeZone('Asia/Calcutta');
	$date = new DateTime($employee_dob,$timezone);
	$date->modify("+60 year");
	echo $year = $date->format("t-m-Y");*/
	$d1 = $_POST['employee_dob'];	
	$retireYear=intval(substr($d1,6))+60;
	$day=intval(substr($d1,0,2));
	$retireday=0;
	$retireMonth=0;
	$month=intval(substr($d1,3,2));
	if($day==1 || $day==01)  {
	  if($month==01 || $month==03 || $month==05 || $month==07 || $month==08 || $month==10 || $month==12 || $month==1 || $month==3 || $month==5 || $month==7 || $month==8){
		  if($month == 03){
				if(isleap($retireYear)){
					$retireday = 29;
					$retireMonth=$month-1;
				}
				else{
					$retireday = 28;
					$retireMonth=$month-1;
				}
			}
			else{
				if($month==01 || $month==1){
					$retireday=31;
					$retireMonth=12;
					$retireYear=$retireYear-1;	
				}elseif($month==08 || $month==8){
					$retireday=31;
					$retireMonth=$month-1;
				}else{ $retireday = 30;
				 $retireMonth=$month-1;
				}
			}
	  }
	  else{
		  $retireday = 31;
		  $retireMonth=$month-1;
	  }
	}
	else{
	 if($month==01 || $month==03 || $month==05 || $month==07 || $month==08 || $month==10 || $month==12 || $month==1 || $month==3 || $month==5 || $month==7 || $month==8){
		 $retireday = 31;
	 }
	 else{
	  if($month == 02 || $month == 2){
		if(isleap($retireYear)){
			$retireday = 29;
		}else{
			$retireday = 28;
			}
		}else{
			$retireday = 30;
		}
	 }
	 $retireMonth=$month;
	}
	if($retireMonth<=9){
	  $retireMonth='0'.$retireMonth;
	}
	if($retireday<=9){
	  $retireday='0'.$retireday;
	}
	echo $retireday.'-'.$retireMonth.'-'.$retireYear;
?>