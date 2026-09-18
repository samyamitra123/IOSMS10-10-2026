<?php 

  class employeeChange {
      
     var $taskData = array(); 
     function __Construct()
       {
            
            $this->userInfo = $_SESSION['user_info'];
            $this->location = $_SESSION['location'];
            if(isset($_POST) && count($_POST) > 0 )
            {
              $this->taskData = $_POST;
              $action = $this->taskData['action'];
              $this->$action();
            }
       }
    function workflowapprove()
       {
           global $db,$cryptoGraph;
        //print_r($this->taskData); exit;   
        $employeeId = $this->taskData['empId'];   
        $Where = array();
        if($this->userInfo['stake_abbr'] == 'BDO')
          {
              $Where[] = " pec.block_code='".$this->location['block_code']."'";
          } 
        if($this->taskData['empId'] != '')
         {
            $Where[] = " pec.ech_id ='".$employeeId."'";
         } 
         //print_r($Where); exit;
        if(count($Where) > 0)
          {  
            $Where = ' WHERE '.implode(' AND ', $Where);
            $Query = "SELECT * from prd_employee_change as pec 
                      LEFT JOIN prd_employee_master as pem ON  pem.emp_id_const = pec.emp_id_const ".$Where;
            //echo $Query; exit;
            $EmployeeData = $db->fetch_table($Query);
            $EmployeeData = $EmployeeData[0];
            if($this->userInfo['stake_abbr'] == 'FC&CAO')
              $this->forwardApproval();
            else
              $this->updateApproval();
            ($this->taskData['status'] == 1)?$this->updateBasic($EmployeeData):'';

           echo ($this->taskData['status'] == 1)? "Employee Request Approved Successfully":'Employee Request Rejected';

          }
       }
    function forwardApproval()
      {
            global $db;
            $Where = array();
            $server = $_SERVER;
            //$post['verified_ip'] = $server['REMOTE_ADDR']; 
            $Where[] = "forward_ip='".$server['REMOTE_ADDR']."'";           
            //if($this->taskData['status'] != 0)
            $Where[] = "ddo_active=1";              
            
            $updateQuery = "UPDATE prd_employee_change SET ".implode(', ',$Where)." WHERE ech_id=".$this->taskData['empId']; 
            $db->update($updateQuery); 
           // print_r($updateQuery); exit;        
      }        
    function updateBasic($EmployeeData)
       {
            global $db;
            $Where = array();
            if($EmployeeData['basic'] != '')
              $Where[] = "emp_pay_in_payband='".$EmployeeData['basic']."'";
             $updateQuery = "UPDATE prd_employee_master SET ".implode(', ',$Where)." WHERE emp_id_const='".$EmployeeData['emp_id_const']."'";
             $db->update($updateQuery);
             //echo  $updateQuery; 
       }
    function updateApproval()
      {
            global $db;
            $Where = array();
            $server = $_SERVER;
            //$post['verified_ip'] = $server['REMOTE_ADDR']; 
            $Where[] = "verified_ip='".$server['REMOTE_ADDR']."'";           
            if($this->taskData['submittedby'] != '')
              $Where[] = "verifiedby='".$this->taskData['submittedby']."'";
          
              $Where[] = "verified_on='".date('Y-m-d')."'";
            if($this->taskData['submittedbydesig'] != '')
              $Where[] = "verified_desig='".$this->taskData['submittedbydesig']."'";
            if($this->taskData['status'] != 0)
              $Where[] = "status='".$this->taskData['status']."'";              
            
            $updateQuery = "UPDATE prd_employee_change SET ".implode(', ',$Where)." WHERE ech_id=".$this->taskData['empId']; 
            $db->update($updateQuery); 
           // print_r($updateQuery); exit;        
      }      
    function viewemployee()
      {
        global $db,$cryptoGraph;
        //print_r($_SESSION); exit;
       // $this->checkIfAlreadySubmitted();
        $employeeId = $cryptoGraph->decode($this->taskData['empId'],4);
        $Where = array();
        //print_r($this->location); exit;
        $officerName = '';
        $officerDesign = '';
        if($this->userInfo['stake_abbr'] == 'GP')
          {
              $Where[] = " pem.gp_id_fk='".$this->location['gp_id']."'";
          } 
        if($this->userInfo['stake_abbr'] == 'BDO')
          {
              $bdoQuery ="SELECT bdo_name as officer_name from prd_block_profile WHERE block_code='".$this->userInfo['stake_user']."'";
              $dboData = $db->fetch_table($bdoQuery);
              $officerName = $dboData[0]['officer_name'];
              $officerDesign = 'Block Development Officer';

          } 
        if($this->userInfo['stake_abbr'] == 'EO')
          {
              $bdoQuery ="SELECT exe_officer_name as officer_name from psemp_ps_profile WHERE ps_id_fk ='".$this->location['ps_id']."'";
              $dboData = $db->fetch_table($bdoQuery);
              $officerName = $dboData[0]['officer_name'];
              $officerDesign = 'Executive Officer';

          } 
        if($this->userInfo['stake_abbr'] == 'AEO')
          {
              $bdoQuery ="SELECT aeo_name as officer_name from zpemp_zp_profile WHERE district_id_fk ='".$this->location['district_id']."'";
              $dboData = $db->fetch_table($bdoQuery);
              $officerName = $dboData[0]['officer_name'];
              $officerDesign = 'Additional Executive Officer ZP';

          }    
        if($this->userInfo['stake_abbr'] == 'FC&CAO')
          {
              //$bdoQuery ="SELECT aeo_name as officer_name from zpemp_zp_profile WHERE district_id_fk ='".$this->location['district_id']."'";
             // $dboData = $db->fetch_table($bdoQuery);
              $officerName = 'FC&CAO '.$this->location['district_name'];
              $officerDesign = 'FC&CAO';

          }                                       
        if(in_array($this->userInfo['stake_abbr'],array('DA','EO')))
          {
              $Where[] = " pem.ps_id_fk='".$this->location['ps_id']."'";
          } 
        if(in_array($this->userInfo['stake_abbr'],array('DEALING ASSISTANT (Establishment)','AEO')))
          {
              $Where[] = " pem.zp_id_fk='".$this->location['district_id']."'";            
          }  
        if($this->taskData['empId'] != '')
         {
            $Where[] = " pec.ech_id ='".$employeeId."'";
         } 
         //print_r($Where); exit;
        if(count($Where) > 0)
          {  
          $Where = ' WHERE '.implode(' AND ', $Where);
          $Query = "SELECT * from prd_employee_change as pec 
                    LEFT JOIN prd_employee_master as pem ON  pem.emp_id_const = pec.emp_id_const ".$Where;
          //echo $Query; exit;
          $EmployeeData = $db->fetch_table($Query);
          if(count($EmployeeData) > 0)
            {
            $EmployeeData = $EmployeeData[0];
            $EmployeeData['officer_name'] = $officerName;
            $EmployeeData['officer_desig'] = $officerDesign;
            $this->template('empinfosingle',$EmployeeData);

            } 
          else
            {
                 echo "Sorry No Employee Found.";  
            }  
          //echo json_encode($EmployeeData);
          }
        else
         {
            echo "Sorry No Employee Found1.";  
         }  
      }
    function getSingleRequest($empChangeId)
      {
          global $db;
         $Where = array();

         $Where[] = " ech_id='".$empChangeId."'";
         $Where = ' WHERE '.implode(' AND ', $Where);          
         $Query = "SELECT sdoc from prd_employee_change ".$Where;
         $employeeChangeRequest = $db->fetch_table($Query); 
         return $employeeChangeRequest[0]['sdoc'];      
      }     
    function getAllMyRequest()
       {
         global $db;
        $Where = array();
       // print_r($this->userInfo['stake_abbr']); exit;
        if($this->userInfo['stake_abbr'] == 'GP')
          {
              $Where[] = " pem.gp_id_fk='".$this->location['gp_id']."'";
              $join = ' RIGHT JOIN prd_dise_code_master as desig on pem.emp_desig::int = desig.code::int ';
              $desigName = ' desig.description as desig_name ';
          } 
        if($this->userInfo['stake_abbr'] == 'BDO')
          {
              $Where[] = " pec.block_code='".$this->location['block_code']."'";
              $join = ' LEFT JOIN prd_dise_code_master as desig on pem.emp_desig::int = desig.code::int ';
              $desigName = ' description as desig_name ';              
          }   
        if(in_array($this->userInfo['stake_abbr'],array('DA','EO')))
          {
              $Where[] = " pec.ps_id_fk='".$this->location['ps_id']."'";
              $join = ' RIGHT JOIN prd_dise_code_master as desig on pem.emp_desig::int = desig.code::int ';
              $desigName = ' desig.description as desig_name ';
          } 
        if(in_array($this->userInfo['stake_abbr'],array('DEALING ASSISTANT (Establishment)','AEO', 'FC&CAO')))
          {
              $Where[] = " pec.zp_id_fk='".$this->location['district_id']."'";
              if($this->userInfo['stake_abbr'] == 'AEO')
                $Where[] = " pec.ddo_active=1";
              $join = ' RIGHT JOIN zpemp_emp_desig_master as desig on pem.emp_desig::int = desig.designation_id::int ';
              $desigName = ' desig.designation_name as desig_name ';              
          }           

         $Where = ' WHERE '.implode(' AND ', $Where);          
         $Query = "SELECT pec.*,pem.*, ".$desigName." from prd_employee_change as pec
                    LEFT JOIN  prd_employee_master as pem  ON pem.emp_id_const = pec.emp_id_const
                    ".$join.$Where;
         //print($Query); exit;           
         $employeeChangeRequest = $db->fetch_table($Query);
         $this->template('employeerequest',$employeeChangeRequest);
         
                    

       }   
    function checkIfAlreadySubmitted()
      {
        global $db;
        if($this->taskData['empId'] != '' && strlen($this->taskData['empId']) ==12)
         {
            $Where[] = " emp_id_const ='".$this->taskData['empId']."'";
         } 
         $Where[] = " status = 0";
        if(count($Where) > 0)
          {  
          $Where = ' WHERE '.implode(' AND ', $Where);
          $Query = "SELECT * from prd_employee_change ".$Where;
          $EmployeeData = $db->fetch_table($Query);
          if(count($EmployeeData) > 0)
            {
                echo "You already requested for this employee on ".date('d,MY',strtotime($EmployeeData[0]['submitted_on'])).".Once approved or reject you may raise again!";
                exit;
            }          
          }                 
      }   
  	function SearchEmployee()
  	  {
  	  	global $db;
        $this->checkIfAlreadySubmitted();
        //print_r($_SESSION); exit;
  	  	$Where = array();
        $WhereNot = array();
        $HeadMemberQuery = '';
        $headMemberFrom = '';
        if($this->userInfo['stake_abbr'] == 'GP')
          {
              $Where[] = " gp_id_fk='".$this->location['gp_id']."'";
              $WhereNot[] = " gp_id_fk='".$this->location['gp_id']."'";
              $join = ' RIGHT JOIN prd_dise_code_master as desig on pem.emp_desig::int = desig.code::int ';
              $desigName = ' desig.description as desig_name ';

              $HeadMemberQuery = "SELECT gram_pradhan_name as name from prd_gp_profile WHERE ".implode('AND', $Where);
              $HeadMemberObj = $db->fetch_table($HeadMemberQuery);
              $headMemberFrom = $HeadMemberObj[0]['name'];
              $Designation = $this->location['gp_name'];
          } 
        if($this->userInfo['stake_abbr'] == 'DA')
          {
              $Where[] = " ps_id_fk='".$this->location['ps_id']."'";
              $WhereNot[] = " ps_id_fk='".$this->location['ps_id']."'";
              $join = ' RIGHT JOIN prd_dise_code_master as desig on pem.emp_desig::int = desig.code::int ';
              $desigName = ' desig.description as desig_name ';
              //$HeadMemberQuery = "SELECT exe_officer_name as name from psemp_ps_profile WHERE ".implode('AND', $Where);
              $Designation = $this->location['ps_name'];
              $headMemberFrom = 'DA '.$Designation;
              //$headName = 'DA';
          }   
        if($this->userInfo['stake_abbr'] == 'DEALING ASSISTANT (Establishment)')
          {
             $Where[] = " zp_id_fk='".$this->location['district_id']."'";
             $WhereNot[] =  " zp_id_fk='".$this->location['district_id']."'";
             $join = ' RIGHT JOIN zpemp_emp_desig_master as desig on pem.emp_desig::int = desig.designation_id::int ';
             $desigName = ' desig.designation_name as desig_name '; 
             $Designation = $this->location['district_name'];  
             $headMemberFrom = 'DAE '.$Designation;          
          }  
            
        if($this->taskData['empId'] != '' && strlen($this->taskData['empId']) ==12)
         {
            $WhereNot[] = " emp_id_const !='".$this->taskData['empId']."'";
         } 

          $WhereNot = ' WHERE '.implode(' AND ', $WhereNot);
          $Query = "SELECT pem.emp_id_const,pem.emp_first_name, pem.emp_second_name, pem.emp_last_name, ".$desigName." from prd_employee_master as pem ".$join.$WhereNot;
          //print($Query); exit;
          $otherEmployeeData = $db->fetch_table($Query);

         // $headMember = ($HeadMemberQuery != '')?:$headMemberFrom;  
           // $headMember =  '';    
          //print_r($headMember); exit;

        if($this->taskData['empId'] != '' && strlen($this->taskData['empId']) ==12)
         {
         	  $Where[] = " emp_id_const ='".$this->taskData['empId']."'";
         } 
        if(count($Where) > 0)
          {  
          $Where = ' WHERE '.implode(' AND ', $Where);
	  	  	$Query = "SELECT * from prd_employee_master ".$Where;
	  	  	//echo $Query; exit;
	  	  	$EmployeeData = $db->fetch_table($Query);
	  	  	if(count($EmployeeData) > 0)
	  	  	  {
		  	  	$EmployeeData = $EmployeeData[0];
            //$EmployeeData['otherEmployeeData'] = $otherEmployeeData;
            $EmployeeData['headMember'] =  $headMemberFrom;
            $EmployeeData['desig'] =  $Designation;
            //print_r($EmployeeData); exit;
		  	  	$this->template('empinfo',$EmployeeData);

	  	  	  }	
	  	  	else
	  	  	  {
                 echo "Sorry No Employee Found.";  
	  	  	  }  
	  	  	//echo json_encode($EmployeeData);
  	  	  }
  	  	else
  	  	 {
            echo "Sorry No Employee Found.";  
         }  
  	  }
      function basicValidation()
       {
          $post = $this->taskData;
         // return json_encode(array('status'=>'error','data'=>json_encode($post)));
          $data = array();
          if($post['newbasic'] == 0  )
          {
              $data[] = "Basic must be integer";
          }
          if($post['submittedby'] == "")
          {
              $data[] = "Submitted should not be blank";
          }
          if($post['submittedbydesig'] == "")
          {
              $data[] = "Designation should not be blank";
          }
          if($_FILES["file"]["name"] == "")
          {
              $data[] = "Please upload pdf file only";
          }
          if(count($data)>0)
          {
              return json_encode(array('status'=>'error','data'=>implode('/n',$data)));
          }
          return 1;

       }
      function getEmployeeDetails()
        {
           global $db;
          if($this->taskData['emp_id_const'] != '' && strlen($this->taskData['emp_id_const']) ==12)
           {
              $Where[] = " emp_id_const ='".$this->taskData['emp_id_const']."'";
           } 
          if(count($Where) > 0)
            {  
                $Where = ' WHERE '.implode(' AND ', $Where);
                $Query = "SELECT * from prd_employee_master ".$Where;
                //echo $Query; exit;
                $EmployeeData = $db->fetch_table($Query); 
                return $EmployeeData[0];                 
            }         
        } 
  	  function workflow()
  	  {
        $validate = $this->basicValidation();

        
        
        if($validate == 1)
        {
    	  	$rand=rand(100000, 999999);
         // $this->fileName = $rand.$_FILES["file"]["name"];
          $this->fileName = $rand.'.pdf';
    	  	$location = '../../../readwrite/intra_pri_upload/employee/'.$this->fileName;
    	  	if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
    	  	  {
    	  	      $return = $this->employeeChangeSave(); 
                echo  json_encode(array('status'=>'success','data'=>"Basic Change Request Sent Sucessfully"));
    	  	  }	
        }
        else
         echo $validate;    
  	  	
  	  }
      function getblockCode($gp_id)
       {
         global $db;
         $Query = "SELECT plmb.block_code from prd_location_master_gp as plmg 
                   LEFT JOIN prd_location_master_block as plmb on plmg.block_id_fk = plmb.block_id_pk
                   WHERE plmg.gp_id_pk=".$gp_id;
         $blockCode = $db->fetch_table($Query);
         return $blockCode[0]['block_code'];          

       }
      function employeeChangeSave()
      {
        global $db;
        $post = $this->taskData;
        $server = $_SERVER;
        $post['submit_ip'] = $server['REMOTE_ADDR'];
        //print_r($server); exit;
        $empDetails = $this->getEmployeeDetails();
        if($empDetails['gp_id_fk'] > 0)
        {
          $blockCode = $this->getblockCode($empDetails['gp_id_fk']);
          $post['block_code'] = $blockCode;
        }
        if($empDetails['ps_id_fk'] > 0)
        {
          $post['ps_id_fk'] = $empDetails['ps_id_fk'];
        }   
        if($empDetails['zp_id_fk'] > 0)
        {
          $post['zp_id_fk'] = $empDetails['zp_id_fk'];
        }              
        unset($post['newbasic']);
        unset($post['action']);
        $post['cur_basic'] = $empDetails['emp_pay_in_payband'];
        $post['basic'] = $this->taskData['newbasic'];
        $post['sdoc'] = $this->fileName;
        
        $Query = "INSERT INTO prd_employee_change ".$this->setInsertQuery($post);
        //print_r($Query); exit;
        $db->insert($Query);
        //return $Query;
        
      }
      function setInsertQuery($pArguments)
       {
          $field = array();
          $Query = array();
          foreach($pArguments as $key=>$value)
          {
             $field[] = $key;
             $Query[] = "'".$value."' as ".$key;
          }
          $Query = "(".implode(',',$field).") SELECT ".implode(',',$Query);
          return $Query;
       }
  	  function template($templateName,$data)
  	    {
          global $cryptoGraph,$config;
           // print_r($data); exit;
  	    	include_once('template/'.$templateName.'.php');
  	    }
  }

?>