<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once 'employeeChange.class.php';

 if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
 /*   Kalyan Ghohs   20/3/2017   Finish   */	
		
		
$cryptoGraph=new cryptography();		
			
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//Self variable




//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
	global $db;
	$db=new database();
	$employeeChangeObject = new employeeChange();

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
 <div class="content">
	<? require '../../../page/common_back_btns.php'; ?>
			<div class="welcome_msg">
			                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
			                      <?php
								  if(isset($_SESSION['location']['gp_name'])) {
			                          echo $_SESSION['location']['gp_name'].", ";
			                      }elseif(isset($_SESSION['location']['block_name'])) {
			                          echo $_SESSION['location']['block_name'];
			                      }elseif(isset($_SESSION['location']['ps_name'])) {
			                          echo $_SESSION['location']['ps_name'].", ";
			                      }elseif(isset($_SESSION['location']['district_name'])) {
			                          echo $_SESSION['location']['district_name'];
			                      } elseif(isset($_SESSION['location']['state_name'])) {
			                          echo $_SESSION['location']['state_name'];
			                    } ?></h2><h3>
						<?php if($_SESSION['user_info']['stake_abbr']=='GP'){ ?>
						<? echo $_SESSION['location']['block_name'].", " .$_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
						}else{
						    
						    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
						}
			                     ?></h3>
			</div>
			     
			<center>
			    <h1 class="heading">Employee Basic Change / Modification Module </h1>
			    <?  
			    if(isset($_SESSION['msg']))
				{
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				}
				?>
			    <!--    Kalyan Ghosh    20/3/2017    Start-->
			    <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
			    
				</div>
			    <br />
			  
			</center>

			     

			<?php
				if(isset($_SESSION['msg']))
			{
					echo $_SESSION['msg'];
					unset($_SESSION['school_msg']);
			} 
			if(!empty($_GET['msg'])){
			echo $cryptoGraph->decode($_GET['msg'],4);
			echo "<br/>";
			echo "<br/>";
			}
			if(!empty($msg)){
				echo $msg;
			}
			?>
			<?php 
      //print_r($employeeChangeObject->userInfo); 
			if(in_array($employeeChangeObject->userInfo['stake_abbr'],array('GP','DA','DEALING ASSISTANT (Establishment)'))): ?>
			<div class="row">
			        <div class="content">
			        	<div class="col-sm-12" style="width: 98%;">
				        	<div class="col-lg-12 col-md-12 col-sm-12">
				        		<a href="" class="btn btn-sm btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#myModal_change">
				        			Request Employee Basic Change
				        		</a>	
				        	</div>	
			            </div>
			        </div>
			</div>  

<?php 
    endif;
  $employeeChangeObject->getAllMyRequest(); 
?> 			      				
 </div>	

<? require '../../../page/layout/footer.php'; ?>


<div class="modal fade bs-example-modal-lg" id="myModal_change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 100%; margin-left: -2.5%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">Search Employee to Change / Modify Basic</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
        <div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12">
        		<input type="text" class="control-form employeeId" name="employeeId" value="" autocomplete="off" max="12">
        		<input type="button" name="Search" value="Search Employee" class="searchEmployee btn btn-sm btn-primary" data-func='SearchEmployee'>
        	</div>	
        	<div class="col-lg-12 col-md-12 col-sm-12 employeeInfochange">
        	</div>	
        </div>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.searchEmployee').click(function(){
           var employeeId = $('.employeeId').val();
           //console.log($(this).data('func'));
           if(employeeId.length == 12)
             {
				$.post(
					'ajax_empDetails.php',
                     {empId:employeeId,action:$(this).data('func')} 
					, function(data){
                    // alert(data);
					 $('.employeeInfochange').html(data);
			       });             	
             } 
		});


	});
	 function employeechangesucess()
	   {
          window.parent.location.href='<?php echo $config['base_url']; ?>page/all_moduls/employee_modification/';
	   }	
</script>

<style>
	.label{display: block; font-size: 16px; font-weight: 700;}
	.head-employee{border: 1px solid #c6c6c6;
    margin-top: 20px;
    background-color: #0d6efd;
    font-size: 22px;
    border-radius: 10px 10px 0 0;
    line-height: 40px;
    margin-bottom: 0px;
    text-align: center;
    color: #ffffff;}
    .emp-info-box{border: 1px solid #c6c6c6;padding: 10px}
</style>