<?

session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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

$cryptoGraph=new cryptography();


if($cryptoGraph->decode($_POST['value_unlock'],4)==1)
{

			$emp_id_pk=$cryptoGraph->decode($_POST['llock'],4);
			
			
			 $db=new database();
		
			 /*$security = $db->insert("
													INSERT INTO
													psemp_employee_profile_update_status (
																		ip_address ,
																		date,
																		ps_id_fk,
																		emp_id_fk,
																		prev_status,
																		 new_status
																		
																		
																	)
													VALUES 			(
																		'".$_SESSION['user_agent']['USER_IP']."',
																		now(),
																		'".$_SESSION['location']['ps_id']."',
																		'".$emp_id_pk."',
																		'3',
																		 '4'
																		
																		
																	   
																		
																	)
										
									");*/
									
						
			
 			 $query_unlock=$db->update("UPDATE prd_employee_master SET emp_status='10',emp_unlock_status='4'
	     						 WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								 AND emp_status='1' AND emp_unlock_status='2' AND emp_id_pk='".$emp_id_pk."'"); 

//if($security && $query_unlock )
if($query_unlock )
{
	
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile Unlocked Successfully...</strong></div>';
		
	header('Location:employee_list_for_unlock.php');
}


else
{

	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Unlock Failed. Please try again...</strong></div>';
	
	header ('Location:employee_list_for_unlock.php');

}
}

if($cryptoGraph->decode($_POST['value_reject'],4)==2)
{

	
 $emp_id_pk=$cryptoGraph->decode($_POST['reject'],4); 
 //$reason=$_POST['reason'];
 
 $db=new database();
 
 /*$security = $db->insert("
										INSERT INTO
										psemp_employee_profile_update_status (
															ip_address ,
															date,
															ps_id_fk,
															emp_id_fk,
														    prev_status,
														     new_status,
															 reason
															
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															now(),
															'".$_SESSION['location']['ps_id']."',
															'".$emp_id_pk."',
															'3',
															 '1',
															 '".$reason."'
															
															
															
														   
															
														)
							
									");*/
										
  $query_reject=$db->update("UPDATE prd_employee_master SET emp_unlock_status='3'
	     						 WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								 AND emp_unlock_status='2' AND emp_id_pk='".$emp_id_pk."'"); 

//if($security && $query_reject )
if($query_reject )
  {
	
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Employee Profile Unlock Request Rejected Successfully...</strong></div>';
		
	header('Location:employee_list_for_unlock.php');
  }

else
{

	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Unlock Reject Request Failed. Please try again...</strong></div>';
	
	header ('Location:employee_list_for_unlock.php');

}
}


?>
