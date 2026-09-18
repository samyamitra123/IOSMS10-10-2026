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
	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = $_GET['mo'];
$ye = $_GET['ye'];
$so = $crypto->decode($_GET['so'], 4);
$bill = substr($_GET['bill'],0,10);
$yemo = $ye.$mo;
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
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
		$ddo_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$schcd."'");
		//$row_ddo_sql = pg_query($ddo_sql);
		//$rs_ddo_sql=pg_fetch_array($row_ddo_sql);
		$ddo_code=$ddo_sql[0]['ddo_code'];
		$ddo_code_array=explode("-",$ddo_code);
		$ddo_code_name=$ddo_code_array[0].$ddo_code_array[1].$ddo_code_array[2];
		$ddo_code_slash=$ddo_code_array[0]."/".$ddo_code_array[1]."/".$ddo_code_array[2];
		$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
		
		//echo $ddo_code_name;exit;
	
	 	$bill_det=$db->fetch_table("select block_bill_pk,bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where block_code='".$schcd."' and bill_no='".$bill."' and salary_monthyear= '".$yemo."' AND requisition_type='".$requisition_type."'");
		
		
	
	
		$det_head=$db->fetch_table("select *from prd_head_details where block_code='".$schcd."'");
		
		$no_of_row=count($det_head);   
		
$teacher_sql="select  distinct(emp.emp_id_pk),
				  emp.empcd, 
				  emp.emp_first_name,
				  emp.emp_second_name,
				  emp.emp_last_name, 
				  emp.gp_id_fk, 
				  emp.emp_status, 
				  emp.emp_branch_code, 
				  emp.emp_system_code,  
				  arr.bankname, 
				  emp.emp_micr_no, 
				  emp.emp_pan_no, 
				  emp.emp_desig, 
				  emp.emp_acc_no, 
				  sum(arr.basic),
				  arr.bank_ifsc,
				  sum(arr.net) as total_net,
				  emp.emp_group,
				  emp.emp_mail_id,
				  emp.emp_aadhar_no,
				  emp.emp_mobile_no,
				  emp.emp_pre_dist,
				  emp.emp_per_dist,
				  arr.delete_status
				FROM 
				   prd_employee_master emp
				  left join prd_employee_arrear arr 
				  ON arr.emp_id_fk=emp.emp_id_pk
				where
					trim(arr.salary_monthyear)='".$yemo."'
					AND arr.delete_status='1' 
					AND arr.status_flag='3'
					AND emp.emp_status in('1','9')
					AND arr.block_code='".$_SESSION['user_info']['stake_user']."'
					AND arr.bill_id_fk='".$bill_det[0]['block_bill_pk']."'
					GROUP BY emp.emp_id_pk,emp.empcd,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.gp_id_fk,emp.emp_status, emp.emp_branch_code,emp.emp_system_code, arr.bankname,emp.emp_micr_no,emp.emp_pan_no,emp.emp_desig, emp.emp_acc_no, arr.bank_ifsc, emp.emp_group, emp.emp_mail_id, emp.emp_aadhar_no, emp.emp_mobile_no,emp.emp_pre_dist,emp.emp_per_dist,arr.delete_status
					ORDER BY emp.gp_id_fk ASC
					";
			
		
		
$sql_teacher=$db->fetch_table($teacher_sql);

$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");




$xml =  new DOMDocument("1.0","UTF-8");
			$container = $xml->createElement('Employee');
		$container = $xml->appendChild($container);
		//$container1 = $xml->createElement('BankDetail');
		//$container1 = $xml->appendChild($container1);
		$row = $xml->createElement('BankDetail');
		$row = $container->appendChild($row);
		$i=1;
		foreach ($sql_teacher as $key){
			
			
			if($key['total_net'] > 0){
				
				
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
				
				
				$sln = $xml->createElement('SL.',$i);
				$sln = $row->appendChild($sln);
				
				$name = $xml->createElement('EmployeeName',$tch_name);
				$name = $row->appendChild($name);
				
				
				
				$ifsccd = $xml->createElement('IFSC',$key['bank_ifsc']);
				$ifsccd = $row->appendChild($ifsccd);
				
				$accno = $xml->createElement('BankAcct.No.',$key['emp_acc_no']);
				$accno = $row->appendChild($accno);
				
				$amount= $xml->createElement('Amount',$key['total_net']);
				$amount = $row->appendChild($amount);
				
				$type = $xml->createElement('BenfFlag',E);
				$type = $row->appendChild($type);
				
				
				
				
				
				
				
				$i++;
			}
			
		}
		
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
		
		
		if($xml->formatOutput = TRUE){
			
			$filename = substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";
			$name = strftime($filename);
			header('Content-Disposition: attachment;filename=' . $name);
			header('Content-Type: text/xml');
			echo $xml->saveXML();
		}
?>
