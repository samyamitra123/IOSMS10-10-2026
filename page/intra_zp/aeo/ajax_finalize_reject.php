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
	
	
	
	$db=new database();

	$arr=$db->fetch_table("select  mas.emp_pension_status,stake.*
	from prd_employee_master as mas
	inner join prd_stake_epension_employee_profile as stake
	on mas.emp_id_pk=stake.emp_id_fk where stake.emp_id_fk='".$cryptoGraph->decode($id, 4)."' ");

	 $momo_no= $arr[0]['first_momo_no'];
	$wef_date=$arr[0]['first_momo_wef_date'];
	$gp_code=$arr[0]['gp_code'];
	$ps_code=$arr[0]['ps_code'];
	$district_id_fk=$arr[0]['district_id_fk'];
	$zp_id_fk=$arr[0]['zp_id_fk'];
	$pension_stat=$arr[0]['emp_pension_status'];
	$present_memo_no=$arr[0]['present_memo_no'];
	$present_momo_date=$arr[0]['presnt_memo_wef_date'];
	
	
	if($arr[0]['zp_id_fk']!='0')
	 {
		$first_gp_ps_zp_code=$zp_id_fk;
	 }
	 else if($gp_code!='0')
	 {
		  $first_gp_ps_zp_code=$gp_code;
	 }
	 else if($ps_code!='0')
	 {
		  $first_gp_ps_zp_code=$ps_code;
	 }
	if($pension_stat=='0')
	{
		$update_pension='0';
	}
	elseif($pension_stat=='1' || $pension_stat=='2')
	{
		$update_pension='2';
	}
	
		$security =$db->update ("UPDATE prd_stake_epension_employee_profile SET
		status='3'
		where emp_id_fk='".$cryptoGraph->decode($id, 4)."'
		and status in('1','2','3')
		");
		
		$query_unlock=$db->update("UPDATE prd_employee_master SET
		update_status='3',emp_pension_status='".$update_pension."',emp_first_memo_no='".$momo_no."',emp_first_memo_date='".$wef_date."',emp_first_gp_ps_zp_code='".$first_gp_ps_zp_code."',emp_present_memo_no='".$present_memo_no."',emp_present_memo_date='".$present_momo_date."'
		where emp_id_pk='".$cryptoGraph->decode($id, 4)."'
		and update_status in('1','2','3')
		"); 
	
	pg_query('BEGIN');
	if($check['0']['emp_id_const']!='0')
	{
		
		$finalize_update=$db->update("
										UPDATE prd_employee_master
										SET emp_status='".$final_accept."',
										emp_system_code='".$rand_code_nw."',
										emp_unlock_status='0',
										modified_status= 1
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
									emp_unlock_status='0',
									modified_status= 1
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
	
    
    //var_dramp($insert_status);die;
    
	if($finalize_update && $insert_status)
	{ 	
	
	//echo 1222; die;
		pg_query('COMMIT');
		
		$_SESSION['msg']='<p class="alert alert-success" style="text-align:center"><strong>Employee Has Been Successfully Approved.</strong></p>';
		?>
		<script>
		window.location.href="<?=$config['base_url'] ?>page/intra_zp/aeo/employee_list.php?msg=<?=$cryptoGraph->encode($msg,4);?>";
		</script>
	<? } 
	else
	{ 
	
	//echo 233; die;
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