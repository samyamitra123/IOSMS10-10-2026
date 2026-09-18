<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$db=new database();
$post = $_GET;
//print($post['type']); exit;
//$monthYear = date('Ym');
//$monthYear = (isset($_GET['month']) && $_GET['month'] != '')?date('Y').$_GET['month']:date('Ym');
$current_year=date("Y");
$next_year=$current_year+1;
$prev_yrr=$current_year-1;
$fin_prev_yrr=$next_year.'04';
$fin_yr_start=$current_year.'03';
$fin_yr_match=$current_year.'04';
$fin_yr_end=$next_year.'03';
switch ($post['type']) {
	    case 'ps':
		    $Query = "SELECT ps_id_pk as id ,ps_name as name from prd_location_master_panchayat_samiti WHERE ps_code=".$post['login_id'];
		    $LoginData = $db->fetch_table($Query);
		    $LoginData = $LoginData[0];
		    $Query = "SELECT ebd.*, emp.emp_first_name, emp.emp_second_name, emp.emp_last_name  FROM prd_employee_bonus_details as ebd 
		              LEFT JOIN prd_employee_master as emp 
		              ON ebd.emp_id_fk = emp.emp_id_pk
		              WHERE ebd.monthyear='".$prev_yrr.$current_year."' AND ebd.ps_id_fk = '".$LoginData['id']."'";
		    $BonusData =  $db->fetch_table($Query);
		    if($post['action'] == 'delete')
		    {
		    	$Query = "DELETE FROM prd_employee_bonus_details WHERE monthyear='".$prev_yrr.$current_year."' AND ps_id_fk = '".$LoginData['id']."'";
		    	$db->update($Query);
		    	echo "Bonus Employee Reset Successfully";
		    	exit;
		    }
		    //print_r($EmployeeData); exit;
		    
	    break;
	    case 'zp':
		    $Query = "SELECT district_id_pk as id ,district_name as name from prd_location_master_district WHERE district_code=".$post['login_id'];
		    $LoginData = $db->fetch_table($Query);
		    $LoginData = $LoginData[0];
		    $Query = "SELECT ebd.*, emp.emp_first_name, emp.emp_second_name, emp.emp_last_name  FROM prd_employee_bonus_details as ebd 
		              LEFT JOIN prd_employee_master as emp 
		              ON ebd.emp_id_fk = emp.emp_id_pk
		              WHERE ebd.monthyear='".$prev_yrr.$current_year."' AND ebd.zp_id_fk = '".$LoginData['id']."'";
		    $BonusData =  $db->fetch_table($Query);
		    if($post['action'] == 'delete')
		    {
		    	$Query = "DELETE FROM prd_employee_bonus_details WHERE monthyear='".$prev_yrr.$current_year."' AND zp_id_fk = '".$LoginData['id']."'";
		    	$db->update($Query);
		    	echo "Bonus Employee Reset Successfully";
		    	exit;
		    }
		    //print_r($EmployeeData); exit;
		    
	    break;
	    case 'block':
		    $Query = "SELECT block_id_pk as id, block_code ,block_name as name from prd_location_master_block WHERE block_code=".$post['login_id'];
		    $LoginData = $db->fetch_table($Query);
		    $LoginData = $LoginData[0];
		    $Query = "SELECT ebd.*, emp.emp_first_name, emp.emp_second_name, emp.emp_last_name  FROM prd_employee_bonus_details as ebd 
		              LEFT JOIN prd_employee_master as emp 
		              ON ebd.emp_id_fk = emp.emp_id_pk
		              WHERE ebd.monthyear='".$prev_yrr.$current_year."' AND ebd.block_code = '".$LoginData['block_code']."'";
		    $BonusData =  $db->fetch_table($Query);
		    if($post['action'] == 'delete')
		    {
		    	$Query = "DELETE FROM prd_employee_bonus_details WHERE monthyear='".$prev_yrr.$current_year."' AND block_code = '".$LoginData['block_code']."'";
		    	$db->update($Query);
		    	echo "Bonus Employee Reset Successfully";
		    	exit;
		    }
		    //print_r($EmployeeData); exit;
		    
	    break;
	}  
?>
<table>
	<thead>
		<th>Name</th>
		<th>Status Flag</th>
        <th>Amount</th>       
	</thead>
<?php foreach($BonusData as $value):?>
	<tr>
		<td><?php echo $value['emp_first_name'].' '.$value['emp_second_name'].' '.$value['emp_last_name']; ?></td>
        <td><?php echo $value['bonus_status']; ?></td>
        <td><?php echo $value['bonus_amount']; ?></td>
	</tr>
<?php endforeach; ?>
<tfoot>
	<td colspan="3"><input type="submit" value="Delete" onclick="DeleteBonus('<?php echo $post['login_id']; ?>');" ></td>
</tfoot>	
</table>