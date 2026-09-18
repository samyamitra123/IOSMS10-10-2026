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
$common['title'] = "PS PROFILE VIEW| PRD | Govt. of West Bengal ";

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
<? require '../../../page/common_back_btns.php';?>
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

<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/
if(isset($_SESSION['gp_msg1'])){
	echo $_SESSION['gp_msg1'];
	unset($_SESSION['gp_msg1']);
}

$db = new database();
	
$query=$db->fetch_table("SELECT  
							ps.ps_id_fk,
							ps.exe_officer_name, 
							ps.mobile_no, 
							ps.road_name,
							pm.ps_name, 
							ps.post_office_name,
							ps.police_station_name, 
							ps.pin_code, 
							ps.cotract_no, 
							ps.email, 
							ps.last_upd_time, 
							ps.ip_address,
							ps.vill_name,
							pm.ps_status, 
							psu.reason,
							psu.sl_no,
							ps.ddo_code,
							ps.treasury_code,
							ps.pl_code_pf,
							ps.t_code_pf
							
						FROM 
							prd_location_master_panchayat_samiti pm 
						INNER JOIN 
							psemp_ps_profile ps 
						ON 
							pm.ps_id_pk=ps.ps_id_fk 
						INNER JOIN 
							psemp_ps_profile_update_status psu 
						ON 
							pm.ps_id_pk=psu.ps_id_fk
	      				WHERE 
							ps.ps_id_fk='".$_SESSION['location']['ps_id']."' 
		   				ORDER BY 
							psu.sl_no DESC LIMIT 1" );
							
$ps_name=$query[0]['ps_name']; 
$executive_officer_name=$query[0]['exe_officer_name'];
$mobile_no=$query[0]['mobile_no'];
$road_name=$query[0]['road_name'];
$vill_name=$query[0]['vill_name'];
$post_office=$query[0]['post_office_name'];
$police_station=$query[0]['police_station_name'];	
$pin=$query[0]['pin_code'];
$contact_no=$query[0]['cotract_no'];
$email_id=$query[0]['email'];
$reject_reason=$query[0]['reason'];
$ps_status=$query[0]['ps_status'];
$ddo_code=$query[0]['ddo_code']; 
$t_code=$query[0]['treasury_code'];
$pl_code_pf=$query[0]['pl_code_pf'];
	$t_code_pf=$query[0]['t_code_pf'];	
	?>
    
<div class="emplist">
<div class="school">

<div class="row">
<div class="col-sm-6">
<?
if($query[0]['ps_status']=='1')
{
	echo $status='<p class="text-warning" style="font-weight:bold; font-size: 18px;">STATUS : Waiting For Approval</p>';
}
else if($query[0]['ps_status']=='2') 
{
	echo $status='<p class="text-success" style="font-weight:bold; font-size: 18px;">STATUS : Approved</p>';
}
else if($query[0]['ps_status']=='3') 
{
	echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Rejected [ Reject Reason : '.$reject_reason.']</p>';
}
else if($query[0]['ps_status']=='7') 
{
	echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Waiting For Unlock</p>';
}
else if($query[0]['ps_status']=='8') 
{
	echo $status='<p class="text-danger" style="font-weight:bold; font-size: 18px;">STATUS : Profile Unlocked For Edit</p>';
}
else if($query[0]['ps_status']=='0' || $arr[0]['flag']=='9')
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
        <div style="width:100px; margin-left:80%; border:rgb(60, 175, 187) 3px solid; text-align:center; padding-top:3px; box-shadow: 7px 7px 7px #888888; border-radius: 5px;">
			<?php 
        	if($ps_status=='2')
        	{ ?>
        		<a href="ps_profile_form.php" style="cursor:pointer; "><i class="fa fa-pencil-square-o fa-2x reason_view" aria-hidden="true"><span class="tooltiptext">Edit Profile</span></i></a>
        	<?php 
			} 
        	else
        	{ ?>
        		<i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
        	<?php 
			} 
        	if($ps_status=='7')
        	{ ?>
        		&nbsp; &nbsp;<a data-bs-toggle="modal" data-bs-target="#unlock" style="cursor:pointer;"><i class="fa fa-key fa-2x reason_view" aria-hidden="true"><span class="tooltiptext">Unlock Profile</span></i></a>
        	<?php 
			} 
        	else
        	{ ?>
        		&nbsp; &nbsp;<i class="fa fa-key fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
        	<?php 
			} ?>
        </div>
    </div>
<div style="height:45px;" ></div>

<div class="downpdf" style="margin-left:89%"><a href="<?= $config['base_url']?>page/intra_ps/eo/pdf_ps_profile_details.php"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>

<div class="downpdf" align="right" style=" height:12px;"></div>
<div class="table-responsive">
    <h2 class="heading">Profile Details</h2>
<div class="border"></div>
<br/>
<table width="100%" class="table" style="font-size:14px;font-family:'calibri'; text-align:left;">
    <tr>
    <td style="width:10%;">&nbsp;</td>
    <td style="text-align:left;width:20%; "><strong>DISTRICT NAME:</strong></td>
    <td style="text-align:left; width:20%;"><?php echo $_SESSION['location']['district_name'] ?></td>
    <td style="text-align:left; width:20%;"><strong>PS NAME:</strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $ps_name ?></td>
    </tr>

    <tr>
    <td style="width:10%;">&nbsp;</td>
    <td style="text-align:left; width:20%;"><strong>NAME OF EXECUTIVE OFFICER:</strong></td>
    <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $executive_officer_name;   ?></td>
    <td style="text-align:left; width:20%;"><strong>MOBILE NO.:</strong></td>
    <td style="text-align:left; width:20%;"><?php echo $mobile_no; ?></td>
    </tr>

</table>

<h2 class="heading">PS ADDRESS</h2>
<div class="border"></div>
<br/>


<table  width="100%" class="table" style="font-size:14px;font-family:'calibri'; text-align:left;">
    <tr >
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>ROAD NAME :</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $road_name; ?></td>
        <td style="text-align:left; width:20%;"><strong>VILLAGE/TOWN NAME:</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $vill_name; ?></td>
    </tr>

    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>POST OFFICE :</strong></td>
        <td style="text-transform:uppercase;text-align:left; width:20%;"><?php echo $post_office; ?></td>
        <td style="text-align:left; width:20%;"><strong>POLICE STATION:</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $police_station; ?></td>
    </tr>

    <tr >
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>PIN CODE :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $pin; ?></td>
        <td style="text-align:left; width:20%;"><strong>CONTACT NO. :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $contact_no; ?></td>
    </tr>

    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>EMAIL ID. :</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $email_id; ?></td>
         <td style="text-align:left; width:20%;"><strong>PL OPERATOR CODE :</strong></td>
        <td style="text-align:left; width:20%;text-transform:uppercase;"><?php echo $ddo_code; ?></td>
    </tr>
    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>TREASURY CODE.:</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $t_code; ?></td>
       <td></td>
       <td></td>
    </tr>
    
    <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>PL OPERATOR CODE(PF SUBSCRIPTION):</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $pl_code_pf; ?></td>
       <td></td>
       <td></td>
    </tr>
     <tr>
        <td style="width:10%;">&nbsp;</td>
        <td style="text-align:left; width:20%;"><strong>TREASURY CODE(PF SUBSCRIPTION):</strong></td>
        <td style="text-align:left; width:20%;"><?php echo $t_code_pf; ?></td>
       <td></td>
       <td></td>
    </tr>
</table>
<?php   
if($ps_status==1){
?>

<div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
        <a class="btn btn-success" data-bs-toggle="modal" onClick="approve_sch(<?=$dise_code?>);">Approve</a>
        <a class="btn btn-danger" data-bs-toggle="modal"  onClick="reject_sch(<?=$dise_code?>);">Reject</a>    
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
</div>
</div>
<div class="clear"></div>


<? require '../../../page/layout/footer.php'; ?>





<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
		
    function approve_sch(k)
{

	//alert(k);
	$('#school_id_app').val(k);
	$('#approve').modal('show');
	//$("#approve").modal();

}

function reject_sch(k)
{

	$('#school_id_rej').val(k);
	$('#reject').modal('show');

}

    </script>

<form name="school_app" id="school_app" action="ps_profile_finalize.php">
<div class="modal fade bs-example-modal-sm" id="approve" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">VTC Approval</h4>
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Approve The PS Profile?</strong></p>
      <input type="hidden" id="school_id_app" name="school_id" />
      <input type="hidden" id="action_app" name="action" value="<?=$cryptoGraph->encode('approve',4)?>"/>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>



<form name="school_rej" id="school_rej" action="ps_profile_finalize.php" onSubmit="return valid_reason();">
<div class="modal fade bs-example-modal-md" id="reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">VTC Rejection</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Reject The PS Profile?</strong></p>
      <input type="hidden" name="school_id" id="school_id_rej" />
      <input type="hidden" name="action" id="action_rej" value="<?=$cryptoGraph->encode('reject',4)?>"/>
      <label for="inputPassword3" class="col-sm-2 control-label">Reason<span class="star_color">*</span>:</label>
      <textarea rows="4" cols="60" name="reason" id="reason_valid" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz,. ');"></textarea>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
       </div>
     </div>
      </form>


<?php
	$accept=$cryptoGraph->encode('accept',4);
	$reject=$cryptoGraph->encode('reject',4);
?> 
      
<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Accept The Unlock Request ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/unlock_ps_profile.php?action=<?php echo $accept ?>">Accept</a> &nbsp;
        <a class="btn btn-danger" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/unlock_ps_profile.php?action=<?php echo $reject ?>">Reject</a> 
      </div>
      </div>
    </div>
  </div>
</div>