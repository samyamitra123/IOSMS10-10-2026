<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';


if($_SERVER['HTTP_REFERER']==''){
header("Location:../../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '.$config['base_url']."page/login.php");
exit;
}



if(!isset($_SERVER['HTTP_REFERER']))
{
	header('Location:'.$config['base_url']."page/error.php?id=1");
	exit("Do not paste URL directly");

} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
// substring is not found in string
header('Location:'. $config['base_url']."page/error.php?id=2");
exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
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


$cryp = new cryptography();

$emp_id_pk=$cryp->decode($_GET['id'],4); 

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


if(isset($_SESSION['location']['gp_id'])!='')
	{
		$gp_id_fk = $_SESSION['location']['gp_id'];
	}
	else if(isset($_SESSION['location']['ps_id'])!='')
	{
		$ps_id_fk = $_SESSION['location']['ps_id'];
	}
	else
	{
		$zp_id_fk = $_SESSION['location']['district_id'];
	}
//$gp_id_fk = $_SESSION['location']['gp_id'];
//$ps_id_fk = $_SESSION['location']['ps_id'];
//$zp_id_fk = $_SESSION['location']['district_id'];

$db = new database();

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

//error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$bonus_id=$_GET['bon_id'];

//if($logged_user=='GP')
//{
// 
//	$bonus_details_fetch=$db->fetch_table(" SELECT 
//										bonus_category,
//										bonus_amount,
//										bonus_monthyear,
//										bonus_name
//										FROM prd_bonus_type_details 
//										WHERE bonus_type_id_pk='".$bonus_id."' AND active_status='1' 
//										AND employee_total_number='0' AND gp_id_fk='".$gp_id_fk."' ");
//	
//}
//else if($logged_user=='DA')
//{
//	$bonus_details_fetch=$db->fetch_table(" SELECT 
//										bonus_category,
//										bonus_amount,
//										bonus_monthyear,
//										bonus_name
//										FROM prd_bonus_type_details 
//										WHERE bonus_type_id_pk='".$bonus_id."' AND active_status='1' 
//										AND employee_total_number='0' AND ps_id_fk='".$ps_id_fk."' ");
//	
//}
//else if($logged_user=='zpdaa')
//{
//	$bonus_details_fetch=$db->fetch_table(" SELECT 
//										bonus_category,
//										bonus_amount,
//										bonus_monthyear,
//										bonus_name
//										FROM prd_bonus_type_details 
//										WHERE bonus_type_id_pk='".$bonus_id."' AND active_status='1' 
//										AND employee_total_number='0' AND zp_id_fk='".$zp_id_fk."' ");
//
//}
//
//
//
//$bonus_monthyr=$bonus_details_fetch[0]['bonus_monthyear']; 
//$bonus_year=substr($bonus_monthyr,0,4);
//$bonus_month=substr($bonus_monthyr,4,2);
//$bonus_month_name=date("F", mktime(0, 0, 0, $bonus_month, 10));
//
//$bonus_category=$bonus_details_fetch[0]['bonus_category'];
//$bonus_max_amount=$bonus_details_fetch[0]['bonus_amount'];
//$bonus_name=$bonus_details_fetch[0]['bonus_name'];


//if($logged_user=='GP')
//{
//
//    $emp_data = $db->fetch_table("SELECT   
//								emp.emp_first_name,
//								emp.emp_second_name,
//								emp.emp_last_name,
//								bonus.*
//								FROM
//								prd_employee_master emp
//								INNER JOIN
//								prd_employee_bonus_details as bonus
//								ON emp.emp_id_pk=bonus.emp_id_fk
//								WHERE
//								emp.emp_id_pk = '".$emp_id_pk."' AND delete_status = '1' 
//								AND bonus.monthyear = '".$bonus_monthyr."' AND emp.gp_id_fk='".$gp_id_fk."'	
//								");
//	
//}
//if($logged_user=='DA')
//{
//	$emp_data = $db->fetch_table("SELECT   
//								emp.emp_first_name,
//								emp.emp_second_name,
//								emp.emp_last_name,
//								emp.emp_religion,
//								bonus.*
//								FROM
//								prd_employee_master emp
//								INNER JOIN
//								prd_employee_bonus_details as bonus
//								ON emp.emp_id_pk=bonus.emp_id_fk
//								WHERE
//								emp.emp_id_pk = '".$emp_id_pk."' AND delete_status = '1' 
//								AND bonus.monthyear = '".$bonus_monthyr."' AND emp.ps_id_fk='".$ps_id_fk."'	
//								");
//								
//																
//}
//else if($logged_user=='zpdaa')
//{
//	$emp_data = $db->fetch_table("SELECT   
//								emp.emp_first_name,
//								emp.emp_second_name,
//								emp.emp_last_name,
//								emp.emp_religion,
//								bonus.*
//								FROM
//								prd_employee_master emp
//								INNER JOIN
//								prd_employee_bonus_details as bonus
//								ON emp.emp_id_pk=bonus.emp_id_fk
//								WHERE
//								emp.emp_id_pk = '".$emp_id_pk."' AND delete_status = '1' 
//								AND bonus.monthyear = '".$bonus_monthyr."' AND emp.zp_id_fk='".$zp_id_fk."'	
//								");
//								
//}

function get_emp_bonus_type_details($emp_id_pk,$bon_id)
{
	$db = new database();
	$current_year=date("Y");
	$prev_yrr=$current_year-1;
	$next_year=$current_year+1;
	$fin_prev_yrr=$current_year.'04';
	$fin_yr_start=$current_year.'03';
	$fin_yr_end=$next_year.'03';
	
	if(date("Ym") > $fin_yr_start && date("Ym") <= $fin_yr_end)
	{
		$bonus_fin_year=$prev_yrr.$current_year;
		//$bonus_fin_year=$current_year.$next_year;
	}

	$c_year = date('Y');
	$p_year = date('Y')-'1';
	$bonus_fin_year = $mnth_year = $p_year.$c_year;	

	$emp_bonus_type_details = $db->fetch_table("SELECT bonus_type_id_pk,bonus_category,bonus_amount,bonus_monthyear,bonus_name
								FROM prd_bonus_type_details
								WHERE active_status='1' and bonus_monthyear='".$bonus_fin_year."' and bonus_type_id_pk='".$bon_id."'");
								
	return $emp_bonus_type_details; 
}

//$bonus_amount=$emp_data[0]['bonus_amount']; 

								
//if(count($emp_data)=='0')
//{
	$emp_data = $db->fetch_table("SELECT   
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name,
								emp.emp_religion
								FROM
								prd_employee_master emp
								WHERE
								emp_id_pk = '".$emp_id_pk."'	
								");
	$bonus_amount=$_GET['bon_amt'];
	
//}
$emp_bonus_type_details=get_emp_bonus_type_details($emp_id_pk,$bonus_id);
?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<?php

$cryptoGraph=new cryptography();
if(isset($_GET['confirm']) == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of The Employee Submitted Successfully.</strong></div>';
}
else if(isset($_GET['confirm']) == 'false')
{
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data Insertion Failed. Please Try Again.</strong></div>';
}

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}


?>

<div class="col-sm-12">
    <div class="emplist">
        <div class="school">
            <center><h1 class="heading">BONUS FOR THE RELIGIONS </h1></center>
            <div class="border"></div>
            </br>
            <form class="form-horizontal bonus_design" style="width: auto;" id="loginForm" method="post" action="ll_individual_employee_bonus_submit.php" onsubmit="return valid_code();">
                <input type="hidden" name="emp_id_pk" value="<?php echo $_GET['id']; ?>" />
                <input type="hidden" name="bon_monthyr" value="<?php echo $emp_bonus_type_details[0]['bonus_monthyear']; ?>" />
                <input type="hidden" name="bon_name" value="<?php echo $emp_bonus_type_details[0]['bonus_name']; ?>" />
                <input type="hidden" name="bon_type_id" value="<?php echo $bonus_id; ?>" />
                <input type="hidden" name="bon_max_amount" value="<?php echo $emp_bonus_type_details[0]['bonus_amount']; ?>" />
                <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>"/>
                <div class="row mb-3">
                	<div class="col-sm-3">
                        <label for="inputPassword3" class="control-label">Employee Name</label>
                    </div>
                    <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label"><?php echo $emp_data[0]['emp_first_name']." ".$emp_data[0]['emp_second_name']." ".$emp_data[0]['emp_last_name']." "; ?></label>
                    </div>
                    <div class="col-sm-3" >
                    <label for="inputPassword3" class="control-label">Bonus Financial Year <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                    	<label for="inputPassword3" class="control-label">
						<?php 
						$year1=substr($emp_bonus_type_details[0]['bonus_monthyear'],0,4);
						$year2=substr($emp_bonus_type_details[0]['bonus_monthyear'],4,4);
						echo $year1."-".$year2; 
						?>
                        </label>
                        
                    </div>
                   
                   
                </div>
                <div class="row mb-3">
                	 <div class="col-sm-3">
                        <label for="inputPassword3" class="control-label">Bonus Name <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label"><?php echo fun_common($emp_bonus_type_details[0]['bonus_name'],$code_data); ?></label>
                    </div>
                	 <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label">Bonus Amount <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                        <input class="form-control"  style="float:left;" maxlength="5" type="text" id="bonus_amount" name="bonus_amount"  value="<?php echo $emp_bonus_type_details[0]['bonus_amount'];  ?>" placeholder="Bonus Amount" autocomplete="off" onkeypress="return keyRestrict(event,'0123456789');"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12" align="center">
                        <button type="submit" class="btn btn-info" >SAVE & CONTINUE </i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
           
<div class="clear"></div>

<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  
<script>

function valid_code()
{

	if($('#bonus_amount').val()=='0')
	{
		alert('Please Enter Valid Bonus Amount.');
		$('#bonus_amount').focus();
		return false;
	}
	
}
</script>

<style>
	.bonus_design
	{
		width:800px;
		border:1px solid #000000;
		padding:9px;
		margin-bottom:5px;
	}
	.delete_one
	{
		float:right;
	}
</style>