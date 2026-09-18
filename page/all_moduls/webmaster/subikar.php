<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	
	$Query = "SELECT * from prd_location_master_block ";
	$Blocks = $db->fetch_table($Query);
    $blockData = array();
    
    foreach($Blocks as $Data)
    {
       $blockData[$Data['block_code']] = $Data['block_name']; 	
    }

	$Query = "SELECT * from prd_location_master_district ";
	$Districts = $db->fetch_table($Query);
    $districtData = array();
    
    foreach($Districts as $Data)
    {
       $districtData[$Data['district_code']] = $Data['district_name']; 	
    }
   // print_r($districtData); exit;
	$Query = "SELECT * from intra_pri_designation_master ";
	$Designations = $db->fetch_table($Query);
    $desigData = array();
    
    foreach($Designations as $Data)
    {
       $desigData[$Data['designation_code']] = $Data['designation']; 	
    }
   // print_r($desigData); 
    //print_r($districtData);
   // exit;
    $Query ="SELECT from_officer_id_const, forwarding_id_pk, ipm.login_level_stake, ipm.stake_user_code, ipm.officer_name, ipm.designation from intra_pri_forwarding as ipf 
             LEFT JOIN intra_pri_master as ipm ON ipf.from_officer_id_const = ipm.officer_id_const";
    $forwardData = $db->fetch_table($Query);
    // print_r($forwardData); exit;
    $UpdateQuery = array();
    foreach($forwardData as $fd)
    {
       $stake_user_code = (int) $fd['stake_user_code'];
       if($fd['login_level_stake'] == 'DISTRICT')
       {
       	 	$locationName = $districtData[$stake_user_code];
       }
       else if($fd['login_level_stake'] == 'BLOCK')
            $locationName = $blockData[$stake_user_code];
       else
       	    $locationName = 'STATE';

       $DesignationName = $desigData[$fd['designation']];

       $UpdateQuery[] = "UPDATE intra_pri_forwarding SET officer_info='".$fd['officer_name']."(".$DesignationName.") ".$locationName."' WHERE forwarding_id_pk=".$fd['forwarding_id_pk'];
       //$db->update($UpdateQuery);

    }
    $UpdateQuery = implode('; ', $UpdateQuery);
    $db->update($UpdateQuery);
    //print_r("subikar"); 
    echo "DONE";
   
?>