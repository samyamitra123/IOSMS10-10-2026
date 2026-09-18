<?php 
   /**
    * 
    */
   class gpEmployeeFetchController extends AuthController
   {
   	
   	function __construct()
   	{
         //return "test"; 
   		$this->data = $this->getGpEmployee();
   	}
      function getGpEmployee()
      {
         
         $validation = $this->checkPriAuthValidation();
         //return $validation;
         if($validation->status == 'error')
         {
            //print_r($validation->status); exit;
            return $validation->msg;
         }
         return $this->getEmployee($validation);
      }
     function getEmployee($validation)
       {
         //print_r($validation->StackDetails->stake_user); exit;
          global $db; 

              $dataFetch = array(
                                   'pem.emp_id_const',
                                   'pem.emp_first_name',
                                   'pem.emp_second_name',
                                   'pem.emp_last_name',
                                   'lmg.gp_name',
                                   'lmg.gp_code',
                                   'lmg.lgd as gp_lgd',
                                   'lmb.block_name',
                                   'lmb.block_code',
                                   'lmb.lgd as block_lgd',
                                   'lmd.district_name',
                                   'lmd.district_code',
                                   'lmd.lgd as district_lgd',
                                   'pdcm.description as designation',
                                   'pem.emp_mobile_no',
                                   'pem.emp_mail_id'                               
                                );
          $dataFetch = implode(', ',$dataFetch);
           //print($dataFetch); exit;
          $Query ="SELECT ".$dataFetch." from prd_employee_master as pem
                   LEFT JOIN prd_dise_code_master as pdcm ON cast(pem.emp_desig AS varchar) = pdcm.code
                   LEFT JOIN prd_location_master_gp as lmg ON pem.gp_id_fk = lmg.gp_id_pk
                   LEFT JOIN prd_location_master_block as lmb ON lmg.block_id_fk = lmb.block_id_pk
                   LEFT JOIN prd_location_master_district as lmd ON lmb.district_id_fk = lmd.district_id_pk
                   LEFT JOIN prd_dise_payscale_master as pdpm ON pem.emp_pay_scale = pdpm.payscale_code
                   WHERE pem.gp_id_fk != 0 AND emp_cosolidated_pay = 0 AND pem.emp_status = 1 AND (pem.emp_desig >= '1114' AND pem.emp_desig <= '1124' AND lmg.gp_code='".$validation->StackDetails->stake_user."')";
          //print_r($Query); exit;         
          $empInArray = $db->fetch_table($Query);  
          $nempInArray = array();
          foreach($empInArray as $employee)
          {
            $middleName = ($employee['emp_second_name'] == '')?'':' '.$employee['emp_second_name'];
            $fullName = $employee['emp_first_name'].$middleName.' '.$employee['emp_last_name'];
            unset($employee['emp_first_name']);
            unset($employee['emp_second_name']);
            unset($employee['emp_last_name']);
            $employee['emp_name'] = $fullName; 
            $nempInArray[] = $employee;
          }
          return $nempInArray; //exit;

       }


   }