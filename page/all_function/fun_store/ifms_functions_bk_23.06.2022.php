<?php

function StatusCheckAPI($method, $url, $data){

	 
     
       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
             break;
          default:
             if ($data)
		 
                $url = sprintf("%s?%s", $url, http_build_query($data));
	    
	    // $url=$url.$data;http_build_query
       }

       // OPTIONS:
   $curl = curl_init($url);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $result = curl_exec($curl);
   
    if(!$result)
	   {
	   die("Connection Failure");
	   
	   }
       curl_close($curl);
       return $result;
 }
/***************************************************************** SFTP LOGIN *******************************************************************/

function sftp_login($target_ip,$user_name,$password)
{  
	$sftp = new Net_SFTP($target_ip);
		if (!$sftp->login($user_name, $password)) 
		{
			return 0;
		}
		else
		{
			return $sftp;
		}
		
}

/******************************************************************* BILL SEND *************************************************************************/
  function callAPI($method, $url, $data){
	//print_r($data); die;
     
       $curl = curl_init();
       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
             break;
          default:
             if ($data)
		 
                $url1 = sprintf("%s?%s", $url, http_build_query($data));
	    // $url=$url.$data;http_build_query
       }
 	//echo $url1;die;
       // OPTIONS:
//print_r($data); die;
//      $url1=$url.'?'.'xmlString='.$data;
//      print_r($url1);
   
       curl_setopt($curl, CURLOPT_URL, $url1);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'APIKEY: 111111111111111111111',
          'Content-Type: application/xml',
       ));
       
      //echo $url1;die;
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
   
       $result = curl_exec($curl);
       
       if(!$result)
	   {
		  
	   	die("Connection Failure");
	   
	   }
       curl_close($curl);
       return $result;
    }
//function bill_send($method,$target_url,$data)
//{ 

//	$ch = curl_init();
//	
//	curl_setopt($ch, CURLOPT_URL, $url);
//	curl_setopt($ch, CURLOPT_POSTFIELDS,
//	"xmlString=" . $xml_file);
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//	$data1 = curl_exec($ch);
//	curl_close($ch); 
//	$arry=simplexml_load_string($data1);
//	$json  = json_encode($arry);
//	$configData = json_decode($json, true);
//	return $configData;
//}

/***************************************************************** BENEFICIARY UPLOAD *******************************************************************/

function sftp_benf_upload($target_ip,$user_name,$password,$remote_dept,$local_directory,$file_name)
{
	//echo $target_ip.'--'.$user_name.'--'.$password.'--'.$remote_dept.'--'.$local_directory.'--'.$file_name;die;
	
	$sftp=sftp_login($target_ip,$user_name,$password);

	if($sftp!=0)
	{
		
		$remote_file=$remote_dept.'ePayment_Files_006/'.$file_name;
		
		
		$local_file=$local_directory.$file_name;
		
			if ($sftp->put($remote_file,$local_file,NET_SFTP_LOCAL_FILE)!='') 
			{
				//echo 88888888; die;
				//return $remote_file;
				return 1;
			}
			else
			{
				//return $sftp -> getSFTPErrors();
			//echo  3333333; die;
				return 0;
			}
	}
	else
	{
		
		 //echo  55555; die;
		return 2;
	}
}

/********************************************************* BENEFICIARY .DONE FILE READ **************************************************************/

function sftp_benf_dot_done_read($target_ip,$user_name,$password,$folder_name)
{
	$sftp_coonection= sftp_login($target_ip,$user_name,$password);
	if($sftp_coonection=='0')
	{
		return 2;
	}
	else
	{
		
		$remote_directory_002='/'.$folder_name.'/ePayment_Files_002/';
		
		$acknowledgment_file = $sftp_coonection->nlist($remote_directory_002);
		
		//$acknowledgment_file = array_slice($acknowledgment_file,2);
		//print_r($acknowledgment_file);die;
		$count=count($acknowledgment_file);
		if($count==0)
		{
			return 0;
		}
		else
		{
			foreach($acknowledgment_file as $file)
			{
				$dot_done = preg_match("/^.*\.(xml.done)$/i", $file);
				if ($dot_done) 
				{
					$dot_done_file[]=$file;
				}
			}
			return $dot_done_file;
		}
	}
}

/****************************************** BENEFICIARY .DONE FILE CHECK AND RECIVED STATUS UPDATE **********************************************/

/*function update_benf_dot_done_status($dot_done_file,$table_name){
$db = new database();
$dotdone_file=explode(".done",$dot_done_file);	
$sftp_benf_upload_response=$db->fetch_table("select count(sftp_benf_file_name) as count_file from ".$table_name." where active_status='1' and sftp_benf_sending_status='1' and sftp_benf_file_name='".$dotdone_file[0]."'");

if($sftp_benf_upload_response[0]['count_file']==1){
	$update_ifms_status=$db->update("UPDATE ".$table_name." SET sftp_benf_sending_status='2' WHERE active_status='1' and sftp_benf_sending_status='1' and sftp_benf_file_name='".$dotdone_file[0]."'");
	return 1;
}
else{
	return 0;
}
}*/

function update_benf_dot_done_status($dot_done_file,$table_name)
{
	$db = new database();
	$dotdone_file=explode(".done",$dot_done_file);	
	
	$sftp_benf_upload_response=$db->fetch_table("SELECT count(sftp_benf_file_name) as count_file from ".$table_name." WHERE active_status='1' AND sftp_benf_sending_status='3' AND sftp_benf_file_name='".$dotdone_file[0]."'");
	if($sftp_benf_upload_response[0]['count_file']==1)
	{
		
		$update_ifms_status=$db->update("UPDATE ".$table_name." SET sftp_benf_sending_status='4' WHERE active_status='1' and sftp_benf_sending_status='3' and sftp_benf_file_name='".$dotdone_file[0]."'");
		if($update_ifms_status)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}


/************************************************** NEW WEB SETRVICE start **************************************************/

function call_WEBSERVICE($filename, $path, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id){
	
	//echo 55555555; die;
	
	 date_default_timezone_set('Asia/Kolkata');
	$current_millisec = mktime();
	 $time_stamp = $current_millisec.'000';
	 
	
	if(isset($filename)){
   
   
  
  if($flag=='1')
  {
	  
	  

		 
		 
		$orignal_parse = parse_url($url, PHP_URL_HOST);
		$opts = array("ssl" => array("capture_peer_cert" => true)); 
		
		$get = stream_context_create($opts);
		
		
		$url = 'https://wbifms.gov.in';
		
		$orignal_parse = parse_url($url, PHP_URL_HOST);
		
		$read = stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr, 90, STREAM_CLIENT_CONNECT , $get);
		$cert=(stream_context_get_params($read)); 
		
		$certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
		
		
		$valid_from = date(DATE_ATOM,$certinfo['validFrom_time_t']);
		$valid_from_date= date("Y-m-d",strtotime($valid_from));
		$valid_to = date(DATE_ATOM,$certinfo['validTo_time_t']);
		$valid_to_date= date("Y-m-d",strtotime($valid_to));
		
		
		
		 
		
 // $target_url = 'http://202.61.117.90/publicapi/epay/api/file/upld/md/1/multipart/epayup/'.$party_code_api.'/'.$request_id.'/'.$time_stamp;// UAT
  
 // $target_url = '202.61.117.96/gws/epay/api/file/upld/md/1/multipart/epayup/'.$party_code_api.'/'.$request_id.'/'.$time_stamp; 
   $target_url = 'https://wbifms.gov.in/gws/epay/api/file/upld/md/1/multipart/epayup/'.$party_code_api.'/'.$request_id.'/'.$time_stamp; 
 
 
	    $fname = $path.$zipped_name; 
	  
	  $cfile = realpath($fname); 
    
	   if(($certinfo['subject']['CN']=='www.wbifms.gov.in' && $certinfo['issuer']['CN']=='DigiCert Global CA G2') )
		 {
			 $check='2';
		 }
		 else
		 {
			$check='3';
		 }
		
		
		if($check=='2')
		{
			if( $certinfo['validFrom_time_t'] > time() || $certinfo['validTo_time_t'] < time() ){
		echo "Certificate is expired."; 
		}
		else
		{ 
		 $postdata = 
            array (
			
					
                    'usersl'=> 1,
					'filename' => $zipped_name,					
                    'username' => $username,
                    'password' => $password,
                    'filetype' => $fileType,
					'file' => '@'. $cfile
                  ); 
		}
		}
	  
	//print_r($postdata); die;
			 
         
		  
		   $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $target_url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
          curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
          curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 100);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

          $result = curl_exec ($ch);
		  
		
          
          if ($result === FALSE) {
              echo "Error sending" . $fname .  " " . curl_error($ch);
              curl_close ($ch);
          }else{
              curl_close ($ch);
			  
			 
           return $result;
			
          }
		  
	  
  }

	}
	
}

function createZip($zip,$dir,$name,$path,$path_sign,$sig_file){
	
	

  if (is_dir($dir)){
	
if ($dh = opendir($dir)){
    

 $zip->addFile($path.$name, $name);
 $zip->addFile($path_sign.$sig_file, $sig_file);
 
  closedir($dh);
}
      
  }
  

}

//function createZip($zip,$dir,$name,$path,$path_sign,$sig_file){
//	
//	
//
//  if (is_dir($dir)){
//	
//
//    if ($dh = opendir($dir)){
//       while (($file = readdir($dh)) !== false){
//		   
//        
//            if($file != '' && $file != '.' && $file != '..'){
//				 // echo 777777; die;
//
// $zip->addFile($path.$name, $name);
// $zip->addFile($path_sign.$sig_file, $sig_file);
// 
// 
//            }
//       }
//       closedir($dh);
//     }
//  }
//  
//
//}

function call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id){


	
	
	 date_default_timezone_set('Asia/Kolkata');
	$current_millisec = mktime();
	 $time_stamp = $current_millisec.'000';
	
	
	  
	   //$target_url = 'http://202.61.117.90/publicapi/epay/api/file/dwnld/multipart/epaydn/'.$party_code_api.'/'.$request_id.'/'.$time_stamp; 
	 // $target_url = '202.61.117.96/gws/epay/api/file/dwnld/multipart/epaydn/'.$party_code_api.'/'.$request_id.'/'.$time_stamp;
	 
	 
	  $target_url = 'https://wbifms.gov.in/gws/epay/api/file/dwnld/multipart/epaydn/'.$party_code_api.'/'.$request_id.'/'.$time_stamp;
	



$postdata = 
            array (
			
			        //'hashVal'=>1,
                    'usersl'=> 1,
					'filename' => $zipped_name,					
                    'username' => $username,
                    'password' => $password,
                    'filetype' => $fileType,
					
                  );
				  
				//print_r($postdata); die; 
				
             
			 
		$ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $target_url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
          curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
          curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 100);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        

          $result = curl_exec ($ch);
		  
		//print_r($result); die;
		   if ($result == FALSE) {
				  echo "Error sending" .$zipped_name .  " " . curl_error($ch);
				  curl_close ($ch);
			  }else{
				
				  
				  
				  
				   curl_setopt($ch, CURLOPT_HEADER, 1);
                 curl_setopt($ch, CURLINFO_HEADER_OUT, true);
				 
				$result_for_fname = curl_exec ($ch);
				 
				 
				
				 $header_arr=explode("\r\n", $result_for_fname);
				  
				 
				 foreach($header_arr as $header_str)
				 {
					
					 if(substr($header_str,0,19)=="Content-Disposition")
					 {
						 $header_str_1=explode(";", $header_str);
						
						 $file_name_sent=explode('"',$header_str_1[1]);
						  $file_name_sent_fromIFMS=$file_name_sent[1]; 
					 }
				 }
				 
				
				 
				  if($file_name_sent_fromIFMS!='')
				 {
					
					if(file_put_contents($local_directory_zip.$file_name_sent_fromIFMS, $result))
					{
						
						
						$zip = new ZipArchive;
						
						$res = $zip->open($local_directory_zip.$file_name_sent_fromIFMS);
						
						if ($res === TRUE) {
							
						  if($zip->extractTo($local_directory))
						  {
							  $r='1'; 
							 return $r;
						  }
						  $zip->close();
						} 
					}
				 }
				 
				 /*curl_close ($ch);
				 
				  file_put_contents('download.zip', $result);*/
				 
				 curl_close ($ch);
				 
				return $result;
			  }
          
         
}


function request_id_generation($party_code)
	{
		
		
		$db = new database();
		//global $dynamnic_yr_mnth_sal;
		$get_request_seq=$db->fetch_table("SELECT nextval('request_no_id_seq') AS request_no_id_seq;");
		$cur_yr=date("y");
		 $sum=$get_request_seq[0]['request_no_id_seq']; 
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		$request_id=$cur_yr.date("m").$inc;
		$unique_check=$db->fetch_table("select request_id_pk FROM prd_ifms_request_master WHERE request_id='".$request_id."'");
		
		if(!empty($unique_check))
		{
			//$unique_check[0]['drn_number'];
			//drn_generation($party_code);
			$this->request_id_generation($party_code);
		}
		else
		{
			//echo $id_drn; die;
			return $request_id;
		}
	}







/**************************************** end *********************************************************/

/*************************************************epension webservice*********************************/


	
	function call_epension($accessKey,$securityKey,$myJSON){
	
	//$target_url = ''; 
	$postdata=$myJSON;
	
	/*$postdata = 
	array (
	
	
	'securityKey'=> $securityKey,
	'accessKey' => $accessKey,					
	'myJSON' => $myJSON
	
	); */
	
	$myobj->emp_id="PE2016015762";
	$myobj->dept_type="S";
	$myobj->status="true";
	$myobj->error_code="0";
	$myobj->ack_time="21-05-31";
	$myobj->new_modified_flag="N";
	$myobj->request_id="1566744674";
	$myJSON=json_encode($myobj);

	$result=$myJSON; 
	return $result;
	
	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
	//curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json',
	'securityKey:'.$securityKey.'','accessKey:'.$accessKey.''));
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	
	$result = curl_exec ($ch);*/
	
	
	
	/*if ($result === FALSE) {
	echo "Error sending" . $fname .  " " . curl_error($ch);
	curl_close ($ch);
	}else{
	curl_close ($ch);
	
	
	return $result;
	
	}*/

}
	
	////////////////////////////////////////////////////////////////////
	function call_epension_ack($emp_id_const){
	
	echo  $target_url = 'http://ependemo.wb.gov.in/ePensionEduAPI/ServiceAPI/iosmsAPIController/Ack?empid='.$emp_id_const.''; die;
	echo $emp_id_const; die;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
	curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json',
	'emp_id:'.$emp_id_const.''));
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	
	$result = curl_exec ($ch);
	
	
	
	if ($result === FALSE) {
	echo "Error sending" . $fname .  " " . curl_error($ch);
	curl_close ($ch);
	}else{
	curl_close ($ch);
	
	
	return $result;
	
	}
	
	
	
	
	}
	
	

///////////////////////////////////////////////////////////////////////

function application_id($flag,$emp_id_const)
	{
		
		$db = new database();
		//global $dynamnic_yr_mnth_sal;
		$get_request_seq=$db->fetch_table("SELECT nextval('application_no_id_seq') AS application_no_id_seq;");
		$cur_yr=date("y");
		 $sum=$get_request_seq[0]['application_no_id_seq']; 
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		$application_id=$flag.'/'.$cur_yr.date("m").'/'.$emp_id_const.'/'.$inc;
		//return $application_id;
		
$unique_check=$db->fetch_table("select application_id_id_pk FROM intra_pri_application_master WHERE application_id='".$application_id."'");
		
		if(!empty($unique_check))
		{
			//$unique_check[0]['drn_number'];
			//drn_generation($party_code);
			$this->application_id($flag,$emp_id_const);
		}
		else
		{
			//echo $id_drn; die;
			return $application_id;
		}
		
		
	}




?>
