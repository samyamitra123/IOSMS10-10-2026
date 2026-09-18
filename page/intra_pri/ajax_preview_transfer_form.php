	<?php
	
	session_start();
	
	//unset($_SESSION['application_id']);
	
	//die;
	//echo 1222; die;
	/*echo "<pre>";
	print_r($_SESSION);
	echo "<pre>"; */
	//print_r($_SESSION);
	error_reporting(0);
	$time_token=time();
	//$_SESSION['security_token']=$time_token;
	//$enc_token=md5('369'.$time_token);
	
	header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	
	
	//error_reporting(0);
	//ob_start();
	require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
	
	$crypto=new cryptography();
	
	   $stake_level_district=$crypto->decode($_POST['dis'],4);
	   
	   if($stake_level_district!='')
	   {
		  $stake_level_district=$crypto->decode($_POST['dis'],4); 
	   }
	   else
	   {
		  $app_no = $_POST['app_no']; 
	   }
	   
	  $user=$_SESSION['user_info']['stake_user_code']; 
	   
	    $check=substr($user,-2);
	   
	   if(substr($user,-2)=="DM")
	   {
		   //echo 222; die;
		   $user_stack="DM"; 
	   }
	   else if(substr($user,-3)=="ADM")
	   {
		  // echo 223; die;
		    $user_stack="ADM"; 
	   }
	   else if(substr($user,-3)=="AEO")
	   {
		    
			
			//echo 224; die;
			$user_stack="AEO"; 
	   }
	   else
	   {
		  // echo 333; die;
		   $user_stack="D"; 
	   }
	
	
	
	

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
	
	//ob_start();
	
	//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------
	
	//Page variables
	
	
	
	
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
	$Query = "SELECT block_id_pk,block_name FROM prd_location_master_block WHERE block_id_pk ='".$val."'";
	$arr =$db->fetch_table($Query);
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
	
	
	?>
	<style>
	.form-horizontal .control-label {
	text-align:left;
	}
	</style>
	<script>
	
	$(function() {
	$( "#notice_date" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	//minDate:dateToday	 
	});
	});
	
	</script>
	
	<div class="content">
    
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
	}

	else
	{
		$a=substr($user,0,7); 
		
		 $id_fk=fun_b($a);
	} 
	 if($stake_level_district=='ID')
	{
	$proposal="PROPOSAL FOR GENERAL TRANSFER AND POSTING";
	}
	 if($stake_level_district=='D')
	{
	$proposal="PROPOSAL FOR GENERAL TRANSFER AND POSTING";
	}
	
	if($stake_level_district!='')
	   {
		    $prv='1';
	$db=new database();
	$data = $db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE from_status in('2') and
	stack_user = '".$user."' and district_level='".$stake_level_district."' and status in('0') order by emp_id_const");
	   }
	   else
	   {
		     $prv='2';
		   $db=new database();
	$data = $db->fetch_table(" SELECT * FROM intra_pri_district_transfer WHERE from_status in('2') and
	 application_id='".$app_no."' and status in('0','1','2','3','4','5','6') order by emp_id_const");
	   }
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
    
    
    
    <?
    
    if($_SESSION['msg']){
    echo $_SESSION['msg'];
    
    unset($_SESSION['msg']);
    
    }
    ?>  
    
   
    

	<?php 
	
	if($district_level=='ID')
	{
	
	
	
	
	
	if(count($data)!='0'){ ?>
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
      
	
	<th>Designation</th>
	
	
	<th>Block Name where posted</th>
	
	<th>GP Name where posted</th>
	
	
	
	<th>PS Name where posted</th>
	
	
	<!--<th>PS Name where posted</th>-->
	<th>Date of joining in Present Office</th>
	
	
	<th>Transfer TO Block</th>
	<th>Transfer TO GP</th>
	
	
	<th>Transfer TO PS</th>
	<th>Reason</th>
	<th>Document Upload</th>
  <th>Status</th>
  <?php if($user_stack=="DM"){ ?>
	<th style=" width:15%">Action</th>
	<?php }?>
	</tr>
	<?php 
	$cnt=1; 
	if(count($data)){ foreach($data as $item){
			
			if($item['status']=='1')
					{
					$status='<span style="color:#660066;font-weight:bold">PENDING FOR APPROVAL/REJECT</span>';
					}
					if($item['status']=='0')
					{
					$status='<span style="color:#660066;font-weight:bold">PENDING IN YOUR END</span>';
					}
					else if($item['status']=='2')
					{
					$status='<span style="color:green;font-weight:bold">ARROVED BY </span>';
					}
					
					else if($item['status']=='3') 
					{
					$status='<span style="color:RED;font-weight:bold">REJECTED BY </span>';
					}
	
	?>
	<tr>
	<td><?= $cnt;?></td>
	<td id="emp_id"><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
	<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
	<td><?= fun_master($item['desig']);?>
	<td><?= code_block($item['pre_block_id_fk']);?></td>
	<td><?= code_gp($item['pre_gp_id_fk']);?></td>
	<td><?= code_ps($item['pre_ps_id_fk']);?></td>
	<td><?= date("d-m-Y",strtotime($emp_join_prsnt_post_date));?></td>
	<?php if($item['pre_gp_id_fk']!='0'){?>
	<td id="block">
		<?php //echo $item['transfer_block_id_fk']; ?>
		<?php if($item['transfer_block_id_fk']=='')
	         {    
							$db= new database();
							$block=$db->fetch_table("select block_id_pk,block_name,block_code,block_name from prd_location_master_block 
							Where district_id_fk='".$id_fk."' order by block_name");?>
							
							<select class="form-control upper_case transBlock" name="block" id="block"  onchange="show_gp_tr(this.value,<?php echo $cnt; ?>)">
							<option value="">-Please Select-</option>
	   <?php
	
							foreach($block as $key)
							{
							
							?>
							<option value="<?php echo  $crypto->encode($key['block_id_pk'],4);?>"<? if($item['transfer_block_id_fk']==$key['block_id_pk']){ echo  "selected";}?>><?php echo $key['block_name']; ?></option>
							<?php
							}
							?>
							</select>
			<?php }else{ ?>
	    <?php echo code_block($item['transfer_block_id_fk']);?>
	    <?php }?>
	</td>
	
	<td id="gp">
	
	<?php if($item['transfer_gp_id_fk']=='') {  ?>  
		<select class="form-control upper_case gp" name="gp_t<?=$cnt?>" id="gp_t<?=$cnt?>">
		</select>
	<?php }else{?>
			<?php echo code_gp($item['transfer_gp_id_fk']);?>
			<? }?>
			</td>
			<td></td>
			<? }?>
			<?php if($item['pre_ps_id_fk']!='0'){?>
					<td></td>
					<td></td>
					<td id="ps">
							<?php if($item['transfer_ps_id_fk']==''){  ?> 
							<?php $db= new database();	
							$block=$db->fetch_table("select ps_id_pk,ps_name,ps_code,ps_name from prd_location_master_panchayat_samiti 
							Where district_id_fk='".$id_fk."' order by ps_name");?>
							<select class="form-control upper_case transBlock" name="ps" id="ps">
							<option value="">-Please Select-</option>
							<?php foreach($block as $key){ ?>
							<option value="<?php echo $crypto->encode($key['ps_id_pk'],4)?>"<? if($item['transfer_ps_id_fk']==$key['ps_id_pk']){ echo  "selected";}?>><?php echo $key['ps_name']; ?></option>
							<?php }	?>
							</select>
						  <?php }else{?>
						    <?php echo code_ps($item['transfer_ps_id_fk']);?>
							<? }?>
					</td>
			<?php }?>
			<td id="reson">
					<?php if($item['reason']=='')
					{  ?> 
					<?php 
					$transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
					FROM prd_dise_code_master WHERE code IN ('600','601','602','603') AND length(code)=3
					");
					?>
					<select name="reson" style="width:100%;" id="reson" class="form-control">
					<option value="">---PLEASE SELECT---</option>
					<? foreach($transfer_level as $key)
					{
					
					?>
					<option value="<?=$key['code']?>" <? if($item['reason']==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
					
					<? } ?>
					</select>
					 <?php }else{?>
				    <?= fun_master($item['reason']);?>
					<? }?>
			</td>
			<td>
					<?php 
					$db=new database();
					$Query = "select file_name,flag from intra_pri_file_upload where emp_id_const = '".$item['emp_id_const']."' AND status in('1','2','3') AND flag= 40 ";
					$arr_file_40 = $db->fetch_table($Query); ?>
					
					<?php  if($arr_file_40[0]['file_name']==''){?>
					
					<?php  }else{?>
					
				    <?php if(count($arr_file_40) > 0){?>
				   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($item['emp_id_const'],4)?>&flag=<?=$arr_file_40[0]['flag']?>" target="_blank"><?php echo substr($arr_file_40[0]['file_name'],6); ?></a>
				    <?php }?>
				  
					<?php  }?>
			</td>
		    <?php $officer_name_pending = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE  officer_id_const ='".$item['officer_id_const_approval']."' "); ?>
		  <td><?= $status.$officer_name_pending[0]['officer_name'] ?></td>
				<?php if($user_stack=="DM"){?>
		        <?php if($item['status']=='1')
		        {  ?> 
		  <td>
		         <div class="btn-group" role="group" style="width:60%;">
		        <button type="button" class="btn btn-success " id="emp_approve<?=$item['emp_id_const']?>" style="display: block;" onclick="emp_approve_modal('<?=$crypto->encode($item['emp_id_const'],4)?>');">Approve</button>
		        <button type="button" class="btn btn-danger" id="emp_reject<?=$item['emp_id_const']?>"style="display: block;" onclick="emp_reject_modal('<?=$crypto->encode($item['emp_id_const'],4)?>');">Reject</button>
		        </div>
		  </td>
       
	  <?php }else{?>
			  <td>
			     <div class="btn-group" role="group" style="width:60%;">
			    <button type="button" class="btn btn-success "  disabled="disabled" style="display: block;" >Approve</button>
			    <button type="button" class="btn btn-danger"  disabled="disabled" style="display: block;" >Reject</button>
			    </div>
			  </td>
	        
	  <?php }?>
  <?php }?>
	</tr>
	
    
	
	
	<?php $cnt+=1; 
    } 
   } else { ?>
	<tr>
	<td colspan="3" style="color:red;font-weight:bold">No Data Found</td>
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
	
    <?php }?>
	
	<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
        <?php if($data[0]['from_status']=='2' && $prv=='1'){?>
      
		<a type="button" id="submit6" name="submit6"  class="btn btn-success"  onclick="final_save('<?php echo $crypto->encode($district_level,4); ?>','<?= $crypto->encode("final",4); ?>');">PROPOSAL SAVED</a>
        <? }else{?>
        <?php }?>
      
        
		</div>
	</div>
	
	<?php }
	else {
		
			if(count($data)!='0'){ ?>
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
    <th>Employee ID</th>
    <th>Employee Name</th>
    
    <th>Designation</th>
    
   
    <th>Block Name where posted</th>
   
     <th>GP Name where posted</th>
   
      
        
    <th>PS Name where posted</th>
   
     
      <!--<th>PS Name where posted</th>-->
      <th>Date of joining in Present Office</th>
      
    
      <th>Transfer TO District</th>
       
     <th style="width:7%;">Reason</th>
      <th>Wheather any disciplinary proceeding has been initiated</th>
      <th>Wheather the incumbent has availed benifit of such inter district Trasfer before</th>
      
     <th> Application from for outside District Transfer</th>
      <th>Document Upload</th>
      <th> Endorsement of hed of office/Pradhan/Resolution of authorization (PS)/Recommendation of BDO</th>
      <th>Status</th>
       <?php if($user=="COMM"){?>
      <!-- <th style=" width:15%">Action</th>-->
      <th style=" width:25%">Sent to DRPRO for NOC to Accommodate the Employee</th>
      
     <!-- <th colspan="3">Action</th>-->
      
<?php }?>
       
    
    </tr>
    <? $cnt=1; if(count($data)){ foreach($data as $item){
		
		if($item['status']=='1')
			{
			$status='<span style="color:#660066;font-weight:bold">PENDING FOR APPROVAL/REJECT</span>';
			}
			else if($item['status']=='2')
			{
			$status='<span style="color:green;font-weight:bold">Sent to DRPRO for NOC to Accommodate the Employee By  </span>';
			}
			if($item['status']=='0')
			{
			$status='<span style="color:#660066;font-weight:bold">PENDING IN YOUR END</span>';
			}
			else if($item['status']=='3') 
			{
			$status='<span style="color:green;font-weight:bold"> NOC For Accommodate the Employee Accepted</span>';
			}
			else if($item['status']=='4') 
			{
			$status='<span style="color:RED;font-weight:bold">NOC For Accommodate the Employee Rejected</span>';
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
    <td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
    <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
    <td><?= fun_master($item['desig']);?>
    
     
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
            Where zp_status=1 order by district_name DESC");?>
            
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
    
    <td id="benifit_d">   
		<?php if($item['benifit']=='')
        {  ?>
    <select name="benifit_d" style="width:100%;" id="benifit_d" class="form-control">
    <option value="">---PLEASE SELECT---</option>
    
   <option value="1" <? if($benifit=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($benifit=='0') { echo "selected"; } ?>>NO</option>
    </select>
    <?php }else{?>
   
   <input type="text"  value=" <? if($benifit=='1'){
	    echo "YES";} else{ echo "NO" ; }?> " readonly="readonly"class="form-control" name="benifit_d" />
   <?php }?>
    </td>
    
    
     <td id="proce_d">   
		<?php if($item['proceding']=='')
        {  ?>
    <select name="proce_d" style="width:100%;" id="proce_d" class="form-control">
    <option value="">---PLEASE SELECT---</option>
    
   <option value="1" <? if($proceding=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($proceding=='0') { echo "selected"; } ?>>NO</option>
    </select>
    <?php }else{?>
   
   <input type="text"  value=" <? if($proceding=='1'){
	    echo "YES";} else{ echo "NO" ; }?> " readonly="readonly"class="form-control" name="proce_d" />
   <?php }?>
    </td>
    
     <td>
    <?php 
    $db=new database();
		$Query = "select file_name,flag from intra_pri_file_upload where emp_id_const = '".$item['emp_id_const']."' AND status = '1' AND flag= 42 order by upload_file_id_pk desc LIMIT 1 OFFSET 0";
		//print($Query);
		$arr_file_42 = $db->fetch_table($Query); ?>
        
          <?php  if($arr_file_42[0]['file_name']==''){?>
     
     <?php  }else{?>
	
    <?php if(count($arr_file_42)=='1'){?>
   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($item['emp_id_const'],4)?>&flag=<?=$arr_file_42[0]['flag']?>" target="_blank"><?php echo substr($arr_file_42[0]['file_name'],6); ?></a>
    <?php }}?>
    </td>
    
    <td>
    <?php 
    $db=new database();
	
		$arr_file_41 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$item['emp_id_const']."' AND status = '1' AND flag= 41 order by upload_file_id_pk desc LIMIT 1 OFFSET 0"); ?>
        
          <?php  if($arr_file_41[0]['file_name']==''){?>
     
     <?php  }else{?>
	
    <?php if(count($arr_file_41)=='1'){?>
   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($item['emp_id_const'],4)?>&flag=<?=$arr_file_41[0]['flag']?>" target="_blank"><?php echo substr($arr_file_41[0]['file_name'],6); ?></a>
    <?php }}?>
    </td>
     <td>
    <?php 
    $db=new database();
		
		$arr_file_43 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$item['emp_id_const']."' AND status = '1' AND flag= 43 order by upload_file_id_pk desc LIMIT 1 OFFSET 0"); ?>
        
          <?php  if($arr_file_43[0]['file_name']==''){?>
     
     <?php  }else{?>
	
    <?php if(count($arr_file_43)=='1'){?>
   <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($item['emp_id_const'],4)?>&flag=<?=$arr_file_43[0]['flag']?>" target="_blank"><?php echo substr($arr_file_43[0]['file_name'],6); ?></a>
    <?php }}?>
    </td>
    <?php
	  if($item['status']=='5'|| $item['status']=='6')
	  { 
    $officer_name_pending = $db->fetch_table(" SELECT officer_name FROM intra_pri_master 
					WHERE  officer_id_const ='".$item['officer_id_const_approval']."' ");
	  }else
	  {
		  $officer_name_pending = $db->fetch_table(" SELECT officer_name FROM intra_pri_master 
					WHERE  officer_id_const ='".$item['officer_id_const_forward']."' ");
	  }
					?>
    
    <td>
    	<?= $status.'  '.$officer_name_pending[0]['officer_name'] ?>
     <?php if($user_stack == 'D'): ?>
     	   <a href="edit_district_transfer.php?empId=<?php echo $crypto->encode($item['emp_id_const'],4); ?>">EDIT</a>
     <?php endif; ?>	
    </td>
      <?php if( $user=="DED"){?>
    <?php if($item['status']=='1')
	{  ?> 
  <td>
  <div class="btn-group" role="group" style="width:60%;">
  <button type="button" class="btn btn-warning " id="emp_approve<?=$item['emp_id_const']?>" style="display: block;" onclick="emp_f_modal();">FORWARD</button>
   <button type="button" class="btn btn-success "  id="emp_approve<?=$item['emp_id_const']?>" style="display: block;" onclick="emp_approve_modal('<?=$crypto->encode($item['emp_id_const'],4)?>');">Approves</button>
     <button type="button" class="btn btn-danger"  id="emp_reject<?=$item['emp_id_const']?>"style="display: block;" onclick="emp_reject_modal('<?=$crypto->encode($item['emp_id_const'],4)?>');">Reject</button>
</div>
	</td>
    <?php }
	else if($item['status']=='2'){?>
		
		<td>
        <div class="btn-group" role="group" style="width:60%;">
        <button type="button" class="btn btn-warning " disabled="disabled" style="display: block;">FORWARD</button>
  <button type="button" class="btn btn-success "  disabled="disabled" style="display: block;" >Approve</button>
     <button type="button" class="btn btn-danger"  disabled="disabled" style="display: block;" >Reject</button>

</div>
	</td>
		
	<?php }
    
    else if($item['status']=='3'){?>
		
		<td>
        <div class="btn-group" role="group" style="width:60%;">
        <button type="button" class="btn btn-warning " disabled="disabled" style="display: block;">FORWARD</button>
  <button type="button" class="btn btn-success "  onclick="emp_a('<?php echo $crypto->encode($item['emp_id_const'],4);?>','<?php echo $crypto->encode("ac",4);?>')" style="display: block;" >Approve</button>
     <button type="button" class="btn btn-danger"   onclick="emp_r('<?php echo $crypto->encode($item['emp_id_const'],4);?>','<?php echo $crypto->encode("rc",4);?>')"style="display: block;" >Reject</button>
</div>
	</td>
		
	<?php }
    
    else if($item['status']=='4' || $item['status']=='5' ||  $item['status']=='6'){?>
		
		<td>
        <div class="btn-group" role="group" style="width:60%;">
        <button type="button" class="btn btn-warning "  disabled="disabled" style="display: block;">FORWARD</button>
     <button type="button" class="btn btn-success "   disabled="disabled" style="display: block;" >Approve</button>
     <button type="button" class="btn btn-danger"  disabled="disabled" style="display: block;" >Reject</button>

</div>
	</td>
		
	<?php }?>
    
 
    
	
    <?php }?>
    
            
     
   
    </tr>
    
    
    
    <? $cnt+=1; }} else { ?>
    <tr>
    <td colspan="3" style="color:red;font-weight:bold">No Data Found</td>
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
    
    <?php }?>
		
		
		<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
        <?php if($data[0]['from_status']=='2' && $prv=='1'){?>
      
		<a type="button" id="submit6" name="submit6"  class="btn btn-success"  onclick="final_save('<?php echo $crypto->encode($district_level,4); ?>','<?= $crypto->encode("final",4); ?>');">PROPOSAL SAVED</a>
        <? }else{?>
        <?php }?>
        
        
		</div>
	</div>
		
		
		
	<?php }?>
    
    
    
    
    
		<?php $db = new database();
		$officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		
		
		/*if($_SESSION['application_id']=='')
		{
			$a_id=$data[0]['application_id'];
			//unset($_SESSION['application_id']);
		}
		else
		{
			 $a_id=$_SESSION['application_id']; 
			//unset($_SESSION['application_id']);
		}*/
		
		?>
    
    
    
    	
    <div id="forward_div" style="display:none;">		
		<div class="row mb-3">
		<label for="inputPassword3" class="col-sm-6 col-form-label" >Forwarded To: </label>
		<div class="col-mb-3" >
		<input type="hidden" id="application_id_o" name="application_id_o" value="<?php echo $data[0]['application_id']; ?>" >
        
        <?php if($district_level=='D')
        {?>
		<input type="hidden" id="service_type" name="service_type" value="<?php echo '1'; ?>" >
        <input type="hidden" id="sub_menu" name="sub_menu" value="<?php echo '1'; ?>" />
        <?php }else
        {?>
        <input type="hidden" id="service_type" name="service_type" value="<?php echo '2'; ?>" >
        <input type="hidden" id="sub_menu" name="sub_menu" value="<?php echo '2'; ?>" />
       <?php  }?>
		<input type="hidden" id="emp_id_const" name="emp_id_const" value="<?php echo $data[0]['emp_id_const']; ?>" />
		
		<input type="hidden" id="for_app_rej_status" name="for_app_rej_status" value="" >
		
		
		  <select class="form-control" id="forw" name="forwarding" style='margin-left: 12%; width: auto; margin-top: -3%;'>
			<option value="" >-- Please Select --</option>
			<?php	
			
			$forwarding_qury_ex = explode(',',$officer_name[0]['forwarding_user']);
			//var_dump($forwarding_qury_ex);
		foreach($forwarding_qury_ex as $val){
		$forward_user = $db->fetch_table(" SELECT desig.* , master.officer_id_const as officer_id_const, master.officer_name as officer_name FROM intra_pri_designation_master as desig 
			INNER JOIN intra_pri_master as master ON master.stake_level_code= desig.designation_code
			WHERE master.stake_user_code = '".$val."' AND master.active_status='1' AND '4' = any( string_to_array( master.role_assign, ',' ) ) ");
					//var_dump($forward_user[0]['officer_id_const']);
			?>
				<option value="<?php echo $forward_user[0]['officer_id_const']; ?>" ><?php if($forward_user[0]['officer_id_const'] != NULL || $forward_user[0]['officer_id_const'] != ""){ echo $forward_user[0]['officer_name']."  (".$forward_user[0]['designation'].")"; }  ?></option>						
			<?php } ?>							
		  </select>
		</div>  
		</div> 


			<button type="button" class="btn btn-success" style="margin-top: -8%; margin-left: 55%; " id="submit_app" onclick="return may_be_for(this.id);" > MAY BE APPROVED </button>
			<button type="button" class="btn btn-danger"  style="margin-top: -8%;" onclick="return may_be_rej(this.id);" > MAY BE REJECTED </button>
		  
		  
		<div class="row mb-3" style="display:none; margin-left: -1%;" id="remarks_div" > 
			<label for="inputPassword3" class="col-sm-2 col-form-label" >Reason: </label>
			<div class="col-sm-6">
				<textarea type="text" class="form-control" name="remarks_res" id="remarks_res" placeholder="Reason" rows="5" cols="40" style='margin-left: -10%;' ></textarea>
				<button type="button" class="btn btn-primary btn-sm" id="submit_rej" name="submit_rej" onclick="return may_be_rej_send(this.id);" > SEND </button>
			</div>
		</div>
	</div>
	

      </div>
    
    
    
    
    
    <script>
    
    
	
	   function emp_a(val,k)
		{
			//alert(1222);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?ac_emp_id='+val+'&stack_ac='+k, function(data){
			
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
    
	
	 function emp_r(val,k)
		{
			//alert(444);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?ac_emp_id='+val+'&stack_ac='+k, function(data){
			
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
	
	
	
	
	
		function emp_approve_modal(empId)
		{
		//var link1=$(this).val();
		//var arr=link1.split('&');
		
		//var approve_emp_id=$('#approve_emp_id').val(arr[0]);
		
		
		
		
		//return false;
		$('.approv_emp_id_const').val(empId);
		$('#confirm_approve').modal('show');
		
		
		
		//$('#revoke_gp_id').val(arr[1]);
		//$('#decoded_emp_id').val(arr[2]);
		
		}
		
		
		function emp_approve_modal_all()
		{
		//var link1=$(this).val();
		//var arr=link1.split('&');
		
		//var approve_emp_id=$('#approve_emp_id').val(arr[0]);
		
		
		
		
		//return false;
		
		$('#confirm_approve_all').modal('show');
		
		
		
		//$('#revoke_gp_id').val(arr[1]);
		//$('#decoded_emp_id').val(arr[2]);
		
		}
		
			function emp_approve_all(val,k)
		{
			//alert(k);
			//alert(val);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?approve_emp_id_all='+val+'&stack_a_all='+k, function(data){
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
			
			
		
		//alert(data);
		//return false;
		
	
		
		});
		}
	
	function emp_approve(k)
		{
			//alert(k);
			var val = $('.approv_emp_id_const').val();
			//console.log(val);
		//	return false;
		
		  $.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?approve_emp_id='+val+'&stack_a='+k, function(data){
			
			//console.log(data);
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
			
			
		
		//alert(data);
		//return false;
		
	
		
		});
		}
	
	
		function emp_reject_modal(empId)
	{
		//var link1=$(this).val();
		//var arr=link1.split('&');
		
		//var approve_emp_id=$('#approve_emp_id').val(arr[0]);
		$('.approv_emp_id_const').val(empId);
		$('#confirm_reject').modal('show');
		
		
		
		
		//$('#revoke_gp_id').val(arr[1]);
		//$('#decoded_emp_id').val(arr[2]);
		
	}
	
	
		function final_save(k,val){
		
		//alert(k);
		//return false;
		$.post('<?= $config['base_url'] ?>page/intra_pri/intra_pri_final_tra_submit.php?stack_final='+k+'&app_id='+val, function(data){
			
			//alert(data);
			//return false;
			 var result = $.parseJSON(data);
			//return false;
			//alert(result[1]);
			$('#forward_div').show();
			if(result[0]==1)
			{ 
			$("#application_id_o").val(result[1]);
			$('#forward_div').show();
			$('#submit6').hide();
			
			}
			//return false;
			
		//$("#employee_id").html(data);
		
		//$("#employee_id_ps").html(data);
			
		});
	
	//alert(111);
	//false;
	
	
	}
	
	function emp_reject(k)
		{
			
		var val = $('.approv_emp_id_const').val();	
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?reject_emp_id='+val+'&stack_a='+k, function(data){
			
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
		
		
			function emp_f_modal()
	{
		//var link1=$(this).val();
		//var arr=link1.split('&');
		
		//var approve_emp_id=$('#approve_emp_id').val(arr[0]);
		
		$('#confirm_f').modal('show');
		
		
		
		
		//$('#revoke_gp_id').val(arr[1]);
		//$('#decoded_emp_id').val(arr[2]);
		
	}
	
	
	function emp_f(val,k)
		{
			//alert(1222);
			//return false;
		
		$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_employee_transfer_submit.php?f_emp_id='+val+'&stack_f='+k, function(data){
			
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
    
    
    
    	
<script>

function may_be_for(k){
	$("#remarks_div").hide();
	var officer_id_const = $("#forw").val();
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_comm").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = 'MF';
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							//return false;
							window.location.reload();
						}
				});		
}


function may_be_rej(k){
	$("#remarks_div").show();
	$("#comments_div").hide();
	$("#for_app_rej_status").val('MR');
}



function may_be_rej_send(k){
	//$("#remarks_div").show();
	var officer_id_const = $("#forw").val();
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_res").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = $("#for_app_rej_status").val();	
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							window.location.reload();
						}
				});		
}



</script>
    
    <div class="modal fade bs-example-modal-sm" id="confirm_approve" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
    
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Approve This Employee?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    
    
    <input type="hidden" name="approv_emp_id_const" value="" class="approv_emp_id_const"> 
    <input type="button"  value="YES" onclick="emp_approve('<?=$crypto->encode("approve",4); ?>');" class="btn btn-success finalize" />
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
    </div>
    </div>
    </div>
    
    
    </div>
    </div>
    
    
    
        <div class="modal fade bs-example-modal-sm" id="confirm_f" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
    
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To sent to DRPRO for NOC to Accommodate this Employee?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    
    
     
    <input type="button"  value="YES" onclick="emp_f('<?=$crypto->encode($item['emp_id_const'],4);?>','<?=$crypto->encode("f",4)?>');" class="btn btn-success finalize" />
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
    </div>
    </div>
    </div>
    
    
    </div>
    </div>
    
    
        <div class="modal fade bs-example-modal-sm" id="confirm_approve_all" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
    
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Approve All this Employee?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    
    
     
    <input type="button"  value="YES" onclick="emp_approve_all('<?=$crypto->encode($item['application_id'],4);?>','<?=$crypto->encode("approveall",4)?>');" class="btn btn-success finalize" />
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
    </div>
    </div>
    </div>
    
    
    </div>
    </div>
    
     <div class="modal fade bs-example-modal-sm" id="confirm_reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Transfer Details</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
   
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This Employee?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    
      <input type="hidden" name="approv_emp_id_const" value="" class="approv_emp_id_const">
    <input type="button" value="YES"  onclick="emp_reject('<?=$crypto->encode("reject",4)?>');" class="btn btn-success finalize" />
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
    </div>
    </div>
    </div>
    
    
    </div>
    </div>