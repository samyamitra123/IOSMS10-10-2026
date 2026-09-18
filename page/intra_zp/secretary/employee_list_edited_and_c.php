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

$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_unlock_status,emp_id_pk,emp_system_code,emp_form_status,emp_id_const from prd_employee_master 
where emp_status in('1','10','6','3','5','7','8','4') AND zp_id_fk='".$_SESSION['location']['district_id']."' order by emp_status,emp_id_const,emp_first_name");

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
        <div class="col-sm-12">
<h1 class="heading">EMPLOYEE LIST FOR APPROVAL</h1>
<div class="border"></div>
</br>
</br>
<?php

if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
} 
if(!empty($_GET['msg'])){
echo $cryptoGraph->decode($_GET['msg'],4);
echo "<br/>";
echo "<br/>";
}
?>
<div class="msg"></div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Status</th>
<th>View</th>
<th>Unlock Request</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item)
{
	/*if($item['emp_status']=='6')
	{
		$status='<span style="color:#660066;font-weight:bold">NOT APPROVED</span>';
	}*/
	if($item['emp_status']=='6' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">WAITING FOR FORWORD</span>';
	}
	else if($item['emp_status']=='3' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">WAITING FOR APPROVAL</span>';
	}
	else if($item['emp_status']=='5' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">REJECTED FROM SECRETARY</span>';
	}
	else if($item['emp_status']=='7' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">REJECTED FROM AEO</span>';
	}
	/*else if($item['emp_status']=='8' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">WAITING FOR UNLOCK</span>';
	}*/
	else if($item['emp_status']=='1' && $item['emp_form_status']=='5' && $item['emp_unlock_status']=='1') 
	{
		$status='<span style="color:RED;font-weight:bold">WAITING FOR UNLOCK</span>';
	}
	else if($item['emp_status']=='1' && $item['emp_form_status']=='5' && $item['emp_unlock_status']=='2') 
	{
		$status='<span style="color:RED;font-weight:bold">UNLOCK REQUEST FORWARDED TO AEO</p>';
	}
	else if($item['emp_status']=='4' and $item['emp_form_status']=='5')
	{
		$status='<span style="color:#660066;font-weight:bold">WAITING FOR EDIT</span>';
	}
	
	else if($item['emp_status']=='1' and $item['emp_form_status']=='5' && $item['emp_unlock_status']=='0') 
	{
		$status='<span style="color:green;font-weight:bold">APPROVED</span>';
	}
	else{
		$status='<span style="color:RED;font-weight:bold">PROFILE INCOMPLTE</span>';
		}

if($item['emp_status']=='7')
{
		$db=new database();
		$arr_reason=$db->fetch_table("select 
							reason from zpemp_emp_profile_update_status where emp_id_fk='".$item['emp_id_pk']."' order by sl_no desc limit 1");
		$reject_reason= $arr_reason[0]['reason'];
?>
<input type="hidden" id="rsn_view<?php echo $cnt; ?>" value="<?php echo $reject_reason; ?>" />

<? }
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $status; ?></td>
<td class="view">
<?php if(($item['emp_status']=='6' or $item['emp_status']=='1' ) and $item['emp_form_status']=='5'){ ?>
<a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-toggle="modal" data-target="#myModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
<?php }else{  ?>
<a  ><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" style="opacity:0.5;" /></a>
<?php } ?>
</td>
<td><?php if($item['emp_unlock_status'] == '1') {?><a class="btn btn-success"style="margin-left:10%" data-toggle="modal"  onClick="lock_sch();">FORWARD</a>
<a class="btn btn-danger" data-toggle="modal" onClick="unlock_sch();">REJECT</a><?php } ?></td>
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


<script>
		$(document).ready(function(){
		$(".view a").click(function() {	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		var status= $('#emp_status').val();
		if(status=='1'){
			$(".finalize").hide();
			$(".reject").hide();
			//$(".delete").hide();
		}
		$(".mbody").load('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_emp_view.php?id='+link, function(responseTxt,statusTxt,xhr){
				  
				 var status= $('#emp_status').val();
					if(status=='1' || status=='7'){
						$(".finz_but").hide();
						$(".rej_but").hide();
						//$(".delete_but").hide();
					}
					else{
						$(".finz_but").show();
						$(".rej_but").show();
					    //$(".delete_but").show();
					}
				  if(statusTxt=="error"){
			        alert("Error: "+xhr.status+": "+xhr.statusText);
			        $("#error_msg").css("display","block");
			      } else {
			      	$("#error_msg").css("display","none");
			      }
			  	});
		});
		
		$(".finalize").click(function(){
			var link1=$("#emp_id").val();
	$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_finalize_reject.php?id='+link1+'&flag=finalize', function(data){
		//alert(data);
				  $('#myModal').modal('toggle');
				  $('#finalize').modal('toggle');
				  $('.msg').html(data);
			  	});
			
			
		});
		$(".reject").click(function(){
			
			var link2=$("#emp_id").val();
			var reason=$('#reason_reject').val();
			
			if(reason=="")
			{
				alert("Please Insert Valid Reason");
			}
			else
			{
			
				$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_finalize_reject.php?id='+link2+'&flag=reject&reject_reason='+reason, function(data){
				  //alert(data);
				  $('#myModal').modal('hide');
				  $('#reject').modal('show');
				  $('.msg').html(data);
			  	});
			}
			
		});
		
	});
	
		
	function show_reason(k)
	{
		var shw_rsn_id=$('#rsn_view'+k).val();
		//alert(shw_rsn_id);
		$('#rsn_bdy').html(shw_rsn_id);
	}	
	
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-warning finz_but" data-toggle="modal" data-target="#finalize">Forward</a>
        <a class="btn btn-info rej_but" data-toggle="modal" data-target="#reject">Reject</a>  
        <!-- <a class="btn btn-danger rej_but" data-toggle="modal" data-target="#delete">Delete</a>-->  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
        </div>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<div class="modal fade bs-example-modal-sm" id="finalize" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Approve</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Forward The Employee Profile ?</strong></p>
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
     <!-- <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure You Want To delete The Employee Profile ?</strong></p>-->
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

<div class="modal fade" id="reject_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Reject Reason</h4>
      </div>
      <div class="modal-body"> 
      <div class="mbody" > 
      <span style="color:#660066;font-weight:bold">Reason : </span><span id="rsn_bdy"></span>
      </div>
      </div>
      <div class="modal-footer">
       <div class="btn-group">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
         </div>
      </div>
    </div>
  </div>
</div>

<!-------------------------------------------------MODAL START--------------------------------------------------------->
 <form name="unlock_salary" id="unlock_salary" action="unlock_reject_employee.php" method="post">
<div class="modal fade bs-example-modal-sm" id="lock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Unlock Employee Profile</h4>
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Forward Unlock Request To AEO ?</strong></p>
      <input type="hidden" id="school_id_app" name="school_id" />
      <input type="hidden" id="reject" name="value_unlock" value="<?=$cryptoGraph->encode(1,4);?>" />
      <input type="hidden" id="action_app" name="llock" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>" />
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
      
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reject Unlock Request</h4>
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Reject Unlock Request ?</strong></p>
      <input type="hidden" id="school_id_app" name="unlock_salary" />
      <input type="hidden" id="reject" name="reject" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>" />
      <input type="hidden" id="reject" name="value_reject" value="<?=$cryptoGraph->encode(2,4);?>" />
       <!--<label for="message-text" class="control-label" id="reason_lbl">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" draggable="false"></textarea>-->
           
            <input type="hidden" name="flag" value="delete" />
            <input type="hidden" name="emp_id_val" id="emp_id_val"/>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        
        <input type="submit" name="submit" value="YES" id="save" class="btn btn-primary delete">
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>



