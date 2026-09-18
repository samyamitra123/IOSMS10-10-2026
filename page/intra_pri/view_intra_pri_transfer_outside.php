	<?php
	
	session_start();
	
	
	
	//print_r($_SESSION);
	
	//echo $_SESSION['user_info']['district_id_fk'];
	 
	error_reporting(0);
	$time_token=time();
	$_SESSION['security_token']=$time_token;
	$enc_token=md5('369'.$time_token);
	
	header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	
	
	error_reporting(0);
	//ob_start();
	require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
	
	$crypto=new cryptography();
	
	   $stake_level_district=$crypto->decode($_GET['dis'],4); 
	//var_dump($_SESSION);
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
	
	<meta charset="UTF-8">
	
	
	
	<!--<body>-->
	<?php
	
	ob_start();
	
	//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------
	
	//Page variables
	$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
	
	
	
	//------------------------------------------------------- HEADER --------------------------------------------------------------
	require '../../page/layout/header.php';
	//---------------------------------- MENU -------------------------------------------------------------------------------------
	require '../../page/layout/menu.php';
	
	
	?>
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
	
	function fun_master_zp($val)
	{
	
	//echo 222; 
	$db=new database();
	
	$arr =$db->fetch_table("SELECT designation_name FROM zpemp_emp_desig_master WHERE designation_id='".$val."'");
	return $arr[0]['designation_name'];																	
	
	}
	
	
	?>
	<style>
	.form-horizontal .control-label {
	text-align:left;
	}
	</style>
	<script>
	
	
	
	</script>
	
	<div class="content">
	<?php require 'common_back_btns_intra_pri.php'; ?>
    
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
	<div class="welcome_msg">
	<?php
	//echo 33;die;
	$db = new database();
	$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
	
	?>
	<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
	<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
	</div>
	<!-- Latest compiled and minified JavaScript -->
	<div class="row" id="cont">
	<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
	<div class="col-sm-12" style="width:98%; padding-left:2%">
	<h1 class="heading">Transfer & Posting</h1>
	<div class="border"></div>
	</br>
	<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
	<div id="form_show">
	
	<?php 
	
	$user=$_SESSION['user_info']['stake_user_code'];
	
	
	 $check=substr($user,0,6);
	
	 $c=substr($check,-2);
	
	if($c=='DP')
	{
		 $a=substr($user,0,4); 
		
		  $id_fk=fun_d($a); 
		 
		 $name=code_district($id_fk);
	}

	else
	{
		$a=substr($user,0,7); 
		
		 $id_fk=fun_b($a);
		  $name=code_block($id_fk);
	} 
	/* if($stake_level_district=='ID')
	{
		$f='40';
	$proposal="PROPOSAL FOR GENERAL TRANSFER AND POSTING I.R.O ".$name."";
	}
	 if($stake_level_district=='D')
	{
		$f='41';
	$proposal="PROPOSAL FOR GENERAL TRANSFER AND POSTING I.R.O ".$name."";
	}*/
	
	$stake_level_district='D';
	
	$db=new database();
	
	
	/*echo (" SELECT * FROM intra_pri_district_transfer WHERE from_status in('1','2') and
	stack_user = '".$user."' and district_level='".$stake_level_district."' and  status='0' order by emp_first_name");die;*/
	$data = $db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE from_status in('2') and
	transfer_district_id_fk = '".$id_fk."' and district_level='".$stake_level_district."' and status in('2','3','4','5','6') order by emp_first_name");
	//var_dump($emp_id_detail_ocon);
	
	 $gp_id_fk=$data[0]['pre_gp_id_fk'];
    $ps_id_fk=$data[0]['pre_ps_id_fk'];
    $block_id_fk=$data[0]['pre_block_id_fk'];
	$emp_join_prsnt_post_date=$data[0]['emp_join_prsnt_post_date'];
	
	
	
	$district_level=$data[0]['district_level'];
	
	
	
	//$district_id_fk=$data[0]['district_id_fk'];
	/*$ps_code=$data[0]['tran_ps_code'];
	 $block_id_fk=$data[0]['tran_block_id_fk']; 
	$gp_code=$data[0]['tran_gp_code'];*/
	
	 
	
	
	
	

	
			?>
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
    <table id="myTable_d" width="110%">
    <tr>
    <th>Serial No.</th>
     <th>Application ID</th>
    <th>Employee ID</th>
   
    <th>Employee Name</th>
    
    <th>Designation</th>
    
   
    <th>Block Name where posted</th>
   
     <th>GP Name where posted</th>
   
      
        
    <th>PS Name where posted</th>
   
     
      <!--<th>PS Name where posted</th>-->
      <th>Date of joining in Present Office</th>
      
    
      <th style="width:7%;">Transfer TO District</th>
       
      <th style="width:7%;">Reason</th>
      <th>Document Upload</th>
      <th>Status</th>
      
      <th style=" width:30%">NOC For Accommodate the Employee</th>

       
    
    </tr>
    <? $cnt=1; if(count($data)){ foreach($data as $item){
		
		if($item['status']=='1')
			{
			$status='<span style="color:#660066;font-weight:bold">PANDING FOR APPROVAL/REJECT</span>';
			}
			else if($item['status']=='2')
			{
			$status='<span style="color:green;font-weight:bold">Sent to DRPRO for NOC to Accommodate the Employee By </span>';
			}
			if($item['status']=='0')
			{
			$status='<span style="color:#660066;font-weight:bold">PANDING IN YOUR END</span>';
			}
			else if($item['status']=='3') 
			{
			$status='<span style="color:green;font-weight:bold"> NOC For Accommodate the Employee Accepted By</span>';
			}
			else if($item['status']=='4') 
			{
			$status='<span style="color:RED;font-weight:bold">NOC For Accommodate the Employee Rejected By</span>';
			}
			else if($item['status']=='5') 
			{
			$status='<span style="color:green;font-weight:bold">Approved By</span>';
			}
			else if($item['status']=='6') 
			{
			$status='<span style="color:RED;font-weight:bold">Rejected By</span>';
			}
    
    ?>
    <tr>
    <td><?= $cnt;?></td>
    <td><?= $item['application_id'];?></td>
    <td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
    <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
    <?php if($item['pre_gp_id_fk']!='0' ||$item['pre_ps_id_fk']!='0' ){?>
    <td><?= fun_master($item['desig']);?></td>
    <?php }else{?>
    
     <td><?= fun_master_zp($item['desig']);?></td>
    
     <?php }?>
     <td><?= code_block($item['pre_block_id_fk']);?></td>
     <td><?= code_gp($item['pre_gp_id_fk']);?></td>
     <td><?= code_ps($item['pre_ps_id_fk']);?></td>
      <td><?= date("d-m-Y",strtotime($emp_join_prsnt_post_date));?></td>
   <td id="dis_tn">
    
    <?php if($item['transfer_district_id_fk']=='')
	{  ?> 
    <?php 
    $db= new database();
            $district=$db->fetch_table("select district_id_pk,district_name,district_code from prd_location_master_district 
            Where district_id_pk!='".$id_fk."' order by district_name");?>
            
             <select class="form-control upper_case tran_district" name="tran_district" id="tran_district">
                <option value="">-Please Select-</option>
                <?php
                
                foreach($district as $key)
                {
                
                ?>
                <option value="<?php echo $crypto->encode($key['district_id_pk'],4)?>"><?php echo $key['district_name']; ?></option>
                <?php
                }
                ?>
                </select>
                
                 <?php }else{?>
                 
                 <?= code_district($item['transfer_district_id_fk']);?>
	<? }?>
    
    </td>
     
            
           <td id="reson_d">   
		<?php if($item['reason']=='')
        {  ?> 
		   <?php 
    $transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
    FROM prd_dise_code_master WHERE code IN ('600','601','602','603') AND length(code)=3
    ");
    ?>
    <select name="reson_d" style="width:100%;" id="reson_d" class="form-control">
    <option value="">---PLEASE SELECT---</option>
    <? foreach($transfer_level as $key)
    {
    
    ?>
    <option value="<?=$key['code']?>" <? if($reson==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
    
    <? } ?>
    </select>
    <?php }else{?>
     <?= fun_master($item['reason']);?>
	<? }?>
    </td>
    <td>
    <?php 
    $db=new database();
		
		$arr_file_41 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$item['emp_id_const']."' AND status = '1' AND flag= 41 "); ?>
        
          <?php  if($arr_file_41[0]['file_name']==''){?>
     
     <?php  }else{?>
	
    <?php if(count($arr_file_41)=='1'){?>
   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($item['emp_id_const'],4)?>&flag=<?=$arr_file_41[0]['flag']?>"><?php echo substr($arr_file_41[0]['file_name'],6); ?></a>
    <?php }}?>
    </td>
    
 
     <?php
	 
	 
	 
    $officer_name_pending = $db->fetch_table(" SELECT officer_name FROM intra_pri_master 
					WHERE  officer_id_const ='".$item['officer_id_const_forward']."' ");
	 
					?>
    
    <td><?= $status.'  '.$officer_name_pending[0]['officer_name'] ?></td>
    <td>
    <?php if($item['status']=='3' || $item['status']=='4' || $item['status']=='5' || $item['status']=='6')
	{  ?>
     <div class="btn-group" role="group" style="width:60%;">
     
    <button type="button" class="btn btn-success emp_yes" disabled="disabled" id="trans_emp<?=$item['emp_id_const']?>"  value="<?=$crypto->encode($item['emp_id_const'],4)?>" style="display: block;">YES</button>
    <button type="button" class="btn btn-danger emp_no" disabled="disabled"style="display: block;">NO</button>
   
 
    </div>
     
    
    <?php }else{?>
    <div class="btn-group" role="group" style="width:60%;">
     <button type="button" class="btn btn-success emp_yes"  id="trans_emp<?=$item['emp_id_const']?>"  value="<?=$crypto->encode($item['emp_id_const'],4)?>" onclick="emp_yes('<?php echo $crypto->encode($item['emp_id_const'],4);?>','<?php echo $crypto->encode("yes",4);?>')"style="display: block;">YES</button>
    <button type="button" class="btn btn-danger emp_no"  onclick="emp_no('<?php echo $crypto->encode($item['emp_id_const'],4);?>','<?php echo $crypto->encode("no",4);?>')" style="display: block;">NO</button>
	<?php }?>
    </div>
                        </td>
    
    
            
   
    </tr>
    
    
    
    <? $cnt+=1; }} else { ?>
    <tr>
    <td colspan="13" style="color:red;font-weight:bold">No Data Found</td>
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
    

    
    
    
    
     <script>
    
    function emp_yes(val,k)
		{
			//alert(1222);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?yes_emp_id='+val+'&stack_yes='+k, function(data){
			
			//alert(data);
			//return false;
		if(data==1)
			{
				alert('Success....');
				location. reload();
			}
			else
			{
				alert('Failed....');
			}
		
	
		
		});
		}
    
	
	 function emp_no(val,k)
		{
			//alert(444);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?no_emp_id='+val+'&stack_no='+k, function(data){
			
			//alert(data);
			//return false;
		if(data==1)
			{
				alert('Success....');
				location. reload();
			}
			else
			{
				alert('Failed....');
			}
		
	
		
		});
		}
    
    
    </script>
    
    
    
    
<?php
	//----------------------------------- FOOTER ----------------------------------------------------------------------------------
	require '../../page/layout/footer.php';
	//----------------------------------------------------------------------------------------------------------------------------
	?>

  