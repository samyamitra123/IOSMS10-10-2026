<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
//require_once '../../page_visite.php';
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
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
$common['title'] = "Salary Requisition | PRD | Govt. of West Bengal ";

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

<div class="content">
<? require '../../../../page/common_back_btns.php'; ?>
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
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
                      ?></h3>
       </div>
       
<div class="row" id="cont">
<div class="content">
	
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
        <div class="col-sm-12">
<h1 class="heading">Employee Salary Requisition ROPA 2019</h1>
<h2 class="heading">Salary Month Year : <?php echo date('M').','.date('Y') ?></h2>

<div class="border"></div>

</br>
</br>
<?php  
//print_r($_SESSION); exit;
  $db = new database();
  $Query = "SELECT 
						*
						FROM
						prd_employee_master 
						WHERE
						gp_id_fk =  '".$_SESSION['location']['gp_id']."'
						AND emp_status IN ('1','11') 
            AND ropa_status = '1' ";
 // print_r($Query); exit;          
	$id_const= $db->fetch_table($Query);
	
	$appMemo = array();
	if($config['appointment'] == 1)
	{	
	foreach ($id_const as $const) {
		if(strlen($const['emp_first_memo_no']) <= 1 || strlen($const['emp_present_memo_no']) <=1 || $const['recruitment_type'] == '0')
			$appMemo[] = $const['emp_first_name'].' '.$const['emp_second_name'].' '.$const['emp_last_name'];
	}
  }
?>
<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/
//echo $_SESSION['location']['block_code'].'--'.$_SESSION['location']['gp_id'];
?>
<div class="border_val"></div>
 <div id="sess_msg" style="width:98%;">
   <?   
 // var_dump($_SESSION['msg']); 
   if(isset($_SESSION['msg']))
			{
				echo "<strong>".$_SESSION['msg']."</strong>";
				
				unset($_SESSION['msg']);
				
			}
			?>
            </div>
            
<?php 

/*if($tch_emp[0]['total_emp']!=$tch_emp[0]['total_ir_emp']){
		echo '<h3 style="color:#2C84B4">Please submit IR for all the employees...</h3>
		<a href="../interim_relief/emp_interim_relief_entry_sal.php" style="font-weight:550; font-size:24px; color:#F36C3F;">Click Here</a>';
}else{*/			
?>
            <div id="dialog" title="Employee details">
			  	<div id="wait"><img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" /></div>
	  			<div class="dial"></div>
			</div>
            
    
    
<div class="emplist" style="width:98%;">
<div class="school">
    
    
    

 <?php if($_SESSION['location']['gpcode']=='3210022010' || $_SESSION['location']['gpcode']=='3207004002'  ||  $_SESSION['location']['gpcode']== '3221004003' || $_SESSION['location']['gpcode']== '3215022010' || $_SESSION['location']['gpcode']== '3203013004' || $_SESSION['location']['gpcode']== '3211020003' || $_SESSION['location']['gpcode']== '3221005007' || $_SESSION['location']['gpcode']== '3203012004' || $_SESSION['location']['gpcode']== '3213011006' ||
$_SESSION['location']['gpcode']=='3210027005' || $_SESSION['location']['gpcode']=='3203013007'  || $_SESSION['location']['gpcode']=='3214001003' || $_SESSION['location']['gpcode']=='3205014007' ||  $_SESSION['location']['gpcode']=='3214001005' || $_SESSION['location']['gpcode']=='3214001007'|| $_SESSION['location']['gpcode']=='3201007006' ||  $_SESSION['location']['gpcode']=='3201012003' ||  $_SESSION['location']['gpcode']=='3203002005' || $_SESSION['location']['gpcode']==' 3203004009'
 || $_SESSION['location']['gpcode']=='3205005004' || $_SESSION['location']['gpcode']=='3206006014' || $_SESSION['location']['gpcode']=='3206010001' || $_SESSION['location']['gpcode']=='3209005004' ||  $_SESSION['location']['gpcode']=='3210029014' ||  $_SESSION['location']['gpcode']=='3211006014' || $_SESSION['location']['gpcode']=='3212005004'
 || $_SESSION['location']['gpcode']=='3212008012' || $_SESSION['location']['gpcode']=='3212010005' || $_SESSION['location']['gpcode']=='3212011007' || $_SESSION['location']['gpcode']=='3212014003' || $_SESSION['location']['gpcode']=='3212014004' || $_SESSION['location']['gpcode']=='3213006006' ||  $_SESSION['location']['gpcode']=='3212023002' ||  $_SESSION['location']['gpcode']=='3213010001'
 || $_SESSION['location']['gpcode']=='3213010007' ||  $_SESSION['location']['gpcode']=='3214001007' || $_SESSION['location']['gpcode']=='3214011002' ||  $_SESSION['location']['gpcode']=='3214007005' ||   $_SESSION['location']['gpcode']=='3214007007' ||  $_SESSION['location']['gpcode']=='3215008001' || $_SESSION['location']['gpcode']=='3214016006' || $_SESSION['location']['gpcode']=='3214016005' || $_SESSION['location']['gpcode']=='3216002001' ||  $_SESSION['location']['gpcode']=='3215010005' ||
  $_SESSION['location']['gpcode']=='3219012008' || $_SESSION['location']['gpcode']=='3217008004' ||  $_SESSION['location']['gpcode']=='3217005004' ||   $_SESSION['location']['gpcode']=='3220010004' ||  $_SESSION['location']['gpcode']=='3217004011' || $_SESSION['location']['gpcode']=='3220012006' || $_SESSION['location']['gpcode']=='3220009004'
  || $_SESSION['location']['gpcode']=='3216019007' || $_SESSION['location']['gpcode']=='3219008010' )
 
{



	$id_const= $db->fetch_table("
							SELECT 
							*
							FROM
							prd_employee_master 
							WHERE
							gp_id_fk =  '".$_SESSION['location']['gp_id']."'
							AND emp_status='1' 
                            and old_emp_const_id is not NULL  AND (ropa_status = '1' OR consolidated_status='1')
							");
	?>
    
    <div style=" front-size:15px;"><p style=" font-size: 20px;color:red;">Due to Data Sanitization Methodology, only the seventh digit of a few(approximately .002%) Employee Ids have been updated. Please check all the employee details mentioned below carefully and correlate accordingly.
    </p>
<p style=" font-size: 15px;color:green;">  <b><?php
     foreach ($id_const as $const) {
    echo $const['emp_first_name'].' '.$const['emp_second_name'].' '.$const['emp_last_name'].'  '.'('.$const['old_emp_const_id'].')'.'  '.'['.'New Employee Id='.$const['emp_id_const'].']'.'      ';    
 }
    ?></b></p> </div> 
    
     <?php }?>   
<div class="table-responsive">
<?php 

	if(count($appMemo) > 0)
	  {
	  	include_once('../../common/appo_type.php');
	  	echo "
	    <ul>
	  	";
	  	foreach($appMemo as $emp)
	  	{
	       echo "<li>".$emp."</li>";
	  	}
	  	echo "</ul>";
	  	?>
	  	<script type="text/javascript">
	  		$("#dialog").css("display", "none");
	  	</script>
	  	<?php 
	  }
	else
	  require_once 'requisition_form_ropa_2019.php';
  
	

?>
</div>
</div>
</div>
<?php
//}
?> 

</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>


<? require '../../../../page/layout/footer.php'; ?>
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
		
		var max_ptax = parseInt(<?php if(isset($max_ptax)){echo $max_ptax; } ?>);
		if($('#p_tax').val() > max_ptax || $('#p_tax').val()==''){
			alert('Please enter valid P.Tax amount.');
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
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}
	}
		function convCheck(){
		var max_conv_al = parseInt(<?php if(isset($conveyance_allowance_max)){ echo $conveyance_allowance_max; } ?>);
		if($('#conv_allow').val() > max_conv_al || $('#conv_allow').val()==''){
			alert('Please enter valid conveyance allowance amount.');
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
		//	var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(cpfd)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}
	}
	
	function retirement_gpfCal()
	{
		var gpf = $('#gpf').val();
		          
				  if(gpf>0)
				  {
					alert('Please enter valid GPF amount as per rule.');
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();   
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
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
					var pay_band = $('#pay_in_band_2009').val();
				var grade_pay = $('#grade_pay_2009').val();
			
				//var pay_band = $('#pay_in_band').val();
				
				//var gpf_cal= $('#gpf_cal').val();
				//var prv_bas= $('#prv_bas').val();
				//alert(prv_bas);
				
				//var grade_pay = $('#grade_pay').val();
				//var min_gpf_amt = ((parseInt(pay_band)+parseInt(grade_pay))/100)*6;
				var min_gpf_amt = (parseInt(pay_band)/100)*6;
				//var min_gpf_amt = ((parseInt(prv_bas)/100)*6);
				var min_gpf = Math.round(min_gpf_amt);
				//alert(min_gpf);
				//return false;
				//var max_gpf = (parseInt(pay_band)+parseInt(grade_pay));
				var max_gpf = parseInt(pay_band);
				//var max_gpf = (parseInt(prv_bas));
				var gpf = $('#gpf').val();
				//	alert(pay_band); alert(grade_pay);	alert(pay_band); 
				if(gpf < min_gpf || gpf > max_gpf){
					alert('Please enter valid GPF amount as per rule.');
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
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					
				}
				
				
			}
			
			//}
			  var b=$('#net').val();
			if( parseInt(b)<=0)
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
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				alert('Net amount is not valid.');
				return false;	
			    }
			
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
			// var reduct1=$('#reduct1').val();
			    // 	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
			}else{
				//var max_pay_band=$('#pay_in_band').val();
				var pay_band=$('#pay_in_band').val();
				var grade_pay=$('#grade_pay').val();
				var max_pay_band = (parseInt(pay_band)+parseInt(grade_pay));
				if(Number($('#pf_loan').val()) > max_pay_band){
					alert('Please enter valid PF loan amount.');
					$('#pf_loan').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var overdrawn = $('#overdrawn').val();
				var gsli = $('#gsli').val();
			//	 var reduct1=$('#reduct1').val();
			   //  	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
			}
				
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('PF LOAN amount is not grater then NET amount.');
				
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
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		function itaxCal(){
			var itax = $('#i_tax').val();
			if(itax==''){
				//$('#i_tax').val(0);
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				//var itax = $('#i_tax').val();
				var itax =0;
				var gsli = $('#gsli').val();
				var overdrawn = $('#overdrawn').val();
				// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				
			}else{
				//var pan_no=$('#pan_no').val();
				var actual_pay_band = $('#pay_in_band').val();
				if(itax> parseInt(actual_pay_band) && itax.length>5){
					alert('Please enter the valid ITAX amount.\nITAX amonut should be less than Pay in pay band.');
					$('#i_tax').val(0);
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					 var reduct1=$('#reduct1').val();
			     	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
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
				// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					
				}

			}
	        var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('I.TAX amount is not grater then NET amount.');
				
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
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
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
					     alert(' Co-operative Loan Recovery Value must be Less then Basic Pay');
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
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					
					 	

			}	else if(parseInt(consolidated_pay)>0){
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc))
						{
							
						 alert(' Co-operative Loan Recovery Value must be Less then consolidated_pay');
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
			   	alert(' Co-operative Loan Recovery amount is not valid.');
				
				
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
						alert('HBL Recovery Value must be Less then Basic Pay');
						
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
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
					}
				 }
					else{
						
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc2)){
							
						 alert(' HBL Recovery amount must be Less then consolidated_pay');
						// $('#reduct2').val(0);
						 var ptax = $('#p_tax').val();
						
						// var reduct2=0;
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
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					 
						
						}

			}
			
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			     	alert('HBL Recovery amount is not valid.');
				
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
				//var reduct1=$('#reduct1').val();
			   // var reduct2=$('#reduct2').val();
				var reduct3 =0;
				var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
				
				
				
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
						alert('Festival Advance Recovery amount must be Less then Basic Pay');
						var reduct3=0;
						 
						 
						 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
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
					var reduct3=$('#reduct3').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
					}
					
				 }
				 else
				 { 
				 
						
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc3)){
							
						 alert(' Festival Advance Recovery amount must be Less then consolidated_pay');
						 $('#reduct3').val(0);
						  var ptax = $('#p_tax').val();
						   var reduct3=0;
						 
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
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					}
					 
						
						
					 
					 }
			}
			
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
					
					
			   	alert('Festival Advance Recovery amount is not valid.');
				
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
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=0;
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		
		
		
			function ovdCal(){
			var overdrawn = $('#overdrawn').val();
			if(overdrawn==''){
				//$('#overdrawn').val(0);
				var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				//var overdrawn = $('#overdrawn').val();
				var overdrawn = 0;
			//	var reduct1=$('#reduct1').val();
			//	var reduct2=$('#reduct2').val();
				var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
			}else{
				var actual_pay_band = $('#pay_in_band').val();
				var consolidated_pay = $('#consolidated_pay').val();
				if(parseInt(consolidated_pay)==0)
				{
				if(overdrawn > parseInt(actual_pay_band)){
					alert('Please enter the valid overdrawn amount.\nOverdrawn amonut should not be greater than Pay in pay band.');
					$('#overdrawn').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				 //   var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
				}
				else
				{
					
				if(overdrawn > parseInt(consolidated_pay)){
					alert('Please enter the valid overdrawn amount.\nOverdrawn amonut should not be greater than consolidated_pay.');
					$('#overdrawn').val(0);
					var pfl = $('#pf_loan').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
				   // var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
				
					
					
					}
			}
		}
		
		
		function gsliCal(){
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
			  //  var reduct1=$('#reduct1').val();
			   //  	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
				
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
				//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
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
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}else{
					var gross = $('#gross').val();
					var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
			}
		}
		
		
		
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
				
				alert('Festival Advance Recovery amount is not valid.');
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
			 if(parseInt(reduct3)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct3').focus();
				return false;	
			}
			
			if(parseInt(add)>= parseInt(m2))
			{ 
		
			   	alert('Net amount is not valid.');
				$('#reduct1').focus();
				$('#reduct2').focus();
				$('#reduct3').focus();
				return false;	
			} 
			}
			
			
			if($('#salary_type').val()==''){
				alert('Please select salary type of employee');
				$('#salary_type').focus();
				return false;
			}
			if($('#salary_type').val()=='2'){
				if($('#working_days').val() == '' || $('#working_days').val() >= parseInt($('#month_last_date').val()) || $('#working_days').val() == 0){
					alert('Please enter the valid working days for part salary calculation');
					$('#working_days').focus();
					return false;
				}
			}
			
			if($('#is_overdrawn').val()=='yes_ovd'){
				if($('#cause_msg').val() == ''){
					alert('Please enter the valid resion  for the deduction of overdrawn amount.');
					$('#cause_msg').focus();
					return false;
				}
				if($('#is_overdrawn').val()=='yes_ovd'){
					if($('#overdrawn').val() == '' || $('#overdrawn').val() == '0'){
						alert('Please enter the valid overdrawn amount');
						$('#overdrawn').focus();
						return false;
					}
					
				}
			}
				
				if($('#salary_type').val()=='2')
				{
				   if($('#part_salary_cause_msg').val() == '')
				   {
				   alert('Please enter the valid resion for Part Salary.');
				   $('#part_salary_cause_msg').focus();
				   return false;
				    }
				}
					
					if($('#salary_type').val()=='8')
					{
					   if($('#no_salary_cause_msg').val() == '')
					    {
					   alert('Please enter the valid resion for No Salary.');
					   $('#no_salary_cause_msg').focus();
					   return false;
				         }
					}
				
			
			
			if(isNaN($('#salary_type').val())) {
				alert('Please select valid salary type.');
				$('#consolidated_pay').focus();
				return false;	
			}
		
			if(isNaN($('#consolidated_pay').val()) || $('#consolidated_pay').val()<0) {
			alert('Please enter valid Consolidated Pay.');
			$('#consolidated_pay').focus();
			return false;	
			}
			
			if(isNaN($('#pay_in_band').val()) || $('#pay_in_band').val()<0) {
				alert('Please enter valid Pay in Pay Band.');
				$('#pay_in_band').focus();
				return false;	
			}
			  
			/*if(isNaN($('#grade_pay').val()) || $('#grade_pay').val()<0) {
				alert('Please enter valid Grade Pay.');
				$('#grade_pay').focus();
				return false;	
			}*/
			
			if(isNaN($('#da').val()) || $('#da').val()<0) {
				alert('Please enter valid DA.');
				$('#da').focus();
				return false;	
			}
			
			if(isNaN($('#hra').val()) || $('#hra').val()<0) {
				alert('Please enter valid HRA.');
				$('#hra').focus();
				return false;	
			}
			
			if(isNaN($('#ma').val()) || $('#ma').val()<0) {
				alert('Please enter valid MA.');
				$('#ma').focus();
				return false;	
			}
			
			if(isNaN($('#conv_allow').val()) || $('#conv_allow').val()<0) {
				alert('Please enter valid Conveyance Allowance.');
				$('#conv_allow').focus();
				return false;	
			}
			
			if(isNaN($('#gross').val()) || $('#gross').val()<0) {
				alert('Gross amount is not valid.');
				$('#gross').focus();
				return false;	
			}
			
			if(isNaN($('#gpf').val()) || $('#gpf').val()<0) {
				alert('GPF amount is not valid.');
				$('#gpf').focus();
				return false;	
			}
			
            if(isNaN($('#pf_loan').val()) || $('#pf_loan').val()<0) {
				alert('PF loan amount is not valid.');
				$('#pf_loan').focus();
				return false;	
			}
			
			if(isNaN($('#p_tax').val()) || $('#p_tax').val()<0) {
				alert('PTAX amount is not valid.');
				$('#p_tax').focus();
				return false;	
			}
			
			if(isNaN($('#i_tax').val()) || $('#i_tax').val()<0) {
				alert('ITAX amount is not valid.');
				$('#i_tax').focus();
				return false;	
			}
			
			if(isNaN($('#gsli').val()) || $('#gsli').val()<0) {
				alert('GSLI amount is not valid.');
				$('#gsli').focus();
				return false;	
			}
			
			if(isNaN($('#overdrawn').val()) || $('#overdrawn').val()<0) {
				alert('Overdrawn amount is not valid.');
				$('#overdrawn').focus();
				return false;	
			}
			
			
			
				
			
			if(isNaN($('#net').val()) || $('#net').val()<0) {
				alert('Net amount is not valid.');
				$('#net').focus();
				return false;	
			}
			
			
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
			$.post('<?= $config['base_url'] ?>page/intra_prd/gp/sal_requisition/cal_ptax.php?gross='+gross, function(data){
				 //alert(data);
				 $('#p_tax').val(data);
				/* var data=data;
				return(data);*/
				 //$("#mbody").html(data);
				 
		       });
		   
			
		}
	
		
	</script>


<script>
	$(document).ready(function(e) {
        $('#submit').click(function(e) {
			//alert($('#pf_loan').val());
	
			
			
        });
    });
	
	</script>
<script>
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

</script>
 