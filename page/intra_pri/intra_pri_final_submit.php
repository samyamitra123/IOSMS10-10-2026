    
    <?php
    //echo 333; die;
    session_start();
    error_reporting(0);
    
    ob_start();
    require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
   // require_once '../all_function/fun_store/ifms_functions.php';
	//echo 222; die;

    $crypto=new cryptography();
   
    
		$db=new database();
		//echo 222; die;
	$form_flage=$crypto->decode($_GET['form_flage'],4);  
	 $app_id=$crypto->decode($_GET['app_id'],4); 
 
	
	
	 if($form_flage=="final")
		{
			
			
		$db=new database();
		
		
		$update_oc_final=$db->update("UPDATE intra_pri_cg_profile_master SET 
			status='1'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
			
			
			  if($update_oc_final){
        $insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu)
            VALUES ('".$app_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'3', '".$_SESSION['emp_id_const']."', '3')");
    }
			
				if($insert_request_forwarding==true)
				{
					echo "1";
					unset($_SESSION['emp_id_const']);
				}
				else
				{
					echo "0";
				}
		
		}
		
		
		
	 
	
   ?>