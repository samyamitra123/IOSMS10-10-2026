<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();

$true_stat=$cryptoGraph->encode('true',4);
$false_stat=$cryptoGraph->encode('false',4);

if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Personal Details of the Employee submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false')
{
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}



//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";


//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

function get_emp($id,$gp_id)
{
	$db  = new database();
	
	$obj_crpto = new cryptography();
	
	

	$data = $db->fetch_table("
							SELECT 
										gp_id_fk,
										pre_state, 
										emp_pre_house_no,
										emp_pre_street_no,
										emp_pre_vill, 
										emp_pre_post,
										emp_pre_pin,
										emp_pre_dist,
										emp_pre_dist_others,
										per_state,
										emp_per_house_no,
										emp_per_street_no,
										emp_per_vill,
										emp_per_post,
										emp_per_pin,
										emp_per_dist,
										emp_per_dist_others,
										emp_land_no,
										emp_mobile_no,
										emp_mail_id,
										emp_form_status,
										emp_status,
										emp_pension_status
							FROM prd_employee_master
							WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
							and gp_id_fk = '".$obj_crpto->decode($gp_id,4)."'
	
	");
	
	return $data;
}
if(!empty($_GET['emp_id_pk']))
{
	$emp_data = get_emp($_GET['emp_id_pk'],$_GET['gp_id']);
}
else
{
	$emp_data = get_emp($emp_id,$gpid);
}


$present_address_state=$emp_data[0]['pre_state'];
$present_house_no=$emp_data[0]['emp_pre_house_no'];
$present_street=$emp_data[0]['emp_pre_street_no'];
$present_town_vill=$emp_data[0]['emp_pre_vill'];
$present_post_office=$emp_data[0]['emp_pre_post'];
$present_pin=$emp_data[0]['emp_pre_pin'];
$present_city_district=$emp_data[0]['emp_pre_dist'];
$present_others_dist=$emp_data[0]['emp_pre_dist_others'];

$permanent_address_state=$emp_data[0]['per_state'];
$permanent_house_no=$emp_data[0]['emp_per_house_no'];
$permanent_street=$emp_data[0]['emp_per_street_no'];
$permanent_town_vill=$emp_data[0]['emp_per_vill'];
$permanent_post_office=$emp_data[0]['emp_per_post'];
$permanent_pin=$emp_data[0]['emp_per_pin'];
$permanent_city_district=$emp_data[0]['emp_per_dist'];
$permanent_others_dist=$emp_data[0]['emp_per_dist_others'];

$landline_no=$emp_data[0]['emp_land_no']=='0'?'':$emp_data[0]['emp_land_no'];
$mobile_no=$emp_data[0]['emp_mobile_no']=='0'?'':$emp_data[0]['emp_mobile_no'];
$email=$emp_data[0]['emp_mail_id'];

$emp_form_status=$emp_data[0]['emp_form_status'];
$gp_id=$emp_data[0]['gp_id_fk'];

$emp_pension_status=$emp_data[0]['emp_pension_status'];

?>

<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<div class="content">
	<? require '../../../../page/common_back_btns.php'; ?>
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
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12" style="width:98%;">
                <h1 class="heading">Contact Details</h1>
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
                ?>
                <form class="form-horizontal" id="first_form" method="post" action="profile_entry_contact_submit.php" onsubmit="return validContact();">
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
                    <input type="hidden" name="gp_id" id="gp_id" value="<?=$gp_id?>" />
                    <input type="hidden" name="emp_date_retirement" id="emp_date_retirement" />
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="emp_id_pk" value="<?= $cryptoGraph->decode($_REQUEST['emp_id_pk'],4) ?>" />
                    <input type="hidden" name="pension_stat" id="pension_stat" value="<?= $emp_pension_status ?>"  />
                    <input type="hidden" name="update_id" id="update_id" />
                    <input type="hidden" name="pension_id" id="pension_id" />
                    
                    <!-------------------------------------------Present Address--------------------------------------------------------------------------------> 
                    <div class="float_l col_line"><p>Present Address</p></div>
                    <div class="dashed_line"></div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="present_address_state" id="present_address_state">
                                <option value="">-Please Select-</option>
                                <option value="32" <? if($present_address_state=='32'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($present_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  placeholder="HOUSE NO"  autocomplete="off" name="present_house_no" id="present_house_no" value="<?=$present_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">		
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Street</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" placeholder="STREET" name="present_street" id="present_street"  value="<?=$present_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="TOWN/VILLAGE" name="present_town_vill" id="present_town_vill"  value="<?=$present_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POST OFFICE" name="present_post_office" id="present_post_office" value="<?=$present_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">PIN <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" autocomplete="off" maxlength="6" placeholder="PIN" name="present_pin" id="present_pin" value="<?=$present_pin?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label" id="present_wb_dist_label" <? if($present_address_state=='32' || $present_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>District <span class="star_color">*</span></label>
                        
                        <div class="col-sm-4" id="present_wb_dist_field" <? if($present_address_state=='32'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>
                        <?php
                        $db=new database();
                        $district_fetch=$db->fetch_table("select * from prd_location_master_district where state_id_fk='1'");
                        ?>
                        <select name="present_city_district" id="present_city_district" class="form-control">
                        <option value="" selected="selected">-Please Select-</option>
                        <?php foreach($district_fetch as $dist_dtls){ ?>	
                        <option value="<? echo $dist_dtls['district_id_pk']?>" <? if($present_city_district==$dist_dtls['district_id_pk']){ echo "selected";}?>><? echo $dist_dtls['district_name'];?></option>
                        <?php	} ?>
                        </select> 
                        </div>
                        <div class="col-sm-4" id="present_others_dist_field" style="display:none;">
                        	<input type="text" autocomplete="off" name="present_others_dist" id="present_others_dist" class="form-control upper_case" value="<?=$present_others_dist?>" onKeyPress="return keyRestrict(event,'0123456789/\,abcdefghijklmnopqrstuvwxyz)(. ');">
                        </div>
                    </div>
                    <br/>
                    <div class="row mb-3">
                        <label style="margin-left:1%">Whether permanent address is equal to present address</label>
                        <input type="checkbox" autocomplete="off" name="address_equal" id="address_equal">
                        <label for="address_equal" ><span style="margin: 0 4px 0 0;"></span></label>
                    </div>
                    
                    <!-------------------------------------------Permanenet Address--------------------------------------------------------------------------------> 
                    <div class="float_l col_line"><p>Permanenet Address</p></div>
                    <div class="dashed_line"></div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="permanent_address_state" id="permanent_address_state">
                                <option value="">-Please Select-</option>
                                <option value="32" <? if($present_address_state=='32'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($present_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" placeholder="HOUSE NO" name="permanent_house_no" id="permanent_house_no" value="<?=$permanent_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">		
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Street</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" placeholder="STREET" name="permanent_street" id="permanent_street"  value="<?=$permanent_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="TOWN/VILLAGE" name="permanent_town_vill" id="permanent_town_vill"  value="<?=$permanent_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POST OFFICE" name="permanent_post_office" id="permanent_post_office" value="<?=$permanent_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
						<label for="inputPassword3" class="col-sm-2 control-label">PIN <span class="star_color">*</span></label>
						<div class="col-sm-4">
							<input type="text" class="form-control" autocomplete="off" maxlength="6" placeholder="PIN" name="permanent_pin" id="permanent_pin" value="<?=$permanent_pin?>" onKeyPress="return keyRestrict(event,'0123456789');">
						</div>
						<label for="inputPassword3" class="col-sm-2 control-label" id="permanent_wb_dist_label" <? if($permanent_address_state=='32' || $permanent_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>District <span class="star_color">*</span></label>
						
						<div class="col-sm-4" id="permanent_wb_dist_field" <? if($permanent_address_state=='32'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>
							<?php
								$db=new database();
								$district_fetch=$db->fetch_table("select * from prd_location_master_district where state_id_fk='1'");
							?>
							<select name="permanent_city_district" id="permanent_city_district" class="form-control">
								<option value="" selected="selected">-Please Select-</option>
								<?php foreach($district_fetch as $dist_dtls){ ?>	
								<option value="<? echo $dist_dtls['district_id_pk']?>" <? if($permanent_city_district==$dist_dtls['district_id_pk']){ echo "selected";}?>><? echo $dist_dtls['district_name'];?></option>
								<?php	} ?>
							</select> 
						</div>
						<div class="col-sm-4" id="permanent_others_dist_field" style="display:none;">
							<input type="text" autocomplete="off" name="permanent_others_dist" id="permanent_others_dist" class="form-control upper_case" value="<?=$present_others_dist?>" onKeyPress="return keyRestrict(event,'0123456789/\,abcdefghijklmnopqrstuvwxyz)(. ');">
						</div>
                    </div>
                    <!------------------------------------------------Contact Details--------------------------------------------------------------->
                    
                    <div class="float_l col_line"><p  class="text-warning">Contact Details</p></div>
                    <div class="dashed_line"></div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">Land Tel. No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" name="landline_no" placeholder="LAND TEL. NO." id="landline_no" value="<?=$landline_no?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Mobile No. <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" name="mobile_no" placeholder="MOBILE NO" id="mobile_no" maxlength="10" value="<?=$mobile_no?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 control-label">Email Id</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" autocomplete="off" placeholder="EMAIL ID" name="email" id="email" value="<?=$email?>" onKeyPress="return keyRestrict(event,'0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.');">
                        </div>
                    </div>
                    
                    
                    <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                        	<button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                        <a href="profile_entry_per.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>&gp_id=<? if(!empty($_GET['gp_id'])){ echo $_GET['gp_id']; } else { echo $gpid;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
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
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  

<script>

	$(document).ready(function() 
	{
		$('#address_equal').click(function(event) 
		{  //on click 
			if(this.checked) 
			{ // check select status
				$("#permanent_address_state").val($("#present_address_state").val());
				$("#permanent_house_no").val($("#present_house_no").val());
				$("#permanent_town_vill").val($("#present_town_vill").val());
				$("#permanent_pin").val($("#present_pin").val());
				$("#permanent_street").val($("#present_street").val());
				$("#permanent_post_office").val($("#present_post_office").val());
				$("#permanent_street").val($("#present_street").val());  
				
				if(document.getElementById('permanent_address_state').value=='')
				{
					$('#permanent_wb_dist_label').hide();
					$('#permanent_wb_dist_field').hide();
					$('#permanent_others_dist_field').hide();
					$('#permanent_others_dist').val('');  
					$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				}  
				if(document.getElementById('present_address_state').value==32)
				{
					$('#permanent_wb_dist_label').show();
					$('#permanent_wb_dist_field').show();
					$('#permanent_others_dist_field').hide();
					$('#permanent_others_dist').val(''); 
					//$("#permanent_city_district:selected").val($("#present_city_district ").val());
					var dist=$("#present_city_district option:selected").text();
					var dist_val=$("#present_city_district option:selected").val();
					$("#permanent_city_district option:selected").text(dist);
					$("#permanent_city_district option:selected").val(dist_val);
				}
				if(document.getElementById('present_address_state').value=='others')
				{
					$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
					$('#permanent_wb_dist_label').show();
					$('#permanent_wb_dist_field').hide();
					$('#permanent_others_dist_field').show();
					$("#permanent_others_dist").val($("#present_others_dist").val());
				}
			}
		});
	
	});
	
	$(document).ready(function() 
	{
		$('#present_address_state').change(function(event) 
		{  //on click 
			if(document.getElementById('present_address_state').value=='')
			{
				$('#present_wb_dist_label').hide();
				$('#present_wb_dist_field').hide();
				$('#present_others_dist_field').hide();
				$('#present_others_dist').val('');  
				$('#present_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
			}
			if(document.getElementById('present_address_state').value==32)
			{
				$('#present_wb_dist_label').show();
				$('#present_wb_dist_field').show();
				$('#present_others_dist_field').hide();
				$('#present_others_dist').val('');  
			}
			if(document.getElementById('present_address_state').value=='others')
			{
				$('#present_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				$('#present_wb_dist_label').show();
				$('#present_wb_dist_field').hide();
				$('#present_others_dist_field').show();
			}
		});
	});
	
	
	
	$(document).ready(function() 
	{
		$('#permanent_address_state').change(function(event) 
		{  //on click 
			if(document.getElementById('permanent_address_state').value=='')
			{
				$('#permanent_wb_dist_label').hide();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').hide();
				$('#permanent_others_dist').val('');  
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
			}
			if(document.getElementById('permanent_address_state').value==32)
			{
				$('#permanent_wb_dist_label').show();
				$('#permanent_wb_dist_field').show();
				$('#permanent_others_dist_field').hide();
				$('#permanent_others_dist').val('');  
			}
			if(document.getElementById('permanent_address_state').value=='others')
			{
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				$('#permanent_wb_dist_label').show();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').show();
			}
		});
	});


</script>

<script type="text/javascript">
	
	function validContact()
	{
		if(document.getElementById('present_address_state').value=='')
		{
			alert("Please Select State.");
			document.getElementById('present_address_state').focus();
			return false;
		}
		if(document.getElementById('present_town_vill').value == 0)
		{
			alert("Please Enter Town/Village.");
			document.getElementById('present_town_vill').focus();
			return false;
		}
		if(document.getElementById('present_post_office').value == 0)
		{
			alert("Please Enter Post Office.");
			document.getElementById('present_post_office').focus();
			return false;
		}
		if(document.getElementById('present_pin').value == 0)
		{
			alert("Please Enter Pin Number.");
			document.getElementById('present_pin').focus();
			return false;
		}
		if(document.getElementById('present_address_state').value==32)
		{
			if(document.getElementById('present_city_district').value == '')
			{
				alert("Please Enter District.");
				document.getElementById('present_city_district').focus();
				return false;
			}
		}
		else
		{
			if(document.getElementById('present_others_dist').value == '')
			{
				alert("Please Enter District.");
				document.getElementById('present_others_dist').focus();
				return false;
			}
		}
		<!-----------------------Permanent validation---------------------->
		if(document.getElementById('permanent_address_state').value=='')
		{
			alert("Please Select State.");
			document.getElementById('permanent_address_state').focus();
			return false;
		}
		if(document.getElementById('permanent_town_vill').value == 0)
		{
			alert("Please Enter Town/Village.");
			document.getElementById('permanent_town_vill').focus();
			return false;
		}
		if(document.getElementById('permanent_post_office').value == 0)
		{
			alert("Please Enter Post Office.");
			document.getElementById('permanent_post_office').focus();
			return false;
		}
		if(document.getElementById('permanent_pin').value == 0)
		{
			alert("Please Enter Pin Number.");
			document.getElementById('permanent_pin').focus();
			return false;
		}
		if(document.getElementById('permanent_address_state').value==32)
		{
			if(document.getElementById('permanent_city_district').value == '')
			{
				alert("Please Enter District.");
				document.getElementById('permanent_city_district').focus();
				return false;
			}
		}
		else
		{
			if(document.getElementById('permanent_others_dist').value == '')
			{
				alert("Please Enter District.");
				document.getElementById('permanent_others_dist').focus();
				return false;
			}
		}
		if(document.getElementById('email').value!=0)
		{
			if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1)
			{
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
			}
		}
		if(document.getElementById('mobile_no').value == 0)
		{
			alert("Please Enter Mobile Number.");
			document.getElementById('mobile_no').focus();
			return false;
		}
		if(document.getElementById('mobile_no').value != 0 && document.getElementById('mobile_no').value.length !=10)
		{
			alert("Please Enter 10 Digit Mobile Number.");
			document.getElementById('mobile_no').focus();
			return false;
		}
		var a=valid_updation();
		
		if(a!=true)
		{
			return false;
		}
	}
	
	function valid_updation()
	{	
		var true_stat='<?php echo $true_stat; ?>';
		var false_stat='<?php echo $false_stat; ?>';
		
		var present_address_state= '<?php echo $present_address_state; ?>';
		var present_house_no= '<?php echo $present_house_no; ?>';
		var present_street= '<?php echo $present_street; ?>';
		var present_town_vill= '<?php echo $present_town_vill; ?>';
		var present_post_office= '<?php echo $present_post_office; ?>';
		var present_pin= '<?php echo $present_pin; ?>';
		var present_city_district= '<?php echo $present_city_district; ?>';
		var present_others_dist= '<?php echo $present_others_dist; ?>';
		var permanent_address_state= '<?php echo $permanent_address_state; ?>';
		var permanent_house_no= '<?php echo $permanent_house_no; ?>';
		var permanent_street= '<?php echo $permanent_street; ?>';
		var permanent_town_vill= '<?php echo $permanent_town_vill; ?>';
		var permanent_post_office= '<?php echo $permanent_post_office; ?>';
		var permanent_pin= '<?php echo $permanent_pin; ?>';
		var permanent_city_district= '<?php echo $permanent_city_district; ?>';
		var permanent_others_dist= '<?php echo $permanent_others_dist; ?>';
		var landline_no= '<?php echo $landline_no; ?>';
		var mobile_no= '<?php echo $mobile_no; ?>';
		var email= '<?php echo $email; ?>';
		
		
		var val_present_address_state= $('#present_address_state').val().trim();
		var val_present_house_no= $('#present_house_no').val().trim();
		var val_present_street= $('#present_street').val().trim();
		var val_present_town_vill= $('#present_town_vill').val().trim();
		var val_present_post_office= $('#present_post_office').val().trim();
		var val_present_pin= $('#present_pin').val().trim();
		var val_present_city_district= $('#present_city_district').val().trim();
		var val_present_others_dist= $('#present_others_dist').val().trim();
		var val_permanent_address_state= $('#permanent_address_state').val().trim();
		var val_permanent_house_no= $('#permanent_house_no').val().trim();
		var val_permanent_street= $('#permanent_street').val().trim();
		var val_permanent_town_vill= $('#permanent_town_vill').val().trim();
		var val_permanent_post_office=$('#permanent_post_office').val().trim();
		var val_permanent_pin= $('#permanent_pin').val().trim();
		var val_permanent_city_district= $('#permanent_city_district').val().trim();
		var val_permanent_others_dist= $('#permanent_others_dist').val().trim();
		var val_landline_no= $('#landline_no').val().trim();
		var val_mobile_no= $('#mobile_no').val().trim();
		var val_email= $('#email').val().trim();
		
		
		if(present_address_state!=val_present_address_state || present_house_no!=val_present_house_no || present_street!=val_present_street || present_town_vill!=val_present_town_vill || present_post_office!=val_present_post_office || present_pin!=val_present_pin || present_city_district!=val_present_city_district || present_others_dist!=val_present_others_dist || permanent_address_state!=val_permanent_address_state || permanent_house_no!=val_permanent_house_no || permanent_street!=val_permanent_street || permanent_town_vill!=val_permanent_town_vill || permanent_post_office!=val_permanent_post_office || permanent_pin!=val_permanent_pin || permanent_city_district!=val_permanent_city_district || permanent_others_dist!=val_permanent_others_dist || landline_no!=val_landline_no || mobile_no!=val_mobile_no || email!=val_email)
		{
			$('#update_id').val(true_stat);
		}
		else
		{
			$('#update_id').val(false_stat);
		}
		if(present_address_state!=val_present_address_state || present_house_no!=val_present_house_no || present_street!=val_present_street || present_town_vill!=val_present_town_vill || present_post_office!=val_present_post_office || present_pin!=val_present_pin || present_city_district!=val_present_city_district || present_others_dist!=val_present_others_dist || permanent_address_state!=val_permanent_address_state || permanent_house_no!=val_permanent_house_no || permanent_street!=val_permanent_street || permanent_town_vill!=val_permanent_town_vill || permanent_post_office!=val_permanent_post_office || permanent_pin!=val_permanent_pin || permanent_city_district!=val_permanent_city_district || permanent_others_dist!=val_permanent_others_dist || mobile_no!=val_mobile_no || email!=val_email)
		{
			$('#pension_id').val(true_stat);
		}
		else
		{
			$('#pension_id').val(false_stat);
		}
		return true;
	
	}
</script>