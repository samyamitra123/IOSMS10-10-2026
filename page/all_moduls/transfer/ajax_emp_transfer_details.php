<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../all_function/fun_store/zp_ps_gp_function.php';
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

$logged_user=$_SESSION['user_info']['stake_abbr'];
$dis_id=$_SESSION['location']['district_id'];

$cryptoGraph=new cryptography();
$fun_store=new zp_ps_gp_class();
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
$common['title'] = "Details for Transfer Employee| PRD | Govt. of West Bengal ";

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
$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk,ps_id_fk,emp_id_const,zp_id_fk, zp_emp_type from prd_employee_master where emp_status in('1','2','9') AND emp_id_pk='".$emp_id_pk."'");

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");
	
							
$desig_data = $db->fetch_table("
								SELECT designation_id, designation_name
								FROM zpemp_emp_desig_master");	
	

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}




?>
<script>


	$('#th_reason_header').hide();
	$('#td_reason_header').hide();
	
   $('#trs_lvl_select').change(function(e) {
		
        var id=$(this).val();
		var emp_desig=$('#desig').val();
		var user=$('#logged_user').val();
		
		if(id=='555')
		{
			if(user=="EO" && emp_desig!='9009')
			{
				alert("Wrong Level Selected");
				$('#trs_lvl_select').val("");
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').hide();
			}
			else if(user=="AEO")
			{
				alert("Wrong Level Selected");
				$('#trs_lvl_select').val("");
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').hide();
				//$('#transfer_details_table_zp').hide();
				$('#dist_th').hide();
				$('#dist_td').hide();
			}
			else
			{
				$('#transfer_details_table').show();
				$('#transfer_details_table_ps').hide();
				$('#dist_th').hide();
				$('#dist_td').hide();
			}
		}
		else if(id=='556')
		{
			if(user=="BDO" && emp_desig!='1114')
			{
				alert("Wrong Level Selected");
				$('#trs_lvl_select').val("");
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').hide();
			}
			else
			{
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').show();
				$('#dist_th').hide();
				$('#dist_td').hide();
			}
		}
		else if(id=='557')
		{
			if(user=="BDO")
			{
				alert("Wrong Level Selected");
				$('#trs_lvl_select').val("");
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').hide();
				//$('#transfer_details_table_zp').hide();
				$('#dist_th').hide();
				$('#dist_td').hide();
			}
			else
			{
				$('#transfer_details_table').hide();
				$('#transfer_details_table_ps').hide();
				//$('#transfer_details_table_zp').show();
				$('#dist_th').show();
				$('#dist_td').show();
			}
		}
		else if(id=='558')
		{
			$('#transfer_details_table').hide();
			$('#transfer_details_table_ps').hide();
			$('#dist_th').hide();
			$('#dist_td').hide();
		}
   
   });
//   ======================
	$('#transfer_employee').click(function(e) {
		e.preventDefault();		
		if($('#reason_id').val()!='1991' && $('#reason_date').val()==''){
			alert('Please enter date for transfer.');
			$('#reason_date').focus();
			return false;
		}
		
		else
		{
			 
			 if($('#reason_id').val()=='1990')
			{
				//alert(44);
				if($('#trs_lvl_select').val()==''){
				alert('Please select transferred Level.');
				$('#trs_lvl_select').focus();
				return false;			
				}
						if($('#trs_lvl_select').val()=='555')
						{
							if($('#transfer_district').val()==''){
							alert('Please select the District where the employee will be transferred.');
							$('#transfer_district').focus();
							return false;			
							}
	
							else if($('#transfer_block').val()==''){
							alert('Please select the Block where the employee will be transferred.');
							$('#transfer_block').focus();
							return false;			
							}
	
							else if($('#transfer_gp').val()==''){
							alert('Please select the GP where the employee will be transferred.');
							$('#transfer_gp').focus();
							return false;			
							}
						}
						else if($('#trs_lvl_select').val()=='556')
						{
							if($('#transfer_district_ps').val()=='')
							{
								alert('Please select the District where the employee will be transferred.');
								$('#transfer_district_ps').focus();
								return false;			
							}
							
							else if($('#transfer_ps').val()=='')
							{
								alert('Please select the PS where the employee will be transferred.');
								$('#transfer_ps').focus();
								return false;			
							}
						}
						
						else if($('#trs_lvl_select').val()=='557')
						{
							if($('#transfer_district_zp').val()=='')
							{
								alert('Please select the District where the employee will be transferred.');
								$('#transfer_district_zp').focus();
								return false;			
							}
						
						}	
			}
			$('#wait').modal('show');
			var link1=$(this).val();
			$.post('<?= $config['base_url'] ?>page/all_moduls/transfer/transfer_revoke_submit.php?id='+link1+'&flag=stop',$(this).closest("form").serialize(), function(data){
				
				 if(data=='<div class="alert alert-success" style="text-align:center"><strong>Employee has been transferred successfully.</strong></div>&lpc_active')
				 {
					$('#wait').modal('hide');
					var arr=data.split('&');
					 $('#msg_start').html(arr[0]);
					  $('#trans_emp'+<?=$emp_id_pk?>).attr('disabled','disabled');
					  $('#revoke_emp'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
					  $('#lpc_gen'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
					  $('#myModal').modal('toggle');
					  $('#view_enable'+<?=$emp_id_pk?>).show();
					  $('#view_disable'+<?=$emp_id_pk?>).hide();
					
				 }
				  if(data=='<div class="alert alert-success" style="text-align:center"><strong>Employee has been transferred successfully.</strong></div>&lpc_deactive')
				 {
					 $('#wait').modal('hide');
					 var arr=data.split('&');
					 $('#msg_start').html(arr[0]);
					  $('#trans_emp'+<?=$emp_id_pk?>).attr('disabled','disabled');
					  $('#revoke_emp'+<?=$emp_id_pk?>).removeAttr('disabled','disabled');
					  $('#view_enable'+<?=$emp_id_pk?>).show();
					  $('#view_disable'+<?=$emp_id_pk?>).hide();
					  //$('#lpc_gen'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
					  $('#myModal').modal('toggle');
					
				 }
				 if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Employee transfer fails.</strong></div>')
				 {
					$('#wait').modal('hide');
					$('#msg_start').html(data);
					  $('#trans_emp'+<?=$emp_id_pk?>).attr('disabled','disabled'); 
					  $('#revoke_emp'+<?=$emp_id_pk?>).removeAttr('disabled','disabled'); 
					  $('#myModal').modal('toggle');
				 }
				
				  if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please select the District where the employee will be transferred.</strong></div>')
				 {
					  $('#wait').modal('hide');
					  $('#msg_start').html(data);
					  $('#myModal').modal('toggle');
				 }
				 
				  if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please select the Block where the employee will be transferred.</strong></div>')
				 {
					  $('#wait').modal('hide');
					  $('#msg_start').html(data);
					  $('#myModal').modal('toggle');
				 }
				 
				  if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Please select the GP where the employee will be transferred.</strong></div>')
				 {
					  $('#wait').modal('hide');
					  $('#msg_start').html(data);
					  $('#myModal').modal('toggle');
				 }
				 
			});
		}
       
    });
	
	
	
	/*$( "#reason_date" ).datepicker({
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
				yearRange: "-100:+50",
				dateFormat: 'dd-mm-yy',
				maxDate: 0
				
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy'
	});*/
	
	
	
	
		
	
	


function show_block(val){
 $.post('<?= $config['base_url'] ?>page/all_moduls/transfer/ajax_block_details.php?district='+val, function(data){
	 $("#transfer_block").html(data);	
 });
}

function show_gp(vall){
	//alert(vall);
	var old_gp=''+$('#gp_id_fk').val()+'';
 $.post('<?= $config['base_url'] ?>page/all_moduls/transfer/ajax_gp_details.php?block='+vall+'&old_gp='+old_gp, function(data){
	 //alert(data);
	 $("#transfer_gp").html(data);	
 });
}

function show_ps(val){
 $.post('<?= $config['base_url'] ?>page/all_moduls/transfer/ajax_ps_details.php?district='+val, function(data){
	 $("#transfer_ps").html(data);	
 });
}


$( "#reason_date").datepicker({
			//alert(12);
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


<input type="hidden" name="gp_id_fk" id="gp_id_fk" value="<?=$cryptoGraph->encode($arr[0]['gp_id_fk'],4)?>" />
<input type="hidden" name="logged_user" id="logged_user" value="<?= $logged_user?>" />
 <input type="hidden" name="zp_id_fk" id="zp_id_fk" value="<?=$cryptoGraph->encode($arr[0]['zp_id_fk'],4)?>" />
 <input type="hidden" name="logged_user" id="logged_user" value="<?= $logged_user?>" />
 <div class="emplist">
        <div class="school_reason">
            <div class="table-responsive"  align="center">
                <table width="100%" >
                    <tr>
                        <th width="18%">Employee Name</th>
                        <th>Designation</th>
                        <th>Employee Code</th>
                        <th id="trs_lvl">Transfer Level</th>
                        <th id="dist_th" style="display:none;">New District</th>
                       <!-- <th >N</th>-->
                        <th id="th_date_header">Date</th>
                        <th style=" width:20%">Remark</th>
                    </tr>
                    <? $cnt=1; if(count($arr))
					{
						foreach($arr as $item)
						{
							?>
							<input type="hidden" name="desig" id="desig" value="<?= $item['emp_desig']?>" />
							<tr>
                                <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                <td>
									<?php if($logged_user=='AEO')
                                    {
                                    	echo $fun_store->fun_desig($item['emp_desig'],$desig_data);
                                    }
                                    else
                                    {
                                    	echo fun_common($item['emp_desig'],$code_data); 
                                    }?>
                                </td>
                                <td>
									<?= $item['emp_id_const'] ?>
                                    <input type="hidden" name="reason" id="reason_id" value="1990" />
                                </td>
                                <td id="trs_lvl_input" style="width: 20%;">
									<?php 
										$transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
																		FROM prd_dise_code_master WHERE code IN ('555','556','557','558') AND length(code)=3
																		");
																		//var_dump($transfer_level); die;
                                     ?>
                                    
                                    <select name="trs_lvl_select" style="width:100%;" id="trs_lvl_select" class="form-control">
                                        <option value="">---SELECT LEVEL---</option>
                                        <? //foreach($transfer_level as $key)
                                       // { var_dump($key);
											if(($logged_user=='EO' && $key['code']!='558') || ($logged_user=='AEO' && $key['code']!='555' && $item['zp_emp_type']=='366' && $key['code']!='556')  || ($logged_user=='AEO' && $key['code']!='555' && $item['zp_emp_type']=='367' && $key['code']!='558') || ($logged_user=='BDO' && $key['code']!='557' && $key['code']!='558'))
											{
											?>
												<!--<option value="<?=$key['code']?>"><?=$key['description']?></option>-->
												<option value="555">GP</option>
												<option value="556">PS</option>
												<option value="557">ZP</option>
												<option value="558">OTHER DEPARTMENT</option>
                                        <?php 
											}
										//} ?>
                                    </select>
                                
                                </td>
                                
                                
                                <td id="dist_td" style="display:none; width: 30%;">
									<?
                                    $db = new database();
                                    $arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district");
                                    ?>
                                    <select class="form-control" name="transfer_district_zp" id="transfer_district_zp" >
                                        <option value="">-Please Select-</option>
                                        <? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
                                        <option value="<?= $key['district_id_pk']; ?>" ><?= $key['district_name']; ?></option>
                                        <? } ?>
                                    </select>
                                </td>
                                 <!--<td style="width: 20%;">
                                
                        	<input type="text" class="form-control" id="tch_dob" name="tch_dob"  placeholder="DD-MM-YYYY"/>
                            </td>-->
                        
                                
                                
                              <td id="td_date_header"><input type="date" name="reason_date" id="reason_date" class="form-control col-md-2" 
                               style="cursor:default; background-color:#fff; width: auto;" /></td>
                                 <!--<td ><input type="text" name="reason_date" id="reason_date" class="form-control col-md-2" style="cursor:default; background-color:#fff; width: auto;"/></td>-->
                                <td>
                                    <textarea class="form-control" id="reason_sus" name="reason_sus" draggable="false"></textarea>
                                    <!--<form id="reason">
                                    <div class="btn-group" role="group">
                                    </div>-->
                                </td>
							</tr>
							<? 
						}
					} 
					else 
					{ ?>
                        <tr>
                        	<td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
                        </tr>
                    <? } ?>
                </table>

<div style="height:10px;"></div>

<table width="100%" id="transfer_details_table_ps" style="display:none;">
<tr>
   
<th style=" width:33%">New District</th>
<th style=" width:33%">New PS</th>
</tr>

<tr>

<td>
	<?
		$db = new database();
		$arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district");
    ?>
    <select class="form-control" name="transfer_district_ps" id="transfer_district_ps" onchange="show_ps(this.value)">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
    	<option value="<?= $key['district_id_pk']; ?>" ><?= $key['district_name']; ?></option>
    <? } ?>
    </select>
</td>
<td>
    <select class="form-control" id="transfer_ps" name="transfer_ps" >
    <option value="">-Please Select-</option>
    </select>
</td>

</tr>
 
</table>

<div style="height:10px;"></div>

<table width="100%" id="transfer_details_table" style="display:none;">
<tr>
   
<th style=" width:33%">New District</th>
<th style=" width:33%">New Block</th>
<th style=" width:33%">New GP</th>
</tr>

<tr>

<td>
	<?
		$db = new database();
		$arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district");
    ?>
    <select class="form-control" name="transfer_district" id="transfer_district" onchange="show_block(this.value)">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
    	<option value="<?= $key['district_id_pk']; ?>" ><?= $key['district_name']; ?></option>
    <? } ?>
    </select>
</td>
<td>
    <select class="form-control" id="transfer_block" name="transfer_block" onchange="show_gp(this.value)">
    <option value="">-Please Select-</option>
    </select>
</td>
<td>
    <select class="form-control" id="transfer_gp" name="transfer_gp">
    <option value="">-Please Select-</option>
    </select>
</td>

</tr>
 
</table>

<div style="height:10px;"></div>


<div class="btn-group" role="group" align="center">
  <button class="btn btn-danger" id="transfer_employee" value="<?=$cryptoGraph->encode($emp_id_pk,4)?>" >Transfer Employee</button>
</div>
</div>
</div>
</div>
</form>


<div class="modal fade bs-example-modal-lg" id="wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-body"> 
		<img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />
      </div>
  </div>
</div>
