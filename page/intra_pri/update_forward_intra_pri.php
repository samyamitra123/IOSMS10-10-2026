<?php
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';
//print_r($_POST); exit;
/*if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();




if($cryptoGraph->decode($_GET['action'],4)=='approve'){ */

	function code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function code_block($val)
	{
		$db=new database();
		/*$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_id_pk='".$val."'"); */
		 
		 $arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block WHERE block_id_pk='".$val."'");
		 
		return $arr[0]['block_name'];																															
	}
	
	function code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
		return $arr[0]['district_name'];																															
	}
	function fun_desig($dsig)
	{ 
		$db = new database();
		$data = $db->fetch_table("select description,code from prd_dise_code_master where code='".$dsig."'");
		return $data[0]['description'];
	}
	function fun_desig1($dsig)
	{ 
		$db = new database();
		$data = $db->fetch_table("SELECT designation_id, designation_name FROM zpemp_emp_desig_master where designation_id='".$dsig."'");
		return $data[0]['designation_name'];
	}
	
	$db=new database();

	$officer_id_const= $_POST['officer_id_const']; 
	$application_id= $_POST['application_id'];
	$remarks= urldecode($_POST['remarks']);
	//echo $remarks; exit;
	$service_type= $_POST['service_type'];
	$for_app_rej_status= $_POST['for_app_rej_status'];
	$emp_id_const = $_POST['emp_id_const'];
	$sub_menu = $_POST['sub_menu'];
	//print_r($_POST); exit;
    $Query ="SELECT officer_name,designation,stake_user_code, login_level_stake from  intra_pri_master WHERE officer_id_const='".$_SESSION['user_info']['officer_id_const']."'";
    $forwardData = $db->fetch_table($Query);
    $forwardData = $forwardData[0]; 

	$Query = "SELECT * from intra_pri_designation_master WHERE designation_code='".$forwardData['designation']."'";
	$Designations = $db->fetch_table($Query);
	$forwardData['designation'] = $Designations[0]['designation'];
	$stake_user_code = (int) $forwardData['stake_user_code'];
	switch($forwardData['login_level_stake'])
	  {
	  	 case 'DISTRICT':
	  	    $Query = "SELECT * from prd_location_master_district WHERE district_code='".$stake_user_code."'";
	  	    $locationName = $db->fetch_table($Query);
	  	    $locationName = $locationName[0]['district_name'];
	  	 break;
	  	 case 'BLOCK':
			$Query = "SELECT * from prd_location_master_block WHERE block_code='".$stake_user_code."'";
	  	    $locationName = $db->fetch_table($Query);
	  	    $locationName = $locationName[0]['block_name']; 	    
	  	 break;
	  	 case 'STATE':
	  	    $locationName = 'STATE';
	  	 break;
	  }
	$officer_info = $forwardData['officer_name']."(".$forwardData['designation'].") ". $locationName; 
    //print_r($officer_info);
     //exit;

//print_r($_FILES); exit;
//var_dump($for_app_rej_status); die;
//print(($_POST['type']=='final_reject')); exit;
if($_POST['type']== 'submit_app' || $_POST['type']== 'comment_app'){  



    if(isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != '')	
    {
		$rand=rand(100000, 999999);
	    $fileName = $rand.$_FILES["file"]["name"];
	    $location = '../../readwrite/intra_pri_upload/comments/'.$fileName;
	    //print($location); exit;
		if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
		  {
		  	 $Query = "UPDATE intra_pri_forwarding SET msg_file='".$fileName."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ";
	         $db->update($Query);
	      }	    
    }
	
	$update=$db->update("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2', remarks='".$remarks."', for_app_rej='".$for_app_rej_status."', officer_info='".$officer_info."'  WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
	
	if($update){
			$Query = "INSERT INTO intra_pri_forwarding
								( application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu )
									VALUES
										('".$application_id."',
										'".$officer_id_const."',
										'1',
										'".$service_type."',
										'".$emp_id_const."',
										'".$sub_menu."')";
										
			$insert_query = $db->insert($Query);							
	}						
	if($update && $insert_query){ 
	
	    unset($_SESSION['application_id']);
		echo "Application Forwareded Successfully.";
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Forwareded Successfully.</strong></div>';
		//echo 'location:block_list_for_approval.php?id='.$dist[0]['district_id_fk'];
		//header('location:view_intra_pri_service.php');
		//exit(0);
	}else{ 
		echo "Application Forwareded Failed.";
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Forwareded Failed.'.$Query.'</strong></div>';
		//header('location:view_intra_pri_service.php');
		//exit(0);

	}
}else if($_POST['type']=='final_reject'){ 
	$db=new database();
	//echo 222; die;
	/*$Query = "UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2',remarks='".$remarks."', for_app_rej='".$for_app_rej_status."', officer_info='".$officer_info."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1'"; */
	$Query = "UPDATE intra_pri_forwarding SET to_officer_id_const='',status='2',remarks='".$remarks."', for_app_rej='".$for_app_rej_status."', officer_info='".$officer_info."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1'";
	//print($Query); exit;
	$update=$db->update($Query);
	/*if($update){
		
	$insert_query = $db->insert("INSERT INTO intra_pri_forwarding
						( application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu )
							VALUES
								('".$application_id."',
								'".$officer_id_const."',
								'1',
								'".$service_type."',
								'".$emp_id_const."',
								'".$sub_menu."'
								)");
	}	*/					
	if($update){
		
		unset($_SESSION['application_id']);
		echo "Proposal For Rejection Send Successfully...";
		//$_SESSION['block_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile Rejected successfully...</strong></div>';
		//header('location:view_intra_pri_service.php');
		//exit(0);
	}else{
		echo "Proposal For Rejection Send Failed...";
		//$_SESSION['block_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>BDO Profile has not been rejected...</strong></div>';
		//header('location:view_intra_pri_service.php');
		//exit(0);

	}
}

// echo $table[0]['db_page']; 



else if($_POST['type']== 'final_approval'){  
	$db=new database();
	$table= $db->fetch_table("SELECT db_page FROM intra_pri_role_assigment WHERE code='".$service_type."' AND sub_menu ='".$sub_menu."' ");
	//print_r($service_type);
  // exit;
	// Check activte_status == 2 if not populating in Order Page
	$update_forwarding = $db->update("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2', remarks='".$remarks."', for_app_rej='".$for_app_rej_status."', officer_info='".$officer_info."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
	
	
	
	if($service_type == '3'){
		$update_service = $db->update("UPDATE ".$table[0]['db_page']." SET status='2' WHERE application_id='".$application_id."' AND status= '1' ");
	}
	else if($service_type == '2' || $service_type == '1'){
		$update_service = $db->update("UPDATE ".$table[0]['db_page']." SET status='2' WHERE application_id='".$application_id."'  ");
	}
	else{
		$update_service = $db->update("UPDATE ".$table[0]['db_page']." SET active_status='2' WHERE application_id='".$application_id."' AND active_status= '1' ");
	}
	$update_forwarding = true; // For testing...
	//$update_service = true;
	if($update_forwarding == true && $update_service == true){
		if($service_type == '3'){
			$emp_id_detail_order = $db->fetch_table(" SELECT * FROM ".$table[0]['db_page']." WHERE application_id='".$application_id."' AND status= '2' ");
		}
		else if($service_type == '2' || $service_type == '1'){
			$emp_id_detail_order = $db->fetch_table(" SELECT * FROM ".$table[0]['db_page']." WHERE application_id='".$application_id."' AND status= '2' ");
		}
		else{
			$emp_id_detail_order = $db->fetch_table(" SELECT * FROM ".$table[0]['db_page']." WHERE application_id='".$application_id."' AND active_status= '2' ");
		}	
		
		if($emp_id_detail_order[0]['emp_sex'] == '91'){ $sex ='Sri.'; } else if($emp_id_detail_order[0]['emp_sex'] == '92'){ $sex ='Smt.'; }
		if($emp_id_detail_order[0]['emp_caste'] == 104)
		{
			 $sex ='';
		}
		
		$emp_name = $sex.' '.$emp_id_detail_order[0]['emp_first_name'].' '.$emp_id_detail_order[0]['emp_second_name'].' '.$emp_id_detail_order[0]['emp_last_name'];
		
		$emp_desig = $db->fetch_table("select description from prd_dise_code_master where code='".$emp_id_detail_order[0]['emp_desig']."' order by code");
		
		if($emp_id_detail_order[0]['gp_id_fk'] !='' && $emp_id_detail_order[0]['gp_id_fk'] !='0'){
			$Query = "SELECT *  FROM prd_location_master_gp
			              WHERE gp_id_pk='".$emp_id_detail_order[0]['gp_id_fk']."'";
			$arr_desig =$db->fetch_table($Query);
			
			$arr_desig_block =$db->fetch_table("SELECT *  FROM prd_location_master_block WHERE block_id_pk='".$arr_desig[0]['block_id_fk']."'");
			
			$arr_desig_district =$db->fetch_table("SELECT *  FROM prd_location_master_district WHERE district_id_pk='".$arr_desig_block[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['gp_name'].' GP';
			$pri_type = 'GP';
			$district_name = $arr_desig_district[0]['district_name'];
			$candidate_Zp = $arr_desig_block[0]['block_name'].' Dev. Block, '.$arr_desig_district[0]['district_name'];
			
		}
		else if($emp_id_detail_order[0]['ps_id_fk'] !='' && $emp_id_detail_order[0]['ps_id_fk'] !='0'){
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$emp_id_detail_order[0]['ps_id_fk']."'");
			
			$arr_desig_district =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_id_pk ='".$arr_desig[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['ps_name'].' PS';
			$pri_type = 'PS';
			$candidate_Zp = $district_name = $arr_desig_district[0]['district_name'];
			
		}
		else if($emp_id_detail_order[0]['zp_id_fk'] !='' && $emp_id_detail_order[0]['zp_id_fk'] !='0'){
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_id_pk ='".$emp_id_detail_order[0]['zp_id_fk']."'");
			$arr_desig_name = $arr_desig[0]['district_name'].' ZP';
			$pri_type = 'ZP';
			$candidate_Zp = $district_name = $arr_desig[0]['district_name'];
		}


		$arr_catagory = $db->fetch_table("select description from prd_dise_code_master where code='".$emp_id_detail_order[0]['emp_caste']."' order by code");
		
		if($service_type == '4'){
			$eligableYear = 37;
			$memo_OVG='Intra-PRD/OVG/'.date('d-m-Y').'/'.rand(0000,9999);
            $condon_year = ($emp_id_detail_order[0]['condon_year']>0)?$emp_id_detail_order[0]['condon_year']:0;
            $condon_month = ($emp_id_detail_order[0]['condon_month'] > 0)?$emp_id_detail_order[0]['condon_month'].' Months ':'';
            $condon_days = ($emp_id_detail_order[0]['condon_days'] > 0)?$emp_id_detail_order[0]['condon_days'].' Days':'';
		    $condon_days = ($condon_year).' Years '.$condon_month.$condon_days;
		$Query = "INSERT INTO intra_pri_overage_condonation_order
			( application_id, emp_name, emp_id_const, emp_designation, pri_name, district, emp_dob, category, condon_days, upper_age_limit, pri_type, cc_1_memo_no, emp_sex, candidate_zp)
				VALUES
				('".$emp_id_detail_order[0]['application_id']."',
				'".$emp_name."',
				'".$emp_id_detail_order[0]['emp_id_const']."',
				'".$emp_desig[0]['description']."',
				'".$arr_desig_name."',
				'".$district_name."',
				'".date("Y-m-d",strtotime($emp_id_detail_order[0]['emp_dob']))."',
				'".$arr_catagory[0]['description']."',
				'".$condon_days."',
				'".$eligableYear."',
				'".$pri_type."',
				'".$emp_id_detail_order[0]['emp_first_memo_no']."', '".$emp_id_detail_order[0]['emp_sex']."', '".$candidate_Zp."')";	
				//print_r($Query); exit;
				$order_insert_query_OVG = $db->insert($Query);	
				
		
				
				
				if($order_insert_query_OVG){ 
					echo "Application Approved Successfully.";
					$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Approved Successfully.</strong></div>';
				}else{ 
					echo "Application Approved Failed.";
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Approved Failed.</strong></div>';
				}
								
		}
		
		//var_dump($service_type);
		
		else if($service_type == '3'){
            //print_r($emp_id_detail_order); exit;
            $applicantName = $emp_id_detail_order[0]['applicant_name'];
            $incumbent_post = $_POST['incumbent_post'];
			$order_insert_query_CG = $db->insert("INSERT INTO intra_pri_cg_order( application_id, emp_name, emp_id_const,incumbent, incumbent_post)
				VALUES
				('".$emp_id_detail_order[0]['application_id']."',
				'".$emp_name."',
				'".$emp_id_detail_order[0]['emp_id_const']."',
				'".$applicantName."',
				'".$incumbent_post."')"
			    );
				
				
				if($order_insert_query_CG){ 
					echo "Application Approved Successfully.";
					$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Approved Successfully.</strong></div>';
				}else{ 
					echo "Application Approved Failed.";
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Approved Failed.</strong></div>';
				}
				
			}
			
		else if($service_type == '2' || $service_type == '1'){

//var_dump($emp_id_detail_order); die;
			foreach($emp_id_detail_order as $key => $val){
				if($val['sex'] == '91'){ $sex_DT ='Mr.'; } else if($val['sex'] == '92'){ $sex_DT ='Mrs.'; }
				
				if($val['pre_gp_id_fk'] != 0 ){ 
				$present_gp = code_gp($val['pre_gp_id_fk'] ).' GP';
				$present_block = code_block($val['pre_block_id_fk'] ).' Block';
				
					$present_desig = fun_desig($val['desig']);
					$gp_ps_zp_dist = $db->fetch_table("select dist.district_name as district_name from prd_location_master_gp as gp 
					INNER JOIN prd_location_master_block as block ON gp.block_id_fk = block.block_id_pk 
					INNER JOIN prd_location_master_district as dist ON block.district_id_fk = dist.district_id_pk
					where gp.gp_id_pk ='".$val['pre_gp_id_fk']."' ");
				}
				else if ($val['pre_ps_id_fk'] != 0 ){ $present_ps = code_ps($val['pre_ps_id_fk'] ).' PS';
					$present_desig = fun_desig($val['desig']);
					$gp_ps_zp_dist = $db->fetch_table("select dist.district_name as district_name from prd_location_master_panchayat_samiti as ps 
					INNER JOIN prd_location_master_district as dist ON ps.district_id_fk = dist.district_id_pk
					where ps.ps_id_pk ='".$val['pre_ps_id_fk']."' ");
				}
				else if ($val['pre_district_id_fk'] != 0 ){ $present_zp = code_district($val['pre_district_id_fk'] ).' ZP';
					$present_desig = fun_desig1($val['desig']);
					$gp_ps_zp_dist = code_district($val['pre_district_id_fk'] );			
				}
				
				
				
				if($val['transfer_gp_id_fk'] != 0 ){ $new_gp = code_gp($val['transfer_gp_id_fk'] ).' GP';
					$new_block = code_block($val['transfer_block_id_fk'] ).' Block'; }
					
				else if ($val['transfer_ps_id_fk'] != 0 ){ $new_ps = code_ps($val['transfer_ps_id_fk'] ).' PS'; }
				else if ($val['transfer_district_id_fk'] != 0 ){ $new_zp = code_district($val['transfer_district_id_fk'] ).' ZP'; }
				
				
				$emp_name_DT = $sex_DT.' '.$val['emp_first_name'].' '.$val['emp_second_name'].' '.$val['emp_last_name'];
				/*
				$order_insert_query_DT = $db->insert("INSERT INTO intra_pri_district_transfer_order( application_id, emp_name, emp_id_const, pre_gp, pre_block, pre_district, new_gp, designation, new_block, pre_ps, new_ps, new_district )
				VALUES
				('".$val['application_id']."',
				'".$emp_name_DT."',
				'".$val['emp_id_const']."', '".$present_gp."', '".$present_block."', '".$gp_ps_zp_dist[0]['district_name']."', '".$new_gp."', '".$present_desig."', '".$new_block."', '".$present_ps."', '".$new_ps."', '".$new_zp."')");*/
			}
				
				if($update_forwarding){ 
					echo "Application Approved Successfully.";
					$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Approved Successfully.</strong></div>';
				}else{ 
					echo "Application Approved Failed.";
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Approved Failed.</strong></div>';
				}
				
			}
			
			else if($service_type == '5' && $sub_menu == '5'){
				
				foreach($emp_id_detail_order as $key => $val){
					
					
					$District = code_district($val['zp_id_fk'] );

					switch($val['posting'])
					 {
					 	 case 'GP':
					 	 $gp_ps_zp_dist = code_gp($val['gp_id_fk']);
					 	 break;
					 	 case 'PS':
					 	 $gp_ps_zp_dist = code_ps($val['ps_id_fk']);
					 	 break;
					 	 case 'ZP':
					 	 $gp_ps_zp_dist = $District;
					 	 break;					 	 					 	 
					 }
					$present_desig = fun_desig($val['sanctioned_post_name']);
					$remuneration = $_POST['remunaration'];
					//$memo_9008 = '9008/GP/01';
					$memo_9008='INTRA-PRD/9008/'.date('d-m-Y').'/'.rand(0000,9999);
					
				//$emp_name_9008 = $sex_9008.' '.$val['emp_first_name'].' '.$val['emp_second_name'].' '.$val['emp_last_name'];
				$Query = "INSERT INTO intra_pri_9008_order( application_id, emp_name, engagged_office, engagged_date, sanctioned_post, remuneration, memo_no, candidate_zp, candidate_gppszp)
				VALUES
				('".$val['application_id']."', '".$emp_name."',
				'".$val['posting']."', '".$val['date_engagement']."', '".$present_desig."', '".$remuneration."', '".$memo_9008."', 
				'".$District."','".$gp_ps_zp_dist."')";
				//echo $Query; exit;
				$order_insert_query_9008 = $db->insert($Query);
				}
				if($order_insert_query_9008){ 
					echo "Application Approved Successfully.";
					$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Approved Successfully.</strong></div>';
				}else{ 
					echo "Application Approved Failed.";
					$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Approved Failed.</strong></div>';
				}
			}
	}						

}


?>