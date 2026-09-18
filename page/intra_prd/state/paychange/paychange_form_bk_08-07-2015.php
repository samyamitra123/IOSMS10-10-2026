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

<script type="text/javascript">
function validContact(){
	
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
	*/
	
	return true;
}
	





</script> 
<link rel="stylesheet" href="themes/default/css/style.css" />
<!--CONTENT START-->
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<div class="mainContent float_l" style="width:1000px">
<!-- Common Back Button --->
  <div class="dashboard-main"> 
    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
          <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>
   
    <div class="dashcontenr"> 
       
      <!-- <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>-->
	   
        <br clear="all" />
        <br clear="all" />
     
     <?php 
	 $time_token=time();
     $_SESSION['security_token']=$time_token;
     $enc_token=md5('369'.$time_token);
	 $cryptography=new cryptography(); 
	 $date=$cryptography->decode($_GET['date_val'],3);
	 
	 				$db = new database();
					$paychange = $db->fetch_table("select paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
												   entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
												   paychange_ptax, paychange_pdf, flag, order_file_name, conveyance_allowance, 
												   hill_allowance 
	   												from prd_admin_paychange 
													where paychange_fromdate='$date' 
		
												");
												
					$check_date=$db->fetch_table("SELECT paychange_fromdate AS max_date FROM prd_admin_paychange WHERE paychange_fromdate=(SELECT MAX(paychange_fromdate ) FROM prd_admin_paychange ) AND flag='FALSE'");	
					if($date==$check_date[0]['max_date']){					
				?>
 <noscript>Please Enable JavaScript In your Browser</noscript>
                <? if($error_msg){
              	echo $error_msg;
                    
                }?>   
     
        <form action="paychange_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validContact();" class="form-horizontal">
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
       
                 <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-4 control-label">DA(%)<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="da"  id="da" class="form-control" value="<?php if(!empty($paychange[0]['paychange_da'])) {echo trim($paychange[0]['paychange_da']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-4 control-label">HRA(%)<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="hra"  id="hra" class="form-control" value="<?php if(!empty($paychange[0]['paychange_hra']) && $paychange[0]['paychange_hra']!='0001-01-01') {echo trim($paychange[0]['paychange_hra']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-4 control-label">MA(%)<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" maxlength="20" autocomplete="off" name="ma"  id="ma" class="form-control" value="<?php if(!empty($paychange[0]['paychange_ma'])) {echo trim($paychange[0]['paychange_ma']);}?>" onkeypress="return keyRestrict(event,'0123456789.');">
                </div>
                </div>
                
                 
                 <div class="form-group">
                    <div class="col-sm-offset-6 col-sm-7">
                      <button type="submit" name="paychange_submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
                      <input type="hidden" name="frm_date" value="<?php echo $date;?>" />
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
                
                 <div>
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">From Date:</label>
                <div class="col-sm-2 control-label">
                 <?php if(!empty($paychange[0]['paychange_fromdate'])) {echo dbdate(trim($paychange[0]['paychange_fromdate']));}else {echo $paychange[0];} ?>
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">To Date:</label>
                <div class="col-sm-2 control-label">
                 <?php if(!empty($paychange[0]['paychange_todate']) && $paychange[0]['paychange_todate']!='0001-01-01') {echo dbdate(trim($paychange[0]['paychange_todate']));}?>
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">DA(%):</label>
                <div class="col-sm-2 control-label">
                 <?php if(!empty($paychange[0]['paychange_da'])) {echo trim($paychange[0]['paychange_da']);}
					//else {echo $paychange[1];} ?>
                </div>
                </div>
                  
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">HRA(%): </label>
                <div class="col-sm-2 control-label">
                <?php if(!empty($paychange[0]['paychange_hra']) && $paychange[0]['paychange_hra']!='0001-01-01') {echo trim($paychange[0]['paychange_hra']);} ?>
                </div>
                </div>
                     
                  <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">MA:</label>
                <div class="col-sm-2 control-label">
                <?php if(!empty($paychange[0]['paychange_ma'])) {echo trim($paychange[0]['paychange_ma']);}?>
                </div>
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
