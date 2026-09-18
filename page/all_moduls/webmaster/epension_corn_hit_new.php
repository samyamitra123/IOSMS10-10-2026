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


/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
?>
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
            <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>

    
    

         
                        <div class=" col-sm-offset-3 col-sm-12">
                          <div class="col-sm-5">      
                            <div class="panel panel-default"  style="border-left:4px solid; border-left-color:#0A6E8C; margin-top: 10%;">
                               <div class="page_title">
                                  <div class="panel-body" style="background-color:#F0F3F4; ">
							<!--<a class="btn btn-success" href="dot_done_cron_view.php?level=1"  style="text-align:center" >.done hit</a>
                            <a class="btn btn-warning" href="dot_done_cron_view.php?level=2" style="text-align:center" >Corn hit</a>-->
                                  </div>
                                 </div>  
                                </div>
                               </div>
                              </div>
                              
                          <div class=" col-sm-offset-3 col-sm-12">
                          <div class="col-sm-5">      
                            <div class="panel panel-default"  style="border-left:4px solid; border-left-color:#0A6E8C; margin-top: 3%;">
                               <div class="page_title">
                                  <div class="panel-body" style="background-color:#F0F3F4; ">
                             <a class="btn btn-info" href="<?= $config['base_url'] ?>page/prd_db_cron_job_m.php?user=gp"style="text-align:center">  CORN HIT FOR ePENSION</a>
                                    <br/>
                                    <!--<a class="btn btn-primary" href="<?= $config['base_url'] ?>page/api/ifms/sftp_cron/sftp_con_test.php?user=ps" style="text-align:center; margin-top: 2%;"> SFTP Conection PS</a>-->
                                    <br/>
                                   <!-- <a class="btn btn-warning"  href="<?= $config['base_url'] ?>page/api/ifms/sftp_cron/sftp_con_test.php?user=zp" style="text-align:center; margin-top: 2%;">SFTP Conection ZP</a>-->
                                  </div>
                                 </div>  
                                </div>
                               </div>
                              </div>
                  
                 <div class="onclick_loader" id="page-load"><img src="<?php echo $config['base_url']; ?>themes/default/image/page_loader.gif" class="img-responsive onclick_load"/></div>	
               </div> 
               </div>
               <?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require 'right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>

 
             </div>

     
		
<script>

    	$(document).ready(function(){
			$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			collapsible: true,
			//$('#drn_number').val('');
			//active: true
		});
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		
		
		
/*	function done_file_check()
	{  
		$('#page-load').show();
		$('#page-load').delay(500).fadeOut();
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/dot_done_ifms_cron_job.php', function (data) {
		location.reload();
		alert(data);
		});
	}
		 

	function corn_hit()
	{
	//alert(11);
		$('#page-load').show();
		$('#page-load').delay(500).fadeOut();
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/prd_ifms_cron_job.php', function (data) {
		location.reload();
		alert(data);
	});
	}*/
		
		
		
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
 

