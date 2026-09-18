<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
$cryptoGraph=new cryptography();
?>
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
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	padding: 19px;
	
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

.ajax_view {
	background-color:  #B8D6C2;
	text-align: center;
	padding: 8px 10px;
	margin: 10px 18px 10px 10px;
	border-radius: 4px;
	-moz-border-radius: 4px;
}
.ajax_view h2 {
	font-size: 18px;
	margin: 0px;
	color: #0070A3;
	font-family: Verdana, Geneva, sans-serif;
}
.ajax_view h3 {
	font-size: 20px;
	margin: 0px;
	color: #00876A;
	/*color:#FFFFFF;*/
	font-weight: bold;
	font-family: Verdana, Geneva, sans-serif;
}

h2.head_contact{
	color: #FFF;
	text-transform: uppercase;
	font-weight: bold;
	text-align: center;
	background-color: rgb(60, 175, 187);
	margin: 1px;
	border-radius: 5px;
	-moz-border-radius: 5px;
	font-size:16px;
	height:25px;
	padding-top:4px;
}

.reason_view {
    position: relative;
    display: inline-block;
}

.reason_view .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 1s;
	font-size:14px;
}

.reason_view .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.reason_view:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
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
$common['title'] = "ZP PROFILE VIEW| PRD | Govt. of West Bengal ";

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
		$('.diff').removeAttr('style'); 
		  $( ".diff" ).css( "background-color", "#A1CAE2" );
		
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
        <div class="col-sm-12" style="width:98%">
<!--<h1 class="heading">VIEW PS PROFILE</h1>
<div class="border"></div>-->
</br>
</br>
<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/
if(isset($_SESSION['school_msg'])){
	echo $_SESSION['school_msg'];
	unset($_SESSION['school_msg']);
}
 
$db = new database();

$query=$db->fetch_table("SELECT  
							zp.district_id_fk,
							pm.zp_status,
							pm.zp_unlock_status,
							zp.aeo_name, 
							zp.secretary_name, 
							zp.fc_cao_name ,
							pm.district_name,
							zp.accountant_name,  
							zp.road_name,
							zp.post_office_name, 
							zp.police_station_name, 
							zp.pin_code, 
							zp.cotract_no, 
							zp.email, 
							zp.ip_address,
							zp.vill_name,
							zp.tan_no,
							zp.gst_no,
							zp.pl_code,
							zp.pan_no,
							zp.ddo_code,
							zp.operator_code_pf,
							zp.t_code_pf,
							psu.reason,
							psu.sl_no
						FROM 
							prd_location_master_district pm 		
						INNER JOIN 
							zpemp_zp_profile zp 
						ON 
							pm.district_id_pk=zp.district_id_fk 
						LEFT JOIN
							zpemp_zp_profile_update_status psu
						ON
							pm.district_id_pk=psu.district_id_fk
						
	     				WHERE 
							zp.district_id_fk='".$_SESSION['location']['district_id']."' 
						ORDER BY 
							psu.sl_no DESC LIMIT 1
		   				");
						
						
						
							
/*$query=$db->fetch_table("SELECT  
							ps.ps_id_fk,
							ps.exe_officer_name, 
							ps.mobile_no, 
							ps.road_name ,
							pm.ps_name, 
							ps.post_office_name,
							ps.police_station_name, 
							ps.pin_code, 
							ps.cotract_no, 
							ps.email, 
							ps.last_upd_time, 
							ps.ip_address,
							ps.vill_name,
							ps.ddo_code,
							pm.ps_status, 
							psu.reason,
							psu.sl_no 
						FROM 
							prd_location_master_panchayat_samiti pm 	X	prd_location_master_district
						INNER JOIN 
							psemp_ps_profile ps 
						ON 
							pm.ps_id_pk=ps.ps_id_fk 
						LEFT JOIN 
							psemp_ps_profile_update_status psu		 
						ON 
							pm.ps_id_pk=psu.ps_id_fk
	     				WHERE 
							ps.ps_id_fk='".$_SESSION['location']['ps_id']."' 
		   				ORDER BY 
							psu.sl_no DESC LIMIT 1" );*/
    		
	$aeo_name=$query[0]['aeo_name'];
	$secretary_name=$query[0]['secretary_name'];
	$fc_cao_name=$query[0]['fc_cao_name'];
	$accountant_name=$query[0]['accountant_name'];
	$road_name=$query[0]['road_name'];
	$post_office_name=$query[0]['post_office_name'];
	$police_station_name=$query[0]['police_station_name'];	
	$pin_code=$query[0]['pin_code'];
	$cotract_no=$query[0]['cotract_no'];
	$email=$query[0]['email'];
	$reject_reason=$query[0]['reason'];
	$vill_name=$query[0]['vill_name'];
	$tan_no=$query[0]['tan_no']; 
	$gst_no=$query[0]['gst_no'];
	$pl_code=$query[0]['pl_code']; 
	$ddo_code=$query[0]['ddo_code'];
	$pan_no=$query[0]['pan_no'];
	$operator_code_pf=$query[0]['operator_code_pf'];
	$t_code_pf=$query[0]['t_code_pf'];
	/*if($query[0]['cotract_no']==0)
	{
		$contact_no='';
	}
	else
	{
		$contact_no=$query[0]['cotract_no'];
	}
	if($query[0]['mobile_no']==0)
	{
		$mobile_no='';
	}
	else
	{
		$mobile_no=$query[0]['mobile_no'];
	}*/
	
	
?>
    
<div class="emplist">
<div class="school">

<div class="row">
<div class="col-sm-6">
	<?
    if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='0')
    {
		echo $status='<p class="text-success" style="font-weight:bold; font-size: 18px;">STATUS : Approved</p>';
    }
    else if($query[0]['zp_status']=='2') 
    {
        echo $status='<p class="text-warning" style="font-weight:bold; font-size: 18px;">STATUS : Waiting For Approval From Secretary</p>';
    }
    else if($query[0]['zp_status']=='3') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Rejected By Secretary [ Reject Reason : '.$reject_reason.']</p>';
    }
	else if($query[0]['zp_status']=='4') 
    {
        echo $status='<p class="text-warning" style="font-weight:bold; font-size: 18px;">STATUS : Forwarded To AEO</p>';
    }
	else if($query[0]['zp_status']=='5') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Rejected By AEO [ Reject Reason : '.$reject_reason.']</p>';
    }
	
	//UNLOCK
    /*else if($query[0]['zp_status']=='6') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Waiting For Unlock</p>';
    }*/
	else if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='1') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Unlock Request Sent To Secretary</p>';
    }
	else if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='2') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Unlock Request Forwarded To AEO</p>';
    }
    else if($query[0]['zp_status']=='7') 
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Unlocked For Edit</p>';
    }
	//UNLOCK
	
    else if($query[0]['zp_status']=='0')
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Not Sent</p>';
    }
    else if(!$query)
    {
        echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Not Submitted</p>';
    }
    ?>
</div>
    
    <div class="col-sm-6 ">
        <div style="width:140px; margin-left:70%; border:rgb(60, 175, 187) 3px solid; text-align:center; padding-top:3px; box-shadow: 7px 7px 7px #888888; border-radius: 5px;">
			<?php 
            if($query[0]['zp_status']=='0' )
            { ?>
        		<a data-bs-toggle="modal" data-bs-target="#send" style="cursor:pointer;"><i class="fa fa-share-square-o fa-2x reason_view" aria-hidden="true"><span class="tooltiptext">Send To Secretary</span></i></a>
        	<?php 
			}
        	else
        	{ ?>
        		<i class="fa fa-share-square-o fa-2x reason_view" aria-hidden="true" style="opacity:0.5;"></i>
        	<?php 
			} 
        	if($query[0]['zp_status']=='0' || $query[0]['zp_status']=='5' || $query[0]['zp_status']=='3' || $query[0]['zp_status']=='7')
        	{ ?>
        		&nbsp; &nbsp;<a href="zp_profile_form.php" style="cursor:pointer; "><i class="fa fa-pencil-square-o fa-2x reason_view" aria-hidden="true"><span class="tooltiptext">Edit Profile</span></i></a>
        	<?php 
			} 
        	else
        	{ ?>
        		&nbsp; &nbsp;<i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
        	<?php 
			} 
        	if($query[0]['zp_status']=='1' && $query[0]['zp_unlock_status']=='0')
        	{ ?>
        		&nbsp; &nbsp;<a data-bs-toggle="modal" data-bs-target="#unlock" style="cursor:pointer;"><i class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"><span class="tooltiptext">Sent For Unlock</span></i></a>
        	<?php 
			} 
        	else
        	{ ?>
        		&nbsp; &nbsp;<i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
        	<?php 
			} ?>
        </div>
    </div>
<div style="height:45px;" ></div>
<div class="downpdf" style="margin-left:88%"><a href="<?= $config['base_url']?>page/intra_zp/dae/pdf_zp_profile_details.php"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>

<div class="downpdf" align="right" style=" height:12px;"></div>
<div class="table-responsive">
<h2 class="heading">ZP Profile Details</h2>
<div class="border"></div>
<br/>

<table width="100%" class="table" style="font-size:14px;font-family:'calibri'; text-align:left;">
    <!--<tr>
    <td style="width:10%;">&nbsp;</td>
    <td style="text-align:left;width:20%; "><strong>DISTRICT NAME :</strong></td>
    <td style="text-align:left; width:20%;"><?php echo $_SESSION['location']['district_name'] ?></td>
    <td style="text-align:left; width:20%;"><strong></strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"></td>
    </tr>

    <tr>
    <td style="width:10%;">&nbsp;</td>
    <td style="text-align:left; width:20%;"><strong>AEO NAME :</strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $aeo_name ?></td>
    <td style="text-align:left; width:20%;"><strong>SECRETARY NAME :</strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $secretary_name;   ?></td>
    </tr>
    <tr>
    <td style="width:10%;">&nbsp;</td>
    <td style="text-align:left; width:20%;"><strong>FC & CAO NAME :</strong></td>
    <td style="text-align:left; width:20%;"><?php echo $fc_cao_name; ?></td>
    <td style="text-align:left; width:20%;"><strong>ACCOUNTANT NAME :</strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $accountant_name;   ?></td>
    </tr>-->
    <tr>
		<td style="width:10%;">&nbsp;</td>
		<td style="text-align:left;width:20%; "><strong>DISTRICT NAME :</strong></td>
		<td style="text-align:left; width:20%;"><?php echo $_SESSION['location']['district_name'] ?></td>
		<td style="text-align:left; width:20%;"><strong>AEO NAME :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $aeo_name; ?></td>
    </tr>

    <tr>
		<td style="width:10%;">&nbsp;</td>
		<td style="text-align:left; width:20%;"><strong>TAN NUMBER :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $tan_no; ?></td>
		<td style="text-align:left; width:20%;"><strong>PAN NO :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $pan_no; ?></td>
    
    </tr>
     <tr>
		<td style="width:10%;">&nbsp;</td>
		<td style="text-align:left; width:20%;"><strong>GST NUMBER :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $gst_no;   ?></td>
		<td style="text-align:left; width:20%;"><strong>LF OPERATOR CODE :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $pl_code; ?></td>
    </tr>
	
    <tr>
		<td style="width:10%;">&nbsp;</td>
		<td style="text-align:left; width:20%;"><strong>TREASURY CODE :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $ddo_code;   ?></td>
  
		<td style="text-align:left; width:20%;"></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"></td>
    </tr>
	<tr>
		<td style="width:10%;">&nbsp;</td>
		<td style="text-align:left; width:20%;"><strong>OPERATOR CODE (PF SUBSCRIPTION) :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $operator_code_pf;   ?></td>
		<td style="text-align:left; width:20%;"><strong>TREASURY CODE (PF SUBSCRIPTION) :</strong></td>
		<td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $t_code_pf; ?></td>
    </tr>
</table>

<h2 class="heading">ZP ADDRESS</h2>
<div class="border"></div>
<br/>


<table  width="100%" class="table" style="font-size:14px;font-family:'calibri'; text-align:left;">
    <tr >
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>ROAD NAME :</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $road_name; ?></td>
        <td style="text-align:left; width:20%;"><strong>VILLAGE/TOWN NAME :</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $vill_name; ?></td>
    </tr>

    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>POST OFFICE :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $post_office_name; ?></td>
        <td style="text-align:left; width:20%;"><strong>POLICE STATION :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $police_station_name; ?></td>
    </tr>

    <tr >
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>PIN CODE :</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $pin_code; ?></td>
        <td style="text-align:left; width:20%;"><strong>CONTACT NO. :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $cotract_no; ?></td>
    </tr>

    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>EMAIL ID. :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $email; ?></td>
       
        <td style="text-align:left; width:20%;"><strong></strong></td>
        <td style="text-align:left; width:20%;text-transform:uppercase;"></td>
       
       
    </tr>
</table>
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


<? require '../../../page/layout/footer.php'; ?>





<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
	$(document).ready(function(){
		$(".view_prof a").click(function() {	
		var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		$.post('<?= $config['base_url'] ?>page/intra_vtc/hoi/ajax_school_view.php', function(data){
				  $(".mbody").html(data);
				});
		});
	});
</script>

<div class="modal fade" id="gpprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">ZP Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->

<?php
$sent=$cryptoGraph->encode('sent',4);
$unlock_sent=$cryptoGraph->encode('unlock_sent',4);

?>

<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This ZP Profile For Approval ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_zp/dae/sent_zp_profile.php?action=<?php echo $sent ?>">YES</a> 
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This ZP Profile For Unlock ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_zp/dae/sent_zp_profile.php?action=<?php echo $unlock_sent ?>">YES</a> 
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>