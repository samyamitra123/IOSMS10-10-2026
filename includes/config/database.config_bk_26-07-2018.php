<?php

define("DBMS","postgres");

//Database host name 
define("HOST_NAME","10.249.122.78");

//Database name 
define("DB_NAME","wbprd");

//Database username 
define("DB_USER","wbprd");

//Database password
define("DB_PASS","Mn#t9*A6");

//Database port number
define("PORT","5432");

////////////////////////////////local Setup//////////////////////////////////////////


//$connect = pg_connect("host=" . HOST_NAME . " port=" . PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASS . "");




//IDLE session timeout
//if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] >05)) {
   // echo "11111";
    //session_destroy();   // destroy session data in storage
    //session_unset();     // unset $_SESSION variable for the runtime
	//header('Location: '. $config['base_url'] . "page/logout.php");
	//exit;
//}
//$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

/*****************sql injection prevention start*********************/
//$request_arr=$_REQUEST;
//echo '<pre>';
//print_r($request_arr);
//echo '</pre>';
//	foreach($request_arr as $key_test){
//		//echo $key_test.'<br>';
//		if($key_test!=''){
//			if(preg_match("/[!$%^&*()_+|~`{}\[\]:\";'<>?,.\/]/i", $key_test)){
//				header('Location: '. $config['base_url'] . "page/errordoc.php");
//			}
//		}
//	}
	
	

/******************sql injection prevention end*********************/
function pattern_match_csf_stop($value){					//pattern CSF
		if(preg_match("/[$^|~`{}\[\]\";:'+<>,]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
foreach($_REQUEST as $key => $value){
	
	if($key!='notice_no')
	 {
	 
		if(pattern_match_csf_stop($_REQUEST[$key]) == FALSE){

			header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit();
		} elseif(pattern_match_csf_stop($key) == FALSE) {

                        header('Location: '. $config['base_url'] . "page/errordoc.php?e=1");
			exit();
               }
	 }

	}
?>
