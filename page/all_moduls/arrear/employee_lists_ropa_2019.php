<?

session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
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
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}


$logged_user=$_SESSION['user_info']['stake_abbr']; 

$cryptoGraph=new cryptography();

$emp_type=$cryptoGraph->decode($_REQUEST['emp_type'],4); 

//var_dump($emp_type); die;
if($emp_type=='6')
{
	
	 $type='366';
	 $type1=$cryptoGraph->encode($type,4);
}
else if($emp_type=='7')
{
	
	 $type='367';
	 $type1=$cryptoGraph->encode($type,4);
}
else if($logged_user=='EO')
{
	 
	 $type='5';
	 $type1=$cryptoGraph->encode($type,4);
}
else if($logged_user=='BDO')
{
	 $type='5';
	 $type1=$cryptoGraph->encode($type,4);
}
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

function get_emp_name($empid)
{
	$db=new database();
	$emp_name=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name from prd_employee_master where emp_id_pk='".$empid."'");
	$emp_full_name=$emp_name[0]['emp_first_name']." ".$emp_name[0]['emp_second_name']." ".$emp_name[0]['emp_last_name'];
	return $emp_full_name;
}

function get_gp_name($gpid)
{
	$db=new database();
	$gp=$db->fetch_table("select gp_name from prd_location_master_gp where gp_id_pk='".$gpid."'");
	$gp_name=$gp[0]['gp_name'];
	return $gp_name;
}

function get_ps_name($psid)
{
	$db=new database();
	$ps=$db->fetch_table("select ps_name from prd_location_master_panchayat_samiti where ps_id_pk='".$psid."'");
	$ps_name=$ps[0]['ps_name'];
	return $ps_name;
}

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

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

<?php
if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
	 
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});

</script>

<script>



function edit_arrear(k)
{
	//alert(k);
	var link=k.split('&');
	var emp_type1="<?php echo $type1;?>";
	//alert(emp_type1);
		$('#myModal').modal('show');
		$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/ajax_arrear_individual_edit_ropa_2019.php?gp_id='+link[0]+'&emp_id='+link[1]+'&emp_type='+emp_type1, function(data){
		//$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/datepicker_test.php?gp_id='+link[0]+'&emp_id='+link[1], function(data){
			//alert(data);
			$("#mbody").html(data);
			var dt = new Date(); //Grab the current Date
			dt.setDate(1);       //Set it to the first of the month
			dt.setHours(-1);     //Subtract an hour to yield the previous date (Last date of previous month)		
		
		var total_row=$('#total_row').val();
		var i;
		for(i=1;i<=total_row;i++)
		{
			<?php if($logged_user=='FC&CAO'){ ?>
			$( "#arrear_to_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				minDate: new Date(2020, 12, 1),
				maxDate: dt
			});
			
			$( "#arrear_fm_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				minDate: new Date(2020, 12, 1),
				maxDate: dt
			});
			//$('.arrear_to_date'+i).datepicker({ MinDate: msg });
			<?php } 
			else if($logged_user=='EO'){?>
				
				$( "#arrear_to_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				autoHide: true,
				//minDate: new Date(2017, 12, 1),
				minDate: new Date('2020-12-01'),
				maxDate: dt
			});
			
			$( "#arrear_fm_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				autoHide: true,
				//minDate: new Date(2017, 12, 1),
				minDate: new Date('2020-12-01'),
				maxDate: dt
			});
			//$('.arrear_to_date'+i).datepicker({ MinDate: msg });
			
			<?php }
			else if($logged_user=='BDO'){ ?>
				$( "#arrear_to_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				minDate: new Date(2020, 12, 1),
				maxDate: dt
			});
			
			$( "#arrear_fm_date"+i).datepicker({
				beforeShow: function(input, inst) 
				{
				$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
				$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				//yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				minDate: new Date(2020, 12, 1),
				maxDate: dt
			});
			//$('.arrear_to_date'+i).datepicker({ MinDate: msg });
			<?php } ?>
		} 
	});
	
}

function save_arrear(k)
{
	var link=k.split('&');
	var sec_token=$('#sec_token').val();
	
	
	$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/ajax_arrear_save_ropa_2019.php?gp_id='+link[1]+'&emp_id='+link[2]+'&sec_tok='+sec_token, function(data){
		//alert(data);
			var result = $.parseJSON(data);
			
			var sal_save=result[0];
			//alert(link[4]);
			var total_arrear=result[1];
			var total_saved_arrear=result[2];
			var emp_name=result[3];
			if(sal_save==2)
			{
				$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>The Arrear of '+emp_name+' has been successfully saved.</strong></div>');
		<!--$('#save_image1').html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit">');-->
				$('#save_image'+link[4]).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit">');
				
				   //$('#finalize_show').show();
				
			}
			
			if(total_arrear==total_saved_arrear)
			{
				$('#finalize_show').show();
			}
			else
			{
				$('#finalize_show').hide();
			}
		});
	
}

function view_deduction(k)
{
	var deduction=$('#tot_ded'+k).val();
	arr_deduction=deduction.split('&');
	
	var gpf=arr_deduction[0];
	var pf_loan=arr_deduction[1];
	var ptax=arr_deduction[2];
	var itax=arr_deduction[3];
	var overdrawn=arr_deduction[4];
	var gsli=arr_deduction[5];
	//var festival_adv=arr_deduction[5];

	var gp_name=$('#gp_name'+k).text();
	var emp_name=$('#emp_full_name'+k).text();
	$('#deduction_modal').modal('show');
	$('#deduct_emp').html(emp_name);
	$('#deduct_gp').html(gp_name);
	$('#deduct_gpf').html(gpf);
	$('#deduct_pfl').html(pf_loan);
	$('#deduct_ptax').html(ptax);
	$('#deduct_itax').html(itax);
	$('#deduct_gsli').html(gsli);
	//$('#deduct_festival_adv').html(festival_adv);

}

function load_page()
{
	location.reload();
}

</script>

<?
$db=new database();
$finalize_count=0;

if($logged_user=='EO')
{
	
	$emp_arrear_fetch=$db->fetch_table("
										SELECT 
										emp_id_fk,
										emp_id_const,
										zp_emp_type,
										sum(consolidated_pay) as cp_sum,
										sum(pay_payband) as pb_sum,
										sum(grade_pay) as gp_sum,
										sum(basic) as basic_sum,
										sum(da) as da_sum,
										sum(hra) as hra_sum,
										sum(ma) as ma_sum,
										sum(conv_allow) as cv_sum,
										sum(hill_allowance) as ha_sum,
										sum(interim_relief) as ir_sum,
										sum(gross_salary) as gs_sum,
										sum(gpf) as gpf_sum,
										sum(cpf) as cpf_sum,
										sum(pf_loan) as pfl_sum,
										sum(p_tax) as ptax_sum,
										sum(i_tax) as itax_sum,
										sum(overdrawn) as ov_sum,
										sum(net) as net_sum,
										status_flag,
										is_saved,
										delete_status,
										sum(CAST (working_days as INTEGER)) as wd_sum,
										ps_id_fk,
										ropa_status,
										ropa_level,
										sum(gsli) as gsli_sum
										 FROM prd_employee_arrear WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND status_flag in (1,2) AND delete_status='1' AND salary_monthyear is null AND ropa_status='1'
										 GROUP BY ps_id_fk,emp_id_fk,emp_id_const,status_flag,is_saved,delete_status,zp_emp_type, ropa_status, ropa_level

	");
	
	$save_count=$db->fetch_table("
								SELECT 
								COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
								COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
								FROM prd_employee_arrear WHERE delete_status=1 AND salary_monthyear is null AND ps_id_fk='".$_SESSION['location']['ps_id']."'
							");



}
else if($logged_user=='BDO')
{
	
	$emp_arrear_fetch=$db->fetch_table("
										SELECT 
										gp_id_fk,
										emp_id_fk,
										emp_id_const,
										zp_emp_type,
										sum(consolidated_pay) as cp_sum,
										sum(pay_payband) as pb_sum,
										sum(grade_pay) as gp_sum,
										sum(basic) as basic_sum,
										sum(da) as da_sum,
										sum(hra) as hra_sum,
										sum(ma) as ma_sum,
										sum(conv_allow) as cv_sum,
										sum(hill_allowance) as ha_sum,
										sum(interim_relief) as ir_sum,
										sum(gross_salary) as gs_sum,
										sum(gpf) as gpf_sum,
										sum(cpf) as cpf_sum,
										sum(pf_loan) as pfl_sum,
										sum(p_tax) as ptax_sum,
										sum(i_tax) as itax_sum,
										sum(overdrawn) as ov_sum,
										sum(net) as net_sum,
										status_flag,
										is_saved,
										delete_status,
										sum(CAST (working_days as INTEGER)) as wd_sum,
										ropa_status,
										ropa_level,
										sum(gsli) as gsli_sum
										 FROM prd_employee_arrear 
										 WHERE block_code='".$_SESSION['location']['block_code']."' AND status_flag in (1,2) AND delete_status='1' AND salary_monthyear is null AND gp_id_fk!=0 AND ropa_status='1'
										 GROUP BY gp_id_fk,emp_id_fk,emp_id_const,status_flag,is_saved,delete_status,zp_emp_type, ropa_status, ropa_level

	");
	
	$save_count=$db->fetch_table("
								SELECT 
								COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
								COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
								FROM prd_employee_arrear WHERE delete_status=1 AND salary_monthyear is null AND block_code='".$_SESSION['location']['block_code']."'
							");


}

else if($logged_user=='FC&CAO')
{
	

	$emp_arrear_fetch=$db->fetch_table("
										SELECT
										zp_id_fk, 
										zp_emp_type,
										emp_id_fk,
										emp_id_const,
										sum(consolidated_pay) as cp_sum,
										sum(pay_payband) as pb_sum,
										sum(grade_pay) as gp_sum,
										sum(basic) as basic_sum,
										sum(da) as da_sum,
										sum(hra) as hra_sum,
										sum(ma) as ma_sum,
										sum(conv_allow) as cv_sum,
										sum(allowance) as allow_sum,
										sum(hill_allowance) as ha_sum,
										sum(interim_relief) as ir_sum,
										sum(gross_salary) as gs_sum,
										sum(gpf) as gpf_sum,
										sum(cpf) as cpf_sum,
										sum(pf_loan) as pfl_sum,
										sum(p_tax) as ptax_sum,
										sum(i_tax) as itax_sum,
										sum(gsli) as gsli_sum,
										sum(overdrawn) as ov_sum,
										sum(net) as net_sum,
										status_flag,
										is_saved,
										delete_status,
										sum(CAST (working_days as INTEGER)) as wd_sum,
										ropa_status,
										ropa_level
										 FROM prd_employee_arrear 
										 WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND status_flag in (1,2) AND delete_status='1' AND salary_monthyear is null AND zp_id_fk!=0 AND zp_emp_type='".$type."' AND ropa_status='1'
										 GROUP BY zp_id_fk,zp_emp_type,emp_id_fk,emp_id_const,status_flag,is_saved,delete_status, ropa_status, ropa_level

	");

	$save_count=$db->fetch_table("
								SELECT 
								COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
								COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
								FROM prd_employee_arrear WHERE delete_status=1 AND salary_monthyear is null AND zp_id_fk='".$_SESSION['location']['district_id']."'  AND zp_emp_type='".$type."'
							");


}





for($i=0;$i<count($emp_arrear_fetch);$i++)
{
	if($emp_arrear_fetch[$i]['status_flag']=='2')
	{
		$finalize_count=1;
	}
	else
	{
		$finalize_count=0;
		break;
	}
}

$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM prd_admin_paychange
							WHERE flag = 't' AND ropa_year='2019'
						");
			$da_per = $paychange[0]['paychange_da']; 
			$max_ma = $paychange[0]['paychange_ma'];
			$hra_per = $paychange[0]['paychange_hra'];
			$cpf_per = $paychange[0]['paychange_cpf'];
			$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
			$hill_allowance_per = $paychange[0]['hill_allowance'];


?>
<style>
h1 {
display: block;
font-size: 2em;
-webkit-margin-before: 0.67em;
-webkit-margin-after: 0.67em;
-webkit-margin-start: 0px;
-webkit-margin-end: 0px;
font-weight: bold;
}
</style>
<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%">
<h1 class="heading">EMPLOYEE LIST</h1>
<?php

if($logged_user=='EO')
{
	$emp_details_fetch=$db->fetch_table("SELECT net,arrear_id_pk FROM prd_employee_arrear WHERE delete_status='1' AND status_flag='2' AND salary_monthyear is null AND ps_id_fk='".$_SESSION['location']['ps_id']."' AND ropa_status = '1'");   

}
else if($logged_user=='BDO')
{
	$emp_details_fetch=$db->fetch_table("SELECT net,arrear_id_pk FROM prd_employee_arrear WHERE delete_status='1' AND status_flag='2' AND salary_monthyear is null AND block_code='".$_SESSION['location']['block_code']."' AND ropa_status = '1'");   
}
else if($logged_user=='FC&CAO')
{
	$emp_details_fetch =$db->fetch_table("SELECT net,arrear_id_pk FROM prd_employee_arrear WHERE delete_status='1' AND status_flag='2' AND 
											salary_monthyear is null AND zp_id_fk='".$_SESSION['location']['district_id']."' AND zp_emp_type='".$type."' AND ropa_status = '1' "); 

//var_dump($emp_details_fetch); die;											
	/*$emp_details_fetch_367 =$db->fetch_table("SELECT net,arrear_id_pk FROM prd_employee_arrear WHERE delete_status='1' AND status_flag='2' AND 
											salary_monthyear is null AND zp_id_fk='".$_SESSION['location']['district_id']."' AND zp_emp_type='".$type." ");   */
}
 

if(count($emp_details_fetch)>0)
{
	if($logged_user=='EO')
	{?>
    <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/bill_unlock_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-danger btn-sm" style="float: right;">Bill Unlock</a>
	<a href="<?= $config['base_url'] ?>page/all_moduls/arrear/text_file_ps_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm" style="float: right;">Bill Generation </a>
	<?php 
	}
	
	else if($logged_user=='FC&CAO')
	{	
		if($type == '366' && count($emp_details_fetch) >0 )
		{ 
		?>  <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/bill_unlock_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-danger btn-sm" style="float: right;">Bill Unlock</a>
			<a href="<?= $config['base_url'] ?>page/all_moduls/arrear/text_file_zp_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm" style="float: right;">Bill Generation</a>
            
		<?php
		 }
		else if($type == '367' && count($emp_details_fetch) >0)
		{ 
		?>
            <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/bill_unlock_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-danger btn-sm" style="float: right;">Bill Unlock</a>
			<a href="<?= $config['base_url'] ?>page/all_moduls/arrear/text_file_zp_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm" style="float: right;">Bill Generation</a>
            
		<?php } 
	}
	else
	{ ?>
     <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/bill_unlock_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-danger btn-sm" style="float: right;">Bill Unlock</a>
	 <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/text_file_ps_ropa_2019.php?emp_type=<?php echo $cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm" style="float: right;">Bill Generation</a>
    
    
    
	
	<?php }?>
<?php 
}else
{ ?>
	<button class="btn btn-info btn-sm" id="view_arrear" name="view_arrear" style="float: right;" onClick="view_arrear();">Arrear Submission</button>
<?php } ?>
</br>
</br>
<div id="sess_msg" class="border_val">
<?   
if(isset($_SESSION['msg']))
{
	echo "<strong>".$_SESSION['msg']."</strong>";
	unset($_SESSION['msg']);
}
?>
</div>
<!--<div class="border_val"></div>-->

<div class="emplist">
<div class="school">
<div class="table-responsive">
<input type="hidden" id="sec_token" value="<?php echo $enc_token; ?>" />
<table width="100%">
<tr>
<?php if($logged_user=='EO')
{
	echo "<th style='display:none'>PS Name</th>";

}
else if($logged_user=='BDO')
{
		echo "<th>GP Name</th>";

} ?>

<th>EMPLOYEE NAME</th>
<th>Working Days</th>
<th>CONSO-<br>LIDATED<br>PAY</th>
<th>BASIC PAY</th>
<th>LEVEL</th>
<th>D.A(<?php echo $da_per; ?>%)</th>
<th>H.R.A(<?php echo $hra_per; ?>%)</th>
<th>M.A</th>
<th>CONV<br>ALLOW</th>
<?php if($logged_user=='FC&CAO'){ ?><th> ADMINISTRATIVE <br>ALLOWANCE</th> <?php } ?>
<th>HIll AllOW<span  style="font-size:9px;">(min 15%)</span></th>
<th>INTERIM RELIEF</th>
<th>GROSS<br>SALARY</th>
<th colspan="2">TOTAL DEDUCTION</th>
<th>NET</th>
<th colspan="2">ACTION</th>
</tr>
<? $cnt=1;
if(count($emp_arrear_fetch)){ foreach($emp_arrear_fetch as $item){ 

$total_deduction=$item['gpf_sum']+$item['pfl_sum']+$item['ptax_sum']+$item['itax_sum']+$item['ov_sum']+$item['gsli_sum'];

?>
<tr>
	
	<?php if($logged_user=='EO')
    { ?>
    	<td style='display:none' id="gp_name<?=$item['emp_id_fk']?>"><?php echo get_ps_name($item['ps_id_fk']); ?></td>
    
   <?php }
    else if($logged_user=='BDO')
    { ?>
    	<td id="gp_name<?=$item['emp_id_fk']?>"><?php echo get_gp_name($item['gp_id_fk']); ?></td>
    <?php } ?>
    
	<td id="emp_full_name<?=$item['emp_id_fk']?>"><?php echo get_emp_name($item['emp_id_fk']); ?></td>
	<td><?php echo $item['wd_sum']; ?></td>
	<td><?php echo $item['cp_sum']; ?></td>
	<td><?php echo $item['pb_sum']; ?></td>
	<td><?php echo $item['ropa_level']; ?></td>
	<td><?php echo $item['da_sum']; ?></td>
	<td><?php echo $item['hra_sum']; ?></td>
	<td><?php echo $item['ma_sum']; ?></td>
	<td><?php echo $item['cv_sum']; ?></td>
	<?php if($logged_user=='FC&CAO'){ ?><td><?php echo $item['allow_sum']; ?></td> <?php } ?>
	<td><?php echo $item['ha_sum']; ?></td>
	<td><?php echo $item['ir_sum']; ?></td>
	<td><?php echo $item['gs_sum']; ?></td>
	<td><input type="hidden" id="tot_ded<?=$item['emp_id_fk']?>" value="<?php echo $item['gpf_sum']."&".$item['pfl_sum']."&".$item['ptax_sum']."&".$item['itax_sum']."&".$item['ov_sum']."&".$item['gsli_sum'] ?>" /><?php echo $total_deduction; ?></td>
    <td><a id="<?=$item['emp_id_fk']?>" onClick="view_deduction(this.id);"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="View"/></a></td>
    	<td><?php echo $item['net_sum']; ?></td>
    <?php if($item['status_flag']==2)
	 { ?>
    <td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" title="View" style="opacity:0.5;" /></td>
    <td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit" style="opacity:0.5;" /></td>
    <?php } else
	 { ?>
	 <td class="edit"><a <?php if($logged_user=='EO') { ?>id="<?php echo $cryptoGraph->encode($item['ps_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4); ?>" <?php }  else if($logged_user=='BDO'){ ?> id="<?php echo $cryptoGraph->encode($item['gp_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4); ?>" <?php } else if($logged_user=='FC&CAO'){ ?> id="<?php echo $cryptoGraph->encode($item['zp_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4);?>" <?php } ?>  onClick="edit_arrear(this.id);" ><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" title="Edit"></a></td>
     <?php if($item['is_saved']=='1')
	 
	
	  { ?>
       
					<td id="save_image1"><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit"></td>
      <?php } 
	  
	 else
	   {?>

        <td class="save" id="save">
        	<div id="save_image<?=$cnt;?>">
                <a <?php if($logged_user=='EO') { ?>
                 id="save&<?php echo $cryptoGraph->encode($item['ps_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4).'&'.$cnt; ?>" 
				 <?php } 
				 else if($logged_user=='BDO'){ ?> 
                  id="save&<?php echo $cryptoGraph->encode($item['gp_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4).'&'.$cnt; ?>" 
				  <?php } 
                  else if($logged_user=='FC&CAO'){ ?> 
                  id="save&<?php echo $cryptoGraph->encode($item['zp_id_fk'],4).'&'.$cryptoGraph->encode($item['emp_id_fk'],4).'&'.$cryptoGraph->encode($item['zp_emp_type'],4).'&'.$cnt; ?>" 
				  <?php } ?>
                  onClick="save_arrear(this.id);" >
                	<img width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" title="Save">
                </a> 
               </div>  
        </td>
       <?php } }?>
</tr>
<? $cnt+=1; } ?>
<? } else { ?>
<tr>
<td colspan="21" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>
<?php if($logged_user=='FC&CAO')
{ ?>

<div class="row mb-3" id="finalize_show" style="display:none; margin-left: 44%;">
    <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
    	 <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/arrear_finalize_ropa_2019.php?emp_type=<?=$cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm">Arrear Finalize</a>
    </div>
</div>
<?php }else{?>
<div class="row mb-3" id="finalize_show" style="display:none; margin-left: 44%;">
    <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
    	 <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/arrear_finalize_ropa_2019.php?emp_type=<?=$cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm">Arrear Finalize</a>
    </div>
</div>
<?php }?>
<?php if($logged_user=='FC&CAO')
{ ?>
<?php if(count($emp_arrear_fetch)>0 && $save_count[0]['total_arrear']==$save_count[0]['total_save_arrear'] && $finalize_count!='1') { ?>
    <div class="row mb-3" id="finalize_show" style="display:none; margin-left: 44%;">
        <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
            <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/arrear_finalize_ropa_2019.php?emp_type=<?=$cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm">Arrear Finalize</a>
        </div>
    </div>
<?php }
}else
{ ?>
<?php if(count($emp_arrear_fetch)>0 && $save_count[0]['total_arrear']==$save_count[0]['total_save_arrear'] && $finalize_count!='1' ) { ?>
    <div class="row mb-3" id="finalize_show" style="display:none; margin-left: 44%;">
        <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
            <a href="<?= $config['base_url'] ?>page/all_moduls/arrear/arrear_finalize_ropa_2019.php?emp_type=<?=$cryptoGraph->encode($type,4); ?>" class="btn btn-success btn-sm">Arrear Finalize</a>
        </div>
    </div>
<?php }
} ?>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>

<? require '../../../page/layout/footer.php'; ?>

<!-------------------------------------------------------------------------- MODAL START --------------------------------------------------------------------------------------------------------------->

<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width:171%; margin-left: -35.5%;">
      <div class="modal-header">
       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="myModalLabel">Employee Arrear Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" onClick="load_page();">Close</button>      
      </div>
    </div>
  </div>
</div>


  
  
  
  
  
    <div class="modal fade" id="deduction_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">Deduction Details</h4>
		  <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        			<div class="school">
                    <div class="table-responsive">
                    <table width="100%">
                    <tr>
                    
                    <th>Employee Name</th>
                    <th>GPF</th>
                    <th>P-Tax</th>
                    <th>I-Tax</th>
                    <th>GSLI</th>
                    <!--<th>Festival Advance Recovery</th>-->
                    </tr>
                    
                    <tr>
                        <!--<td id="deduct_gp"></td>-->
                        <td id="deduct_emp"></td>
                        <td id="deduct_gpf"></td>
                        <td id="deduct_ptax"></td>
                        <td id="deduct_itax"></td>
                        <td id="deduct_gsli"></td>
                        <!--<td id="deduct_festival_adv"></td>-->
                    </tr>
                    </table>
                    </div>
                    </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  
  
  
  
  
  
  
  
  
  
  
  


<div class="modal fade bs-example-modal-sm" id="delete_arrear" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
		<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Delete This Employee Arrear?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <input type="hidden" name="emp_arrear_id" id="emp_arrear_id">
      <input type="hidden" name="table_row_id" id="table_row_id">
      <button name="del_arr" id="del_arr" onClick="confirm_delete_arrear();" class="btn btn-success">YES </button>
       <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button> 
        </form>     
      </div>
      </div>
    </div>
  </div>
</div>


<script>



function view_arrear()
{
	var user="<?php echo $logged_user; ?>";
	var emp_type="<?php echo $type; ?>";
	var finalize_count=<?php echo $finalize_count; ?>;
	var emp_type1="<?php echo $type1;?>";
	//alert(emp_type1);
	if(finalize_count=='1')
	{
		alert("Until the finalized arrear bill not been generated, new arrear can not be submitted");
	}
	else
	{	
		if(user=='EO')
		{
			$('#myModal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/ajax_arrear_edit_ps_ropa_2019.php?emp_type='+emp_type1, function(data){
				// alert(data);
				$("#mbody").html(data);
				});
		}
		else if(user=='BDO')
		{
			$('#myModal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/ajax_arrear_edit_ropa_2019.php?emp_type='+emp_type1, function(data){
				// alert(data);
				$("#mbody").html(data);
				});
		}
		
		else if(user=='FC&CAO')
		{
			$('#myModal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/ajax_arrear_edit_zp_ropa_2019.php?emp_type='+emp_type1, function(data){
				 //alert(data);
				$("#mbody").html(data);
				});
		}
	}
		
}
</script>