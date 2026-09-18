<?

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

//header('Location:login.php');
require_once("captcha/simple-php-captcha.php");
require_once '../includes/library/xml.class.php';
require_once '../includes/config/config.php';

error_reporting(0);

if(!$select_val){
    
    header('Location: '. $config['base_url'] . "index.php");
}
//----------------------------------------------------------------
//------------------------------------------------------------
/*$_SESSION['captcha'] = simple_php_captcha( array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'abcdefghjkmnprstuvwxyz23456789',
	'min_font_size' => 25,
	'max_font_size' => 30,
	'color' => '#222',
	'angle_min' => 0,
	'angle_max' => 0,
	'shadow' => true,
	'shadow_color' => '#FFF',
	'shadow_offset_x' => -1,
	'shadow_offset_y' => 1
));*/
if( $select_val=='gp'){
   
$type = simplexml_load_file('xml/login/stake_level_type.xml');
$stake = simplexml_load_file('xml/login/stake_level.xml');
}
else if($select_val=='ps'){
    
$type = simplexml_load_file('xml/login/ps_stake_level_type.xml');
$stake = simplexml_load_file('xml/login/ps_stake_level.xml');
    
}else if($select_val=='zp'){
    
$type = simplexml_load_file('xml/login/zp_stake_level_type.xml');
$stake = simplexml_load_file('xml/login/zp_stake_level.xml');
    
}


/*$_SESSION['captcha_code']=$_SESSION['captcha']['code'];
$captcha_code=$obj_crpto->encode($_SESSION['captcha_code'],4);*/
?>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>-->
				<!--<script src="<//?= $config['base_url'] ?>themes/default/js/md5.js" type="text/javascript"></script>-->
				<script src="<?= $config['base_url'] ?>themes/default/js/crypto-js.js" type="text/javascript"></script>
				
				<script>
					$(function () {
						$('[data-toggle="tooltip"]').tooltip()
					})
                </script>
				<script>
					$(document).ready(function(){
						//$("a").removeAttr("href").css("cursor","pointer");
						$( "#capcha_refresh" ).click(function( event ) {
							//alert(1);
							$(document).ajaxStart(function() {
								$('.loading').html('<h3>Loading...<img height="20" src="<?= $config['base_url']?>themes/default/image/preloader.gif" /></h3>');
							});
							$(document).ajaxComplete(function() {
								
								//$("#saving").fadeOut();
								$('.loading').text('');
								//$("#saving").css("display", "none");
		
							});
							$("#captcha_code").load('<?=$config['base_url']?>page/ajax_captcha.php', function(responseTxt, statusTxt, xhr) {
								//alert(responseTxt);
								if (statusTxt == "error") {
									alert("Error: "+xhr.status+": "+xhr.statusText);
									$('.loading').text('Loading error');
									//$("#saving").css("display", "block");
								} else {
									$('.loading').text('');
									$('#captcha').attr("src",responseTxt) ;
									//$("#saving").css("display", "none");
								}
							});
							event.preventDefault();
					 	  });
				  	 });
				</script>
				<script type="text/javascript">
					function encription(){
					if(document.login_form.pw.value!=''){
						//var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.pw.value));
						var pass = CryptoJS.SHA256(document.login_form.pw.value);
						//alert(pass);
						document.login_form.pw.value=pass;
					}
					if(document.login_form.capcha.value!=''){
						var cap = CryptoJS.MD5(document.login_form.capcha.value);
						document.login_form.capcha.value="***************************";
						document.login_form.security_code.value=cap;
					}
				}

				</script>
				<script>
				
//Done by Suman Mahajan on 26.01.2020
function restrict() {
  var loginid, distid, text, stake;

  // Get the value of the input field with id="numb"
  stake = document.getElementById("stake").value;
  loginid = document.getElementById("un").value;
  distid = loginid.substring(0, 4);
  var date = new Date();
  var time = date.getHours();
  //alert(time);
  if (time >= 10 && time < 11){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3299 || distid == 3216) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  if (time >= 11 && time < 12){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3215 || distid == 3216 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
   if (time >= 12 && time < 13){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3206 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
   if (time >= 13 && time < 14){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if ( distid == 3214 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
    if (time >= 14 && time < 15){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3213 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  if (time >= 15 && time < 16){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3210 || distid == 3221 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  if (time >= 16 && time < 17){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3203 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  if (time >= 17 && time < 18){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3202 || distid == 3299) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  if (time >= 18 && time < 19){
  if (stake == 64 || stake == 35 || stake == 63 || stake == 25 || stake == 36 || stake == 37 || stake == 50 || stake == 51 || stake == 52 || stake == 53 || stake == 54 || stake == 55){
  // If x is Not a Number or less than one or greater than 10
  if (distid == 3211 || distid == 3222 || distid == 3299 ) {
    //text = "Input OK";
	return true;
  } else {
    //text = "Input wrong";
	alert("Login is restricted to avoid huge rush on the servers. Please login according to the following schedule. \n \n 29 January 2020 \n 10AM-11AM: 24 PARGANAS (SOUTH) \n 11AM-12PM: 24 PARGANAS (NORTH) \n 12PM-1PM: HOOGHLY \n  \n 1PM-2PM: PURULIA \n 2PM-3PM: BANKURA \n 3PM-4PM: PASCHIM MEDINIPUR, JHARGRAM \n  \n 4PM-5PM: BIRBHUM \n 5PM-6PM: PURBA BARDHAMAN \n 6PM-7PM: PASCHIM BARDHAMAN, PURBA MEDINIPUR");
	return false;
  }}}
  return true;
  //document.getElementById("demo").innerHTML = text;
} 
</script> 
<div class="container" <?php if(isset($invalid_username) || isset($invalid_password) || isset($caperrot)){?> style="margin-left: 30%; width: 175%;" <?php } else{?> style="margin-left: 42%; width: 175%;" <?php } ?> >
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title" style="text-align:center">LOGIN</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form" action="log_sub.php" method="post" name="login_form" onsubmit="return encription()">
			<input type="hidden" name = "login_stake" value="<?php if($select_val){echo $select_val;}  ?> " />	    
                    <input type="hidden" name = "security_code" value="" />
					<input type="hidden" name = "code_cap" value="<?php echo $captcha_code; ?>" />
                    <fieldset>
					<div class="form-group">
                     <?php if(isset($stakeerrot)){  ?>
			    		    <select class="form-control" placeholder="User" name="stake" id="stake" style="border-color:red;" data-toggle="tooltip" data-placement="top" title="<?php echo $stakeerrot; ?>">
                             <option value="">--- Select User --- </option>
							<?php foreach ($type->level_type as $key ) { ?>
                                                        <optgroup label="<?php echo $key->name; ?>">
																							
															<?php foreach ($stake->level as $key2) { 
																if( (int)$key->type ==(int)$key2->type){
															?>

																	<option value="<?php echo $key2->id; ?>" <?php if(isset($_POST['stake'] ) && $_POST['stake'] == $key2->id){ echo 'selected';} ?>><?php echo $key2->desc . '['. $key2->abbr.']'; ?></option>

                                                            <?php }} ?>
																							
														</optgroup>
													<?php } ?>
										</select>
                                        <?php }else{ ?>
                                        <select name="stake" id="stake" class="form-control">
                                                                                    
                                                <option value="">--- Select User --- </option>

                                                   <?php foreach ($type->level_type as $key ) { ?>
                                                        <optgroup label="<?php echo $key->name; ?>">
																							
															<?php foreach ($stake->level as $key2) { 
																if( (int)$key->type ==(int)$key2->type){
															?>

																	<option value="<?php echo $key2->id; ?>" <?php if(isset($_POST['stake'] ) && $_POST['stake'] == $key2->id){ echo 'selected';} ?>><?php echo $key2->desc . '['. $key2->abbr.']'; ?></option>

                                                            <?php }} ?>
																							
														</optgroup>
													<?php } ?>
										</select>
                                        <?php } ?>
							</select>
			    		</div></br>
			    	  	<div class="form-group">
                         <?php if(isset($unerrot)){ ?>
			    		    <input class="form-control" placeholder="User name" id="un" name="un" type="text" style="border-color:red;" data-toggle="tooltip" data-placement="top" title="<?php echo $unerrot ; ?>" value="<?php echo $username;?>" autocomplete="off">
                            <?php }elseif(isset($invalid_username) || isset($_GET['error'])){ ?>
                            <input class="form-control" placeholder="User name" id="un" name="un" type="text" style="border-color:red;" data-toggle="tooltip" data-placement="top" title="<?php echo $invalid_username ; ?>" value="<?php echo $username;?>" autocomplete="off">
                            <?php }else{ ?>
                             <input class="form-control" placeholder="User name" id="un" name="un" type="text" value="<?php echo $username;?>" autocomplete="off">
                             <?php } ?>
			    		</div></br>
			    		<div class="form-group">
                         <?php if(isset($pwerrot)){  ?>
			    			<input class="form-control" placeholder="Password" id="pw" name="pw" type="password" style="border-color:red;" data-toggle="tooltip" data-placement="top" title="<?php echo $pwerrot ; ?>" autocomplete="off">
                          <?php }elseif(isset($invalid_password) || isset($_GET['error'])){  ?>
                          <input class="form-control" placeholder="Password" id="pw" name="pw" type="password" style="border-color:red;" data-toggle="tooltip" data-placement="top" title="<?php echo $invalid_password ; ?>" autocomplete="off">
                           <?php }else{ ?>
                          <input class="form-control" placeholder="Password" id="pw" name="pw" type="password" autocomplete="off"> 
                           <?php } ?>
			    		</div></br>
						<div class="form-group">
                                     <?php if(isset($caperrot)){ ?>
			    			<input class="form-control" placeholder="Captcha" id="capcha" name="capcha" type="text" style="border-color:red;width:65%;float:left;" data-toggle="tooltip" data-placement="top" title="<?php echo $caperrot ; ?>" autocomplete="off">
                             <?php }else{ ?>
                             <input class="form-control" placeholder="Captcha" id="capcha" name="capcha" type="text" style="width:65%;float:left;" autocomplete="off">	
                             <?php } ?>
                         <input type="hidden" name="captcha_code" id="Captcha_code" value="" />
			    			<?php echo '<img width ="80" height="40" style="
  border: 1px solid #C9C9C9;margin-left:3%" id="captcha_img" src="'.$config['base_url'].'page/CaptchaSecurityImages.php?width=115&height=40&characters=5" style="border:1px solid #FA6F1B; margin:0 0 0 10px;" alt="CAPTCHA code">'; ?>
                             <a id="capcha_refresh" style="cursor:pointer;"><img src="<?= $config['base_url'] ?>themes/default/image/refresh1.png"  alt="Refresh" style="vertical-align: -webkit-baseline-middle;" onclick="document.getElementById('captcha_img').src='<? echo $config['base_url']?>page/CaptchaSecurityImages.php?'+Math.random();document.getElementById('capcha').focus();" /></a>
			    		</div></br>
                        <!--<div class="form-group">
                         <input type="hidden" name="captcha_code" id="captcha_code" value="" />
			    			<?php /*echo '<img width ="80" height="40" style="border: 1px solid #C9C9C9;margin-left: 32%" id="captcha" src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';*/ ?>
                             <a id="capcha_refresh" style="cursor:pointer;"><img src="<?= $config['base_url'] ?>themes/default/image/refresh1.png"  alt="Refresh" style="vertical-align: -webkit-baseline-middle;" /></a>
			    		</div>-->
			    		<!--<div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="Remember Me"> Remember Me
			    	    	</label>
			    	    </div>-->
                    <input class="btn btn-lg btn-info btn-block" type="submit" value="Login" style="width:100%;">
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
</br>