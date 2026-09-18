<?php
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


$cryp = new cryptography();

if(!empty($_GET['id'])){
	$emp_id_pk=$cryp->decode($_GET['id'],4); 
}


$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


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

$desig_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	");


function fun_desig($dcode, $code_desig)
{
	foreach ($code_desig as $key) 
	{
		if($key['designation_id'] == $dcode)
		{
			return $key['designation_name'];
		}
	}
}

//error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$year=$_GET['year'];

if($logged_user=='zpdaa')
{

	$fad_details=$db->fetch_table(" SELECT 
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND fad_emp.festival_advance_status in('4','5','6') AND fad.status='1'
									AND substr(fad_monthyear,1,4)='".$year."'
									  ");
}
else if($logged_user=='DA')
{

	$fad_details=$db->fetch_table(" SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND fad_emp.festival_advance_status in('4','5','6') AND fad.status='1'
									AND substr(fad_monthyear,1,4)='".$year."'
									  ");
}
else if($logged_user=='GP')
{

	$fad_details=$db->fetch_table(" SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.gp_id_fk='".$_SESSION['location']['gp_id']."' AND fad_emp.festival_advance_status in('4','5','6') AND fad.status='1'
									AND substr(fad_monthyear,1,4)='".$year."'");
}
								



?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>



<div class="school">
    <table class="table-responsive" style="width:100%;">
                                    <thead>
                                        <tr>
                                        <th style="width: 5%;">SL NO.</th>
                                        <th>Employee Name</th>
                                        <th>Employee Id</th>
                                        <th  style="width: 12%;">Festival Advance Amount</th>
                                        <th style="width: 10%;">Total Instalment Number</th>
                                        <th>Instalment Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($fad_details))
                                    { 
										$count = 1;$total_saved=0; 
										foreach ($fad_details as $key) 
										{?> 
                                            <tr>
                                                <td id="show"><?php echo $count; ?></td>
                                                <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
                                                <td><?php echo $key['emp_id_const']; ?></td>
                                                <td><?php echo $key['festival_advance_total_amount']; ?></td>
                                                <td><?php echo $key['festival_advance_instalment_no']; ?></td>
                                                <td><?php echo $key['festival_advance_instalment_amount'];if($key['festival_advance_instalment_amount']!=$key['festival_advance_instalment_last_amount']){echo ' (Last Month Payable : '.$key['festival_advance_instalment_last_amount'].')';} ?></td>
                                            </tr> 
                                            <?php
										}
                                    } 
                                    else 
                                    {?> 
                                    <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> 
                                    <?php 
                                    }?>
                                    </tbody>
                                </table>
    
</div>
   
           
<div class="clear"></div>

<?
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  
<script>

$(document).ready(function() {
	
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
});
</script>
