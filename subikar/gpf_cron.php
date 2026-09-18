<?php 
require '../includes/config/config.php';
require '../includes/config/database.config_api.php';
require '../includes/library/database.class.php';
$db = new database();

$Query = "SELECT * from prd_gpf_request_master WHERE error_status ='1'; ";
$ResponseStatus = $db->fetch_table($Query);
//print_r($ResponseStatus); exit;
foreach($ResponseStatus as $response)
{
	   
	    $fullResponse = $response['full_response'];
		$key = 'dGl4nfy0jGtbT23z'; //base64_decode("G0HPTE61KCQ+CYn3voqMlFnXEtpaow6gYDqaaGSVzuE=");
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		//$iv = openssl_random_pseudo_bytes($ivlen);
		$iv = 'eT83Bhtd023jSkyg'; //'abcdefghijklmnop';
		$fullResponse = json_decode( $fullResponse ); 
		$fullResponse = $fullResponse->req->encData;
		$fullResponse = base64_decode($fullResponse);
		 
		
		$ciphertext_raw = openssl_decrypt($fullResponse, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
		print_r($ciphertext_raw);
		echo "<hr />";
		//$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		
		//$hash_jeson_cs = base64_encode($ciphertext_raw); 	
		//print_r($ciphertext); exit;
}

?>