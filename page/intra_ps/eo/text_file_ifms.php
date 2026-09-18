<?php
session_start();
error_reporting(0);

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
error_reporting(0);

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];


$k=strtotime("first day of last month");
$arr = date("Y-m-d",$k); 

$month_arr=explode('-',$arr);
$monthyr_prev=$month_arr[0].$month_arr[1];


$salary_bill_year_prev=$month_arr[0];

$salary_bill_month_prev=date('F', mktime(0, 0, 0, $month_arr[1], 1));


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
else if($_GET['confirm'] == 'false')
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



$find_bill = $db->fetch_table("
								SELECT * FROM prd_block_bill_details
								WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
								AND salary_monthyear ='".$yemo."' AND status='1' AND requisition_type='".$requisition_type."' AND status='1'
");


if(count($find_bill)>0)
{
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
                                            
                                            for ($i=$cryear; $i > $cryear-$le; $i--) 
                                            { 
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
                            <?php if($bill_no=='' && $bill_date=='')
							{ ?>
                                <div class="form-group">
                                    <div class="col-sm-offset-5 col-sm-7">
                                    	<button type="submit" class="btn btn-info" id="submit-text" name="val_submit">SUBMIT</button>
                                      
                                    </div>
                                </div>
                          <?php } ?>
                           
                        </form>



     <?php
						
                        if(count($find_bill)=='1')
                            {
								
							?>
                           
                                <div class="alert alert-success"  id="send_bill" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
                               
                            <?php 
							} ?>
                         
                        <div class="ajax_text_link"></div>
                        <!--<p><center><a href="../../ifms_inte.php" id="send_bill" style="display:none;">Go To Bill Sending Page...</a></center></p>-->
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
		
		if($('#bill').val()!='' && $('#bill_date').val()!='')
		{
			$.post('<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file_ifms/ajax_links.php',$('#gp_form').serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').show();
				});
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
				
				//$('#ifms_form').show();
				$.post('<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file_ifms/ajax_links.php',$(this).closest("form").serialize(), function(data){
					$('.ajax_text_link').html(data);
					$('#submit-text').show();
				});
			}
			//event.preventDefault();
			return false;
		});
		
			$('#bill_report_month , #bill_report_year').change(function(){
		
			$('#bill').val('');
			$('#bill_date').val('');
			$('.ajax_text_link').html('');
			var year = $('#bill_report_year').val();
			var month = $('#bill_report_month').val();
			$.ajax({
				url:'<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file_ifms/find_bill.php',
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
				if(data.priv_drn_number==null){
				    alert('Integration module not avalable. ');
				}else{
				$.post('<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file_ifms/ajax_links.php?priv_drn='+data.priv_drn_number,$('#gp_form').serialize(), function(data){
				$('.ajax_text_link').html(data);
				$('#submit-text').show();
				$("#val_submit").removeAttr("disabled");
				});
			    }
		}
				
			});
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
		function ifms_view()
		{
		
		}
	</script>
	
 