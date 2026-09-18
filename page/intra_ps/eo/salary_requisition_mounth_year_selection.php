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
if($_GET['dise']){
	$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}

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




<script>
var year=0;
var month=0;
function generate_excel(k,l)
{
	
	if(l==1)
	{
		year=k;
	}
	if(l==2)
	{
		 month=k;
	}
	if(year!=0 && month!=0)
	{
		
		$('#report').show();
		
	if(l==3)
	{
			//window.location.href= "salary_requisition_excel.php?month="+month+"&year="+year;
				window.location.href= "salary_requisition_excel.php?year="+year+"&month="+month;
	}
	}
	else
	{
		$('#report').hide();
	}
}





</script>


<div class="content">
<!-- Common Back Button --->
<? require '../../../page/common_back_btns.php'; ?>


    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
 
  <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      }elseif(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].", ";
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'].", ";
                    } ?></h2><h3>
			<?php   
			    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
			
                     ?></h3>
       </div>

    <div class="row" id="cont">
<div class="content">
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#dfeaec" );
		  $( "tr:even" ).css( "background-color", "#fff6" ); 

		  
		});
		
</script>
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
  <div class="col-sm-12">
     <div class="container">
        <div class="row">
            <div class="col-xs-11  ">
                <div class="offer offer-success">
				   <div class="offer-content">
				<h1 class="heading">Salary Requistion Month & Year Selection</h1>
<div class="border"></div>
</br></br>
                        
           <div class="form-group">
              <div class="col-sm-4"></div>
                        <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm; color:#0070A3;">Select Year<span class="star_color">*</span>:</label>
                   <div class="col-sm-4">
                         <select class="form-control upper_case" name="year" id="year" style="width:150px;"onChange="generate_excel(this.value,1);">
                            <option value="">-Please Select-</option>
						<?php
							
							for($i=2017; $i<=date('Y'); $i++)
							{
								
						?>
                             <option value="<?php echo $i?>" <? if($i==$_REQUEST['year']){echo "selected";} ?>><?php echo $i; ?></option>
                  		 <?php
							}
						?>
                           </select>		
                  </div>
                </div>
                  <div class="col-sm-12" style=" margin-top:20px;"></div>
                    <div class="form-group">
                       <div class="col-sm-4"></div>
                        <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm;color:#0070A3;">Select Month<span class="star_color">*</span>:</label>
                           <div class="col-sm-4">
                          <select class="form-control upper_case" name="month" id="month" style="width:150px;" onChange="generate_excel(this.value,2);">
                            <option value="">-Please Select-</option>
                            <option value="01" <? if($_REQUEST['month']=='01'){echo "selected";} ?>>January</option>
                            <option value="02"  <? if($_REQUEST['month']=='02'){echo "selected";} ?>>February</option>
                            <option value="03"  <? if($_REQUEST['month']=='03'){echo "selected";} ?>>March</option>
                            <option value="04"  <? if($_REQUEST['month']=='04'){echo "selected";} ?>>April</option>
                            <option value="05"  <? if($_REQUEST['month']=='05'){echo "selected";} ?>>May</option>
                            <option value="06"  <? if($_REQUEST['month']=='06'){echo "selected";} ?>>June</option>
                            <option value="07"  <? if($_REQUEST['month']=='07'){echo "selected";} ?>>July</option>
                            <option value="08"  <? if($_REQUEST['month']=='08'){echo "selected";} ?>>August</option>
                            <option value="09"  <? if($_REQUEST['month']=='09'){echo "selected";} ?>>September</option>
                            <option value="10"  <? if($_REQUEST['month']=='10'){echo "selected";} ?>>October</option>
                            <option value="11"  <? if($_REQUEST['month']=='11'){echo "selected";} ?>>November</option>
                            <option value="12"  <? if($_REQUEST['month']=='12'){echo "selected";} ?>>December</option>
                         </select>
                           
                        </div>
                      </div>
                     </div>
                  <div class="col-sm-12" style="margin-top:15px">
                     <div class="col-sm-5"></div>
                        <div style="display:none;" id="report" ><a class="btn btn-info btn-sm" onClick="generate_excel(0,3);"><i class="fa fa-file-text"></i>&nbsp;Download Salary Requistion Report</a>
                     </div>
                   </div>
			    </div> 
		     </div>
           </div>
          </div> 
    <div style="height:10px;"></div>
   
        </div> 
      </div>
     </div>
   </div> 
  </div>
 </div>
     


<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
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



