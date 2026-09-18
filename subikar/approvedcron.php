<?php
    include_once('master.php');
    $master = new Master();
    global $db;
    /*$Query = "SELECT approved_days, application_id from intra_pri_forwarding WHERE for_app_rej='A' AND application_id LIKE 'ID/%' order by approved_days asc;";
    $ApprovedcgCases = $db->fetch_obj($Query);
    print_r($ApprovedcgCases); exit;*/

    $Query = "SELECT district_id_pk from prd_location_master_district WHERE district_code='3209'";
    $DistrictData = $db->fetch_obj($Query);
    //$Query = "SELECT count(employee_login_id_pk) as zplogin from prd_employee_login WHERE zp_id_fk = ".$DistrictData[0]->district_id_pk;
   /* $Query = "SELECT count(emp_id_pk) as zplogin from prd_employee_master WHERE zp_id_fk = ".$DistrictData[0]->district_id_pk.' AND emp_status =1';
    $zplogin = $db->fetch_obj($Query);        
    print_r($zplogin); exit; */

    $Query = "SELECT block_id_pk from prd_location_master_block WHERE district_id_fk='".$DistrictData[0]->district_id_pk."'";
    $blockData = $db->fetch_obj($Query); 
    $blockIdfk = array();
    foreach($blockData as $item)  
      {
         $blockIdfk[] = $item->block_id_pk;
      } 
   $blockIdfk = implode(',', $blockIdfk);
   // $Query = "SELECT count(employee_login_id_pk) as gplogin from prd_employee_login WHERE ps_id_fk IN (".$blockIdfk.")";
  /*  $Query = "SELECT count(emp_id_pk) as pslogin from prd_employee_master WHERE ps_id_fk IN (".$blockIdfk.") AND emp_status =1";
    $pslogin = $db->fetch_obj($Query);        
    print_r($pslogin); exit; */

   $Query = "SELECT gp_id_pk from prd_location_master_gp WHERE block_id_fk IN (".$blockIdfk.")";
   $gpData = $db->fetch_obj($Query); 
    $gpIdfk = array();
    foreach($gpData as $item)  
      {
         $gpIdfk[] = $item->gp_id_pk;
      }    
    $gpIdfk = implode(',', $gpIdfk);
    //$Query = "SELECT count(employee_login_id_pk) as gplogin from prd_employee_login WHERE gp_id_fk IN (".$gpIdfk.")";
    $Query = "SELECT count(emp_id_pk) as pslogin from prd_employee_master WHERE gp_id_fk IN (".$gpIdfk.") AND emp_status =1";
    $gplogin = $db->fetch_obj($Query);        
    print_r($gplogin); exit;

    $Query = "SELECT forwarding_id_pk,submitted_on from intra_pri_forwarding WHERE for_app_rej='A' AND approved_days='0' LIMIT 10 OFFSET 0;";
    $ApprovedCases = $db->fetch_obj($Query);
    //print_r($ApprovedCases); exit;
    foreach($ApprovedCases as $item)
    {
       $Query = "SELECT submitted_on from intra_pri_forwarding WHERE application_id='".$item->application_id."' order by forwarding_id_pk asc LIMIT 1 OFFSET 0;";
       $ApprovedFirstCase = $db->fetch_obj($Query);
      // print_r($ApprovedFirstCase); exit;
       $startedDate = strtotime($ApprovedFirstCase[0]->submitted_on);
       $solvedDate = strtotime($item->submitted_on);
       $difference = $solvedDate - $startedDate;
       $days = round($difference / (60*60*24));
       $days = ($days ==0)?1:$days;
       $Query = "update intra_pri_forwarding SET approved_days='".$days."' WHERE forwarding_id_pk='".$item->forwarding_id_pk."';";
       //print($Query); exit;
       $db->update($Query);
       //print_r($days);  exit;

    }
    $Query = "SELECT count(forwarding_id_pk) as count from intra_pri_forwarding WHERE for_app_rej='A' AND approved_days='0'";
    $LeftApprovedCases = $db->fetch_obj($Query);    
    echo "Done. Left: ".$LeftApprovedCases[0]->count;
    
?>