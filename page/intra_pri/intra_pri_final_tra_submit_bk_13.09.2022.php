    
    <?php
    //echo 333; die;
    session_start();
    error_reporting(0);
    
    ob_start();
    require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
   require_once '../all_function/fun_store/ifms_functions.php';
	//echo 222; die;

    $crypto=new cryptography();
   
    
		$db=new database();
		//echo 222; die;
	 $stack_final=$crypto->decode($_GET['stack_final'],4);  
	 //$app_id=$crypto->decode($_GET['app_id'],4); 
 
	
	
	 $user=$_SESSION['user_info']['stake_user_code'];
	
	if($stack_final=="ID" || $stack_final=="D")
	 {
	
		 if($stack_final=="ID")
		 {
			 $statement='TRANSFER WITHIN DISTRICT';
			 $s='2';
		 }
		 else
		 {
			  $statement='TRANSFER OUTSIDE DISTRICT';
			   $s='1';
		 }

$db=new database();
 $application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_district_transfer where stack_user='".$user."' and from_status='2' and district_level='".$stack_final."'");
 
 if(count($application_id_check)=='1')
		{
		
		//echo 1; die;
		  
			if($application_id_check[0]['application_id']!='')
			{
			$application_id=$application_id_check[0]['application_id'];
			}
			else
			{
			$application_id = application_id($stack_final,$statement);
			
			$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
			VALUES (
			'".$application_id."'
			)");
			
			}
		
		}
		else
		{
			//echo 2; die;
			
		$application_id = application_id($stack_final,$statement);
		
		$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
		VALUES (
		'".$application_id."'
		)");
		
		}
		
		$_SESSION['application_id']=$application_id;
	
	
	
	
	//header("Location:intra.php");
		
		
		$update_oc_final=$db->update("UPDATE intra_pri_district_transfer 
SET 
status='1',
application_id='".$application_id."'

 WHERE stack_user='".$user."' and from_status='2' and district_level='".$stack_final."'");
 
 if($update_oc_final){
	 
	
        $insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu)
            VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."','1','".$s."', '".$statement."','".$s."')");
    }
 
 if($insert_request_forwarding)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
	 
	 }
	
	
	

	

		
		
	 
	
   ?>