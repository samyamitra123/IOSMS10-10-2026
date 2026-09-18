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
//$dise=$cryp->decode($_GET['gp_id'],4);
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



if($logged_user=='zpdaa')
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
								  fad_details.festival_advance_instalment_last_amount
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."' AND emp.zp_id_fk = '".$_SESSION['location']['district_id']."' 
								AND emp.emp_status=1 AND substr(fad.fad_monthyear,1,4)='".date('Y')."'
								ORDER BY 
								emp.emp_first_name
									");
}
else if($logged_user=='DA')
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
								  fad_details.festival_advance_instalment_last_amount
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."' AND emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' 
								AND emp.emp_status=1 AND substr(fad.fad_monthyear,1,4)='".date('Y')."'
								ORDER BY 
								emp.emp_first_name
									");
}
else if($logged_user=='GP')
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
								  fad_details.festival_advance_instalment_last_amount
								FROM prd_employee_master emp 
								INNER JOIN prd_festival_advance_employee_details as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								INNER JOIN prd_festival_advance_entry_sal fad_details
								ON fad.festival_advance_id_pk=fad_details.festival_advance_id_fk
								WHERE emp.emp_id_pk='".$emp_id_pk."' AND emp.gp_id_fk = '".$_SESSION['location']['gp_id']."' 
								AND emp.emp_status=1 AND substr(fad.fad_monthyear,1,4)='".date('Y')."'
								ORDER BY 
								emp.emp_first_name
									");
}

$fad_min_max_amount_fetch=$db->fetch_table(" SELECT * FROM prd_master_bonus WHERE id_pk='2' ");

$fad_min_amount=$fad_min_max_amount_fetch[0]['lower_limit'];
$fad_max_amount=$fad_min_max_amount_fetch[0]['upper_limit'];

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
		var total_fad_amt=$('#festival_adv'+row_id).val();
		if(total_fad_amt>14000)
		{
			alert("Wrong amount inserted");
			$('#festival_adv'+row_id).val(0);
			$('#instl_amount_span'+row_id).html(0);
		}
		else
		{
			if(total_fad_amt!='' && instl_no!="")
			{
				instl_amount_per_month=Math.floor(total_fad_amt/instl_no);
				instl_amount_last_month=instl_amount_per_month+Math.floor(total_fad_amt%instl_no);
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
	}
	
	
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
              
<form id="form_arrear" method="post" action="ll_ajax_fad_submit.php" enctype="multipart/form-data" > 
    <input type="hidden" name="gp_id_fk" id="gp_id_fk" value="<?php echo $_SESSION['location']['gp_id']; ?>" >
    <div class="school">
		<div class="table-responsive">
            <table width="100%" id="tbl1">
                <tr id="base_header">
                <th>NAME OF EMPLOYEE</th>
                <th>FESTIVAL ADVANCE AMOUNT</th>
                <th>TOTAL INSTALMENT NUMBER</th>
                <th style="width:25%;">INSTALMENT AMOUNT</th>
                </tr>
                
                <tr style="background-color: rgb(221, 247, 255);" id="tr1">
                    <td> 
                        <SELECT class="form-control upper_case emp_class" name="emp_name[]" id="emp_name1" readonly>
                        <option value="<?=$arr_emp_name[0]['emp_id_pk']; ?>"><?= $arr_emp_name[0]['emp_first_name']." ".$arr_emp_name[0]['emp_second_name']." ".$arr_emp_name[0]['emp_last_name']; ?></option>
                        </SELECT>
                    </td>
                     <td>
                    	<input maxlength="5"  type="text" name="festival_adv[]" id="festival_adv1" autocomplete="off" value="<?php echo $arr_emp_name[0]['festival_advance_total_amount'];?>" size="5" onkeypress="return key_restrict(event);" onKeyUp="calculate_instl_amount(this.id,12);" autocomplete="off" />
                    </td>
                    <td>
                    	<SELECT class="form-control upper_case emp_class" name="instl_no[]" id="instl_no1" onChange="calculate_instl_amount(this.id,8);">
                        	<option value="">--Please Select--</option>
						<? for($i=1;$i<11;$i++)
						{  ?>
                        	<option value="<?=$i; ?>" <?php if($arr_emp_name[0]['festival_advance_instalment_no']==$i){ echo "selected";}?>><?= $i; ?></option>
                        <? }  ?>
                        </SELECT>
                    </td>
                    <td>
                    	<span id="instl_amount_span1">
                        <?php if($arr_emp_name[0]['festival_advance_instalment_amount']==$arr_emp_name[0]['festival_advance_instalment_last_amount'])
                        {
                            echo $arr_emp_name[0]['festival_advance_instalment_amount'];
                        }
                        else
                        {
                            echo $arr_emp_name[0]['festival_advance_instalment_amount']." (Last month payable : ".$arr_emp_name[0]['festival_advance_instalment_last_amount'].")";
                        } ?>
                        </span>
                        <input type="hidden" name="instl_amount[]" id="instl_amount1" value="<?php echo $arr_emp_name[0]['festival_advance_instalment_amount']; ?>" >
                        <input type="hidden" name="instl_amount_last[]" id="instl_amount_last1" value="<?php echo $arr_emp_name[0]['festival_advance_instalment_last_amount']; ?>">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <input type="hidden" name="total_row" id="total_row" />
    <br>
    <center><input type="submit" class="btn btn-info" id="submit_arrear" value="Submit" ></center>
    
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
					var employee=$('#emp_name'+new_id).val();
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
    