<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}



$cryptoGraph=new cryptography();
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page



$db=new database();
 $stake=$_GET['stake']; 
  $district= $_GET['district']; 
 //$district= $cryptoGraph->decode($_GET['district'],4); 
if($stake=='2')
{
	
if($cryptoGraph->decode($_GET['district'],4) == 999)
{
	
	$arr = $db->fetch_table("SELECT ps_id_pk, ps_name FROM prd_location_master_panchayat_samiti ");
}
else 
{
	
	
	$arr = $db->fetch_table("SELECT ps_id_pk, ps_name FROM prd_location_master_panchayat_samiti where district_id_fk='".$district."' ");
}

if(count($arr) > 0)
{
?>
<input type="checkbox" name="sample" id="selectall" />SELECT ALL
<label for="selectall"><span></span></label>
&nbsp;&nbsp;
<? foreach($arr as $municipality) { ?>
&nbsp;&nbsp;
<input type="checkbox" name="arr2[]" class="ps_id" id="<?= $municipality['ps_id_pk']?>" autocomplete="off" value="<?=$cryptoGraph->encode($municipality['ps_id_pk'],4)?>" onclick="return sposeDetails();">
                            <label for="<?= $municipality['ps_id_pk']?>"><span></span></label>
<? // echo '<input type="checkbox" name="arr2[]" class="ps_id" id="'.$municipality['ps_id_pk'].'" value="'.$cryptoGraph->encode($municipality['ps_id_pk'],4).'" />'; ?>&nbsp;&nbsp;
<?=$municipality['ps_name'] ?><br />
<? } 
}
else
{
	echo "No Data Found.";	
}
}

if($stake=='1')
{
if($cryptoGraph->decode($_GET['district'],4) == 999)
{
	
	$arr = $db->fetch_table("SELECT  block_id_pk,block_name,block_code  from prd_location_master_block  ORDER BY block_name ASC");
}
else 
{
	
	
	$arr = $db->fetch_table("SELECT  block_id_pk,block_name,block_code  from prd_location_master_block where district_id_fk='".$district."' ");
}

if(count($arr) > 0)
{
	
?>
<input type="checkbox" name="sample" id="selectalb" />SELECT ALL
<label for="selectalb"><span></span></label>
&nbsp;&nbsp;
<? foreach($arr as $key) {
	
	 ?>
&nbsp;&nbsp;

<input type="checkbox" name="arr3[]" class="block_id1" id="<?= $key['block_code']?>"   value="<?= $cryptoGraph->encode($key['block_code'],4)?>" />&nbsp;&nbsp;
 <label for="<?= $key['block_code']?>"><span></span></label>

<? //echo '<input type="checkbox" name="arr3[]" class="block_id1" id="'.$key['block_code'].'" value="'.$cryptoGraph->encode($key['block_code'],4).'" />'; ?>
<?= $key['block_name'] ?><br />
<? } 
}
else
{
	echo "No Data Found.";	
}
}

if($stake=='3')
{
	
if($cryptoGraph->decode($_GET['district'],4) == 999)
{
	
	$arr = $db->fetch_table("district_id_pk,district_name  from prd_location_master_district ORDER BY block_name ASC");
}
else 
{
	
	
	$arr = $db->fetch_table("SELECT  district_id_pk,district_name  from prd_location_master_district where district_id_pk='".$district."' ");
}

if(count($arr) > 0)
{


?>

<? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
<option value="<?= $key['district_id_pk']; ?>" ><?= $key['district_name']; ?></option>
<? }} 


else
{
	echo "No Data Found.";	
}
}
?>













<script>
	$(document).ready(function(){
	  $('#selectall').attr('checked', true);
	  $('#selectalb').attr('checked', true);
	  $('.ps_id').attr('checked', true);
	  $('.block_id1').attr('checked', true);		  
	});
</script> 
<script>
$('#selectall').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});

$('.ps_id').click(function() {
    if ($(".ps_id:checked").length == $(".ps_id").length) {
        $('#selectall').attr('checked', true);
    } else {
        $('#selectall').attr('checked', false);
    }
});
$('.block_id1').click(function() {
    if ($(".block_id1:checked").length == $(".block_id1").length) {
        $('#selectalb').attr('checked', true);
    } else {
        $('#selectalb').attr('checked', false);
    }
});
</script>   
      

