<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$crypto = new cryptography();

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>IFMS details submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

$yemo=date("Y").date("m");
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];


$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
		AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."'
	
");
if(count($find_bill)>0){
	$bill_no=$find_bill[0]['bill_no'];
	$bill_date=$find_bill[0]['bill_entry_time'];
	}

?>
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
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
<div class="col-sm-12">
<h1 class="heading">Salary Bill Generation</h1>
<div class="border"></div>
</br>
<?php 
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
echo "<br/>";
}
if($error_message){
echo $error_msg;
echo "<br/>";
}
?>
<?php
				/*echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";*/
			?>
    <script>
       
		
        </script> 
            
<script>
$(document).ready(function() {
	
	if($("#ifms_check").val()=='1'){
				$('#ifms_details').show();
				$('#ifms_up_date').show();
				$('#ifms_ref').show();
				$('#ifms_sub').show();
			}
	$('#submit-text').click(function(){
		
			if($('#bill').val()=='')
			{
				alert('Please enter salary bill no.');
				$('#bill').focus();
				return false;	
			}
			else if($('#bill_date').val()=='')
			{
				alert('Please enter bill date.');
				$('#bill_date').focus();
				return false;	
			}
			else
			{
				//alert(11);
				//$('#ifms_form').show();
				$.post('<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/ajax_links.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				//alert(data);
				
				});
				
			}
			//event.preventDefault();
			return false;
	});
	
	//$('#submit_ifms').click(function(){
//		
//			if($('#ifms_upload_date').val()=='')
//			{
//				alert('Please choose IFMS Upload Date.');
//				$('#ifms_upload_date').focus();
//				return false;	
//			}
//			else if($('#ifms_ref_no').val()=='')
//			{
//				alert('Please enter IFMS Reference No.');
//				$('#ifms_ref_no').focus();
//				return false;	
//			}
//			else
//			{
//				
//				$.post('<?php //echo $config['base_url'] ?>page/intra_prd/block/text_file/ajax_ifms.php',$(this).closest("form").serialize(), function(data){
//				
//				});
//				
//			}
//			//event.preventDefault();
//			return false;
//	});
	$('#bill_report_month , #bill_report_year').change(function(){
		//alert(111);
				$('#bill').val('');
				$('#bill_date').val('');
				$('.ajax_text_link').html('');
				var year = $('#bill_report_year').val();
				var month = $('#bill_report_month').val();
				 $.ajax({
				url:'<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/find_bill.php',
				dataType:"json",
            	type:'GET',
				data:{
					ye:year,
					mo:month
				},
				success:function(data){
					//alert(data.bill);
					$("#bill").val(data.bill);
					$("#bill_date").val(data.bill_date);
				}
			});
		});
		
});
</script>

<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" > 
<?
if(isset($_SESSION['head_msg'])){
	echo $_SESSION['head_msg'];
	unset($_SESSION['head_msg']);
}
?>
<form class="form-horizontal" id="gp_form" name="gp_form" method="post" >
<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill For The Month<span class="star_color">*</span></label>
    <div class="col-sm-6">
     <div class="col-sm-3">
     <select name="bill_report_month" id="bill_report_month" class="form-control" style="margin-left: -15%;">
		<?php

							for ($m=1; $m<=12; $m++) {
							    $month = date('F', mktime(0,0,0,$m));
								$num = date('m', mktime(0,0,0,$m));
								?>
						<option value ="<?php echo $crypto->encode($num, 4) ?>"<?php if(date("m") == $num){ echo " selected";} ?> ><?php echo $month; ?></option>
								<?php
							     }
							?>				
     </select>
     </div>
     <div class="col-sm-3">
     <select name="bill_report_year" id="bill_report_year" class="form-control" >
		<?php
					    	$cryear = date("Y");
					    	$le = 10;
							
							for ($i=$cryear; $i > $cryear-$le; $i--) { 
								?>
								<option value="<?php echo $crypto->encode($i, 4) ?>"<?php if(date("Y") == $i){ echo " selected";} ?>><?php echo $i; ?></option>
								<?php
							}
							
					    	?>
							
     </select>
     </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Salary Bill No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="bill" id="bill" placeholder="Salary Bill Number" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_no?>"  >
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<? if(!empty($bill_date)){ echo date('d-m-Y',strtotime($bill_date)); }?>" >
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" class="btn btn-info" id="submit-text">SUBMIT</button>
    </div>
  </div>
</form>
<div class="ajax_text_link"></div>
<?php  
$db  = new database();
$obj_crpto = new cryptography();
$data = $db->fetch_table("SELECT * FROM prd_block_bill_details
							WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
							AND salary_monthyear ='".date('Ym')."' AND status='1' AND requisition_type='".$requisition_type."'");
							
if(count($data)>0){
	$status=$data[0]['status'];
	$upload_date=$data[0]['ifms_uploaded_date'];
	$ref_no= $data[0]['ifms_reference_no'];
	$ifms_status=$data[0]['ifms_status'];
	}
if($status==1){
?>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_ifms" class="ifms_form_details" > 
<form class="form-horizontal" id="ifms_form" method="post" action="<?php echo $config['base_url'] ?>page/intra_prd/block/ifms_submit.php" onsubmit="return valid_code();" >
<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label" >Whether the file is uploaded in IFMS<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control" name="ifms_check" id="ifms_check" onChange="return ifmsDetailsView(this.value);" >
      <option value="">Please Select</option>
      <option value="1" <? if($ifms_status=='1' || $ifms_check=='1') { echo "selected"; } ?>>YES</option>
      <option value="0" <? if($ifms_status=='0' || $ifms_check=='0') { echo "selected"; } ?>>NO</option>
      </select>
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div id="ifms_details" style="display:none">
    <div class="form-group" id="ifms_up_date">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label" >IFMS Uploaded Date<span class="star_color">*</span></label>
    <div class="col-sm-3">
       <input type="text" class="form-control" id="ifms_upload_date" name="ifms_upload_date"  value="<?=$upload_date=='0001-01-01' ? '' : dateshow($upload_date);?>" placeholder="DD-MM-YYYY" readonly style="background-color:#FFF;cursor:pointer;" />
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group" id="ifms_ref">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label" >IFMS Reference No<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="ifms_ref_no" id="ifms_ref_no" placeholder="IFMS REFERENCE NO" onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $ref_no?>" >
    </div>
    <div class="col-sm-3"></div>
    </div>
     <div class="form-group" id="ifms_sub">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" class="btn btn-info" id="submit_ifms">SUBMIT</button>
    </div>
  </div>
  </div>
</form>
</div>
<?php } ?>


<div class="clear"></div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>

<script>
$(document).ready(function(){
		if($('#form_show').css("visibility")=="hidden"){
				$('#form_show').removeClass("invisible").css('height', 'auto');
			}
		});
		
	
		
function valid_code(){
	
		if($('#ifms_upload_date').val()==''){
			alert('Please Enter IFMS Uploded Date.');
			$('#ifms_upload_date').focus();
			return false;
		}
		else if($('#ifms_ref_no').val()==''){
			alert('Please Enter IFMS reference Number.');
			$('#ifms_ref_no').focus();
			return false;
		}
	
}
</script>
<script>
         $(function() {
			$( "#bill_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
		 });
		 
		  $(function() {
			$( "#ifms_upload_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
			
        });
		 
		 function ifmsDetailsView(type){
			 
			if(type=='1'){
				//alert(44);
				$('#ifms_details').show();
				$('#ifms_up_date').show();
				$('#ifms_ref').show();
				$('#ifms_sub').show();
			}else{
				$('#ifms_details').hide();
				$('#ifms_up_date').hide();
				$('#ifms_ref').hide();
				$('#ifms_sub').hide();
			}
		}
	function ifms_view(){
	
	}
</script>

<script>
	/*$(document).ajaxStart(function() {
		if($('#bill_date').val()!='' && $('#bill').val()!='')
				{
		$('#bill_date').attr('readonly','readonly');
		$('#bill').attr('readonly','readonly');
				}
				else
				{
					$('#bill_date').removeAttr('readonly');
					$('#bill').removeAttr('readonly');
					}
			});*/
</script>