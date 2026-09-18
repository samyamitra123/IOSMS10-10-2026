<?php 
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
$db = new database();
$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
?>
<option value="">--Please Select--</option>
<?php 
foreach($arr as $key){
$key['code']. '<br />';
?>
<option value="<?= $key['code']; ?>" 
<?php 
if($data_9008[0]['sanctioned_post_name']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
<?php } ?>