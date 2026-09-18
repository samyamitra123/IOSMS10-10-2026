    <?php
   //echo 11;die;
    session_start();
    error_reporting(1);
    
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
    $crypto=new cryptography();
   
    $db=new database();
    if($sec_time_token!=$enc_session)
    {
    
    $_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
    header('Location:general_transfer.php');
    }
    else
    {
	 $proposal=$_POST['proposal'];
	 $emp_stack=$_POST['emp_stack']; 
     $dis_stack=$crypto->decode($_POST['dis_stack'],4);
	 $pre_district_id_fk=$crypto->decode($_POST['pre_district_id_fk'],4); 
     $pre_gp_id_fk=$crypto->decode($_POST['gp_code'],4); 
	 $pre_ps_id_fk=$crypto->decode($_POST['ps_code'],4); 
	 $emp_id_const_gp=$crypto->decode($_POST['employee_id'],4); 
	 $emp_id_cons_ps=$crypto->decode($_POST['employee_id_ps'],4); 
	 $fileInArray = $_FILES;
	 $emp_id_const = ($emp_stack=='GP' || $emp_stack=='ZP')?$emp_id_const_gp:$emp_id_cons_ps;
	  

	  
	 $user=$_SESSION['user_info']['stake_user_code'];
	
	if($pre_gp_id_fk!='')
	{
		
		//echo 1222; die;
		$pre_ps_id_fk='0';
		$pre_gp_id_fk=$crypto->decode($_POST['gp_code'],4);
		 $pre_block_id_fk=$crypto->decode($_POST['block'],4); 
	}
	else if($pre_ps_id_fk!='')
	{
		//echo 2222; die;
		 $pre_ps_id_fk=$crypto->decode($_POST['ps_code'],4); 
		//$pre_ps_id_fk='0';
		$pre_gp_id_fk='0';
		 $pre_block_id_fk='0';
	}
	else
	{
		$pre_ps_id_fk='0'; 
		//$pre_ps_id_fk='0';
		$pre_gp_id_fk='0';
		 $pre_block_id_fk='0';
	}
	 

	 $file_flag=$crypto->decode($_POST['f'],4);   
	 $app_id='';
	 //print_r($emp_id_const); exit;
	 $emp_fetch =$db->fetch_table("SELECT emp_first_name,emp_second_name,emp_last_name,emp_dob,emp_sex,emp_join_prsnt_office_date,emp_desig,emp_first_join_date,emp_join_prsnt_post_date,emp_first_memo_no FROM prd_employee_master WHERE emp_id_const='".$emp_id_const."' ");
	
	$tch_fname=$emp_fetch[0]['emp_first_name'];
	$tch_mname=$emp_fetch[0]['emp_second_name'];
	$tch_lname=$emp_fetch[0]['emp_last_name'];
	$tch_dob=$emp_fetch[0]['emp_dob'];
	$vice_desig=$emp_fetch[0]['emp_desig'];
	 $sex=$emp_fetch[0]['emp_sex'];
	 $emp_first_memo_no=$emp_fetch[0]['emp_first_memo_no'];
	  $first_join_date=$emp_fetch[0]['emp_first_join_date']; 
	 $emp_join_prsnt_post_date=$emp_fetch[0]['emp_join_prsnt_office_date'];
	 

$Query = "INSERT INTO intra_pri_district_transfer
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
			emp_join_prsnt_post_date,
			pre_gp_id_fk,
			pre_ps_id_fk,
			pre_block_id_fk,
			from_status,
			stack_user,
			district_level,
			pre_district_id_fk
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
			'".$emp_join_prsnt_post_date."',
			'".$pre_gp_id_fk."',
			'".$pre_ps_id_fk."',
			'".$pre_block_id_fk."',
			'1',
			'".$user."',
			'".$dis_stack."',
			'".$pre_district_id_fk."'
			
			) RETURNING app_id as id";

            //$query = $db->insert($Query);
            $appId = $db->insert_id($Query);
		    $InsertQuery = array();								
			 if(isset($fileInArray['file']['name']) && count($fileInArray['file']['name']) > 0 && $appId > 0)
			 {
			 	foreach($fileInArray['file']['name'] as $key=>$value){
			 		if($fileInArray['file']['type'][$key] == 'application/pdf')
			 		{
					 	$rand=rand(111111, 999999); 
					 	$fileLocation = ($dis_stack == 'ID')?'within_district':'outhside_district';
					 	$filePath = '../../readwrite/intra_pri_upload/'.$fileLocation.'/'.$rand.$fileInArray['file']['name'][$key];
					 	
					 	move_uploaded_file($fileInArray['file']['tmp_name'][$key], $filePath);

					 	$InsertQuery[] = "('".$appId."','".$key."','".$rand.$fileInArray['file']['name'][$key]."','1','".$emp_id_const."' )";		 			
			 		}
			 	}

			 }
			 if(count($InsertQuery) > 0)
			 {
			 	$InsertQuery = "INSERT INTO intra_pri_file_upload (application_id, flag, file_name,status,emp_id_const) values ".implode(',', $InsertQuery);
			 	$query = $db->insert($InsertQuery);
			 }			
			
			
			if($appId)
			{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Employee Submitted Successfully...</strong></div>';
			header('Location:general_transfer.php?dis='.$_POST['dis_stack']);
			}
			else
			{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Employee Submitted failed...</strong></div>';
			header('Location:general_transfer.php?dis='.$_POST['dis_stack']);
			}
	 
	 
	}
   ?>