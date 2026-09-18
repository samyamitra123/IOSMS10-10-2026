<?php 
   /**
    * 
    */
   class zpUserFetchController extends AuthController
   {
   	
   	function __construct()
   	{
         //return "test"; 
   		$this->data = $this->getGpEmployee();
   	}
      function getGpEmployee()
      {
         
         $validation = $this->checkAuthValidation();
         if($validation->status == 'error')
         {
            //print_r($validation->status); exit;
            return $validation->msg;
         }
         return $this->getEmployee();
      }
     function getEmployee()
       {
         
          global $db; 

              $dataFetch = array(
                                   'pem.emp_id_const',
                                   'pem.emp_first_name',
                                   'pem.emp_second_name',
                                   'pem.emp_last_name',
                                   'lmd.district_name',
                                   'lmd.district_code',
                                   'lmd.lgd as district_lgd',
                                   'pdcm.designation_name as designation',
                                   'pem.emp_mobile_no',
                                   'pem.emp_mail_id'                               
                                );
          $dataFetch = implode(', ',$dataFetch);
           //print($dataFetch); exit;
          $Query ="SELECT ".$dataFetch." from prd_employee_master as pem
                   LEFT JOIN zpemp_emp_desig_master as pdcm ON cast(pem.emp_desig AS varchar) = cast(pdcm.designation_id AS varchar)
                   LEFT JOIN prd_location_master_district as lmd ON pem.zp_id_fk = lmd.district_id_pk
                   LEFT JOIN prd_dise_payscale_master as pdpm ON pem.emp_pay_scale = pdpm.payscale_code
                   WHERE pem.zp_id_fk != 0 AND emp_cosolidated_pay = 0 AND pem.emp_status = 1";
          //print($Query); exit;
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