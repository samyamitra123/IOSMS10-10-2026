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

if($_POST['claimant_mob']=='' || $_POST['claimant_mob']=='0')
{
	$claimant_mob='0';
}
else{
	$claimant_mob=$_POST['claimant_mob'];
}
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
		else if((($_POST['reason'])==1993) && ($validator->blank_select($_POST['claimant_name']) == FALSE))
		{
			
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please  Enter claimant Name!!.</strong></div>';exit;
		}
		else if((($_POST['reason'])==1993) && ($validator->blank_select($_POST['relationship_incumbent']) == FALSE))
		{
			
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please choose Enter relationship with incumbent!!.</strong></div>';exit;
		}
		else if((($_POST['reason'])==1993) &&  ($validator->pattern_number($_POST['claimant_mob'])==FALSE) )
		{
			
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter claimant Mobile Number !!.</strong></div>';exit;
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
													zp_id_fk,
													claimant_name,
													relationship_incumbent,
													reason_date,
													claiment_mobile_no
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
													'".$_SESSION['location']['district_id']."',
													'".strtoupper($_POST['claimant_name'])."',
													'".$_POST['relationship_incumbent']."',
													'".$reason_date."',
													'".$claimant_mob."'
												)");
			
			//var_dump($stop_reason_insert); die;
			if($stop_reason_insert)
			{
				$dath=$db->fetch_table("Select reason_date,claimant_name,relationship_incumbent from prd_stop_sal_reason where emp_id_fk='". $crypto->decode($_REQUEST['id'], 4)."' and reason='1993'");
				$date=substr($dath[0]['reason_date'],0,10);
				
				$pension_stat_fetch=$db->fetch_table(" SELECT emp_cosolidated_pay,emp_pension_status FROM prd_employee_master WHERE emp_id_pk='". $crypto->decode($_REQUEST['id'],4)."' ");
				$pension_stat=$pension_stat_fetch[0]['emp_pension_status'];
				if($pension_stat=='0')
				{
					$update_pension='0';
				}
				elseif($pension_stat=='1' || $pension_stat=='2') 
				{
					$update_pension='2';
				} 
				if($dath>0)
				{ 
					$stop = $db->update("UPDATE prd_employee_master SET emp_status = 2,emp_pension_status='".$update_pension."',emp_termination_date='". $date."' WHERE emp_id_pk = ". $crypto->decode($_REQUEST['id'],4)."");
				}
				else
				{
					$stop = $db->update("UPDATE prd_employee_master SET emp_status = 2 WHERE emp_id_pk = ". $crypto->decode($_REQUEST['id'],4)."");
				}
			}
		}

//var_dump($stop); die;
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
