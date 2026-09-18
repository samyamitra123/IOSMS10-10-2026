<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';

//require '../../../page_visite.php';

$crypto = new cryptography();
if(isset($_GET['dise']))
{
	$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}
/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
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

$logged_user=$_SESSION['user_info']['stake_abbr']; 

$cryptoGraph=new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);




//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic---------------------------------------------------------------------------------
//-----------------------------QUERY----------------------------------------------------------------------------------

$crypto=new cryptography();
$db = new database();


?>
<!--CONTENT START-->
<div class="content">
<!-- Common Back Button --->
<?php require '../../common_back_btns.php'; ?>	
 <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
                      ?></h3>
                     
       </div>
    <div class="row" id="cont">
<!---<div class="content">--->
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		
</script>
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
  <div class="col-sm-12" style="width:98%">
     <div class="container">
        <div class="row">
            <div class="col-xs-11  ">
                <div class="offer offer-success">
				   <div class="offer-content">
				<h1 class="heading">ARREAR BILL SELECTION</h1>
<div class="border"></div>
</br></br>
	<?
    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg']."<br/>";
        unset($_SESSION['msg']);
    }
    ?>
                        
<form class="form-horizontal" id="loginForm" method="post" action="add_arrear_bill_submit.php" onsubmit="return valid_code();">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
		<div class="form-group text-center" >
        <?php if($logged_user=='FC&CAO'){?>
    
             <button type="submit" value="<?=$cryptoGraph->encode(3,4);?>"  id="type3" name="type3"class="btn btn-info" style="margin-top: 10px;" >GOVERNMENT EMPLOYEE BILL FOR ROPA 2009</button>
             <button type="submit" value="<?=$cryptoGraph->encode(4,4);?>"  id="type4" name="type4"class="btn btn-info" style="margin-top: 10px;">GRANT-IN -AID EMPLOYEE BILL FOR ROPA 2009</button>
			 
             <button type="submit" value="<?=$cryptoGraph->encode(6,4);?>"  id="type6" name="type6"class="btn btn-info" style="margin-top: 10px;" >GOVERNMENT EMPLOYEE BILL FOR ROPA 2019</button>
             <button type="submit" value="<?=$cryptoGraph->encode(7,4);?>"  id="type7" name="type7"class="btn btn-info" style="margin-top: 10px;">GRANT-IN -AID EMPLOYEE BILL FOR ROPA 2019</button>
			 
             <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php'  id="type1" name="type" class="btn btn-info" style="margin-top: 10px;">View previous Arrear Bill</a>
             
       
		<?php }
		else{?>
    		<!--<div class="col-sm-3"></div>-->
            
          
              <button type="submit" value="<?=$cryptoGraph->encode(0,4);?>"  id="type2" name="type2"class="btn btn-info">Create New Arrear Bill For ROPA 2009</button>
              
              <button type="submit" value="<?=$cryptoGraph->encode(5,4);?>"  id="type5" name="type5"class="btn btn-info">Create New Arrear Bill For ROPA 2019</button>
              
           
			<?php if($logged_user=='EO' || $logged_user=='BDO' )
            {?>
            <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php'  id="type" name="type" class="btn btn-info">View previous Arrear Bill</a>
            <?php } 
			else{?>
		 <button type="submit"  value="<?=$cryptoGraph->encode(1,4);?>"  id="type" name="type" class="btn btn-info">View previous Arrear Bill</button>

				
			<?php }
			
			}?>
    			
    		<!--<div class="col-sm-3">
                      
   			 </div>-->
<!--<div class="col-sm-3"></div>-->
    </div>
        <!--<div class="form-group">
            <div class="col-sm-offset-3 col-sm-7" align='center'>
  			
   			 </div>
  		</div>-->
</form>
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    <!--</div>-->
   </div>
  </div>
<div class="clear"></div>



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
<script>
function valid_code(){
	if($("#type2").val()==""){
		alert("Please Select Type");
		$("#type").focus();
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



