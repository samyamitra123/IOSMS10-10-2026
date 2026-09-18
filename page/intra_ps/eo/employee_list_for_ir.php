<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
<?
$db=new database();

$arr=$db->fetch_table("SELECT 
						DISTINCT( mas.emp_id_const),
						mas.emp_first_name,
						mas.emp_second_name,
						mas.emp_last_name,
						mas.emp_id_pk,
						ir.emp_id_fk,
						ir.ir_status,
						ir.interim_relief,
						mas.ps_id_fk,
						ir.ps_id_fk,
						ir.delete_status,
						ir.unlock_req
					FROM prd_employee_master mas 
				INNER JOIN psemp_interim_relief ir 
				ON(mas.emp_id_pk=ir.emp_id_fk AND mas.ps_id_fk=ir.ps_id_fk)
				WHERE ir.ir_status in('3','4') AND ir.unlock_req in('0','1','2') AND ir.delete_status ='1' AND ir.ps_id_fk='".$_SESSION['location']['ps_id']."' order by mas.emp_id_const");
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
<? require '../../../page/common_back_btns.php'; ?>
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
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
    <div class="col-sm-12">
    <h1 class="heading"> EMPLOYEE IR DETAILS</h1>
    <div class="border"></div>
    </br>
    </br>
<?
     if($_SESSION['msg'])
     {
     echo $_SESSION['msg'];
     unset($_SESSION['msg']);
      }
?>  
<?php 
	if($msg)
    {
	echo $msg;
	echo "<br/>";
	echo "<br/>";
    }
?>
    <div class="emplist">
    <div class="school">
    <div class="table-responsive">
    <table width="100%">
    <tr>
    <th>Serial No.</th>
    <th>Employee ID</th>
    <th>Employee Name</th>
    <th>IR</th>
    <th>EDIT</th>
    <th>Action</th>
    
    </tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item)
 {

?>
<input type="hidden" id="id<?=$cnt; ?>" name="id" value="<?=$cryptoGraph->encode($item['emp_id_fk'],4);?>"  />
<input type="hidden" id="ename<?=$cnt; ?>" name="ename" value="<?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'];?>"  />
<input type="hidden" id="emp_id_const<?=$cnt; ?>" name="id" value="<?=$item['emp_id_const'];?>"  />
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<? ?>

<td><?php echo $item['interim_relief']==''?'0':$item['interim_relief'];?>
</td>
<td><? if($item['ir_status']=='4' && $item['delete_status']=='1' && $item['unlock_req']=='0'){ ?><a data-toggle="modal" onClick="ir_edit(<?=$cnt;?>);" style="cursor:pointer; "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" ></a>
<? } 
else { ?>
	<a><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" style="opacity:0.5" ></a>
</td>
<?php } ?>
<td>
 <?php   if($item['unlock_req']=='1' && $item['ir_status']=='4' && $item['delete_status']=='1') 
{ ?>
	<a  onClick="unlock_req(<?=$cnt;?>)"><i class="fa fa-key fa-2x" aria-hidden="true"></i></a>
<?php }
else
{ ?>
	 <i class="fa fa-key fa-2x" aria-hidden="true" style="opacity:0.5;"></i>
	
<?php
} ?>
    
   
<?php if($item['ir_status']=='3')
{ ?>
	<a class="btn btn-success"style="margin-left:10%" onClick="request_accept(<?=$cnt;?>);">ACCEPT</a>
<? } 
else
{ ?>
	<a class="btn btn-success" style="margin-left:10%" disabled >ACCEPT</a>
<?php
 } 
if($item['ir_status']=='3') 
{ ?>
	<a class="btn btn-danger" onClick="request_reject(<?=$cnt;?>)">REJECT</a>
<?php }
else
{ ?>
	<a class="btn btn-danger" disabled>REJECT</a>
	
<?php
} ?>
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




<? require '../../../page/layout/footer.php'; ?>
<script>
function ir_edit(k)
{
	var emp_id=$('#id'+k).val();
	$('#unlock_emp_id').val(emp_id);
	 var emp_name=$('#ename'+k).val();
	 $('#emp_name').html(emp_name);
	 var emp_id_const=$('#emp_id_const'+k).val();
	 $('#emp_id_const').html(emp_id_const);
	 $('#edit').modal('show');
}

function request_accept(k)
{
	var emp_id=$('#id'+k).val();
	$('#request').val(emp_id);
	$('#accept_show').show();
	$('#reject_show').hide();
	$('#flag').val('accept');
	$('#ir_confirmation').modal('show');
}

function request_reject(k)
{
	var emp_id=$('#id'+k).val();
	$('#request').val(emp_id);
	$('#reject_show').show();
	$('#accept_show').hide();
	$('#flag').val('reject');
	$('#ir_confirmation').modal('show');
}

function unlock_req(k)
{ 
	var emp_id=$('#id'+k).val();
	$('#request').val(emp_id);
	$('#reject_show').hide();
	$('#accept_show').hide();
	$('#unlock_show').show();
	$('#flag').val('unlock');
	$('#ir_confirmation').modal('show');
}
</script>
<!----------------------------------------------------------MODAL START----------------------------------------------------------------->


<form name="unlock_emp" id="unlock_emp" action="employee_ir_submit.php" method="post">
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="hed">INTERIM RELIEF SUBMISSION</h4>
                    <br>
                    <div class="row">
                        <div class="col-sm-12 heading">
                        <label id="emp_name"></label> (<label id="emp_id_const"></label>) <label>Interim Relief</label>
                        </div>
                    </div>
                </div>
                <div class="modal-body"> 
                    <div class="form-group">
                        <div class="col-sm-4 "></div>
                        <div class="col-sm-4 ">
                            <input type="text" id="ir_submit"  class="form-control" name="ir_submit" placeholder="SUBMIT IR" autocomplete="off" />
                            <input type="hidden" name="ir_emp_name" id="ir_emp_name"/>
                        </div>
                        <div class="col-sm-4 "></div>
                    </div>
                    <div style="height:40px;"></div>
                    <div class="form-group">
                    	<div class="col-sm-3" style="margin-left:45%;">
                        <input type="submit" name="submit" value="SUBMIT" class="btn btn-success"  alt="center">
                        </div>
                    </div>
                    <div style="height:20px;"></div>
	 
                </div>
                <div class="modal-footer" aligin="center">
                    <div class="btn-group" >
                    <input type="hidden" id="unlock_emp_id" name="unlock_emp_id" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
  <!------------------------------------------------------------MODAL ENND-------------------------------------------------------------------------->


  <!------------------------------------------------------------MODAL START------------------------------------------------------------------------->
<form name="school_app" id="school_app"  action="emp_ir_accept.php" method="post">
<div class="modal fade bs-example-modal-sm" id="ir_confirmation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
     </div>
     <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do you want to <span id="accept_show" style="display:none;">Accept</span><span id="reject_show" style="display:none;">Reject</span><span id="unlock_show" style="display:none;">unlock</span><strong>The IR Request ?</strong></p>
      <input type="hidden" id="request" name="request" />
      <input type="hidden" id="flag" name="flag"/>

     </div>
     <div class="modal-footer">
     <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
     </div>
     </div>
    </div>
  </div>
</div>
</form>
<!---------------------------------------------------------------MODAL ENND-------------------------------------------------------->

 
 