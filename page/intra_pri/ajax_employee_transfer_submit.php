    
    <?php
    //echo 333; die;
    session_start();
    error_reporting(0);
    
    ob_start();
    require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
    require_once '../all_function/fun_store/ifms_functions.php';
	

    $crypto=new cryptography();
   
    
		$db=new database();
		//echo 222; die;
	$emp_id_const=$_GET['emp_id']; 
    $revoke_emp_id=$crypto->decode($_POST['revoke_emp_id'],4);  
	$stack=$_GET['stack']; 
	$block_id_fk=$crypto->decode($_GET['block_id_fk'],4);
	$gp_id_fk=$crypto->decode($_GET['gp_id_fk'],4);  
	$ps_id_fk=$crypto->decode($_GET['ps_id_fk'],4); 
	$tran_district_id_fk=$crypto->decode($_GET['district_id_fk'],4); 
	$reason=$_GET['reason'];
	$proceding=$_GET['proce']; 
	$benifit=$_GET['benifit'];
	
	$stack_final=$crypto->decode($_POST['stack_final'],4); 
	 $stack_final_revoke=$crypto->decode($_POST['stack_final_revoke'],4); 
	 $stack_del_final=$crypto->decode($_POST['stack_del_final'],4);
	 $del_emp_id=$crypto->decode($_POST['del_emp_id'],4);  
	 
	 $stack_approve=$crypto->decode($_POST['stack_approve'],4);
	 $stack_reject=$crypto->decode($_POST['stack_reject'],4);
	  $reject_emp_id=$crypto->decode($_GET['reject_emp_id'],4);
	    $approve_emp_id=$crypto->decode($_GET['approve_emp_id'],4); 
	 $stack_a=$crypto->decode($_GET['stack_a'],4); 
	// print($stack_a); exit;
	  $stack_a_all=$crypto->decode($_GET['stack_a_all'],4);  
	  $approve_app_all=$crypto->decode($_GET['approve_emp_id_all'],4); 
	   
		
		 $user=$_SESSION['user_info']['stake_user_code'];
		 
		 $f_emp_id=$crypto->decode($_GET['f_emp_id'],4);
		  $stack_f=$crypto->decode($_GET['stack_f'],4);
		  
		   $yes_emp_id=$crypto->decode($_GET['yes_emp_id'],4);
		  $stack_yes=$crypto->decode($_GET['stack_yes'],4);
		  
		  $no_emp_id=$crypto->decode($_GET['no_emp_id'],4);
		  $stack_no=$crypto->decode($_GET['stack_no'],4);
		  
		  $ac_emp_id=$crypto->decode($_GET['ac_emp_id'],4);
		  $stack_ac=$crypto->decode($_GET['stack_ac'],4);
	/*if($emp_stack=='GP')
	{
		$emp_id_const=$crypto->decode($_POST['employee_id'],4);
	}*/
	  
	  
	  /*if($emp_stack=='GP')
	  {
		 $emp_id_const=$emp_id_const_gp;
	  }
	  else
	  {
		  $emp_id_const=$emp_id_cons_ps;
	  }
	  
	 $user=$_SESSION['user_info']['stake_user_code'];
	
	if($gp_id_fk!='')
	{
		
		//echo 1222; die;
		$ps_id_fk='0';
		$gp_id_fk=$crypto->decode($_GET['gp_id_fk'],4); 
		$block_id_fk=$crypto->decode($_GET['block_id_fk'],4);
	}
	else
	{
		//echo 2222; die;
		 $pre_ps_id_fk=$crypto->decode($_POST['ps_code'],4); 
		//$pre_ps_id_fk='0';
		$pre_gp_id_fk='0';
		 $pre_block_id_fk='0';
	}*/
	
	 if($stack_ac=='ac')
		{
			
			
		$db=new database();
		
		
		$application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_district_transfer where emp_id_const='".$ac_emp_id."' and from_status='2' and status='3' and district_level='D'");
			
			$application_id_approve=$application_id_check[0]['application_id'];
			
			$update_forwarding = $db->update("UPDATE intra_pri_forwarding SET status='2', for_app_rej='A' WHERE application_id='".$application_id_approve."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='5',
		officer_id_const_approval='".$_SESSION['user_info']['officer_id_const']."'
		WHERE status='3' and  emp_id_const='".$ac_emp_id."'");
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
		
		
		if($stack_ac=='rc')
		{
			
			
		$db=new database();
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='6',
		officer_id_const_approval='".$_SESSION['user_info']['officer_id_const']."'
		WHERE status='3' and  emp_id_const='".$ac_emp_id."'");
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
	
		 if($stack_yes=='yes')
		{
			
			
		$db=new database();
		
		
		
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='3',
		officer_id_const_forward='".$_SESSION['user_info']['officer_id_const']."'
		WHERE status='2' and  emp_id_const='".$yes_emp_id."'");
		
		
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
		
		 if($stack_no=='no')
		{
			
			
		$db=new database();
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='4',
		officer_id_const_forward='".$_SESSION['user_info']['officer_id_const']."'
		WHERE status='2' and  emp_id_const='".$no_emp_id."'");
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
	
		 if($stack_f=='f')
		{
			
			
		$db=new database();
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='2',
		officer_id_const_forward='".$_SESSION['user_info']['officer_id_const']."'
		WHERE from_status='2' and status='1'  emp_id_const='".$f_emp_id."'");
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
	 
	 if($stack_a_all=='approveall')
		{
			
			
		$db=new database();
		
		
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='2',
		officer_id_const_approval='".$_SESSION['user_info']['officer_id_const']."' 
		WHERE from_status='2' and  application_id='".$approve_app_all."'");
		
		$query=$db->update("UPDATE intra_pri_forwarding 
		SET 
		status='2',
		for_app_rej='A',
		from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' 
		WHERE status='1' and  application_id='".$approve_app_all."' and service_type='2'");
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
	 
		if($stack_a=='approve')
		{
			//echo $_SESSION['user_info']['officer_id_const']; die;
			$Query = "SELECT application_id FROM intra_pri_district_transfer where emp_id_const='".$approve_emp_id."' and from_status='2' and status='1' and district_level='ID'";
            //print($query); exit;
			$application_id_check=$db->fetch_table($Query);
			//print_r($Query); exit;
			$application_id_approve=isset($application_id_check[0]['application_id'])?$application_id_check[0]['application_id']:$application_id_check;
			$Query = "UPDATE intra_pri_forwarding SET status='2', for_app_rej='A' WHERE application_id='".$application_id_approve."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ";
			$update_forwarding = $db->update($Query);
			//print($Query); 
			//echo "<hr />";
		$Query = "UPDATE intra_pri_district_transfer SET status='2', officer_id_const_approval='".$_SESSION['user_info']['officer_id_const']."'	WHERE emp_id_const='".$approve_emp_id."' and from_status='2'";
		   //print($Query); exit;
		$query=$db->update($Query);
		
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
		
		if($stack_a=='reject')
		{
			$application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_district_transfer where emp_id_const='".$reject_emp_id."' and from_status='2' and status='1' and district_level='ID'");
			
			$application_id_reject=$application_id_check[0]['application_id'];
			
			$update_forwarding = $db->update("UPDATE intra_pri_forwarding SET status='2', for_app_rej='R' WHERE application_id='".$application_id_reject."' AND from_officer_id_const='".$_SESSION['user_info']['officer_id_const']."' AND status= '1' ");
			
		$db=new database();
		$query=$db->update("UPDATE intra_pri_district_transfer 
		SET 
		status='3',
		officer_id_const_approval='".$_SESSION['user_info']['officer_id_const']."'
		
		WHERE emp_id_const='".$reject_emp_id."' and from_status='2'");
		if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
		}
	
	 
	 if($stack=="ID")
	 {
$db=new database();
$query=$db->update("UPDATE intra_pri_district_transfer 
SET 
from_status='2',
transfer_gp_id_fk='".$gp_id_fk."',
transfer_block_id_fk='".$block_id_fk."',
transfer_ps_id_fk='".$ps_id_fk."',
reason='".$reason."'

 WHERE emp_id_const='".$emp_id_const."' and from_status='1'");
			
			
			
			if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
	 
	 }
	 
	 if($stack=="D")
	 {
		 $db=new database();

$query=$db->update("UPDATE intra_pri_district_transfer 
SET 
from_status='2',
transfer_district_id_fk='".$tran_district_id_fk."',
reason='".$reason."',
proceding='".$proceding."',
benifit='".$benifit."'


 WHERE emp_id_const='".$emp_id_const."' and from_status='1'");
			
			
			
			if($query)
			{
			echo "1";
			}
			else
			{
			echo "0";
			}
	 
	 }
	 
	  if($stack_final=="ID" || $stack_final=="D")
	 {
		 if($stack_final=="ID")
		 {
			 $statement='TRANSFER WITHIN DISTRICT';
			 $s='2';
		 }
		 else
		 {
			  $statement='TRANSFER OUTSIDE DISTRICT';
			   $s='1';
		 }

$db=new database();
 $application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_district_transfer where stack_user='".$user."' and from_status='2' and district_level='".$stack_final."'");
 
 if(count($application_id_check)=='1')
		{
		
		//echo 1; die;
		  
			if($application_id_check[0]['application_id']!='')
			{
			$application_id=$application_id_check[0]['application_id'];
			}
			else
			{
			$application_id = application_id($stack_final,$statement);
			
			$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
			VALUES (
			'".$application_id."'
			)");
			
			}
		
		}
		else
		{
			//echo 2; die;
			
		$application_id = application_id($stack_final,$statement);
		
		$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
		VALUES (
		'".$application_id."'
		)");
		
		}
		
		
		
		
		$update_oc_final=$db->update("UPDATE intra_pri_district_transfer 
SET 
status='1',
application_id='".$application_id."'

 WHERE stack_user='".$user."' and from_status='2' and district_level='".$stack_final."'");
 
 if($update_oc_final){
	 
	
        $insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu)
            VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."','1','".$s."', '".$statement."','".$s."')");
    }
 
 if($insert_request_forwarding)
			{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Poposal Submitted Successfully...</strong></div>';
			header('Location:general_transfer.php?dis='.$_POST['stack_final']);
			}
			else
			{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Poposal Submitted failed...</strong></div>';
			header('Location:general_transfer.php?dis='.$_POST['stack_final']);
			}
	 }
	 
	if($stack_final_revoke=='ID'|| $stack_final_revoke=='D')
	{
	
	$update_oc_final_revoke=$db->update("UPDATE intra_pri_district_transfer 
	SET 
	from_status='1',
	transfer_gp_id_fk='',
	transfer_block_id_fk='',
	transfer_ps_id_fk='',
	transfer_district_id_fk='',
	reason='',
	proceding='',
	benifit=''
	WHERE emp_id_const='".$revoke_emp_id."' and from_status='2' and district_level='".$stack_final_revoke."'");
	
	
	if($update_oc_final_revoke)
	{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Poposal Revoke Successfully...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_final_revoke']);
	}
	else
	{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Poposal Revoke failed...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_final_revoke']);
	}
	
	}
	
	//$stack_del_final=$crypto->decode($_POST['stack_del_final'],4);
	 //$del_emp_id=$crypto->decode($_POST['del_emp_id'],4);  
	
	if($stack_del_final=='ID'|| $stack_del_final=='D')
	{
	
	//echo 1222; die;
	$update_oc_final_del=$db->update("UPDATE intra_pri_district_transfer 
	SET 
	from_status='0'
	WHERE emp_id_const='".$del_emp_id."' and from_status in('2','1') and district_level='".$stack_del_final."'");
	
	
	
	if($update_oc_final_del)
	{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Delete Successfully...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_del_final']);
	}
	else
	{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee Delete Revoke failed...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_del_final']);
	}
	
	}
	
	if($stack_approve=='ID'|| $stack_approve=='D')
	{
	
	
	echo 1222; die;
	$update_oc_final_approve=$db->update("UPDATE intra_pri_district_transfer 
	SET 
	status='3'
	WHERE emp_id_const='".$approve_emp_id."' and from_status in('2') and district_level='".$stack_approve."'");
	
	
	if($update_oc_final_approve)
	{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Delete Successfully...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_del_final']);
	}
	else
	{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee Delete Revoke failed...</strong></div>';
	header('Location:general_transfer.php?dis='.$_POST['stack_del_final']);
	}
	
	}
	 
	 
	
   ?>