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


/*$teacher_id_pk=isset($_GET['teacher_id_pk'])?$_GET['teacher_id_pk']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/



//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Ptax Deduction Insertion | PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>

<?php 
	 				$db = new database();
					$cryptography=new cryptography();
																	
				?>
                


<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>

<script type="text/javascript">

function validContact(){
	if(document.getElementById('create_date').value==0){
		alert("Please Enter Date.");
		document.getElementById('create_date').focus();
		return false;
	}
	
	
	<!-----------------------Permanent validation---------------------->
	
	var limit=<?php echo count($ptax_deduction);?>;
	
	for(var i=0;i<limit;i++)
	{ var max_id="max_amount"+i;
		if(document.getElementById(max_id).value==''){
		alert("Please Enter Max Amount.");
		document.getElementById(max_id).focus();
		return false;
		exit;
	}
	}
	
	for(var i=0;i<limit;i++)
	{ var min_id="min_amount"+i;
		if(document.getElementById(min_id).value==''){
		alert("Please Enter Min Amount.");
		document.getElementById(min_id).focus();
		return false;
		exit;
	}
	}
	
	for(var i=0;i<limit;i++)
	{ var ptax_id="ptax_amount"+i;
		if(document.getElementById(ptax_id).value==''){
		alert("Please Enter Ptax Amount.");
		document.getElementById(ptax_id).focus();
		return false;
		exit;
	}
	}
	return true;
}
	

$(document).ready(function (e)
{
$("#form_emp_contact").submit(function(e) {
			
		// validation end
		
	});
});


</script>
<script>
		                 
$(document).ready(function(){
   $("#create_date").change(function (){
	 
	   if($("#create_date").val() != 0){
	                    date_val = $("#create_date").val();
						var val=$(this).val();
						var arr=val.split('&');
						//alert(arr[1]);
    					$.get("ptax_deduction_ajax_form2.php?date_val="+arr[0]+'&ptax_orderfile_pk='+arr[1],function(data,status){
							//alert(data);
						$('#ajax_form').html(data);
    	});
										}
	});
	$("#msg_print").delay(5000).fadeOut(3000);
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
   
  		 <div class="row" id="cont">
        <div class="content">
              <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
        <h1 class="heading">Insert/Edit Ptax Deduction</h1>
        <div class="border"></div>
      </br>
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
			if(isset($_SESSION['msg']))
			{
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
			if(!empty($error_msg)){
				echo $error_msg;
			}?> 
        <form method="post" class="form-horizontal">
        
       
           <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Select WEF Date</label>
                <div class="col-sm-3">
                   
                    <?php
					$select_ptax_order_date=$db->fetch_table("SELECT activation_date,ptax_orderfile_pk FROM prd_ptax_order_file ORDER BY activation_date DESC");
					?> 
                    <select name="activation_date[]"  id="create_date" class="form-control">
                    <option value="0">Select Date</option>
                    <?php
					for($j=0;$j<count($select_ptax_order_date);$j++)
					{
					?>
                    <option value="<?php echo $cryptography->encode($select_ptax_order_date[$j]['activation_date'],3);?> & <?php echo $cryptography->encode($select_ptax_order_date[$j]['ptax_orderfile_pk'],3);?>"><? echo dbdate($select_ptax_order_date[$j]['activation_date']);?></option>
                    <?php
					}
					?>
                    </select>
                     </div>
					</div>   
                    </form>      
                     <br />  
                      <br />       
       
        
        
                 <div id="ajax_form" class="form-horizontal"></div>
                   
    </div>
  </div>
</div>
</div>
</div>
<div class="clear"></div>
