<?php 
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
$db = new database();
$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '90%' and code in('9001','9001','9002','9003','9004','9005','9006','9007','9008','9009','9010','9011') order by code");
?>
<option value="">--Please Select--</option>
<?php 
foreach($arr as $key){
?>

<option value="<?= $key['code']; ?>" 
<?php 
if($data_9008[0]['sanctioned_post_name']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
<?php } ?>