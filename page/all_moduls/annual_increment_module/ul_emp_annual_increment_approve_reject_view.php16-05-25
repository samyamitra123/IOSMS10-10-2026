<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
$cryp = new cryptography();

$cryptoGraph=new cryptography();

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



//error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
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
else if($_SESSION['user_info']['stake_abbr']=='AEO')
{
	$logged_user='zpaeo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	


//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
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


    
<?
$db=new database();

if($logged_user == 'BDO')
{

	$arr=$db->fetch_table("select 
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name,
								emp.emp_status,
								emp.emp_id_pk,
								emp.gp_id_fk,
								emp.ropa_level, 
								emp.emp_id_const,
								annu.previous_emp_pay_in_payband,
								annu.new_emp_pay_band,
								annu.new_emp_pay_in_payband,
								annu.new_emp_pay_scale,
								annu.new_emp_grade_pay,
								annu.annual_increment_amount,
								annu.status,
								annu.annual_increment_date,
								annu.effective_monthyear,
								annu.previous_consolidated_pay,
								annu.new_consolidated_pay, 
								annu.increment_condisation 
								FROM prd_employee_master AS emp  
								INNER JOIN prd_employee_annual_increment_details AS annu 
								ON annu.emp_id_fk = emp.emp_id_pk 
								WHERE emp.emp_status in(1)   AND emp.gp_id_fk='".$cryptoGraph->decode($_GET['gp_id_fk'],4)."' AND  annu.effective_monthyear='".date('Ym')."' AND annu.status in('1','2') AND emp.emp_id_pk = annu.emp_id_fk ORDER BY emp.emp_id_pk DESC
									");
}
else if($logged_user == 'EO')
{

	$arr=$db->fetch_table("select 
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name,
								emp.emp_status,
								emp.emp_id_pk,
								emp.gp_id_fk, 
								emp.ropa_level,
								emp.emp_id_const,
								ps.ps_name,
								annu.previous_emp_pay_in_payband,
								annu.new_emp_pay_band,
								annu.new_emp_pay_in_payband,
								annu.new_emp_pay_scale,
								annu.new_emp_grade_pay,
								annu.annual_increment_amount,
								annu.status,
								annu.annual_increment_date,
								annu.effective_monthyear,
								annu.previous_consolidated_pay,
								annu.new_consolidated_pay,
								annu.increment_condisation 
								FROM prd_location_master_panchayat_samiti as ps 
								INNER JOIN prd_employee_master AS emp 
								ON ps.ps_id_pk=emp.ps_id_fk 
								INNER JOIN prd_employee_annual_increment_details AS annu 
								ON annu.emp_id_fk = emp.emp_id_pk 
								WHERE emp.emp_status in(1) AND ps.ps_id_pk='".$_SESSION['location']['ps_id']."' AND annu.status in('1','2') AND  annu.effective_monthyear='".date('Ym')."' AND emp.emp_id_pk = annu.emp_id_fk ORDER BY emp.emp_id_pk DESC
									");

}
else if($logged_user == 'zpacc' || $logged_user == 'zpaeo')
{

	$arr=$db->fetch_table("select 
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name,
								emp.emp_status,
								emp.emp_id_pk,
								emp.gp_id_fk, 
								emp.ropa_level,
								emp.emp_id_const,
								emp.zp_id_fk,
								zp.district_name,
								annu.previous_emp_pay_in_payband,
								annu.new_emp_pay_band,
								annu.new_emp_pay_in_payband,
								annu.new_emp_pay_scale,
								annu.new_emp_grade_pay,
								annu.annual_increment_amount,
								annu.status,
								annu.zp_forward_status,
								annu.annual_increment_date,
								annu.effective_monthyear,
								annu.previous_consolidated_pay,
								annu.new_consolidated_pay,
								annu.increment_condisation  
								FROM prd_location_master_district as zp 
								INNER JOIN prd_employee_master AS emp 
								ON zp.district_id_pk=emp.zp_id_fk 
								INNER JOIN prd_employee_annual_increment_details AS annu 
								ON annu.emp_id_fk = emp.emp_id_pk 
								WHERE emp.emp_status in(1) AND zp.district_id_pk='".$_SESSION['location']['district_id']."'  AND  annu.effective_monthyear='".date('Ym')."' AND annu.status in('1','2') AND annu.zp_forward_status in('1') AND emp.emp_id_pk = annu.emp_id_fk ORDER BY emp.emp_id_pk DESC
									");

}

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
function fun_grade_pay($val){
		$db = new database();
		$grade_amount = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $grade_amount[0]['grade_amount'];
	}

?> 

 
<div class="content">

	<? require '../../../page/common_back_btns.php'; ?>
    
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'];
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'].", ";
        }elseif(isset($_SESSION['location']['ps_name'])) {
        echo $_SESSION['location']['ps_name'].", ";
        }elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        
        <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        
        ?></h3>
    </div>
    
    <center>
        <h1 class="heading">ANNUAL INCREMENT DETAILS </h1>
        <?  
        if(isset($_SESSION['msg']))
        {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <div id="sess_msg" style="padding-left:12px;padding-right:12px;"></div>
        <br />
    </center>
    
    
    <?php 
    if(isset($_SESSION['msg']))
    {
		echo $_SESSION['msg'];
		unset($_SESSION['school_msg']);
    } 
    if(!empty($_GET['msg']))
	{
		echo $cryptoGraph->decode($_GET['msg'],4);
		echo "<br/>";
		echo "<br/>";
    }
    echo (!empty($msg));
    ?>
    
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="emplist">
                        <div class="school">
                            <div class="table-responsive">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th>Serial No.</th>
                                            <th>Employee Name</th>
                                            <th>Employee ID</th>
                                            <th>Existing LEVEL</th>
                                            <th>Existing Basic(OLD)</th>
                                            <!--<th>Existing Consolidated Pay(OLD)</th>-->
                                            <th>Increment Basic</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <? $cnt=1; 
									if(count($arr) > 0)
									{ 
										foreach($arr as $item)
										{?>
                                            <tr>
                                                <td><?= $cnt;?></td>
                                                <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                                <td><?= $item['emp_id_const']?></td>
                                                 <td><?= strtoupper($item['ropa_level'])?></td>
                                                 <td ><?php echo $item['previous_emp_pay_in_payband']; ?></td>
                                                 <!--<td><?php //echo $item['previous_consolidated_pay']; ?></td>-->
                                                <td ><?php echo $item['annual_increment_amount']; ?></td>
                                                <?php if($item['status']=='2') 
												{?>
                                                	<td>
                                                    	<a class="btn btn-success" data-toggle="modal" style="opacity:0.5;">ACCEPT</a>
														<a class="btn btn-danger" data-toggle="modal"  style="opacity:0.5;">REJECT</a>
                                                    </td>
                                                <?php 
												} 
												else
												{?>
                                                	<td>
                                                    	<a onClick="confirm_accept(<?= $cnt?>,1);"  class="btn btn-success">ACCEPT</a>
                                                        <a onClick="confirm_accept(<?= $cnt?>,2);"  class="btn btn-danger">REJECT</a>
                                                    </td>
                                                <? } ?>
                                                
                                            </tr>
                                            <input type="hidden" id="emp<?=$cnt;?>" name="emp"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>"/>
                                            <input type="hidden" id="i_amount<?=$cnt;?>" name="i_amount"  value="<?=$cryptoGraph->encode($item['annual_increment_amount'],4);?>"/>
                                            <input type="hidden" id="gp<?=$cnt;?>" name="gp"  value="<?=$_GET['gp_id_fk'];?>"/>
                                            <input type="hidden" id="increment_con<?=$cnt;?>" name="increment_con"  value="<?=$cryptoGraph->encode($item['increment_condisation'],4);?>"/>
                                            <? $cnt+=1; 
										}
									} 
									else 
									{ ?>
                                    <tr>
                                    <td colspan="8" style="color:red;font-weight:bold;">No Data Found</td>
                                    </tr>
                                    <? } ?>
                                    </tbody>
                                
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>



<? //----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
 ?>


<script>

function confirm_accept(k,l)
{
	
	var emp_id=$('#emp'+k).val();
	var gp_id=$('#gp'+k).val();
	var i_amount=$('#i_amount'+k).val();
	var increment_con=$('#increment_con'+k).val();
	//alert(increment_con);
	if(l==1)
	{
		$('#action').val('accept');
		$('#acc_para').show();
		$('#rej_para').hide();
	}
	else
	{
		$('#action').val('reject');
		$('#rej_para').show();
		$('#acc_para').hide();
	}
	
	$('#annu_emp').val(emp_id);
	$('#annu_gp').val(gp_id);
	$('#inc_amt').val(i_amount);
	$('#increment_condition').val(increment_con);
	$('#annu_app_rej').modal('show');
	
	
	
}


    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>



<form name="approve_promostio"  action="ul_emp_annual_increment_approve_reject.php" method="post">
<div class="modal fade bs-example-modal-sm" id="annu_app_rej" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
     
        <button type="button" class="close" id="close1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
	
 <div class="modal-body"> 
      <p class="alert alert-warning" id="acc_para" style="display:none;"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Accept the Increment Amount ?</strong></p>
      <p class="alert alert-warning" id="rej_para" style="display:none;"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Reject the Increment Amount ?</strong></p>
      <input type="hidden" id="action" name="action" />
      <input type="hidden" id="annu_emp" name="annu_emp" />
      <input type="hidden" id="annu_gp" name="annu_gp" />
       <input type="hidden" id="inc_amt" name="inc_amt" />
       <input type="hidden" id="increment_condition" name="increment_condition" />
      </div>
	
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success" />
        <button type="button" data-dismiss="modal" class="btn btn-warning" >NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>

 