<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$crypto = new cryptography();

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>IFMS details submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}?>
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
    <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
    ?></h3>
</div>
<!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <h1 class="heading">NEW SALARY INTREGRATION MODULE COMING SOON</h1>
                
                </br>
            
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
                <div id="form_show" > 
					
                    <form class="form-horizontal" id="gp_form" name="gp_form" method="post" >
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                           
                            <div class="col-sm-6">
                                <div class="col-sm-3">
                                    
                                    <?php
                                    for ($m=1; $m<=12; $m++) 
									{
										$month = date('F', mktime(0,0,0,$m));
										$num = date('m', mktime(0,0,0,$m));
										?>
										
                                    <?php
                                    }
                                    ?>				
                                   
                                </div>
                                <div class="col-sm-3">
                                   
                                    <?php
                                    $cryear = date("Y");
                                    $le = 10;
                                    for ($i=$cryear; $i > $cryear-$le; $i--) 
                                    { 
                                    ?>
                                    	
                                    <?php
                                    }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            
                            <div class="col-sm-3">
                            	
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                        
                            <div class="col-sm-2"></div>
                            
                            <div class="col-sm-3">
                            	
                            <div class="col-sm-3"></div>
                        </div>
                         <?php if($bill_no=='' && $bill_date=='')
						{ ?>
                            <div class="form-group">
                                <div class="col-sm-offset-5 col-sm-7">
                                    
                                </div>
                            </div>
                        <?php } ?>
                        
                    </form>
                    <?php if(count($find_bill)=='1')
                    {?>
                    	
                    <?php 
                    } ?>
                    <div class="ajax_text_link"></div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>


	
		
		


