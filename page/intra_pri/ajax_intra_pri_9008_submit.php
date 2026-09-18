<?php
//echo 11;die;
session_start();
error_reporting(0);
  
ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../all_function/fun_store/ifms_functions.php';
//echo 23;die;

$crypto=new cryptography();

 if($_SERVER['HTTP_REFERER']==''){
 	header("Location:dashboard_intra_pri.php");
}
// else{
// 	header("Location:dashboard_intra_pri.php");
// }

//$cryptoGraph=new cryptography();

  //print_r($_POST); exit;
  //$formData = array();
  $form_flage=$crypto->decode($_POST['form_flage'],4); 
  $myForm_type=$crypto->decode($_POST['myForm_type'],4); 
  $id_9008=$crypto->decode($_POST['9008_id'],4); 
  $application_id_9008 =$crypto->decode($_POST['application_id'],4); 
  $place_of_posting = $_POST['place_of_posting'];
  $tch_fname = strtoupper($_POST['tch_fname']);
  $tch_mname = strtoupper($_POST['tch_mname']);
  //if($tch_mname==""){$tch_mname1="null";}else{ $tch_mname1= $tch_mname;} 
  $tch_lname =  strtoupper($_POST['tch_lname']);
  $date_of_birth = date("Y-m-d",strtotime($_POST['date_of_birth']));
  $tch_doe = date("Y-m-d",strtotime($_POST['tch_doe']));
  $drp_engaged = $_POST['drp_engaged'];
  $sanctioned_vacant_post = $_POST['sanctioned_vacant_post'];
  $name_of_post = $_POST['name_of_post1'];
  //if($sanctioned_vacant_post=="0"){$name_of_post1="00";}else{ $name_of_post1= $name_of_post;} 
  $date_of_vacancy= date("Y-m-d",strtotime($_POST['date_of_vacancy'])); //$_POST['date_of_vacancy'];
  if($sanctioned_vacant_post=="0"){$date_of_vacancy1="01-01-0001";}else{ $date_of_vacancy1= $date_of_vacancy;}
  $amount_of_remuneration= $_POST['amount_of_remuneration'];
  $still_working =$_POST['still_working'];
  $source_of_fund =$_POST['source_of_fund'];
  $noted=$_POST['noted'];
  $gp_id_fk = ($_POST['gp_id_fk'] == '')?0:$_POST['gp_id_fk'];
  $ps_id_fk = ($_POST['ps_id_fk'] == '')?0:$_POST['ps_id_fk'];

  $observation=$_POST['observation'];
  $updateId = isset($_POST['updateId'])?$crypto->decode($_POST['updateId'],4):0;
  //print($myForm_type); exit;
  
  
  $block_zp=$_POST['block_zp'];
  $gp_ps_stack=$_POST['gp_ps_stack'];
  
  if($gp_ps_stack =='GP'){
	  $gp_ps_zp=$_POST['gp_code'];
  }
  else if($gp_ps_stack =='PS'){
	  $gp_ps_zp=$_POST['ps_code'];
  }
  else if($gp_ps_stack =='ZP'){
	  $gp_ps_zp=$_POST['ps_code'];
  }
  
  //$gp_ps_zp=$_POST['gp_ps_zp'];
  $casual_daily_contractual=$_POST['casual_daily_contractual'];
  if($_POST['san_post'] != ''){ $san_post=$_POST['san_post']; }
  else if($_POST['san_post1'] != ''){ $san_post=$_POST['san_post1']; }
  else if($_POST['san_post2'] != ''){ $san_post=$_POST['san_post2']; }
 
  
  $cat_post=$_POST['cat_post'];
  $period_eng=$_POST['period_eng'];
  $remuneration=$_POST['remuneration'];
  $dt_join=date("Y-m-d",strtotime($_POST['dt_join']));
  $dept_order=$_POST['dept_order'];
  
  
  
  $gp_ps_stack_B=$_POST['gp_ps_stack'];
  $gp_ps_zp_B=$_POST['gp_ps_zp_B'];
  $app_cas_day_con_worker=$_POST['app_cas_day_con_worker'];
  $present_remuneration=$_POST['present_remuneration'];
  $enhanced_remuneration=$_POST['enhanced_remuneration'];
  
  
  
  $gp_ps_stack_C=$_POST['gp_ps_stack_C'];
  $gp_ps_zp_C=$_POST['gp_ps_zp_C'];
  $app_cas_day_con_worker_C=$_POST['app_cas_day_con_worker_C'];
  $duration_C=$_POST['duration_C'];
  $calculation=$_POST['calculation'];
  
  
  
  $delete_id=$crypto->decode($_POST['delete_id'],4); 
  $delete_f=$crypto->decode($_POST['delete_f'],4); 
  
   //if($source_of_fund=="0"){$noted1="00";}else{$noted1= $noted;}
	//$all_supporting_documents=$_POST['all_supporting_documents'];
  //$id_auto= ("9008".$tch_fname.$tch_mname.$tch_lname.rand().date("Y")); 
   $flag="9008";


	$service_in_each_year = $_POST['service_in_each_year'];
	$service_in_each_days = $_POST['service_in_each_days'];
	//var_dump($service_in_each_year);
	//var_dump($service_in_each_days); die;

//print_r($myForm_type); exit;

$db=new database();

if($myForm_type=="myForm_addi_A"){
	
	if($updateId >0)
	{
      //print_r($_POST); exit;
      $UpdateArray = array();
      $UpdateArray[] = "block_name='".$block_zp."'";
      $UpdateArray[] = "gp_ps_zp_stake='".$gp_ps_stack."'";
      $UpdateArray[] = "gp_ps_zp_code='".$gp_ps_zp."'";
      $UpdateArray[] = "worker_name='".$casual_daily_contractual."'";
      $UpdateArray[] = "sanctioned_post_code='".$san_post."'";
      $UpdateArray[] = "post_category='".$cat_post."'";
      $UpdateArray[] = "period_engagement='".$period_eng."'";
      $UpdateArray[] = "present_remuneration='".$remuneration."'";
      $UpdateArray[] = "approval_order_memo_no='".$dept_order."'";
      $UpdateArray[] = "application_id='".$_POST['app_no_A']."'";
      $updateQuery = 'UPDATE entry_format_9008 SET '.implode(', ',$UpdateArray).'WHERE entry_format_id_pk='.$updateId;
      if($db->update($updateQuery))
      {
         $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
         unset($_SESSION['user_info']['application_id']);
			   header('Location:intre_pri_9008_form.php?val=myForm_addi_A');      	
      }
      //print_r($updateQuery); exit;
     // $UpdateArray[] = 'active_status='.$block_zp;
	}
	else
	{	
	$application_id = application_id($flag,$casual_daily_contractual);
	
	$Query = "INSERT INTO entry_format_9008
						( block_name, gp_ps_zp_stake, gp_ps_zp_code, worker_name, sanctioned_post_code, post_category,
						period_engagement, present_remuneration, joining_date, approval_order_memo_no, application_id, active_status )
							VALUES
								('".$block_zp."',
								'".$gp_ps_stack."',
								'".$gp_ps_zp."',
								'".$casual_daily_contractual."',
								'".$san_post."', 
								'".$cat_post."', 
								'".$period_eng."', 
								'".$remuneration."', 
								'".$dt_join."', '".$dept_order."','".$application_id."','0' )";
		//print_r($Query); exit;
		$myForm_addi_A_insert = $db->insert($Query);						
								
	if($myForm_addi_A_insert)
		{
			
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_A');
			$_SESSION['user_info']['application_id'] = $application_id;
			//$_SESSION['data'] = $formData;
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Submitted failed...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_A');
		}
	}	

		
}



else if($myForm_type=="myForm_addi_B"){

	if($updateId >0)
	{
      //print_r($_POST); exit;
      $UpdateArray = array();
      $UpdateArray[] = "gp_ps_zp_stake='".$gp_ps_stack_B."'";
      $UpdateArray[] = "gp_ps_zp_code='".$gp_ps_zp_B."'";
      $UpdateArray[] = "worker_name='".$app_cas_day_con_worker."'";
      $UpdateArray[] = "present_remuneration='".$present_remuneration."'";
      $UpdateArray[] = "enhanced_remuneration='".$enhanced_remuneration."'";
      $UpdateArray[] = "application_id='".$_POST['app_no_B']."'";


      $updateQuery = 'UPDATE enhancement_remuneration_9008 SET '.implode(', ',$UpdateArray).'WHERE enhancement_remuneration_9008_id_pk='.$updateId;
      if($db->update($updateQuery))
      {
         $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
         unset($_SESSION['user_info']['application_id']);
			   header('Location:intre_pri_9008_form.php?val=myForm_addi_B');      	
      }
      //print_r($updateQuery); exit;
     // $UpdateArray[] = 'active_status='.$block_zp;
	}
	else
	{		
	$application_id = application_id($flag,$app_cas_day_con_worker);
	
	$myForm_addi_B_insert = $db->insert("INSERT INTO enhancement_remuneration_9008
						( gp_ps_zp_stake, gp_ps_zp_code, worker_name, present_remuneration, enhanced_remuneration, application_id, active_status )
							VALUES
								('".$gp_ps_stack_B."',
								'".$gp_ps_zp_B."',
								'".$app_cas_day_con_worker."',
								'".$present_remuneration."',
								'".$enhanced_remuneration."', 
								'".$application_id."','0' )");
								
								
	if($myForm_addi_B_insert)
		{
			
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_B');
			$_SESSION['user_info']['application_id'] = $application_id;
			//$_SESSION['data'] = $formData;
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Submitted failed...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_B');
		}
	}

		
}




else if($myForm_type=="myForm_addi_C"){

	if($updateId >0)
	{
      //print_r($_POST); exit;
      $UpdateArray = array();
      $UpdateArray[] = "gp_ps_zp_stake='".$gp_ps_stack_C."'";
      $UpdateArray[] = "gp_ps_zp_code='".$gp_ps_zp_C."'";
      $UpdateArray[] = "worker_name='".$app_cas_day_con_worker_C."'";
      $UpdateArray[] = "duration='".$duration_C."'";
      $UpdateArray[] = "auto_calculation='".$calculation."'";
      $UpdateArray[] = "application_id='".$_POST['app_no_C']."'";


      $updateQuery = 'UPDATE fund_requisition_9008 SET '.implode(', ',$UpdateArray).'WHERE enhancement_remuneration_9008_id_pk='.$updateId;
      if($db->update($updateQuery))
      {
         $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
         unset($_SESSION['user_info']['application_id']);
			   header('Location:intre_pri_9008_form.php?val=myForm_addi_C');      	
      }
      //print_r($updateQuery); exit;
     // $UpdateArray[] = 'active_status='.$block_zp;
	}
	else
	{			
	$application_id = application_id($flag,$app_cas_day_con_worker_C);
	
	$myForm_addi_C_insert = $db->insert("INSERT INTO fund_requisition_9008
						( gp_ps_zp_stake, gp_ps_zp_code, worker_name, duration, application_id, active_status, auto_calculation )
							VALUES
								('".$gp_ps_stack_C."',
								'".$gp_ps_zp_C."',
								'".$app_cas_day_con_worker_C."',
								'".$duration_C."',
								'".$application_id."','0','".$calculation."' )");
								
								
	if($myForm_addi_C_insert)
		{
			
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_C');
			$_SESSION['user_info']['application_id'] = $application_id;
			//$_SESSION['data'] = $formData;
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Submitted failed...</strong></div>';
			header('Location:intre_pri_9008_form.php?val=myForm_addi_C');
		}
		
}
		
}



else if($myForm_type=="myForm"){
	
	
	//print($updateId); exit;
 	if($updateId > 0)
	{
		$selectQuery = 'SELECT application_id from intra_pri_9008 WHERE id_pk='.$updateId;
      $appDetails = $db->fetch_table($selectQuery);
      $applicationId = $appDetails[0]['application_id'];
      //print_r($applicationId); exit;
      if($applicationId != '')
      {

      	$Query = "DELETE FROM intra_pri_service_each_year_9008 WHERE application_id='".$applicationId."'";
      	$db->update($Query);
      	//print($Query); exit;
			for($i=0; $i<count($service_in_each_year); $i++ ){
				//$db=new database();
			$Query = "INSERT INTO intra_pri_service_each_year_9008
								( year, days, application_id )
									VALUES('".$service_in_each_year[$i]."', '".$service_in_each_days[$i]."', '".$applicationId."' )";	
			//print($Query); exit;
			$insert_query_year = $db->insert($Query);
			
			}   
			//print($Query); exit;   	
      }
     /* print_r($appDetails); exit;
		print_r($service_in_each_year); 
      print_r($service_in_each_days);
		exit; */
		$UpdateArray = array();
      $UpdateArray[] = "posting='".$place_of_posting."'";
      $UpdateArray[] = "emp_first_name='".$tch_fname."'";
      $UpdateArray[] = "emp_second_name='".$tch_mname."'";
      $UpdateArray[] = "emp_last_name='".$tch_lname."'";
      $UpdateArray[] = "emp_dob='".$date_of_birth."'";
      $UpdateArray[] = "date_engagement='".$tch_doe."'";
      $UpdateArray[] = "whether_engagged='".$drp_engaged."'";
      $UpdateArray[] = "sanctioned_vacant_post='".$sanctioned_vacant_post."'";
      $UpdateArray[] = "sanctioned_post_name='".$name_of_post."'";
      $UpdateArray[] = "occurence_vacancy_date='".$date_of_vacancy1."'";
      $UpdateArray[] = "remuneration='".$amount_of_remuneration."'";
      $UpdateArray[] = "still_working='".$still_working."'";
      $UpdateArray[] = "source_of_fund='".$source_of_fund."'";
      $UpdateArray[] = "note='".$noted."'";
      $UpdateArray[] = "gp_id_fk='".$gp_id_fk."'";
      $UpdateArray[] = "ps_id_fk='".$ps_id_fk."'";
      $UpdateArray[] = "zp_id_fk='".$_SESSION ['user_info'] ['district_id_fk']."'";
      $updateQuery = 'UPDATE intra_pri_9008 SET '.implode(', ',$UpdateArray).' WHERE id_pk='.$updateId;


      
      if($db->update($updateQuery))
      {

         $Query = "SELECT count(*) from intra_pri_forwarding WHERE application_id='".$applicationId."'"; 
         $forwardApp  = $db->fetch_table($Query);
         //print_r($forwardApp); exit;
         if($forwardApp[0]['count'] == 0)
         {
         	$insertQuery = "INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu)
			VALUES ('".$applicationId."', '".$_SESSION['user_info']['officer_id_const']."',1,'5', '0','5')";
				$db->insert($insertQuery);
				//$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
				//header('Location:page/intra_pri/view_intra_pri_service.php');
         }
         //print_r($forwardApp); exit;
         $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
        // unset($_SESSION['user_info']['application_id']);
			   header('Location:view_intra_pri_service.php');
			   exit;    	
      }  
      //print_r($db); exit;   
      
	}
	else
	{	
		$application_id = application_id($flag,$tch_fname);
		if($form_flage=="partA")
		 {							
		
			 	$query = "INSERT INTO intra_pri_9008
									( posting, emp_first_name, emp_second_name, emp_last_name, emp_dob, date_engagement,
									whether_engagged, sanctioned_vacant_post, sanctioned_post_name, occurence_vacancy_date, remuneration, still_working, source_of_fund, note,
									application_id, active_status, gp_id_fk, ps_id_fk, zp_id_fk )
										VALUES
											('".$place_of_posting."',
											'".$tch_fname."',
											'".$tch_mname."',
											'".$tch_lname."',
											'".$date_of_birth."',
											'".$tch_doe."',
											'".$drp_engaged."',
											'".$sanctioned_vacant_post."',
											'".$name_of_post."',
											'".$date_of_vacancy1."',
											'".$amount_of_remuneration."',
											'".$still_working."', 
											'".$source_of_fund."', 
											'".$noted."', '".$application_id."','0',$gp_id_fk,$ps_id_fk,'".$_SESSION ['user_info'] ['district_id_fk']."' )";
				 //print_r($query); exit;

		      $insert_query = $db->insert($query);
				if($insert_query == true){
					for($i=0; $i<count($service_in_each_year); $i++ ){
						$db=new database();
						
						$insert_query_year = $db->insert("INSERT INTO intra_pri_service_each_year_9008
										( year, days, application_id )
											VALUES('".$service_in_each_year[$i]."', '".$service_in_each_days[$i]."', '".$application_id."' )");
					
					}
				}


			if($insert_query && $insert_query_year)
					{
						
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';
						header('Location:intre_pri_9008_form.php?val=myForm');
						$_SESSION['user_info']['application_id'] = $application_id;
						//$_SESSION['data'] = $formData;
					}
					else
					{
						$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Submitted failed...</strong></div>';
						header('Location:intre_pri_9008_form.php?val=myForm');
					}
			}			

	    

	   else if($form_flage=="partB")
	   {	
	      $db=new database();
	  	   $update_oc = $db->update(" UPDATE intra_pri_9008 SET observation= '".$observation."' WHERE application_id = '".$application_id_9008."' AND active_status='0' ");
		   if($update_oc)
			  {
				
				$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
				header('Location:intre_pri_9008_form.php?val=myForm');
			  }
			else
			  {
				$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Updated failed...</strong></div>';
				header('Location:intre_pri_9008_form.php?val=myForm');
			  }
		  }
	}  

}




 if($delete_id=="delete")
			{	//echo ("UPDATE intra_pri_file_upload SET status='0' WHERE application_id='".$application_id_9008."' and flag='".$delete_f."' and status='1'"); die;
		   $Query = "UPDATE intra_pri_file_upload SET status='0' WHERE application_id='".$application_id_9008."' and flag='".$delete_f."' and status='1'";
		   //print($Query); exit;
			$query=$db->update($Query);
			//$_SESSION['user_info']['emp_id_const'] = $emp_id_const_del;
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>DOCUMENT SUCCESSFULLY REVOMED </strong></div>';
					header('Location:intre_pri_9008_form.php?val=myForm');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> DOCUMENT REMOVED FAILED.</strong></div>';
					header('Location:intre_pri_9008_form.php?val=myForm');
				}
			}
			
			
?>

  
