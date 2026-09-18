
<?php


ob_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
$str=$_SESSION['location']['block_code'];
$state10=substr($str,0,4);
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../includes/library/cryptography.class.php';
$cryp = new cryptography();

$logged_user=$_SESSION['user_info']['stake_abbr'];

 $emp_id_pk=$cryp->decode($_GET['emp_id'],4);
$dise=$cryp->decode($_GET['gp_id'],4);


/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);*/

$db = new database();


function get_emp_name($empid)
{
	$db=new database();
	$emp_name=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name from prd_employee_master where emp_id_pk='".$empid."'");
	$emp_full_name=$emp_name[0]['emp_first_name']." ".$emp_name[0]['emp_second_name']." ".$emp_name[0]['emp_last_name'];
	return $emp_full_name;
}

function get_gp_name($gpid)
{
	$db=new database();
	$gp=$db->fetch_table("select gp_name from prd_location_master_gp where gp_id_pk='".$gpid."'");
	$gp_name=$gp[0]['gp_name'];
	return $gp_name;
}

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

$emp_arrear_fetch=$db->fetch_table("
										SELECT
										arrear_id_pk,
										emp_id_fk,
										gp_id_fk,
										emp_id_const,
										consolidated_pay,
										pay_payband,
										grade_pay,
										basic,
										da,
										hra,
										ma,
										conv_allow,
										hill_allowance,
										interim_relief,
										gross_salary,
										gpf,
										cpf,
										pf_loan,
										p_tax,
										i_tax,
										
										net,
										working_days,
										arrear_to_date,
										arrear_from_date
										 FROM prd_employee_arrear WHERE emp_id_fk='".$emp_id_pk."' AND gp_id_fk='".$dise."' AND status_flag='1' AND delete_status='1' AND salary_monthyear is null

");

$arr_emp_id_const=$emp_arrear_fetch[0]['emp_id_const'];
/*$arr_emp_id_const=$emp_arrear_fetch[0]['emp_id_const'];
$arr_consolidated_pay=$emp_arrear_fetch[0]['consolidated_pay'];
$arr_pay_payband=$emp_arrear_fetch[0]['pay_payband'];
$arr_grade_pay=$emp_arrear_fetch[0]['grade_pay'];
$arr_basic=$emp_arrear_fetch[0]['basic'];
$arr_da=$emp_arrear_fetch[0]['da'];
$arr_hra=$emp_arrear_fetch[0]['hra'];
$arr_ma=$emp_arrear_fetch[0]['ma'];
$arr_conv_allow=$emp_arrear_fetch[0]['conv_allow'];
$arr_hill_allowance=$emp_arrear_fetch[0]['hill_allowance'];
$arr_interim_relief=$emp_arrear_fetch[0]['interim_relief'];
$arr_gross_salary=$emp_arrear_fetch[0]['gross_salary'];
$arr_gpf=$emp_arrear_fetch[0]['gpf'];
$arr_cpf=$emp_arrear_fetch[0]['cpf'];
$arr_pf_loan=$emp_arrear_fetch[0]['pf_loan'];
$arr_p_tax=$emp_arrear_fetch[0]['p_tax'];
$arr_i_tax=$emp_arrear_fetch[0]['i_tax'];
$arr_overdrawn=$emp_arrear_fetch[0]['overdrawn'];
$arr_net=$emp_arrear_fetch[0]['net'];
$arr_working_days=$emp_arrear_fetch[0]['working_days'];
$arr_arrear_to_date=$emp_arrear_fetch[0]['arrear_to_date'];
$arr_arrear_from_date=$emp_arrear_fetch[0]['arrear_from_date'];*/

$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM prd_admin_paychange
							WHERE flag = 't'
						");
$da_per = $paychange[0]['paychange_da']; 
$max_ma = $paychange[0]['paychange_ma'];
$hra_per = $paychange[0]['paychange_hra'];
$cpf_per = $paychange[0]['paychange_cpf'];
$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
$hill_allowance_per = $paychange[0]['hill_allowance'];


?>


<style>
input[type="checkbox"] {
	display:inline !important;
}
</style>

	 
    
	<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>
 
   <script>
  /*$( "#arrear_to_date2").attr('disabled','disabled');
	$( "#arrear_fm_date2").attr('disabled','disabled');
	*/
		$(document).ready(function() {
			
/*			$( "#arrear_to_date2").attr('disabled','disabled');
			$( "#arrear_fm_date2").attr('disabled','disabled');*/
			
			$( "#arrear_to_date").datepicker({
				beforeShow: function(input, inst) 
				{
					$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
					$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				maxDate: +0
				});
				
				$( "#arrear_fm_date").datepicker({
				beforeShow: function(input, inst) 
				{
					$(document).off('focusin.bs.modal');
				},
				onClose:function()
				{
					$(document).on('focusin.bs.modal');
				},
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy',
				maxDate: +0
				});	
		
		});
	
			
	</script>
 
    <script>
	
	function del_arrear(k)
	{
		var length=k.length;
		var row=k.substring(3, length);
		var arrear_id=$('#arrear_id'+row).val();
		$('#delete_arrear').modal('show');
		//$('#delete_arrear').modal({backdrop: 'static', keyboard: false})  
		$('#emp_arrear_id').val(arrear_id);
		$('#table_row_id').val(row);
		
	}
	
	function confirm_delete_arrear()
	{
		var emp_arrear_id=$('#emp_arrear_id').val();
		var table_row_id=$('#table_row_id').val();
		$('#delete_arrear').modal('hide');
		$.ajax({
		type: "POST",
		url: "ajax_arrear_delete.php",
		data:'arrear_id='+emp_arrear_id,
		success: function(data)
		{
			if(data=='1')
			{
				$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>The Arrear has been successfully deleted.</strong></div>');
				$('#tr'+table_row_id).remove();
				var last_row_id=$('#tbl1 tr:last').attr('id');
				if(last_row_id=='base_header')
				{
					location.reload();
				}
			}
			else
			{
				$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Arrear Deletion Fails.</strong></div>');
			}
		}
		});
	}
	
	
	function count_days()
	{
	
		
		var oneDay = 24*60*60*1000;
		var to_date= $('#arrear_to_date').val();
		var from_date= $('#arrear_fm_date').val();
		var to_date_split=to_date.split('-');
		var from_date_split=from_date.split('-');
		
		
		var secondDate = new Date(to_date_split[2],to_date_split[1],to_date_split[0]);
		var firstDate = new Date(from_date_split[2],from_date_split[1],from_date_split[0]);
		
		if(firstDate>secondDate)
		{
			alert("To Date should be greater than From Date... Please choose valid date for Arrear");
			$('#arrear_to_date').val("DD-MM-YYYY");
			$('#arrear_fm_date').val("DD-MM-YYYY");
			$('#working_days').val(" ");
		}
		else
		{
		
			var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
			
			
			if(isNaN(diffDays))
			{
				$('#working_days').val(0);
			}
			else
			{
				$('#working_days').val(diffDays);
			}
		}
		
		
	}
	
	
	

//
//function calculate_net()
//{
//	
//	
//		var gross=$('#gross').val();
//	
//		var gpf=$('#gpf').val();
//		var pf_loan=$('#pf_loan').val();
//		var ptax=$('#p_tax').val();
//		var itax=$('#i_tax').val();
//		var ovd=$('#overdrawn').val();
//		
//		
//
//	if(gross!="0")
//	{
//		var total_deduction=parseInt(gpf)+parseInt(pf_loan)+parseInt(ptax)+parseInt(itax)+parseInt(ovd);
//		var net=parseInt(gross)-parseInt(total_deduction);
//		if(isNaN(net))
//		{
//			$('#net').val(gross);
//		}
//		else
//		{
//			$('#net').val(net);
//		}
//	}
//	else
//	{
//		alert("Invalid Insertion");
//		$('#gpf').val("");
//		$('#pf_loan').val("");
//		$('#p_tax').val("");
//		$('#i_tax').val("");
//		$('#overdrawn').val("");
//	}
//}

function show_arrear()
{
	
			var gp_id=$('#gp').val();
			var emp_id=$('#emp_name').val();
			var arrear_to_date=$('#arrear_to_date').val();
			var arrear_fm_date=$('#arrear_fm_date').val();
		
			if(gp_id!="" && emp_id!="" && arrear_to_date!="" && arrear_fm_date!="")
			{
				 
				 $.ajax({
					url:'get_employee_total_arrear.php',
					type:'POST',
					data:{
						gp_id:gp_id,
						emp_id:emp_id,
						arrear_to_date:arrear_to_date,
						arrear_fm_date:arrear_fm_date
					},
					success:function(data){
						
						//alert(data);
						var result = $.parseJSON(data);
						$('#working_days').val(result[0]);
						$('#pay_in_band').val(result[1]);
						$('#grade_pay').val(result[2]);
						$('#da').val(result[3]);
						$('#hra').val(result[4]);
						$('#ma').val(result[5]);
						$('#conv_allow').val(result[6]);
						$('#hill_allow').val(result[7]);
						$('#interim_relief').val(result[8]);
						$('#gross').val(result[9]);
						$('#net').val(result[9]);						
						$('#consolidated_pay').val(result[10]);
						$('#basic').val(result[11]);
						
						/*$('#gpf').removeAttr('style');
						$('#gpf').removeAttr('readonly');
						$('#pf_loan').removeAttr('style');
						$('#pf_loan').removeAttr('readonly');
						$('#p_tax').removeAttr('style');
						$('#p_tax').removeAttr('readonly');
						$('#i_tax').removeAttr('style');
						$('#i_tax').removeAttr('readonly');
						$('#overdrawn').removeAttr('style');
						$('#overdrawn').removeAttr('readonly');*/
						
						var gross=$('#gross').val();

						var gpf=$('#gpf').val();
						var pf_loan=$('#pf_loan').val();
						var ptax=$('#p_tax').val();
						var itax=$('#i_tax').val();
						//var ovd=$('#overdrawn').val();
						
						var total_deduction=parseInt(gpf)+parseInt(pf_loan)+parseInt(ptax)+parseInt(itax)+parseInt(ovd);
						var net=parseInt(gross)-parseInt(total_deduction);
						if(isNaN(net))
						{
							$('#net').val(gross);
						}
						else
						{
							$('#net').val(net);
						}
					}
				});
			}
			
		
}
	

	</script>

    <style>
    
    input[type=text], textarea{
        padding: 2px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #3E4255;
        
    }
    </style>
              
				<form id="form_arrear_individual" method="post" action="individual_arrear_submit.php" enctype="multipart/form-data" > 

                <div class="school">
                <div class="table-responsive">
                    <table >
                            <tr>
                                <td style="text-align:left; font-weight:bold; font-size:16px;">GP NAME:</td>
                                <td>&nbsp;</td>
                                <td style="text-align:left; font-size:16px;">
                                <b><?php echo get_gp_name($dise); ?></b>
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="text-align:left; font-weight:bold; font-size:16px;">EMPLOYEE NAME:</td>
                                <td>&nbsp;</td>
                                <td style="text-align:left; font-size:16px;"> 
                                <b><?php echo get_emp_name($emp_id_pk); ?></b>
                                </td>
                            </tr>
                            
                           
                            
                    </table>
               </div>
               </div>
               <div class="school">
				 <div class="table-responsive">
                <table width="100%" id="tbl1">
                	<tr id="top_header">
                    	<th colspan="3" align="center"></th>                     
                    	<th colspan="9" align="center"><strong>PAY & ALLOWANCE</strong></th>
                        <th colspan="1" align="center"></th>
                        <th colspan="4" align="center"><strong>DEDUCTION</strong></th>
                        <th>&nbsp;</th>
                    </tr>
					
					<tr id="base_header">
                    	<th style="width:180px;">FROM DATE</th>
                        <th style="width:180px;">TO DATE</th>
                         <th>WORKING DAYS</th>
                         <th>CONSOLIDATED<br>PAY</th>
						<th>PAY IN <br>PAY BAND</th>
						<th>GRADE<br>PAY</th>
						<th>D.A(<?php echo $da_per; ?>%)</th>
						<th>H.R.A(<?php echo $hra_per; ?>%)</th>
						<th>M.A</th>
						<th>CONV<br>ALLOW</th>
                         <th>HIll AllOW<span  style="font-size:9px;">(min 15%)</span></th>
                         <th>Interim Relief</th>
						<th>GROSS<br>SALARY</th>
						<th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
						<th>PF LOAN</th>
						<th>P.TAX</th>
						<th>I.TAX</th>
                       
						<th>NET SALARY</th>
					</tr>
                    <?php $cnt=1;
if(count($emp_arrear_fetch)){ foreach($emp_arrear_fetch as $item){ ?>
<input type="hidden" id="arrear_id<?= $cnt ?>" name="arrear_id[]" value="<?php echo $item['arrear_id_pk'];?>" />
<input type="hidden" id="gp" name="gp[]" value="<?php echo $item['gp_id_fk'];?>" />
<input type="hidden" id="emp_name" name="emp_id_fk[]" value="<?php echo $item['emp_id_fk'];?>" />
					<tr style="background-color: rgb(221, 247, 255);" id="tr<?=$cnt?>">
                        <td>
                        	
                            <input type="text" class="form-control" name="arrear_fm_date[]" id="arrear_fm_date" value="<?php echo dateshow($item['arrear_from_date']);?>" placeholder="DD-MM-YYYY" autocomplete="off" onChange="count_days(this.id),show_arrear();"/>
                        </td>
                        <td>
                        	<input type="text" class="form-control" name="arrear_to_date[]" id="arrear_to_date" value="<?php echo dateshow($item['arrear_to_date']);?>" placeholder="DD-MM-YYYY" autocomplete="off" onChange="count_days(this.id),show_arrear();"/>
                        </td>
                        <td>
                        	<input type="text" class="form-control" name="working_days[]" id="working_days" value="<?php echo $item['working_days'];?>" readonly autocomplete="off"/>
                        </td>
                        <td> 
                        	<input maxlength="5"  type="text" name="consolidated_pay[]" id="consolidated_pay" value="<?php echo round($item['consolidated_pay']); ?>" size="5" />
                        </td>
			<td>
                            <input type="hidden" name="sec_tok[]" id="sec_tok" value="<?=$enc_token?>" />
                            <input type="hidden" name="basic[]" id="basic" value="<?php echo $basic; ?>" />
                            <input maxlength="5"  type="text" name="pay_in_band[]" id="pay_in_band" value="<?php echo round($item['pay_payband']); ?>"  size="5" />
                        
                        </td>
                        
						<td><input maxlength="5"  type="text" name="grade_pay[]" id="grade_pay" value="<?php echo round($item['grade_pay']); ?>"  size="5" /></td>
						<td><input maxlength="5"  type="text" name="da[]" id="da" value="<?php echo round($item['da']); ?>" size="5"  /></td>
						<td><input maxlength="5" type="text" name="hra[]" id="hra"  value="<?php echo round($item['hra']); ?>"size="5" /></td>
						<td><input maxlength="5"  type="text" name="ma[]" id="ma"  value="<?php echo round($item['ma']); ?>"size="5" /></td>
						<td><input maxlength="5"  type="text" name="conv_allow[]" id="conv_allow"  value="<?php echo round($item['conv_allow']); ?>"size="5" /></td>
                        <td><input maxlength="5"  type="text" name="hill_allow[]" id="hill_allow"  value="<?php echo round($item['hill_allowance']); ?>"size="5" /></td>
                        
                         <td><input maxlength="5"  type="text" name="interim_relief[]" id="interim_relief" value="<?php echo round($item['interim_relief']); ?>" size="5"/></td>
                        <td><input  maxlength="5" type="text"  name="gross[]" id="gross" value="<?php echo round($item['gross_salary']); ?>"size="5" /></td>
					
                        

                        
                        
                      <td><input maxlength="5" type="text" name="gpf[]" id="gpf" autocomplete="off" value="<?php echo $item['gpf'];?>" size="4"   onkeypress="return keyRestrict(event,'0123456789');" /></td> 
                        
                      
                        
						<td><input maxlength="5" type="text" name="pf_loan[]" id="pf_loan" autocomplete="off" value="<?php echo $item['pf_loan'];?>" size="5"  onkeypress="return keyRestrict(event,'0123456789');"/></td>
                        
                        <td><input maxlength="5" type="text" name="p_tax[]" id="p_tax" autocomplete="off"  onkeypress="return keyRestrict(event,'0123456789');" value="<?php echo $item['p_tax'];?>" size="5"   /></td>
						<td><input maxlength="5" type="text" name="i_tax[]" id="i_tax" autocomplete="off" value="<?php echo $item['i_tax'];?>" size="5"  onkeypress="return keyRestrict(event,'0123456789');" /></td>
                       

                        
						<td><input maxlength="5" type="text"  name="net[]" id="net" value="<?php echo round($item['net']); ?>"size="6"  /></td>
                       

                       
					 
                    </tr>
                    <? $cnt+=1; } 
					 } else { ?>
                    <tr>
                    <td colspan="19" style="color:red;font-weight:bold">No Data Found</td>
                    </tr>
                    <? } ?>
				</table>
                </div>
                </div>
               <input type="hidden" name="total_row" id="total_row" value="<?php echo count($emp_arrear_fetch); ?>" />
                <br>
				<center><input type="submit" class="btn btn-info" id="submit" value="Submit" ></center>
            
				</div>
				</form>
		
	
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


 <script>
 
 $(document).ready(function(e) {
	$('#form_arrear_individual').submit(function(event) {
		
				if($('#gp').val()==''){
					alert('Please select GP.');
					$('#gp').focus();
					event.preventDefault();
					return false;
				}
				if($('#emp_name').val()==''){
					alert('Please select employee.');
					$('#emp_name').focus();
					event.preventDefault();
					return false;
				}
				
				if($('#arrear_fm_date').val()==''){
					alert('Please select arrear from date.');
					$('#arrear_fm_date').focus();
					event.preventDefault();
					return false;
				}
				
				if($('#arrear_to_date').val()==''){
					alert('Please select arrear to date.');
					$('#arrear_to_date').focus();
					event.preventDefault();
					return false;
				}
				
				var from_date= $('#arrear_fm_date').val();
				var to_date= $('#arrear_to_date').val();
	
				var to_date_split=to_date.split('-');
				var from_date_split=from_date.split('-');
				
				var firstDate = new Date(from_date_split[2],from_date_split[1],from_date_split[0]);
				var secondDate = new Date(to_date_split[2],to_date_split[1],to_date_split[0]);
		
				if(firstDate>secondDate)
				{
					alert("To Date should be greater than From Date... Please choose valid date for Arrear");
					$('#arrear_fm_date').val("DD-MM-YYYY");
					$('#arrear_to_date').val("DD-MM-YYYY");
					$('#working_days').val(" ");
					event.preventDefault();
					return false;
				}
				
				if($('#working_days').val()==''){
					alert('Please enter working days.');
					$('#working_days').focus();
					event.preventDefault();
					return false;
				}
	
		
		});
 });

 
 </script>
   
	
    