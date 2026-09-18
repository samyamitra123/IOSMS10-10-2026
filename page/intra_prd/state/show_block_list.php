<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
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
$cryptoGraph=new cryptography();
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




<?php
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Unlock Salary Requisition| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

$db=new database();
$arr=$db->fetch_table("
						select 
						block_id_pk, block.block_code,block_name,count(emp.emp_id_pk) as total,(save.status_flag) as status
						from prd_location_master_gp gp
						INNER JOIN prd_location_master_block block
						ON block.block_id_pk=gp.block_id_fk
						INNER JOIN prd_employee_master emp
						ON gp.gp_id_pk=emp.gp_id_fk 
						LEFT JOIN (select emp_id_fk,status_flag from prd_employee_salary_save  where delete_status=1 and is_saved=1 AND salary_monthyear='".date('Ym')."') as save
						ON emp.emp_id_pk=save.emp_id_fk
						WHERE
						emp.emp_status=1
						AND CAST(block.block_code AS text) like '".$_SESSION['location']['state_code']."%' 
						group by block_id_pk,save.status_flag,block_code,block_name
						order by 
						block_name
						
						");
						
//print_r($status);
//echo $status[0]['status'] ; 
$count = count($status) ;						
?>
<style>
h1 {
display: block;
font-size: 2em;
-webkit-margin-before: 0.67em;
-webkit-margin-after: 0.67em;
-webkit-margin-start: 0px;
-webkit-margin-end: 0px;
font-weight: bold;
}
</style>

<script>
    	$(document).ready(function(){
			
			
			
			// alert(1);
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $('#save').click(function(e) {
				if($('#reason').val()==''){
					alert('Please enter reason for unlock.');	
					return false;
				}
				return true;
        	});
			
		  $(".unlock_btn").click(function() {	
			  var link = $(this).val();
			  var arr=link.split('&');
			  $('#salary_unlock').show();
			  $('#blck').html(arr[1]);
			  $('#block_code').val(arr[0]);
			  $('#block_id_fk').val(arr[3]);
			  $('#status').val(arr[2]);
		  });
		});
    </script>
<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3><?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h3>
       </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">BLOCK LIST</h1>
<div class="border"></div>
</br>
</br>
<?php 
if($_SESSION['msg']){
//echo 'aaaa';
echo $_SESSION['msg'];
//$_SESSION['msg']='';
unset ($_SESSION['msg']);
}
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Block Code</th>
<th>Block Name</th>
<th>Total Finalized Employee</th>
<th>Action</th>
</tr>
<? $total_finz=0;$cnt=1;
if(count($arr)){ foreach($arr as $item){ 
$total_finz+=$item['total'];
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['block_code']?></td>
<td><?= $item['block_name']?></td>
<td><?= $item['total']?></td>
<?php
if($item['status']=='3'){
?>
<td><button type="submit" name="submit" id="btnSubmit" class="btn btn-warning btn-sm unlock_btn" value="<?=$item['block_code']?> & <?= $item['block_name']?> & <?= $item['status']?>& <?=$item['block_id_pk']?> " data-toggle="modal" data-target="#salary_unlock">Unlock</button></td>
<?php } else { ?>
<td class="view"><button type="submit" name="submit" id="btnSubmit" class="btn btn-warning btn-sm disabled" >Unlock</button></td>
<?php } ?>
</tr>
<? $cnt+=1; } ?>
<tr>
<td style="font-weight:bold;">TOTAL:</td>
<td></td>
<td></td>
<td style="font-weight:bold;"><?= $total_finz ?></td>
<td></td>
</tr>
<? } else { ?>
<tr>
<td colspan="9" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>


<div class="modal fade" id="salary_unlock" tabindex="-1" role="dialog" aria-labelledby="salary_unlockLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="salary_unlockLabel">SALARY UNLOCK</h4>
      </div>
      <div class="modal-body">
      <div class="form-group">
            <label for="recipient-name" class="control-label">Salary Unlock for:  <label class="text-warning" id="blck"></label></label>
          </div>
        <form action="submit_unlock_salary.php" method="post">
          <div class="form-group">
            <label for="message-text" class="control-label">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" draggable="false"></textarea>
            <input type="hidden" name="block_code" id="block_code" />
            <input type="hidden" name="block_id_fk" id="block_id_fk" />
            <input type="hidden" name="status" id="status" />
          </div>
     
      <div class="modal-footer">
      <div class="btn-group" role="group">
		<input type="submit" name="submit" value="Save" id="save" class="btn btn-primary">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		</div>
      </div>
       </form>
    </div>
  </div>
</div>
