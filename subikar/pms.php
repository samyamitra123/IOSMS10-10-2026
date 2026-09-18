<?php

   /* $retireTimeStamp = strtotime('+60 year',strtotime('1962-12-06'));
    $curTimeStamp = time();
    $EmployeeRetireDate = date('Y-m-d',$retireTimeStamp);
    if($curTimeStamp > $retireTimeStamp)
    	echo "I am retired";
    else
      echo "I am working";	

    print($EmployeeRetireDate); exit;*/


	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db=new database();	

  $Query = "SELECT * from prd_location_master_gp";
  $districtData = $db->fetch_obj($Query); 
  print_r(json_encode($districtData)); exit;
	function GPLGDUpdate()
	 {
	 	  global $db;
	 	  $Query = "SELECT block_id_pk, lgd from prd_location_master_block WHERE lgd > 0 ";
	 	  $districtData = $db->fetch_table($Query); 
	 	  $District = array();
	 	  foreach($districtData as $data)
	 	  	$District[$data['lgd']] = $data['block_id_pk'];
	 	  	
	 	  //print_r(count($District)); exit;	 
		  $ndata = file_get_contents('csv/gp.txt');
		  $ndata = json_decode($ndata,true);
		  //print_r($ndata);
		  $Query = array();
		  foreach($ndata as $data)
		  {
				  $Where = array();

				  $Where[] = ' lgd ='.$data['GP_LGD'];
				 // $Where[] = ' lgd_pri ='.$data['DIST_LGD_PRI'];
				  if($data['GP_NAME_BENG'] != 'NULL')
				    $Where[] = " gp_name_beng ='".$data['GP_NAME_BENG']."'";
				  $Query ="UPDATE prd_location_master_gp SET ".implode(', ',$Where)." WHERE gp_name='".strtoupper($data['GP_NAME'])."' AND block_id_fk=".$District[$data['BLOCK_LGD']];
				  $db->update($Query);
		  }
		  //$updateQ = implode('; ',$Query).';';
		  //print($updateQ); exit;
		  
		  echo "Update Done";
    }
  //GPLGDUpdate();  
	function BlockLGDUpdate()
	 {
	 	  global $db;
	 	  $Query = "SELECT district_id_pk, lgd from prd_location_master_district WHERE lgd != 0";
	 	  $districtData = $db->fetch_table($Query); 
	 	  $District = array();
	 	  foreach($districtData as $data)
	 	  	$District[$data['lgd']] = $data['district_id_pk'];
	 	  //print_r($District); exit;	 
		  $ndata = file_get_contents('csv/block.txt');
		  $ndata = json_decode($ndata,true);
		  //print_r($ndata);
		  $Query = array();
		  foreach($ndata as $data)
		  {
				  $Where = array();

				  $Where[] = ' lgd ='.$data['BLOCK_LGD'];
				 // $Where[] = ' lgd_pri ='.$data['DIST_LGD_PRI'];
				  if($data['BLOCK_NAME_BENG'] != 'NULL')
				    $Where[] = " block_name_beng ='".$data['BLOCK_NAME_BENG']."'";
				  $Query[] ="UPDATE prd_location_master_block SET ".implode(', ',$Where)." WHERE block_name='".strtoupper($data['BLOCK_NAME'])."' AND district_id_fk=".$District[$data['DIST_LGD']];
		  }
		  $updateQ = implode('; ',$Query).';';
		  //print($updateQ); exit;
		  $db->update($updateQ);
		  echo "Update Done";
    }

//BlockLGDUpdate();



	function DistrictLGDUpdate()
	 {
	 	  global $db;
		  $ndata = file_get_contents('csv/district.txt');
		  $ndata = json_decode($ndata,true);
		  //print_r($ndata);
		  $Query = array();
		  foreach($ndata as $data)
		  {
				  $Where = array();

				  $Where[] = ' lgd ='.$data['DIST_LGD'];
				  $Where[] = ' lgd_pri ='.$data['DIST_LGD_PRI'];
				  $Where[] = " district_name_bengali ='".$data['DIST_NAME_BENG']."'";
				  $Query[] ="UPDATE prd_location_master_district SET ".implode(', ',$Where)." WHERE district_name='".strtoupper($data['DIST_NAME'])."'";
		  }
		  $updateQ = implode('; ',$Query).';';
		 // print($updateQ); exit;
		  $db->update($updateQ);
		  echo "Update Done";
    }

  //DistrictLGDUpdate();
?>