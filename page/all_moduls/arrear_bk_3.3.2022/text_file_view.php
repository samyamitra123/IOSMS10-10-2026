<?php
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

$logged_user=$_SESSION['user_info']['stake_abbr'];


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

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
$requisition_type=$requisition[0]['code'];

if($logged_user=='EO')
{
	$find_bill = $db->fetch_table("
									SELECT * FROM prd_block_bill_details
									WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
									AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."'
		
	");

}
else if($logged_user=='BDO')
{
	$find_bill = $db->fetch_table("
									SELECT * FROM prd_block_bill_details
									WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
									AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."'
		
	");
}

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
					  if(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
                      ?></h3>
       </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
<div class="col-sm-12">
<h1 class="heading">Arrear Bill Generation</h1>
<div class="border"></div>
</br>
<?
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg']."<br/>";
	unset($_SESSION['msg']);
}
?>
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
$(document).ready(function() {
	
				var monthyear=$('#monyr').val();
				 $.ajax({
				url:'<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/find_bill.php',
				dataType:"json",
            	type:'GET',
				data:{
					yemo:monthyear
				},
				success:function(data){
					//alert(data.bill);
					$("#bill").val(data.bill);
					$("#bill_date").val(data.bill_date);
				}
			});
	
	
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
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/file_view.php',$(this).closest("form").serialize(), function(data){
					//alert(data);
				$('.ajax_text_link').html(data);
			//alert(data);
				
				});
				
			}
			//event.preventDefault();
			return false;
	});
	
	
		
});

function valid_coden(){ 
	var year=$("#bill_report_year").val();
	var bill=$("#bill_no").val();
	if(year==""){
		alert("Please Select Year");
		$("#bill_report_year").focus();
		$(".ajax_text_link").hide();
		return false;
		
	}
	
	if(bill==""){
		alert("Please Select Bill No");
		$("#bill_no").focus();
		$(".ajax_text_link").hide();
		return false;
		
	}
	if(year!="" && bill!=""){
	$(".ajax_text_link").show();
	}
}
</script>


<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" > 
<?
if(isset($_SESSION['head_msg'])){
	echo $_SESSION['head_msg'];
	unset($_SESSION['head_msg']);
}

 $month = date('F', mktime(0,0,0,$m));
 $year=date('Y');
 
?>

<input type="hidden" id="monyr" name="monyr" value="<?php echo date('Ym');?>" />
<form class="form-horizontal" id="gp_form" name="gp_form" method="post"  >
<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Year<span class="star_color">*</span></label>
     <div class="col-sm-3">
   <select name="bill_report_year" id="bill_report_year" class="form-control" onChange="getbill_no(this.value);">
        <option value="">----Please Select-----</option>
		<?php
					    	$cryear = date("Y");
					    	$le = 10;
							
							for ($i=$cryear; $i > $cryear-$le; $i--) { 
								?>
								<option value="<?php echo $crypto->encode($i, 4) ?>"><?php echo $i; ?></option>
								<?php
							}
							
					    	?>
							
     </select>
     </div>
 </div>

<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Arrear Bill No.<span class="star_color">*</span></label>
    
       
    
     <div class="col-sm-3" id="bill_no_div" class="bill_no_div">
  <SELECT class="form-control" name="bill_no" id="bill_no">
      <option value="">----Please Select-----</option>
  </select>
     </div>
    
 <div class="col-sm-3"></div>
    </div>
<!--    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<? if(!empty($bill_date)){ echo date('d-m-Y',strtotime($bill_date)); }?>" >
    </div>
    <div class="col-sm-3"></div>
    </div>-->
    <div class="form-group">
    <div class="col-sm-offset-3 col-sm-7" align='center'>
      <button type="submit" class="btn btn-info" id="submit-text"  onClick="return valid_coden();">SUBMIT</button>
    </div>
  </div>
</form>
<div class="ajax_text_link" ></div>
<?php  
$db  = new database();
$obj_crpto = new cryptography();

if($logged_user=='EO')
{
	$data = $db->fetch_table("SELECT * FROM prd_block_bill_details
							WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
							AND salary_monthyear ='".date('Ym')."'
							AND status='1' AND requisition_type='".$requisition_type."' ");

}
else if($logged_user=='BDO')
{
	$data = $db->fetch_table("SELECT * FROM prd_block_bill_details
							WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
							AND salary_monthyear ='".date('Ym')."'
							AND status='1' AND requisition_type='".$requisition_type."' ");
}


							
if(count($data)>0){
	$status=$data[0]['status'];
	$upload_date=$data[0]['ifms_uploaded_date'];
	$ref_no= $data[0]['ifms_reference_no'];
	$ifms_status=$data[0]['ifms_status'];
	}
 ?>


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
    function getbill_no(val) 
{  
	
	$.ajax({
		type: "POST",
		url: "text_file/find_bill_view.php",
		data:'year='+val,
		success: function(data)
		{
		   //alert(data);
			$("#bill_no").html(data);
		}
	});
}
</script>