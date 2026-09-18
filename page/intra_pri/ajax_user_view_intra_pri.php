<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


header("Strict-Transport-Security: max-age=63072000");
session_start();

require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';



$db=new database();
/*
$officer_detail = $db->fetch_table(" SELECT master.*, desig.designation as designation, code.description as description, assign.description as ass FROM intra_pri_master as master
											INNER JOIN intra_pri_designation_master as desig ON CAST(master.designation AS character varying) = desig.designation_code 
											INNER JOIN prd_dise_code_master as code ON CAST(master.sex AS character varying) = code.code
											INNER JOIN intra_pri_role_assigment as assign ON master.role_assign= assign.code
											WHERE master.mobile_no = '".$_REQUEST['id']."' "); */
											
											
$officer_detail = $db->fetch_table(" SELECT master.*, desig.designation as designation, code.description as description FROM intra_pri_master as master
								INNER JOIN intra_pri_designation_master as desig ON CAST(master.designation AS character varying) = desig.designation_code 
								INNER JOIN prd_dise_code_master as code ON CAST(master.sex AS character varying) = code.code
								WHERE master.mobile_no = '".$_REQUEST['id']."' ");
								
					//var_dump(explode(",",$officer_detail['0']['role_assign']));			

?>
<!--<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_prd/block/pdf_block_details.php"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>-->
<div class="table-responsive">
	<table width="100%" class="table" style="font-size:14px;font-family: 'calibri';">
		<tr style="background-color:#3E9B96;color:#FFF;font-size:18px;">
		<td colspan="4" style="text-align:center"><strong>VIEW PROFILE</strong></td>
		</tr>
		<tr class="success">
			<td><strong>OFFICER NAME :</strong></td>
			<td><?php echo $officer_detail['0']['officer_name']; ?></td>
			<td><strong>SEX :</strong></td>
			<td><?php echo $officer_detail['0']['description']; ?></td>
		</tr>
		<tr class="warning">
			<td><strong>DESIGNATION :</strong></td>
			<td><?php echo $officer_detail['0']['designation']; ?></td>
			<td><strong>MOBILE NO. :</strong></td>
			<td style="text-transform:uppercase;"><?php echo $officer_detail['0']['mobile_no']; ?></td>
		</tr>
		<tr class="success">
			<td><strong>EMAIL ID :</strong></td>
			<td><?php echo $officer_detail['0']['email_id']; ?></td>
			<td><strong>DATE OF JOINING IN PRESENT POST:</strong></td>
			<td style="text-transform:uppercase;"><?php echo date("d-M-Y",strtotime($officer_detail['0']['date_of_join_prsnt_post'])); ?></td>
		</tr>
		<tr class="warning">
			<td><strong>ROLE ASSIGN :</strong></td>
			<td style="text-transform:uppercase;"><?php 
				
				$role= explode(",",$officer_detail['0']['role_assign']);
				foreach($role as $key){
					$db=new database();
					$role_fetch = $db->fetch_table(" SELECT * FROM intra_pri_role_assigment WHERE code= '".$key."' ");
					echo "<b>".$role_fetch[0]['master_cell_description']."</b> - ". $role_fetch[0]['description']."<br>" ; 
				}
			?></td>
		</tr>
	</table>			
</div>