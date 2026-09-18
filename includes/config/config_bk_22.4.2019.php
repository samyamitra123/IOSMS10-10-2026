<?php

$ipadresses = array("222.186.61.3", "222.186.61.13", "61.147.103.94","23.239.65.122","142.54.187.194","23.91.15.203","123.249.24.233","192.151.152.202","80.241.208.17","91.205.173.206","61.183.41.81","61.147.103.94","222.186.61.2","117.21.176.128","155.94.64.42","61.160.215.126","220.120.240.219","118.193.162.8","104.255.67.140","173.249.158.189");

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')){
        $ipaddress = getenv('HTTP_CLIENT_IP');
    }else if(getenv('HTTP_X_FORWARDED_FOR')){
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if(getenv('HTTP_X_FORWARDED')){
        $ipaddress = getenv('HTTP_X_FORWARDED');
    }else if(getenv('HTTP_FORWARDED_FOR')){
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    }else if(getenv('HTTP_FORWARDED')){
       $ipaddress = getenv('HTTP_FORWARDED');
    }else if(getenv('REMOTE_ADDR')){
        $ipaddress = getenv('REMOTE_ADDR');
    }else{
        $ipaddress = 'UNKNOWN';
    }	
    return $ipaddress;
}

if (in_array(get_client_ip(), $ipadresses)){
        //$ipaddress = getenv('HTTP_CLIENT_IP');
		exit();
    }

		
$host=$_SERVER['HOST_NAME'];

$config['base_url'] = "https://priemp.wbprd.gov.in/";

if($host=="https://priemp.wbprd.gov.in/"){
	@header('locaton:https://priemp.wbprd.gov.in/');
	$config['base_url'] = "https://priemp.wbprd.gov.in/";	
}else if($host=="https://priemp.wbprd.gov.in/"){
	@header('locaton:https://priemp.wbprd.gov.in/');
	$config['base_url'] = "https://priemp.wbprd.gov.in/";
}else{
	$config['base_url'] = "https://priemp.wbprd.gov.in/";
}

$config['library_link'] = "includes/library/";

$config['config_link'] = "includes/config/";

$config['encript_key'] = "jhabnn4n53jdudh56gdtaro3ghadasrf";


////////////////////////////////////    SFTP IP, USER ID, PASSWORD     //////////////////////////////////////////////////////////


$config['sftp_ip_gp']='202.61.117.90';

$config['sftp_user_name_gp']='gen006';

$config['sftp_user_password_gp']='msgp@321';

/*$config['sftp_ip_ps']='202.61.117.90';

$config['sftp_user_name_ps']='gen007';

$config['sftp_user_password_ps']='msps@321';*/

$config['sftp_ip_ps']='202.61.117.96';

$config['sftp_user_name_ps']='gen007';

$config['sftp_user_password_ps']='Wb!fms.G07';

/*$config['sftp_ip_zp']='202.61.117.90';

$config['sftp_user_name_zp']='gen008';

$config['sftp_user_password_zp']='mszp@321';*/

$config['sftp_ip_zp']='202.61.117.96';
//$config['sftp_ip_zp']='172.17.2.39';

$config['sftp_user_name_zp']='gen008';

$config['sftp_user_password_zp']='Wb!fms.G08';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////Privilege escalation///////////////////////////
$url = $_SERVER['PHP_SELF'] ;
$url_test = str_replace('/',' ',$url) ;
$url=strchr($url_test,'page'); 
$url_test = explode(" ",$url) ;
if($url_test['1']=='icons'){
		header('Location: '. $config['base_url']);
		exit;
	}
if($url_test['2']=='gp'){
	if(($_SESSION['user_info']['stake_level']!=64)){
		header('Location: '. $config['base_url'] . "page/login.php");
		exit;
	}
}else if($url_test['2']=='block'){
	if(($_SESSION['user_info']['stake_level']!=35)){
		header('Location: '. $config['base_url'] . "page/login.php");
		exit;
	}
}else if($url_test['2']=='state'){
	if(($_SESSION['user_info']['stake_level']!=57)){
		header('Location: '. $config['base_url'] . "page/login.php");
		exit;
	}
}
ob_start();
//error_reporting(0);

?>
