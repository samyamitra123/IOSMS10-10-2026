<?php
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
header("Location:../../dashboard.php");
}

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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

if(!isset($_SERVER['HTTP_REFERER']))
{
header('Location:'.$config['base_url']."page/errordoc.php?id=1");
exit("Do not paste URL directly");

} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
// substring is not found in string
header('Location:'. $config['base_url']."page/errordoc.php?id=2");
exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
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

if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>IFMS details submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false'){
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

$logged_user=$_SESSION['user_info']['stake_abbr'];
$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='407'");
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
	
	$find_bill = $db->fetch_table("SELECT * FROM prd_block_bill_details
	WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
	AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."'");
}

else if($logged_user=='FC&CAO')
{ 
	
	
	$find_bill = $db->fetch_table("
									SELECT * FROM prd_block_bill_details
									WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
									AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' ORDER BY oid DESC
		
	");
	
	
	
}
								
								//var_dump($find_bill); die;
if(count($find_bill)>0)
{
	$bill_no=$find_bill[0]['bill_no'];
	$bill_date=$find_bill[0]['bill_entry_time'];
}


/*
$checking=	$db->fetch_table("
				SELECT count(*) FROM prd_block_bill_details 
				WHERE block_code ='".$_SESSION['location']['block_code']."'
				AND status ='1' AND salary_monthyear='".$yemo."'
			");	
	
	if($checking[0]['count']=='0'){
		if(count($find_bill)>0){
			$bill_no=$find_bill[0]['bill_no']; 
			}
	}
	else{
		$bill_no= '';
	}
	*/
				//var_dump($checking[0]['count']); die;
								
	if($logged_user=='FC&CAO' || $logged_user=='EO' || $logged_user=='BDO')
	{				
		if($logged_user=='BDO')
		{
		$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND block_code =  '".$_SESSION['location']['block_code']."' ");
		
		$serial_no_check= $db->fetch_table(" SELECT count(*) FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND block_code =  '".$_SESSION['location']['block_code']."' AND requisition_type='".$requisition_type."' ");
		
		
		//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		
		if($serial_no_check[0]['count']>='1' )
		{
		$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
		}
		else
		{
		
		$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		}
		
		$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND block_code =  '".$_SESSION['location']['block_code']."'   AND requisition_type='".$requisition_type."'");
		}
		else if($logged_user=='EO')
		{
		
		//var_dump($checking[0]['count']); die;
		$get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' ");
		
		
		$serial_no_check= $db->fetch_table(" SELECT count(*) FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' AND requisition_type='".$requisition_type."' ");
		
		
		//$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		
		if($serial_no_check[0]['count']>='1' )
		{
		$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
		}
		else
		{
		
		$bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		}
	
	
	
	$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
	prd_block_bill_details WHERE salary_monthyear='".$yemo."'
	AND status='1' 
	AND ps_id_fk =  '".$_SESSION['location']['ps_id']."' AND requisition_type='".$requisition_type."'  ");
	
	//$bill_no=$get_bill_serial_no[0]['bill_no'];
	//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	}
	else if( $logged_user=='FC&CAO')
	{
 
 /******************************************** Changed By ANJAN 21-11-2019 ****************************************************/
 
  $get_bill_serial_no = $db->fetch_table("SELECT max(bill_serial_no) AS max_bill_serial_no FROM 
									prd_block_bill_details WHERE salary_monthyear='".$yemo."'
									AND status='1' 
									AND zp_id_fk =  '".$_SESSION['location']['district_id']."'  ");
									
	$serial_no_check= $db->fetch_table(" SELECT count(*) FROM 
									prd_block_bill_details WHERE salary_monthyear='".$yemo."'
									AND status='1' 
									AND zp_id_fk =  '".$_SESSION['location']['district_id']."' AND requisition_type='".$requisition_type."' ");
								
									
	if($serial_no_check[0]['count']>='1' )
		{
			 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']); 
		}
		else
		{
			
			 $bill_serial_no = ($get_bill_serial_no[0]['max_bill_serial_no']+1); 
		}
			
			
		
		$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
		prd_block_bill_details WHERE salary_monthyear='".$yemo."'
		AND status='1' 
		AND zp_id_fk =  '".$_SESSION['location']['district_id']."' AND bill_serial_no='".$bill_serial_no."' ");
		
		
		//$bill_no=$get_bill_serial_no[0]['bill_no'];
		//$bill_date=$get_bill_serial_no[0]['bill_entry_time']; 
	
	/******************************************** END 21-11-2019 **********************************************/

	
	}
	
	}
$k = strtotime("first day of last month");
$arr = date("Y-m-d", $k);

/* $month_ini = new DateTime("first day of last month");
  $arr=$month_ini->format('Y-m-d'); // 2012-02-01
  echo $arr; */

$month_arr = explode('-', $arr);
$salary_monthyear = $month_arr[0] . $month_arr[1];
$db = new database();
$fun_store = new zp_ps_gp_class();
$party_code = '006';

function ifms_error_description_generate($code) 
{
    $db = new database();
    $err_desc_fetch = $db->fetch_table(" SELECT description FROM prd_ifms_response_code_master WHERE code='" . $code . "' ");
    return $err_desc_fetch[0]['description'];
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
        <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        ?></h3>
    </div>
<!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <h1 class="heading">Supplementary Salary Bill Generation</h1>
                <div class="border"></div>
                </br>
                <?php 
                if($msg)
				{
					echo $msg;
					echo "<br/>";
                }
                if($error_msg)
				{
					echo $error_msg;
					echo "<br/>";
                }
                if($error_message)
				{
					echo $error_msg;
					echo "<br/>";
                }
                ?>
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
                <div id="form_show" > 
                <?
					if(isset($_SESSION['head_msg']))
					{
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
									<input type="hidden" id="requisition_type" name="requisition_type" value="<?php echo $crypto->encode($requisition_type,4);?>" />
									<input type="hidden" name="bill_serial_no" id="bill_serial_no" value="<?= $bill_serial_no;?>" >
									<input type="hidden" id="bill_report_year" name="bill_report_year" value="<?php echo $crypto->encode(date('Ym'),4);?>" />
                                    <input type="hidden" id="emp_type" name="emp_type" value="<?php echo $crypto->encode(0,4);?>" />
									<input type="text" class="form-control" name="bill_report_month" id="bill_report_month" value="<?= date('F'); ?>" style="margin-left: -15%; width: 135px;" readonly >
                                       
                                    </div>
                                    <div class="col-sm-3">
									<input type="text" class="form-control" name="bill_year" id="bill_year" value="<?= date('Y'); ?>" readonly >
                                        
                                    </div>
                                </div>
                            </div>
                            
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
                                	<input type="text" class="form-control upper_case" name="bill" id="bill" placeholder="Salary Bill Number"  autocomplete="off" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz0123456789/');" value="<?= $bill_no?>"  >
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <label for="inputPassword3" class="col-sm-3 control-label">Enter Bill Date<span class="star_color">*</span></label>
                                <div class="col-sm-3">
                                	<input type="text" class="form-control" name="bill_date" id="bill_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?  echo date('d-m-Y'); ?>" readonly="readonly" >
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            
							<div class="form-group">
								<div class="col-sm-offset-5 col-sm-7">
								
							 <?php 
							 /*$check=$db->fetch_table("SELECT count(bill_no) as check  FROM 
								prd_block_bill_details WHERE salary_monthyear='".$yemo."'
								AND status='1' 
								AND block_code =  '".$_SESSION['location']['block_code']."' AND requisition_type='".$requisition_type."'");*/
							 //var_dump($check[0]['check']); die;
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



     <?php
						
                        if(count($find_bill)=='1')
                            {
								
							?>
                           
                                <div class="alert alert-success"  id="send_bill" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
                               
                            <?php 
							} ?>
                         
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
	$(document).ready(function() {
	
		if($("#ifms_check").val()=='1')
		{
			$('#ifms_details').show();
			$('#ifms_up_date').show();
			$('#ifms_ref').show();
			$('#ifms_sub').show();
		}
		$('#submit-text').click(function()
		{
		
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
			//$('#ifms_form').show();
				//$.post('<?php echo $config['base_url'] ?>page/intra_prd/block/supplementary_text_file/ajax_links.php',$(this).closest("form").serialize(), function(data){
				$.post('<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_bill_insert.php',$(this).closest("form").serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').hide();

			
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
		//				$.post('<?php //echo $config['base_url'] ?>page/intra_prd/block/supplementary_text_file/ajax_ifms.php',$(this).closest("form").serialize(), function(data){
		//				
		//				});
		//				
		//			}
		//			//event.preventDefault();
		//			return false;
		//	});
		$('#bill_report_month , #bill_year').change(function()
		{
			$('#bill').val('');
			$('#bill_date').val('');
			$('.ajax_text_link').html('');
			var year = $('#bill_year').val();
			var month = $('#bill_report_month').val();
			$.ajax({
			url:'<?php echo $config['base_url'] ?>page/intra_prd/block/supplementary_text_file/find_bill.php',
			dataType:"json",
			type:'GET',
			data:{
			ye:year,
			mo:month
			},
			success:function(data){
			$("#bill").val(data.bill);
			$("#bill_date").val(data.bill_date);
			}
			});
		});
	
	});
</script>
<script>
	$(document).ready(function(){
		if($('#form_show').css("visibility")=="hidden"){
		$('#form_show').removeClass("invisible").css('height', 'auto');
		}
	});
	
	/*function valid_code()
	{
		if($('#ifms_upload_date').val()=='')
		{
			alert('Please Enter IFMS Uploded Date.');
			$('#ifms_upload_date').focus();
			return false;
		}
		else if($('#ifms_ref_no').val()=='')
		{
			alert('Please Enter IFMS reference Number.');
			$('#ifms_ref_no').focus();
			return false;
		}
	}*/
</script>
<script>
	/* $(function() {
		$( "#bill_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy' 
		});
	});*/
	
	
	/*
	function ifmsDetailsView(type)
	{
		if(type=='1')
		{
			$('#ifms_details').show();
			$('#ifms_up_date').show();
			$('#ifms_ref').show();
			$('#ifms_sub').show();
		}
		else
		{
			$('#ifms_details').hide();
			$('#ifms_up_date').hide();
			$('#ifms_ref').hide();
			$('#ifms_sub').hide();
		}
	}
	*/
	
</script>

