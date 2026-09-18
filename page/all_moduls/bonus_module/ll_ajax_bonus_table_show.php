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

error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$year=$_GET['year'];
$month=$_GET['month'];
$bon_cat=$_GET['bon_cat'];
 $bon_name=$_GET['bon_name']; 

if($logged_user=='zpdaa' || $logged_user=='zpddo')
{


	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk and bill.zp_id_fk=emp_bon.zp_id_fk
											WHERE monthyear='".$year."' AND emp_bon.delete_status='1' AND bon_type.active_status!='0' 
											AND emp_bon.zp_id_fk='".$_SESSION['location']['district_id']."' AND bon_type.bonus_category='".$bon_cat."'
											
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)");
}
else if($logged_user=='DA' ||$logged_user=='EO')
{

 
	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk and bill.ps_id_fk=emp_bon.ps_id_fk
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1'  
											AND emp_bon.ps_id_fk='".$_SESSION['location']['ps_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)
											");
}
else if($logged_user=='GP')
{

	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1' 
											AND emp_bon.gp_id_fk='".$_SESSION['location']['gp_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)
											");
}
else if( $logged_user=='BDO')
{
	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1' 
											AND emp_bon.block_code='".$_SESSION['location']['block_code']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)
											");
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
                <th style="width: 15%;">Employee Name</th>
                <th style="width: 12%;">Employee Id</th>
                <th style="width: 8%;">Designation</th>
                <th>Bonus Name</th>
                <th>Bonus Amount</th>
                <th style="width: 16%;">Status</th>
                <th style="width: 8%;">Bill Number</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(count($bonus_details_fetch))
        {
			$count = 1; 
			foreach ($bonus_details_fetch as $key) 
			{
				/*if($key['active_status']=='2')
				{
					$status='<p style="font-weight:bold;color:#656fb3;">Bill Generated</p>';
				}*/
				 
					if($key['bonus_status']=='4')
					{
						$status='<p style="font-weight:bold;color:#6CB0BF;">Locked</p>';
					}
					else if($key['bonus_status']=='3')
					{
						$status='<p style="font-weight:bold;color:#D59960;">Waiting For Lock</p>';
					}
					
					else if($key['bonus_status']=='5')
					{
						$status='<p style="font-weight:bold;color:#656fb3;">Bill Generated</p>';
					}
					
				
				?>
                <tr>
                    <td id="show"><?php echo $count; ?></td>
                    <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
                    <td><?php echo $key['emp_id_const']; ?></td>
                    <td><?php if($logged_user=='zpdaa'){ echo fun_desig($key['emp_desig'],$desig_data);}else{ echo fun_common($key['emp_desig'],$code_data);} ?></td>
                    <td><?php echo fun_common($key['bonus_name'],$code_data); ?></td>
                    <td><?php echo $key['bonus_amount']; ?></td>
                    <td><?php echo $status; ?></td>
                     <td><?php echo $key['bill_no'];; ?></td>
                </tr>
                <?php
                $count += 1 ; 
				
				
			}
		}
        else 
        {?> <tr><td colspan="8" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
        </tbody>
    </table>
    
    <?php if($bonus_details_fetch[0]['bonus_status']=='5')
    {?>
    
                        <div id="report" ><a class="btn btn-info btn-sm" onClick="generate_excel(0,3);"><i class="fa fa-file-text"></i>&nbsp;Download Report</a>
                     </div>
                    <?php }?>
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


function generate_excel(k,l)
{
	
year="<?=$year?>";
month="<?= $month?>";
bon_cat="<?= $bon_cat?>";
bon_name="<?= $bon_name?>";
	
		
	
			//window.location.href= "salary_requisition_excel.php?month="+month+"&year="+year;
				window.location.href= "ll_bonus_excel.php?year="+year+"&month="+month+"&bon_cat="+bon_cat+"&bon_name="+bon_name;
	
	
	
}
</script>
