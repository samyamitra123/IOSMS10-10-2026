
<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
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
                          echo $_SESSION['location']['gp_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      }elseif(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].", ";
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'].", ";
                    } ?></h2><h3>
			<?php   
			    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
			
                     ?></h3>
       </div>

       
<div class="row" id="cont">
<div class="content">
	
			<div class="save_alert">
				<div id="saving" style="display: none">
					<div class="bor">
						<h3>Saving...<img height="20" src="<?php echo $config['base_url'] ?>themes/default/image/preloader.gif" />           
                        </h3>
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
 <div id="sess_msg">
   <?   
  
   if(isset($_SESSION['msg']))
			{
				echo "<strong>".$_SESSION['msg']."</strong>";
				
				unset($_SESSION['msg']);
				
			}
			?>
            </div>
</br>
</br>


<div class="border_val"></div>

            <div id="dialog" title="Employee details">
			  	<div id="wait"><img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" /></div>
	  			<div class="dial"></div>
			</div>
            
<div class="emplist">
<div class="school">


    <?php 
$db = new database();
	$id_const= $db->fetch_table("
							SELECT 
							*
							FROM
							prd_employee_master 
							WHERE
							zp_id_fk =  '".$_SESSION['location']['district_id']."'
							AND emp_status='1' 
                            and old_emp_const_id is not NULL 
							AND (ropa_status = '1' OR emp_cosolidated_pay!='0')
							");

?>
<?php if($_SESSION['location']['district_id']=='9')
{?>
    
    <div style=" front-size:15px;"><p style=" font-size: 20px;color:red;">Due to Data Sanitization Methodology, only the seventh digit of a few(approximately .002%) Employee Ids have been updated. Please check all the employee details mentioned below carefully and correlate accordingly.
    </p>
<p style=" font-size: 15px;color:green;">  <b>
<?php
     foreach ($id_const as $const) {
    echo $const['emp_first_name'].' '.$const['emp_second_name'].' '.$const['emp_last_name'].'  '.'('.$const['old_emp_const_id'].')'.'  '.'['.'New Employee Id='.$const['emp_id_const'].']'.'      ';    
 }
 ?></b></p> </div>     
    
    <?php }?>
    
    
    
    
    
    
    
<div class="table-responsive">
<?php 
require_once 'zp_requisition_form_ropa_2019.php'; 
?>
    
   <?php //if($_SESSION['location']['district_id']=='21')
//{?>

	<?php //require_once 'zp_requisition_form3.php'; 
//}
//else
//{
	//require_once 'zp_requisition_form.php'; 
//}?>
    
    
    

  
	



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
		
		var max_ptax = parseInt(<?php echo $max_ptax ?>);
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
			var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(da)+parseInt(hill_allow)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			//var pfl = $('#pf_loan').val();
			var total_loan_deduction=$('#total_loan_deduction').val();
			//var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var other_loan_deduction = $('#other_loan_deduction').val();
			var adv_sal = $('#adv_sal').val();
			var overdrawn = $('#overdrawn').val();
		          //  var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
				 var hra_deduc = $('#hra_deduc').val();
					var reduct3=$('#reduct3').val();
				var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				$('#deduct').val(deduct);
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
			var gross = parseInt(pay_band)+parseInt(consolidated_pay)+parseInt(hill_allow)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			//var pfl = $('#pf_loan').val();
			//var cpfd = $('#cpf_deduct').val();
			var total_loan_deduction=$('#total_loan_deduction').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var other_loan_deduction = $('#other_loan_deduction').val();
			var overdrawn = $('#overdrawn').val();
			var adv_sal = $('#adv_sal').val();
			      //  var reduct1=$('#reduct1').val();
			     	//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					 var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
			$('#deduct').val(deduct);
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#net').val(net);
		}
	}
		function convCheck(){
		var max_conv_al = parseInt(<?php echo $conveyance_allowance_max ?>);
		//alert(max_conv_al);
		if($('#conv_allow').val() > max_conv_al || $('#conv_allow').val()==''){
		  //  alert(63);
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
			var gross = parseInt(pay_band)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow)+parseInt(cpf);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			//var pfl = $('#pf_loan').val();
			var total_loan_deduction=$('#total_loan_deduction').val();
			var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var other_loan_deduction = $('#other_loan_deduction').val();
			
			var overdrawn = $('#overdrawn').val();
			var adv_sal = $('#adv_sal').val();
			var reduct=$('#reduct').val();
			// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
			var reduct3=$('#reduct3').val();
			var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(cpfd)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#deduct').val(deduct);
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
			var gross = parseInt(pay_band)+parseInt(da)+parseInt(hra)+parseInt(ma)+parseInt(conv_allow)+parseInt(hill_allow)+parseInt(cpf);
			$('#gross').val(gross);
			var gpf = $('#gpf').val();
			//var pfl = $('#pf_loan').val();
			var total_loan_deduction=$('#total_loan_deduction').val();
			var cpfd = $('#cpf_deduct').val();
			var ptax = $('#p_tax').val();
			var itax = $('#i_tax').val();
			var gsli = $('#gsli').val();
			var other_loan_deduction = $('#other_loan_deduction').val();
			var overdrawn = $('#overdrawn').val();
			var adv_sal = $('#adv_sal').val();
			// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
				var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
		//	var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(cpfd)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
			var net = parseInt(gross)-parseInt(deduct);	
			$('#deduct').val(deduct);
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
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					
					var overdrawn = $('#overdrawn').val();
				//	 var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();   
					
					var hra_deduc = $('#hra_deduc').val();
			    	var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				
				  }
		
		}
	
	
	
	
	function gpfCal(){
		
			$('#cpf').val(0);
			$('#cpf_deduct').val(0);
			//if($('#cpf').val() == 0){
				var zp_emp_type=$("#emp_type_zp").val();
				var gpf_acc_no=$("#gpf_acc_no").val();
				//var pay_band = $('#pay_in_band').val();
				//var grade_pay = $('#grade_pay').val();
				
				var pay_band = $('#pay_in_band_2009').val();
				var grade_pay = $('#grade_pay_2009').val();
				
				var min_gpf_amt = ((parseInt(pay_band))/100)*6;
				var min_gpf = Math.round(min_gpf_amt);
				var max_gpf = (parseInt(pay_band));
				var gpf = $('#gpf').val();
				
				if(zp_emp_type=='366' && (gpf_acc_no=='' || gpf_acc_no=='0'))
				{
					$('#gpf').val(0);
					var gpf = $('#gpf').val();
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
				 //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val(); 
					//var adv_sal = $('#adv_sal').val();  
					var hra_deduc = $('#hra_deduc').val();
					
					
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
						$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
				else
				{
					if(gpf < min_gpf || gpf > max_gpf){
					alert('Please enter valid GPF amount as per rule.');
					$('#gpf').val(min_gpf);
					var gpf = $('#gpf').val();
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val(); 
					//var adv_sal = $('#adv_sal').val();  
					var hra_deduc = $('#hra_deduc').val();
					
					
				    var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
						$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
				else
				{
					
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var adv_sal = $('#adv_sal').val();  
					var hra_deduc = $('#hra_deduc').val();
				    var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					
					$('#deduct').val(deduct);
					//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
					
				}
				}
				
			//}
			
			//}
			  var b=$('#net').val();
			if( parseInt(b)<=0)
			{
					
					
			   	
				var pay_band = $('#pay_in_band').val();
				var grade_pay = $('#grade_pay').val();
				var min_gpf_amt = ((parseInt(pay_band))/100)*6;
				var min_gpf = Math.round(min_gpf_amt);
				var max_gpf = (parseInt(pay_band));
				$('#gpf').val(min_gpf);
				    var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf= $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					
					var overdrawn = $('#overdrawn').val();
					// var reduct1=$('#reduct1').val();
			     //	var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					//alert(gpf);
					
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
						$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				alert('Net amount is not valid.');
				return false;	
			    }
			
		}

		function itaxCal(){
			var itax = $('#i_tax').val();
			//alert(itax);
			if(itax==''){
				//$('#i_tax').val(0);
				//var pfl = $('#pf_loan').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				//var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				//var itax = $('#i_tax').val();
				var itax =0;
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3=$('#reduct3').val();
				 //var hra_deduc = $('#hra_deduc').val();
				 var total_loan_deduction=$('#total_loan_deduction').val();
				var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(total_loan_deduction)+parseInt(other_loan_deduction);
				//alert(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#deduct').val(deduct);
				$('#net').val(total_deduct);
				
			}else{
				var actual_pay_band = $('#pay_in_band').val();
				if(itax> parseInt(actual_pay_band) && itax.length>5){
					alert('Please enter the valid ITAX amount.\nITAX amonut should be less than Pay in pay band.');
					$('#i_tax').val(0);
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					 
					var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(total_loan_deduction)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				} else {
					
					//$('#gsli').val(0);
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					//var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
				    var reduct3=$('#reduct3').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
						 //var hra_deduc = $('#hra_deduc').val();
			//var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc);
			var deduct = parseInt(gpf)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(total_loan_deduction)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
			//alert(deduct);
					$('#deduct').val(deduct);
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
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
				    var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
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
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3 = $('#reduct3').val();
				var hra_deduc = $('#hra_deduc').val();
			var deduct =parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#deduct').val(deduct);
				$('#net').val(total_deduct);
		    	}
			
			   else{
				   
				
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
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
						 
						 var hra_deduc = $('#hra_deduc').val();
						 var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					    var total_deduct = parseInt(gross)-parseInt(deduct);
					    $('#deduct').val(deduct);
						$('#net').val(total_deduct);
						
					}
					
					else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
					}
					
					 	

			}	else if(parseInt(consolidated_pay)>0){
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc))
						{
							
						 alert(' Co-operative Loan Recovery Value must be Less then consolidated_pay');
						 $('#reduct1').val(0);
						 var ptax = $('#p_tax').val();
						 var adv_sal = $('#adv_sal').val();
						 var hra_deduc = $('#hra_deduc').val();
						 var deduct =  parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc);
					         var total_deduct = parseInt(gross)-parseInt(deduct);
						 $('#deduct').val(deduct);
						 $('#net').val(total_deduct);
						
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
					}
					 }
					 
					}
			
			
			var b=$('#net').val();
			
			if( parseInt(b)<=0)
		     	{
				
			   	alert(' Co-operative Loan Recovery amount is not valid.');
				var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		
		function reduc2(value){
			
			var reduc2 = value;
			if(reduc2==0 || reduc2==''){
			//	$('#reduct').val(0);
				//$('#i_tax').val(0);
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3=$('#reduct3').val();
				var hra_deduc = $('#hra_deduc').val();
				var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#deduct').val(deduct);
				$('#net').val(total_deduct);
				
				
				
			}
			
			else{
				
			
					var a=$('#pay_in_band').val()
					var b=$('#grade_pay').val();
					var c= parseInt(a)+ parseInt(b);
				   
				    var consolidated_pay=$('#consolidated_pay').val()
				    var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
				 if(parseInt(consolidated_pay)==0)
				 {
					if( parseInt(c)<= parseInt(reduc2))
					{
						alert('HBL Recovery Value must be Less then Basic Pay');
						$('#reduct2').val(0);
						 var deduct =  parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
						$('#net').val(total_deduct);
					}
					
					else
					{
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				
					}
				 }
					else{
						
						
					    if(parseInt(consolidated_pay)<=parseInt(reduc2)){
							
						alert(' HBL Recovery amount must be Less then consolidated_pay');
						var ptax = $('#p_tax').val();
						var hra_deduc = $('#hra_deduc').val();
						var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc);
					    var total_deduct = parseInt(gross)-parseInt(deduct);
					    $('#deduct').val(deduct);
						$('#net').val(total_deduct);
						
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					//var pfl = $('#pf_loan').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					//var reduct1=$('#reduct1').val();
					//var reduct2=$('#reduct2').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
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
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				
				return false;	
			    }
		}
		
		function reduc3(value){
			
			var reduc3 = value;
			if(reduc3==0 || reduc3==''){
			
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3 =0;
				var hra_deduc = $('#hra_deduc').val();
		var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#deduct').val(deduct);
				$('#net').val(total_deduct);
				
				
				
			}
			
			else{
				
				
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var a=$('#pay_in_band').val()
					var b=$('#grade_pay').val();
					var c= parseInt(a)+ parseInt(b);
					var consolidated_pay=$('#consolidated_pay').val()
				 if(parseInt(consolidated_pay)==0)
				 {
					if( parseInt(c)<= parseInt(reduc3))
					{
						$('#reduct3').val(0);
						alert('Festival Advance Recovery amount must be Less then Basic Pay');
						 var reduct3=0;
						 var hra_deduc = $('#hra_deduc').val();
						 var adv_sal = $('#adv_sal').val();
						 var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
						
					}
					
					else
					{
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
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
			 var deduct = parseInt(gpf)+parseInt(pfl)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli);
					          var total_deduct = parseInt(gross)-parseInt(deduct);
						  $('#deduct').val(deduct);
						$('#net').val(total_deduct);
						}
				else
					{
						//var consolidated_pay = $('#consolidated_pay').val();
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
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
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=0;
					var hra_deduc = $('#hra_deduc').val();
			var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					
					
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				return false;	
			    }
		}
		
		//--------------------------------------------------------------------------------------------------------------------------------------------------
		function hra_deduction(){
		
			var hra_deduc = $('#hra_deduc').val();
		   	var payband=$('#pay_in_band').val();
			var grade_pay=$('#grade_pay').val();
			var basic=parseInt(payband);
			if(hra_deduc=='' || hra_deduc=='0'){
				// alert(hra_deduc);
				
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3=$('#reduct3').val();
				var adv_sal = $('#adv_sal').val();
				var hra_dedu=0;
				 var total_loan_deduction=$('#total_loan_deduction').val();
				var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_dedu)+parseInt(other_loan_deduction);
				
				
				
				var total_deduct = parseInt(gross)-parseInt(deduct);
			$('#deduct').val(deduct);
				$('#net').val(total_deduct);
				
		     
			}else{
					var hra = $('#hra').val();
					
				if(parseInt(basic) < parseInt(hra_deduc)){
					
					alert('Please enter the valid HRA Deduction / Licence Fees amount.\nHRA Deduction / Licence Fees amonut should not be greater than Basic pay.');
					$('#hra_deduc').val(0);
					var hra = $('#hra').val();
				    var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var adv_sal = $('#adv_sal').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					//alert(deduct)
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
					
				}else{
				
				
				    var hra = $('#hra').val();
				    var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var hra_deduc=$('#hra_deduc').val();
					var reduct3=$('#reduct3').val();
					
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				
				}
					
					
			}
			}
		
		
//==================================================================================================================================================		
		
			function ovdCal(){
			var overdrawn = $('#overdrawn').val();
			if(overdrawn==''){
				//$('#overdrawn').val(0);
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli = $('#gsli').val();
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = 0;
				var reduct3=$('#reduct3').val();
				
				var hra_deduc = $('#hra_deduc').val();
		var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#deduct').val(deduct);
				$('#net').val(total_deduct);
			}else{
			   
				var actual_pay_band = $('#pay_in_band').val();
				var consolidated_pay = $('#consolidated_pay').val();
				if(parseInt(consolidated_pay)==0)
				{
				if(overdrawn > parseInt(actual_pay_band)){
					alert('Please enter the valid overdrawn amount.\nOverdrawn amonut should not be greater than Pay in pay band.');
					$('#overdrawn').val(0);
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				}else{
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				}
				}
				else
				{
					
				if(overdrawn > parseInt(consolidated_pay)){
					alert('Please enter the valid overdrawn amount.\nOverdrawn amonut should not be greater than consolidated_pay.');
					$('#overdrawn').val(0);
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var adv_sal = $('#adv_sal').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
					
				}else{
				    
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					//alert(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#deduct').val(deduct);
					$('#net').val(total_deduct);
				}
				
					
					
					}
			}
		}
		
		
		function gsliCal(){
			var gsli = $('#gsli').val();
			//alert(gsli);
			if(gsli=='' || gsli=='0'){
				//$('#gsli').val(0);
				//alert(55);
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli =0;
				var other_loan_deduction = $('#other_loan_deduction').val();
				var overdrawn = $('#overdrawn').val();
				var reduct3=$('#reduct3').val();
				//var adv_sal = $('#adv_sal').val();
					var hra_deduc = $('#hra_deduc').val();
					
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					$('#deduct').val(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
			}else{
				
				var gsli = $('#gsli').val();
				var c =120;
				if(parseInt(gsli) >parseInt(c)){
					//alert(99);
					alert('Please enter the valid GSLI amount.\nGSLI amonut should not be greater then 120.');
					$('#gsli').val(0);
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gross = $('#gross').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
				$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}else{
					//alert(88);
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					
					$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				}
			}
		}
		
		function otherloanCal(){
			
			var other_loan_deduction = $('#other_loan_deduction').val();
			//alert(gsli);
			if(other_loan_deduction=='' || other_loan_deduction=='0'){
				//$('#gsli').val(0);
				//alert(55);
				//var pfl = $('#pf_loan').val();
				var total_loan_deduction=$('#total_loan_deduction').val();
				//alert(total_loan_deduction);
				var gross = $('#gross').val();
				var gpf = $('#gpf').val();
				var cpfd = $('#cpf_deduct').val();
				var ptax = $('#p_tax').val();
				var itax = $('#i_tax').val();
				var gsli =$('#gsli').val();
				var other_loan_deduction = 0;
				var overdrawn = $('#overdrawn').val();
				var reduct3=$('#reduct3').val();
				//var adv_sal = $('#adv_sal').val();
					var hra_deduc = $('#hra_deduc').val();
					
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					$('#deduct').val(deduct);
				var total_deduct = parseInt(gross)-parseInt(deduct);
				$('#net').val(total_deduct);
			}else{
				
				
					var gross = $('#gross').val();
					//var pfl = $('#pf_loan').val();
					var total_loan_deduction=$('#total_loan_deduction').val();
					//alert(total_loan_deduction);
					var gpf = $('#gpf').val();
					var cpfd = $('#cpf_deduct').val();
					var ptax = $('#p_tax').val();
					var itax = $('#i_tax').val();
					var gsli = $('#gsli').val();
					var other_loan_deduction = $('#other_loan_deduction').val();
					var overdrawn = $('#overdrawn').val();
					var reduct3=$('#reduct3').val();
					var hra_deduc = $('#hra_deduc').val();
					var deduct = parseInt(gpf)+parseInt(total_loan_deduction)+parseInt(ptax)+parseInt(itax)+parseInt(overdrawn)+parseInt(gsli)+parseInt(reduct3)+parseInt(hra_deduc)+parseInt(other_loan_deduction);
					
					$('#deduct').val(deduct);
					var total_deduct = parseInt(gross)-parseInt(deduct);
					$('#net').val(total_deduct);
				//}
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
				   if($('#part_salary_cause_msg').val().trim()  == '')
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
				
			
			
			if(isNaN($('#salary_type').val())) 
			{
				alert('Please select valid salary type.');
				$('#consolidated_pay').focus();
				return false;	
			}
		
//			if(isNaN($('#consolidated_pay').val()) || $('#consolidated_pay').val()<0) {
//			alert('Please enter valid Consolidated Pay.');
//			$('#consolidated_pay').focus();
//			return false;	
//			}
			
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
			
		/*   if(isNaN($('#pf_loan').val()) || $('#pf_loan').val()<0) {
				alert('PF loan amount is not valid.');
				$('#pf_loan').focus();
				return false;	
			}*/
			
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
			if(isNaN($('#other_loan_deduction').val()) || $('#other_loan_deduction').val()<0) {
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
		function limiter(k){
			var count = "500";
			
			if($('#cause_msg').val().charAt(0)!=' ' && $('#cause_msg').val()!='' && $('#is_overdrawn').val()=='yes_ovd'){
				//alert('ok');
				$('#overdrawn').removeAttr('readonly');
				$('#overdrawn').css('background-color','#FFFFFF');
				$('#overdrawn').val(0);
			}else{
				$('#overdrawn').attr('readonly','readonly');
				$('#overdrawn').css('background-color','#EEE');
			}
			if(k=='over')
			{
				var tex = $('#cause_msg').val();
			}
			else if(k=='no')
			{
				var tex = $('#no_salary_cause_msg').val();
			}
			else if(k=='part')
			{
				var tex = $('#part_salary_cause_msg').val();
			}
			
			var len = tex.length;
			var a=count-len;
			
			if(len > count)
			{
				tex = tex.substring(0,count);
				if(k=='over')
				{
					$('#cause_msg').val(tex);
				}
				else if(k=='no')
				{
					$('#no_salary_cause_msg').val(tex);
				}
				else if(k=='part')
				{
					$('#part_salary_cause_msg').val(tex);
				}
				$('#cause_msg').val(tex);
        		return false;
        	}
			
			if(k=='over')
			{
				$('#limit_over').text(count-len);
			}
			else if(k=='no')
			{
				$('#limit_no').text(count-len);
			}
			else if(k=='part')
			{
				$('#limit_part').text(count-len);
			}
			
        }
		
		function cal_ptax(gross)
		{
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/sal_requisition/cal_ptax.php?gross='+gross, function(data){
				// alert(data);
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
			}

</script>

