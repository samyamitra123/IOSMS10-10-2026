<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

 
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once ('../../../includes/library/database.class.php');
include_once '../../../includes/library/myvalidation.class.php';
require '../../../includes/library/qrtstr_encrp.php';
include_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

?>
<?php

	if(!isset($_POST['changepassword_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/changepassword/change_password_employee.php");
		exit;
	}else if(isset($_POST['changepassword_submit'])){
		
		$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	//$security_code = htmlentities(strip_tags($_POST['security_code']));
		//var_dump($_POST['security_code']); die;
	if($sec_time_token!=$enc_session){
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:change_password_employee.php');
		exit(0);
		}else{
		
		$msg="";
		 $cryptography=new cryptography();
		
		 $new_pass=$_POST['new_pass'];
		$conf_pass=$_POST['conf_pass'];
		 $id=$cryptography->decode($_POST['id'],4); 
		
		
		$date=date("Y-m-d");
		
		
				if((!$validator->blank_select($new_pass)) ){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert New Password.</strong></div>';
				header('location:change_password_employee.php');
				exit(0);
				
				}
				if((!$validator->blank_select($conf_pass)) ){	
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Confirm Password.</strong></div>';
				header('location:change_password_employee.php');
				exit(0);
				
				}
				
				if($new_pass!=$conf_pass){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password and Confirm Password does not match.</strong></div>';
				header('location:change_password_employee.php');
				exit(0);
				}
		
		
		
		
		
			if($conf_pass==$new_pass)
			{
					
					if($id=='employee')
					{
										
					$db=new database();
					
					
					//echo $new_pass; die;
					$update= $db->update("UPDATE prd_employee_login
									 SET 
									 otp='".$new_pass."',
									 active_status = '1'
									 WHERE emp_id_const='".$_SESSION['user_info']['stake_user']."'
									 AND active_status='0'
								   ");
						if($update==true)
						{
							$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password has been successfully changed.</strong></div>';
							header('location:change_password_employee.php');
							exit(0);
						}
						else
						{
							$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password Update Failed.</strong></div>';
							header('location:change_password_employee.php');
							exit(0);
						}
					}
			}
			else
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New password And Confirm Password does not matched.</strong></div>';
			header('location:change_password_employee.php');
			exit(0);
			}
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>'.$msg.'</strong></div>';
			header('location:change_password_employee.php');
			exit(0);
			
			}
	
	}
	?>
