    
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
    if($_SERVER['HTTP_REFERER']==''){
    header("Location:dashboard_intra_pri.php");
    }
    
    $sec_time_token=$_POST['sec_tok']; 
    $session_token=$_SESSION['security_token'];
    $enc_session=md5('369'.$session_token);
    // else{
    // 	header("Location:dashboard_intra_pri.php");
    // }
    
    //$cryptoGraph=new cryptography();
    
    //echo 225;die;
    
    $crypto=new cryptography();
    
    $db=new database();
    if($sec_time_token!=$enc_session)
    {
    
    $_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
    header('Location:intra_pri_intra_district_transfer.php');
    }
    else
    {
    //echo 222; die;
    
    $emp_id_cons_st=$_SESSION['emp_id_const'];
    
    $emp_id_const = $_POST['tch_emp_id'];
    $tch_fname = $_POST['tch_fname'];
    $tch_mname = $_POST['tch_mname'];
    // if($tch_mname==""){$tch_mname1="null";}else{ $tch_mname1= $tch_mname;} 
    $tch_lname =  $_POST['tch_lname'];
    $tch_dob = date("Y-m-d",strtotime($_POST['tch_dob']));
    $sex = $_POST['sex'];
    $vice_desig = $_POST['desig'];
	 $officer_id=$_SESSION['user_info']['officer_id_const'];
    
    $proposal = $_POST['proposal'];
    
    $emp_first_memo_no = $_POST['emp_first_memo_no'];
    $first_join_date = date("Y-m-d",strtotime($_POST['first_join_date']));
    $appoinment_authority = $_POST['appoinment_authority'];
    $notice_date = date("Y-m-d",strtotime($_POST['notice_date']));
    
    
    $gp_id_fk=$_POST['gp_id_fk'];
    $ps_id_fk=$_POST['ps_id_fk'];
    $zp_id_fk=$_POST['zp_id_fk'];
    $edit_status=$crypto->decode($_POST['edit_id'],4);
     $delete_id=$crypto->decode($_POST['delete_id'],4); 
    $form_flage=$crypto->decode($_POST['form_flage'],4);
	 $delete_f=$crypto->decode($_POST['delete_f'],4); 
    //$group='634';
    
    if($_POST['gp_id_fk']!='0' && $_POST['gp_id_fk']!='' )
    {
    $pre_gp_id_fk=$_POST['gp_id_fk']; 
    $pre_ps_id_fk=0;
    $pre_zp_id_fk=0;
    }
    else if($_POST['ps_id_fk']!='0' && $_POST['ps_id_fk']!='')
    {
    $pre_gp_id_fk=0; 
    $pre_ps_id_fk=$_POST['ps_id_fk'];
    $pre_zp_id_fk=0;
    }
    else if($_POST['zp_id_fk']!='0' && $_POST['zp_id_fk']!='')
    {
    $pre_gp_id_fk=0; 
    $pre_ps_id_fk=0;
    $pre_zp_id_fk=$_POST['zp_id_fk'];
    }
    $note=$_POST['note'];
    $all_supporting_documents=$_POST['all_supporting_documents'];
    
    $stake_lvl_select=$_POST['stake_lvl_select']; 
    
    if($stake_lvl_select=='555')
    {
		
		//echo 122; die;
    $tran_ps_code=0;
     $tran_block_id_fk=$crypto->decode($_POST['block_id'],4); 
     $tran_gp_code=$crypto->decode($_POST['gp_code'],4); 
    
    //$tran_gp_code='3299001001';
    }
    else
    {
    $tran_ps_code=$crypto->decode($_POST['ps_code'],4); 
    $tran_block_id_fk=0;
    $tran_gp_code=0;
    }
    
	$application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_district_transfer WHERE emp_id_const = '".$emp_id_cons_st."'");
		
		$app_id=$application_id_check[0]['application_id'];
	
	
    if($form_flage=="partA")
	 {
		 
		
			$application_id_check=$db->fetch_table("SELECT application_id,app_id FROM intra_pri_district_transfer WHERE emp_id_const = '".$emp_id_const."'");
			$application_id=$application_id_check[0]['application_id'];
			if(count($application_id_check)=='1')
			{
			
			$query=$db->update("UPDATE intra_pri_district_transfer SET
			emp_first_name='".$tch_fname."',
			emp_second_name='".$tch_mname."',
			emp_last_name='".$tch_lname."',
			tch_dob='".$tch_dob."', 
			desig='".$vice_desig."',
			sex='".$sex."',
			proposal='".$proposal."' ,
			emp_first_memo_no='".$emp_first_memo_no."' ,
			first_join_date='".$first_join_date."' ,
			appoinment_authority='".$appoinment_authority."' ,
			notice_date='".$notice_date."' ,
			pre_gp_id_fk='".$pre_gp_id_fk."' ,
			pre_ps_id_fk='".$pre_ps_id_fk."' ,
			pre_zp_id_fk='".$pre_zp_id_fk."',
			all_supporting_documents='".$all_supporting_documents."',
			stake_lvl_select='".$stake_lvl_select."',
			tran_gp_code='".$tran_gp_code."',
			tran_block_id_fk='".$tran_block_id_fk."',
			tran_ps_code='".$tran_ps_code."',
			note='".$note."',
			from_status='1'
			WHERE  emp_id_const='".$emp_id_const."'");
			
			
			
				if($query==true)
			{
				
				
				$file_flag=40;  
				//echo $_FILES["file_tran"]["size"]; die;
				if($_FILES["file_tran_up"]["name"] != '' && ($_FILES["file_tran_up"]["size"] != 0) && $file_flag!=''){
				//echo 12222; die;
				$rand=rand(100000, 999999);
				$db=new database();
				
				
				
				$insert_file=$db->insert(" INSERT INTO intra_pri_file_upload
				(application_id, flag, file_name,status,emp_id_const)
				values ('".$application_id."','".$file_flag."','".$rand.$_FILES['file_tran_up']['name']."','1','".$emp_id_const."' ) ");	
				
				}
				
				if($insert_file == 1 && $file_flag=='40'){
				$location = '../../readwrite/intra_pri_upload/intra_transfer/'.$rand.$_FILES["file_tran_up"]["name"];
				move_uploaded_file($_FILES['file_tran_up']['tmp_name'], $location);
				
				
				
				}
  
 
			}
			
			
			
			}
			else
			{
			
			
			$flag="ID";						
			
			if(count($application_id_check)=='1')
				{
				
				//echo 1; die;
				
					if($application_id_check[0]['application_id']!='')
					{
					$application_id=$application_id_check[0]['application_id'];
					}
					else
					{
					$application_id = application_id($flag,$emp_id_const);
					
					$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
					VALUES (
					'".$application_id."'
					)");
					
					}
				
				}
			else
			{
			//echo 2; die;
			
			$application_id = application_id($flag,$emp_id_const);
			
			$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
			VALUES (
			'".$application_id."'
			)");
			
			}		
			
			
			$query=$db->insert("INSERT INTO intra_pri_district_transfer
			(
			emp_id_const,
			emp_first_name,
			emp_second_name,
			emp_last_name,
			tch_dob,
			desig,
			sex,
			proposal,
			emp_first_memo_no,
			first_join_date,
			appoinment_authority,
			notice_date,
			pre_gp_id_fk,
			pre_ps_id_fk,
			pre_zp_id_fk,
			all_supporting_documents,
			stake_lvl_select,
			tran_gp_code,
			tran_block_id_fk,
			tran_ps_code,
			note,
			from_status,
			application_id
			)
			VALUES 
			('".$emp_id_const."',
			'".$tch_fname."',
			'".$tch_mname."',
			'".$tch_lname."',
			'".$tch_dob."',
			'".$vice_desig."',
			'".$sex."',
			'".$proposal."',
			'".$emp_first_memo_no."',
			'".$first_join_date."',
			'".$appoinment_authority."',
			'".$notice_date."',
			'".$pre_gp_id_fk."',
			'".$pre_ps_id_fk."',
			'".$pre_zp_id_fk."',
			'".$all_supporting_documents."',
			'".$stake_lvl_select."',
			'".$tran_gp_code."',
			'".$tran_block_id_fk."',
			'".$tran_ps_code."',
			'".$note."',
			'1',
			'".$application_id."'
			)");
			
		
			
			}
			
			if($query==true)
			{
				
				
				$file_flag=40;  
				//echo $_FILES["file_tran"]["size"]; die;
				if($_FILES["file_tran"]["name"] != '' && ($_FILES["file_tran"]["size"] != 0) && $file_flag!=''){
					//echo 12222; die;
			$rand=rand(100000, 999999);
			$db=new database();
			
			
			
			$insert_file=$db->insert(" INSERT INTO intra_pri_file_upload
			(application_id, flag, file_name,status,emp_id_const,officer_id_const)
			values ('".$application_id."','".$file_flag."','".$rand.$_FILES['file_tran']['name']."','1','".$emp_id_const."','".$officer_id."' ) ");	
					
				}
				
				if($insert_file == 1 && $file_flag=='40'){
				$location = '../../readwrite/intra_pri_upload/intra_transfer/'.$rand.$_FILES["file_tran"]["name"];
				move_uploaded_file($_FILES['file_tran']['tmp_name'], $location);
				
				
				
				}
  
 
			}
			
			
			if($query)
			{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Profile Submitted Successfully...</strong></div>';
			header('Location:intra_pri_intra_district_transfer.php');
			}
			else
			{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Profile Submitted failed...</strong></div>';
			header('Location:intra_pri_intra_district_transfer.php');
			}
}
    
    if($edit_status=="intra_district")
    {
   
    $query_edit=$db->update("UPDATE intra_pri_district_transfer SET 
    from_status='0'
    WHERE 
    emp_id_const='".$_SESSION['emp_id_const']."'");
    
    
    if($query_edit==true)
    {
    $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Intra District Transfer Profile Now redy For Edit ...</strong></div>';
    header('Location:intra_pri_intra_district_transfer.php');
    }
    else
    {
    $_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Intra District Transfer Profile Now Not redy For Edit ......</strong></div>';
    header('Location:intra_pri_intra_district_transfer.php');
    }
	
	}
    
    
    
    if($delete_id=="delete")
    {
    
    
    $query_delete=$db->update("UPDATE intra_pri_file_upload SET 
    status='0'
    WHERE 
    emp_id_const='".$_SESSION['emp_id_const']."' and flag='".$delete_f."' and status='1'");
    
    if($query_delete==true)
    {
    $_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>DOCUMENT SUCCESSFULLY REVOMED </strong></div>';
    header('Location:intra_pri_intra_district_transfer.php');
    }
    else
    {
    $_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> DOCUMENT REMOVED FAILED.</strong></div>';
    header('Location:intra_pri_intra_district_transfer.php');
    }
    }
	
	
	  if($form_flage=="final")
	 {
		 //echo 23; die;
		$update_oc_final=$db->update("UPDATE intra_pri_district_transfer SET 
			from_status='2',status='1'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
			
			if($update_oc_final){
        $insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const)
            VALUES ('".$app_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'2', '".$_SESSION['emp_id_const']."')");
    }
			
				
			
				if($insert_request_forwarding==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>INTRA DISTRICT TRANSFER APPLICATION SUBMITED SUCCESSFULLY </strong></div>';
					header('Location:intra_pri_intra_district_transfer.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>INTRA DISTRICT TRANSFER APPLICATION SUBMITED FAILED.</strong></div>';
					header('Location:intra_pri_intra_district_transfer.php');
				}
	 }
    
    
    
    
    }
    
    ?>