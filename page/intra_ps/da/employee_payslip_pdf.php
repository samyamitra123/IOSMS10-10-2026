<?php

ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';

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
function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
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



$monthno=$_REQUEST['year'].$_REQUEST['month'];
if($_REQUEST['month']=='01'){
$Month='January';
}
else if($_REQUEST['month']=='02'){
$Month='February';
}
else if($_REQUEST['month']=='03'){
$Month='March';
}
else if($_REQUEST['month']=='04'){
$Month='April';
}
else if($_REQUEST['month']=='05'){
$Month='May';
}
else if($_REQUEST['month']=='06'){
$Month='June';
}
else if($_REQUEST['month']=='07'){
$Month='July';
}
else if($_REQUEST['month']=='08'){
$Month='August';
}
else if($_REQUEST['month']=='09'){
$Month='September';
}
else if($_REQUEST['month']=='10'){
$Month='October';
}
else if($_REQUEST['month']=='11'){
$Month='November';
}
else if($_REQUEST['month']=='12'){
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


$ropa_status=$_REQUEST['ropa'];
//var_dump($ropa_status); die;

if($ropa_status ==0 || $ropa_status== ''){
	
	$emp_id_const_db=$db->fetch_table("Select emp_first_name,emp_second_name,emp_pay_in_payband,
    emp_pay_scale,emp_group,emp_pay_band,emp_pan_no,emp_last_name,emp_id_const,emp_desig 
    from 
    prd_employee_master
WHERE emp_id_pk='".$crypto->decode($_REQUEST['id'],4)."' and emp_status in('1','9','2')  ");

}
else{

$emp_id_const_db=$db->fetch_table("Select emp_first_name,emp_second_name,emp_pay_in_payband,
    emp_pay_scale,emp_group,emp_pay_band,emp_pan_no,emp_last_name,emp_id_const,emp_desig 
    from 
    prd_employee_master
WHERE emp_id_pk='".$crypto->decode($_REQUEST['id'],4)."' and emp_status in('1','9','2')  ");
}


	$Query = "SELECT 
										  sal.bankname,
										  sal.accountno,
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
										  tch.ropa_level,
										  sal.lock_status,
										  ps.ps_name,
										  sal.hra_deduction,
										  sal.requisition_type,
										  tch.emp_pan_no
   FROM prd_monthly_salary_archive_final as sal LEFT JOIN prd_employee_master as tch ON sal.emp_id_fk = tch.emp_id_pk 
    LEFT JOIN prd_location_master_panchayat_samiti as ps ON tch.ps_id_fk=ps.ps_id_pk
    WHERE
						 	            sal.emp_id_fk='".$crypto->decode($_REQUEST['id'],4)."'
						  				AND tch.emp_status in('1','9','2') 
						  				AND sal.status_flag IN ('3','4') 
										AND salary_monthyear='".$monthno."'
									    AND sal.is_saved='1'
									    AND sal.delete_status = '1'
										AND sal.requisition_type in ('1001','1003')
										AND sal.ropa_status='".$ropa_status."'
										ORDER by sal.archive_final_pk DESC LIMIT 1 OFFSET 0
								";
   //print_r($Query); exit;
   $key= $db->fetch_table($Query);								
  // print_r($key); exit;
if($key[0]['requisition_type']=='1001')
{
	$bill_details_fetch=$db->fetch_table(" SELECT bill_no, bill_entry_time FROM prd_block_bill_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".$monthno."' AND requisition_type='1001' AND status='1' AND ropa_status='".$ropa_status."' ");
}
elseif($key[0]['requisition_type']=='1003')
{
	$bill_details_fetch=$db->fetch_table(" SELECT bill_no, bill_entry_time FROM prd_block_bill_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".$monthno."' AND requisition_type='1003' AND status='1' AND ropa_status='".$ropa_status."' ");
}
if(count($key)>0){
	

$msg = $key[0]['emp_id_fk'];

$circle = $db->fetch_table("
		select ps.ps_name,dist.district_name from prd_location_master_district dist
        INNER JOIN prd_location_master_panchayat_samiti ps on dist.district_id_pk=ps.district_id_fk
		where ps.ps_id_pk='".$_SESSION['location']['ps_id']."'
");

		


$amt_net_total=number_to_words($key[0]['net']);

 $total_ern=$key[0]['pay_payband']+$key[0]['tch_grade_pay']+$key[0]['da']+$key[0]['hra']+$key[0]['ma']+$key[0]['conv_allow']+$key[0]['hill_allowance']+$key[0]['interim_relief'];

 $total_deduct=$key[0]['gpf']+$key[0]['pf_loan']+$key[0]['p_tax']+$key[0]['i_tax']+$key[0]['gsli']+$key[0]['overdrawn']+$key[0]['cooperative_loan']+$key[0]['festival_loan']+$key[0]['hbl_loan']+$key[0]['hra_deduction'];
  
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
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">Panchayats & Rural Development Department </p>
								<p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">'. $circle[0]['ps_name'].",".$circle[0]['district_name'].'</p>
							
								
							</td>
						<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><!--<img src="'.$config['base_url'].'page/qrcode/image.php?msg='.urlencode($msg).'" alt="generation qr-code" style="border: solid 1px black;width:50px;height:50px;">--></div></td>
						</tr>
					</table>
					
					',2);
					
$mpdf->WriteHTML('<div class="segment">
						<p style="text-align:center;font-size:12px;margin-top:0.5%;font-weight:bold;">PAY SLIP FOR THE MONTH OF '.strtoupper($Month).",".$_REQUEST['year'].'</p>
						<table width="100%" style="font-size:12px;padding-top:3%">
						<tr>
				   		<td><b>EMPLOYEE NAME:</b> '.$emp_id_const_db[0]['emp_first_name']." ".$emp_id_const_db[0]['emp_second_name']." ".$emp_id_const_db[0]['emp_last_name'].'</td>
				    	<td><b>EMPLOYEE ID:</b> '.$emp_id_const_db[0]['emp_id_const'].'</td>
				   </tr>
						<tr> 
						<td><b>DESIGNATION: </b>'.fun_common($emp_id_const_db[0]['emp_desig']).'</td>',2);
							$mpdf->WriteHTML('<td><b>BILL NO, DATED:</b> '.$bill_details_fetch[0]['bill_no'].', '.date_frmt($bill_details_fetch[0]['bill_entry_time']).'</td>',2);
							
													
$mpdf->WriteHTML('</tr>
				   <tr>',2);
				   if($ropa_status==2 || $ropa_status==0){ 
				   $mpdf->WriteHTML('<td><b>PAY BAND:</b> '.fun_common($key[0]['emp_pay_band']).' ('.get_pay_scale($emp_id_const_db[0]['emp_pay_scale']).')'.'</td>',2);
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
									  if($key[0]['hra']!='0'){
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">HRA</td>
                                        <td align="right">'.$key[0]['hra'].'</td>
                                      </tr>',2);
									  }if($key[0]['ma']!='0'){
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">MA</td>
                                        <td align="right">'.$key[0]['ma'].'</td>
                                      </tr>',2);
									  }if($key[0]['conv_allow']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">CA</td>
                                        <td align="right">'.$key[0]['conv_allow'].'</td>
                                      </tr>',2);
									  }if($key[0]['interim_relief']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">IR</td>
                                        <td align="right">'.$key[0]['interim_relief'].'</td>
                                      </tr>',2);
									  }
									  if($key[0]['hra']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['ma']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['interim_relief']=='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['conv_allow']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  
                                $mpdf->WriteHTML('</table>
                              </td>
                              <td style="padding-left: 5px; padding-right: 2%; border-right:1px solid #666;width:25%">',2);
                              	$mpdf->WriteHTML('<table style="font-size:12px;" width="150px;">',2);
								  if($key[0]['gpf']!='0'){
                                     $mpdf->WriteHTML(' <tr>
                                        <td style="width:50%;"><strong>GPF</strong></td>
                                        <td align="right" style="width:50%;">'.$key[0]['gpf'].'</td>
                                      </tr>',2);
								  }
								  if($key[0]['pf_loan']!='0'){
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">PF LOAN</td>
                                        <td align="right">'.$key[0]['pf_loan'].'</td>
                                      </tr>',2);
								  }if($key[0]['p_tax']!='0'){
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">PTX</td>
                                        <td align="right">'.$key[0]['p_tax'].'</td>
                                      </tr>',2);
									  }if($key[0]['i_tax']!='0'){
                                      $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">IT</td>
                                        <td align="right">'.$key[0]['i_tax'].'</td>
                                      </tr>',2);
									  }if($key[0]['gsli']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">GSLI</td>
                                        <td align="right">'.$key[0]['gsli'].'</td>
                                      </tr>',2);
									  }if($key[0]['overdrawn']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">OVERDRAWN</td>
                                        <td align="right">'.$key[0]['overdrawn'].'</td>
                                      </tr>',2);
									  }
									  if($key[0]['festival_loan']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <!--<td style="font-weight:bold">FESTIVAL ADVANCE RECOVERY</td>-->
                                        <td style="font-weight:bold">FAR</td>
                                        <td align="right">'.$key[0]['festival_loan'].'</td>
                                      </tr>',2);
									  }
									  if($key[0]['hra_deduction']!='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">HRA DEDUCTION</td>
                                        <td align="right">'.$key[0]['hra_deduction'].'</td>
                                      </tr>',2);
									  }
									  if($key[0]['gpf']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['pf_loan']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['cpf_deduct']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['p_tax']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['i_tax']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['gsli']=='0'){
										  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['festival_loan']=='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  if($key[0]['hra_deduction']=='0'){
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  }
									  $mpdf->WriteHTML('<tr>
                                        <td style="font-weight:bold">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                      </tr>',2);
									  
                                $mpdf->WriteHTML('</table>
                              </td>
                              
                              <td style="text-align:right;padding-right: 2%; border-right:1px solid #666;width:25%">
                              </td>
                              
                              <td style="text-align:right;padding-right: 2%;width:25%">
                              </td>
                          </tr>
                        
						  
						  <tr>
						  <th style="text-align:left;border-top:1px solid #666;">Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$total_ern.'</th>
						  <td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$total_deduct.'</td>
						  <td style="text-align:left;border-top:1px solid #666;"></td>
						  <td style="text-align:left;border-top:1px solid #666;font-weight:bold;"></td>
				
				
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left;border-top:1px solid #666;">GROSS PAY: '.$key[0]['gross_salary'].'</th>
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left;border-top:1px solid #666;">NET PAY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$key[0]['net'].' (Rupees &nbsp;<b>'.$amt_net_total.'</b>&nbsp;only)</th>
						  </tr>
						  <tr>
						  <th colspan="4" style="text-align:left">Transferred to '.fun_bank($key[0]['bankname']).' Account no '.$key[0]['accountno']. '&nbsp;&nbsp;&nbsp;&nbsp; IFSC Code '.$key[0]['bank_ifsc'].'</th>
						  </tr>
						  </table>
						  </div>
						  <!--<div>
						  <p align="right">_______________________________________________</p>
						  <p align="right">Signature &amp; Stamp Of HOI</p>
						  <p align="right">'.$key[0]['gp_name'].'</p>
						  <p align="right">'.$circle[0]['circle_name'] .",".$dpsc[0]['district_name'].'</p>
						  </div>-->
						  <div>
						  <br />
						  <div>
						  <p style="font-size:11px;">GP: Grade Pay, DA: Dearness Allowance, HRA: House Rent Allowance, MA: Medical Allowance, CA: Conveyance Allowance, </p>
						  
						  <p style="font-size:11px;">GSLI: Group Savings Linked Insurance, FAR: Festival Advance Recovery .</p>
						  </div>
						  <br />
						  <p style="padding-top:55%"><b>Disclaimer:</b>&nbsp;This is a computer generated Pay Slip and hence does not require any signature.</p>
						  

						  </div>
							',2);
$mpdf->Output('PAYSLIP FOR '.$emp_id_const_db[0]['emp_first_name']." ".$emp_id_const_db[0]['emp_second_name']." ".$emp_id_const_db[0]['emp_last_name'].'.pdf','D');
}else{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>No data found.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_ps/da/employee_payslip.php');
	exit(0);
}
?>