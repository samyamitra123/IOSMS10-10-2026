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
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_REQUEST['emp_id_pk'],4);
	include 'profile_ret_entry_basic_edit_form.php';
	exit;
}
else{
/*error_reporting(0);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
$emp_id_pk=$_REQUEST['emp_id_pk'];
$emp_date_retirement=$_REQUEST['emp_date_retirement'];
$fname=strtoupper($_REQUEST['tch_fname']);
$mname=strtoupper($_REQUEST['tch_mname']);
$lname=strtoupper($_REQUEST['tch_lname']);
$dob=date("Y-m-d",strtotime($_REQUEST['tch_dob']));
$drpSex=$_REQUEST['drpSex'];
$drpCast=$_REQUEST['drpCast'];
$voter_id=$_REQUEST['voter_id']; 
$aadhar_status=$_POST['aadhar_status'];
if($aadhar_status=='0')
{
	$aadhaar_no="";
}
else
{
	$aadhaar_no=$_REQUEST['aadhaar_no'];
}
$emp_quali=$_REQUEST['emp_quali'];

			if($validator->blank_select($fname) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter First Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->pattern_math_chcarecter($fname) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in First Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($mname) == TRUE && $validator->pattern_math_chcarecter($mname) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Middle Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($lname) == TRUE && $validator->pattern_math_chcarecter($lname) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Last Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($dob) == FALSE || $dob == '0001-01-01')
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid DOB.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($dob) == TRUE && $validator->valid_age($dob) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Date Of Birth must be greater than 18 year.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($drpSex) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Gender.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($drpCast) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Caste.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($voter_id) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Voter ID.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->blank_select($aadhar_status) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Aadhaar Card Present or not.</strong></div>';
				include 'profile_entry_basic_form.php';
				exit;
			}
			else if($validator->blank_select($aadhar_status) == FALSE && $validator->blank_select($aadhaar_no) == TRUE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Aadhaar Card Present or not.</strong></div>';
				include 'profile_entry_basic_form.php';
				exit;
			}
			else if($aadhar_status == '1' && $validator->blank_select($aadhaar_no) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Aadhaar No.</strong></div>';
				include 'profile_entry_basic_form.php';
				exit;
			}
			else if($validator->blank_select($emp_quali) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Educational Qualification.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_basic_edit_form.php';
			exit;
			}
			else if($validator->pattern_number(trim($aadhaar_no)) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Aadhar Number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
			else if($validator->blank_select($aadhaar_no) == TRUE && strlen($aadhaar_no) !=12)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Aadhar Number Must be 12 digit long.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
			/*-----------------------------------------------Code_Master Checking--------------------------------------------------------------------*/

			$db = new database();
			$voterid_check=$db->fetch_table("select emp_id_pk,emp_voter_id from prd_employee_master where emp_voter_id !=
			(select emp_voter_id from prd_employee_master where emp_id_pk='$emp_id_pk' and 
			 gp_id_fk='".$_SESSION['location']['gp_id']."')");
			 
			 $aadhar_check=$db->fetch_table("select emp_id_pk,emp_aadhar_no from prd_employee_master where emp_aadhar_no !=
			(select emp_aadhar_no from prd_employee_master where emp_id_pk='$emp_id_pk' and 
			 gp_id_fk='".$_SESSION['location']['gp_id']."')");

			$code_data = $db->fetch_table("
											SELECT code, description
											FROM prd_dise_code_master;
	
										");
			
			if($validator->blank_select($voter_id) == TRUE)
			{
				foreach($voterid_check as $key){
					if(strtoupper($voter_id) == strtoupper($key['emp_voter_id']))
					{
						$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Voter ID already exists. Please Enter valid Voter ID.</strong></div>';
						$emp_id=$cryptoGraph->encode($emp_id_pk,4);
						include 'profile_entry_basic_edit_form.php';
						exit;
					}
				
			  	}
			}
			if($validator->blank_select($aadhaar_no) == TRUE)
			{
				foreach($aadhar_check as $key){
					if($aadhaar_no == $key['emp_aadhar_no'])
					{
						$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Aadhaar ID already exists. Please Enter valid Aadhaar ID.</strong></div>';
						$emp_id=$cryptoGraph->encode($emp_id_pk,4);
						include 'profile_entry_basic_edit_form.php';
						exit;
					}
				
			  	}
			}
			if($validator->code_match($drpCast,$code_data) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Caste Selection.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
			if($validator->code_match($drpSex,$code_data) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Gender Selection.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
			if($validator->code_match($emp_quali,$code_data) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Educational Qualification Selection.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
/*--------------------------------------------------END-------------------------------------------------------------------------*/

else{
$db = new database();	
if(!empty($emp_date_retirement)){
$retire_date=explode('-',$_REQUEST['emp_date_retirement']);
$emp_retirement_date=$retire_date[2].'-'.$retire_date[1].'-'.$retire_date[0];
$query_insert=$db->update("UPDATE prd_employee_master SET
											 emp_first_name='$fname',
											 emp_second_name='$mname',   
											 emp_last_name='$lname',
											 emp_dob='$dob',
											 emp_sex='$drpSex',
											 emp_caste='$drpCast',
											 emp_voter_id='".strtoupper($voter_id)."',
											 emp_aadhar_no='$aadhaar_no',
											 emp_edu_quali='$emp_quali',
											 emp_retirement_date='$emp_retirement_date',
											 entry_time='now()',
											 entry_ip='".$_SERVER['REMOTE_ADDR']."'
										WHERE emp_id_pk='$emp_id_pk'"
											 );
}else{
$query_insert=$db->update("UPDATE prd_employee_master SET
											 emp_first_name='$fname',
											 emp_second_name='$mname',   
											 emp_last_name='$lname',
											 emp_dob='$dob',
											 emp_sex='$drpSex',
											 emp_caste='$drpCast',
											 emp_voter_id='".strtoupper($voter_id)."',
											 emp_aadhar_no='$aadhaar_no',
											 emp_edu_quali='$emp_quali',
											 entry_time='now()',
											 entry_ip='".$_SERVER['REMOTE_ADDR']."'
										WHERE emp_id_pk='$emp_id_pk'"
											 );	
}

/*echo "UPDATE prd_employee_master SET
											 emp_first_name='$fname',
											 emp_second_name='$mname',   
											 emp_last_name='$lname',
											 emp_dob='$dob',
											 emp_sex='$drpSex',
											 emp_caste='$drpCast',
											 emp_voter_id='$voter_id',
											 emp_aadhar_no='$aadhaar_no',
											 emp_edu_quali='$emp_quali',
											 entry_time='now()',
											 entry_ip='".$_SERVER['REMOTE_ADDR']."'
										WHERE emp_id_pk='$emp_id_pk'";
exit;*/
if($query_insert){
	if($emp_date_retirement!=''){
header('Location:profile_entry_prof.php?emp_date_retirement='.$cryptoGraph->encode($emp_date_retirement,4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
exit(0);
	}
	else{
		header('Location:profile_ret_entry_prof.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
exit(0);
	}
}
else{
header('Location:profile_ret_entry_basic_edit.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=false');
exit(0);
}
}
}
?>