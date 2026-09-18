<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	

    $yearMonth = date('Ym',strtotime('-9 month'));
    //print_r($yearMonth); exit;
    $curYearMonth = date('Ym');

	$Query ="SELECT * from prd_festival_advance_employee_details as faed
	         LEFT JOIN prd_festival_advance_entry_sal as faes ON faed.festival_advance_id_pk=faes.festival_advance_id_fk
	         WHERE faes.festival_advance_instalment_no=faes.deduction_counter AND faes.deduction_end_monthyear > '".$curYearMonth."'";
	$festivalTemp = $db->fetch_table($Query);
	//print_r($festivalTemp); exit;
	foreach($festivalTemp as $festival)
	{
        $IstalNo = $festival['festival_advance_instalment_no'] - ($festival['deduction_end_monthyear'] - $curYearMonth);
    	$Query = "Update prd_festival_advance_employee_details SET festival_advance_status=5 WHERE festival_advance_id_pk= ".$festival['festival_advance_id_pk'];
       $db->update($Query);
    	//print_r($Query);
    	//echo "<hr />";
    	$Query = "Update prd_festival_advance_entry_sal SET deduction_counter='".$IstalNo."' WHERE festival_advance_id_fk= ".$festival['festival_advance_id_pk'];
    	//print($Query); 
    	$db->update($Query);

    	//$db->update($Query);
	}
	//print_r($festivalTemp); 

	//exit;
/*	$employeeTemp = $db->fetch_table($Query);
	foreach($employeeTemp as $employee)
	{
		if($employee['emp_id_const'] != '')
		{
			$employee['emp_id_const'] = rtrim($employee['emp_id_const']);
			$changeData = json_decode($employee['changedata']);
			$status = (strtotime($changeData->emp_termination_date) > time())?1:2;
			$updateQuery = "Update prd_employee_master SET emp_retirement_date='".$changeData->emp_retirement_date."', emp_termination_date='".$changeData->emp_termination_date."', emp_status='".$status."', emp_pension_status='0', ropa_status=1 WHERE emp_id_const='".$employee['emp_id_const']."'";
			//print($updateQuery); exit;
			$db->update($updateQuery);

			$updateQuery = "Update prd_pension_employee SET emp_pension_status='0' WHERE emp_id_const='".$employee['emp_id_const']."'";
			//print($updateQuery); exit;
			$db->update($updateQuery);

			$updateQuery = "Update employee_temp SET status = 0 WHERE emp_id_const='".$employee['emp_id_const']."'";
			$db->update($updateQuery);
		}
		
	}
*/


    echo "All Done";

?>