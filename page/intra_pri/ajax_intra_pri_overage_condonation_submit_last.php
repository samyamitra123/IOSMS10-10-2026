
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


	function code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function code_block($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_id_pk='".$val."'");
		return $arr[0]['block_name'];																															
	}
	
	function code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
		return $arr[0]['district_name'];																															
	}
function first_code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_code='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function first_code_block($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_code='".$val."'");
		return $arr[0]['block_name'];																															
	}
	
	function first_code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_code ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function first_code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_code ='".$val."'");
		return $arr[0]['district_name'];																															
	}

	
	$emp_id_const = $_POST['emp_id_const'];
	$id =  $_POST['app_no'];
	$fi_sub =  $_POST['fi_sub'];


$db=new database();
/*
$insert_pk = $db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_master WHERE emp_id_const = '".$emp_id_const."' AND overage_condonation_pk= '".$cg_id."' ");
*/
//echo (" SELECT * FROM intra_pri_overage_condonation_master WHERE application_id = '".$id."' ");
$insert_pk = $db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_master WHERE application_id = '".$id."' ");
$arr_catagory = $db->fetch_table("select description from prd_dise_code_master where code='".$insert_pk[0]['emp_caste']."' order by code");
//var_dump($insert_pk);
?>

<!--<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_prd/gp/pdf_gp_details.php"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>-->
<div class="table-responsive">
<table width="100%" class="table" style="font-size:14px;">
<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
<td colspan="4" style="text-align:center"><strong> EMPLOYEE DETAIL </strong></td>
</tr>
<tr class="success">
	<td><strong>PROPOSAL NAME:</strong></td>
	<td><?php echo $insert_pk[0]['proposal']; ?></td>
</tr>
<tr class="success">
	<td><strong>APPLICATION ID:</strong></td>
	<td><?php echo $insert_pk[0]['application_id']; ?></td>
</tr>
<tr class="warning">
	<td><strong>EMPLOYEE ID :</strong></td>
	<td><?php echo $insert_pk[0]['emp_id_const']; ?></td>
	<td><strong>EMPLOYEE NAME :</strong></td>
	<td style="text-transform:uppercase;"><?php echo $insert_pk[0]['emp_first_name']." ". $insert_pk[0]['emp_second_name']." ". $insert_pk[0]['emp_last_name']; ?></td>
</tr>
<tr class="success">
	<td><strong>DATE OF BIRTH :</strong></td>
	<td><?php echo date("d-m-Y",strtotime($insert_pk[0]['emp_dob'])); ?></td>
	<td><strong>SEX :</strong></td>
	<td style="text-transform:uppercase;"><?php if(isset($insert_pk[0]['emp_sex']) && $insert_pk[0]['emp_sex']== 91){ echo "Male"; }
			else if(isset($insert_pk[0]['emp_sex']) && $insert_pk[0]['emp_sex'] == 92){ echo "Female"; }
			else if(isset($insert_pk[0]['emp_sex']) && $insert_pk[0]['emp_sex'] == 93){ echo "Others"; } ?></td>
</tr>
<tr class="warning">
	<td><strong>DESIGNATION:</strong></td>

	<td style="text-transform:uppercase;">
	<?php //$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$insert_pk[0]['emp_desig']."' order by code");
	$arr_des = $db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where designation_id='".$insert_pk[0]['emp_desig']."' order by designation_id");
		echo $arr_des[0]['designation_name']; ?></td>
		
		
<?php 

if($insert_pk[0]['gp_id_fk'] != 0 ){ ?>
	<td><strong>Name of GP Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo code_gp($insert_pk[0]['gp_id_fk'] ); ?></td>
    
<?php } 
else if($insert_pk[0]['ps_id_fk'] != 0 ){ ?>

<td><strong>Name of PS Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo code_ps($insert_pk[0]['ps_id_fk'] ); ?></td>
    

<?php } 
else if($insert_pk[0]['zp_id_fk'] != 0 ){  ?>

<td><strong>Name of ZP Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo code_district($insert_pk[0]['zp_id_fk'] ); ?></td>
    
	
<?php }
else{ ?>

    <td><strong>Name of GP or PS or ZP Posted: </strong></td>
    <td style="text-transform:uppercase;">NA </td>
    
<?php } ?>



</tr>
<tr class="success">
	<td><strong>FIRST APPOINMENT ORDER NO. :</strong></td>
	<td style="text-transform:uppercase;"><?php echo $insert_pk[0]['emp_first_memo_no']; ?></td>
	<td><strong>DATE OF JOINING IN THE FIRST POSTING:</strong></td>
	<td><?php echo date("d-m-Y",strtotime($insert_pk[0]['emp_first_join_date'])); ?></td>
</tr>
<tr class="warning">
	<td><strong>NAME OF APPOINTING AUTHORITY :</strong></td>
	<td><?php echo $insert_pk[0]['appoinment_authority']; ?></td>
	<td><strong>DATE OF EMPLOYMENT NOTIFICATION/</br> EMPLOYMENT EXCHANGE CALL LETTER DATE:</strong></td>
	<td><?php echo date("d-m-Y",strtotime($insert_pk[0]['notice_date'])); ?></td>
</tr>
<tr class="warning">
<?php 

if($insert_pk[0]['emp_first_tier'] == 1 ){ ?>
	<td><strong>Name of First GP Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo first_code_gp($insert_pk[0]['emp_first_gp_ps_zp_code'] ); ?></td>
    
<?php } 
else if($insert_pk[0]['emp_first_tier'] == 2 ){ ?>

<td><strong>Name of First PS Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo first_code_ps($insert_pk[0]['emp_first_gp_ps_zp_code'] ); ?></td>
    

<?php } 
else if($insert_pk[0]['emp_first_tier'] == 3 ){  ?>

<td><strong>Name of First ZP Posted: </strong></td>
    <td style="text-transform:uppercase;"><?php echo first_code_district($insert_pk[0]['emp_first_gp_ps_zp_code'] ); ?></td>
    
	
<?php }
else{ ?>

    <td><strong>Name of GP or PS or ZP Posted: </strong></td>
    <td style="text-transform:uppercase;">NA </td>
    
<?php } ?>	
<?php 
//$diff = $date2 – $date1;


?>
<td><strong>Condon Age</strong></td>
<td>
	<?php 
    if($insert_pk[0]['condon_year'] < 35 ){
         echo '<span style="color:#f3170b;font-size: 13px;font-weight: 700;">Condon Year month day is wrong. Please ask to refillup again.</span>';
         echo $insert_pk[0]['condon_year'] .'Years '.$insert_pk[0]['condon_month'].' Months '.$insert_pk[0]['condon_days'].' Days';
    }
    else
	  echo $insert_pk[0]['condon_year'] .'Years '.$insert_pk[0]['condon_month'].' Months '.$insert_pk[0]['condon_days'].' Days'; 

	?>
</td>

</tr>
<tr>
<td><strong>Caste: </strong></td>
<td><?php echo $arr_catagory[0]['description']; ?></td>
</tr>

<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
	<td colspan="4" style="text-align:center"><strong> LIST OF ATTACHMENT </strong></td>
</tr>
<tr class="success">
	<td><strong>FIRST APPOINMENT LETTER :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 20 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>" target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a>
	</td>	
	<td><strong>FIRST JOINING LETTER DULY ACCEPTED:</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 21 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
</tr>
<tr class="success">
	<td><strong>PROOF OF DATE OF BIRTH :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 22 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
	<td><strong>EMPLOYEE NOTIFICATION/</br> EMPLOYMENT EXCHANGE CALL LETTER :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 23 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
</tr>
<tr class="success">
	<td><strong>COURT CASE DETAILS :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 25 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
		
	<td><strong>CASTE CERTIFICATE :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 26 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
</tr>
<tr class="success">
	<td><strong>SERVICE BOOK RECORD COPY:</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 38 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>	
	<td><strong>OTHERS :</strong></td>
	<td><?php 
		$arr_file = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 24 and application_id='".$insert_pk[0]['application_id']."' "); ?>
		<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $insert_pk[0]['application_id']?>&flag=<?=$arr_file[0]['flag']?>"  target="_blank"><?php echo substr($arr_file[0]['file_name'],6); ?></a></td>
</tr>

</table>
</div>
</br>

<a href="<?= $config['base_url']?>page/intra_pri/pdf_overage_condonation.php?id=<?= $crypto->encode($insert_pk[0]['application_id'],4); ?>" class="btn btn-info" style="margin-left: 36%;" >DOWNLOAD PDF </a>
<!--<a href="<?= $config['base_url']?>page/intra_pri/pdf_overage_condonation.php" class="btn btn-info" >UPLOAD PDF </a>-->

</br>


<?php 

 if($fi_sub == 'fi_sub'){ 
	$db=new database();
	$update_oc_final = $db->update(" UPDATE intra_pri_overage_condonation_master SET active_status = '1'
								WHERE emp_id_const ='".$emp_id_const."' AND application_id = '".$id."' ");	
	if($update_oc_final){
		$insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const, sub_menu)
			VALUES ('".$insert_pk[0]['application_id']."', '".$_SESSION['user_info']['officer_id_const']."',1,'4', '".$emp_id_const."','4')");
	}
	//var_dump($update_oc_final);
	//var_dump($insert_request_forwarding); die;
	if($update_oc_final == true && $insert_request_forwarding == true){ $_SESSION['user_info']['emp_id_const'] = $emp_id_const;
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>OVER AGE CONDONATION PROPOSAL SAVED SUCCESSFULLY </strong></div>';
		
	}
	else{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>OVER AGE CONDONATION PROPOSAL SAVED FAILED. </strong></div>';
		
	}
 }
	?>