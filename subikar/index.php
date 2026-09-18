<?php
    //print_r($_SERVER); exit;
    include_once('master.php');
    $master = new Master();
    $role = 2; // Transfer Within District
    $roleText = 'TRANSFER WITHIN DISTRICT';
    $Reports = $master->GetReports($role,$roleText);

    $role = 1; // Transfer Within District
    $roleText = 'TRANSFER OUTSIDE DISTRICT';
    $OutDistrictReports = $master->GetReports($role,$roleText);
    include_once('show-reports.php');
?>