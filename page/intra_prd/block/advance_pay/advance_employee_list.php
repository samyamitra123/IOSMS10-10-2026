<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

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
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>
<?
$db=new database();




$arr=$db->fetch_table(  "
						SELECT 
						DISTINCT(adv.emp_id_fk),
						emp_first_name,
						emp_desig,
						emp_grade_pay, 
						emp_second_name,
						 emp_last_name,
						 gp_id_pk,gp_name, 
						 gp_code,  
						  
						 block_code,
						 advance_amount,
						  status_flag, 
						 adance_type, 
						advance_monthyear 
						from prd_adavance_pay adv 
						inner JOIN prd_location_master_gp gp on adv.gp_id_fk=gp.gp_id_pk 
						inner JOIN prd_employee_master emp on emp.emp_id_pk=adv.emp_id_fk
						 WHERE adv.gp_id_fk='".$cryptoGraph->decode($_REQUEST['id'],4)."'
						AND advance_monthyear='".date('Ym')."' and adv.status_flag in('2','3')
						

");





function func_gradepay($val){
			$db = new database();
			$arr = $db->fetch_table("select grade_amount from prd_dise_gradepay_master where grade_code='$val'");
			return $arr[0]['grade_amount'];
		}




$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}




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
<div class="content">
<? require '../../../../page/common_back_btns.php'; ?>
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
					<? echo $_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EMPLOYEE LIST FOR DRAW ADVANCE</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(!empty($_GET['msg'])){
echo $cryptoGraph->decode($_GET['msg'],4);
echo "<br/>";
echo "<br/>";
}
?>
<div class="msg"></div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Grade Pay</th>
<th>Willing To Draw Advance (Amount Of Rs.2000/5000)</th>
<th>Action</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
if($item['status_flag']=='1'){
	$status='<span style="color:#660066;font-weight:bold">NOT FINALIZED</span>';
}
else if($item['status_flag']=='2') {
	$status='<span style="color:green;font-weight:bold">FINALIZED</span>';
}
else if($item['status_flag']=='3') {
	$status='<span style="color:RED;font-weight:bold">LOCK</span>';
}
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= func_gradepay($item['emp_grade_pay'],$code_data); ?></td>
<td class="view"><?=$item['advance_amount'] ?></td>


<td class="edit"><?= $status; ?>
</td>
        
         
          
       
       
       
       
</tr>
 


<? $cnt+=1; }} else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
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
</div>
<div class="clear"></div>





<? require '../../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>


$(document).ready(function(){
			
			
			var tch=(<?=count($tch)?>);
			
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
		
		  $(".edit a").click(function() {	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		var link1= $("#gp_id").val();
		 
		
		
	//	alert(link1);
		//alert(link1);
		$.post('<?= $config['base_url'] ?>page/intra_prd/block/interim_relief/ajax_interim_relief_edit.php?id='+link+'&gp_id='+link1, function(data){
				
				 $("#mbody1").html(data);
		       });
		    });
			
		
		
		
		
			
		});












	
		
		
		
		
</script>
<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>

