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

//$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

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
//-------------------------------------------------------QUERY-----------------------------------------------------------------

	//$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
	$db = new database();
	
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	 $requisition_type=$requisition[0]['code'];

	
    $code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	                              ");
if($monthyear==date('Ym'))
{
							
	$tch= $db->fetch_table("SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
			   sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
			   sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
			   sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
			   sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
			   sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
			   sal.overdrawn, sal.salary_type, sal.cause, sal.part_day,   sal.ropa_level,sal.gsli,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_desig,
										tch.emp_last_name,
										tch.emp_pay_in_payband
									FROM
										prd_employee_salary_save as sal
											
									INNER JOIN 
										prd_employee_master as tch
										ON sal.emp_id_fk =tch.emp_id_pk AND sal.ps_id_fk= tch.ps_id_fk
										WHERE
										sal.salary_monthyear='".date('Ym')."'
										AND tch.emp_status in('1','9')	 
										AND sal.ps_id_fk = '".$_SESSION['location']['ps_id']."'
										AND sal.net != '0' AND sal.delete_status='1'
										AND sal.status_flag in('1','2','3')  AND sal.delete_status='1' AND salary_monthyear='".date('Ym')."' 
										AND is_saved='1'  AND requisition_type='".$requisition_type."' AND sal.ropa_status IN ('0','2')
										order by tch.emp_first_name ;
								");
}
else
{
	$tch= $db->fetch_table("SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
			   sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
			   sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
			   sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
			   sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
			   sal.hill_allowance, sal.gpf, sal.gross_salary, sal.is_saved, sal.conv_allow, 
			   sal.overdrawn, sal.salary_type, sal.cause, sal.part_day,   sal.gsli,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_desig,
										tch.emp_last_name,
										tch.emp_pay_in_payband
									FROM
										prd_monthly_salary_archive_final as sal
											
									INNER JOIN 
										prd_employee_master as tch
										ON sal.emp_id_fk =tch.emp_id_pk 
										WHERE
										sal.salary_monthyear='".$monthyear."' 
										AND sal.ps_id_fk = '".$_SESSION['location']['ps_id']."'
										AND sal.net != '0' AND sal.delete_status='1'
										AND sal.status_flag in('1','2','3')  AND sal.delete_status='1'
										AND is_saved='1'  AND requisition_type='".$requisition_type."' AND sal.ropa_status IN ('0','2')
										order by tch.emp_first_name ;
								");
}
								
$date = $tch[0]['latestupdate_time'];
$msg="";
if($tch[0]['status_flag'] == 1){ 
	$msg="Not finalized (Just Saved)";
}					
elseif($tch[0]['status_flag'] == 2){ 
	$msg="Requisition finalized by CIRCLE";
} elseif($tch[0]['status_flag'] == 3){
	$msg="Requisition finalized by DPSC";
} 
?>

<table width="100" border="1">
   <tr>
      <th colspan="24" style="text-align:center;"><br /><br />DETAILED SALARY BILL OF Grant-in-aid Employees UNDER <?= $_SESSION['location']['ps_name']?> PS FOR THE MONTH OF <?= GetMonthString($_GET['month']).", ".$_GET['year'] ?><br /><br /></th>
   </tr>
    <tr>
 	 <th style="border:none"></th>
 	 <th></th>
  	<th></th>
  	<th></th>
  	<th></th>
  	<th></th>
  	<th></th>
  	<th colspan="7">PAY & ALLOWANCES</th>
   	<th colspan="1"></th>
  	<th style="border:none"></th>
  	<th colspan="8">DEDUCTIONS</th>
  	<th style="border:none"></th>
  </tr>
  <tr>
    <th scope="col">SL</th>
    <th scope="col">NAME OF THE PS</th>
    <th scope="col">NAME OF THE EMPLOYEE</th>
    <th scope="col">DESIGNATION</th>
    <th scope="col">BANK</th>
    <th scope="col">ACCOUNT NO</th>
    <th scope="col">IFSC</th>
   <?php //if($monthyear<='201912')
    //{
		?>
    <th scope="col">PAY IN PAY BAND</th>
    <th scope="col">GRADE PAY</th>
     <? /*}else{
		
		 
		 ?>
      <th scope="col">BASIC</th>
    <th scope="col">LEVEL</th>
    <? } */?>
    <th scope="col">DA</th>
    <th scope="col">HRA</th>
    <th scope="col">MA</th>
    <th scope="col">CONV ALLOW</th>
    <th scope="col">HIll ALLOWANCE</th>
    <?php if($monthyear<='201812')
	{?>
    <th scope="col">INTERIM RELIEF</th>
    <?php } ?>
    <th scope="col" style="border:none">GROSS</th>
    <th scope="col">GPF</th>
    <th scope="col">PF-LOAN</th>
    <th scope="col">P. TAX</th>
    <th scope="col">I.TAX</th>
    <th scope="col">GSLI</th>
    <th scope="col">HRA Deduction</th>
    <th scope="col">OVERDRAWN</th>
	<th scope="col">Festival Advance Recovery</th> 
    <th scope="col" style="border:none">NET PAY</th>
  </tr>
  <? $cnt=1; 
$gross=$net=0;
$tot_conv_allow=$tot_payband=$tot_basic=$tot_da=$tot_hra=$tot_ma=$tot_ca=$tot_ha=$tot_cpf=$tot_gross=$tot_gpf=$tot_pfloan=$tot_cpfdeduct=$tot_ptax=$tot_itax=$tot_od=$tot_gsli=$tot_net=0;
   if(count($tch)){foreach ($tch as $key) {?>
  <tr>
    <td scope="row"><?= $cnt;?></td>
    <td><?= $_SESSION['location']['ps_name']?></td>
    <td><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
    <td><?= fun_common($key['emp_desig'] ,$code_data)?></td>
    <td><?= $key['bankname']?></td>
    <td><? 
	$val="&nbsp;".$key['accountno'];
	//$val1=ltrim($val,' ');
	echo $val;
	?></td>
    <td><?= $key['bank_ifsc']?></td>
<!--    <td style="text-align:center;"><?= $key['consolidated_pay']?></td>-->
    <td style="text-align:center;"><?= $key['pay_payband']?></td>
     <?php //if($monthyear<='201812')
    //{
		?>
    <td style="text-align:center;"><?= $key['tch_grade_pay']?></td>
    <? /*}else{?>
     <td style="text-align:center;"><?= $key['ropa_level']?></td>
        <? }*/?>
    <td style="text-align:center;"><?= $key['da']?></td>
    <td style="text-align:center;"><?= $key['hra']?></td>
    <td style="text-align:center;"><?= $key['ma']?></td>
    <td style="text-align:center;"><?= $key['conv_allow']?></td>
    <td style="text-align:center;"><?= $key['hill_allowance']?></td>
     <?php if($monthyear<='201812')
	{?>
     <td style="text-align:center;"><?= $key['interim_relief']?></td>
	 <?php }?>
    <td style="text-align:center;"><?= $key['gross_salary']?></td>
    <td style="text-align:center;"><?= $key['gpf']?></td>
    <td style="text-align:center;"><?= $key['pf_loan']?></td>
    <td style="text-align:center;"><?= $key['p_tax']?></td>
    <td style="text-align:center;"><?= $key['i_tax']?></td>
    <td style="text-align:center;"><?= $key['gsli']?></td>
     <td style="text-align:center;"><?= $key['hra_deduction']?></td>
    <td style="text-align:center;"><?= $key['overdrawn']?></td>
    <td style="text-align:center;"><?= $key['festival_loan']?></td>
    <td style="text-align:center;"><?= $key['net']?></td>
  </tr>
 <? 
	//$tot_conv_allow+=$key['consolidated_pay'];
	$tot_payband+=$key['pay_payband'];
	$tot_basic+=$key['tch_grade_pay'];
	$tot_da+=$key['da'];
	$tot_hra+=$key['hra'];
	$tot_ma+=$key['ma'];
	$tot_ca+=$key['conv_allow'];
	$tot_hill+=$key['hill_allowance'];
	 if($monthyear<='201811')
	 {
	
	$tot_interim_relief+=$key['interim_relief'];
	 }
	 else
	 {
		 $tot_interim_relief+=0;
	 }
	$tot_gross+=$key['gross_salary'];
	$tot_gpf+=$key['gpf'];
	$tot_pfloan+=$key['pf_loan'];
	$tot_ptax+=$key['p_tax'];
	$tot_itax+=$key['i_tax'];
	$tot_gsli+=$key['gsli'];
	$hra_deduction+=$key['hra_deduction'];
	$tot_od+=$key['overdrawn'];
//$tot_ovd1+=$key['cooperative_loan'];
//$tot_ovd2+=$key['hbl_loan'];
	$tot_ovd3+=$key['festival_loan'];
	$tot_net+=$key['net'];
 
$cnt++; } ?>
<tr style="text-align:center;font-weight:bold;">
	<td colspan="7" style="text-align:right;">TOTAL</td>
<!--    <td><?= $tot_conv_allow?></td>
-->    <td><?= $tot_payband?></td>
    <td><?= $tot_basic?></td>
    <td><?= $tot_da?></td>
    <td><?= $tot_hra?></td>
    <td><?= $tot_ma?></td>
    <td><?= $tot_ca?></td>
    <td><?= $tot_hill?></td>
   <?php  if($monthyear<='201812')
	 {?>
    <td><?= $tot_interim_relief?></td>
    <? }?>
    <td><?= $tot_gross?></td>
    <td><?= $tot_gpf?></td>
    <td><?= $tot_pfloan?></td>
    <td><?= $tot_ptax?></td>
    <td><?= $tot_itax?></td>
    <td><?= $tot_gsli?></td>
    <td><?= $hra_deduction?></td>
    <td><?= $tot_od?></td>
	<td><?= $tot_ovd3?></td>
    <td><?= $tot_net?></td>
</tr> 
<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th colspan="21" style="text-align:right; border:none;"><br /><br /><br />Panchayat Samiti OF <?= $_SESSION['location']['ps_name']?></th>
</tr>
</table>

<? } else{ ?>
<tr style="border-color:#3E9B96;">
	<td colspan="26" style="color:red;font-weight:bold;border-color:#3E9B96; text-align:center;">No Data Found</td>
</tr>
<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th colspan="21" style="text-align:right; border:none;"><br /><br /><br />Panchayat Samiti OF <?= $_SESSION['location']['ps_name']?></th>
</tr>
</table>
<? } ?>
</table>


