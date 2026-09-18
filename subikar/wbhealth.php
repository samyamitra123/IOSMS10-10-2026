<?php 
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db=new database();	
    
    // All GP Block District
	/*$Query = "SELECT district_name,block_name,gp_name FROM prd_location_master_gp as gp
	          LEFT JOIN prd_location_master_block as b ON gp.block_id_fk = b.block_id_pk
	          LEFT JOIN prd_location_master_district as d ON b.district_id_fk= d.district_id_pk
	          WHERE gp.gp_name != '%TEST%' OR gp.gp_name != '%OTHER%' AND gp.flag = 2";
	$allDistricts = $db->fetch_table($Query);
	print_r(json_encode($allDistricts));
	*/
  /*'lmg.gp_name',
                                   'lmg.gp_code',
                                   'lmg.lgd as gp_lgd',
                                   'lmb.block_name',
                                   'lmb.block_code',
                                   'lmb.lgd as block_lgd',
                                   'lmd.district_name',
                                   'lmd.district_code',
                                   'lmd.lgd as district_lgd',
                                   'pdcm.description as designation',*/
  /*               $dataFetch = array(
                                   'pem.emp_id_const',
                                   'pem.emp_first_name',
                                   'pem.emp_second_name',
                                   'pem.emp_last_name',
                                   
                                   'pem.emp_bank_name',
                                   'pem.emp_acc_no',
                                   'pem.emp_ifsc_no',
                                   'pem.emp_pan_no',
                                   'pem.emp_aadhar_no'                               
                                );
          $dataFetch = implode(', ',$dataFetch);
           //print($dataFetch); exit;
          $Query ="SELECT ".$dataFetch." from prd_employee_master as pem
                   WHERE pem.emp_cosolidated_pay = 0 AND pem.emp_status = 1 AND emp_status_deputation = 0";
          //print_r($Query); exit;         
          $empInArray = $db->fetch_table($Query);  
          $nempInArray = array();
          foreach($empInArray as $employee)
          {
            $middleName = ($employee['emp_second_name'] == '')?'':' '.$employee['emp_second_name'];
            $fullName = $employee['emp_first_name'].$middleName;
            unset($employee['emp_first_name']);
            unset($employee['emp_second_name']);
            
            $employee['emp_fname'] = $fullName; 
            $employee['emp_lname'] = $employee['emp_last_name'];
            unset($employee['emp_last_name']); 
            $nempInArray[] = $employee;
          }
          print_r(json_encode($nempInArray)); */ //exit;

           /* $Query = "SELECT pda.treasury_name, pda.treasury_block_code, pda.ddo_code, plmb.block_name, pda.operator_code_pf  from prd_dise_admin as pda
                      LEFT JOIN prd_location_master_block as plmb ON pda.block_code::int = plmb.block_code::int WHERE pda.treasury_name != 'TEST'";
            $psData = $db->fetch_obj($Query); 
            print_r(json_encode($psData)); exit;*/

            $Query = "SELECT 
    pem.emp_id_const,
    pem.emp_first_name,
    pem.emp_second_name,
    pem.emp_last_name,
    pem.emp_pan_no,
    pem.emp_aadhar_no,
    pem.emp_bank_name,
    pem.emp_acc_no,
    pem.emp_ifsc_no,
    CASE WHEN bl.block_name IS NOT NULL THEN bl.block_name ELSE '' END AS block_name,
    CASE WHEN gl.gp_name IS NOT NULL THEN gl.gp_name ELSE '' END AS gp_name,
    CASE WHEN ps.ps_name IS NOT NULL THEN ps.ps_name ELSE '' END AS ps_name,
    CASE WHEN zp.district_name IS NOT NULL THEN zp.district_name ELSE '' END AS district_name    
FROM prd_employee_master pem
LEFT JOIN health h ON pem.emp_id_const = h.emp_id_const
LEFT JOIN 
    prd_location_master_gp AS gl ON pem.gp_id_fk > 0 AND pem.gp_id_fk = gl.gp_id_pk
LEFT JOIN 
    prd_location_master_block AS bl ON gl.block_id_fk = bl.block_id_pk
LEFT JOIN 
    prd_location_master_panchayat_samiti AS ps ON pem.ps_id_fk > 0 AND pem.ps_id_fk = ps.ps_id_pk
LEFT JOIN 
    prd_location_master_district AS zp ON pem.zp_id_fk > 0 AND pem.zp_id_fk = zp.district_id_pk
WHERE h.emp_id_const IS NULL 
  AND pem.emp_id_const != '0'
  AND pem.emp_cosolidated_pay = 0 
  AND emp_status_deputation = 0 
  AND (bl.block_name IS NULL OR bl.block_name NOT LIKE '%TEST%') 
  AND (ps.ps_name IS NULL OR ps.ps_name NOT LIKE '%TEST%') 
  AND (zp.district_name IS NULL OR zp.district_name NOT LIKE '%TEST%')
  AND (gl.gp_name IS NULL OR gl.gp_name NOT LIKE '%TEST%');";
            $EmployeeExisting = $db->fetch_obj($Query); 
            print_r(json_encode($EmployeeExisting)); exit;
            $updateQuery = array();
            foreach($EmployeeExisting as $key=>$emp)
            {

              $updateQuery[] = "update health SET 
                                block_name='".$emp->block_name."', 
                                gp_name='".$emp->gp_name."',
                                ps_name='".$emp->ps_name."',
                                district_name='".$emp->district_name."'";


            }
            
            $updateQuery = implode('; ',$updateQuery);
            print_r($updateQuery); exit;
            $db->update($updateQuery); 
            echo "Done";

?>