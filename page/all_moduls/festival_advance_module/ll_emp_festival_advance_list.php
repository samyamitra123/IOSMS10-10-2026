<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';


if($_SERVER['HTTP_REFERER']==''){
header("Location:../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

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
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


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


$crypto = new cryptography();

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//$str=$_SESSION['location']['gpcode'];
//$state10=substr($str,0,4);
//-------------------------------------------------------------------

$db = new database();
$tch = array();
//$municipality_id_fk = substr($_SESSION['location']['gpcode'],0,7);
//$zp_id_fk = $_SESSION['location']['district_id'];

if($logged_user=='DA')
{
	
	$Query = " SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND fad_emp.festival_advance_status in('1','2','3') AND fad.status='1'
									  ";
									  
	$fad_details=$db->fetch_table($Query);
	//print_r($fad_details); exit;
	
}
else if($logged_user=='GP')
{
	$fad_details=$db->fetch_table(" SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.gp_id_fk='".$_SESSION['location']['gp_id']."' AND fad_emp.festival_advance_status in('1','2','3') AND fad.status='1'
									  ");
	
}
else if($logged_user=='zpdaa')
{
	$fad_details=$db->fetch_table(" SELECT
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									fad_emp.emp_id_const,
									fad_emp.festival_advance_total_amount,
									fad_emp.festival_advance_status,
									CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
									fad.festival_advance_instalment_no,
									fad.festival_advance_instalment_amount,
									fad.festival_advance_instalment_last_amount
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details fad_emp
									ON emp.emp_id_pk=fad_emp.emp_id_fk
									INNER JOIN prd_festival_advance_entry_sal fad
									ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
									WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND fad_emp.festival_advance_status in('1','2','3') AND fad.status='1'
									  ");
	
}


for($i=0;$i<count($fad_details);$i++)
{
	if($fad_details[$i]['festival_advance_status']=='3')
	{
		$all_finalized='TRUE';
	}
	else
	{
		$all_finalized='FALSE';
		break;
	}
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "P&RD | Govt. of West Bengal ";

//Self variable


//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//$crypto = new cryptography();


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
	
	#wait{
	display: none;
	}
	.headRow > div{
	text-align:center !important;
	}
	#divcol > div{
	text-align:center !important;
	}
</style>



<script>
	$(document).ready(function(){
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
	//$( ".modal fade in" ).css( "height", "1000px" );
	});

	function fad_entry()
	{ 
		$('#fad_entry_modal').modal('show');
		$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/ll_ajax_fad_edit.php', function(data){
			$("#mbody").html(data);
			});					
	}
	
	function fun_individual_edit(k)
	{
		var arr=k.split('&');
		var enc_emp_id=arr[0];
			$('#fad_entry_modal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/ll_ajax_fad_individual_edit.php?enc_emp_id='+enc_emp_id, function(data){
				
				$("#mbody").html(data);
				});
	}
	
	function save_fad(k)
	{
		var arr=k.split('&');
		var emp_encrpt_id=arr[1];
		var emp_id=arr[2];
		
		$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/ll_ajax_fad_individual_employee_save.php?emp_id='+emp_encrpt_id, function(data){
			if(data=='saved')
			{
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong>Employee Festival Advance Details Has Been Saved Successfully.</strong></div>');
				$('#save_div_id'+emp_id).hide();
				$('#saved_div_id'+emp_id).show();
			}
			else if(data=='all_saved')
			{
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong>Employee Festival Advance Details Has Been Saved Successfully.</strong></div>');
				$('#save_div_id'+emp_id).hide();
				$('#saved_div_id'+emp_id).show();
				$('#finalize_show').show();
			}
			else if(data=='not_saved')
			{
				$('#sess_msg').html('<div class="alert alert-danger" style="text-align:center"><strong>Employee Festival Advance Details Has Not Been Saved. Please Try Again...</strong></div>');
			}
				
		});
	}
	
	function del_fad(k)
	{
		var arr=k.split('&');
		var enc_emp_id=arr[1];
		
		$('#emp_del_id').val(enc_emp_id);
		$('#delete_modal').modal('show');
	}
	
	function confirm_finalize()
	{
		$('#finz_modal').modal('show');
	}
	
	function load_page()
	{
		location.reload();
	}
</script>


<div class="content">

	<? require '../../../page/common_back_btns.php'; ?>
    
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'].", ";
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'];
        }elseif(isset($_SESSION['location']['ps_name'])) {
        echo $_SESSION['location']['ps_name'].", ";
        }elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        <?php if($_SESSION['user_info']['stake_abbr']=='GP'){ ?>
        <? echo $_SESSION['location']['block_name'].", " .$_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }else{
        
        echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }
        ?></h3>
    </div>
    
    <center>
    	<br/>
        <h1 class="heading">EMPLOYEE FESTIVAL ADVANCE MANAGEMENT</h1>
         <div class="border"></div>
        <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
        	<?  
			if(isset($_SESSION['msg']))
			{
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
			?>
        </div>
        <br />
    </center>
    
    <?php
    if(isset($_SESSION['msg']))
    {
        echo $_SESSION['msg'];
        unset($_SESSION['school_msg']);
    } 
    if(!empty($_GET['msg']))
    {
        echo $cryptoGraph->decode($_GET['msg'],4);
        echo "<br/>";
        echo "<br/>";
    }
	if(!empty($msg)){
		echo $msg;
	}
    ?>
    
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12" style="width:98%">
                    <div class="emplist">
                        <div class="school">
                        	<?php if(count($fad_details)==0 || $all_finalized=='FALSE')
							{ ?>
                                <div class="entry_bonus" align="right" style="padding-bottom:5px;">
                                    <a class="btn btn-success" onClick="fad_entry();" style="font-weight:400; font-size:14px;">Festival Advance Entry </a>
                                </div>
                        	<?php } ?>
                        <table class="table-responsive" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">SL NO.</th>
                                <th>Employee Name</th>
                                <th>Employee Id</th>
                                <th style="width: 12%;">Festival Advance Amount</th>
                                <th style="width: 10%;">Total Instalment Number</th>
                                <th>Instalment Amount</th>
                                <th colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
                            
                            if(count($fad_details))
                            { 
                                $count = 1;$total_saved=0; 
                                foreach ($fad_details as $key) 
								{?> 
										<tr>
										<td id="show"><?php echo $count; ?></td>
										<td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
										<td><?php echo $key['emp_id_const']; ?></td>
                                        <td><?php echo $key['festival_advance_total_amount']; ?></td>
                                        <td><?php echo $key['festival_advance_instalment_no']; ?></td>
                                        <td><?php echo $key['festival_advance_instalment_amount'];if($key['festival_advance_instalment_amount']!=$key['festival_advance_instalment_last_amount']){echo ' (Last Month Payable : '.$key['festival_advance_instalment_last_amount'].')';} ?></td>
                                        
                                        <td class="edit">
											<?php if($key['festival_advance_status']!='3')
                                            { ?>
                                                <a onClick="fun_individual_edit(this.id);" id="<? echo $crypto->encode($key['emp_id_pk'],4).'&'.$key['emp_id_pk'] ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit">
                                                </a>
                                            <?php }
                                            else
                                            { ?>
                                            	<img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" style="opacity:0.5;">
                                            <?php }?>
                                        </td>
                                        
                                        <td class="save" id="save">
											<?php if($key['festival_advance_status']=='1')
                                            { ?>
                                                <div id="save_div_id<?php echo $key['emp_id_pk'];?>" style="display:block;">
                                                <a id="save_fad&<? echo $crypto->encode($key['emp_id_pk'],4).'&'.$key['emp_id_pk'] ?>" onClick="save_fad(this.id);"><img id="img_id<?=$count?>"  width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save">
                                                </a>
                                                </div>
                                                <div id="saved_div_id<?php echo $key['emp_id_pk'];?>" style="display:none;">
                                                <img id="img_id<?=$count?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved">
                                                </div>
                                            <?php }
                                            else if($key['festival_advance_status']=='2' || $key['festival_advance_status']=='3')
                                            { ?>
                                            	<img id="img_id<?=$count?>"  width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved">
                                            <?php } 
                                            else
                                            { ?>
                                            	<img id="img_id<?=$count?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" style="opacity:0.5;">
                                            <?php }?>
                                        </td>
                                        <td class="delete">
											<?php if($key['festival_advance_status']!='3')
                                            { ?>
                                                <a onClick="del_fad(this.id);" id="delete_fad&<?php echo $crypto->encode($key['emp_id_pk'],4).'&'.$key['emp_id_pk'] ?> "><i class="fa fa-trash fa-2x" aria-hidden="true" style="color:red"></i>
                                                </a>
                                            <?php }
                                            else
                                            { ?>
                                            	<i class="fa fa-trash fa-2x" aria-hidden="true" style="color:red; opacity:0.5;"></i>
                                            <?php }?>
                                        </td>
									</tr> 
									<?php
									$total_saved=$total_saved+$key['saved'];  
								}
                            } 
                            else 
							{?> 
                            	<tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> 
							<?php 
							}?>
                            </tbody>
                        </table>
                        <div class="row mb-3" id="finalize_show" style="display:none;">
                        <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
                            <a onClick="confirm_finalize();" class="btn btn-success btn-sm">Festival Advance Finalize</a>
                        </div>
                    </div>
                    
                    <?php if(count($fad_details)>0 && count($fad_details)==$total_saved) { ?>
                        <div class="row mb-3" id="sal_save" style="margin-left: 41%;">
                            <div class="col-sm-offset-5 col-sm-7" style="margin-top:2%">
                                <a onClick="confirm_finalize();" class="btn btn-success btn-sm">Festival Advance Finalize</a>
                            </div>
                        </div>
                    <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>


<? require '../../../page/layout/footer.php'; ?>


<!-----------------------------------------------------------------MODAL Start---------------------------------->

<style>
	ul.sub-menu>li>a:hover {
	/* color: #ffffff!important; */
	color: #74ad1c !important;
	}
	
	#save
	{
	cursor: pointer;
	}
	.finalize{
	text-align: center;
	margin-left: 370px;
	}
	.finalize ul {
	list-style-type:none;
	margin:0;
	padding:0;
	overflow:hidden;
	}
	.finalize li {
	float:left;
	}
	.finalize a:link, .finalize a:visited {
	display:block;
	width:133px;
	font-weight:bold;
	color:#FFFFFF;
	text-align:center;
	height:32px;
	text-decoration:none;
	text-transform:uppercase;
	background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
	}
	.finalize a:hover, .finalize a:active {
	
	background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
	background-position: 0px 32px;
	}
</style>


<!---------------------------------------------------------FESTIVAL ADVANCE ENTRY MODAL-------------------------------------------------------->

<div class="modal fade bs-example-modal-lg" id="fad_entry_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width:800px;">
      <div class="modal-header">
       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="myModalLabel">Employee Festival Advance Details of <?php echo date('Y');?></h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" onClick="load_page();">Close</button>      
      </div>
    </div>
  </div>
</div>


<!-----------------------------------------------------------------FESTIVAL ADVANCE FINALIZE MODAL---------------------------------------------------->


<form action="ll_fad_finalize.php" method="post" name="fad_finz" id="fad_finz" >
<div class="modal fade bs-example-modal-sm" id="finz_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
            	<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Finalize Festival Advance Details?</strong></p>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                <button type="submit" class="btn btn-success" >YES</button>
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<!-----------------------------------------------------------------FESTIVAL ADVANCE DELETE MODAL---------------------------------------------------->


<form action="ll_fad_delete_individual_employee.php" method="post" name="fad_del" id="fad_del" >
    <div class="modal fade bs-example-modal-sm" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i>Do You Want To Delete This Employee's Festival Advance Details?</strong></p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                    <input type="hidden" name="emp_del_id" id="emp_del_id" />
                    <button type="submit" class="btn btn-success" >YES</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>