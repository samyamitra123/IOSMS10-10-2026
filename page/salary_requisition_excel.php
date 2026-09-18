<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
ob_start();
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
//require '../../../page_visite.php';

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
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//-----------------------------------------------------------------------------------------------------------------------------
function addzero($val){
	if(strlen($val) == 1){
		return '0'.$val;
	}
	else {
		return $val;
	}
}

function date_frmt($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01'){
		return "---";
	}
	else{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}
function get_month($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01'){
		return "---";
	}
	else{
		
		return $newDate = date(" F Y", strtotime($original_date));
		//require  date('l jS \of F Y');
	}
}
function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
				return $key['description'];
			}
		}
	}
//-------------------------------------------------------QUERY-----------------------------------------------------------------

	$db = new database();

	$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");
	
	/*$tch= $db->fetch_table("
									SELECT 
										sal.slno, sal.latestupdate_time, sal.latestupdate_ip_address, sal.schcd, sal.tchcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.teacher_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear, 
       sal.category_id, sal.dpsc_code, sal.teacher_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.circle_code,sal.part_day,sal.gsli,
										lms.school_name,
										tch.tchname,
										tch.basic_pay,
										tch.category,
										tch.tch_band_no,
										tch.tch_date_joining,
										tch.tch_approval_no,
										tch.tch_approval_date,
										tch.salary_source
									FROM
										ehrms_teacher_salary_save_primary as sal
									
									INNER JOIN 
										ehrms_dise_teacher_primary as tch
										ON sal.tchcd = tch.tchcd
									INNER JOIN 
										ehrms_dise_location_master_school as lms
										ON sal.schcd = lms.school_dise_code
								
										WHERE
												sal.circle_code = '".$_SESSION['user_info']['stake_user']."'
											AND tch.schcd = sal.schcd 
											AND tch.basic_pay != ''
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 )
											AND delete_status=1
										ORDER BY tch.rank,lms.school_name ASC;
		
								");*/
$tch=$db->fetch_table("SELECT 
       sal.slno, sal.latestupdate_time, sal.latestupdate_ip_address, sal.gp_code, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.gp_code,sal.part_day,sal.gsli,
										gp.gp_name,
										emp.emp_first_name,
										emp.emp_second_name,
										emp.emp_last_name,
										emp.emp_pay_in_payband,
										emp.emp_desig,
										emp.emp_pay_band,
										emp.emp_first_join_date
										
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as emp
										ON sal.emp_id_fk = emp.emp_id_pk
									INNER JOIN 
										prd_location_master_gp as gp
										ON sal.gp_code = CAST(gp.gp_code AS text)
								
										WHERE
												sal.gp_code = '".$_SESSION['user_info']['stake_user']."'
											AND CAST(emp.gp_id_fk AS text) = sal.gp_id_fk 
											AND sal.net!='0'
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 )
											AND delete_status=1
										ORDER BY gp.gp_name ASC");								
$date = $tch[0]['latestupdate_time'];
$msg="";
if($tch[0]['status_flag'] == 1){ 
	$msg="Not finalized (Just Saved)";
}					
elseif($tch[0]['status_flag'] == 2){ 
	$msg="Requisition finalized by GP";
} elseif($tch[0]['status_flag'] == 3){
	$msg="Requisition finalized by BLOCK";
} 
//-----------------------------------------------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------------------------------
if(count($tch)>0)
{	
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Calcutta');
//set_time_limit(3600);


define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
//require_once '../Build/PHPExcel.phar';
require '../../../../includes/third-party/PHPExcel/PHPExcel.php';

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();


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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10);



	
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', 'SL')
			->setCellValue('B5', 'NAME OF THE GP')
			->setCellValue('C5', 'NAME OF THE EMPLOYEE')
            ->setCellValue('D5', 'DESIGNATION')
            ->setCellValue('E5', 'BANK')
            ->setCellValue('F5', 'ACCOUNT NO')
			->setCellValue('G5', 'IFSC')
			->setCellValue('H6', 'CONSOLIDATED PAY')
			->setCellValue('I6', 'PAY IN PAY BAND')
			->setCellValue('J6', 'GRADE PAY')
			->setCellValue('K6', 'DA')
			->setCellValue('L6', 'HRA')
			->setCellValue('M6', 'MA')
			->setCellValue('N6', 'CONV ALLOW')
			->setCellValue('O5', 'GROSS')
			->setCellValue('P6', 'GPF')
			->setCellValue('Q6', 'PF-LOAN')
			->setCellValue('R6', 'P. TAX')
			->setCellValue('S6', 'I.TAX')
			->setCellValue('T6', 'GSLI')
			->setCellValue('U6', 'OVERDRAWN')
			->setCellValue('V5', 'NET PAY');
			$objPHPExcel->getActiveSheet()->mergeCells('H5:N5');
            $objPHPExcel->setActiveSheetIndex(0)
	                     ->setCellValue('H5', 'PAY & ALLOWANCES');
						 
			$objPHPExcel->getActiveSheet()->mergeCells('P5:U5');
            $objPHPExcel->setActiveSheetIndex(0)
	                     ->setCellValue('P5', 'DEDUCTIONS');

$objPHPExcel->getActiveSheet()->mergeCells('A5:A6');
$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
$objPHPExcel->getActiveSheet()->mergeCells('F5:F6');
$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');
$objPHPExcel->getActiveSheet()->mergeCells('O5:O6');
$objPHPExcel->getActiveSheet()->mergeCells('V5:V6');

//cellColor('A5:U5', 'C6E9F4');
//cellColor('H6:T6', 'C6E9FE');
			
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
  ),
  'font' => array(
        'bold' => true
    )
);
$styleArray1 = array(
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
  ),
);

$objPHPExcel->getActiveSheet()->getStyle('A5:V5')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('H6:U6')->applyFromArray($styleArray);	
$objPHPExcel->getActiveSheet()->getStyle('A5:V5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H6:U6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);				


		
$count = 7;
$i=0;
$gross=$net=0;
$tot_cons=$tot_payband=$tot_basic=$tot_da=$tot_hra=$tot_ma=$tot_ca=$tot_ha=$tot_cpf=$tot_gross=$tot_gpf=$tot_pfloan=$tot_cpfdeduct=$tot_ptax=$tot_itax=$tot_od=$tot_gsli=$tot_net=0;
foreach ($tch as $key) {
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$count, ($i+1))
				->setCellValue('B'.$count, $tch[$i]['gp_name'])
				->setCellValue('C'.$count, $tch[$i]['emp_first_name'].' '.$tch[$i]['emp_second_name'].' '.$tch[$i]['emp_last_name'])
	            ->setCellValue('D'.$count, fun_common($tch[$i]['emp_desig'] ,$code_data))
				->setCellValue('E'.$count, $tch[$i]['bankname'])
				->setCellValueExplicit('F'.$count, $tch[$i]['accountno'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('G'.$count, $tch[$i]['bank_ifsc'])
				->setCellValue('H'.$count, $tch[$i]['consolidated_pay'])
				->setCellValue('I'.$count, $tch[$i]['pay_payband'])
				->setCellValue('J'.$count, $tch[$i]['tch_grade_pay'])
				->setCellValue('K'.$count, $tch[$i]['da'])
				->setCellValue('L'.$count, $tch[$i]['hra'])
				->setCellValue('M'.$count, $tch[$i]['ma'])
				->setCellValue('N'.$count, $tch[$i]['conv_allow'])
				->setCellValue('O'.$count, $tch[$i]['gross_salary'])
				->setCellValue('P'.$count, $tch[$i]['gpf'])
				->setCellValue('Q'.$count, $tch[$i]['pf_loan'])
				->setCellValue('R'.$count, $tch[$i]['p_tax'])
				->setCellValue('S'.$count, $tch[$i]['i_tax'])
				->setCellValue('T'.$count, $tch[$i]['gsli'])
				->setCellValue('U'.$count, $tch[$i]['overdrawn'])
				->setCellValue('V'.$count, $tch[$i]['net']);
				
$tot_cons+=$tch[$i]['consolidated_pay'];
$tot_payband+=$tch[$i]['pay_payband'];
$tot_basic+=$tch[$i]['tch_grade_pay'];
$tot_da+=$tch[$i]['da'];
$tot_hra+=$tch[$i]['hra'];
$tot_ma+=$tch[$i]['ma'];
$tot_ca+=$tch[$i]['conv_allow'];
$tot_ha+=$tch[$i]['hill_allowance'];
$tot_cpf+=$tch[$i]['cpf'];
$tot_gross+=$tch[$i]['gross_salary'];
$tot_gpf+=$tch[$i]['gpf'];
$tot_pfloan+=$tch[$i]['pf_loan'];
$tot_cpfdeduct+=$tch[$i]['cpf_deduct'];
$tot_ptax+=$tch[$i]['p_tax'];
$tot_itax+=$tch[$i]['i_tax'];
$tot_gsli+=$tch[$i]['gsli'];
$tot_od+=$tch[$i]['overdrawn'];
$tot_net+=$tch[$i]['net'];
$objPHPExcel->getActiveSheet()->getStyle('A'.$count.':V'.$count)->applyFromArray($styleArray1);
$objPHPExcel->getActiveSheet()->getStyle('M'.$count)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
$objPHPExcel->getActiveSheet()->getStyle('H'.$count.':V'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$count += 1;
$i+=1;
}
$cnt=$count+1;
$cnt1=$cnt+1;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$count.':G'.$count);
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$count,'TOTAL')
			->setCellValue('H'.$count,$tot_cons)
			->setCellValue('I'.$count,$tot_payband)
			->setCellValue('J'.$count,$tot_basic)
			->setCellValue('K'.$count,$tot_da )
			->setCellValue('L'.$count,$tot_hra )
			->setCellValue('M'.$count,$tot_ma )
			->setCellValue('N'.$count,$tot_ca )
			->setCellValue('O'.$count,$tot_gross )
			->setCellValue('P'.$count,$tot_gpf )
			->setCellValue('Q'.$count,$tot_pfloan )
			->setCellValue('R'.$count,$tot_ptax )
			->setCellValue('S'.$count,$tot_itax )
			->setCellValue('T'.$count,$tot_gsli )
			->setCellValue('U'.$count,$tot_od )
            ->setCellValue('V'.$count,$tot_net );
$objPHPExcel->getActiveSheet()->getStyle('A'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B'.$count.':V'.$count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle('A'.$count.':V'.$count)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:V2');
$objPHPExcel->setActiveSheetIndex(0)
	        ->setCellValue('A2', 'DETAILED SALARY BILL OF Grant-in-aid and Contractual Employees UNDER  '.$_SESSION['location']['gp_name'].' GP FOR THE MONTH OF '.get_month($date));
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A2:X2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);




//$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt.':X'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);				
$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt.':V'.$cnt)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$cnt.':V'.$cnt);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$cnt1.':V'.$cnt1);
$objPHPExcel->setActiveSheetIndex(0)
	        ->setCellValue('A'.$cnt1, 'Gram Pradhan OF '.$_SESSION['location']['gp_name'].' GP');
$objPHPExcel->getActiveSheet()->getRowDimension($cnt1)->setRowHeight(100);
$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt1)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt1.':V'.$cnt1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



// Rename worksheet
//echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Salary Bill Details');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
//

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Salary_Data".xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
}
else
{
	$common['title'] = "Profile Form| eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';?>
<style>
.msg-dig {
			display: block;
			color: #900;
			background-color: #ACDBEA;
			text-align: center;
			padding: 20px;
			text-decoration: none;
			text-transform: uppercase;
			border-radius: 5px;
			margin: 10px 18px 10px 10px;
		}

</style>
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<div class="mainContent float_l" style="width:1000px">
<!-- Common Back Button --->
	<?php require '../../../common_back_btns.php'; ?>	
  <div class="dashboard-main"> 
    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
    <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3><?php
					  if(isset($_SESSION['location']['school_name'])){
                          echo $_SESSION['location']['school_name'];
                      } elseif(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['circle_name'])) {
                          echo $_SESSION['location']['circle_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                      }
                      ?></h3>
       </div>
    <link rel="stylesheet" href="themes/default/css/style.css" />
    
    <div id="show_box"><div class="msg-dig" style="margin-top: 2%;"><b>No Data Found.</b></div></div>
	
    </div>
  </div>
</div>
<br clear="all">

<?php  
require '../../../../page/layout/footer.php';  
}
?>