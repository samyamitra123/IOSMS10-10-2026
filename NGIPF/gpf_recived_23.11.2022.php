<?php 

set_time_limit(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../includes/config/config.php';
require '../includes/config/database.config_api.php';
require '../includes/library/database.class.php';

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
	
	if ($strl_check <='14')
	{
	header("content-type:application/json");
	header("HTTP/1.1 401 Page Not Found");
	$output = array(
		"resp"=>array(
		"iosmsresp"=>array(
			'Code'=>401,
			'status'=>'F',
			'msg'=>'Not Authorised'
			
		)
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
		"iosmsresp"=>array(
			'Code'=>401,
			'status'=>'F',
			'msg'=>'Not Authorised'
			
		)
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
		"iosmsresp"=>array(
			'Code'=>402,
			'status'=>'F',
			'msg'=>'Json Missing'
			
		)
		)
	);
	 echo json_encode($output);
	exit;
	}
	
	$data_decode=json_decode($input_param);
	
	//echo $emp_id = $data_decode->empId; die;
	//foreach ($data_decode as $a)
	////{
	//print_r($a); 
	//}
/*	echo ("INSERT INTO prd_gpf_request_master(request_id,full_response)
								VALUES (
								'".$param_serviceID."',
								'".$input_param."'
								
								)");die;*/
	$insert_request=$db->insert("INSERT INTO prd_gpf_request_master(request_id,full_response)
								VALUES (
								'".$param_serviceID."',
								'".$input_param."'
								
								)");
	if ($insert_request==TRUE)
	{
	header("content-type:application/json");
	$output = array(
		"resp"=>array(
		"iosmsresp"=>array(
		'empId'=>$emp_id,
			'Code'=>1,
			'status'=>'S',
			'msg'=>'Successfully Saved'
		)
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
		"iosmsresp"=>array(
		'empId'=>$emp_id,
			'Code'=>0,
			'status'=>'F',
			'msg'=>'Data not Insert'
		)
		)
	);
	 echo json_encode($output);
	exit;
	
	}
	
	

print_r($data_decode); die;
//$data_val1=$data_decode[0]['finYear'];
//$data_val1=$data_decode->finYear;
 
 //print_r($data_val1); die;
 
 
 /*foreach ($data_decode as $k=>$v)
{
	echo $v;
}*/
//echo $v[1];
//die;
/*if(empty($input_param) || ($input_param = json_decode($input_param)) === FALSE || !isset($input_param->source) || $input_param->source != 'EODB'){
	*/
	if($input_param = json_decode($input_param) === FALSE ){
	header("content-type:application/json");
	header("HTTP/1.1 401 Page Not Found");
	$output = array(
		"code"=>401,
		"content"=>array(
			'status'=>0,
			'error_msg'=>'Not Authorised'
		)
	);
	echo json_encode($output);
	exit;
}
else
{
	echo 55555; die;
}


///////////////////////////////////////////Testing data recive////////////////////////////
$eodb_application_no = $input_param->application_no;
$applicant_name = $input_param->applicant_name == "" ? NULL : $input_param->applicant_name;
// insert into table

//callculate payment amt
$payment_amount = 150;
$payment_url = 'https://deptabc.gov.in/paynow.php?app_no=AP202200012';

$atachment1_file_name = $input_param->atachment1_file_name == "" ? NULL : $input_param->atachment1_file_name;
if(!is_null($atachment1_file_name)) {
	$ext = strrchr($atachment1_file_name, ".");
	$atachment1_file_name = "my_attachment1_".microtime(TRUE).$ext;
	$atachment1_file_content = $input_param->atachment1_file_content == "" ? NULL : base64_decode($input_param->atachment1_file_content);
	write_file("./uploads/".$atachment1_file_name, $atachment1_file_content);
	//insert/update table with filepath ->  "./uploads/".$atachment1_file_name
}


function write_file($path, $data, $mode = 'wb'){
	$fp = fopen($path, $mode);
    flock($fp, LOCK_EX);
	for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($data, $written))) === FALSE) {
                break;
            }
    }

    flock($fp, LOCK_UN);
    fclose($fp);
	return is_int($result);
}


$output = array(
	"code" => 200,
	"content" => array(
		"status" => 1,
		"conf_msg" => "Successfully Saved",
		"payment_amount" => $payment_amount,
		"payment_url" => $payment_url,
	)
);

header("content-type:application/json");
header("HTTP/1.1 200 Page Not Found");
echo json_encode($output);
exit;

?>