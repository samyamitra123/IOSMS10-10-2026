<?php 

set_time_limit(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../includes/config/config.php';
require '../includes/config/database.config_api.php';
require '../includes/library/database.class.php';
require_once '../page/api/gpf/ngipf.class.php';
 $input_param = file_get_contents("php://input");
 

// echo $url_value=file_get_contents("https://priemp.wbprd.gov.in/NGIPF/"); die;
//print_r(json_decode($input_param,true));

    $db = new database();
//$data_decode=json_decode($input_param,true);
	$param = trim(str_replace("/", "|", $_REQUEST['service'])); 
	
	$exat_param = explode('|', $param); 
	$param_serviceID = $exat_param[0];
	$strl_check=strlen($param_serviceID); 
	$param_Type = $exat_param[1]; 
	//print($param_serviceID); exit;
	if ($strl_check <='14')
	{
	header("content-type:application/json");
	header("HTTP/1.1 401 Page Not Found");
	$output = array(
		"resp"=>array(
			'Code'=>401,
			'status'=>'F',
			'msg'=>'Not Authorised'
			
		
		)
	);
	echo json_encode($output);
	exit;
	}
	
	if ($param_Type =='')
	{
	header("content-type:application/json");
	header("HTTP/1.1 401 Page Not Found");
	$output = array(
		"resp"=>array(
		
			'Code'=>401,
			'status'=>'F',
			'msg'=>'Not Authorised'
			
		)
		
	);
	echo json_encode($output);
	exit;
	}
	
	if ($input_param=="")
	{
	header("content-type:application/json");
	$output = array(
		"resp"=>array(
			'Code'=>402,
			'status'=>'F',
			'msg'=>'Json Missing'
			
		)
		
	);
	 echo json_encode($output);
	exit;
	}
	
	$data_decode=json_decode($input_param);

	//print_r($data_decode); exit;
	 
	//$emp_id = $data_decode->resp->data->empId;

    $emp_id = $data_decode->req->iosms_empId;     
   // $reqData = $data_decode->req->encData; 
	//$fullResponse =$reqData;
	$key = 'dGl4nfy0jGtbT23z'; //base64_decode("G0HPTE61KCQ+CYn3voqMlFnXEtpaow6gYDqaaGSVzuE=");
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	//$iv = openssl_random_pseudo_bytes($ivlen);
	$iv = 'eT83Bhtd023jSkyg'; //'abcdefghijklmnop';
	//$fullResponse = json_decode( $fullResponse ); 
	$fullResponse = $data_decode->req->encData;
	$fullResponse = base64_decode($fullResponse);
	 

	$ciphertext_raw = openssl_decrypt($fullResponse, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);   
    $responseData = json_decode($ciphertext_raw); 
    $pfAccNo = $responseData->resp->data->pfAccNo;
    $responseStatus = $responseData->resp->status;
    //print_r($responseData); exit;
    //$responseStatus = 'S';
      if($responseStatus == 'S')
      {
      	 $Ngipf = new NGIPF_API(); 
      	 $Ngipf->SyncyNgiPFData($emp_id);
      	 //echo $emp_id;
        // exit;
      } 
     
    //exit;
	
	//var_dump($emp_id); die;
	
	//foreach ($data_decode as $a)
	////{
	//print_r($a); 
	//}
/*	echo ("INSERT INTO prd_gpf_request_master(request_id,full_response)
								VALUES (
								'".$param_serviceID."',
								'".$input_param."'
								
								)");die;*/
	$ResponseTime = date('Y-m-d h:i:s');
	$SetQuery = array();
	if($param_serviceID != '')
		$SetQuery[]= "request_id ='".$param_serviceID."'";
	if($param_serviceID != '')
		$SetQuery[]= "full_response ='".$ciphertext_raw."'";
	if($ResponseTime != '')
		$SetQuery[]= "res_time ='".$ResponseTime."'";
	if($pfAccNo != '')
		$SetQuery[]= "pfaccno ='".$pfAccNo."'";			
    if($responseStatus == 'S')
		$SetQuery[]= "status ='1'";	
	else
		$SetQuery[]= "status ='3'";	


    $Query = "UPDATE prd_gpf_request_master SET ".implode(", ",$SetQuery)." WHERE emp_id_const='".$emp_id."'";
	$insert_request=$db->update($Query);
	if ($insert_request==TRUE)
	{
	header("content-type:application/json");
	$output = array(
		"resp"=>array(
		
		    'empId'=>$emp_id,
			'Code'=>1,
			'status'=>'S',
			'genTime'=>$ResponseTime,
			'msg'=>'Successfully Saved'
		
		)
	);
	 echo json_encode($output);
	exit;
	}
	else
	{
		
	header("content-type:application/json");
	$output = array(
		"resp"=>array(		
		'empId'=>$emp_id,
		'Code'=>0,
		'status'=>'F',
		'genTime'=>$ResponseTime,
		'msg'=>'Data not Insert'
		//'Query'=>$Query
		)
		
	);
	 echo json_encode($output);
	exit;
	
	}
	
	

print_r($data_decode); die;
?>