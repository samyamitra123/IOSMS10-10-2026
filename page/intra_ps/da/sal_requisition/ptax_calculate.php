<?php
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../page_visite.php';
$gross_sal=$_GET['gross'];
$db = new database();
$ptax_range= $db->fetch_table("SELECT ehrms_ptax_deduction.ptax_amount 
 FROM ehrms_ptax_deduction 
 INNER JOIN ehrms_ptax_order_file 
 ON ehrms_ptax_deduction.ptax_order_id_fk=ehrms_ptax_order_file.ptax_orderfile_pk 
 WHERE $gross_sal>=mn_amount AND $gross_sal<=mx_amount AND active_status=1");
echo $ptax_range[0]['ptax_amount'];

?>
