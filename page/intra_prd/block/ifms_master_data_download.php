<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
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

$sql=$db->fetch_table("SELECT gp.gp_name,emp.emp_first_name, emp.emp_id_const,desig.description,emp.emp_dob,emp.emp_termination_date,emp.emp_second_name,emp.emp_last_name,emp.emp_acc_no,emp.emp_micr_no,emp.emp_group,emp_pan_no,emp_mobile_no,emp_mail_id,emp_aadhar_no,emp_ifsc_no,emp.emp_pay_in_payband,emp.ropa_level,emp.emp_edu_quali
FROM prd_employee_master AS emp
    inner join prd_location_master_gp AS gp on emp.gp_id_fk=gp.gp_id_pk 
	 inner join prd_dise_code_master as desig on  CAST (emp.emp_desig as character varying)= desig.code
WHERE emp.emp_status='1' AND substr(CAST(gp.gp_code AS text),1,7)='".$_SESSION['location']['block_code']."' 
ORDER BY gp.gp_name");
//$res = pg_query($sql);
/** Error reporting */

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
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

// Add some data
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(70);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)
          		->setCellValue('A1', 'Beneficiary Name')
				->setCellValue('B1', 'Employee ID')
				->setCellValue('C1', 'Employee Designation')
				->setCellValue('D1', 'Employee DOB')
				->setCellValue('E1', 'Employee Retirement Date')
				->setCellValue('F1', 'Bank Account No')
				->setCellValue('G1', 'IFSC Code')
				->setCellValue('H1', 'MICR No')
				->setCellValue('I1', 'Account Type')
				->setCellValue('J1', 'Beneficiary Type')
				->setCellValue('K1', 'Group')
				->setCellValue('L1', 'PAN Number')
				->setCellValue('M1', 'Mobile No')
				->setCellValue('N1', 'GPF/TPF No')
				->setCellValue('O1', 'Adhar Number')
				->setCellValue('P1', 'Address')
				->setCellValue('Q1', 'E-mail ID')
				->setCellValue('R1', 'Basic')
				->setCellValue('S1', 'Level')
				->setCellValue('T1', 'Qualification');

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
		if($row['emp_edu_quali']=='1202'){
			$quali = 'SECONDARY';
		}elseif($row['emp_edu_quali']=='1203'){
			$quali = 'HIGHER SECONDARY';
		}elseif($row['emp_edu_quali']=='1204'){
			$quali = 'GRADUATE';
		}elseif($row['emp_edu_quali']=='1206'){
			$quali = 'POST GRADUATE';
		}elseif($row['emp_edu_quali']=='1209'){
			$quali = 'Other';
		}
		
		elseif($row['emp_edu_quali']=='1210'){
			$quali = 'PRIMARY';
		}
		elseif($row['emp_edu_quali']=='1211'){
			$quali = 'CLASS VIII';
		}
		elseif($row['emp_edu_quali']=='1212'){
			$quali = 'DIPLOMA';
		}
		
		
		
		//$school_name = str_replace("'","",$row['school']);
$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$count, $row['emp_first_name'].' '.$row['emp_second_name'].' '.$row['emp_last_name'])
					->setCellValueExplicit('B'.$count, $row['emp_id_const'])
					->setCellValueExplicit('C'.$count, $row['description'])
					->setCellValueExplicit('D'.$count, dateshow($row['emp_dob']))
					->setCellValueExplicit('E'.$count, dateshow($row['emp_termination_date']))
					->setCellValueExplicit('F'.$count, $row['emp_acc_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('G'.$count, $row['emp_ifsc_no'])
					->setCellValueExplicit('H'.$count, $row['emp_micr_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('I'.$count, 'Savings')
					->setCellValue('J'.$count, 'Employee')
					->setCellValue('K'.$count, $group)
					->setCellValue('L'.$count, $row['emp_pan_no'])
					->setCellValueExplicit('M'.$count, $row['emp_mobile_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('N'.$count, '')
					->setCellValueExplicit('O'.$count, $row['emp_aadhar_no'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('P'.$count, $row['gp_name'])
					->setCellValue('Q'.$count, $row['emp_mail_id'])
					->setCellValue('R'.$count, $row['emp_pay_in_payband'])
					->setCellValue('S'.$count, $row['ropa_level'])
					->setCellValue('T'.$count, $quali);
$count += 1;
				
}
}
				
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFont()->setBold(true);				
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray(
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
