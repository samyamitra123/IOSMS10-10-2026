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
    function deletedrnDetails()
    {
        global $db,$crypto;
        $drn = $_POST['drn'];
        $billno = $_POST['billno'];
        $drn = $crypto->decode($drn,4);
        $Query = "DELETE from prd_gpf_subscriber_master WHERE drn_number='".$drn."'";
        $db->delete($Query);
        $returnData = array(
                              'drn_number'=>$drn,
                              'bill_no'=>$billno
                           );
        return $this->templateAssign('ajax_back_ngipf',$returnData);
        
    }
  	function getblock()
  	  {
  	  	  global $db;
  	  	  $districtId = $_POST['districtId'];
  	  	  $Query = "SELECT ps_id_pk as key, ps_name as value from prd_location_master_panchayat_samiti WHERE district_id_fk=".$districtId;
  	  	  $psData = $db->fetch_table($Query);
          $selectBox = $this->generateSelectBox($psData,'Select PS');
          return $selectBox;
  	  }
  	  function generateSelectBox($data,$intialValue)
  	   {
  	   	 $selectOption = array();
  	   	 $selectOption[] = '<option>'.$intialValue.'</option>';
  	   	 foreach($data as $item)
  	   	 {
            $selectOption[] = '<option value="'.$item['key'].'">'.$item['value'].'</option>';
  	   	 }
  	   	 $selectOption = implode('', $selectOption);
  	   	 return $selectOption;
  	   }
  	   function getdrnDetails()
  	   {
  	   	 global $db;
  	   	 $psId = $this->post['psId'];
  	   	 $year = $this->post['year'];
         $psDrnData = array();
         if($psId > 0 && $year != '')
            {
               $Query = "SELECT pbbd.bill_no, pbbd.salary_monthyear,pbbd.ps_id_fk, pbbd.drn_number, psbur.total_beneficiary, psbur.total_amount
                         from prd_block_bill_details as pbbd 
                         LEFT JOIN prd_sftp_benf_upload_response as psbur 
                         ON pbbd.block_bill_pk = psbur.bill_id_fk 
                         WHERE pbbd.ps_id_fk=".$psId." 
                         AND pbbd.salary_monthyear LIKE '".$year."%' 
                         AND pbbd.requisition_type = '1001' 
                         AND bill_sending_status=2 
                         AND status = '1' 
                         AND pbbd.salary_monthyear > '".$year."03'
                         AND psbur.sftp_benf_sending_status = 4
                         order by salary_monthyear asc;";
               $psDrnData = $db->fetch_table($Query);
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
              $Query = "select count(archive_final_pk) as salemp from prd_monthly_salary_archive_final 
                        WHERE ps_id_fk='".$item['ps_id_fk']."' AND salary_monthyear='".$item['salary_monthyear']."' AND consolidated_pay=0";
              $totalSalemployee = $db->fetch_table($Query);
              $totalSalemployee = $totalSalemployee[0]['salemp'];
              $Query = "select  count(pgrm.pfaccno) as total from prd_monthly_salary_archive_final as pmsaf 
                        LEFT JOIN prd_employee_master as pem 
                        ON pmsaf.emp_id_fk = pem.emp_id_pk
                        RIGHT JOIN prd_gpf_request_master as pgrm
                        ON pem.emp_id_const = pgrm.emp_id_const
                        WHERE pmsaf.ps_id_fk='".$item['ps_id_fk']."' AND pmsaf.salary_monthyear='".$item['salary_monthyear']."'";
              $totalNgipfBenf = $db->fetch_table($Query);
              $psDrnData[$key]['ngipf_allowed'] = ($totalSalemployee == $totalNgipfBenf[0]['total'])?1:0;

          }
          return $psDrnData;
       }
  }
 $action = $_POST['action'];
 $classObj = new NgipfAjax($action);
 print_r($classObj->return);

?>