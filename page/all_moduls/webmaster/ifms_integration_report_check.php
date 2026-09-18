<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

//require '../../../page_visite.php';

				/*echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";*/
			
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
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	|| !isset($_SESSION['user_info']['stake_abbr'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}


if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

	
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$db=new database();
$crypto = new cryptography();
$drn_check=$crypto->encode('drn_check',4);
$ben_check=$crypto->encode('ben_check',4);
$ack_check=$crypto->encode('ack_check',4);
$done_check=$crypto->encode('done_check',4);
//$banklist=$db->fetch_table("select bank_name,bank_code from ehrms_dise_bank_master order by bank_name");
//$banklist=$db->fetch_table("select distinct(bm.bank_code),bank_name from ehrms_dise_bank_master bm
//inner join ehrms_dise_teacher_primary tch on tch.bankname=bm.bank_code
//where substring(schcd,1,4)='".$_SESSION['location']['schcd']."'");

/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
?>
<!-- Common Back Button --->
<div class="content">
<div style="padding:10px">
	<?php require '../../common_back_btns.php'; ?>
</div>
	<div class="mainContent float_l" style="min-height: 400px;">
	   	<div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3>
					  <? echo $_SESSION['location']['state_name'];;
                      ?></h3>
       </div>
            <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
    <div class="col-sm-12">
		<div class="container">
			<div class="row">
				<div class="col-xs-11 ">
					<div class="offer offer-success">
						<div class="offer-content">
							<div class="row" id="cont">
								<div class="page_title">
									<div id="accordion">     
                                        <h1>DRN STATUS</h1>
                                        <div class="form-group">
                                        <label for="inputPassword3"  class="col-sm-4 control-label">DRN NUMBER<span class="star_color">*</span>:</label>
                                            <div class="col-sm-4">
                                            <input type="text" class="form-control" name="drn_number" maxlength="15" id="drn_number"  placeholder="DRN NUMBER" autocomplete="off" onkeyup="drn_value(this.value,'<?php echo $drn_check?>')" onKeyPress="return keyRestrict(event,'0123456789');">
                                            </div>
                                            </br>
                                             <div class="ajax_status_check_table"></div>
                                        </div>
                                        <h1>UnLock Bill</h1>
                                        <div class="form-group">
                                       
                                            <div class="col-md-8">
                                            <div class="col-md-6">
                                             <label for="login_id"  class="control-label">Login Id<span class="star_color">*</span>:</label>	
                                            <input type="text" class="form-control" name="login_id" maxlength="15" id="login_id"  placeholder="Login Id" autocomplete="off" onkeyup="login_value(this.value)">
                                            </div>
                                            <div class="col-md-6">
                                            <select name="type" id="typeofemployee">
                                            	<option value="">Select Stack</option>
                                            	<option value="ps">PS</option>
                                            	<option value="zp">ZP</option>
                                            	<option value="block">Block</option>
                                            	<option value="gp">GP</option>
                                            </select>
                                            <select name="type" id="month">
                                            	<option value="">Select Month</option>
                                            	<?php for($month=1;$month<=12;$month++):?>
                                            	<option value="<?php echo ($month<10)?'0'.$month:$month; ?>"><?php echo $month; ?></option>
                                                <?php endfor?>

                                            </select>                                            
                                            </div>
                                            </div>
                                            </br>
                                             <div class="ajax_show_table"></div>
                                        </div>      
                                        <h1>UnLock Bonus Bill</h1>
                                        <div class="form-group">
                                       
                                            <div class="col-md-8">
                                            <div class="col-md-6">
                                             <label for="login_id"  class="control-label">Login Id<span class="star_color">*</span>:</label>	
                                            <input type="text" class="form-control" name="login_id" maxlength="15" id="login_id"  placeholder="Login Id" autocomplete="off" onkeyup="login_bonus_value(this.value)">
                                            </div>
                                            <div class="col-md-6">
                                            <select name="type" id="btypeofemployee">
                                            	<option value="">Select Stack</option>
                                            	<option value="ps">PS</option>
                                            	<option value="zp">ZP</option>
                                            	<option value="block">Block</option>
                                            	<option value="gp">GP</option>
                                            </select>
                                            </div>

                                            </div>
                                            </br>
                                             <div class="ajax_bonus_show_table"></div>
                                        </div>

                                        <h1>Password Reset</h1>
                                        <div class="form-group">
                                       
                                            <div class="col-md-8">
                                            <div class="col-md-6">
                                             <label for="login_id"  class="control-label">Login Id<span class="star_color">*</span>:</label>	
                                            <input type="text" class="form-control" name="login_id" maxlength="15" id="rlogin_id"  placeholder="Login Id" autocomplete="off">
                                            <input type="button" name="changepass" value="Reset Password" onclick="resetPass(this.value)">
                                            </div>
                                            </div>
                                            </br>
                                             <div class="ajax_reset_show_table"></div>
                                        </div>                                                                                                                   

    						<!--<h2>BENEFICIARY FILE NAME CHECK</h2>-->
                            
                            <!--<div class="form-group">
                                        <label for="inputPassword3"  class="col-sm-4 control-label">DRN NUMBER<span class="star_color">*</span>:</label>
                                            <div class="col-sm-4">
                                            <input type="text" class="form-control" name="drn_number" maxlength="15" id="drn_number"  placeholder="DRN NUMBER" autocomplete="off" onkeyup="drn_value(this.value,'<?php echo $ben_check ?>')" onKeyPress="return keyRestrict(event,'0123456789');">
                                            </div>
                                            </br>
                                             <div class="ajax_status_check_table"></div>
                                        </div>
                                        
                                        <h2>.ACK FILE NAME CHECK</h2>
                                        
                                        <div class="form-group">
                                        <label for="inputPassword3"  class="col-sm-4 control-label">DRN NUMBER<span class="star_color">*</span>:</label>
                                            <div class="col-sm-4">
                                            <input type="text" class="form-control" name="drn_number" maxlength="15" id="drn_number"  placeholder="DRN NUMBER" autocomplete="off" onkeyup="drn_value(this.value,'<?php echo $ack_check ?>')" onKeyPress="return keyRestrict(event,'0123456789');">
                                            </div>
                                            </br>
                                             <div class="ajax_status_check_table"></div>
                                        </div>
                                        
                                         <h2>.DONE FILE  CHECK</h2>
                                        
                                        <div class="form-group">
                                        <label for="inputPassword3"  class="col-sm-4 control-label">DRN NUMBER<span class="star_color">*</span>:</label>
                                            <div class="col-sm-4">
                                            <input type="text" class="form-control" name="drn_number" maxlength="15" id="drn_number"  placeholder="DRN NUMBER" autocomplete="off" onkeyup="drn_value(this.value,'<?php echo $done_check ?>')" onKeyPress="return keyRestrict(event,'0123456789');">
                                            </div>
                                            </br>
                                             <div class="ajax_status_check_table"></div>
                                        </div>

                                        </div>  
                                       </div>
                                      </div>
                                     </div>
                                   </div>
                                  </div>
                                 </div>
                                </div>
                               </div>
                              </div>-->

<script>

    	/*$(document).ready(function(){
			$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			collapsible: true,
			//$('#drn_number').val('');
			//active: true
		});
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		}); */
	    function resetPass(thisobj)
	    {
	    	var login_id = $('#rlogin_id').val();
	    	console.log(login_id);
		  	 if(login_id != '')
		  	 {
				 $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_pass_reset.php?login_id='+login_id+'&action=resetpass',function(data){
		  	 
				console.log(data); 
				$('.ajax_reset_show_table').html(data);
			       });
				} 
	    }
        function DeleteBonus(thisobj)
          {
          	 console.log(thisobj);
  		  	 var login_id = thisobj;
		  	 var type = $('#btypeofemployee').val();
		  	// var month = $('#month').val();
		  	 if(login_id != '' && type != '')
		  	 {
				 $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_unlock_bonus_bill.php?login_id='+login_id+'&type='+type+'&action=delete',function(data){
		  	 
				console.log(data); 
				$('.ajax_bonus_show_table').html(data);
			       });
				}        	
          }
		function login_bonus_value(thisobj)
		  {
		  	 var login_id = thisobj;
		  	 var type = $('#btypeofemployee').val();
		  	// var month = $('#month').val();
		  	 if(login_id != '' && type != '')
		  	 {
				 $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_unlock_bonus_bill.php?login_id='+login_id+'&type='+type+'&action=view',function(data){
		  	 
				console.log(data); 
				$('.ajax_bonus_show_table').html(data);
			       });
				}
		  }		
		function login_value(thisobj)
		  {
		  	 var login_id = thisobj;
		  	 var type = $('#typeofemployee').val();
		  	 var month = $('#month').val();
		  	 if(login_id != '' && type != '')
		  	 {
				 $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_unlock_bill.php?login_id='+login_id+'&type='+type+'&month='+month+'&action=view',function(data){
		  	 
				console.log(data); 
				$('.ajax_show_table').html(data);
			       });
				}
		  }
		function onlockSalary(login_id, type, action)
		  {
		  	var month = $('#month').val();
		    $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_unlock_bill.php?login_id='+login_id+'&type='+type+'&month='+month+'&action='+action,function(data){
				console.log(data); 
				$('.ajax_show_table').html(data);
			});		  	
		  }
		function drn_value(k,l) 
		{

		var drn_no=k;
//alert(k);
			if(drn_no.length==15)
			{
				 $.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_ifms_report_check.php?drn_no='+drn_no+'&id='+l,function(data){
				console.log(data); 
				$('.ajax_status_check_table').html(data);
			});
			$('.ajax_status_check_table').show();
			}
			else
			{
				$('.ajax_status_check_table').hide();
			}
			
		}
		
		/*	$('#accordion').click(function()
		   {
				$('.ajax_status_check_table').hide();
				//$('#drn_number').val('0');
				if($('#drn_number').val()!='')
				{
					//alert(11);
					$('#drn_number').val('');
				}
           }); */

		function DeleteBill(drn_no)
		  {
		  	conformBox = confirm("Are You Sure you want to Delete Bill for DRN: "+drn_no);
		  	if(conformBox == true)
		  	  {
			  	$.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_bill_delete.php?drn_no='+drn_no,function(data){
					//console.log(data); 
					$('.ajax_status_check_table').html(data);
					$('.ajax_status_check_table').show();
				});
			  }
		  }
		
	 function DeleteRow(rowId)
	     {
		  	conformBox = confirm("Are You Sure you want to Delete?");
		  	if(conformBox == true)
		  	  {
			  	$.post('<?= $config['base_url'] ?>page/all_moduls/webmaster/ajax_bill_delete.php?rowid='+rowId,function(data){
			  		var loginId = $('#login_id').val();
			  		login_value(loginId);
					//console.log(data); 
					//$('.sal'+rowId).html('');
					//$('.ajax_status_check_table').show();
				});
			  }	     	
	     }
	
		
		
		
</script>

    			    <!--SIDEBAR START-->
		<style>
		
			.btn1{
				display: block;
				margin-bottom: 0;
				padding: 5px 10px;
				font-size: 12px;
				line-height: 1.5;
				border-radius: 3px;
				font-weight: 400;
				
				text-align: center;
				white-space: nowrap;
				vertical-align: middle;
				-ms-touch-action: manipulation;
				touch-action: manipulation;
				cursor: pointer;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
				border-radius: 4px;
				color: #fff;
				background-color: #5bc0de;
				border-color: #46b8da;
				}
		.sal_report{
		display:block;
		height:auto;
		width:120px;
		padding: 10px 20px;
		border-radius:6px;
		-moz-border-radius:6px;
		background-color: #5BC0DE;
		width: 222px;
		margin-bottom: 5px;
		color: #FFF;
		text-decoration: none;
		
	}
	
	.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		border:3px solid #fff;
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #5B7778;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 5px 10px;
}
ul.mynav {
list-style-type: none;
margin: 0;
padding: 0;
}
ul.mynav a:link, ul.mynav a:visited {
display: block;
font-weight: bold;
color: #FFFFFF;
background-color: #3BAAE3;
/*padding: 4px;*/
margin:2px;
border-radius:3px;
-moz-border-radius3px;
text-decoration: none;
text-transform: uppercase;
height:35px;
padding:8px 0px 1px 27px;
}
ul.mynav a:hover, ul.mynav a:active {
background-color: #1988c1;
}
ul.mynav img{
vertical-align: middle;
padding-right: 10px;
overflow-x:hidden;
}
.ui-accordion .ui-accordion-content{
margin: 0px;
padding: 10px;
overflow-x:hidden;
}
.ui-widget {
font-size:14px;
}
.accordion .mynav ul li a img{
border: 0px;
}
.accordion .mynav ul li a{
height:60px;
padding-left:10px;

}
.accordion h2{
margin: 0px;
padding-top: 10px;
font-size: 16px;

}


</style>



<style>
  .shape{    
    border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
	-ms-transform:rotate(360deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(360deg); /* Safari and Chrome */
	transform:rotate(360deg);
}
.offer{
	/*background:rgba(228, 232, 223, 0.59);*/
	background:rgba(243, 246, 240, 0.71); border:1px solid #ddd; box-shadow: 0 10px 20px rgba(148, 112, 29, 0.64); margin: 15px 0; overflow:hidden; margin-right:28px; padding-bottom:22px;padding-top:10px;
}

.shape {
	border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-radius{
	border-radius:7px;
}
.offer-danger {	border-color: #d9534f; }
.offer-danger .shape{
	border-color: transparent #d9534f transparent transparent;
}
.offer-success {	/*border-color: #9e9fb1;*/ }
.offer-success .shape{
	border-color: transparent #5cb85c transparent transparent;
}
.offer-default {	border-color: #999999; }
.offer-default .shape{
	border-color: transparent #999999 transparent transparent;
}
.offer-primary {	border-color: #428bca; }
.offer-primary .shape{
	border-color: transparent #428bca transparent transparent;
}
.offer-info {	border-color: #5bc0de; }
.offer-info .shape{
	border-color: transparent #5bc0de transparent transparent;
}
.offer-warning {	border-color: #f0ad4e; }
.offer-warning .shape{
	border-color: transparent #f0ad4e transparent transparent;
}

.shape-text{
	color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
	-ms-transform:rotate(30deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(30deg); /* Safari and Chrome */
	transform:rotate(30deg);
}	
.offer-content{
		padding:16px 100px 20px;
}
@media (min-width: 487px) {
  .container {
    max-width: 750px;
  }
  .col-sm-6 {
    width: 50%;
  }
}
@media (min-width: 900px) {
  .container {
    max-width: 970px;
  }

}

@media (min-width: 1200px) {
  .container {
    max-width: 1170px;
  }
  .col-lg-3 {
    width: 25%;
  }
  }
	</style>
 

    <div class="clear"></div>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
