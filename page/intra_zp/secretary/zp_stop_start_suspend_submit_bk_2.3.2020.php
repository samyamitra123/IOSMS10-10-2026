<?php


ob_start();
session_start();
//error_reporting(0);
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
require '../../../includes/library/myvalidation.class.php';


$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//print_r($_REQUEST); die;
//--------------------------------------------------------------QUERY---------------------------------------------------------
function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
				return $key['description'];
			}
		}
	}
function addzero($val){
	if(strlen($val) == 1){
		return '0'.$val;
	}
	else {
		return $val;
	}
}

$db = new database();
if($crypto->decode($_REQUEST['id'], 4) == "" || $crypto->decode($_REQUEST['id'], 4) == NULL)
{ ?>
    <div class="ui-state-error ui-corner-all">
    	<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> Wrong Data inserted</p>
    </div>
<?php		
} 
else 
{
	
////////////////////////////////////////////////////////////////////////////////////////// STOP SALARY /////////////////////////////////////////////////////////////////////////////////////////////

	
	if($_REQUEST['flag'] == 'stop')
	{
		//echo 11; die;

		if($_REQUEST['reason_date']=="")
		{
			$reason_date="0001-01-01";
		}
		else
		{
			$reason_date=date("Y-m-d", strtotime($_REQUEST['reason_date']));
			$next_month = date("Y-m-01", strtotime("$reason_date +1 month")); 
		}
		
		if(!$validator->blank_select($_POST['reason']) )
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please choose reason!!.</strong></div>';exit;
		}
		
		
		else 
		{
			
			$stop_reason_insert = $db->insert("INSERT INTO
												prd_stop_sal_reason 
												(
													ip_address,
													date,
													stopped_by,
													reason,
													
													reason_text,
													emp_id_fk,
													auto_stop_status,
													gp_id_fk,
													ps_id_fk,
													zp_id_fk
												)
												VALUES
												(
													'".$_SESSION['user_agent']['USER_IP']."',
													now(),
													'".$_SESSION['user_info']['stake_user']."',
													'".$_POST['reason']."',
													
													'".$_POST['reason_sus']."',
													". $crypto->decode($_REQUEST['id'],4).",
													0,
													0,
													0,
													'".$_SESSION['location']['district_id']."'
												)");
			
			if($stop_reason_insert)
			{
				$stop = $db->update("UPDATE prd_employee_master SET emp_status = 2 WHERE emp_id_pk = ". $crypto->decode($_REQUEST['id'],4)."");
			}
		}

		if($stop)
		{
		
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
									gp_id_fk,
									emp_id_fk,
									ps_id_fk,
									zp_id_fk
									
									)
									VALUES 			(
									'".$_SESSION['user_agent']['USER_IP']."',
									now(),
									'".$_SESSION['user_agent']['BROWSER']."',
									'".$_SESSION['user_agent']['OS']."',
									2,
									'".$_SESSION['user_info']['stake_user']."',
									'".$_SESSION['user_info']['stake_level']."',
									'0',
									".$crypto->decode($_REQUEST['id'], 4).",
									'0',
									'".$_SESSION['location']['district_id']."'
									)
									");	
			
			if($security)
			{
				echo '<div class="alert alert-success" style="text-align:center"><strong>Salary has been stopped successfully.</strong></div>';exit;
			} 
			else 
			{
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Stop Salary fails.</strong></div>';exit;
			}
		}
		 
		else 
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select reason!!.</strong></div>';exit;
		}

	} 
	
	
	
////////////////////////////////////////////////////////////////////////////////////////// START SALARY /////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	elseif ($_REQUEST['flag'] == 'start') 
	{
		//print_r($_REQUEST);die;
		//echo 11; die;
		
		$start = $db->update("
										UPDATE prd_employee_master
										SET
										emp_status = 1
										WHERE emp_status!=1 AND emp_id_pk = ". $crypto->decode($_REQUEST['id'],4).";
		");
		
		
		
		
		$security = $db->insert("
									INSERT INTO
									prd_salary_log 
									(
										ip,
										date,
										browser,
										os,
										salary_status_id_fk,
										created_by,
										created_by_stake,
										gp_id_fk,
										emp_id_fk,
										ps_id_fk,
										zp_id_fk
									)
									VALUES 
									(
										'".$_SESSION['user_agent']['USER_IP']."',
										now(),
										'".$_SESSION['user_agent']['BROWSER']."',
										'".$_SESSION['user_agent']['OS']."',
										1,
										'".$_SESSION['user_info']['stake_user']."',
										'".$_SESSION['user_info']['stake_level']."',
										'0',
										". $crypto->decode($_REQUEST['id'], 4).",
										'0',
										'".$_SESSION['location']['district_id']."'
									);
								");	
		
		if($start )
		{
			echo '<div class="alert alert-success" style="text-align:center"><strong>Started Salary successfully!!.</strong></div>';exit;
		} 
		else 
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Start Salary error!!.</strong></div>';exit;
		}
	} 
	
	
	
////////////////////////////////////////////////////////////////////////////////////////// SUSPEND SALARY /////////////////////////////////////////////////////////////////////////////////////////////


	
	elseif ($_REQUEST['flag'] =='suspend') 
	{

		$suspention_start_date=date("Y-m-d", strtotime($_REQUEST['suspention_effect_date']));
		
		if(date("Y-m-d", strtotime($_REQUEST['suspention_effect_date']))==date('Y-m-01',strtotime($_REQUEST['suspention_effect_date'])))
		{
			$suspention_effect_date=date("Y-m-d", strtotime($_REQUEST['suspention_effect_date']));
		}
		else
		{
			$suspention_effect_date = date('Y-m-01', strtotime('+1 month', strtotime($suspention_start_date)));
		}

		
		if($_REQUEST['suspend_withdrawn_date']=="")
		{
			$suspend_withdrawn_date="0001-01-01";
		}
		else
		{
			$suspend_withdrawn_date=date("Y-m-d", strtotime($_REQUEST['suspend_withdrawn_date']));
		}	
			
				
				
		$update_delete = $db->update("
										UPDATE prd_suspend_dts
										SET
										delete_status = 1
										WHERE emp_id_fk = ". $crypto->decode($_REQUEST['id'],4)." AND delete_status=0;
		
		");
				
				
		$suspend_dts= $db->insert("
								INSERT INTO prd_suspend_dts 
								(
									gp_id_fk,
									emp_id_fk,
									suspend_start_date,
									suspend_withdrawn_date,
									pencentage_basic,
									suspend_effect_date,
									resion,
									ps_id_fk
								)
								VALUES 			
								(
									'0',
									'".$crypto->decode($_REQUEST['id'],4)."',
									'".$suspention_start_date."',
									'".$suspend_withdrawn_date."',
									'".$_REQUEST['percentage_basic']."',
									'".$suspention_effect_date ."',
									'".$_REQUEST['reason_sus']."',
									'".$_SESSION['location']['ps_id']."'
								);
								
								");	
				
			
		
		
		if($suspend_dts)
		{
			$suspend = $db->update("
										UPDATE prd_employee_master
										SET
										emp_status = 9
										WHERE emp_id_pk = ". $crypto->decode($_REQUEST['id'],4).";
									
									");
		
											
												
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
										gp_id_fk,
										emp_id_fk,
										ps_id_fk
										
										)
										VALUES 			(
										'".$_SESSION['user_agent']['USER_IP']."',
										now(),
										'".$_SESSION['user_agent']['BROWSER']."',
										'".$_SESSION['user_agent']['OS']."',
										9,
										'".$_SESSION['user_info']['stake_user']."',
										'".$_SESSION['user_info']['stake_level']."',
										'".$crypto->decode($_REQUEST['gp_id_fk'], 4)."',
										". $crypto->decode($_REQUEST['id'], 4).",
										'".$_SESSION['location']['ps_id']."'
										)
										
									");	
			
			if($suspend)
			{
				echo '<div class="alert alert-success" style="text-align:center"><strong>Salary has been Suspended successfully.</strong></div>';
			} 
			else 
			{
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Suspend fails.</strong></div>';exit;
			}
		} 
		else 
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Wrong Data inserted!!!!.</strong></div>';exit;
		}
			
		
	}
	else 
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Wrong Data inserted!!!!.</strong></div>';exit;
	}
		
}
