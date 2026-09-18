
<?php

session_start();
error_reporting(0);

ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require_once '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:dashboard_intra_pri.php");
}
//$cryptoGraph=new cryptography();
$crypto=new cryptography();
   $EditOption =0;

   if($app_status[0]['status'] == 1)
     {

				//$Query =" SELECT * FROM intra_pri_forwarding WHERE application_id='".$app_no."' ORDER BY forwarding_id_pk ASC LIMIT 1 OFFSET 0";
				$Query = "SELECT forwarding_id_pk from intra_pri_forwarding WHERE application_id ='".$app_no."'AND status=1 AND from_officer_id_const ='".$_SESSION['user_info']['officer_id_const']."' order by forwarding_id_pk DESC LIMIT 1 OFFSET 0";
				$lastOfficerID = $db->fetch_table($Query);
				 //print_r($app_status[0]['status']);
				$EditOption = ($lastOfficerID[0]['forwarding_id_pk'] > 0)?1:0; 
		 }
		 //$EditOption = 1;
    //print($EditOption); 
?>
 <!-- modal -->


<style>
.modal-backdrop fade in{
  height:auto !important;
}

</style>
<?php
//echo $fi_sub;
//var_dump($_GET['fi_sub']); die;
$application_id = $_POST['app_no'];
$fi_sub =  $_POST['fi_sub'];
  $db=new database();
  
//echo $fi_sub_type;

//var_dump($fi_sub);

if($fi_sub =='myForm_PREV' || $fi_sub_type=='5'){
	
	 $emp_detail_ocon = $db->fetch_table("SELECT * FROM intra_pri_9008 WHERE application_id ='".$application_id."' ");  
	 //var_dump($emp_detail_ocon);
	 if($emp_detail_ocon[0]['sanctioned_post_name'] > 0)
     {
     	 $Query = "select code,description from prd_dise_code_master where code='".$emp_detail_ocon[0]['sanctioned_post_name']."'";
       //print($Query);exit;
       $arr = $db->fetch_table($Query);	 
       $SanctionPostName = $arr[0]['description'];
     } 
	 switch($emp_detail_ocon[0]['posting'])
     {
     	 case 'GP':
     	 $Query = "select gp_name from prd_location_master_gp where gp_id_pk='".$emp_detail_ocon[0]['gp_id_fk']."'";
       //print($Query);exit;
       $LocationData = $db->fetch_table($Query);	 
       $priName = '<p><b>'.$LocationData[0]['gp_name'].' Gram Panchayat</b></p>';
       break; 

     }        
  ?>
	<?php if($EditOption == 1):?>	
  <span style="text-align: right!important; width: 100%;">
  <a href="application-edit.php?app=<?php echo $crypto->encode($application_id,4); ?>" style="color:#000000;">Edit Application</a>
  </span>
  <?php endif; ?>  
  <div class="table-responsive">

    <table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
	  <tr style="border:1px solid black;" >
		<th style="border:1px solid black;">Place of Posting</th>
		<th style="border:1px solid black;">Employee Name</th>
		<th style="border:1px solid black;">Date Of Birth</th>
		<th style="border:1px solid black;">Date Of Engagement</th>
		<th style="border:1px solid black;">Whether engaged as Casual/Daily rated Worker/Contractual Worker</th>
		<th style="border:1px solid black;">Whether engaged against sanctioned vacant post</th>
		<th style="border:1px solid black;">Name of the sanctioned post</th>
		<th style="border:1px solid black;">Date of occurence of vacancy</th>
		<th style="border:1px solid black;">Amount of remuneration P.M.</th>
		<th style="border:1px solid black;">Whether the post is still vacant</th>
		<th style="border:1px solid black;">Source of fund</th>
		<th style="border:1px solid black;">Note</th>
		
	  </tr>
	  <tr style="border:1px solid black;" >
		<td style="border:1px solid black;"disabled><?php echo $emp_detail_ocon[0]['posting'];?></td>
		<td style="border:1px solid black;"disabled><?php echo $emp_detail_ocon[0]['emp_first_name'].' '.$emp_detail_ocon[0]['emp_second_name'].' '.$emp_detail_ocon[0]['emp_last_name'];?></td>
		<td style="border:1px solid black;"disabled><?php echo date ("d-m-Y",strtotime($emp_detail_ocon[0]['emp_dob'])); ?></td>
		<td style="border:1px solid black;"disabled><?php echo date("d-m-Y",strtotime($emp_detail_ocon[0]['date_engagement'])); ?></td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['weather_engagged']== 200){echo "Casual";}else if($emp_detail_ocon[0]['weather_engagged']== 201) {echo "Daily rated Worker";}else{echo "Contractual Worker";} ?>
			<?php echo $priName; ?>
		</td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['sanctioned_vacant_post']== 0){echo "No";}else{echo"Yes";} ?></td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['sanctioned_post_name']== 00){}else{echo $SanctionPostName ;} ?></td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['occurence_vacancy_date']== 0001-01-01){ }else{echo date("d-m-Y",strtotime($emp_detail_ocon[0]['occurence_vacancy_date']));} ?></td>
		<td style="border:1px solid black;"disabled><?php echo $emp_detail_ocon[0]['remuneration'] ?></td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['still_working']== 500){echo "Yes";}else{echo"No";} ?></td>
		<td style="border:1px solid black;"disabled><?php if($emp_detail_ocon[0]['source_of_fund']== 0){echo "OWN Source(OSR)";}else{echo "Project";} ?></td>
		<td style="border:1px solid black;"disabled><?php echo $emp_detail_ocon[0]['note']; ?></td>
		
	  </tr>
</table>
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
	<tr class="success">
		<td><strong>Engagement Letter :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 30 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id; ?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		
		<td><strong>Resolution (copy):</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 31 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id; ?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
	</tr>
	<tr class="success">
		<td><strong>Year Wise Attandance in the From of Certificate :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 32 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id; ?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		
		<td><strong>Certificate of BDO/AEO Zilla Parishad:</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 33 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id; ?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
	</tr>
	<tr class="success">
		<td><strong>Recommendation of DM/ADM(P)/AEO :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 34 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id; ?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
	</tr>
	
</table>

      </div>
      
<?php }


else if($fi_sub =='myForm_PREV_A' || $fi_sub_type=='5A'){
	
	$emp_detail_ocon_A = $db->fetch_table("SELECT * FROM entry_format_9008 WHERE application_id ='".$application_id."' ");  
	?>

<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
	<td colspan="4" style="text-align:center"><strong> Entry Format for approved contractual Employees under FD GO-9008 before 31/05/2022 </strong>
	<?php if($EditOption == 1):?>	
  <span style="text-align: right!important;">
  <a href="application-edit.php?app=<?php echo $crypto->encode($application_id,4); ?>" style="color:#ffffff;">Edit Application</a>
  </span>
  <?php endif; ?>
	</td>
</tr>
	<tr class="success">
		<td><strong>Application No. :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['application_id']; ?></td>
	</tr>
	<tr class="success">
		<td><strong>Block Name :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['block_name']; ?></td>
		
		<td><strong> GP/ PS/ ZP Stack:</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['gp_ps_zp_stake']; ?></td>
	</tr>
	<tr class="success">
		<td><strong> GP/ PS/ ZP Name :</strong></td>
		<td><?php $db = new database();
		
		if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '10'){
		$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
		echo $arr_gp[0]['gp_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '7'){
			$arr_ps =$db->fetch_table("select ps_name , ps_code from prd_location_master_panchayat_samiti where ps_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_ps[0]['ps_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '4'){
			$arr_zp =$db->fetch_table("select district_name , district_code from prd_location_master_district where district_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_zp[0]['district_name'];
		}		?>
		
		</td>
		
		<td><strong>Name of Casual/ Daily rated/ Contractual Workers:</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['worker_name']; ?></td>
	</tr>
	<tr class="success">
		<td><strong>Name of Sanctioned Post :</strong></td>
		<td><?php 
		$db = new database();
		$arr_desig =$db->fetch_table("select description , code from prd_dise_code_master where code = '".$emp_detail_ocon_A[0]['sanctioned_post_code']."'");
		echo $arr_desig[0]['description']; ?></td>
		
		<td><strong>Category of Post :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['post_category']; ?></td>
	</tr>
	<tr class="success">
		<td><strong>Period of Engagement :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['period_engagement']; ?></td>
		
		<td><strong>Present Remuneration :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['present_remuneration']; ?></td>
	</tr>
	
	<tr class="success">
		<td><strong>Date of Joining :</strong></td>
		<td><?php echo date("d-M-Y",strtotime($emp_detail_ocon_A[0]['joining_date'])); ?></td>
		
		<td><strong>Approval Order of Department (Memo No.) :</strong></td>
		<td><?php echo $emp_detail_ocon_A[0]['approval_order_memo_no']; ?></td>
	</tr>
	<tr class="success">
		<td><strong> Approval Order of Department :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 35 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		<td><strong> Copy of Wages Bill for entire period:</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 39 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>		
	</tr>
	<tr class="success">
		<td><strong> Copy of Engagement Letter :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 40 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		<td><strong> Proforma Report:</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 41 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>		
	</tr>

	<tr class="success">
		<td><strong> Year of occurrence of vacancy :</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 42 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		<td><strong> Certificate of BDO regarding number of days of service in each year:</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 43 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>		
	</tr>
	<tr class="success">
		<td><strong>Copy of Resolution:</strong></td>
		<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 44 and application_id='".$application_id."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id ?>&flag=<?=$arr_file[0]['flag']?>"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>

	</tr>			
</table>
</div>
<?php }


else if($fi_sub =='myForm_PREV_B' || $fi_sub_type=='5B'){
	
	$emp_detail_ocon_B = $db->fetch_table("SELECT * FROM enhancement_remuneration_9008 WHERE application_id ='".$application_id."' ");  
	?>

<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
	<td colspan="4" style="text-align:center"><strong>Enhancement of Remuneration after every year </strong>

	<?php if($EditOption == 1):?>	
  <span style="text-align: right!important;">
  <a href="application-edit.php?app=<?php echo $crypto->encode($application_id,4); ?>" style="color:#ffffff;">Edit Application</a>
  </span>
  <?php endif; ?>
	</td>
</tr>
	<tr class="success">
		<td><strong>Application No. :</strong></td>
		<td><?php echo $emp_detail_ocon_B[0]['application_id']; ?></td>
	</tr>
	<tr class="success">
		<td><strong> GP/ PS/ ZP Stack:</strong></td>
		<td><?php echo $emp_detail_ocon_B[0]['gp_ps_zp_stake']; ?></td>
		<td><strong> GP/ PS/ ZP Name :</strong></td>
		<td><?php $db = new database();
		
		if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '10'){
		$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
		echo $arr_gp[0]['gp_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '7'){
			$arr_ps =$db->fetch_table("select ps_name , ps_code from prd_location_master_panchayat_samiti where ps_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_ps[0]['ps_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '4'){
			$arr_zp =$db->fetch_table("select district_name , district_code from prd_location_master_district where district_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_zp[0]['district_name'];
		}		?></td>
	</tr>
	<tr class="success">
		<td><strong>Approved Casual/ Daily rated/ Contractual Workers:</strong></td>
		<td><?php echo $emp_detail_ocon_B[0]['worker_name']; ?></td>
		<td><strong>Present Remuneration :</strong></td>
		<td><?php echo $emp_detail_ocon_B[0]['present_remuneration']; ?></td>
	</tr>
	<tr class="success">
		<td><strong>Enhanced Remuneration :</strong></td>
		<td><?php echo $emp_detail_ocon_B[0]['enhanced_remuneration']; ?></td>
	</tr>
	
	
</table>
</div>
<?php }


else if($fi_sub =='myForm_PREV_C' || $fi_sub_type=='5C'){
	
	$emp_detail_ocon_C = $db->fetch_table("SELECT * FROM fund_requisition_9008 WHERE application_id ='".$application_id."' ");  
	?>

<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
	<td colspan="4" style="text-align:center"><strong>Requisition of Fund for Approved Casual/ Daily rated/ Contractual workers for payment of Remuneration </strong>

	<?php if($EditOption == 1):?>	
  <span style="text-align: right!important;">
  <a href="application-edit.php?app=<?php echo $crypto->encode($application_id,4); ?>" style="color:#ffffff;">Edit Application</a>
  </span>
  <?php endif; ?>
	</td>
</tr>
	<tr class="success">
		<td><strong>Application No. :</strong></td>
		<td><?php echo $emp_detail_ocon_C[0]['application_id']; ?></td>
	</tr>
	<tr class="success">
		<td><strong> GP/ PS/ ZP Stack:</strong></td>
		<td><?php echo $emp_detail_ocon_C[0]['gp_ps_zp_stake']; ?></td>
		<td><strong> GP/ PS/ ZP Name :</strong></td>
		<td><?php $db = new database();
		
		if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '10'){
		$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
		echo $arr_gp[0]['gp_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '7'){
			$arr_ps =$db->fetch_table("select ps_name , ps_code from prd_location_master_panchayat_samiti where ps_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_ps[0]['ps_name'];
		}
		else if(strlen($emp_detail_ocon_A[0]['gp_ps_zp_code']) == '4'){
			$arr_zp =$db->fetch_table("select district_name , district_code from prd_location_master_district where district_code = '".$emp_detail_ocon_A[0]['gp_ps_zp_code']."'");
			echo $arr_zp[0]['district_name'];
		}		?></td>
	</tr>
	<tr class="success">
		<td><strong>Approved Casual/ Daily rated/ Contractual Workers:</strong></td>
		<td><?php echo $emp_detail_ocon_C[0]['worker_name']; ?></td>
		<td><strong>Select Duration (Financial year-wise) :</strong></td>
		<td><?php echo $emp_detail_ocon_C[0]['duration']; ?></td>
	</tr>
	<tr class="success">
		<td><strong>Auto Calculation :</strong></td>
		<td><?php echo $emp_detail_ocon_C[0]['auto_calculation']; ?></td>
	</tr>
	
	
</table>
</div>
<?php }


 

 else if($fi_sub == 'fi_sub_form'){ 

	$application_id = $_SESSION['user_info']['application_id'];
	$db=new database();
	$update_9008_final = $db->update(" UPDATE intra_pri_9008 SET active_status = '1' WHERE application_id ='".$application_id."' ");	
	if($update_9008_final){
		$insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu, forward_head)
			VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'5', '0', '5','1')");
	}
	
	if($update_9008_final && $insert_request_forwarding){ unset ($_SESSION['user_info']['application_id']); 
		
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>9008 PROPOSAL SAVED SUCCESSFULLY. </strong></div>';
		
	}
	else{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>9008 PROPOSAL SAVED FAILED. </strong></div>';
	}
 }
 
 
 else if($fi_sub == 'fi_sub_formA'){ 
	$application_id = $_SESSION['user_info']['application_id'];
	$db=new database();
	$update_9008_final = $db->update(" UPDATE entry_format_9008 SET active_status = '1' WHERE application_id ='".$application_id."' ");	
	if($update_9008_final){
		$insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu, forward_head)
			VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'5', '0', '5A', '1')");
	}
	
	if($update_9008_final && $insert_request_forwarding){ unset ($_SESSION['user_info']['application_id']); 
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>9008 PROPOSAL SAVED SUCCESSFULLY. </strong></div>';
		
	}
	else{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>9008 PROPOSAL SAVED FAILED. </strong></div>';
	}
 }
 
 
 
 else if($fi_sub == 'fi_sub_formB'){ 
	$application_id = $_SESSION['user_info']['application_id'];
	$db=new database();
	$update_9008_final = $db->update(" UPDATE enhancement_remuneration_9008 SET active_status = '1' WHERE application_id ='".$application_id."' ");	
	if($update_9008_final){
		$insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu, forward_head)
			VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'5', '0', '5B','1')");
	}
	
	if($update_9008_final && $insert_request_forwarding){ unset ($_SESSION['user_info']['application_id']); 
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>9008 PROPOSAL SAVED SUCCESSFULLY. </strong></div>';
		
	}
	else{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>9008 PROPOSAL SAVED FAILED. </strong></div>';
	}
 }
 
 
 
 else if($fi_sub == 'fi_sub_formC'){ 
	$application_id = $_SESSION['user_info']['application_id'];
	$db=new database();
	$update_9008_final = $db->update(" UPDATE fund_requisition_9008 SET active_status = '1' WHERE application_id ='".$application_id."' ");	
	if($update_9008_final){
		$insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu, forward_head)
			VALUES ('".$application_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'5', '0', '5C','1')");
	}
	
	if($update_9008_final && $insert_request_forwarding){ unset ($_SESSION['user_info']['application_id']); 
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>9008 PROPOSAL SAVED SUCCESSFULLY. </strong></div>';
		
	}
	else{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>9008 PROPOSAL SAVED FAILED. </strong></div>';
	}
 }
 
	?>