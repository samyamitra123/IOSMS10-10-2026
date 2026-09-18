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


//print_r($_SESSION); die;
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
//var_dump($_SESSION['user_info']['stake_level_code']); die;


	function fun_desig($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$desig = @$db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."'");
	return $desig[0]['description'];
	}
	function fun_gp($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$gp = @$db->fetch_table("SELECT gp_name FROM prd_location_master_gp where gp_id_pk='".$val."'");
	return $gp[0]['gp_name'];
	}
	function fun_ps($val)
	{
	$db = new database();
	//echo ("SELECT ps_name FROM prd_location_master_panchayat_samiti where ps_id_pk='".$val."'");die;
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$ps = @$db->fetch_table("SELECT ps_name FROM prd_location_master_panchayat_samiti where ps_id_pk='".$val."'");
	return $ps[0]['ps_name'];
	}
	
	function fun_block($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$block = @$db->fetch_table("SELECT block_name FROM prd_location_master_block where block_id_pk='".$val."'");
	return $block[0]['block_name'];
	}
	
	function fun_district($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$district = @$db->fetch_table("SELECT district_name FROM prd_location_master_district where district_id_pk='".$val."'");
	return $district[0]['district_name'];
	}
	
	function fun_district_head_office($val)
	{
	$db = new database();
	//echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
	$district = @$db->fetch_table("SELECT head_office FROM prd_location_master_district where district_id_pk='".$val."'");
	return $district[0]['head_office'];
	}
	
	
$crypto = new cryptography();
//$cryptoGraph=new cryptography();
$db=new database();
 $id = $crypto->decode($_GET['id'],4);
//var_dump($id); die;

//echo (" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' "); die;
$pdf_fetch_DT =$db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' and district_level='ID' ");

//echo $pdf_fetch_DT[0]['pre_district'];
//echo $pdf_fetch_DT[1]['pre_ps_id_fk']; die;


$officer_name =$db->fetch_table(" SELECT * FROM intra_pri_master WHERE stake_user_code = '".$_SESSION['user_info']['stake_user_code']."' ");
$officer_desig =$db->fetch_table(" SELECT * FROM intra_pri_designation_master WHERE designation_code = '".$_SESSION['user_info']['stake_level_code']."' ");
	  
	   
	   
	   $memo=rand(000,999).'/'.date('Y').'/'.'Inter District Transfer';
//var_dump($overage_pdf_fetch); die;	
/*
 $emp_name = $pdf_fetch_DT[0]['emp_name'];   
 $present_posting = $pdf_fetch_DT[0]['present_posting'];   
 $new_posting = $pdf_fetch_DT[0]['new_posting'];   
 $district = $pdf_fetch_DT[0]['district'];   
 $emp_dob = $pdf_fetch_DT[0]['emp_dob'];   
 $category = $pdf_fetch_DT[0]['category'];
 $condon_days = $pdf_fetch_DT[0]['condon_days'];
 $upper_age_limit = $pdf_fetch_DT[0]['upper_age_limit'];
 $pri_type = $pdf_fetch_DT[0]['pri_type'];
 $cc_1_memo_no = $pdf_fetch_DT[0]['cc_1_memo_no'];
 $emp_sex = $pdf_fetch_DT[0]['emp_sex'];
 
 if($emp_sex =='91'){ $he_she = 'he'; $his_her = 'his';} else if($emp_sex =='92'){ $he_she = 'she';  $his_her ='her'; }
*/
 //var_dump($he_she); die;
	    //------------------------------------------------------------------------------------------------------	
	    define("_MPDF_TEMP_PATH", '../../locker/temp/');
	    include ('../../includes/third-party/mpdf/mpdf.php');
	    $stylesheet ='';
	    $mpdf=new mPDF();
	     

	    $mpdf->WriteHTML($stylesheet,1);
		

	    //Header and footer
	    $mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
 
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
<h4 align="center" style="margin:0em 0em 0em;">OFFICE OF THE DISTRICT MAGISTRATE & COLLECTOR </h4>
<h4 align="center" style="margin:0em 0em 0em;">'.fun_district_head_office($pdf_fetch_DT[0]['pre_district_id_fk']).'</h4>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td width="370" class="layout"><strong>Memo No. - '.$memo.'</strong></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>
<br>


<table style="font-size:10pt;margin:3pt 0pt 0pt 0pt;">
<tr>
<td width="370" class="layout">Order: Inter Dstrict Transfer</td>
</tr>
</table>

<table border="0" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:10pt;" > 
<tr>
<td align="justify">
           The following PRI employees now posted at different Gram Panchayats/ Panchayat Samiti as detailed in column nos. 4&5 are hereby transferred and posted at Gram Panchayats/ Panchayat Samiti of this district as detailed in column nos. 6&7. </span></span></td>		   
</tr>
</table>
<table align="center" border="1" width="100%" style="border-collapse: collapse; margin:10pt 0pt 0pt 0pt; font-size:8pt;" > 
<tr>
<td width="5%" height="22" align="center" valign="middle"><div align="center"><strong><span class="style2">Sl. No.</span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name Of The Employees </span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">Designation </span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name of the Present Gram Panchayat</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">Name of the Present Block</span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">Transferred to Gram Panchayat</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">Transferred to Block</span></strong></div></td>
</tr>
<tr>
<td width="5%" height="22" align="center" valign="middle"><div align="center"><strong><span class="style2">(1)</span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">(2) </span></strong></div></td>
<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">(3) </span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">(4)</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">(5)</span></strong></div></td>
<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">(6)</span></strong></div></td>
<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">(7)</span></strong></div></td>
</tr>',2);
$count = 1;
 foreach($pdf_fetch_DT as  $val){
	 
	// echo $val['pre_ps_id_fk']; 
	 
	 
	 
	 
	 
$mpdf->WriteHTML('<tr>
	<td width="5%" height="19" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$count.'</span></strong></div></td>
	<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].'</span></strong></div></td>
	<td width="19%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_desig($val['desig']).'</span></strong></div></td>',2);
	
	if($val['pre_gp_id_fk']!='' && $val['pre_ps_id_fk']=='0')
	{
	
	$mpdf->WriteHTML('
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_gp($val['pre_gp_id_fk']).'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_block($val['pre_block_id_fk']).'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_gp($val['transfer_gp_id_fk']).'</span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_block($val['transfer_block_id_fk']).'</span></strong></div></td>
</tr>',2);
	}
	else if($val['pre_ps_id_fk']!='')
	{
		
		//echo 1111; 
		$mpdf->WriteHTML('
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2"></span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_ps($val['pre_ps_id_fk']).'</span></strong></div></td>
	<td width="20%" align="center" valign="middle"><div align="center"><strong><span class="style2"></span></strong></div></td>
	<td width="15%" align="center" valign="middle"><div align="center"><strong><span class="style2">'.fun_ps($val['transfer_ps_id_fk']).'</span></strong></div></td>
</tr>',2);
	}
$count++; } 

//die;
$mpdf->WriteHTML('
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
<td align="center"><p>'.$officer_name[0]['officer_name'].' </p>
  <p>'.$officer_desig[0]['designation'].' of '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']). '</td>
</tr>
</table>
<hr>
<table class="layout" style="font-size:10pt;">
<tr>
    <td width="370" class="layout"><strong>Memo No. - '.$memo.'</strong></td>
    <td class="layout"></td>
    <td class="layout" width="160"></td>
    <td class="layout" align="right">Date: <strong>'.date('d-M-Y').'</strong></td>
</tr>
</table>

<table>
<tr>
<td>Copy forwarded for information and necessary action to:</td>
</tr>
</table>
<ol style="font-size:14px">
<li>The Sub-Divisional Officer,___________________________ Sub-Division, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);




/*$db=new database();

		$check =$db->fetch_table(" Select pre_block_id_fk,transfer_block_id_fk FROM intra_pri_district_transfer  WHERE application_id = '".$id."' and status='2' and district_level='ID'  ");
		foreach($check as  $b){
//echo 1;
		$b1=$b['pre_block_id_fk'];
		$c1=$b['transfer_block_id_fk']
		 $result=array_diff($b[0]['pre_block_id_fk'],$b[0]['transfer_block_id_fk']);
		
		print_r($result); 
		}
		
		die;*/


$db=new database();
		$check =$db->fetch_table("select distinct(b.block_id_pk) from prd_location_master_block as b
inner join intra_pri_district_transfer as t on t.pre_block_id_fk=b.block_id_pk or cast(t.transfer_block_id_fk as text)=cast(b.block_id_pk as text) WHERE t.application_id = '".$id."' and t.status='2' and t.district_level='ID'");

foreach($check as  $val_block){
	$db=new database();
	
	
	/*$check_block =$db->fetch_table("select block_id_pk from prd_location_master_block  WHERE block_id_pk = '".$val_block['block_id_pk']."'");*/
	
	$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val_block['block_id_pk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
}

$db=new database();


		$check_ps =$db->fetch_table("select distinct(b.ps_id_pk) from prd_location_master_panchayat_samiti as b
inner join intra_pri_district_transfer as t on t.pre_ps_id_fk=b.ps_id_pk or cast(t.transfer_ps_id_fk as text)=cast(b.ps_id_pk as text) WHERE t.application_id = '".$id."' and t.status='2' and t.district_level='ID'");

foreach($check_ps as  $val_ps){
	$db=new database();
	
	
	/*$check_block =$db->fetch_table("select ps_id_pk from prd_location_master_panchayat_samiti  WHERE ps_id_pk = '".$val_ps['ps_id_pk']."'");*/
	
	$mpdf->WriteHTML('<li>The Executive officer,'.fun_ps($val_ps['ps_id_pk']).' Panchay Samit, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
}


$db=new database();

		$check_gp =$db->fetch_table("select distinct(b.gp_id_pk),b.block_id_fk from prd_location_master_gp as b
inner join intra_pri_district_transfer as t on t.pre_gp_id_fk=b.gp_id_pk or cast(t.transfer_gp_id_fk as text)=cast(b.gp_id_pk as text) WHERE t.application_id = '".$id."' and t.status='2' and t.district_level='ID'");

foreach($check_gp as  $val_gp){
	$db=new database();
	
	
	$check_gp_name =$db->fetch_table("select gp_id_pk,block_id_fk from prd_location_master_gp  WHERE gp_id_pk = '".$val_gp['gp_id_pk']."'");
	
	/*$mpdf->WriteHTML('<li>The Prodhan,_______________________ Gram Panchayat under __________________________ Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	*/
	
	
	$mpdf->WriteHTML('<li>The Prodhan,'.fun_gp($val_gp['gp_id_pk']).' Gram Panchayat under, '.fun_block($val_gp['block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
	
	
}

$pdf_fetch_DT =$db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' and district_level='ID' ");

 foreach($pdf_fetch_DT as  $val){
	 
	 if($val['pre_gp_id_fk']!='' && $val['pre_ps_id_fk']=='0')
	{

$mpdf->WriteHTML('<li>Sri. '.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].' of '.fun_gp($val['pre_gp_id_fk']).' GP under '.fun_block($val['pre_block_id_fk']).' Dev. Block for compliance</li>',2);

	}
	else if($val['pre_ps_id_fk']!='')
	{
		$mpdf->WriteHTML('<li>Sri. '.$val['emp_first_name'].'&nbsp;'.$val['emp_second_name'].'&nbsp;'.$val['emp_last_name'].' of '.fun_ps($val['pre_ps_id_fk']).' Panchayat Samity for compliance</li>',2);

	}
 }
/*$db=new database();
		$check_trasfer_gp =$db->fetch_table(" Select pre_block_id_fk,transfer_block_id_fk FROM intra_pri_district_transfer  WHERE application_id = '".$id."' and status='2' and district_level='ID' and  CAST(pre_block_id_fk as text)= CAST(transfer_block_id_fk as text) ");*/
		
		
		/*if(count ($check_trasfer_gp)>='1')
		{

foreach($check_trasfer_gp as  $val_t){
	
	$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val_t['pre_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
	
	


$db=new database();

		$check_trasfer_pre =$db->fetch_table(" Select pre_block_id_fk,transfer_block_id_fk FROM intra_pri_district_transfer  WHERE application_id = '".$id."' and status='2' and district_level='ID' and  pre_block_id_fk!='".$val_t['pre_block_id_fk']."' ");

foreach($check_trasfer_pre as  $val_p){
	
	$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val_p['pre_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
	
}	
	$db=new database();

		$check_trasfer_bl =$db->fetch_table("Select pre_block_id_fk,transfer_block_id_fk FROM intra_pri_district_transfer  WHERE application_id = '".$id."' and status='2' and district_level='ID' and  transfer_block_id_fk!='".$val_t['transfer_block_id_fk']."' ");

foreach($check_trasfer_bl as  $val_b){
	
	$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val_b['transfer_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
	
	
}

}

}
else
{
	

	$check =$db->fetch_table(" Select pre_block_id_fk,transfer_block_id_fk FROM intra_pri_district_transfer  WHERE application_id = '".$id."' and status='2' and district_level='ID' and  CAST(pre_block_id_fk as text)!= CAST(transfer_block_id_fk as text)  ");
		foreach($check as  $b){
$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($b['transfer_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);

$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($b['pre_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
}
}*/

/*$db=new database();
		$check_pre_gp =$db->fetch_table(" SELECT pre_gp_id_fk,pre_block_id_fk, COUNT(*)
FROM intra_pri_district_transfer WHERE application_id = '".$id."' and status='2' and district_level='ID' GROUP BY pre_gp_id_fk,pre_block_id_fk
HAVING COUNT(*) > 1 ");

foreach($check_pre_gp as  $val_t){
	
	echo $pre_block=$val_t['pre_block_id_fk']; 
	
	$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val['pre_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
	
}*/


//die;
 /*foreach($pdf_fetch_DT as  $val){
//echo 233;

if($val['transfer_gp_id_fk']!='' && $val['transfer_gp_id_fk']!='0')
	{
		
}

		
		
		
$mpdf->WriteHTML('<li>The Block Development Officer,'.fun_block($val['pre_block_id_fk']).' Dev. Block, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);
		

	}*/
	
/*$mpdf->WriteHTML('<li>The Executive officer,___________________________ Panchay Samiti, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>',2);*/




$mpdf->WriteHTML('<li>The Treasury Officers,_______________________________ Treasury, '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']).'&nbsp;'.'</li>


</ol>
<table style="margin-top:14%;font-size:9pt"> 
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
<td align="center">'.$officer_desig[0]['designation'].' of '.fun_district($pdf_fetch_DT[0]['pre_district_id_fk']). '</td>
</tr>
</table>
</body>
</html>',2);


$mpdf->Output('order_district_transfer'.'.pdf','I');


$mpdf->SetWatermarkImage('./img/biswa_bangla_1.png',1,'',array(10,65));
$mpdf->showWatermarkImage = true;
$mpdf->watermarkImageAlpha = 0.15;




?>