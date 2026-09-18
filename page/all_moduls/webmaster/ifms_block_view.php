 <?php

session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
  
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>User Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}?>
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

/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

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
		  //$( ".table:last" ).css( "border-radius", "0px 0px 5px 5px" );
		 /* 
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
*/
			
		
		});
		
		
		
    </script>
<?

$db=new database();
$crypto = new cryptography();
$stack = $crypto->decode($_GET['stack'],4);
$month = $crypto->decode($_GET['month'],4);
$year = $crypto->decode($_GET['year'],4);
$req_type = $crypto->decode($_GET['req_type'],4);

$current_monthyr=$year.$month;

	
if($stack =='GP'){	

	$condition="block.block_name,bill.drn_number, bill.block_code,
	sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status,sftp.sftp_benf_file_name  ";
	$condition3="INNER join prd_location_master_block as block 
	on dist.district_id_pk=block.district_id_fk ";
	$condition4="INNER join prd_block_bill_details as bill on 
	CAST(block.block_code as character varying)=bill.block_code and bill.salary_monthyear='".$current_monthyr."' and  bill.requisition_type='".$req_type."' and bill.status='1' ";
	$condition5 = "";
	
						
}
elseif($stack =='PS'){
	
	$condition="ps.ps_name,ps.ps_code,bill.drn_number,
	sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status,sftp.sftp_benf_file_name ";
	$condition3="INNER join prd_location_master_panchayat_samiti as ps 
	on dist.district_id_pk=ps.district_id_fk ";
	$condition4="INNER join prd_block_bill_details as bill 
	on bill.ps_id_fk=ps.ps_id_pk  and bill.salary_monthyear='".$current_monthyr."' and bill.requisition_type='".$req_type."' and bill.status='1' ";
	$condition5="INNER join psemp_ps_profile as pse 
	on bill.ps_id_fk=pse.ps_id_fk ";
	
	
}
elseif($stack =='ZP'){
	
	$condition="bill.drn_number,dist.district_name,dist.district_code,
	sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status,sftp.sftp_benf_file_name";
	$condition4="INNER join prd_block_bill_details as bill 
	on dist.district_id_pk=bill.zp_id_fk  and bill.salary_monthyear='".$current_monthyr."' and bill.requisition_type='".$req_type."' and bill.status='1' ";
	$condition5="INNER join zpemp_zp_profile as zp 
	on bill.zp_id_fk=zp.district_id_fk ";
	$condition3= "";
	
}



$bill_details_fetch=$db->fetch_table("SELECT dist.district_name,".$condition."
	from 
	prd_location_master_district as dist
	".$condition3."
	".$condition4."
	".$condition5."
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk and sftp.active_status='1' 
	where dist.district_code not in('3297','3295','3296') and bill.status='1'
	order by dist.district_name");
	
	
/*	$bill_details_fetch_gp=$db->fetch_table(" SELECT 
												bill.block_bill_pk,
												bill.salary_monthyear,
												bill.drn_number,
												bill.block_code,
												sftp.sftp_benf_id_pk,
												sftp.sftp_benf_file_name,
												sftp.sftp_benf_response_status 
											FROM prd_block_bill_details bill
											INNER JOIN prd_sftp_benf_upload_response sftp
											ON bill.block_bill_pk=sftp.bill_id_fk
											WHERE bill.salary_monthyear in ('".$current_monthyr."') AND bill.status='1' AND block_code!='0' 
											AND sftp.active_status='1' AND sftp.sftp_benf_sending_status='4' 
											AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
	*/		
			
?>

<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
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
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">IFMS CRON JOB FOR THE MONTH OF: <?php echo date("F", mktime(0, 0, 0, $month, 10));?>,<?php echo $year;?> </h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 
if($msg){
echo "<br/>";
echo $msg;
echo "<br/>";
}
if(isset($_SESSION['msg'])){
	echo "<br/>";
	echo $_SESSION['msg'];
	echo "<br/>";
	unset($_SESSION['msg']);
	             
}


if(isset($_SESSION['ifms_msg'])){
	echo $_SESSION['ifms_msg'];
	unset($_SESSION['ifms_msg']);
}


?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%" id="example">
 <input type="hidden" id="user" name="user" value="<?php echo $stack;?>" />
<thead>
	<tr>
		<th>Serial No.</th>
		<th><? if($stack =='GP'){ echo "Block Code"; } elseif($stack =='PS'){ echo "PS Code"; } elseif($stack =='ZP'){ echo "ZP Code"; }?></th>	
		<th><? if($stack =='GP'){ echo "Block Name"; } elseif($stack =='PS'){ echo "PS Name"; } elseif($stack =='ZP'){ echo "ZP Name"; }?></th>	
		<th>SFTP File Name</th>
		<th>DRN Number</th>
		<th>.Done Received</th>
		<th>ACK File Received</th>
		<th>Payment File Received</th>
		<th>Action</th>
		<!--<th>File Status</th>-->
	</tr>
</thead>





<?php 

$cnt=1; if(count($bill_details_fetch)){ foreach($bill_details_fetch as $item){
	
	$encode_drn=$crypto->encode($item['drn_number'],4);
	$encode_requsition=$crypto->encode($item['requisition_type'],4);
		
		//$encode_drn=$item['drn_number'];
		//$encode_requsition=$item['requisition_type'];
	//$block_name = $db->fetch_table("SELECT block_name FROM prd_location_master_block WHERE block_code ='".$item['block_code']."'");
	?>
<tr>
	<td><?= $cnt; ?></td>	
	<td><? if($stack =='GP'){ echo $item['block_code']; } elseif($stack =='PS'){ echo $item['ps_code']; } elseif($stack =='ZP'){ echo $item['district_code']; }?></td>	
	<td><? if($stack =='GP'){ echo $item['block_name']; } elseif($stack =='PS'){ echo $item['ps_name']; } elseif($stack =='ZP'){ echo $item['district_name']; } ?></td>	
	<td><?= $item['sftp_benf_file_name']; ?></td>	
	<td><?= $item['drn_number']; ?></td>	
	<td><?php if($item['sftp_benf_sending_status']=='4'){ echo'<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>	
	<td><?php if($item['sftp_benf_response_status']=='5' || $item['sftp_benf_response_status']=='8'){ echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>	
	<td><?php if($item['sftp_benf_response_status']=='8'){ echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>	
    <td width="20%"><div class="link_p3">
	<a id="view_status"  onClick="value_pass_view('<?php echo $encode_requsition; ?>','<?php echo $encode_drn; ?>');"> <img src="<?php echo $config['base_url']; ?>themes/default/image/status_btn.png" class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a></div></td>
	</td>
	<!--<td id="view_bill_status" width="50%"> </td>-->
</tr>	
<?php	
	$cnt++; }

?>


<?php 
}
 else { ?>
	<tr>
		<td colspan="10" style="color:red;font-weight:bold">No Data Found</td>
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

<script type="text/javascript">
$(document).ready(function() 
{
	//alert(11);
	$("#example").DataTable({
		"aLengthMenu": [[2, 3, 5, -1], [2, 3,5, "All"]],
        "iDisplayLength": 3,
		"bFilter": true,
	  	"bSort": true,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});

	
	
  });
  
  function value_pass_view(encode_requsition,drn_number)
{
	
    
	var requsition=encode_requsition;
	var drn_no=drn_number;
	var user1=$('#user').val();	
	//alert(requsition);
	//alert(drn_no);
	//return false;
	if(user1=='ZP')
	{		
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/bill_status_check.php?bill_type='+requsition+'&drn_no='+drn_no,function(data){
		alert(data);
		
		
		});	
	}
	if(user1=='GP')
	{		
		$.post('<?= $config['base_url'] ?>page/api/ifms/gp/bill_status_check.php?bill_type='+requsition+'&drn_no='+drn_no,function(data){
		alert(data);
		
		
		});	
	}
	if(user1=='PS')
	{		
		$.post('<?= $config['base_url'] ?>page/api/ifms/ps/bill_status_check.php?bill_type='+requsition+'&drn_no='+drn_no,function(data){
		alert(data);
		
		});	
	}
}

</script>




