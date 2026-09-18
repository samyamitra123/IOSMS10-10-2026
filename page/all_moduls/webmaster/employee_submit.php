<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	
    $post = $_POST;
    if(isset($post['changefestadvstatus']) && $post['employeeId'] != '' && $post['festAdvStatus'] != '')
    {
        //print_r("dgdgkk"); exit;
         $salary_monthyear = date('Ym');
         if($post['festAdvStatus'] == 6)
           {
              $Query = "UPDATE prd_festival_advance_employee_details SET festival_advance_status='".$post['festAdvStatus']."' WHERE emp_id_fk='".$post['employeeId']."'"; 
           }
          else
          {
            $Query = "UPDATE prd_festival_advance_employee_details SET festival_advance_status='".$post['festAdvStatus']."' WHERE festival_advance_id_pk='".$post['festAdvId']."'";           
          }

        //print($Query); exit;
        $db->update($Query);
        if($post['festAdvStatus'] == 5)
          {
               $Query = "SELECT * from prd_festival_advance_entry_sal WHERE festival_advance_id_fk=".$post['festAdvId'];
               $festAdvDetails = $db->fetch_table($Query);
               $festAdvDetails = $festAdvDetails[0];
               $currentMonth = date('Y/m/01');
              // $festival_advance_instalment_no = $festAdvDetails['festival_advance_instalment_no'] - 1;
               $deduction_start_monthyear = substr($festAdvDetails['deduction_start_monthyear'],0,4).'/'.substr($festAdvDetails['deduction_start_monthyear'],4,5).'/01';
               //$deduction_end_monthyear = $festAdvDetails['deduction_end_monthyear'];

               $BalanceDay = (strtotime($currentMonth) - strtotime($deduction_start_monthyear)) / (60*60*24*30);
               $BalanceDay = round($BalanceDay);
               $update = "update prd_festival_advance_entry_sal SET deduction_counter=".$BalanceDay." WHERE festival_advance_id_fk=".$post['festAdvId'];
               $db->update($update);
              // print($update); 


          }  
       // exit; 
        $Query = "DELETE from  prd_employee_salary_save WHERE emp_id_fk='".$post['employeeId']."' AND salary_monthyear='".$salary_monthyear."'";
        $db->update($Query);
        echo "Festival Advance Status Changed.";
    }    
    if(isset($post['deletestatus']) && $post['pension_details_id'] != '')
    {
    	$Query = "DELETE from prd_pension_employee WHERE pension_details_id=".$post['pension_details_id'];
    	//print($Query); 
    	$db->update($Query);
    	echo "Pension Employee Deleted successfully. Please Run The Cron Job Now";
    }
    if(isset($post['changemstatus']) && $post['pension_details_id'] != '')
    {
    	$Query = "UPDATE prd_pension_employee SET flag='M' WHERE pension_details_id=".$post['pension_details_id'];
    	//print($Query); 
    	$db->update($Query);
    	echo "Pension Employee M status change";
    }   
    if(isset($post['changePensionstatus']) && $post['employeeId'] != '')
    {
    	$Query = "UPDATE prd_employee_master SET emp_pension_status=0 WHERE emp_id_const='".$post['employeeId']."'";;
    	//print($Query); 
    	$db->update($Query);
    	echo " Employee Pension status Reset to O Run Epension Cron";
    }       
   // print_r($post); exit;
    if(isset($post['festivaladvemployee']) && $post['employeeId'] != '')
    {
        $Query = "select faed.*, em.* from prd_festival_advance_employee_details as faed 
                  LEFT JOIN prd_employee_master as em ON faed.emp_id_fk = em.emp_id_pk
                  WHERE faed.emp_id_const='".$post['employeeId']."' AND bill_id_fk !=0 ORDER BY faed.festival_advance_id_pk desc LIMIT 1 OFFSET 0";
        //print($Query); exit;
        $festAdvemployeeDetails = $db->fetch_table($Query);
        $festAdvemployeeDetails = $festAdvemployeeDetails[0];  
            echo "<ul class='employeedetails'>";
            foreach($festAdvemployeeDetails as $key=>$value)
            {
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
            }
            $status = ($festAdvemployeeDetails['festival_advance_status'] == 5)?'Disable':'Enable';
            $changeStatus = ($festAdvemployeeDetails['festival_advance_status'] == 5)?6:5;
            echo "<li>

            <input type='hidden' name='employeeId' id='festadvId' value='".$festAdvemployeeDetails['festival_advance_id_pk']."'>
            <input type='hidden' name='employeeId' id='festadvemployeeId' value='".$festAdvemployeeDetails['emp_id_fk']."'>
            <input type='button' class='changestatus' value='".$status." Employee EMI' onclick='javascript:changefestAdvStatus(\"".$changeStatus."\");'>
            </li>";            
            echo "</ul>";     
    }    
    if(isset($post['updatetermination']) && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_employee_master WHERE emp_id_const='".$post['employeeId']."'";
         	
         	$employeeDetails = $db->fetch_table($Query);
         	$employeeDetails = $employeeDetails[0];
         	//print_r($employeeDetails); exit;
         	$terminationDate = date('Y-m-d',strtotime($post['empTermination']));
         	if($employeeDetails['emp_id_pk'] > 0)
         	{
         		$updateQuery = "update prd_employee_master SET emp_termination_date='".$terminationDate. "' WHERE emp_id_pk=".$employeeDetails['emp_id_pk'];
         		$db->update($updateQuery);
         		echo "Congratulation! Employee ID : ".$employeeDetails['employee_id_const'].' Changed successfully.<br /> Below Previous Record.<br />';
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
    if(isset($post['checkemployee']) && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_employee_master 
                      WHERE emp_id_const='".$post['employeeId']."'";
         	$employeeDetails = $db->fetch_table($Query);
         	$employeeDetails = $employeeDetails[0];
            if($employeeDetails['ps_id_fk'] > 0)
              {
                $Query = "select * from prd_location_master_panchayat_samiti 
                          WHERE ps_id_pk='".$employeeDetails['ps_id_fk']."'";
                $psDetails = $db->fetch_table($Query);
                $psDetails = $psDetails[0];
                $employeeDetails['ps_code'] = $psDetails['ps_code'];
                $employeeDetails['ps_name'] = $psDetails['ps_name'];
              }  
            if($employeeDetails['gp_id_fk'] > 0)
              {
                $Query = "select * from prd_location_master_gp 
                          WHERE gp_id_pk='".$employeeDetails['gp_id_fk']."'";
                $psDetails = $db->fetch_table($Query);
                $psDetails = $psDetails[0];
                $employeeDetails['gp_code'] = $psDetails['gp_code'];
                $employeeDetails['gp_name'] = $psDetails['gp_name'];
              } 
            if($employeeDetails['zp_id_fk'] > 0)
              {
                $Query = "select * from prd_location_master_district 
                          WHERE district_id_pk='".$employeeDetails['zp_id_fk']."'";
                $psDetails = $db->fetch_table($Query);
                $psDetails = $psDetails[0];
                $employeeDetails['district_code'] = $psDetails['district_code'];
                $employeeDetails['district_name'] = $psDetails['district_name'];
              }                               
         	echo "<ul class='employeedetails'>";
         	foreach($employeeDetails as $key=>$value)
         	{
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
         	}
         	echo "<li>
            <input type='hidden' name='employeeId' id='employeeId' value='".$employeeDetails['emp_id_const']."'>
         	<input type='button' class='changestatus' value='Enable Employee' onclick='javascript:changeStatus();'>
          <input type='button' class='transferreset' value='Transfer Employee Reset' onclick='javascript:transferReset();'>

            <input type='button' class='pensionstatus' value='Reset Pension Status' onclick='javascript:changePensionStatus();'>

         	</li>";
         	echo "</ul>";
         	

    }
    if(isset($post['checkemployeepension']) && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_pension_employee WHERE emp_id_const='".$post['employeeId']."'";
         	//print($Query); exit;
         	$employeeDetails = $db->fetch_table($Query);

         	$employeeDetails = $employeeDetails[0];
         	$ecodeLen = (strlen($employeeDetails['error_code']) - 1) ;
         	$errorCode = str_replace(',',"','",substr($employeeDetails['error_code'],0,$ecodeLen));
         	if($employeeDetails['response_status'] == 'false')
         	{
	         	$Query = "select description,error_code from prd_pension_error_code_master WHERE error_code IN ('".$errorCode ."')";
	         	//print($Query); exit;
	         	$errorCode = $db->fetch_table($Query); 
	         	echo "<ul class='employeedetails' style='color:red;'>"; 
	         	foreach($errorCode as $error)
	         	  {
                       echo "<li><b>".$error['error_code']."</b>:  ".$error['description'];
	         	  }  
	         	echo '</ul>';     		
         	}
         	echo "<ul class='employeedetails'>";
         	foreach($employeeDetails as $key=>$value)
         	{
               $key = strtoupper(str_replace('_', ' ', $key));
               echo "<li><b>".$key."</b>:  ".$value;
         	}
         	echo "<li>
            
         	<input type='button' class='changestatus' value='Back To Employee' onclick='javascript:backtohome();'>

            <input type='button' class='changestatus' value='Delete From Epension' onclick='javascript:deleteEpension(\"".trim($employeeDetails['pension_details_id'])."\");'>
            <input type='button' class='changestatus' value='Change Status M' onclick='javascript:changeMEpension(\"".trim($employeeDetails['pension_details_id'])."\");'>
         	</li>";
         	echo "</ul>";
         	

    }    
    if(isset($post['changestatus']) && $post['employeeId'] != '')
    {
 
         	$Query = "select * from prd_employee_master WHERE emp_id_const='".$post['employeeId']."'";
         	$employeeDetails = $db->fetch_table($Query);
         	$employeeDetails = $employeeDetails[0];
         	$Query = "SELECT count(*) as countofentry from employee_temp WHERE emp_id_const='".$employeeDetails['emp_id_const']."' AND status=1";
         	$employeeTemp = $db->fetch_table($Query);
         	$employeeTemp = $employeeTemp[0]['countofentry'];
         	if($employeeTemp <= 0)
         	  {
	         	$data = array (
	         		             'emp_retirement_date'=>$employeeDetails['emp_retirement_date'],
	         		             'emp_termination_date'=>$employeeDetails['emp_termination_date']
	         	              );
	         	$data = json_encode($data);
	            $InsertQuery = "INSERT INTO employee_temp (emp_id_const,changedata,status) VALUES ('".$employeeDetails['emp_id_const']."', '".$data."','1')";
	         	$db->insert($InsertQuery);
	         	if($employeeDetails['emp_id_const'] != '')
	         	{
		         	$updateQuery = "Update prd_employee_master SET emp_retirement_date='".date('Y-m-d',strtotime('+ 1 year',time()))."', emp_termination_date='".date('Y-m-d',strtotime('+ 1 year',time()))."', emp_status=1 WHERE emp_id_const='".$employeeDetails['emp_id_const']."'";
		         	$db->update($updateQuery);
	            }
	         	
	         	echo "Data Changed For Employee ID:".$employeeDetails['emp_id_const'];
         	  }
         	 else
         	  {
         	  	 echo "Employee Already Active..";
         	  }

    }    
?>