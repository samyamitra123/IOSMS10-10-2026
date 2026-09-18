<?php


session_start();
ob_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'user_profile_view.php';
	exit;
}
else
{
	//var_dump($_SESSION['user_info']['stake_user_code']); die;
	$db = new database();
	$arr_code_suffix = $db->fetch_table("select * from intra_pri_designation_master WHERE designation_code= '".$_POST['designation_code']."' order by designation");
			
			
	//$insert_stake=$_POST['insert_stake'];
	//$block=$_POST['block'];
	$executive_officer_name=strtoupper($_POST['executive_name']);
	$mobile_no=$_SESSION['user_info']['stake_user_mob'];
	$drpSex=$_POST['drpSex'];
	//$desig=$_POST['desig'];
	$email=$_POST['email'];
	//$mobile=$_POST['mobile'];
	
	 $date_join=date("Y-m-d",strtotime($_POST['date_join'])); 
	//$designation_code=$_POST['designation_code'];
	//$checkbox_assign_code=implode(',',$_POST['assign_code']);
	//echo $executive_name.'--'.$stake_code.'----'.'---'.$mobile_no.'---'.$drpSex.'---'.$desig.'------'.$email; die;
	
	
	/*if($_POST['block'] !=''){
		$block=$_POST['block'].$arr_code_suffix[0]['stake_user_code_suffix'];
	}
	else{
		$block= substr($_SESSION['user_info']['stake_user_code'],0,4).$arr_code_suffix[0]['stake_user_code_suffix'];
	}*/
	
	//var_dump($block); die;
	
	//$otp=$_POST['d_pin'];
	//$hashedOTP = hash('sha256', $otp);
	
	//var_dump($block); die;
	
	/*function check_unique_emp_const_id()
	{            
		
        $db = new database();
        
        $check_id=$db->fetch_table("SELECT nextval('intra_pri_officer_id_const_sqn')");
		
		$empid= "OE".date("Y");
      //  $empid= "PE".'2018';
		//$digit=$check_id['0']['emp_id_max']; //privious_code
        $sum=$check_id['0']['nextval'];
        
		//$sum=$digit+1; //privious_code
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		 $id_const=$empid.$inc; 
	
		$unique_check=$db->fetch_table("select officer_id_pk from intra_pri_master where officer_id_const='".$id_const."'");
		
		if(!empty($unique_check))
		{
           
			$this->check_unique_emp_const_id();
            
		}
		else
		{
           
			return $id_const;
		}
	}*/ 
	
	//$officer_id_const= check_unique_emp_const_id();
	//var_dump($officer_id_const);
	
	
	
/////////////// SERVER SIDE VALIDATION  ///////////////////////////////////////////////////////////////////////////////////////////////

	
	if($validator->blank_select($executive_officer_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>NAME OF OFFICER.</strong></div>';
		include 'intra_pri_registration.php';
		exit;
	}
	else if($validator->blank_select($drpSex) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select SEX</strong></div>';
		include 'intra_pri_registration.php';
		exit;
	}
	
	
	else
	{

		$db=new database();
		$lms_query=$db->fetch_table("select mobile_no from intra_pri_master where mobile_no='".$mobile_no."' ");

		
			
				$query=$db->update("UPDATE intra_pri_master SET
												officer_name='$executive_officer_name', 
												sex='$drpSex',
												email_id='$email', 
												date_of_join_prsnt_post='$date_join'
												WHERE mobile_no='".$mobile_no."' ");
			
    	
		if($query)
		{
			/*$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>User profile submitted Successfully. A Secrate PIN has been sent to User Registered Mobile.</strong></div>';*/
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>User profile submitted Successfully.</strong></div>';
			header('Location:user_profile_view.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>User profile submitted failed...</strong></div>';
			header('Location:user_profile_view.php');
		}
	}
}

?>

