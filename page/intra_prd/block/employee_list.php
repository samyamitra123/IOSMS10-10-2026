<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
//print_r($_SERVER); exit; 
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
$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_pk,emp_system_code,gp_id_fk from prd_employee_master where emp_status in('6','1') AND gp_id_fk='".$cryptoGraph->decode($_REQUEST['id'],4)."' order by emp_first_name");

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
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%;">
<h1 class="heading">EMPLOYEE LIST FOR FINALIZATION</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(!empty($_GET['msg'])){
echo $cryptoGraph->decode($_GET['msg'],4);
echo "<br/>";
echo "<br/>";
}
?>
<div class="msg"></div>
<div class="emplist" >
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Status</th>
<th>View</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
if($item['emp_status']=='6'){
	$status='<span style="color:#660066;font-weight:bold">NOT FINALIZED</span>';
}
else if($item['emp_status']=='1') {
	$status='<span style="color:green;font-weight:bold">FINALIZED</span>';
}
else if($item['emp_status']=='7') {
	$status='<span style="color:RED;font-weight:bold">PROFILE REJECTED</span>';
}
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $status; ?></td>
<td class="view"><a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#myModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
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
			$(".delete").hide();
		}
		$(".mbody").load('<?= $config['base_url'] ?>page/intra_prd/block/ajax_emp_view.php?id='+link, function(responseTxt,statusTxt,xhr){
				  
				 var status= $('#emp_status').val();
					if(status=='1' || status=='7'){
						$(".finz_but").hide();
						$(".rej_but").hide();
						$(".delete_but").hide();
					}
					else{
						$(".finz_but").show();
						$(".rej_but").show();
					    $(".delete_but").show();
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
	$.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_finalize_reject.php?id='+link1+'&flag=finalize', function(data){
				  $('#myModal').modal('toggle');
				  $('#finalize').modal('toggle');
				  $('.msg').html(data);
			  	});
			
			
		});
		$(".reject").click(function(){
			
			var link2=$("#emp_id").val();
	$.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_finalize_reject.php?id='+link2+'&flag=reject', function(data){
				  $('#myModal').modal('hide');
				  $('#reject').modal('show');
				  $('.msg').html(data);
			  	});
			
		});
		
	
		
		
		
		
		$(".delete").click(function(){
			var link4=$("#reason").val();
			
			var link2=$("#emp_id").val();
		
			var a=$("#emp_id_val").val(link2);
		
			
			
//	$.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_finalize_reject.php?id='+link2+'&flag=delete', function(data){
				//  $('#myModal').modal('hide');
				//  $('#delete').modal('show');
			//	  $('.msg').html(data);
			  //	});
			
		});
		
		
		
		
		
		});
	
		
		
		
		
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
        <a class="btn btn-warning finz_but" data-bs-toggle="modal" data-bs-target="#finalize">Finalize</a>
        <a class="btn btn-info rej_but" data-bs-toggle="modal" data-bs-target="#reject">Reject</a>  
         <!--<a class="btn btn-danger rej_but" data-toggle="modal" data-target="#delete">Delete</a> --> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
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
          <h4 class="modal-title" id="myModalLabel">Employee Finalize</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Finalize The Employee Profile ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a type="submit" class="btn btn-success finalize">YES</a> 
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-sm" id="reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Employee Reject</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure You Want To Reject The Employee Profile ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a type="submit" class="btn btn-success reject">YES</a> 
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>






<div class="modal fade bs-example-modal-md" id="delete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
     <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Employee Delete</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     
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
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
      </form>
    </div>
  </div>
</div>



