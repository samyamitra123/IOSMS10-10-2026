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


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

	
		
$cryptoGraph=new cryptography();		
			
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "P&RD | Govt. of West Bengal ";

//Self variable




//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

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
		//$dise = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
		function func_gradepay($val){
			$db = new database();
			$arr = $db->fetch_table("select grade_amount from mad_dise_gradepay_master where grade_code='$val'");
			return $arr[0]['grade_amount'];
		}
	

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
											AND (tch.emp_status='1' OR tch.emp_status='11') and emp_desig<>'1120' ORDER BY tch.emp_id_pk DESC ");
		

		$emp_data = $db->fetch_table("SELECT   
                                        DISTINCT(emp_id_fk),
										approval_status
										
									FROM
										prd_loan_deduction
								");




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
    <h1 class="heading">EMOLOYEE LOAN MANAGEMENT  </h1>
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
            <th>Unlock Request</th>
            <th width="100">Action <?php //print_r($tch); ?></th>
        </tr>       
	</thead>
    <tbody>
			<?php
			 
			if(count($tch)){ 
			$count = 1; 
			foreach ($tch as $key) {
				//print_r($key);
				$empcd = $key['empcd'];
				$emp_id_pk=$key['emp_id_pk'];
				
	   
	        //---------- Start Gpf=0 before retirement--------------------
			 $retirement_date=$key['emp_retirement_date'];
		
			 $date=date('Y-m-d', strtotime('-3 month',strtotime($retirement_date))); 	
					
			 $emp_next_increment_amount=$key['emp_next_increment_amount'];
	    	 $emp_next_increment_date=$key['emp_next_increment_date'];	
			
			 $emp_first_join_date=$key['emp_first_join_date'];
			 $emp_first_join_match_date=date('Y-m-d', strtotime('+12 month',strtotime($emp_first_join_date)));
			
			
			
					if(strtotime($emp_next_increment_date)<=strtotime(date('Y-m-d')) && strtotime($emp_next_increment_date)>0)
				    {
					  $pay_in_band =$key['emp_pay_in_payband']+$emp_next_increment_amount;
				    }
					else
					{
				      $pay_in_band = $key['emp_pay_in_payband'];
					}
				
				
				$emp_id_const=$key['emp_id_const'];
			
				$emp_loan_status = $db->fetch_table("SELECT   
                                        status,
										approval_status,
										lock_status
										FROM
										prd_loan_deduction where emp_id_fk='".$emp_id_pk."'
										AND status not in(0) AND approval_status not in(1) ORDER BY loan_id_pk DESC
								");	
			
		  

if(($emp_loan_status[0]['status']=='1') && ($emp_loan_status[0]['approval_status']=='2')){
	$status='<p style="font-weight:bold;color:#FA8072;">WAITING FOR APPROVAL</p>';
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
/*else{
	$status='<p class="text-danger" style="font-weight:bold">SUBMISSION PENDING</p>';
}*/
if(!empty($emp_loan_status))
{
?>
            
          <tr>
            <td id="show"><?php echo $count; ?></td>
            <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
            <td><?php echo $emp_id_const; ?></td>
            <td><?php echo $status; ?></td>
            <td><?php $emp_loan_lock_status = $db->fetch_table("SELECT prd_loan_deduction.lock_status,prd_loan_deduction.deduction_loan_type_variable,  
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
				}
			}				
				if(strlen($locked_status) != 0)
				{
					$status_locked = "<p style='font-weight:500;'><font color='#FF0000'>".strtoupper($locked_status)." UNLOCKED</font></p>";
				}
				else if($locked_status=='' && ($emp_loan_status[0]['lock_status'] == '2'||$emp_loan_status[0]['lock_status'] == '3'))
				{
					$status_locked = "<p style='font-weight:500;'><font color='#008000'>"."LOCKED</font></p>";
				}
				else 
				{
					$status_locked = "<p style='font-weight:bold;color:#FA8072;'>N/A</p>";
				}
			 	echo $status_locked; ?></td>
             
             <td><?php /*$emp_loan_lock_status = $db->fetch_table("SELECT prd_loan_deduction.lock_status,prd_loan_deduction.deduction_loan_type_variable,  
                                        prd_master_loan_type.loan_type
										FROM
										prd_loan_deduction,mad_master_loan_type where prd_loan_deduction.emp_id_fk='".$emp_id_pk."' AND prd_master_loan_type.dise_code = prd_loan_deduction.dise_code_fk AND 
										mad_loan_deduction.status in(2) AND prd_loan_deduction.approval_status in(3) AND prd_loan_deduction.lock_status in(3) ORDER BY prd_loan_deduction.loan_id_pk DESC
								");*/
								$locked_status = '';
				/*foreach($emp_loan_lock_status as $lock_status)
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
				}	*/
				if(strlen($locked_status) != 0)
				{
					$status_locked = "<p style='font-weight:500;'><font color='#FF0000'>".strtoupper($locked_status)."</font></p>";
				}
				else
				{
					$status_locked = "<p style='font-weight:bold;color:#FA8072;'>N/A</p>";
				}
			 echo $status_locked; ?></td>
    
       
       
              <td>
              
              <div class="view" style="display:inline;"><a data-bs-toggle="modal" data-bs-target="#view_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="View" /></a></div>&nbsp;&nbsp;
              
              
               <?php
				$chk_status = $db->fetch_table("SELECT loan_id_pk FROM prd_loan_deduction WHERE emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND status in(2) AND approval_status in (3) AND lock_status in(3)");
				if(count($chk_status)>0)
				{						
			  ?>
              <div class="lock_unlock" style="display:inline;"><a data-bs-toggle="modal" data-bs-target="#lock_unlock_loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><i class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"></i></a></div>
              <?php
				}
				else
				{?>
			  <div class="lock_unlock" style="display:inline;"><i style="opacity:0.5" class="fa fa-unlock-alt fa-2x reason_view" aria-hidden="true"></i></div>	
			  <?php	}
			  ?>
             
              </td>

            <?php $count += 1 ; 
				
			?>

            </tr> 
			
             <?php }   
			    }
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
		  //$.post('<?= $config['base_url'] ?>page/intra_mad/ddo/loan_module/ajax_loan_deduction_edit.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/u_ajax_loan_deduction_edit.php?id='+link, function(data){
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
		 // var link1= $("#gp_id").val();
		  //alert(link);
		  //alert(link1);
		  //$.post('<?= $config['base_url'] ?>page/intra_mad/ddo/loan_module/ajax_emp_loan_view.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/u_ajax_emp_loan_view.php?id='+link, function(data){
				// alert(data);
				 $("#mbody_view").html(data);
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
		  //alert(link);
		  //alert(link1);
		  //$.post('<?= $config['base_url'] ?>page/intra_mad/ddo/loan_module/ajax_emp_loan_module_lock_unlock.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/u_ajax_emp_loan_module_lock_unlock.php?id='+link, function(data){
				// alert(data);
				 $("#mbody_lock_unlock").html(data);
		       });
		    });
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

<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" >
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" onclick="return remove_conf();" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Unlock This Loan Details?</strong></p>
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



