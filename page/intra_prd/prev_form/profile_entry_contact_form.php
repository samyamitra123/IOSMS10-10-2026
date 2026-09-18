<?php
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

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

/*if($status=='success'){
		$msg='<span style="color:#5F983D; font-weight:bolder;">Personal details inserted successfully...</span>';
}else if($status=='failed'){
		//$msg='<span style="color:#D83118; font-weight:bolder;">Data insertion failed. Please try again...</span>';
		header();
}else if(!$status){
		$msg='';
}*/

$cryptoGraph=new cryptography();
$teacher_id_pk_enc=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$teacher_id_pk=$cryptoGraph->decode($teacher_id_pk_enc, 4);

$tchcd_enc=isset($_GET['tchcd'])?$_GET['tchcd']:' ';
$tchcd=$cryptoGraph->decode($tchcd_enc, 4);
$flag=isset($_GET['flag'])?$_GET['flag']:' ';

$db=new database();
$emp_exist=@$db->fetch_table(
				'
					select * from ehrms_dise_teacher
					where tchcd="'.$tchcd.'" and
						  teacher_id_pk="'.$teacher_id_pk.'"
				'
				);
/*if(count($emp_exist)==0 || count($emp_exist)==NULL){
	header("Location:../../dashboard.php");
}*/

/*else if(count($emp_exist)==1){
	if($flag=='edit'){
		if($emp_exist[0]['form_status']!='3' || $emp_exist[0]['form_status']!='4'){
			header("Location:../../dashboard.php");
	}else if(!$flag){
		if($emp_exist[0]['form_status']!='2'){
			header("Location:../../dashboard.php");
		}
	}
}*/
/*$teacher_id_pk=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/
?>
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
</style>
<?php
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Personal Details of the Employee submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Data insertion failed. Please try again...</div>';
}
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Dashboard | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>
<?
if($_GET['flag']=='edit'){
function get_emp($id){
	$db  = new database();
	
	$obj_crpto = new cryptography();
	
	
	$teacher_id_pk =  $obj_crpto->decode($_GET['teacher_id_pk'],4);
	$tchcd =  $obj_crpto->decode($_GET['tchcd'],4);

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
										tch_mob_no,
										emp_mail_id,
										form_status,
										status
							FROM ehrms_dise_teacher_primary
							WHERE teacher_id_pk = '".$teacher_id_pk."'
							and tchcd = '".$tchcd."'
							and schcd = '".$tchcd =  $obj_crpto->decode($_SESSION['school_dise'], 4)."'
	
	");
	return $data;
}

/*if (base64_decode($_GET['teacher_id_pk'], true) === false)
{
	$msg='<span style=color:#D83118; font-weight:bolder;">Not a valid data. Please try again...</span>';
    //echo 'Not a valid data';
	//exit;
}*/

$emp_data = get_emp($_GET['teacher_id_pk']);
/*foreach($data as $data_key)
{
	print $teacher_fname= $data_key[0]['tchname'];
}
*/
//print_r ($emp_data);
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
		
		$landline_no=$emp_data[0]['emp_land_no'];
		$mobile_no=$emp_data[0]['tch_mob_no'];
		$email=$emp_data[0]['emp_mail_id'];
		
		$form_status=$emp_data[0]['form_status'];
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------
}
?>

<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>

<script type="text/javascript">

function validContact(){
	if(document.getElementById('present_address_state').value==''){
		alert("Please Select State.");
		document.getElementById('present_address_state').focus();
		return false;
	}
	if(document.getElementById('present_town_vill').value == 0){
	//if(document.getElementById('present_town_vill').value.search(/([\<])([^\>]{1,})*([\>])/) !=-1){
		alert("Please Enter Town/Village.");
		document.getElementById('present_town_vill').focus();
		return false;
	}
	
	if(document.getElementById('present_pin').value == 0){
		alert("Please Enter Pin Number.");
		document.getElementById('present_pin').focus();
		return false;
	}
	if(document.getElementById('present_address_state').value==19){
		if(document.getElementById('present_city_district').value == ''){
			alert("Please Enter District.");
			document.getElementById('present_city_district').focus();
			return false;
		}
	}else{
		if(document.getElementById('present_others_dist').value == ''){
			alert("Please Enter District.");
			document.getElementById('present_others_dist').focus();
			return false;
		}
	}
	<!-----------------------Permanent validation---------------------->
	if(document.getElementById('permanent_address_state').value==''){
		alert("Please Select State.");
		document.getElementById('permanent_address_state').focus();
		return false;
	}
	if(document.getElementById('permanent_town_vill').value == 0){
		alert("Please Enter Town/Village.");
		document.getElementById('permanent_town_vill').focus();
		return false;
	}
	
	if(document.getElementById('permanent_post_office').value == 0){
		alert("Please Enter Post Office.");
		document.getElementById('permanent_post_office').focus();
		return false;
	}
	if(document.getElementById('permanent_pin').value == 0){
		alert("Please Enter Pin Number.");
		document.getElementById('permanent_pin').focus();
		return false;
	}
	if(document.getElementById('permanent_address_state').value==19){
		if(document.getElementById('permanent_city_district').value == ''){
			alert("Please Enter District.");
			document.getElementById('permanent_city_district').focus();
			return false;
		}
	}else{
		if(document.getElementById('permanent_others_dist').value == ''){
			alert("Please Enter District.");
			document.getElementById('permanent_others_dist').focus();
			return false;
		}
	}
	if(document.getElementById('email').value!=0){
		if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Please Enter A Valid Email Id.");
				document.getElementById('email').focus();			
				return false;
		}
	}
}
	

$(document).ready(function() {
    $('#address_equal').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $("#permanent_address_state").val($("#present_address_state").val());
			$("#permanent_house_no").val($("#present_house_no").val());
			$("#permanent_town_vill").val($("#present_town_vill").val());
			$("#permanent_pin").val($("#present_pin").val());
			$("#permanent_street").val($("#present_street").val());
			$("#permanent_post_office").val($("#present_post_office").val());
			$("#permanent_street").val($("#present_street").val());  
			
			if(document.getElementById('permanent_address_state').value==''){
				$('#permanent_wb_dist_label').hide();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').hide();
				$('#permanent_others_dist').val('');  
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
			}  
			if(document.getElementById('present_address_state').value==19){
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
			if(document.getElementById('present_address_state').value=='others'){
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				$('#permanent_wb_dist_label').show();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').show();
				$("#permanent_others_dist").val($("#present_others_dist").val());
			}
        }
    });
    
});

$(document).ready(function() {
    $('#present_address_state').change(function(event) {  //on click 
			if(document.getElementById('present_address_state').value==''){
				$('#present_wb_dist_label').hide();
				$('#present_wb_dist_field').hide();
				$('#present_others_dist_field').hide();
				$('#present_others_dist').val('');  
				$('#present_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
			}
			if(document.getElementById('present_address_state').value==19){
				$('#present_wb_dist_label').show();
				$('#present_wb_dist_field').show();
				$('#present_others_dist_field').hide();
				$('#present_others_dist').val('');  
			}
			if(document.getElementById('present_address_state').value=='others'){
				$('#present_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				$('#present_wb_dist_label').show();
				$('#present_wb_dist_field').hide();
				$('#present_others_dist_field').show();
			}
    });
});

$(document).ready(function() {
    $('#permanent_address_state').change(function(event) {  //on click 
			if(document.getElementById('permanent_address_state').value==''){
				$('#permanent_wb_dist_label').hide();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').hide();
				$('#permanent_others_dist').val('');  
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
			}
			if(document.getElementById('permanent_address_state').value==19){
				$('#permanent_wb_dist_label').show();
				$('#permanent_wb_dist_field').show();
				$('#permanent_others_dist_field').hide();
				$('#permanent_others_dist').val('');  
			}
			if(document.getElementById('permanent_address_state').value=='others'){
				$('#permanent_city_district').removeAttr('selected').find('option:first').attr('selected','selected');
				$('#permanent_wb_dist_label').show();
				$('#permanent_wb_dist_field').hide();
				$('#permanent_others_dist_field').show();
			}
    });
	
	/*******************Visibility of Form*********************/
	if($('#form_show').css("visibility")=="hidden"){
		$('#form_show').removeClass("invisible").css('height', 'auto');
	}
	/*******************Form Submit****************************/
	$("#btnSubmit").click(function(){
    	$("form").submit();
  	});
    
});




</script> 
<link rel="stylesheet" href="themes/default/css/style.css" />
<!--CONTENT START-->
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<div class="mainContent float_l" style="width:1000px">
<!-- Common Back Button --->
	<?php require '../../common_back_btns.php'; ?>	
  <div class="dashboard-main"> 
    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
    <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3><?php
					  if(isset($_SESSION['location']['school_name'])){
                          echo $_SESSION['location']['school_name'];
                      } elseif(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['circle_name'])) {
                          echo $_SESSION['location']['circle_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                      }
                      ?></h3>
       </div>
   	<strong style="color:#E93437;"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
    <div id="form_show" class="dashcontenr invisible"> 
       
      <!-- <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>-->
     
        <div class="profile_heading3"></div>
	    <div class="border"></div>
        <br clear="all" />
        <br clear="all" />
        <form action="page/intra_ehrms/circle/profile_entry_contact_submit.php?tchcd=<?=$tchcd_enc;?>&teacher_id_pk=<?=$teacher_id_pk_enc;?>&flag=<?=$flag;?>" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validContact();">
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?=$teacher_id_pk?>" />
   				<input type="hidden" name="tchcd" id="tchcd" value="<?=$tchcd?>" />
                <input type="hidden" name="flag" id="flag" value="<?=$flag?>" />
                <input type="hidden" name="form_status" id="form_status" value="<?=$form_status?>" />
        <!---------------------------Present Address------------>
              <div id="" class="child3">
              <? if($msg){?>
              	<div class="float_l col_line"><?php echo $msg;?></div>
                    <div class="clear"></div> 
                <? }?>
                <? if($error_msg){?>
              	<div class="float_l col_line"><?php echo $error_msg;?></div>
                    <div class="clear"></div> 
                <? }?>    
                <div class="float_l col_line"><p>Present Address</p></div>
                <div class="dashed_line"></div>
                    <div class="float_l col3"><p>State <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> 
                    		<select name="present_address_state" id="present_address_state" class="login-input">
                                <option value="">-Please Select-</option>
                                <option value="19" <? if($present_address_state=='19'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($present_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select><br />
                     </div>
                     <div class="clear"></div>
                     
                    <div class="float_l col3"><p>House No.</p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="present_house_no" id="present_house_no" class="login-input upper_case" value="<?=$present_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <div class="float_l col3"><p>Street</p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="present_street" id="present_street" class="login-input upper_case" value="<?=$present_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="clear"></div>
                     
                    <div class="float_l col3"><p>Town/ Village <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="present_town_vill" id="present_town_vill" class="login-input upper_case" value="<?=$present_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <div class="float_l col3"><p>Post Office <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="present_post_office" id="present_post_office" class="login-input upper_case" value="<?=$present_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="clear"></div>
                   
                    <div class="float_l col3"><p>Pin <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" maxlength="6" name="present_pin" id="present_pin" class="login-input" value="<?=$present_pin?>" onKeyPress="return keyRestrict(event,'0123456789');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <?
                       // if($present_address_state=='19' || $present_address_state=='others'){
						?>
                     <div id="present_wb_dist_label" <? if($present_address_state=='19' || $present_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?> class="float_l col3"><p>District <span class="star_color">*</span></p></div>
                     	<? 
						//}
					 ?>
                     <?
                        //if($present_address_state=='19'){
						?>
                     <div id="present_wb_dist_field" <? if($present_address_state=='19'){?>style="display:block;"<? }else{?>style="display:none;"<? }?> class="float_l col2">
                    	<?php
                        	$db=new database();
							$district_fetch=$db->fetch_table("select * from ehrms_dise_location_master_district where state_id_fk='35'");
						?>
                        
                    	<select name="present_city_district" id="present_city_district" class="login-input">
                            <option value="" selected="selected">-Please Select-</option>
                            <?php
								foreach($district_fetch as $dist_dtls){
								?>	
                                <option value="<? echo $dist_dtls['district_id_pk']?>" <? if($present_city_district==$dist_dtls['district_id_pk']){ echo "selected";}?>><? echo $dist_dtls['district_name'];?></option>
								<?php	}
							?>
                        </select> 
                        
                    <br />
                    </div>
						<? 
					//}
					?>
                    <?
                    //if($present_address_state=='others'){
						?>
                    <div id="present_others_dist_field" style="display:none;" class="float_l col2"> <input type="text" autocomplete="off" name="present_others_dist" id="present_others_dist" class="login-input upper_case" value="<?=$present_others_dist?>" onKeyPress="return keyRestrict(event,'0123456789/\,abcdefghijklmnopqrstuvwxyz)(. ');"><br />
                     </div>
                     	<?
					//}
					?>
                    <div class="clear"></div>      
                    
                    
                    
                    <div class="float_l col_line"><p>Whether permanent address is equal to present address <input type="checkbox" autocomplete="off" name="address_equal" id="address_equal">
                    <label for="address_equal"><span></span></label></p></div>
                    <div class="clear"></div> 
                   	
                    
                    
                  <!---------------------------Permanenet Address------------>  
                    
                    <div class="float_l col_line"><p>Permanent Address</p></div>
                    <div class="dashed_line"></div>
                    <div class="float_l col3"><p>State <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> 
                    		<select name="permanent_address_state" id="permanent_address_state" class="login-input dropdown">
                                <option value="">-Please Select-</option>
                                <option value="19" <? if($permanent_address_state=='19'){ echo 'selected';}?>>West Bengal</option>
                                <option value="others" <? if($permanent_address_state=='others'){ echo 'selected';}?>>Others</option>
                            </select><br />
                     </div>
                     <div class="clear"></div>
                     
                    <div class="float_l col3"><p>House No.</p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="permanent_house_no" id="permanent_house_no" class="login-input upper_case" value="<?=$permanent_house_no?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <div class="float_l col3"><p>Street</p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="permanent_street" id="permanent_street" class="login-input upper_case" value="<?=$permanent_street?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="clear"></div>
                     
                    <div class="float_l col3"><p>Town/ Village <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="permanent_town_vill" id="permanent_town_vill" class="login-input upper_case" value="<?=$permanent_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <div class="float_l col3"><p>Post Office <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="permanent_post_office" id="permanent_post_office" class="login-input upper_case" value="<?=$permanent_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     <div class="clear"></div>
                   
                    <div class="float_l col3"><p>Pin <span class="star_color">*</span></p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" maxlength="6" name="permanent_pin" id="permanent_pin" class="login-input" value="<?=$permanent_pin?>" onKeyPress="return keyRestrict(event,'0123456789');"><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                    <?
                    //if($permanent_address_state=='19' || $permanent_address_state=='others'){
						?> 
                    <div id="permanent_wb_dist_label" <? if($permanent_address_state=='19' || $permanent_address_state=='others'){?>style="display:block;"<? }else{?> style="display:none;"<? }?> class="float_l col3"><p>District <span class="star_color">*</span></p></div>
                    	<?
					//}
					?>
                    <?
					//if($permanent_address_state=='19'){
						?>
                    <div id="permanent_wb_dist_field" <? if($permanent_address_state=='19'){?>style="display:block;"<? }else{?> style="display:none;"<? }?> class="float_l col2">
                    <?php
                        	$db=new database();
							$district_fetch_prm=$db->fetch_table("select * from ehrms_dise_location_master_district where state_id_fk='35'");
						?>
                    	<select name="permanent_city_district" id="permanent_city_district" class="login-input">
                            <option value="" selected="selected">-Please Select-</option>
                            <?php
								foreach($district_fetch_prm as $dist_dtls_prm){
								?>	
                                <option value="<? echo $dist_dtls_prm['district_id_pk']?>" <? if($permanent_city_district==$dist_dtls_prm['district_id_pk']){ echo "selected=selected";}?>><? echo $dist_dtls_prm['district_name'];?></option>
								<?php	}
							?>
                        </select> 
                    <br />
                    </div>
                    	<?
					//}
					?>
                    <?
					//if($permanent_address_state=='others'){
						?>
                    <div id="permanent_others_dist_field" <? if($permanent_address_state=='others'){?>style="display:block;"<? }else{?> style="display:none;"<? }?> class="float_l col2"> <input type="text" autocomplete="off" name="permanent_others_dist" id="permanent_others_dist" value="<?=$permanent_others_dist?>" class="login-input upper_case" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');"><br />
                     </div>
                     	<?
					//}
					?>
                    <div class="clear"></div> 
                    
                    <!---------------------Contact Details-------------------->
                    <div class="float_l col_line"><p>Contact Details</p></div>
                    <div class="dashed_line"></div>
                    <div class="float_l col3"><p>Land Tel. No.</p></div>
                    <div class="float_l col2"> <input type="text" autocomplete="off" name="landline_no" id="landline_no" value="<?=$landline_no?>" class="login-input" maxlength="11" onKeyPress="return keyRestrict(event,'0123456789');"/><br />
                     </div>
                     <div class="float_l col4"><p>&nbsp;</p></div>
                     <div class="float_l col3"><p>Mobile No.</p></div>
                    <div class="float_l col2"><input type="text" autocomplete="off" name="mobile_no" id="mobile_no" maxlength="10" value="<?=$mobile_no?>" class="login-input" onKeyPress="return keyRestrict(event,'0123456789');" /><br />
                     </div>
                     <div class="clear"></div>
                     
                    <div class="float_l col3"><p>Email Id</p></div>
                    <div class="float_l col2"><input type="text" autocomplete="off" name="email" id="email" value="<?=$email?>" class="login-input" onKeyPress="return keyRestrict(event,'0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.');"/><br />
                     </div>
                     <div class="clear"></div>
                     
             		  <br>
                	  <p style="border-top:1px dashed #27769F; text-align:center;">
                      <br>
                      <input type="button" name="sub_contact" id="btnSubmit" value="Save & Continue" class="login-submit" >
                </p>
            </div>
      	</form>
    </div>
  </div>
</div>
<br clear="all">