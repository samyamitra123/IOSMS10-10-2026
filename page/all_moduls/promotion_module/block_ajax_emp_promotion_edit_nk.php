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


error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryp = new cryptography();

$emp_id_pk=$cryp->decode($_GET['id'],4); 

$time_token=time();
//$_SESSION['security_token']=$time_token;
//$enc_token=md5('371371371'.$time_token);


$db = new database();
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$id=$tch[0]['emp_id_pk']; 
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';
$db=new database();

if($_SESSION['user_info']['stake_abbr'] == 'BDO')
{
	$promotion_data = $db->fetch_table("SELECT   
											emp_id_fk,
											emp_pay_scale,
											emp_pay_in_payband,
											emp_grade_pay,
											emp_desig,
											emp_pay_band,
											effective_date,
											increment_type,
											approval_status,
											delete_status,
											gp_id_pk,
											cas_type,
											emp_desig,
											dop_doi,
										  increment_amount 
		
										FROM
											prd_employee_promotion_details inner join 
											prd_location_master_gp on gp_id_fk=gp_id_pk
											inner join prd_location_master_block on block_id_fk=block_id_pk
											
										WHERE
											emp_id_fk = '".$emp_id_pk."'
											AND block_code = '".substr($_SESSION['user_info']['stake_user'],0,7)."'
											AND approval_status in ('2','3')
											AND delete_status = '1'
											");
											
	$gp_id_pk=$promotion_data[0]['gp_id_pk'];
	$gp_or_ps_id_fk = 'gp_id_fk';
	$gp_or_ps_id_fk_val = $promotion_data[0]['gp_id_pk'];
}
else if($_SESSION['user_info']['stake_abbr'] == 'EO')
{
	$promotion_data = $db->fetch_table("SELECT   
											emp_id_fk,
											emp_pay_scale,
											emp_pay_in_payband,
											emp_grade_pay,
											emp_desig,
											emp_pay_band,
											effective_date,
											increment_type,
											approval_status,
											delete_status,
											ps_id_fk,
											cas_type,
											emp_desig,
											dop_doi,
											increment_amount 
											
										FROM
											prd_employee_promotion_details 
											
										WHERE
											emp_id_fk = '".$emp_id_pk."'
											AND ps_id_fk = '".substr($_SESSION['location']['ps_id'],0,7)."'
											AND approval_status in ('2','3')
											AND delete_status = '1'
											");	
												
		$ps_id_fk=$promotion_data[0]['ps_id_fk'];
		$gp_or_ps_id_fk = 'ps_id_fk';
		$gp_or_ps_id_fk_val = $promotion_data[0]['ps_id_fk'];								
}
										
		$emp_count=count($promotion_data); 
		$emp_pay_bandp=$promotion_data[0]['emp_pay_band'];
	    $emp_pay_in_paybandp=$promotion_data[0]['emp_pay_in_payband'];
	    $emp_grade_payp=$promotion_data[0]['emp_grade_pay'];
	    $emp_pay_scalep=$promotion_data[0]['emp_pay_scale']; 
		$emp_desigp = $promotion_data[0]['emp_desig']; 
		$effective_datep = $promotion_data[0]['effective_date'];
		$increment_typep = $promotion_data[0]['increment_type'];
		$single_double_incrementp = $promotion_data[0]['single_double_increment'];
		
		$get_effective_datep_year = explode("-",$effective_datep);
		$effective_datep_year = $get_effective_datep_year[0];
		
		$cas_type=$promotion_data[0]['cas_type'];
		$emp_designation =$promotion_data[0]['emp_desig'];
		$dop_doi =$promotion_data[0]['dop_doi'];
		$increment_amount= $promotion_data[0]['increment_amount']; 
		
if(count($promotion_data) > 0)
{
	$status = $promotion_data[0]['status'];
	$approval_status = $promotion_data[0]['approval_status'];	
	$delete_status = $promotion_data[0]['delete_status'];
}
else
{
	$status = 99;
	$approval_status = 99;	
	$delete_status = 99;	
}

$emp_data = $db->fetch_table("SELECT   
                                        emp_id_pk,
										emp_pay_scale,
										emp_pay_in_payband,
										emp_grade_pay,
										emp_desig,
										emp_pay_band,
										emp_next_increment_date,
										emp_next_increment_amount
										
									FROM
										prd_employee_master 
									WHERE
										emp_id_pk = '".$emp_id_pk."'
										AND ".$gp_or_ps_id_fk." = '".$gp_or_ps_id_fk_val."'
								");
		$emp_count=count($emp_data); 
		$emp_pay_band=$emp_data[0]['emp_pay_band'];
		$emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
		$emp_grade_pay=$emp_data[0]['emp_grade_pay'];
		$emp_pay_scale=$emp_data[0]['emp_pay_scale']; 
		$emp_desig = $emp_data[0]['emp_desig']; 
		$increment_type = 99;
		$single_double_increment = 99;
		$emp_next_increment_date=$emp_data[0]['emp_next_increment_date'];
		//$emp_next_increment_date='01-07-2018';
		//by nirupam on 11_08_2017 to get the string value of effective date and increment date
		//echo date("Y").'-07-01';
		//echo $effective_datep;
		 $entry_date = date('d-m-Y');
		 $entry_date_str_value = strtotime($entry_date);
		 $effective_date_str_value = strtotime($effective_datep);
		 $increment_date_str_value = strtotime($emp_next_increment_date);
		 $annual_increment_date = '01-07-'.date('Y');
		 $annual_increment_date_str_value = strtotime($annual_increment_date);
		//End of by nirupam on 11_08_2017 to get the string value of effective date and increment date
		
		//$promotion_effective_date=$emp_data[0]['promotion_effective_date'];
		$emp_next_increment_amount=$emp_data[0]['emp_next_increment_amount'];
		//$emp_next_increment_amount=800;
		$emp_payscale_val_search = $db->fetch_table("SELECT payscale_range FROM prd_employee_promotion_details 
													INNER JOIN prd_dise_payscale_master 
													ON prd_dise_payscale_master.payscale_code =prd_employee_promotion_details.emp_pay_scale
													AND prd_employee_promotion_details.emp_id_fk = '".$emp_id_pk."'");
		
		$emp_pay_scale_range = explode('-', $emp_payscale_val_search[0]['payscale_range']);
		$emp_pay_scale_chk = $emp_pay_scale_range[0];
		
		
	


?>
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-1.11.2.min.js"></script>
<script>


//alert('1234');
$(document).ready(function() {
	//alert('1234');
	var dop_doi_db='<? echo $dop_doi; ?>';
	var pay_in_payband_db='<? echo $emp_pay_in_paybandp;?>';
	var increment_amount_db='<? echo $increment_amount;?>';
	 
	if($("#increment").val() == 1)
	{
		$("#type_incre").hide();
	}
	window.value = $("#pay_in_payband").val();
	//alert(window.value);
	
	//effective date checking
	//alert('1234');
	var effective_date = $("#effective_date").val();
	effective_date=effective_date.split("-");
	var new_effective_date = effective_date[1]+"/"+effective_date[0]+"/"+effective_date[2];
	var new_effective_date_t = new Date(new_effective_date).getTime();
	//alert(new_effective_date_t);
	
	Date.prototype.ddmmyyyy = function() {
		  var mm = this.getMonth() + 1; // getMonth() is zero-based
		  var dd = this.getDate();
		  return [(dd>9 ? '' : '0') + dd +'-',
				  (mm>9 ? '' : '0') + mm +'-',
				   this.getFullYear()
				 ].join('');
		};
		//range1
		var date = new Date();
		var today= date.ddmmyyyy();
		today = today.split("-");
		var effective_date_yr = '<?php echo $effective_datep_year; ?>';
		//var new_today_date_range1 = "01"+"/"+"02"+"/"+today[2];
		var new_today_date_range1 = "01"+"/"+"01"+"/"+effective_date_yr; 
		//alert(new_today_date_range1);
		var new_today_date_range1_t = new Date(new_today_date_range1).getTime();
		//range2
		//var new_today_date_range2 = "06"+"/"+"30"+"/"+today[2];
		var new_today_date_range2 = "06"+"/"+"30"+"/"+effective_date_yr; 
		//alert(new_today_date_range2);
		//alert('<?php echo date("Y"); ?>');
		//alert(new_today_date_range1_t);
		var new_today_date_range2_t = new Date(new_today_date_range2).getTime();
		//alert(new_today_date_range2_t);
		//$("#promotion_details2").hide();
		if(Number(dop_doi_db)==3)
		{
			$('#date_increment').prop('checked', true);
			
		}
		else if(Number(dop_doi_db)==4)
		{
			$('#date_promotion').prop('checked', true);
			
		}
		else
		{
			$('.inc_options').prop('checked', false);
		}
		
		
		if((new_effective_date_t >= new_today_date_range1_t) && (new_effective_date_t <= new_today_date_range2_t))
		{
			$("#promotion_details1").show();
			if(Number(dop_doi_db)==3 || Number(dop_doi_db)==4)
			{
				$('#pipb_section').show();
				$('#pay_in_payband').val(pay_in_payband_db);
				$('#increment_amount').val(increment_amount_db);
			}
			else
			{
				$("#pipb_section").hide();
			}
			//var dop_or_doi_applicable = 1;  //newly addedfor validation
		}
		else
		{
			$("#promotion_details1").hide();
			//var dop_or_doi_applicable = 0;  //newly addedfor validation
			//$("#pipb_section").show();
			//var pay_in_payband = promotion_calculation();
			promotion_calculation();
			//alert(pay_in_payband);
		}
	//$(".option_get_promo").hide();
	//$(".option_get_cas").hide();
	   //$("#promotion_details1").hide();
	//$("#promotion_details2").hide();
	
});

function promotion_calculation()
{

 var effective_date = $("#effective_date_new").val();

	effective_date=effective_date.split("-");
//	var new_effective_date = effective_date[1]+"/"+effective_date[0]+"/"+effective_date[2];
//	var new_effective_date_t = new Date(new_effective_date).getMonth();
//if(effective_date[1]>=1 || effective_date[1]<=6){}
//alert(effective_date[1]);

var pre_grade_pay = $("#pre_grade_pay").val();
  var future_grade_pay=$("#future_grade_pay").val();
	if($(".promotion_option:checked").val() == '1')
	{
		if($(".inc_options:checked").val() == 'QRVMSZFRNhnVGFUP')
		{  
		    
		    
			           var pre_pay_in_payband = +$("#pre_pay_in_payband").val();
				   var pre_basic_pay = +pre_pay_in_payband + +pre_grade_pay;
				   var increment = '1.03';
			           var new_basic_pay = (pre_basic_pay * increment);
				   var pay_band_amount=parseInt(new_basic_pay)-parseInt(pre_grade_pay);
				   var future_basic_pay=parseInt(pay_band_amount)+parseInt(future_grade_pay);
				   var double_increment = (future_basic_pay * increment);
				   var new_pay_in_payband = double_increment - future_grade_pay; 
				       //new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
				       if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
				
		}
		else if($(".inc_options:checked").val() == 'QRVMuZVRJhnVGFUP')
		{
				 
				    var pre_pay_in_payband = $("#pre_pay_in_payband").val() ;
				    var pre_grade_pay = $("#pre_grade_pay").val();
				    var pre_basic_pay = +pre_pay_in_payband + +parseInt(future_grade_pay);
				    var increment = '1.03';
				    var new_basic_pay = (pre_basic_pay * increment);
				    var new_pay_in_payband = new_basic_pay - parseInt(future_grade_pay); 
				       //new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
				       if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
		
		}
		else
		{ 
				    var pre_pay_in_payband = $("#pre_pay_in_payband").val() ;
				    var pre_grade_pay = $("#pre_grade_pay").val();
				    var pre_basic_pay = +pre_pay_in_payband + +parseInt(pre_grade_pay);
				    var increment = '1.03';
				    var new_basic_pay = (pre_basic_pay * increment);
				    var new_pay_in_payband = new_basic_pay - parseInt(pre_grade_pay);
				    
				     if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
				    
				    
				       // new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
				   
		}
	}
	else if($(".promotion_option:checked").val() == '2')
	{                
		   
		if($(".inc_options:checked").val() == 'QRVMSZFRNhnVGFUP')
		{ 
		 
				   var pre_pay_in_payband = +$("#pre_pay_in_payband").val();
				   var pre_basic_pay = +pre_pay_in_payband + +pre_grade_pay;
				   var increment = '1.03';
			           var new_basic_pay = (pre_basic_pay * increment);
				   var pay_band_amount=parseInt(new_basic_pay)-parseInt(pre_grade_pay);
				   // alert(pay_band_amount);
				   var future_basic_pay=parseInt(pay_band_amount)+parseInt(pre_grade_pay);
				   var double_increment = (future_basic_pay * increment);
				   var new_pay_in_payband = double_increment - pre_grade_pay; 
				      // new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
				      //alert(new_pay_in_payband);
				      if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
				   
//			var pre_pay_in_payband = $("#pre_pay_in_payband").val();
//			var pre_grade_pay = $("#pre_grade_pay").val();
//			var pre_basic_pay = +pre_pay_in_payband + +pre_grade_pay;
//			var increment = '1.03';
//			var new_basic_pay = (pre_basic_pay * increment);
//			var double_increment = (new_basic_pay * increment);
//			var new_pay_in_payband = double_increment - pre_grade_pay; 
//			new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
			
			
		}
		else if($(".inc_options:checked").val() == 'QRVMuZVRJhnVGFUP')
		{ 
			
			var pre_pay_in_payband = $("#pre_pay_in_payband").val() ;
			var pre_grade_pay = $("#pre_grade_pay").val();
			var pre_basic_pay = +pre_pay_in_payband + parseInt(pre_grade_pay);
			var increment = '1.03';
			var new_basic_pay = (pre_basic_pay * increment);	
			var new_pay_in_payband = new_basic_pay - parseInt(pre_grade_pay);
			//alert(new_pay_in_payband);
			//new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
			if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
			
		}
		else
		{
			
			var pre_pay_in_payband = $("#pre_pay_in_payband").val() ;
			//var pre_grade_pay = $("#pre_grade_pay").val();
			var pre_basic_pay = +pre_pay_in_payband + parseInt(pre_grade_pay);
			var increment = '1.03';
			var new_basic_pay = (pre_basic_pay * increment);	
			var new_pay_in_payband = new_basic_pay - parseInt(pre_grade_pay);
			//new_pay_in_payband = Math.round((new_pay_in_payband/10))*10;
			if(Math.floor(new_pay_in_payband)%10==0){
					var new_pay_in_payband=(Math.floor(new_pay_in_payband/10)*10);
					}else{
					 var new_pay_in_payband=(Math.ceil(new_pay_in_payband/10)*10);
						
					}
			
			
		}
	} 
	$("#pipb_section").show();
	var band_min_payband = '<?php echo $emp_pay_scale_chk; ?>';
	if(Number(band_min_payband) > Number(new_pay_in_payband))
	{
		new_pay_in_payband = band_min_payband;
		var increment_amount = (new_pay_in_payband - pre_pay_in_payband);
	}
	else
	{   
		new_pay_in_payband = new_pay_in_payband;
		var increment_amount = (new_pay_in_payband - pre_pay_in_payband);
		
	}           
	
	$("#increment_amount").val(increment_amount);
	$("#pay_in_payband").val(new_pay_in_payband);
	
	//alert('<?php echo $emp_pay_scale_chk; ?>');
}

function valid_promotion()
{
		//if($(".inc_options:checked").val() == '')
		//alert($(".inc_options:checked").val());
		var effective_date = $("#effective_date").val();
		effective_date=effective_date.split("-");
		var new_effective_date = effective_date[1]+"/"+effective_date[0]+"/"+effective_date[2];
		var new_effective_date_t = new Date(new_effective_date).getTime();
		//alert(new_effective_date_t);
	
		Date.prototype.ddmmyyyy = function() {
		  var mm = this.getMonth() + 1; // getMonth() is zero-based
		  var dd = this.getDate();
		  return [(dd>9 ? '' : '0') + dd +'-',
				  (mm>9 ? '' : '0') + mm +'-',
				   this.getFullYear()
				 ].join('');
		};
		
		var date = new Date();
		
		var effective_date_yr = '<?php echo $effective_datep_year; ?>';
		var new_today_date_range1 = "01"+"/"+"01"+"/"+effective_date_yr; 
		var new_today_date_range1_t = new Date(new_today_date_range1).getTime();
		var new_today_date_range2 = "06"+"/"+"30"+"/"+effective_date_yr; 
		var new_today_date_range2_t = new Date(new_today_date_range2).getTime();
		if((new_effective_date_t >= new_today_date_range1_t) && (new_effective_date_t <= new_today_date_range2_t))
		{
			if(($(".inc_options:checked").val() != 'QRVMSZFRNhnVGFUP') && ($(".inc_options:checked").val() != 'QRVMuZVRJhnVGFUP'))
			{
				alert("Please Select Date of Promotion or Date of Increment.");	
				return false;	
			}
		}
	
	if((dop_or_doi_applicable == 1) && ($('.inc_options').prop('checked', false)))
	{
		alert("Please Select Date of Promotion or Date of Increment.");	
		return false;	
	}
	if(Number(window.value) >= Number($("#pay_in_payband").val()))
	{
		//alert(window.value);
		//alert($("#pay_in_payband").val());
		alert("Please Enter Valid Pay in Payband.");
		$("#pay_in_payband").focus();
		return false;
	}
	if($(".promotion_option:checked").val() == '')
	{
		alert("Please Select Get Promotion or Get Career Advancement Scheme (CAS).");	
		return false;
	}
	return true;
}
</script>

<style>
        .form-horizontal .control-label {
			text-align:left;
			}
        </style>

<?php

$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of The Employee Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data Insertion Failed. Please Try Again...</strong></div>';
}


function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
	
?>
 

<div class="content" style="padding-top:20px !important;">
  
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8"> 
        <div class="col-sm-12">
	     <div class="col-sm-6">
<center><h1 class="heading">EMPLOYEE PROMOTION OPTION SELECTION</h1></center>
<div class="border"></div>
</br>
<?php 
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
echo "<br/>";
}
?>


<script>
//$(function() {
//	$( "#effective_date" ).datepicker({
//		changeMonth: true,
//		changeYear: true,
//		yearRange: "-50:+50",
//		dateFormat: 'dd-mm-yy' 
//	});
//});
</script>
 <script>
 /*  function valid_promotion()
{
	var effective_date = $("#effective_date").val();
		effective_date=effective_date.split("-");
		var new_effective_date = effective_date[1]+"/"+effective_date[0]+"/"+effective_date[2];
		var new_effective_date_t = new Date(new_effective_date).getTime();
	//alert(new_effective_date_t);
	
		Date.prototype.ddmmyyyy = function() {
		  var mm = this.getMonth() + 1; // getMonth() is zero-based
		  var dd = this.getDate();
		  return [(dd>9 ? '' : '0') + dd +'-',
				  (mm>9 ? '' : '0') + mm +'-',
				   this.getFullYear()
				 ].join('');
		};
		
		var date = new Date();
		//var today= date.ddmmyyyy();
		var effective_date_yr = '<?php echo $effective_datep_year; ?>';
		var new_today_date_range1 = "01"+"/"+"02"+"/"+effective_date_yr; 
		var new_today_date_range1_t = new Date(new_today_date_range1).getTime();
		var new_today_date_range2 = "06"+"/"+"30"+"/"+effective_date_yr; 
		var new_today_date_range2_t = new Date(new_today_date_range2).getTime();
		if((new_effective_date_t >= new_today_date_range1_t) && (new_effective_date_t <= new_today_date_range2_t))
		{
	
		var type_3=$('#date_increment').is(':checked');
		var type_4=$('#date_promotion').is(':checked');
		
			if(!(type_3 || type_4))
			{
			
				alert('Please Select Date of Increment or Date of promotion.');
				//$('#cas_type10').focus();
				return false;
			}
		}
			if ($('#pay_in_payband').val()=='')
			{
				alert('Please Enter Pay in Pay Band.');
				$('#pay_in_payband').focus();
				return false;
			}
		
			if ($('#increment_amount').val()=='')
			{
				alert('Please Enter Increment Ammount.');
				$('#increment_amount').focus();
				return false;
			}
	
  }
   */
   </script>

<!--For increment module-->

<form class="form-horizontal" style="width:500px !important; float:left;" id="loginForm" method="post" action="" onsubmit="return valid_promotion();">
<noscript>Please Enable JavaScript In your Browser</noscript>
<center><h4 class="heading" style="font-weight:bold;">Basic Pay Details of Employee</h4></center><br/>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Pay Band<span class="star_color"></span></label>
    <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payband_code,payband_name from prd_payband_master order by payband_code");
				?>
      <select class="form-control" name="pay_band1" disabled="disabled"; style="background-color:#d3d3d3;">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['payband_code']. '<br />'; ?>
       <option <?php if($cryptoGraph->encode($emp_pay_band,4) == $cryptoGraph->encode($key['payband_code'],4)){ echo "selected"; } ?> value="<?= $cryptoGraph->encode($key['payband_code'],4); ?>" <? if($emp_pay_band==$key['payband_code'] || $pay_band==$key['payband_code']){ echo "selected";}?>><?= $key['payband_name']; ?></option>
       <? } ?>
      </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Pay Scale<span class="star_color"></span></label>
  <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payscale_range,payscale_code from prd_dise_payscale_master where payscale_code='$emp_pay_scale'");
				?>
      <select class="form-control" id="pay_scale1" disabled="disabled" style="background-color:#d3d3d3;">
      <option value="">-Please Select-</option>
		<? foreach($arr as $key){ $key['payscale_code']. '<br />'; ?>
        <option value="<?= $key['payscale_code']; ?>" <? if($emp_pay_scale==$key['payscale_code'] || $pay_scale==$key['payscale_code']){ echo "selected";}?>><?= $key['payscale_range']; ?></option>
        <? } ?>
      </select>
      <input type="hidden" value="<?= $key['payscale_range']; ?>" />
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Pay in Pay Band<span class="star_color"></span></label>
    <div class="col-sm-4">
      <input style="background-color:#d3d3d3;" type="text" id="pre_pay_in_payband" class="form-control" name="pay_in_payband1" placeholder="PAY IN PAY BAND" autocomplete="off" value="<? if(!empty($emp_pay_in_payband)){ echo $emp_pay_in_payband; }else{ echo $pay_in_payband;} ?>" onKeyPress="return keyRestrict(event,'0123456789');" maxlength="5" readonly>
    </div>
     <label for="inputPassword3" class="col-sm-2 control-label">Grade Pay<span class="star_color"></span> </label>
  
  <div class="col-sm-4">
   				<?
				$db = new database();
				
				$arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND grade_code='$emp_grade_pay' order by grade_code ");
    		
	  ?>
       
      <select style="background-color:#d3d3d3;" class="form-control" name="grade_pay1" disabled="disabled" id="pre_grade_pay">
      
       <? foreach($arr as $key){ ?>
       <option value="<?= $key['grade_amount']; ?>" <? if($emp_grade_pay==$key['grade_code'] || $grade_pay==$key['grade_code']){ echo "selected";}?>><?= $key['grade_amount']; ?></option>
       <? } ?>
      </select>
    </div>
  
  </div>

  
  <div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">Designation<span class="star_color"></span></label>
    <div class="col-sm-4">
      <div>
     	<select style="background-color:#d3d3d3;" class="form-control" name="vice_desig1" disabled="disabled">
        	<option value="">-Please Select-</option>
            <?php $desig = $db->fetch_table("SELECT code,description FROM prd_dise_code_master WHERE code ='".$emp_desig."'"); ?>
            <option selected value="<?php echo $desig[0]['code'] ?>"><?php echo $desig[0]['description'] ?></option>
            
        </select>
     </div>
     
    </div>
    
   
  </div>
  



</form>
	     </div>
<div class="col-sm-6">
<form class="form-horizontal" style="min-height: 250px; width:500px !important; padding-left:20px;  border-left:dashed;" id="loginForm" method="post" action="block_emp_promotion_submission.php" onsubmit="return valid_promotion();">
<noscript>Please Enable JavaScript In your Browser</noscript>
<center><h4 class="heading" style="font-weight:bold;">Insert Promotion Details of Employee</h4></center><br/>
 <input type="hidden" name="emp_id_pk" value="<?= $cryptoGraph->encode($emp_id_pk,4) ?>" />
 <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
 <input type="hidden" name="status" id="status" value="<?=$status?>" />
 <input type="hidden" name="approval_status" id="approval_status" value="<?=$approval_status?>" />
 <input type="hidden" name="delete_status" id="delete_status" value="<?=$delete_status?>" />
 <input type="hidden" name="effective_date" id="effective_date_new" value="<?=$effective_datep?>" />
 <input type="hidden" name="effective_date_of_promotion_or_cas" id="effective_date_of_promotion_or_cas" value="<?=$effective_date_str_value?>" />
 <input type="hidden" name="date_of_next_increment" id="date_of_next_increment" value="<?=$increment_date_str_value?>" /> 
 <input type="hidden" name="entry_date_str_value" id="entry_date_str_value" value="<?=$entry_date_str_value?>" /> 
 <input type="hidden" name="annual_increment_date_str_value" id="annual_increment_date_str_value" value="<?=$annual_increment_date_str_value?>" /> 

  <div class="form-group"> 
    <label class="col-sm-3 control-label">Effective Date<span class="star_color">*</span></label>
    <div class="col-sm-4">
    	<input type="text" class="form-control" id="effective_date" name="effective_date" value="<?php echo dateshow($effective_datep); ?>" placeholder="DD-MM-YYYY" readonly style="background-color:#FFF;cursor:pointer;" disabled="disabled" />
    </div>
  </div>
  
   <div class="form-group" style="padding-left:15px;"> 
  
  <!-- <label><strong>Do You want to Give Promotion Or CAS?</strong><span class="star_color">*</span></label>-->
  <?php if($increment_typep == '1'){ ?>
    <label class="option_get_promo"><input class="promotion_option" type="radio" value="1" name="increment_type" id="option_get_promo" onclick="return promotion_or_cas();" <?php if($increment_typep == '1'){ echo 'checked'; } ?> disabled="disabled"/>  Get Promotion</label>
  <?php } else if($increment_typep == '2') { ?>
    <label class="option_get_cas"><input class="promotion_option" type="radio" value="2" name="increment_type" id="option_get_cas" onclick="return promotion_or_cas();" <?php if($increment_typep == '2'){ echo 'checked'; } ?> disabled="disabled"/>  Get Career Advancement Scheme (CAS)</label>
   <div class="form-group" style="padding-left:15px;"> 
  <label class="option_cas_type">CAS Type <span class="star_color">*</span> :</label>
    <label class="option_cas_type"><input class="cas_type" type="radio" value="<?=$cryptoGraph->encode('10',4)?>" name="cas_type" id="cas_type10"  <?php if($increment_typep == '2' && $cas_type=='10'){ echo 'checked'; } ?> disabled="disabled"/>10 Year</label>
      <!--<label class="option_cas_type"><input class="cas_type" type="radio" value="<?=$cryptoGraph->encode('18',4)?>" name="cas_type" id="cas_type18" <?php if($increment_typep == '2' && $cas_type=='18'){ echo 'checked'; } ?> disabled="disabled"/> 18 Year</label>-->
    <label class="option_cas_type"><input class="cas_type" type="radio" value="<?=$cryptoGraph->encode('20',4)?>" name="cas_type" id="cas_type20" <?php if($increment_typep == '2' && $cas_type=='20'){ echo 'checked'; } ?> disabled="disabled"/> 20 Year</label>
 </div>
  <?php } ?>
  </div>
  
  
<div id="promotion_details2">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Pay Band<span class="star_color">*</span></label>
    <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payband_code,payband_name from prd_payband_master order by payband_code");
				?>
      <select class="form-control" name="pay_band" id="pay_band" onchange="payband(this.value)" disabled="disabled">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['payband_code']. '<br />'; ?>
       <option <?php if($cryptoGraph->encode($emp_pay_bandp,4) == $cryptoGraph->encode($key['payband_code'],4)){ echo "selected"; } ?> value="<?=  $cryptoGraph->encode($key['payband_code'],4); ?>" <? //if($emp_pay_band==$key['payband_code'] || $pay_band==$key['payband_code']){ echo "selected";}?>><?= $key['payband_name']; ?></option>
       <? } ?>
      </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Pay Scale<span class="star_color">*</span></label>
  <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payscale_range,payscale_code from prd_dise_payscale_master where payscale_code='$emp_pay_scalep'");
				?>
      <select class="form-control payscale" name="pay_scale" id="pay_scale" disabled="disabled">
      <option value="">-Please Select-</option>
		<? foreach($arr as $key){ $key['payscale_code']. '<br />'; ?>
        <option value="<?=$key['payscale_code']; ?>" <? if($emp_pay_scalep==$key['payscale_code']){ echo "selected";}?>><?=$key['payscale_range']; ?></option>
        <? } ?>
      </select>
      <input type="hidden" id="pay_scale_range" value="<?=$key['payscale_range']; ?>" />
    </div>
  </div>
 
  <div class="form-group">
     <label for="inputPassword3" class="col-sm-2 control-label">Grade Pay<span class="star_color">*</span> </label>
  <div class="col-sm-4">
   				<?
				$db = new database();
				
				$arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND grade_code='$emp_grade_payp' order by grade_code ");
				?>
      <input type="hidden" name="future_grade_pay" id="future_grade_pay" value="<?= $arr[0]['grade_amount']; ?>" />
      <select class="form-control gradepay" name="grade_pay" id="grade_pay" onchange="grade(this.value)" disabled="disabled">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
       <option value="<?=$key['grade_code']; ?>" <? if($emp_grade_payp==$key['grade_code']){ echo "selected";}?>><?=$key['grade_amount']; ?></option>
       <? } ?>
      </select>
    </div>
    <?php if($increment_typep == '1')
	{ ?>
    <label for="inputEmail3" class="col-sm-2 control-label">Designation<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <div  id="vice_desig">
     	<select class="form-control vice_designation" name="vice_desig" disabled="disabled">
        	<option value="">-Please Select-</option>
            <?php //$desig = $db->fetch_table("SELECT designation_id,designation_name FROM mad_master_designation WHERE designation_id ='".$emp_desig."'"); ?>
            <?php $desig = $db->fetch_table("SELECT code,description FROM prd_dise_code_master WHERE code ='".$emp_designation."'"); ?>
            <?php foreach($desig as $designations){ ?>
            <option <?php if($designations['code'] == $emp_desigp){ echo 'selected'; }?> value="<?php echo $designations['code'] ?>"><?php echo $designations['description'] ?></option>
            <?php } ?>
        </select>
     </div>
     
    </div>
  <?  } ?>
  </div>

  <div class="form-group" style="padding-left:15px;" id="promotion_details1"> 
  
      <label><strong style="color:red;">Choose Date of Increment Or Date of Promotion</strong><span class="star_color"> *</span></label>:<br>
   <label style="float:left; margin-right: 20px;"><input type="radio" class="inc_options" name="inc_options" id="date_increment" onclick="return promotion_calculation();" value="<?=$cryptoGraph->encode('3',4)?>" <?php if($dop_doi== '3'){ echo 'checked'; } ?>/> Date of Increment</label>
   <label style="float:left;"><input type="radio" class="inc_options" name="inc_options"  id="date_promotion"onclick="return promotion_calculation();" value="<?=$cryptoGraph->encode('4',4)?>" <?php if($dop_doi=='4'){ echo 'checked="checked"'; } ?>/>  Date of Promotion</label>
  </div>
  
  <div class="form-group" id="pipb_section">
    <label for="inputPassword3" class="col-sm-2 control-label">Pay in Pay Band<span class="star_color">*</span></label>
    <div class="col-sm-4" style="align:left;">
      <input type="text" class="form-control" name="pay_in_payband" id="pay_in_payband" placeholder="Pay in Pay Band" autocomplete="off" value="<? if(!empty($emp_pay_in_payband)){ echo $emp_pay_in_payband; }else{ echo $pay_in_payband;} ?>" onKeyPress="return keyRestrict(event,'0123456789');" maxlength="5">
    </div>
    
    <label for="inputPassword3" class="col-sm-2 control-label">Increment Amount<span class="star_color">*</span></label>
    <div class="col-sm-4" style="align:left;">
      <input type="text" class="form-control" name="increment_amount" id="increment_amount" placeholder="Increment Amount" autocomplete="off" value="" onKeyPress="return keyRestrict(event,'0123456789');" maxlength="5">
    </div>
  </div>
  <br />
  
  <div class="form-group">
    <div class="col-sm-8" align="center">
    <?php  
	$current_date = date("d-m-Y");
	$current_date_t = strtotime($current_date);
	$range_start_date = '02-07-'.date("Y");
	$range_start_date_t = strtotime($range_start_date);
	$range_end_date = '31-12-'.date("Y");
	$range_end_date_t = strtotime($range_end_date);
	
	
	?>
      <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <!--<i class="fa fa-chevron-right"></i>--></button>

    </div>
  </div>
  
</div>
<!--End of promotion_details2 -->
 
</form>
</div>
<!--End of increment module-->

 <p style="border-top:1px dashed #27769F; text-align:center;"></p>

        </div>
        
      </div>

    </div>
    </div>
    <div class="clear"></div>
    
  
    
    
    
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  

  