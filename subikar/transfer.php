<?php
    include_once('master.php');
    $master = new Master();
    $role = 5; // Compassionate employment
    $roleText = 'D';
    $joinTable = 'intra_pri_district_transfer';
    $PageTitle = "9008 New Applications";
    $Reports = $master->GetReportsTransfer($role,$roleText,$joinTable);
    //print_r($Reports); exit;
    include_once('template/overage.php'); 
?>