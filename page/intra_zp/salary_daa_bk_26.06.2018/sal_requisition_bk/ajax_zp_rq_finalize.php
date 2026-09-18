<?php

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';




//--------------PHP MAIL-----------------------

require '../../../../includes/third-party/PHPMailer/class.phpmailer.php';
require '../../../../includes/third-party/PHPMailer/PHPMailerAutoload.php';
require_once '../../../all_function/mail/mail_fun.php';


//---------------------------------------------


//require_once '../../../../includes/library/cryptography.class.php';
//require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

//$cryp = new cryptography();

if (
	!isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$arr_emp_cnt = $db->fetch_table("select emp_id_pk from prd_employee_master as emp
	INNER JOIN prd_employee_salary_save as save
	ON emp.emp_id_pk=save.emp_id_fk
	where emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND save.requisition_type='".$requisition_type."'");
$total_row=count($arr_emp_cnt);

for ($i=0;$i<=$total_row-1;$i++)
        {
            $sql.="'".$arr_emp_cnt[$i]['emp_id_pk']."'";
		   
            if($i!=$total_row-1)
            $sql=$sql.",";
       
	}

	
           $upd = $db->update("
					UPDATE prd_employee_salary_save
						SET status_flag = 2
					WHERE
						ps_id_fk = '".$_SESSION['location']['ps_id']."'
						AND emp_id_fk in($sql)    
						AND status_flag = 1 AND is_saved=1 and delete_status=1 AND requisition_type='".$requisition_type."'
					");

	$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
													SET promotion_effective_status = '2'
													WHERE promotion_effective_status in(1)
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
					
					

		
//$gp_info = $db->fetch_table("select  
//									gp.email_id,
//									gp.mobile_no,
//									gp.gram_pradhan_name 
//									from prd_gp_profile gp 
//where gp.gp_id_fk='".$_SESSION['location']['gp_id']."' ");
//
// $mail_body  = 'Dear '.$gp_info['0']['gram_pradhan_name'].' '.'<p>The Salary Requisition has been successfully Finalized.  </p> <hr><b>Disclaimer:</b>This is a system generated mail.Please do not reply.';
//
//$email=$gp_info['0']['email_id'];
//$emp_sub='GP Salary Finalized';

//mail_function($mail_body,$email,$emp_sub);
	
	
					
					
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Salary Requisitions has been Sent to EO Successfully.</strong></div>';
					header('location:ps_emp_sal_requisition.php');
					exit(0);
}
						

} else {
	//echo "in else";
					pg_query("rollback"); 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisitions Not to be sent to EO .</strong></div>';
					header('location:ps_emp_sal_requisition.php');
					exit(0);
} ?>
