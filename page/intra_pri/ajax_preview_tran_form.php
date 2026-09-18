	<?php
	
	session_start();
	
	/*
	echo "<pre>";
	print_r($_SESSION);
	echo "<pre>"; die;*/
	error_reporting(0);

	
	header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	
	
	error_reporting(0);
	//ob_start();
	require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
	
	$crypto=new cryptography();
	//var_dump($_SESSION);
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
	
	//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------
	
	//Page variables
	$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
	
	
	
	
	
	
	function code_gp($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
	return $arr[0]['gp_name'];																	
	
	}
	
	function code_block($val)
	{
	
	
	$db=new database();
	
	$arr =$db->fetch_table("SELECT block_name
	FROM prd_location_master_block as b
	inner join prd_location_master_gp as gp 
	on gp.block_id_fk=b.block_id_pk
	WHERE gp.gp_id_pk='".$val."'");
	return $arr[0]['block_name'];																	
	
	}
	function code_ps($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
	return $arr[0]['ps_name'];																	
	
	}
	
	function code_district($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
	return $arr[0]['district_name'];																	
	
	}
	
	
	?>
	<style>
	.form-horizontal .control-label {
	text-align:left;
	}
	</style>
	<script>
	
	$(function() {
	$( "#notice_date" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	//minDate:dateToday	 
	});
	});
	
	</script>
	
	
	<?php //require 'common_back_btns_intra_pri.php'; ?>
	
	<?php
	//echo 33;die;
	$db = new database();
	
	
	?>
	
	
	<!-- Latest compiled and minified JavaScript -->
	<div class="row" id="cont">
	<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
	<div class="col-sm-12" style="width:98%; padding-left:2%">
	<h1 class="heading">Intra District Transfer</h1>
	<div class="border"></div>
	</br>
	<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
	<div id="form_show">
	
	<?php 
	
	
	
	
	$data = $db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE 
	emp_id_const = '".$_SESSION['emp_id_const']."'");
	//var_dump($emp_id_detail_ocon);
	
	 $gp_id_fk=$data[0]['pre_gp_id_fk'];
    $ps_id_fk=$data[0]['pre_ps_id_fk'];
    $zp_id_fk=$data[0]['pre_zp_id_fk'];
	$stake=$data[0]['stake_lvl_select'];
	$application_id=$data[0]['application_id'];
	$emp_id_const=$_SESSION['emp_id_const'];
	$status=$data[0]['status'];
	/*$ps_code=$data[0]['tran_ps_code'];
	 $block_id_fk=$data[0]['tran_block_id_fk']; 
	$gp_code=$data[0]['tran_gp_code'];*/
	
	 $id =$db->fetch_table("SELECT district_name FROM prd_location_master_district as d
    
    inner join prd_location_master_block as b on b.district_id_fk=d.district_id_pk
    inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk 
    WHERE gp.gp_id_pk='".$gp_id_fk."' ");
	
	
	
	?>
    
    
    
    
    
	
	
	  
	
	<div class="row mb-3">
	<label for="inputEmail3" class="col-sm-3 col-form-label">Employee ID:<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="tch_emp_id"  disabled name="tch_emp_id" placeholder="ID Search IOSMS" onKeyup="return empplyee_details_fetch(this.value,'<?php echo $crypto->encode(2,4); ?>');" maxlength="12" value="<?php if(isset($data[0]['emp_id_const'])){ echo $data[0]['emp_id_const'];} ?>" >
	</div>
	</div>
    
    
    <?php if($application_id!=''){ ?>
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">APPLICATION ID: </label>
    <div class="col-sm-3">
    <p class="text-muted" style="font-size:18px;font-weight:700;font-family: "MS Serif", "New York", serif;"><?php echo $application_id; ?></p>
    </div>
    </div>
    
    <?php  }?>
	
	<div class="row mb-3">
	<label for="inputEmail3" class="col-sm-3 control-label">Proposal Name<span class="star_color">*</span></label>
	<div class="col-sm-7">
	<input type="text" class="form-control upper_case" id="proposal" disabled name="proposal"  readonly="readonly" placeholder="proposal" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"  autocomplete="off" value="<?= $data[0]['proposal']; ?>">
	</div></div>
	
	<div id="number_msg" style="color: green;"></div>
	<div class="row mb-3">
	<label for="inputEmail3" class="col-sm-3 col-form-label">Employee Name<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="tch_fname"  disabled name="tch_fname" placeholder="First Name" value="<?php if(isset($data[0]['emp_first_name'])){ echo $data[0]['emp_first_name'];} ?>" readonly>
	</div>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="tch_mname" disabled name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"readonly value="<?php if(isset($data[0]['emp_second_name'])){ echo $data[0]['emp_second_name'];} ?>">
	</div>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="tch_lname" disabled name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" readonly value="<?php if(isset($data[0]['emp_last_name'])){ echo $data[0]['emp_last_name'];} ?>">
	</div>
	</div>
	<div class="row mb-3">
	<label for="inputPassword3" class="col-sm-3 col-form-label">Date Of Birth<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control" id="tch_dob" name="tch_dob" value="<?php if(isset($data[0]['tch_dob'])){ echo date("d-m-Y",strtotime($data[0]['tch_dob']));} ?>" placeholder="DD-MM-YYYY" readonly />
	</div>
	<label for="inputPassword3" class="col-sm-3 control-label">SEX<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<?php
	$db = new database();
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
	?>
	<select class="form-control"  readonly name="sex" id="sex">
	<option value="">-Please Select-</option>
	<?php foreach($arr as $key){ $key['code']. '<br />';?>
	<option value="<?= $key['code']; ?>" <?php if($data[0]['sex']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
	<?php } ?>
	</select>
	</div>
	</div>
	<div class="row mb-3">
	<label for="inputEmail3" class="col-sm-3 control-label" >Designation<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<?php
	$db = new database();
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
	?>
	<select class="form-control" name="desig" id="desig" readonly="readonly" onchange="showAcadmic(this.value)">
	<option value="">-Please Select-</option>
	<?php 
	if(strlen($designation) == 1) 
	$designation = '110'.$designation;
	else if (strlen($designation) == 2)
	$designation = '11'.$designation;
	
	foreach($arr as $key){
	$key['code']. '<br />';
	?>
	<option value="<?= $key['code']; ?>" 
	<?php 
	if($data[0]['desig']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
	<? } ?>
	</select>
	
	</div>
	
	</div>
	
	
	<div class="row mb-3" id="gp_description_div" style="display:none;">
	
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>
	<div class="col-sm-3">
	
	<input type="hidden" class="form-control upper_case" id="gp_id_fk" disabled name="gp_id_fk"  value="<?php echo $gp_id_fk; ?>" style="display:none">
	<input type="text" class="form-control upper_case" id="gp_description" readonly name="gp_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>   
	<div class="row mb-3" id="block_description_div" style="display:none;">
	<label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>
	<div class="col-sm-3">
	
	<input type="text" class="form-control upper_case" id="block_description" readonly name="block_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>              
	
	<?php if($data[0]['pre_gp_id_fk']!='0' && $data[0]['from_status']=='1'){?>
	
	<div class="row mb-3">
	
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>
	<div class="col-sm-3">
	
	<input type="hidden" class="form-control upper_case" id="gp_id_fk" disabled name="gp_id_fk"  value="<?php echo $gp_id_fk;?>">
	<input type="text" class="form-control upper_case" id="gp_description" disabled readonly="readonly" name="gp_description" placeholder="" autocomplete="off"  value="<?php echo  code_gp($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>
	<div class="row mb-3" id="block_description_div">
	
	<label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>
	<div class="col-sm-3">
	
	<input type="text" class="form-control upper_case" id="block_description" disabled readonly name="block_description" placeholder="" autocomplete="off"  value="<?php echo  code_block($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>  
	
	
	
	<?php }?>
	
	
	<div class="row mb-3">
	<label for="inputEmail3" class="col-sm-3 control-label">District<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="district" disabled readonly="readonly" name="district" placeholder="District" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php echo  $id[0]['district_name'];?>">
	</div></div>
	
	
	<div class="row mb-3" id="ps_description_div" style="display:none;">
	
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="hidden" class="form-control upper_case" id="ps_id_fk"  disabled name="ps_id_fk"  value="<?php echo $ps_id_fk;?>" style="display:none">
	<input type="text" class="form-control upper_case" id="ps_description" readonly name="ps_description" placeholder="Last Pay Drawn" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>
	
	<?php if($data[0]['pre_ps_id_fk']!='0' && $data[0]['from_status']=='1'){?>
	<div class="row mb-3">
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="hidden" class="form-control upper_case" id="ps_id_fk" disabled name="ps_id_fk"  value="<?php echo $ps_id_fk;?>">
	<input type="text" class="form-control upper_case" id="ps_description" disabled readonly name="ps_description" placeholder="" autocomplete="off"  value="<?php echo  code_ps($ps_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
	
	</div>
	</div>
	
	<?php }?>
	
	
	
	<div class="row mb-3" id="zp_description_div" style="display:none;">
	
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="hidden" class="form-control upper_case" id="zp_id_fk" disabled  name="zp_id_fk"  value="<?php echo $zp_id_fk;?>" style="display:none">
	<input type="text" class="form-control upper_case" id="zp_description" disabled readonly name="zp_description" placeholder="Last Pay Drawn" autocomplete="off"  value="<?php echo  code_district($zp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>
	
	
	<?php if($data[0]['pre_zp_id_fk']!='0' && $data[0]['from_status']=='1'){?>
	<div class="row mb-3">
	<label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="hidden" class="form-control upper_case" id="zp_id_fk" disabled name="zp_id_fk"  value="<?php echo $zp_id_fk;?>">
	<input type="text" class="form-control upper_case" id="zp_description" readonly disabled name="zp_description" placeholder="" autocomplete="off"  value="<?php echo  code_district($zp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
	</div>
	</div>
	
	<?php }?>
	
	
	
	
	<div class="row mb-3">
	<label for="inputPassword3" class="col-sm-3 col-form-label">First Appointment Order No.<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="emp_first_memo_no"  disabled name="emp_first_memo_no" placeholder="First Appointment Order No." readonly value="<?php if(isset($data[0]['emp_first_memo_no'])){ echo $data[0]['emp_first_memo_no'];} ?>">
	</div>
	<label for="inputPassword3" class="col-sm-3 col-form-label">Date of Joining in the First Posting<span class="star_color">*</span></label>
	
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case"  name="first_join_date" disabled id="first_join_date" placeholder="Date of Joining in the First Posting" readonly value="<?php if(isset($data[0]['first_join_date'])){ echo date("d-m-Y",strtotime($data[0]['first_join_date']));} ?>">
	</div>
	</div>
	
	<div class="row mb-3">
	<label for="inputPassword3" class="col-sm-3 col-form-label">Name of Appointing Authority <span class="star_color">*</span></label>
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="appoinment_authority" disabled  name="appoinment_authority" placeholder="Name of Appointing Authority" value="<?php if(isset($data[0]['appoinment_authority'])){ echo $data[0]['appoinment_authority'];} ?>">
	</div>
	<label for="inputPassword3" class="col-sm-3 col-form-label">Date of Notification of Employement Notice/ Employement Exchange Call Letter Date<span class="star_color">*</span></label>
	
	<div class="col-sm-3">
	<input type="text" class="form-control upper_case" id="notice_date"  name="notice_date" disabled placeholder="DD-MM-YYYY" value="<?php if(isset($data[0]['notice_date'])){ echo date("d-m-Y",strtotime($data[0]['notice_date']));} ?>">
	</div>
	</div>
	

	
    
    
    
    <div class="row mb-3" >
        
         <label for="inputPassword3" class="col-sm-3 control-label">Transfer To<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <?php 
										$transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
																		FROM prd_dise_code_master WHERE code IN ('555','556') AND length(code)=3
																		");
                                     ?>
        	<select name="stake_lvl_select" style="width:100%;" id="stake_lvl_select" disabled class="form-control">
                                        <option value="">---SELECT LEVEL---</option>
                                        <?php foreach($transfer_level as $key)
                                        {
											
											?>
												<option value="<?=$key['code']?>" <?php if($stake==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
                                        
										<?php } ?>
                                    </select>
        </div>
     </div>
    
    
    
    
     <?php if($stake=='557')
 {
	 
	// $display= "style='display:yes;'";
	 $display1= "style='display:none;'";
	  $display2= "style='display:none;'";
	 $district_id=$data[0]['district_id_fk']; 
	  $display1= "style='display:none;'";
 }
 else if($stake=='556')
 {
	 $display1= "style='display:yes;'";
	 $display2= "style='display:none;'";
	 $ps_code=$data[0]['tran_ps_code'];
	 $district_id=$data[0]['district_id_fk'];
	 //$display= "style='display:none;'";
 }
 else if($stake=='555')
 {
	 $display2= "style='display:yes;'";
	// $display= "style='display:none;'";
	 $display1= "style='display:none;'";
	 $block_id_fk=$data[0]['tran_block_id_fk'];
	 $district_id=$data[0]['district_id_fk'];
	 $gp_code= $data[0]['tran_gp_code'];
 }
 else
 {
	$display3= "style='display:none;'";
	$display2= "style='display:none;'";
	//$display= "style='display:none;'";
	$display1= "style='display:none;'";

 }
 ?>
    
     <div class="row mb-3"  >

  
  
  <label for="inputPassword3"  class="col-sm-3 control-label" id="ps_show" <?php echo $display1;?>>PS<span class="star_color">*</span></label>
  
  <div class="col-sm-3">
  
   <select class="form-control" id="ps_code" name="ps_code"  disabled <?php echo $display1;?> onChange="gps(this.value);">
   
  <?php $arr = $db->fetch_table("select ps_id_pk,ps_name,ps_code,district_id_fk from prd_location_master_panchayat_samiti where ps_id_pk='".$ps_code."'");?>
                            	<option value="">-Please Select-</option>
                                <?php foreach($arr as $key){ $key['ps_id_pk']. '<br />'; ?>
                               <option value="<?= $crypto->encode($key['ps_id_pk'],4); ?>" <?php if($ps_code==$key['ps_id_pk']){ echo "selected";}?> ><?= $key['ps_name']; ?></option>
             
                               <?php } ?>
              </select>
                            </div>
                            </div>  
    
    
    <div class="row mb-3"  >
    
  
    <label for="inputPassword3"  class="col-sm-3 control-label"  id="block_show" <?php echo $display2;?>>BLOCK<span class="star_color">*</span></label>
    
    <div class="col-sm-3">
    
   <?php  $arr=$db->fetch_table("select block_id_pk,block_name,block_code from prd_location_master_block where block_id_pk='".$block_id_fk."'");
   
  
   
   
?>

 
    
    <select class="form-control" id="block_id" name="block_id" disabled <?php echo $display2;?> onchange="show_gp(this.value)">
    <option value="">-Please Select-</option>
<?php foreach($arr as $key){ $key['block_id_pk']. '<br />'; ?>
<option value="<?= $crypto->encode($key['block_id_pk'],4); ?>"<?php if($block_id_fk==$key['block_id_pk']){ echo "selected";}?> ><?= $key['block_name']; ?></option>
<?php } ?>
    </select>
    </div>  
    </div>
                
                
    <div class="row mb-3"  >
    
    <label for="inputPassword3"  id="gp_show" class="col-sm-3 control-label  " <?php echo $display2;?>>GP<span class="star_color">*</span></label>
    
    <div class="col-sm-3">
    
    
  <?php   $arr=$db->fetch_table("select gp_id_pk,gp_name,gp_code from prd_location_master_gp where block_id_fk='".$block_id_fk."'"); ?>

    
    <select class="form-control" id="gp_code" name="gp_code" disabled <?php echo $display2;?> onChange="gps(this.value);">
    <option value="">-Please Select-</option>
    <?php foreach($arr as $key){ $key['gp_code']. '<br />'; ?>
<option value="<?=$crypto->encode($key['gp_code'],4); ?>" <?php if($gp_code==$key['gp_code']){ echo "selected";}?>><?= $key['gp_name']; ?></option>
<?php } ?>
    </select>
    </div>    
    </div>
 
 
    
	
	
	
	<div class="row mb-3" id="note"style="display:none;">
	<label for="inputPassword3" class="col-sm-3 col-form-label">Note<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<textarea type="text" class="form-control upper_case" disabled name="note" id="note" placeholder="note" rows="5" cols="40" ></textarea>
	</div>
	<label for="inputPassword3" class="col-sm-3 col-form-label">Do you want to submit any supporting document?<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<select class="form-control" name="all_supporting_documents"  disabled id="all_supporting_documents" onChange="supporting_documents(this.value);">
	<option value="">Please Select</option>
	<option value="0" <?php if($Court_Case=='') { echo "selected"; } ?>>NO</option>
	<option value="1" <?php if($Court_Case!='') { echo "selected"; } ?>>YES</option>
	</select>
	</div>
	</div>
    
    
    <?php 
	
	
	
	if($data[0]['note']!=''){		
		?>
    
    
    
    <div class="row mb-3" id="note">
	<label for="inputPassword3" class="col-sm-3 col-form-label">Note<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<textarea type="text" class="form-control upper_case" disabled name="note" id="note" placeholder="note" rows="5" cols="40" ><?php echo $data[0]['note'];?></textarea>
	</div>
    
    
	<label for="inputPassword3" class="col-sm-3 col-form-label">Do you want to submit any supporting document?<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<select class="form-control" name="all_supporting_documents" disabled id="all_supporting_documents" onChange="supporting_documents(this.value);">
	<option value="">Please Select</option>
	<option value="0" <?php if($data[0]['all_supporting_documents']=='0') { echo "selected"; } ?>>NO</option>
	<option value="1" <?php if($data[0]['all_supporting_documents']=='1') { echo "selected"; } ?>>YES</option>
	</select>
	</div>
	</div>
    
    <?php }?>
    

    
<!--    <div class="row mb-3" style="display:none;" id="uplord_pdf">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Requisite Document <span class="star_color">*</span></label>
    <div class="col-sm-2">
    
  
    <input type="file" class="form-control upper_case" autocomplete="off" name="file_tran"  id="file_tran"  >
    
   
    
    </div>
    
    </div>-->
    
    
    
    
      
    <?php if($data[0]['all_supporting_documents']=='1')
    {
		$db=new database();
		
		$arr_file_1 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 40 and application_id='".$application_id."'"); ?>
	
	
	
	
	
	<div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Required Document <span class="star_color">*</span></label>
    <div class="col-sm-2">
    
    
  <?php if(count($arr_file_1)==1){?>
   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_1[0]['flag']?>"><?php echo substr($arr_file_1[0]['file_name'],6); ?></a>
    <?php }?>
    
    
    
    
    </div>
    
    </div>
    
    
    <?php }?>
 <?php if($status!='1'){?>
  <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
    <div class="col-sm-offset-5 col-sm-7">
    <a id="edit1"  onclick="edit('<?php echo $crypto->encode("intra_district",4); ?>');" class="btn btn-warning">EDIT & UPDATE</a>
    
     <a type="button" id="submit6" name="submit6"  class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalize">FINAL SUBMIT</a>
    </div>
    </div>

	</div>
    <?php }?>
	
	
