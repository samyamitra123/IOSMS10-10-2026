<?

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Payment_Status_Excel.xls');
header("Content-Transfer-Encoding: binary");
ob_start();
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';  
$crypto = new cryptography();
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
function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
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
	$bill_type =$crypto->decode($_GET['bill_type'],4);

	$monthyear =$_REQUEST['ye'].$_REQUEST['mo'];
	$db = new database();

	$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");
	

	
	$tch= $db->fetch_table(" 
							   SELECT gp_name, emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_id_const,payment_status,payment_date
							FROM
							prd_location_master_gp gp
							INNER JOIN prd_employee_master emp
							ON gp.gp_id_pk=emp.gp_id_fk
							INNER JOIN prd_employee_salary_save sal
							ON sal.emp_id_fk=emp.emp_id_pk AND sal.gp_id_fk=CAST(emp.gp_id_fk AS character varying)
							WHERE gp.block_id_fk='".$_SESSION['location']['block_id']."' AND sal.status_flag='3' AND sal.is_saved='1' 
							AND sal.delete_status='1' AND sal.requisition_type='".$bill_type."' AND sal.salary_monthyear='".date('Ym')."'
								");
								
	
$date = $tch[0]['latestupdate_time']; 
if(count($tch)>0)
{	?>
<table width="200" border="1">
  <tr>
  <th colspan="7" style="text-align:center;"><br /><br />PAYMENT STATUS EXCEL FOR THE EMPLOYEES UNDER <?= $_SESSION['location']['block_name']?> BLOCK FOR THE MONTH OF <?= GetMonthString(date('m')).", ".date('Y') ?><br /><br /></th>
  </tr>
  <tr>
  	<th scope="col">SL. NO</th>
    <th scope="col">NAME OF THE GP</th>
    <th scope="col">NAME OF THE EMPLOYEE</th>
    <th scope="col">EMPLOYEE ID</th>
    <th scope="col">DESIGNATION</th>
    <th scope="col">PAYMENT STATUS</th>
    <th scope="col">PAYMENT DATE</th>
  </tr>
  <? $cnt=1; 
  foreach ($tch as $key) {?>
  <tr>
    <td scope="row"><?= $cnt;?></td>
    <td><?= $key['gp_name']?></td>
    <td><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
    <td><?= $key['emp_id_const']?></td>
    <td><?= fun_common($key['emp_desig'] ,$code_data)?></td>
    <td>
	<?php if($key['payment_status']=='11'){ echo "SUCCESS"; }else{ echo "FAILURE";}?></td>
    <td><?= date_frmt_change($key['payment_date'])?></td>
  </tr>
 <? 
$cnt++; } ?>

</table>

<? } ?>
