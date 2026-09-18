<?php

ob_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../includes/library/cryptography.class.php';
$cryp = new cryptography();

$emp_id_pk=$cryp->decode($_GET['enc_emp_id'],4);
$bill_id=$cryp->decode($_GET['enc_bill_id'],4);
$dise=$cryp->decode($_GET['gp_id'],4);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


$db = new database();

function fun_grade_pay($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
	return $dist_data2[0]['grade_amount'];
}

function count_total_month($from, $to) 
{
    $month_in_year = 12;
    $date_from = getdate(strtotime($from));
    $date_to = getdate(strtotime($to));
    return ($date_to['year'] - $date_from['year']) * $month_in_year -
        ($month_in_year - $date_to['mon']) +
        ($month_in_year - $date_from['mon']);
}



//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($logged_user=='zpacc')
{
	$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									fad.festival_advance_total_amount,
									fad_details.festival_advance_instalment_no,
								  fad_details.festival_advance_instalment_amount,
								  fad_details.festival_advance_instalment_last_amount,
								  fad_details.deduction_end_monthyear,
								  fad_details.total_amt_given,
								  fad_details.deduction_counter
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."' AND emp.zp_id_fk = '".$_SESSION['location']['district_id']."' 
								AND emp.emp_status=1 AND fad.bill_id_fk='".$bill_id."'
								ORDER BY 
								emp.emp_first_name
									");
	
	$emp_fad_deduction_check=$db->fetch_table(" SELECT net FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."' AND zp_id_fk = '".$_SESSION['location']['district_id']."' AND salary_monthyear='".date('Ym')."' AND delete_status='1' AND status_flag='3' AND is_saved='1'");								
	
}
else if($logged_user=='EO')
{
	$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									fad.festival_advance_total_amount,
									fad_details.festival_advance_instalment_no,
								  fad_details.festival_advance_instalment_amount,
								  fad_details.festival_advance_instalment_last_amount,
								  fad_details.deduction_end_monthyear,
								  fad_details.total_amt_given,
								  fad_details.deduction_counter
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."' AND emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' 
								AND emp.emp_status=1 AND fad.bill_id_fk='".$bill_id."'
								ORDER BY 
								emp.emp_first_name
									");
									
	$emp_fad_deduction_check=$db->fetch_table(" SELECT net FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."' AND ps_id_fk = '".$_SESSION['location']['ps_id']."' AND salary_monthyear='".date('Ym')."' AND delete_status='1' AND status_flag='3' AND is_saved='1'");	

}
else if($logged_user=='BDO')
{
	$gp_id=$cryp->decode($_GET['enc_gp_id'],4);
	$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									fad.festival_advance_total_amount,
									fad.fad_monthyear,
									fad_details.festival_advance_instalment_no,
								  fad_details.festival_advance_instalment_amount,
								  fad_details.festival_advance_instalment_last_amount,
								  fad_details.deduction_end_monthyear,
								  fad_details.total_amt_given,
								  fad_details.deduction_counter
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."'
								AND emp.emp_status=1 AND fad.bill_id_fk='".$bill_id."'
								AND emp.gp_id_fk='".$gp_id."'
								ORDER BY 
								emp.emp_first_name
									");
									
	$emp_fad_deduction_check=$db->fetch_table(" SELECT net FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."' AND gp_id_fk='".$gp_id."' AND salary_monthyear='".date('Ym')."' AND delete_status='1' AND status_flag='3' AND is_saved='1'");
}


$fad_min_max_amount_fetch=$db->fetch_table(" SELECT * FROM prd_master_bonus WHERE id_pk='2' ");

$fad_min_amount=$fad_min_max_amount_fetch[0]['lower_limit'];
$fad_max_amount=$fad_min_max_amount_fetch[0]['upper_limit'];

$current_month_date=date('Y-m-d');
$deduction_end_month_date=substr($arr_emp_name[0]['deduction_end_monthyear'],0,4).'-'.substr($arr_emp_name[0]['deduction_end_monthyear'],4,2).'-'.date('d');

$rest_months=count_total_month($current_month_date,$deduction_end_month_date);

if($rest_months=='10')
{
	$months_pending=$rest_months;
}
else if($rest_months<'10')
{
	if(count($emp_fad_deduction_check)>0)
	{
		$months_pending=$rest_months;
	}
	else
	{
		$months_pending=$rest_months+1;
	}
}

$paychange = $db->fetch_table("
								SELECT paychange_da
								FROM prd_admin_paychange
								WHERE flag = 'TRUE';
							");
						
if(count($paychange)>0)
{			
	$da_per = $paychange[0]['paychange_da'];
}
else
{
	$da_per = 0;
}

?>


<style>
	input[type="checkbox"] 
	{
		display:inline !important;
	}
</style>

<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>


<script>
	
	function show_employee(k)
	{
		var row_id_length=k.length;
		var row_id=k.substr(2,row_id_length);
		var gp_id=$('#'+k).val();
		$.ajax({
		type: "POST",
		url: "get_employee.php",
		data:'gp_id='+gp_id,
		success: function(data)
		{
		//alert(data);
		$("#emp_name"+row_id).html(data);
		show_datepicker(k,1);
		}
		});	
	}
	
	function calculate_instl_amount(k,l)
	{
		var row_id_length=k.length;
		var row_id=k.substr(l,row_id_length);
		var instl_no=$('#instl_no'+row_id).val();
		var total_rem_amt=$('#festival_adv_rem'+row_id).val();
		
		if(total_rem_amt!='' && instl_no!="")
		{
			instl_amount_per_month=Math.floor(total_rem_amt/instl_no);
			instl_amount_last_month=instl_amount_per_month+Math.floor(total_rem_amt%instl_no);
			if(instl_amount_per_month==instl_amount_last_month)
			{
				$('#instl_amount_span'+row_id).html(instl_amount_per_month);
			}
			else
			{
				$('#instl_amount_span'+row_id).html(instl_amount_per_month+" (Last month payable : "+instl_amount_last_month+")");
			}
			$('#instl_amount'+row_id).val(instl_amount_per_month);
			$('#instl_amount_last'+row_id).val(instl_amount_last_month);
		}
		else
		{
			$('#instl_amount_span'+row_id).html(0);
		}
	}
	
$('#submit_fad').click(function(e) 
{
		var fad_year=$('#fad_yr').val();
		var bill_id='<?php echo $bill_id;?>';
		if($('#instl_no1').val()==''){
			alert('Please select instalment number.');
			$('#reason_id').focus();
			return false;			
		}
		else
		{
			$('#wait').modal('show');
			var link1=$(this).val();
			$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_fad_submit.php',$(this).closest("form").serialize(), function(data){
				$('#wait').modal('hide');
				$('#msg_fad').html(data);
				$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_fad_bill_details_show.php?year='+fad_year+'&bill_id='+bill_id, function(data){
				$("#bonus_table").html(data);
				});	 
			});
		}
	});	
	
</script>

<style>

	input[type=text], textarea
	{
		padding: 2px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		border: 1px solid #3E4255;
	}
</style>
 <input type="hidden" name="fad_yr" id="fad_yr" value="<?=substr($arr_emp_name[0]['fad_monthyear'],0,4); ?>" />            
<form id="form_fad" > 
    <input type="hidden" name="gp_id_fk" id="gp_id_fk" value="<?php echo $dise; ?>" >
    <div class="school">
    <div id="msg_fad"></div>
		<div class="table-responsive">
            <table width="100%" id="tbl1">
                <tr id="base_header">
                <!--<th>NAME OF GP</th>-->
                <th>NAME OF EMPLOYEE</th>
                <th>FESTIVAL ADVANCE AMOUNT</th>
                <th>REMAINING AMOUNT</th>
                <th>TOTAL INSTALMENT NUMBER</th>
                <th style="width:25%;">INSTALMENT AMOUNT</th>
                </tr>
                
                <tr style="background-color: rgb(221, 247, 255);" id="tr1">
                    <!--<td>
                        <SELECT class="form-control upper_case gp_class" name="gp[]" id="gp1" onChange="show_employee(this.id),show_datepicker(this.id,1),show_arrear(this.id,2);">
                        <option value="">----Please Select-----</option>
                        <? 
                        foreach($arr_gp_name as $key){
                        ?>
                        <option value="<?=$key['gp_id_pk'] ?>" ><?=$key['gp_name']; ?></option>
                        <? } ?>
                        </SELECT>
                    </td>-->
                    <td> 
                        <SELECT class="form-control upper_case emp_class" name="emp_name" id="emp_name1" readonly>
                        <option value="<?=$arr_emp_name[0]['emp_id_pk']; ?>"><?= $arr_emp_name[0]['emp_first_name']." ".$arr_emp_name[0]['emp_second_name']." ".$arr_emp_name[0]['emp_last_name']; ?></option>
                        </SELECT>
                    </td>
                     <td>
                    	<input maxlength="4"  type="text" name="festival_adv" id="festival_adv1" disabled value="<?php echo $arr_emp_name[0]['festival_advance_total_amount'];?>" size="5" onkeypress="return key_restrict(event);"  autocomplete="off" />
                    </td>
                    <td>
                    	<input maxlength="4"  type="text" name="festival_adv_rem" id="festival_adv_rem1" disabled value="<?php echo ($arr_emp_name[0]['festival_advance_total_amount']-$arr_emp_name[0]['total_amt_given']);?>" size="5" onkeypress="return key_restrict(event);"  autocomplete="off" />
                    </td>
                    <td>
                    	<SELECT class="form-control upper_case emp_class" name="instl_no" id="instl_no1" onChange="calculate_instl_amount(this.id,8);">
                        	<option value="">--Please Select--</option>
						<? for($i=1;$i<=$months_pending;$i++)
						{  ?>
                        	<option value="<?=$i; ?>"><?= $i; ?></option>
                        <? }  ?>
                        </SELECT>
                    </td>
                    <td>
                    	<span id="instl_amount_span1">0
                        </span>
                        <input type="hidden" name="instl_amount" id="instl_amount1" / >
                        <input type="hidden" name="instl_amount_last" id="instl_amount_last1" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <input type="hidden" name="bill_id" id="bill_id" value="<?php echo $bill_id;?>" />
    <br>
    <center><input type="button" class="btn btn-info" id="submit_fad" value="Submit" ></center>
    
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
	.school table th
	{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
	.school table
	{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
	.school
	{
		background-color: #FFFFFF;
		border-radius: 8px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
		padding: 10px;
	}
	.school .title h2
	{
		color: #FFF;
		text-align: center;
		padding: 0px;
		margin: 0px;
		background-color: #0D8BBD;
		border-radius: 8px;
		-moz-border-radius: 8px;
	}
	.school .action .ui-widget
	{
		font-size: 11px;
	}
	.school .action
	{
		text-align: center;
	}
	.school .action .ui-button .ui-button-text
	{
		padding: 5px 10px;
	}

</style>
   
<script>
	$(document).ready(function(e) 
	{
		$('#form_arrear').submit(function(event) 
		{
			var last_row_id=$('#tbl1 tr:last').attr('id');
			var last_row_id_len=last_row_id.length;
			var last_row = parseInt(last_row_id.substring(2,last_row_id_len));
			$('#total_row').val(last_row);
			
			var emparray = new Array();
			$('#tbl1 tr').each(function() 
			{
				if($(this).attr('id')!='top_header' && $(this).attr('id')!='base_header')
				{
					var id = $(this).attr('id');
					var len=$(this).attr('id').length;
					var new_id = id.substring(2,len); 
					var gp=$('#gp'+new_id).val();
					var employee=$('#emp_name'+new_id).val();
					if($('#gp'+new_id).val()=='')
					{
						alert('Please select GP.');
						$('#gp'+new_id).focus();
						event.preventDefault();
						return false;
					}
					if($('#emp_name'+new_id).val()=='')
					{
						alert('Please select employee.');
						$('#emp_name'+new_id).focus();
						event.preventDefault();
						return false;
					}
					if($('#festival_adv'+new_id).val()=='')
					{
						alert('Please Enter Festival Advance Amount.');
						$('#emp_name'+new_id).focus();
						event.preventDefault();
						return false;
					}
					
				}
				emparray.push($('#emp_name'+new_id).val());
			});
			
			
			var sorted_arr = emparray.sort(); 
			var results = [];
			for (var i = 0; i < emparray.length - 1; i++) 
			{
				if (sorted_arr[i + 1] == sorted_arr[i]) 
				{
					results.push(sorted_arr[i]);
				}
			}
			if(results!="")
			{
			alert("Festival Advance Can Not be Submitted Due To Duplicate Entry");
			return false;
			}
			else
			{
				return true;
			}
		});
	});
	
	function key_restrict(e1)
	{
		return keyRestrict(e1,'0123456789');
	}
</script>
    
    
<div class="modal fade bs-example-modal-lg" id="wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-body"> 
		<img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />
      </div>
  </div>
</div>