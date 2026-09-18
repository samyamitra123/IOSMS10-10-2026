<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require 'includes/library/session.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
	
set_time_limit(0);
$db = new database();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
//echo $enc_session;
//echo $sec_time_token;
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'ifms_report_for_first_join.php';
	exit;
}
else{
			if($validator->blank_select($_POST['year']) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Year Of Report.</strong></div>';
			include 'ifms_report_for_first_join.php';
			exit;
			}
		else{
			if(!empty($_POST['year']) && !empty($_POST['month'])){	
			$sql=$db->fetch_table("SELECT gp.gp_name,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_acc_no,
			emp.emp_micr_no,emp.emp_group,emp_pan_no,emp_mobile_no,emp_mail_id,emp_aadhar_no,emp_ifsc_no
			FROM prd_employee_master AS emp,
     		prd_location_master_gp AS gp
			WHERE emp.gp_id_fk=gp.gp_id_pk AND emp.emp_status='1' 
			AND substr(CAST(gp.gp_code AS text),1,7)='".$_SESSION['location']['block_code']."' AND
			extract('Y' from emp_join_prsnt_office_date)='".$_POST['year']."' AND
			extract('MON' from emp_join_prsnt_office_date)='".$_POST['month']."' 
			ORDER BY gp.gp_name");
			} else{
			$sql=$db->fetch_table("SELECT gp.gp_name,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_acc_no,
			emp.emp_micr_no,emp.emp_group,emp_pan_no,emp_mobile_no,emp_mail_id,emp_aadhar_no,emp_ifsc_no
			FROM prd_employee_master AS emp,
     		prd_location_master_gp AS gp
			WHERE emp.gp_id_fk=gp.gp_id_pk AND emp.emp_status='1' 
			AND substr(CAST(gp.gp_code AS text),1,7)='".$_SESSION['location']['block_code']."' AND
			extract('Y' from emp_join_prsnt_office_date)='".$_POST['year']."'
			ORDER BY gp.gp_name");
			}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require '../../../includes/third-party/PHPExcel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(70);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(35);
$objPHPExcel->setActiveSheetIndex(0)
          		->setCellValue('A1', 'Beneficiary Name')
				->setCellValue('B1', 'Bank Account No')
				->setCellValue('C1', 'IFSC Code')
				->setCellValue('D1', 'MICR No')
				->setCellValue('E1', 'Account Type')
				->setCellValue('F1', 'Beneficiary Type')
				->setCellValue('G1', 'Group')
				->setCellValue('H1', 'PAN Number')
				->setCellValue('I1', 'Mobile No')
				->setCellValue('J1', 'GPF/TPF No')
				->setCellValue('K1', 'Adhar Number')
				->setCellValue('L1', 'Address')
				->setCellValue('M1', 'E-mail ID');

// Miscellaneous glyphs, UTF-8
$count = 2;
$group = '';
if(!empty($sql)){
foreach($sql as $row){
	if($row['emp_group']=='631'){
			$group = 'A';
		}elseif($row['emp_group']=='632'){
			$group = 'B';
		}elseif($row['emp_group']=='633'){
			$group = 'C';
		}elseif($row['emp_group']=='634'){
			$group = 'D';
		}elseif($row['emp_group']=='635'){
			$group = 'Other';
		}
		//$school_name = str_replace("'","",$row['school']);
$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$count, $row['emp_first_name'].' '.$row['emp_second_name'].' '.$row['emp_last_name'])
					->setCellValueExplicit('B'.$count, $row['emp_acc_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('C'.$count, $row['emp_ifsc_no'])
					->setCellValueExplicit('D'.$count, $row['emp_micr_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('E'.$count, 'Savings')
					->setCellValue('F'.$count, 'Employee')
					->setCellValue('G'.$count, $group)
					->setCellValue('H'.$count, $row['emp_pan_no'])
					->setCellValueExplicit('I'.$count, $row['emp_mobile_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('J'.$count, '')
					->setCellValueExplicit('K'.$count, $row['emp_aadhar_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('L'.$count, $row['gp_name'])
					->setCellValue('M'.$count, $row['emp_mail_id']);
$count += 1;
				
}
}
else{
	$error_msg='<div class="alert alert-info" style="text-align:center"><strong>Sorry !!! No Data Found.</strong></div>';
	include 'ifms_report_for_first_join.php';
	exit;
}
				
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);				
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'E05CC2')
            )
        )
	);
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Master Data');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$_SESSION['location']['block_code'].'_'.$_SESSION['location']['block_name'].'_Beneficiary Mater Data.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
}
}
