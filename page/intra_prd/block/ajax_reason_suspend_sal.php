<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
//print_r($_REQUEST);
$emp_id_pk=$cryptoGraph->decode($_REQUEST['id'],4);
//echo $emp_id_pk;exit;
?>

<style>
.school_reason table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school_reason table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school_reason table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school_reason table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school_reason{
	background-color: #D3C8C8;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school_reason .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school_reason .action .ui-widget{
	font-size: 11px;
}
.school_reason .action{
	text-align: center;
}
.school_reason .action .ui-button .ui-button-text{
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
$common['title'] = "Reason for Stop Salary| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>
<?
$db=new database();
$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk,emp_id_const from prd_employee_master where emp_status in('1','2','9') AND emp_id_pk='".$emp_id_pk."'");

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




?>
<script>



/*$(function() {

    $("#suspention_effect_date").datepicker({
			
        dateFormat: "dd-mm-yy",
        onSelect: function(dateText, instance) {
        date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
        date.setMonth(date.getMonth() + 3);
		$("#suspention_hidden_date").datepicker("setDate", date);
			     
}
});
$("#suspention_hidden_date").datepicker({
	          
              dateFormat: "dd-mm-yy",
			
 });
	
    
        
 

});*/
/*$('.ui-state-default').click(function() {
				$a=$('#suspention_effect_date').val();
				alert($a);
			});*/
/*	$('#th_reason_header').hide();
	$('#td_reason_header').hide();
	$('#reason_id').change(function(e) {
		
        var id=$(this).val();
		if(id=='1991')
		{
			$('#th_reason_header').hide();
			$('#td_reason_header').hide();
				
		}
		else
		{
			$('#th_reason_header').show();
			$('#td_reason_header').show();
		}
    });*/
	
	   $('#suspend_sal_employee').click(function(e) {
		 if($('#suspention_effect_date').val()==''){
			alert('Please enter date of suspend salary.');
			$('#suspention_effect_date').focus();
			return false;
		}
		
		
	
		else if($('#percentage_basic').val()==''){
			alert('Please enter percentage of basic.');
			$('#percentage_basic').focus();
			return false;
		}
			else if($('#percentage_basic').val()<25 || $('#percentage_basic').val()>75){
			alert('Please enter percentage of basic.');
			$('#percentage_basic').focus();
			return false;
		}
		else
		{
			/*if($('#suspention_effect_date').val()!='')
			{
			var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; 
            var mm1=today.getMonth()+4;
            var yyyy = today.getFullYear();
            var today = dd+'-'+mm+'-'+yyyy;
            var today_match = dd+'-'+mm1+'-'+yyyy;
			var a=$('#suspention_effect_date').val();
            var res = a.substring(4, 5);
			
           
			if(a>'01-08-2016') 
               {
               alert("please enter valid date")	;
			   $('#suspention_effect_date').focus('');
			   return false;
			   }
		   }*/ 
			if($('#suspention_effect_date').val()!='')
			{
			var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; 
            var mm1=today.getMonth()+4;
            var yyyy = today.getFullYear();
            var today = dd+'-'+mm+'-'+yyyy;
            var today_match = dd+'-'+mm1+'-'+yyyy;
			var a=$('#suspention_effect_date').val();
            var res = a.substring(4, 5);
			var res1 = a.substring(0, 4);
		
			
           /* if(res<=8 && res1<='2016')
               {
               alert("please enter valid date")	;
			   $('#suspention_effect_date').focus('');
			   return false;
			   }*/
		   }  
			   
		 //alert($(this).val());
		var link1=$(this).val();
		//alert(link1);
		$.post('<?= $config['base_url'] ?>page/intra_prd/block/stop_start_suspend_submit.php?id='+link1+'&flag=suspend',$(this).closest("form").serialize(), function(data){
		//alert(data);
			 //console.log(data);
			 if(data=='<div class="alert alert-success" style="text-align:center"><strong>Salary has been Suspended successfully.</strong></div>')
			 {
				  $('#msg_start').html(data);
				  $('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
				  $('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
				  $('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
				  $('#myModal1').modal('toggle');
		  		  $('#stop').modal('toggle');
				
			 }
			 if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Sunpend fails.</strong></div>')
			 {
				$('#msg_start').html(data);
				  $('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled'); 
				  $('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
				  $('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
				  $('#myModal1').modal('toggle');
		  		  $('#stop').modal('toggle');
			 }
			 
			 if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please select reason!!.</strong></div>')
			 {
				$('#msg_start').html(data);
				  $('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
				  $('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
				  $('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
				  $('#myModal1').modal('toggle');
		  		  $('#stop').modal('toggle');
			 }
			 
			 if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please choose reason!!.</strong></div>')
			 {
				$('#msg_start').html(data);
				  $('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
				  $('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
				  $('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
				  $('#myModal1').modal('toggle');
		  		  $('#stop').modal('toggle');
			 }
			 
		});
		}
       
    });
	
	
	
/*	$( "#suspention_effect_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
	});
$( "#suspention_withdrawn_date" ).datepicker({
			changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
	});*/
	
	
			
</script>


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


<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/
?>
<form id="reason">
<input type="hidden" name="gp_id_fk" value="<?=$cryptoGraph->encode($arr[0]['gp_id_fk'],4)?>" />
<input type="hidden" id="future_date"  />
<div class="emplist">
<div class="school_reason">
<div class="table-responsive"  align="center">
<table width="100%">
<tr>
<th width="18%">Employee Name</th>
<th>Designation</th>
<th>Employee Code</th>
<!--<th>Reason</th>-->
<th id="th_reason_header">Suspension with effect from<span class="star_color">*</span></th>
<th id="th_reason_header">Suspension withdrawn date</th>
<th id="th_reason_header" width="10%">Percentage of basic to be paid<span class="star_color">*</span></th>

<th style=" width:28%">Remarks</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
?>
<tr>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?=  fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $item['emp_id_const'] ?></td>

<td id="td_reason_header">

<input type="text" class="form-control" id="suspention_effect_date" name="suspention_effect_date" placeholder="DD-MM-YYYY" style="width: auto;" />



</td>
<td id="td_suspention_header"><input type="text" name="suspention_withdrawn_date" id="suspention_withdrawn_date" class="form-control col-md-2"  placeholder="DD-MM-YYYY" style="width: auto;"/>


</td>
<td>
<input maxlength="2" type="text" id="percentage_basic" name="percentage_basic" size="8"  />
</td>



<td>
 <textarea class="form-control" id="reason_sus" name="reason_sus" draggable="false"></textarea>

</td>
</tr>
 

<? }} else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>
<form id="reason"  align="center">
<div class="btn-group" role="group" align="center" style="margin-top: 2%;">
  <button type="button" class="btn btn-danger" id="suspend_sal_employee" value="<?=$cryptoGraph->encode($emp_id_pk,4)?>">Suspend Salary</button>
  </div>
</div>
</div>
</div>


