<?php
session_start();

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require_once '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';

header("Strict-Transport-Security: max-age=63072000");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
/*
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}
*/

//var_dump($_REQUEST['id']); die;
$db=new database();
$officer_detail = $db->fetch_table(" SELECT master.*, desig.designation as designation, desig.designation_code as designation_code, 
								code.description as description FROM intra_pri_master as master
								INNER JOIN intra_pri_designation_master as desig ON CAST(master.designation AS character varying) = desig.designation_code 
								INNER JOIN prd_dise_code_master as code ON CAST(master.sex AS character varying) = code.code
								WHERE master.mobile_no = '".$_REQUEST['id']."' ");

?>

<div class="content">
<? require 'common_back_btns_intra_pri.php'; ?>

	<div class="welcome_msg">
		<?php 
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		
		//var_dump($officer_name);
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>


<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EDIT REGISTRATION PROFILE </h1>
<div class="border"></div>
</br>
<?php 
/*
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
}
*/
?>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" class="dashcontenr invisible"> 
<form class="form-horizontal" id="first_form" method="post" action="intra_pri_registration_profile_submit.php" onsubmit="return valid_code();">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />


	<div class="row mb-3">
		<div class="col-sm-2"></div>
			<label for="inputPassword3" class="col-sm-3 control-label">SELECT USER <span class="star_color">*</span></label>
			<div class="col-sm-3">
			<?php
			$db = new database();
			$arr_stake=$db->fetch_table("select * from intra_pri_designation_master WHERE higher_authority_code= '".$_SESSION['user_info']['stake_level_code']."' order by designation");
			?>
				
				<select class="form-control" name="designation_code" id="designation_code" onchange="open_block(this.value);">
					<option value="">-Please Select-</option>
					<?php foreach($arr_stake as $key){ ?>
					<option value="<?=$key['designation_code']; ?>" <? if($officer_detail[0]['designation_code']==$key['designation_code']){ echo "selected";}?>><?= $key['designation']; ?></option>
					<?php } ?>
				</select>
			</div>
		<div class="col-sm-3"></div>
	</div>
	
	
	<div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">STAKE <span class="star_color">*</span></label>
        <div class="col-sm-3">
			<input type="text" class="form-control upper_case" autocomplete="off" name="insert_stake" id="insert_stake" value="<?php echo $officer_detail[0]['login_level_stake']; ?>" readonly>
        </div>
        <div class="col-sm-3"></div>
    </div>
        
	<div class="row mb-3" id="block_div" <?php if($officer_detail[0]['stake_user_code']=='') {?> style='display:none;' <?php } ?> >
		<div class="col-sm-2"></div>
			<label for="inputPassword3" class="col-sm-3 control-label">BLOCK <span class="star_color">*</span></label>
			<div class="col-sm-3">
			<?php
			$db = new database();
			$arr=$db->fetch_table("select block_code,block_name,block_id_pk from prd_location_master_block WHERE district_id_fk= '".$_SESSION['user_info']['district_id_fk']."' order by block_name");
			?>
				<select class="form-control" name="block" id="block">
					<option value="">-Please Select-</option>
					<?php foreach($arr as $key){ $key['code']. '<br />';?>
					<option value="<?=$key['block_code'] ?>" <? if($officer_detail[0]['stake_user_code']==$key['block_code']){ echo "selected";}?> ><?= $key['block_name']; ?></option>
					<?php } ?>
				</select>
			</div>
		<div class="col-sm-3"></div>
	</div>
    
    
    
   
   
    
    <div class="row mb-3">
    	<div class="col-sm-2"></div>
    	<label for="inputPassword3" class="col-sm-3 control-label">NAME OF EMPLOYEE<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control upper_case" autocomplete="off" name="executive_name" id="executive_name" placeholder=" Officer Name"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?php echo $officer_detail[0]['officer_name']; ?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">SEX<span class="star_color">*</span></label>
    <div class="col-sm-3">
   				<?php
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
				?>
      <select class="form-control" name="drpSex" id="drpSex">
      <option value="">-Please Select-</option>
       <?php foreach($arr as $key){ $key['code']. '<br />';?>
         <option value="<?= $key['code']; ?>" <? if($officer_detail[0]['sex']==$key['code']){ echo "selected";}?> ><?= $key['description']; ?></option>
       <?php } ?>
      </select>
        </div>
        <div class="col-sm-3"></div>
    </div>
    
 
    <div class="row mb-3" id="designation_div" >
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">DESIGNATION<span class="star_color">*</span></label>
		<div class="col-sm-3" id="desig_block">
			<input type="hidden" name="desig" id="desig" value="<?php echo $officer_detail[0]['designation_code']; ?>" />
			<input type="text" class="form-control" autocomplete="off"  placeholder="Designation" value="<?php echo $officer_detail[0]['designation']; ?>" readonly >	
				
        </div>
        <div class="col-sm-3"></div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">MOBILE NO.<span class="star_color">*</span></label>
        <div class="col-sm-3">
			<input type="text" class="form-control upper_case" autocomplete="off" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?php echo $officer_detail[0]['mobile_no']; ?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    

   <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">EMAIL ID:<span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" name="email" id="email" placeholder="EMAIL ID"  autocomplete="off" onKeyPress="return keyRestrict(event,''0123456789/\_-abcdefghijklmnopqrstuvwxyz@#)(.'.');" value="<?php echo $officer_detail[0]['email_id']; ?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">DATE OF JOINING IN PRESENT POST: <span class="star_color">*</span></label>
        <div class="col-sm-3">
        	<input type="text" class="form-control" autocomplete="off" name="date_join" id="date_join" placeholder="DATE OF JOINING IN PRESENT POST"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .');" value="<?php echo date("d-m-Y",strtotime($officer_detail[0]['date_of_join_prsnt_post'])); ?>" >
        </div>
        <div class="col-sm-3"></div>
    </div>
    
   <div class="row mb-3">
		<div class="col-sm-2"></div>
        <label for="inputPassword3" class="col-sm-3 control-label">ROLE ASSIGN: <span class="star_color">*</span></label>
		
		<?php 	
				$db = new database();
				$arr_assign = $db->fetch_table("select DISTINCT master_cell_description, master_cell_code from intra_pri_role_assigment WHERE status ='1' ");
				//var_dump($arr_assign);
				foreach($arr_assign as $key=> $val){
				?>
				<div class="col-sm-1 " >
						<input type="checkbox" name="master_cell_code" id="master_cell_code" value="<?php echo $val['master_cell_code']; ?>" onClick="return open_assign();" >
						<label for="master_cell_code"><span></span></label>
					</div>
					<label for="inputPassword3" class="col-sm-1 control-label" id="label_<?php echo $val['master_cell_code']; ?>" ><b><?php echo $val['master_cell_description']; ?></b></label>
					
					
				<div id="sub_assign" style="display: none; margin-left: 55%;">	
				<?php 	
					$db = new database();
					$arr_sub_assign = $db->fetch_table("select * from intra_pri_role_assigment where master_cell_code= '".$val['master_cell_code']."' AND sub_menu IN('1','2','3','4','5','6','7','8','9','11','12','13') AND status ='1' ");
					//var_dump($arr_assign);
					foreach($arr_sub_assign as $key=> $val){
				?>
				
				<table width="100%">
					<tr>
					<td>
						<input type="checkbox" name="assign_code[]" id="<?php echo $val['code']; ?>" value="<?php echo $val['code']; ?>">
						<label for="<?php echo $val['code']; ?>"><span></span></label>
						<label for="inputPassword3" class="col-sm-2 control-label" id="label_<?php echo $val['code']; ?>" ><?php echo $val['description']; ?></label>
						</td>
					</tr>
					</table>
				<?php } ?>
				</div>
				<?php
				}				?>
    </div>
	
    
    
    
    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
        <div class="col-sm-offset-5 col-sm-7">
        	<button type="submit" class="btn btn-info">SAVE & CONTINUE</button>
        </div>
    </div>
  
  
</form>
<div class="clear"></div>
</div>
</div>
</div>
</div>
</div>
<? require '../layout/footer.php'; ?>
<script>
$(document).ready(function(){
		if($('#form_show').css("visibility")=="hidden"){
				$('#form_show').removeClass("invisible").css('height', 'auto');
			}
		});





function open_assign(){ 

if(document.getElementById('master_cell_code').checked==true)
		{
			$('#sub_assign').show();
			
			
		}
		else
		{
			
			$('#sub_assign').hide();
			
			
		}
		
}



</script>