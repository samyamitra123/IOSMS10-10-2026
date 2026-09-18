<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
ob_start();
session_start();


include '../includes/config/config.php';
include '../includes/config/database.config.php';
include '../includes/library/database.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "index.php");
	exit;
}
	
/*if($_SERVER['HTTP_REFERER']=='' || $_SERVER['HTTP_REFERER']==NULL){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}*/



//---------------------------------- HEADER -------------------------------------------------------------------------------------
include 'layout/header.php';

?> 
<div class="content">
	<div style="height:350px; padding:0 0 0 70px;">
        <?php if($_GET['e']==1){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;" /> 
        <?php }elseif($_GET['e']==2){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;" /> 
        <?php }elseif($_GET['e']==3){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png"  style="margin-left: 29%;"/> 
        <?php }elseif($_GET['e']==4){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/> 
        <?php }elseif($_GET['e']==5){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/> 
        <?php }elseif($_GET['e']==6){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
        <?php }elseif($_GET['e']==7){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
        <?php }elseif($_GET['e']==8){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
        <?php }elseif($_GET['e']==9){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
        <?php }elseif($_GET['e']==10){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/> 
        <?php }elseif($_GET['e']==11){ ?>
            <img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
        <?php }else{?>
			<img src="<?=$config['base_url'] ?>themes/default/image/error1.png" style="margin-left: 29%;"/>
		<?php }?>
		
		<?php
		/*else{
                session_unset();
                session_destroy();
                header('Location: page/dashboard.php');
        	 }*/
		?>
        <a class="btn btn-danger btn-lg" style="margin-left: 36%;font-family: serif;" href="<?=$config['base_url'] ?>page/login.php"><i class="fa fa-chevron-left"></i> Back To Dashboard</a>
        <!--<a href="<?=$config['base_url'] ?>page/login.php" style="color:#FE3054; text-decoration:none;"><h2> <i class="fa fa-backward fa-3x"></i> Back To Dashboard</h2></a>-->
      </div> 
      <div class="clear"></div>
      </div>
      
      
<?php
//----------------------------------- FOOTER --------------------------------------------------------------------------------------
//include 'layout/footer.php';
include 'layout/footer.php';?>
 
</body>
</html>
