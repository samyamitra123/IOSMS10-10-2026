<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
global $db,$crypto;
$db=new database();
$crypto = new cryptography();
  class NgipfAjax {
  	var $return  = '';
  	var $post = array();
  	function __construct($action)
  	  {
  	  	 $this->post = $_POST;
         $this->return = $this->{$action}();
         //return $this->return;
  	  }

  	   function getdrnDetails()
  	   {
  	   	 global $db;
  	   	 $zpId = $this->post['zpId'];
  	   	 $year = $this->post['year'];
         $psDrnData = array();
         if($zpId > 0 && $year != '')
            {
               //pbbd.bill_no, pbbd.salary_monthyear,pbbd.zp_id_fk, pbbd.drn_number, psbur.total_beneficiary, psbur.total_amount
               $Query = "SELECT pbbd.bill_no, pbbd.salary_monthyear,pbbd.zp_id_fk, pbbd.drn_number, psbur.total_beneficiary, psbur.total_amount
                         from prd_block_bill_details as pbbd 
                         LEFT JOIN prd_sftp_benf_upload_response as psbur 
                         ON pbbd.block_bill_pk = psbur.bill_id_fk 
                         WHERE pbbd.zp_id_fk='".$zpId."' 
                         AND pbbd.zp_emp_type = '367'
                         AND pbbd.salary_monthyear LIKE '".$year."%' 
                         AND pbbd.requisition_type = '1001' 
                         AND bill_sending_status=2 
                         AND status = '1' 
                         AND psbur.sftp_benf_sending_status = 4
                         order by salary_monthyear asc;";
               //print_r($Query); exit;
               $psDrnData = $db->fetch_table($Query);
               //print_r($psDrnData); exit;
               $psDrnData = $this->checkEmployeeGPFGenerated($psDrnData);
               //print_r($psDrnData); exit;
               return $this->templateAssign('ajax_ps_ngipf',$psDrnData);
        	   //return $psDrnData;
           }
  	   }
       function templateAssign($templateName,$variable)
       {
          global $db,$crypto;
          ob_start();
          include_once('templae-ngipf/'.$templateName.'.php');
          $content = ob_get_contents();
          ob_end_clean();
          return $content;
       }
       function checkEmployeeGPFGenerated($psDrnData)
       {
          global $db;
          foreach($psDrnData as $key=>$item)
          {
              $psDrnData[$key]['ngipf_send_status'] = -1;
              $Query = "SELECT * from prd_gpf_subscriber_master WHERE drn_number='".$item['drn_number']."'";
              //$psDrnData[$key]['ngipf_send_status_query'] = $Query;
              $SubscribeDetails = $db->fetch_table($Query);
              if(isset($SubscribeDetails[0]['subscriber_id']))
              {
                 $psDrnData[$key]['ngipf_send_status'] = $SubscribeDetails[0]['status'];

              }
              // Only PRI Employee pmsaf.zp_emp_type='367'
              $Query = "select  count(pgrm.pfaccno) as total from prd_monthly_salary_archive_final as pmsaf 
                        LEFT JOIN prd_employee_master as pem 
                        ON pmsaf.emp_id_fk = pem.emp_id_pk
                        LEFT JOIN prd_gpf_request_master as pgrm
                        ON pem.emp_id_const = pgrm.emp_id_const
                        WHERE pmsaf.zp_emp_type='367' AND pem.emp_status_deputation=0  AND pmsaf.zp_id_fk='".$item['zp_id_fk']."' AND pmsaf.salary_monthyear='".$item['salary_monthyear']."'";
              //print_r($Query); exit;
              $totalNgipfBenf = $db->fetch_table($Query);
              $totalNgipfBenf = $totalNgipfBenf[0]['total'];
              $Query = "select  count(pmsaf.*) as total from prd_monthly_salary_archive_final as pmsaf 
                        LEFT JOIN prd_employee_master as pem 
                        ON pmsaf.emp_id_fk = pem.emp_id_pk
                        WHERE pmsaf.zp_emp_type='367' AND pem.emp_status_deputation=0 AND pmsaf.zp_id_fk='".$item['zp_id_fk']."' AND pmsaf.salary_monthyear='".$item['salary_monthyear']."'";
              $totalBenf = $db->fetch_table($Query);   
              $totalBenf = $totalBenf[0]['total'];           
              $psDrnData[$key]['ngipf_allowed'] = ($totalBenf == $totalNgipfBenf)?1:0;
              $psDrnData[$key]['ngipf_employee_missing'] = $totalBenf - $totalNgipfBenf;

          }
          return $psDrnData;
       }
  }
 $action = $_POST['action'];
 $classObj = new NgipfAjax($action);
 print_r($classObj->return);

?>