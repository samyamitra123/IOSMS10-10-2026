<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../all_function/fun_store/zp_ps_gp_function.php';
if (!empty($_SESSION['HTTP_REFERER'])) {
    // Use the referer from session directly
     $_SERVER['HTTP_REFERER'] = $_SESSION['HTTP_REFERER'];
  
} 
if($_SERVER['HTTP_REFERER']=='')
{
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

$cryptoGraph=new cryptography();
 $fun_store=new zp_ps_gp_class();
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
$common['title'] = "Start or Stop Or Suspend Salary| PRD | Govt. of West Bengal ";

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
<?
$db=new database();



if($logged_user=='BDO')
{
	//$abc = decode($_REQUEST['gp_id_fk'],4);
$gp_id_fk=$cryptoGraph->decode($_REQUEST['gp_id_fk'],4);
//echo $gp_id_fk;exit;
/*echo "SELECT 
							distinct(emp.emp_id_pk),
							emp.emp_first_name,
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_desig,
							emp.emp_next_increment_date,
							emp.emp_status,
							emp.emp_system_code,
							emp.gp_id_fk,
							emp.emp_id_const,
							emp.zp_emp_type,
							tran.transfer_emp_status,
							tran.lpc_status,
							tran.transfer_date,
							tran.transfer_level
						FROM 
							prd_employee_master emp
						LEFT JOIN
							(SELECT emp_id_fk,gp_id_fk,transfer_emp_status,lpc_status,transfer_date,transfer_level FROM prd_employee_transfer WHERE transfer_emp_status=0) as tran
						ON
							emp.emp_id_pk=tran.emp_id_fk
						WHERE 
							((emp.emp_status=1 )  OR (emp.emp_status=2 AND tran.lpc_status=1 )) AND emp.gp_id_fk='".$gp_id_fk."' ORDER BY emp_first_name";exit;*/
	$arr=$db->fetch_table("SELECT 
							distinct(emp.emp_id_pk),
							emp.emp_first_name,
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_desig,
							emp.emp_next_increment_date,
							emp.emp_status,
							emp.emp_system_code,
							emp.gp_id_fk,
							emp.emp_id_const,
							emp.zp_emp_type,
							tran.transfer_emp_status,
							tran.lpc_status,
							tran.transfer_date,
							tran.transfer_level
						FROM 
							prd_employee_master emp
						LEFT JOIN
							(SELECT emp_id_fk,gp_id_fk,transfer_emp_status,lpc_status,transfer_date,transfer_level FROM prd_employee_transfer WHERE transfer_emp_status=0) as tran
						ON
							emp.emp_id_pk=tran.emp_id_fk
						WHERE 
							((emp.emp_status=1 )  OR (emp.emp_status=2 AND tran.lpc_status=1 )) AND emp.gp_id_fk='".$gp_id_fk."' ORDER BY emp_first_name");
}
else if($logged_user=='EO')
{
	
	$arr=$db->fetch_table("SELECT 
							distinct(emp.emp_id_pk),
							emp.emp_first_name,
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_desig,
							emp.emp_next_increment_date,
							emp.emp_status,
							emp.emp_system_code,
							emp.gp_id_fk,
							emp.emp_id_const,
							emp.zp_emp_type,
							tran.transfer_emp_status,
							tran.lpc_status,
							tran.transfer_date,
							tran.transfer_level
						FROM 
							prd_employee_master emp
						LEFT JOIN
							(SELECT emp_id_fk,gp_id_fk,transfer_emp_status,lpc_status,transfer_date,transfer_level FROM prd_employee_transfer WHERE transfer_emp_status=0) as tran
						ON
							emp.emp_id_pk=tran.emp_id_fk
						WHERE 
							((emp.emp_status=1 )  OR (emp.emp_status=2 AND tran.lpc_status=1 )) AND emp.ps_id_fk='".$_SESSION['location']['ps_id']."' ORDER BY emp_first_name");
}
else if($logged_user=='AEO')
{
	$Query = "SELECT 
	distinct(emp.emp_id_pk),
	emp.emp_first_name,
	emp.emp_second_name,
	emp.emp_last_name,
	emp.emp_desig,
	emp.emp_next_increment_date,
	emp.emp_status,
	emp.emp_system_code,
	emp.zp_id_fk,
	emp.emp_id_const,
	emp.zp_emp_type,
	tran.transfer_emp_status,
	tran.lpc_status,
	tran.transfer_date,
	tran.transfer_level
	FROM 
	prd_employee_master emp
	LEFT JOIN
	(SELECT emp_id_fk,zp_id_fk,transfer_emp_status,lpc_status,transfer_date,transfer_level FROM prd_employee_transfer WHERE transfer_emp_status=0) as tran
	ON
	emp.emp_id_pk=tran.emp_id_fk
	WHERE 
	((emp.emp_status=1 )  OR (emp.emp_status=2 AND tran.lpc_status=1 )) AND emp.zp_id_fk='".$_SESSION['location']['district_id']."' ORDER BY emp_first_name";
	//print_r($Query); exit;
	$arr=$db->fetch_table($Query);
}

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

$current_monthyear=date('Ym');
$int_current_monthyear=intval($current_monthyear);
  
?>
<script>
$(document).ready(function(e) {

	$('.btn-group').show();
    $('.emp_revoke').click(function(e) {
		var link1=$(this).val();
		var arr=link1.split('&');
		$('#confirm_revoke').modal('show');
		
		$('#revoke_emp_id').val(arr[0]);
		$('#revoke_gp_id').val(arr[1]);
		$('#decoded_emp_id').val(arr[2]);
		
    });
	
	
	
	
	
	$('.emp_transfer').click(function(e) {
		
		var emp_id=$(this).val();
		var transfer_id=$(this).attr('id');
		var transfer_id_length=transfer_id.length;
		var id=transfer_id.substr(9,transfer_id_length);
		var increment_flag=$('#increment_flag'+id).val();
		if(increment_flag=='0')
		{
			alert("Please Update Employee's Next Increment Date");
		}
		else
		{
			$.post('<?= $config['base_url'] ?>page/all_moduls/transfer/ajax_emp_transfer_details.php?id='+emp_id, function(data){
				//alert(data);
			  $('#myModal').modal('toggle');
			  $('#stop').modal('toggle');
			  $('#mbody_transfer').html(data);
			});
		}

		
    });
	
	$('.gen_lpc').click(function(e) {
		
		var link=$(this).val();
		var arr=link.split('&');
		var encrypt_emp=arr[0];
		$('#enc_emp_id').val(encrypt_emp);
		
		var encrypt_gp=arr[1];
		$('#enc_gp_id').val(encrypt_gp);
		
		var non_encrypt_emp=arr[2];
		$('#non_enc_emp_id').val(non_encrypt_emp);

		
		$('#lpc_modal').modal('show');
	
	 });
	

});


function confirm_revoke_emp()
{

	var rev_emp_id=$('#revoke_emp_id').val();
	var rev_gp_id=$('#revoke_gp_id').val();
	var decode_emp=$('#decoded_emp_id').val();
	
	
	$.post('<?= $config['base_url'] ?>page/all_moduls/transfer/transfer_revoke_submit.php?id='+rev_emp_id+'&flag=start'+'&gp_id_fk='+rev_gp_id, function(data){
	if(data=='<div class="alert alert-success" style="text-align:center"><strong>Employee has been revoked successfully!!.</strong></div>')
	{
		$('#msg_start').html(data);
		$('#confirm_revoke').modal('hide');
		$('#suspend_sal'+decode_emp).removeAttr('disabled','disabled');
		$('#lpc_gen'+decode_emp).attr('disabled','disabled');
		$('#revoke_emp'+decode_emp).attr('disabled','disabled');
		$('#trans_emp'+decode_emp).removeAttr('disabled','disabled');
		$('#view_enable'+decode_emp).hide();
		$('#view_disable'+decode_emp).show();
		
	 
	}
	if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Employee revoke error!!.</strong></div>')
	{
		$('#msg_start').html(data);
		$('#confirm_revoke').modal('hide');
		$('#revoke_emp'+decode_emp).attr('disabled','disabled');
		$('#trans_emp'+decode_emp).removeAttr('disabled','disabled');
	}
	});
}


function modal_off()
{
	$('#lpc_modal').modal('hide');
	
	var emp_id=$('#non_enc_emp_id').val();
	$('#revoke_emp'+emp_id).attr('disabled','disabled');
	$('#trans_emp'+emp_id).attr('disabled','disabled');
	
}
function view_details(k)
{
	var length=k.length;
	var emp_id=k.substr(16,length);
	$('#transfer_details_modal').modal('show');
	$.post('<?= $config['base_url'] ?>page/all_moduls/transfer/ajax_transfer_view.php?id='+emp_id, function(data){
		$('#trans_details').html(data);
	});
}


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
<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['ps_name'])) {
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
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%">
<h1 class="heading">EMPLOYEE LIST</h1>
<div class="border"></div>
</br>
</br>
<div id="msg_start">
<?php 
if(isset($_SESSION['msg']))
{
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
}
?>
</div>
<div class="msg"></div>

<div class="emplist">
<div class="school">
<div class="table-responsive">

<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Employee Code</th>
<th style=" width:30%" colspan="2">Action</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
if(strtotime(date('Y-m-d'))<strtotime($item['emp_next_increment_date']))
{
	$increment_flag=1;
}
else
{
	$increment_flag=0;
}

?>
<input type="hidden" name="increment_flag" id="increment_flag<?=$item['emp_id_pk']?>" value="<?= $increment_flag ?>" />
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td>
<?php if($logged_user=='AEO')
{
	echo $fun_store->fun_desig($item['emp_desig'],$desig_data);
}
else
{
	echo fun_common($item['emp_desig'],$code_data); 
}
?>
</td>
<td><?= $item['emp_id_const'] ?></td>
<td>
    <div class="btn-group" role="group" style="width:100%;">
    <?php 
    if($item['emp_status']=='1' and $item['transfer_emp_status']=='0' ) 
    { ?>
    <button type="button" class="btn btn-success emp_revoke" id="revoke_emp<?=$item['emp_id_pk']?>" <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php }  else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php } else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?>>Revoke</button>
    <?php 
    }
    else if($item['emp_status']=='2' && $item['lpc_status']=='1') 
    { ?>
    <button type="button" class="btn btn-success emp_revoke" id="revoke_emp<?=$item['emp_id_pk']?>" <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php } else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }  else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?> disabled="disabled">Revoke</button>
    <?php 
    } 
    else 
    { ?>
    <button type="button" class="btn btn-success emp_revoke" id="revoke_emp<?=$item['emp_id_pk']?>" <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php }  else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php 	}else if($logged_user=='AEO')  {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?> disabled="disabled">Revoke</button>
    <?php 
    } 
    if ($item['transfer_emp_status']=='0' || $item['lpc_status']=='1') 
    { ?>
    <button type="button" class="btn btn-danger emp_transfer" id="trans_emp<?=$item['emp_id_pk']?>" data-bs-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" disabled="disabled" style="display: block;">Transfer</button>
    <?php 
    } 
    else 
    {?>
    <button type="button" class="btn btn-danger emp_transfer" id="trans_emp<?=$item['emp_id_pk']?>" data-bs-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" style="display: block;" >Transfer</button>
    <?php 
    }  
	
	//var_dump($item); die;
if(!empty($item['transfer_date'])){
	$transfer_date_exp=explode('-',$item['transfer_date']);
	$transfer_monthyear= $transfer_date_exp['0'].$transfer_date_exp['1'];
	$transfer_date=$transfer_date_exp['2'].'-'.$transfer_date_exp['1'].'-'.$transfer_date_exp['0'];
	$current_date=date('d-m-Y');
}
else{
	$transfer_date_exp="";
	$transfer_monthyear= "";
	$transfer_date= "";
	$current_date= "";
}
	
	
	$salary_check=$db->fetch_table(" SELECT status_flag FROM prd_employee_salary_save WHERE emp_id_fk='".$item['emp_id_pk']."'  AND salary_monthyear='".date('Ym')."' AND is_saved='1' AND delete_status='1' ");
	//echo "<pre>";print_r($salary_check);
	//echo $item['zp_emp_type'];exit;
	
	if($item['zp_emp_type']=='366' || $item['zp_emp_type']=='367' || $item['transfer_level']!='558')
	{
		if(($salary_check[0]['status_flag']!=3 && strtotime($current_date)>=strtotime($transfer_date)) && $item['transfer_emp_status']=='0') 
		{?>
		<button type="button" class="btn btn-info gen_lpc" id="lpc_gen<?=$item['emp_id_pk']?>"  <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php } else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?> >LPC Generation</button>
		<?php 
		} 
		else
		{ ?>
		<button type="button" class="btn btn-info gen_lpc" id="lpc_gen<?=$item['emp_id_pk']?>"   <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php } else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?>>LPC Generation</button>
		<?php 
		}
	}
	
	else if($item['zp_emp_type']!='366' && $item['zp_emp_type']!='367' && $item['transfer_level']!='558')
	{
		if(($salary_check[0]['status_flag']!=3 && strtotime($current_date)>=strtotime($transfer_date)) && $item['transfer_emp_status']=='0') 
		{?>
		<button type="button" class="btn btn-info gen_lpc" id="lpc_gen<?=$item['emp_id_pk']?>"  <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php } else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?> >LPC Generation</button>
		<?php 
		} 
		else
		{ ?>
		<button type="button" class="btn btn-info gen_lpc" id="lpc_gen<?=$item['emp_id_pk']?>"   <?php if($logged_user=='BDO') { ?> value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['gp_id_fk'],4).'&'.$item['emp_id_pk']?>" <?php } else if($logged_user=='EO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }else if($logged_user=='AEO') {?>value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>"<?php }?> disabled>LPC Generation</button>
		<?php 
		}
	}
	else if($item['transfer_level']=='558')
	{ ?>
	<button type="button" class="btn btn-default" style="width:116px; padding-left:6px;" disabled>LPC Not Required</button>
	<?php
	}
	?>
	</div>
    </td>
<?php if ($item['transfer_emp_status']=='0' || $item['lpc_status']=='1') { ?>
<td style="display:block;" id="view_enable<?=$item['emp_id_pk']?>">
	<a onClick="view_details(this.id);" id="transfer_details<?=$item['emp_id_pk']?>"> <img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="Transfer Details" style="padding-top:6px;" /></a>
    </td>
<td style="display:none;" id="view_disable<?=$item['emp_id_pk']?>">
	<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view"  style="padding-top:6px; opacity:0.5;" />
</td>

<?php }
else
{ ?>
<td style="display:block;" id="view_disable<?=$item['emp_id_pk']?>">
	<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view"  style="padding-top:6px; opacity:0.5;" />
</td>
<td style="display:none;" id="view_enable<?=$item['emp_id_pk']?>" >
	<a onClick="view_details(this.id);" id="transfer_details<?=$item['emp_id_pk']?>"> <img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="Transfer Details" style="padding-top:6px;" /></a>

</td>
<?php } ?>
  </div>
 </td>  
</tr>
<? $cnt+=1; }} else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>
<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>

<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="min-height:1000px;"data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" style="height:">
    <div class="modal-content" style="width: 125%; margin-left: -13%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <div id="mbody_transfer"> 
      </div>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-target="#close" data-bs-toggle="modal">Close</button>   
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" id="confirm_revoke" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="width: 130%; margin-left: -17%;">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Revoke This Employee?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <input type="hidden" name="revoke_emp_id" id="revoke_emp_id">
      <input type="hidden" name="revoke_gp_id" id="revoke_gp_id">
      <input type="hidden" name="decoded_emp_id" id="decoded_emp_id">
      <button name="rev_emp" id="rev_emp" onClick="confirm_revoke_emp();" class="btn btn-success">YES </button>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button> 
        </form>     
      </div>
      </div>
    </div>
  </div>
</div>


<form action="pdf_lpc.php" method="post" onSubmit="modal_off();">

<div class="modal fade bs-example-modal-sm" id="lpc_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="width: 137%; margin-left: -20%;">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
     
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> LPC of previous month is being generated. <br/> Salary of Current month will be stopped after LPC generation from this <?php if($logged_user=='BDO') { ?>GP<?php }else if($logged_user=='EO'){ ?>PS<?php }else if ($logged_user=='AEO'){ ?>ZP<?php }?>. <br/> Do you want to generate LPC?</strong></p>
     
      
      
      <input type="hidden" name="enc_emp_id" id="enc_emp_id" />
      <input type="hidden" name="enc_gp_id" id="enc_gp_id" />
      <input type="hidden" name="non_enc_emp_id" id="non_enc_emp_id" />
      <input type="hidden" name="cur_date" id="cur_date" value="<?= date('d-m-Y')?>" />
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success"/>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
</div>
</form>


<div class="modal fade bs-example-modal-lg" id="transfer_details_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" >
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body" id="trans_details"> 
      
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">CLOSE</button>      
      </div>
      </div>
    </div>
  </div>
</div>
</div>






<!-----------------------------------------------------------------MODAL END---------------------------------------------------->