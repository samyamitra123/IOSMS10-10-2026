<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
	global $db;
	$db=new database();	

	$LoginId = $_GET['login_id'];
	$Query = "SELECT * from prd_stack_user_login WHERE stake_user_alias='".$LoginId."'";
	$UserLoginData = $db->fetch_table($Query);
	if(count($UserLoginData) > 0)
	{
		$Query = "UPDATE prd_stack_user_login SET stake_password='5ba4c552121b20535d7391a1dd1ff9af4dcee5d1', new_stake_password='9ea338953f5d4fe4b6686dbd21b639ae4d655e23b69192acd5fd85e48557cbac', password_change_status=0 WHERE login_id_pk='".$UserLoginData[0]['login_id_pk']."'";
		$db->update($Query);
		echo "Password Changed Successfully! Use Default Password: 1@Priemp to login";
	}
	else
	{
        echo "Sorry! Login Id does not exists.";
	}	//print_r($Query); exit;
?>