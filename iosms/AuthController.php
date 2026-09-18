<?php 
  class AuthController {
    var $AuthKey = '123456789';
   	function __construct()
   	{
        
   	}
      function getAuthorizationHeader(){
              $headers = null;
              if (isset($_SERVER['Authorization'])) {
                  $headers = trim($_SERVER["Authorization"]);
              }
              else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                  $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
              } elseif (function_exists('apache_request_headers')) {
                  $requestHeaders = apache_request_headers();
                  // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                  $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                  //print_r($requestHeaders);
                  if (isset($requestHeaders['Authorization'])) {
                      $headers = trim($requestHeaders['Authorization']);
                  }
              }
              return $headers;
          }
      /**
       * get access token from header
       * */
      function getBearerToken() {
          $headers = $this->getAuthorizationHeader();
          //print_r($headers); exit;
          // HEADER: Get the access token from the header
          if (!empty($headers)) {
              //if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $Bearer = trim(str_replace('Bearer', '', $headers));
                  return $Bearer;
              //}
          }
          return null;
         } 
      function checkAuthValidation()
         {
          
         	$bearerToken = $this->getBearerToken();
         	$AuthKey = $this->AuthKey;
         	if($bearerToken != $AuthKey)
         	{
         		return json_decode(json_encode(array('status'=>"error",'msg'=>"Api Validation Error")));
         		
         	}
          else
          {
            return json_decode(json_encode(array('status'=>"success")));
          }
         }  
      function checkPriAuthValidation()
         {
          
          $bearerToken = $this->getBearerToken();
          global $post,$db;
          $Query = "SELECT * from prd_stack_user_login WHERE token='".$bearerToken."'";
          $StackDetails = $db->fetch_obj($Query);
          
          
          if(count($StackDetails) <= 0)
          {
            return json_decode(json_encode(array('status'=>"error",'msg'=>"Api Validation Error")));
            
          }
          else
          {
            return json_decode(json_encode(array('status'=>"success",'StackDetails'=>$StackDetails[0])));
          }
         }            

  }
?>