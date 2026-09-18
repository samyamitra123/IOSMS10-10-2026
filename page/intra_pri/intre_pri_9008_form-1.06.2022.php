<?php 
//echp 11;die;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");



//ob_start();
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';

$crypto = new cryptography();
?>

<style>
.modal-body{
font-size: 10px;
}

</style>

<meta charset="UTF-8">



<!--<body>-->
<?php

ob_start();

/*
if ( !isset($_SESSION['user_info']['stake_level']) || !isset($_SESSION['user_info']['stake_user_mob']) || !isset($_SESSION['user_info']['flag']) ){
		header('Location: '. $config['base_url'] . "index.php");
		exit;
}
*/

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";



//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------




?>

<script>

  $(function() {
	$( "#date_of_birth,#tch_doe,#date_of_vacancy" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	//minDate:dateToday	 
	});
});
 
function file_upload(k,j){
      //alert(k);
      var property = document.getElementById(k).files[0];
      var image_name = property.name;
      var image_extension = image_name.split('.').pop().toLowerCase();

      if(jQuery.inArray(image_extension,['pdf']) == -1){
        alert("Invalid PDF file");
      }
      
      var form_data = new FormData();
      form_data.append("file",property);
	  form_data.append('flag_id',j);
	  form_data.append('emp_id_const',$('#tch_fname').val());
      form_data.append('application_id',$('#application_id').val());
	  
        $.ajax({
          url : 'ajax_intra_pri_9008_file_upload.php',
          type : 'POST',
          data:form_data,
          contentType:false,
          cache:false,
          processData:false,
          success : function(data1) {
            alert('Document Uploaded Successfully....');
          },
          error:function(){
            alert('Server Error');
          }
          });
    
    }
    
    
</script>
	
	

    <!-- validation -->
<script>
	function validateForm() {
		if($('#place_of_posting').val()==''){
					alert('Please Select Place of Posting.');
					$('#place_of_posting').focus();
					return false;
				}
		else if($('#tch_fname').val()=='' ){
					alert('Please Enter Your FIRST Name.');
					$('#tch_fname').focus();
					return false;
				}
		else if($('#date_of_birth').val()==''){
					alert('Please Enter Date of Birth.');
					$('#date_of_birth').focus();
					return false;
				}
		else if($('#tch_doe').val()==''){
					alert('Please Enter Date of Engagement.');
					$('#tch_doe').focus();
					return false;
				}
		else if($('#drp_engaged').val()==''){
					alert('Please Select Whether Engaged as Casual/Daily Rated Worker/Contractual Worker.');
					$('#drp_engaged').focus();
					return false;
				}
		else if($('#service_in_each_year').val()==''){
					alert('Please Enter No. of Days of Service in Each Year.');
					$('#service_in_each_year').focus();
					return false;
				}
		else if($('#service_in_each_days').val()==''){
					alert('Please Enter No. of Days of Service in Each Year.');
					$('#service_in_each_days').focus();
					return false;
				}
		else if($('#sanctioned_vacant_post').val()==''){
					alert('Please Select Whether engaged against sanctioned vacant post.');
					$('#sanctioned_vacant_post').focus();
					return false;
				}
		else if($('#amount_of_remuneration').val()==''){
					alert('Please Enter Amount of Remuneration P.M.');
					$('#amount_of_remuneration').focus();
					return false;
				}
		else if($('#still_working').val()==''){
					alert('Please Select Whether the incumbent is still working.');
					$('#still_working').focus();
					return false;
				}
		else if($('#source_of_fund').val()==''){
					alert('Please Select Source of Fund.');
					$('#source_of_fund').focus();
					return false;
				}
	}


</script>
<!-- validation -->


	
<style>
.form-horizontal .control-label {
  text-align:left;
	}
</style>
<div class="content">
<?php 

require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
	<!-- <?php 
	//echo 33;die;
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		?> -->
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%; padding-left:2%">
<h1 class="heading">9008-F(P)</h1>
<div class="border"></div>
</br>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" > 

  <?php 
	  if($_SESSION['msg']){
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	  }
  
  $data_9008 = $db->fetch_table(" SELECT * FROM intra_pri_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
  $year_day_9008 = $db->fetch_table(" SELECT * FROM intra_pri_service_each_year_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
  //unset($_SESSION['user_info']['application_id']);
  //var_dump($year_day_9008[0]);
  ?>  
	
<form name="myForm" id="myForm" method="post" class="w3_form_post" action="ajax_intra_pri_9008_submit.php" onsubmit="return validateForm()" enctype="multipart/form-data" >
<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partA",4); ?>" />
      <div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no"  name="app_no" placeholder="Application No." value="<?php if(isset($data_9008[0]['application_id'])){ echo $data_9008[0]['application_id'];} ?>" readonly >
		</div>
	</div>          
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 col-form-label">Place of Posting<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control" name="place_of_posting"  id="place_of_posting" onChange="place_of_posting(this.value)" >
            <option value="">-Please Select-</option>
            <option value="ZP" <?php if($data_9008[0]['posting']== 'ZP') { echo "selected"; } ?>>ZP</option>
            <option value="GP" <?php if($data_9008[0]['posting']== 'GP') { echo "selected"; } ?>>GP</option>
            <option value="PS" <?php if($data_9008[0]['posting']== 'PS') { echo "selected"; } ?>>PS</option>
      </select>
    </div>
    
  </div>  

  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 col-form-label">Employee Name <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" value="<?php if(isset($data_9008[0]['emp_first_name'])){ echo $data_9008[0]['emp_first_name'];} ?>"  />
      <!-- required pattern="[a-zA-Z]+" [a-zA-Z-' ]-->
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ')" value="<?php if(isset($data_9008[0]['emp_second_name'])){ echo $data_9008[0]['emp_second_name'];} ?>" />
      <!-- required pattern="[a-zA-Z]+" -->
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php if(isset($data_9008[0]['emp_last_name'])){ echo $data_9008[0]['emp_last_name'];} ?>"/>
      <!-- required pattern="[a-zA-Z]+" -->
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Birth<span class="star_color">*</span></label>
    <div class="col-sm-3">
	 <input type="text" class="form-control upper_case" id="date_of_birth"  name="date_of_birth" placeholder="DD-MM-YYYY" value="<?php if(isset($data_9008[0]['emp_dob'])){ echo date("d-m-Y",strtotime($data_9008[0]['emp_dob']));} ?>">
    </div>
     <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Engagement<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="tch_doe" name="tch_doe" placeholder="DD-MM-YYYY" value="<?php if(isset($data_9008[0]['date_engagement'])){ echo date("d-m-Y",strtotime($data_9008[0]['date_engagement']));} ?>"/>
     <!-- required -->

    </div>
     </div>
   <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether Engaged as Casual/Daily Rated Worker/Contractual Worker<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select class="form-control" name="drp_engaged" readonly id="drp_engaged">
			<option value="" selected>-Please Select-</option>
			<option value="200" <?php if($data_9008[0]['whether_engagged']== '200') { echo "selected"; } ?> >Casual</option>
			<option value="201" <?php if($data_9008[0]['whether_engagged']== '201') { echo "selected"; } ?> >Daily Rated Worker</option>
			<option value="202" <?php if($data_9008[0]['whether_engagged']== '202') { echo "selected"; } ?> >Contractual Worker</option>
		</select>
    </div>
    <!-- add -->
     <label for="inputPassword3" class="col-sm-3 col-form-label">No. of Days of Service in Each Year<span class="star_color">*</span></label>
    <div class="col-sm-3">
<!-- add fild -->
        
    <table name="each_year[]" id="each_year" width="100%">
		
        <?php if($year_day_9008[0]['service_each_year_9008_pk'] == 0 && $year_day_9008[0]['service_each_year_9008_pk'] == null){ ?>
		<tr id="tr0">
			<td><strong>Year:</strong></td>
            <td><input type="text" size="8" class="custom-input-field" name="service_in_each_year[]" id="service_in_each_year" placeholder="Year"  maxlength="4" /></td>
			<td><strong>Days:</strong></td>
			<td><input type="text" size="8" class="custom-input-field"name="service_in_each_days[]" id="service_in_each_days" placeholder="Days"  maxlength="3" /></td>
            
            <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row0" class="add_row0" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"></td>
        </tr>
		<?php } else{ 
			foreach($year_day_9008 as $value){
		?>	
		<tr id="tr0">
			<td><strong>Year:</strong></td>
			<td><input type="text" size="8" value="<?php echo $value['year']; ?>" readonly /></td>
			<td><strong>Days:</strong></td>
			<td><input type="text" size="8" value="<?php echo $value['days']; ?>" readonly /></td>
		</tr>	
        <?php }
		}		?> 
		
			
    </table> 
   
        <!-- end add fild -->
  </div>
    </div>
 
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether engaged against sanctioned vacant post<span class="star_color">*</span></label>
		<div class="col-sm-3">
            <select class="form-control" name="sanctioned_vacant_post" id="sanctioned_vacant_post" onChange="vacant_post(this.value);" > 
			  <option value="">Please Select</option>
			  <option value="0" <?php if($data_9008[0]['sanctioned_vacant_post']== '0') { echo "selected"; } ?> >NO</option>
			  <option value="1" <?php if($data_9008[0]['sanctioned_vacant_post']== '1') { echo "selected"; } ?> >YES </option>
        
      </select>  			
    	</div>
     
  </div>
  
<div class="row mb-3" id="new_post" <?php if($data_9008[0]['sanctioned_post_name']==''){ ?> style="display:none" <?php }?> >
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name of the Sanctioned Post<span class="star_color">*</span></label>

    <div class="col-sm-3">
      <select type="text" class="form-control upper_case"  name="name_of_post" id="name_of_post" >
         <!-- required -->
      
          <option value="">--- Please Select --- </option>
          <option value="1" <?php if($data_9008[0]['sanctioned_post_name']== '1') { echo "selected"; } ?> >1 </option>
          <option value="2" <?php if($data_9008[0]['sanctioned_post_name']== '2') { echo "selected"; } ?> >2 </option>

       </select> 
    </div>
          <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Occurence of Vacancy<span class="star_color">*</span></label>
          <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="date_of_vacancy"  name="date_of_vacancy" placeholder="Date of vacancy" value="<?php if(isset($data_9008[0]['occurence_vacancy_date'])){ echo date("d-m-Y",strtotime($data_9008[0]['occurence_vacancy_date']));} ?>" >
             <!-- required -->
          </div>
</div>




  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Amount of Remuneration P.M. <span class="star_color">*</span></label>

    <div class="col-sm-3">
      <input type="text" class="form-control"  name="amount_of_remuneration" id="amount_of_remuneration" placeholder="Amount of remuneration P.M" onKeyPress="return keyRestrict(event,'1234567890')" value="<?php if(isset($data_9008[0]['remuneration'])){ echo $data_9008[0]['remuneration'];} ?>"/>
    </div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether the incumbent is still working<span class="star_color">*</span></label>
     
    <div class="col-sm-3">
     <select class="form-control" name="still_working"  id="still_working">
            <option value="">-Please Select-</option>
            <option value="500" <?php if($data_9008[0]['still_working']== '500') { echo "selected"; } ?> >YES</option>
            <option value="600" <?php if($data_9008[0]['still_working']== '600') { echo "selected"; } ?> >NO</option>
            </select> 
    </div>

  </div>
 <div class="row mb-3">
  
  <label for="inputPassword3" class="col-sm-3 col-form-label">Source of Fund<span class="star_color">*</span></label>
    <div class="col-sm-3">     
        <select class="form-control" name="source_of_fund" id="source_of_fund" onChange="source_of_fund_note(this.value);">
			  <option value="">Please Select</option>
			  <option value="0" <?php if($data_9008[0]['source_of_fund']== '0') { echo "selected"; } ?> >Own Source(OSR)</option>
			  <option value="1" <?php if($data_9008[0]['source_of_fund']== '1') { echo "selected"; } ?> >Project</option>
		</select>
	</div>
 
 </div> 
 
 <div class="row mb-3" id="note" <?php if($data_9008[0]['source_of_fund']!='1'){ ?> style="display:none" <?php }?> >
    <label for="inputPassword3" class="col-sm-3 col-form-label">NOTE<span class="star_color">*</span></label>
    <div class="col-sm-3"> 
      <textarea class="form-control"  name="noted" id="noted" placeholder="Note" ><?php if(isset($data_9008[0]['note'])){ echo $data_9008[0]['note'];} ?></textarea>
    </div>
 </div>

 <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
    <div class="col-sm-offset-5 col-sm-7">

      <?php
      
    if($data_9008=='' && $data_9008 == null){
      
      ?>
	  <button class="btn btn-info" type="submit" id="submit" name="submit"> SAVE & CONTINUE</button>
	  <?php
	  }
	  else
	  {
		?>
               
    </div>
  </div>

<div class="border"></div>
	</br>
	<h5 class="heading"> Document to be Uploaded</h5>
	</br>
	<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partB",4); ?>" /> 
	<input type="hidden" name="9008_id" id="9008_id" value="<?= $crypto->encode($data_9008[0]['9008_id_pk'],4); ?>" /> 
	<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($data_9008[0]['application_id'],4); ?>" /> 
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Engagement Letter<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_30 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 30 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_30[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="engasment_letter"  id="engasment_letter" value="" onchange="return file_upload(this.id,30);">
	  <?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_30[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("30",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
  </div>
  <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  
  
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Resolution (copy)<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_31 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 31 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_31[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="resolution"  id="resolution" value="" onchange="return file_upload(this.id,31);" >
	   <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_31[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("31",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
    
  
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Year Wise Attandance in the From of Certificate<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_32 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 32 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_32[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="attandance"  id="attandance" value="" onchange="return file_upload(this.id,32);" >
	  <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_32[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
   <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("32",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Proof of Engagement <span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_33 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 33 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_33[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="poof_of_engagement"  id="poof_of_engagement" value="" onchange="return file_upload(this.id,33);" >
	  <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_33[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("33",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
<div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Recommendation of DM/ADM(P)/AEO  <span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_34 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 34 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_34[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="recommendation"  id="recommendation" value="" onchange="return file_upload(this.id,34);" >
	   <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_34[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("34",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  	
 <div class="row mb-3">
  <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Observation <span class="star_color">*</span></label>
    <div class="col-sm-3">
     <textarea type="text" class="form-control " name="observation" id="observation" placeholder="Observation" rows="5" cols="40" ><?php if(isset($data_9008[0]['observation'])){ echo $data_9008[0]['observation'];} ?></textarea>
    </div>
  </div> 
  
	<!--<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
		<a href="intre_pri_overage_condonation_pdf.php" id="submit" name="submit" class="btn btn-info" > GENERATE ORDER </a>
			<a type="button" id="submit" name="submit" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" > SUBMIT & PREVIEW </a>
		</div>
	</div>-->
	
	<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
		<?php if($data_9008[0]['observation'] == '' ){ ?>	
			<button class="btn btn-info" type="submit" id="submit" name="submit" >SAVE & CONTINUE </button>
		<?php }
		else if($data_9008[0]['observation'] != '' ){ ?>
			<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" onclick="return sub_prev_9008(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
		<?php }	?>	
		</div>
	</div>
	<?php
 }

?>
</form>

</div> 
</div> 
</div>
    </div>
    </div>
     

<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>



<!-- vacant post -->
    <script>
    
    function vacant_post(k)
    {
      if(k==1)
      {
        $('#new_post').show();
      }
      else
      {
        $('#new_post').hide();
      }
    }
    
    
    function source_of_fund_note(k)
    {
      if(k==1)
      {
        $('#note').show();
      }
      else
      {
        $('#note').hide();
      }
    }
	
	function del(k,l){
		var delete_f=$("#delete_f").val(k);
		var delete_id=$("#delete_id").val(l);
		
		$('#delet').modal('toggle');
	};
    
</script>  
		
		
<!-- add row -->
<script >
   function add_row(id)
    {  
    var id_length=id.length;
    var id=id.substr(7,id_length);
    $("#each_year").each(function(){ 
    var r = $('#each_year tr').length;
    if('tr'+id==$('#each_year tr:last').attr('id'))
    { 
		var tds = '<tr id="tr'+r+'">';
		tds+='<td><strong>Year:</strong></td>';
		tds+='<td><input type="text" size="8" class="custom-input-field" name="service_in_each_year[]" id="service_in_each_year"placeholder="Year" maxlength="4" /></td>';
		tds+='<td><strong>Days:</strong></td>';
		tds+='<td><input type="text" size="8" class="custom-input-field" name="service_in_each_days[]" id="service_in_each_days" placeholder="Days"   maxlength="3" /></td>';
		tds+=' <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row'+r+'" class="add_row3" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"><img onclick="return remove_row(this.id)" style="cursor:pointer;" id="remove_row'+r+'" class="remove_row3" title="Click To Remove" src="../../themes/default/image/remove_row_image.png" width="15"></td>';
		tds += '</tr>';
		
		if($('tbody', this).length > 0)
		{
			$('#each_year tr').last().after(tds);
			}  
		}
    })
    } 
	
    function remove_row(id)
    { 
		var rowCount = $('#each_year tr').length;
		var tr_id=id.substr(10);
		if(rowCount>1)
		{
			$('#tr'+tr_id).remove();
		}
    
    }
	
	
	
function sub_prev_9008(v){
	var id_9008 = $("#9008_id").val();
	var app_no = $("#app_no").val();
	
 if($('#observation').val()=='' ){
		alert('Please Enter Observation.');
		$('#observation').focus();
		return false;
	}
				
				
  $.ajax({
      url : 'ajax_intra_pri_9008_submit_last.php',
      type : 'POST',
      data : {
				"id_9008" : id_9008,
				"app_no" : app_no,
				"fi_sub" : v
				},
        success : function(response) { //alert(v);
			//var result1 = $.parseJSON(response);
			
				if(v =='fi_sub'){
					//alert(response);
				window.location.href = "intre_pri_9008_form.php";

			}
			else{
				$(".mbody").html(response);
			}
			
        },
        error:function(){
          alert('Server Error');
        }
      });
}
    
    </script>
    
 <!-- end add row -->


<div class="modal fade" id="gpprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 188%; margin-left: -44%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">9008-F(P)</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="fi_sub" onclick="return sub_prev_9008(this.id)" > PROPOSAL SAVED </button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 200%; margin-left: -58%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">9008-F(P)</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
		<form action="ajax_intra_pri_9008_submit.php" method="post">
		<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>
    </div>
    <div class="modal-footer">
    
		<div class="btn-group">
		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
		<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($data_9008[0]['application_id'],4); ?>" /> 
		<input type="hidden" id="delete_id" name="delete_id" />
		<input type="hidden" id="delete_f" name="delete_f" />
		<input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
		<button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
		</div>
    </div>
    </div>
    
    </form>
    </div>
    </div>







 