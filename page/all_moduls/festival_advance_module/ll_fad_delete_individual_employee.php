<?
ob_start();
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);

$emp_id_pk=$cryptoGraph->decode($_POST['emp_del_id'],4);
$gp_id_fk = $_SESSION['location']['gp_id'];
$ps_id_fk = $_SESSION['location']['ps_id'];
$zp_id_fk = $_SESSION['location']['district_id'];
$bonus_type_id=$_POST['send_bonus_type_id'];

$db = new database();	

if($emp_id_pk!="")
{
	if($logged_user=='GP')
	{
		
		/*$get_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details
									 WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
								 
				$festival_advance_id_pk=$get_details[0]['festival_advance_id_pk'];					 
									 
									 $query_update_new=$db->delete("DELETE FROM prd_festival_advance_entry_sal WHERE festival_advance_id_fk='$festival_advance_id_pk' ");
									 
		$query_update=$db->delete("DELETE FROM prd_festival_advance_employee_details WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");*/
		$query_update=$db->update(" UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=0
									FROM prd_employee_master emp
									WHERE emp.emp_id_pk=fad.emp_id_fk AND emp.gp_id_fk='".$gp_id_fk."' AND emp_id_fk='".$emp_id_pk."' 
									AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
	}
	else if($logged_user=='DA')
	{
		
		
		/*$get_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details
									 WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
								 
				$festival_advance_id_pk=$get_details[0]['festival_advance_id_pk'];					 
									 
									 $query_update_new=$db->delete("DELETE FROM prd_festival_advance_entry_sal WHERE festival_advance_id_fk='$festival_advance_id_pk' ");
									 
		$query_update=$db->delete("DELETE FROM prd_festival_advance_employee_details WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");*/
				
				
					
		
		
		
		$query_update=$db->update(" UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=0
									FROM prd_employee_master emp
									WHERE emp.emp_id_pk=fad.emp_id_fk AND emp.ps_id_fk='".$ps_id_fk."' AND emp_id_fk='".$emp_id_pk."' 
									AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
									
				
	}
	else if($logged_user=='zpdaa')
	{
		
		
		/*$get_details=$db->fetch_table("SELECT festival_advance_id_pk FROM prd_festival_advance_employee_details
									 WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
								 
				$festival_advance_id_pk=$get_details[0]['festival_advance_id_pk'];					 
									 
									 $query_update_new=$db->delete("DELETE FROM prd_festival_advance_entry_sal WHERE festival_advance_id_fk='$festival_advance_id_pk' ");
									 
		$query_update=$db->delete("DELETE FROM prd_festival_advance_employee_details WHERE emp_id_fk='$emp_id_pk' AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");*/
		
		if($zp_id_fk=='16')
		{
			
		$query_update=$db->update(" UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=0
									FROM prd_employee_master emp
									WHERE emp.emp_id_pk=fad.emp_id_fk AND emp.zp_id_fk='".$zp_id_fk."' AND emp_id_fk='".$emp_id_pk."' 
									AND festival_advance_status in (1,2)");
		}
		else
		{
		
		$query_update=$db->update(" UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=0
									FROM prd_employee_master emp
									WHERE emp.emp_id_pk=fad.emp_id_fk AND emp.zp_id_fk='".$zp_id_fk."' AND emp_id_fk='".$emp_id_pk."' 
									AND festival_advance_status in (1,2) AND substr(fad_monthyear,1,4)='".date('Y')."'");
		}
		
	
	}
}
	
	
if($query_update)
{
	$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Festival Details Has Been Deleted Successfully.</strong></div>';
	header('location:ll_emp_festival_advance_list.php');
	exit(0);
}
else
{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Festival Details Has Not Been Deleted. Please Try Again...</strong></div>';
	header('location:ll_emp_festival_advance_list.php');
	exit(0);
}
@pg_close($con);
?>