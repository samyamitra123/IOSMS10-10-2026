<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

require_once("captcha/simple-php-captcha.php");
require_once '../includes/library/xml.class.php';
require '../includes/config/config.php';

//----------------------------------------------------------------
//------------------------------------------------------------
$_SESSION['captcha'] = simple_php_captcha( array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'abcdefghjkmnprstuvwxyz23456789',
	'min_font_size' => 20,
	'max_font_size' => 25,
	'color' => '#222',
	'angle_min' => 0,
	'angle_max' => 0,
	'shadow' => true,
	'shadow_color' => '#FFF',
	'shadow_offset_x' => -1,
	'shadow_offset_y' => 1
));
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
$type = simplexml_load_file('xml/login/stake_level_type.xml');
$stake = simplexml_load_file('xml/login/stake_level.xml');

$_SESSION['captcha_code']=$_SESSION['captcha']['code'];
$captcha_code=$obj_crpto->encode($_SESSION['captcha_code'],4);
?>				

 
				<!--<script src="<//?= $config['base_url'] ?>themes/default/js/md5.js" type="text/javascript"></script>-->
				<script src="<?= $config['base_url'] ?>themes/default/js/sha1.js" type="text/javascript"></script>
				<script type="text/javascript">
					function encription(){
					if(document.login_form.pw.value!=''){
						var pass = CryptoJS.SHA1(CryptoJS.MD5(document.login_form.pw.value));
						document.login_form.pw.value=pass;
					}
					if(document.login_form.capcha.value!=''){
						var cap = CryptoJS.MD5(document.login_form.capcha.value);
						document.login_form.capcha.value="***************************";
						document.login_form.security_code.value=cap;
					}
				}

				</script>
                  <?php if(isset($_GET['error'])){ ?><div style="text-align: center;color: #f00;">!!Sorry, wrong username or password, try again</div><?php } ?>
<form  action="log_sub.php" method="post" accept-charset="utf-8" class="form-horizontal" name="login_form" onsubmit="return encription()">
<input type="hidden" name = "security_code" value="" />
<input type="hidden" name = "code_cap" value="<?php echo $captcha_code; ?>" />
<br /><br />
<div class="login">
<div class="login_text_big"></div>
<div class="border"></div>
<br /><br/>
<div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">User</label>
    <div class="col-sm-8">
      <select class="form-control" name="stake" id="stake">
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
    </div>
    <br />
  <div class="col-sm-5"><span class="red-col"><?php if(isset($stakeerrot)){ echo $stakeerrot; } ?></span></div>
  </div> 
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Username</label>
    <div class="col-sm-8">
      <input type="text" name="un" class="form-control" placeholder="Please enter username here" autocomplete="off" value="<?php echo $username;?>">
    </div>
    <div class="col-sm-5"><span class="red-col"><?php if(isset($unerrot)){ echo $unerrot; } ?></span></div>
  </div>  
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
    <div class="col-sm-8">
     <input type="password" id="pw" name="pw" class="form-control" placeholder="Please enter password here" autocomplete="off" value="<?php echo $username;?>">
    </div>
  <div class="col-sm-5"> <span class="red-col"><?php if(isset($pwerrot)){ echo $pwerrot; } ?></span></div>
  </div> 
  
    <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label"><?php echo '<img width ="80" height="40" src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">'; ?></label>
    <div class="col-sm-8">
      <input type="text" name="capcha" class="form-control" autocomplete="off" placeholder="Please enter the security code">
    </div>
  <div class="col-sm-5"><span class="red-col"><?php if(isset($caperrot)){ echo $caperrot; } ?></span></div>
  </div>                
   
   <div class="form-group">
    <div class="col-sm-offset-5 col-sm-8">
      <button type="submit" class="btn btn-primary">Log in</button>
    </div>
  </div>
  </div>  
  </div>             
</form>
                  
                  