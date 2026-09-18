<?php
    include_once('master.php');
    $master = new Master();
    $role = '5C'; // Compassionate employment
    $roleText = 'MF';
    $joinTable = 'fund_requisition_9008';
    $PageTitle = "Fund Requisation 9008";
    $Reports = $master->GetReportsNewApplication($role,$roleText,$joinTable);

    include_once('template/overage.php');
?>