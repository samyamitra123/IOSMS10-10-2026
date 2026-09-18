<?php
//echo 88; die;
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
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
function DiseCode($val){
		$db = new database();
		//print("SELECT description FROM prd_dise_code_master where code='".$val."';"); exit;
		$dist_data2 = $db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."';");
		return $dist_data2[0]['description'];
	}
function DiseCodeZp($val){
		$db = new database();
		//print("SELECT description FROM prd_dise_code_master where code='".$val."';"); exit;
		$dist_data2 = $db->fetch_table("SELECT designation_name as description FROM zpemp_emp_desig_master where designation_id='".$val."';");
		return $dist_data2[0]['description'];
	}	
$crypto = new cryptography();
//$cryptoGraph=new cryptography();
$db=new database();
$id = $crypto->decode($_GET['id'],4);
//var_dump($id); die;
$overage_pdf_fetch =$db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_order WHERE application_id = '".$id."' ");

$appNo = explode('/',$overage_pdf_fetch[0]['application_id']);
//print_r($appNo); exit;
$orderNo = 'I/'.($appNo[3] + $overage_pdf_fetch[0]['overage_order_pk']).'/'.date('Y');

$overage_details_fetch =$db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_master WHERE application_id = '".$id."' ");

$officer_name =$db->fetch_table(" SELECT * FROM intra_pri_master WHERE stake_user_code = '".$_SESSION['user_info']['stake_user_code']."' ");
$officer_desig =$db->fetch_table(" SELECT * FROM intra_pri_designation_master WHERE designation_code = '".$_SESSION['user_info']['stake_level_code']."' ");
 $pri_type = $overage_pdf_fetch[0]['pri_type'];	   
 //var_dump($overage_details_fetch); die;	
 $emp_desig_first_app = $overage_details_fetch[0]['emp_desig_first_app'];
 //print($pri_type); exit;
 if($pri_type == 'GP' || $pri_type == 'PS')
 {
 	$Firstdesignation = DiseCode($emp_desig_first_app);

 }
 if($pri_type == 'ZP')
 {
 	$Firstdesignation = DiseCodeZp($emp_desig_first_app);

 }

		if($pri_type == 'GP'){
			$Query = "SELECT *  FROM prd_location_master_gp
			              WHERE gp_code='".$overage_details_fetch[0]['emp_first_gp_ps_zp_code']."'";
			$arr_desig =$db->fetch_table($Query);
			
			$arr_desig_block =$db->fetch_table("SELECT *  FROM prd_location_master_block WHERE block_id_pk='".$arr_desig[0]['block_id_fk']."'");
			
			$arr_desig_district =$db->fetch_table("SELECT *  FROM prd_location_master_district WHERE district_id_pk='".$arr_desig_block[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['gp_name'].' GP';
			$pri_type = 'GP';
			$district_name = $arr_desig_district[0]['district_name'];
			$firstcandidate_Zp = '<strong>'.$arr_desig_name.'</strong> under <strong>'.$arr_desig_block[0]['block_name'].' Dev. Block, '.$arr_desig_district[0]['district_name'].'</strong>';
			
		}
		else if($pri_type == 'PS'){
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_panchayat_samiti WHERE ps_code ='".$overage_details_fetch[0]['emp_first_gp_ps_zp_code']."'");
			//print_r($arr_desig); exit;
			$arr_desig_district =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_id_pk ='".$arr_desig[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['ps_name'].' PS';
			$pri_type = 'PS';
			$firstcandidate_Zp = $district_name = $arr_desig_district[0]['district_name'];
			$firstcandidate_Zp = '<strong>'.$arr_desig_name.'</strong> under <strong> '.$arr_desig_district[0]['district_name'].'</strong>';
			
		}
		else if($pri_type == 'ZP'){
			//print($overage_details_fetch[0]['emp_first_gp_ps_zp_code']); exit;
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_code ='".$overage_details_fetch[0]['emp_first_gp_ps_zp_code']."'");
			$arr_desig_name = $arr_desig[0]['district_name'].' ZP';
			$pri_type = 'ZP';
			$firstcandidate_Zp = $district_name = $arr_desig[0]['district_name'];
		}



 //print_r($candidate_Zp); exit;
 $emp_name = $overage_pdf_fetch[0]['emp_name'];   
 $emp_designation = $overage_pdf_fetch[0]['emp_designation'];   
 $pri_name = $overage_pdf_fetch[0]['pri_name'];   
 $district = $overage_pdf_fetch[0]['district']; 
 $candidateZp = $overage_pdf_fetch[0]['candidate_zp'];   
 $emp_dob = date("d-m-Y",strtotime($overage_pdf_fetch[0]['emp_dob']));   
 $category = $overage_pdf_fetch[0]['category'];
 $condon_days = $overage_pdf_fetch[0]['condon_days'];
 

 $priName = ($pri_type == 'GP')?'Gram Panchayat':'Panchayat Samiti';
 $priName = ($pri_type == 'ZP')?'Zilla Parishad':$priName; 
 $cc_1_memo_no = $overage_pdf_fetch[0]['cc_1_memo_no'];
 $emp_sex = $overage_pdf_fetch[0]['emp_sex'];
 //$overageDob = explode('-',$overage_pdf_fetch[0]['emp_dob']); 
 //$overageDob = $overageDob[0].'-'.$overageDob[1].'-'.$overageDob[2];
 $overageDob = $overage_pdf_fetch[0]['emp_dob'];
 $emp_first_join_date = $overage_details_fetch[0]['emp_first_join_date'];
 $emp_first_join_date = explode('-',$overage_details_fetch[0]['emp_first_join_date']);
 $upper_age_limit = $overage_pdf_fetch[0]['upper_age_limit'];   

 $joinYear = $emp_first_join_date[0]; 
   switch($category)
   {
   	  case 'GENERAL':
   	    if($joinYear <= 2012)
           $upper_age_limit = 37;
   	    else
           $upper_age_limit = 40;
   	  break;
   	  case 'SC':
   	  case 'ST':
   	    if($joinYear <= 2012)
           $upper_age_limit = 37 + 5;
   	    else
           $upper_age_limit = 40 + 5;
   	  break;
   	  case 'OBC':
   	  case 'OBC-A':
   	  case 'OBC-B':
   	    if($upper_age_limit > 37)
           $upper_age_limit = $upper_age_limit;
   	    elseif($joinYear <= 2012)
           $upper_age_limit = 37 + 3;
   	    else
           $upper_age_limit = 40 + 3;   	  
   	  break;   	     	  
   }
 //$upper_age_limit = $overage_pdf_fetch[0]['upper_age_limit'] + (($category != 'GENERAL')?5:0);

 $emp_first_join_date = $emp_first_join_date[2].'-'.$emp_first_join_date[1].'-'.$emp_first_join_date[0];
 
$presentAddress = ''; 
$retireTimeStamp = strtotime('+60 year',strtotime($overageDob));
//echo "<hr />";
//print(date('Y-m-d',$retireTimeStamp)); exit;
//$retireTimeStamp = strtotime('+60 year',strtotime('1962-12-06'));
$curTimeStamp = time();
$Desig = '';        
if($curTimeStamp >= $retireTimeStamp){
$empData =$db->fetch_table(" SELECT * FROM prd_employee_master WHERE emp_id_const = '".$overage_pdf_fetch[0]['emp_id_const']."' ");
   $empData = $empData[0];
   //print_r($empData); exit;
   $presentAddress = ', Vill:'.$empData['emp_per_vill'].', Post Office:'.$empData['emp_per_post'].' Pin Code:'.$empData['emp_per_pin']; 
   $Desig = ($curTimeStamp > $retireTimeStamp)?'Ex. ':'';
  }

  	 $presentAddress = ', '.$pri_name.'</strong> under <strong>'.$candidateZp.$presentAddress; 
 
 $emp_designation = $Desig.$emp_designation; 
 switch($pri_type)
   {
   	  case 'GP':
   	    $forwardOrderPri = 'The Block Development Officer,'.$candidateZp;
   	  break;
   	  case 'PS':
   	     $forwardOrderPri = 'The Executive Officer,'.$candidateZp; 
   	  break;
   	  case 'ZP':
   	     $forwardOrderPri = 'The Additional Executive Officer,'.$candidateZp; 
   	  break;
   }
 
 if($emp_sex =='91'){ $he_she = 'he'; $his_her = 'his';} else if($emp_sex =='92'){ $he_she = 'she';  $his_her ='her'; }

 //var_dump($he_she); die;
	    //------------------------------------------------------------------------------------------------------	
	    define("_MPDF_TEMP_PATH", '../../locker/temp/');
	    include ('../../includes/third-party/mpdf/mpdf.php');
	    $stylesheet ='';
	    $mpdf=new mPDF();
	     

	    $mpdf->WriteHTML($stylesheet,1);
		

	    //Header and footer
	    $mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
 //<td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
	    //PDF header
	    $mpdf->WriteHTML('
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<h1 align="center"><img src="img/Pnrd_logo.png" style="float:center;width:60pt;height:80pt"></h1>
<h2 align="center" style="margin:0em 0em 0em;">Government of West Bengal</h2>
<h4 align="center" style="margin:0em 0em 0em;">Panchayats & Rural Development Department</h4>
<h4 align="center" style="margin:0em 0em 0em;">Joint Administrative Building, HC-7, Sector – III, Bidhan Nagar, Kolkata-700106</h4>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td class="layout" width="370">'.$orderNo.'</td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>
<br>



<h4 align="center" style="margin:0em 0em 0em;">ORDER</h4>
<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
           Whereas <strong>'.$emp_name.'</strong>, <strong>'.$emp_designation.'</strong>, <strong>'.$pri_name.'</strong> under <strong>'.$candidateZp.'</strong>, had joined '.$his_her.'
		   first assignment in the post of <strong>'.$Firstdesignation.'</strong> in '.$firstcandidate_Zp.'</strong> on <strong>'.$emp_first_join_date.'</strong> at the age of <strong>'.$condon_days.'</strong> when '.$he_she.' had crossed the upper age limit of <strong>'.$upper_age_limit.'</strong> 
		   years for joining the service of PR Bodies under 
		   <strong>'.$category.'</strong> Category.
           <br><br>
           And whereas <strong>'.$emp_name.'</strong> could not join the service of <strong>'.$priName.'</strong> within prescribed upper age limit due to delay 
		   caused by administrative issues which were beyond '.$his_her.' control. 
		   <br><br>
		   Now, therefore, after careful consideration of all aspects, the Governor is pleased to condone the overage beyond the prescribed upper 
		   age limit of <strong>'.$upper_age_limit.'</strong> years at the time of entry into service in respect of <strong>'.$emp_name.'</strong>, <strong>'.$emp_designation.'</strong>, 
		   <strong>'.$pri_name.'</strong> under <strong>'.$candidateZp.'</strong> district.
		   <br><br>
		   This order issues with the approval of the Secretary of this Department.
		   
</tr>
</table>

<table style="margin-top:4%;font-size:9pt"> 
<tr>
<td width="20"></td>
<td align="center"> </td>
<td width="400"></td>
<td align="center">.......................................................................................</td>
</tr>
<tr>
<td width="20"></td>
<td align="center"><strong></strong></td>
<td width="400"></td>
<td align="center">'.$officer_desig[0]['designation'].' to the Govt. of West Bengal <br>
Panchayats & Rural Development Department</td>
</tr>
</table>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td class="layout" width="370"></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>

<table>
<tr>
<td>Copy forwarded for information and necessary action to:-</td>
</tr>
</table>
<ol style="font-size:14px">
<li> The Senior P.A. to the Secretary of this Department.</li>
<li> The Additional Director, P&RD, West Bengal, This has ref. to his Memo No. '.$overage_pdf_fetch[0]['cc_1_memo_no'].'</li>
<li>The District Panchayat & Rural Development Officer, '.$overage_pdf_fetch[0]['district'].'</li>
<li>'.$forwardOrderPri.'</li>
<li>Head Assistant, PRI cell of this Department.</li>
<li><strong>'.$emp_name.'</strong>, <strong>'.$emp_designation.'</strong>'. $presentAddress.'</li>
</ol>
<table style="margin:10pt 0pt 0pt 0pt;font-size:9pt"> 
<tr>
<td width="20"></td>
<td align="center"> </td>
<td width="400"></td>
<td align="center">.......................................................................................</td>
</tr>
<tr>
<td width="20"></td>
<td align="center"><strong></strong></td>
<td width="400"></td>
<td align="center">'.$officer_desig[0]['designation'].' to the Govt. of West Bengal <br>
Panchayats & Rural Development Department</td>
</tr>
</table>
</body>
</html>',2);

//'.date('d-M-Y').'
$mpdf->Output('overage'.'.pdf','I');


$mpdf->SetWatermarkImage('./img/biswa_bangla_1.png',1,'',array(10,65));
$mpdf->showWatermarkImage = true;
$mpdf->watermarkImageAlpha = 0.15;




?>