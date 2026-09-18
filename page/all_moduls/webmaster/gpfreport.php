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
	|| !isset($_SESSION['user_info']['stake_abbr'])
	

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
$crypto = new cryptography();

$Query = "SELECT count(*) as totemployeerecdone from prd_gpf_request_master ";
$totemployeerecdone = $db->fetch_table($Query);
$totemployeerecdone = $totemployeerecdone[0]['totemployeerecdone'];


$Query = "SELECT count(*) as totemployeedirect from prd_gpf_request_master WHERE status = '1' AND pfaccno!=''";
$totemployeedirect = $db->fetch_table($Query);
$totemployeedirect = $totemployeedirect[0]['totemployeedirect'];

$Query = "SELECT count(*) as totemployeepromoted from prd_gpf_request_master WHERE status = '3'";
$totemployeepromoted = $db->fetch_table($Query);
$totemployeepromoted = $totemployeepromoted[0]['totemployeepromoted'];


$Query = "SELECT count(*) as totemployeepromoted from prd_gpf_request_master WHERE status = '2'";
$totemployeePending = $db->fetch_table($Query);
$totemployeePending = $totemployeePending[0]['totemployeepromoted'];

$Query = "SELECT ps_id_pk,ps_name,ps_code from prd_location_master_panchayat_samiti WHERE ngipf_status = '0' AND ps_status IN (2,4)";
$psNotSendNgipf = $db->fetch_table($Query);
$countOfPS = count($psNotSendNgipf);
$psIdsNoInNgipf = array();
foreach($psNotSendNgipf as $data)
{
	$psIdsNoInNgipf[] = $data['ps_id_pk'];
}
$psIdsNoInNgipf = implode(', ',$psIdsNoInNgipf);
$Query = "SELECT count(*) as total from prd_employee_master as pem
          LEFT JOIN prd_location_master_panchayat_samiti lmb
          ON pem.ps_id_fk = lmb.ps_id_pk
          WHERE lmb.ps_status =2 AND pem.ps_id_fk > 0 AND pem.gpf_acc_no = '0' AND pem.ropa_status =1 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1  AND pem.emp_status_deputation=0";
$psEmplNotSendNgipf = $db->fetch_table($Query);
$psEmplNotSendNgipf = $psEmplNotSendNgipf[0]['total'];

$Query = "SELECT block_id_pk,block_name,block_code, gp_id_pk, gp_name, gp_code from prd_location_master_block as lmb   
          LEFT JOIN prd_location_master_gp as lmg ON lmb.block_id_pk = lmg.block_id_fk WHERE lmb.ngipf_status = '0' AND lmb.block_status = 0";
$psNotSendNgipf = $db->fetch_table($Query);
$countOfGP = count($psNotSendNgipf);
$psIdsNoInNgipf = array();
foreach($psNotSendNgipf as $data)
{
	$psIdsNoInNgipf[] = "'".$data['gp_id_pk']."'";
}
$psIdsNoInNgipf = implode(', ',$psIdsNoInNgipf);

$Query = "SELECT count(*) as total from prd_employee_master WHERE gp_id_fk IN (".$psIdsNoInNgipf.") AND emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0";
$gpEmplNotSendNgipf = $db->fetch_table($Query);
$gpEmplNotSendNgipf = $gpEmplNotSendNgipf[0]['total'];

$Query = "SELECT count(*) as total from prd_employee_master WHERE zp_id_fk != 0 AND emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0";
$zpEmplNotSendNgipf = $db->fetch_table($Query);
$zpEmplNotSendNgipf = $zpEmplNotSendNgipf[0]['total'];

$Query = "SELECT count(*) as total from prd_employee_master WHERE emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0";
$totalEmployee = $db->fetch_table($Query);
$totalEmployee = $totalEmployee[0]['total'];

$Query = "SELECT count(*) as total from prd_employee_master WHERE gp_id_fk != 0 AND emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0";
$totalgpEmployee = $db->fetch_table($Query);
$totalgpEmployee = $totalgpEmployee[0]['total'];

$Query = "SELECT count(*) as total from prd_employee_master WHERE ps_id_fk != 0 AND emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0";
$totalpsEmployee = $db->fetch_table($Query);
$totalpsEmployee = $totalpsEmployee[0]['total'];

$Query = "SELECT count(*) as total from prd_employee_master WHERE zp_id_fk != 0 AND emp_cosolidated_pay=0 AND emp_status=1 AND emp_status_deputation=0 AND ropa_status=1 ";
//print($Query); exit;
$totalzpEmployee = $db->fetch_table($Query);
$totalzpEmployee = $totalzpEmployee[0]['total'];


$Query = "SELECT count(*) as total from prd_gpf_request_master as grm 
          LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
          WHERE pem.gp_id_fk != 0 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1 AND pem.emp_status_deputation=0 AND grm.pfaccno IS NOT NULL";
$totalgpEmployeeNgipf = $db->fetch_table($Query);
//print_r($totalgpEmployeeNgipf); exit;
$totalgpEmployeeNgipf = $totalgpEmployeeNgipf[0]['total'];

$Query = "SELECT count(*) as total from prd_gpf_request_master as grm 
          LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
          WHERE pem.ps_id_fk != 0 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1 AND pem.emp_status_deputation=0 AND grm.pfaccno IS NOT NULL";
$totalpsEmployeeNgipf = $db->fetch_table($Query);
//print_r($totalgpEmployeeNgipf); exit;
$totalpsEmployeeNgipf = $totalpsEmployeeNgipf[0]['total'];

$Query = "SELECT count(*) as total from prd_gpf_request_master as grm 
          LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
          WHERE pem.zp_id_fk != 0 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1 AND pem.emp_status_deputation=0 AND grm.pfaccno IS NOT NULL";
$totalzpEmployeeNgipf = $db->fetch_table($Query);
//print_r($totalgpEmployeeNgipf); exit;
$totalzpEmployeeNgipf = $totalzpEmployeeNgipf[0]['total'];


$Query = "SELECT count(*) as total from prd_gpf_request_master as grm 
          LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
          WHERE pem.gp_id_fk != 0 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1 AND pem.emp_status_deputation=0 AND grm.status =3";
$totalgpRejectedEmployeeNgipf = $db->fetch_table($Query);
//print_r($totalgpEmployeeNgipf); exit;
$totalgpRejectedEmployeeNgipf = $totalgpRejectedEmployeeNgipf[0]['total'];

$Query = "SELECT count(*) as total from prd_gpf_request_master as grm 
          LEFT JOIN prd_employee_master as pem ON grm.emp_id_const = pem.emp_id_const
          WHERE pem.ps_id_fk != 0 AND pem.emp_cosolidated_pay=0 AND pem.emp_status=1 AND pem.emp_status_deputation=0 AND grm.status =3";
$totalpsRejectedEmployeeNgipf = $db->fetch_table($Query);
//print_r($totalgpEmployeeNgipf); exit;
$totalpsRejectedEmployeeNgipf = $totalpsRejectedEmployeeNgipf[0]['total'];

/*$Query = "SELECT pgrm.*, pem.emp_id_pk from prd_gpf_request_master as pgrm
          LEFT JOIN prd_employee_master as pem ON pgrm.emp_id_const= pem.emp_id_const
          WHERE status = '3'";
$RejectedEmployee = $db->fetch_table($Query);


$Query = "SELECT pgrm.*, pem.emp_id_pk from prd_gpf_request_master as pgrm
          LEFT JOIN prd_employee_master as pem ON pgrm.emp_id_const= pem.emp_id_const
          WHERE status = '2'";
$PendingEmployee = $db->fetch_table($Query); */


?>

<style>

	.page_title{
	text-align: center;
	text-transform: uppercase;
	color: #006666;
	
	}

</style>

<script>
	function call_modal(m,n)
	{
		$('#user').val(m);
		$('#folder').val(n);
		$('#monthyear').modal('show');
	}

</script>

       
<!-- Common Back Button --->
<div class="content">
    <div style="padding:10px">
        <?php require '../../common_back_btns.php'; ?>
    </div>
    <div class="mainContent float_l" style="min-height: 400px;">
        <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
        <h3>
        <? echo $_SESSION['location']['state_name'];;
        ?></h3>
        </div>
            
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-11 ">
                            <div class="offer offer-success">
                                <div class="offer-content">
                                    <div class="row" id="cont">
                                        <div class="page_title">
                                        	<h1>Total Done: <?php echo $totemployeerecdone; ?></h1>
                                        	<- TOTAL PENDING:<?php echo $totemployeePending; ?> ->
             	                            <div style="float:left;">NGIPF APPROVED:<?php echo $totemployeedirect; ?></div>
                                        	<div style="float:right;">NGIPF REJECTED:<?php echo $totemployeepromoted; ?></div>
                                        	<p></p>
                                        	<br clear="all">
              	                            <div style="float:left;">PS Count:<?php echo $countOfPS; ?></div>
                                        	<div style="float:right;">GP Count:<?php echo $countOfGP; ?></div>                                       	
                                        	<p></p>
                                        	<br clear="all">

             	                            <div style="float:left;">PS Employee Not Send:<?php echo $psEmplNotSendNgipf; ?></div>
                                        	<div style="float:right;">GP Employee Not Send:<?php echo $gpEmplNotSendNgipf; ?></div>                                        	
                                        	<p></p>
                                            <br clear="all">
             	                            <div style="float:left;">ZP Employee Not SEND:<?php echo $zpEmplNotSendNgipf ?></div>
                                        	<div style="float:right;">Total Employee :<?php echo $totalEmployee; ?></div> 
                                        	<p></p>
                                            <br clear="all">
             	                            <div style="float:left;">Total Gp Emp:<?php echo $totalgpEmployee ?></div>
                                        	<div style="float:right;">Total PS Employee :<?php echo $totalpsEmployee; ?></div>  
                                            <br clear="all">
             	                            <div style="float:left;">Total ZP Emp:<?php echo $totalzpEmployee ?></div>
                                        	<div style="float:right;">Total GP GPF Generated :<?php echo $totalgpEmployeeNgipf; ?></div>                                        
                                        	<p></p>
                                            <br clear="all">
             	                            <div style="float:left;">Total PS GPF Generated:<?php echo $totalpsEmployeeNgipf ?></div>
                                        	<div style="float:right;">Total ZP GPF Generated :<?php echo $totalzpEmployeeNgipf; ?></div>                                          	
                                        	<p></p>
                                            <br clear="all">
             	                            <div style="float:left;">Total GP GPF Rejected:<?php echo $totalgpRejectedEmployeeNgipf ?></div>
                                        	<div style="float:right;">Total PS GPF Rejected :<?php echo $totalpsRejectedEmployeeNgipf; ?></div>


                                        	                                       	                                       	
                                        	<p></p>                                        	
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
			$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			collapsible: true,
			//active: true
		});
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		
		
		
		
		
		
</script>

    			    <!--SIDEBAR START-->
		<style>
		
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
ul.mynav {
list-style-type: none;
margin: 0;
padding: 0;
}
ul.mynav a:link, ul.mynav a:visited {
display: block;
font-weight: bold;
color: #FFFFFF;
background-color: #3BAAE3;
/*padding: 4px;*/
margin:2px;
border-radius:3px;
-moz-border-radius3px;
text-decoration: none;
text-transform: uppercase;
height:35px;
padding:8px 0px 1px 27px;
}
ul.mynav a:hover, ul.mynav a:active {
background-color: #1988c1;
}
ul.mynav img{
vertical-align: middle;
padding-right: 10px;
overflow-x:hidden;
}
.ui-accordion .ui-accordion-content{
margin: 0px;
padding: 10px;
overflow-x:hidden;
}
.ui-widget {
font-size:14px;
}
.accordion .mynav ul li a img{
border: 0px;
}
.accordion .mynav ul li a{
height:60px;
padding-left:10px;

}
.accordion h2{
margin: 0px;
padding-top: 10px;
font-size: 16px;

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

