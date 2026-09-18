<?php
//session_start();

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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
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
		
/////////////////Date validation////////////////////////////////////	
var inputText= document.getElementById('create_date');
var dateformat = /^(((((0[1-9])|(1\d)|(2[0-8]))-((0[1-9])|(1[0-2])))|((31-((0[13578])|(1[02])))|((29|30)-((0[1,3-9])|(1[0-2])))))-((20[0-9][0-9]))|(29-02-20(([02468][048])|([13579][26]))))$/;
if(inputText.value=='' || inputText.value=='NULL'){
	 alert('Please Insert a Valid Date.');
	 inputText.focus();
	 return false;
}else{
  // Match the date format through regular expression
  if(!inputText.value.match(dateformat))
  {
  	 alert('Please Insert a Valid Date.');
	  inputText.focus();
	  return false;
  }
}
//////////////////////Date Validation/////////////////////


/*if( file == '')
		{
						
			if($("#order_file").val() == "" )
			{
				//$("#error_file").html('File Empty'); // oreder file empty
				alert('Please Insert File');
				return false;
	        }
			else
			{
				$("#error_file").html('');
			}
			
					 	
		}*/
		if ( file ) 
		{
			var filesize=$("#order_file")[0].files[0].size;
			//alert(filesize);
        	var get_ext = file.split('.');
	        get_ext = get_ext.reverse();
	        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) == -1 )
			{
    	      	//$("#error_file").html('Sorry Invalid File Choosen'); //proper file choosen with extn
				alert("Sorry Invalid File Choosen");
				return false;
        	}
			if(filesize > 41943040)
			{
				$("#error_file").html('Sorry File is too Large'); //size check
				return false;
			}
      	}
		// validation end
		
	});
});


</script> 
<div class="content">
	<?php require '../../../common_back_btns.php'; ?>	
    <div class="welcome_msg">
      <h2>WELCOME: <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        </h2><h3>
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
      <script>
        $(function() {
			$( "input[name='activation_date']" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+100",
				dateFormat: 'dd-mm-yy' 
			});
			
        });
        </script> 
     <?php
	 	if(isset($_POST['submit']))
		{
			$month=jdmonthname(gregoriantojd($_POST['month'], 1, 1), CAL_MONTH_GREGORIAN_LONG);
			$monthno=$_POST['month'];
			$_SESSION['treasury_monthno']=$monthno;
			$_SESSION['treasury_month']=$month;
			$_SESSION['treasury_year']=$_POST['year'];
			
		}
	 ?>
          <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
		<h1 class="heading">Edit Ptax Order</h1>
        <div class="border"></div>
        </br>
        <strong style="color:#E93437;"><noscript>Please Enable Javascript in Your Browser</noscript></strong>
        <?php
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
			$msg2=$cryptography->decode($_GET['msg'],3);
			echo $msg2;
          }
			  else{
			$msg2="";
          }
?>
 <? if(!empty($error_msg1)){
              		echo $error_msg1;
                }
				$db = new database();
				if(isset($_GET['order_id']))
				{
					$order_id=$cryptography->decode($_GET['order_id'],3);
					
					$ptax_order = $db->fetch_table("select * from psemp_ptax_order_file 
													where ptax_orderfile_pk=$order_id
												");
				}
				?>
       
        <form action="ptax_order_edit_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data"  class="form-horizontal">
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
               <input type="hidden" name="ptax_id_pk" id="ptax_id_pk" value="<?=$ptax_order[0]['ptax_orderfile_pk']?>" />
               <input type="hidden" name="file_name" id="file_name" value="<?=$ptax_order[0]['file_name']?>" /> 
                
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">With Effect From<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="text" class="form-control" autocomplete="off" name="activation_date"  id="create_date" placeholder="Enter Activation Date" value="<?php echo dbdate(trim($ptax_order[0]['activation_date']));?>">
                </div>
                </div>
    
                <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="attachment" class="col-sm-3 control-label">Attached Document<span class="star_color">*</span></label>
                <div class="col-sm-3">
                <input type="file" class="form-control" autocomplete="off" name="order_file"  id="order_file" value="">
                </div>
                 <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo $ptax_order[0]['file_name'];  ?></label>
                </div>

                  <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
             <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 2MB.</span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                      <button type="submit" name="ptax_order_submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
                  </div>
      	</form>
<div class="clear"></div>
</div>
</div>
</div>
</div>
</div>