<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Contet-type : text/xml");

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
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = $crypto->decode($_GET['mo'], 4);
$ye = $crypto->decode($_GET['ye'], 4);
$so = $crypto->decode($_GET['so'], 4);
$bill = substr($_GET['bill'],0,10);
$yemo = $ye.$mo;
$db = new database();

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
	echo "select bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where block_code='".$schcd."' and bill_no='".$bill."' and salary_monthyear= '".$yemo."' AND status='1'"; die;
	 	$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where block_code='".$schcd."' and bill_no='".$bill."' and salary_monthyear= '".$yemo."' AND status='1'");
		
		//$row_data=pg_query($sql_query);
		//$bill_det=pg_fetch_array($row_data);
		
		//$det_schtype=$db->fetch_table("select * from ehrms_dise_schtype_code where schcode='".$bill_det[0]['salary_source']."' and type_status='1'");
		//$row_data_schtype=pg_query($sql_query_schtype);
		//$det_schtype=pg_fetch_array($row_data_schtype);
		//$no_schtype_code=count($det_schtype);
		
		/*$schtype_code=$det_schtype[0]['depart_code']."-".$det_schtype[0]['demand_no']."-".$det_schtype[0]['maj_head']."-".$det_schtype[0]['s_maj_head']."-".$det_schtype[0]['minor_head']."-".$det_schtype[0]['plan_head']."-".$det_schtype[0]['schm_head']."-".$det_schtype[0]['vot_ch']."-".$det_schtype[0]['dtl_head'];*/
	
	
		$det_head=$db->fetch_table("select *from prd_head_details where block_code='".$schcd."'");
		//echo "select *from prd_head_details where block_code='".$schcd."'";exit;
		
		//$row_data_head=pg_query($sql_query_head);
		//$det_head=pg_fetch_array($row_data_head);
		$no_of_row=count($det_head);   
		//echo $no_of_row;     
		
		/*if($no_of_row>0 || $no_schtype_code>0){        
		$itax_head=$det_head[0]['it_maj_head']."-".$det_head[0]['it_s_maj_head']."-".$det_head[0]['it_minor_head']."-".$det_head[0]['it_schm_head']."-".$det_head[0]['it_dtl_head'];
		$ptax_head=$det_head[0]['pt_maj_head']."-".$det_head[0]['pt_s_maj_head']."-".$det_head[0]['pt_minor_head']."-".$det_head[0]['pt_schm_head']."-".$det_head[0]['pt_dtl_head'];
		$gpf_head=$det_head[0]['gpf_maj_head']."-".$det_head[0]['gpf_s_maj_head']."-".$det_head[0]['gpf_minor_head']."-".$det_head[0]['gpf_schm_head']."-".$det_head[0]['gpf_dtl_head'];
		}
		else{
		echo "Problem to get head details";
		exit(1);
		}*/


//error_reporting(1);

/*$teacher_sql="select  emp.empcd, 
	  emp.emp_first_name,
      emp.emp_second_name,
      emp.emp_last_name, 
	  emp.gp_id_fk, 
	  emp.emp_status, 
	  emp.emp_branch_code, 
	  emp.emp_system_code,  
	  save.empcd, 
	  save.bankname, 
	  emp.emp_micr_no, 
	  emp.emp_pan_no, 
	  emp.emp_desig, 
	  emp.emp_acc_no, 
	  save.basic,
	  save.bank_ifsc,
	  save.net,
	  emp.emp_group,
	  emp.emp_mail_id,
	  emp.emp_aadhar_no,
	  emp.emp_mobile_no,
	  emp.emp_pre_dist,
	  emp.emp_per_dist,
	  save.delete_status,
	  gp.gp_name as gpname  
	FROM prd_location_master_state as state
	INNER JOIN prd_location_master_district as district
		ON state.state_id_pk= district.state_id_fk
	INNER JOIN prd_location_master_block as block
		ON district.district_id_pk= block.district_id_fk
	INNER JOIN prd_location_master_gp as gp
		ON block.block_id_pk =  gp.block_id_fk
	INNER JOIN prd_employee_master as emp
		ON emp.gp_id_fk =gp.gp_id_pk
	INNER JOIN prd_monthly_salary_archive_final as save	
		ON save.gp_id_fk=CAST(gp.gp_id_pk AS text)
		
		AND CAST(emp.gp_id_fk AS text) = save.gp_id_fk 
		AND CAST(emp.empcd AS text)=save.empcd
		and trim(save.salary_monthyear)='".$yemo."'
		AND save.delete_status='1' 
		AND save.status_flag='3'
		AND emp.emp_status =1 
		AND save.block_code='".$schcd."'
		ORDER BY emp.gp_id_fk ASC
		";
		*/
		
$teacher_sql="select  distinct(emp.emp_id_pk),
	  emp.empcd, 
	  emp.emp_first_name,
      emp.emp_second_name,
      emp.emp_last_name, 
	  emp.gp_id_fk, 
	  emp.emp_status, 
	  emp.emp_branch_code, 
	  emp.emp_system_code,  
	  save.empcd, 
	  save.bankname, 
	  emp.emp_micr_no, 
	  emp.emp_pan_no, 
	  emp.emp_desig, 
	  emp.emp_acc_no, 
	  save.basic,
	  save.bank_ifsc,
	  save.net,
	  emp.emp_group,
	  emp.emp_mail_id,
	  emp.emp_aadhar_no,
	  emp.emp_mobile_no,
	  emp.emp_pre_dist,
	  emp.emp_per_dist,
	  save.delete_status
	FROM 
	   prd_employee_master emp
      left join prd_employee_salary_save save 
      ON save.emp_id_fk=emp.emp_id_pk
	where
		trim(save.salary_monthyear)='".$yemo."'
		AND save.delete_status='1' 
		AND save.status_flag='3'
		AND emp.emp_status =1 
		AND save.block_code='".$schcd."'
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
			
			
			if($key['net'] > 0){
				$sln = $xml->createElement('SL.',$i);
				$sln = $row->appendChild($sln);
				
				$name = $xml->createElement('EmployeeName',$key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']);
				$name = $row->appendChild($name);
				
				/*$desig = $xml->createElement('Designation',$fetch_teacher['category']);
				$desig = $row->appendChild($desig);*/
				/*if(strlen($fetch_teacher['category'])==1){
					$desig = $xml->createElement('Designation',fun_common('110'.$fetch_teacher['category']));
					$desig = $row->appendChild($desig);
				}else if(strlen($fetch_teacher['category'])==2){
					$desig = $xml->createElement('Designation',fun_common('11'.$fetch_teacher['category']));
					$desig = $row->appendChild($desig);
				}*/
				
				/*$micrno= $xml->createElement('MICR',$fetch_teacher['emp_bank_micr']);
				$micrno = $row->appendChild($micrno);*/
				
				$ifsccd = $xml->createElement('IFSC',$key['bank_ifsc']);
				$ifsccd = $row->appendChild($ifsccd);
				
				$accno = $xml->createElement('BankAcct.No.',$key['emp_acc_no']);
				$accno = $row->appendChild($accno);
				
				$amount= $xml->createElement('Amount',$key['net']);
				$amount = $row->appendChild($amount);
			
				$type = $xml->createElement('BenfFlag',E);
				$type = $row->appendChild($type);
				
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
			
			$filename = substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";
			$name = strftime($filename);
			header('Content-Disposition: attachment;filename=' . $name);
			header('Content-Type: text/xml');
			echo $xml->saveXML();
		}
?>
