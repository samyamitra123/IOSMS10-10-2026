<?php 
   /**
    * 
    */
   class empPayslipFetchController extends AuthController
   {
   	
   	function __construct()
   	{
         global $task;
         switch($task)
          {
            case 'otpCheck':
              $this->data = $this->VerfyOtp();
            break;
            case 'yearMonthPaySlip':
              $this->data = $this->yearMonthPaySlip();
            break; 
            case 'annualStatement':
              $this->data = $this->annualStatement();
            break;              
            case 'cronOtp':
              $this->data = $this->cronOtp();
            break;                        
            case 'default':
               $this->data = $this->getEmployeeByPhoneNo();
            break;          
         } 
   		
   	}
      function annualStatement()
       {
            global $db,$post,$crypto;
            $Query = "Select gp_id_fk,ps_id_fk,zp_id_fk,ropa_status,emp_id_pk as user_id from prd_employee_master WHERE access_key='".$post['access_key']."'";
            $empInArray = $db->fetch_table($Query); 
            if(isset($empInArray[0]['user_id']) && $empInArray[0]['user_id'] > 0)
            {
               $AnnualStatementArg = array(
                                     'year'=>$post['fin_year'],
                                     'userId'=>$empInArray[0]['user_id']
                                  );
               $AnnualStatementArg = json_encode($AnnualStatementArg);
               $AnnualStatementArg = $crypto->encode($AnnualStatementArg,2);

               $baseUrl = 'https://priemp.wbprd.gov.in/page/all_moduls/mis_report/';                           
               $annualStatementDownload = $baseUrl.'statement-'.$AnnualStatementArg.'.xls';
               $returnData = array(
                                    'content'=>'Your Annual Statement is ready to download',
                                    'download'=>$annualStatementDownload
                                  );
               return $returnData;
            }
            else
            {
                return array('error'=>1,'error_msg'=>'Something Wrong Please start again.');
            }                     
       }
      function yearMonthPaySlip()
      {
         global $db,$post,$crypto;
         $Query = "Select gp_id_fk,ps_id_fk,zp_id_fk,ropa_status,emp_id_pk as user_id from prd_employee_master WHERE access_key='".$post['access_key']."'";
         $empInArray = $db->fetch_table($Query); 
         //return "test";
         if(isset($empInArray[0]['user_id']) && $empInArray[0]['user_id'] > 0)
         {
            $PayslipArg = array(
                                  'user_id'=>$empInArray[0]['user_id'],
                                  'year'=>$post['year'],
                                  'month'=>$post['month'],
                                  'ropa'=>$empInArray[0]['ropa_status']
                               );
            $PayslipArg = json_encode($PayslipArg);
            $PayslipArg = $crypto->encode($PayslipArg,2);
            if($empInArray[0]['gp_id_fk'] > 0)
               $baseUrl = 'https://priemp.wbprd.gov.in/page/intra_prd/gp/';
            if($empInArray[0]['ps_id_fk'] > 0)
               $baseUrl = 'https://priemp.wbprd.gov.in/page/intra_ps/da/';
            if($empInArray[0]['zp_id_fk'] > 0)
               $baseUrl = 'https://priemp.wbprd.gov.in/page/all_moduls/payslip_generation/';                           
            $payslipDownload = $baseUrl.'payslip-'.$PayslipArg.'.pdf';
            $returnData = array(
                                 'content'=>'Your Payslip is ready to download',
                                 'download'=>$payslipDownload
                               );
            return $returnData;
         }
         else
         {
             return array('error'=>1,'error_msg'=>'Something Wrong Please start again.');
         }
         
      }
      function VerfyOtp()
        {
          global $db,$post;
          if($post['phone'] == '' || strlen($post['phone']) != 10 || !is_numeric($post['phone']))
          {
            return array('error'=>1,'error_msg'=>'Wrong Input');
          }
          if($post['otp'] == '' || strlen($post['otp']) != 6 || !is_numeric($post['otp']))
          {
            return array('error'=>1,'error_msg'=>'Wrong Input');
          }  
          $Query = "Select * from prd_employee_master WHERE emp_otp='".$post['otp']."' AND emp_mobile_no='".$post['phone']."'";
          $empInArray = $db->fetch_table($Query);  
          if(isset($empInArray[0]['emp_id_pk']) && $empInArray[0]['emp_id_pk'] > 0)      
            {
               $key = md5($empInArray[0]['emp_id_pk'].time());
               $otp = 0;
               $Query = "Update prd_employee_master SET emp_otp ='".$otp."', access_key='".$key."' WHERE emp_id_pk='".$empInArray[0]['emp_id_pk']."'";
               //return $Query;
               $db->update($Query);   
               return  array('access_key'=>$key);          
            }
          else
            {
               return array('error'=>1,'error_msg'=>'Sorry Wrong OTP / Phone Number');
            }
        }
      function getEmployeeByPhoneNo()
      {
         
         $validation = $this->checkAuthValidation();
         if($validation->status == 'error')
         {
            //print_r($validation->status); exit;
            return $validation->msg;
         }
         return $this->getEmployeeInfo();
      }
     function getEmployeeInfo()
      {
         
         $empInfo = $this->getEmployee();
        // return ;
         if(isset($empInfo[0]) && $empInfo[0]['emp_id_const'] != '')
         {
            $this->generateOtp($empInfo[0]['emp_id_const'],$empInfo[0]['emp_mobile_no']);
            return 'Please Enter OTP Send to your Register mobile No: '.$empInfo[0]['emp_mobile_no'];
         }
         else
         {
            return array('error'=>1,'error_msg'=>'Sorry Number Does not exists');
         }
         
      }
      function generateOtp($empId,$mobile)
      {
         global $db;
         $otp = rand(111111,999999);
         //$otp = 666666;
         $this->sendOtp($mobile,$otp);
         $Query = "Update prd_employee_master SET emp_otp ='".$otp."',access_key='0' WHERE emp_id_const='".$empId."'";
         //return $Query;
         $db->update($Query);
         return $otp;
      }
     function cronOtp()
     {
         global $db;
         $query = "SELECT * from prd_otp WHERE status = 0";
         $OtpData= $db->fetch_table($query); 
         if(count($OtpData) > 0)
         {
            $otpDataInArray = array();
            $IdInArray = array();
            foreach($OtpData as $item)
            {
               $otpDataInArray[] = array(
                                           'mobile'=>$item['mobile'],
                                           'otp'=>$item['otp']
                                        );
               $IdInArray[] = $item['id'];
            }
            $IdInArray = implode(',',$IdInArray);
            $updateQuery = 'update prd_otp SET status = 1 WHERE id IN ('.$IdInArray.')';
            $db->update($updateQuery); 
            return $otpDataInArray;  
         }  
         else
           return null; 
     }
     function sendOtp($mobile,$otp)
      {
         global $db;
         $query = "SELECT id from prd_otp WHERE mobile = '".$mobile."'";
         $OtpMobile = $db->fetch_table($query);
         if(isset($OtpMobile[0]['id']) && $OtpMobile[0]['id'] > 0)
          {
            $InsertQuery = "UPDATE prd_otp SET otp = '".$otp."', status=0 WHERE id =".$OtpMobile[0]['id'];
            $db->update($InsertQuery); 
          } 
          else
          { 
            $InsertQuery = "INSERT INTO prd_otp (mobile, otp, status) VALUES ('".$mobile."','".$otp."',0)";
            $db->insert($InsertQuery);  
         }      
      }
     function getEmployee()
       {
         
          global $post,$db;
          $mobile = $post['phone'];
          $Query = "SELECT * from prd_employee_master WHERE emp_mobile_no='".$mobile."'";     
          $empInArray = $db->fetch_table($Query);  
          return $empInArray; 
       }


   }