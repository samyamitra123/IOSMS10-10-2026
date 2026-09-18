<?php
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';
require '../../all_function/fun_store/zp_ps_gp_function.php';


$cryptoGraph=new cryptography();


/*------------------EMPLOYEE FINALIZE----------------------------------------------------------------------*/

$db = new database();
$fun_store=new zp_ps_gp_class();
//echo $fun_store->get_rand_numbers(2); die;
if($_REQUEST['flag']=='finalize')
{
	
	$final_accept=1;
	$id=$_REQUEST['id'];
	$rand_code_nw=$fun_store->check_rand_code();
	$empid_const=$fun_store->check_unique_emp_const_id();
	$db = new database();
	
	$check=$db->fetch_table("select emp_id_const,emp_pay_in_payband,emp_status from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($id, 4)."'");
	
	pg_query('BEGIN');
	
	if($check['0']['emp_id_const']!='0')
	{
		$finalize_update=$db->update("
										UPDATE prd_employee_master
										SET emp_status='".$final_accept."',
										emp_system_code='".$rand_code_nw."',
										emp_unlock_status='0'
										WHERE emp_id_pk='".$cryptoGraph->decode($id, 4)."'
										AND emp_status='3';
										");
										
	}
	else
	{
		$finalize_update=$db->update("
									UPDATE prd_employee_master
									SET emp_status='".$final_accept."',
									emp_system_code='".$rand_code_nw."',
									emp_id_const='".$empid_const."',
									emp_unlock_status='0'
									WHERE emp_id_pk='".$cryptoGraph->decode($id, 4)."'
									AND emp_status='3';
									");
	}
	
	$insert_status=$db->insert("
								INSERT INTO psemp_employee_profile_update_status
								(
									ip_address,
									date,
									ps_id_fk,
									emp_id_fk,
									prev_status,
									new_status,
									zp_id_fk
									)
									VALUES
									(
										'".$_SERVER['REMOTE_ADDR']."',
										'now()',
										'0',
										'".$cryptoGraph->decode($id, 4)."',
										'3',
										'1',
										'".$_SESSION['location']['district_id']."'
									)
								");
	
	if($finalize_update && $insert_status)
	{ 	
		pg_query('COMMIT');
		
		$_SESSION['msg']='<p class="alert alert-success" style="text-align:center"><strong>Employee Has Been Successfully Approved.</strong></p>';
		?>
		<script>
		window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? } 
	else
	{ 
		pg_query('ROLLBACK');
		
		$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Employee Approval Fails.</strong></p>';
		?>
		<script>
		window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? }
}

/*-----------------------------------------------------EMPLOYEE REJECT----------------------------------------------------------------------*/

if($_REQUEST['flag']=='reject')
{
	$final_reject=7;
	$id=$_REQUEST['id'];
	$reject_reason=$_GET['reject_reason'];
	
	if($reject_reason=="")
	{
		$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Insert Valid Reason.</strong></p>';
		?>
		<script>
		window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? 
	}
	else
	{
		$db = new database();
		
		$arr=$db->fetch_table("select emp_status from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($id,4)."'");								   
		
		pg_query('BEGIN');
		
		$finalize_update=$db->update("
										UPDATE prd_employee_master
										SET emp_status='".$final_reject."'
										WHERE emp_id_pk='".$cryptoGraph->decode($id, 4)."'
										AND emp_status='3';
										");
		
		$insert_status=$db->insert("
										INSERT INTO psemp_employee_profile_update_status
										(
											ip_address,
											date,
											reason,
											ps_id_fk,
											emp_id_fk,
											prev_status,
											new_status,
											zp_id_fk
											)
											VALUES
											(
												'".$_SERVER['REMOTE_ADDR']."',
												'now()',
												'".$reject_reason."',
												'0',
												'".$cryptoGraph->decode($id, 4)."',
												'3',
												'7',
												'".$_SESSION['location']['district_id']."'
											)
										");
		
		if($finalize_update && $insert_status)
		{ 
			pg_query('COMMIT');
			$_SESSION['msg']='<p class="alert alert-success" style="text-align:center"><strong>Employee Has Been Successfully Rejected.</strong></p>';
			?>
			<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
			</script>
		<? } 
		else
		{ 
			pg_query('ROLLBACK');
			$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Employee Rejection Fails.</strong></p>';
			?>
			<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
			</script>
		<? }
	}
}

?>