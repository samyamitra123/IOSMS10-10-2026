<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
<?
$db=new database();
$arr=$db->fetch_table("select emp_id_const,emp_first_name,emp_second_name,emp_last_name,emp_status,emp_id_pk,emp_system_code from prd_employee_master where emp_status in('6','1','3','4','9') AND ps_id_fk='".$_SESSION['location']['ps_id']."' order by emp_id_const");
?>
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
<? require '../../../../page/common_back_btns.php'; ?>
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
        <div class="col-sm-12" style="width:98%;">
<h1 class="heading">VIEW EMPLOYEE DETAILS</h1>
<div class="border"></div>
</br>
</br>
        <?
 
	   if($_SESSION['msg']){
	echo $_SESSION['msg'];

	 unset($_SESSION['msg']);
	
}
?>  
<?php 
if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee ID</th>
<th>Employee Name</th>
<!--<th>Employee Code</th>-->
<th>Status</th>
<th>View</th>
<th>REQUEST FOR UNLOCK</th>
<th>EDIT</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
if($item['emp_status']=='6')
{
	$status='<span style="color:#660066;font-weight:bold">PROFILE SENT</span>';
}
else if($item['emp_status']=='1')
 {
	$status='<span style="color:green;font-weight:bold">PROFILE FINALIZED</span>';
}

else if($item['emp_status']=='3') 
{
	$status='<span style="color:RED;font-weight:bold">WAITING FOR UNLOCK</span>';
}
else if($item['emp_status']=='4') 
{
	$status='<span style="color:green;font-weight:bold">WAITING FOR EDIT</span>';
}
else if($item['emp_status']=='9') 
{
	$status='<span style="color:#4d0bcc;font-weight:bold">PROFILE SUSPENDED</span>';
}
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<!--<td><? //echo $item['emp_system_code'] ?></td>-->
<td><?= $status; ?><input type="hidden" id="id<?=$cnt; ?>" name="id" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>"  /></td>
<td class="view"><a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#myModal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
<?php
if($item['emp_status']=='1') 
		 { ?>
<td><a onClick="sent_unlock(<?=$cnt;?>);" style="cursor:pointer;"><i class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"></i></a></td>
<?php } 
else
		{ ?>
       <td><i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
		<?php } ?></td>
        <? if($item['emp_status']=='4'||$item['emp_status']=='7') { $a=$cryptoGraph->encode($item['emp_id_pk'],4); ?>
<td><a  href="employee_edit_details.php?emp_id=<?php echo $a; ?>" ><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" /></a>

<? } else { ?>
<td><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" style="opacity:0.5" /></td>
<? } ?>
</tr>
 


<? $cnt+=1; }} else { ?>
<tr>
<td colspan="7" style="color:red;font-weight:bold">No Data Found</td>
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





<? require '../../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
		$(document).ready(function(){
		$(".view a").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/intra_ps/da/ps_emp/ajax_emp_view.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		});

function sent_unlock(k)
{
	var emp_id=$('#id'+k).val();
	//alert (emp_id);
	$('#unlock_emp_id').val(emp_id);
	$('#unlock').modal('show');
}
/*function edit(k)
{
	var emp_id=$('#id'+k).val();
	$('#edit_emp_id').val(emp_id);
	
}*/

</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
     <!-- <h4>&nbsp;</h4>-->
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
<form name="unlock_emp" id="unlock_emp" action="unlock_employee_profile.php" method="post">
<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send UNLOCK Request To EO ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <input type="hidden" id="unlock_emp_id" name="unlock_emp_id" />
       <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>
 <!-----------------------------------------------------------------UNLOCK MODAL END---------------------------------------------------->
