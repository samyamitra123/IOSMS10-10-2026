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
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

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
$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk,emp_id_const from prd_employee_master where emp_status in('3') AND ps_id_fk='".$_SESSION['location']['ps_id']."' order by emp_first_name");

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
        <div class="col-sm-12" style=" width:98%;">
<h1 class="heading">EMPLOYEE LIST FOR UNLOCK</h1>
<div class="border"></div>
</br>
</br>
 <?
 
	if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];

	 unset($_SESSION['msg']);
	
}
?>  

<div class="msg"></div>
<div class="emplist" >
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee ID</th>
<th>Employee Name</th>
<th>Designation</th>
<th>View</th>
<th>Action</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item)
{
	
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td class="view"><a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#myModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
<td><a class="btn btn-success"style="margin-left:10%" data-bs-toggle="modal"  onClick="lock_sch();">UNLOCK</a>
<a class="btn btn-danger" data-bs-toggle="modal" onClick="unlock_sch();">REJECT</a></td>

</tr>

<? $cnt+=1; }} else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
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


<script>
		$(document).ready(function(){
		$(".view a").click(function() {	
		var link = $(this).attr('id');
		$(".mbody").load('<?= $config['base_url'] ?>page/intra_ps/eo/ajax_emp_view.php?id='+link, function(responseTxt,statusTxt,xhr){
				  
				 $(".mbody").html(data);
			  	});
		});
		
		
	});
	
		
		
    function lock_sch(k)
{

	//alert(k);
	$('#school_id_app').val(k);
	$('#lock').modal('show');
	//$("#approve").modal();

}

function unlock_sch(k)
{

	$('#unlock_salary').val(k);
	$('#unlock').modal('show');

}


   
		
		
		
</script>
<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
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

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<!--<div class="modal fade bs-example-modal-sm" id="finalize" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Approve</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Approve The Employee Profile ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success finalize">YES</a> 
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-sm" id="reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Reject</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure You Want To Reject The Employee Profile ?</strong></p>
             <label for="message-text" class="control-label" id="reason_lbl">Reason<span class="star_color">*</span>:</label>
            <textarea class="form-control" id="reason_reject" name="reason_reject" draggable="false" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"></textarea>

      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success reject">YES</a> 
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>






<div class="modal fade bs-example-modal-md" id="delete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Delete</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure You Want To delete The Employee Profile ?</strong></p>
       <form action="ajax_finalize_reject.php" method="post">
       <label for="message-text" class="control-label" id="reason_lbl">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" draggable="false"></textarea>
            <input type="hidden" name="flag" value="delete"  />
            <input type="hidden" name="emp_id_val" id="emp_id_val"   />
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        
        <input type="submit" name="submit" value="YES" id="save" class="btn btn-primary delete">
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
      </form>
    </div>
  </div>
</div>
-->
 <!-------------------------------------------------MODAL START--------------------------------------------------------->
 <form name="unlock_salary" id="unlock_salary" action="unlock_reject_employee.php" method="post">
<div class="modal fade bs-example-modal-sm" id="lock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Unlock Employee Profile</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Unlock Employee Profile ?</strong></p>
      <input type="hidden" id="school_id_app" name="school_id" />
      <input type="hidden" id="reject" name="value_unlock" value="<?=$cryptoGraph->encode(1,4);?>" />
      <input type="hidden" id="action_app" name="llock" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>" />
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
 
 <form name="school_app" id="school_app" action="unlock_reject_employee.php" method="post">
<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel">Reject Unlock Request</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Reject Unlock Request ?</strong></p>
      <input type="hidden" id="school_id_app" name="unlock_salary" />
      <input type="hidden" id="reject" name="reject" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>" />
      <input type="hidden" id="reject" name="value_reject" value="<?=$cryptoGraph->encode(2,4);?>" />
       <label for="message-text" class="control-label" id="reason_lbl">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" draggable="false"></textarea>
           
            <input type="hidden" name="flag" value="delete" />
            <input type="hidden" name="emp_id_val" id="emp_id_val"/>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        
        <input type="submit" name="submit" value="YES" id="save" class="btn btn-primary delete">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>
 
 
 
 
 
 