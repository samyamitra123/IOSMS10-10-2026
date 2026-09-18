<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
/*header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");*/

ini_set('memory_limit', '-1');
//ini_set('max_execution_time',500);
set_time_limit(0);
 
$bdo=$_SESSION['user_info']['stake_user'];
$block_name=$_SESSION['location']['block_name'];

/*$arr_dist=explode("<br />",$dist_name);
$arr_ddo=explode("-",$arr_dist[0]);
$str_dis=str_replace("(",",",$arr_dist['1']);
$str_district=str_replace(")"," ",$str_dis);*/
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
//$crypto = new cryptography();

//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//---------------------------------------------------------------------------------------------------------------


$db = new database();


$arr = $db->fetch_table("SELECT gp_name,emp_first_name,emp_dob,emp_first_join_date,emp_pan_no,emp_mobile_no,emp_desig,emp_ifsc_no, emp_acc_no ,emp_status, emp_bank_name,emp_bank_branch,gp_id_fk,emp_id_const FROM ((prd_employee_master emp INNER JOIN prd_location_master_gp gp ON  gp.gp_id_pk=emp.gp_id_fk)
INNER JOIN prd_location_master_block block ON gp.block_id_fk=block.block_id_pk) WHERE block.block_code='$bdo' ORDER BY emp.emp_id_const");


//---------------------------------------------------------------------------------------------------------------
if(count($arr)>0)
{	
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Calcutta');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
//require_once '../Build/PHPExcel.phar';
require '../../../includes/third-party/PHPExcel/PHPExcel.php';

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();


function fun_desig($value){
	$db=new database();
	$desig=$db->fetch_table("select description from prd_dise_code_master where code='$value'");
	return $desig[0]['description'];
}
function fun_bank($value){
	$db=new database();
	$desig=$db->fetch_table("select bank_name from prd_dise_bank_master where bank_code='$value'");
	return $desig[0]['bank_name'];
}
function fun_status($value){
	$status='';
	switch($value){
		/*case '1':$status=1;
		break;
		case '6':$status=2;
		break;
		case '10':$status=3;
		break;
		default:$status=4;*/
		
		case '1':$status="Finalize";
		break;
		case '6':$status="Waiting";
		break;
		case '10':$status="Request Not Sent";
		break;
		default:$status="error";
	}

  return $status;
}
function cellColor($cells,$color){
        global $objPHPExcel;
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
        ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => $color)
		
        ));
    }
	
function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return "--";
	}
	else{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}

function set_date($original_date){
	return substr($original_date, 0,10);
} 	
// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("WB P&RD")
							 ->setLastModifiedBy("WB P&RD")
							 ->setTitle("WB P&RD test Document")
							 ->setSubject("WB P&RD Test Doc")
							 ->setDescription("WB P&RD Description")
							 ->setKeywords("WB P&RD, WB P&RD, WB P&RD")
							 ->setCategory("test test");


// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);

	
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', 'Sl. No.')
            ->setCellValue('B5', 'GP Name')
            ->setCellValue('C5', 'Teacher Name')
            ->setCellValue('D5', 'DOB')
			->setCellValue('E5', 'Joining Date')
			->setCellValue('F5', 'Pan No.')
			->setCellValue('G5', 'Mobile')
			->setCellValue('H5', 'Catagory')
			->setCellValue('I5', 'IFSC Code')
			->setCellValue('J5', 'A/C No.')
			->setCellValue('K5', 'Status')
			->setCellValue('L5', 'Bank Name')
			->setCellValue('M5', 'Branch Name');
			
			cellColor('A5:M5', 'C6E9F4');
			$objPHPExcel->getActiveSheet()
    ->getStyle('A5:M5')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$styleArray = array(
  'borders' => array(
    'inside'     => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'argb' => '000000'
      )
    ),
    'outline'     => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
         'argb' => '000000'
      )
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->applyFromArray($styleArray);	


		
$count = 6;
foreach ($arr as $key) {


	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$count, $count-5)
	            ->setCellValue('B'.$count, $key['gp_name'])
				->setCellValue('C'.$count, $key['emp_first_name'])
				->setCellValue('D'.$count, date_frmt_change(set_date($key['emp_dob'])))
				->setCellValue('E'.$count, date_frmt_change(set_date($key['emp_first_join_date'])))
				->setCellValue('F'.$count, $key['emp_pan_no'])
				->setCellValueExplicit('G'.$count, $key['emp_mobile_no'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('H'.$count, fun_desig($key['emp_desig']))
				->setCellValue('I'.$count, $key['emp_ifsc_no'])
				->setCellValueExplicit('J'.$count, $key['emp_acc_no'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('K'.$count, fun_status($key['emp_status']))
				->setCellValue('L'.$count, fun_bank($key['emp_bank_name']))
				->setCellValue('M'.$count, " ".$key['emp_bank_branch']);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$count.':M'.$count)->applyFromArray($styleArray);
				//$objPHPExcel->getActiveSheet()->getStyle('J'.$count)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
				
				$count += 1;
}

$objPHPExcel->getActiveSheet()->mergeCells('A2:M2');
$objPHPExcel->setActiveSheetIndex(0)
	        ->setCellValue('A2', 'GP wise Employee List for '.$block_name);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);







// Rename worksheet
//echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Report');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
//

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report-GP wise Employee List".xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
}
else
{
	echo "NO DATA FOUND";
}
?>