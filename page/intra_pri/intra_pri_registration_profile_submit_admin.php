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
	include 'intra_pri_registration.php';
	exit;
}
else
{
	
	
	$insert_stake=$_POST['insert_stake'];
	$stake_user_code=$_POST['block'];
	$district=$_POST['district'];
	$executive_officer_name=strtoupper($_POST['executive_name']);
	$mobile_no=$_POST['mobile_no'];
	$drpSex=$_POST['drpSex'];
	$desig=$_POST['desig'];
	$email=$_POST['email'];
	$date_join=date('Y-m-d',strtotime($_POST['date_join']));
	$designation_code=$_POST['designation_code'];
	$checkbox_assign_code=implode(',',$_POST['assign_code']);
	
	$otp=$_POST['d_pin'];
	$hashedOTP = hash('sha256', $otp);
	//echo $executive_name.'--'.$stake_code.'----'.'---'.$mobile_no.'---'.$drpSex.'---'.$desig.'------'.$email; die;
	
	
	
	$db = new database();
	$desig_role_query =$db->fetch_table("select forwarding_level, stake_user_code_suffix from intra_pri_designation_master where designation_code='".$designation_code."' ");

	$stake_user_code_suffix = $district.$desig_role_query[0]['stake_user_code_suffix'];
	$desig_role_explode = explode(',',$desig_role_query[0]['forwarding_level']);
	//var_dump($desig_role_explode); die;
	
	$forward_desig =array();
	for($i =0; $i< count($desig_role_explode); $i++){
		$db = new database();
		$desig_role_add =$db->fetch_table("select stake_user_code_suffix from intra_pri_designation_master where designation_code='".$desig_role_explode[$i]."' ");
		$forward_desig[] = $district.$desig_role_add[0]['stake_user_code_suffix'];
	}
	
	$forward_desig_implode = implode(',',$forward_desig);
	//var_dump($forward_desig_implode);
	//die;
	
	$district_id_fk_query =$db->fetch_table("select district_id_pk from prd_location_master_district WHERE district_code ='".$district."' order by district_name");

	if($district_id_fk_query[0]['district_id_pk'] !='' || $district_id_fk_query[0]['district_id_pk'] != NULL){ 
			$district_id_pk = $district_id_fk_query[0]['district_id_pk']; 
		}
		else{
			$district_id_pk = 0;
		}
	
	//var_dump($district_id_pk); die;
	
	function check_unique_emp_const_id()
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
	} 
	
	$officer_id_const= check_unique_emp_const_id();
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
	
	else if($validator->blank_select($mobile_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile NO</strong></div>';
		include 'intra_pri_registration.php';
		exit;
	}
	
	
	
	
	else
	{

		$db=new database();
		$lms_query=$db->fetch_table("select mobile_no from intra_pri_master where mobile_no='".$mobile_no."' ");

		if(empty($lms_query))
		{

										
			$query=$db->insert("INSERT INTO intra_pri_master
								(
									officer_name,
									sex,
									designation, 
									mobile_no, 
									email_id , 
									date_of_join_prsnt_post,  
									stake_level_code, 
									stake_user_code, 
									district_id_fk, 
									higher_authority_code, 
									ip, 
									user_pin,
									active_status,
									officer_id_const,login_level_stake, role_assign, forwarding_user)
									VALUES 
										(
										'$executive_officer_name','$drpSex',
										'$desig',
										'$mobile_no',
										'$email',
										'$date_join',
										'$designation_code','$stake_user_code_suffix',
										'".$district_id_pk."',
										'00',
										'".$_SERVER['REMOTE_ADDR']."',
										'".$hashedOTP."',
										'0','$officer_id_const','$insert_stake','$checkbox_assign_code','$forward_desig_implode')");
						
		} 
		else
		{
			//echo 2333; die;
			
				$query=$db->update("UPDATE intra_pri_master SET
												officer_name='$executive_officer_name', 
												sex='$drpSex',
												designation='$desig' , 
												mobile_no='$mobile_no',
												email_id='$email', 
												date_of_join_prsnt_post='$date_join',
												stake_level_code='$designation_code',
												stake_user_code='$stake_user_code', 
												district_id_fk='0' , 
												higher_authority_code='00' , 
												ip='".$_SERVER['REMOTE_ADDR']."',
												user_pin='".$hashedOTP."',
												active_status='1',
												officer_id_const='$officer_id_const',
												login_level_stake='$insert_stake',
												role_assign='$checkbox_assign_code'
												WHERE mobile_no='".$mobile_no."' ");
			
    	}
		
		if($query)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>User profile submitted Successfully. A Secrate PIN has been sent to User Registered Mobile.</strong></div>';
			header('Location:intra_pri_registration_admin.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>User profile submitted failed...</strong></div>';
			header('Location:intra_pri_registration_admin.php');
		}
	}
}

?>

