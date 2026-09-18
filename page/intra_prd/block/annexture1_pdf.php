<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
error_reporting(0);

if($_SERVER['HTTP_REFERER']=='')
{
	header("Location:../../dashboard.php");
}
?>

<?
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);

$cryptograph=new cryptography();
$fiyear=$cryptograph->decode($_GET['report_year'],4);
$fimonth=$cryptograph->decode($_GET['report_month'],4);
$report_month=$fimonth;
$fiyear2=$fiyear+1;
$c_year=date("Y");
$c_month=date("m");
$sal_momth_year=$fiyear.$fimonth;
$district_sub=explode('-',$_SESSION['location']['district_name']);
$sub=explode('(',$district_sub['1']);

$bill_no=isset($_GET['bill_no'])?$_GET['bill_no']:'';
$bill_entry_date=isset($_GET['bill_date'])?$_GET['bill_date']:'';
$bill_no=$cryptograph->decode($bill_no, 4);
$bill_entry_date=$cryptograph->decode($bill_entry_date, 4);


function number_to_words($number)
{
	if ($number > 999999999)
	{
		throw new Exception("Number is out of range");
	}
	$Gn = floor($number / 10000000);  /* Millions (giga) */
	$number -= $Gn * 10000000;
	$Ln = floor($number / 100000);  /* Millions (giga) */
	$number -= $Ln * 100000;
	$kn = floor($number / 1000);     /* Thousands (kilo) */
	$number -= $kn * 1000;
	$Hn = floor($number / 100);      /* Hundreds (hecto) */
	$number -= $Hn * 100;
	$Dn = floor($number / 10);       /* Tens (deca) */
	$n = $number % 10;               /* Ones */
	$cn = round(($number-floor($number))*100); /* Cents */
	$result = ""; 
	if ($Gn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Gn) . " Crore";  } 
	if ($Ln)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Ln) . " Lakh"; } 
	if ($kn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 
	if ($Hn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 
	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
	"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
	"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
	"Nineteen");
	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
	"Seventy", "Eigthy", "Ninety"); 
	if ($Dn || $n)
	{
		if (!empty($result))
		{  
			$result .= " and ";
		} 
		if ($Dn < 2)
		{  
			$result .= $ones[$Dn * 10 + $n];
		}
		else
		{  
			$result .= $tens[$Dn];
			if ($n)
			{  
				$result .= "-" . $ones[$n];
			}
		}
	}
	if ($cn)
	{
		if (!empty($result))
		{  
			$result .= ' and ';
		}
		$title = $cn==1 ? 'paisa ': 'paisa';
		$result .= strtolower(number_to_words($cn)).' '.$title;
	}
	if (empty($result))
	{  
		$result = "zero"; 
	} 
	return $result;
}

if(($fimonth!="") || ($fimonth!="")|| ($fimonth!=""))
{ 
	$report_year=$fiyear;
	$report_month=$fimonth;
	if($report_month=='01'){ $month=January;
	}else if($report_month=='02'){ $month=February;
	}else if($report_month=='03'){ $month=March;     
	}else if($report_month=='04'){ $month=April;     
	}else if($report_month=='05'){ $month=May;     
	}else if($report_month=='06'){ $month=June;     
	}else if($report_month=='07'){ $month=July;     
	}else if($report_month=='08'){ $month=August;     
	}else if($report_month=='09'){ $month=September;     
	}else if($report_month=='10'){ $month=October;     
	}else if($report_month=='11'){ $month=November;     
	}else if($report_month=='12'){ $month=December;     
	}else { $month=All; }	
}
//------------------------------------------query--------------------------------------------------------------------------------	
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$prd_dise_admin = $db->fetch_table("select * from prd_dise_admin where block_code = '".$_SESSION['location']['block_code']."'	
");

if($fiyear==$c_year && $fimonth==$c_month)
{
	$salary_total_sql = $db->fetch_table("
											SELECT SUM(net) AS net_sal FROM prd_employee_salary_save sal
											inner join prd_employee_master emp on emp.emp_id_pk=sal.emp_id_fk 
											WHERE salary_monthyear='".$sal_momth_year."' AND block_code='".$_SESSION['location']['block_code']."' 		
											AND status_flag='3' AND category_id='1' AND delete_status='1' AND is_saved='1' and emp.emp_status in ('1','9')
											AND requisition_type='".$requisition_type."'
											");
}
else
{
	$salary_total_sql = $db->fetch_table("
											SELECT SUM(net) AS net_sal 
											FROM prd_monthly_salary_archive_final sal 
											inner join prd_employee_master emp on emp.emp_id_pk=sal.emp_id_fk 
											WHERE salary_monthyear='".$sal_momth_year."' AND block_code='".$_SESSION['location']['block_code']."' 
											AND status_flag='3' AND delete_status='1' AND emp.emp_status in ('1','9')
											AND requisition_type='".$requisition_type."'
										");
}

if(1)
{
	unset($_SESSION['nodata']);					
	$date_array=explode('+', $data1[0]['latestupdate_time']);
	$date_time=$date_array[0];
	
	//-----------------------------------------------------------------------------------------------------------------------------
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');
	include ('../../../includes/third-party/mpdf/mpdf.php');
	$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
	
	$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_annual_salary.css');
	
	$mpdf->WriteHTML('
	<table width="660" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td colspan="2" align="center"><strong>ANNEXTURE - I </strong></td>
		</tr>
		<tr>
			<!--<td colspan="2" align="center"><strong>( See Rule 72 )</strong></td>-->
		</tr>
		<tr>
			<!--<td colspan="2" align="center"><u><strong>Acknowledgement For  '.$month."'".$fiyear.' &nbsp;(&nbsp; '.$sal_source_desc[0]['description'].'&nbsp;)&nbsp;</strong></u></td>-->
			<td colspan="2" align="center"><u><strong>Acknowledgement For  '.$month."'".$fiyear.' &nbsp;</strong></u></td>
		</tr>
		<tr>
			<td colspan="2" align="left">.&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="left">Received by cash / cheque Rs. &nbsp;<strong> '.$salary_total_sql[0]['net_sal'].' </strong></td>
		</tr>
		<tr>
			<td colspan="2" align="left">Rupees (in words):&nbsp;<strong> '.number_to_words($salary_total_sql[0]['net_sal']).' &nbsp; only</strong></td>
		</tr>
		<tr>
			<td colspan="2" align="left">from the Treasury Officer, <b>'.$sub['0'].' Treasury I/ II</b></td>
		</tr>
		<tr>
			<td colspan="2" align="left">Payment of my bill bearing Token No.___________________________</td>
		</tr>
		<tr>
			<td colspan="2" align="left">Dated the________________on account of__________________________</td>
		</tr>
		<tr>
			<td colspan="2" align="left">( if an advice the bill has been passed, has been received his should also be sent )</td>
		</tr>
		<tr>
			<td width="321" height="60" align="left" valign="bottom">STATION :-<u>'.$sub['0'].'</u></td>
			<td width="339" height="60" align="right" valign="bottom"> '.$prd_dise_admin['0']['desig_block'].'&nbsp;</td>
		</tr>
		<tr>
			<td width="321" align="left">Date ________________</td>
			<td width="339" align="right">
			<!--Sub-Division,-->  
			'.$prd_dise_admin['0']['address_line2'].'
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><u>(One Rupee Revenue stamp should be affixed or personal payments exceeding Rs. 5000/=)</u></td>
		</tr>
		<tr>
			<td colspan="2" align="left">.&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="left">Name of the messenger to whom payment is to be made:_____________________________________</td>
		</tr>
		<tr>
			<td colspan="2" height="60" align="left" valign="bottom">Signature of the messenger:____________________</td>
		</tr>
		<tr>
			<td colspan="2" height="70" align="center" valign="bottom">Signature Attested</td>
		</tr>
		<tr>
			<td colspan="2" align="center"> '.$prd_dise_admin['0']['desig_block'].' &nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="center"> '.$prd_dise_admin['0']['address_line2'].'</td>
		</tr>
	</table>
	');				
}
else
{
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');
	include ('../../../includes/third-party/mpdf/mpdf.php');
	$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
	$mpdf->WriteHTML('NO DATA FOUND');
}		

$mpdf->SetFooter('www.wbprd.gov.in |{PAGENO}');
$mpdf->Output('Annexture-I of '.ucwords(strtolower($_SESSION['location']['block_name'])).'.pdf', 'D');