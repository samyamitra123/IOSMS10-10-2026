<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	
    $post = $_POST;
    //print_r($post); exit;
   if(isset($post['action']) && $post['action'] == 'reset' && $post['employeeId'] != '')
    {
 
            $Query = "update prd_employee_transfer SET lpc_status=0 WHERE transfer_date LIKE '".date('Y')."%' 
                      emp_id_const='".$post['employeeId']."'";
         	//print($Query); exit;
         	$db->update($Query);
            $Query = "update prd_employee_master SET emp_status=1 WHERE emp_id_const='".$post['employeeId']."'";
            
            $db->update($Query); 
            echo "Employee Transfer Reset And activated";          
    } 

  
?>