<?php
require '../includes/config/config.php';
require '../includes/config/database.config_api.php';
require '../includes/library/database.class.php';
require_once '../includes/library/cryptography.class.php';
$db = new database();

$Query = "SELECT designation_code, stake_user_code_suffix, forwarding_level from intra_pri_designation_master order by designation_code desc";
$designations = $db->fetch_table($Query);
$designationNew = array();
foreach($designations as $designation)
   {
   	if($designation['forwarding_level'] != 'NA')
        $designationNew[$designation['designation_code']] = array(
        	                                                       'suffix'=> $designation['stake_user_code_suffix'],
        	                                                       'forward'=>explode(',',$designation['forwarding_level'])
        	                                                     );
   } 
   // BDO need to unset
   //unset($designationNew[34]);
//print_r($designationNew); exit;
// foreach($designationNew as $key=>$desg)
    {
      $key = 33;
    	$Query = "SELECT designation, officer_id_pk,stake_user_code,forwarding_user, higher_authority_stake_user_code from intra_pri_master WHERE designation=".$key;
    	$employees = $db->fetch_table($Query);
      //print_r($employees);exit;
      //$UpdateQuery = array();
    	foreach($employees as $key=>$employee)
    	{
    		
    		//$employees[$key]['take_user_code'] = $higher_authority_stake_user_code.$designationNew[$employee['designation']]['suffix'];
    		$forwardTemp = array();
    		foreach($designationNew[$employee['designation']]['forward'] as $forward)
    		{
               //if($designationNew[$forward]['suffix'] == 'JTBDO' || $designationNew[$forward]['suffix'] == 'BDOOP' || $designationNew[$forward]['suffix'] == 'BDOSO')
               
               //if($designationNew[$forward]['suffix'] == 'COMM' || $designationNew[$forward]['suffix'] == 'JS' || $designationNew[$forward]['suffix'] == 'AS')
              if($designationNew[$forward]['suffix'] == 'COMM' || $designationNew[$forward]['suffix'] == 'BDO')
               {
                  $higher_authority_stake_user_code = ''; //(int) $employee['stake_user_code'];
               }
               else
               {
                  $higher_authority_stake_user_code = (int) $employee['stake_user_code'];
                  //$higher_authority_stake_user_code = (int) $employee['higher_authority_stake_user_code'];
               }
                //$higher_authority_stake_user_code = (int) $employee['higher_authority_stake_user_code'];
                $forwardTemp[] = $higher_authority_stake_user_code.$designationNew[$forward]['suffix'];
    		}
    		$employees[$key]['newforwarding'] = implode(',',$forwardTemp);


           $UpdateQuery = "UPDATE intra_pri_master SET forwarding_user='".$employees[$key]['newforwarding']."' WHERE officer_id_pk=".$employees[$key]['officer_id_pk'];
 
   /*      else
         {
         $UpdateQuery = "UPDATE intra_pri_master SET stake_user_code='".$employees[$key]['take_user_code']."', forwarding_user='".$employees[$key]['newforwarding']."' WHERE officer_id_pk=".$employees[$key]['officer_id_pk'];            
         }*/
         

         print($UpdateQuery); exit;
    		//$db->update($UpdateQuery);
    	}
      //print_r($UpdateQuery); exit;
    	
    } 

?>