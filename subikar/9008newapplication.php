<?php
    include_once('master.php');
    $master = new Master();
    $role = 5; // Compassionate employment
    $roleText = 'MF';
    $joinTable = 'intra_pri_9008';
    $PageTitle = "9008 New Applications";
    $Reports = $master->GetReportsNewApplication($role,$roleText,$joinTable);
    //print_r($Reports); exit;
    include_once('template/overage.php');
?>