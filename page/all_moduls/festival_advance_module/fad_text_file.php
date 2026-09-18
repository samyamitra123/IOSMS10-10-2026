<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
//error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//error_reporting(0);

//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='429'");
$requisition_type=$requisition[0]['code'];


$k=strtotime("first day of last month");
$arr = date("Y-m-d",$k); 

/* $month_arr=explode('-',$arr);
$monthyr_prev=$month_arr[0].$month_arr[1];


$salary_bill_year_prev=$month_arr[0];

$salary_bill_month_prev=date('F', mktime(0, 0, 0, $month_arr[1], 1));

$ifms_status=$_POST['ifms_check'];
$ifms_upload_date=$_POST['ifms_upload_date'];
$ifms_ref_no=$_POST['ifms_ref_no'];
$prev_monthyr=$_POST['prev_monthyr'];

if($ifms_status=='0')
{
	$ifms_details_entry=$db->update(" UPDATE prd_block_bill_details SET
											ifms_reference_no='',
											ifms_status='".$ifms_status."',
											ifms_uploaded_date='' 
											WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' 
											AND salary_monthyear='".$prev_monthyr."' 
											AND requisition_type='".$requisition_type."' AND status='1'");
	
	
	if($ifms_details_entry)
	{
		$bill_view='1';
	}
}
else if($ifms_status=='1')
{
	if($validator->blank_select($ifms_upload_date) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFMS Uploaded Date.</strong></div>';
		include 'ifms_reference_number_form.php';
		exit;
	}
	else if($validator->blank_select($ifms_ref_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFMS Reference Number.</strong></div>';
		include 'ifms_reference_number_form.php';
		exit;
	}
	else if($prev_monthyr!=$monthyr_prev)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Security Error!!!</strong></div>';
		include 'ifms_reference_number_form.php';
		exit;
	}
	else
	{
		
		$bill_check=$db->fetch_table(" SELECT block_bill_pk  
									FROM prd_block_bill_details
									WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' 
									AND salary_monthyear='".$prev_monthyr."'
									AND requisition_type='".$requisition_type."' 
									AND status='1'");
									
		if(count($bill_check)>0)
		
		{
			$ifms_details_entry=$db->update(" UPDATE prd_block_bill_details SET
												ifms_reference_no='".$ifms_ref_no."',
												ifms_status='".$ifms_status."',
												ifms_uploaded_date='".$ifms_upload_date."' 
												WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' 
												AND salary_monthyear='".$prev_monthyr."'
												AND requisition_type='".$requisition_type."' 
												AND status='1'");
		
			if($ifms_details_entry)
			{
				$bill_view='1';
			}
			else
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong> Data Insertion Failed!! </strong></div>';
				include 'ifms_reference_number_form.php';
				exit;
			}
		}
		else
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Bill Has Not Generated For '.$salary_bill_year_prev.', '.$salary_bill_year_prev.' </strong></div>';
			include 'ifms_reference_number_form.php';
			exit;
		}
		
	
	}

}


if($bill_view=='1')
{*/


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$crypto = new cryptography();

if(isset($_GET['confirm']) == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>IFMS details submitted Successfully...</strong></div>';
}
else if(isset($_GET['confirm']) == 'false')
{
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

if($logged_user=='zpddo')
{

	$find_bill = $db->fetch_table("
								SELECT * FROM prd_block_bill_details
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' ORDER BY oid DESC
								");
}
else if($logged_user=='EO')
{

	$find_bill = $db->fetch_table("
								SELECT * FROM prd_block_bill_details
								WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
								AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' ORDER BY oid DESC
								");
}
else if($logged_user=='BDO')
{

	$find_bill = $db->fetch_table("
								SELECT * FROM prd_block_bill_details
								WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
								AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' ORDER BY oid DESC
								");
}

/*
if(count($find_bill)>0){
	$bill_no=$find_bill[0]['bill_no'];
	$bill_date=$find_bill[0]['bill_entry_time'];
	}
*/
?>
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
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
<div class="col-sm-12">
<h1 class="heading">Festival Advance Bill Generation</h1>
<div class="border"></div>
</br>
<?php 
if(!empty($msg)){
echo $msg;
echo "<br/>";
}
if(!empty($error_msg)){
echo $error_msg;
echo "<br/>";
}
if(!empty($error_message)){
echo $error_msg;
echo "<br/>";
}
?>

            
<script>
$(document).ready(function() {
	

	$('#submit-text').click(function(){
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
			else
			{
				/*
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/ajax_links.php',$(this).closest("form").serialize(), function(data){
					$('.ajax_text_link').html(data);
				});
				*/
				
				//$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_bill_insert.php',$(this).closest("form").serialize(), function(data){
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_bill_insert_others.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').hide();
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
<div id="form_show" style="margin-left: 15%;"> 
<?
if(isset($_SESSION['head_msg'])){
	echo $_SESSION['head_msg'];
	unset($_SESSION['head_msg']);
}

 $month = date('F');
 $year=date('Y');

?>
<form class="form-horizontal" id="gp_form" name="gp_form" method="post" >
<div class="row mb-3">
    <div class="col-sm-2"></div>
	<input type="hidden" id="requisition_type" name="requisition_type" value="<?php echo $crypto->encode($requisition_type,4);?>" />
	<input type="hidden" id="bill_report_year" name="bill_report_year" value="<?php echo $crypto->encode(date('Ym'),4);?>" />
	<input type="hidden" id="emp_type" name="emp_type" value="<?php echo $crypto->encode(0,4);?>" />
	<input type="hidden" id="ropa_status" name="ropa_status" value="<?php echo $crypto->encode(1,4); ?>" />
	
    <label for="inputPassword3" class="col-sm-3 control-label">Bill For The Month</label>
    <label for="inputPassword3" class="col-sm-3 control-label" style="color:#2873A3; font-weight:bold; text-align:left; font-size:16px;"><?php echo $month.", ".$year ?></label>
 
</div>

<?php 
//var_dump($logged_user); die;
if($logged_user=='zpddo' || $logged_user=='EO' || $logged_user=='BDO')

{
	if( $logged_user=='zpddo')
	{
 
		$checking=	$db->fetch_table("
					SELECT count(*) FROM prd_festival_advance_employee_details 
					WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
					AND festival_advance_status !='0' 
					AND bill_id_fk='0'
				");	
		
		if($checking[0]['count']=='0'){
			if(count($find_bill)>0){
				$bill_no=$find_bill[0]['bill_no']; 
				}
		}
		else{
			$bill_no= '';
		}
		
		$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
										prd_block_bill_details WHERE salary_monthyear='".date("Ym")."'
										AND status='1' 
										AND zp_id_fk =  '".$_SESSION['location']['district_id']."'  ");
										
										//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
									 
									 if($checking[0]['count']>='1' )
										{
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
										}
										else
										{
											
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
										}
											
										
										$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
										prd_block_bill_details WHERE salary_monthyear='".$yemo."'
										AND status='1' AND requisition_type='".$requisition_type."'
										AND zp_id_fk =  '".$_SESSION['location']['district_id']."' AND bill_serial_no='".$bill_serial_no."' ");
			
			//$bill_no=$get_bill_serial_no[0]['bill_no'];
			//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	else if($logged_user=='EO')
		{
			$checking=	$db->fetch_table("
					SELECT count(*) FROM prd_festival_advance_employee_details 
					WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
					AND festival_advance_status !='0' 
					AND bill_id_fk='0'
				");	
		
		if($checking[0]['count']=='0'){
			if(count($find_bill)>0){
				$bill_no=$find_bill[0]['bill_no']; 
				}
		}
		else{
			$bill_no= '';
		}
		
					//var_dump($checking[0]['count']); die;
					
			$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
										prd_block_bill_details WHERE salary_monthyear='".$yemo."'
										AND status='1' 
										AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' ");
										
										
									 //$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
									 
									 if($checking[0]['count']>='1' )
										{
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
										}
										else
										{
											
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
										}
											
										
										$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
										prd_block_bill_details WHERE salary_monthyear='".$yemo."'
										AND status='1' AND requisition_type='".$requisition_type."'
										AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' AND bill_serial_no='".$bill_serial_no."' ");
			
			//$bill_no=$get_bill_serial_no[0]['bill_no'];
			//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	
	else if($logged_user=='BDO')
	{	
			$checking=	$db->fetch_table("
					SELECT count(*) FROM prd_festival_advance_employee_details 
					WHERE block_code ='".$_SESSION['location']['block_code']."'
					AND festival_advance_status !='0' 
					AND bill_id_fk='0'
				");	
				
		if($checking[0]['count']=='0'){
			if(count($find_bill)>0){
				$bill_no=$find_bill[0]['bill_no']; 
				}
		}
		else{
			$bill_no= '';
		}
		
					//var_dump($checking[0]['count']); die;
			$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
										prd_block_bill_details WHERE salary_monthyear='".$yemo."'
										AND status='1' 
										AND block_code =  '".$_SESSION['location']['block_code']."' ");
										
										
									 //$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
									 
									 if($checking[0]['count']>='1' )
										{
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
										}
										else
										{
											
											 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
										}
											
										
										$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
										prd_block_bill_details WHERE salary_monthyear='".$yemo."'
										AND status='1' AND requisition_type='".$requisition_type."'
										AND block_code =  '".$_SESSION['location']['block_code']."' AND bill_serial_no='".$bill_serial_no."' ");
			
			//$bill_no=$get_bill_serial_no[0]['bill_no'];
			//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	?>
<?php  } ?>

	<div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">Bill Serial No.<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" name="bill_serial_no" id="bill_serial_no" placeholder="Salary Bill Number" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_serial_no?>" readonly >
		</div>
		<div class="col-sm-3"></div>
    </div>
	
	<div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">Festival Advance Bill No.<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" autocomplete="off" class="form-control upper_case" name="bill" id="bill" placeholder=" Festival Advance Bill Number" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_no;?>"   >
		</div>
		<div class="col-sm-3"></div>
    </div>
	
    <div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off" readonly value="<?  echo date('d-m-Y'); ?>" >
		</div>
		<div class="col-sm-3"></div>
    </div>
	
		<div class="row mb-3" style="margin-left: 41%;">
			<div class="col-sm-offset-5 col-sm-7">
				<?php 
					if($check[0]['check']==0)
				{?>
				  <button type="submit" class="btn btn-info" id="submit-text" >SUBMIT</button> 
				<?php }
					else if($check[0]['check']>=1){
				?>
					<div  style="width: 28%; text-align: center;">  <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS UPLOAD</a></div>
				<?php } ?>
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
		
	
/*
         $(function() {
			$( "#bill_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
		 });
		 */
		  
</script>


<?php //} ?>