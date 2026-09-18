<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

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
//function DaysArray(n) {
//	for (var i = 1; i <= n; i++) {
//		this[i] = 31
//		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
//		if (i==2) {this[i] = 29}
//   } 
//   return this;
//}

//function daysInFebruary (year){
//	
//	// February has 29 days in any year evenly divisible by four,
//    // EXCEPT for centurial years which are not also divisible by 400.
//    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
//}


$(document).ready(function (e)
{
$("#form_emp_contact").submit(function(e) {
	
			var file=$("#order_file").val(); 
				//var exts = ['doc','docx','rtf','odt'];
		var exts = ['pdf'];
		/*if($("#create_date").val()=="")
		{
			alert("Please Enter Form Date WEF Date");
			$("#create_date").focus();
			return false;
		}
		
		else
		{
			if($("#create_date").val().length!=10){
				return false;
			}
			else
			{
				var date_val=$("#create_date").val();
				var date_arr=date_val.split("-");
				var date_arr_count=date_arr.length;
				if(date_arr_count!=3)
				{
					return false;
				}
				else
				{
					var strDay=date_arr[0];
					var strMonth=date_arr[1];
					var strYear=date_arr[2];
					var daysInMonth = DaysArray(12);
					var minYear=1900;
                     var maxYear=2100;

					
		if (strMonth.length<1 || strMonth<1 || strMonth>12){
		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || strDay<1 || strDay>31 || (strMonth==2 && strDay>daysInFebruary(strYear)) || strDay > daysInMonth[strMonth]){
		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || strYear==0 || strYear<minYear || strYear>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}		
				}

			}
		}
		
*/	

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


if( file == '')
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
    
    <div class="dashcontenr"> 
       
      <!-- <link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>-->
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
        
         <div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
		<h1 class="heading">Insert Ptax Order</h1>
        <div class="border"></div>
        </br>
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
         
        <strong style="color:#E93437;"><noscript>Please Enable Javascript in Your Browser</noscript></strong>
        <?php
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
			$msg2=$cryptography->decode($_GET['msg'],3);
			echo $msg2;
		}else{
		$msg2="";
		}
?>


        
        <!--<div id="error_file"></div>-->
        <form action="ptax_order_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" class="form-horizontal" >
        		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <!---------------------------Present Address------------>
                <? if(!empty($error_msg1)){
						echo $error_msg1;  
					}
				$db = new database();
				$ptax_order = $db->fetch_table("select * from prd_ptax_order_file 
												where active_status=1
											");
				?>
                 
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
                      <button type="submit" name="ptax_order_submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
                  </div>
                      <input type="hidden" name="ptax_id_pk" value="<?php echo $ptax_order[0]['ptax_orderfile_pk']?>" />
               
      	</form>
   <div class="clear"></div>
</div>
</div>
</div>
</div>
</div>