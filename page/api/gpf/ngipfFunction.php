<?php 
 function ModifyData($currentJson, $prevJson,$pfaccno)
   {
     $currentJson = json_decode($currentJson);
     //print_r($currentJson); exit;
     $prevJson = json_decode($prevJson);
     $prevJson = $prevJson->req->empDtls;
     $spouseDtls =  $prevJson->spouseDtls[0];
     unset($prevJson->spouseDtls[0]);
     $prevJson->spouseDtls = $spouseDtls;

     $workDtls =  $prevJson->workDtls[0];
     unset($prevJson->workDtls[0]);
     $prevJson->workDtls = $workDtls; 
     
     $payInfoDtls =  $prevJson->payInfoDtls[0];
     unset($prevJson->payInfoDtls[0]);
     $prevJson->payInfoDtls = $payInfoDtls; 

     $payAllowDtls =  $prevJson->payAllowDtls[0];
     unset($prevJson->payAllowDtls[0]);
     $prevJson->payAllowDtls = $payAllowDtls; 


     unset($prevJson->benfDtls->benfWef);
     //$currentJson = $currentJson->req->empDtls;
     $NewData = array();
    // $NewData['basicDtls'] = $currentJson->req->empDtls->basicDtls;
     //unset($currentJson->req->empDtls->basicDtls);
     $overlookattr = array('maritalStatusWef','cpfRefWef','spouseNmWef','presAdrWef','permAdrWef','apptAppIsActive','ropaWef','basicPayWef','oldDtls','payAllowDtls','gradePayWef');
     //$groupLookAttr = [{'maritalStatusIsActive','maritalStatusWef'}];
     foreach($currentJson->req->empDtls as $key=>$curData)
       {

        $previousJsonData = $prevJson->$key;
        //print_r($key);
        $Temp = array();
        foreach($curData as $mkey=>$cData)
        {  
          $Temp[$mkey] = "";
          //echo $previousJsonData->$mkey.' = '.$cData;
            if(!is_object($cData))
            {
                if(!in_array($mkey,$overlookattr))
                {
                  if($previousJsonData->$mkey != $cData)
                  {
                      $Temp[$mkey] = $cData;
                  }
                }
                else
                {
                    $groupData = GroupFunction($mkey,$previousJsonData,$curData);

                    //print_r($groupData); exit;
                    if(count($groupData) > 0)
                    {
                      foreach($groupData as $gkey=>$gvalue)
                      {
                         $Temp[$gkey] = $gvalue;
                      }
                    }
                }
             }
             else
             {
                $prevData  = $previousJsonData[0];
                $icTemp = array();
                //print_r($cData); exit;
                foreach($cData as $ickey=>$icData)
                {

                  if(!in_array($ickey,$overlookattr))
                  {                 
                     if($prevData->$ickey != $icData)
                       {
                          $icTemp[$ickey] = $icData;
                       }
                  } 
                  else
                  {

                    $groupData = GroupFunction($ickey,$prevData,$cData);
                    $icTemp = $groupData;
                  }    
                }
                $Temp[$mkey] = $icTemp;
             }

        }
        $NewData[$key] = array (
                                'oldDtls'=>$previousJsonData,
                                'newDtls'=>$Temp
                               );
        //print_r($Temp); exit;
          
       }
       unset($NewData['basicDtls']['newDtls']['empId']);
       $basicDtls = $NewData['basicDtls']['newDtls'];
       unset($NewData['basicDtls']);
       unset($NewData['cDateTime']);
       //print_r($NewData); exit;
       $NewData = BlankDetectFunction($NewData); 
       

       $NewData['basicDtls'] = $basicDtls;
       $NewData['cDateTime'] = date('Y-m-d hh:i');
       $NewData['exitSerDtls']['payInfoDtls'] =  $NewData['payInfoDtls'];
       unset($NewData['payInfoDtls']);

       $NewData['exitSerDtls']['payAllowDtls'] =  $NewData['payAllowDtls'];
       unset($NewData['payAllowDtls']);

       $NewData['exitSerDtls']['benfDtls'] =  $NewData['benfDtls'];
       unset($NewData['benfDtls']);  

       //print_r($NewData); exit;

       $ifmsDtls = Array(
                          'empId'=>$prevJson->basicDtls->empId,
                          'pfAccNo'=>$pfaccno, // will get it from table
                          'modifyFlag'=>'M'
                        );
       $NewData['ifmsDtls'] = $ifmsDtls;
       $newJson['req']['empDtls'] = $NewData;
       $Rjson = json_encode($newJson);
       print_r(json_decode($Rjson)); exit;
      return $Rjson; 
   }
 /*function AdvGroupFunction($currentJsonData,$prevJsonData)
   {
       $overlookattr = array('maritalStatusWef','cpfRefWef','spouseNmWef','presAdrWef','permAdrWef','apptAppIsActive','ropaWef','basicPayWef','oldDtls','payAllowDtls');
       $newDetails = array();
       foreach($currentJsonData as $key=>$value)
       {
           if($value == $prevJsonData->$key)
             {
                 $newDetails[$key] = '';
             }
           else
             {
                $newDetails[$key] = $value;
             }  
       }
       return  $newDetails;
   }  
  function BasicGroupFunction($currentJsonData,$prevJsonData)
   {
       $newDetails = array();
       foreach($currentJsonData as $key=>$value)
       {
           if($value == $prevJsonData->$key)
             {
                 $newDetails[$key] = '';
             }
           else
             {
                $newDetails[$key] = $value;
             }  
       }
       return  $newDetails;
   }   */ 
 function BlankDetectFunction($Data)
   {
    $NewArray = array();
    foreach($Data as $key=>$newData)
    {
       
       foreach($newData['newDtls'] as $mkey=>$value)
       {
          if($value != '')
           {
              //print_r($newData['oldDtls']); exit;
              $NewArray[$key]['oldDtls'][$mkey] =  $newData['oldDtls']->$mkey;
              $NewArray[$key]['newDtls'][$mkey] = $value;
           } 
          else
           {
              $NewArray[$key]['oldDtls'][$mkey] = "";
              $NewArray[$key]['newDtls'][$mkey] = "";            
           }   
       }
      
    }
    return $NewArray;

   }  
 function  GroupFunction($mkey,$previousJsonData,$curData)
   {
   	  //print($mkey); exit;
   	  $temp = array();
      switch($mkey)
        {
        	 case 'maritalStatusWef':
        	   {

                if($previousJsonData->maritalStatusIsActive != $curData->maritalStatusIsActive)
                {
                	$temp['maritalStatusIsActive'] = $curData->maritalStatusIsActive;
                	$temp['maritalStatusWef'] = $curData->maritalStatusWef;
                }
                else
                {
                  $temp['maritalStatusIsActive'] = "";
                  $temp['maritalStatusWef'] = "";                  
                }  
        	   	  break;
        	   }
        	 case 'cpfRefWef':
        	   {

                if($previousJsonData->cpfRefIsActive != $curData->cpfRefIsActive)
                {
                  $temp['cpfRefDt'] = $curData->cpfRefDt;
                  $temp['cpfRefTresNm'] = $curData->cpfRefTresNm;
                  $temp['cpfRefAmt'] = $curData->cpfRefAmt;
                	$temp['cpfRefIsActive'] = $curData->cpfRefIsActive;
                	$temp['cpfRefWef'] = $curData->cpfRefWef;
                  $oldDetails = $previousJsonData;
                }
                else
                {
                  $temp['cpfRefDt'] = "";
                  $temp['cpfRefTresNm'] = "";
                  $temp['cpfRefAmt'] = "";
                  $temp['cpfRefIsActive'] = "";
                  $temp['cpfRefWef'] = "";
                  $oldDetails = $temp; 

                }  
        	   	  break;
        	   }
        	 case 'spouseNmWef':
        	   {
                if($previousJsonData->spouseNm != $curData->spouseNm || $previousJsonData->spouseNmIsActive != $curData->spouseNmIsActive )
                {
                	$temp['spouseNm'] = $curData->spouseNm;
                	$temp['spouseNmIsActive'] = $curData->spouseNmIsActive;
                	$temp['spouseNmWef'] = $curData->spouseNmWef;
                	//print_r($temp); exit;
                }
                else
                {
                	$temp['spouseNm'] = "";
                	$temp['spouseNmIsActive'] = "";
                	$temp['spouseNmWef'] = "";                	
                }
        	   	  break;        	   	  
        	   } 
        	 case 'presAdrWef':
        	   {
                if($previousJsonData->presStreet != $curData->presStreet || $previousJsonData->presCity != $curData->presCity || $previousJsonData->presDist != $curData->presDist || $previousJsonData->presState != $curData->presState || $previousJsonData->presPin != $curData->presPin || $previousJsonData->presAdrIsActive != $curData->presAdrIsActive)
                {
                	$temp['presStreet'] = $curData->presStreet;
                	$temp['presCity'] = $curData->presCity;
                	$temp['presDist'] = $curData->presDist;
                	$temp['presState'] = $curData->presState;
                	$temp['presPin'] = $curData->presPin;
                	$temp['presAdrIsActive'] = $curData->presAdrIsActive; 
                	$temp['presAdrWef'] = $curData->presAdrWef;               	
                	//print_r($temp); exit;
                }
                else
                {
                  $temp['presStreet'] = "";
                  $temp['presCity'] = "";
                  $temp['presDist'] = "";
                  $temp['presState'] = "";
                  $temp['presPin'] = "";
                  $temp['presAdrIsActive'] = ""; 
                  $temp['presAdrWef'] = "";                 
                  //print_r($temp); exit;
                }  
        	   	  break;        	   	  
        	   }   
           case 'permAdrWef':
             {
                if($previousJsonData->permStreet != $curData->permStreet || $previousJsonData->permCity != $curData->permCity || $previousJsonData->permDist != $curData->permDist || $previousJsonData->permState != $curData->permState || $previousJsonData->permPin != $curData->permPin || $previousJsonData->permAdrIsActive != $curData->permAdrIsActive)
                {
                  $temp['permStreet'] = $curData->permStreet;
                  $temp['permCity'] = $curData->permCity;
                  $temp['permDist'] = $curData->permDist;
                  $temp['permState'] = $curData->permState;
                  $temp['permPin'] = $curData->permPin;
                  $temp['permAdrIsActive'] = $curData->permAdrIsActive; 
                  $temp['permAdrWef'] = $curData->permAdrWef;                 
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['permStreet'] = "";
                  $temp['permCity'] = "";
                  $temp['permDist'] = "";
                  $temp['permState'] = "";
                  $temp['permPin'] = "";
                  $temp['permAdrIsActive'] = ""; 
                  $temp['permAdrWef'] = "";                 
                  //print_r($temp); exit;
                }                 
                break;                
             }               
        	 case 'apptAppIsActive':
        	   {

                if($previousJsonData->ddoCode != $curData->ddoCode || $previousJsonData->ddoIsActive != $curData->ddoIsActive)
                {
                	$temp['ddoCode'] = $curData->ddoCode;
                	$temp['ddoWef'] = $curData->ddoWef;
                	$temp['ddoIsActive'] = $curData->ddoIsActive;
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['ddoCode'] = "";
                	$temp['ddoWef'] = "";
                	$temp['ddoIsActive'] = "";               	
                }


                if($previousJsonData->opCodeLf != $curData->opCodeLf || $previousJsonData->opCodeLfIsActive != $curData->opCodeLfIsActive || $previousJsonData->opCodeLfTresCode != $curData->opCodeLfTresCode)
                {
                	$temp['opCodeLf'] = $curData->opCodeLf;
                	$temp['opCodeLfWef'] = $curData->opCodeLfWef;
                	$temp['opCodeLfIsActive'] = $curData->opCodeLfIsActive;
                	$temp['opCodeLfTresCode'] = $curData->opCodeLfTresCode;
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['opCodeLf'] = "";
                	$temp['opCodeLfWef'] = "";
                	$temp['opCodeLfIsActive'] = "";
                	$temp['opCodeLfTresCode'] = "";                 	
                } 
                if($previousJsonData->opCodePf != $curData->opCodePf || $previousJsonData->opCodePfIsActive != $curData->opCodePfIsActive || $previousJsonData->opCodePfTresCode != $curData->opCodePfTresCode)
                {
                	$temp['opCodePf'] = $curData->opCodePf;
                	$temp['opCodePfWef'] = $curData->opCodePfWef;
                	$temp['opCodePfIsActive'] = $curData->opCodePfIsActive;
                	$temp['opCodePfTresCode'] = $curData->opCodePfTresCode;
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['opCodePf'] = "";
                	$temp['opCodePfWef'] = "";
                	$temp['opCodePfIsActive'] = "";
                	$temp['opCodePfTresCode'] = "";                 	
                }  
                if($previousJsonData->hooCode != $curData->hooCode || $previousJsonData->hooCodeIsActive != $curData->hooCodeIsActive)
                {
                	$temp['hooCode'] = $curData->hooCode;
                	$temp['hooCodeWef'] = $curData->hooCodeWef;
                	$temp['hooCodeIsActive'] = $curData->hooCodeIsActive;
                	
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['hooCode'] = "";
                	$temp['hooCodeWef'] = "";
                	$temp['hooCodeIsActive'] = "";
                	                 	
                }  
                if($previousJsonData->sancAuthCode != $curData->sancAuthCode || $previousJsonData->sancAuthIsActive != $curData->sancAuthIsActive)
                {
                	$temp['sancAuthCode'] = $curData->sancAuthCode;
                	$temp['sancAuthWef'] = $curData->sancAuthWef;
                	$temp['sancAuthIsActive'] = $curData->sancAuthIsActive;
                }
                else
                {
                 	$temp['sancAuthCode'] = "";
                	$temp['sancAuthWef'] = "";
                	$temp['sancAuthIsActive'] = "";
                	                 	
                } 
                if($previousJsonData->sectionCode != $curData->sectionCode || $previousJsonData->sectionCodeWef != $curData->sectionCodeWef || $previousJsonData->sectionCodeIsActive != $curData->sectionCodeIsActive)
                {
                  $temp['sectionCode'] = $curData->sectionCode;
                  $temp['sectionCodeWef'] = $curData->sectionCodeWef;
                  $temp['sectionCodeIsActive'] = $curData->sectionCodeIsActive;
                  
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['sectionCode'] = "";
                  $temp['sectionCodeWef'] = "";
                  $temp['sectionCodeIsActive'] = "";
                                    
                } 

                if($previousJsonData->recAuthCode != $curData->recAuthCode || $previousJsonData->recAuthCodeWef != $curData->recAuthCodeWef || $previousJsonData->recAuthCodeIsActive != $curData->recAuthCodeIsActive)
                {
                  $temp['recAuthCode'] = $curData->recAuthCode;
                  $temp['recAuthCodeWef'] = $curData->recAuthCodeWef;
                  $temp['recAuthCodeIsActive'] = $curData->recAuthCodeIsActive;
                  
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['recAuthCode'] = "";
                  $temp['recAuthCodeWef'] = "";
                  $temp['recAuthCodeIsActive'] = "";
                                    
                }   
                               
                if($previousJsonData->desig != $curData->desig || $previousJsonData->desigIsActive != $curData->desigIsActive)
                {
                	$temp['desig'] = $curData->desig;
                	$temp['desigWef'] = $curData->hooCodeWef;
                	$temp['desigIsActive'] = $curData->desigIsActive;
                	
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['desig'] = "";
                	$temp['desigWef'] = "";
                	$temp['desigIsActive'] = "";
                	                 	
                }                                                                                    
                 if($previousJsonData->apptAppNo != $curData->apptAppNo || $previousJsonData->apptAppDt != $curData->apptAppDt || $previousJsonData->apptWefDt != $curData->apptWefDt || $previousJsonData->apptAppIsActive != $curData->apptAppIsActive)
                {
                  $temp['apptAppNo'] = $curData->apptAppNo;
                  $temp['apptAppDt'] = $curData->apptAppDt;
                  $temp['apptWefDt'] = $curData->apptWefDt;
                  $temp['apptAppIsActive'] = $curData->apptAppIsActive;
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['apptAppNo'] = "";
                  $temp['apptAppDt'] = "";
                  $temp['apptWefDt'] = "";
                  $temp['apptAppIsActive'] = "";
                                    
                }                                                                                    


                                                                                  
                //print_r($temp); exit;
        	   	  break;        	   	  
        	   } 
             case 'ropaWef':
        	    {  
                if($previousJsonData->ropa != $curData->ropa || $previousJsonData->ropaIsActive != $curData->ropaIsActive)
                {
                	$temp['ropa'] = $curData->ropa;
                	$temp['ropaWef'] = $curData->ropaWef;
                	$temp['ropaIsActive'] = $curData->ropaIsActive;
                	
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['ropa'] = "";
                	$temp['desigWef'] = "";
                	$temp['ropaIsActive'] = "";
                	                 	
                }          	    	
        	    	break;
        	    } 
             case 'basicPayWef':
        	    {  
                if($previousJsonData->basicPay != $curData->basicPay || $previousJsonData->basicPayIsActive != $curData->basicPayIsActive)
                {
                	$temp['basicPay'] = $curData->basicPay;
                	$temp['basicPayWef'] = $curData->basicPayWef;
                	$temp['basicPayIsActive'] = $curData->basicPayIsActive;
                	
             	
                	//print_r($temp); exit;
                }
                else
                {
                 	$temp['basicPay'] = "";
                	$temp['basicPayWef'] = "";
                	$temp['basicPayIsActive'] = "";
                	                 	
                } 
                break;
              }                  
             case 'gradePayWef':
              {                 
                if($previousJsonData->gradePay != $curData->gradePay || $previousJsonData->gradePayIsActive != $curData->gradePayIsActive)
                {

                	$temp['gradePay'] = $curData->gradePay;
                	$temp['gradePayWef'] = $curData->gradePayWef;
                	$temp['gradePayIsActive'] = $curData->gradePayIsActive;
                	
             	
                	
                }
                else
                {
                 	$temp['gradePay'] = "";
                	$temp['gradePayWef'] = "";
                	$temp['gradePayIsActive'] = "";
                	                 	
                }   
                //print_r($temp); exit;                       	    	
        	    	break;
        	    }      	           	             	          	        	         	   


        }
      return $temp;

   }
 function SaveRequest($pArguments)
  {
  	 $db = new database();
  	 $Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$pArguments['emp_id_const']."' AND pfaccno = '' ";
  	 $fetchDataInArray = $db->fetch_table($Query);
     //print_r($Query); exit;
  	 if(count($fetchDataInArray) > 0)
  	 {

  	 	 $Query ="UPDATE prd_gpf_request_master SET gpf_id_generation='".$pArguments['gpf_id_generation']."', pnrd_request='".$pArguments['pnrd_request']."', status=2, hitresponse='".$pArguments['sendstatus']."' WHERE emp_id_const='".$pArguments['emp_id_const']."'";
       //print($Query); exit;
  	 }
  	 else
  	 {

       $Query = "Insert INTO prd_gpf_request_master (emp_id_const, gpf_id_generation,pnrd_request,status,hitresponse) VALUES ('".$pArguments['emp_id_const']."','".$pArguments['gpf_id_generation']."','".$pArguments['pnrd_request']."',2,'".$pArguments['sendstatus']."')";
  	 }
  	 //print_r($Query); exit;
  	 $db->update($Query);
     $Query = "update prd_ngipf_request_cron SET send_status = 1 WHERE emp_id_fk='".$pArguments['emp_id_fk']."'";
     //print($Query); exit;
     $db->update($Query);
  }
 
 function gpf_id_generation($party_code)
	{
		
		
		$db = new database();
		//global $dynamnic_yr_mnth_sal;
		$get_request_seq=$db->fetch_table("SELECT nextval('gpf_id_generation') AS gpf_id_generation;");
		$cur_monyr=date('Ym');
		$sum=$get_request_seq[0]['gpf_id_generation'];
		
		
		$inc=str_pad($sum,11,'0',STR_PAD_LEFT);
	 $request_id=$cur_monyr.$party_code.$inc; 
		$unique_check=$db->fetch_table("select request_id_pk FROM prd_gpf_request_master WHERE request_id='".$request_id."'");
		
		if(!empty($unique_check))
		{
			
			$this->gpf_id_generation($party_code);
		}
		else
		{
			
			return $request_id;
		}
	}
 
 
 
 	function call_GPF($emp_id_const,$gpf_id_generation,$all_array_json,$status){
		
		//print_r($all_array_json); exit;
		 //$gpf_id_generation; 
		date_default_timezone_set('Asia/Kolkata');
		$current_millisec = mktime();
		$time_stamp = $current_millisec.'000';
		$src="iOSMS";
		
		$src_ifms="PRD003";
		
		 $hash_jeson = hash("sha256", $all_array_json); 
		
		
		
		$key = 'dGl4nfy0jGtbT23z'; //base64_decode("G0HPTE61KCQ+CYn3voqMlFnXEtpaow6gYDqaaGSVzuE=");
		$plaintext =$all_array_json;
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		//$iv = openssl_random_pseudo_bytes($ivlen);
		$iv = 'eT83Bhtd023jSkyg'; //'abcdefghijklmnop';
		$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
		//$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		//$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw ); 
		$hash_jeson_cs = base64_encode($ciphertext_raw); 
		

		
	
	$key88="req";
	$reqDtls = array("iosms_empId"=>$emp_id_const, "encData"=>$hash_jeson_cs, "cs"=>$hash_jeson,"src"=>$src_ifms,"action"=>$status);

	 $postdata_array= array($key88=> $reqDtls); 

	 $postdata= json_encode($postdata_array);
   // echo $postdata; exit;
    //print_r($postdata); 
	  $target_url = 'https://www.wbifms.gov.in/ngipf/employee-master/api/post/employee/'.$src.'/'.$gpf_id_generation.'/'.$time_stamp; 
	// $emp_id_const; die;
	  //echo $target_url; die;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $target_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	
	$result = curl_exec ($ch);
	//print_r($result); exit;
	
	
	if ($result === FALSE) {
		
		//echo 1222; die;
	echo "Error sending" . $fname .  " " . curl_error($ch);
	curl_close ($ch);
	}else{
	curl_close ($ch);
	
//echo 222; die;
	return $result;
	
	}
	
	
	
	
	}
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='--'?'':$dob;
}


	
function fun_code($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT * FROM prd_dise_code_master where code='".$val."';");
		return $dist_data2[0]['gpf_code_master'];
	}


function fun_dist($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT district_code, district_name, gpf_id FROM prd_location_master_district where district_id_pk='".$val."';");
		return $dist_data2[0]['gpf_id'];
	}
	
function getZpDesign($design_zp)
    {
        $db = new database();
        $code_data = $db->fetch_table("
                      SELECT gpf_code
                      FROM zpemp_emp_desig_master WHERE designation_id=".$design_zp.";");

        return $code_data[0]['gpf_code'];
    }  

?>