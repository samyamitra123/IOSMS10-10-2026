<?

//echo $_GET['month']."  ".$_GET['year'];  die;
$monthyear=$_GET['year'].$_GET['month']; 
//echo $monthyear; die;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
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

function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
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
	
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='407'");
	 $requisition_type=$requisition[0]['code'];

	
    $code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	                              ");
	
	
							
	$tch= $db->fetch_table("SELECT gp_name,gp.gp_code,emp_first_name,emp_second_name,emp_last_name,emp_pan_no,emp_bank_name,
	   				   emp_bank_branch,emp_micr_no,emp_ifsc_no,emp_acc_no,consolidated_pay,pay_payband,tch_grade_pay,
	   				   basic,da,sal.interim_relief,hra,ma,conv_allow,other_deduction,hill_allowance,gross_salary,gpf,pf_loan,p_tax,i_tax,gsli,overdrawn,net,
										gp.gp_name,
										emp.emp_first_name,
										emp.emp_second_name,
										emp.emp_last_name,
										emp.emp_pay_in_payband,
										emp.emp_desig,
										emp.emp_pay_band,
										emp.emp_first_join_date,festival_loan
										
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as emp
										ON sal.emp_id_fk = emp.emp_id_pk
									INNER JOIN 
										prd_location_master_gp as gp
										ON sal.gp_code = CAST(gp.gp_code AS text)
								
										WHERE
												sal.block_code = '".$_SESSION['user_info']['stake_user']."'
											AND cast(emp.gp_id_fk as character varying)= sal.gp_id_fk 
											AND sal.net!='0'
											AND sal.status_flag = 3
											AND delete_status=1
											AND salary_monthyear='".$monthyear."'
											AND sal.requisition_type='".$requisition_type."'
										ORDER BY gp.gp_name ASC");
								


if(!$tch)	
	{
		$tch=$db->fetch_table("SELECT 
       sal.slno, sal.gp_code, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da,sal.interim_relief, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.gross_salary,sal.conv_allow, 
       sal.overdrawn,sal.gp_code,sal.gsli,sal.cooperative_loan,sal.hbl_loan,sal.hbl_loan,festival_loan,
										gp.gp_name,
										emp.emp_first_name,
										emp.emp_second_name,
										emp.emp_last_name,
										emp.emp_pay_in_payband,
										emp.emp_desig,
										emp.emp_pay_band,
										emp.emp_first_join_date,festival_loan
										
									FROM
										prd_monthly_salary_archive_final as sal
									
									INNER JOIN 
										prd_employee_master as emp
										ON sal.emp_id_fk = emp.emp_id_pk
									INNER JOIN 
										prd_location_master_gp as gp
										ON sal.gp_code = CAST(gp.gp_code AS text)
								
										WHERE
												sal.block_code = '".$_SESSION['user_info']['stake_user']."'
											AND emp.gp_id_fk = sal.gp_id_fk 
											AND sal.net!='0'
											AND sal.status_flag = 3 
											AND sal.is_saved = 1
											AND delete_status='1'
											AND salary_monthyear='".$monthyear."'
											AND sal.requisition_type='".$requisition_type."'
										ORDER BY gp.gp_name ASC
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

<?php /*?><table width="100" border="1">
   <tr>
      <th colspan="24" style="text-align:center;"><br /><br />DETAILED SALARY BILL OF Grant-in-aid Employees UNDER <?= $_SESSION['location']['ps_name']?> PS FOR THE MONTH OF <?= GetMonthString(date('m')).", ".date(Y) ?><br /><br /></th>
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
<!--    <th scope="col">CONSOLIDATED PAY</th>-->
    <th scope="col">PAY IN PAY BAND</th>
    <th scope="col">GRADE PAY</th>
    <th scope="col">DA</th>
    <th scope="col">HRA</th>
    <th scope="col">MA</th>
    <th scope="col">CONV ALLOW</th>
    <th scope="col">HIll ALLOWANCE</th>
    <th scope="col">INTERIM RELIEF</th>
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
    <td style="text-align:center;"><?= $key['tch_grade_pay']?></td>
    <td style="text-align:center;"><?= $key['da']?></td>
    <td style="text-align:center;"><?= $key['hra']?></td>
    <td style="text-align:center;"><?= $key['ma']?></td>
    <td style="text-align:center;"><?= $key['conv_allow']?></td>
    <td style="text-align:center;"><?= $key['hill_allowance']?></td>
     <td style="text-align:center;"><?= $key['interim_relief']?></td>
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
	$tot_interim_relief+=$key['interim_relief'];
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
    <td><?= $tot_interim_relief?></td>
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
<table>
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

</table><?php */?>


<table width="200" border="1">
  <tr>
  <th colspan="24" style="text-align:center;"><br /><br />SALARY REQUISITION EXCEL FOR THE EMPLOYEES UNDER <?= $_SESSION['location']['block_name']?> BLOCK FOR THE MONTH OF <?= GetMonthString(date('m')).", ".date('Y') ?><br /><br /></th>
  </tr>
 <tr>
  <th style="border:none"></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th colspan="8">PAY & ALLOWANCES</th>
  <th colspan="1"></th>
  <th style="border:none"></th>
  <th colspan="7">DEDUCTIONS</th>
  <th style="border:none"></th>
  </tr>
  <tr>
    <th scope="col">SL</th>
    <th scope="col">NAME OF THE GP</th>
    <th scope="col">NAME OF THE EMPLOYEE</th>
    <th scope="col">DESIGNATION</th>
    <th scope="col">BANK</th>
    <th scope="col">ACCOUNT NO</th>
    <th scope="col">IFSC</th>
    <th scope="col">CONSOLIDATED PAY</th>
    <th scope="col">PAY IN PAY BAND</th>
   
    <th scope="col">GRADE PAY</th>
    <th scope="col">DA</th>
    <th scope="col">HRA</th>
    <th scope="col">MA</th>
    <th scope="col">CONV ALLOW</th>
     <th scope="col">HILL ALLOWANCE</th>
	<?php if($monthyear<='201812')
    {?>
    <th scope="col">INTERIM RELIEF</th>
    <?php }?>
    <th scope="col" style="border:none">GROSS</th>
    <th scope="col">GPF</th>
    <th scope="col">PF-LOAN</th>
    <th scope="col">P. TAX</th>
    <th scope="col">I.TAX</th>
    <th scope="col">GSLI</th>
    <th scope="col">OVERDRAWN</th>
<!--     <th scope="col">Co-operative Loan Recovery</th>
    <th scope="col">HBL Recovery</th>-->
    <th scope="col">Festival Advance Recovery</th>
    <th scope="col" style="border:none">NET PAY</th>
  </tr>
  <? $cnt=1; 
$gross=$net=0;
$tot_conv_allow=$tot_payband=$tot_basic=$tot_da=$tot_hra=$tot_ma=$tot_ca=$tot_ha=$tot_cpf=$tot_gross=$tot_gpf=$tot_pfloan=$tot_cpfdeduct=$tot_ptax=$tot_itax=$tot_od=$tot_gsli=$tot_net=0;
  foreach ($tch as $key) {?>
  <tr>
    <td scope="row"><?= $cnt;?></td>
    <td><?= $key['gp_name']?></td>
    <td><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
    <td><?= fun_common($key['emp_desig'] ,$code_data)?></td>
    <td><?= fun_bank($key['emp_bank_name'])?></td>
    <td><? 
	$val="&nbsp;".$key['emp_acc_no'];
	//$val1=ltrim($val,' ');
	echo $val;
	?></td>
    <td><?= $key['emp_ifsc_no']?></td>
     <td style="text-align:center;"><?= $key['consolidated_pay']?></td>
    <td style="text-align:center;"><?= $key['pay_payband']?></td>
    <td style="text-align:center;"><?= $key['tch_grade_pay']?></td>
    <td style="text-align:center;"><?= $key['da']?></td>
    <td style="text-align:center;"><?= $key['hra']?></td>
    <td style="text-align:center;"><?= $key['ma']?></td>
    <td style="text-align:center;"><?= $key['conv_allow']?></td>
   <td style="text-align:center;"><?= $key['hill_allowance']?></td>
	<?php if($monthyear<='201812')
    {?>
    <td style="text-align:center;"><?= $key['interim_relief']?></td>
    <? }?>
    <td style="text-align:center;"><?= $key['gross_salary']?></td>
    <td style="text-align:center;"><?= $key['gpf']?></td>
    <td style="text-align:center;"><?= $key['pf_loan']?></td>
    <td style="text-align:center;"><?= $key['p_tax']?></td>
    <td style="text-align:center;"><?= $key['i_tax']?></td>
    <td style="text-align:center;"><?= $key['gsli']?></td>
    <td style="text-align:center;"><?= $key['overdrawn']?></td>
<!--  <td style="text-align:center;"><?= $key['cooperative_loan']?></td>
    <td style="text-align:center;"><?= $key['hbl_loan']?></td>-->
    <td style="text-align:center;"><?= $key['festival_loan']?></td>
    <td style="text-align:center;"><?= $key['net']?></td>
  </tr>
 <? 
 
$tot_conv_allow+=$key['consolidated_pay'];
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
$tot_pfloan+=$key['pf_loan'];
$tot_ptax+=$key['p_tax'];
$tot_itax+=$key['i_tax'];
$tot_gsli+=$key['gsli'];
$tot_od+=$key['overdrawn'];
//$tot_ovd1+=$key['cooperative_loan'];
//$tot_ovd2+=$key['hbl_loan'];
$tot_ovd3+=$key['festival_loan'];
$tot_advance_amount+=$key['advance_amount'];
$tot_net+=$key['net'];
 
$cnt++; } ?>
<tr style="text-align:center;font-weight:bold;">
	<td colspan="7" style="text-align:right;">TOTAL</td>
    <td><?= $tot_conv_allow?></td>
    <td><?= $tot_payband?></td>
    <td><?= $tot_basic?></td>
    <td><?= $tot_da?></td>
    <td><?= $tot_hra?></td>
    <td><?= $tot_ma?></td>
    <td><?= $tot_ca?></td>
    <td><?= $tot_hill?></td>
	<?php if($monthyear<='201812')
    {?>
    <td><?= $tot_interim_relief?></td>
    <? }?>
    <td><?= $tot_gross?></td>
    <td><?= $tot_gpf?></td>
    <td><?= $tot_pfloan?></td>
    <td><?= $tot_ptax?></td>
    <td><?= $tot_itax?></td>
    <td><?= $tot_gsli?></td>
    <td><?= $tot_od?></td>
    <!--<td><?= $tot_ovd1?></td>
    <td><?= $tot_ovd2?></td>-->
    <td><?= $tot_ovd3?></td>
    <td><?= $tot_net?></td>
</tr> 



</table>
<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th style="color:#F00;">*GROSS PAY NOTE :</th>
<th colspan="6" style="text-align:right; border:none;"><br />Actual Gross pay amount in Bill Summary is inclusive of overdrawn amount and Festival Advance Recovery.</th>
   
</tr> 
<tr></tr><tr></tr>
<tr>
<th style="color:#F00;">*NET PAY NOTE :</th>
<th colspan="3" style="text-align:right; border:none;"><br />Actual Net pay amount in Bill Summary is inclusive of GSLI amount.</th>
   
</tr> 

</table>