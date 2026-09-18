<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';


$crypto = new cryptography();
if(isset($_GET['dise']))
{
	$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}


/*if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}*/

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

$cryptoGraph=new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$requisition_type=$cryptoGraph->encode('1001',4);

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic---------------------------------------------------------------------------------
//-----------------------------QUERY----------------------------------------------------------------------------------

$db = new database();

if($logged_user=='zpacc')
{
	$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];
}
if($logged_user=='DA')
{
	$stake_clause='ps_id_fk='.$_SESSION['location']['ps_id'];
}
if($logged_user=='GP')
{
	$stake_clause='gp_id_fk='.$_SESSION['location']['gp_id'];
}

$employee_details_fetch=$db->fetch_table(" SELECT emp_id_pk,emp_first_name,emp_second_name,emp_last_name from prd_employee_master where emp_status in ('1','9','2') AND ".$stake_clause." ORDER BY emp_first_name");


?>

<script>

	$(document).ready(function() {

	$('#year,#month,#emp_name').change(function(){
			var year=$('#year').val();
			var month=$('#month').val();
			var emp_name=$('#emp_name').val();
			var ropa_status=$('#ropa_status').val();
			//alert(ropa_status);
			if($('#all_emp').val()!='')
			{
				var all_emp=$('#all_emp').val();
				if(year!="" && month!="")
				{
					$.post('<?= $config['base_url'] ?>page/all_moduls/payslip_generation/ajax_payment_check.php?month='+month+'&year='+year+'&type='+all_emp+'&ropa='+ropa_status, function(data){//alert(data);
						if(data=='1')
						{
							$('#show_box').show();
							$('#show_box').html('<a class="btn btn-info btn-sm"  href="employee_payslip_pdf.php?month='+month+'&year='+year+'&type='+all_emp+'&ropa='+ropa_status+'"><i class="fa fa-file-text"></i>  Download Payslip</a>');
						}
						else
						{
							$('#show_box').show();
							$('#show_box').html('<p style="color:red;font-weight:bold;">Payment File not generated</p>.');
						}
					});
					
				}
				else
				{
					$('#show_box').hide();
				}
			}
			
			else if($('#indi_emp').val()!='')
			{
				var indi_emp=$('#indi_emp').val();
				if(year!="" && month!="" && emp_name!="")
				{
					
					$.post('<?= $config['base_url'] ?>page/all_moduls/payslip_generation/ajax_payment_check.php?month='+month+'&year='+year+'&type='+indi_emp+'&id='+emp_name+'&ropa='+ropa_status, function(data){//alert(data);
						if(data=='1')
						{
							$('#show_box').show();
							$('#show_box').html('<a class="btn btn-info btn-sm"  href="employee_payslip_pdf.php?month='+month+'&year='+year+'&type='+indi_emp+'&id='+emp_name+'&ropa='+ropa_status+'"><i class="fa fa-file-text"></i>  Download Payslip</a>');
						}
						else
						{
							$('#show_box').show();
							$('#show_box').html('<p style="color:red;font-weight:bold;">Payment File not generated</p>.');
						}
					});
					
				}
				else
				{
					$('#show_box').hide();
				}
			}
			else
			{
				$('#show_box').hide();
			}
			
		});
	});


function select_ropa(i){
		
		if(i.value >= 2020 ){ 
			$('#ropa_div').show();
		}
		else{
			$('#ropa_div').hide();
			//$('#ropa_status').val(0);
		}
	}	
	
	
	function show_form(k)
	{
		var all_emp='<?php echo $cryptoGraph->encode('all',4); ?>';
		var indi_emp='<?php echo $cryptoGraph->encode('indi',4); ?>';
		if(k==1)
		{
			$('#name_div').hide();
			$('#year_div').show();
			//$('#ropa_div').show();
			$('#month_div').show();
			$('#indi_emp').val('');
			$('#all_emp').val(all_emp);
			$('#show_box').hide();
			$('#year').val('');
			$('#month').val('');
			$('#emp_name').val('');
		}
		else if(k==2)
		{
			$('#name_div').show();
			$('#year_div').show();
			//$('#ropa_div').show();
			$('#month_div').show();
			$('#all_emp').val('');
			$('#indi_emp').val(indi_emp);
			$('#show_box').hide();
			$('#year').val('');
			$('#month').val('');
			$('#emp_name').val('');
		}
	}
	
	
	
	
</script>


<!--CONTENT START-->
<div class="content">
<!-- Common Back Button --->
	<?php require '../../common_back_btns.php'; ?>	
    <div class="welcome_msg">
    <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
    <?php
	if(isset($_SESSION['location']['gp_name'])) {
    echo $_SESSION['location']['gp_name'];
	}else if(isset($_SESSION['location']['ps_name'])) {
    echo $_SESSION['location']['ps_name'].", ";
    }elseif(isset($_SESSION['location']['block_name'])) {
    echo $_SESSION['location']['block_name'].", ";
    } elseif(isset($_SESSION['location']['district_name'])) {
    echo $_SESSION['location']['district_name'];
    } elseif(isset($_SESSION['location']['state_name'])) {
    echo $_SESSION['location']['state_name'];
    } ?></h2><h3>
    <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
    ?></h3>
    </div>
    
    <div class="row" id="cont">
        <div class="content">
			<script>
            $(document).ready(function(){
				$( "tr:odd" ).css( "background-color", "#dfeaec" );
				$( "tr:even" ).css( "background-color", "#fff6" ); 
            });
            </script>
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-11  ">
                                <div class="offer offer-success">
                                    <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
                                    <?php  
                                    if(isset($_SESSION['msg']))
                                    {
										echo $_SESSION['msg'];
										unset($_SESSION['msg']);
                                    }
                                    ?>
                                    </div>
                                    <div class="offer-content">
                                        <h1 class="heading">EMPLOYEE PAYSLIP GENERATION</h1>
                                        <div class="border"></div>
                                        </br></br>
                                        
                                        
                                        <form class="form-horizontal" id="loginForm" method="post">
                                            <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                                            <div class="form-group text-center" >
                                                <button type="button" id="all_emp" name="all_emp" class="btn btn-info" onclick="show_form(1);">All Employee Payslip</button>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type="button" id="indi_emp" name="indi_emp" class="btn btn-info" onclick="show_form(2);">Individual Employee Payslip</button>
                                            </div>
											<?php 
											
												if(!empty($_REQUEST['year'])){
													$year = $_REQUEST['year'];
													$month = $_REQUEST['month'];
													$ropa_status = $_REQUEST['ropa'];
												}
												else {
													$year = "";
													$month = "";
													$ropa_status = "";
												}
											?>
                                            <div style="height:10px;"></div>
                                            <div class="form-group col-sm-12" style="display:none;" id="name_div">
                                            <div class="col-sm-4"></div>
                                                <label for="inputPassword3" class="col-sm-3 control-label" style="color:#0070A3; width:auto; margin-left:-31px;">Employee Name<span class="star_color">*</span>:</label>
                                                <div class="col-sm-3">
                                                    <select class="login-input" name="emp_name" id="emp_name" style="width:150px; margin-left:11px;" >
                                                    <option value="">-Please Select-</option>
                                                    <? foreach($employee_details_fetch as $key){ $key['emp_id_pk']. '<br />'; ?>
                                                    <option value="<?= $cryptoGraph->encode($key['emp_id_pk'],4); ?>"><?= $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];  ?></option>
                                                    <? } ?>
                                                    </select>
                                                </div> 
                                            </div>
                                            <div style="height:10px;"></div>
                                            <div class="form-group col-sm-12" style="display:none;" id="year_div">
                                            <div class="col-sm-4"></div>
                                                <label for="inputPassword3" class="col-sm-3 control-label" style="color:#0070A3; width:auto;">Select Year<span class="star_color">*</span>:</label>
                                                <div class="col-sm-3">
                                                    <select name="year" class="login-input" id="year" style="width:150px; margin-left:12px;" onchange='select_ropa(this);'>
                                                        <option value="">-Please Select-</option>
                                                        <?php
                                                        for($i=2015; $i<=date('Y'); $i++)
                                                        {
                                                        ?>
                                                        	<option value="<?php echo $i?>" <? if($i==$year){echo "selected";} ?>><?php echo $i; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div> 
                                            </div>
											
											<div style="height:10px;"></div>
											<div class="form-group col-sm-12" id='ropa_div' style='display:none;'>
											<div class="col-sm-4"></div>
											   
											<label for="inputPassword3" class="col-sm-3 control-label" style="color:#0070A3; width:auto;">Select ROPA: <span class="star_color">*</span></label>
												<div class="col-sm-3">
													<select name="ropa_status" class="login-input" id="ropa_status" style="width:150px;" >
														<option value="">-Please Select-</option>
														<option value="<?= $cryptoGraph->encode(2,4);?>" <? if($ropa_status=='2'){echo "selected";} ?> >ROPA 2009</option>
														<option value="<?= $cryptoGraph->encode(1,4);?>" <? if($ropa_status=='1'){echo "selected";} ?> >ROPA 2019</option>	
													</select>
												  
												</div>
											</div>
	
	
                                            <div style="height:10px;"></div>
                                            <div class="form-group col-sm-12" style="display:none;" id="month_div">
                                            <div class="col-sm-4"></div>
                                                <label for="inputPassword3" class="col-sm-3 control-label" style="color:#0070A3; width:auto;">Select Month<span class="star_color">*</span>:</label>
                                                <div class="col-sm-3">
                                                    <select name="month" class="login-input"  id="month" style="width:150px;">
                                                    <option value="">-Please Select-</option>
                                                    <option value="01" <? if($month=='01'){echo "selected";} ?>>January</option>
                                                    <option value="02"  <? if($month=='02'){echo "selected";} ?>>February</option>
                                                    <option value="03"  <? if($month=='03'){echo "selected";} ?>>March</option>
                                                    <option value="04"  <? if($month=='04'){echo "selected";} ?>>April</option>
                                                    <option value="05"  <? if($month=='05'){echo "selected";} ?>>May</option>
                                                    <option value="06"  <? if($month=='06'){echo "selected";} ?>>June</option>
                                                    <option value="07"  <? if($month=='07'){echo "selected";} ?>>July</option>
                                                    <option value="08"  <? if($month=='08'){echo "selected";} ?>>August</option>
                                                    <option value="09"  <? if($month=='09'){echo "selected";} ?>>September</option>
                                                    <option value="10"  <? if($month=='10'){echo "selected";} ?>>October</option>
                                                    <option value="11"  <? if($month=='11'){echo "selected";} ?>>November</option>
                                                    <option value="12"  <? if($month=='12'){echo "selected";} ?>>December</option>
                                                    </select>
                                                </div> 
                                            </div>
                                        </form>
                                        <div style="height:20px;"></div>
                                        <div id="show_box" style="margin-left:41%;display:none;">
                                        	<a class="btn btn-info btn-sm"  href="employee_payslip_pdf.php?month=<?= $month?>&year=<?= $year?>&id=<?= $crypto->encode($emp_id,4)?>&ropa=<?= $crypto->encode($_REQUEST['ropa'],4)?>"><i class="fa fa-file-text"></i>  Download Payslip</a>
                                        </div>
                                    </div>
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



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
<script>
function valid_code(){
	if($("#type").val()==""){
		alert("Please Select Type");
		$("#type").focus();
		return false;
		
	}
}
</script>


<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		border:3px solid #fff;
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #5B7778;
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



<style>
  .shape{    
    border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
	-ms-transform:rotate(360deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(360deg); /* Safari and Chrome */
	transform:rotate(360deg);
}
.offer{
	/*background:rgba(228, 232, 223, 0.59);*/
	background:rgba(243, 246, 240, 0.71); border:1px solid #ddd; box-shadow: 0 10px 20px rgba(148, 112, 29, 0.64); margin: 15px 0; overflow:hidden; margin-right:28px; padding-bottom:22px;padding-top:10px;
}

.shape {
	border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-radius{
	border-radius:7px;
}
.offer-danger {	border-color: #d9534f; }
.offer-danger .shape{
	border-color: transparent #d9534f transparent transparent;
}
.offer-success {	/*border-color: #9e9fb1;*/ }
.offer-success .shape{
	border-color: transparent #5cb85c transparent transparent;
}
.offer-default {	border-color: #999999; }
.offer-default .shape{
	border-color: transparent #999999 transparent transparent;
}
.offer-primary {	border-color: #428bca; }
.offer-primary .shape{
	border-color: transparent #428bca transparent transparent;
}
.offer-info {	border-color: #5bc0de; }
.offer-info .shape{
	border-color: transparent #5bc0de transparent transparent;
}
.offer-warning {	border-color: #f0ad4e; }
.offer-warning .shape{
	border-color: transparent #f0ad4e transparent transparent;
}

.shape-text{
	color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
	-ms-transform:rotate(30deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(30deg); /* Safari and Chrome */
	transform:rotate(30deg);
}	
.offer-content{
		padding:16px 100px 20px;
}
@media (min-width: 487px) {
  .container {
    max-width: 750px;
  }
  .col-sm-6 {
    width: 50%;
  }
}
@media (min-width: 900px) {
  .container {
    max-width: 970px;
  }

}

@media (min-width: 1200px) {
  .container {
    max-width: 1170px;
  }
  .col-lg-3 {
    width: 25%;
  }
  }


</style>



