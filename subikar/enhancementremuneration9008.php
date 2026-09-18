<?php
    include_once('master.php');
    $master = new Master();
    $role = '5B'; // Compassionate employment
    $roleText = 'MF';
    $joinTable = 'enhancement_remuneration_9008';
    $Reports = $master->GetReportsOverAge($role,$roleText,$joinTable);
    print_r($Reports); exit;
    include_once('template/overage.php');
?>