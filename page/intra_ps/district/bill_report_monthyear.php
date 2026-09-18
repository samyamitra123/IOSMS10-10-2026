<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

//require '../../../page_visite.php';
$crypto = new cryptography();


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
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";


//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$db=new database();

?>
<!-- Common Back Button --->
<div class="content">
    <div style="padding:10px">
    	<?php require '../../common_back_btns.php'; ?>
    </div>
	<div class="mainContent float_l" style="min-height: 400px;">
	<script>
	
		function valid_check()
		{
			if($('#rep_month').val()=="")
			{
				alert("Please Select Month!");
				$('#rep_month').focus();
				return false;
			}
			if($('#rep_year').val()=="")
			{
				alert("Please Select Year!");
				$('#rep_year').focus();
				return false;
			}
		}
	
    
    </script>



    <div class="welcome_msg">
        <h2>WELCOME: <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        </h2> <h3>
        <? if(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'].",".$_SESSION['location']['state_name'];
        }
        ?></h3>
    </div>
	<style>
    
    .page_title{
    text-align: center;
    text-transform: uppercase;
    color: #006666;
    }
    </style>
    <div class="row" id="cont">
        <div class="page_title">
            <h1>Month Year Selection For Bill Report</h1>
            <div class="border"></div>
        </div>
        <div class="search_box" style="padding-left: 28%;">
        <br /><br />
            <div id="form_show" class="form-horizontal">
                <form action="ps_list_for_bill_report.php" method="post" name="bill_rep_form" id="bill_rep_form" onSubmit="return valid_check();">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Salary Report Month: </label>
                        <div class="col-sm-3">
                        <select class="form-control" id="rep_month" name="rep_month">
                        <option value="">---SELECT MONTH---</option>
                        <option value="<?php echo $crypto->encode('01',3) ?>">January</option>
                        <option value="<?php echo $crypto->encode('02',3) ?>">February</option>
                        <option value="<?php echo $crypto->encode('03',3) ?>">March</option>
                        <option value="<?php echo $crypto->encode('04',3) ?>">April</option>
                        <option value="<?php echo $crypto->encode('05',3) ?>">May</option>
                        <option value="<?php echo $crypto->encode('06',3) ?>">June</option>
                        <option value="<?php echo $crypto->encode('07',3) ?>">July</option>
                        <option value="<?php echo $crypto->encode('08',3) ?>">August</option>
                        <option value="<?php echo $crypto->encode('09',3) ?>">September</option>
                        <option value="<?php echo $crypto->encode('10',3) ?>">October</option>
                        <option value="<?php echo $crypto->encode('11',3) ?>">November</option>
                        <option value="<?php echo $crypto->encode('12',3) ?>">December</option>
                        </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Salary Report Year: </label>
                        <div class="col-sm-3">
                        <select class="form-control" id="rep_year" name="rep_year">
                        <option value="">---SELECT YEAR---</option>
                        <? for($i=2015;$i<=date('Y');$i++){?>
                        <option value="<?php echo $crypto->encode($i,3) ?>"><?= $i?></option>
                        <? } ?>
                        </select>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <button class="btn btn-success col-sm-offset-3 col-sm-2" type="submit" name="bill_rep" id="bill_rep">View</button>
                    </div>
				</form>
            </div>
        </div>  
    </div>
    
    </div>
</div>
    	
	
<div class="clear"></div>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
