<?php
    //print_r($_SERVER); exit;
    include_once('master.php');
    $master = new Master();

    $yearMonth = $_GET['yearmonth'];
    $level = $_GET['level'];
    $master->getSalaryDetails($yearMonth,$level);
?>