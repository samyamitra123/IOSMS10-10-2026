<?

//print_r($_POST);die;
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';



if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

			$cryptoGraph=new cryptography();
			$sec_time_token=$_POST['sec_tok'];
			$session_token=$_SESSION['security_token'];
			$enc_session=md5('369'.$session_token);
			$emp_count=$_POST['emp_count'];
			$app_order_no_of_dlb=$_POST['app_order_no_of_dlb'];
			$date_of_order=date("Y-m-d", strtotime($_POST['date_of_order']));

if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('location:emp_promotion_entry_view.php');
	exit;
}
else{

			$increment_ty = $cryptoGraph->decode($_POST['increment_type'],4);
			//$pay_band1 = $cryptoGraph->decode($_POST['pay_band'],4);
			//$pay_scale1 = $_POST['pay_scale'];			 
			//$grade_pay1 = $_POST['grade_pay'];
			
			$pay_band = $cryptoGraph->decode($_POST['pay_band'],4);
			if($pay_band == "" || $pay_band == 0){
				$pay_band = 0;
			}
			else{
				$pay_band = $cryptoGraph->decode($_POST['pay_band'],4);
			}
			
			if($_POST['pay_scale'] =="" || $_POST['pay_scale'] == 0){
				$pay_scale = 0;
			}
			else{
				$pay_scale = $_POST['pay_scale'];
			}
			
			if($_POST['grade_pay'] =="" || $_POST['grade_pay']== 0){
				$grade_pay = 0;
			}
			else{
				$grade_pay = $_POST['grade_pay'];
			}
			
			$vice_desig1 = $_POST['vice_desig'];
			$ropa_level = $_POST['ropa_level'];
			$effective_date1=date("Y-m-d",strtotime($_POST['effective_date'])); 
		
			if($increment_ty=='2')
			{
				$vice_desig1 = 0; 
				$cas_type = $cryptoGraph->decode($_POST['cas_type'],4); 
			}
			else
			{
				$vice_desig1 = $_POST['vice_desig']; 
				$cas_type=0;
			}
			$db = new database();
			$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_pk'],4); 
			
			$emp_data = $db->fetch_table("SELECT prd_dise_gradepay_master.grade_amount FROM prd_dise_gradepay_master 
			INNER JOIN prd_employee_master 
			ON prd_dise_gradepay_master.grade_code=trim(prd_employee_master.emp_grade_pay)
			WHERE prd_employee_master.emp_id_pk = '".$emp_id_pk."'");
		
								 //die('1234');
			//$promotion_data = $db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master WHERE grade_code = '".$grade_pay1."'");
	
			if($validator->blank_select($effective_date1) == FALSE || $effective_date1 == "1970-01-01" || $effective_date1 == '--')
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date.</strong><div>';
				header('location:emp_promotion_entry_view.php');
				exit;
			}
			
			if($increment_ty != '1' && $increment_ty != '2')
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Promotion or Career Advancement Scheme.</strong><div>';
				header('location:emp_promotion_entry_view.php');
				exit;
			}
			if($increment_ty== '1')
			{
				if($validator->blank_select($ropa_level) == FALSE  )
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Level.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				/*if($validator->blank_select($pay_band1) == FALSE  )
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Pay Band.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				if($validator->blank_select($pay_scale1) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Pay Scale.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				if($validator->blank_select($grade_pay1) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Grade Pay.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}*/
				if($validator->blank_select($vice_desig1) == FALSE || $vice_desig1==0)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Designation.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
//				if(!($emp_data[0]['grade_amount'] < $promotion_data[0]['grade_amount']) )
//				{
//					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Grade Pay.</strong><div>';
//					header('location:emp_promotion_entry_view.php');
//					exit;
//				}
			}
			else if ($increment_ty== '2')
			
			{
			
				if($validator->blank_select($cas_type) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter CAS Type.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				if($validator->blank_select($ropa_level) == FALSE  )
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Level.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				/*if($validator->blank_select($pay_band1) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter select Band.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				if($validator->blank_select($pay_scale1) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Pay Scale.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				
				if($validator->blank_select($grade_pay1) == FALSE)
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please select Grade Pay.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}
				if(!($emp_data[0]['grade_amount'] <= $promotion_data[0]['grade_amount']) )
				{
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Grade Pay.</strong><div>';
					header('location:emp_promotion_entry_view.php');
					exit;
				}*/
			/*if($validator->blank_select($vice_desig1) == TRUE || $vice_desig1==0)
			{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Wrong Enter Designation.</strong><div>';
			header('location:emp_promotion_entry_view.php');
			exit;
			}*/
			}
		/*if($validator->blank_select($cas_type) == FALSE)
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter CAS Type.</strong><div>';
			header('location:emp_promotion_entry_view.php');
			exit;
		}
*/

	
	

		$db = new database();	
	if($_SESSION['user_info']['stake_abbr'] == 'GP')
	{
		$emp_pre_data = $db->fetch_table("SELECT   
                                        emp_id_pk,
										emp_pay_scale,
										emp_pay_in_payband,
										emp_grade_pay,
										emp_desig,
										emp_pay_band,
										emp_id_const,
										gp_id_fk,
										emp_desig
									FROM
										prd_employee_master 
									WHERE
										emp_id_pk = '".$emp_id_pk."'
										AND gp_id_fk = '".substr($_SESSION['location']['gp_id'],0,7)."'");
										
				$gp_id_fk = $emp_pre_data[0]['gp_id_fk'];
	}
	else if($_SESSION['user_info']['stake_abbr'] == 'DA')
	{
		$emp_pre_data = $db->fetch_table("SELECT   
                                        emp_id_pk,
										emp_pay_scale,
										emp_pay_in_payband,
										emp_grade_pay,
										emp_desig,
										emp_pay_band,
										emp_id_const,
										emp_desig,
										ps_id_fk
										
									FROM
										prd_employee_master 
									WHERE
										emp_id_pk = '".$emp_id_pk."'
										AND ps_id_fk = '".substr($_SESSION['location']['ps_id'],0,7)."'");
										
				
				 $ps_id_fk = $emp_pre_data[0]['ps_id_fk'];
				
	}
	
	/*********************************************** Added by ANJAN for ZP Start ******************************************************************/
	
	else if($_SESSION['user_info']['stake_abbr'] == 'DEALING ASSISTANT (Establishment)')
		{
			$emp_pre_data = $db->fetch_table("SELECT   
											emp_id_pk,
											emp_pay_scale,
											emp_pay_in_payband,
											emp_grade_pay,
											emp_desig,
											emp_pay_band,
											emp_id_const,
											emp_desig,
											zp_id_fk
											
										FROM
											prd_employee_master 
										WHERE
											emp_id_pk = '".$emp_id_pk."'
											AND zp_id_fk = '".substr($_SESSION['location']['district_id'],0,7)."'");
											
					
					 $zp_id_fk = $emp_pre_data[0]['zp_id_fk'];
					
		}

/************************************************* ZP end **********************************************************************************************/		
									
				$emp_pay_band=$emp_pre_data[0]['emp_pay_band']; 
				$emp_pay_in_payband=$emp_pre_data[0]['emp_pay_in_payband'];
				$emp_grade_pay=$emp_pre_data[0]['emp_grade_pay'];
				$emp_pay_scale=$emp_pre_data[0]['emp_pay_scale']; 
				$emp_desig = $emp_pre_data[0]['emp_desig']; 
				$emp_id_const = $emp_pre_data[0]['emp_id_const']; 
				$pre_emp_desig = $emp_pre_data[0]['emp_desig']; 
				 
				
				$increment = $cryptoGraph->decode($_POST['increment_type'],4);
				//$pay_band = $cryptoGraph->decode($_POST['pay_band'],4);
				//$pay_scale = $_POST['pay_scale'];
				//$grade_pay = $_POST['grade_pay'];
				
				
				$effective_date=date("Y-m-d",strtotime($_POST['effective_date']));
				
	if($cryptoGraph->decode($_POST['approval_status'],4) == 99 && $cryptoGraph->decode($_POST['delete_status'],4) == 99)
	{

/*********************************************** Added by ANJAN for ZP Start ******************************************************************/
					  
				if($_SESSION['user_info']['stake_abbr'] == 'GP')
				{
					$gp_id_fk = $gp_id_fk;
					$ps_id_fk = 0;
					$zp_id_fk = 0;
				}
				else if($_SESSION['user_info']['stake_abbr'] == 'DA')
				{
					$gp_id_fk = 0;
					$zp_id_fk = 0;
					$ps_id_fk = $ps_id_fk;
				}
				else if($_SESSION['user_info']['stake_abbr'] == 'DEALING ASSISTANT (Establishment)')
				{
					$gp_id_fk = 0;
					$ps_id_fk = 0;
					$zp_id_fk = $zp_id_fk;
				}

/************************************************* ZP end **********************************************************************************************/
				
				/*$update_delete_status = $db->update("UPDATE prd_employee_promotion_details SET delete_status = '0'
													WHERE emp_id_fk = '".$emp_id_pk."' 
													AND delete_status = '1' and status='1'");*/
				
				$previous_promotion_data = $db->fetch_table("SELECT COUNT(*) AS pre_data FROM prd_employee_promotion_details
													WHERE approval_status in('6') AND delete_status in('1')
													AND emp_id_fk = '".$emp_id_pk."'
													AND promotion_effective_status in('2')");
		
				if($previous_promotion_data[0]['pre_data'] > 0)
				{
				
/*********************************************** Added by ANJAN for ZP Start ******************************************************************/
							 	 
					$copy_data = $db->insert("INSERT INTO  prd_employee_promotion_details_archive (
										  increment_id,
										  emp_id_fk,
										  emp_id_const,
										  gp_id_fk,
										  emp_pay_in_payband,
										  emp_pay_scale,
										  emp_grade_pay,
										  pre_emp_pay_in_payband,
										  pre_emp_grade_pay,
										  who,
										  date,
										  approval_status,
										  promotion_date,
										  increment_type,
										  effective_date,
										  emp_desig,
										  approval_status_by_ddo,
										  delete_status,
										  increment_amount,
										  annual_increment_date,
										  pre_increment_amount,
										  pre_increment_date,
										  pre_effective_date,
										  pre_emp_desig,
										  pre_emp_pay_scale,
										  pre_emp_payband,
										  pre_emp_group,
										  emp_pay_band,
										  cas_type,
										  dop_doi,
										  ps_id_fk,
										  promotion_effective_status,
										  archived_by,
										  archive_date_time,
										  zp_id_fk, ropa_level
										  )
										  
										SELECT increment_id,
										  emp_id_fk,
										  emp_id_const,
										  gp_id_fk,
										  emp_pay_in_payband,
										  emp_pay_scale,
										  emp_grade_pay,
										  pre_emp_pay_in_payband,
										  pre_emp_grade_pay,
										  who,
										  date,
										  approval_status,
										  promotion_date,
										  increment_type,
										  effective_date,
										  emp_desig,
										  approval_status_by_ddo,
										  '0',
										  increment_amount,
										  annual_increment_date,
										  pre_increment_amount,
										  pre_increment_date,
										  pre_effective_date,
										  pre_emp_desig,
										  pre_emp_pay_scale,
										  pre_emp_payband,
										  pre_emp_group,
										  emp_pay_band,
										  cas_type,
										  dop_doi,
										  ps_id_fk,
										  promotion_effective_status,
										  '".$_SESSION['user_info']['stake_user']."',
										  now(),
										  zp_id_fk, ropa_level
										  
								 FROM prd_employee_promotion_details
								 WHERE emp_id_fk='".$emp_id_pk."'
								 AND approval_status in('6') AND delete_status in('1')
								 AND promotion_effective_status in('2')");
	
/************************************************* ZP end **********************************************************************************************/	
							
					if($copy_data)
					{
						$delete_data = $db->delete("DELETE FROM prd_employee_promotion_details 
													WHERE emp_id_fk='".$emp_id_pk."'
								 					AND approval_status in('6') AND delete_status in('1')
													AND promotion_effective_status in('2')");	
					}
				}
				
				if((($previous_promotion_data[0]['pre_data'] > 0) && ($delete_data)) || ($previous_promotion_data[0]['pre_data'] == 0))
				{
				    
					$insert_new_data = 	$db->insert("INSERT INTO prd_employee_promotion_details (
													emp_id_fk,
													emp_id_const,
													gp_id_fk,
													emp_pay_band,
													emp_pay_scale,
													emp_grade_pay,
													who,
													date,
													approval_status,
													effective_date,
													increment_type,
													delete_status,
													emp_desig,
													cas_type,
													ps_id_fk,
													promotion_effective_status,
													pre_emp_desig,
													zp_id_fk, ropa_level
													) VALUES (
													'".$emp_id_pk."',
													'".$emp_id_const."',
													'".$gp_id_fk."',
													'".$pay_band."',
													'".$pay_scale."',
													'".$grade_pay."',
													'".$_SESSION['user_info']['stake_user']."',
													now(),
													'1',
													'".$effective_date."',
													'".$increment."',
													'1',
													'".$vice_desig1."',
													'".$cas_type."',
													'".$ps_id_fk."',
													'1',
													'".$pre_emp_desig."',
													'".$zp_id_fk."',
													'".$ropa_level."'
													)")	;	
				}
	}
	else if( (($cryptoGraph->decode($_POST['approval_status'],4) == 1) || ($cryptoGraph->decode($_POST['approval_status'],4) == 4) || ($cryptoGraph->decode($_POST['approval_status'],4) == 10)) && ($cryptoGraph->decode($_POST['delete_status'],4) == 1))
	{
	   
			if($_SESSION['user_info']['stake_abbr'] == 'GP')
			{
				$gp_or_ps_id_fk = 'gp_id_fk';
				$gp_or_ps_id_fk_val = $gp_id_fk;
			}
			else if($_SESSION['user_info']['stake_abbr'] == 'DA')
			{
				$gp_or_ps_id_fk = 'ps_id_fk';
				$gp_or_ps_id_fk_val = $ps_id_fk;
			}
			
/*********************************************** Added by ANJAN for ZP Start ******************************************************************/
			
			else if($_SESSION['user_info']['stake_abbr'] == 'DEALING ASSISTANT (Establishment)')
			{
				$gp_or_ps_id_fk = 'zp_id_fk';
				$gp_or_ps_id_fk_val = $zp_id_fk;
			}
		
/************************************************* ZP end **********************************************************************************************/
	  
			$insert_new_data = $db->update("UPDATE prd_employee_promotion_details SET
											approval_status = '1',
											emp_pay_band = '".$pay_band."',
											emp_pay_scale = '".$pay_scale."',
											emp_grade_pay = '".$grade_pay."',
											who = '".$_SESSION['user_info']['stake_user']."',
											date = now(),
											effective_date = '".$effective_date."',
											increment_type = '".$increment."',
											emp_desig = '".$vice_desig1."',
											pre_emp_desig='".$pre_emp_desig."',
											cas_type='".$cas_type."',    
											ropa_level='".$ropa_level."'    
											WHERE emp_id_fk = '".$emp_id_pk."'
											AND approval_status in ('1')
											AND ".$gp_or_ps_id_fk." = '".$gp_or_ps_id_fk_val."'
											AND delete_status = '1'
											");									
	}
						
			if($insert_new_data)
			{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Promotion Details Has Been Saved Successfully.</strong></div>';	
				header('location:emp_promotion_entry_view.php');
				exit;
		?>
  	
			<? } else{ 
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Employee Promotion Details Has Not Been Saved. Please Try Again...</strong></div>';
				header('Location:emp_prmotion_entry_view.php');	
				exit;

	?>
   
<? }		
}
@pg_close($con);
?>
