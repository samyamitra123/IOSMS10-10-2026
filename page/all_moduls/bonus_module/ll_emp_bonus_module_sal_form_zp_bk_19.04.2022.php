<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';






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

$current_year=date("Y");
$next_year=$current_year+1;
$prev_yrr=$current_year-1;
$fin_prev_yrr=$next_year.'04';
$fin_yr_start=$current_year.'03';
$fin_yr_match=$current_year.'04';
$fin_yr_end=$next_year.'03';

$db = new database();

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code)
{
	foreach ($code as $key) 
	{
		if($key['code'] == $tcode)
		{
				return $key['description'];
		}
	}
}
function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
	}

function get_employee_list_for_bonus($emp_bonus_category, $stake_id, $stake_level)
{ 
    $current_year=date("Y");
	$next_year=$current_year+1; 
	$prev_yrr=$current_year-1;
	$db = new database();
	
	if($emp_bonus_category==167)
	{
		 $bonus_type="emp_religion = '".$emp_bonus_category."'";
	}
	else if($emp_bonus_category==666)
	{
		$bonus_type="emp_religion != '0'";
	}
		
	if($stake_level=='ZP')
	{
										
		$employee_list_bonus = $db->fetch_table("SELECT 
													emp_id_pk,
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_id_pk,
													emp_retirement_date,
													emp_religion,
													emp_desig,
													emp_first_join_date,
													emp_id_const,
													emp_pay_in_payband,
													emp_grade_pay,
													emp_bank_name,
													emp_acc_no,
													emp_ifsc_no,
													emp_cosolidated_pay
												FROM
													prd_employee_master
												WHERE
												
										           emp_religion != '0'
													
													AND zp_id_fk = '".$stake_id."'
													AND emp_pay_in_payband + (emp_pay_in_payband*3/100) <= '36000'
													AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
												AND emp_id_pk in 
(SELECT emp_id_pk FROM prd_employee_master WHERE emp_religion != '0' AND zp_id_fk = '".$stake_id."' AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' ) or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
EXCEPT
SELECT emp_id_fk FROM prd_employee_bonus_details WHERE delete_status=1 AND bonus_status not in (1,2,3,4) AND monthyear='".$prev_yrr.$current_year."' AND zp_id_fk = '".$stake_id."') 	
										");
										
										
									
		
	}
	else if($stake_level=='PS')
	{
		
		
		
		/*echo ("SELECT 
													emp_id_pk,
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_id_pk,
													emp_retirement_date,
													emp_religion,
													emp_desig,
													emp_first_join_date,
													emp_id_const,
													emp_pay_in_payband,
													emp_grade_pay,
													emp_cosolidated_pay
										FROM
										prd_employee_master
										WHERE
										".$bonus_type."
										AND ps_id_fk = '".$stake_id."'
										AND (emp_status = '1'  or (emp_retirement_date>='2019-09-30' and  emp_retirement_date<='2020-03-31'))
										AND emp_id_pk in 
(SELECT emp_id_pk FROM prd_employee_master WHERE ".$bonus_type." AND ps_id_fk = '".$stake_id."' AND (emp_status = '1'  or (emp_retirement_date>='2019-09-30' and emp_retirement_date<='2020-03-31'))
EXCEPT
SELECT emp_id_fk FROM prd_employee_bonus_details WHERE delete_status=1 AND bonus_status not in (1,2,3) AND monthyear='".$prev_yrr.$current_year."' AND ps_id_fk = '".$stake_id."') 	
										");*/
	
		$employee_list_bonus = $db->fetch_table("SELECT 
													emp_id_pk,
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_id_pk,
													emp_retirement_date,
													emp_religion,
													emp_desig,
													emp_first_join_date,
													emp_id_const,
													emp_pay_in_payband,
													emp_grade_pay,
													emp_bank_name,
													emp_acc_no,
													emp_ifsc_no,
													emp_cosolidated_pay
										FROM
										prd_employee_master
										WHERE
										".$bonus_type."
										AND ps_id_fk = '".$stake_id."'
										AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
										AND emp_id_pk in 
(SELECT emp_id_pk FROM prd_employee_master WHERE ".$bonus_type." AND ps_id_fk = '".$stake_id."' AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
EXCEPT
SELECT emp_id_fk FROM prd_employee_bonus_details WHERE delete_status=1 AND bonus_status not in (1,2,3,4) AND monthyear='".$prev_yrr.$current_year."' AND ps_id_fk = '".$stake_id."') 	
										");
									 
										
	}
	
	else if($stake_level=='GP')
	{
	
	
	
		$employee_list_bonus = $db->fetch_table("SELECT 
													emp_id_pk,
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_id_pk,
													emp_retirement_date,
													emp_religion,
													emp_desig,
													emp_first_join_date,
													emp_id_const,
													emp_pay_in_payband,
													emp_grade_pay,
													emp_bank_name,
													emp_acc_no,
													emp_ifsc_no,
													emp_cosolidated_pay
										FROM
										prd_employee_master
										WHERE
										".$bonus_type."
										AND gp_id_fk = '".$stake_id."'
										AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
										AND emp_id_pk in 
(SELECT emp_id_pk FROM prd_employee_master WHERE ".$bonus_type." AND gp_id_fk = '".$stake_id."' AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
EXCEPT
SELECT emp_id_fk FROM prd_employee_bonus_details WHERE delete_status=1 AND bonus_status not in (1,2,3,4) AND monthyear='".$prev_yrr.$current_year."' AND gp_id_fk = '".$stake_id."') 	
										");
		
	}
	return $employee_list_bonus;
}




function get_employee_list_for_bonus_not_select($emp_bonus_category, $stake_id, $stake_level)
{
	$current_year=date("Y");
	$next_year=$current_year+1; 
	$prev_yrr=$current_year-1;
	$db = new database();
	
	if($emp_bonus_category==167)
	{
		 $bonus_type="emp_religion = '".$emp_bonus_category."'";
	}
	else if($emp_bonus_category==666)
	{
		$bonus_type="emp_religion != '0'";
	}
		
	if($stake_level=='ZP')
	{
										
									
			$employee_list_bonus_for_checking = $db->fetch_table("SELECT 
													emp_id_pk as emp_id_pk
												FROM prd_employee_master
												WHERE
										          emp_religion != '0'
													AND emp_pay_in_payband + (emp_pay_in_payband*3/100) > '36000'
													AND zp_id_fk = '".$stake_id."'
													AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
												AND emp_id_pk in 
(SELECT emp_id_pk FROM prd_employee_master WHERE emp_religion != '0' AND zp_id_fk = '".$stake_id."' AND ((emp_status = '1' and emp_first_join_date<'2020-10-02' )or (emp_termination_date>='2020-09-30' and emp_termination_date<='2021-10-31' )) 
EXCEPT
SELECT emp_id_fk FROM prd_employee_bonus_details WHERE delete_status=1 AND bonus_status not in (1,2,3,4) AND monthyear='".$prev_yrr.$current_year."' AND zp_id_fk = '".$stake_id."') 	
										");
										
	}
	return $employee_list_bonus_for_checking;
}



function fun_grade_pay($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
	return $dist_data2[0]['grade_amount'];
}






$crypto = new cryptography();

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

//-------------------------------------------------------------------

$db = new database();

$tch = array();
	if(isset($_SESSION['location']['gp_id'])!='')
	{
		$gp_id_fk = $_SESSION['location']['gp_id'];
		//$gp_id_fk = '3355';
	}
	else if(isset($_SESSION['location']['ps_id'])!='')
	{
		$ps_id_fk = $_SESSION['location']['ps_id'];
		//$ps_id_fk = '88888888888';
	}
	else
	{
		$zp_id_fk = $_SESSION['location']['district_id'];
		//$zp_id_fk = '66666';
	}


if(date("Ym") >= $fin_yr_match && date("Ym") <= $fin_yr_end)
{
	$bonus_fin_year=$prev_yrr.$current_year;
}

	
	$c_year = date('Y');
	$p_year = date('Y')-'1';
	$mnth_year = $p_year.$c_year;	
	
$bonus_type_details_fetch=$db->fetch_table("
											SELECT bonus_type_id_pk,bonus_category,bonus_amount,bonus_monthyear,bonus_name
											FROM prd_bonus_type_details
											WHERE active_status='1' and bonus_monthyear='".$bonus_fin_year."' AND delete_status=1");
	
if($logged_user=='zpdaa')
{
	
	$save_sent_bonus_data_fetch = $db->fetch_table("SELECT
													COUNT(CASE WHEN (bonus_status='1') THEN emp_id_fk END) as submitted,
													COUNT(CASE WHEN (bonus_status='2') THEN emp_id_fk END) as saved,
													COUNT(CASE WHEN (bonus_status='3') THEN emp_id_fk END) as sent 
													FROM prd_employee_bonus_details
													WHERE  bonus_status in ('1','2','3') AND delete_status='1' AND zp_id_fk = '".$zp_id_fk."' AND monthyear='".$mnth_year."'");

}
else if($logged_user=='DA')
{
	$save_sent_bonus_data_fetch = $db->fetch_table("SELECT
													COUNT(CASE WHEN (bonus_status='1') THEN emp_id_fk END) as submitted,
													COUNT(CASE WHEN (bonus_status='2') THEN emp_id_fk END) as saved,
													COUNT(CASE WHEN (bonus_status='3') THEN emp_id_fk END) as sent 
													FROM prd_employee_bonus_details
													WHERE bonus_status in ('1','2','3') AND delete_status='1' AND ps_id_fk = '".$ps_id_fk."' AND monthyear='".$mnth_year."'");
}
else if($logged_user=='GP')
{
	
	$save_sent_bonus_data_fetch = $db->fetch_table("SELECT
													COUNT(CASE WHEN (bonus_status='1') THEN emp_id_fk END) as submitted,
													COUNT(CASE WHEN (bonus_status='2') THEN emp_id_fk END) as saved,
													COUNT(CASE WHEN (bonus_status='3') THEN emp_id_fk END) as sent 
													FROM prd_employee_bonus_details
													WHERE bonus_status in ('1','2','3') AND delete_status='1' AND gp_id_fk = '".$gp_id_fk."'  AND monthyear='".$mnth_year."'");
											
}

if(count($bonus_type_details_fetch)>0)
{	
	
	$bonus_id=$bonus_type_details_fetch[0]['bonus_type_id_pk'];
	$emp_bonus_category=$bonus_type_details_fetch[0]['bonus_category'];	
	$emp_bonus_monthyear=$bonus_type_details_fetch[0]['bonus_monthyear'];
	$emp_bonus_month=substr($emp_bonus_monthyear,4,2);
	$bonus_month_name=date("F", mktime(0, 0, 0, $emp_bonus_month, 10));
	$emp_bonus_year=substr($emp_bonus_monthyear,0,4);
	$emp_bonus_name=$bonus_type_details_fetch[0]['bonus_name'];	
	$emp_bonus_amount=$bonus_type_details_fetch[0]['bonus_amount'];
		
	if($logged_user=='zpdaa')
	{					
   $emp_list_for_bonus = get_employee_list_for_bonus($emp_bonus_category, $zp_id_fk,'ZP'); 
	 $emp_list_for_bonus_not_select = get_employee_list_for_bonus_not_select($emp_bonus_category, $zp_id_fk,'ZP'); 
	}
	else if($logged_user=='DA')
	{
		$emp_list_for_bonus = get_employee_list_for_bonus($emp_bonus_category, $ps_id_fk,'PS');
	}
	else if($logged_user=='GP')
	{
		$emp_list_for_bonus = get_employee_list_for_bonus($emp_bonus_category, $gp_id_fk,'GP');
	}
	
	
foreach($emp_list_for_bonus as $ar){
	
	$str1.=','."'". $ar['emp_id_pk']."'";
										
}
  $emp_list_for_bonus_pk =  trim($str1,',');  

foreach($emp_list_for_bonus_not_select as $ar){
	
	$str2.=','."'". $ar['emp_id_pk']."'";

}
 $emp_list_for_bonus_not_select_list_pk =  trim($str2,',');  

//$emp_list_for_bonus_not_select_list = implode(', ', array_column($emp_list_for_bonus_not_select, 'emp'));
	
	//$emp_list_for_bonus_not_select_list = implode(', ', array_column($emp_list_for_bonus_not_select, 'emp_id_pk'));
	
										
	$short_employee_from_not_select =$db->fetch_table(" SELECT 
													DISTINCT (emp.emp_id_pk),
													emp.emp_first_name,
													emp.emp_id_pk,
													emp.emp_second_name,
													emp.emp_last_name,
													emp.emp_retirement_date,
													emp.emp_religion,
													emp.emp_desig,
													emp.emp_retirement_date,
													emp.emp_first_join_date,
													emp.emp_id_const,
													emp.emp_pay_in_payband,
													emp.emp_grade_pay,
													emp.emp_cosolidated_pay,
													emp.emp_bank_name,
													emp.emp_acc_no,
													emp.emp_ifsc_no
										FROM prd_employee_master as emp 
										INNER JOIN prd_monthly_salary_archive_final as save ON emp.emp_id_pk = save.emp_id_fk
										WHERE save.emp_id_fk IN (".$emp_list_for_bonus_not_select_list_pk.") and delete_status='1'
										AND save.salary_monthyear IN('202009','202010') AND save.basic <= '36000' ");
										
										
										
	if($emp_list_for_bonus !='' && $emp_list_for_bonus != NULL && $short_employee_from_not_select !='' && $short_employee_from_not_select != NULL){
		
		$new_list = array_merge($emp_list_for_bonus,$short_employee_from_not_select);
		//var_dump($short_employee_from_not_select); die;
	}	
	else if($emp_list_for_bonus =='' && $emp_list_for_bonus == NULL){
		$new_list = $short_employee_from_not_select;
	}
	else if($short_employee_from_not_select =='' && $short_employee_from_not_select == NULL){
		$new_list = $emp_list_for_bonus;
	}

	
 

}



										
else{
	$emp_list_for_bonus = array();
}
//var_dump($bonus_type_details_fetch); die;

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "P&RD | Govt. of West Bengal ";

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//$crypto = new cryptography();


?>
<style>
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
	padding: 6px;
	text-align:center;
	}
	.school table{
	border-radius: 5px;
	-moz-border-radius: 5px;
	overflow: hidden;
	font-size: 14px;
	}
	.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
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
	padding: 5px 10px;
	}

</style>

<script>
	$(document).ready(function(){
		
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
	//$( ".modal fade in" ).css( "height", "1000px" );
	
	var rowCount = $('#bonus_table tr').length;
	if(rowCount=='1')
	{
		$('#bonus_table tr:last').after('<tr style="background-color:#CCE6FF;"><td colspan="10" style="color:#F00; font-size:18px;"><strong>No data found</strong></td></tr>');
	}
	
	
	});
</script>

<div class="content">
	<? require '../../../page/common_back_btns.php'; ?>
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'].", ";
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'];
        }elseif(isset($_SESSION['location']['ps_name'])) {
        echo $_SESSION['location']['ps_name'].", ";
        }elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        <?php if($_SESSION['user_info']['stake_abbr']=='GP'){ ?>
        <?php echo $_SESSION['location']['block_name'].", " .$_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }else{
        
        echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }
        ?></h3>
    </div>
    
    <center>
    <br/>
        <h1 class="heading">EMPLOYEE BONUS MANAGEMENT</h1>
        <div class="border" > </div>
        <br/><br/>
       
	  
        <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
         <?php  
       if(isset($_SESSION['msg']))
        {
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
        }
        ?>
        </div>
    </center>
    
  
    
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="emplist" style="width:98%;">
                        <div class="school">
                        <?php 
						if($save_sent_bonus_data_fetch[0]['sent']==0)
						{
							if(count($bonus_type_details_fetch)==0) 
							{ ?>
								<!--<div class="entry_bonus" align="right">
									<a class="btn btn-success" onClick="bonus_entry();" style="font-weight:400; font-size:14px;">Bonus Entry </a>
								</div>-->
							<?php 
							}
							else
							{ ?>
								<div class="delete_bonus" align="right">
									<!--<a class="btn btn-danger" onClick="bonus_delete();"  style="font-weight:400; font-size:14px;">Bonus Delete </a>-->
                                    <?php 
									if($save_sent_bonus_data_fetch[0]['saved']!=0)
									{ ?>
                                    	<a class="btn btn-primary" onClick="send_bonus();" id="after_save_send_link"  style="font-weight:400; font-size:14px;">Bonus Sent </a>
									<?php 
									}
									else
									{ ?>
                                    	<a class="btn btn-primary" onClick="send_bonus();" id="before_save_send_link" style="font-weight:400; font-size:14px; display:none;">Bonus Sent </a>
                                    <?php } ?>
								</div>
							<? 
							}
						}
						?>
                            
                            <br/>
                            <table class="table-responsive" style="width:100%;" id="bonus_table">
                                <thead>
                                    <tr>
                                        <th>
                                            <?php if($save_sent_bonus_data_fetch[0]['saved']!=0 && $save_sent_bonus_data_fetch[0]['submitted']==0 && $save_sent_bonus_data_fetch[0]['sent']==0)
                                            { ?>
                                            	<input type="checkbox" class="single-checkbox" name="all[]" id="all" ><label for="all"><span></span></label>
                                            <?php }
                                            else
                                            { ?>
                                                <div id="not_ready_all_send_div_id" style="display:block;">
                                                &nbsp;
                                                </div>
                                                <div id="ready_all_send_div_id" style="display:none;">
                                                <input type="checkbox" class="single-checkbox" name="all[]" id="all" ><label for="all"><span></span></label>
                                                </div>
                                            <?php } ?>
                                        </th>
                                        <th style="width: 5%;">SL NO.</th>
                                        <th>Employee Name</th>
                                        <th>Employee Id</th>
                                        <th>Religion</th>
                                        <th>Bonus Amount</th>
                                        <th>Bank Name</td>
                                        <th>Account No</td>
                                        <th>IFSC Code</td>
                                        <!--<th>Status</th>-->
                                        <th colspan="2" style="width:10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
									if(count($new_list))
									{
										//echo $financial_year."03";
										$count = 1; 
										foreach ($new_list as $key) 
										{
											$emp_id_pk=$key['emp_id_pk'];
											$bonus_status_fetch=$db->fetch_table("SELECT bonus_amount,bonus_status FROM prd_employee_bonus_details
											WHERE bonus_type_id_fk='".$bonus_id."' AND emp_id_fk='".$emp_id_pk."'
											AND delete_status='1' AND monthyear='".$mnth_year."'");
													
												
													//echo 1;
													?>
													
													<tr>
                                                    	<td>
                                                    	<?php if($bonus_status_fetch[0]['bonus_status']=='2' && $save_sent_bonus_data_fetch[0]['sent']==0)
                                                        { ?>
                                                            <input type="checkbox" class="check_all" name="single[]" id="single<?=$count ?>" value="<?= $key['emp_id_pk']?>"><label for="single<?=$count ?>"><span></span></label>
														<?php }
                                                        else
                                                        { ?>
                                                        	<div id="not_ready_send_div_id<?php echo $emp_id_pk;?>" style="display:block;">
                                                        		&nbsp;
                                                            </div>
                                                            <div id="ready_send_div_id<?php echo $emp_id_pk;?>" style="display:none;">
                                                        		<input type="checkbox" class="check_all" name="single[]" id="single<?=$count ?>" value="<?= $key['emp_id_pk']?>"><label for="single<?=$count ?>"><span></span></label>
                                                            </div>
                                                            
														<?php } ?>
                                                        </td>
                                                        <td id="show"><?php echo $count; ?></td>
                                                        <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
                                                        <td><?php echo $key['emp_id_const']; ?></td>
                                                        <td><?php echo fun_common($key['emp_religion'],$code_data); ?></td>
                                                        <td id="bon_amount<? echo $key['emp_id_pk'] ?>"><?php if($bonus_status_fetch[0]['bonus_amount']!=''){ echo $bonus_status_fetch[0]['bonus_amount']; } else { echo 0; } ?></td>
                                                        
                                                     <td><?php echo fun_bank($key['emp_bank_name']); ?></td>
                                                        <td><?php echo $key['emp_acc_no']; ?></td>
                                                        <td><?php echo $key['emp_ifsc_no']; ?></td>
                                                       
                                                         <?php if($bonus_status_fetch[0]['bonus_status']!='3' && $bonus_status_fetch[0]['bonus_status']!='4')
														{ ?>
                                                        <td class="edit">
                                                        <?php if(($bonus_status_fetch[0]['bonus_status']!='3' || $bonus_status_fetch[0]['bonus_status']=='') && ($save_sent_bonus_data_fetch[0]['sent']==0 || $save_sent_bonus_data_fetch[0]['sent']==''))
                                                        { ?>
                                                            <a onClick="fun_individual_edit(this.id);" id="<? echo $crypto->encode($key['emp_id_pk'],4).'&'.$key['emp_id_pk'] ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit">
                                                            </a>
														<?php }
                                                        else
                                                        { ?>
                                                        	<img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" style="opacity:0.5;">
														<?php }?>
                                                        </td>
                                                       
                                                        <td class="save" id="save">
                                                        <?php if($bonus_status_fetch[0]['bonus_status']!="" && $bonus_status_fetch[0]['bonus_status']!='2' && $bonus_status_fetch[0]['bonus_status']!='3' && $save_sent_bonus_data_fetch[0]['sent']==0)
                                                        { //echo 1;  ?>
                                                        	<div id="save_div_id<?php echo $emp_id_pk;?>" style="display:block;">
                                                            	<a id="save_bonus&<? echo $crypto->encode($key['emp_id_pk'],4).'&'.$key['emp_id_pk'] ?>" onClick="save_bonus(this.id);"><img id="img_id<?=$count?>"  width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save">
                                                            	</a>
                                                            </div>
                                                            <div id="saved_div_id<?php echo $emp_id_pk;?>" style="display:none;">
                                                            	<img id="img_id<?=$count?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved">
                                                            </div>
														<?php }
                                                        else if($bonus_status_fetch[0]['bonus_status']=="2" || $bonus_status_fetch[0]['bonus_status']=="3")
                                                        { //echo 2; ?>
                                                        	<img id="img_id<?=$count?>"  width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved">
														<?php } 
														else
                                                        { //echo 3; ?>
                                                        	<img id="img_id<?=$count?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" style="opacity:0.5;">
														<?php }?>
                                                        </td>
                                                        <?php } 
														 else if( $bonus_status_fetch[0]['bonus_status']=='4')
														{  //echo 4;
														
														
															echo "<td colspan='2' >Bonus Lock</td>";
														} else
														{
															echo "<td colspan='2'>Bonus Sent</td>";
														}?>
                                                        
													</tr>
													<?php
													$saved = 0;
													
													
													$count += 1 ; 
												 
											//} 
										}
									}
                                    else 
                                    {?> <tr><td colspan="26" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                echo $status='<span style="color:RED;font-weight:bold">*** You may check and verify Employee Bank Details.</span>'; 
				?>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>


<? require '../../../page/layout/footer.php'; ?>


<!-----------------------------------------------------------------MODAL Start---------------------------------->

<style>
	ul.sub-menu>li>a:hover {
	/* color: #ffffff!important;*/
	color: #74ad1c !important;
	}
</style>

<script>
	
	function bonus_entry()
	{
		$('#bonus_insert_modal').modal('show');
		$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ll_ajax_bonus_module_insert_details.php', function(data){
			$("#mbody").html(data);
		}); 
	}
	
	function bonus_delete()
	{
		$('#bonus_delete_modal').modal('show');
	}
	
	function fun_individual_edit(k)
	{
		var arr=k.split('&');
		$('#bonus_edit').modal('show');
		var link = arr[0];
		var bon_amount=$('#bon_amount'+arr[1]).text();
		var bonus_id='<?php echo $bonus_id;?>';
		$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ll_ajax_individual_employee_bonus_edit.php?id='+link+'&bon_amt='+bon_amount+'&bon_id='+bonus_id, function(data){
			$("#mbody_single_entry").html(data);
		});
	}
	
	function save_bonus(k)
	{
		var arr=k.split('&');
		var emp_encrpt_id=arr[1];
		var emp_id=arr[2];
		var bonus_id='<?php echo $bonus_id;?>';
		$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ll_ajax_individual_employee_bonus_save.php?emp_id='+emp_encrpt_id+'&bon_id='+bonus_id, function(data){
			if(data=='saved')
			{
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Saved Successfully.</strong></div>');
				$('#save_div_id'+emp_id).hide();
				$('#saved_div_id'+emp_id).show();
				$('#not_ready_send_div_id'+emp_id).hide();
				$('#ready_send_div_id'+emp_id).show();
				$('#before_save_send_link').show();
			}
			else if(data=='all_saved')
			{
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Saved Successfully.</strong></div>');
				$('#save_div_id'+emp_id).hide();
				$('#saved_div_id'+emp_id).show();
				$('#not_ready_send_div_id'+emp_id).hide();
				$('#ready_send_div_id'+emp_id).show();
				$('#ready_all_send_div_id').show();
				$('#not_ready_all_send_div_id').hide();
				$('#before_save_send_link').show();
			}
			else if(data=='not_saved')
			{
				$('#sess_msg').html('<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Saved. Please Try Again...</strong></div>');
			}
				
		});
	}
	
	function send_bonus()
	{
		if($('input.check_all:checked').length == 0)
		{
			alert ( "Please select at least one employee" );
			return false;
		}
		else
		{
			var selected_emp = new Array();
	
			$("input.check_all:checked").each(function() {
				selected_emp.push($(this).val());
				});
				
			var enc_selected_emp=window.btoa(selected_emp);
			$('#send_bonus_modal').modal('show');
			$('#send_bonus_emp_id').val(enc_selected_emp);
		}
		/*var arr=k.split('&');
		var emp_encrpt_id=arr[1];
		$('#send_bonus_modal').modal('show');
		$('#send_bonus_emp_id').val(emp_encrpt_id);*/
		
	}
	
	$('#all').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.check_all').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "check_all"              
            });
        }else{
            $('.check_all').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "check_all"                      
            });        
        }
    });	
	
	
	$('.check_all').change(function(){ //".checkbox" change
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){ //if this item is unchecked
        $("#all")[0].checked = false; //change "select all" checked status to false
    }
   
    //check "select all" if all checkbox items are checked
    if ($('.check_all:checked').length == $('.check_all').length ){
        $("#all")[0].checked = true; //change "select all" checked status to true
    }
});
	
</script>
<!-----------------------------------------------------------------MODAL Start---------------------------------->

<!-----------------------------------------------------------------BONUS TYPE ENTRY MODAL---------------------------------->


<div class="modal fade bs-example-modal-lg" id="bonus_insert_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" >
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Employee Bonus Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Employee Bonus Details</h4>
            </div>
            <div class="modal-body"> 
                <div id="mbody"></div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
            </div>
        </div>
    </div>
</div>


<!-----------------------------------------------------------------BONUS TYPE DELETE MODAL---------------------------------->


<div class="modal fade bs-example-modal-md" id="bonus_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <form action="ll_bonus_type_delete.php" method="post">
        <div class="modal-dialog modal-md" >
            <div class="modal-content" >
                <div class="modal-header"> 
                  <h4 class="modal-title" id="myModalLabel">Employee Bonus Delete Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  
                </div>
                <div class="modal-body" style="height:150px;"> 
                    <div class="form-group">
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#298077;">Bonus Month Year</label>
                        </div>
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#3c34b5;"><?php echo $bonus_month_name.', '.$emp_bonus_year;?></label>
                        </div>
                        
                        
                        <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label" style="color:#298077;">Bonus Category</label>
                        </div>
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#3c34b5;"><?php echo fun_common($emp_bonus_category,$code_data);?></label>
                        </div>  
                    </div>
                    <br><br>
                    <div class="form-group">
                        <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label" style="color:#298077;">Bonus Name</label>
                        </div>
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#3c34b5;"><?php echo fun_common($emp_bonus_name,$code_data);?></label>   
                        </div>
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#298077;">Bonus Amount</label>
                        </div>
                        <div class="col-sm-3" >
                            <label for="inputPassword3" class="control-label" style="color:#3c34b5;"><?php echo $emp_bonus_amount;?></label>   
                        </div>
                    </div>
                    <input type="hidden" name="del_id" id="del_id" value="<?php echo $crypto->encode($bonus_id,4); ?>" >
                    <br><br>
                    <p style="font-weight:bold; color:#CC272A; text-align:center;" > Do you want to delete this Bonus Details? </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Yes</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">No</button>      
                </div>
            </div>
        </div>
    </form>
</div>



<!-----------------------------------------------------------------BONUS INDIVIDUAL EDIT MODAL---------------------------------->


<div class="modal fade bs-example-modal-lg" id="bonus_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" >
            <div class="modal-header">
             <h4 class="modal-title" id="myModalLabel">Employee Bonus Edit Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body"> 
                <div id="mbody_single_entry"></div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
            </div>
        </div>
    </div>
</div>

<!-----------------------------------------------------------------BONUS INDIVIDUAL SEND MODAL---------------------------------->

<div class="modal fade bs-example-modal-sm" id="send_bonus_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<form action="ll_individual_employee_bonus_send.php" method="post"> 
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
		    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Finally Send All Selected Employee's Bonus Details  ?</strong></p>
                    <input type="hidden" id="send_bonus_emp_id" name="send_bonus_emp_id" />
                    <input type="hidden" id="send_bonus_type_id" name="send_bonus_type_id" value="<?php echo $bonus_id;?>" />
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">YES</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
    	</div>
    </form>
</div>



