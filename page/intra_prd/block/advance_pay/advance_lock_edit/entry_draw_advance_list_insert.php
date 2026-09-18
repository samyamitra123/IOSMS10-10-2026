<?

ob_start();
session_start();
require '../../../../../includes/config/config.php';
require '../../../../../includes/config/database.config.php';
require '../../../../../includes/library/database.class.php';
require '../../../../../includes/library/cryptography.class.php';
require '../../../../../includes/library/myvalidation.class.php';

/*error_reporting(0);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
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

$gp_id=$cryptoGraph->decode($_REQUEST['gp_id'],4);
$emp_id=$cryptoGraph->decode($_REQUEST['emp_id'],4);
$desig=$cryptoGraph->decode($_REQUEST['desig'],4);
$adance_type=$_REQUEST['adance_type'];

if($adance_type==1)
{
	if($desig==1120)
	{
		$adv_amount=2000;
	}
	else
	{
		$adv_amount=5000;
	}
}
else
{
	$adv_amount=0;
}

$salary_monthyear = date('Ym');
$db = new database();
$block = $db->fetch_table("SELECT   
                                       gp_code
										
									FROM
										prd_location_master_gp where gp_id_pk='".$gp_id."'
								");

$block_code=substr($block[0]['gp_code'],0,7);		


	
				$db = new database();	
				
				
				/*$emp_count=$db->fetch_table("select *
                                from 
								prd_adavance_pay where emp_id_fk='$emp_id'"); 
								


					if(!$emp_count){		
					
										 
						$query_insert=$db->insert("INSERT into prd_adavance_pay
											(
				                           	 gp_id_fk,
											  emp_id_fk,
											  block_code,
											  advance_amount,
											  status_flag,
											  entry_time,
											  entry_ip,
											  adance_type,
											  advance_monthyear
											 )
											 VALUES(
											 '$gp_id',
											'$emp_id',
											'$block_code',
											'$adv_amount',
											'2',
										    'now()',
											'".$_SERVER['REMOTE_ADDR']."',
											'$adance_type',
											'$salary_monthyear'
											)");
				
							
								
				
				if($query_insert){
				echo '<div class="alert alert-success" style="text-align:center"><strong>Data Has Been Succesfully Inserted.</strong></div>';exit;
				//$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Data Has Been Succesfully Inserted.</strong></div>';
					
				}
				else{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Data insertion Fails.</strong></div>';exit;
					
					}
				
					}
					else{
						*/
					
					$emp_count=$db->fetch_table("select submit_count_status
                                from 
								prd_adavance_pay where emp_id_fk='$emp_id'");
								 
						$salary_count=$db->fetch_table("select status_flag,delete_status,is_saved
                                from 
								prd_employee_salary_save  WHERE
											 emp_id_fk='$emp_id' and salary_monthyear='".date('Ym')."' "); 
											 
										;	 
											 
								
						$update_query=$db->update("UPDATE prd_employee_salary_save set
				                           delete_status= '0'
										 WHERE
											 emp_id_fk='$emp_id' and salary_monthyear='".date('Ym')."' and status_flag='1' and is_saved='1' and delete_status='1'");	
											 	
					
					
						if($emp_count[0]['submit_count_status']!="" and $salary_count[0]['status_flag']=='1' and $salary_count[0]['delete_status']=='1')
						{
						
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='".$emp_count[0]['submit_count_status']."'+1
											
										 WHERE
											 emp_id_fk='$emp_id'");	
											 
											 
											 
											 
											 
											 
						}
						
						else if($emp_count[0]['submit_count_status']=="" and $salary_count[0]['status_flag']=='1' and $salary_count[0]['delete_status']=='1')
						{
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
											status_flag='3',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='1'
											
										 WHERE
											 emp_id_fk='$emp_id'");	
						}
						else if($emp_count[0]['submit_count_status']=="" and count($salary_count)=='0')
						{
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
											status_flag='3',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='1'
											
										 WHERE
											 emp_id_fk='$emp_id'");	
						}
						else if($emp_count[0]['submit_count_status']!="" and count($salary_count)=='0')
						{
						
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
											status_flag='3',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='1'
											
										 WHERE
											 emp_id_fk='$emp_id'");	
						}else if($emp_count[0]['submit_count_status']!="" and $salary_count[0]['delete_status']=='0' and $salary_count[0]['status_flag']=='1')
						{
						
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
											status_flag='3',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='1'
											
										 WHERE
											 emp_id_fk='$emp_id'");	
						}
				else if($emp_count[0]['submit_count_status']=="" and $salary_count[0]['delete_status']=='0' and $salary_count[0]['status_flag']=='1')
						{
						
						
						$query_update=$db->update("UPDATE prd_adavance_pay set
				                            gp_id_fk= '$gp_id',
											block_code='$block_code',
											advance_amount='$adv_amount',
											status_flag='3',
										    entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											adance_type='$adance_type',
											advance_monthyear='$salary_monthyear',
											submit_count_status='1'
											
										 WHERE
											 emp_id_fk='$emp_id'");	
						}
				if($query_update){
				echo 'success';exit;
				//$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Data Has Been Succesfully Inserted.</strong></div>';
					
				}
				else{
					echo 'fail';exit;
					
				}
						
					//}


?>