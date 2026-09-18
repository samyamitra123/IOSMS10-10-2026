<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();
    $post = $_POST;
   if(isset($post['action']) && $post['action'] == 'reset' && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_employee_master WHERE emp_id_const='".$post['employeeId']."'";
         	
         	$employeeDetails = $db->fetch_table($Query);
         	$employeeDetails = $employeeDetails[0];
            $Query = "SELECT basic from prd_employee_salary_save where emp_id_fk=".$employeeDetails['emp_id_pk']." AND salary_monthyear='".date('Ym',strtotime('-1 month'))."'";
            
            $employeeSalary = $db->fetch_table($Query);
            $employeeSalary = $employeeSalary[0]['basic'];

         	
         	
         	if($employeeDetails['emp_id_pk'] > 0)
         	{
         		$updateQuery = "update prd_employee_master SET emp_pay_in_payband='".$employeeSalary. "' WHERE emp_id_pk=".$employeeDetails['emp_id_pk'];
                $db->update($updateQuery);
                $deleteQuery = "DELETE from prd_employee_annual_increment_details WHERE emp_id_fk=".$employeeDetails['emp_id_pk']." AND effective_monthyear='".date('Ym')."'";
                $db->update($deleteQuery);  
                $deleteQuery = "DELETE from prd_employee_promotion_details WHERE emp_id_fk=".$employeeDetails['emp_id_pk']." AND effective_date LIKE '".date('Y-m')."%'";
                $db->update($deleteQuery);
                //print_r($deleteQuery); exit;    


         		//$db->update($updateQuery);
         		echo "Congratulation! Employee ID : ".$employeeDetails['employee_id_const'].' Reset Annual Increment & Promotion.<br /> Below Previous Record.<br />';
         		//print($updateQuery); exit;
         	}
         	echo "<ul class='employeedetails'>";
         	foreach($employeeDetails as $key=>$value)
         	{
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
         	}

         	echo "</ul>";
         	

    } 

    if(isset($post['action']) && $post['action'] == 'show' && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_employee_master WHERE emp_id_const='".$post['employeeId']."'";
         	$employeeDetails = $db->fetch_table($Query);
         	$employeeDetails = $employeeDetails[0];
            $Query = "SELECT basic from prd_employee_salary_save where emp_id_fk=".$employeeDetails['emp_id_pk']." AND salary_monthyear='".date('Ym',strtotime('-1 month'))."'";
            
            $employeeSalary = $db->fetch_table($Query);
            $employeeSalary = $employeeSalary[0]['basic'];
            
         	echo "<ul class='employeedetails'>";
            echo "<li><b>Last Month Basic</b>:  ".$employeeSalary;
         	foreach($employeeDetails as $key=>$value)
         	{
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
         	}
         	echo "<li>
            <input type='hidden' name='paemployeeId' id='paemployeeId' value='".$employeeDetails['emp_id_const']."'>
         	<input type='button' class='resetap' value='ResetAp' onclick='javascript:resetap();'>

     	    </li>";
         	echo "</ul>";
         	

    }

    if(isset($post['action']) && $post['action'] == 'ngipfview' && $post['employeeId'] != '')
    {
 
            $Query = "select * from prd_gpf_request_master WHERE emp_id_const='".$post['employeeId']."'";
            $employeeDetails = $db->fetch_table($Query);
            $employeeDetails = $employeeDetails[0];
            if($employeeDetails['pfaccno'] != '' && $post['reqtype'] == 'sub')
            {
                $pnrdRequest =  json_decode($employeeDetails['pnrd_req_2']);
                //$pnrdRequest = $pnrdRequest->req->empDtls;
                //$pnrdRequest
                foreach($pnrdRequest as $mkey=>$empDetails)
                {
                    echo "<h2>".$mkey."</h2>";
                    print_r($empDetails); 
                    //echo "<ul class='employeedetails'>";
                   // $empDetails = isset($empDetails[0])?$empDetails[0]:$empDetails;
                   /* foreach($empDetails as $key=>$value)
                    {
                       $key = strtoupper(str_replace('_', ' ', $key));
                       echo "<li><b>".$key."</b>:  ".$value;
                    }*/  
                    //echo "</ul>";                 
                }
            }
            elseif($post['reqtype'] == 'main')
            {
                //print("test"); exit;
                $pnrdRequest =  json_decode($employeeDetails['pnrd_request']);
                $pnrdRequest = $pnrdRequest->req->empDtls;
                //$pnrdRequest
                foreach($pnrdRequest as $mkey=>$empDetails)
                {
                    echo "<h2>".$mkey."</h2>";
                    print_r($empDetails); 
                    //echo "<ul class='employeedetails'>";
                   // $empDetails = isset($empDetails[0])?$empDetails[0]:$empDetails;
                   /* foreach($empDetails as $key=>$value)
                    {
                       $key = strtoupper(str_replace('_', ' ', $key));
                       echo "<li><b>".$key."</b>:  ".$value;
                    }*/  
                    //echo "</ul>";                 
                }
                //print_r($pnrdRequest); exit;
            } 
          /*  $Query = "SELECT basic from prd_employee_salary_save where emp_id_fk=".$employeeDetails['emp_id_pk']." AND salary_monthyear='".date('Ym',strtotime('-1 month'))."'";
            
            $employeeSalary = $db->fetch_table($Query);
            $employeeSalary = $employeeSalary[0]['basic'];
            
            echo "<ul class='employeedetails'>";
            echo "<li><b>Last Month Basic</b>:  ".$employeeSalary;
            foreach($employeeDetails as $key=>$value)
            {
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
            }
            echo "<li>
            <input type='hidden' name='paemployeeId' id='paemployeeId' value='".$employeeDetails['emp_id_const']."'>
            <input type='button' class='resetap' value='ResetAp' onclick='javascript:resetap();'>

            </li>";
            echo "</ul>"; */
            

     }    

?>