<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//echo $_GET['month']."  ".$_GET['year'];  die;
 $year=$_GET['year']; 
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
//-------------------------------------------------------QUERY-----------------------------------------------------------------
 
	//$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
	$db = new database();

	$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	                              ");

	$tch=$db->fetch_table("Select emp.emp_first_name,
		   emp.emp_second_name,
		   emp_last_name,
		   emp.emp_id_pk,
		   stop.date,
		   emp.emp_dob,
		   emp.emp_retirement_date,
		   gp.gp_name,
		   gp.gp_code,
		   emp.emp_id_const
	   from  prd_location_master_block block 
		inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk
		inner join  prd_employee_master emp  ON gp.gp_id_pk=emp.gp_id_fk
		inner join prd_stop_sal_reason stop ON emp.emp_id_pk=stop.emp_id_fk
				Where
				emp_status='2'
				AND stop.reason='1991'
			    AND extract('Y' from  emp.emp_retirement_date)= '" .$year."'
				AND block.block_code='" . $_SESSION['user_info']['stake_user'] . "'" );
								
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
<table width="200" border="1">
	<tr>
  		<th colspan="7" style="text-align:center;"><br /><br />DETAILED RETIRED EMPLOYEE OF Grant-in-aid Employees UNDER <?= $_SESSION['location']['block_name']?>  FOR THE YEAR OF <?= $year ?><br /><br />
  		</th>
	</tr>
	<tr>
    	<th scope="col">SL</th>
    	<th>Gp Name</th>
    	<th>GP User ID</th>
    	<th>Employee Name</th>
    	<th>Employee Id</th>
    	<th>Date of Birth</th>
    `	<th>Rerirement Date</th>
	</tr>
  <? $cnt=1; 
   if(count($tch))
   {
	   foreach ($tch as $key) 
	   {?>
   <tr>
        <td scope="row"><?= $cnt;?></td>
        <td><?= $key['gp_name']?></td>
        <td><?= $key['gp_code']?></td>
        <td><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
        <td><?= $key['emp_id_const']?></td>
        <td><?= $key['emp_dob']?></td>
        <td><?= $key['emp_retirement_date']?></td> 
   </tr>
 <? 
 $cnt++;
 	  }
  ?>
  <? } 
   else
	{ ?>
  <tr style="border-color:#3E9B96;">
        <td colspan="7" style="color:red;font-weight:bold;border-color:#3E9B96; text-align:center;">No Data Found</td>
   </tr>
<? 	} ?>
</table>


