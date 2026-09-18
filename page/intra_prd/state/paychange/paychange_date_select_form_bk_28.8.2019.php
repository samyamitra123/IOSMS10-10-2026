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
$common['title'] = "Paychange Insertion| PRD | Govt. of West Bengal";

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
	return true;
}
	



</script>
 <script type="text/javascript">
    	$(document).ready(function(){
			//alert(111);
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );	  

		  
		});
    </script>
<script>
		                 
$(document).ready(function(){
   $("#create_date").change(function (){
	 /* alert($(this).val());*/
	 
	   if(
			    	$("#create_date").val() != 0){
						//alert($(this).val());
						var val=$(this).val();
						var arr=val.split('&');
						//alert(arr[0]);
						var paychange_id_pk=$(this).val();
	                    date_val = $("#create_date").val();
   						$.get("paychange_form.php?paychange_id_pk="+arr[0]+'&date_val='+arr[1],function(data,status){
							//alert(data);
						$('#ajax_form').html(data);
	
    					});
					}else {
					$('#ajax_form').html('');	
					}
										
										 /*if(
			    	$("#create_date").val() == 0 
			    				    	){
											alert(1);
										}*/
					});
					$("#msg_print").delay(5000).fadeOut(3000);
});
</script> 
<!--CONTENT START-->
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
           <div class="row" id="cont">
        <div class="content">
              <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
        <h1 class="heading">Insert/Edit Paychange Value</h1>
        <div class="border"></div>
        </br>
        </br>
        <?php
		if(isset($_SESSION['msg']))
		{
			echo "<p id='msg_print' style='padding-top:10px;'>".$_SESSION['msg']."</p>";
			unset($_SESSION['msg']);
		}
		
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
		$msg2=$cryptography->decode($_GET['msg'],3);
		echo $msg2;
		}
		else{
		$msg2="";
			  }
		if(isset($_GET['error_msg'])){
           	$error_msg=$cryptography->decode($_GET['error_msg'],3);
              echo $error_msg;
         }
		else{
			}
		?>          
               

        </div>
        <br clear="all" />
       
        <form method="post" class="form-horizontal">
        
       			 <div class="form-group">
                <div class="col-sm-2"></div>
                <label for="WithEffectFrom" class="col-sm-3 control-label">Select WEF Date</label>
                <div class="col-sm-3">
                <?php $select_paychange_order_date=$db->fetch_table("SELECT paychange_id_pk,paychange_fromdate FROM prd_admin_paychange ORDER BY paychange_fromdate DESC"); ?>
                	<select name="activation_date[]"  id="create_date" class="form-control">
                    <option value="0">Select Date</option>
                    <?php
					for($j=0;$j<count($select_paychange_order_date);$j++)
					{
					?>
                    <option value="<?php echo $cryptography->encode($select_paychange_order_date[$j]['paychange_id_pk'],3);?> & <?php echo $cryptography->encode($select_paychange_order_date[$j]['paychange_fromdate'],3); ?>"><?php echo dbdate($select_paychange_order_date[$j]['paychange_fromdate']);?></option>
                    <?php
					}
					?>
                    </select>
                </div>
                </div>
		</form>        
     
     
        <div id="ajax_form" class="form-horizontal"></div>
         
    </div>
  </div>
</div>
</div>

