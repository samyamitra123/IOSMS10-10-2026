<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
	require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$cryptoGraph=new cryptography();
/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
*/

$db=new database();



	//------------------------------------------------------- HEADER --------------------------------------------------------------
	require '../../page/layout/header.php';
	//---------------------------------- MENU -------------------------------------------------------------------------------------
	require '../../page/layout/menu.php';
$crypto=new cryptography();
$db = new database();

	
?>
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
    
    <div class="row" id="cont">
        <div class="content">
			<script>
            $(document).ready(function(){
				$( "tr:odd" ).css( "background-color", "#dfeaec" );
				$( "tr:even" ).css( "background-color", "#fff6" ); 
            });
            </script>
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-11  ">
                                <div class="offer offer-success">
                                    <div class="offer-content">
                                        <h1 class="heading">Transfer & Posting</h1>
                                        <div class="border"></div>
                                        </br></br>
                                        <?
                                        if(isset($_SESSION['msg']))
										{
											echo $_SESSION['msg']."<br/>";
											unset($_SESSION['msg']);
                                        }
                                        ?>
                                        
                                        <form class="form-horizontal" id="loginForm" method="post" action="ul_bonus_bill_selection_submit.php" >
                                        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                                        <div class="form-group text-center" >
                                          
                                           
                                            <a href='<?php echo $config['base_url'] ?>page/intra_pri/general_transfer.php?dis=<?php echo $crypto->encode("D",4);?>'  id="type3" name="type3"class="btn btn-info">TRANSFER OUTSIDE DISTRICT</a>
                                            <a href='<?php echo $config['base_url'] ?>page/intra_pri/general_transfer_search.php?dis=<?php echo $crypto->encode("S",4);?>'  id="type3" name="type3"class="btn btn-info">Employee Post in History Search</a>
                                            
                                            
                                           
                                            
                                            
											
                                        </div>
                                        
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
<script>

/*var k ='<?php echo $bonus_locked; ?>';

$('.bt').click(function() {
			menu_id=this.id;
			if(menu_id=='type2' && k=='TRUE')
			{
				alert("Current Bonus has been Locked. New Bonus Entry will be possible after current bonus bill generation");
				return false;
			}
		 
		})*/

	/*function valid_code()
	{
		
		if($('.bt_entry').attr('id')=='type2' && k=='TRUE')
		{
			alert("Current Bonus has been Locked. New Bonus Entry will be possible after current bonus bill generation");
			return false;
		}
	}*/
</script>


<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		border:3px solid #fff;
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #5B7778;
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
  .shape{    
    border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
	-ms-transform:rotate(360deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(360deg); /* Safari and Chrome */
	transform:rotate(360deg);
}
.offer{
	/*background:rgba(228, 232, 223, 0.59);*/
	background:rgba(243, 246, 240, 0.71); border:1px solid #ddd; box-shadow: 0 10px 20px rgba(148, 112, 29, 0.64); margin: 15px 0; overflow:hidden; margin-right:28px; padding-bottom:22px;padding-top:10px;
}

.shape {
	border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-radius{
	border-radius:7px;
}
.offer-danger {	border-color: #d9534f; }
.offer-danger .shape{
	border-color: transparent #d9534f transparent transparent;
}
.offer-success {	/*border-color: #9e9fb1;*/ }
.offer-success .shape{
	border-color: transparent #5cb85c transparent transparent;
}
.offer-default {	border-color: #999999; }
.offer-default .shape{
	border-color: transparent #999999 transparent transparent;
}
.offer-primary {	border-color: #428bca; }
.offer-primary .shape{
	border-color: transparent #428bca transparent transparent;
}
.offer-info {	border-color: #5bc0de; }
.offer-info .shape{
	border-color: transparent #5bc0de transparent transparent;
}
.offer-warning {	border-color: #f0ad4e; }
.offer-warning .shape{
	border-color: transparent #f0ad4e transparent transparent;
}

.shape-text{
	color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
	-ms-transform:rotate(30deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(30deg); /* Safari and Chrome */
	transform:rotate(30deg);
}	
.offer-content{
		padding:16px 100px 20px;
}
@media (min-width: 487px) {
  .container {
    max-width: 750px;
  }
  .col-sm-6 {
    width: 50%;
  }
}
@media (min-width: 900px) {
  .container {
    max-width: 970px;
  }

}

@media (min-width: 1200px) {
  .container {
    max-width: 1170px;
  }
  .col-lg-3 {
    width: 25%;
  }
  }


</style>



