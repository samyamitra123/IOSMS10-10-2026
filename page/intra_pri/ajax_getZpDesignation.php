<?php 
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
$db = new database();
$arr = $db->fetch_table("select designation_id ,designation_name from zpemp_emp_desig_master where status=1 order by designation_id");
?>
<option value="">--Please Select--</option>
<?php 
foreach($arr as $key){
?>

<option value="<?= $key['designation_id']; ?>"
<?php 
if($data_9008[0]['sanctioned_post_name']==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option>
<?php } ?>