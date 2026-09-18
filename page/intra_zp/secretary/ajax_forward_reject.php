<?php
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';


$cryptoGraph=new cryptography();


/*------------------EMPLOYEE FORWARD----------------------------------------------------------------------*/

$db = new database();

if($_REQUEST['flag']=='forward')
{
	$id=$_REQUEST['id'];
	$db = new database();
	
	pg_query('BEGIN');
	$forward_update=$db->update("UPDATE prd_employee_master SET emp_status='3'
									WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
									AND emp_status='6' AND emp_id_pk='".$cryptoGraph->decode($id, 4)."'");
									
    $update_epension_status= $db->update("UPDATE prd_stake_epension_employee_profile SET status='2'
		
		where  emp_id_fk='".$cryptoGraph->decode($id, 4)."'"); 									
	
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
										'6',
										'3',
										'".$_SESSION['location']['district_id']."'
									)
								");	
	
	
	if($forward_update && $insert_status)
	{
		pg_query('COMMIT');
		$_SESSION['msg']='<p class="alert alert-success" style="text-align:center"><strong>Employee Has Been Successfully Forwaded.</strong></p>';
		?>
		<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/secretary/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? 
	} 
	else
	{ 
		pg_query('ROLLBACK');
		$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Employee Forward Fails.</strong></p>';
		?>
		<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/secretary/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? 
	}
}



/*-----------------------------------------------------EMPLOYEE REJECT----------------------------------------------------------------------*/

if($_REQUEST['flag']=='reject')
{
	$id=$_REQUEST['id'];
	$reject_reason=$_GET['reject_reason'];
	$emp_stat=$_GET['emp_status'];
	
	if($reject_reason=="" && $emp_stat!='7')
	{
		$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Insert Valid Reason.</strong></p>';
		?>
		<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/secretary/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? 
	}
	else
	{
		$db = new database();
		
		pg_query('BEGIN');
		
		$reject_update=$db->update("
										UPDATE prd_employee_master
										SET emp_status='5'
										WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
										AND emp_status in ('6','7') AND emp_id_pk='".$cryptoGraph->decode($id, 4)."'");
		
		
		
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
												'".$emp_stat."',
												'5',
												'".$_SESSION['location']['district_id']."'
											)
										");	
		
		if($reject_update && $insert_status)
		{ 
			pg_query('COMMIT');
			$_SESSION['msg']='<p class="alert alert-success" style="text-align:center"><strong>Employee Has Been Successfully Rejected.</strong></p>';
			?>
			<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/secretary/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
			</script>
		<? } 
		else
		{ 
			pg_query('ROLLBACK');
			$_SESSION['msg']='<p class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Employee Rejection Fails.</strong></p>';
			?>
			<script>
			window.location.href="<?=$config['base_url'] ?>page/intra_zp/secretary/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
			</script>
		<? }
		
	}
}
?>