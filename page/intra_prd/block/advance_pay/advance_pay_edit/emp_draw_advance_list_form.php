<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

require_once '../../../../../includes/config/config.php';
require_once '../../../../../includes/config/database.config.php';
require_once '../../../../../includes/library/database.class.php';
require_once '../../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
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
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$str=$_SESSION['location']['gpcode'];
 $state10=substr($str,0,4);
/*if(!$_GET['dise'] || $_GET['dise']=='' || !isset($_GET['dise'])){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}*/
//-------------------------------------------------------------------

		$db = new database();
		$dise = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
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
	
		$tch = array();
	
	
	$tch= $db->fetch_table("
								SELECT  
										emp_id_pk,
										emp_group,
										emp_first_name,
										emp_second_name,
										emp_last_name,
										emp_system_code,
										emp_pay_in_payband,
										emp_grade_pay,							
										emp_spouse_hra,
										emp_diff_able,
										emp_spouse_res,
										emp_bank_name,
										emp_acc_no,
										emp_ifsc_no,
										emp_id_pk,
										empcd,
										emp_retirement_date,
										emp_desig,
										emp_cosolidated_pay,
										gp_id_fk,
										emp_diff_able,
										emp_next_increment_date,
										emp_next_increment_amount,
										emp_first_join_date,
										emp_id_const
										FROM
										prd_employee_master
										WHERE
										gp_id_fk = '".$crypto->decode($_REQUEST['id'],4)."'
										AND emp_status='1' 
										and ((emp_group in ('633','634') 
        								and trim(emp_grade_pay) in ('1001','1002','1003','1004','1005','1006','1007')) or emp_desig='1120')
										and emp_retirement_date>'2016-11-30'
		
		");
		
		

		
		$data_draw=$db->fetch_table("select 
		count(distinct CASE WHEN (adv1.status_flag='1') then adv1.emp_id_fk else null end) as finz_count,
		count(distinct CASE WHEN '1' then adv1.emp_id_fk else null end) as total_emp
                                from 
								prd_adavance_pay adv1 where gp_id_fk = '".$crypto->decode($_REQUEST['id'],4)."'");
		
?>
			<style>
				#wait{
					display: none;
				}
				.headRow > div{
					 text-align:center !important;
				}
				#divcol > div{
					text-align:center !important;
				}
			</style>
            
   <div id="sess_msg">
    
  
            </div>  
     
     
       
       <?php 
	
	   	   
	   ?>
<table width="100%">

            <tr>
            <th></th>
            <th colspan="4"><strong>EMPLOYEE DETAILS</strong></th>
             <th></th>
           
           
           
            </tr>
            <tr>
            <th style="width: 5%;">SL NO.</th>
            <th>Employee Name</th>
             <th>Designation</th>
            <th>Grade Pay</th>
             <th>Willing To Draw Advance (Amount Of Rs.2000/5000)</th>
            <th>Action</th>
          
            </tr>

            
       
            <?php 
			if(count($tch)){ 
			$count = 1; 
			$m=1;
			foreach ($tch as $key) {
				//print_r($key);
				$empcd = $key['empcd'];
				$emp_id_pk=$key['emp_id_pk'];
		 		
             $db = new database();
			 $data_adv=$db->fetch_table("select adance_type,status_flag
                                from 
								prd_adavance_pay where emp_id_fk='$emp_id_pk'");
								
			$adv_type=$data_adv[0]['adance_type'];
			$status_flag=$data_adv[0]['status_flag'];
                ?> 
            <tr>
            <td id="show"><?php echo $count; ?></td>
            <td id="emp_name<?=$m?>"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
           <td><?php echo fun_common($key['emp_desig'],$code_data); ?></td>
           <td><?php if($key['emp_desig']=='1120')
		   			{ echo 0;}
		   			else
					{ echo func_gradepay($key['emp_grade_pay']);} ?></td>
            <td align="center"> <div align="center">
              <select name="draw_adv" id="draw_adv<?php echo $m; ?>" class="form-control" style="width:100px;">
                <option value="0" <? if($adv_type=='0'){ echo 'selected';}?>>NO</option>
                <option value="1" <? if($adv_type=='1'){ echo 'selected';}?>>YES</option>
              </select>
            </div></td>
        <input type="hidden" name="gp_id" id="gp_id" value="<?php echo $crypto->encode($key['gp_id_fk'],4) ?>" />
        <input type="hidden" name="emp_id" id="emp_id<?php echo $m; ?>" value="<?php echo $crypto->encode($key['emp_id_pk'],4) ?>" />
         <input type="hidden" name="desig" id="desig<?php echo $m; ?>" value="<?php echo $crypto->encode($key['emp_desig'],4) ?>" />
              <td> <button type="submit" class="btn btn-info" onclick="submit_data(<?php echo $m; ?>)" >Submit</button></td>
      
       <?php $count += 1 ; $m += 1 ; }} else {?> <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
         </table>
        
        <div class="form-group" id="finalize">
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <a href="#" class="btn btn-success btn-md" style="display:none;">Finalize</a>
            </div>
          </div>
        
        <?php
		if(count($tch) == $data_draw[0]['finz_count']){ 
		if(count($tch)){
         	?>
            <div class="form-group" id="sal_save" >
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <a onClick="finalize_list();" class="btn btn-success btn-md" id="finz_but">Finalize</a>
            </div>
          </div>
         	
		<?php } } ?>
        
         <style>
		
  #save
{
    cursor: pointer;
}
         .finalize{
         	text-align: center;
         	margin-left: 370px;
         }
         .finalize ul {
				list-style-type:none;
				margin:0;
				padding:0;
				overflow:hidden;
			}
			.finalize li {
				float:left;
			}
			.finalize a:link, .finalize a:visited {
				display:block;
				width:133px;
				font-weight:bold;
				color:#FFFFFF;
				text-align:center;
				height:32px;
				text-decoration:none;
				text-transform:uppercase;
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
			}
			.finalize a:hover, .finalize a:active {
				
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
				background-position: 0px 32px;
			}
         </style>
        
      
        
	   
<!-----------------------------------------------------------------MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 980px; margin-left: -5.5%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Salary Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<script>
		$(document).ready(function(){
			
			
			var tch=(<?=count($tch)?>);
			
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  //$("#wait").css("display","none");
		  
			
		});
		

function submit_data(k)
{
		
		var link = $(this).attr('id');
		var link1= $("#gp_id").val();
		var link2= $('#emp_id'+k).val();
		var link3= $('#desig'+k).val(); 
		var link4= $('#draw_adv'+k).val();
		var emp_name=$('#emp_name'+k).text();
			
		$.post('<?= $config['base_url'] ?>page/intra_prd/block/advance_pay/advance_pay_edit/entry_draw_advance_list_insert.php?id='+link+'&gp_id='+link1+'&emp_id='+link2+'&desig='+link3+'&adance_type='+link4, function(data){
			
				if(data=='success')
			 {
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong>'+emp_name+'\'s data has been updated successfully </strong></div>');
			 }
			 if(data=='fail')
			 {
				$('#sess_msg').html('<div class="alert alert-success" style="text-align:center"><strong> Sorrry!!! '+emp_name+'\'s data has not been updated </strong></div>');
			 }
			 
		       });
}
				
function finalize_list()
{
	
	var link1= $("#gp_id").val();
	$.post('<?= $config['base_url'] ?>page/intra_prd/gp/draw_advance/entry_draw_advance_list_finalize.php?&gp_id='+link1, function(data){
				//alert(data);
				// $("#mbody").html(data);
				
				if(data=='<div class="alert alert-success" style="text-align:center"><strong>You have successfully finalize the list.</strong></div>')
			 {
				 
				$('#finz_but').hide;
				$('#sess_msg').html(data);
				window.setTimeout(function(){
							window.location.replace("<?php echo $config['base_url'] . 'page/intra_prd/gp/draw_advance/emp_draw_advance_list.php'; ?>");
					},1200);
			 }
			 if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Finalization Fails.</strong></div>')
			 {
				$('#sess_msg').html(data);
			 }
			 
		});
	
	
}
				


			
       </script>
   
