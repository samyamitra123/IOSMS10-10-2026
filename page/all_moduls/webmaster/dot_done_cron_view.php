<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require 'page_visite.php';
$crypto = new cryptography();
$db = new database();







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
/*if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])


	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}*/

//Page variables
$common['title'] = "Employee Payslip | PRD | Govt. of West Bengal ";

$level = $_GET['level'];
//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable
//-----------------------------------------------------------------QUERY-------------------------------------------------------

/*$circle_arr = $db->fetch_table("SELECT circle_id_pk FROM ehrms_dise_location_master_circle
							WHERE circle_code='".$_SESSION['user_info']['stake_user']."'");
$circle_id_pk = $circle_arr[0]['circle_id_pk'];

$arr=$db->fetch_table("select 
				school_id_pk,  
				school_dise_code, school_name,flag
			from 
				ehrms_dise_location_master_school 
			where 
				circle_id_fk ='$circle_id_pk' and flag=2
			order by 
			school_name");*/
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';


?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF");
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );	
		  
			$("#stack").val($("#stack option:first").val());
			$("#year").val($("#year option:first").val());
			$("#month").val($("#month option:first").val());
			$("#req_type").val($("#req_type option:first").val());
		
		});
</script>
<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>

      <script>
        $(function() {
			$( "#tch_dob" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
		});
		
		function valid_code(){
			/*if(document.getElementById('tch_dob').value==''){
					alert('Please Enter Date of Birth.');
					document.getElementById('tch_dob').focus();
					return false;
				}
			if(document.getElementById('mobile_no').value == 0){
					alert("Please Enter Mobile Number.");
					document.getElementById('mobile_no').focus();
					return false;
			}*/
			if(document.getElementById('stack').value == 0){
					alert("Please Select Stack.");
					document.getElementById('stack').focus();
					return false;
			}
			if(document.getElementById('year').value == 0){
					alert("Please Select Year.");
					document.getElementById('year').focus();
					return false;
			}
			if(document.getElementById('month').value == 0){
					alert("Please Select Month.");
					document.getElementById('month').focus();
					return false;
			}
		}
		
		
		function get_button(){
			var stack = $("#stack").val();
			var year = $("#year").val();
			var month = $("#month").val();
			var req_type = $("#req_type").val();
			
			if(stack !="" && year !="" && month !="" && req_type !=""){
				$("#sub_button").show();
			}
			else{
				$("#sub_button").hide();
			}
			
		}

	</script>
    

    
    
    
    
    
    
    
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
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">IFMS Cron Hit New</h1>

<div class="border"></div>
<div class="border_val"></div>
</br>
 <div id="sess_msg" >
 
   <?   
  
   if(isset($_SESSION['msg']))
			{
				?>
                <div class="alert alert-danger" style="text-align:center;">
                <?php
				echo "<strong>".$_SESSION['msg']."</strong>";
				
				unset($_SESSION['msg']);
				?>
                </div>
                <?
			}
			?>
            </div>
    <?php if($level==1){ ?>        
		<form action="<?= $config['base_url'] ?>page/api/ifms/dot_done_ifms_cron_job_new.php"  method="post" onsubmit="return valid_code();">
	<?php }
		else if($level==2){?>
		<form action="<?= $config['base_url'] ?>page/api/ifms/prd_ifms_cron_job_new.php"  method="post" onsubmit="return valid_code();">
		<?php } ?>	
    
      <div class="form-group">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Stack: <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php
	/*$emp_name=$db->fetch_table( "Select emp_id_pk,emp_first_name,emp_second_name,emp_last_name
from prd_location_master_gp inner join prd_employee_master on gp_id_pk=gp_id_fk 				
WHERE gp_id_fk='".$_SESSION['location']['gp_id']."' and emp_status in('1','9','2') ");


	if(isset($_REQUEST['emp_id'])){
		$emp = $_REQUEST['emp_id'];
		$reay = $_REQUEST['year'];
		$nthmo = $_REQUEST['month'];
	}
	else{
		$emp = "";
		$reay ="";
		$nthmo = "";
	}
	*/
	
	//var_dump($iddd); die;
		?>
				


		<select class="login-input" name="stack" id="stack" onchange="get_button();">
		  <option value="">-Please Select-</option>
		  <option value="<?php echo $crypto->encode(GP,4); ?>">GP</option>
		  <option value="<?php echo $crypto->encode(PS,4); ?>">PS</option>
		  <option value="<?php echo $crypto->encode(ZP,4); ?>">ZP</option>  
		</select>
    </div>
    <div class="col-sm-3"></div>
    </div><br/><br/><br/>
    
   
    
    <!-------------------------------->
    <div class="form-group">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Year: <span class="star_color">*</span></label>
		<div class="col-sm-3">
			<select name="year" class="login-input" id="year" onchange="get_button();">
                    	<option value="">-Please Select-</option>
						<?php
							
							for($i=2015; $i<=date('Y'); $i++)
							{
								
						?>
                        <option value="<?php echo $crypto->encode($i,4); ?>"><?php echo $i; ?></option>
                  		 <?php
							}
						?>
                     
			</select>
      
		</div>
    <div class="col-sm-3"></div>
    </div><br/><br/>
    
    <!-- ------------------------------------------->
    
     <div class="form-group">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Month: <span class="star_color">*</span></label>
    <div class="col-sm-3">
       <select name="month" class="login-input"  id="month" onchange="get_button();">
          				
                            <option value="">-Please Select-</option>
                            <option value="<?php echo $crypto->encode("01",4); ?>" >January</option>
                            <option value="<?php echo $crypto->encode("02",4); ?>" >February</option>
                            <option value="<?php echo $crypto->encode("03",4); ?>" >March</option>
                            <option value="<?php echo $crypto->encode("04",4); ?>" >April</option>
                            <option value="<?php echo $crypto->encode("05",4); ?>" >May</option>
                            <option value="<?php echo $crypto->encode("06",4); ?>" >June</option>
                            <option value="<?php echo $crypto->encode("07",4); ?>" >July</option>
                            <option value="<?php echo $crypto->encode("08",4); ?>" >August</option>
                            <option value="<?php echo $crypto->encode("09",4); ?>" >September</option>
                            <option value="<?php echo $crypto->encode("10",4); ?>" >October</option>
                            <option value="<?php echo $crypto->encode("11",4); ?>" >November</option>
                            <option value="<?php echo $crypto->encode("12",4); ?>" >December</option>
                    
      </select>
    </div><br/><br/><br/>
    
<div class="form-group">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Requisition Type: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select class="login-input" name="req_type" id="req_type" onchange="get_button();">
		  <option value="">-Please Select-</option>
		  <option value="<?php echo $crypto->encode(1001,4); ?>">Monthly Salary</option>
		  <option value="<?php echo $crypto->encode(1002,4); ?>">Arrear</option>
		  <option value="<?php echo $crypto->encode(1003,4); ?>">Supplymentary</option>  
		  <option value="<?php echo $crypto->encode(1004,4); ?>">Bonus</option>  
		  <option value="<?php echo $crypto->encode(1005,4); ?>">Festival</option>  
		</select>
    </div>
    <div class="col-sm-3"></div>
    </div><br/><br/>
    
    
    <div class="col-sm-3"></div>
    </div><br/><br/>
                      
    <!-- ------------------------------------------->
    
    
  <div class="form-group" id="sub_button" style="display:none">
    <div class="col-sm-offset-5 col-sm-7">
	<?php if($level==1){?>
      <button type="submit" class="btn btn-info" name="submit" value="1">.Done Cron Job</button>
	<?php }
		else if($level==2){?> 
      <button type="submit" class="btn btn-info" name="submit" value="2">IFMS Cron Job</button>
		<?php } ?> 
    </div>
  </div>
</form>
</div>
</div>
</div>
</div>
</div>

               

 <div class="clear"></div>
<?

  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>   