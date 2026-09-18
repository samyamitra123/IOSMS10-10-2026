<?php

require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';

$db=new database();	


// Inter District & District Transfer 

$Query = "Select district_id_pk, district_name from prd_location_master_district ";

// Loop Iteration for each District Start //

$Query ="Select block_id_pk, block_name from prd_location_master_block WHERE district_id_fk=district_id_pk";

// District Transfer Count 

// Complete
$Query = "Select count(app_id) from intra_pri_district_transfer WHERE district_level='ID' AND pre_block_id= block_id_pk AND status=2"

// Pending District
$Query = "Select application_id from intra_pri_district_transfer WHERE district_level='ID' AND pre_block_id= block_id_pk AND status=1"

// Pending District Application Pending End
$Query = "Select from_officer_id_const from intra_pri_forwarding WHERE application_id=application_id AND status=1"

// Pending District Application Pending End
$Query = "Select designation from pri_master WHERE officer_id_const=from_officer_id_const "

// Get Designation Name

$Query ="select designation from intra_pri_designation_master WHERE designation_code = designation"

//Loop Iteration for each District END //

?>