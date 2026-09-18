<?php
   require_once('api/ngipf.php');
   date_default_timezone_set('Asia/Kolkata');
   class NGIPF_API extends NGIPF
    {
        var $empLoyeeDetals = array();
        var $ngipfEmpDetails = array();
        var $ngipfEmpSendDetails = array();
        var $ModifyField = array();
        var $NGIPFURL = 'https://www.wbifms.gov.in';
        function __construct()
          {
             $this->ModifyField = array(
                                            'empNm',
                                            'gender',
                                            'religion',
                                            'dob',
                                            'doj',
                                            'gender',
                                            'retDt',
                                            'gpfCpf',
                                            'mobile',
                                            'email',
                                            'aadhaar',
                                            'pan',
                                            'maritalStatus',
                                            'oldGpfAccNo',
                                            'partyCode',
                                            'fatherNm',
                                            'motherNm',
                                            'religion',
                                            'maritalStatus',
                                       );

          }
        function sendToNgipfApi()
          {
             parent::sendToNGIPF();
          }  
        function SyncyNgiPFData($emp_id)
          {
             $this->empLoyeeDetals['emp_id_const'] = $emp_id;

             $this->GetNgipfEmployeeDetails();
             if(is_array($this->NgEmpDetails) && !empty($this->NgEmpDetails))
             {
                parent::syncData();
             }
          }  
    	function GetEmployeeDetails($emp_id_pk)
    	   {
               global $db;
               $Query = "select * from 
	                                 prd_employee_master as pem
	                                 LEFT JOIN prd_stake_epension_employee_profile as pseep ON pem.emp_id_pk=pseep.emp_id_fk
	                                 WHERE emp_id_pk ='".$emp_id_pk."' 
	                                 ORDER BY emp_id_pk ASC";

               $EmpDetails = $db->fetch_table($Query);
               //print_r($EmpDetails); exit;
               $this->empLoyeeDetals = $EmpDetails[0];
               
               return $this->empLoyeeDetals;

    	   }
    	function GetNgipfEmployeeDetails()
    	   {
    	   	 if(isset($this->empLoyeeDetals['emp_id_const']) && $this->empLoyeeDetals['emp_id_const'] != '')
    	   	   { 
    	   	   	 global $db; 
	    	   	 $emp_id_const= $this->empLoyeeDetals['emp_id_const'];
	             $Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$emp_id_const."' AND pfaccno!=''";
                 //print_r($Query); exit; 
                 $NgEmpDetails = $db->fetch_table($Query); 

                 $PnrdRequest = json_decode($NgEmpDetails[0]['pnrd_request'],true); 
                 $PnrdRequest = $PnrdRequest['req']['empDtls'];
                 //print_r($PnrdRequest); exit;
                 $this->ngipfEmpDetails = $PnrdRequest;
                 $this->empId = $NgEmpDetails[0]['emp_id_const'];
                 $this->pfaccno = $NgEmpDetails[0]['pfaccno'];
                 $this->NgEmpDetails = $NgEmpDetails[0];
               }
    	   }
         function getNameFromId($Code)
           {
             global $db;
             $Query ="SELECT * FROM prd_dise_code_master where code='".$Code."';";
             $diseData = $db->fetch_table($Query);
             return $diseData[0]['gpf_code_master'];
           }  
         function getDistrictNameFromId($Code)
           {
             global $db;
             $Query ="SELECT district_code, district_name, gpf_id FROM prd_location_master_district where district_id_pk='".$Code."';";
             $DistrictData = $db->fetch_table($Query);
             return $DistrictData[0]['gpf_id'];           
           }  
         function dateshow($dateval)
           {
                $date=substr($dateval,0,10);
                //return $date;
                $datearr=explode('-',$date);
                $dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
                $dob = trim($dob);
                //print("test:".$dob); exit;
                return $dob=='--'?'':$dob;            
           }
          function getEmployeeType()
            {
                global $db;
                $empTermination = '';
                $employee_dtls = $this->empLoyeeDetals;
                if($employee_dtls['emp_status'] != 1)
                    { 
                      $Query = "SELECT * from prd_stop_sal_reason_ngipfori WHERE emp_id_fk=".$employee_dtls['emp_id_pk'];
                      //print($Query); exit;
                      $TerminationReasonData = $db->fetch_table($Query);
                       
                      $TerminationReason = rtrim($TerminationReasonData[0]['reason']);
                     // print_r($TerminationReason); exit;
                      $this->TerminationDate = $this->dateshow($TerminationReasonData[0]['reason_date']);
                      switch($TerminationReason)
                        {
                             case '1992':  // Resignation R
                             {
                               $emp_status ="EMTER";
                               $empTermination = 'R';
                               break;
                              } 
                             case '1993': // Death
                               {
                                  $emp_status ="EMTER";
                                  $empTermination = 'D';
                                  break;
                               }
                             case '1991': // Retiring Pension
                               {
                                  $emp_status ="EMTER";
                                  $empTermination = 'RP';
                                  break;
                               }   
                             case '1990': // Resignation for new post in PRI
                               {
                                  $emp_status ="EMTER";
                                  $empTermination = 'O';
                                  break;                               
                                }
                        }
                        //print($emp_status); exit;
                    }
                else
                    {
                        $emp_status ="EME";
                    }
                if($employee_dtls['emp_status'] == 9)
                    {
                             $emp_status ="EMS";
                    } 
                
               $this->emp_status = $emp_status; 
               $this->empTermination = $empTermination; 
               // print($emp_status); exit; 
               return $emp_status;     

            }
         function getPartyCode()
            {
                $employee_dtls = $this->empLoyeeDetals;
                if($employee_dtls['gp_id_fk'] > 0)
                    $party_code = '006';
                elseif($employee_dtls['ps_id_fk'] > 0)
                    $party_code = '007';
                elseif($employee_dtls['zp_id_fk'] > 0)
                    $party_code = '008';
                $this->party_code = $party_code;
                return $party_code;
            } 
         function getOfficeDetails()
            {
                global $db;
                $employee_dtls = $this->empLoyeeDetals;
                if($employee_dtls['gp_id_fk'] > 0)
                {
                    $Query = "select pda.* from prd_dise_admin as pda
                              LEFT JOIN prd_location_master_block as lmb ON pda.block_code = lmb.block_code::varchar
                              LEFT JOIN prd_location_master_gp as lmg ON lmb.block_id_pk = lmg.block_id_fk
                              WHERE lmg.gp_id_pk ='".$employee_dtls['gp_id_fk']."'";
                      $OfficialDetails = $db->fetch_table($Query);
                      $OfficialDetails = $OfficialDetails[0];
                      $OfficialDetails['ddoIsActive'] = 'Y';
                      $OfficialDetails['opCodeLf'] = ' ';
                      $OfficialDetails['opCodePf'] = $OfficialDetails['operator_code_pf'];
                               
                }
                elseif($employee_dtls['ps_id_fk'] > 0)
                 {

                      $Query = "select * from psemp_ps_profile WHERE ps_id_fk =".$employee_dtls['ps_id_fk'];
                      $OfficialDetails = $db->fetch_table($Query);
                      $OfficialDetails = $OfficialDetails[0];
                      $OfficialDetails['ddoIsActive'] = 'N';
                      $OfficialDetails['opCodeLf'] = $OfficialDetails['ddo_code'];
                      unset( $OfficialDetails['ddo_code']);
                      $OfficialDetails['opCodePf'] = $OfficialDetails['pl_code_pf'];                    

                 }   //$party_code = '007';
                elseif($employee_dtls['zp_id_fk'] > 0)
                 {
                    // ZP office Query will come here..
                 }  
                             
               return  $OfficialDetails; 
            }             
         function newOldSet($oldData,$newData)
            {
                 
                 foreach($newData as $key=>$value)
                 {
                    if($value == "")
                     $oldData[$key] = "";
                 }
                 return $oldData;
            }         
         function GetEmployeeNgipfStructure()
           {
              $ifmsDtls = $this->getifmsDtls();
              $basicDtls = $this->getbasicDtls();
              $otherDtls = $this->getotherDtls();
              $relationDtls = $this->getrelationDtls();
              $spouseDtls = $this->getspouseDtls(); 
              $addrDtls = $this->getaddrDtls(); 
              $workDtls = $this->getworkDtls(); 
              $exitSerDtls = $this->getexitSerDtls();  
              $payInfoDtls = $this->getpayInfoDtls(); 
              $payAllowDtls = $this->getpayAllowDtls();
              $benfDtls = $this->getbenfDtls(); 
              $this->setexitSerDtls();  
              $this->CheckIsChangeFlag();
              //print_r($this->ngipfEmpSendDetails); exit;     
           }
         function CheckIsChangeFlag()
           {
              if($this->ngipfEmpSendDetails['ifmsDtls']['modifyFlag'] == 'C')
              {
                 $this->ngipfEmpSendDetails['otherDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['otherDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['relationDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['relationDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['spouseDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['spouseDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['addrDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['addrDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['workDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['workDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['exitSerDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['exitSerDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['exitSerDtls']['payInfoDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['exitSerDtls']['payInfoDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['exitSerDtls']['payAllowDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['exitSerDtls']['payAllowDtls']['oldDtls']);
                 $this->ngipfEmpSendDetails['exitSerDtls']['benfDtls']['oldDtls'] = $this->emptyoldDetails($this->ngipfEmpSendDetails['exitSerDtls']['benfDtls']['oldDtls']);
              }
           }
         function emptyoldDetails($oldDetails)
           {
               $mOldDetails = array();
               foreach($oldDetails as $key=>$value)
                 {
                    $mOldDetails[$key] = ""; 
                 }
                return $mOldDetails;
           }    
         function getifmsDtls()
           {
              $this->ngipfEmpSendDetails['ifmsDtls'] = array(
                                                               'empId'=>$this->empId,
                                                               'pfAccNo'=>$this->pfaccno,
                                                               'modifyFlag'=> 'C'
                                                            );

           }  
         function setexitSerDtls()
           {
            $this->ngipfEmpSendDetails['exitSerDtls']['payInfoDtls'] =  $this->ngipfEmpSendDetails['payInfoDtls'];
            $this->ngipfEmpSendDetails['exitSerDtls']['payAllowDtls'] =  $this->ngipfEmpSendDetails['payAllowDtls'];
            $this->ngipfEmpSendDetails['exitSerDtls']['benfDtls'] =  $this->ngipfEmpSendDetails['benfDtls'];
            $this->ngipfEmpSendDetails['cDateTime'] = date('Y-m-d h:i');
            unset($this->ngipfEmpSendDetails['payInfoDtls']);
            unset($this->ngipfEmpSendDetails['payAllowDtls']);
            unset($this->ngipfEmpSendDetails['benfDtls']);
           }  
         function getbenfDtls()
          {
            $employee_dtls = $this->empLoyeeDetals;
            $benfDtls = array(
                                  "ifsc"=>$employee_dtls['emp_ifsc_no'], 
                                  "accNo"=>$employee_dtls['emp_acc_no'],
                                  "accNoIsActive"=>'Y',
                                );
            $this->sendbenfDtls($benfDtls);

          } 
         function sendbenfDtls($curData)
          {
            $previousJsonData = $this->ngipfEmpDetails['benfDtls'];
            if($previousJsonData['ifsc'] != $curData['ifsc'] || $previousJsonData['accNo'] != $curData['accNo'])
                {
                  $temp['ifsc'] = $curData['ifsc'];
                  $temp['accNo'] = $curData['accNo'];
                  $temp['accNoIsActive'] = $curData['accNoIsActive'];
                  
              
                 
                }
                else
                {
                  $temp['ifsc'] = "";
                  $temp['accNo'] = "";
                  $temp['accNoIsActive'] = "";
                                    
                }   
              unset($previousJsonData['benfWef']); 
              $temp = $this->bypassChangeDataIfModifiedFound($temp); 
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['benfDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                            );  


          }  
         function getpayAllowDtls()
           {
                 $curr_date = date("d/m/Y"); 
                 $employee_dtls = $this->empLoyeeDetals;
                 $payAllowDtls = array(
                                             "basicPay"=>$employee_dtls['emp_pay_in_payband'], 
                                             "basicPayWef"=>$curr_date,
                                             'basicPayIsActive'=>'Y', 
                                             "gradePay"=>$employee_dtls['emp_grade_pay'], 
                                             "gradePayWef"=>$curr_date, 
                                             'gradePayIsActive'=>'Y'
                                           );   
                 $this->sendpayAllowDtls($payAllowDtls);                                      
           }  
         function sendpayAllowDtls($curData)
           {
               //print_r($curData); exit;
               $previousJsonData = $this->ngipfEmpDetails['payAllowDtls'][0];
                if($previousJsonData['basicPay'] != $curData['basicPay'] || $previousJsonData['basicPayIsActive'] != $curData['basicPayIsActive'])
                                {
                                  $temp['basicPay'] = $curData['basicPay'];
                                  $temp['basicPayWef'] = $curData['basicPayWef'];
                                  $temp['basicPayIsActive'] = $curData['basicPayIsActive'];
                                  
                              
                                 
                                }
                                else
                                {
                                  $temp['basicPay'] = "";
                                  $temp['basicPayWef'] = "";
                                  $temp['basicPayIsActive'] = "";
                                                    
                                }  
                if($previousJsonData['gradePay'] != $curData['gradePay'] || $previousJsonData['gradePayIsActive'] != $curData['gradePayIsActive'])
                {

                  $temp['gradePay'] = $curData['gradePay'];
                  $temp['gradePayWef'] = $curData['gradePayWef'];
                  $temp['gradePayIsActive'] = $curData['gradePayIsActive'];
                  
              
                  
                }
                else
                {
                  $temp['gradePay'] = "";
                  $temp['gradePayWef'] = "";
                  $temp['gradePayIsActive'] = "";
                                    
                } 
              $temp = $this->bypassChangeDataIfModifiedFound($temp);      
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['payAllowDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );                    

           }  
         function getpayInfoDtls()
           {
                $curr_date = date("d/m/Y"); 
                $employee_dtls = $this->empLoyeeDetals;            
                if($employee_dtls['ropa_status'] == 0){ $ropa_status ="WGSROPA09";}
                else if($employee_dtls['ropa_status'] == 1){ $ropa_status ="WGSROPA19";}
                $payInfoDtls = array(
                                           "ropa"=>$ropa_status, 
                                           "ropaWef"=>$curr_date,
                                           "ropaIsActive"=>1
                                     );
                $this->sendpayInfoDtls($payInfoDtls);

           } 
          function  sendpayInfoDtls($curData)
           {
              //print_r($this->ngipfEmpDetails['payInfoDtls']); exit;
              $previousJsonData = $this->ngipfEmpDetails['payInfoDtls'][0];

              if($previousJsonData['ropa'] != $curData['ropa'] || $previousJsonData['ropaIsActive'] != $curData['ropaIsActive'])
                {
                  //echo $previousJsonData['ropa'] .'!='. $curData['ropa']; exit;  
                  $temp['ropa'] = $curData['ropa'];
                  $temp['ropaWef'] = $curData['ropaWef'];
                  $temp['ropaIsActive'] = $curData['ropaIsActive'];
                }
                else
                {
                  $temp['ropa'] = "";
                  $temp['ropaWef'] = "";
                  $temp['ropaIsActive'] = "";
                                    
                }   
              $temp = $this->bypassChangeDataIfModifiedFound($temp);    
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['payInfoDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );                             
           }
         function getexitSerDtls()
           {
                 if($this->emp_status != 'EME')
                   {
                      $exitSerDtls = array(
                                               "terType"=>$this->empTermination, 
                                               "terDt"=>$this->TerminationDate
                                           );            
                   }   
                  else
                   {
                      $exitSerDtls = array(
                                               "terType"=>"", 
                                               "terDt"=>""
                                           );            

                   } 
               $this->sendexitSerDtls($exitSerDtls);    
              //    ` print_r($exitSerDtls); exit;    
           } 
         function sendexitSerDtls($curData)
           {
               
               $previousJsonData = $this->ngipfEmpDetails['exitSerDtls'];
             
               if($previousJsonData['terType'] != $curData['terType'] || $previousJsonData['terDt'] != $curData['terDt'])
                {
                    $temp['terType'] = $curData['terType'];
                    $temp['terDt'] = $curData['terDt'];
                }
                else
                {
                    $temp['terType'] = "";
                    $temp['terDt'] = "";
                } 
              $temp = $this->bypassChangeDataIfModifiedFound($temp);  
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['exitSerDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );                                
           }   
         function getworkDtls()
           {
              $employee_dtls = $this->empLoyeeDetals;  
              $desig = $this->getNameFromId($employee_dtls['emp_desig']);
              $OfficialDetails = $this->getOfficeDetails();

              $emp_join_prsnt_post_date = $this->dateshow($employee_dtls['emp_join_prsnt_post_date']);
              $emp_present_memo_date = ($employee_dtls['emp_present_memo_date'] != '')?$this->dateshow($employee_dtls['emp_present_memo_date']):$this->dateshow($employee_dtls['presnt_memo_wef_date']);

              $curr_date = date("d/m/Y"); 
              $workDtls = array(
                                       "ddoCode"=>$OfficialDetails['ddo_code'], 
                                       "ddoWef"=>($OfficialDetails['ddo_code'] != '')?$curr_date:'', 
                                       "ddoIsActive"=>($OfficialDetails['ddo_code'] != '')?$OfficialDetails['ddoIsActive']:'',
                                       "opCodeLf"=>$OfficialDetails['opCodeLf'],
                                       "opCodeLfWef"=>($OfficialDetails['opCodeLf'] != '')?$curr_date:'',
                                       "opCodeLfIsActive"=>($OfficialDetails['opCodeLf'] != '')?"Y":'', 
                                       "opCodeLfTresCode"=>$OfficialDetails['treasury_code'],
                                       "opCodePf"=>$OfficialDetails['opCodePf'],
                                       "opCodePfWef"=>($OfficialDetails['opCodePf'] != '')?$curr_date:'',
                                       "opCodePfIsActive"=>($OfficialDetails['opCodePf'] != '')?"Y":'', 
                                       "opCodePfTresCode"=>$OfficialDetails['treasury_code'], 
                                       "hooCode"=>$OfficialDetails['hoo_code'], 
                                       "hooCodeWef"=>($OfficialDetails['hoo_code'] != '')?$curr_date:'', 
                                       "hooCodeIsActive"=>($OfficialDetails['hoo_code'] != '')?'Y':'', 
                                       "sancAuthCode"=>$OfficialDetails['hoo_code'], // Mandatory Field
                                       "sancAuthWef"=>$curr_date, 
                                       "sancAuthIsActive"=>($OfficialDetails['hoo_code']!='')?"Y":'N', 
                                       "recAuthCode"=>"", 
                                       "recAuthCodeWef"=>"", 
                                       "recAuthCodeIsActive"=>"", 
                                       "sectionCode"=>"", 
                                       "sectionCodeWef"=>"", 
                                       "sectionCodeIsActive"=>"", 
                                       "desig"=>$desig, 
                                       "desigWef"=>$emp_join_prsnt_post_date, 
                                       "desigIsActive"=>"Y", 
                                       "apptAppNo"=>($employee_dtls['emp_present_memo_no'] =='')?$employee_dtls['present_memo_no']:$employee_dtls['emp_present_memo_no'], 
                                       "apptAppDt"=>$emp_present_memo_date, 
                                       "apptWefDt"=>$emp_join_prsnt_post_date, 
                                       "apptAppIsActive"=>"Y"
                                ); 
               $this->sendworkDtls($workDtls);                  
                                           
           }  
         function sendworkDtls($curData)
           {

              $previousJsonData = $this->ngipfEmpDetails['workDtls'][0];
               if($previousJsonData['ddoCode'] != $curData['ddoCode'] || $previousJsonData['ddoIsActive'] != $curData['ddoIsActive'])
                {
                    $temp['ddoCode'] = $curData['ddoCode'];
                    $temp['ddoWef'] = $curData['ddoWef'];
                    $temp['ddoIsActive'] = $curData['ddoIsActive'];
                
                    
                }
                else
                {
                    $temp['ddoCode'] = "";
                    $temp['ddoWef'] = "";
                    $temp['ddoIsActive'] = "";                  
                }


                if($previousJsonData['opCodeLf'] != $curData['opCodeLf'] || $previousJsonData['opCodeLfIsActive'] != $curData['opCodeLfIsActive'] || $previousJsonData['opCodeLfTresCode'] != $curData['opCodeLfTresCode'])
                {
                    $temp['opCodeLf'] = $curData['opCodeLf'];
                    $temp['opCodeLfWef'] = $curData['opCodeLfWef'];
                    $temp['opCodeLfIsActive'] = $curData['opCodeLfIsActive'];
                    $temp['opCodeLfTresCode'] = $curData['opCodeLfTresCode'];
                
                    //print_r($temp); exit;
                }
                else
                {
                    $temp['opCodeLf'] = "";
                    $temp['opCodeLfWef'] = "";
                    $temp['opCodeLfIsActive'] = "";
                    $temp['opCodeLfTresCode'] = "";                     
                } 
                if($previousJsonData['opCodePf'] != $curData['opCodePf'] || $previousJsonData['opCodePfIsActive'] != $curData['opCodePfIsActive'] || $previousJsonData['opCodePfTresCode'] != $curData['opCodePfTresCode'])
                {
                    $temp['opCodePf'] = $curData['opCodePf'];
                    $temp['opCodePfWef'] = $curData['opCodePfWef'];
                    $temp['opCodePfIsActive'] = $curData['opCodePfIsActive'];
                    $temp['opCodePfTresCode'] = $curData['opCodePfTresCode'];
                
                    //print_r($temp); exit;
                }
                else
                {
                    $temp['opCodePf'] = "";
                    $temp['opCodePfWef'] = "";
                    $temp['opCodePfIsActive'] = "";
                    $temp['opCodePfTresCode'] = "";                     
                }  
                if($previousJsonData['hooCode'] != $curData['hooCode'] || $previousJsonData['hooCodeIsActive'] != $curData['hooCodeIsActive'])
                {
                    $temp['hooCode'] = $curData['hooCode'];
                    $temp['hooCodeWef'] = $curData['hooCodeWef'];
                    $temp['hooCodeIsActive'] = $curData['hooCodeIsActive'];
                    
                
                    //print_r($temp); exit;
                }
                else
                {
                    $temp['hooCode'] = "";
                    $temp['hooCodeWef'] = "";
                    $temp['hooCodeIsActive'] = "";
                                        
                }  
                if($previousJsonData['sancAuthCode'] != $curData['sancAuthCode'] || $previousJsonData['sancAuthIsActive'] != $curData['sancAuthIsActive'])
                {
                    $temp['sancAuthCode'] = $curData['sancAuthCode'];
                    $temp['sancAuthWef'] = $curData['sancAuthWef'];
                    $temp['sancAuthIsActive'] = $curData['sancAuthIsActive'];
                }
                else
                {
                    $temp['sancAuthCode'] = "";
                    $temp['sancAuthWef'] = "";
                    $temp['sancAuthIsActive'] = "";
                                        
                } 
                if($previousJsonData['sectionCode'] != $curData['sectionCode'] || $previousJsonData['sectionCodeWef'] != $curData['sectionCodeWef'] || $previousJsonData['sectionCodeIsActive'] != $curData['sectionCodeIsActive'])
                {
                  $temp['sectionCode'] = $curData['sectionCode'];
                  $temp['sectionCodeWef'] = $curData['sectionCodeWef'];
                  $temp['sectionCodeIsActive'] = $curData['sectionCodeIsActive'];
                  
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['sectionCode'] = "";
                  $temp['sectionCodeWef'] = "";
                  $temp['sectionCodeIsActive'] = "";
                                    
                } 

                if($previousJsonData['recAuthCode'] != $curData['recAuthCode'] || $previousJsonData['recAuthCodeWef'] != $curData['recAuthCodeWef'] || $previousJsonData['recAuthCodeIsActive'] != $curData['recAuthCodeIsActive'])
                {
                  $temp['recAuthCode'] = $curData['recAuthCode'];
                  $temp['recAuthCodeWef'] = $curData['recAuthCodeWef'];
                  $temp['recAuthCodeIsActive'] = $curData['recAuthCodeIsActive'];
                  
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['recAuthCode'] = "";
                  $temp['recAuthCodeWef'] = "";
                  $temp['recAuthCodeIsActive'] = "";
                                    
                }   
                               
                if($previousJsonData['desig'] != $curData['desig'] || $previousJsonData['desigIsActive'] != $curData['desigIsActive'])
                {
                    $temp['desig'] = $curData['desig'];
                    $temp['desigWef'] = $curData['hooCodeWef'];
                    $temp['desigIsActive'] = $curData['desigIsActive'];
                    
                
                    //print_r($temp); exit;
                }
                else
                {
                    $temp['desig'] = "";
                    $temp['desigWef'] = "";
                    $temp['desigIsActive'] = "";
                                        
                }                                                                                    
                 if($previousJsonData['apptAppNo'] != $curData['apptAppNo'] || $previousJsonData['apptAppDt'] != $curData['apptAppDt'] || $previousJsonData['apptWefDt'] != $curData['apptWefDt'] || $previousJsonData['apptAppIsActive'] != $curData['apptAppIsActive'])
                {
                  $temp['apptAppNo'] = $curData['apptAppNo'];
                  $temp['apptAppDt'] = $curData['apptAppDt'];
                  $temp['apptWefDt'] = $curData['apptWefDt'];
                  $temp['apptAppIsActive'] = $curData['apptAppIsActive'];
                  
              
                  //print_r($temp); exit;
                }
                else
                {
                  $temp['apptAppNo'] = "";
                  $temp['apptAppDt'] = "";
                  $temp['apptWefDt'] = "";
                  $temp['apptAppIsActive'] = "";
                                    
                } 
              $temp = $this->bypassChangeDataIfModifiedFound($temp);     
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              
              $this->ngipfEmpSendDetails['workDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );  
              //print_r($this->ngipfEmpSendDetails['workDtls']); exit;                                                                                   

           }  
         function getaddrDtls()
           {
               $employee_dtls = $this->empLoyeeDetals;
               $district = $this->getDistrictNameFromId($employee_dtls['emp_pre_dist']);
               $districtperm = $this->getDistrictNameFromId($employee_dtls['emp_per_dist']);
               $curr_date = date("d/m/Y"); 
               $Address = $employee_dtls['emp_pre_street_no'].$employee_dtls['emp_pre_vill']; 

               $addressCount = strlen($Address);
               $presStreet = '';
               if($addressCount > 100)
               {

                  $presStreet = substr($Address,0,100);
                  $Address = substr($Address,100);

               }

               $AddressPerm = $employee_dtls['emp_per_street_no'].$employee_dtls['emp_per_vill']; 

               $addressCount = strlen($AddressPerm);
               $permStreet = '';
               if($addressCount > 100)
               {

                  $permStreet = substr($AddressPerm,0,100);
                  $AddressPerm = substr($AddressPerm,100);

               }

               $addrDtls = array(
                                      "presStreet"=>($presStreet!='')?$presStreet:'NA', 
                                      "presCity"=>$Address, 
                                      "presDist"=>$district ,
                                      "presState"=>'10',
                                      "presPin"=>$employee_dtls['emp_pre_pin'], 
                                      "presAdrIsActive"=>'Y',
                                      "presAdrWef"=>$curr_date, 
                                      "permStreet"=>($permStreet!='')?$permStreet:'NA', 
                                      "permCity"=>$AddressPerm,
                                      "permDist"=>$districtperm,
                                      "permState"=>'10',
                                      "permPin"=>$employee_dtls['emp_per_pin'],
                                      'permAdrIsActive'=>'Y',
                                      'permAdrWef'=>$curr_date
                                 );  
               // print_r($addrDtls); exit;
                $this->sendaddrDtls($addrDtls);                              

           }  
         function sendaddrDtls($curData) 
           {
             // print_r($this->ngipfEmpDetails['addrDtls']); exit;
              $previousJsonData = $this->ngipfEmpDetails['addrDtls'];
              
                if($previousJsonData['presStreet'] != $curData['presStreet'] || $previousJsonData['presCity'] != $curData['presCity'] || $previousJsonData['presDist'] != $curData['presDist'] || $previousJsonData['presState'] != $curData['presState'] || $previousJsonData['presPin'] != $curData['presPin'] || $previousJsonData['presAdrIsActive'] != $curData['presAdrIsActive'])
                {
                    $temp['presStreet'] = $curData['presStreet'];
                    $temp['presCity'] = $curData['presCity'];
                    $temp['presDist'] = $curData['presDist'];
                    $temp['presState'] = $curData['presState'];
                    $temp['presPin'] = $curData['presPin'];
                    $temp['presAdrIsActive'] = $curData['presAdrIsActive']; 
                    $temp['presAdrWef'] = $curData['presAdrWef'];                 
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
                if($previousJsonData['permStreet'] != $curData['permStreet'] || $previousJsonData['permCity'] != $curData['permCity'] || $previousJsonData['permDist'] != $curData['permDist'] || $previousJsonData['permState'] != $curData['permState'] || $previousJsonData['permPin'] != $curData['permPin'] || $previousJsonData['permAdrIsActive'] != $curData['permAdrIsActive'])
                {
                  $temp['permStreet'] = $curData['permStreet'];
                  $temp['permCity'] = $curData['permCity'];
                  $temp['permDist'] = $curData['permDist'];
                  $temp['permState'] = $curData['permState'];
                  $temp['permPin'] = $curData['permPin'];
                  $temp['permAdrIsActive'] = $curData['permAdrIsActive']; 
                  $temp['permAdrWef'] = $curData['permAdrWef'];                 
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
              $temp = $this->bypassChangeDataIfModifiedFound($temp);     
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['addrDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );    


           }
         function getspouseDtls()
          {
                $employee_dtls = $this->empLoyeeDetals;
                if($employee_dtls['emp_marital_status']=='241')
                {
                    $spouse_name=$employee_dtls['emp_spouse_name'];
                    $spouse_status='Y';
                }
                else
                {
                    $spouse_name=NULL;
                    $spouse_status='N';
                } 
                $curr_date = date("d/m/Y");                
                $spouseDtls = array(
                                      "spouseNm"=>$spouse_name, 
                                      "spouseNmIsActive"=>$spouse_status, 
                                      "spouseNmWef"=>$curr_date
                                     ); 
                $this->sendspouseDtls($spouseDtls);              
          }  
         function sendspouseDtls($curData) 
          {
              //print_r($this->ngipfEmpDetails['spouseDtls']); exit;
              $previousJsonData = $this->ngipfEmpDetails['spouseDtls'][0];
              $temp = array();
              $curr_date = date("d/m/Y");
              if($previousJsonData['spouseNm'] != $curData['spouseNm'] || $previousJsonData['spouseNmIsActive'] != $curData['spouseNmIsActive']) 
              {
                   $temp['spouseNm'] = $curData['spouseNm']; 
                   $temp['spouseNmIsActive'] = $curData['spouseNmIsActive']; 
                   $temp['spouseNmWef'] = $curr_date; 
              } 
              else
               {
                   $temp['spouseNm'] = ""; 
                   $temp['spouseNmIsActive'] = "";                  
                   $temp['spouseNmWef'] = ''; 
               } 
              $temp = $this->bypassChangeDataIfModifiedFound($temp);   
              $oldData = $this->newOldSet($previousJsonData,$temp);  
              $this->ngipfEmpSendDetails['spouseDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$temp
                                                              );              
          } 
         function getrelationDtls()
           {
                $employee_dtls = $this->empLoyeeDetals;
                $relationDtls = array(
                                         "fatherNm"=>$employee_dtls['emp_father_name'], 
                                         "motherNm"=>$employee_dtls['emp_mother_name']
                                     ); 
                $this->sendrelationDtls($relationDtls);                                

           }  
         function sendrelationDtls($relationDtls)
           {
              $ngipfEmpSendDetails = array();
              foreach($this->ngipfEmpDetails['relationDtls'] as $key=>$value)
                {
                   if($relationDtls[$key] != $value)        
                     $ngipfEmpSendDetails[$key] = $relationDtls[$key];
                   else
                     $ngipfEmpSendDetails[$key] = "";
                } 
              $ngipfEmpSendDetails = $this->bypassChangeDataIfModifiedFound($ngipfEmpSendDetails);   
              $oldData = $this->newOldSet($this->ngipfEmpDetails['relationDtls'],$ngipfEmpSendDetails);  
              $this->ngipfEmpSendDetails['relationDtls'] = array(
                                                                    'oldDtls'=>$oldData,
                                                                    'newDtls'=>$ngipfEmpSendDetails
                                                                );            
           }  
         function getotherDtls()
           {

                $employee_dtls = $this->empLoyeeDetals;
                $empType = $this->getEmployeeType();
                $maritalStatus = $this->getNameFromId($employee_dtls['emp_marital_status']); 
                $emp_termination_date = $this->TerminationDate;
                if($employee_dtls['emp_marital_status']=='241')
                {
                    $spouse_name=$employee_dtls['emp_spouse_name'];
                    $spouse_status='Y';
                }
                else
                {
                    $spouse_name=NULL;
                    $spouse_status='N';
                }   
                $curr_date = date("d/m/Y");  
                $party_code = $this->getPartyCode();           
                $otherDtls = array(
                                       "empType"=>$empType,
                                       "empTypeWef"=>($empType == 'EME')?$curr_date:$emp_termination_date, 
                                       "mobile"=>$employee_dtls['emp_mobile_no'], 
                                       "email"=>$employee_dtls['emp_mail_id'],
                                       "aadhaar"=>$employee_dtls['emp_aadhar_no'],
                                       "pan"=>$employee_dtls['emp_pan_no'],
                                       "maritalStatus"=>$maritalStatus,
                                       "maritalStatusIsActive"=>$spouse_status,
                                       "maritalStatusWef"=>$curr_date,
                                       "oldGpfAccNo"=>"",
                                       // Added for Modify Purpose 
                                       "cpfRefDt"=>"",
                                       "cpfRefTresNm"=>"",
                                       "cpfRefAmt"=>"",
                                       "cpfRefIsActive"=>'N',
                                       "cpfRefWef"=>"",
                                       "partyCode"=>$party_code,
                                  ); 
                 //print_r($otherDtls); exit;
                 $this->sendotherDtls($otherDtls);
                 return $otherDtls;
           }       
         function sendotherDtls($otherDtls)
           {
              $ngipfEmpSendDetails = array();
              $continueStatus = 0;
              foreach($this->ngipfEmpDetails['otherDtls'] as $key=>$value)
                {
                   if(($key == 'maritalStatus' || $continueStatus ==1) && $key != 'partyCode')
                     { 
                        $continueStatus = 1;
                        continue; 
                     }

                   if($otherDtls[$key] != $value)        
                     $ngipfEmpSendDetails[$key] = $otherDtls[$key];
                   else
                     $ngipfEmpSendDetails[$key] = "";
                } 
              //$ngipfEmpSendDetails['empTypeWef'] = $otherDtls['empTypeWef'];  
              //print_r($this->ngipfEmpDetails['otherDtls']); exit; 
              $ngipfEmpSendDetails['oldGpfAccNo'] = $otherDtls['oldGpfAccNo'];  
              //if($this->ngipfEmpDetails['otherDtls']['empType'] != $otherDtls['empType'] || !isset($this->ngipfEmpDetails['otherDtls']['empTypeWef']) || $this->ngipfEmpDetails['otherDtls']['empTypeWef'] != $otherDtls['empTypeWef'])
              if($this->ngipfEmpDetails['otherDtls']['empType'] != $otherDtls['empType'] )
                {
                    //print("I");
                    $ngipfEmpSendDetails['empType'] = $otherDtls['empType'];
                    $ngipfEmpSendDetails['empTypeWef'] = $otherDtls['empTypeWef'];
                }
               else
                {
                    //print("D");
                    $ngipfEmpSendDetails['empType'] = "";
                    $ngipfEmpSendDetails['empTypeWef'] = "";
                }  
               // print_r($ngipfEmpSendDetails); exit;  
               // exit;           
              if($this->ngipfEmpDetails['otherDtls']['maritalStatus'] != $otherDtls['maritalStatus'] || $this->ngipfEmpDetails['otherDtls']['maritalStatusIsActive'] != $otherDtls['maritalStatusIsActive'])
                {
                    //print("I");
                    $ngipfEmpSendDetails['maritalStatus'] = $otherDtls['maritalStatus'];
                    $ngipfEmpSendDetails['maritalStatusIsActive'] = $otherDtls['maritalStatusIsActive'];
                    $ngipfEmpSendDetails['maritalStatusWef'] = $otherDtls['maritalStatusWef'];
                }
               else
                {
                    //print("D");
                    $ngipfEmpSendDetails['maritalStatus'] = "";
                    $ngipfEmpSendDetails['maritalStatusIsActive'] = "";
                    $ngipfEmpSendDetails['maritalStatusWef'] = "";

                }  
                //print_r($ngipfEmpSendDetails); exit;
              if($this->ngipfEmpDetails['otherDtls']['cpfRefIsActive'] != $otherDtls['cpfRefIsActive'])
                {
                    $ngipfEmpSendDetails['cpfRefDt'] = "";                    
                    $ngipfEmpSendDetails['cpfRefTresNm'] = "";
                    $ngipfEmpSendDetails['cpfRefAmt'] = "";                      
                    $ngipfEmpSendDetails['cpfRefIsActive'] = $otherDtls['cpfRefIsActive'];
                    $ngipfEmpSendDetails['cpfRefWef'] = $otherDtls['cpfRefWef'];
                }
               else
                {
                    $ngipfEmpSendDetails['cpfRefDt'] = "";                    
                    $ngipfEmpSendDetails['cpfRefTresNm'] = "";
                    $ngipfEmpSendDetails['cpfRefAmt'] = "";                    
                    $ngipfEmpSendDetails['cpfRefIsActive'] = "";
                    $ngipfEmpSendDetails['cpfRefWef'] = "";

                }  
              $ngipfEmpSendDetails = $this->bypassChangeDataIfModifiedFound($ngipfEmpSendDetails);   
              //print_r($ngipfEmpSendDetails); exit;
              $oldData = $this->newOldSet($this->ngipfEmpDetails['otherDtls'],$ngipfEmpSendDetails);
               
              $this->ngipfEmpSendDetails['otherDtls'] = array(
                                                                'oldDtls'=>$oldData,
                                                                'newDtls'=>$ngipfEmpSendDetails
                                                              );
              //print_r($this->ngipfEmpSendDetails['otherDtls']); exit; 
           }  
         function getbasicDtls()
           {
                //print_r($this->ModifyField); exit;
                $employee_dtls = $this->empLoyeeDetals;
                //print_r($employee_dtls); exit;
                $employee_dtls['emp_second_name'] = (rtrim($employee_dtls['emp_second_name']) != '')?$employee_dtls['emp_second_name'].' ':'';
                $emp_name = rtrim($employee_dtls['emp_first_name']).' '.rtrim($employee_dtls['emp_second_name']).rtrim($employee_dtls['emp_last_name']);
                $emp_id_const = $employee_dtls['emp_id_const'];
                $emp_dob = $this->dateshow($employee_dtls['emp_dob']); 
                $desig = $this->getNameFromId($employee_dtls['emp_desig']);
                $emp_religion = $this->getNameFromId($employee_dtls['emp_religion']);
                $maritalStatus = $this->getNameFromId($employee_dtls['emp_marital_status']);
                $district = $this->getDistrictNameFromId($employee_dtls['emp_pre_dist']);                 
                $gender = $this->getNameFromId($employee_dtls['emp_sex']);
                $emp_first_join_date = $this->dateshow($employee_dtls['emp_first_join_date']);
                $emp_termination_date = $this->dateshow($employee_dtls['emp_termination_date']);
                $empStatus = ($employee_dtls['emp_status'] == 1)?'Y':'N';
                $gpfCpf = ($employee_dtls['emp_status_deputation'] == 1)?'CPF':'GPF';
                $basicDtls = array(
                                     "empNm"=>$emp_name, 
                                     "empId"=>$emp_id_const, 
                                     "gender"=>$gender,
                                     "religion"=>$emp_religion,
                                     "dob"=>$emp_dob,
                                     "doj"=>$emp_first_join_date,
                                     "isActive"=>$empStatus,
                                     "retDt"=>$emp_termination_date,
                                     "gpfCpf"=>$gpfCpf
                                  );

                $this->sendbasicDtls($basicDtls);
                //print_r($basicDtls); exit;
                return  $basicDtls;                                
           }
         function sendbasicDtls($basicDtls)    
           {
              $ngipfEmpSendDetails = array();
              //print_r($basicDtls['dob']); exit;
             //print($this->ngipfEmpDetails['basicDtls']['dob'].'='.$basicDtls['dob']); 
              foreach($this->ngipfEmpDetails['basicDtls'] as $key=>$value)
                {
                   if($basicDtls[$key] != $value)        
                     $ngipfEmpSendDetails[$key] = $basicDtls[$key];
                   else
                     $ngipfEmpSendDetails[$key] = "";
                } 
              $ngipfEmpSendDetails = $this->bypassChangeDataIfModifiedFound($ngipfEmpSendDetails);  
              $this->ngipfEmpSendDetails['basicDtls'] = $ngipfEmpSendDetails;  

              //print_r($ngipfEmpSendDetails); exit;  
           }
        function bypassChangeDataIfModifiedFound($ChangeData)
           {
              foreach($ChangeData as $key=>$value)
                 {
                       if(in_array($key,$this->ModifyField) && $value != '' && $this->ngipfEmpSendDetails['ifmsDtls']['modifyFlag'] == 'C')
                       {
                            $this->ngipfEmpSendDetails['ifmsDtls']['modifyFlag'] = 'M';
                       }

                       if($this->ngipfEmpSendDetails['ifmsDtls']['modifyFlag'] == 'M' && !in_array($key,$this->ModifyField))
                         {
                            $ChangeData[$key] = '';
                         }
                 }
              return  $ChangeData; 
           }   
    }
?>