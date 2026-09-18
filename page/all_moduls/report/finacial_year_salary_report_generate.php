<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

//require '../../../page_visite.php';
$crypto = new cryptography();

				/*echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";*/
			
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


if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

	
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$db=new database();
//$banklist=$db->fetch_table("select bank_name,bank_code from ehrms_dise_bank_master order by bank_name");
//$banklist=$db->fetch_table("select distinct(bm.bank_code),bank_name from ehrms_dise_bank_master bm
//inner join ehrms_dise_teacher_primary tch on tch.bankname=bm.bank_code
//where substring(schcd,1,4)='".$_SESSION['location']['schcd']."'");
?>
<!-- Common Back Button --->
<div class="content">
<div style="padding:10px">
	<?php require '../../common_back_btns.php'; ?>
</div>
<?php function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}?>
	<div class="mainContent float_l" style="min-height: 400px;">
		<script>
			$(document).ready(function(){
			$("#stake,#year,#month,#type").change(function(){
				//alert('123');
				stk = $("#stake option:selected").val();
				mo = $("#month option:selected").val();
			    ye = $("#year option:selected").val();
				type = $("#type option:selected").val();
				if(
			    	$("#stake option:selected").val() != "" &&
					$("#month option:selected").val() != "" &&
			    	$("#year option:selected").val() != ""  &&
					$("#type option:selected").val() != ""
			    	){
				//$("#show").html('<a class="btn btn-info btn-sm" href="ifms_details_report.php?stk='+stk+'&mo='+mo+'&ye='+ye+'&type='+type+'"" style="width: 90%;text-align: left;"><i class="fa fa-file-text"></i>  IFMS UPLOAD DETAILS</a>');
				/*$("#show2").html('<a class="btn btn-info btn-sm" href="monthwise_salary_disburstment.php?mo='+mo+'&ye='+ye+'" style="width: 90%;text-align: left;"><i class="fa fa-file-text"></i>  Salary Amount Disbursement Report</a>');*/
				}
			   });
			   
			});
		</script>
    
        
        
        	<div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3>
					  <? echo $_SESSION['location']['state_name'];;
                      ?></h3>
       </div>
            <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
 <div class="col-sm-12">
   <div class="container">
     <div class="row">
        <div class="col-xs-11 ">
           <div class="offer offer-success">
              <div class="offer-content">
                 <div class="row" id="cont">
                      <div class="page_title">
               <h1>	Financial year wise Salary Report</h1>
                   <div class="border"></div>
                     </div>
                     <form class="form-horizontal" action="report_return_generation_excel.php"  method="post" onsubmit="return valid_code();">
        <div class="search_box" style="padding-left: 28%;">
        <br />
        <div id="form_show" class="form-horizontal">
                        <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label"> FINANCIAL YEAR<span class="star_color">*</span>:</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="year" id="year">
                                <option value="">---SELECT YEAR---</option>
                                
                                <option value="<?php echo $crypto->encode('2017-2018',4) ?>">2017-2018</option>
                                <option value="<?php echo $crypto->encode('2018-2019',4) ?>">2018-2019</option>
                   
                            </select>
                            </div>
                        </div>
            			<br/>	 
                             
        <!--<div class="form-group">
        
        	<label for="inputPassword3" class="col-sm-4 control-label" >From Date <span class="star_color">*</span></label>
        	<div class="col-sm-4">
        	<input type="text" name="From_Date" id="From_Date" readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off"  value="<?= dateshow($From_Date); ?>" style="cursor:pointer;">
        	</div>
        </div>
       <br/>
     
     <div class="form-group">
     
     <label for="inputPassword3" class="col-sm-4 control-label" >To Date <span class="star_color">*</span></label>
     <div class="col-sm-4">
      <input type="text" name="To_Date" id="To_Date" readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off"  value="<?= dateshow($To_Date); ?>" style="cursor:pointer;">
    </div>
    </div>
    <br/>-->
                            
                             
                             
                             <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">SALARY REPORT STAKE<span class="star_color">*</span>:</label>
                                  <div class="col-sm-4">
                                    <select class="form-control" name="stake" id="stake" onChange="value_pass_stake();"> 
                                    <option value="0">---SELECT STAKE---</option>
                                    
                                    <option value="<?php echo 1; ?>">GP</option>
                                    <option value="<?php echo 2; ?>">PS</option>
                                    <option value="<?php echo 3; ?>">ZP</option>
                                    </select>
                                   </div>
                             </div>
                      <br/>
                        <!--<div class="form-group" id="district" style="display:yes">
                        <label for="inputPassword3" class="col-sm-4 control-label">DISTRICT NAME: <span class="star_color">*</span></label>
                       <!-- <div class="col-sm-4">-->
                        
                        
                        
                        
                        <div class="form-group" id="district" style="display:yes">
                        <label for="inputPassword3" class="col-sm-4 control-label">DISTRICT NAME: <span class="star_color">*</span></label>
                       <!-- <div class="col-sm-4">-->
                        <div class="col-sm-4" >
                        
                       
                        <select class="form-control" name="emp_district"  id="emp_district"   onchange="district()" /> 
                         
                        
                        
                        <option value="">--Please Select--</option>
               
                     </select>
                          </div>
                        </div>
                        
                        
                        
                        </br>
                        
                        
                        <div class="form-group" id="zp" style="display:none">
                        <label for="inputPassword3" class="col-sm-4 control-label">ZP NAME: <span class="star_color">*</span></label>
                       <!-- <div class="col-sm-4">-->
                        <div class="col-sm-4" >
                        
                        <select class="form-control" name="zp_name"  id="zp_name"   /> 
                       <option value="">--Please Select--</option>
               
             
              
                     </select>
               
               
                     
                          </div>
                        </div>
                        
                       
                       
                     <div class="form-group" id="ps" style="display:none">
                        <label for="inputPassword3" class="col-sm-4 control-label" >PS NAME: <span class="star_color">*</span></label>
                       <?php $arr=$db->fetch_table("select ps_id_pk,ps_name  from prd_location_master_panchayat_samiti order by ps_name ASC");?>
                        <div class="col-sm-4" style="height:200px;width:400px;background-color:#FFFFFF;overflow-y:scroll;overflow-x: hidden;border-radius: 5px;border:1px solid #006;padding:5px;  text-align: left;" id="ps_name">
                        <!--<select class="form-control upper_case" name="ps_id" id="ps_id" onChange="value_pass();">-->
              
                        <label><input type="checkbox" name="sample" id="selectall" /> SELECT ALL</label>
                        <label for="selectall"><span></span></label>
                        &nbsp;&nbsp;
                        <?php
                        

                        foreach($arr as $key)
                        {?>
							&nbsp;&nbsp;
                            <input type="checkbox" name="arr2[]" class="ps_id" id="<?= $key['ps_id_pk']?>" autocomplete="off"  value="<?=$crypto->encode($key['ps_id_pk'],4)?>" />
                            <label for="<?= $key['ps_id_pk']?>"><span></span></label>
                            
                        <?php //echo '<input type="checkbox" name="arr2[]" style="float: left !important" class="ps_id" id="'.$key['ps_id_pk'].'" value="'.$crypto->encode($key['ps_id_pk'],4).'" />'; ?>&nbsp;&nbsp;
                        
                        <?=$key['ps_name']; ?><br />
                        
         
                        <?php
                        }
                        ?>
                        
                          </div>
                        </div>
                     <br/>
                     <div class="form-group" id="block" style="display:none">
                        <label for="inputPassword3" class="col-sm-4 control-label"  >BLOCK NAME: <span class="star_color">*</span></label>
                        <?php $arr=$db->fetch_table("select block_id_pk,block_name,block_code  from prd_location_master_block order by block_name ASC");?>
                        <div class="col-sm-4" style="height:200px;width:400px;background-color:#FFFFFF;overflow-y:scroll;overflow-x: hidden;border-radius: 5px;border:1px solid #006;padding:5px;  text-align: left;" id="block_name">
                        <!--<select class="form-control upper_case" name="ps_id" id="ps_id" onChange="value_pass();">-->
                        
                        <label><input type="checkbox" name="sample" id="selectalb" /> SELECT ALL</label>
                        <label for="selectalb"><span></span></label>
                        &nbsp;&nbsp;
                        
                        
                      <!--<label><input type="checkbox" name="block_id" id="block_id" /> SELECT ALL</label> 
                       <label for="block_id"><span></span></label>-->
                        
                        <?php
                        

                        foreach($arr as $key)
                        {?>
							&nbsp;&nbsp;
                            
                            <input type="text" name="arr3[]" class="block_id1" id="<?= $key['block_code']?>" autocomplete="off"  value="<?=$crypto->encode($key['block_code'],4)?>" />&nbsp;&nbsp;
                            <label for="<?= $key['block_code']?>"><span></span></label>
                        <?php // echo '<input type="checkbox" name="arr3[]"  class="block_id1"  id="'.$key['block_code'].'" value="'.$crypto->encode($key['block_code'],4).'" />'; ?>&nbsp;&nbsp;
                        
                        <?=$key['block_name'] ?><br />
                        
         
                        <?php
                        }
                        ?>
                          </div>
                        </div>
            		 <br/>
                     
                     
                     
                    <div class="form-group">
                   <div class="col-sm-offset-3 col-md-4" ><button  class="btn btn-success">submit</button></div>
                    		<!--<div class="col-sm-offset-3 col-md-4"  id="show">submit</div>-->
                    				</div>
                                    </form>
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
  

<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		

		
		
		
		
	
	
		
</script>
<script>
function valid_code(){
	if($("#year").val() == '')
	{
		alert('Please Select Finacial Year.');
		$("#year").focus();
		return false;
	}
	
	if($("#stake").val() == '0')
	{
		alert('Please Select Stake.');
		$("#stake").focus();
		return false;
	}
	if($("#emp_district").val() =='')
	{
		alert('Please Select Dristrict Name.');
		$("#emp_district").focus();
		return false;
	}
	
	
	
	
	return true;
}
</script>


<script type="text/javascript">
$(function() {
			var startDate = new Date(<?=date('Y');?>, <?=date('m');?>, 1);
   			$( "#From_Date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "0:+10",
				dateFormat: 'dd-mm-yy',
				minDate: startDate
			});
			var startDate = new Date(<?=date('Y');?>, <?=date('m');?>, 1);
			$( "#To_Date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "0:+10",
				dateFormat: 'dd-mm-yy',
				minDate: startDate
			});
        });
</script>



<script>
$('#selectall').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});

$('#selectalb').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});

/*$('#block_id').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});*/

$('.ps_id').click(function() {
    if ($(".ps_id:checked").length == $(".ps_id").length) {
        $('#selectall').attr('checked', true);
    } else {
        $('#selectall').attr('checked', false);
    }
});

$('.block_id1').click(function() {
    if ($(".block_id1:checked").length == $(".block_id1").length) {
        $('#selectalb').attr('checked', true);
    } else {
        $('#selectalb').attr('checked', false);
    }
});
$('.district_id').click(function() {
    if ($(".district_id:checked").length == $(".district_id").length) {
		//alert(11);
        $('.emp_district').attr('checked', true);
    } else {
		//alert(12);
        $('.emp_district').attr('checked', false);
    }
	
	
	
});

var dist_id='';
/*$('.district_id').click(function(){
	//alert(11);
	//var stake=$("#stake").val();
	//var val = [];
	//alert(1234);
	var avb=$(".district_id:checked").val();
	//alert(avb);
	if( $(this).is(":checked")==true)
	{
	var val=$(".district_id:checked").val();
	var dist_val=$("#u").val();
	dist_val=dist_val+','+val;
	alert(dist_val);
	$("#u").val(dist_val);
	}
	else
	{alert(22222);
	}
	
	
	/*$(':checkbox:checked').each(function(i){
	val[i] = $(this).val();
	//alert(val[i]);
	//alert(stake);
	
	
	$.post('<?= $config['base_url'] ?>page/all_moduls/report/ajax_district_wise_block_gp_ps_name.php?district='+val[i]+'&stake='+stake, function(data){
	// alert (data);
	if(stake==1)
	{
		$("#block_name").html(data);
	}
	if(stake==2)
	{
	
	 $("#ps_name").html(data);	
	}*/
/* });
 
	
	
	
	
	
	});*/
	
//});
/*function district(){
	alert(val);
	var stake=$("#stake").val();
 $.post('<?= $config['base_url'] ?>page/all_moduls/report/ajax_district_wise_block_gp_ps_name.php?district='+val+'&stake='+stake, function(data){
	// alert (data);
	if(stake==1)
	{
		$("#block_name").html(data);
	}
	if(stake==2)
	{
	
	 $("#ps_name").html(data);	
	}
 });

}*/

function district(){
		
	 var stake=$("#stake").val();
	 var val=$("#emp_district").val();
	// alert(val);
	 
 $.post('<?= $config['base_url'] ?>page/all_moduls/report/ajax_district_wise_block_gp_ps_name.php?district='+val+'&stake='+stake, function(data){
	//alert(data);
	

	
	
	 if(stake==1)
	{
		$("#block_name").html(data);
		$("#district").show();
		$("#block").show();
		$("#ps").hide();
		
	}
	if(stake==2)
	{
	  
		$("#ps_name").html(data);	
		$("#district").show();
		$("#block").hide();
		$("#ps").show();
	}
	if(stake==0)
	{
		$("#district").show();
		$("#block").hide();
		$("#ps").hide();
		$("#zp").hide();
		
	}
	
	if(stake==3)
	{
		$("#zp_name").html(data);
		$("#district").show();
		$("#block").hide();
		$("#ps").hide();
		$("#zp").show();
		//$("#zp_name").show();
		
	}
	 
	 
 });

}


function value_pass_stake()
{
	 var stake=$("#stake").val();
 $.post('<?= $config['base_url'] ?>page/all_moduls/report/ajax_district_fetch.php?stake='+stake, function(data){
	//alert(data);
	$("#emp_district").html(data);
	//alert(stake);
	if(stake==1)
	{   //$("#district").show();
		//$("#block").show();
		$("#ps").hide();
		$("#block").hide();
		$("#zp").hide();
		//$("#district").val('');
		
		
		
		
	}
	else if(stake==2)
	{
		//$("#district").show();
		$("#block").hide();
		$("#ps").hide();
		$("#zp").hide();
		//$("#ps").show();
	}
	else if(stake==0)
	{
		$("#district").show();
		$("#block").hide();
		$("#ps").hide();
		$("#zp").hide();
		$("#district").val("");
	}
	
	else if(stake==3)
	{
		//alert(12);
		$("#district").show();
		$("#block").hide();
		$("#ps").hide();
		//$("#zp").show();
		//$("#zp_name").show();
		
	}
	 });
}


</script>


<script>
function disable_selection(select_id)
{
	if($("#"+select_id).prop("checked")==true)
	{
		$("#emp_group").prop("disabled", true);
		$("#emp_grade_pay").prop("disabled", true);
		$("#emp_caste").prop("disabled", true);
		$("#emp_designation").prop("disabled", true);
		$("#emp_religion").prop("disabled", true);
		$("#emp_sex").prop("disabled", true);
		$("#"+select_id).prop("disabled", false);
		$("#"+select_id+"_val").prop("disabled", false);
	}
	else if($("#"+select_id).prop("checked")==false)
	{
		$("#emp_group").prop("disabled", false);
		$("#emp_grade_pay").prop("disabled", false);
		$("#emp_caste").prop("disabled", false);
		$("#emp_designation").prop("disabled", false);
		$("#emp_religion").prop("disabled", false);
		$("#emp_sex").prop("disabled", false);
		$("#"+select_id+"_val").prop("disabled", true);
	}
}
</script>





 
    			    <!--SIDEBAR START-->
		<style>
			/*.side-nav ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
			}
			.side-nav li {
				margin-bottom: 2px;
			}
			.side-nav a:link, .nav .side-nav a:visited {
				display: block;
				color: #FFFFFF;
				background-color: #bbb;
				text-align: center;
				padding: 4px;
				text-decoration: none;
				text-transform: uppercase;
				border-radius: 5px;
				-moz-border-radius: 4px;
			}
			.side-nav a:hover, .side-nav a:active {
				background-color: #7A991A;
			}
			.bav-a{
				color: #fff;
			}
			.msg-dig
			{
				display: block;
				color:#900;
				background-color:#ACDBEA;
				text-align: center;
				padding: 4px;
				text-decoration: none;
				text-transform: uppercase;
				border-radius: 5px;
				-moz-border-radius: 4px;
			}*/
			.btn1{
				display: block;
				margin-bottom: 0;
				padding: 5px 10px;
				font-size: 12px;
				line-height: 1.5;
				border-radius: 3px;
				font-weight: 400;
				
				text-align: center;
				white-space: nowrap;
				vertical-align: middle;
				-ms-touch-action: manipulation;
				touch-action: manipulation;
				cursor: pointer;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
				border-radius: 4px;
				color: #fff;
				background-color: #5bc0de;
				border-color: #46b8da;
				}
		.sal_report{
		display:block;
		height:auto;
		width:120px;
		padding: 10px 20px;
		border-radius:6px;
		-moz-border-radius:6px;
		background-color: #5BC0DE;
		width: 222px;
		margin-bottom: 5px;
		color: #FFF;
		text-decoration: none;
		
	}
	
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
 

    <div class="clear"></div>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
