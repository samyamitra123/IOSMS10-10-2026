<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../includes/config/config.php';
require_once '../includes/config/database.config.php';
include_once ('../includes/library/database.class.php');
include_once '../includes/library/myvalidation.class.php';
require '../includes/library/qrtstr_encrp.php';
include_once '../includes/library/cryptography.class.php';
//require 'page_visite.php';
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

?>


<!--
  <div id="myModal" class="modal fade">
    <div class="modal-dialog"><p></p>
        <div class="modal-content">
            <div class="modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
             <div class="form-group">
          
                      <h4>System Generated Default Password has been Successfully Changed. Please Login with Username and New Password.</h4>
                    </div>
                    <div align="right">
                    <a href='login.php'>
                      <button type="button" class="btn btn-success" data-dismiss="">OK</button> </a>
                   </div>
            </div>
        </div>
    </div>
</div>
-->




<?php
	/*echo md5($_SESSION['security_code']); 
	echo "<br>";
	echo $_POST['security_code'];
	die;*/
	if(!isset($_POST['forgetpassword_submit'])){
		//echo "ddd";
		//die;
		header('Location: '. $config['base_url'] . "page/aeo_change_password.php");
		exit;
	}
	else if(isset($_POST['forgetpassword_submit'])){
	
	$new_pass=$_POST['new_pass'];
	$conf_pass=$_POST['conf_pass'];
	$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	$security_code = htmlentities(strip_tags($_POST['security_code']));
		
	if($sec_time_token!=$enc_session){
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:aeo_change_password.php');
		exit(0);
	}
else{
	if (isset($security_code)) {
	if ($security_code == "") {
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('aeo_change_password.php');
		exit(0);

	} 
	
	else if (md5($_SESSION['security_code']) == $security_code) {
	
	 if((!$validator->blank_select($new_pass)) ){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert New Password.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);

		}
	 if((!$validator->blank_select($conf_pass)) ){	
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Confirm Password.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);

		}
	/*else if($validator->valid_pass($new_pass) == FALSE)
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Strong Password.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);
		}*/
		/*if($new_pass==$old_pass){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password should not be old password.</strong></div>';
			header('location:forget_password_form_submit.php');
			exit(0);
		}*/
	 if($new_pass!=$conf_pass){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password and Confirm Password does not match.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);
		}
	}
	
	else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);
	}
	


	/*else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
			header('location:aeo_change_password.php');
			exit(0);
	}*/

	//echo "nnn";
    if($conf_pass==$new_pass)
	{
	
	// unset($_SESSION['otp_code']);
							  	
	 $db=new database();
	 $cryptography=new cryptography();
	 //echo $old_pass."<br>";exit;
	  $new_pass=sha1($new_pass);
	  $stake_level = $_SESSION['password_stake_level'];
	  $stake_username = $_SESSION['password_username'];
						
						   	$update= $db->update("UPDATE prd_stack_user_login
		                     SET 
							 stake_password='".$new_pass."',
							 password_status='1'
							 WHERE stake_user_alias='".$stake_username."'
							 AND stake_level_id_fk ='".$stake_level."'
							 AND password_status='0'
							");
						
				if($update)
				{
					unset($_SESSION['password_stake_level']);
					unset($_SESSION['password_username']);
					//unset($_SESSION['district_name']);
					//header('location:aeo_change_password.php');
					$cryptoGraph=new cryptography();
					//echo "jjj";
					header('Location:'. $config['base_url'].'page/aeo_change_password.php?confirm='.$cryptoGraph->encode('success_re_pas',7)) ;
				   /* echo '<script type="text/javascript">'; 
					
					echo '$(window).load(function(){';
					
						  echo '$("#myModal").modal("show");';
						echo '});';
					
					echo '</script>';
					exit(0);*/
				}
				else
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password Updation Failed.</strong></div>';
					header('location:aeo_change_password.php');
					exit(0);
				}
	}
	else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New password And Confirm Password does not matched.</strong></div>';
		  /*unset($_SESSION['username']);
		  unset($_SESSION['mobile']);
		  unset($_SESSION['otp_code']);*/
		header('location:aeo_change_password.php');
		
		exit(0);
	}
/*		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>'.$msg.'</strong></div>';
		  unset($_SESSION['username']);
		  unset($_SESSION['mobile']);
		  unset($_SESSION['otp_code']);
		header('location:aeo_change_password.php');
		
		exit(0);*/
	}
	
	
	else
	{
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:aeo_change_password.php');
		exit(0);
	}
	
  }
	
}

	?>
