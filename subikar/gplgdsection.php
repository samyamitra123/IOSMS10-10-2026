<?php
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db=new database();	
	$post = $_POST;
	 
    $Query = "SELECT district_id_pk, district_name from prd_location_master_district WHERE lgd != 0";
	$districtData = $db->fetch_table($Query); 
	//print_r($districtData); exit;	
	if(isset($post['district']) && $post['district'] > 0)
	{
	 	  $Query = "SELECT block_id_pk, block_name from prd_location_master_block WHERE lgd > 0 AND district_id_fk=".$post['district'];
	 	  $blockData = $db->fetch_table($Query);
	 	  //print_r($blockData); exit; 		
	}

	if(isset($post['savelgd']) && isset($post['token']) && $post['token'] == '1@subikar321')
	{
		$Query = array();
        foreach($post['gpInsData']['gp_code'] as $key=>$gpInsData)
        {
		  $Where = array();
          
          if($post['gpInsData']['lgd'][$key] != '') 
		    $Where[] = ' lgd ='.$post['gpInsData']['lgd'][$key];
		 // $Where[] = ' lgd_pri ='.$data['DIST_LGD_PRI'];
		  if($post['gpInsData']['gp_name_beng'][$key] != '')
		    $Where[] = " gp_name_beng ='".$post['gpInsData']['gp_name_beng'][$key]."'";
		  if($post['gpInsData']['section_code'][$key] != '')
		    $Where[] = " section_code ='".$post['gpInsData']['section_code'][$key]."'";
		if(count($Where) > 0)
           $Query[] ="UPDATE prd_location_master_gp SET ".implode(', ',$Where)." WHERE gp_code='".$gpInsData."'";
				 	    		
        }
        $updateQ = implode('; ',$Query).';';
        //print_r($updateQ); exit;
        $db->update($updateQ);	

	}	
	if(isset($post['block']) && $post['block'] > 0)
	{
	 	  $Query = "SELECT gp_id_pk, gp_code, gp_name,section_code,lgd,gp_name_beng from prd_location_master_gp WHERE block_id_fk=".$post['block'];
	 	  $gpData = $db->fetch_table($Query);
	 	  //print_r($blockData); exit; 		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keyword" content="West Bengal Panchayat and Rural Development Department, P&RD department" />
<meta name="description" content="West Bengal Panchayat and Rural Development Department, P&RD department" />
<title>P&RD | Govt. of West Bengal </title>
<link rel="stylesheet" type="text/css" href="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/css/bootstrap.min.css">
<script src="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
<form name="updateGPData" action="" method="POST" enctype="form-control">	
<div class="row">
<div class="col-md-6">	
<select name="district" class="form-control" onchange="this.form.submit();">
	<option value="">Select District</option>
	<?php foreach ($districtData as $district) { ?>
	<option value="<?php echo $district['district_id_pk']?>" <?php echo (isset($post['district']) && $post['district']==$district['district_id_pk'])?'selected':''; ?>><?php echo $district['district_name']?></option>
	<?php }?>
</select>
</div>
<div class="col-md-6">	

<select name="block" class="form-control" onchange="this.form.submit();">
	<option value="">Select Block</option>
	<?php 
    if(count($blockData) > 0)
      {	
	foreach ($blockData as $block) { ?>
	<option value="<?php echo $block['block_id_pk']?>" <?php echo (isset($post['block']) && $post['block']==$block['block_id_pk'])?'selected':''; ?>><?php echo $block['block_name']?></option>
	<?php 
       }
       } 
	?>
</select>
</div>
<?php if(count($gpData)>0):?>
<div class="maintable">
	<table width="100%" cellpadding="10" cellspacing="10">
		<thead>
			<th>Gp Code</th>
			<th>Gp Name</th>
			<th>Section Code</th>
			<th>LGD</th>
			<th>Gp Bengali</th>
		</thead>
		<tbody>
			<?php foreach($gpData as $gp):?>
			<tr>	
			<td><input type="text" name="gpInsData[gp_code][]" value="<?php echo $gp['gp_code']?>" readonly></td>
			<td><?php echo $gp['gp_name']?></td>
			<td><input type="text" name="gpInsData[section_code][]" value="<?php echo $gp['section_code']?>"></td>
			<td><input type="text" name="gpInsData[lgd][]" value="<?php echo $gp['lgd']?>"></td>
			<td><input type="text" name="gpInsData[gp_name_beng][]" value="<?php echo $gp['gp_name_beng']?>"></td>

            </tr>   
			<?php endforeach; ?>	
		</tbody>
		<tfoot>
			<tr><th colspan="5" style="text-align: center;">Enter Master Pass<input type="password" name="token" value="1@subikar321"></th></tr>
			<tr><th colspan="5" style="text-align: center;"><input type="submit" name="savelgd" value="Update"></th></tr>
		</tfoot>
	</table>
</div>
<?php endif; ?>	
</div>
</form>
</div>
</body>
</html>