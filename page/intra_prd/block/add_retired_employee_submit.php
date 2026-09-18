<?

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");

header("Pragma: no-cache");



session_start();

require_once '../../../includes/config/config.php';

require_once '../../../includes/config/database.config.php';

$sec_time_token=$_POST['sec_tok'];

$session_token=$_SESSION['security_token'];

$enc_session=md5('369'.$session_token);

if($sec_time_token!=$enc_session)

{

	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';

	header('Location:add_employee.php');

	exit(0);

}       



$type=$_REQUEST['type'];

if(empty($type)){

	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Type.</strong></div>';

	header('Location:add_employee.php');

	exit(0);

}



if($type=='99'){

	header('Location:profile_ret_entry_basic.php');

	exit(0);

}





?>