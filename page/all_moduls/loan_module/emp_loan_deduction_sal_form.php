<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

 
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


if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}


//error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

//   Kalyan Ghosh   16/3/2017    Finish

$crypto = new cryptography();

//$municipality_id = $_SESSION['user_info']['stake_user'];//By Nirupam
//$municipality_id_fk = substr($municipality_id,0,7);
$district_id = $_SESSION['location']['district_id'];
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//$str=$_SESSION['location']['gpcode'];
//$state10=substr($str,0,4);

//-------------------------------------------------------------------

	$db = new database();
	$tch = array();
	//$created_by = substr($_SESSION['location']['block_code'], 0, 7);
	$tch= $db->fetch_table("
								SELECT  
										tch.emp_id_pk,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_system_code,
										tch.emp_pay_in_payband,
										tch.emp_spouse_hra,
										tch.emp_diff_able,
										tch.emp_spouse_res,
										tch.emp_bank_name,
										tch.emp_acc_no,
										tch.emp_ifsc_no,
										tch.emp_id_pk,
										tch.empcd,
										emp_retirement_date,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_diff_able,
										tch.emp_next_increment_date,
										tch.emp_next_increment_amount,
										tch.emp_first_join_date,
										tch.emp_id_const
										
									FROM
										prd_employee_master as tch
									WHERE
											tch.zp_id_fk = '".$district_id."'
											AND (tch.emp_status='1' OR tch.emp_status='11') and emp_desig<>'1120' ORDER BY tch.emp_id_pk DESC");
		
	
		$emp_data = $db->fetch_table("SELECT   
                                        DISTINCT(emp_id_fk),
										approval_status
										
									FROM
										prd_loan_deduction
								");





//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------




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




<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
    


    

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
    <h1 class="heading">EMPLOYEE LOAN DETAILS </h1>
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

<div class="row">
        <div class="content">
       <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
       <div class="emplist" style="width:98%;">
<div class="school">
<div class="table-responsive">
           
       <table class="table-responsive" style="width:100%;">
                <thead>
           		    <tr>
                       <th style="width: 5%;">SL NO.</th>
                       <th>Employee Name</th>
                       <th>Employee Id</th>
                       <th>Status</th>
                       <th>Lock Status</th>
                       <th width="150">Action <?php //print_r($tch); ?></th>
                    </tr>       
                </thead>
                <tbody>
                        <?php
                         
                        if(count($tch)){
                        ?>

                        <?php 
                         
                        $count = 1; 
                        foreach ($tch as $key) {
                            //print_r($key);
                         $empcd = $key['empcd'];
                         $emp_id_pk=$key['emp_id_pk'];


                            //$grade_pay = func_gradepay($key['emp_grade_pay']);
                            $emp_id_const=$key['emp_id_const'];
                 
                        
                        	$emp_loan_status = $db->fetch_table("SELECT   
                                                    status,
                                                    approval_status,
													lock_status
                                                    FROM
                                                    prd_loan_deduction where emp_id_fk='".$emp_id_pk."'
                                                    AND status not in(0) ORDER BY loan_id_pk DESC
                                            ");	
                            
                            
                        
           
            if(($emp_loan_status[0]['status']=='1') && ($emp_loan_status[0]['approval_status']=='2')){
                $status='<p style="font-weight:bold; color:#FA8072;">FORWARDED FOR APPROVAL</p>';
            }
            else if(($emp_loan_status[0]['status']=='2') && ($emp_loan_status[0]['approval_status']=='3')){
                $status='<p style="font-weight:bold;color:#008000;">APPROVED</p>'; 
            }
            else if(($emp_loan_status[0]['status']=='1') && ($emp_loan_status[0]['approval_status']=='4')){
                $status='<p style="font-weight:bold;color:#FF0000;">REJECTED BY ACCOUNTANT</p>';
            }
            else if(($emp_loan_status[0]['status']=='1') && ($emp_loan_status[0]['approval_status']=='1')){
                $status='<p style="font-weight:bold;color:#FA8072;">SUBMISSION PENDING</p>';
            }
            else{
                $status='<p style="font-weight:bold;color:#FA8072;">N/A</p>';
            }
            ?>
                        
                      <tr>
                        <td id="show" width="5%"><?php echo $count; ?></td>
                        <td id="emp_name" width="25%"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
                        <td width="15%"><?php echo $emp_id_const; ?></td>
                        <td width="15%"><?php echo $status; ?></td>
                        <td width="15%"><?php
                        
						
                        $emp_loan_lock_status = $db->fetch_table("SELECT prd_loan_deduction.lock_status,prd_loan_deduction.deduction_loan_type_variable,  
                                                    prd_master_loan_type.loan_type
                                                    FROM
                                                    prd_loan_deduction,prd_master_loan_type where prd_loan_deduction.emp_id_fk='".$emp_id_pk."' AND prd_master_loan_type.dise_code = prd_loan_deduction.dise_code_fk AND prd_loan_deduction.status in(2) AND prd_loan_deduction.approval_status in(3) AND prd_loan_deduction.lock_status in(4) ORDER BY prd_loan_deduction.loan_id_pk DESC
                                            ");	
                           
                            $locked_status = '';
						if(!empty($emp_loan_lock_status)){	
                            foreach($emp_loan_lock_status as $lock_status)
                            {
                                if($locked_status == '')
                                {
									
                                    $concate = '';	
                                }
                                else
                                {
                                    $concate = ', ';	
                                }
                                $locked_status = $locked_status.$concate.$lock_status['loan_type'];
                                //$locked_status[$i] = $lock_status['loan_type'];
                            }	
						}
                            if(strlen($locked_status) != 0)
							{
								$status_locked = "<p style='font-weight:500;'><font color='#FF0000'>".strtoupper($locked_status)." UNLOCKED</font></p>";
							}
							else if($locked_status=='' && ($emp_loan_status[0]['lock_status'] == '2'))
							{
								$status_locked = "<p style='font-weight:500;'><font color='#008000'>LOCKED</font></p>";
							}
							else if($locked_status=='' && ($emp_loan_status[0]['lock_status'] == '3'))
							{
								$status_locked = "<p style='font-weight:500;'><font color='#FA8072'>UNLOCK REQUEST SENT</font></p>";
							}
							else 
							{
								$status_locked = "<p style='font-weight:bold;color:#FA8072;'>N/A</p>";
							}		
                        	echo $status_locked;
							  ?></td>
                    
                          <td width="21%">
                          <?php
						  $chk_send_status = $db->fetch_table("SELECT loan_id_pk FROM prd_loan_deduction WHERE emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND status in(1) AND approval_status in (2) AND edit_status in (1) AND lock_status in(1)");
						  if(count($chk_send_status)>0)
						  {						
						  ?>
                          <div class="lock_unlock" style="display:inline; padding-left:21px;"><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" style="opacity:0.5" alt="Edit" title="Edit"></div>
                          <?php
						  }
                          else
						  {?>
                          <div class="edit" style="display:inline; padding-left:21px;"><a data-bs-toggle="modal" data-bs-target="#edit_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" title="Edit"></a></div>	
						  <?php	}
						  ?>
                          <div class="view" style="display:inline; padding-left:21px;"><a data-bs-toggle="modal" data-bs-target="#view_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="View"/></a></div>
             			  <?php
						  $chk_status = $db->fetch_table("SELECT loan_id_pk FROM prd_loan_deduction WHERE emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND status in(2) AND approval_status in (3) AND edit_status in (1) AND lock_status in(2)");
						  if(count($chk_status)>0 && count($chk_send_status)<1)
						  {						
						  ?>
                          <div class="lock_unlock" style="display:inline; padding-left:21px;"><a data-bs-toggle="modal" data-bs-target="#lock_unlock_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><i class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"></i></a></div>
                          <?php
						  }
						  else
						  {?>
						  <div class="lock_unlock" style="display:inline; padding-left:21px;"><i style="opacity:0.5" class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"></i></div>	
						  <?php	}
						  ?>
                          
                          <?php
						  	$chk_status = $db->fetch_table("SELECT loan_id_pk FROM prd_loan_deduction WHERE emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND status in(1) AND approval_status in (1,4) AND delete_status in (1) AND edit_status in (1)");
							if(count($chk_status)>0 && count($chk_send_status)<1)
							{						
						  ?>
                           <div class="delete" style="display:inline; padding-left:21px;"><a data-bs-toggle="modal" data-bs-target="#delete_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><i class="fa fa-trash fa-2x" aria-hidden="true" style="color:#db5151"></i></a></div>
                          <?php
							}
                           else
							{?>
						  <div class="delete" style="display:inline; padding-left:21px;"><i class="fa fa-trash fa-2x" aria-hidden="true" style="opacity:0.5"></i></div>	
						  <?php	}
						  ?>
                          </td>

                         <?php $count += 1 ; }   
                        
                        } 
                        else {?> <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
                   </tbody>
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

		  $(".edit a").click(function() {	
		  //alert('dd');
		  var tch=(<?=count($tch)?>);
			
		  $( "tr:odd" ).css( "background-color", "#FFFFFF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//var link1= $("#gp_id").val();

		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/loan_module/ajax_loan_deduction_edit.php?id='+link+'&gp_id='+link1, function(data)
		
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/ajax_loan_deduction_edit.php?id='+link, function(data){
				// alert(data);
				 $("#mbody").html(data);
		       });
		    });
			
		$(".view a").click(function() {	
		  //alert('dd');
		  var tch=(<?=count($tch)?>);
			
		  $( "tr:odd" ).css( "background-color", "#FFFFFF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//var link1= $("#gp_id").val();
		 
		
		
		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/loan_module/ajax_emp_loan_view.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/ajax_emp_loan_view.php?id='+link, function(data){
				// alert(data);
				 $("#mbody_view").html(data);
		       });
		    });
		
		$(".delete a").click(function() {	
		  //alert('dd');
		  var tch=(<?=count($tch)?>);
			
		  $( "tr:odd" ).css( "background-color", "#FFFFFF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//var link1= $("#gp_id").val();
		 
		
		
		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/loan_module/ajax_emp_loan_delete.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/ajax_emp_loan_delete.php?id='+link, function(data){
				// alert(data);
				 $("#mbody_delete").html(data);
		       });
		    });
			
	
		$(".lock_unlock a").click(function() {	
		  //alert('dd');
		  var tch=(<?=count($tch)?>);
		  $( "tr:odd" ).css( "background-color", "#FFFFFF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//var link1= $("#gp_id").val();
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/loan_module/ajax_emp_loan_module_lock_unlock.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/ajax_emp_loan_module_lock_unlock.php?id='+link, function(data){
				// alert(data);
				 $("#mbody_lock_unlock").html(data);
		       });
		    });

</script>	


<script>
function send_all_to_ddo()
{
	
  $('#send_ddo_all').modal({
	  show:true
  });
  $('#ir_conform_send_all').click(function() {
	
	  window.location.href=("emp_loan_module_submit_all_to_ddo.php");
	 
  });  
}
</script>
    
 
	   
<!-----------------------------------------------------------------MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="edit_loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-lg" id="view_loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      
      <div id="mbody_view"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-lg" id="delete_loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      
      <div id="mbody_delete"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-lg" id="lock_unlock_loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
       <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
        <button type="button" class="close" data-bs-dismiss="modal" onclick="return close_lock_unlock();" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
      
      <div id="mbody_lock_unlock"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" onclick="return close_lock_unlock();">Close</button>      
      </div>
    </div>
  </div>
</div>

<script>
function view_gp_modal(emp_id_pk)
 {
	
   //alert(emp_id_pk);	
 
  alert('dd');
  $('#send').modal({
	  show:true
  });
  $('#ir_conform').click(function() {
	
	  window.location.href=("loan_deduction.php?id="+emp_id_pk);
	 
  });
	  
 }
</script>
   
<!-----------------------------------------------------------------MODAL END---------------------------------------------------->
<!--<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send Interim Relief For Approval ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        
<a class="btn btn-success" id="ir_conform" href="javascript:void(0);">YES</a>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>-->

<script>
function close_lock_unlock()
{
		$(".modal-backdrop").hide();
}
</script>

<div class="modal fade bs-example-modal-sm" id="send_ddo_all" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
      
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       <h4 class="modal-title" id="myModalLabel">Employee Loan Details Approval</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send All Saved Loan Details For Approval?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
		<a class="btn btn-success" id="ir_conform_send_all">YES</a>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" >
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" onclick="return remove_conf();" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send Unlock Request For This Loan Details?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      	<input type="hidden" id="loan_id_pk" />
        <input type="hidden" id="emp_id_fk" />
        <a class="btn btn-success" id="send_conf" href="javascript:void(0);" onclick="return confirm_send();">YES</a> 
        <button type="button" class="btn btn-warning" onclick="return remove_conf();">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
