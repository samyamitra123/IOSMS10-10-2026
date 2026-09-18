<?
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';
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

 $officer_id_const= $_POST['officer_id_const']; 
$application_id= $_POST['application_id'];
$remarks= $_POST['remarks'];
$service_type= $_POST['service_type'];
$for_app_rej_status= $_POST['for_app_rej_status'];
$emp_id_const = $_POST['emp_id_const'];
$sub_menu = $_POST['sub_menu'];

//var_dump($for_app_rej_status); die;

if($_POST['type']== 'submit_app'){  
	
	
	$db=new database();
	/*echo ("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2', remarks='".$remarks."', for_app_rej='".$for_app_rej_status."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");die;*/
	$update=$db->update("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2', remarks='".$remarks."', for_app_rej='".$for_app_rej_status."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
	
	if($update){
		
	$insert_query = $db->insert("INSERT INTO intra_pri_forwarding
						( application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu )
							VALUES
								('".$application_id."',
								'".$officer_id_const."',
								'1',
								'".$service_type."',
								'".$emp_id_const."',
								'".$sub_menu."')");
								
								
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
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Forwareded Failed.</strong></div>';
		//header('location:view_intra_pri_service.php');
		//exit(0);

	}
}

//else if($cryptoGraph->decode($_GET['action'],4)=='reject'){
	else if($_POST['type']== 'submit_rej'){ 
	$db=new database();
	
	$update=$db->update("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2',remarks='".$remarks."', for_app_rej='".$for_app_rej_status."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1'");
	
	if($update){
		
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
	}						
	if($update && $insert_query){
		
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



else if($_POST['type']== 'final_approval'){  
	$db=new database();
	
	$update_forwarding = $db->update("UPDATE intra_pri_forwarding SET to_officer_id_const='".$officer_id_const."',status='2', remarks='".$remarks."', for_app_rej='".$for_app_rej_status."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
	
	$table= $db->fetch_table("SELECT db_page FROM intra_pri_role_assigment WHERE code='".$service_type."'");
	
	$update_ovg = $db->update("UPDATE ".$table[0]['db_page']." SET active_status='2' WHERE application_id='".$application_id."' AND active_status= '1' ");
	
	if($update_forwarding == true && $update_ovg == true){
		
		$emp_id_detail_order = $db->fetch_table(" SELECT * FROM ".$table[0]['db_page']." WHERE application_id='".$application_id."' AND active_status= '2' ");
		
		if($emp_id_detail_order[0]['emp_sex'] == '91'){ $sex ='Mr.'; } else if($emp_id_detail_order[0]['emp_sex'] == '92'){ $sex ='Mrs.'; }
		
		$emp_name = $sex.' '.$emp_id_detail_order[0]['emp_first_name'].' '.$emp_id_detail_order[0]['emp_second_name'].' '.$emp_id_detail_order[0]['emp_last_name'];
		
		$emp_desig = $db->fetch_table("select description from prd_dise_code_master where code='".$emp_id_detail_order[0]['emp_desig']."' order by code");
		
		if($emp_id_detail_order[0]['gp_id_fk'] !='' && $emp_id_detail_order[0]['gp_id_fk'] !='0'){
			$arr_desig =$db->fetch_table("SELECT *  FROM prd_location_master_gp WHERE gp_id_pk='".$emp_id_detail_order[0]['gp_id_fk']."'");
			
			$arr_desig_block =$db->fetch_table("SELECT *  FROM prd_location_master_block WHERE block_id_pk='".$arr_desig[0]['block_id_fk']."'");
			
			$arr_desig_district =$db->fetch_table("SELECT *  FROM prd_location_master_district WHERE district_id_pk='".$arr_desig_block[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['gp_name'].' GP';
			$pri_type = 'GP';
			$district_name = $arr_desig_district[0]['district_name'];
			
		}
		else if($emp_id_detail_order[0]['ps_id_fk'] !='' && $emp_id_detail_order[0]['ps_id_fk'] !='0'){
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$emp_id_detail_order[0]['ps_id_fk']."'");
			
			$arr_desig_district =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_id_pk ='".$arr_desig[0]['district_id_fk']."'");
			
			$arr_desig_name = $arr_desig[0]['ps_name'].' PS';
			$pri_type = 'PS';
			$district_name = $arr_desig_district[0]['district_name'];
			
		}
		else if($emp_id_detail_order[0]['zp_id_fk'] !='' && $emp_id_detail_order[0]['zp_id_fk'] !='0'){
			$arr_desig =$db->fetch_table("SELECT * FROM prd_location_master_district WHERE district_id_pk ='".$emp_id_detail_order[0]['zp_id_fk']."'");
			$arr_desig_name = $arr_desig[0]['district_name'].' ZP';
			$pri_type = 'ZP';
			$district_name = $arr_desig[0]['district_name'];
		}
		
		$arr_catagory = $db->fetch_table("select description from prd_dise_code_master where code='".$emp_id_detail_order[0]['emp_caste']."' order by code");
		
		if($service_type == '4'){
		$condon_days = $emp_id_detail_order[0]['condon_year'].' Year '.$emp_id_detail_order[0]['condon_month'].' Month '.$emp_id_detail_order[0]['condon_days'].' Days';
		
		
		$order_insert_query = $db->insert("INSERT INTO intra_pri_overage_condonation_order
			( application_id, emp_name, emp_id_const, emp_designation, pri_name, district, emp_dob, category, condon_days, upper_age_limit, pri_type, cc_1_memo_no, emp_sex )
				VALUES
				('".$emp_id_detail_order[0]['application_id']."',
				'".$emp_name."',
				'".$emp_id_detail_order[0]['emp_id_const']."',
				'".$emp_desig[0]['description']."',
				'".$arr_desig_name."',
				'".$district_name."',
				'".date("d-m-Y",strtotime($emp_id_detail_order[0]['emp_dob']))."',
				'".$arr_catagory[0]['description']."',
				'".$condon_days."',
				'30',
				'".$pri_type."',
				'".$emp_id_detail_order[0]['emp_first_memo_no']."', '".$emp_id_detail_order[0]['emp_sex']."')");
								
		}
		else if($service_type == '3'){


			}
	}						
	if($order_insert_query){ 
		echo "Application Approved Successfully.";
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Application Forwareded Successfully.</strong></div>';
	}else{ 
		echo "Application Approved Failed.";
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Application Forwareded Failed.</strong></div>';
	}
}


?>