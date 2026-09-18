<?php
ob_start();
if(!isset($_SESSION)) 
{ 
session_start(); 
} 
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

)
{
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

error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>GP Profile submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false')
{
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}
else if($_GET['confirm'] == 'fails')
{
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data Alresdy Exists...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("select block_id_pk,block_code from prd_location_master_block where block_code='".$_SESSION['location']['block_code']."'");

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='407'");
$requisition_type=$requisition[0]['code'];

?>

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
        <? echo $_SESSION['location']['district_name'];
        ?></h3>
    </div>
    
    <!-- Latest compiled and minified JavaScript -->
   
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <h1 class="heading">Download Supplementary Bill &amp; Annexture </h1>
                <div class="border"></div>
                </br>
                <?php 
                if($msg){
                echo $msg;
                echo "<br/>";
                }
                if($error_msg){
                echo $error_msg;
                }
                ?>
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
                <div id="form_show" class="dashcontenr invisible"> 
                <form class="form-horizontal" id="bill_annex" name="bill_annex" method="post" action="download_treasury_bill_supplementary.php" onsubmit="return valid_code();">
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Select Year<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                            <select class="form-control upper_case" name="year" id="year">
                                <option value="">-Please Select-</option>
                                <?php
                                for($i=2015;$i<=date('Y');$i++)
                                {
                                ?>
                                    <option value="<?php echo $i?>"><?php echo $i; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Select Month<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                            <select class="form-control upper_case" name="month" id="month">
                                <option value="">-Please Select-</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-7">
                        	<button type="submit" name="submit" class="btn btn-info">SUBMIT</button>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
                </div>
                <? if(isset($_POST['submit']))
                {
						 
					$month=jdmonthname(gregoriantojd($_POST['month'], 1, 1), CAL_MONTH_GREGORIAN_LONG);
					$monthno=$_POST['month'];
					$year=$_POST['year'];
					$cryptograph=new cryptography();
					
					?>
					<script>
					
					document.getElementById('month').value='<?php echo $monthno;?>';
					document.getElementById('year').value='<?php echo $year;?>';
					<!--document.getElementById('cat').value='<?php // echo $_POST['sal_source'];?>';-->
					
					</script>
					<?php 
					if(date("Y")==$year && date("m")==$monthno)
					{
						$data=$db->fetch_table("select 									
												sum(basic) as basic,
												sum(da) as da,
												sum(hra) as hra,
												sum(ma) as ma,
												sum(cpf) as cpf,
												sum(pf_loan ) as pf_loan,
												sum(p_tax) as p_tax,
												sum(i_tax) as i_tax, 
												sum(spl_pay)as spl_pay, 
												sum(pf_deduct) as pf_deduct,
												sum(net) as net,
												bill.bill_no,
												bill.bill_entry_time,
												gp.gp_name
												from prd_employee_salary_save as sv
												
												inner join prd_block_bill_details as bill
												on bill.block_code=sv.block_code and bill.requisition_type=sv.requisition_type
												inner join prd_location_master_gp as gp	
												on CAST(gp.gp_code AS text)=sv.gp_code
												where sv.status_flag='3'
												and sv.category_id='1'
												and bill.block_code='".$_SESSION['user_info']['stake_user']."'
												and sv.salary_monthyear='".date('Ym')."'
												and bill.salary_monthyear='".date('Ym')."'
												AND sv.requisition_type='".$requisition_type."'
												group by 
												
												bill.bill_no,bill.bill_entry_time,gp.gp_name");
					}
					else
					{
						$data=$db->fetch_table
												("select sum(basic) as basic, 
												sum(da) as da,
												sum(hra) as hra,
												sum(ma) as ma, 
												sum(cpf) as cpf,
												sum(pf_loan ) as pf_loan,
												sum(p_tax) as p_tax,
												sum(i_tax) as i_tax,
												sum(spl_pay)as spl_pay,
												sum(pf_deduct) as pf_deduct, 
												sum(net) as net, bill.bill_no, 
												bill.bill_entry_time from prd_monthly_salary_archive_final as sv 
												inner join prd_block_bill_details as bill
												on bill.block_code=sv.block_code and bill.requisition_type=sv.requisition_type 
												where sv.status_flag='3' and  
												bill.block_code='".$_SESSION['user_info']['stake_user']."' and
												sv.salary_monthyear='".date('Ym')."' and
												bill.salary_monthyear='".date('Ym')."'
												AND sv.requisition_type='".$requisition_type."' 
												group by bill.bill_no,bill.bill_entry_time");
					}
					
					if(count($data)>0)
					{?>            
						<div class="emplist">
                            <div class="school">
                                <div class="table-responsive">
                                    <table width="100%">
                                        <tr>
                                            <th>BILLS AND ANNEXTURES FOR <?= strtoupper($month). "-".$year?></th>
                                            <th>DOWNLOAD</th>
                                        </tr>
                                        <tr>
                                            <td>Annexture-I of &nbsp;<?php echo ucwords(strtolower($_SESSION['location']['block_name']." ".$_SESSION['location']['district_name']));?>&nbsp;<?php echo date("F")."-".date("Y") ?></td>
                                            <td><a href="<?php echo $config['base_url'] ?>page/intra_prd/block/annexture1_supplementary_pdf.php?report_month=<?php echo $cryptograph->encode($monthno, 4); ?>&report_year=<?php echo $cryptograph->encode($year, 4);?>&bill_no=<?php echo $cryptograph->encode($data[0]['bill_no'], 4);?>&bill_date=<?php echo $cryptograph->encode($data[0]['bill_entry_time'], 4);?>" class="btn btn-success btn-sm"><i class="fa fa-cloud-download fa-lg"></i> Download</a></td>
                                        </tr>
                                        <tr>
                                            <td>P Tax Deduction of &nbsp;<?php echo ucwords(strtolower($_SESSION['location']['block_name']." ".$_SESSION['location']['district_name']));?>&nbsp;<?php echo date("F")."-".date("Y") ?></td>
                                            <td><a href="<?php echo $config['base_url'] ?>page/intra_prd/block/ptax_deduction_supplementary_pdf.php?report_month=<?php echo $cryptograph->encode($monthno, 4); ?>&report_year=<?php echo $cryptograph->encode($year, 4);?>&bill_no=<?php echo $cryptograph->encode($data[0]['bill_no'], 4);?>&bill_date=<?php echo $cryptograph->encode($data[0]['bill_entry_time'], 4);?>" class="btn btn-success btn-sm"><i class="fa fa-cloud-download fa-lg"></i> Download</a></td>
                                        </tr>
                                        <!--<tr>
                                            <td>T.R. Form No.10 of &nbsp;<?php echo ucwords(strtolower($_SESSION['location']['block_name']." ".$_SESSION['location']['district_name']));?>&nbsp;<?php echo date("F")."-".date("Y") ?></td>
                                            <td><a href="<?php echo $config['base_url'] ?>page/intra_prd/block/tr_form_10_pdf.php?report_month=<?php echo $cryptograph->encode($monthno, 4); ?>&report_year=<?php echo $cryptograph->encode($year, 4);?>&bill_no=<?php echo $cryptograph->encode($data[0]['bill_no'], 4);?>&bill_date=<?php echo $cryptograph->encode($data[0]['bill_entry_time'], 4);?>" class="btn btn-success btn-sm"><i class="fa fa-cloud-download fa-lg"></i> Download</a></td>
                                        </tr>-->
                                        <tr>
                                            <td>T.R. Form No.31 of &nbsp;<?php echo ucwords(strtolower($_SESSION['location']['block_name']." ".$_SESSION['location']['district_name']));?>&nbsp;<?php echo date("F")."-".date("Y") ?></td>
                                            <td><a href="<?php echo $config['base_url'] ?>page/intra_prd/block/tr_form_31_supplementary_pdf.php?report_month=<?php echo $cryptograph->encode($monthno, 4); ?>&report_year=<?php echo $cryptograph->encode($year, 4);?>&bill_no=<?php echo $cryptograph->encode($data[0]['bill_no'], 4);?>&bill_date=<?php echo $cryptograph->encode($data[0]['bill_entry_time'], 4);?>" class="btn btn-success btn-sm"><i class="fa fa-cloud-download fa-lg"></i> Download</a></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
						</div>	
					<?	
					} 
					else
					{ ?>
						<div class="alert alert-danger" style="text-align:center;"><strong>No Data Found</strong></div>
					<? 
					} 
				}
                ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    
</div>
<? require '../../../page/layout/footer.php'; ?>

<script>
$(document).ready(function(){
	if($('#form_show').css("visibility")=="hidden"){
		$('#form_show').removeClass("invisible").css('height', 'auto');
	}
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
});

function valid_code()
{
	if($('#year').val()=='')
	{
		alert('Please Select Year Of Report.');
		$('#year').focus();
		return false;
	}
	if($('#month').val()=='')
	{
		alert('Please Select Month Of Report.');
		$('#month').focus();
		return false;
	}
}
</script>

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
	padding: 8px;
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
