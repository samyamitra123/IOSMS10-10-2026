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
$crypto = new cryptography();

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
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
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
//$banklist=$db->fetch_table("select bank_name,bank_code from ehrms_dise_bank_master order by bank_name");
//$banklist=$db->fetch_table("select distinct(bm.bank_code),bank_name from ehrms_dise_bank_master bm
//inner join ehrms_dise_teacher_primary tch on tch.bankname=bm.bank_code
//where substring(schcd,1,4)='".$_SESSION['location']['schcd']."'");
?>
<!-- Common Back Button --->
<div class="content">
<div style="padding:10px">
	<?php require '../../common_back_btns.php'; ?>
</div>
	<div class="mainContent float_l" style="min-height: 400px;">
		<script>
			$(document).ready(function(){
			$("#year,#month").change(function(){
				//alert('123');
				mo = $("#month option:selected").val();
			    ye = $("#year option:selected").val();
				if(
			    	$("#month option:selected").val() != "" &&
			    	$("#year option:selected").val() != "" 
			    	){
				$("#show").html('<a class="btn btn-info btn-sm" href="ifms_details_report.php?mo='+mo+'&ye='+ye+'" style="width: 50%;text-align: left;"><i class="fa fa-file-text"></i>Generate Report</a>');
				/*$("#show2").html('<a class="btn btn-info btn-sm" href="monthwise_salary_disburstment.php?mo='+mo+'&ye='+ye+'" style="width: 90%;text-align: left;"><i class="fa fa-file-text"></i>  Salary Amount Disbursement Report</a>');*/
				}
			   });
			   
			});
		</script>
    
        
        
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
    		 <div class="row" id="cont">
            <div class="page_title">
          <h1>	Download Salary Report</h1>
           <div class="border"></div>
            </div>
            <div class="search_box" style="padding-left: 28%;">
            <br />
            <div id="form_show" class="form-horizontal">
            <div class="form-group">
            	<label for="inputPassword3" class="col-sm-3 control-label">Salary Report Month: </label>
            			<div class="col-sm-3">
            				<select class="form-control" id="month">
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
							<select class="form-control" id="year">
							<option value="">---SELECT YEAR---</option>
							  <? for($i=2015;$i<=date('Y');$i++){?>
                              <option value="<?php echo $crypto->encode($i,3) ?>"><?= $i?></option>
							  <? } ?>
							</select>
            			</div>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-offset-3 col-md-4"  id="show"></div>
                        </div>
                         <!--<div class="form-group">
                        <div class="col-sm-offset-3 col-md-4"  id="show2"></div>
                        </div>-->
            			
            		
            </div>
          </div>  
      </div>
        
    </div>
    </div>
    			    <!--SIDEBAR START-->
		<style>
			/*.side-nav ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
			}
			.side-nav li {
				margin-bottom: 2px;
			}
			.side-nav a:link, .nav .side-nav a:visited {
				display: block;
				color: #FFFFFF;
				background-color: #bbb;
				text-align: center;
				padding: 4px;
				text-decoration: none;
				text-transform: uppercase;
				border-radius: 5px;
				-moz-border-radius: 4px;
			}
			.side-nav a:hover, .side-nav a:active {
				background-color: #7A991A;
			}
			.bav-a{
				color: #fff;
			}
			.msg-dig
			{
				display: block;
				color:#900;
				background-color:#ACDBEA;
				text-align: center;
				padding: 4px;
				text-decoration: none;
				text-transform: uppercase;
				border-radius: 5px;
				-moz-border-radius: 4px;
			}*/
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
	</style>
	

    <div class="clear"></div>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
