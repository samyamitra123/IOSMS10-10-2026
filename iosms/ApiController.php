<?php 
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	require_once '../includes/library/cryptography.class.php';
	require 'AuthController.php';
	global $db,$task,$crypto;
	$db=new database();	
	$crypto = new cryptography();
  /**
   * 
   */
  class ApiController 
  {
  	var $config_token = '198763456';
  	function __construct()
  	{
  		global $post;
      $post = file_get_contents("php://input");
      $post = json_decode($post,true);
  		//$get   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
      //$post  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      //$this->return = "subikar"; exit;
  		//$this->token = $this->getBearerToken();
  		$this->apiLog();

  		$this->token = '198763456';
  		if($this->token == $this->config_token)
  		{
  			 $data = $this->methodFunction();
  			 if($data['error'] == 0)
            $this->return =  json_encode(array('status'=>'Success','data'=>$data,'error'=>0));
         else
         	  $this->return =  json_encode(array('status'=>'Fail','error'=>$data['error_msg']));
  		}
  		else
  		{
  			 $this->return = json_encode(array('error'=>'You Do not have access'));
  		}
  	}
  	function apilog()
  	{
  		  global $post,$db;
        
				$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
				$uriEx = explode( '/', $uri );

				// all of our endpoints start with /person
				// everything else results in a 404 Not Found
				if ($uriEx[1] !== 'iosms') {
				    header("HTTP/1.1 404 Not Found");
				    exit();
				}
				$action = $uri;  
				$ip = $_SERVER['REMOTE_ADDR'];	
				$postData = json_encode($post);	
				$Query = "INSERT INTO api_log (ip_address,collection_data,collection_api) 
				           VALUES ('".$ip."','".$postData."','".$action."')";
				//return $Query;
				$db->update($Query);
  	}
  	function methodFunction()
  	 {
  	 	 global $task;
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode( '/', $uri );

		// all of our endpoints start with /person
		// everything else results in a 404 Not Found
		if ($uri[1] !== 'iosms') {
		    header("HTTP/1.1 404 Not Found");
		    exit();
		}
		$action = $uri[2];
		$task = isset($uri[3])?$uri[3]:'default';
		$requestMethod = $_SERVER["REQUEST_METHOD"]; 
   // print('Controller/'.$action.'Controller.php'); exit;
		if(file_exists('Controller/'.$action.'Controller.php'))
		   {
              
		   	  require_once('Controller/'.$action.'Controller.php');
              //print('Controller/'.$action.'Controller.php'); exit;
          $actionClass = $action.'Controller';
		   	  $actionobj = new $actionClass();
		   	///  print_r($actionobj); exit;
		   	  return $actionobj->data;
		   }
   	 else
	  	 {
	  	 	  return "Function Not Found";
	  	 }
  }	 
}

?>