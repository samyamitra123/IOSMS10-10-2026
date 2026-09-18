<?
if($_SERVER['HTTP_REFERER']=='')
{
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
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';

$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Personal Details of the Employee submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false')
{
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

function get_emp($id)
{
	
	$db  = new database();
	
	$obj_crpto = new cryptography();
	
	$data = $db->fetch_table("
							SELECT 
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
								emp_pre_ps,
								emp_per_ps,
								emp_pension_status
								FROM prd_employee_master
								WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
								and zp_id_fk = '".$_SESSION['location']['district_id']."'
	");
	
	return $data;
}
if(!empty($_GET['emp_id_pk']))
{
	$emp_data = get_emp($_GET['emp_id_pk']);
}
else
{
	$emp_data = get_emp($emp_id);
}
$obj_crpto = new cryptography();

 $present_address_state=$emp_data[0]['pre_state']; 
$present_house_no=$emp_data[0]['emp_pre_house_no'];
$present_street=$emp_data[0]['emp_pre_street_no'];
$present_town_vill=$emp_data[0]['emp_pre_vill'];
$present_post_office=$emp_data[0]['emp_pre_post'];
$present_pin=$emp_data[0]['emp_pre_pin']=='0'?'':$emp_data[0]['emp_pre_pin'];
$present_city_district=$emp_data[0]['emp_pre_dist'];
$present_others_dist=$emp_data[0]['emp_pre_dist_others'];

$permanent_address_state=$emp_data[0]['per_state']; 
$permanent_house_no=$emp_data[0]['emp_per_house_no'];
$permanent_street=$emp_data[0]['emp_per_street_no'];
$permanent_town_vill=$emp_data[0]['emp_per_vill'];
$permanent_post_office=$emp_data[0]['emp_per_post'];
$permanent_pin=$emp_data[0]['emp_per_pin']=='0'?'':$emp_data[0]['emp_per_pin'];
$permanent_city_district=$emp_data[0]['emp_per_dist'];
$permanent_others_dist=$emp_data[0]['emp_per_dist_others'];

$present_police_station=$emp_data[0]['emp_pre_ps'];
$permanent_police_station=$emp_data[0]['emp_per_ps'];

$landline_no=$emp_data[0]['emp_land_no']=='0'?'':$emp_data[0]['emp_land_no'];
$mobile_no=$emp_data[0]['emp_mobile_no']=='0'?'':$emp_data[0]['emp_mobile_no'];
$email=$emp_data[0]['emp_mail_id'];

$emp_form_status=$emp_data[0]['emp_form_status'];
$emp_pension_status=$emp_data[0]['emp_pension_status'];

?>

<style>
.form-horizontal .control-label {
text-align:left;
}
</style>

<div class="content">
    <? require '../../../../page/common_back_btns.php'; ?>
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
					if(!empty($_GET['emp_id_pk']))
					{
						$employee_id=$cryptoGraph->decode($_GET['emp_id_pk'],4);
					}
					else
					{
						$employee_id=$cryptoGraph->decode($emp_id,4);
					}
                ?>
                <form class="form-horizontal" id="first_form" method="post" action="profile_entry_contact_submit.php" onsubmit="return validContact();">
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
                    <input type="hidden" name="tchcd" id="tchcd" value="<?=$tchcd?>" />
                    <input type="hidden" name="emp_date_retirement" id="emp_date_retirement" />
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="emp_id_pk" value="<?=$employee_id  ?>" />
                    <input type="hidden" name="edit_status" id="edit_status" value="<?php echo $obj_crpto->encode('1',4); ?>" />
                    <input type="hidden" name="emp_pension_status" id="emp_pension_status" value="<?php echo $obj_crpto->encode($emp_pension_status,4); ?>" />
                    <!-------------------------------------------Present Address--------------------------------------------------------------------------------> 
                    <div class="float_l col_line"><p>Present Address</p></div>
                    <div class="dashed_line"></div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="present_address_state" id="present_address_state">
                                <option value="">-Please Select-</option>
                                <option value="32" <? if($present_address_state=='32'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($present_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Police Station <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POLICE STATION" name="present_police_station" id="present_police_station" value="<?=$present_police_station?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" placeholder="HOUSE NO" name="present_house_no" id="present_house_no" value="<?=$present_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">		
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Street</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" name="present_street" placeholder="STREET" id="present_street"  value="<?=$present_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="TOWN/VILLAGE" name="present_town_vill" id="present_town_vill"  value="<?=$present_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POST OFFICE" name="present_post_office" id="present_post_office" value="<?=$present_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
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
                    <div class="form-group">
                        <label style="margin-left:1.1%">Whether Permanent Address is Equal to Present Address</label>
                        <input type="checkbox" autocomplete="off" name="address_equal" id="address_equal">
                        <label for="address_equal" ><span style="margin: 0 4px 0 0;"></span></label>
                    </div>
                    
                    <!-------------------------------------------Permanenet Address--------------------------------------------------------------------------------> 
                    <div class="float_l col_line"><p>Permanenet Address</p></div>
                    <div class="dashed_line"></div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="permanent_address_state" id="permanent_address_state">
                                <option value="">-Please Select-</option>
                                <option value="32" <? if($permanent_address_state=='32'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($permanent_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Police Station <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POLICE STATION" name="permanent_police_station" id="permanent_police_station" value="<?=$permanent_police_station?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" name="permanent_house_no" placeholder="HOUSE NO" id="permanent_house_no" value="<?=$permanent_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">		
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Street</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case"  autocomplete="off" placeholder="STREET" name="permanent_street" id="permanent_street"  value="<?=$permanent_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" name="permanent_town_vill" placeholder="TOWN/VILLAGE" id="permanent_town_vill"  value="<?=$permanent_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control upper_case" autocomplete="off" placeholder="POST OFFICE" name="permanent_post_office" id="permanent_post_office" value="<?=$permanent_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">PIN<span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" maxlength="6" placeholder="PIN" name="permanent_pin" id="permanent_pin" value="<?=$permanent_pin?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label" id="permanent_wb_dist_label" <? if($permanent_address_state=='32' || $permanent_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>District <span class="star_color">*</span></label>
                        <div class="col-sm-4" id="permanent_wb_dist_field" <?php  if($permanent_address_state=='32'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>
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
                        <div class="col-sm-4" id="permanent_others_dist_field" <?php  if($permanent_address_state=='32'){?>style="display:none;"<? }else{?>style="display:block;"<? }?>>
                        	<input type="text" autocomplete="off" name="permanent_others_dist" id="permanent_others_dist" class="form-control upper_case" value="<?=$permanent_others_dist?>" onKeyPress="return keyRestrict(event,'0123456789/\,abcdefghijklmnopqrstuvwxyz)(. ');">
                        </div>
                    </div>
                    <!------------------------------------------------Contact Details--------------------------------------------------------------->
                    <div class="float_l col_line"><p>Contact Details</p></div>
                    <div class="dashed_line"></div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Land Tel. No.</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" name="landline_no" placeholder="LAND TEL. NO." id="landline_no" value="<?=$landline_no?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Mobile No. <span class="star_color">*</span></label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" placeholder="MOBILE NO" name="mobile_no" id="mobile_no" maxlength="10" value="<?=$mobile_no?>" onKeyPress="return keyRestrict(event,'0123456789');">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Email Id</label>
                        <div class="col-sm-4">
                        	<input type="text" class="form-control" autocomplete="off" name="email" placeholder="EMAIL ID" id="email" value="<?=$email?>" onKeyPress="return keyRestrict(event,'0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.');">
                        </div>
                    </div>
                    
                    <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                            <a href="profile_entry_per.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
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
	$(document).ready(function() {
		$('#address_equal').click(function(event) {  //on click 
			if(this.checked) 
			{ // check select status
				$("#permanent_address_state").val($("#present_address_state").val());
				$("#permanent_house_no").val($("#present_house_no").val());
				$("#permanent_town_vill").val($("#present_town_vill").val());
				$("#permanent_pin").val($("#present_pin").val());
				$("#permanent_street").val($("#present_street").val());
				$("#permanent_post_office").val($("#present_post_office").val());
				$("#permanent_street").val($("#present_street").val());
				$("#permanent_police_station").val($("#present_police_station").val());  
				
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
		$('#present_address_state').change(function(event) {  //on click 
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
		$('#permanent_address_state').change(function(event) {  //on click 
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
		if(document.getElementById('present_police_station').value=='')
		{
			alert("Please Enter Police Station.");
			document.getElementById('present_police_station').focus();
			return false;
		}
		if(document.getElementById('present_town_vill').value == 0)
		{
			//if(document.getElementById('present_town_vill').value.search(/([\<])([^\>]{1,})*([\>])/) !=-1){
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
		if(document.getElementById('permanent_police_station').value=='')
		{
			alert("Please Enter Police Station.");
			document.getElementById('permanent_police_station').focus();
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
		
		var db_present_address_state = '<?php echo $present_address_state; ?>';
		var db_present_house_no = '<?php echo $present_house_no; ?>';
		var db_present_street = '<?php echo $present_street; ?>';
		var db_present_town_vill = '<?php echo $present_town_vill; ?>';
		var db_present_post_office = '<?php echo $present_post_office; ?>';
		var db_present_pin = '<?php echo $present_pin; ?>';
		var db_present_city_district = '<?php echo $present_city_district; ?>';
		var db_present_others_dist = '<?php echo $present_others_dist; ?>';
		var db_permanent_address_state = '<?php echo $permanent_address_state; ?>';
		var db_permanent_house_no = '<?php echo $permanent_house_no; ?>';
		var db_permanent_street = '<?php echo $permanent_street; ?>';
		var db_permanent_town_vill = '<?php echo $permanent_town_vill; ?>';
		var db_permanent_post_office = '<?php echo $permanent_post_office; ?>';
		var db_permanent_pin = '<?php echo $permanent_pin; ?>';
		var db_permanent_city_district = '<?php echo $permanent_city_district; ?>';
		var db_permanent_others_dist = '<?php echo $permanent_others_dist; ?>';
		var db_present_police_station = '<?php echo $present_police_station; ?>';
		var db_permanent_police_station = '<?php echo $permanent_police_station; ?>';
		var db_landline_no = '<?php echo $landline_no; ?>';
		var db_mobile_no = '<?php echo $mobile_no; ?>';
		var db_email = '<?php echo $email; ?>';
		
		/*alert(db_emp_dob);
		alert($('#tch_dob').val());*/
		if((db_present_address_state != $('#present_address_state').val()) ||
		(db_present_house_no != $('#present_house_no').val()) ||
		(db_present_street != $('#present_street').val()) ||
		(db_present_town_vill != $('#present_town_vill').val()) ||
		(db_present_post_office != $('#present_post_office').val()) ||
		(db_present_pin != $('#present_pin').val()) ||
		(db_present_city_district != $('#present_city_district').val()) ||
		(db_present_others_dist != $('#present_others_dist').val()) ||	
		(db_present_police_station != $('#present_police_station').val()) ||
		
		(db_permanent_address_state != $('#permanent_address_state').val()) ||
		(db_permanent_house_no != $('#permanent_house_no').val()) ||
		(db_permanent_street != $('#permanent_street').val()) ||
		(db_permanent_town_vill != $('#permanent_town_vill').val()) ||
		(db_permanent_post_office != $('#permanent_post_office').val()) ||
		(db_permanent_pin != $('#permanent_pin').val()) ||
		(db_permanent_city_district != $('#permanent_city_district').val()) ||
		(db_permanent_others_dist != $('#permanent_others_dist').val()) ||	
		(db_permanent_police_station != $('#permanent_police_station').val()) ||
		(db_landline_no != $('#landline_no').val()) ||
		(db_mobile_no != $('#mobile_no').val())	 ||
		(db_email != $('#email').val())	
		)
		{
			$('#edit_status').val('<?php echo $obj_crpto->encode('2',4); ?>');	
		}
		
	}
</script>