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
			$( "#tch_dob" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
			$( "#doj" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
			});
			$( "#tch_approval_appointment" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy'
			});
			$( "#date_of_next_increment" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+60",
				dateFormat: 'dd-mm-yy'
			});
			$( "#emp_date_termination" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+50",
				dateFormat: 'dd-mm-yy'
			});
        });
		function calculate_retireyear(employee_dob){
			//alert(employee_dob);
			//child1 = new ajaxLoader('.child1');
			$.post('<?php echo $config['base_url'] ?>page/intra_prd/common/retirement_date.php',{employee_dob:employee_dob},function(data){
				if(data){
					//if (child1) child1.remove();
					//alert(data);
					$('#emp_date_retirement').val(data);
				}
			});
	  	}
        </script> 


<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
       <br /> <? require '../../../page/common_back_btns.php'; ?>
<h2 class="primary" align="center">Primary Details</h2>

<form class="form-horizontal" id="first_form" method="post" action="profile_entry_basic_insert.php">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?=$teacher_id_pk?>" />
   		<input type="hidden" name="tchcd" id="tchcd" value="<?=$tchcd?>" />
        <input type="hidden" name="emp_date_retirement" id="emp_date_retirement" />
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
 <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">District</label>
    <div class="col-sm-10">
      
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Block</label>
    <div class="col-sm-10">
      
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Gram Panchayet</label>
    <div class="col-sm-10">
      
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="tch_fname"  name="tch_fname" placeholder="First Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="tch_mname"  name="tch_mname" placeholder="Middle Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="tch_lname"  name="tch_lname" placeholder="Last Name">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">DOB</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="tch_dob" name="tch_dob" placeholder="DOB" onChange="calculate_retireyear(this.value)" value="<?=$tch_dob=='0001-01-01' ? '' : dateshow($tch_dob);?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Sex</label>
    <div class="col-sm-10">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
				?>
      <select class="form-control" name="drpSex" id="drpSex">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['code']. '<br />';?>
         <option value="<?= $key['code']; ?>" <? if('9'.$gender==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
       <? } ?>
      </select>
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Caste</label>
    <div class="col-sm-10">
    			<?
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)>=3 and code like '10%' and code!='100' and code!='105' and code!='106' order by code");
				?>
      <select class="form-control" name="drpCast" id="drpCast">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['code']. '<br />'; ?>
       <option value="<?= $key['code']; ?>" <? if('10'.$drpCast==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
       <? } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Voter ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control"  name="voter_id" id="voter_id" placeholder="Voter ID">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Aadhaar ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="aadhaar_no" id="aadhaar_no" placeholder="Aadhaar ID">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Educational Qualification</label>
    <div class="col-sm-10">
    <? $db = new database();
		$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '12%' and code in('1210','1211','1202','1203','1204','1206','1209') order by code");
	?>
      <select class="form-control" name="emp_quali" id="emp_quali">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['code']. '<br />'; ?>
       <option value="<?= $key['code']; ?>" <? if($employee_qualification==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
       <? } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
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
		$(document).ready(function(){
			$('#first_form').click(function(){
		var today_obj = new Date();
				if($("#tch_dob").val()!=''){
				datediff = DateDiff.inDays(createdate($('#tch_dob').val()),today_obj);	
					if(datediff <= 6750){
						alert('Date Of Birth must be greater than 18 year');
						return false;
					}
				}
		});
		});
</script>