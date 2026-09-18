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
			if(document.getElementById('ropa_status').value == 0 && document.getElementById('year').value == 2020 ){
					alert("Please Select ROPA status.");
					document.getElementById('ropa_status').focus();
					return false;
			}
		}
		
		
		
		
	function select_ropa(i){
		
		//alert(i.value);
		
		if(i.value >= 2020){
			$('#ropa_div').show();
		}
		else{
			$('#ropa_div').hide();
			//$('#ropa_status').val(0);
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
        <div class="col-sm-12" style="width:98%" >
<h1 class="heading">Employee Payslip Generation</h1>

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
            
   <form action="employee_payslip_submit.php"  method="post" onsubmit="return valid_code();">
   

<!--	<div class="form-group">
    <div class="col-sm-4"></div>
    <label for="inputEmail3" class="col-sm-2 control-label">Date Of Birth :<span class="star_color">*</span></label>
    <div class="col-sm-3">
     </select>
    </div>
     <div class="col-sm-3"></div>
    </div><br/><br/><br/>-->
   <!-- ------------------------------------------->
<!--    <div class="form-group">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Mobile Number: <span class="star_color">*</span></label>
    <div class="col-sm-3">
     </select>
    </div>
    <div class="col-sm-3"></div>
    </div><br/><br/><br/>-->
    
    <!-- ------------------------------------------->
    
      <div class="row mb-3">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Name: <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php
	$emp_name=$db->fetch_table( "Select emp_id_pk,emp_first_name,emp_second_name,emp_last_name
from prd_location_master_gp inner join prd_employee_master on gp_id_pk=gp_id_fk 				
WHERE gp_id_fk='".$_SESSION['location']['gp_id']."' and emp_status in('1','9','2') ");


	if(isset($_REQUEST['emp_id'])){
		
		//var_dump($_REQUEST['ropa']); die;
		$emp = $_REQUEST['emp_id'];
		$reay = $_REQUEST['year'];
		$nthmo = $_REQUEST['month'];
		$ropa_status = $_REQUEST['ropa'];
	}
	else{
		$emp = "";
		$reay ="";
		$nthmo = "";
		$ropa_status = "";
	}
	
		?>
				


	<select class="form-control" class="login-input" name="emp_name" id="emp_name">
      <option value="">-Please Select-</option>
       <? foreach($emp_name as $key){ $key['payband_code']. '<br />'; ?>
       <option value="<?= $key['emp_id_pk']; ?>"  <? if($key['emp_id_pk']== $emp){echo "selected";} ?>><?= $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];  ?></option>
       <? } ?>
    </select>
    </div>
    <div class="col-sm-3"></div>
    </div><br/><br/><br/>
    
   
    
    <!-------------------------------->
     <div class="row mb-3">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Year: <span class="star_color">*</span></label>
    <div class="col-sm-3">
     <select name="year" class="login-input" id="year" onchange='select_ropa(this);' >
                    	<option value="">-Please Select-</option>
						<?php
							
							for($i=2015; $i<=date('Y'); $i++)
							{
								
						?>
                        <option value="<?php echo $i?>" <? if($i== $reay){echo "selected";} ?>><?php echo $i; ?></option>
                  		 <?php
							}
						?>
                     </select>
      </select>
      
    </div>
    <div class="col-sm-3"></div>
    </div><br/><br/><br/>
    
    <!-- ------------------------------------------->
	
	
	
	<div class="row mb-3" id='ropa_div' style='display:none;'>
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select ROPA: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select name="ropa_status" class="login-input" id="ropa_status" >
			<option value="">-Please Select-</option>
			<option value="2" <? if($ropa_status=='2'){echo "selected";} ?> >ROPA 2009</option>
			<option value="1" <? if($ropa_status=='1'){echo "selected";} ?> >ROPA 2019</option>	
        </select>
      
    </div>
    <div class="col-sm-3"></div><br/><br/><br/>
    </div>
	
	
	
    
     <div class="row mb-3">
    <div class="col-sm-4"></div>
       
    <label for="inputEmail3" class="col-sm-2 control-label">Select Month: <span class="star_color">*</span></label>
    <div class="col-sm-3">
       <select name="month" class="login-input"  id="month" >
          				
                            <option value="">-Please Select-</option>
                             <option value="01" <? if($nthmo=='01'){echo "selected";} ?>>January</option>
                            <option value="02"  <? if($nthmo=='02'){echo "selected";} ?>>February</option>
                            <option value="03"  <? if($nthmo=='03'){echo "selected";} ?>>March</option>
                            <option value="04"  <? if($nthmo=='04'){echo "selected";} ?>>April</option>
                            <option value="05"  <? if($nthmo=='05'){echo "selected";} ?>>May</option>
                            <option value="06"  <? if($nthmo=='06'){echo "selected";} ?>>June</option>
                            <option value="07"  <? if($nthmo=='07'){echo "selected";} ?>>July</option>
                            <option value="08"  <? if($nthmo=='08'){echo "selected";} ?>>August</option>
                            <option value="09"  <? if($nthmo=='09'){echo "selected";} ?>>September</option>
                            <option value="10"  <? if($nthmo=='10'){echo "selected";} ?>>October</option>
                            <option value="11"  <? if($nthmo=='11'){echo "selected";} ?>>November</option>
                            <option value="12"  <? if($nthmo=='12'){echo "selected";} ?>>December</option>
                    </select>
      </select>
    </div>
    

    
    
    <div class="col-sm-3"></div>
    </div><br/><br/><br/>
    <!--                    
    <!-- ------------------------------------------->
    
    
     <div class="row mb-3" style="margin-left: 45%;">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" class="btn btn-info" name="submit">SUBMIT</button>
    </div>
  </div>
</form>
</div>
</div>
</div>
</div>
</div>

               
 
<?php  if(isset($_SESSION['payslip_active'])){ ?>

<div id="show_box" style="margin-left:41%">
    <br>  <br>
<a class="btn btn-info btn-sm"  href="employee_payslip_pdf.php?month=<?= $_REQUEST['month']?>&year=<?= $_REQUEST['year']?>&id=<?= $crypto->encode($_REQUEST['emp_id'],4)?>&ropa=<?= $crypto->encode($_REQUEST['ropa'],4)?>"><i class="fa fa-file-text"></i> Download Payslip</a>

</div>


<?php
}
unset($_SESSION['payslip_active']);

?>
 <div class="clear"></div>
<?

  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>   