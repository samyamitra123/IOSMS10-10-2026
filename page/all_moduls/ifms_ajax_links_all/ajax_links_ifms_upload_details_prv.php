<?php

session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once'../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../all_function/fun_store/zp_ps_gp_function.php'; 
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

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];
//$ropa_status=$crypto->decode($_GET['ropa_status'],4); 
$ropa_status= $_GET['ropa_status']; 

//var_dump($ropa_status); die;

/*
echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/

 



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
<h1 class="heading">Bill Submition In IFMS</h1>
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
    
            

<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" > 
<?
if(isset($_SESSION['head_msg'])){
	echo $_SESSION['head_msg'];
	unset($_SESSION['head_msg']);
}

 $month = date('F');
 $year=date('Y');

?>
<form class="form-horizontal" id="gp_form" name="gp_form" action="" method="post">
<input type="hidden" id="emp_type" name="emp_type" value="<?php echo $_GET['emp_type'];?>" />
<input type="hidden" id="requisition_type" name="requisition_type" value="<?php echo $crypto->encode($requisition_type,4);?>" />
<input type="hidden" id="user" name="user" value="<?php echo $logged_user;?>" />
<input type="hidden" id="ropa_status" name="ropa_status" value="<?php echo $ropa_status;?>" />



<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill For The Month.<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select name="bill_report_month" id="bill_report_month" class="form-control upper_case">
    <option value="">--Please Select--</option>
		<?php
		$current_month_no =  intval(date('m')); // by nirupam for not showing the next months when bill generated

		/*for ($m=4; $m<=$current_month_no; $m++) {
			//$month = date('F', mktime(0,0,0,$m));
			$month = date('F', mktime(0,0,0,$m,10));
			$num = date('m', mktime(0,0,0,$m));*/
			$current_month_no =12;
			if(date("Y") > '2018')
			{
				$m=1;
			}
			else
			{
				$m=8;
			}
			for ($m; $m<=$current_month_no; $m++) {
			//$month = date('F', mktime(0,0,0,$m));
			$month = date('F', mktime(0,0,0,$m,10));
			$num = date('m', mktime(0,0,0,$m));
			?>
            
		<option value ="<?php echo $crypto->encode($num, 4) ?>"><?php echo $month; ?></option>
			<?php
			 }
		?>	
    
</select>
    </div>
    <div class="col-sm-3"></div>
    </div>


<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill For The Year.<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <select name="bill_report_year" id="bill_report_year" class="form-control" style="" >
     <option value="">--Please Select--</option>
		<?php
			$cryear = date("Y");
			
			if(date("Y") > '2019')
			{
				$le = 2;
			}
			else
			{
				$le = 1;
			}
			
			for ($i=$cryear; $i > $cryear-$le; $i--) { 
				?>
				<option value="<?php echo $crypto->encode($i, 4) ?>"><?php echo $i; ?></option>
				<?php
			}
			
			?>
            </select>
    
    </div>
    <div class="col-sm-3"></div>
    </div>


<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill Serial Number<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control" name="bill_serial_no" id="bill_serial_no">
        <option value="">--Please Select--</option>
	           
            </select>
    </div>
    <div class="col-sm-3"></div>
    </div>


<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Bill No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" readonly style="background-color:#d3d3d3;" class="form-control upper_case" name="bill" id="bill" placeholder=" Salary Bill Number" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value=""  >
    </div>
    <div class="col-sm-3"></div>
    </div>

    
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text"  readonly="readonly" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<? if(!empty($bill_date)){ echo date('d-m-Y',strtotime($bill_date)); }?>" >
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" name="submit-text" class="btn btn-info" id="submit-text">SUBMIT</button>
    </div>
  </div>
</form>
<div class="ajax_text_link"></div>


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
$(document).ready(function() {
	
	
	if($('#bill').val()!='' && $('#bill_date').val()!='' && $('#bill_serial_no').val()!='')
		{
			$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links/ajax_links_ifms_upload_details_send.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').show();
				});
		}
	$('#submit-text').click(function(){
			if($('#bill_serial_no').val()=='')
			{
				alert('Please Select Bill Serial Number.');
				$('#bill_serial_no').focus();
				return false;
			}
			else if($('#bill_report_month').val()=='')
			{
				alert('Please Select Month of Salary Bill.');
				$('#bill_report_month').focus();
				return false;
			}
			else if($('#bill_report_year').val()=='')
			{
				alert('Please Select Year of Salary Bill.');
				$('#bill_report_year').focus();
				return false;
			}
			else if($('#bill').val()=='')
			{
				alert('Please Enter Salary Bill No.');
				$('#bill').focus();
				return false;	
			}
			else if($('#bill_date').val()=='')
			{
				alert('Please Enter Bill Date.');
				$('#bill_date').focus();
				return false;	
			}
			else
			{
				//alert ('ff');
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details_send.php',$(this).closest("form").serialize(), function(data){
					//alert(data);
				$('.ajax_text_link').html(data);
				$("#submit-text").show();
				});
			}
			//event.preventDefault();
			return false;
	});	
});
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
$('#bill_serial_no').change(function(){
		//alert(111);
		//$('#bill').val('');
		//$('#bill_date').val('');
		//$('.ajax_text_link').html('');
		var year = $('#bill_report_year').val();
		var month = $('#bill_report_month').val();
		var bill_serial_no=$("#bill_serial_no").val();
		 $.ajax({
		url:'<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_find_bill_details.php',
		dataType:"json",
		type:'GET',
		data:{
			ye:year,
			mo:month,
			bill_serial_no:bill_serial_no
		},
		success:function(data){
			//alert(data.bill);
			$("#bill").val(data.bill);
			//alert(data.bill_date);
			$("#bill_date").val(data.bill_date);
		}
	});
});
</script>
<script>
$("#bill_report_month, #bill_report_year").change(function(){ 
	
	var month = $("#bill_report_month").val();
	var year = $("#bill_report_year").val();
	//alert (month);
	//alert (year);
	$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_bill_serial_list.php?month='+month+'&year='+year, function(data){
		
		$('#bill_serial_no').html(data);
	});
});
</script>

