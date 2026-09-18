<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../page_visite.php';

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
							FROM ehrms_dise_code_master;
	
	");
	$tch= $db->fetch_table("
									SELECT 
										sal.*,
										tch.tchname,
										tch.basic_pay,
										tch.category,
										tch.tch_band_no,
										tch.tch_date_joining,
										tch.tch_approval_no,
										tch.tch_approval_date,
										tch.salary_source
									FROM
										ehrms_teacher_salary_save as sal
									
									INNER JOIN 
										ehrms_dise_teacher as tch
										ON sal.tchcd = tch.tchcd
								
										WHERE
												sal.schcd = '".$_SESSION['user_info']['stake_user']."'
											AND tch.schcd = '".$_SESSION['user_info']['stake_user']."'
											AND tch.basic_pay != ''
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 OR sal.status_flag = 4)
										ORDER BY tch.rank ASC;
		
								");
//-----------------------------------------------------------------------------------------------------------------------------
define("_MPDF_TEMP_PATH", '../../../../locker/temp/');
include ('../../../../includes/third-party/mpdf/mpdf.php');
$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3,"L");
$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_salary_requisition.css');
$mpdf->WriteHTML($stylesheet,1);
//Header and footer
$mpdf->SetFooter('www.wbehrms.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');

//PDF header
$mpdf->AddPage('L','Legal');

$tch_count = count($tch);

$row_pp = 10;
$total = $tch_count;


$div = ($total / $row_pp) ;
$rem = ($total % $row_pp) ;
$page = 0;
if($rem != 0){
	$page = (int)$div + 1;
} else {
	$page = (int)$div;
}

$page_count = 0;
$page_start = 0;
$page_max = $row_pp;
$date = $tch[0][latestupdate_time];


for($j = 0; $j < $page; $j++){
//$var = $tch_count % 10;

	$mpdf->WriteHTML('
					<table border="1" width="100%">
						<tr>
							<th class="heading" colspan="21">Salary Requisition of '.$_SESSION['location']['school_name'].' for '.get_month($date).' ( '.fun_common($tch[0]['salary_source'],$code_data).' )</th>
						</tr>
						<tr class="colgray">
							<th rowspan="2">SL. No.</th>
							<th rowspan="2">NAME</th>
							<th rowspan="2">DOA</th>
							<th rowspan="2">APPVL NO & DATE</th>
							<th rowspan="2">PAY BAND</th>
							<th rowspan="2">BANK IFSC</th>
							<th rowspan="2">ACCOUNT NO.</th>
							<th colspan="8">Pay</th>
							<th colspan="5">Deduct</th>
							<th rowspan="2">Net Salary</th>
						</tr>
						<tr class="colgray">
							<th>Basic Pay</th>
							<th>DA</th>
							<th>HRA</th>
							<th>MA</th>
							<th>Conv<br>Allow</th>
							<th>Hill<br>Allow</th>
							<th>CPF</th>
							<th>Gross Salary</th>
							<th>GPF</th>
							<th>PF Loan</th>
							<th>CPF<br>Deduct</th>
							<th>P. Tax</th>
							<th>I. Tax</th>
						</tr>
				',2);
	if($page_count == 0){
		$page_start = 0;
	} else {
		$page_start = $page_count * $row_pp;
	}
	
	if(($page_count * $row_pp)+$row_pp > $total){
		
		$max = ($page_count * $row_pp)+$row_pp;
		$extra = $max - $total;
		$page_max = $max - $extra;
		
	} else{
		$page_max = ($page_count * $row_pp)+$row_pp;
	}
	
	for($i = $page_start; $i < $page_max; $i++){

	$mpdf->WriteHTML('
					<tr>
							<td>'.($i+1).'</td>
							<td width="180"><b>'.$tch[$i]['tchname'].', '.fun_common('11'. addzero($tch[$i]['category']) ,$code_data) .'</b></td>
							<td>'.date_frmt($tch[$i]['tch_date_joining']).'</td>
							<td>'.$tch[$i]['tch_approval_no'].'<br />'.date_frmt($tch[$i]['tch_approval_date']).'</td>
							<td>'.fun_common($tch[$i]['tch_band_no'],$code_data).'</td>
							<td>'.$tch[$i]['bank_ifsc'].'</td>
							<td>'.$tch[$i]['accountno'].'</td>
							<td>'.$tch[$i]['basic'].'</td>
							<td>'.$tch[$i]['da'].'</td>
							<td>'.$tch[$i]['hra'].'</td>
							<td>'.$tch[$i]['ma'].'</td>
							<td>'.$tch[$i]['conv_allow'].'</td>
							<td>'.$tch[$i]['hill_allowance'].'</td>
							<td>'.$tch[$i]['cpf'].'</td>
							<td>'.$tch[$i]['gross_salary'].'</td>
							<td>'.$tch[$i]['gpf'].'</td>
							<td>'.$tch[$i]['pf_loan'].'</td>
							<td>'.$tch[$i]['cpf_deduct'].'</td>
							<td>'.$tch[$i]['p_tax'].'</td>
							<td>'.$tch[$i]['i_tax'].'</td>
							<td>'.$tch[$i]['net'].'</td>
							
						</tr>		
				',2);

	};
	$i += 1;
	$mpdf->WriteHTML('</table>',20);

	$mpdf->WriteHTML('<br />
					<table border="1" width="100%">
						<tr class="colgray">
							<td colspan="3">i) Certified that the claim in this bill was not drawn before & said claim has been calculated against the approved teaching and non-teaching staff.</td>
						</tr>
						<tr class="colgray">
							<td colspan="3">ii) Certified that none of the incumbent has drawn HRA (both husband and wife) exceeding Rs. 6000/- per month.</td>
						</tr>
						<tr>
							<td style="padding:20px;">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr class="colgray">
							<td>Signature of Secretary / Administrator</td>
							<td>Signature of the Head of the Institution</td>
							<td>Signature of DIS(SE) / ADIS(SE)</td>
						</tr>
						<tr class="colgray">
							<td colspan="3">Salary Requisition Finalized on 25/05/2014</td>
						</tr>
						
					</table>

					',2);
if($tch[0]['status_flag'] == 1){ 
	$mpdf->WriteHTML('<div class="statusn">Not finalized (Just Saved)</div>',2);
}					
elseif($tch[0]['status_flag'] == 2){ 
	$mpdf->WriteHTML('<div class="statusf">Requisition finalized by HOI</div>',2);
} elseif($tch[0]['status_flag'] == 3){
	$mpdf->WriteHTML('<div class="statusf">Requisition finalized by HOI</div>',2);
} elseif($tch[0]['status_flag'] == 4){
	$mpdf->WriteHTML('<div class="statusf">Requisition finalized by HOI</div>',2);
}
	
					

$page_count += 1;
//$mpdf->WriteHTML(''. $page_count .'',2);
if($page_count != $page){
	$mpdf->AddPage('L','Legal');
}

}


$mpdf->Output('Requisition.pdf','D');
?>