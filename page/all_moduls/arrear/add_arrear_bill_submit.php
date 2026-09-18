<?php
//die("debjit");

session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/cryptography.class.php';

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];

$enc_session=md5('369'.$session_token);
$cryptoGraph=new cryptography();

if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('Location:arrear_module_view.php');
	exit(0);
}
      
 $type=$cryptoGraph->decode($_REQUEST['type'],4); 
 $type2=$cryptoGraph->decode($_REQUEST['type2'],4);
 $type3=$cryptoGraph->decode($_REQUEST['type3'],4);
 $type4=$cryptoGraph->decode($_REQUEST['type4'],4);
 $type5=$cryptoGraph->decode($_REQUEST['type5'],4);
 $type6=$cryptoGraph->decode($_REQUEST['type6'],4);
 $type7=$cryptoGraph->decode($_REQUEST['type7'],4);

/*if(($type!=1 ||  $type!=0)&& $type2!=2 && $type3!=3 && $type4!=4){
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Wrong Selection.</strong></div>';
	header('Location:arrear_module_view.php');
	exit(0);
}*/




 if($type2=='0'){
	header('Location:employee_lists.php?emp_type='.$_REQUEST['type2']);
	exit(0);
}
else if($type3=='3'){
	header('Location:employee_lists.php?emp_type='.$_REQUEST['type3']);
	exit(0);
}
else if($type4=='4'){
	header('Location:employee_lists.php?emp_type='.$_REQUEST['type4']);
	exit(0);
}
else if($type5=='5'){
	header('Location:employee_lists_ropa_2019.php?emp_type='.$_REQUEST['type5']);
	exit(0);
}


else if($type6=='6'){
	header('Location:employee_lists_ropa_2019.php?emp_type='.$_REQUEST['type6']);
	exit(0);
}
else if($type7=='7'){
	header('Location:employee_lists_ropa_2019.php?emp_type='.$_REQUEST['type7']);
	exit(0);
}
else if($type=='1'){
	header('Location:text_file_view.php');
	exit(0);
}


?>