<?php
    include_once('master.php');
    $master = new Master();
    $role = 3; // Compassionate employment
    $roleText = 'MF';
    $PageTitle = "Compasanate Employee";
    $Reports = $master->GetReportsCompasanate($role,$roleText);

    include_once('template/show-compa.php');
?>