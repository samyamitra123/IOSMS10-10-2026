<?php
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db=new database();	
	$Query = "SELECT gp_name, gp_code from prd_location_master_gp WHERE section_code = '0' ";
	$gpSectionCodeMissing = $db->fetch_obj($Query);
	print_r(json_encode($gpSectionCodeMissing)); exit;
	//$Query = "SELECT * prd_location_master_block";
/*    $ndata = file_get_contents('csv/section_code.txt');
    $ndata = json_decode($ndata,true);	
    foreach($ndata as $gpSectionCode)
    {
    	$Query = "UPDATE prd_location_master_gp SET section_code='".$gpSectionCode['GP_SECTION_CODE']."', section_name='".$gpSectionCode['GP_SECTION_NAME']."' WHERE gp_code='".$gpSectionCode['GP_CODE']."'";
    	$db->update($Query);
    } */
    echo "update Done";
?>