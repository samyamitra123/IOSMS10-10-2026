<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/
?>
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
</style>

<?php
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Primary Details of the Employee submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Data insertion failed. Please try again...</div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
?>
<script>
         $(function() {
			$( "#first_join_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
			$( "#confirm_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
			});
			$( "#present_post_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy'
			});
			$( "#present_office_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+60",
				dateFormat: 'dd-mm-yy'
			});
			$( "#increment_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+50",
				dateFormat: 'dd-mm-yy'
			});
        });
        </script> 

<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
         <br /> <? require '../../../page/common_back_btns.php'; ?>
<h2 class="primary" align="center">Professional Details</h2>

<form class="form-horizontal" id="loginForm" method="post" action="profile_entry_prof_insert.php">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Designation</label>
    <div class="col-sm-7">
    			<?
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120') order by code");
				?>
      <select class="form-control" name="vice_desig" id="vice_desig">
      <option value="">-Please Select-</option>
                      <? 
					  	if(strlen($designation) == 1) 
						 		$designation = '110'.$designation;
						else if (strlen($designation) == 2)
							$designation = '11'.$designation;
					  
					 	 foreach($arr as $key){
							 $key['code']. '<br />';
						?>
                        <option value="<?= $key['code']; ?>" 
						<? 
 						if($designation==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                         <? } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Date of First Joining in service</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="first_join_date" id="first_join_date" readonly="readonly" placeholder="Date Of First Join" autocomplete="off" value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>">
    </div>
  </div>
  <div class="form-group">
     <label for="inputPassword3" class="col-sm-3 control-label">Date of Confirmation in service </label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="confirm_date" id="confirm_date" readonly="readonly" placeholder="Date Of Confirmation" autocomplete="off" value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>">
  </div>
  </div>
   <div class="form-group">
     <label for="inputPassword3" class="col-sm-3 control-label">Date of joining in the present post</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="present_post_date" id="present_post_date" readonly="readonly" class="login-input" placeholder="Date Of Join In Present Post" autocomplete="off"  value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>" />
  </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Date of Joining in the Present Office</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="present_office_date" id="present_office_date" readonly="readonly" class="login-input" placeholder="Date Of Join In Present Office" autocomplete="off"  value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Date of Retirement / Date of Termination (for temporary post)</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="emp_date_retirement" id="emp_date_termination" readonly="readonly" class="login-input" placeholder="Date Of Retirement" autocomplete="off"  value="<?= $_REQUEST['emp_date_retirement']; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether on Deputation</label>
    <div class="col-sm-7">
      <select class="form-control" name="deputation" id="deputation">
      <option value="">Please Select</option>
      <option value="1">YES</option>
      <option value="0">NO</option>
      </select>
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Employee Group</label>
    <div class="col-sm-7">
    <?
    $db = new database();
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and substring(code,1,2)='63' order by code"); ?>
      <select class="form-control" name="employee_group" id="employee_group">
      <option value="">-Please Select-</option>
      <? foreach($arr as $key){ $key['code']. '<br />'; ?>
      <option value="<?= $key['code']; ?>" <? if($employee_group==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
       <? } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Designation at First Appointment</label>
    <div class="col-sm-7">
    			<?
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119') order by code");
				?>
      <select class="form-control" name="first_desig" id="first_desig">
      <option value="">-Please Select-</option>
                      <? 
					  	if(strlen($designation) == 1) 
						 		$designation = '110'.$designation;
						else if (strlen($designation) == 2)
							$designation = '11'.$designation;
					  
					 	 foreach($arr as $key){
							 $key['code']. '<br />';
						?>
                        <option value="<?= $key['code']; ?>" 
						<? 
 						if($designation==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                         <? } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Date of Next Increment</label>
    <div class="col-sm-7">
      <input type="text" class="form-control"  name="increment_date" id="increment_date" readonly="readonly" class="login-input" placeholder="Date Of Increment" autocomplete="off"  value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Amount of Increment</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="increment_amount" name="increment_amount" maxlength="11" class="login-input" placeholder="Increment Amount" autocomplete="off" value="<?=$txt_ifsc_no;?>">
    </div>
  </div>
  
  
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-7">
      <button type="submit" class="btn btn-primary">Save & Continue</button>
    </div>
  </div>
</form>

        </div>
      </div>

    </div>

<script>
		function createdate(dateval){
			// dateval must be in dd/mm/yyyy format	
			var x=dateval.split("-");
			var jvsdateval=new Date(x[2],parseInt(x[1]-1),parseInt(x[0]));
			return jvsdateval;
		}
		var DateDiff = {
			inDays: function(d1, d2) {
				var t2 = d2.getTime();
				var t1 = d1.getTime();
				return parseInt((t2-t1)/(24*3600*1000));
			},
			inWeeks: function(d1, d2) {
				var t2 = d2.getTime();
				var t1 = d1.getTime();
				return parseInt((t2-t1)/(24*3600*1000*7));
			},
			inMonths: function(d1, d2) {
				var d1Y = d1.getFullYear();
				var d2Y = d2.getFullYear();
				var d1M = d1.getMonth();
				var d2M = d2.getMonth();
				return (d2M+12*d2Y)-(d1M+12*d1Y);
			},
			inYears: function(d1, d2) {
				return d2.getFullYear()-d1.getFullYear();
			}
		}

	  	function calculate_retireyear(employee_dob){
			//alert(employee_dob);
			//child1 = new ajaxLoader('.child1');
			$.post('page/intra_ehrms/common/retirement_date.php',{employee_dob:employee_dob},function(data){
				if(data){
					//if (child1) child1.remove();
					//alert(data);
					$('#emp_date_retirement').val(data);
				}
			});
	  	}
</script>