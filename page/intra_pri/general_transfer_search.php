	<?php
	
	session_start();
	
	
	
	/*echo "<pre>";
print_r($_SESSION);
echo "<pre>"; die;*/
	 
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
	
	
	?>
	<style>
	.form-horizontal .control-label {
	text-align:left;
	}
	</style>
	
	
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
	<h1 class="heading">Employee post in History Search</h1>
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
    
    
  
    
    
    

        
        <div class="row mb-3">
    
        
        <?php     
       // $db= new database();
       /* $block=$db->fetch_table("select block_name,block_code,block_name from prd_location_master_block 
        Where district_id_fk='21' order by block_name");*/?>
        <label for="inputPassword3" class="col-sm-1 control-label">Search: <span class="star_color">*</span></label>
        <div class="col-sm-2">
        <select class="form-control upper_case" name="emp_serach" id="emp_serach"onchange="search_emp(this.value)">
        <option value="">-Please Select-</option>
        
       <!-- <option value="all">ALL</option>-->
        
         <option value="zp"> ZP</option>
         <option value="ps"> PS</option>
         <option value="gp"> GP</option>
        
        </select>
        </div>
        </div>
        
         <div class="row mb-3" id="post" style="display: none">
    
        
        <?php     
       // $db= new database();
       /* $block=$db->fetch_table("select block_name,block_code,block_name from prd_location_master_block 
        Where district_id_fk='21' order by block_name");*/?>
        <label for="inputPassword3" class="col-sm-1 control-label">POST: <span class="star_color">*</span></label>
        <div class="col-sm-2">
        
        <?
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('1114','1115','1116','1117','1118','1119','1120','1122','1123') order by code");
	
	 
    ?>
    <select class="form-control" name="vice_desig" id="vice_desig"  onchange="showdesig(this.value,'<?php echo 1;?>')">
    <option value="">-Please Select-</option>
    <? 
    /*if(strlen($designation) == 1) 
    $designation = '110'.$designation;
    else if (strlen($designation) == 2)
    $designation = '11'.$designation;*/
    
    foreach($arr as $key){
    $key['code']. '<br />';
    ?>
    <option value="<?= $key['code']; ?>" 
    <? 
    if($emp_desig==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
        
        
         <!--<option value="ZP"> ZP EMPLOYEE</option>-->
        
        </select>
        </div>
        </div>
        
        
        <div class="row mb-3" id="post_ps" style="display: none">
    
        
        <?php     
       // $db= new database();
       /* $block=$db->fetch_table("select block_name,block_code,block_name from prd_location_master_block 
        Where district_id_fk='21' order by block_name");*/?>
        <label for="inputPassword3" class="col-sm-1 control-label">POST: <span class="star_color">*</span></label>
        <div class="col-sm-2">
        
        <?
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('9001','9002','9003','9004','9005','9006','9007','9008','9009','9010','9011') order by code");
	
	 
    ?>
    <select class="form-control" name="vice_desig" id="vice_desig_ps"  onchange="showdesig(this.value,'<?php echo 3;?>')">
    <option value="">-Please Select-</option>
    <? 
    /*if(strlen($designation) == 1) 
    $designation = '110'.$designation;
    else if (strlen($designation) == 2)
    $designation = '11'.$designation;*/
    
    foreach($arr as $key){
    $key['code']. '<br />';
    ?>
    <option value="<?= $key['code']; ?>" 
    <? 
    if($emp_desig==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
        
        
         <!--<option value="ZP"> ZP EMPLOYEE</option>-->
        
        </select>
        </div>
        </div>
        
        
        
        
        
        
        
            <div class="row mb-3" id="post_zp" style="display: none">
    
        
        <?php     
       // $db= new database();
       /* $block=$db->fetch_table("select block_name,block_code,block_name from prd_location_master_block 
        Where district_id_fk='21' order by block_name");*/?>
        <label for="inputPassword3" class="col-sm-1 control-label">POST Zp: <span class="star_color">*</span></label>
        <div class="col-sm-2">
        
        <?
    $db = new database();
	
	
    $arr = $db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where status='1'  order by designation_id");
	
	 
    ?>
    <select class="form-control" name="vice_desig_zp" id="vice_desig_zp"  onchange="showdesig(this.value,'<?php echo 2;?>')">
    <option value="">-Please Select-</option>
    <? 
    /*if(strlen($designation) == 1) 
    $designation = '110'.$designation;
    else if (strlen($designation) == 2)
    $designation = '11'.$designation;*/
    
    foreach($arr as $key){
    $key['designation_id']. '<br />';
    ?>
    <option value="<?= $key['designation_id']; ?>" 
    <? 
    if($emp_desig==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option>
    <? } ?>
    </select>
        
        
         <!--<option value="ZP"> ZP EMPLOYEE</option>-->
        
        </select>
        </div>
        </div>
        
        
        
        
         <!--<div class="row mb-3" id="time" style="display:none;">
    
        
        <?php     
       // $db= new database();
       /* $block=$db->fetch_table("select block_name,block_code,block_name from prd_location_master_block 
        Where district_id_fk='21' order by block_name");*/?>
        <label for="inputPassword3" class="col-sm-1 control-label">Time Periord: <span class="star_color">*</span></label>
        <!--<div class="col-sm-2">
        <select class="form-control upper_case" name="time_p" id="time_p"  onchange="showtime(this.value)">
        <option value="">-Please Select-</option>
        <option value="3">More than 3 yeras</option>
        <option value="5">More than 5 yeras</option>
         <option value="10">More than 10 yeras</option>
        -->
         <!--<option value="ZP"> ZP EMPLOYEE</option>-->
        
        </select>
        <!--</div>
        </div>-->
        
        <div class="row mb-3"id="time" style="display:none;">
    
    <label for="inputPassword3" class="col-sm-1 control-label">FROM DATE<span class="star_color">*</span></label>
    <div class="col-sm-2">
    <input type="text" class="form-control" id="from_date" name="from_date"  value=""  autocomplete="off" placeholder="DD-MM-YY" onchange="showtime(this.value)">
    </div>
    </div>
    
    
    <div class="row mb-3"id="time_to" style="display:none;">
    <label for="inputPassword3" class="col-sm-1 control-label">TO DATE<span class="star_color">*</span></label>
    <div class="col-sm-2">
    <input type="text" class="form-control" id="to_date" name="to_date"  value=""  autocomplete="off" placeholder="DD-MM-YY"  onchange="showtime(this.value)">
    </div>
    </div>
        
         <div id="val_div">
         </div>
        
       
<?php
	//----------------------------------- FOOTER ----------------------------------------------------------------------------------
	require '../../page/layout/footer.php';
	//----------------------------------------------------------------------------------------------------------------------------
	?>
    <script>
	    
    $(function() {
    /*	$( "#first_join_date" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    dateFormat: 'dd-mm-yy' 
    });*/
    
    /*$( "#Death_date" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    dateFormat: 'dd-mm-yy' 
    });*/
	
	
	
	
    
    $( "#from_date" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    dateFormat: 'dd-mm-yy',
    //minDate:dateToday	 
    });
    $( "#to_date" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    dateFormat: 'dd-mm-yy' 
    });
    });
		
	
   function search_emp(i){
	   
	   //alert(i);
		
		if(i=='zp'){
			$('#post_zp').show();
			$('#post').hide();
		}
		else if(i=='gp'){
			$('#post').show();
			$('#post_zp').hide();
		}
		else if(i=='ps'){
			$('#post_ps').show();
			$('#post_zp').hide();
			$('#post').hide();
		}
		
		else{
			$('#post').hide();
			$('#post_zp').hide();
			
		}
	}
	
	function showdesig(i,k){
	   
 //alert(k);
		
		if(k=='1' ){
			$('#time').show();
			$('#time_to').show();
			$('#post').show();
			$('#post_zp').hide();
			$('#post_ps').hide();
		}
		else if(k=='2' ){
			$('#time').show();
			$('#time_to').show();
			$('#post').hide();
		$('#post_zp').show();
		$('#post_ps').hide();
		}
		
		else if(k=='3' ){
			$('#time').show();
			$('#time_to').show();
			$('#post').hide();
		$('#post_zp').hide();
		$('#post_ps').show();
		
		}
		else{
			$('#time').hide();
			$('#post').hide();
			$('#time_to').hide();
			$('#post_zp').hide();
			$('#post_ps').hide();
			
		}
	}
	
/*	function showtime(i){
	  //alert(222);
	   var time_p=$('#time_p').val();
			var post=$('#vice_desig').val();
			
			alert(time_p);
			
		
		
	}*/		
	
	
	function showtime(i){
	  //alert(222);
	   var from_date=$('#from_date').val();
	    var to_date=$('#to_date').val();
			var post_emp=$('#vice_desig').val();
			var post_emp_zp=$('#vice_desig_zp').val();
			var post_emp_ps=$('#vice_desig_ps').val();
			
			//alert(post_emp_zp);
			//alert(to_date);
			//if(time_p!="" && post_emp!="")
//			{
			//alert(22);
			
			
			$.post('<?= $config['base_url'] ?>page/intra_pri/ajax_table_check.php?from_date='+from_date+'&post_emp='+post_emp+'&to_date='+to_date+'&post_emp_zp='+post_emp_zp+'&post_emp_ps='+post_emp_ps, function(data){
			//alert(data);
			//return false;
			$('#val_div').html(data);
			//return false;
			
			
			
		
		//alert(data);
		//return false;
		
	
		
		});
			
			//}

		
		
	}	
	
	
		


	
	
    </script>
	