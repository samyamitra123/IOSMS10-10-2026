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

$emp_id_pk=$cryp->decode($_GET['id'],4); 

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

function fun_gp($gp)
{ 
	$db = new database();
	$data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
	return $data[0]['gp_name'];
}

//error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$year=$_GET['year'];
$bill_id=$_GET['bill_id'];


if($logged_user=='zpacc')
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
									fad.festival_advance_instalment_last_amount,
									fad.deduction_end_monthyear,
									fad.total_amt_given,
									bill.bill_no,
									bill.bill_entry_time
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									INNER JOIN prd_block_bill_details bill
									ON bill.block_bill_pk=fad_emp.bill_id_fk
									WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND fad_emp.festival_advance_status=5 
									AND fad.status='1' AND fad_emp.bill_id_fk='".$bill_id."'
									  ");
}
else if($logged_user=='EO')
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
									fad.festival_advance_instalment_last_amount,
									fad.deduction_end_monthyear,
									fad.total_amt_given,
									bill.bill_no,
									bill.bill_entry_time
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									INNER JOIN prd_block_bill_details bill
									ON bill.block_bill_pk=fad_emp.bill_id_fk
									WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND fad_emp.festival_advance_status=5 
									AND fad.status='1' AND fad_emp.bill_id_fk='".$bill_id."'
									  ");
}
else if($logged_user=='BDO')
{
	$fad_details=$db->fetch_table(" SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.gp_id_fk,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount,
									fad.deduction_end_monthyear,
									fad.total_amt_given,
									bill.bill_no,
									bill.bill_entry_time
									FROM prd_employee_master emp
									INNER JOIN prd_location_master_gp gp
									ON gp.gp_id_pk=emp.gp_id_fk
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									INNER JOIN prd_block_bill_details bill
									ON bill.block_bill_pk=fad_emp.bill_id_fk
									WHERE gp.block_id_fk='".$_SESSION['location']['block_id']."' AND fad_emp.festival_advance_status=5 
									AND fad.status='1' AND fad_emp.bill_id_fk='".$bill_id."'
									  ");
}
								


$bill_number=$fad_details[0]['bill_no'];
$bill_month=substr($fad_details[0]['bill_entry_time'],5,2);
$bill_year=substr($fad_details[0]['bill_entry_time'],0,4);

?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<!--href="bg_fad_text_file_view.php?bill_no=<?php echo $cryp->encode($bill_number,4);?>&bill_month=<?php echo $cryp->encode($bill_month,4); ?>&bill_year=<?php echo $cryp->encode($bill_year,4); ?>"-->

<div class="school">
    <div class="table-responsive">
        <div class="bill_fad" align="right" style="padding-bottom:5px;">
        	<a class="btn btn-success" onClick="bill_view_show(this.id);" id="<?php echo $bill_number."&".$bill_month."&".$bill_year;?>"  style="font-weight:400; font-size:14px;">Bill View </a>
        </div>
        <table class="table-responsive" style="width:100%;">
            <thead>
                <tr>
                    <th style="width: 5%;">SL NO.</th>
                    <?php if($logged_user=='BDO'){ echo "<th>GP NAME</th>";}?>
                    <th>Employee Name</th>
                    <th>Employee Id</th>
                    <th  style="width: 12%;">Festival Advance Amount</th>
                    <th style="width: 10%;">Total Instalment Number</th>
                    <th>Instalment Amount</th>
                    <th >Total Amount Given</th>
                    <th>Edit</th>
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
                        <?php if($logged_user=='BDO'){ echo "<td>".fun_gp($key['gp_id_fk'])."</td>";}?>
                        <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
                        <td><?php echo $key['emp_id_const']; ?></td>
                        <td><?php echo $key['festival_advance_total_amount']; ?></td>
                        <td><?php echo $key['festival_advance_instalment_no']; ?></td>
                        <td><?php echo $key['festival_advance_instalment_amount'];if($key['festival_advance_instalment_amount']!=$key['festival_advance_instalment_last_amount']){echo ' (Last Month Payable : '.$key['festival_advance_instalment_last_amount'].')';} ?></td>
                        <td><?php echo $key['total_amt_given']; ?></td>
                        <td class="edit">
                        <?php if(trim(date('Ym'))<=trim($key['deduction_end_monthyear']) && ($key['festival_advance_instalment_amount']-$key['total_amt_given'])>0)
                        { ?>
                            <a onClick="fun_individual_edit(this.id);" <?php if($logged_user=='BDO'){?> id="<?php echo $cryp->encode($key['emp_id_pk'],4).'&'.$cryp->encode($bill_id,4).'&'.$cryp->encode($key['gp_id_fk'],4); ?> " <?php } else{?> id="<?php echo $cryp->encode($key['emp_id_pk'],4).'&'.$cryp->encode($bill_id,4); ?> " <?php } ?> ><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit">
                        </a>
                        <?php }
                        else
                        { ?>
                        <img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" style="opacity:0.5;">
                        <?php }?>
                        </td>
                    </tr> 
                    <?php
                    $total_saved=$total_saved+$key['saved'];  
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
</div>


<div class="clear"></div>

<?

//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  

<style>
	.button
	{
		padding-left:785px;
	}
	.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
	.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
	.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 2px;
		text-align:center;
	}
	.school table{
		border-radius: 5px;
		-moz-border-radius: 0px;
		overflow: hidden;
		font-size: 14px;
	}
	.school{
		background-color: #FFFFFF;
		border-radius: 8px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
		padding: 20px;
	
	}
	.school .title h2{
		color: #FFF;
		text-align: center;
		padding: 0px;
		margin: 0px;
		background-color: #0D8BBD;
		border-radius: 8px;
		-moz-border-radius: 8px;
	}
	.school .action .ui-widget{
		font-size: 11px;
	}
	.school .action{
		text-align: center;
	}
	.school .action .ui-button .ui-button-text{
		padding: 20px 10px;
	}

</style>