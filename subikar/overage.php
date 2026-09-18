<?php
    include_once('master.php');
    $master = new Master();
    $role = 4; // Compassionate employment
    $roleText = 'MF';
    $joinTable = 'intra_pri_overage_condonation_master';
    $Reports = $master->GetReportsOverAge($role,$roleText,$joinTable);

    include_once('template/overage.php');
?>