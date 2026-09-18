<?php
  
   class  NGIPF
    {

       function syncData()
        {
           global $db;
           $pnrd_request = json_decode($this->NgEmpDetails['pnrd_request'],true);
           $pnrd_req_2   = json_decode($this->NgEmpDetails['pnrd_req_2'],true);
           
           $NewPnrRequest['basicDtls'] = $pnrd_req_2['basicDtls'];
           $NewPnrRequest['otherDtls'] = $pnrd_req_2['otherDtls']['newDtls']; 
           $NewPnrRequest['relationDtls'] = $pnrd_req_2['relationDtls']['newDtls']; 
           $NewPnrRequest['spouseDtls'] = $pnrd_req_2['spouseDtls']['newDtls']; 
           $NewPnrRequest['addrDtls'] = $pnrd_req_2['addrDtls']['newDtls']; 
           $NewPnrRequest['workDtls'] = $pnrd_req_2['workDtls']['newDtls']; 
           $NewPnrRequest['exitSerDtls'] = $pnrd_req_2['exitSerDtls']['newDtls']; 
           $NewPnrRequest['payInfoDtls'] = $pnrd_req_2['exitSerDtls']['payInfoDtls']['newDtls']; 
           $NewPnrRequest['payAllowDtls'] = $pnrd_req_2['exitSerDtls']['payAllowDtls']['newDtls']; 
           $NewPnrRequest['benfDtls'] = $pnrd_req_2['exitSerDtls']['benfDtls']['newDtls']; 
          // print_r($NewPnrRequest); exit;
          foreach($NewPnrRequest as $key=>$NewRequest)
          {

              if(isset($pnrd_request['req']['empDtls'][$key][0]))
                 {

                      foreach($NewRequest as $mkey=>$mvalue)
                          {
                             if($mvalue != '')
                               $pnrd_request['req']['empDtls'][$key][0][$mkey] =  $mvalue;
                          }
                     /* $temp = $pnrd_request['req']['empDtls'][$key][0];
                      unset($pnrd_request['req']['empDtls'][$key]);
                      $pnrd_request['req']['empDtls'][$key][0] = $temp;  */  

                 }
              else{
                      
                      foreach($NewRequest as $mkey=>$mvalue)
                          {
                             if($mvalue != '')
                               $pnrd_request['req']['empDtls'][$key][$mkey] =  $mvalue;
                          }
                 }   
          }

  /*$spouseDtls = $pnrd_request['req']['empDtls']['spouseDtls'];
  unset($pnrd_request['req']['empDtls']['spouseDtls']);
  $pnrd_request['req']['empDtls']['spouseDtls'][0] = $spouseDtls;
 
  $spouseDtls = $pnrd_request['req']['empDtls']['workDtls'];
  unset($pnrd_request['req']['empDtls']['workDtls']);
  $pnrd_request['req']['empDtls']['workDtls'][0] = $spouseDtls;
 
   $spouseDtls = $pnrd_request['req']['empDtls']['payInfoDtls'];
  unset($pnrd_request['req']['empDtls']['payInfoDtls']);
  $pnrd_request['req']['empDtls']['payInfoDtls'][0] = $spouseDtls;

   $spouseDtls = $pnrd_request['req']['empDtls']['payAllowDtls'];
  unset($pnrd_request['req']['empDtls']['payAllowDtls']);
  $pnrd_request['req']['empDtls']['payAllowDtls'][0] = $spouseDtls; */
 


          //print_r($pnrd_request); exit;
          $pnrd_request = json_encode($pnrd_request);
          $Query = "update prd_gpf_request_master SET pnrd_request='".$pnrd_request."' WHERE emp_id_const='".$this->empLoyeeDetals['emp_id_const']."'";
          $db->update($Query);
        }
       function sendToNGIPF()
         {
            $this->generateGpfId();
            $this->callGPF();
           // print_r($this->ngipfEmpSendDetails);
           // print_r($this->gpfId);
         }

       function callGPF()
         {
               $ngipfEmpSendDetails['req']['empDtls'] = $this->ngipfEmpSendDetails;
               $ngipfEmpSendDetails = json_encode($ngipfEmpSendDetails);
               $ngipfEmpSendDetails = str_replace('\/','/',$ngipfEmpSendDetails);
               //print_r($ngipfEmpSendDetails); exit;
               $current_millisec = mktime();
               $time_stamp = $current_millisec.'000';
               $src="iOSMS";
               $src_ifms="PRD003";  
               $hash_jeson = hash("sha256", $ngipfEmpSendDetails); 
               $key = 'dGl4nfy0jGtbT23z'; //base64_decode("G0HPTE61KCQ+CYn3voqMlFnXEtpaow6gYDqaaGSVzuE=");
               $plaintext =$ngipfEmpSendDetails;
               $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
               $iv = 'eT83Bhtd023jSkyg'; //'abcdefghijklmnop';
               $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
               $hash_jeson_cs = base64_encode($ciphertext_raw);                         
               $reqDtls['req'] = array(
                                   "iosms_empId"=>$this->ngipfEmpSendDetails['ifmsDtls']['empId'], 
                                   "encData"=>$hash_jeson_cs, 
                                   "cs"=>$hash_jeson,
                                   "src"=>$src_ifms,
                                   "action"=>$this->ngipfEmpSendDetails['ifmsDtls']['modifyFlag']
                                ); 
               $this->reqDtls = json_encode($reqDtls);                     
               $this->target_url = $this->NGIPFURL.'/ngipf/employee-master/api/post/employee/'.$src.'/'.$this->gpfId.'/'.$time_stamp; 
               $sendStatus = $this->sendCurl();
               $this->sendStatus($sendStatus);

         }
       function sendStatus($pArguments)
         {
             global $db;
             $pArguments = json_decode($pArguments,true);
             $pArguments = $pArguments['resp'];
             //print_r($pArguments); exit;
             if($pArguments['status'] == 'S')
             {
                $Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$pArguments['empId']."'";
                $fetchDataInArray = $db->fetch_table($Query);
                 //print_r($Query); exit;
                if(count($fetchDataInArray) > 0)
                {

                   $Query ="UPDATE prd_gpf_request_master SET gpf_id_generation='".$this->gpfId."', status=2, hitresponse='".json_encode($pArguments)."', request='".$this->reqDtls."' , pnrd_req_2='".json_encode($this->ngipfEmpSendDetails)."' WHERE emp_id_const='".$pArguments['empId']."'";
                   $db->update($Query);  
                  // print($Query); exit;
                }
                // else
                // {
                //    $Query = "Insert INTO prd_gpf_request_master (emp_id_const, gpf_id_generation,pnrd_request,status) VALUES ('".$pArguments['empId']."','".$this->gpfId."','".json_encode($this->ngipfEmpSendDetails)."',2)";
                // }
                //print_r($Query); exit;
                          
             }
         }     
       function sendCurl()
         {
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $this->target_url);
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_HEADER, 0);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                  curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
                  curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                  curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                  curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $this->reqDtls);
                  $result = curl_exec ($ch); 
                  curl_close ($ch);           
                  if($result === FALSE){
                    echo "Error sending" . $fname .  " " . curl_error($ch); 
                    die();
                  } 
                  else
                  {
                     return $result;
                  }   

         }  
       function generateGpfId()
         {
               global $db;
               $get_request_seq=$db->fetch_table("SELECT nextval('gpf_id_generation') AS gpf_id_generation;");
               $cur_monyr=date('Ym');
               $sum=$get_request_seq[0]['gpf_id_generation'];
               
               
               $inc=str_pad($sum,11,'0',STR_PAD_LEFT);
               $request_id=$cur_monyr.$this->party_code.$inc; 
               $unique_check=$db->fetch_table("select request_id_pk FROM prd_gpf_request_master WHERE request_id='".$request_id."'");
               
               if(!empty($unique_check))
               {
                  
                  $this->generateGpfId();
               }
               else
               {
                  
                  $this->gpfId = $request_id;
               }            
         }   
    }
?>    