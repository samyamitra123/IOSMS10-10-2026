<?php
set_time_limit(0);
session_start();
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
$db = new database();

$bill_type=$crypto->decode($_GET['bill_type'],4);
$drn_number=$crypto->decode($_GET['drn_no'],4);

//$drn_number=$_GET['drn_no'];

/*$i=1;

for($j=2017;$j<=date('Y');$j++)
{
$number='002';
$starting_year=(date('Y')-2017);
$c=$starting_year;
$a = sprintf("%06d", $c);
$drn_sequence_number=date('Ym').$number.$a ;

}*/

$url = "http://192.168.1.254/epension/bill_check.php";

$data = array (
'drn' => $drn_number
);
//print_r($data); exit;
$params = '';
foreach($data as $key=>$value)
$params .= $key.'='.$value;

$params = trim($params); 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url); //Remote Location URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //Timeout after 10 seconds
//    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_HEADER, 0);

//We add these 2 lines to create POST request
//   curl_setopt($ch, CURLOPT_POST, count($data)); //number of parameters sent
curl_setopt($ch, CURLOPT_POSTFIELDS,"bill_status=" .$params); //parameters data

$result = curl_exec($ch);
curl_close($ch);
//print_r($result) ;
//print_r($result);

$xml=simplexml_load_string($result);
/*$array = json_decode(json_encode((array)$xml), true);
$array = array($xml->getName() => $array);
print_r($array);*/
$json  = json_encode($xml);
$configData = json_decode($json, true);

//print_r($configData); exit;

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//'".$_SESSION['user_info']['stake_user']."'
$i=0;

foreach($configData['BILL_ERRORS']['BILL_ERROR_CODE'] as $key=>$val)
		{
    if($i==0){
	$bill_error_code.=$val;
    }else if($i!=0){
     $bill_error_code.=','.$val;
    }
    $i=$i+1;
}
//print_r($bill_error_code) ; die;


	
$bill_status_check=$db->fetch_table("SELECT 
		description,code
	FROM 
		prd_ifms_response_code_master 
		where code in ('".$configData['BILL_STATUS_FLAG']."')
		
	");

$bill_error=$db->fetch_table("SELECT 
		description,code
	FROM 
		prd_ifms_response_code_master 
		where 
		CAST(code as integer) in($bill_error_code)

		
	");
//$status_check=$db->fetch_table("SELECT  code,
//  description,
//  code_master_id_pk
//		FROM 
//		prd_ifms_response_code_master
//		WHERE 
//		code in('".$configData['BILL_STATUS_FLAG']."' || '".$configData['BILL_ERROR_CODE']."')
//	");

if($bill_status_check)
{
echo $bill_status_check[0]['description']; "<br>";
echo '[';
 foreach($bill_error as $value)
		{
     
	    echo $value['description'].',';
		}
echo ']';
}
else
{
echo 'NO Data Found';
}
?>