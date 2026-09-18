<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

$crypto = new cryptography();
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

			$db = new database();
			$salary_monthyear = date('Ym');
			$school_code = $_SESSION['user_info']['stake_user'];
			/*$check_dise = $db->fetch_table("SELECT schcd FROM ehrms_monthly_salary_table_fix
							WHERE schcd='$school_code' AND salary_monthyear='$salary_monthyear'");
			if(!$check_dise[0]['schcd']){
				header('Location: '. $config['base_url'] . "page/login.php");
				exit;
			}*/
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Salary Requisition | PRD | Govt. of West Bengal ";

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
<div class="content">
	
			<div class="save_alert">
				<div id="saving" style="display: none">
					<div class="bor">
						<h3>Saving...<img height="20" src="<?php echo $config['base_url'] ?>themes/default/image/preloader.gif" /></h3>
					</div>
				</div>
			</div>
            
			<div id="dialog-confirm" title="Do you want to finalize your requisition?">
				<div class="loading" style="display: none; text-align: center;"><img height="20" src="<?php echo $config['base_url'] ?>themes/default/image/preloader.gif" /></div>
			</div>
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Draw Advance List</h1>
<?php /*?><h2 class="heading">Salary Month Year : <?php echo date('M').','.date('Y') ?></h2><?php */?>

<div class="border"></div>

</br>
</br>

<?php 
/*if($msg){
echo $msg;
echo "<br/>";
echo "<br/>";
}*/

?>
<div class="border_val"></div>
 <div id="sess_msg">
   <?   
  
   if(($_SESSION['msg']))
			{
				echo "<strong>".$_SESSION['msg']."</strong>";
				
				unset($_SESSION['msg']);
				
			}
			?>
            </div>
            <div id="dialog" title="Employee details">
			  	
	  			<div class="dial"></div>
			</div>
            
<div class="emplist">
<div class="school">
<div class="table-responsive">
<?php require_once 'emp_draw_advance_list_form.php'; 
  
	

?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>


<? require '../../../../page/layout/footer.php'; ?>
<style>
	
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
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

</style>




