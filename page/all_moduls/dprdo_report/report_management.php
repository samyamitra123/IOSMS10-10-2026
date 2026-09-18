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



if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
	
$logged_user=$_SESSION['user_info']['stake_abbr']; 	
$crypto = new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";


//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------


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

<!-- Common Back Button --->
<div class="content">
    <div style="padding:10px">
    	<?php require '../../common_back_btns.php'; ?>
    </div>
	<div class="mainContent float_l" style="min-height: 350px;">
	<script>
	
		
$(document).ready(function() {
	
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );

	$('#bill_rep').click(function(){ 
			var sec_tok = $('#sec_tok').val();
			var rep_month = $('#rep_month').val();
			var rep_year = $('#rep_year').val();
			//alert(rep_month);
			if($('#rep_month').val()=="")
			{
				alert("Please Select Month!");
				$('#rep_month').focus();
				return false;
			}
			else if($('#rep_year').val()=="")
			{
				alert("Please Select Year!");
				$('#rep_year').focus();
				return false;
			}
			
			else if(rep_month!=""  && rep_year!="")
			{ 
				//$.post('<?= $config['base_url'] ?>page/all_moduls/dprdo_report/report_management_submit.php?sec_tok='+sec_tok+'&rep_month='+rep_month+'&rep_year='+rep_year, 
				$.post("report_management_submit.php",
				  {
					sec_tok: sec_tok,
					rep_month: rep_month,
					rep_year: rep_year
				  },
				function(data){
					$("#report_table").html(data);
				});
			}
			else
			{
				$('#report_table').html("");
			}
		});
});
	
    
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
    <div class="row" id="cont" >
        <div class="page_title">
            <h1>Month Year Selection For Amount Disbursment Report</h1>
            <div class="border"></div>
        </div>
        <div class="search_box" >
        <br /><br />
            <div id="form_show" class="form-horizontal" style="padding-left: 13%;">
                <!--<form action="" name="bill_rep_form" id="bill_rep_form" onSubmit="return valid_check();">-->
				<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <div class="row mb-3" >
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Report Month: </label>
                        <div class="col-sm-2" style="margin-left: -5%;">
                        <select class="form-control" id="rep_month" name="rep_month">
                        <option value="">--SELECT MONTH--</option>
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
                    
                        <label for="inputPassword3" class="col-sm-2 col-form-label" style="margin-left: 17%;">Report Year: </label>
                        <div class="col-sm-2" style="margin-left: -7%;">
							<select class="form-control" id="rep_year" name="rep_year">
							<option value="">--SELECT YEAR--</option>
							<? for($i=2015;$i<=date('Y');$i++){?>
							<option value="<?php echo $crypto->encode($i,3) ?>"><?= $i?></option>
							<? } ?>
							</select>
                        </div>	
                    </div>
                    <br />
                    <div class="form-group">
                        <button class="btn btn-success col-sm-offset-5 col-sm-1" type="submit" name="bill_rep" id="bill_rep" style="margin-left: 36%;" >View</button>
                    </div>
				<!--</form>-->
				
				<div class="col-sm-12" style="margin-top:3%; width: 110%; margin-left: -13%;">
					 <div class="col-sm-5"></div>
						<div align="center" id="report_table">
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
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
