<?php
//error_reporting(0);

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';


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
$enc_token=md5('371'.$time_token);
$db = new database();
$cryptography= new cryptography();
/*$circle_arr = $db->fetch_table("SELECT circle_id_pk FROM ehrms_dise_location_master_circle
							WHERE circle_code='".$_SESSION['user_info']['stake_user']."'");
$circle_id_pk = $circle_arr[0]['circle_id_pk'];

$district_arr = $db->fetch_table("SELECT district_id_pk FROM ehrms_dise_location_master_district
							WHERE district_code=substring('".$_SESSION['user_info']['stake_user']."',3,2)");
$district_id_pk = $district_arr[0]['district_id_pk'];*/
//require_once '../../page_visite.php';
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Treasury Details| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------


?>
<style> input.upper { text-transform: uppercase; }</style>
<div class="content">
	<?php require '../../../common_back_btns.php'; ?>	
    <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <h3><?php
					  if(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].",".$_SESSION['location']['state_name'];
                      }
                      ?></h3></h2>
       </div>
        <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
		<h1 class="heading">Treasury Details</h1>
        <div class="border"></div>
        </br>
		<?php 
        if($_SESSION['msg']){
        	echo $_SESSION['msg'];
        	unset($_SESSION['msg']);
        }
        ?>
    <script type="application/javascript" src="themes/default/js/commonfunc.js"></script>
	<script>
		$(document).ready(function(){
			
			$('#btnSubmit').click(function(){
				var x=$('#tan_no').val();
			//x.toString().length;
			
			if((x.length)>12 ){
					alert('Please Enter Valid Contact no.');
					$('#tan_no').focus();
					return false;
				}
				
				
				if($('#district_name').val()==''){
					alert('Please Select District Name.');
					$('#district_name').focus();
					return false;
				}
				if($('#block_name').val()==''){
					alert('Please Select Sub Division Name.');
					$('#sub_div_name').focus();
					return false;
				}
				if($('#desig_block').val()==''){
					alert('Please Enter Designation of DPSC.');
					$('#desig_ddo').focus();
					return false;
				}
				/*if($('#sub_div_code').val()==''){
					alert('Please Enter Sub Division Code.');
					$('#sub_div_code').focus();
					return false;
				}*/
				if($('#treasury_block_code').val()==''){
					alert('Please Enter Treasury DDO Code.');
					$('#treasury_ddo_code').focus();
					return false;
				}
				/*if($('#line1').val()==''){
					alert('Please Enter Address Line1.');
					$('#line').focus();
					return false;
				}*/
				/*if($('#line2').val()==''){
					alert('Please Enter Address Line 2.');
					$('#line2').focus();
					return false;
				}*/
				if($('#treasury_name').val()==''){
					alert('Please Enter Treasury Name.');
					$('#treasury_name').focus();
					return false;
				}
				if($('#lead_bank_branch').val()==''){
					alert('Please Enter Bank Name.');
					$('#ps').focus();
					return false;
				}
				if($('#branch_name').val()=='' || $('#pin').val().length!='6'){
					alert('Please Enter Branch Name.');
					$('#branch_name').focus();
					return false;
				}
				
				/*if($('#dpp').val()==''){
					alert('Please Enter Duration of Permanent Post(for Approval).');
					$('#dpp').focus();
					return false;
				}*/
				
				
				
				return true;
			});
		});
		function block_name_fetch(district_code){
		   //alert(district_code);
		 $('#branch_name,#desig_block,#lead_bank_branch,#line1,#line2,#dpp,#treasury_block_code,#block_code,#treasury_name,#tan_no').val('');
			$('#loader').show();
			$.post('block_name.php',{district_code:district_code},function(data){
			    //alert(data) ;
				$('#block_name').html(data);
				$('#loader').hide();
			});
		}
		function block_code_fetch(block_code){
			
		  ////alert($('#line').val());
			$('#loader').show();
			$.post('block_code.php',{block_code:block_code},function(data){
			   
				$('#loader').hide();
				var item = JSON.parse(data) ;
				
				//alert(item);
				if(item=='1'){
					//alert("not");
					//$('#sub_div_code').removeAttr("readonly");
					document.getElementById('block_code').value = document.getElementById('block_name').value ;
					document.getElementById('treasury_block_code').value = '' ;
					document.getElementById('line1').value = '' ;
					document.getElementById('line2').value = '' ;
					document.getElementById('treasury_name').value = '' ;
					document.getElementById('lead_bank_branch').value = '' ;
					document.getElementById('branch_name').value = '' ;
				//	document.getElementById('dpp').value = '' ;
					document.getElementById('tan_no').value = '' ;
				//	document.getElementById('desig_block').value = '' ;
					document.getElementById('email').value = '' ;
					
				}
				else
				{
					//alert("Ok");
					//alert(item['distcd']);
						document.getElementById('line1').value = '' ;
						document.getElementById('line2').value = '' ;				
					document.getElementById('block_code').value = item['block_code'] ;
					document.getElementById('treasury_block_code').value = item['treasury_block_code'] ;
					 
					document.getElementById('treasury_name').value = item['treasury_name'] ;
					document.getElementById('lead_bank_branch').value = item['lead_bank'] ;
					document.getElementById('branch_name').value = item['lead_branch'] ;
					//document.getElementById('dpp').value = item['app_duration'] ;
					document.getElementById('tan_no').value = item['contact_no'] ;
					//document.getElementById('desig_block').value = item['desig_block'] ;
					document.getElementById('email').value = item['email'] ;
					
					var line=item['address_line2'];
					
					var line_split=line.split(',',2);
					
					if(!line_split[0])
					{
						document.getElementById('line1').value = '' ;
					}else{
								
					     document.getElementById('line1').value = line_split[0] ;
						}
					if(!line_split[1])
					{
						document.getElementById('line2').value = '' ;
					}else{
								
					    document.getElementById('line2').value = line_split[1] ;
						}	
					
				}
			});
		}
		
		
		</script>
    <? if($_GET['msg_er']){
              	echo $cryptography->decode($_GET['msg_er'],3);
				 }
				else {
				echo "";
				} 
				?>
                
        <form method="post" action="treasury_ins_upd.php" enctype="multipart/form-data" id="form_data" class="form-horizontal">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <?php
		//echo "select block_code,block_name from prd_location_master_block where CAST(block_code AS text) like '".$_SESSION['location']['district_code']."%' order by block_name";exit;
		
		?>
        			<div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">District Name<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <?php
                  
						//echo $item['district_code']." ".$item['district_name'] ;
						$name.="<option value='".$_SESSION['location']['district_code']."'>".$_SESSION['location']['district_name']."</option>";
						//echo $name ;
					
                    ?>
                    <select name="district_name" id="district_name" class="form-control" readonly="readonly">
                    <?php echo $name ; ?>
                    </select>
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>
                <div class="col-sm-3">
                 
                <?php 
				
				$block = $db->fetch_table("select block_code,block_name from prd_location_master_block where CAST(block_code AS text) like '".$_SESSION['location']['district_code']."%' order by block_name") ; 
				?>
                 <select name="block_name" id="block_name" class="form-control" onchange="return block_code_fetch(this.value);">
				 <option value=''>-Please Select-</option>
                 <?php
				foreach($block as $item){
					?>
				<option value=<?=$item['block_code']?>><?=$item['block_name']?></option>"
                <?php
		       }
               ?> </select>
                    <span style="display:none;" id="loader">&nbsp; <img src="<?php echo $config['base_url']; ?>themes/default/image/preloader.gif"  alt="loading" /></span>
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Block Code<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="block_code"  id="block_code" placeholder="Block Code" readonly="readonly">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Treasury Code<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="treasury_block_code"  id="treasury_block_code" placeholder="Enter Treasury Block Code" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>
                
                  
                   
                     
                  <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Line1</label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="line1"  id="line1" placeholder="Address Line1" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>    
                  
                  <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Line2</label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="line2"  id="line2" placeholder="Address Line2" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>       
                    
                  <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Treasury Name<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="treasury_name"  id="treasury_name" placeholder="Enter Treasury Name" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>     
                    
                  <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Lead Bank Branch<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="lead_bank_branch"  id="lead_bank_branch" placeholder="Enter Bank Name" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>       
                    
                   <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Branch Name<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="branch_name"  id="branch_name" placeholder="Enter Branch Name" onkeypress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-');">
                </div>
                </div>       
                    
                      
                    
                    
                 <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Contact No :</label>
                <div class="col-sm-3">
                <input type="text" class="form-control upper" autocomplete="off" name="tan_no"  id="tan_no" placeholder=" Contact No." onkeypress="return keyRestrict(event,'0123456789');">
                </div>
                </div>       
                   
                   
                   <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Email :</label>
                <div class="col-sm-3">
                <input type="email" class="form-control upper" autocomplete="off" name="email" style="text-transform:lowercase" id="email" placeholder=" abc@xyz" >
                </div>
                </div>   
                
                
                     <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                      <button type="submit" name="submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
                  </div>
         </form>
      
    
<div class="clear"></div>
</div>
</div>
</div>
</div>
<?php


//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
