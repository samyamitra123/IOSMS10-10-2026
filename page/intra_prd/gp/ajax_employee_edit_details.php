<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/cryptography.class.php';
include_once '../../../includes/library/database.class.php';


//--------------PHP MAIL-----------------------

require '../../../includes/third-party/PHPMailer/class.phpmailer.php';
require '../../../includes/third-party/PHPMailer/PHPMailerAutoload.php';
require_once '../../all_function/mail/mail_fun.php';


//---------------------------------------------

$cryptoGraph = new cryptography();
if(isset($_SERVER['HTTP_REFERER'])){
$conf=isset($_REQUEST['confirm'])?$_REQUEST['confirm']:' ';
$confirm=$cryptoGraph->decode($conf, 4);
}

//echo $obj_crpto->encode('qqq','20');
//require '../../../includes/library/database.class.php';
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location: '. $config['base_url'] . "page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location: '. $config['base_url'] . "page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//require '../../page_visite.php';
//-----------------------------QUERY----------------------------------------------------------------------------------





function get_emp(){ 
	require_once '../../../includes/library/database.class.php';
	$db  = new database();	
	
	$data = $db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_form_status,emp_id_pk,emp_desig from prd_employee_master where emp_form_status in('1','2','3','4','5') AND emp_status='10' AND gp_id_fk='".$_SESSION['location']['gp_id']."' order by emp_first_name");
	
	return $data; 
}

function send_to_ddo($pk){
	
	require_once '../../../includes/library/database.class.php';
	$db  = new database();
	$obj_crpto = new cryptography();
	$data = $db->update("
							UPDATE prd_employee_master
							SET emp_status = 6
							WHERE emp_id_pk = '".$pk."'
								AND gp_id_fk = '".$_SESSION['location']['gp_id']."'
								AND emp_status in (10,7,11)
								AND emp_form_status = '5'
								AND gp_id_fk='".$_SESSION['location']['gp_id']."'
	
	");

/*	if($_SESSION['location']['gp_id']=='3355');
	{
		
	$gp_info = $db->fetch_table("select  emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									gp.email_id,
									gp.mobile_no,
									gp.gram_pradhan_name 
									from prd_gp_profile gp 
INNER JOIN prd_employee_master emp on gp.gp_id_fk=emp.gp_id_fk
where gp.gp_id_fk='".$_SESSION['location']['gp_id']."' and emp.emp_id_pk = '".$pk."' and emp.emp_status = 6");


 $mail_body  = 'Dear '.$gp_info['0']['gram_pradhan_name'].' '.'<p>The '.$gp_info['0']['emp_first_name'].' '.$gp_info['0']['emp_second_name'].' '.$gp_info['0']['emp_last_name'].' profile has been successfully send to BDO for approval </p> <hr><b>Disclaimer:</b>This is a system generated mail.Please do not reply.';

$email=$gp_info['0']['email_id'];
$emp_sub='Employee Profile status';
	mail_function($mail_body,$email,$emp_sub);
	
	}*/
}
//------------------------------------------------------------------------------------------------------------------------------
?>
    <script>
    	$(document).ready(function(){
			$('#msg_session').hide();
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  
		  $("#dialog").dialog({
				autoOpen : false,
				modal: true,
				width:900,
				opacity: 1,
				height:580,
				//resize: "auto",

				show : {
					effect : "fade",
					duration : 500
				},
				hide : {
					effect : "fade",
					duration : 500
				},
			});

			$(".sendtoddo a").click(function() {
				//alert('hello');
				var link = $(this).attr('href');
				//alert(link);
				$(document).ajaxStart(function(){
				    $("#wait").css("display","block");
				  });
				$(document).ajaxComplete(function(){
				    $("#wait").css("display","none");
				    
				});
			    $(".emplist").load('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_employee_edit_details.php?id='+link, function(responseTxt,statusTxt,xhr){
			      if(statusTxt=="error"){
			        //alert("Error: "+xhr.status+": "+xhr.statusText);
			        $("#error_msg").css("display","block");
			      } else {
			      	$("#error_msg").css("display","none");
			      }
			  	});
				
				//$(".dial").html($(this).attr('href'));
				//$("#dialog").dialog("open");
				
				return false;
			});
			//Disable right click
		  /*$(document).bind("contextmenu",function(e){
			        e.preventDefault();
			});*/
		});
		
		/*$(document).ready(function() {
			setTimeout(function() {
				$("#confMsg").fadeOut(3500);
			},5500);
		});*/
		
    </script>
    
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
   
   if(send_to_ddo($cryptoGraph->decode($_GET['id'], 4)) == TRUE){ ?>
   	<div id="confMsg" class="emprow" style="vertical-align:middle; height:30px; border-radius:4px; -moz-border-radius:4px; margin:0 0 5px 0;">
					<div class="alert alert-danger" style="text-align:center"><strong>Error!!!!</strong></div>
				</div>
                
   <?php } else{?>
   <div id="confMsg" class="emprow" style="vertical-align:middle; height:30px; border-radius:4px; -moz-border-radius:4px; margin:0 0 5px 0;">
					<div class="alert alert-success" style="text-align:center"><strong>Profile Sent Successfully...</strong></div>
				</div> <br />
   	
   <?php }
	?>

            	<? if(isset($confirm) && $confirm=='completed'){?>
            	<div id="confMsg" class="emprow" style="vertical-align:middle; height:30px; border-radius:4px; -moz-border-radius:4px; margin:0 0 5px 0;">
					<div class="alert alert-success" style="text-align:center"><strong>Employee Profile submission completed...</strong></span>
				</div>
                <? }?>
                <br />
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Name</th>
<th>Primary</th>
<th>Professional</th>
<th>Salary</th>
<th>Personal</th>
<th>Contact</th>
<th>Send To BDO</th>
</tr>
<?php 

$data = get_emp();
if(count($data)){
$cnt=1; foreach($data as $item){ ?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>

<td><? if($item['emp_form_status']=='1' || $item['emp_form_status']=='2' || $item['emp_form_status']=='3' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5'){ ?>
<a href="profile_entry_basic_edit.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
<img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
</a><? } ?>
</td>

<td>
<? if($item['emp_form_status']=='2'|| $item['emp_form_status']=='3' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5'){ ?>
<a href="profile_entry_prof.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
<img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
</a><? } ?>
</td>

<td>
<? if($item['emp_form_status']=='3'|| $item['emp_form_status']=='4' || $item['emp_form_status']=='5'){ ?>
<a href="profile_entry_sal.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>&desig=<?=$cryptoGraph->encode($item['emp_desig'],4) ?>">
<img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
</a><? } ?>
</td>

<td><? if($item['emp_form_status']=='4' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5'){ ?>
<a href="profile_entry_per.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>&desig=<?=$cryptoGraph->encode($item['emp_desig'],4) ?>">
<img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
</a><? } ?>
</td>

<td><? if($item['emp_form_status']=='5'){ ?>
<a href="profile_entry_contact.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
<img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
</a><? } ?>
</td>

<td class="sendtoddo"><? if($item['emp_form_status']=='5'){ ?><a href="<?php echo $cryptoGraph->encode($item['emp_id_pk'],4) ?>"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/></a></a>
<? } else{ ?>
<img width="25" src="<?= $config['base_url'];?>themes/default/image/send_disable.png" alt="Edit" />
<?php } ?></td>
</tr>
<?php $cnt++;} }  else {?><tr>
<td colspan="8" style="color:red;font-weight:bold">No Data Found</td>
</tr><?php }?>
</table>
</div>
</div>
