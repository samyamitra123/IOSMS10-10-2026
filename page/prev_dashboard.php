<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

ob_start();
session_start();
require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';
//require 'page_visite.php';
//include_once '../includes/library/file_cache.class.php';
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//Block list
//require_once 'block_list.php';

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Dashboard | eHRMS | Govt. of West Bengal ";

include('../page/layout/header.php'); 
include('../page/layout/menu.php'); 
//require '../includes/config/config.php'; 


$block01 = null;
if (isset($_SESSION['blocked_privilege']['01'])) {
	$block01 = $_SESSION['blocked_privilege']['01'];
}
$block0101 = null;
if (isset($_SESSION['blocked_privilege']['0101'])) {
	$block0101 = $_SESSION['blocked_privilege']['0101'];
}
$block0102 = null;
if (isset($_SESSION['blocked_privilege']['0102'])) {
	$block0102 = $_SESSION['blocked_privilege']['0102'];
}
$block0103 = null;
if (isset($_SESSION['blocked_privilege']['0103'])) {
	$block0103 = $_SESSION['blocked_privilege']['0103'];
}


$block02 = null;
if (isset($_SESSION['blocked_privilege']['02'])) {
	$block02 = $_SESSION['blocked_privilege']['02'];
}
$block0201 = null;
if (isset($_SESSION['blocked_privilege']['0201'])) {
	$block0201 = $_SESSION['blocked_privilege']['0201'];
}
$block0202 = null;
if (isset($_SESSION['blocked_privilege']['0202'])) {
	$block0202 = $_SESSION['blocked_privilege']['0202'];
}
$block0203 = null;
if (isset($_SESSION['blocked_privilege']['0203'])) {
	$block0203 = $_SESSION['blocked_privilege']['0203'];
}
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
						color: #000;
						background-color: #CCC;
						/*padding: 4px;*/
						margin:2px;
						border-radius:3px;
						-moz-border-radius3px;
						text-decoration: none;
						text-transform: uppercase;
						height:40px;
						padding:8px 0px 1px 27px;
					}
					ul.mynav a:hover, ul.mynav a:active {
						background-color: #;
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
							height:40px;
							padding-left:10px;
					}
					.accordion h2{
						margin: 0px;
						padding-top: 10px;
						font-size: 16px;
					}
					#headingOne,#headingTwo,#headingThree,#headingFour{
					background-color:#CCC;
					color:#000;
					}
				</style>

<br/>

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
    <div class="col-md-9">
   <?php  if(strlen($_SESSION['user_info']['stake_user']) == 10){ ?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <? if(isset($_SESSION['privilege']['01']) && $_SESSION['privilege']['01'] == "TRUE" && $block01 == null ) { ?>
  <div class="panel panel-default">
    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration:none">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        
          Master Directory Management
        
      </h4>
    </div>
    </a>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <div>
						<ul class ="mynav">
							
							<?php if(isset($_SESSION['privilege']['0101']) && $_SESSION['privilege']['0101'] == "TRUE" && $block0101 == null) { ?><li><a href='intra_prd/gp/profile_entry_basic.php'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Add New Employee</a></li><?php } ?>
							<?php if(isset($_SESSION['privilege']['0102']) && $_SESSION['privilege']['0102'] == "TRUE" && $block0102 == null) { ?><li><a href='intra_prd/gp/employee_edit_details.php'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Edit Employee Details</a></li><?php } ?>
							<?php if(isset($_SESSION['privilege']['0103']) && $_SESSION['privilege']['0103'] == "TRUE" && $block0103 == null) { ?><li><a href='intra_prd/gp/employee_view_list.php'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> View Employee Details</a></li><?php } ?>

						</ul>
					</div>
      </div>
    </div>
  </div>
  <? } ?>
  <? if(isset($_SESSION['privilege']['02']) && $_SESSION['privilege']['02'] == "TRUE" && $block02 == null ) { ?>
  <div class="panel panel-default">
  <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration:none">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        
           Transaction Directory Management
        
      </h4>
    </div>
    </a>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
       <div>
						<ul class ="mynav">
							
							<?php if(isset($_SESSION['privilege']['0201']) && $_SESSION['privilege']['0201'] == "TRUE" && $block0201 == null) { ?><li><a href='#'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Submit Salary requisition</a></li><?php } ?>
							<?php if(isset($_SESSION['privilege']['0202']) && $_SESSION['privilege']['0202'] == "TRUE" && $block0202 == null) { ?><li><a href='#'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> View Salary requisition</a></li><?php } ?>
							<?php if(isset($_SESSION['privilege']['0203']) && $_SESSION['privilege']['0203'] == "TRUE" && $block0203 == null) { ?><li><a href='#'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Employee Ranking</a></li><?php } ?>

						</ul>
					</div>
      </div>
    </div>
  </div>
   <? } ?>
  <? if(isset($_SESSION['privilege']['03']) && $_SESSION['privilege']['03'] == "TRUE" && $block03 == null ) { ?>
  <div class="panel panel-default">
  <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="text-decoration:none">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        
           Report Directory Management
        
      </h4>
    </div>
    </a>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <div>
						<ul class ="mynav">
							
							<?php //if(isset($_SESSION['privilege']['0102']) && $_SESSION['privilege']['0102'] == "TRUE" && $block0102 == null) { ?><li><a href='#'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Download Salary Requisition PDF</a></li><?php //} ?>
							
						</ul>
					</div>
      </div>
    </div>
  </div>
   <? } ?>
  <? if(isset($_SESSION['privilege']['04']) && $_SESSION['privilege']['04'] == "TRUE" && $block04 == null ) { ?>
    <div class="panel panel-default">
    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="text-decoration:none">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        
           Account Management
       
      </h4>
    </div>
     </a>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
       <div>
						<ul class ="mynav">
							
							<?php //if(isset($_SESSION['privilege']['0102']) && $_SESSION['privilege']['0102'] == "TRUE" && $block0102 == null) { ?><li><a href='#'><!--<img src="themes/default/image/ico/change_password.png" alt="" />--> Change Password</a></li><?php //} ?>
							

						</ul>
					</div>
      </div>
    </div>
  </div>
  <? } ?>
</div>
<?php } ?>
<?php
				echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";
			?>
    </div>
    <div class="col-md-3">
    <?php include('layout/right_sidebar_dashboard.php'); ?>
    </div>
    </div>
<?php include('layout/footer.php'); ?>