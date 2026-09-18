<?php 
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db = new database();

  /*$gpfQuery = "SELECT lmg.gp_code, lmg.gp_name, grm.emp_id_const, pem.emp_first_name,pem.emp_second_name,pem.emp_last_name, grm.full_response FROM prd_gpf_request_master as grm
               LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
               LEFT JOIN prd_location_master_gp as lmg ON pem.gp_id_fk = lmg.gp_id_pk WHERE grm.status=2 AND grm.full_response LIKE '%NGIPF Acc No Already Generated for for IOSMS id%' AND pem.gp_id_fk > 0";
  $gpfError = $db->fetch_obj($gpfQuery);
  print_r(json_encode($gpfError)); exit;*/


  $Query = "update prd_gpf_request_master SET status = 10 WHERE status = 3";
  $db->update($Query);
  echo "Done"; exit; 
 //ND full_response LIKE '%NGIPF Acc No Already Generated for for IOSMS id%'
   

  $gpfQuery = "SELECT grm.emp_id_const, grm.pfaccno, pem.emp_first_name,pem.emp_second_name,pem.emp_last_name,grm.pnrd_request, grm.full_response FROM prd_gpf_request_master as grm
               LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
               WHERE grm.status = 3 "; //AND grm.full_response LIKE '%NGIPF Acc No Already Generated for for IOSMS id%'"; //LIMIT 1 OFFSET 1
 // print_r($gpfQuery); exit;

  $gpfError = $db->fetch_obj($gpfQuery);
  $newgpf = array();
  $employeeIds = array();
  $haystick = array('Not found','555544');
  foreach($gpfError as $key=>$gpf)
  {
     $request = json_decode($gpf->pnrd_request);
     $response = json_decode($gpf->full_response);
     $error = $response->resp->errDesc;
     //$empType = $request->req->empDtls->otherDtls->empType;
    // $empType = $request->req->empDtls->workDtls[0]->sectionCode;
     //$empType = $request->req->empDtls->workDtls[0]->opCodePf;
     $empType = $request->req->empDtls->otherDtls->mobile;

     //if(in_array($empType,$haystick))
     {
        $employeeIds[] = "'".$gpf->emp_id_const."'";
     }
     $gpfError[$key]->pnrd_request = $empType;
     $gpfError[$key]->error = $error;
     unset($gpfError[$key]->full_response);
     //print_r(); exit;

  }
 /* $employeeIds = implode(',', $employeeIds);
  $Query = "update prd_gpf_request_master SET status = 9 WHERE emp_id_const IN (".$employeeIds.")";
  $db->update($Query);
  echo "Done"; exit; */
  //print_r($employeeIds); exit;
  print_r(json_encode($gpfError));
	/*
AND pem.ps_id_fk > 0
LEFT JOIN prd_location_master_panchayat_samiti as lmg ON pem.ps_id_fk = lmg.ps_id_pk 
	$Query = "SELECT * from prd_location_master_block WHERE ngipf_status=1";
	$blockInArray = $db->fetch_obj($Query);

	foreach($blockInArray as $key=>$block)
	  {
	  	//print_r($block); exit;
         $blockQuery = "SELECT emp_id_const,emp_first_name,emp_second_name,emp_last_name,lmg.gp_code, lmg.gp_name from prd_employee_master as pem 
                        LEFT JOIN prd_location_master_gp as lmg ON pem.gp_id_fk = lmg.gp_id_pk
                        WHERE lmg.block_id_fk='".$block->block_id_pk."' AND pem.emp_status=1 AND pem.emp_cosolidated_pay=0 AND pem.ropa_status = 1";
         $blockEmployee = $db->fetch_obj($blockQuery);
         $blockInArray[$key]->employeeCount = count($blockEmployee);
         $empIds = array();
         foreach($blockEmployee as $employee)
         {
         	$empIds[] = "'".$employee->emp_id_const."'";
         }
         $empIds = implode(', ',$empIds);
         $gpfQuery = "SELECT emp_id_const,full_response FROM prd_gpf_request_master WHERE emp_id_const IN (".$empIds.") AND status=2";
         $blockgpfEmployee = $db->fetch_obj($gpfQuery);
         $blockInArray[$key]->employeegpfMissing = $blockgpfEmployee; 

               
         
	  } 
	 // print_r($blockInArray); exit;
	  $ngipfAccountAlready = array();
	  foreach($blockInArray as $blockInfo)
	  {
	  	if(count($blockInfo->employeegpfMissing) > 0){
	  		foreach($blockInfo->employeegpfMissing as $item){
	           //$middleName = ($item->emp_second_name != '')?' '.$item->emp_second_name:'';
			       $ngipfAccountAlready[] = array(
		       	                                'block_code'=>$blockInfo->block_code,
		       	                                'block_name'=>$blockInfo->block_name,
		       	                                //'gp_name'=>$item->gp_name,
		       	                                //'gp_code'=>$item->gp_code,
		       	                                //'employeeCount'=>$blockInfo->employeeCount,
		       	                                //'emp_name'=>$item->emp_first_name.$middleName.' '.$item->emp_last_name,
		       	                                'empId'=>$item->emp_id_const
			       	                              );
		     }
     }

	  }
	  print_r($ngipfAccountAlready); exit;  */
?>