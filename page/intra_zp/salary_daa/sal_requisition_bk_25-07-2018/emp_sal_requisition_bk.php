<?php
ob_start();
session_start();

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}


if(!isset($_SERVER['HTTP_REFERER'])){
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

$crypto = new cryptography();

			$db = new database();
			$salary_monthyear = date('Ym');
			$school_code = $_SESSION['user_info']['stake_user'];
			/*$check_dise = $db->fetch_table("SELECT schcd FROM ehrms_monthly_salary_table_fix
							WHERE schcd='$school_code' AND salary_monthyear='$salary_monthyear'");
			if(!$check_dise[0]['schcd']){
				header('Location: '. $config['base_url'] . "page/login.php");
				exit;
			}*/
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Salary Requisition | P&RD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>

<div class="page-content">
    <div class="content">
    <? require '../../../../page/common_back_btns.php'; ?>
    <h1 class="heading" style="text-align:center">EMPLOYEE SALARY REQUISITION</h1>
<h2 class="heading" style="text-align:center">Salary Month Year : <?php echo date('F').', '.date('Y') ?></h2>
<br/>
     <div class="page-title">
       
<div class="row" id="cont">

	
			<div class="save_alert">
				<div id="saving" style="display: none">
					<div class="bor">
						<h3>Saving...<img height="20" src="<?php echo $config['base_url'] ?>themes/default/image/preloader.gif" /></h3>
					</div>
				</div>
			</div>
            
			<div id="dialog-confirm" title="Do you want to finalize your requisition?">
				<div class="loading" style="display: none; text-align: center;"><img height="20" src="<?php echo $config['base_url'] ?>themes/default/image/preloader.gif" /></div>
			</div>
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        


<div class="border"></div>



<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/

?>
<div class="border_val"></div>
 <div id="sess_msg">
   <?   
  
   if(($_SESSION['msg']))
			{
				echo "<strong>".$_SESSION['msg']."</strong>";
				
				unset($_SESSION['msg']);
				
			}
			?>
            </div>
            
         <div id="dialog" title="Employee details">
			  	<div id="wait"><img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" /></div>
	  			<div class="dial"></div>
			</div>
            
<div class="emplist">
<div class="school">
<div class="table-responsive">
<?php require_once 'requisition_form.php'; 
  
	

?>
</div>
</div>
</div>
 
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<link rel="shortcut icon" href="<?php echo $config['base_url']; ?>themes/default/img/fav_icon.png">
<link href="<?php echo $config['base_url']; ?>themes/default/css/allinone_carousel.css" rel="stylesheet" type="text/css">
<!--<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css">-->
<link href="<?php echo $config['base_url']; ?>themes/default/css/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $config['base_url']; ?>themes/default/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css">
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
<!--<script src="<?php //echo $config['base_url']; ?>themes/default/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
--><script src="<?php echo $config['base_url']; ?>themes/default/js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/allinone_carousel.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-migrate-1.2.1.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/commonfunc.js" type="text/javascript"></script>
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


function hidemsg(id)
{
$('#sess_msg').hide();
	}



	function ptaxCal(){
		
		var max_ptax = parseInt(<?php echo $max_ptax ?>);
		if($('#p_tax').val() > max_ptax || $('#p_tax').val()==''){
			alert('Please Enter Valid P.Tax Amount.');
			$('#p_tax').val(0);
			if($('#pay_in_band').val()!=0){
			var pay_band = $('#pay_in_band').val();
			var consolidated_pay=0;
			} else {
				var consolidated_pay=$('#consolidated_pay').val();	
				var pay_band=0;
			}
			var grade_pay = $('#grade_pay').val();
			var da = $('#da').val();
			var hra = $('#hra').val();
			var ma = $('#ma').val();
			var conv_allow = $('#conv_allow').val();
			var hill_allow = $('#hill_allow').val();
			//var cpf = $('#cpf').val();
			var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(grade_pay)+parseInt(da)+parseInt(hill_allow)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			var pfl = $('#pf_loan').val();
			//var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			
			var overdrawn = $('#overdrawn').val();
		          //  var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}else{
			var pay_band = $('#pay_in_band').val();
			var consolidated_pay=$('#consolidated_pay').val();
			var grade_pay = $('#grade_pay').val();
			var da = $('#da').val();
			var hra = $('#hra').val();
			var ma = $('#ma').val();
			var conv_allow = $('#conv_allow').val();
			var hill_allow = $('#hill_allow').val();
			//var cpf = $('#cpf').val();
			var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(hill_allow)+parseInt(grade_pay)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			var pfl = $('#pf_loan').val();
			//var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var overdrawn = $('#overdrawn').val();
			
			      //  var reduct1=$('#reduct1').val();
			     	//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
			var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
			////$('#deduct').val(deduct);
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}
	}
		function convCheck(){
		var max_conv_al = parseInt(<?php echo $conveyance_allowance_max ?>);
		if($('#conv_allow').val() > max_conv_al || $('#conv_allow').val()==''){
			alert('Please Enter Valid Conveyance Allowance Amount.');
			$('#conv_allow').val(0);
			var pay_band = $('#pay_in_band').val();
			var grade_pay = $('#grade_pay').val();
			var da = $('#da').val();
			var hra = $('#hra').val();
			var ma = $('#ma').val();
			var conv_allow = $('#conv_allow').val();
			var hill_allow = $('#hill_allow').val();
			var cpf = $('#cpf').val();
			var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow)+parseInt(cpf);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			var pfl = $('#pf_loan').val();
			var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var overdrawn = $('#overdrawn').val();
			var reduct=$('#reduct').val();
			// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(cpfd)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}else{
			var pay_band = $('#pay_in_band').val();
			var grade_pay = $('#grade_pay').val();
			var da = $('#da').val();
			var hra = $('#hra').val();
			var ma = $('#ma').val();
			var conv_allow = $('#conv_allow').val();
			var hill_allow = $('#hill_allow').val();
			var cpf = $('#cpf').val();
			var gross = parseInt(pay_band)+parseInt(grade_pay)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow)+parseInt(cpf);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			var pfl = $('#pf_loan').val();
			var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var overdrawn = $('#overdrawn').val();
			// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
		//	var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(cpfd)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}
	}
	
	retirement_gpfCal()
	{
		var gpf = $('#gpf').val();
		          
				  if(gpf>0)
				  {
					alert('Please Enter Valid GPF Amount As Per Rule.');
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();   
				 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				
				  }
		
		}
	
	
	
	
	function gpfCal(){
		//}
			
			if($('#consolidated_pay').val()!=0){
					$('#gpf').val(0);
			} else {
				
				
			$('#cpf').val(0);
			$('#cpf_deduct').val(0);
			//if($('#cpf').val() == 0){
				var pay_band = $('#pay_in_band').val();
				var grade_pay = $('#grade_pay').val();
				var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
				var min_gpf = Math.round(min_gpf_amt);
				var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
				var gpf = $('#gpf').val();
				
				if(gpf < min_gpf || gpf > max_gpf){
					alert('Please Enter Valid GPF Amount As Per Rule.');
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();   
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}else{
					//alert(222);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					
				}
				
				
			}
			
			//}
			  var b=$('#net').val();
			/*if( parseInt(b)<=0)
			{
					
					
			   	
				var pay_band = $('#pay_in_band').val();
				var grade_pay = $('#grade_pay').val();
				var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
				var min_gpf = Math.round(min_gpf_amt);
				var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
				$('#gpf').val(min_gpf);
				    var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf= $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					//alert(gpf);
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				alert('Net Amount Is Not Valid.');
				return false;	
			    }*/
			
		}
		function pflCal(){
			
			var pfl = $('#pf_loan').val();
			if(pfl==''){
				//$('#pf_loan').val(0);
				//var pfl = $('#pf_loan').val();
				var pfl =0;
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				var loan_deduc = $("#total_loan_deduction").val();
			// var reduct1=$('#reduct1').val();
			    // 	var reduct2=$('#reduct2').val();
				var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				////$('#deduct').val(deduct);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
				$("#num_to_word").html("<?php echo 'two'; ?>");
			}else{
				//var max_pay_band = parseInt(<?php echo $pay_in_band ?>);
				//var max_pay_band=$('#pay_in_band').val();
				var pay_band=$('#pay_in_band').val();
				var grade_pay=$('#grade_pay').val();
				var max_pay_band = (parseInt(pay_band)+parseInt(grade_pay));
				if(Number($('#pf_loan').val()) > max_pay_band){
					alert('Please Enter Valid PF Loan Amount.');
					$('#pf_loan').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
					$("#num_to_word").html("<?php echo 'three'; ?>");
				}
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var overdrawn = $('#overdrawn').val();
				var gsli = $('#gsli').val();
				var loan_deduc = $("#total_loan_deduction").val();
			//	 var reduct1=$('#reduct1').val();
			   //  	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				////$('#deduct').val(deduct);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
				<?php $num = "<script>document.write(total_deduct)</script>" ?>
				
				var x = "<?php number_to_words(99);?>";
				alert(x);
				$("#num_to_word").html(x);
			}
				
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('PF LOAN Amount Is Not Grater Than NET Amount.');
				
				$('#pf_loan').val(0);
				$('#pf_loan').focus();
				    var gross = $('#gross').val();
					var pfl =0;
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
			// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var loan_deduc = $("#total_loan_deduction").val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
					$("#num_to_word").html("<?php echo 'five'; ?>");
				
				return false;	
			    }
		}
		function itaxCal(){
			var itax = $('#i_tax').val();
			if(itax==''){
				//$('#i_tax').val(0);
				//var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				//var itax = $('#i_tax').val();
				var itax =0;
				var gsli = $('#gsli').val();
				
				var ssl = $('#ssl').val();
				var scl = $('#scl').val();
				var scc = $('#scc').val();
				
				var overdrawn = $('#overdrawn').val();
				var loan_deduc = $("#total_loan_deduction").val();
				// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				
				var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
				////$('#deduct').val(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
				
			}else{
				//var pan_no=$('#pan_no').val();
				var actual_pay_band = $('#pay_in_band').val();
				if(itax> parseInt(actual_pay_band) && itax.length>5){
					alert('Please Enter The Valid ITAX Amount.\nITAX Amonut Should Be Less Than Pay In Pay Band.');
					$('#i_tax').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					 var reduct1=$('#reduct1').val();
			     	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
					
				var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
				////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				} else {
					
					//$('#gsli').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
				// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
					
				}

			}
	        var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('I.TAX Amount Is Not Grater Then NET Amount.');
				
				$('#i_tax').val(0);
				$('#i_tax').focus();
				    var gross = $('#gross').val();
					var pfl =0;
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = 0;
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				
				return false;	
			    }
		}
		
function reduc(value){
			
			    var reduc = value;
			    if(reduc==0 || reduc=='')
				{ 
					//var consolidated_pay = $('#consolidated_pay').val();
			     //	$('#reduct').val(0);
				//$('#i_tax').val(0);
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				//var reduct1 =0;
				//var reduct2 = $('#reduct2').val();
				var reduct3 = $('#reduct3').val();
				var deduct =parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
		    	}
			
			   else{
				   
				/*    //var pan_no=$('#pan_no').val();
					//var consolidated_pay = $('#consolidated_pay').val();
				    var actual_pay_band = $('#pay_in_band').val();
					
					//$('#gsli').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var reduct=$('#reduct1').val();
				   // check(itax,reduct);
				   //alert(itax);
				   var reduct2 = $('#reduct2').val();
				   var reduct3 = $('#reduct3').val();
				   var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduc)+parseInt(reduct2)+parseInt(reduct3);
				   var total_deduct =parseInt(gross)-parseInt(deduct);*/
					//$('#net').val(total_deduct);
				 var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				//reduct2=$('#reduct2').val();
			    reduct3=$('#reduct3').val();
			   
			    var a=$('#pay_in_band').val()
			    var b=$('#grade_pay').val();
			    var c= parseInt(a)+ parseInt(b);
				var consolidated_pay=$('#consolidated_pay').val()	
				
				 if(parseInt(consolidated_pay)==0)
				 {
					 if( parseInt(c)<= parseInt(reduc))
					{
						 $('#reduct1').val(0);
					     alert(' Co-operative Loan Recovery Value Must Be Less Than Basic Pay');
						  var reduct1=0;
						 
						
						 //alert(gpf);
						 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					    var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						
					}
					
					else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					
					 	

			}	else if(parseInt(consolidated_pay)>0){
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc))
						{
							
						 alert(' Co-operative Loan Recovery Value Must Be Less Than Consolidated Pay');
						 $('#reduct1').val(0);
						 var ptax = $('#p_tax').val();
						 var reduct1=0;
						 //alert(reduct2)
						 var deduct =  parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					     var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					 }
					 
					}
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					//alert(24);
					$('#reduct1').val(0);
			   	alert(' Co-operative Loan Recovery Amount Is Not Valid.');
				
				
				$('#reduct1').focus();
				var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				//	var reduct=0;
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		
		function reduc2(value){
			
			var reduc2 = value;
			if(reduc2==0 || reduc2==''){
			//	$('#reduct').val(0);
				//$('#i_tax').val(0);
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
			//	var reduct1=$('#reduct1').val();
			//	var reduct2 =0;
				var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				////$('#deduct').val(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				
				
				
			}
			
			else{
				
			/*	//var pan_no=$('#pan_no').val();
				var actual_pay_band = $('#pay_in_band').val();
					
					//$('#gsli').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var reduct2=$('#reduct2').val();
				   // check(itax,reduct);
				   //alert(itax);
				   var reduct1=$('#reduct1').val();
			     	var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct1)+parseInt(reduc2)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);*/
				
					var a=$('#pay_in_band').val()
					var b=$('#grade_pay').val();
					var c= parseInt(a)+ parseInt(b);
				   
				    var consolidated_pay=$('#consolidated_pay').val()
				    var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					
					var reduct3=$('#reduct3').val();
				 if(parseInt(consolidated_pay)==0)
				 {
					if( parseInt(c)<= parseInt(reduc2))
					{
						alert('HBL Recovery Value Must Be Less Than Basic Pay');
						
						 $('#reduct2').val(0);
						 
						 //reduct2=0;
						 var deduct =  parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						
					}
					
					else
					{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct2=$('#reduct2').val();
					//var reduct1=$('#reduct1').val();
			     	var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
					}
				 }
					else{
						
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc2)){
							
						 alert(' HBL Recovery Amount Must Be Less Than Consolidated Pay');
						// $('#reduct2').val(0);
						 var ptax = $('#p_tax').val();
						
						// var reduct2=0;
						 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
						 ////$('#deduct').val(deduct);
					    var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					 
						
						}

			}
			
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			     	alert('HBL Recovery Amount Is Not Valid.');
				
				    $('#reduct2').val(0);
				    $('#reduct2').focus();
				    var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct2=0;
					//var reduct1=$('#reduct1').val();
			     	var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		
		function reduc3(value){
			
			var reduc3 = value;
			if(reduc3==0 || reduc3==''){
			
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
			
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				var loan_deduc = $("#total_loan_deduction").val();
				//var reduct1=$('#reduct1').val();
			   // var reduct2=$('#reduct2').val();
				var reduct3 =0;
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
				
				
			}
			
			else{
				
				//var pan_no=$('#pan_no').val();
			/*	var actual_pay_band = $('#pay_in_band').val();
					
					//$('#gsli').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
				   // check(itax,reduct);
				   //alert(itax);
				   var reduct1=$('#reduct1').val();
			     	var reduct2=$('#reduct2').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct1)+parseInt(reduct2)+parseInt(reduc3);
					var total_deduct = parseInt(gross)-parseInt(deduct);*/
					//$('#net').val(total_deduct);
					
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				var loan_deduc = $("#total_loan_deduction").val();
				//var reduct1=$('#reduct1').val();
			   // var reduct2=$('#reduct2').val();
				
					var a=$('#pay_in_band').val()
					var b=$('#grade_pay').val();
					var c= parseInt(a)+ parseInt(b);
					//alert(c);
					var consolidated_pay=$('#consolidated_pay').val()
				 if(parseInt(consolidated_pay)==0)
				 {
					if( parseInt(c)<= parseInt(reduc3))
					{
						$('#reduct3').val(0);
						alert('Festival Advance Recovery Amount Must Be Less Than Basic Pay');
						var reduct3=0;
						 
						 
						 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
						 ////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						$('#deduct').val(deduct);
						
					}
					
					else
					{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				
					}
					
				 }
				 else
				 { 
				 
						
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc3)){
							
						 alert(' Festival Advance Recovery Amount Must Be Less Than Consolidated Pay');
						 $('#reduct3').val(0);
						  var ptax = $('#p_tax').val();
						   var reduct3=0;
						 
						 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
						 ////$('#deduct').val(deduct);
					    var total_deduct = parseInt(gross)-parseInt(deduct);
						$('#net').val(total_deduct);
						$('#deduct').val(deduct);
						
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
					}
					 
						
						
					 
					 }
			}
			
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('Festival Advance Recovery Amount Is Not Valid.');
				
				$('#reduct3').val(0);
				$('#reduct3').focus();
				var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=0;
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					////$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				return false;	
			    }
		}
		
		
		
			function ovdCal(){
			var overdrawn = $('#overdrawn').val();
			if(overdrawn==''){
				//$('#overdrawn').val(0);
				//var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				
				var ssl = $('#ssl').val();
				var scl = $('#scl').val();
				var scc = $('#scc').val();
				//var overdrawn = $('#overdrawn').val();
				var overdrawn = 0;
				var loan_deduc = $("#total_loan_deduction").val();
			//	var reduct1=$('#reduct1').val();
			//	var reduct2=$('#reduct2').val();
				var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					////$('#deduct').val(deduct);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
			}else{
				var actual_pay_band = $('#pay_in_band').val();
				var consolidated_pay = $('#consolidated_pay').val();
				if(parseInt(consolidated_pay)==0)
				{
				if(overdrawn > parseInt(actual_pay_band)){
					alert('Please Enter The Valid Overdrawn Amount.\nOverdrawn Amonut Should Not Be Greater Than Pay In Pay Band.');
					$('#overdrawn').val(0);
					//var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
				 //   var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}
				}
				else
				{
					
				if(overdrawn > parseInt(consolidated_pay)){
					alert('Please Enter The Valid Overdrawn Amount.\nOverdrawn Amonut Should Not Be Greater Than Consolidated Pay.');
					$('#overdrawn').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					
					var ssl = $('#ssl').val();
					var scl = $('#scl').val();
					var scc = $('#scc').val();
				   // var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc)+parseInt(ssl)+parseInt(scl)+parseInt(scc);
					////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}
				
					
					
					}
			}
		}
		
		//For Calculation from other deduction table on 23_03_2017 by Rupesh
		/*function gsliCal(){
			var gsli = $('#gsli').val();
			if(gsli==''){
				//$('#gsli').val(0);
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				
				//var gsli = $('#gsli').val();
				var gsli =0;
				
				var overdrawn = $('#overdrawn').val();
				var loan_deduc = $("#total_loan_deduction").val();
			  //  var reduct1=$('#reduct1').val();
			   //  	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				$('#deduct').val(deduct);
			}else{
				
				var gsli = $('#gsli').val();
				var c =120;
				if(parseInt(gsli) >parseInt(c)){
					
					alert('Please enter the valid GSLI amount.\nGSLI amonut should not be greater then 120.');
					$('#gsli').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					//$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					var loan_deduc = $("#total_loan_deduction").val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(loan_deduc);
					////$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					$('#deduct').val(deduct);
				}
			}
		}*/
		
		
		
		function checkForm(){
			//var reduct1=$('#reduct1').val();
			//reduct2=$('#reduct2').val();
			reduct3=$('#reduct3').val();
			//var add= parseInt(reduct1)+ parseInt(reduct2)+ parseInt(reduct3);
			var add=parseInt(reduct3);
			var m2=$('#net').val();
			if ($('#reduction').val() == "yes_ovd1")
			{
			
			/*if($('.cls6').is(':checked')==false && $('.cls7').is(':checked')==false && $('.cls8').is(':checked')==false)
			{
				alert('Please select at lease one other deduction cause.');
				$('#reduct').focus();
				return false;	
			}
			if($('.cls6').is(':checked')==true)
			{
			if($('#reduct1').val()<=0) {
				
				alert('Co-operative Loan Recovery amount is not valid.');
				$('#reduct1').focus();
				return false;	
			}
			}
			if($('.cls7').is(':checked')==true)
			{
			if($('#reduct2').val()<=0) {
				
				alert('HBL Recovery amount is not valid.');
				$('#reduct2').focus();
				return false;	
			}
			}*/
			if($('.cls8').is(':checked')==true)
			{
			if($('#reduct3').val()<=0) {
				
				alert('Festival Advance Recovery Amount Is Not Valid.');
				$('#reduct3').focus();
				return false;	
			}
			}
			/* if(parseInt(reduct1)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct1').focus();
				return false;	
			}
			 if(parseInt(reduct2)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct2').focus();
				return false;	
			}*/
			 /*if(parseInt(reduct3)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct3').focus();
				return false;	
			}*/
			
			/*if(parseInt(add)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct1').focus();
				$('#reduct2').focus();
				$('#reduct3').focus();
				return false;	
			} */
			}
			
			
			if($('#salary_type').val()==''){
				alert('Please Select Salary Type of Employee');
				$('#salary_type').focus();
				return false;
			}
			if($('#salary_type').val()=='2'){
				if($('#working_days').val() == '' || $('#working_days').val() >= parseInt($('#month_last_date').val()) || $('#working_days').val() == 0){
					alert('Please Enter The Valid Working Days For Part Salary Calculation');
					$('#working_days').focus();
					return false;
				}
			}
			
			if($('#is_overdrawn').val()=='yes_ovd'){
				if($('#cause_msg').val() == ''){
					alert('Please Enter The Valid Resion  For The Deduction of Overdrawn Amount.');
					$('#cause_msg').focus();
					return false;
				}
				if($('#is_overdrawn').val()=='yes_ovd'){
					if($('#overdrawn').val() == '' || $('#overdrawn').val() == '0'){
						alert('Please Enter The Valid Overdrawn Amount');
						$('#overdrawn').focus();
						return false;
					}
					
				}
			}
				if($('#salary_type').val()=='2')
				{
				   if($('#part_salary_cause_msg').val() == '')
				   {
				   alert('Please Enter The Valid Resion For Part Salary.');
				   $('#part_salary_cause_msg').focus();
				   return false;
				    }
				}
					
					if($('#salary_type').val()=='8')
					{
					   if($('#no_salary_cause_msg').val() == '')
					    {
					   alert('Please Enter The Valid Resion For No Salary.');
					   $('#no_salary_cause_msg').focus();
					   return false;
				         }
					}
				
			
			
			if(isNaN($('#salary_type').val())) {
				alert('Please Select Valid Salary Type.');
				$('#consolidated_pay').focus();
				return false;	
			}
		
			/*if(isNaN($('#consolidated_pay').val()) || $('#consolidated_pay').val()<0) {
			alert('Please Enter Valid Consolidated Pay.');
			$('#consolidated_pay').focus();
			return false;	
			}*/
			
			if(isNaN($('#pay_in_band').val()) || $('#pay_in_band').val()<0) {
				alert('Please Enter Valid Pay In Pay Band.');
				$('#pay_in_band').focus();
				return false;	
			}
			  
			if(isNaN($('#grade_pay').val()) || $('#grade_pay').val()<0) {
				alert('Please Enter Valid Grade Pay.');
				$('#grade_pay').focus();
				return false;	
			}
			
			if(isNaN($('#da').val()) || $('#da').val()<0) {
				alert('Please Enter Valid DA.');
				$('#da').focus();
				return false;	
			}
			
			if(isNaN($('#hra').val()) || $('#hra').val()<0) {
				alert('Please Enter Valid HRA.');
				$('#hra').focus();
				return false;	
			}
			
			if(isNaN($('#ma').val()) || $('#ma').val()<0) {
				alert('Please Enter Valid MA.');
				$('#ma').focus();
				return false;	
			}
			
			if(isNaN($('#conv_allow').val()) || $('#conv_allow').val()<0) {
				alert('Please Enter valid Conveyance Allowance.');
				$('#conv_allow').focus();
				return false;	
			}
			
			if(isNaN($('#gross').val()) || $('#gross').val()<0) {
				alert('Gross Amount Is Not Valid.');
				$('#gross').focus();
				return false;	
			}
			
			if(isNaN($('#gpf').val()) || $('#gpf').val()<0) {
				alert('GPF amount is not valid.');
				$('#gpf').focus();
				return false;	
			}
			
            /*if(isNaN($('#pf_loan').val()) || $('#pf_loan').val()<0) {
				alert('PF Loan Amount Is Not Valid.');
				$('#pf_loan').focus();
				return false;	
			}*/
			
			if(isNaN($('#p_tax').val()) || $('#p_tax').val()<0) {
				alert('PTAX Amount Is Not Valid.');
				$('#p_tax').focus();
				return false;	
			}
			
			if(isNaN($('#i_tax').val()) || $('#i_tax').val()<0) {
				alert('ITAX Amount Is Not Valid.');
				$('#i_tax').focus();
				return false;	
			}
			
			if(isNaN($('#gsli').val()) || $('#gsli').val()<0) {
				alert('GSLI Amount Is Not Valid.');
				$('#gsli').focus();
				return false;	
			}
			
			if(isNaN($('#overdrawn').val()) || $('#overdrawn').val()<0) {
				alert('Overdrawn Amount Is Not Valid.');
				$('#overdrawn').focus();
				return false;	
			}
			
			
			
				
			
			/*if(isNaN($('#net').val()) || $('#net').val()<0) {
				alert('Net Amount Is Not Valid.');
				$('#net').focus();
				return false;	
			}*/
			
			
			return true;
		}
		var count = "500";
		function limiter(){
			//alert('111');
			if($('#cause_msg').val().charAt(0)!=' ' && $('#cause_msg').val()!='' && $('#is_overdrawn').val()=='yes_ovd'){
				//alert('ok');
				$('#overdrawn').removeAttr('readonly');
				$('#overdrawn').css('background-color','#FFFFFF');
				$('#overdrawn').val(0);
			}else{
				$('#overdrawn').attr('readonly','readonly');
				$('#overdrawn').css('background-color','#EEE');
			}
			var tex = $('#cause_msg').val();
			var len = tex.length;
			if(len > count){
				tex = tex.substring(0,count);
				$('#cause_msg').val(tex);
        		return false;
        	}
        	$('#limit').text(count-len);
        }
		
		function cal_ptax(gross)
		{
			$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/cal_ptax.php?gross='+gross, function(data){
				 //alert(data);
				// alert('PTAX VAL: '+data);
				 $('#p_tax').val(data);
				 //var ptax = data;
				/* var data=data;
				return(data);*/
				 //$("#mbody").html(data);
				 
		       });
		   
			
		}
	
		
	</script>


<!--<script>
	$(document).ready(function(e) {
        $('#submit').click(function(e) {
			//alert($('#pf_loan').val());
	
			
			
        });
    });
	
	</script>-->
<!--<script>
 function check(itax,reduct)
		{
			
			 if(itax=='')
			{
				itax==0;
			}
		   if(reduct=='')
			{
			 
				reduct=0; 
			}
			//alert(itax);
			
			
			//alert(itax);
			
			}

</script>-->
 
<?php 
//Number To Word For Gross Amount
function number_to_words($number)
{
 if ($number > 999999999)
  {
   throw new Exception("Number is out of range");
  }
  $Gn = floor($number / 10000000);  /* Millions (giga) */
  $number -= $Gn * 10000000;
  $Ln = floor($number / 100000);  /* Millions (giga) */
  $number -= $Ln * 100000;
  $kn = floor($number / 1000);     /* Thousands (kilo) */
  $number -= $kn * 1000;
  $Hn = floor($number / 100);      /* Hundreds (hecto) */
  $number -= $Hn * 100;
  $Dn = floor($number / 10);       /* Tens (deca) */
  $n = $number % 10;               /* Ones */
  $cn = round(($number-floor($number))*100); /* Cents */
  $result = ""; 
  if ($Gn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Gn) . " Crore";  } 
  if ($Ln)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Ln) . " Lakh"; } 
  if ($kn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 
  if ($Hn)
   {  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
        "Nineteen");
   $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety"); 
   if ($Dn || $n)
    {
     if (!empty($result))
      {  $result .= " and ";
      } 
     if ($Dn < 2)
      {  $result .= $ones[$Dn * 10 + $n];
      }
      else
         {  $result .= $tens[$Dn];
          if ($n)
          {  $result .= "-" . $ones[$n];
          }
         }
    }
   if ($cn)
    {
     if (!empty($result))
      {  $result .= ' and ';
      }
       $title = $cn==1 ? 'paisa ': 'paisa';
       $result .= strtolower(number_to_words($cn)).' '.$title;
    }
   if (empty($result))
    {  $result = "zero"; } 
  return $result;
}
?>