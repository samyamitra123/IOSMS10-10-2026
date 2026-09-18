<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';


/*require '../../../../includes/third-party/PHPMailer/class.phpmailer.php';
require '../../../../includes/third-party/PHPMailer/PHPMailerAutoload.php';
require_once '../../../all_function/mail/mail_fun.php';*/



if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
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
if($_POST['edit_emp_id'])
{
$emp_id_pk=$cryptoGraph->decode($_POST['edit_emp_id']);
 echo $emp_id_pk; die;
}


?>