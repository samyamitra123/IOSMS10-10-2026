<?
session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$db=new database();
$bonus_cat=$_GET['id'];
if($bonus_cat=='167')
{
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code='401' ORDER BY description");

}
else
{
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code='400' ORDER BY description");
}

?>
<select class="form-control" name="bonus_name" id="bonus_name" readonly style="cursor:pointer;">
<?php foreach($arr as $key){ $key['code']. '<br />'; ?>
<option value="<?php echo $key['code']; ?>"><?php echo $key['description']; ?></option>
<?php } ?>
</select>