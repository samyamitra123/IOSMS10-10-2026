<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
include_once '../../../../includes/library/database.class.php';
include_once '../../../../includes/library/cryptography.class.php';

header("X-Frame-Options: deny");
header("X-Content-Type-Options: nosniff");
//require '../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])


	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
?>

<?php
		//print_r($_REQUEST);
 		$cryptography=new cryptography(); 
		$date=$cryptography->decode($_GET['date_val'],3);
		$ptax_orderfile_pk=$cryptography->decode($_GET['ptax_orderfile_pk'],3);
		$db = new database();
		/*echo "select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk from prd_ptax_deduction as pd 
													INNER JOIN prd_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.ptax_orderfile_pk='".$ptax_orderfile_pk."' ORDER BY pd.ptax_id_pk";exit;*/
					$ptax_deduction = $db->fetch_table("select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk,pf.active_status from prd_ptax_deduction as pd 
													INNER JOIN prd_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.ptax_orderfile_pk='".$ptax_orderfile_pk."' ORDER BY pd.ptax_id_pk
												");
												
										
												
												if(count($ptax_deduction)<1)
												{
													$ptax_deduction = $db->fetch_table("select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk from prd_ptax_deduction as pd 
													INNER JOIN prd_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.active_status=0 ORDER BY pd.ptax_id_pk
												");
												
												}
												else
												{
													if($ptax_deduction[0]['active_status']==0)
													{
														$ptax_deduction = $db->fetch_table("select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk from prd_ptax_deduction as pd 
															INNER JOIN prd_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.active_status=0 ORDER BY pd.ptax_id_pk
														");
													}
													else
													{
														$ptax_deduction = $db->fetch_table("select pd.ptax_id_pk,pd.mn_amount,pd.mx_amount,pd.ptax_amount,pd.ptax_order_id_fk from prd_ptax_deduction as pd 
															INNER JOIN prd_ptax_order_file as pf ON pd.ptax_order_id_fk=pf.ptax_orderfile_pk  WHERE pf.active_status=1 ORDER BY pd.ptax_id_pk
														");
													}
												}
?>
<script type="text/javascript">

function validContact(){
	
	var limit=6;
	
	for(var i=0;i<limit;i++)
	{ var max_id="max_amount"+i;
		if(document.getElementById(max_id).value=='' || isNaN(document.getElementById(max_id).value)){
		alert("Please Enter Valid Max Amount.");
		document.getElementById(max_id).focus();
		return false;
		exit;
	}
	}
	
	for(var i=0;i<limit;i++)
	{ var min_id="min_amount"+i;
		if((document.getElementById(min_id).value=='') || isNaN(document.getElementById(min_id).value)){
		alert("Please Enter Valid Min Amount.");
		document.getElementById(min_id).focus();
		return false;
		exit;
	}
	}
	
	for(var i=0;i<limit;i++)
	{ var ptax_id="ptax_amount"+i;
		if((document.getElementById(ptax_id).value=='' ) || isNaN(document.getElementById(ptax_id).value)){
		alert("Please Enter Valid Ptax Amount.");
		document.getElementById(ptax_id).focus();
		return false;
		exit;
	}
	}
	return true;

	
}
$(document).ready(function (e)
{
	 
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
$("#form_emp_contact").submit(function(e) {
			
		// validation end
		
	});
});


</script>

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
<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
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

<?php
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
       
		
		
	 				
												
					/*$check_date=$db->fetch_table("SELECT ptax_orderfile_pk,activation_date,active_status AS max_date FROM prd_ptax_order_file WHERE activation_date=(SELECT MAX(activation_date) FROM prd_ptax_order_file) AND active_status='0'");	*/
					$check_date=$db->fetch_table("SELECT ptax_orderfile_pk,activation_date,active_status AS max_date FROM prd_ptax_order_file WHERE ptax_orderfile_pk='$ptax_orderfile_pk' AND active_status='1'");
					
					if(count($check_date)>0){					
												
				?>
        
        <form action="ptax_deduction_insert_submit.php" name="form_emp_contact" id="form_emp_contact" method="post" enctype="multipart/form-data" onsubmit="return validContact();" class="form-horizontal" >
        		
       		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
            
            
            
            <div class="emplist">
            <div class="school">
            <div class="table-responsive">
            <table width="100%" cellpadding="3" cellspacing="5">
            <tr>
            <th>Minimum Amount</th>
            <th>Maximum Amount</th>
            <th>Ptax Amount</th>
            </tr>
            
			  <?php
              //for($i=0;$i<count($ptax_deduction);$i++)
			  for($i=0;$i<count($ptax_deduction);$i++)
              {
              ?>
             <tr>
              <td>
              <div class="col-sm-3"></div>
              <div class="col-sm-8">
              <?php //echo  $ptax_deduction[$i]['mn_amount'];?>
              <input type="text" maxlength="20"  autocomplete="off" name="max_amount[]"  id="max_amount<?php echo $i;?>" class="form-control" value="<?php if($ptax_deduction[$i]['mn_amount']!='') {echo trim($ptax_deduction[$i]['mn_amount']);}else {echo $ptax_deduction[$i];} ?>" onkeypress="return keyRestrict(event,'0123456789.');" >
                </div>
                    
                     
                     </td>
                     <td>
                      <div class="col-sm-3"></div>
                   <div class="col-sm-8">
                    <input type="text" class="form-control" maxlength="20"  autocomplete="off" name="min_amount[]"  id="min_amount<?php echo $i;?>" class="login-input_paychange upper_case" value="<?php if(!empty($ptax_deduction[$i]['mx_amount'])) {echo trim($ptax_deduction[$i]['mx_amount']);}else {echo $ptax_deduction[$i];} ?>" onkeypress="return keyRestrict(event,'0123456789.');" >
                     </div>
                     </td>
                     <td>
                      <div class="col-sm-3"></div>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" maxlength="20"  autocomplete="off" name="ptax_amount[]"  id="ptax_amount<?php echo $i;?>" class="login-input_paychange upper_case" value="<?php if(isset($ptax_deduction[$i]['ptax_amount']))  echo $ptax_deduction[$i]['ptax_amount'];?>"  onkeypress="return keyRestrict(event,'0123456789.');">
                    <input type="hidden" name="ptax_id[]" value="<?php if(isset($ptax_deduction[$i]['ptax_id_pk']))  echo $ptax_deduction[$i]['ptax_id_pk'];?>" />
                     </div>
                     </td>
                      
                     </tr>
                  
                      <?php
					  }
                     ?>
                    </table>
                    
                    </div>
                    </div>
                    </div>
                    
                    
                      <input type="hidden" value="<?php echo $date;?>" name="activation_date_val" />
                    <input type="hidden" name="order_id_fk" value="<?php  echo $ptax_deduction[0]['ptax_id_pk'];?>" />
                      <input type="hidden" name="count" value="<?php echo $i;?>" />
                      <br />
                       <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                      <button type="submit" name="ptax_deduction_submit" id="btnSubmit" class="btn btn-info">Submit Details</button>
                    </div>
      	</form>
        <?php
					}
					else
					{
					?>
                    
        		
        <!---------------------------Present Address------------>
              
                                
              
              
                    
                     <div class="emplist">
                    <div class="school">
                    <div class="table-responsive">
                    <table width="100%" cellpadding="3" cellspacing="5">
                    <tr>
                    <th>Minimum Amount</th>
                    <th>Maximum Amount</th>
                    <th>Ptax Amount</th>
                    </tr>
                      <?php
					  for($i=0;$i<count($ptax_deduction);$i++)
					  {
					  ?>
                      
                      <tr>
              <td>
              <div class="col-sm-2"></div>
              <div class="col-sm-8">
               <label class="control-label" for="inputError1"> <?php if($ptax_deduction[$i]['mn_amount']!='') {echo trim($ptax_deduction[$i]['mn_amount']);}else {echo $ptax_deduction[$i];} ?></label>
                </div>
                    
                     
                     </td>
                     <td>
                      <div class="col-sm-2"></div>
                   <div class="col-sm-8">
                     <label class="control-label" for="inputError1"> <?php if($ptax_deduction[$i]['mx_amount']!='') {echo trim($ptax_deduction[$i]['mx_amount']);}else {echo $ptax_deduction[$i];} ?></label>
                     </div>
                     </td>
                     <td>
                      <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                     <label class="control-label" for="inputError1"> <?php if(isset($ptax_deduction[$i]['ptax_amount']))  echo $ptax_deduction[$i]['ptax_amount'];?></label>
                     </div>
                     </td>
                      
                     </tr>
                  
                      <?php
					  }
                     ?>
                    </table>
                    
                    </div>
                    </div>
                    </div>
                      
                      
                      <?php
					}
					  ?>
					  
</div>
                     