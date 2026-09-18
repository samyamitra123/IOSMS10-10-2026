<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	
	$Query ="SELECT * from employee_temp WHERE status =1";
	$employeeTemp = $db->fetch_table($Query);
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



    echo "All Done";

?>