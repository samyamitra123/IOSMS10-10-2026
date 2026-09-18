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
      //print_r($url1);
   
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
       
 //echo $result;die;
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
	
	$sftp=sftp_login($target_ip,$user_name,$password);

	if($sftp!=0)
	{
		$remote_file=$remote_dept.'ePayment_Files_006/'.$file_name;
		
		$local_file=$local_directory.$file_name;
		
			if ($sftp->put($remote_file,$local_file,NET_SFTP_LOCAL_FILE)!='') 
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