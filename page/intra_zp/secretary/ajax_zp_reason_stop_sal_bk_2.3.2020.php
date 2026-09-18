<?
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
$arr=$db->fetch_table("SELECT emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk,zp_id_fk,emp_id_const,ps_id_fk FROM prd_employee_master WHERE emp_status in('1','2','9') AND emp_id_pk='".$emp_id_pk."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");

$code_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	
	");
function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['designation_id'] == $tcode){
				return $key['designation_name'];
		}
	}
}




?>
<script>
$(document).ready(function(){
		 
		 if($('#reason_id').val()=="")
		 {
			 $('#stop_sal_employee').show();
			 $('#continue_sal_employee').hide();
		 }
		 
		});

	$('#th_reason_header').hide();
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
		if(id=='1990')
		{
			//$('#transfer_details_table').show();
			$('#trs_lvl').show();
			$('#trs_lvl_input').show();
			
		}
		else
		{
			//$('#transfer_details_table').hide()
			$('#trs_lvl').hide();
                       $('#trs_lvl_input').hide();
		}
   
   });
   //   ======================
   $('#trs_lvl_select').change(function(e) {
		
        var id=$(this).val();
		
		if(id=='555')
		{
			$('#transfer_details_table').show();
			$('#transfer_details_table_ps').hide();
		}
		else if(id=='556')
		{
			$('#transfer_details_table').hide();
			$('#transfer_details_table_ps').show();
		}
		else if(id=='557')
		{
			$('#transfer_details_table').hide();
			$('#transfer_details_table_ps').hide();
		}
   
   });
//   ======================
$('#stop_sal_employee').click(function(e) {

						if($('#reason_id').val()=='')
						{
							alert('Please select reason of stop salary.');
							$('#reason_id').focus();
							return false;			
						}
						else if($('#reason_id').val()!='1991' && $('#reason_date').val()=='')
						{
							alert('Please enter date of stop salary.');
							$('#reason_date').focus();
							return false;
						}
						else
						{
							
							var link1=$(this).val();
							$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/zp_stop_start_suspend_submit.php?id='+link1+'&flag=stop',$(this).closest("form").serialize(), function(data){
								//alert(data);
								if(data=='<div class="alert alert-success" style="text-align:center"><strong>Salary has been stopped successfully.</strong></div>')
								{
									$('#msg_start').html(data);
									$('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
									$('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
									$('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
									$('#myModal').modal('toggle');
									$('#stop').modal('toggle');
									
								}
								if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Stop Salary fails.</strong></div>')
								{
									$('#msg_start').html(data);
									$('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled'); 
									$('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
									$('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
									$('#myModal').modal('toggle');
									$('#stop').modal('toggle');
								}
								
								if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please select reason!!.</strong></div>')
								{
									$('#msg_start').html(data);
									$('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
									$('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
									$('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
									$('#myModal').modal('toggle');
									$('#stop').modal('toggle');
								}
								
								if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please choose reason!!.</strong></div>')
								{
									$('#msg_start').html(data);
									$('#stop_sal'+<?=$emp_id_pk?>).attr('disabled','disabled');
									$('#start_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
									$('#suspend_sal'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
									$('#myModal').modal('toggle');
									$('#stop').modal('toggle');
								}
								
								
							
							});
						}

});
	
	
	
	$( "#reason_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy'
	});
	
	



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
<input type="hidden" name="zp_id_fk" value="<?=$cryptoGraph->encode($arr[0]['zp_id_fk'],4)?>" />
<div class="emplist">
<div class="school_reason">
<div class="table-responsive"  align="center">
<table width="100%">
<tr>
<th width="18%">Employee Name</th>
<th>Designation</th>
<th>Employee Code</th>
<th>Reason</th>
<th id="trs_lvl" style="display:none;">Transferred To</th>
<th id="th_date_header">Date</th>
<th style=" width:26%">Remark</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
?>
<tr>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $item['emp_id_const'] ?></td>
<td>
<?php $reson_sal=$db->fetch_table("SELECT code, description, code_master_id_pk
								  FROM prd_dise_code_master where code in ('1992','1993','1994')
								"); ?>
<select name="reason" style="width: 100%;" id="reason_id" class="form-control">
			    <option value="">---SELECT REASON---</option>
                            <? foreach($reson_sal as $key){;?>
                            <option value="<?=$key['code']?>"><?=$key['description']?></option>
                            <?php } ?>
		</select>

</td>
<td id="trs_lvl_input" style="display:none;">
<?php $transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
								  FROM prd_dise_code_master WHERE code LIKE '55%' AND length(code)=3
								"); ?>
<select name="trs_lvl_select" style="width: 100%;" id="trs_lvl_select" class="form-control">
							<option value="">---SELECT LEVEL---</option>
							<? foreach($transfer_level as $key){;?>
                            <option value="<?=$key['code']?>"><?=$key['description']?></option>
                            <?php } ?>
                            		</select>

</td>
<td id="td_date_header"><input type="text" name="reason_date" id="reason_date" class="form-control col-md-2" /></td>
<td>
 <textarea class="form-control" id="reason_sus" name="reason_sus" draggable="false"></textarea>
<!--<form id="reason">
<div class="btn-group" role="group">
  <button type="button" class="btn btn-danger" id="stop_sal_employee" value="<?=$cryptoGraph->encode($emp_id_pk,4)?>">Stop Salary</button>
</div>-->
</td>
</tr>
 


<? }} else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>


<div style="height:10px;"></div>


<div class="btn-group" role="group" align="center">
  <button type="button" class="btn btn-danger" id="stop_sal_employee" value="<?=$cryptoGraph->encode($emp_id_pk,4)?>" >Stop Salary</button>
</div>
</div>
</div>
</div>
</form>

