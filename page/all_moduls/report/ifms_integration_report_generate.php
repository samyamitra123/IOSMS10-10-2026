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
			$("#stake,#year,#month,#type").change(function(){
				//alert('123');
				stk = $("#stake option:selected").val();
				mo = $("#month option:selected").val();
			    ye = $("#year option:selected").val();
				type = $("#type option:selected").val();
				if(
			    	$("#stake option:selected").val() != "" &&
					$("#month option:selected").val() != "" &&
			    	$("#year option:selected").val() != ""  &&
					$("#type option:selected").val() != ""
			    	){
				$("#show").html('<a class="btn btn-info btn-sm" href="ifms_details_report.php?stk='+stk+'&mo='+mo+'&ye='+ye+'&type='+type+'"" style="width: 90%;text-align: left;"><i class="fa fa-file-text"></i>  IFMS UPLOAD DETAILS</a>');
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
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
 <div class="col-sm-12">
   <div class="container">
     <div class="row">
        <div class="col-xs-11 ">
           <div class="offer offer-success">
              <div class="offer-content">
                 <div class="row" id="cont">
                      <div class="page_title">
               <h1>	Download Salary Report</h1>
                   <div class="border"></div>
                     </div>
        <div class="search_box" style="padding-left: 28%;">
        <br />
        <div id="form_show" class="form-horizontal">
                             <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">Salary Report Stake<span class="star_color">*</span>:</label>
                                  <div class="col-sm-4">
                                    <select class="form-control" id="stake">
                                    <option value="">---SELECT STAKE---</option>
                                    
                                    <option value="<?php echo $crypto->encode('gp',3) ?>">GP</option>
                                    <option value="<?php echo $crypto->encode('ps',3) ?>">PS</option>
                                    <option value="<?php echo $crypto->encode('zp',3) ?>">ZP</option>
                                    </select>
                                   </div>
                             </div>
            				 <br/>
                               <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">Salary Report Month<span class="star_color">*</span>:</label>
                                 <div class="col-sm-4">
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
                                <label for="inputPassword3" class="col-sm-4 control-label">Salary Report Year<span class="star_color">*</span>:</label>
                               <div class="col-sm-4">
                                <select class="form-control" id="year">
                                <option value="">---SELECT YEAR---</option>
                                <? for($i=2018;$i<=date('Y');$i++){?>
                                <option value="<?php echo $crypto->encode($i,3) ?>"><?= $i?></option>
                                <? } ?>
                                </select>
                                </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label"> Report Type <span class="star_color">*</span>:</label>
                                 <div class="col-sm-4">
                                    <select class="form-control" id="type">
                                    <option value="">---PLEASE SELECT---</option>
                                    <option value="<?php echo $crypto->encode('01',3) ?>">ALL FILE SENDING REPORT</option>
                                    <option value="<?php echo $crypto->encode('02',3) ?>">PAYMENT MANDATE NOT GENARATE REPORT </option>
                                    </select>
                                  </div>
                               </div>
                                <div class="form-group">
                                <div class="col-sm-offset-3 col-md-4"  id="show"></div>
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
       </div>
    </div>
</div>

<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		
</script>
 
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
