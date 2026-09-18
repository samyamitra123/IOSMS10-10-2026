<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");



//ob_start();
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';


$cryptoGraph=new cryptography();

?>

<style>
.modal-body{
font-size: 10px;
}

</style>

<meta charset="UTF-8">


<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-3.6.0.min.js"></script>


<script type="text/javascript">

$(document).ready(function()
{
	//alert(11);
	$("#myModal").modal('show');
	$('#site_stop_modal').modal('show');

});

</script>

<!--<body>-->
<?php

ob_start();


if ( !isset($_SESSION['user_info']['stake_level']) || !isset($_SESSION['user_info']['stake_user_mob']) || !isset($_SESSION['user_info']['flag']) ){
		header('Location: '. $config['base_url'] . "index.php");
		exit;
}

//require_once 'block_list.php';

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables

$common['title'] = "Dashboard | P&RD | Govt. of West Bengal ";

include('../../page/layout/header.php'); 
include('../../page/layout/menu.php'); 



?>
<style>
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
	}
	.ui-accordion .ui-accordion-content{
	margin: 0px;
	padding: 10px;
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


<script>
	$(function() {
	$( "#accordion" ).accordion({
	heightStyle: "content",
	collapsible: true,
	active: false
	});
	});
</script>

<br/>

	<div class="welcome_msg">
		<?php 
		$db = new database();
		$Query = " SELECT * FROM intra_pri_master as ipm left join intra_pri_designation_master as ipdm ON ipm.designation = ipdm.designation_code::integer WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ";

		$officer_name = $db->fetch_table($Query);
		
		//var_dump($officer_name);
		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?> (<?php echo $officer_name[0]['designation'];?>)</h3>
    </div>
	

<?php 
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
	
}
	
	
	$service_menu = $db->fetch_table(" SELECT role_assign FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' AND active_status='1' ");
	//echo $service_menu[0]['role_assign'];exit;

	$service_menu_ex = explode(',',$service_menu[0]['role_assign']);
	//var_dump($service_menu_ex); die;
	//print_r($service_menu_ex);exit;



 
?>


<div class="row" id="cont">
         
        <div class="col-md-9">
            <div class="mainContent float_l">
                <div class="dashboard-main">
                    <div class="dashcontenr" id="dashcontenr">
                        <div id="accordion">
                        
							
							
							<?php
							

								//echo $SESSION['user_info'];exit;

							/*************************** DPRDO Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '33'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>		
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            <li><a href='view_intra_pri_transfer_outside.php'>NOC to Accommodate the Employee For Transfer & Posting </a></li>
                                        </ul>
                                    </div>
									
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' ");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>	
                                        </ul>
                                    </div>	
							<?php  
							
							} 
							
							/*************************** DPRDO Login END *****************************************************/
							
							
							
							
							
							/*************************** DPRDO SO AND DPRDO Operator Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '84' ||$_SESSION['user_info']['stake_level_code'] == '81' ||$_SESSION['user_info']['stake_level_code'] == '86' ){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                        </ul>
                                    </div>
									
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' ");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                           
                                        </ul>
                                    </div>	
							<?php  
							
							} 
							
							/*************************** DPRDO SO AND DPRDO Operator Login END *****************************************************/
							
							
							
							
							/*************************** DPRDO Clerk Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '83'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                        </ul>
                                    </div>
									
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' ");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                           
                                        </ul>
                                    </div>	
							<?php  
							
							} 
							
							/*************************** DPRDO Clerk Login END *****************************************************/
							
							
							
							
							
							/*************************** ADM&AEO Login Start *****************************************************/
							
									if($_SESSION['user_info']['stake_level_code'] == '70'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->		
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
							<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' ");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>                                        
                                        
                                         <!--<li><a href='intra.php'>Compassionate employment </a></li>
                                          <li><a href='intre_pri_overage_condonation_form.php'>Over Age Condonation </a></li>
                                           <li><a href='view_intra_pri_service.php'>Compassionate employment </a></li>-->
											<?php //foreach($service_menu_ex as $val){
											//$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' and role_assign in() ");
												?>
												<!--<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>-->
											<?php //} ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <!--<li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>-->	
                                        </ul>
                                    </div>	
							<?php  
							
							} 
							
						/*************************** ADM&AEO Login END*****************************************************/	
							
							
					
							
							
							/*************************** ADM Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '51'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
											<!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>	
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}

								/*************************** ADM Login END *****************************************************/	



							/*************************** AEO Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '75'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
								
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1' ");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>   
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** AEO Login END *****************************************************/
							
							
							
							/*************************** DM Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '50'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** DM Login END *****************************************************/
							
							
							
							/*************************** COMMISSIONER Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '65' || $_SESSION['user_info']['stake_level_code'] == '87' || $_SESSION['user_info']['stake_level_code'] == '88' || $_SESSION['user_info']['stake_level_code'] == '89' || $_SESSION['user_info']['stake_level_code'] == '90' || $_SESSION['user_info']['stake_level_code'] == '91'  || $_SESSION['user_info']['stake_level_code'] == '92'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->		
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** COMMISSIONER Login END *****************************************************/
							
							
							
							/*************************** Deputy Secratry Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '66'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Deputy Secratry Login END *****************************************************/
							
							
							
							/*************************** Joint Secratry Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '67'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Joint Secratry Login END *****************************************************/
							
							
							
							/*************************** Additional Secratry Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '68'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Additional Secratry Login END *****************************************************/
							
							
							
							
							/*************************** Additional Chief Secratry Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '69'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Additional Chief Secratry Login END *****************************************************/
							
							
							/*************************** Additional Director Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '93' || $_SESSION['user_info']['stake_level_code'] == '64'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Additional Director Login END *****************************************************/
							
							
							
							/*************************** PDO (HQ) AND LDA Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '63' || $_SESSION['user_info']['stake_level_code'] == '55'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>                                           	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** PDO (HQ) AND LDA Login END *****************************************************/
							
							
							
							
							/*************************** HA (NGE) AND HA Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '62' || $_SESSION['user_info']['stake_level_code'] == '56'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** HA (NGE) AND HA Login END *****************************************************/
							
							
							
							
							/*************************** Supervisor Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '61'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Supervisor Login END *****************************************************/
							
							
							
							
							/*************************** UDA AND SO Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '60' || $_SESSION['user_info']['stake_level_code'] == '57'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>

                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** UDA AND SO Login END *****************************************************/
							
							
							
							
							
							
							/*************************** Assistant Secretary AND OSD Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '59' || $_SESSION['user_info']['stake_level_code'] == '58' ){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!--<li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
									
								<!--<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                            <li><a href='intre_pri_overage_condonation_form.php'>Overage Condonation </a></li>
                                            <li><a href='intra.php'>Condonation Ground </a></li>
                                            
                                        </ul>
                                </div>-->
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
							/*************************** Assistant Secretary AND OSD Login END *****************************************************/
							
							
							
							/*************************** BDO Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '34'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            <li><a href='intra_pri_registration.php'>Add New USER </a></li>
                                            <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
								
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1'");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            <li><a href='reset_password_intra_pri.php'>Reset User's Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
									/*************************** BDO Login END *****************************************************/
									
									
									
									
									
									
									
									/*************************** BDO Operator Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '82'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                           <!-- <li><a href='intra_pri_registration.php'>Add New USER </a></li>-->
                                           <!-- <li><a href='view_intra_pri_profile_history.php'> List Of Existing User </a></li>-->	
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
								
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1'");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
									/*************************** BDO operator Login END*******************************************/ 
									
									
									
									
									
							
							
							
							/*************************** Joint BDO Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '35'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                      
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
								
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1'");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
						/*************************** Joint BDO Login END *****************************************************/
							
							
							
							/*************************** BDO CLERK Login Start *****************************************************/
							
							
							if($_SESSION['user_info']['stake_level_code'] == '85'){
							
							?>
								<h3>Profile Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='user_profile_view.php'>Your Profile </a></li>
                                            	
                                        </ul>
                                    </div>
								
								<h3>Inbox</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='view_intra_pri_service.php'>Your Activity </a></li>
                                            
                                        </ul>
                                    </div>
								
								<h3>Services</h3>
								<div>
                                        <ul class ="mynav">
                                        
											<?php foreach($service_menu_ex as $val){
											$role_assign = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE sub_menu='".$val."' AND status ='1'");
												?>
												<li><a href='<?php echo $role_assign[0]['url']; ?>'><?php echo $role_assign[0]['description']; ?> </a></li>
											<?php } ?>
                                            
                                        </ul>
                                </div>
									
								<h3>Account Management</h3>
                                    <div>
                                        <ul class ="mynav">
                                            <li><a href='change_password_form_intra_pri_user.php'>Reset Your Password</a></li>
                                            	
                                        </ul>
                                    </div>	
							<?php  
							
							}
							
									/*************************** BDO CLERK Login END *****************************************************/
									
							?>

                        
                    </div>
                    
                   
                </div>
            </div>
		</div>
		</div>
            <div class="col-md-3">
                <?php include('right_sidebar_dashboard_intra_pri.php'); ?>
            </div>
        
    </div>

<div class="clear" style="margin-top: 1%;"></div>

<?php include('../layout/footer.php'); ?>

 