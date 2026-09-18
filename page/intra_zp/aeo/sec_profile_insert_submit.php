<?
ob_start();
session_start();
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';





if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "../../page/login.php");
	exit;
}
if (
    !(($_SESSION['privilege']['01'] == TRUE) 
  || ($_SESSION['privilege']['0101'] == TRUE)
  || ($_SESSION['privilege']['0102'] == TRUE)
  || ($_SESSION['privilege']['0103'] == TRUE)
  || ($_SESSION['privilege']['0104'] == TRUE)
  || ($_SESSION['privilege']['07'] == TRUE)
  || ($_SESSION['privilege']['0701'] == TRUE)
  || ($_SESSION['privilege']['0702'] == TRUE)
  || ($_SESSION['privilege']['0703'] == TRUE)
  || ($_SESSION['privilege']['56'] == TRUE)
  || ($_SESSION['privilege']['5601'] == TRUE)
  || ($_SESSION['privilege']['5602'] == TRUE)
  || ($_SESSION['privilege']['5603'] == TRUE)
  || ($_SESSION['privilege']['59'] == TRUE)
  || ($_SESSION['privilege']['5901'] == TRUE)
  || ($_SESSION['privilege']['5902'] == TRUE)
  || ($_SESSION['privilege']['5903'] == TRUE)
  || ($_SESSION['privilege']['5904'] == TRUE))
  ){
  header('Location: '. $config['base_url'] . "page/dashboard.php");
  exit;
}

error_reporting(0);
/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Dashboard | WBULBHRMS | Govt. of West Bengal";

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$new_deo_id =  $_POST['deo_id'];
//$_SESSION[deo_id1];
?>
<link href="<?php echo $config['base_url']; ?>page/municipality_admin/assets/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $config['base_url']; ?>page/municipality_admin/assets/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>page/municipality_admin/assets/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script> 

  <div id="myModal" class="modal fade">
    <div class="modal-dialog"><p></p>
        <div class="modal-content">
            <div class="modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
             <div class="form-group">
          
                      <h4>Password Successfully Generated, Please Provide The Login Id: <?= $new_deo_id ?> And Password to The Concerned DEO.</h4>
                    </div>
                    <div align="right">
                    <a href="reset_password_for_ddo.php?confirm=<?=$cryptoGraph->encode('success_deo',7)?>">
                      <button type="button" class="btn btn-success" data-dismiss="">OK</button> </a>
                   </div>
            </div>
        </div>
    </div>
</div>

    <div id="myModall" class="modal fade">
        <div class="modal-dialog"><p></p>
            <div class="modal-content">
                <div class="modal">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                 <div class="form-group">
              
                          <h4>Password Successfully Generated, Please Provide The Login Id: <?= $new_deo_id ?>  And Password to The Concerned DEO.</h4>
                        </div>
                        <div align="right">
                        <a href="deo_profile_edit.php?confirm=<?=$cryptoGraph->encode('success_deo',7)?>">
                          <button type="button" class="btn btn-success" data-dismiss="">OK</button> </a>
                       </div>
                </div>
            </div>
        </div>
    </div>




<?php
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again.</strong></div>';
	include 'deo_profile_insert.php';
	exit;
}
else{
$ddo_name=strtoupper($_POST['ddo_name']);
$ddo_email=$_POST['ddo_email'];
$deo_id=$_POST['deo_id'];
$ddo_mobile=$_POST['ddo_mobile'];
$new_pass=$_POST['new_pass'];
$conf_pass=$_POST['conf_pass'];

$original_deo_code = $_SESSION['deo2_code'];

$block_code = $_SESSION['location']['block_code'];
$dist_name = $_SESSION['location']['district_name'];
$block_name = $_SESSION['location']['block_name'];

 			if($validator->blank_select($deo_id) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter User Id.</strong></div>';
			include 'deo_profile_insert.php';
			exit;
			}
			else if($deo_id != $original_deo_code)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid User Id.</strong></div>';
			unset($_SESSION['deo2_code']);
			include 'deo_profile_insert.php';
			exit;
			}
			else if($validator->blank_select($ddo_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter DEO Name.</strong></div>';
			include 'deo_profile_insert.php';
			exit;
			}
			else if($validator->pattern_math_chcarecter($ddo_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character In DEO Name.</strong></div>';
			include 'deo_profile_insert.php';
			exit;
			}
			
		
		if($ddo_email!='' || $ddo_email!=NULL){
			if($validator->pattern_match_email($ddo_email)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Email.</strong></div>';
				//$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'deo_profile_insert.php';
				exit;
			}
		}
		if($ddo_email==''){
			    $error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Email Id.</strong></div>';
				//$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'deo_profile_insert.php';
				exit;
		}
			
		if($ddo_mobile!='' || $ddo_mobile!=NULL){
			if($validator->pattern_number($ddo_mobile)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Mobile Number.</strong></div>';
				//$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'deo_profile_insert.php';
				exit;
			}
		}
		if($ddo_mobile==''){
			    $error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Number.</strong></div>';
				//$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'deo_profile_insert.php';
				exit;
		}
		//password validation start
		
		if((!$validator->blank_select($new_pass)) ){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert New Password.</strong></div>';
			header('location:deo_profile_insert.php');
			exit(0);

		}
		if((!$validator->blank_select($conf_pass)) ){	
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Confirm Password.</strong></div>';
			header('location:deo_profile_insert.php');
			exit(0);

		}
	
		if($new_pass!=$conf_pass){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password And Confirm Password Does Not Match.</strong></div>';
			header('location:deo_profile_insert.php');
			exit(0);
		}
		
		//password validation end
		
/*-----------------------------------------------Code_Master Checking----------------------------------------------*/

/*--------------------------------------------------END------------------------------------------------------------*/
	
else{
$db = new database();
 $cryptography=new cryptography();
	 //echo $old_pass."<br>";exit;
	 $new_pass=sha1($new_pass);
	

 $block_code = $_SESSION['location']['block_code'];
 $dist_name = $_SESSION['location']['district_name'];
 $block_name = $_SESSION['location']['block_name'];
 //$db = new database();
 $data10 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM mad_location_master_deo where block_id_fk ='".$_SESSION ['location']['block_id_pk']."'");
// echo $_SESSION ['location']['block_id_pk'];
$count10 = $data10[0][result_count];
//die;
$arr = $db->fetch_table("select municipality_code, block_id_pk,block_code from mad_location_master_municipality where block_code='".$block_code."'");
 $m_code = $arr[0]['municipality_code'];
 $block1_code = $arr[0]['block_id_pk'];
  $block_login_back_code = $arr[0]['block_code'];
$arr1 = $db->fetch_table("select max(gp_code) as entry_rec_tchcd, max(deo_code) as entry_rec_tchcd1 from mad_location_master_deo where block_id_fk='".$block1_code."'");
if($count10<"1"){
 $block12_code = $arr1[0]['entry_rec_tchcd'];
 $block121_code = $arr1[0]['entry_rec_tchcd1'];
 $deo1_code =$m_code.'01';
 $gp_code_max = $block_login_back_code.'001';
	}
	else
	{
 $block12_code = $arr1[0]['entry_rec_tchcd'];
 $block121_code = $arr1[0]['entry_rec_tchcd1'];
  $deo1_code = $block121_code + '1'; 
 $gp_code_max = $block12_code + '1';
	}
 
						 
								 
$municipality_id_fk = $_SESSION['user_info']['stake_user'];											 
$query_insert=$db->insert("INSERT into mad_location_master_deo
											(											
				                             
											 block_id_fk,
											 gp_code,   
											 gp_name,
											 deo_flag,
											 deo_code,
											 deo_email,
											 deo_mobile,
											 municipality_id_fk
											 )
											 VALUES(
											 										 
											 '$block1_code',
											 '$gp_code_max',
											 '$ddo_name',
											 '2',
											 '$deo1_code',
											 '$ddo_email',
											 '$ddo_mobile',
											 '".$municipality_id_fk."')");
											
											
	$query1_insert=$db->insert("INSERT into mad_stack_user_login
											(											
												
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 email_id,
											 mobile_no
											 )
											 VALUES(
																						 
											 '65',
											 '$gp_code_max',
											 '$new_pass',
											 '1',
											 '$ddo_email',
											 '$ddo_mobile')");
											 


if($query_insert){

 $_SESSION[deo_id1] = $deo1_code;
 $end = substr($_SESSION[deo_id1], -2);
//if($end=="01")
//{
	
 $db = new database();
	    $data10 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM mad_location_master_deo where block_id_fk ='".$_SESSION ['location']['block_id_pk']."'");

	  $count10 = $data10[0][result_count];
	 // die;

if ($count10 < "2")
  {
		echo '<script type="text/javascript">'; 
		echo '$(window).load(function(){';
		echo '$("#myModal").modal("show");';
		echo '});';
					
		//echo 'alert("Password Successfully Generated, Please Provide The Login Id: '.$_SESSION[deo_id1].' And Password to The Concerned DEO.");'; 
		//echo 'window.location.href = "reset_password_for_ddo.php?confirm=success";';
		//echo "window.location.href = 'reset_password_for_ddo.php?confirm=confirm=".$cryptoGraph->encode(success_deo,7)."';";
		echo '</script>';
	?>
    <script>
	/*function show()
	{
		$(document).ready(function(){
        $("#alert").modal();
		});
		alert('Hi');
	}*/
	</script>
    
    <?
//header('Location:reset_password_for_ddo.php?confirm=success');
exit(0);
  }
  else{
	  //echo $count10;
		  echo '<script type="text/javascript">';
		  echo '$(window).load(function(){';
		  echo '$("#myModall").modal("show");';
		  echo '});';
	  
		//echo 'alert("Password Successfully Generated, Please Provide The Login Id: '.$_SESSION[deo_id1].' And Password to The Concerned DEO.");'; 
		//echo 'window.location.href = "deo_profile_edit.php?confirm=success";';
		//echo "window.location.href = 'deo_profile_edit.php?confirm=".$cryptoGraph->encode(success_deo,7)."';";
		echo '</script>';
	  ?>
      <script>
		/*$(document).ready(function(){
        $("#alert").modal();
		});
		alert('Hi');*/
	  </script>
      <?	
	  exit(0);
	  }

}
else{
	header('Location:deo_profile_insert.php?confirm="'.$cryptoGraph->encode(fails,7).'"');
//header('Location:deo_profile_insert.php?confirm=false');
exit(0);
}
}
}

?>
