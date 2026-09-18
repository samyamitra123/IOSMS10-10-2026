<?php
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';
$db=new database();
$cryptoGraph=new cryptography();
$loggedInOfficerID = $_SESSION['user_info']['officer_id_const'];
$applicationId = $_POST['application_id'];
$lastSent = json_decode($cryptoGraph->decode($_POST['lastSent'],4),true);
if($lastSent['sender'] > 0)
  {
	$Query = "update intra_pri_forwarding SET to_officer_id_const ='', status = 1, remarks='',msg_file='' WHERE forwarding_id_pk=".$lastSent['sender'];
	//echo $Query;
	$db->update($Query);
  }
if($lastSent['reciever'] > 0)
  { 
	$Query = "update intra_pri_forwarding SET delete ='1' WHERE forwarding_id_pk=".$lastSent['reciever'];
	//echo $Query;
	$db->update($Query);
  }
echo "Done";
?>