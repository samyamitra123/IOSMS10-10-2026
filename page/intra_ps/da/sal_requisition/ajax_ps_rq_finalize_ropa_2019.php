<?php


//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

//$cryp = new cryptography();

if (
	!isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];
$salary_monthyear = date('Ym');
/*$arr_emp_cnt = $db->fetch_table("select emp_id_pk from prd_employee_master as emp
	INNER JOIN prd_employee_salary_save as save
	ON emp.emp_id_pk=save.emp_id_fk
	where emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND save.requisition_type='".$requisition_type."'
	AND save.ropa_status ='1'");
	*/
	
	
	$arr_emp_cnt = $db->fetch_table("select emp_id_pk from prd_employee_master as emp
								INNER JOIN prd_employee_salary_save as save
								ON emp.emp_id_pk=save.emp_id_fk
								where emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND save.requisition_type='".$requisition_type."' AND save.status_flag=1 AND save.delete_status=1 AND save.is_saved=1 AND save.ropa_status ='1'  and save.salary_monthyear='".date('Ym')."' 
								
								");
$total_row=count($arr_emp_cnt);

for ($i=0;$i<=$total_row-1;$i++)
        {
            $sql.="'".$arr_emp_cnt[$i]['emp_id_pk']."'";
		   
            if($i!=$total_row-1)
            $sql=$sql.",";
       
	}


/*$upd = $db->update("
					UPDATE prd_employee_salary_save
						SET status_flag = 2
					WHERE
						ps_id_fk = '".$_SESSION['location']['ps_id']."'
						AND emp_id_fk in($sql)  
						AND salary_monthyear='".date('Ym')."'   
						AND status_flag = 1 AND is_saved=1 and delete_status=1 AND ropa_status ='1' 
						AND requisition_type='".$requisition_type."'
					");*/
	
           $upd = $db->update("
					UPDATE prd_employee_salary_save
						SET status_flag = 2
					WHERE
						ps_id_fk = '".$_SESSION['location']['ps_id']."' 
						AND salary_monthyear='".date('Ym')."'   
						AND status_flag = 1 AND is_saved=1 and delete_status=1 AND ropa_status ='1' 
						AND requisition_type='".$requisition_type."'
					");

	$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1) AND approval_status in(5,6)
													AND emp_id_fk in($sql)");

if($upd){
	
	$security = $db->insert("
							INSERT INTO
							prd_salary_log (
												ip,
												date,
												browser,
												os,
												salary_status_id_fk,
												created_by,
												created_by_stake,
												ps_id_fk
									)
							VALUES 			(
												'".$_SESSION['user_agent']['USER_IP']."',
												now(),
												'".$_SESSION['user_agent']['BROWSER']."',
												'".$_SESSION['user_agent']['OS']."',
												2,
												'".$_SESSION['user_info']['stake_user']."',
												'".$_SESSION['user_info']['stake_level']."',
												'".$_SESSION['location']['ps_id']."'	
										)
				
						");
if($upd && $security){
	//echo "in if";
					pg_query("commit"); 
										
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Salary Requisitions has been Sent to EO Successfully.</strong></div>';
					header('location:ps_emp_sal_requisition_ropa_2019.php');
					exit(0);
}
						

} else {
	//echo "in else";
					pg_query("rollback"); 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisitions Not to be sent to EO .</strong></div>';
					header('location:ps_emp_sal_requisition_ropa_2019.php');
					exit(0);
} ?>
