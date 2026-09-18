<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);


//---------------------------------- HEADER -----------------------------------------------------------------------------------
require_once '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require_once '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>
<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>

<script type="text/javascript">

	

$(document).ready(function (e)
{
$("#form_emp_contact").submit(function(e) {
	
			var file=$("#order_file").val(); 
				//var exts = ['doc','docx','rtf','odt'];
		var exts = ['pdf'];
		if($("#create_date").val()=="")
		{
			alert("Please Enter Form Date WEF Date.");
			$("#create_date").focus();
			return false;
		}

		if( file == '')
		{
						
			if($("#order_file").val() == "" )
			{
				//$("#error_file").html('File Empty'); // oreder file empty
				alert('Please Insert File.');
				return false;
	        }
			else
			{
				$("#error_file").html('');
			}
			
					 	
		}
		if ( file ) 
		{
			var filesize=$("#order_file")[0].files[0].size;
			//alert(filesize);
        	var get_ext = file.split('.');
	        get_ext = get_ext.reverse();
	        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) == -1 )
			{
    	      	//$("#error_file").html('Sorry Invalid File Choosen'); //proper file choosen with extn
				alert("Sorry Invalid File Choosen.");
				return false;
        	}
			if(filesize > 41943040)
			{
				$("#error_file").html('Sorry File is too Large.'); //size check
				return false;
			}
      	}
		// validation end
		
	});
});


</script> 
<!--CONTENT START-->
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<!-- Common Back Button --->
	<?php require_once '../../../common_back_btns.php'; ?>	
    <div class="welcome_msg">
      <h2>WELCOME: <?php echo $_SESSION['user_info']['stake_abbr']; ?>
      </h2> <h3>
					<? echo $_SESSION['location']['state_name'];
                      ?></h3>
      
    </div>
      <style>

		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		
    </style>
       
      <!-- <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>-->
      <script>
        $(function() {
			$( "input[name='activation_date']" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
			});
			        });
        </script> 
        <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
		<h1 class="heading">Insert Paychange Order</h1>
        <div class="border"></div>
        </br>
       
        <strong style="color:#E93437;"><noscript>Please Enable Javascript in Your Browser</noscript></strong>

                <? if($error_msg1){?>
              	<?php echo $error_msg1;?>
                <? }?>   
                
                         <?php
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
	$msg2=$cryptography->decode($_GET['msg'],3);
	?>
    	<?php echo $msg2;?>
<?php
          }
      else{
	$msg2="";
          }
?>
       
        <!--<div id="error_file"></div>-->
        <form class="form-horizontal" action="paychange_order_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data"  >
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
      
              

				<div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">With Effect From<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control" autocomplete="off" name="activation_date"  id="create_date" placeholder="Enter Activation Date">
                </div>
                </div>
    
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="attachment" class="col-sm-3 control-label">Attached Document<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="file" class="form-control" autocomplete="off" name="order_file"  id="order_file" value="">
                </div>
                </div>

                  <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
             <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 2MB.</span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                      <button type="submit" name="paychange_order_submit" id="btnSubmit" class="btn btn-info">SUBMIT</button>
                    </div>
                  </div>
      	</form>
    <div class="clear"></div>
</div>
</div>
</div>
</div>
</div>