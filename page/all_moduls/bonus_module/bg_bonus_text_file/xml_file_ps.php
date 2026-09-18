<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

/*require '../../../page_visite.php';
*/

$crypto = new cryptography();
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
    || isset($_SESSION['blocked_privilege']['0801'])
	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$crypto = new cryptography();

$current_year = date("Y");
$next_year=$current_year+1;


//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = $_GET['mo'];
$ye = $_GET['ye'];
$so = $crypto->decode($_GET['so'], 4);
$bill = substr($_GET['bill'],0,50);
$yemo = $ye.$mo;
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='428'");
$requisition_type=$requisition[0]['code'];


function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	if($date==''){
	return '01/01/1900';
	}
	if($date=='0001-01-01'){
	return '01/01/1900';
	}
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='01/01/1900'?'':$dob;
}
	
$treasury_code=$db->fetch_table(" SELECT treasury_block_code FROM prd_dise_admin WHERE block_code='".$_SESSION['location']['block_code']."' ");
		
$psemp_ps_profile=$db->fetch_table("select ddo_code,treasury_code from psemp_ps_profile where ps_id_fk='".$_SESSION['location']['ps_id']."'");
		$ddo_code=$psemp_ps_profile[0]['ddo_code'];
		$ddo_code_array=explode("-",$ddo_code);
		$ddo_code_name=$ddo_code_array[0].$ddo_code_array[1].$ddo_code_array[2];
		$ddo_code_slash=$ddo_code_array[0]."/".$ddo_code_array[1]."/".$ddo_code_array[2];
		$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
		
		//echo $ddo_code_name;exit;
	
	 	$bill_det=$db->fetch_table("SELECT 
											bill.block_bill_pk,
											bill.bill_no,
											bill.bill_entry_time,
											bill.salary_monthyear
									FROM prd_block_bill_details bill
									WHERE bill.ps_id_fk='".$_SESSION['location']['ps_id']."' and bill.bill_no='".$bill."' and bill.salary_monthyear= '".$yemo."' AND bill.requisition_type='".$requisition_type."' AND bill.status='1'");
		
	
	
		$det_head=$db->fetch_table("select *from psemp_head_details");
		
		$no_of_row=count($det_head);   
	
		
 $teacher_sql="select  distinct(emp.emp_id_pk),
	  emp.empcd, 
	  emp.emp_first_name,
      emp.emp_second_name,
      emp.emp_last_name, 
	  emp.ps_id_fk, 
	  emp.emp_status, 
	  emp.emp_branch_code,  
	  emp.emp_bank_name, 
	  emp.emp_micr_no, 
	  emp.emp_pan_no, 
	  emp.emp_desig, 
	  emp.emp_acc_no, 
	  emp.emp_ifsc_no,
	  bonus.bonus_amount,
	  emp.emp_group,
	  emp.emp_mail_id,
	  emp.emp_aadhar_no,
	  emp.emp_mobile_no,
	  emp.emp_pre_dist,
	  emp.emp_per_dist,
	  bonus.delete_status
	FROM 
	   prd_employee_master emp
      left join prd_employee_bonus_details bonus 
      ON bonus.emp_id_fk=emp.emp_id_pk AND bonus.ps_id_fk=emp.ps_id_fk
	where
		bonus.delete_status='1' 
		AND bonus.bonus_status='5'
		AND emp.emp_status in('1','9')
		AND bonus.ps_id_fk='".$_SESSION['location']['ps_id']."'
		AND bonus.bill_id_fk='".$bill_det[0]['block_bill_pk']."'
		ORDER BY emp.emp_id_pk ASC
		";
			
		
		
$sql_teacher=$db->fetch_table($teacher_sql);

$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");


if($psemp_ps_profile[0]['treasury_code']!="")
{
	$treassury_code=substr($psemp_ps_profile[0]['treasury_code'],0,3);
}
else
{
	$treassury_code=$treasury_code[0]['treasury_block_code'];
}
//$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");


if($psemp_ps_profile[0]['treasury_code']!="")
{
	$treassury_code=substr($psemp_ps_profile[0]['treasury_code'],0,3);
}
else
{
	$treassury_code=$treasury_code[0]['treasury_block_code'];
}


$xml =  new DOMDocument("1.0","UTF-8");
		$container = $xml->createElement('bankdetails');
		$container = $xml->appendChild($container);
		/*$row = $xml->createElement('BankDetail');
		$row = $xml->appendChild($row);*/
		/*$row = $xml->createElement('bankdetails');
		$row = $container->appendChild($row);*/
		$i=1;
		foreach ($sql_teacher as $key){
			
			if (trim($key['emp_second_name']) == '') 
			{
				$last_name = preg_replace('/\s+/', ' ', trim($key['emp_last_name']));
				$tch_name = trim($key['emp_first_name']) . " " . trim($last_name);
			} 
			else 
			{
				$last_name = preg_replace('/\s+/', ' ', trim($key['emp_last_name']));
				$tch_name = trim($key['emp_first_name']) . " " . trim($key['emp_second_name']) . " " . trim($last_name);
			}
			
			if($key['bonus_amount'] > 0){
				$row = $xml->createElement('employee');
				$row->setAttribute("slno",$i);
				$row = $container->appendChild($row);
				
				$name = $xml->createElement('name',$tch_name);
				$name = $row->appendChild($name);
				
				$type = $xml->createElement('type',EP);
				$type = $row->appendChild($type);
				
				$ifsccd = $xml->createElement('ifscode',$key['emp_ifsc_no']);
				$ifsccd = $row->appendChild($ifsccd);
				
				$accno = $xml->createElement('accountno',$key['emp_acc_no']);
				$accno = $row->appendChild($accno);
				
				$amount= $xml->createElement('amount',$key['bonus_amount']);
				$amount = $row->appendChild($amount);
				
				
				
				
				/*$rank= $xml->createElement('Rank','');
				$rank = $row->appendChild($rank);*/
				
				$i++;
			}
			
		}
		
		//find_dist(substr($_SESSION['schcd'],0,4))
		//$xml->formatOutput = TRUE;
		$bill_array=explode("/",$bill_det[0]['bill_no']);
		foreach($bill_array as $a=>$i){
			$billno1=$billno1.$i;
		}
		
		$bill_array=explode("-",$billno1);
			foreach($bill_array as $a=>$i){
			$billno=$billno.$i;
		}
		$bill_date_array=explode("/",dateshow_slash($bill_det[0]['bill_entry_time']));
			foreach($bill_date_array as $a=>$i){
			$billdate=$billdate.$i;
		}
		
		//echo substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";exit;
		
		if($xml->formatOutput = TRUE){
			//$string_value = $xml->saveXML();
			//$xml->save('../readwrite/xml2/'.$_SESSION['schcd'].'_'.$_SESSION['schname'].'_beneficiary_salary.xml');
		//	MONTH(10)YEAR(2015)TR_TYPE(31)DDO_CODE(NPCEDS002)ANY_SERIAL_NO(132)CREATED_DATE(07102015)_Benf(1)
			
			$filename = substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4).$treassury_code.$ddo_code.$billno.$billdate."_Benf.xml";
			$name = strftime($filename);
			header('Content-Disposition: attachment;filename=' . $name);
			header('Content-Type: text/xml');
			echo $xml->saveXML();
		}
?>
