<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

header("Strict-Transport-Security: max-age=63072000");

session_start();

require '../../includes/config/config.php';
require '../../includes/config/database.config.php';

require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';

//print_r($_SESSION); exit;

if (
	  !isset($_SESSION['user_info']['officer_id_const'])
	|| !isset($_SESSION['user_info']['stake_user_mob'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/intra_pri/login_intra_pri.php");
	exit;
} 
//$cryptoGraph=new cryptography();
$crypto = new cryptography();


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
	padding: 20px;
	
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
/*if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}*/

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "GP PROFILE VIEW| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
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
.menu {margin: 0 auto; width: 360px;}
.menu li {display: inline-table; margin: 10px;}
</style>
<div class="content noPrint" id="nonPrintable">
<?php require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
		<?php 
		$db = new database();
		$Query = " SELECT * FROM intra_pri_master as ipm left join intra_pri_designation_master as ipdm ON ipm.designation = ipdm.designation_code::integer WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ";

		$officer_name = $db->fetch_table($Query);
		
		$Query = " SELECT forw.*, role.description as role_desc, forw.status as status, forw.for_app_rej as for_app_rej,
											role.db_page as db_page
											FROM intra_pri_forwarding as forw  
											INNER JOIN intra_pri_role_assigment as role ON forw.sub_menu = role.sub_menu
											WHERE (forw.status = 1 OR forw.status = 2) AND forw.delete=0 AND forw.from_officer_id_const = '".$_SESSION['user_info']['officer_id_const']."' order by forwarding_id_pk asc"; 
		$application_list = $db->fetch_table($Query);
	  //print_r($Query); exit;
	  $Applications = array();
	  foreach($application_list as $app)
	  {
	  	$Applications[$app['application_id']] = $app;
	  }

	  $appDate = array();
	  foreach($Applications as $key=>$value)
	  {
	  	$appDate[$key] = $value['submitted_on'];
	  }
	  array_multisort($appDate,SORT_DESC,$Applications);

	
	
	
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?> (<?php echo $officer_name[0]['designation'];?>)</h3>
    </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">VIEW SERVICES</h1>
<div class="border"></div>
<ul class="menu">
	<li><a href="view_intra_pri_service.php" class="btn btn-sm btn-primary">Inbox</a></li>
	<li><a href="approved_intra_pri_service.php" class="btn btn-sm btn-primary">Approved</a></li>
	<li><a href="javascript:void(0);" class="btn btn-sm btn-primary active">Rejected</a></li>
</ul>
</br>
</br>
<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}
if(isset($_SESSION['gp_msg'])){
	echo $_SESSION['gp_msg'];
	unset($_SESSION['gp_msg']);
}
if(isset($_SESSION['unlock_msg'])){
	echo $_SESSION['unlock_msg'];
	unset($_SESSION['unlock_msg']);
}*/
?>
<div class="emplist" style="width: 99%;">
<div class="school">
<div class="table-responsive">
<table width="100%" id="services" class="table table-striped table-bordered">
<thead>
<th>SL NO</th>
<th>APPLICATION ID</th>
<th>EMPLOYEE NAME</th>
<th>EMPLOYEE ID</th>
<th>NAME OF SERVICE</th>
<th>STATUS From</th>
<th>STATUS To</th>
<th>VIEW</th>

</thead>

<tbody>
<?php 
		
	$count=1;	
foreach($Applications as $key=>$value){ 

//if($value['status'] == 2) {
			$Query = " SELECT master.officer_name as officer_name, login_level_stake as stake, forw.for_app_rej FROM intra_pri_master as master
					INNER JOIN intra_pri_forwarding as forw ON master.officer_id_const = forw.from_officer_id_const
					WHERE  forw.application_id ='".$value['application_id']."' order by forwarding_id_pk desc LIMIT 1 offset 0 ";
			$officer_name_pendingTemp = $db->fetch_table($Query);
			$officer_name_pendingTemp = $officer_name_pendingTemp[0];	
			if($officer_name_pendingTemp['for_app_rej'] != 'R')
			{
				continue;
			}
//}

	?>
<tr>
	<?php 
	$emp_name = $db->fetch_table(" SELECT * FROM ".$value['db_page']." WHERE application_id = '".$value['application_id']."' "); 



	?>
	<td>
		 <?php echo $count; ?>
		 <?php //echo $officer_name_pendingTemp['for_app_rej']; ?>
	</td>
	<td><?php echo $value['application_id']; ?></td>
	<td><?php echo $emp_name[0]['emp_first_name'].' '.$emp_name[0]['emp_second_name'].' '.$emp_name[0]['emp_last_name']; ?></td>
	<td><?php if($value['emp_id_const'] != '0'){ echo $value['emp_id_const']; } else {echo "NA"; } ?></td>
	<td><?php echo $value['role_desc']; ?></td>
	<td>
		<?php //if($value['status'] == 1){ echo "Pending in your end."; } else if($value['status'] == 2) { 
	
			$Query = " SELECT master.officer_name as officer_name, login_level_stake as stake, forw.for_app_rej FROM intra_pri_master as master
					INNER JOIN intra_pri_forwarding as forw ON master.officer_id_const = forw.from_officer_id_const
					WHERE  forw.application_id ='".$value['application_id']."' order by forwarding_id_pk desc LIMIT 1 offset 1 ";
			$officer_name_pending = $db->fetch_table($Query);
			$officer_name_pending = $officer_name_pending[0];
			switch($officer_name_pending['for_app_rej'])
			{
					case 'R': 
					  echo "Rejected";
					  break;
					case "A":
					  echo "Approved";
					  break;
					case "FA":
					  if(($_SESSION['user_info']['stake_level'] == 'BLOCK'|| $_SESSION['user_info']['stake_level'] == 'DISTRICT') && $officer_name_pending['stake'] == 'STATE')
					  {
              echo "STATE";
					  }
					  else
					  {	
					  echo "".$officer_name_pending['officer_name'].' ('.$officer_name_pending['stake'].') '. " ";
					  }
					  break;
					default:
					  if(($_SESSION['user_info']['stake_level'] == 'BLOCK'|| $_SESSION['user_info']['stake_level'] == 'DISTRICT') && $officer_name_pending['stake'] == 'STATE')
					  {
              echo "STATE";
					  }
					  else
					  {	
					  echo $officer_name_pending['officer_name'].' ('.$officer_name_pending['stake'].') ';
					  }
					  break;
			//}
	}
   // echo $value['status'];
	?>		

	</td>
	<td>
		<?php if($value['status'] == 1){ echo "Pending in your end."; } else if($value['status'] == 2) { 
	
			/*$Query = " SELECT master.officer_name as officer_name, login_level_stake as stake, forw.for_app_rej FROM intra_pri_master as master
					INNER JOIN intra_pri_forwarding as forw ON master.officer_id_const = forw.from_officer_id_const
					WHERE  forw.application_id ='".$value['application_id']."' order by forwarding_id_pk desc LIMIT 1 offset 0 ";
			$officer_name_pending = $db->fetch_table($Query);*/
			$officer_name_pending = $officer_name_pendingTemp;
			switch($officer_name_pending['for_app_rej'])
			{
					case 'R': 
					  echo "Rejected";
					  break;
					case "A":
					  echo "Approved";
					  break;
					case "FA":
					  if(($_SESSION['user_info']['stake_level'] == 'BLOCK'|| $_SESSION['user_info']['stake_level'] == 'DISTRICT') && $officer_name_pending['stake'] == 'STATE')
					  {
              echo "Pending with STATE";
					  }
					  else
					  {	
					  echo "Pending with ".$officer_name_pending['officer_name'].' ('.$officer_name_pending['stake'].') '. " end";
					  }
					  break;
					default:
					  if(($_SESSION['user_info']['stake_level'] == 'BLOCK'|| $_SESSION['user_info']['stake_level'] == 'DISTRICT') && $officer_name_pending['stake'] == 'STATE')
					  {
              echo "Pending with STATE";
					  }
					  else
					  {	
					  echo "Pending with ".$officer_name_pending['officer_name'].' ('.$officer_name_pending['stake'].') '. " end";
					  }
					  break;
			}
	}
   // echo $value['status'];
	?>
	</td>
	<td class="view_prof">
		<?php 
		
		$order_gen = $db->fetch_table(" SELECT for_app_rej,orderupload,forwarding_id_pk FROM intra_pri_forwarding WHERE application_id = '".$value['application_id']."' ORDER BY forwarding_id_pk DESC ");
		if($order_gen[0]['for_app_rej'] == 'R')
		  {
		?>
				<a href="" id="<?= $value['application_id'];?>" data-bs-toggle="modal" data-bs-target="#bdoprofModal"><button <?php if($value['status'] == 1){ ?> class="btn btn-fail" <?php } else if($value['status'] == 2) { ?> class="btn btn-warning" <?php } ?> >Rejected</button></a>
		<?php 
     }		
		else if($order_gen[0]['for_app_rej'] != 'A')
		{
		?>

		<a href="" id="<?= $value['application_id'];?>" data-bs-toggle="modal" data-bs-target="#bdoprofModal"><button <?php if($value['status'] == 1){ ?> class="btn btn-success" <?php } else if($value['status'] == 2) { ?> class="btn btn-warning" <?php } ?> >PROPOSAL</button></a>
<?php
      } 
     else
     {
?>
		<a href="" id="<?= $value['application_id'];?>" data-bs-toggle="modal" data-bs-target="#bdoprofModal"><button <?php if($value['status'] == 1){ ?> class="btn btn-approved" <?php } else if($value['status'] == 2) { ?> class="btn btn-approved" <?php } ?> >Approved</button></a>
<?php     	
     } 		
		$last_step = $db->fetch_table(" SELECT final_approval_stake_level_code FROM intra_pri_role_assigment WHERE sub_menu='".$value['sub_menu']."' ");	
		
		$final_approval_stake_level_code = explode(',',$last_step[0]['final_approval_stake_level_code']);
		
		if($order_gen[0]['for_app_rej'] == 'A' && in_array($_SESSION['user_info']['stake_level_code'],$final_approval_stake_level_code) && $order_gen[0]['orderupload'] == 0){ 
		
		$pdf_id = $value['application_id'];
		
		if($value['service_type'] == '4'){
		?>
			<a href='intre_pri_overage_condonation_pdf.php?id=<?php echo $crypto->encode($pdf_id,4);?>' id="submit" name="submit" class="btn btn-info" target="_blank" > GENERATE OVG ORDER </a>
			
		<?php }
		else if($value['service_type'] == '3'){ ?>
			<a href='intra_pri_order_print_compassionate.php?id=<?php echo $crypto->encode($pdf_id,4);?>' id="submit" name="submit" class="btn btn-info" target="_blank"> GENERATE CG ORDER </a>
			
		<?php		
			}
		else if($value['service_type'] == '5'){ ?>
			<a href='intra_pri_9008_pdf.php?id=<?php echo $crypto->encode($pdf_id,4);?>' id="submit" name="submit" class="btn btn-info" arget="_blank"> GENERATE 9008 ORDER </a>
			
		<?php		
			}
		else if($value['service_type'] == '1'){
			
			 ?>
			<a href='intra_pri_district_transfer_outside_pdf.php?id=<?php echo $crypto->encode($pdf_id,4);?>' id="submit" name="submit" class="btn btn-info" target="_blank"> GENERATE DISTRICT TRANSFER ORDER </a>
			
		<?php		
			}
		else if($value['service_type'] == '2'){
			
			 ?>
			<a href='intra_pri_district_transfer_pdf.php?id=<?php echo $crypto->encode($pdf_id,4);?>' id="submit" name="submit" class="btn btn-info" target="_blank"> GENERATE DISTRICT TRANSFER ORDER </a>
			
		<?php		
			}			
		}	elseif($order_gen[0]['for_app_rej'] == 'A' && $order_gen[0]['orderupload'] == 1) {	?>
			  <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_application.php?app_id=<?= $crypto->encode($order_gen[0]['forwarding_id_pk'],4)?>" target="_blank" class="btn btn-success"><i class="fa fa-paperclip"></i> Download </a>
		<?php } ?>
	</td>
	
	<!--<td><button>FORWARD</button> <button>REJECT</button></td>-->
</tr>
	<!--<td><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" style="opacity:0.5" /></td>-->
<?php $count++;

} ?>
</tbody>
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





<?php require '../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
		$(document).ready(function () {
		    $('#services').DataTable();
		});		
		$(".view_prof a").click(function() {	
		var link = $(this).attr('id');
		console.log(link);
    $.ajax({
      url : 'ajax_history_view_intra_pri.php',
      type : 'POST',
      data : { "app_no" : link },
        success : function(response) { //alert(v);
				$(".mbody").html(response);
			}
        
      });
	  
});
		
</script>

<div class="modal fade" id="bdoprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 160%; margin-left: -30%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">Application Details</h4>

        <button type="button" class="btn" id='btnPrint' data-dismiss="modal"><i class="fa fa-print"></i> Print</button>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body"> 
      <div class="mbody" id="printThis"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
  <style type="text/css">
.control-label{display: block;} 
.text-show{font-size:18px;font-weight:700;font-family: "MS Serif", "New York", serif;} 	
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position:absolute;
    left:0;
    top:0;
  }
}
  </style>
<!-----------------------------------------------------------------MODAL END---------------------------------------------------->
<script type="text/javascript">
document.getElementById("btnPrint").onclick = function () {
    printElement(document.getElementById("printThis"));
    
  /*  var modThis = document.querySelector("#printSection .modifyMe");
    modThis.appendChild(document.createTextNode(" new"));
    */
    window.print();
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);
    
    var $printSection = document.getElementById("printSection");
    
    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    
    $printSection.innerHTML = "";
    
    $printSection.appendChild(domClone);
}
</script>
<!--<script>

function for_rej(k){
	var officer_id_const = $("#forwarding").val();
	var application_id = $("#application_id").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks").val();
	var emp_id_const = $("#emp_id_const").val();
	//$.post('<?= $config['base_url'] ?>page/intra_pri/update_forward_intra_pri.php?officer_id_const='+officer_id_const+'&type='+k, function(data){
		
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"remarks": remarks },
							success : function(response) {
							alert(response);
						}
				});
				
			
}

</script>-->