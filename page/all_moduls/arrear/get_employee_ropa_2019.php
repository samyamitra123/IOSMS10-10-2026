<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

$gp_id=$_POST['gp_id'];
$cryptoGraph=new cryptography();

$db=new database();


//$arr=$db->fetch_table("select emp_id_pk, emp_first_name, emp_second_name, emp_last_name from prd_employee_master where gp_id_fk = '" . $gp_id . "' order by emp_first_name"); 

$arr=$db->fetch_table("SELECT 
								distinct(main.emp_id_pk),main.emp_first_name,main.emp_second_name,main.emp_last_name,
								CASE WHEN (arrear_out.delete_status=1) THEN 1 ELSE 0 END as arrear_status
								from (
								SELECT distinct(emp.emp_id_pk),emp.emp_first_name,emp.emp_second_name,emp.emp_last_name
								FROM 
								prd_employee_master emp 
								LEFT JOIN 
								prd_employee_arrear arrear 
								ON 
								emp.emp_id_pk=arrear.emp_id_fk
								WHERE emp.gp_id_fk = '".$gp_id."' AND emp.emp_status in('1','9') AND (emp.ropa_status = '1' OR emp.emp_cosolidated_pay!='0')
								ORDER BY 
								emp_first_name
								) as main
								LEFT JOIN
								prd_employee_arrear arrear_out
								ON
								main.emp_id_pk=arrear_out.emp_id_fk and arrear_out.delete_status=1");


?>
	<option value="">Please Select</option>
    
	 <? foreach($arr as $key){ 
	 $emp_full_name=$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];
	 ?>
		 <option value="<?=$key['emp_id_pk']; ?>" ><?= $emp_full_name; ?></option>
<!--      <option value="<?=$key['emp_id_pk']; ?>" <?php if($key['arrear_status']==1){ ?> disabled style="color:#DF5255" <?php } ?>><?= $emp_full_name; ?></option>-->
     <? }  

?>







