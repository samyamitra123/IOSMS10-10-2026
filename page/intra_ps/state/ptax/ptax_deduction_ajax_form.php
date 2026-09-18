<?php
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
$common['title'] = "Dashboard | eHRMS | Govt. of West Bengal ";

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
<?

?>
<?php 
	 				$db = new database();
					$ptax_deduction = $db->fetch_table("select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk from psemp_ptax_deduction as pd 
													INNER JOIN psemp_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.active_status=1 ORDER BY pd.ptax_id_pk
												");
												
											
												
				?>


<script type="text/javascript" src="themes/default/js/commonfunc.js" /></script>

<script type="text/javascript">

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
<link rel="stylesheet" href="themes/default/css/style.css" />
<!--CONTENT START-->
<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<div class="mainContent float_l" style="width:1000px">
<!-- Common Back Button --->
	<?php require '../../../common_back_btns.php'; ?>	
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
    <div class="welcome_msg">
      <h2>WELCOME:
        <?php
                      
                      if(isset($_SESSION['location']['school_name'])){
                          echo $_SESSION['location']['school_name'];
                      } elseif(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                      }
                      ?>
      </h2>
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
     
     
         <div class="page_title">
           <h2>Insert/Edit Ptax&nbsp;</h2></div>
	    <div class="border"></div>
        <br clear="all" />
        <br clear="all" />
        <div id="error_file"></div>
        <form action="page/intra_ps/state/ptax/ptax_deduction_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validContact();" >
        		
        <!---------------------------Present Address------------>
              <div id="" class="child3">
                <? if($error_msg){?>
              	<div class="float_l col_line"><?php echo $error_msg;?></div>
                    <div class="clear"></div> 
                <? }?>   
                
                                
                <div class="float_l col_line"  style="margin-left:350px"><p>Ptax Deduction(Current Use Table)</p></div>
                <div class="dashed_line" style="margin-left:290px"></div>
                    
                    <div class="float_l col3">
                     </div>
                      <table cellpadding="0" cellspacing="0">
                      <tr>
                      <td>Minimum Amount</td>
                      <td>Maximum Amount</td>
                      <td>Ptax Amount</td>
                     </tr>
                      <?php
					  for($i=0;$i<count($ptax_deduction);$i++)
					  {
					  ?>
                      <tr>
                      <td>
                    <div class="float_l col2"> 
                    <input type="text" maxlength="20"  autocomplete="off" name="max_amount[]"  id="max_amount<?php echo $i;?>" class="login-input upper_case" value="<?php if(!empty($ptax_deduction[$i]['mn_amount'])) {echo trim($ptax_deduction[$i]['mn_amount']);}else {echo $ptax_deduction[$i];} ?>" ><br />
                     </div>
                     </td>
                     <td>
                    <div class="float_l col2"> 
                    <input type="text" maxlength="20"  autocomplete="off" name="min_amount[]"  id="min_amount<?php echo $i;?>" class="login-input upper_case" value="<?php if(!empty($ptax_deduction[$i]['mx_amount'])) {echo trim($ptax_deduction[$i]['mx_amount']);}else {echo $ptax_deduction[$i];} ?>" ><br />
                     </div>
                     </td>
                     <td>
                    <div class="float_l col2"> 
                    <input type="text" maxlength="20"  autocomplete="off" name="ptax_amount[]"  id="ptax_amount<?php echo $i;?>" class="login-input upper_case" value="<?php if(isset($ptax_deduction[$i]['ptax_amount']))  echo $ptax_deduction[$i]['ptax_amount'];?>" ><br />
                    <input type="hidden" name="ptax_id[]" value="<?php if(isset($ptax_deduction[$i]['ptax_id_pk']))  echo $ptax_deduction[$i]['ptax_id_pk'];?>" />
                     </div>
                     </td>
                     </tr>
                    
                      <?php
					  }
                     ?>
                    </table>
                      <div class="clear"></div>
                    <input type="hidden" name="order_id_fk" value="<?php  echo $ptax_deduction[0]['ptax_id_pk'];?>" />
                      <input type="hidden" name="count" value="<?php echo $i;?>" />
                      <input type="submit" name="ptax_deduction_submit" value="Submit Details " class="login-submit" id="btnSubmit">
                </p>
            </div>
      	</form>
    </div>
  </div>
</div>
<br clear="all">