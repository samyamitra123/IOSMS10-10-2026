<?php

/********************************************** New Page made by ANJAN for ZP ************************************************************/


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
 /*   Kalyan Ghohs   20/3/2017   Finish   */	
		
		
$cryptoGraph=new cryptography();		
			
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------




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



<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
		
		$(document).ready(function(){
			$(".view_desig").click(function() {	
			//var link = $(this).attr('href');
			var link = $(this).attr('id');
			$(".mbody").load('ajax_emp_promotion_view.php?id='+link, function(data){
					 // alert(data);
				});
			});
		});
</script>
    
<script>
function send_to_promo(emp_id_pk){
	//alert(emp_id_pk);
	$("#emp_id_fk").val(emp_id_pk);
	$("#emp_id_fk1").val(emp_id_pk);
}

</script>
    
<?
//var_dump($_SESSION['user_info']['stake_abbr']); die;
$db=new database();


if($_SESSION['user_info']['stake_abbr'] == 'SECRETARY')
{
							
	$arr=$db->fetch_table("select emp_first_name, emp_second_name, emp_last_name, emp.emp_desig, emp_status, emp_id_pk, emp.gp_id_fk, emp.emp_id_const, effective_date from 
							prd_employee_master as emp INNER JOIN prd_employee_promotion_details as pro ON emp.emp_id_pk= pro.emp_id_fk
							where emp.emp_status in(1) AND 
							emp.zp_id_fk='".substr($_SESSION['location']['district_id'],0,7)."' AND 
							emp.emp_desig not in('1120') AND
							pro.approval_status ='2' order by emp.emp_id_pk DESC");
}


$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

?> 
    

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
    <h1 class="heading">PROMOTION DETAILS </h1>
    <?  
    if(isset($_SESSION['msg']))
	{
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	}
	?>
    <!--    Kalyan Ghosh    20/3/2017    Start-->
    <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
    
	</div>
    <br />
  
</center>

     

<?php
	if(isset($_SESSION['msg']))
{
		echo $_SESSION['msg'];
		unset($_SESSION['school_msg']);
} 
if(!empty($_GET['msg'])){
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
        <div class="col-sm-12">
       <div class="emplist" style="width:98%;">
<div class="school">
<div class="table-responsive">
           
      <table class="table-responsive" style="width:100%;">
<thead>
<tr>
<th>Serial No.</th>
<th>Employee ID</th>
<th>Employee Name</th>
<th>Effective Date</th>
<th>Designation</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<? $cnt=1; if(count($arr) > 0){ foreach($arr as $item){
   
$get_promotional_data = $db->fetch_table("SELECT delete_status,approval_status,approval_status_by_ddo FROM prd_employee_promotion_details WHERE emp_id_fk = '".$item['emp_id_pk']."' AND delete_status = '1'");
 
if($get_promotional_data[0]['delete_status']=='1' && $get_promotional_data[0]['approval_status']=='1'){
	$status="<a data-toggle='modal' data-target='#send' style='cursor:pointer;' ><span class='text-primary' style='font-weight:bold; color:#FA8072;'>SEND TO ".$sent_to_name."</span></a>";
}
else if($get_promotional_data[0]['delete_status']=='1' && $get_promotional_data[0]['approval_status']=='2' || $get_promotional_data[0]['approval_status']=='3') {
	$status='<span style="color:#FA8072;font-weight:bold">Forwarded</span>';
}
else if(($get_promotional_data[0]['delete_status']=='1' && $get_promotional_data[0]['approval_status']=='3') ) {
	$status='<span style="color:#FA8072;font-weight:bold">Forwarded</span>';
}
/*else if($get_promotional_data[0]['delete_status']=='1' && $get_promotional_data[0]['approval_status']=='0') {
	$status='<span style="color:#FF0000;font-weight:bold">REJECTED BY BDO</span>';
}*/
else if( $get_promotional_data[0]['approval_status']=='4' || $get_promotional_data[0]['approval_status']=='5') {
	$status='<span style="color:#008000;font-weight:bold">APPROVED</span>';
}

else
{
 $status = '<span style="color:#FA8072;font-weight:bold">N/A</span>';
}
?>

<tr>
	<td><?= $cnt;?></td>
	<td><?= $item['emp_id_const']?></td>
	<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
	<td><?= date("d/m/Y", strtotime($item['effective_date']));?> </td>
	<td><? if($_SESSION['user_info']['stake_abbr'] != 'SECRETARY'){
			$desig = $db->fetch_table("SELECT description,code FROM prd_dise_code_master WHERE code='".$item['emp_desig']."'");
			echo $desig[0]['description'];
			}
		else if($_SESSION['user_info']['stake_abbr'] == 'SECRETARY'){
			$desig = $db->fetch_table("SELECT designation_name,designation_id FROM zpemp_emp_desig_master WHERE designation_id='".$item['emp_desig']."'");
			echo $desig[0]['designation_name'];
		}?></td>
	<td><?= $status; ?></td>
	<td class="view">
	<?php 
	$query_opacity = $db->fetch_table("SELECT count(*) FROM prd_employee_promotion_details WHERE 
								emp_id_fk='".$item['emp_id_pk']."'  AND approval_status in ('2') AND 
								zp_id_fk = '".substr($_SESSION['location']['district_id'],0,7)."' AND 
								delete_status = '1' AND zp_forward_status = '1' "); 
			//var_dump($query_opacity); die;					
								?>
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-warning view_desig" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#myModal">View</button>
		<?php if($query_opacity[0]['count'] == 0){ ?>
			<button type="button" class="btn btn-success start_salary" id="<?=$item['emp_id_pk']?>" data-bs-toggle="modal" data-bs-target="#for_modal" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>" onclick='send_to_promo(this.id);' >Forward</button>
			<button type="button" class="btn btn-danger stop_salary" id="<?=$item['emp_id_pk']?>" data-bs-toggle="modal" data-bs-target="#rej_modal" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" style="display: block;" onclick='send_to_promo(this.id);' >Reject</button>
		<?php } else if($query_opacity[0]['count'] > 0){ ?>
			<button type="button" class="btn btn-success start_salary" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>" style="display: block; opacity: 0.5; cursor: default;" >Forward</button>
			<button type="button" class="btn btn-danger stop_salary" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" style="display: block; opacity: 0.5; cursor: default;" >Reject</button>
		<?php } ?>
		</div>
	</td>

</tr>

<? $cnt+=1; }
} else { ?>
<tr>
<td colspan="7" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
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


<? require '../../../page/layout/footer.php'; ?>





   


<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>


<!-----------------------------------------------------------------MODAL START ---------------------------------------------------->





<div class="modal fade bs-example-modal-sm" id="for_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Forward This Employee Promotion Details For Approval?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <form action="for_rej_emp_promotion.php" method="post">
      <input name="emp_id_fk" id="emp_id_fk" type="hidden"/>
      <input type="hidden" name="send_id" id="send_id" value='FORWARD' />
        

		<input type="submit" id="send_promotion" name="send_promotion" value="YES" class="btn btn-success" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>
        </form>      
      </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade bs-example-modal-sm" id="rej_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This Employee Promotion Details ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <form action="for_rej_emp_promotion.php" method="post">
      <input name="emp_id_fk" id="emp_id_fk1" type="hidden"/>
		<input type="hidden" name="send_id" id="send_id" value='REJECT' />
        

		<input type="submit" id="send_promotion" name="send_promotion" value="YES" class="btn btn-success" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>
        </form>      
      </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">View Employee Promotion/CAS Details </h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
      <div class="btn-group"> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
        </div>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END ---------------------------------------------------->
