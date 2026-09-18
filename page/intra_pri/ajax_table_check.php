	
    	<?php
		
	session_start();
	
    
    header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	
	
	error_reporting(0);
	//ob_start();
	require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
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
	<style>
	.modal-body{
	font-size: 10px;
	}
	
	</style>
    
    <script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
	
	<?php
	function code_gp($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
	return $arr[0]['gp_name'];																	
	
	}
	
	function code_gp_block($val)
	{
	
	//echo 222; 
	$db=new database();
	//echo ("SELECT gp_id_pk,block_id_fk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'"); die;
	$arr =$db->fetch_table("SELECT gp_id_pk,block_id_fk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
	
	//echo ("SELECT block_id_pk,block_name FROM prd_location_master_block WHERE block_id_pk ='".$arr[0]['block_id_fk']."'");die;
	$arr_block =$db->fetch_table("SELECT block_id_pk,block_name FROM prd_location_master_block WHERE block_id_pk ='".$arr[0]['block_id_fk']."'");
	return $arr_block[0]['block_name'];																	
	
	}
	
	
	function code_ps($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
	return $arr[0]['ps_name'];																	
	
	}
	
	function code_district($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
	return $arr[0]['district_name'];																	
	
	}
	
	function code_block($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT block_id_pk,block_name FROM prd_location_master_block WHERE block_id_pk ='".$val."'");
	return $arr[0]['block_name'];																	
	
	}
	
	function fun_d($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_code ='".$val."'");
	return $arr[0]['district_id_pk'];																	
	
	}
	
	function fun_b($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT block_id_pk,block_name FROM prd_location_master_block WHERE block_code ='".$val."'");
	return $arr[0]['block_id_pk'];																	
	
	}
	
	function fun_master($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT description FROM prd_dise_code_master WHERE code ='".$val."'");
	return $arr[0]['description'];																	
	
	}
	
	
	
	//$time=$_GET['time_p'];
	
	$from_date=date("Y-m-d",strtotime($_GET['from_date']));
	
	 $to_date=date("Y-m-d",strtotime($_GET['to_date'])); 
	 
	 //$to_date=$_GET['to_date']; 
	
	if($time=='10')
	{
		$current_year=date("Y");
		$year=$current_year-10; 
		$date= date("d-m");
		 $val_year=$date.'-'.$year; 
	}
	
	else if($time=='3')
	{
		$current_year=date("Y");
		$year=$current_year-3; 
		$date= date("d-m");
		 $val_year=$date.'-'.$year;
	}
	else if($time=='5')
	{
		$current_year=date("Y");
		$year=$current_year-5; 
		$date= date("d-m");
		 $val_year=$date.'-'.$year;
	}
	 
	
	 if($_GET['post_emp']!='')
	 {
		 $user=$_SESSION['user_info']['district_id_fk'];
		 $stake='1'; 
		 $desig=$_GET['post_emp']; 
	 }else if($_GET['post_emp_zp']!='')
	 
	{
		$user=$_SESSION['user_info']['district_id_fk'];
		 $stake='2'; 
		$desig=$_GET['post_emp_zp']; 
	}
	else
	{
		$user=$_SESSION['user_info']['district_id_fk'];
		 $stake='3'; 
		$desig=$_GET['post_emp_ps']; 
	}
	
	//'2011-12-25'
	

/*	
	$data = $db->fetch_table(" SELECT * FROM prd_employee_master WHERE emp_status in('1') and
	emp_desig = '".$desig."' and (gp_id_fk in('3355','3380','3383','3400','3401','3402','3403','3411','3413','3412') or ps_id_fk in('342','343','344')) and emp_join_prsnt_office_date <= '".$val_year."' order by emp_first_name");*/
	if( $stake=='2')
	{
		
		$db=new database();
	$data = $db->fetch_table(" SELECT * FROM prd_employee_master WHERE emp_status in('1') and
	emp_desig = '".$desig."' and zp_id_fk='".$user."' and emp_join_prsnt_office_date BETWEEN '".$from_date."' and  '".$to_date."'  order by emp_first_name");
	}
	else if($stake=='3')
	
	
	{
		
		
		$db=new database();
		$data = $db->fetch_table(" SELECT * FROM prd_employee_master e 

inner join prd_location_master_panchayat_samiti p on  e.ps_id_fk=p.ps_id_pk

			inner join prd_location_master_district d
			on p.district_id_fk=d.district_id_pk WHERE e.emp_status in('1') and
	emp_desig = '".$desig."' and d.district_id_pk='".$user."' and e.emp_join_prsnt_office_date BETWEEN '".$from_date."' and  '".$to_date."'  order by emp_first_name");
	}
	else
	{
		$db=new database();
		
	
		$data = $db->fetch_table("SELECT * FROM prd_employee_master e 
inner join prd_location_master_gp g on  e.gp_id_fk=g.gp_id_pk


inner join prd_location_master_block b on  g.block_id_fk=b.block_id_pk

 inner join prd_location_master_district d on b.district_id_fk=d.district_id_pk  WHERE e.emp_status in('1') and
	emp_desig = '".$desig."' and d.district_id_pk='".$user."' and e.emp_join_prsnt_office_date BETWEEN '".$from_date."' and  '".$to_date."'  order by emp_first_name");
	}
	 
	
	
	//select * from table1 where b > YEAR(CURRENT_TIMESTAMP) - 5
	?>
	<style>
	.form-horizontal .control-label {
	text-align:left;
	}
	</style>
	
	<div class="row" id="cont">
	<div class="content">
	<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
	<div class="col-sm-12" style="width:98%;">
	<h1 class="heading">VIEW EMPLOYEE DETAILS</h1>
	<div class="border"></div>
	</br>
	</br>
	<div class="emplist">
	<div class="school">
	<div class="table-responsive">
	<table id="myTable" width="110%">
	<tr>
	<th>Serial No.</th>
	<th>Employee ID</th>
	<th>Employee Name</th>
	<th>DOB</th>
	<th>Gender</th>
	
	<!--<th>Designation</th>-->
	
	<?php if($stake == 1):?>
	<th>Block Name where posted <?php echo $stake; ?></th>
	
	<th>GP Name where posted</th>
	<?php endif; ?>
	
	<?php if($stake == 3):?>
	<th>PS Name where posted</th>
	<?php endif; ?>
	<th>Address</th>
	<!--<th>PS Name where posted</th>-->
	<th>Date of joining in Present Office</th>
	

	
	
	
	</tr>
	<? $cnt=1; if(count($data)){ foreach($data as $item){
	
	//$tn_block_id_fk=$item['transfer_block_id_fk']; 
	
	?>
	<tr>
	<td><?= $cnt;?></td>
	<td id="emp_id"><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
	<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
	<td><?php echo $item['emp_dob'] ?></td>
	<td><?php echo ($item['emp_sex'] ==91)?'M':'F'; ?></td>
	
	
	<!--<td><?php //fun_master($item['emp_desig']);?>-->
	
	<?php if($stake == 1):?>
	<td><?= code_gp_block($item['gp_id_fk']);?></td>
	<td><?= code_gp($item['gp_id_fk']);?></td>
    <?php endif; ?>
	<?php if($stake == 3):?>
	<td><?= code_ps($item['ps_id_fk']);?></td>
    <?php endif; ?>
    <td><?php echo $item['emp_per_vill'].' '.$item['emp_per_post'].' '.$item['emp_per_pin']?></td>
	<td><?= date("d-m-Y",strtotime($item['emp_join_prsnt_office_date']));?></td>
	
	
	 
	
	
	</tr>
	
    
	
	
	<? $cnt+=1; }} else { ?>
	<tr>
	<td colspan="8" style="color:red;font-weight:bold">No Data Found</td>
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
	
  