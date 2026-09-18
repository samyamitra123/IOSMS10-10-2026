<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';
$db=new database();
$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");
?>
<style>
        .form-horizontal .control-label {
			text-align:left;
			}
        </style>

<?php
$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of the Employee submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";


//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>
<script>
function payband(val){
 $.post('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_payscale_details.php?payband='+val, function(data){
	 $(".payscale").html(data);	 
	 $.post('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_gradepay_details.php?payband='+val, function(data){
	 $(".gradepay").html(data);	 
 });
 });
}
</script>
<?

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

function get_emp($id){
	$db  = new database();
	
	$obj_crpto = new cryptography();
	
	

	$data = $db->fetch_table("
							SELECT 
									  emp_desig,
									  emp_cosolidated_pay,
									  emp_pay_band,
									  emp_pay_in_payband,
									  emp_grade_pay,
									  emp_pay_scale,
									  emp_bank_name,
									  emp_bank_branch,
									  emp_branch_code,
									  emp_micr_no,
									  emp_acc_no,
									  emp_ifsc_no,
									  emp_form_status,
									  emp_first_join_date,
									  ropa_status,
									  ropa_level
							FROM prd_employee_master
							WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
							and gp_id_fk = '".$_SESSION['location']['gp_id']."'
	
	");
	
	return $data;
}
if(!empty($_GET['emp_id_pk'])){
$emp_data = get_emp($_GET['emp_id_pk']);
}
else{
$emp_data = get_emp($emp_id);
}
//$emp_data = get_emp($_GET['emp_id_pk']);

		$emp_desig=$emp_data[0]['emp_desig'];
		$emp_cosolidated_pay=$emp_data[0]['emp_cosolidated_pay'];
		$emp_pay_band=$emp_data[0]['emp_pay_band'];
		$emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
		$emp_grade_pay=$emp_data[0]['emp_grade_pay'];
		$emp_pay_scale=$emp_data[0]['emp_pay_scale'];
		$emp_bank_name=$emp_data[0]['emp_bank_name'];
		$emp_bank_branch=$emp_data[0]['emp_bank_branch'];
		$emp_branch_code=$emp_data[0]['emp_branch_code'];
		$emp_micr_no=$emp_data[0]['emp_micr_no']=='0'?'':$emp_data[0]['emp_micr_no'];
		$emp_acc_no=$emp_data[0]['emp_acc_no'];
		$emp_ifsc_no=$emp_data[0]['emp_ifsc_no'];
		$emp_form_status=$emp_data[0]['emp_form_status'];		
	$emp_first_join_date=$emp_data[0]['emp_first_join_date']; 
	$ropa_status=$emp_data[0]['ropa_status'];
	$ropa_level=$emp_data[0]['ropa_level'];
?>
<script>
        $(function() {
			$( "#tch_dob" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
			$( "#doj" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
			});
			$( "#tch_approval_appointment" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy'
			});
			$( "#date_of_next_increment" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+60",
				dateFormat: 'dd-mm-yy'
			});
			$( "#emp_date_termination" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+50",
				dateFormat: 'dd-mm-yy'
			});
        });
		function calculate_retireyear(employee_dob){
			//alert(employee_dob);
			//child1 = new ajaxLoader('.child1');
			$.post('<?php echo $config['base_url'] ?>page/intra_prd/common/retirement_date.php',{employee_dob:employee_dob},function(data){
				if(data){
					//if (child1) child1.remove();
					//alert(data);
					$('#emp_date_retirement').val(data);
				}
			});
	  	}
		
		function show_basic(k)
	{ 
		
		var emp_pay_in_payband ='<?php echo $emp_pay_in_payband ?>';
	//alert(emp_pay_in_payband);
		$.ajax({
			type: "POST",
			url: "get_basic_ropa_2019.php",
			//data:'level='+k,
			data:{level: k, emp_pay_in_payband: emp_pay_in_payband},
			success: function(data)
			{ 
			//alert(data);
				$("#pay_in_payband").html(data);
				//show_datepicker(k,1);
			}
		});	
	}
		
		$(document).ready(function(){
			var account_len = $("#txt_account").val().length;
			$("#txt_account").attr("maxlength",account_len);
			$("#txt_bank").click(function() { 
                if($(this).val() == '') {
					$("#txt_account").val('');
					$("#txt_ifsc").val('');
					$("#txt_account").attr("disabled","disabled");
					$("#txt_ifsc").attr("disabled","disabled");
					$("#acc_display").html('');
					
				}
				else{
					<?php foreach($bank_details as $item){ ?>
					if(($(this).val() == '<?php echo $item['bank_code'] ?>')) {                       	
					$("#txt_account").removeAttr('disabled');
					$("#txt_ifsc").removeAttr('disabled');
					$("#txt_account").val('');	
					$("#txt_account").attr("maxlength","<?php echo $item['digit_in_account_no'] ?>");
					$("#txt_ifsc").val('<?php echo $item['bank_ifsc'] ?>');
					$("#acc_display").html('<?php echo $item['digit_in_account_no']." digits" ?>');
					}
					<?php } ?>
				}
			});
		});
        </script> 

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
        <div class="col-sm-12" style="width:98%; padding-left:2%" >
<h1 class="heading">Salary Details</h1>
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
if(!empty($_GET['emp_id_pk'])){
$employee_id=$cryptoGraph->decode($_REQUEST['emp_id_pk'],4);
}
else{
$employee_id=$cryptoGraph->decode($emp_id,4);	
}
?>
<form class="form-horizontal" id="loginForm" method="post" action="profile_entry_salary_insert.php" onsubmit="return valid_code();">
<input type="hidden" name="desig" id="desig" value=<?= $emp_desig ?>  />
<input type="hidden" name="emp_id_pk" value="<?= $employee_id ?>" />
 <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
 <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
 <input type="hidden" name="ropa_status" id="ropa_status" value="<?= $ropa_status ?>"  />
  <input type="hidden" name="emp_first_join_date" id="emp_first_join_date" value="<?= $emp_first_join_date ?>"  />
 
  <input type="hidden" name="emp_first_join_date" id="emp_first_join_date" value="<?= $emp_first_join_date ?>"  />
  <?php if($emp_desig=='1120' || $emp_desig=='1124' ||  $emp_desig=='1125') { ?>
  <div class="row mb-3">
     <label for="inputPassword3" class="col-sm-2 control-label">Consolidated Pay <span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" name="cons_pay" id="cons_pay" placeholder="Consolidated Pay" autocomplete="off" value="<? if(!empty($emp_cosolidated_pay)){ echo $emp_cosolidated_pay; }else{ echo $cons_pay;}?>" onKeyPress="return keyRestrict(event,'0123456789');"  maxlength="5">
  </div>
  </div>
  <?php } else {
	  
	  
	  
	  
	  if($ropa_status == 1 || $emp_first_join_date>='2020-01-01'){?>
				
				
				<div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-2 control-label">LEVEL<span class="star_color">*</span></label>
                            <div class="col-sm-4">
								<?
                                $db = new database();
								//$get_column=$db->fetch_table("SELECT * from ropa_2019 order by level ");
								$get_column=$db->fetch_table("SELECT  level1,level2,level3,level4,level5,level6,level7,level8,level9,level10,level11,level12,level13,level14,level15,level16,level17,level19 
								from ropa_2019 order by level ");
								
                                ?>
                                <select class="form-control" name="ropa_level" id="ropa_level" onChange="show_basic(this.value);">
                                    <option value="">-Please Select-</option>
                                    <? 
									foreach($get_column[0] as $key=>$value){ //var_dump($key);?>
                                    <option value="<?= $key; ?>" <? if($ropa_level==$key){ echo "selected";}?>><?= $key; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-2 control-label">Basic Pay<span class="star_color">*</span></label>
                            <div class="col-sm-4">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("SELECT $ropa_level from ropa_2019 ");
                                ?>
                                <SELECT class="form-control upper_case emp_class" name="pay_in_payband" id="pay_in_payband" >
									<option value="">-Please Select-</option>
										<? foreach($arr as $key1 => $val1){ ?>
                                    <option value="<?= $val1[$ropa_level]; ?>" <? if($emp_pay_in_payband==$val1[$ropa_level]){ echo "selected";}?>><?= $val1[$ropa_level]; ?></option>
									<? } ?>
								</SELECT>
                            </div>
                        </div>
						<?php }
	  
	  else{

	   ?>
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 control-label">Pay Band<span class="star_color">*</span></label>
    <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payband_code,payband_name from prd_payband_master order by payband_code");
				?>
      <select class="form-control" name="pay_band" id="pay_band" onchange="payband(this.value)">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['payband_code']. '<br />'; ?>
       <option value="<?= $key['payband_code']; ?>" <? if($emp_pay_band==$key['payband_code'] || $pay_band==$key['payband_code']){ echo "selected";}?>><?= $key['payband_name']; ?></option>
       <? } ?>
      </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Pay Scale<span class="star_color">*</span></label>
  <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select payscale_range,payscale_code from prd_dise_payscale_master where payscale_code='$emp_pay_scale'");
				?>
      <select class="form-control payscale" name="pay_scale" id="pay_scale">
      <option value="">-Please Select-</option>
		<? foreach($arr as $key){ $key['payscale_code']. '<br />'; ?>
        <option value="<?= $key['payscale_code']; ?>" <? if($emp_pay_scale==$key['payscale_code'] || $pay_scale==$key['payscale_code']){ echo "selected";}?>><?= $key['payscale_range']; ?></option>
        <? } ?>
      </select>
    </div>
  </div>
 
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">Pay in Pay Band<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" name="pay_in_payband" id="pay_in_payband" placeholder="PAY IN PAY BAND" autocomplete="off" value="<? if(!empty($emp_pay_in_payband)){ echo $emp_pay_in_payband; }else{ echo $pay_in_payband;} ?>" onKeyPress="return keyRestrict(event,'0123456789');" maxlength="5">
    </div>
     <label for="inputPassword3" class="col-sm-2 control-label">Grade Pay<span class="star_color">*</span> </label>
    <!--<div class="col-sm-4">
      <input type="text" class="form-control" name="grade_pay" id="grade_pay" placeholder="Grade Pay" autocomplete="off" value="<?//=$emp_grade_pay ?>" onKeyPress="return keyRestrict(event,'0123456789');">
  </div>-->
  
  <div class="col-sm-4">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where																				pm.payband_id_pk=gm.payband_id_fk AND payband_code='$emp_pay_band' order by grade_code ");
				//echo $emp_grade_pay;
				//print_r($arr);
				?>
      <select class="form-control gradepay" name="grade_pay" id="grade_pay">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
       <option value="<?= $key['grade_code']; ?>" <? if($emp_grade_pay==$key['grade_code'] || $grade_pay==$key['grade_code']){ echo "selected";}?>><?= $key['grade_amount']; ?></option>
       <? } ?>
      </select>
    </div>
  
  </div>
  <?php } 
  } ?>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">Bank Name<span class="star_color">*</span></label>
     <div class="col-sm-4">
     <?
	 $db = new database();
	 $arr = $db->fetch_table("select bank_code,bank_name from prd_dise_bank_master order by bank_name");
	 ?>
      <select class="form-control" id="txt_bank" name="txt_bank">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['bank_code']. '<br />'; ?>
       <option value="<?= $key['bank_code']; ?>" <? if($emp_bank_name==$key['bank_code'] || $txt_bank==$key['bank_code']){ echo "selected";}?>><?= $key['bank_name']; ?></option>
        <? } ?>
      </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Branch Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_bank_branch" name="tch_bank_branch" placeholder="Branch Name" autocomplete="off" value="<?=$emp_bank_branch ?>">
    </div>
  </div>
 
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">Branch Code</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_branch_code" name="tch_branch_code" placeholder="Branch Code" autocomplete="off" value="<?=$emp_branch_code ?>">
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">MICR Code</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_micr_code" name="tch_micr_code" placeholder="MICR Code" autocomplete="off" value="<?=$emp_micr_no ?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 control-label">Account No <span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="txt_account" name="txt_account" placeholder="ACCOUNT NUMBER" autocomplete="off" value="<?=$emp_acc_no ?>" onKeyPress="return keyRestrict(event,'0123456789');">
       <p id="acc_display" style="color: green;font-weight: bold;"></p>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">IFSC Code<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control"  id="txt_ifsc" name="txt_ifsc"  placeholder="IFSC CODE" autocomplete="off"  value="<?=$emp_ifsc_no ?>" maxlength="11">
    </div>
  </div>

  
  <p style="border-top:1px dashed #27769F; text-align:center;"></p>
  <div class="row mb-3">
    <div class="col-sm-12">
      <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
       <a href="profile_entry_prof.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
    </div>
  </div>
</form>

        </div>
      </div>

    </div>
    </div>
    <div class="clear"></div>
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  
    
    <script>
    function valid_code(){
		//alert($('#pay_scale').val());
		//var scale=cal_pay_in_band($('#pay_scale').val());
		if($('#desig').val()!='1120' && $('#desig').val()!='1124' && $('#desig').val()!='1125' ){
		if($('#pay_band').val()==''){
					alert('Please Select Pay Band.');
					$('#pay_band').focus();
					return false;
		}	
		if($('#pay_in_payband').val()==''){
					alert('Please Enter Pay in pay band.');
					$('#pay_in_payband').focus();
					return false;
		}
		if($('#grade_pay').val()==''){
					alert('Please Enter Grade Pay.');
					$('#grade_pay').focus();
					return false;
		}
		if($('#pay_scale').val()==''){
					alert('Please Enter Pay Scale.');
					$('#pay_scale').focus();
					return false;
		}
	 }
	if($('#desig').val()=='1120' || $('#desig').val()=='1124' || $('#desig').val()=='1125' ){
		if($('#cons_pay').val()==''){
					alert('Please Enter Consolidated Pay.');
					$('#cons_pay').focus();
					return false;
		} 
	 }
	 
	 if($('#txt_bank').val()==''){
					alert('Please Select Bank Name.');
					$('#txt_bank').focus();
					return false;
		} 
		if($('#txt_account').val()=='' || ($("#txt_account").val().length != parseInt($("#txt_account").attr('maxlength')) && parseInt($("#txt_account").attr('maxlength'))!=-1)){
					alert('Please enter valid  Bank Account No.');
					$('#txt_account').focus();
					return false;
				}
		if(($('#txt_ifsc').val()=='' || $('#txt_ifsc').val().length!=11)){
					alert('Please enter Valid Bank IFSC Code.');
					$('#txt_ifsc').focus();
					return false;
		}
		if(($('#txt_ifsc').val().substring(0,4)) != ($('#txt_bank').val().substring(0,4))){
					alert('Please enter Valid Bank IFSC Code.');
					$('#txt_ifsc').focus();
					return false;
		}
		
	}
    </script>
    <script>
    function cal_pay_in_band(val){
		alert(val);
	}
    </script>