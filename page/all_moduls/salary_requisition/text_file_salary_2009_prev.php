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
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
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

$yemo=date("Y").date("m", strtotime("-1 months"));
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$bill_status=$crypto->decode($_GET['bill_status'],4);
$bill_type= $crypto->decode($_GET['emp_type'],4);
$ropa_status=2;
/*
echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/

if($logged_user=='EO')
{ 
		$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
		AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."'
		AND ropa_status='".$ropa_status."'	ORDER BY oid DESC
		
		");
	

	
}
else if($logged_user=='BDO')
{ 
		$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
		AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' 
		AND ropa_status='".$ropa_status."' ORDER BY oid DESC
		
		");
	

	
}
else if($logged_user=='FC&CAO')
{ 
	
		$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
		AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' AND zp_emp_type='".$bill_type."'
		AND ropa_status='".$ropa_status."'
		
		");
		
	
	
}

//var_dump($find_bill[0]['bill_no']); die;


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
<h1 class="heading">Salary Bill Generation ROPA 2009</h1>
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
	//$('#bill').val('');
				/*var monthyear=$('#monyr').val();
				 $.ajax({
				url:'<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/find_bill.php',
				dataType:"json",
            	type:'GET',
				data:{
					yemo:monthyear
				},
				success:function(data){
					//alert(data.bill);
					//$("#bill").val(data.bill);
					//$("#bill_date").val(data.bill_date);
				}
			});*/
	
	
	if($("#ifms_check").val()=='1'){
				$('#ifms_details').show();
				$('#ifms_up_date').show();
				$('#ifms_ref').show();
				$('#ifms_sub').show();
			}
	$('#submit-text').click(function(){
		var user=$('#user').val();
		
		var new_bill_no=$('#bill').val();
		//alert(new_bill_no);
		var new_bill_date=$('#bill_date').val();
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
			else if(user=='EO' || user=='BDO')
			{
				//alert(11);
				//$('#ifms_form').show();
				//$.post('<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/ajax_links_zp_arr.php',$(this).closest("form").serialize(), function(data){
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_bill_insert_prev.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').hide();
				$('#bill').val(new_bill_no);
				$('#bill_date').val(new_bill_date);
				});
				
			}
			
			
			else if(user=='FC&CAO')
			{
				//alert(11);
				//$('#ifms_form').show();
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_bill_insert_prev.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				//alert(data);
				$('#submit-text').hide();
				//var new_bill_no=$('#new_bill_no').val();
				$('#bill').val(new_bill_no);
				
				$('#bill_date').val(new_bill_date);
				});
				
			}
			//event.preventDefault();
			return false;
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

 $month = date('F', strtotime("last month"));
 $year=date('Y');
 
?>
<?php //echo $crypto->decode($_GET['emp_type'],4); ?>
<input type="hidden" id="monyr" name="monyr" value="<?php echo date('Ym');?>" />
<form class="form-horizontal" id="gp_form" name="gp_form" method="post" >
<input type="hidden" id="emp_type" name="emp_type" value="<?php echo $_GET['emp_type'];?>" />
<input type="hidden" id="requisition_type" name="requisition_type" value="<?php echo $crypto->encode($requisition_type,4);?>" />
<input type="hidden" id="bill_report_year" name="bill_report_year" value="<?php echo $crypto->encode(date("Y").date("m", strtotime("-1 months")),4);?>" />
<input type="hidden" id="ropa_status" name="ropa_status" value="<?php echo $crypto->encode($ropa_status,4);?>" />
<input type="hidden" id="user" name="user" value="<?php echo $logged_user;?>" />

<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill For The Month</label>
     <label for="inputPassword3" class="col-sm-3 control-label" style="color:#2873A3; font-weight:bold; text-align:left; font-size:16px;"><?php echo $month.", ".$year ?></label>
 </div>

<?php if($logged_user=='FC&CAO' || $logged_user=='EO' || $logged_user=='BDO')

	{
	if( $logged_user=='FC&CAO')
		{
		
		$checking=	$db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
		AND status='1' AND salary_monthyear='".$yemo."' 
		");	
		
		
		if($checking[0]['count']>='1'){
		if(count($find_bill)>0){
		$bill_no=$find_bill[0]['bill_no']; 
		}
		}
		else{
		$bill_no= '';
		}
		
		$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no, bill_entry_time FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND zp_id_fk = '".$_SESSION['location']['district_id']."' GROUP BY bill_entry_time ORDER BY max_bill_serial_no DESC LIMIT 1 ");
		
		
		if($checking[0]['count']>='1' )
		{
		
		$pre_bill_serial_no=$db->fetch_table("SELECT bill_serial_no FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND zp_id_fk = '".$_SESSION['location']['district_id']."'  AND ropa_status='".$ropa_status."' 
		AND requisition_type='".$requisition_type."' AND zp_emp_type='".$bill_type."'");
		//var_dump($pre_bill_serial_no); die;
		
		if(count($pre_bill_serial_no)>=1){
		$bill_serial_no = ($pre_bill_serial_no[0]['bill_serial_no']);
		$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 		
		}
		else{
		$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
		}
		}
		else
		{
		
		//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
		$bill_serial_no = 1; 
		}
		
		$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' AND requisition_type='".$requisition_type."'
		AND zp_id_fk =  '".$_SESSION['location']['district_id']."' AND bill_serial_no='".$bill_serial_no."' 
		AND ropa_status='".$ropa_status."' ");
		
		
		//$bill_no=$get_bill_serial_no[0]['bill_no'];
		//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
		}
	
	
	
	else if($logged_user=='EO')
	{
	$checking=	$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
	AND status='1' AND salary_monthyear='".$yemo."' 
	");	
	
	if($checking[0]['count']>='1'){
	if(count($find_bill)>0){
	$bill_no=$find_bill[0]['bill_no']; 
	}
	}
	else{
	$bill_no= '';
	}
	
	//var_dump($checking[0]['count']); die;
	$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no, bill_entry_time FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' GROUP BY bill_entry_time ORDER BY max_bill_serial_no DESC LIMIT 1 ");
	
	
	//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
	
	if($checking[0]['count']>='1' )
	{
	
	$pre_bill_serial_no=$db->fetch_table("SELECT bill_serial_no FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND ps_id_fk =  '".$_SESSION['location']['ps_id']."'  AND ropa_status='".$ropa_status."' 
	AND requisition_type='".$requisition_type."' ");
	//var_dump($pre_bill_serial_no); die;
	
	if(count($pre_bill_serial_no)>=1){
	$bill_serial_no = ($pre_bill_serial_no[0]['bill_serial_no']);
	$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	else{
	$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
	//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	}
	else
	{
	
	//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
	$bill_serial_no = 1; 
	}
	
	
	$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' AND ropa_status='".$ropa_status."'
	AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' AND bill_serial_no='".$bill_serial_no."' ");
	
	//$bill_no=$get_bill_serial_no[0]['bill_no'];
	//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	
	
	
	
	else if($logged_user=='BDO')
	{
	
	$checking=	$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE block_code ='".$_SESSION['location']['block_code']."'
	AND status='1' AND salary_monthyear='".$yemo."' 
	");	
	
	if($checking[0]['count']>='1'){
	if(count($find_bill)>0){
	$bill_no=$find_bill[0]['bill_no']; 
	}
	}
	else{
	$bill_no= '';
	}
	
	//var_dump($checking[0]['count']); die;
	$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no, bill_entry_time FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND block_code =  '".$_SESSION['location']['block_code']."' GROUP BY bill_entry_time ORDER BY max_bill_serial_no DESC LIMIT 1 ");
	
	
	//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
	
	if($checking[0]['count']>='1' )
	{
	
	$pre_bill_serial_no=$db->fetch_table("SELECT bill_serial_no FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND block_code = '".$_SESSION['location']['block_code']."'  AND ropa_status='".$ropa_status."' 
	AND requisition_type='".$requisition_type."' ");
	//var_dump($pre_bill_serial_no); die;
	
	if(count($pre_bill_serial_no)>=1){
	$bill_serial_no = ($pre_bill_serial_no[0]['bill_serial_no']); 
	$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	else{
	$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
	//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	}
	else
	{
	
	//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
	$bill_serial_no = 1; 
	}
	
	
	$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND block_code =  '".$_SESSION['location']['block_code']."' AND bill_serial_no='".$bill_serial_no."' 
	AND ropa_status='".$ropa_status."'");
	
	//$bill_no=$get_bill_serial_no[0]['bill_no'];
	//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	
	
	?>
	<?php  }
	?>
<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Bill Serial No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="bill_serial_no" id="bill_serial_no" placeholder="Bill Serial Number" readonly="readonly" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_serial_no;?>"   >
    </div>
    <div class="col-sm-3"></div>
    </div>

<div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Salary Bill No.<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" name="bill" id="bill" placeholder="Salary Bill Number" autocomplete="off" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_no;?>"   >
    </div>
    <div class="col-sm-3"></div>
    </div>

    
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
    <div class="col-sm-3">
	<?php if(!empty($bill_date)){ ?>
      <input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?  echo date('d-m-Y',strtotime($bill_date)); ?>" readonly="readonly" >
	  
	<?php } 
	else{ ?>  
      <input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?  echo date('d-m-Y'); ?>" readonly="readonly" >
	<?php } ?>  
    </div>
    <div class="col-sm-3"></div>
    </div>
<div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
    <!--<button type="submit" class="btn btn-info" id="submit-text" >SUBMIT</button>-->
 <?php 
 //var_dump($check[0]['check']); die;
 if($check[0]['check']==0)
 {?>
      <button type="submit" class="btn btn-info" id="submit-text" >SUBMIT</button> 
    <?php }
else if($check[0]['check']>=1){
	?>
	<div  style="width: 28%; text-align: center;">  <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php?ropa_status=<?php echo $crypto->encode($ropa_status,4);?>' class="btn btn-success">IFMS UPLOAD</a></div>
	<?php } ?>
    </div>
  </div>
</form>
<?php 
	if(count($find_bill)=='1')
		{ ?>
			<div class="alert alert-success"  id="send_bill" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br /> 
		<?php 
		}

	?>
<div class="ajax_text_link"></div>
<?php  
$db  = new database();
$obj_crpto = new cryptography();

if($logged_user=='EO')
{
	$data = $db->fetch_table("SELECT * FROM prd_block_bill_details
	WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
	AND salary_monthyear ='".date('Ym')."'
	AND status='1' AND requisition_type='".$requisition_type."' AND ropa_status='".$ropa_status."'");

}
else if($logged_user=='BDO')
{
	$data = $db->fetch_table("SELECT * FROM prd_block_bill_details
	WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
	AND salary_monthyear ='".date('Ym')."'
	AND status='1' AND requisition_type='".$requisition_type."' AND ropa_status='".$ropa_status."'");
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
      /*   $(function() {
			$( "#bill_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
		 }); */
		 
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
	
</script>
