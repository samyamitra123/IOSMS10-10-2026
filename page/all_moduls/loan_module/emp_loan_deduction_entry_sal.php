<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../includes/library/cryptography.class.php';

//   Kalyan Ghosh   16/3/2017   Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
//   Kalyan Ghosh   16/3/2017   Finish

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//   Kalyan Ghosh   16/3/2017    Start



if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}



header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

//   Kalyan Ghosh   16/3/2017    Finish

$crypto = new cryptography();

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------

$db = new database();
$salary_monthyear = date('Ym');

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables

$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic---------------------------------------------------------------------------------
?>
<?php require '../../common_back_btns.php'; ?>
<div class="page-content">
    <div class="content">
    <center>
        <h1 class="heading">LOAN DEDUCTION DETAILS</h1>
        <!--<h2 class="heading">Salary Month Year : <?php echo date('M').','.date('Y') ?></h2>-->
    </center>
    <br/>
      <div class="page-title">
       
		<div class="row" id="cont">
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
        

		<div class="border"></div>

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
			  	<div id="wait"><img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" /></div>
	  				<div class="dial"></div>
			    </div>
            
            
            
                    <?php
                    
                    require 'emp_loan_deduction_edit_sal.php';
                    
                    ?>

        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  <div class="clear"></div>
  
  
  <?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
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
        
    /*.school table th{
            background-color: #3E9B96;
            border:1px solid #fff;
            color: #fff;
            padding: 6px;
            text-align:center;
        }*/
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
   



	