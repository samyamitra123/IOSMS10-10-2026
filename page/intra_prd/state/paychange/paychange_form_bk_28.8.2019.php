<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
header("X-Frame-Options: deny");
header("X-Content-Type-Options: nosniff");

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

function dbdate($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 } 

/*$teacher_id_pk=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/



//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables

?>

<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-1.11.2.min.js"></script>
<script src="<?php echo $config['base_url']; ?>themes/default/js/bootstrap.min.js"></script>


<script type="text/javascript">
/*function validContact(){
	
	if(document.getElementById('da').value==''){
		alert("Please Enter Da.");
		document.getElementById('da').focus();
		return false;
	}
	
	if(document.getElementById('hra').value==''){
		alert("Please Enter HRA.");
		document.getElementById('hra').focus();
		return false;
	}
	
	if(document.getElementById('ma').value==''){
		alert("Please Enter ma.");
		document.getElementById('ma').focus();
		return false;
	}
	
	
	/*if(document.getElementById('cpf').value=='' ){
		alert("Please Enter CPF.");
		document.getElementById('cpf').focus();
		return false;
	}
	
	
	return true;
}*/
	





</script> 
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
		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
		
    </style>
<!--CONTENT START-->
<div class="row" id="cont">
<div class="content">
 <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
          
   
   
     
     <?php 
	 $time_token=time();
     $_SESSION['security_token']=$time_token;
     $enc_token=md5('369'.$time_token);
	 $cryptography=new cryptography(); 
	 $paychange_id_pk=$cryptography->decode($_GET['paychange_id_pk'],3);
	 //echo $paychange_id_pk;exit;
	 $date=$cryptography->decode($_GET['date_val'],3);
	 
	 				$db = new database();
					$paychange = $db->fetch_table("select paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
												   entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
												   paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance, 
												   hill_allowance 
	   												from prd_admin_paychange 
													where paychange_id_pk='$paychange_id_pk' 
		
												");
												
												/*echo "select paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
												   entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
												   paychange_ptax, paychange_pdf, flag, order_file_name, conveyance_allowance, 
												   hill_allowance 
	   												from prd_admin_paychange 
													where paychange_id_pk='$paychange_id_pk'";exit;*/
												
					//$check_date=$db->fetch_table("SELECT paychange_fromdate AS max_date FROM prd_admin_paychange WHERE paychange_fromdate=(SELECT MAX(paychange_fromdate ) FROM prd_admin_paychange ) AND flag='FALSE'");	
					if($paychange[0]['flag']=='t'){					
				?>
 <noscript>Please Enable JavaScript In your Browser</noscript>
                <? if($error_msg){
              	echo $error_msg;
                    
                }?>   
     
        <form action="paychange_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validContact();" class="form-horizontal">
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                
       
                 <div class="form-group">
                <div class="col-sm-1.9"></div>
                <label for="WithEffectFrom" class="col-sm-5 control-label">DA(%)<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="da"  id="da" class="form-control" value="<?php if(!empty($paychange[0]['paychange_da'])) {echo trim($paychange[0]['paychange_da']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-1.9"></div>
                <label for="WithEffectFrom" class="col-sm-5 control-label">HRA(%)<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="hra"  id="hra" class="form-control" value="<?php if(!empty($paychange[0]['paychange_hra']) && $paychange[0]['paychange_hra']!='0001-01-01') {echo trim($paychange[0]['paychange_hra']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-1.9"></div>
                <label for="WithEffectFrom" class="col-sm-5 control-label">MA<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="ma"  id="ma" class="form-control" value="<?php if(!empty($paychange[0]['paychange_ma'])) {echo trim($paychange[0]['paychange_ma']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-1.9"></div>
                <label for="WithEffectFrom" class="col-sm-5 control-label">CONV ALLOW &nbsp;<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="conveyance_allowance"  id="conveyance_allowance" class="form-control" value="<?php if(!empty($paychange[0]['conveyance_allowance'])) {echo trim($paychange[0]['conveyance_allowance']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                 
                 <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                      <button type="submit" name="paychange_submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
                      <input type="hidden" name="frm_date" value="<?php echo $date;?>" />
                       <input type="hidden" name="paychange_id_pk" value="<?php echo $paychange_id_pk;?>" />
               </div>
      	</form>
        <?php
					}
					else
					{
		?>       


        		       
              
               <noscript>Please Enable JavaScript In your Browser</noscript>
                <? if($error_msg){?>
              	<?php echo $error_msg;?>
                <? }?> 
                
                <div class="emplist">
				<div class="school">	
                <div class="table-responsive" >
				<table style="width:100%">
                <tr>
                <th colspan="6" style="font-size:25px">PAYCHANGE DETAILS</th>
                </tr>
                <tr>
                 <th><strong>From Date</strong></th>
                 <th ><strong>To Date</strong></th>
                 <th><strong>DA(%)</strong></th>
                 <th ><strong>HRA(%)</strong></th>
                 <th><strong>MA</strong></th>
                 <th><strong>CONV ALLOW</strong></th>
                </tr>
                <tr style="background-color:#DDF7FF">
                <td ><?php if(!empty($paychange[0]['paychange_fromdate'])) {echo dbdate(trim($paychange[0]['paychange_fromdate']));}else {echo $paychange[0];} ?></td>
                 <td ><?php  if(!empty($paychange[0]['paychange_todate']) && $paychange[0]['paychange_todate']!='0001-01-01') {echo dbdate(trim($paychange[0]['paychange_todate']));} ?></td>
                <td ><?php if(!empty($paychange[0]['paychange_da'])) {echo trim($paychange[0]['paychange_da']);} ?></td>
                <td ><?php if(!empty($paychange[0]['paychange_hra']) && $paychange[0]['paychange_hra']!='0001-01-01') {echo trim($paychange[0]['paychange_hra']);} ?></td>
                <td ><?php if(!empty($paychange[0]['paychange_ma'])) {echo trim($paychange[0]['paychange_ma']);} ?></td>
                 <td ><?php if(trim($paychange[0]['conveyance_allowance'])) {echo trim($paychange[0]['conveyance_allowance']);} ?></td>
                </tr>
                
                </table> 
                 <div>
                </div>  
                </div>
               
                    
      	        
        <?php
					}
		?>
    </div>
  </div>
</div>
</div>
</div>
</div>


