<?
//echo $_GET['month']."  ".$_GET['year'];  die;
 $monthyear=$_GET['year'].$_GET['month']; 

//echo $monthyear; die;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Salary_Data.xls');
header("Content-Transfer-Encoding: binary");
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../all_function/fun_store/zp_ps_gp_function.php';

//$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
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

function GetMonthString($n)
{
$n=(int)$n;
    $timestamp = mktime(0, 0, 0, $n);
    
    return date("F", $timestamp);
}

function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
				return $key['description'];
			}
		}
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

/*function fun_bank($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
	return $dist_data2[0]['bank_name'];
}*/	
//-------------------------------------------------------QUERY-----------------------------------------------------------------
$crypto = new cryptography();
$fun_store=new zp_ps_gp_class();

 $employee_type=$crypto->decode($_GET['type'],4); 
	//$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
	$db = new database();
	
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	 $requisition_type=$requisition[0]['code'];

	 $desig_data = $db->fetch_table("
		SELECT designation_id, designation_name
		FROM zpemp_emp_desig_master");

							
	if($monthyear==date('Ym'))
	{
			$tch= $db->fetch_table("SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.part_day, sal.total_loan_deduction,sal.gsli,sal.consolidated_pay,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,sal.other_loan_deduction,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_desig,
										tch.emp_last_name,
										tch.emp_pay_in_payband,
										tch.emp_id_pk,tch.emp_desig,tch.emp_id_const
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as tch
										ON sal.emp_id_fk =tch.emp_id_pk AND sal.zp_id_fk= tch.zp_id_fk
										WHERE
											tch.emp_status in('1')	 
											AND sal.zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND sal.zp_emp_type='".$employee_type."'
											AND sal.status_flag in('1','2','3','4') AND is_saved='1' AND sal.delete_status='1' AND salary_monthyear='".date('Ym')."'  AND requisition_type='".$requisition_type."' order by tch.emp_first_name ;
								");
	}
	else
	{
		
		$tch= $db->fetch_table("SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.part_day, sal.total_loan_deduction,sal.gsli,sal.consolidated_pay,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,sal.other_loan_deduction,
										tch.emp_first_name,
										tch.emp_desig,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_pay_in_payband,
										tch.emp_id_pk,tch.emp_desig
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as tch
										ON sal.emp_id_fk =tch.emp_id_pk AND sal.zp_id_fk= tch.zp_id_fk
										WHERE
											tch.emp_status in('1')	 
											AND sal.zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND sal.zp_emp_type='".$employee_type."'
											AND sal.status_flag in('1','2','3','4') AND is_saved='1' AND sal.delete_status='1' AND salary_monthyear='".$monthyear."'  AND requisition_type='".$requisition_type."' order by tch.emp_first_name ;
								");
	}		
$date = $tch[0]['latestupdate_time'];
$msg="";
if($tch[0]['status_flag'] == 1){ 
	$msg="Not finalized (Just Saved)";
}					
elseif($tch[0]['status_flag'] == 3){ 
	$msg="Requisition finalized by FC&CAO ";
} elseif($tch[0]['status_flag'] == 4){
	$msg="Requisition Locked by FC&CAO ";
}
if($employee_type=='366')
{
	$type_of_employee='GOVERNMENT EMPLOYEES';
}
else
{
	$type_of_employee='GRANT-IN-AID EMPLOYEES';
}

?>
<style>
.tr_style{ vertical-align:central;}
</style>


<table border="1" style="font-size:9px;">
   <tr>
      <th colspan="7" style="text-align:center;"><br /><br />DETAILED SALARY BILL OF ZILLA PARISHAD <?php echo $type_of_employee;?> UNDER <?= $_SESSION['location']['district_name']?> FOR THE MONTH OF <?= GetMonthString($_GET['month']).", ".$_GET['year'] ?><br /><br /></th>
   </tr>
  <tr>
    <th class="tr_width">SL</th>
    <!--<th scope="col">NAME OF THE ZP</th>-->
    <th>EMPLOYEE DETAILS</th>
   <!-- <th scope="col">DESIGNATION</th>-->
    <th >BANK DETAILS</th>
    <!--<th scope="col">ACCOUNT NO</th>
    <th scope="col">IFSC</th>-->
    <th >PAY AND ALLOWANCES</th>
    <th  style="border:none">GROSS</th>
    <th>DEDUCTIONS</th> 
    <!--<th>TOTAL DEDUCTION</th>-->
    <th  style="border:none">NET PAY</th>
  </tr>
  <? $cnt=1; 
$gross=$net=0;
$tot_conv_allow=$tot_payband=$tot_basic=$tot_da=$tot_hra=$tot_ma=$tot_ca=$tot_ha=$tot_cpf=$tot_gross=$tot_gpf=$tot_cpfdeduct=$tot_ptax=$tot_itax=$tot_od=$tot_gsli=$total_loan=$other_loan=$tot_net=0;
   if(count($tch)){foreach ($tch as $key) {
	   
	   
	   ?>
  <tr>
    <td class="tr_style" ><?= $cnt;?></td>
    <!--<td class="tr_style"><?= $_SESSION['location']['district_name']?></td>-->
    <td class="tr_style">EMPLOYEE NAME : <?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?><br/>DESIGNATION : <?= $fun_store->fun_desig($key['emp_desig'],$desig_data);?><br/>EMPLOYEE ID : <?= $key['emp_id_const'];?></td>
    <!--<td class="tr_style"><?= $fun_store->fun_desig($key['emp_desig'],$desig_data);?></td>-->
    <td class="tr_style">BANK NAME : <?= $fun_store->fun_bank($key['bankname'])?><br/>ACCOUNT NUMBER : <? $val="&nbsp;".$key['accountno']; echo $val; ?><br/>IFSC CODE : <?= $key['bank_ifsc']?></td>
    <!--<td class="tr_style"><? $val="&nbsp;".$key['accountno']; echo $val; ?></td>
    <td class="tr_style"><?= $key['bank_ifsc']?></td>-->
    <td>CONSOLIDATED PAY : <?= $key['consolidated_pay']?><br/>PAY IN PAY BAND : <?= $key['pay_payband']?><br/>GRADE PAY : <?= $key['tch_grade_pay']?><br/>DA : <?= $key['da']?><br/>HRA : <?= $key['hra']?><br/>MA : <?= $key['ma']?><br/>CONV ALLOW: <?= $key['conv_allow']?><br/>HILL ALLOW : <?= $key['hill_allowance']?><?php if($monthyear<='201812'){?><br/>INTERIM RELIEF : <?= $key['interim_relief']?><?php }?><br/><span  style="color:#CD1316;font-weight:bold;">OVERDRAWN : <?php if($key['overdrawn']=='0'){ echo $key['overdrawn'];}else{ '-'.$key['overdrawn'];}?><span/></td>
    <td class="tr_style" ><?= $key['gross_salary']-$key['overdrawn']?></td>
    <td >GPF : <?= $key['gpf']?><br/>P-TAX : <?= $key['p_tax']?><br/>I-TAX : <?= $key['i_tax']?><br/>GSLI : <?= $key['gsli']?><br/>HRA DEDUCTION/LICENCE FEES : <?= $key['hra_deduction']?><br/>FESTIVAL LOAN RECOVERY : <?= $key['festival_loan']?><br/>TOTAL LOAN DEDUCTION: <?= $key['total_loan_deduction'] ?><br/>OUT OF ACCOUNT DEDUCTION: <?= $key['other_loan_deduction'] ?></td> 
    <!--<td class="tr_style"><?= $key['gpf']+$key['p_tax']+$key['i_tax']+$key['gsli']+$key['hra_deduction']+$key['overdrawn']+$key['festival_loan']+$key['total_loan_deduction']+$key['other_loan_deduction']?></td>-->
    <td class="tr_style" ><?= $key['net']?></td>
  </tr>
 <? 
$tot_consolidated_pay+=$key['consolidated_pay'];
$tot_payband+=$key['pay_payband'];
$tot_basic+=$key['tch_grade_pay'];
$tot_da+=$key['da'];
$tot_hra+=$key['hra'];
$tot_ma+=$key['ma'];
$tot_ca+=$key['conv_allow'];
$tot_hill+=$key['hill_allowance'];
if($monthyear<='201812')
{
$tot_interim_relief+=$key['interim_relief'];
}
else
{
	$tot_interim_relief+=0;
}
$tot_gross+=$key['gross_salary'];
$tot_gpf+=$key['gpf'];
$tot_ptax+=$key['p_tax'];
$tot_itax+=$key['i_tax'];
$tot_gsli+=$key['gsli'];
$hra_deduction+=$key['hra_deduction'];
$tot_od+=$key['overdrawn'];
//$tot_ovd1+=$key['cooperative_loan'];
//$tot_ovd2+=$key['hbl_loan'];
$tot_ovd3+=$key['festival_loan'];
$total_loan+=$key['total_loan_deduction'];
$other_loan+=$key['other_loan_deduction'];
$tot_net+=$key['net'];	
 
$cnt++; } ?>
<tr style="text-align:center;font-weight:bold;">
	<td colspan="4" style="text-align:right;">TOTAL</td>
    <td><?= $tot_gross?></td>
    <td></td>
    <td><?= $tot_net?></td>
</tr> 



<? } else{ ?>
<tr style="border-color:#3E9B96;">
	<td colspan="7" style="color:red;font-weight:bold;border-color:#3E9B96; text-align:center;">No Data Found</td>
</tr>
<? } ?>
</table>
<table border="1" style="font-size:12px;">
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL CONSOLIDATED PAY</td>
    <td><?= $tot_consolidated_pay?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL PAY IN PAY BAND</td>
    <td><?= $tot_payband?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL GRADE PAY</td>
    <td><?= $tot_basic?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL DA</td>
    <td><?= $tot_da?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL HRA</td>
    <td><?= $tot_hra?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL MA</td>
    <td><?= $tot_ma?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL CONV. ALLOWANCE</td>
    <td><?= $tot_ca?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL HILL ALLOWANCE</td>
    <td><?= $tot_hill?></td>
</tr> 
<?php
if($monthyear<='201812')
{?>
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL INTERIM RELIEF</td>
    <td><?= $tot_interim_relief?></td>
</tr> 
<?php }?>
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL GPF</td>
    <td><?= $tot_gpf?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL P-TAX</td>
    <td><?= $tot_ptax?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL I-TAX</td>
    <td><?= $tot_itax?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL GSLI</td>
    <td><?= $tot_gsli?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL HRA DEDUCTION/LICENCE FEES</td>
    <td><?= $hra_deduction?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL OVERDRAWN</td>
    <td><?= $tot_od?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL FESTIVAL ADVANCE</td>
    <td><?= $tot_ovd3?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL LOAN DEDUCTION</td>
    <td><?= $total_loan?></td>
</tr> 
<tr style="text-align:center;font-weight:bold;">
	<td style="border:none;">&nbsp;</td>
	<td style="text-align:right;">TOTAL OUT OF ACCOUNT DEDUCTION</td>
    <td><?= $other_loan?></td>
</tr> 

</table>

<table >
<tr>
<th colspan="7" style="text-align:right; border:none;">Zilla parishad OF <?= $_SESSION['location']['district_name']?></th>
</tr>
</table>
<!--<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th style="color:#F00;">*GROSS PAY NOTE :</th>
<th colspan="6" style="text-align:right; border:none;"><br />Actual Gross pay amount in Bill Summary is inclusive of overdrawn amount,Festival Advance Recovery and Less Advance .</th>
   
</tr> 
<tr></tr><tr></tr>
<tr>
<th style="color:#F00;">*NET PAY NOTE :</th>
<th colspan="3" style="text-align:right; border:none;"><br />Actual Net pay amount in Bill Summary is inclusive of GSLI amount.</th>
   
</tr> 

</table>-->


