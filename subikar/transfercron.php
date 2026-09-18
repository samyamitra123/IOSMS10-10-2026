<?php
    include_once('master.php');
    $master = new Master();
    global $db;
    $Query = "SELECT DISTINCT ON (emp_id_fk) emp_id_fk,transfer_date from prd_employee_transfer WHERE emp_id_fk > 0 AND transfer_emp_status=1 order by emp_id_fk, transfer_id_pk desc;";
    $EmployeeDataInArray = $db->fetch_obj($Query);
    $updateQuery = array();
    foreach($EmployeeDataInArray as $item)
    {
        $updateQuery[] = "update prd_employee_master SET transfer_date='".$item->transfer_date."' WHERE emp_id_pk=".$item->emp_id_fk;
    }
    $updateQuery = implode(';',$updateQuery);
    $db->update($updateQuery);
    echo "Done";
    print_r($updateQuery); exit;
    
?>