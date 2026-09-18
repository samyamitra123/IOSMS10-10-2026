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

/*$Query = "SELECT pgrm.*, pem.emp_id_pk from prd_gpf_request_master as pgrm
          LEFT JOIN prd_employee_master as pem ON pgrm.emp_id_const= pem.emp_id_const
          WHERE pgrm.status = '3' "; */
$Query = "SELECT 
    gpf.full_response,
    gpf.pfaccno,
    gpf.status,
    gpf.pnrd_request,
    pem.emp_id_pk,
    pem.emp_first_name,
    pem.emp_last_name,
    pem.emp_id_const,
    pem.gp_id_fk,
    pem.ps_id_fk,
    pem.zp_id_fk,
    CASE WHEN bl.block_name IS NOT NULL THEN bl.block_name ELSE '' END AS block_name,
    CASE WHEN ps.ps_name IS NOT NULL THEN ps.ps_name ELSE '' END AS ps_name,
    CASE WHEN zp.district_name IS NOT NULL THEN zp.district_name ELSE '' END AS district_name
FROM 
    public.prd_gpf_request_master AS gpf
LEFT JOIN 
    prd_employee_master AS pem ON gpf.emp_id_const = pem.emp_id_const
LEFT JOIN 
    prd_location_master_gp AS gl ON pem.gp_id_fk > 0 AND pem.gp_id_fk = gl.gp_id_pk
LEFT JOIN 
    prd_location_master_block AS bl ON gl.block_id_fk = bl.block_id_pk
LEFT JOIN 
    prd_location_master_panchayat_samiti AS ps ON pem.ps_id_fk > 0 AND pem.ps_id_fk = ps.ps_id_pk
LEFT JOIN 
    prd_location_master_district AS zp ON pem.zp_id_fk > 0 AND pem.zp_id_fk = zp.district_id_pk
WHERE 
    gpf.status = 3 
    AND pem.emp_id_const != '';";
$RejectedEmployee = $db->fetch_table($Query);
// Employee With Section Code = 0 Temporary
$Query = "SELECT 
    gpf.full_response,
    gpf.pfaccno,
    gpf.status,
    gpf.pnrd_request,
    pem.emp_id_pk,
    pem.emp_first_name,
    pem.emp_last_name,
    pem.emp_id_const,
    pem.gp_id_fk,
    pem.ps_id_fk,
    pem.zp_id_fk,
    CASE WHEN bl.block_name IS NOT NULL THEN bl.block_name ELSE '' END AS block_name,
    CASE WHEN gl.gp_name IS NOT NULL THEN gl.gp_name ELSE '' END AS gp_name,
    CASE WHEN gl.section_code IS NOT NULL THEN gl.section_code ELSE '' END AS section_code,
    CASE WHEN ps.ps_name IS NOT NULL THEN ps.ps_name ELSE '' END AS ps_name,
    CASE WHEN zp.district_name IS NOT NULL THEN zp.district_name ELSE '' END AS district_name
FROM 
    public.prd_gpf_request_master AS gpf
LEFT JOIN 
    prd_employee_master AS pem ON gpf.emp_id_const = pem.emp_id_const
LEFT JOIN 
    prd_location_master_gp AS gl ON pem.gp_id_fk > 0 AND pem.gp_id_fk = gl.gp_id_pk
LEFT JOIN 
    prd_location_master_block AS bl ON gl.block_id_fk = bl.block_id_pk
LEFT JOIN 
    prd_location_master_panchayat_samiti AS ps ON pem.ps_id_fk > 0 AND pem.ps_id_fk = ps.ps_id_pk
LEFT JOIN 
    prd_location_master_district AS zp ON pem.zp_id_fk > 0 AND pem.zp_id_fk = zp.district_id_pk
WHERE 
    gpf.status IN (3,2) 
    AND gl.section_code = '0'
    AND pem.emp_id_const != '';";
$RejectedSectionCodeZeroEmployee = $db->fetch_table($Query);
//print_r($RejectedSectionCodeZeroEmployee); exit;

/*$Query = "SELECT pgrm.*, pem.emp_id_pk from prd_gpf_request_master as pgrm
          LEFT JOIN prd_employee_master as pem ON pgrm.emp_id_const= pem.emp_id_const
          WHERE pgrm.status = '2'"; */
$Query = "SELECT 
    gpf.full_response,
    gpf.pfaccno,
    gpf.status,
    gpf.pnrd_request,
    pem.emp_id_pk,
    pem.emp_first_name,
    pem.emp_last_name,
    pem.emp_id_const,
    pem.gp_id_fk,
    pem.ps_id_fk,
    pem.zp_id_fk,
    CASE WHEN bl.block_name IS NOT NULL THEN bl.block_name ELSE '' END AS block_name,
    CASE WHEN gl.gp_name IS NOT NULL THEN gl.gp_name ELSE '' END AS gp_name,
    CASE WHEN gl.section_code IS NOT NULL THEN gl.section_code ELSE '' END AS section_code,
    CASE WHEN ps.ps_name IS NOT NULL THEN ps.ps_name ELSE '' END AS ps_name,
    CASE WHEN zp.district_name IS NOT NULL THEN zp.district_name ELSE '' END AS district_name
FROM 
    public.prd_gpf_request_master AS gpf
LEFT JOIN 
    prd_employee_master AS pem ON gpf.emp_id_const = pem.emp_id_const
LEFT JOIN 
    prd_location_master_gp AS gl ON pem.gp_id_fk > 0 AND pem.gp_id_fk = gl.gp_id_pk
LEFT JOIN 
    prd_location_master_block AS bl ON gl.block_id_fk = bl.block_id_pk
LEFT JOIN 
    prd_location_master_panchayat_samiti AS ps ON pem.ps_id_fk > 0 AND pem.ps_id_fk = ps.ps_id_pk
LEFT JOIN 
    prd_location_master_district AS zp ON pem.zp_id_fk > 0 AND pem.zp_id_fk = zp.district_id_pk
WHERE 
    gpf.status = 2
    AND pem.emp_id_const != '';";          
$PendingEmployee = $db->fetch_table($Query);
/*$newPending = array();
foreach($PendingEmployee as $pe)
 {
    $newPending[$pe['request_id_pk']] = $pe;
 }
 $PendingEmployee = $newPending; */
//print_r(count($newPending)); exit;

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
                                            <div id="accordion">     
                                                <h1>Rejected NGIPF</h1>
                                                <div class="form-group">

												<div class="emplist" style="width:98%; margin: 0 auto;">

												    <div class="school" >
														<div class="table-responsive">
												            <table width="100%" id="tbl1">
												                <tr id="base_header" style="background-color: rgb(221, 247, 255); ">
												                <th>#</th>	
												                <th>Employee ID</th>
												                <th>Name</th>
												                <th>Block/PS/ZP</th>
												                <th>Stake</th>
												                <th>Error</th>
												                <th>Action</th>
												                </tr>
												                <?php 
                                                                $Counter = 1;  
												                foreach($RejectedEmployee as $rEmployee):
                                                                   
                                                                   $pnrdRequest = json_decode($rEmployee['pnrd_request']);
                                                                   $pnrdRequest = $pnrdRequest->req->empDtls->basicDtls; 
                                                                   $pnrdResponse = json_decode($rEmployee['full_response']); 
                                                                   $pnrdResponse = $pnrdResponse->resp->errDesc;
                                                                   $stakeName = '';
                                                                   $stakeName = ($rEmployee['gp_id_fk'] > 0)?$rEmployee['block_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['ps_id_fk'] > 0)?$rEmployee['ps_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['zp_id_fk'] > 0)?$rEmployee['district_name']:$stakeName;
                                                                   $stakeType = '';
                                                                   $stakeType = ($rEmployee['gp_id_fk'] > 0)?'GP':$stakeType;
                                                                   $stakeType = ($rEmployee['ps_id_fk'] > 0)?'PS':$stakeType;
                                                                   $stakeType = ($rEmployee['zp_id_fk'] > 0)?'ZP':$stakeType;                                                                   
												                ?>
												                <tr>
												                	<td><?php echo $Counter; ?></td>
												                	<td><?php echo $rEmployee['emp_id_const']; ?></td>
												                	<td><?php echo $pnrdRequest->empNm; ?></td>
												                	<td><?php echo $stakeName; ?></td>
												                	<td><?php echo $stakeType; ?></td>
												                	<td><?php echo $pnrdResponse; ?></td>		
												                	<td>
                                                                    <?php if($rEmployee['status'] == 3 && $rEmployee['pfaccno'] != ''){ ?>
                                                                        <a href="/page/api/gpf/updateapi.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Send API</a>	
                                                                        <?php } else { ?>	
                                                                         <a href="/page/api/gpf/index_gpf.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Send API</a>
                                                                         <a href="/page/api/gpf/index_gpf.php?debug=1&emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Debug API</a>                                                                         
                                                                        <?php } ?>
												                	</td>										                	
												                </tr>
												                <?php 
                                                                 $Counter++;
												                 endforeach; ?>
												              </table>
												        </div>
												     </div>
												</div>                
                                                </div>
                                                <h1>Rejected NGIPF Section Code 0</h1>
                                                <div class="form-group">

												<div class="emplist" style="width:98%; margin: 0 auto;">

												    <div class="school" >
														<div class="table-responsive">
												            <table width="100%" id="tbl1">
												                <tr id="base_header" style="background-color: rgb(221, 247, 255); ">
												                <th>#</th>	
												                <th>Employee ID</th>
												                <th>Name</th>
												                <th>Block/PS/ZP</th>
												                <th>Stake</th>
												                <th>Error</th>
												                <th>Action</th>
												                </tr>
												                <?php 
                                                                $Counter = 1;  
												                foreach($RejectedSectionCodeZeroEmployee as $rEmployee):
                                                                   
                                                                   $pnrdRequest = json_decode($rEmployee['pnrd_request']);
                                                                   $pnrdRequest = $pnrdRequest->req->empDtls->basicDtls; 
                                                                   $pnrdResponse = json_decode($rEmployee['full_response']); 
                                                                   $pnrdResponse = $pnrdResponse->resp->errDesc;
                                                                   $stakeName = '';
                                                                   $stakeName = ($rEmployee['gp_id_fk'] > 0)?$rEmployee['block_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['ps_id_fk'] > 0)?$rEmployee['ps_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['zp_id_fk'] > 0)?$rEmployee['district_name']:$stakeName;
                                                                   $stakeType = '';
                                                                   $stakeType = ($rEmployee['gp_id_fk'] > 0)?'GP':$stakeType;
                                                                   $stakeType = ($rEmployee['ps_id_fk'] > 0)?'PS':$stakeType;
                                                                   $stakeType = ($rEmployee['zp_id_fk'] > 0)?'ZP':$stakeType;                                                                   
												                ?>
												                <tr>
												                	<td><?php echo $Counter; ?></td>
												                	<td><?php echo $rEmployee['emp_id_const']; ?></td>
												                	<td><?php echo $pnrdRequest->empNm; ?></td>
												                	<td><?php echo $stakeName; ?></td>
												                	<td><?php echo $stakeType; ?></td>
												                	<td><?php echo $pnrdResponse; ?></td>		
												                	<td>
                                                                    <?php if($rEmployee['status'] == 3 && $rEmployee['pfaccno'] != ''){ ?>
                                                                        <a href="/page/api/gpf/updateapi.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Send API</a>	
                                                                        <?php } else { ?>	
                                                                         <a href="/page/api/gpf/index_gpf.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Send API</a>
                                                                         <a href="/page/api/gpf/index_gpf.php?debug=1&emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Debug API</a>                                                                         
                                                                        <?php } ?>
												                	</td>										                	
												                </tr>
												                <?php 
                                                                 $Counter++;
												                 endforeach; ?>
												              </table>
												        </div>
												     </div>
												</div>                
                                                </div>                                                
                                                <h2>PENDING EMPLOYEE</h2>
                                                <div class="form-group">

												<div class="emplist" style="width:98%; margin: 0 auto;">

												    <div class="school" >
														<div class="table-responsive">
												            <table width="100%" id="tbl1">
												                <tr id="base_header" style="background-color: rgb(221, 247, 255); ">
												                <th>#</th>	
												                <th>Employee ID</th>
												                <th>Name</th>
                                                                <th>Block/PS/ZP</th>
                                                                <th>Stake</th>
												                <th>Date</th>
												                <th>Action</th>
												                </tr>
												                <?php 
                                                                $Counter = 1;  
												                foreach($PendingEmployee as $rEmployee):
                                                                   
                                                                   $pnrdRequest = json_decode($rEmployee['pnrd_request']);
                                                                   $reqDate = $pnrdRequest->req->empDtls->cDateTime;
                                                                   $pnrdRequest = $pnrdRequest->req->empDtls->basicDtls; 
                                                                   $pnrdResponse = json_decode($rEmployee['full_response']); 
                                                                   $pnrdResponse = $pnrdResponse->resp->errDesc;
                                                                   $stakeName = '';
                                                                   $stakeName = ($rEmployee['gp_id_fk'] > 0)?$rEmployee['block_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['ps_id_fk'] > 0)?$rEmployee['ps_name']:$stakeName;
                                                                   $stakeName = ($rEmployee['zp_id_fk'] > 0)?$rEmployee['district_name']:$stakeName;
                                                                   $stakeType = '';
                                                                   $stakeType = ($rEmployee['gp_id_fk'] > 0)?'GP':$stakeType;
                                                                   $stakeType = ($rEmployee['ps_id_fk'] > 0)?'PS':$stakeType;
                                                                   $stakeType = ($rEmployee['zp_id_fk'] > 0)?'ZP':$stakeType;                                                                     
												                ?>
												                <tr>
												                	<td><?php echo $Counter; ?></td>
												                	<td><?php echo $rEmployee['emp_id_const']; ?></td>
												                	<td><?php echo $pnrdRequest->empNm; ?></td>
                                                                    <td><?php echo $stakeName; ?></td>
                                                                    <td><?php echo $stakeType; ?></td>                                                                    
												                	<td><?php echo $reqDate; ?></td>		
												                	<td>
                                                                    <?php if($rEmployee['pfaccno'] != ''){ ?>
                                                                        <a href="/page/api/gpf/updateapi.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Resend API</a>	
                                                                        <?php } else { ?>	
                                                                         <a href="/page/api/gpf/index_gpf.php?emp_id_pk=<?php echo $crypto->encode($rEmployee['emp_id_pk'],4); ?>">Resend API</a>

                                                                        <?php } ?>
												                	</td>										                	
												                </tr>
												                <?php 
                                                                $Counter++;
												                endforeach; ?>
												              </table>
												        </div>
												     </div>
												</div>                                                     
                                                </div>
                                                
                                                <h2>NEW NGIPF EMPLOYEE</h2>
                                                <div class="form-group">

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

