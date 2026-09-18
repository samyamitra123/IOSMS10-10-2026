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
$stake_level = $crypto->decode($_GET['stake_level'],4);

//var_dump($stake_level); die;
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = " Download Excel for Bank  | PRD | Govt. of West Bengal ";


//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------



function get_all_financial_year()
{

	$start_year='2019';
	$cur_year=date('Y');
	$end_year=date('Y');	
	$cur_month=date('m');
	$end_month=date('m');
	$month='04';
	$i=$start_year;
	$arr=array();
	$arr1=array();
	while($i<=$end_year)
	{
		if($i==$cur_year)
		{
			if($end_month<$month)
			break;
		}
		$arr['year']=$i.($i+1);
		$arr['f_year']=$i.'-'.($i+1);
		if($i==$cur_year)
			$arr['select_year']='yes';
		else
			$arr['select_year']='no';
		$i++;
		array_push($arr1,$arr);
	}
	
	return $arr1;
}

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
	$("#bill_rep_reset_div").hide();
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );

	$('#bill_rep').click(function(){ 
			var sec_tok = $('#sec_tok').val();
			var from_month = $('#from_month').val();
			var to_month = $('#to_month').val();
			var rep_year = $('#rep_year').val();
			var allotment_order_number = $('#allotment_order_number').val();
			var allotment_number = $('#allotment_number').val();
			var allotment_amount = $('#allotment_amount').val();
			
			//alert(rep_month);
			if($('#from_month').val()=="")
			{
				alert("Please Select Month!");
				$('#from_month').focus();
				return false;
			}
			else if($('#rep_year').val()=="")
			{
				alert("Please Select Year!");
				$('#rep_year').focus();
				return false;
			}
			
			else if(from_month!=""  && rep_year!="")
			{ 
				//$.post('<?= $config['base_url'] ?>page/all_moduls/dprdo_report/report_management_submit.php?sec_tok='+sec_tok+'&rep_month='+rep_month+'&rep_year='+rep_year, 
				$.post("allotment_submit.php",
				  {
					sec_tok: sec_tok,
					from_month: from_month,
					to_month: to_month,
					rep_year: rep_year,
					allotment_order_number: allotment_order_number,
					allotment_number: allotment_number,
					allotment_amount: allotment_amount
				  },
				function(data){
					//$('#bill_rep').prop('readonly',true);
					$("#report_table").html(data);
					$("#bill_rep_div").hide();
					$("#bill_rep_reset_div").show();
					//$('.bill_rep_reset').css('display','block');
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
                        <label for="inputPassword3" class="col-sm-3 col-form-label" style="color: #246a8e;">Allotment From Month: <span class="star_color">*</span></label>
                        <div class="col-sm-2" style="margin-left: -8%;">
                        <select class="form-control" id="from_month" name="from_month">
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
						
						<label for="inputPassword3" class="col-sm-3 col-form-label" style="color: #246a8e;">Allotment To Month: <span class="star_color">*</span></label>
                        <div class="col-sm-2" style="margin-left: -8%;">
                        <select class="form-control" id="to_month" name="to_month">
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
                    
					</div>
					
					<div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 col-form-label" style="color: #246a8e;">Allotment Year: <span class="star_color">*</span></label>
                        <div class="col-sm-2" style="margin-left: -5%;">
							<!--<select class="form-control" id="rep_year" name="rep_year">
							<option value="">--SELECT YEAR--</option>
							<? for($i=2015;$i<=date('Y');$i++){?>
							<option value="<?php echo $crypto->encode($i,3) ?>"><?= $i?></option>
							<? } ?>
							</select>-->
							
							<select class="form-control" name="rep_year" id="rep_year" >
								<option value="">-Select Year -</option>
								<?php
								$financial_arr=get_all_financial_year();
								//var_dump($financial_arr); die;
								foreach($financial_arr as $item=>$val)
								{ ?>
									<option value="<?php echo $crypto->encode($val['f_year'],3); ?>"  ><?= $val['f_year']?></option>
								<?php }
								?>
							</select>
					
					
                        </div>	
						
						<label for="inputPassword3" class="col-sm-3 control-label" style="color: #246a8e;">Allotment Amount: <span class="star_color">*</span></label>
						<div class="col-sm-3" style="align:left; margin-left: -11%;">
						  <input type="text" class="form-control" name="allotment_amount" id="allotment_amount" placeholder="Enter Allotment amount" autocomplete="off" >
						</div>
						
                    </div>
					
					<div class="row mb-3">
						<label for="inputPassword3" class="col-sm-3 control-label" style="color: #246a8e;">Allotment Order Number: <span class="star_color">*</span></label>
						<div class="col-sm-3" style="align:left; margin-left: -7%;">
						  <input type="text" class="form-control" name="allotment_order_number" id="allotment_order_number" placeholder="Enter Allotment Order Number" autocomplete="off" >
						</div>
						
						<label for="inputPassword3" class="col-sm-2 control-label" style="color: #246a8e;">Allotment Number: <span class="star_color">*</span></label>
						<div class="col-sm-3" style="align:left; margin-left: -3%;">
						  <input type="text" class="form-control" name="allotment_number" id="allotment_number" placeholder="Enter Allotment Number" autocomplete="off" >
						</div>
					</div>
					
					<div class="row mb-3">
						<label for="inputPassword3" class="col-sm-4 control-label" style="color: #246a8e;">Remaining Amount in Commissioner End: <span class="star_color">*</span></label>
						<div class="col-sm-3" style="align:left;">
						
						<?php  	//$db = new database();
								//$report_data = $db->fetch_table(" select allotment_amount from prd_commissioner_allotment ORDER BY commissioner_allotment_pk DESC "); ?>
						  <input type="text" class="form-control" name="remaining_amount" id="remaining_amount"  value="<?php echo 0; ?>" disabled >
						</div>
					</div>
					
					<div class="col-sm-12" style="margin-top:3%; width: 110%; margin-left: -13%;">
						<div class="col-sm-5"></div>
							<div align="center" id="report_table">
						</div>
					</div>
  
                    <br />
					<div class="row mb-3">
						<div class="col-sm-3" name="bill_rep_div" id="bill_rep_div">
							<button class="btn btn-success col-sm-offset-5 col-sm-4" type="submit" name="bill_rep" id="bill_rep" style="margin-left: 161%;" >Submit</button>
						</div>
						<div class="col-sm-3" name="bill_rep_reset_div" id="bill_rep_reset_div" >
							<button class="btn btn-danger col-sm-offset-5 col-sm-4" type="reset" name="bill_rep_reset" id="bill_rep_reset" style="margin-left: 161%;" >Reset</button>
						</div>
					</div>
				<!--</form>-->
				
				
					   
					   
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
