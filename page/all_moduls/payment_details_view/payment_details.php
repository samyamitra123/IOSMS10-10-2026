<?php
//error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();


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

if(
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

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
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}




//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//-----------------------------QUERY----------------------------------------------------------------------------------
$db = new database();


$requisition_type=$crypto->decode($_GET['requisition_type'],4);
$monthyear=$crypto->decode($_GET['monthyear'],4);
$month=substr($monthyear,4,2);
$year=substr($monthyear,0,4);

if($logged_user=='zpddo')
{
	$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];
}
if($logged_user=='EO')
{
	$stake_clause='ps_id_fk='.$_SESSION['location']['ps_id'];
}
if($logged_user=='BDO')
{
	$gp_fetch=$db->fetch_table(" SELECT gp_id_pk FROM prd_location_master_gp WHERE block_id_fk='".$_SESSION['location']['block_id']."'");
	for($i=0;$i<count($gp_fetch);$i++)
	{
		$gp_id_str.="'".$gp_fetch[$i]['gp_id_pk']."'";
		if($i!=count($gp_fetch)-1)
		{
			$gp_id_str=$gp_id_str.',';
		}
	}
	$stake_clause='gp_id_fk in ('.$gp_id_str.')';
}

function GetMonthString($n)
{
	$n=(int)$n;
    $timestamp = mktime(0, 0, 0, $n);
    return date("F", $timestamp);
}
function fun_gp_name($gp)
{ 
	$db = new database();
	$data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
	return $data[0]['gp_name'];
}

function date_frmt_change($original_date)
{
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") 
	{
		return "--";
	}
	else
	{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}

$payment_data= $db->fetch_table("SELECT
							sal.gp_id_fk, 
							sal.accountno,
							sal.bank_ifsc,
							sal.net,
							sal.payment_status,
							sal.payment_date,
							sal.utr_number,
							emp.emp_id_pk,
							emp.emp_first_name,
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_id_const
						FROM
							prd_monthly_salary_archive_final as sal
						LEFT JOIN 
							prd_employee_master as emp
						ON sal.emp_id_fk =emp.emp_id_pk
						WHERE
							emp.emp_status in (1,9) AND sal.salary_monthyear='".$monthyear."' AND sal.delete_status='1' AND is_saved='1'
							AND status_flag in ('3','4') AND sal.requisition_type='".$requisition_type."' AND sal.payment_status in('11','12')
							AND sal.".$stake_clause."
						ORDER BY emp.emp_first_name");
										
												

//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->

<script>
	$(document).ready(function(){
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );	  
	});
</script>

<div class="content">
    <!-- Common Back Button --->
    <?php require '../../common_back_btns.php'; ?>	
    
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
        <? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
        ?></h3>
    </div>
    <div class="row" id="cont">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
                <div class="col-sm-12">
                <h1 class="heading">View Payment Details</h1>
                <h2 class="heading">Month Year : <?php echo GetMonthString($month).", ".$year ?></h2>
                <div class="border"></div>
                <br>
                    <div class="emplist">
                    	<?php if(count($payment_data)>0)
						{ ?>
                    		<div class="downpdf" style="text-align:right; margin-right:20px;"><a href="<?= $config['base_url']?>page/all_moduls/payment_details_view/payment_excel.php?monthyear=<?php echo $crypto->encode($monthyear,4);?>&requisition_type=<?php echo $crypto->encode($requisition_type,4); ?>"><img src="<?= $config['base_url'] ?>themes/default/image/excel_download.png"/></a></div>
						<?php } ?>
                        <div class="school">	
                            <div class="table-responsive">
                                <table width="100%">
                                    <tr>
                                        <th>SL NO.</th>
                                        <?php if($logged_user=='BDO'){echo '<th>GP NAME</th>';} ?>
                                        <th>EMPLOYEE NAME</th>
                                        <th>EMPLOYEE ID</th>
                                        <th>ACCOUNT NUMBER</th>
                                        <th>IFSC CODE</th>
                                        <th>NET AMOUNT</th>
                                        <th>UTR NUMBER</th>
                                        <th>PAYMENT DATE</th>
                                        <th>PAYMENT STATUS</th>
                                    </tr>
                                    <?php 
                                    if(count($payment_data))
                                    {
                                        $count = 1;
                                        $total = 0;
                                        foreach($payment_data as $key)
                                        { ?>
                                            <tr style="text-align: right;">
                                            <td style="text-align: center;"><?php echo $count; ?></td>
                                            <?php if($logged_user=='BDO'){echo '<td style="text-align: left;">'.fun_gp_name($key['gp_id_fk']).'</td>';} ?>
                                            <td style="text-align: left;"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']; ?></td>
                                            <td><?php echo $key['emp_id_const']; ?></td>
                                            <td><?php echo $key['accountno']; ?></td>
                                            <td><?php echo $key['bank_ifsc']; ?></td>
                                            <td ><?php echo $key['net']; ?></td>
                                            <td><?php echo $key['utr_number']; ?></td>
                                            <td><?php echo date_frmt_change($key['payment_date']); ?></td>
                                             <td><?php if($key['payment_status']=='11'){echo "SUCCESS";} else if($key['payment_status']=='12'){echo "FAILED";}?></td>
                                           
                                            </tr>
                                            <?php
                                            $total += $key['net']; 
                                            $count += 1;
                                        } 
                                    } 
                                    else
                                    { ?>
                                        <tr>
                                        <td colspan="20" style="color:red;font-weight:bold">No Data Found</td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<?php

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
	
.school table th
{
	background-color: #3E9B96;
	border:1px solid #fff;
	color: #fff;
	padding: 2px;
	text-align:center;
}
.school table
{
	border-radius: 5px;
	-moz-border-radius: 0px;
	overflow: hidden;
	font-size: 14px;
}
.school
{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 20px;
}
.school .title h2
{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget
{
	font-size: 11px;
}
.school .action
{
	text-align: center;
}
.school .action .ui-button .ui-button-text
{
	padding: 20px 10px;
}

</style>

