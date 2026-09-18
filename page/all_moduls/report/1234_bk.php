<?php


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
$db  = new database();

$dist_id= $db->fetch_table("SELECT district_id_pk from prd_location_master_district where district_id_pk not in('21','26','27','28')");

$sql='';
?>
<table border="1">
<?php
foreach($dist_id as $b)
{
$sql=$db->fetch_table("select
 district_name ,sum(gross_salary) as total_gros,sum(net) as total_net FROM prd_monthly_salary_archive_final mmsaf
			INNER JOIN prd_location_master_block mlmm
			ON mlmm.block_code= CAST(mmsaf.block_code AS integer) 
			inner join prd_location_master_district d
			on mlmm.district_id_fk=d.district_id_pk	
			WHERE  district_id_pk in('".$b['district_id_pk']."')
			AND salary_monthyear='201904'
			AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' 
			GROUP BY district_name
			order by district_name ASC ");
?>
<tr>
	<td><?=$sql[0]['district_name']?></td>
    <td><?=$sql[0]['total_gros']?></td>
    <td><?=$sql[0]['total_net']?></td>
</tr>
<?php			
}
//echo $sql;
?>
<table>