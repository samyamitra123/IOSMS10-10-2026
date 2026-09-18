<?php

/*
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

ob_start();
session_start();


require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
//---------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------


$db = new database();



$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	");
	
function fun_common( $code){
	$db = new database();
	$emp_desg_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master where code='".$code."'");


	return $emp_desg_data[0]['description'];
		
		
	}
function date_frmt($original_date){
	if($original_date =="0001-01-01" || $original_date =="1970-01-01"){
		return "---";
	}
	else{
		//return $newDate = date("d-m-Y", strtotime($original_date));
		$old=explode("-",$original_date);
        $new=$old[2]."-".$old[1]."-".$old[0];
		return $new;
	}
}
	function fun_desig($code)
	{
		$db = new database();
		$emp_desg_data = $db->fetch_table(" SELECT designation_id, designation_name FROM zpemp_emp_desig_master where designation_id='".$code."'");
		return $emp_desg_data[0]['designation_name'];
	}


 $monthno=$crypto->decode($_REQUEST['year'],4).$crypto->decode($_REQUEST['month'],4); 
if($crypto->decode($_REQUEST['month'],4)=='01'){
$Month='January';
}
else if($crypto->decode($_REQUEST['month'],4)=='02'){
$Month='February';
}
else if($crypto->decode($_REQUEST['month'],4)=='03'){
$Month='March';
}
else if($crypto->decode($_REQUEST['month'],4)=='04'){
$Month='April';
}
else if($crypto->decode($_REQUEST['month'],4)=='05'){
$Month='May';
}
else if($crypto->decode($_REQUEST['month'],4)=='06'){
$Month='June';
}
else if($crypto->decode($_REQUEST['month'],4)=='07'){
$Month='July';
}
else if($crypto->decode($_REQUEST['month'],4)=='08'){
$Month='Auguest';
}
else if($crypto->decode($_REQUEST['month'],4)=='09'){
$Month='September';
}
else if($crypto->decode($_REQUEST['month'],4)=='10'){
$Month='October';
}
else if($crypto->decode($_REQUEST['month'],4)=='11'){
$Month='November';
}
else if($crypto->decode($_REQUEST['month'],4)=='12'){
$Month='December';
}

function get_pay_scale($pay_code)
{
	
	$db  = new database();
	$data = $db->fetch_table("SELECT payscale_id, payscale_range, payscale_code
  FROM prd_dise_payscale_master WHERE payscale_code='".$pay_code."'");
  return $data[0]['payscale_range'];

}

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
      {  $result .= " and ";
      } 
     if ($Dn < 2)
      {  $result .= $ones[$Dn * 10 + $n];
      }
      else
         {  $result .= $tens[$Dn];
          if ($n)
          {  $result .= "-" . $ones[$n];
          }
         }
    }
   if ($cn)
    {
     if (!empty($result))
      {  $result .= ' and ';
      }
       $title = $cn==1 ? 'paisa ': 'paisa';
       $result .= strtolower(number_to_words($cn)).' '.$title;
    }
   if (empty($result))
    {  $result = "zero"; } 
  return $result;
}

//$ropa_status=$crypto->decode($_REQUEST['ropa'],4);

//var_dump($ropa_status); die;

/*if($ropa_status=='0' || $ropa_status=='')
{*/
$db = new database();
	//$emp_id='PE2018022524';
	
	 $emp_id=$_SESSION['user_info']['stake_user'];
	
	
	$emp_id_const_db=$db->fetch_table("Select emp_id_pk,emp_first_name,emp_second_name,emp_pay_in_payband,emp_pay_scale,emp_group,emp_pay_band,emp_pan_no,emp_last_name,emp_id_const,emp_desig,gp_id_fk,ps_id_fk,zp_id_fk from 
	prd_employee_master
WHERE emp_id_const='".$emp_id."' and emp_status in('1','9','2')");

//}
/*else
{*/
/*$emp_id_const_db=$db->fetch_table("Select emp_first_name,emp_second_name,emp_pay_in_payband,emp_pay_scale,emp_group,emp_pay_band,emp_pan_no,emp_last_name,emp_id_const,emp_desig from prd_employee_master
WHERE emp_id_pk='".$crypto->decode($_REQUEST['id'],4)."' and emp_status in('1','9','2')");*/
if($emp_id_const_db[0]['gp_id_fk']!='0')
{
	//echo 12; die;
	$emp_desig=fun_common($emp_id_const_db[0]['emp_desig']);
}
else if($emp_id_const_db[0]['ps_id_fk']!='0')
{
	//echo 13; die;
	$emp_desig=fun_common($emp_id_const_db[0]['emp_desig']);
}
else if($emp_id_const_db[0]['zp_id_fk']!='0')
{
	//echo 14; die;
	$emp_desig=fun_desig($emp_id_const_db[0]['emp_desig']);
}

$check_data=$db->fetch_table("Select count(emp_id_fk) AS cnt from prd_monthly_salary_archive_final
WHERE salary_monthyear='".$monthno."' AND status_flag in( '3','4') AND emp_id_fk='".$emp_id_const_db[0]['emp_id_pk']."'");
//print_r($check_data); exit;
if($check_data[0]['cnt']>0){


$key= $db->fetch_table(" select
								
										  sal.bankname,
										  sal.accountno,
										  sal.block_code,
										  sal.zp_id_fk,
										  sal.ps_id_fk,
										  sal.gp_id_fk,
										  sal.basic,
										  sal.da,
										  sal.hra,
										  sal.ma,
										  sal.cpf,
										  sal.pf_loan,
										  sal.p_tax,
										  sal.i_tax,
										  sal.net,
										  sal.bank_ifsc,
										  sal.sal_source,
										  sal.spl_pay,
										  sal.pf_deduct,
										  sal.code,
										  sal.spl_alo,
										  sal.status_flag,
										  sal.salary_monthyear,
										  sal.category_id,
										  sal.block_code,
										  sal.emp_id_fk,
										  sal.pay_payband,
										  sal.tch_grade_pay,
										  sal.hill_allowance,
										  sal.gpf,
										  sal.gross_salary ,
										  sal.is_saved,
										  sal.conv_allow,
										  sal.overdrawn,
										  sal.salary_type,
										  sal.cause ,
										  sal.gsli,
										  sal.delete_status,
										  sal.consolidated_pay,
										  sal.other_deduction_cause,
										  sal.other_deduction,
										  sal.cooperative_loan,
										  sal.hbl_loan,
										  sal.festival_loan,
										  sal.cooperative_loan_cause,
										  sal.hbl_loan_cause,
										  sal.festival_loan_cause,
										  sal.part_salary_cause,
										  sal.no_salary_cause,
										  sal.interim_relief,
										  sal.lock_status,
										  sal.ropa_level,
										  sal.allowance,
										  sal.ropa_status,
										  sal.hra_deduction,
										  sal.total_loan_deduction,
										  sal.other_loan_deduction
										  
   FROM prd_monthly_salary_archive_final as sal 
    WHERE
										sal.emp_id_fk='".$emp_id_const_db[0]['emp_id_pk']."'
						 
						  				AND sal.status_flag in( '3','4') 
						 			    AND sal.delete_status='1'
										AND sal.salary_monthyear='".$monthno."'
									        AND sal.is_saved='1'
											
    ");
	
	
}


//echo $key[0]['ps_id_fk']; die;
if($key[0]['gp_id_fk']!='0' && $key[0]['gp_id_fk']!='')
{
	
	//please add sal.block_code,
										 // sal.zp_id_fk,
										  //sal.ps_id_fk,
										  //sal.gp_id_fk, above query////////////////////////////////////////////
	$db = new database();
	$stake= $db->fetch_table("select block_name from prd_location_master_block where block_code='".$key[0]['block_code']."'");
	$gp_stake= $db->fetch_table("select gp_name from prd_location_master_gp where gp_id_pk='".$key[0]['gp_id_fk']."'");
	$stake_name=  $gp_stake[0]['gp_name'].' GP'.'UNDER'.$stake[0]['block_name'].' BLOCK';
	
}
else if($key[0]['ps_id_fk']!='0' && $key[0]['ps_id_fk']!='')
{
	//echo 122; die;
	$db = new database();
	
	$stake= $db->fetch_table("select ps_name from prd_location_master_panchayat_samiti where ps_id_pk='".$key[0]['ps_id_fk']."'");
	$stake_name=$stake[0]['ps_name'].' PANCHAYAT SAMITI';
}
else
{
	//echo 133; die;
	$db = new database();
	$stake= $db->fetch_table("select district_name from prd_location_master_district where district_id_pk='".$key[0]['zp_id_fk']."'");
	$stake_name=$stake[0]['district_name'].' ZILLA PARISHAD';;
}


$ropa_status=$key[0]['ropa_status'];
if(count($key)>0){
	

$msg = $key[0]['emp_id_fk'];

$circle = $db->fetch_table("
		select gp.gp_name,block.block_name,dist.district_name from prd_location_master_district dist
        INNER JOIN prd_location_master_block block on dist.district_id_pk=block.district_id_fk
		INNER JOIN prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk
		where gp.gp_code='".$_SESSION['location']['gpcode']."'


		");

		


$amt_net_total=number_to_words($key[0]['net']);

 $total_ern=$key[0]['pay_payband']+$key[0]['tch_grade_pay']+$key[0]['da']+$key[0]['hra']+$key[0]['ma']+$key[0]['conv_allow']+$key[0]['hill_allowance']+$key[0]['interim_relief']+$key['allowance']+$key['consolidated_pay'];

 $total_deduct=$key[0]['gpf']+$key[0]['p_tax']+$key[0]['i_tax']+$key[0]['gsli']+$key[0]['overdrawn']+$key[0]['cooperative_loan']+$key[0]['festival_loan']+$key[0]['hbl_loan']+$key[0]['hra_deduction'];
  
define("_MPDF_TEMP_PATH", '../../../locker/temp/');
		include ('../../../includes/third-party/mpdf/mpdf.php');

//$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_employee_details.css');
$stylesheet='';
$mpdf=new mPDF();

// This sets sufficient rights for the user to modify your annotations
//$mpdf->SetUserRights(false, '/Create/Delete/Modify/Copy/Import/Export');

// If you want to encrypt the file, include the necessary permissions
//$mpdf->SetProtection(array(), 'userpass', 'nicpass');

//------------------------------------------------------------------------------------------------------



$mpdf->WriteHTML($stylesheet,1);

//Header and footer
$mpdf->SetFooter('priemp.wbprd.gov.in|Page-{PAGENO}|Date of Generation: '.date('jS \of F Y'));

//PDF header
$mpdf->WriteHTML('
					
					<table style="margin-top:-5%">
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">GOVERNMENT OF WEST BENGAL</p>
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">'.$stake_name.' </p>
								
							
								
							</td>
						<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><!--<img src="'.$config['base_url'].'page/qrcode/image.php?msg='.urlencode($msg).'" alt="generation qr-code" style="border: solid 1px black;width:50px;height:50px;">--></div></td>
						</tr>
					</table>
					
					',2);
					
$mpdf->WriteHTML('<div class="segment">
						<p style="text-align:center;font-size:12px;margin-top:0.5%;font-weight:bold;">PAY SLIP FOR THE MONTH OF '.strtoupper($Month).",".$crypto->decode($_REQUEST['year'],4).'</p>
						<table width="100%" style="font-size:12px;padding-top:3%">
						<tr>
				   		<td><b>EMPLOYEE NAME:</b> '.$emp_id_const_db[0]['emp_first_name']." ".$emp_id_const_db[0]['emp_second_name']." ".$emp_id_const_db[0]['emp_last_name'].'</td>
				    	<td><b>EMPLOYEE ID:</b> '.$emp_id_const_db[0]['emp_id_const'].'</td>
				   </tr>
						<tr> 
						<td>DESIGNATION: </b>'.$emp_desig.'</td>',2);
						
						
						
							$mpdf->WriteHTML('<td><b> GROUP: </b>'.fun_common($emp_id_const_db[0]['emp_group']).'</td>',2);
							
													
$mpdf->WriteHTML('</tr>
				   <tr>',2);
				  if($ropa_status==2 || $ropa_status==0){ 
				   $mpdf->WriteHTML('<td><b>SCALE:</b> '.fun_common($emp_id_const_db[0]['emp_pay_band']).'('.get_pay_scale($emp_id_const_db[0]['emp_pay_scale']).')'.'</td>',2);
				  } 
$mpdf->WriteHTML('<td><b>PAN:</b> '.$emp_id_const_db[0]['emp_pan_no'].'</td>
				   </tr>
					</table>
					<br><table width="100%" style="border: 1px solid #666; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;font-size: 12px;vertical-align: bottom;">
						  <tr>
						  <th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">EARNING(Rs)</th>
						  <th style="border-bottom:1px solid #666;border-right:1px solid #666; vertical-align:middle;">DEDUCTION(Rs)</th>
						  <th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">RECOVERIES OF LOAN(Rs)</th>
						  <th style="border-bottom:1px solid #666; vertical-align:middle;">OUT/ACCT.DED (Rs)</th>
						  </tr>
						  
						  <tr>
                              <td style="padding-left: 5px; padding-right: 2%; border-right:1px solid #666;width:25%">',2);
								  $mpdf->WriteHTML('<table style="font-size:12px;" width="500px;">',2);
								  
						if($key['emp_cosolidated_pay']!='0' && $key['emp_cosolidated_pay']!='')
							{
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">CONSOLITED PAY</td>
								<td align="right">'.$key['consolidated_pay'].'</td></tr>',2);
								
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
							}
							else
							{
								  if($key[0]['pay_payband']!='0'){
                                     $mpdf->WriteHTML(' <tr>
                                        <td><strong>PAY</strong></td>
                                        <td align="right">'.$key[0]['pay_payband'].'</td>
                                      </tr>',2);
								  }
								  if($ropa_status==2 || $ropa_status==0){ 
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">GP</td>
                                        <td align="right">'.$key[0]['tch_grade_pay'].'</td>
                                      </tr>',2);
								  }
								  else if($ropa_status==1){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">LEVEL</td>
                                        <td align="right">'.$key[0]['ropa_level'].'</td>
                                      </tr>',2);
									  
								  }
								  
                                  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">DA</td>
                                        <td align="right">'.$key[0]['da'].'</td>
                                      </tr>',2);
									  
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">HRA</td>
                                        <td align="right">'.$key[0]['hra'].'</td>
                                      </tr>',2);
									  
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">MA</td>
                                        <td align="right">'.$key[0]['ma'].'</td>
                                      </tr>',2);
									 if($key[0]['conv_allow']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">CA</td>
                                        <td align="right">'.$key[0]['conv_allow'].'</td>
                                      </tr>',2);
									 }
								   if($key[0]['hill_allowance']!='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">HILL ALLOW</td>
                                        <td align="right">'.$key[0]['hill_allowance'].'</td>
                                      </tr>',2);
									  }
									  if($key[0]['allowance']!='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">AA</td>
                                        <td align="right">'.$key[0]['allowance'].'</td>
                                      </tr>',2);
									  }
							}
                                $mpdf->WriteHTML('</table>
                              </td>
                              <td style="padding-left: 5px; padding-right: 2%; border-right:1px solid #666;width:25%">',2);
                              	$mpdf->WriteHTML('<table style="font-size:12px;" width="150px;">',2);
						  if($key['emp_cosolidated_pay']!='0' && $key['emp_cosolidated_pay']!='')
							{
								
								$mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">PT</td>
                                        <td align="right">'.$key[0]['p_tax'].'</td>
                                      </tr>',2);
									  
							    $mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								
							}
							else
							{
								
								
                                     $mpdf->WriteHTML(' <tr>
									 
                                        <td style="width:50%;"><strong>GPF</strong></td>
                                        <td align="right" style="width:50%;">'.$key[0]['gpf'].'</td>
                                      </tr>',2);
								 
								  
								  
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">PT</td>
                                        <td align="right">'.$key[0]['p_tax'].'</td>
                                      </tr>',2);
									 
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">IT</td>
                                        <td align="right">'.$key[0]['i_tax'].'</td>
                                      </tr>',2);
									 
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">GSLI</td>
                                        <td align="right">'.$key[0]['gsli'].'</td>
                                      </tr>',2);
									  if($key[0]['overdrawn']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">OVERDRAWN</td>
                                        <td align="right">'.$key[0]['overdrawn'].'</td>
                                      </tr>',2);
									  }
									
									  if($key[0]['festival_loan']!='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">FL</td>
                                        <td align="right">'.$key[0]['festival_loan'].'</td>
                                      </tr>',2);
									  }
									   
									  if($key[0]['hra_deduction']!='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">HRD</td>
                                        <td align="right">'.$key[0]['hra_deduction'].'</td>
                                      </tr>',2);
									  }
									 
							}
									  
									  
									 
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
										
                                      </tr>',2);
									  
                               $mpdf->WriteHTML('
							</table>
						</td>
						
						<td style="text-align:right;padding-right: 2%; border-right:1px solid #666;width:25%">
							<table style="font-size:12px;" width="150px;">',2);
							if($key[0]['pf_loan']!='0'  && $key[0]['pf_loan']!='' )
							{
							$mpdf->WriteHTML('<tr><td style="font-weight:bold">PF RECOVERY</td><td align="right">'.$key['pf_loan'].'</td></tr>',2);
							$pf_loan=$key[0]['pf_loan'];
							}
							else
							{
							
							$mpdf->WriteHTML('<tr>
							<td style="font-weight:bold">PF RECOVERY</td><td align="right">'.$key[0]['total_loan_deduction'].'
							</td>
							</tr>',2);
							$pf_loan=$key[0]['total_loan_deduction'];
							
							}
		
		$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								
									
								
								
							$mpdf->WriteHTML('
							</table>
						</td>
						
					
						
						<td style="text-align:right;padding-right: 2%; border-right:1px solid #666;width:25%">
							<table style="font-size:12px;" width="150px;">',2);
								
								
		
		
		$mpdf->WriteHTML('<tr><td style="font-weight:bold">OAD</td><td align="right">'.$key[0]['other_loan_deduction'].'</td></tr>',2);
		
		$oad=$key[0]['other_loan_deduction'];
		
		$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
						
						
						$mpdf->WriteHTML('
							</table>
						</td>
						
						
						
                        
						  
						  <tr>
						  <th style="text-align:left;border-top:1px solid #666;">Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$total_ern.'</th>
						  <td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$total_deduct.'</td>
						  <td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$pf_loan.'</td>
						<td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$oad.'</td>
				
				
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left;border-top:1px solid #666;">GROSS PAY: '.$key[0]['gross_salary'].'</th>
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left;border-top:1px solid #666;">NET PAY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$key[0]['net'].' (Rupees &nbsp;<b>'.$amt_net_total.'</b>&nbsp;only)</th>
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left">Transferred to '.$key[0]['bankname'].' Account no '.$key[0]['accountno']. '&nbsp;&nbsp;&nbsp;&nbsp; IFS Code '.$key[0]['bank_ifsc'].'</th>
						  </tr>
						  </table>
						  </div>
						 
						  <div>
						  <br />
						  <div>
						
						  
						  <p style="font-size:11px;">GP: Grade Pay, DA: Dearness Allowance, HRA: House Rent Allowance, MA: Medical Allowance, CA: Conveyance Allowance, AA: Administrative Allowance </p>
					<p style="font-size:11px;">GSLI: Group Savings Linked Insurance, OVD: Overdrawn, OAD: Out of Account Deduction.</p>
						  </div>
						  <br />
						  <p style="padding-top:55%"><b>Disclaimer:</b>&nbsp;This is a computer generated Pay Slip and hence does not require any signature.</p>
						  </div>
							',2);
$mpdf->Output('PAYSLIP FOR '.$emp_id_const_db[0]['emp_first_name']." ".$emp_id_const_db[0]['emp_second_name']." ".$emp_id_const_db[0]['emp_last_name'].'.pdf','D');
}else{
	$_SESSION['msg']='<div id="error">No Data Found.</div>';
	header('Location:'.$config['base_url'].'page/intra_prd_gp/employee_payslip.php');
	exit(0);
}
?>