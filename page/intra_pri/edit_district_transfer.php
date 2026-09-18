<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

header("Strict-Transport-Security: max-age=63072000");

session_start();

require '../../includes/config/config.php';
require '../../includes/config/database.config.php';

require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';
$db = new database();
$crypto = new cryptography();
$user=$_SESSION['user_info']['stake_user_code'];
//print_r($_SESSION['user_info']); exit;
$empId = $crypto->decode($_GET['empId'],4);
//print_r($empId); exit;
$Query ="SELECT * from intra_pri_district_transfer WHERE emp_id_const='".$empId."' AND stack_user='".$user."'";
//print($Query);exit;

$TransferInfo = $db->fetch_table($Query);
$TransferInfo = (object) $TransferInfo[0];
//print_r($TransferInfo); exit;


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
 function del(k,l,m,n){
	
	var delete_f=$("#delete_f").val(k);
	var delete_id=$("#delete_id").val(l);
	var emp_id=$("#empId").val(m);
	var applicationId = $("#appId").val(n);
	$('#delet').modal('toggle');
	}

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
<?php require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
		<?php 
		
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
		<div class="row" id="cont">
			<div class="content">
			      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
			        <div class="col-sm-12">
			         <h1 class="heading"><?php echo $TransferInfo->proposal; ?></h1>
			         <div class="container px-5 my-5 form_panal">
			         	<div class="row">
			          <div class="col-sm-12"><label class="form-control"><b>Application ID: </b><?php echo $TransferInfo->application_id; ?></label></div>		
			         <div class="col-sm-6">
			         	<label class="form-control">
			         	<b>Employee Code</b>
			         	<span><?php echo $TransferInfo->emp_id_const; ?></span>
			         </label>
			         </div>	
			         <div class="col-sm-6">
			         	<label class="form-control"><b>Employee Name</b>
			         	<span><?php echo $TransferInfo->emp_first_name.' '.$TransferInfo->emp_second_name.' '.$TransferInfo->emp_last_name; ?></span></label>
			         </div>	
			         <div class="col-sm-12">
			         	<label class="form-control"><b>Application from for outside District Transfer:</b>
			         	<?php 
			         	   $arr_file_42 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$TransferInfo->emp_id_const."' AND application_id='".$TransferInfo->application_id."' AND status = '1' AND flag= 42 ");
                  // print_r($arr_file_42); 
			         	 ?>
			         	<?php  if($arr_file_42[0]['file_name']==''){?>
			         	<span><input type="file" class="form-control upper_case" autocomplete="off" name="app_odt"  id="app_odt"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("42",4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>');" ></span>
			          <?php }else{

			          	//echo $TransferInfo->emp_id_const;exit;
			          	?>
									 <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($TransferInfo->emp_id_const,4)?>&flag=<?=$arr_file_42[0]['flag']?>"><?php echo substr($arr_file_42[0]['file_name'],6); ?></a>
									 <span ><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="del('<?php echo $crypto->encode("42",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>');" >Delete</a></span>			          	
			          <?php } ?>
			          </label>	
			         </div>
			         <div class="col-sm-12">
			         	<label class="form-control"><b>Other Document Upload:</b>
			         	<?php 
			         	   $arr_file_41 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$TransferInfo->emp_id_const."' AND application_id='".$TransferInfo->application_id."' AND status = '1' AND flag= 41 ");
                  // print_r($arr_file_42); 
			         	 ?>
			         	<?php  if($arr_file_41[0]['file_name']==''){?>
			         	<span class="appup41"><input type="file" class="form-control upper_case" autocomplete="off" name="app_od"  id="app_od"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("41",4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>');" ></span>
			          <?php }else{?>
									 <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($TransferInfo->emp_id_const,4)?>&flag=<?=$arr_file_41[0]['flag']?>"><?php echo substr($arr_file_41[0]['file_name'],6); ?></a>
                   <span ><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="del('<?php echo $crypto->encode("41",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>');" >Delete</a></span>									 			          	
			          <?php } ?>
			          </label>
			         </div>	
			         <div class="col-sm-12">
			         	<label class="form-control"><b>Endorsement of head of office/Pradhan/Resolution of authorization (PS)/Recommendation of BDO:</b>
			         	<?php 
			         	   $arr_file_43 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$TransferInfo->emp_id_const."' AND application_id='".$TransferInfo->application_id."' AND status = '1' AND flag= 43 ");
                  // print_r($arr_file_42); 
			         	 ?>			         		
		         	<?php  if($arr_file_43[0]['file_name']==''){?>
			         	<span><input type="file" class="form-control upper_case" autocomplete="off" name="app_hod_auth"  id="app_hod_auth"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("43",4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>');" ></span>
			          <?php }else{?>
									 <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($TransferInfo->emp_id_const,4)?>&flag=<?=$arr_file_43[0]['flag']?>"><?php echo substr($arr_file_43[0]['file_name'],6); ?></a>			
									 <span ><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="del('<?php echo $crypto->encode("43",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($TransferInfo->emp_id_const,4); ?>','<?php echo $crypto->encode($TransferInfo->application_id,4); ?>');" >Delete</a></span>          	
			          <?php } ?>
			         	
			         	</label>
			         </div>	
			         	<div class="col-sm-12">
			         		<center><a href="view_intra_pri_service.php" class="btn btn-sm btn-primary">Back To Inbox</a></center>
			         	</div>		         			         				         
			         </div>
			         </div>

			       </div>
			      </div>
			</div>
		</div>
<?php require '../../page/layout/footer.php'; ?>
</div>


<div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel"><?php echo $TransferInfo->proposal; ?></h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
   
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    <form action="intra_pri_transfer-delete.php" method="post">  
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" id="delete_id" name="delete_id" />
    <input type="hidden" id="delete_f" name="delete_f" />
    <input type="hidden" id="empId" name="empId" />
    <input type="hidden" id="appId" name="appId" />
    <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>
    </form>
          
    </div>
    </div>
    </div>


<script type="text/javascript">
     function file_upload(k,f,app_id,emp_id){
    
    var property = document.getElementById(k).files[0];
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
    
    if(jQuery.inArray(image_extension,['pdf']) == -1){
    alert("Invalid PDF file");
	
	return false;
    }
    
   var form_data = new FormData();
   form_data.append("file",property);
   form_data.append('f',f);
	 form_data.append('app_id',app_id);
	 form_data.append('emp_id',emp_id);
	 form_data.append('transfer_type','<?php echo $TransferInfo->district_level ?>');
	 
	 
	// alert(app_id);
	 
	// return false;
     
    $.ajax({
      url : 'ajax_intra_pri_transfer_edit_file_upload.php',
      type : 'POST',
      data:form_data,
      contentType:false,
      cache:false,
      processData:false,
        success : function(data) {
          console.log(data);
          alert('Uploaded....');
        },
        error:function(){
          alert('Server Error');
        }
      });
	 }
	

</script>