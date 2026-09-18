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

//$emp_id_pk=$cryp->decode($_GET['id'],4);
//$dise=$cryp->decode($_GET['gp_id'],4);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);
$yeye=(date("Y")-1).date("Y");

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

	$Query = "SELECT fadd.*,
			CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
			CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present	
			FROM prd_festival_advance_cron_hit as fadd
			LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' AND bonus_status in (3,4,5) AND zp_id_fk='".$_SESSION['location']['district_id']."') as bon 
			ON fadd.emp_id_fk=bon.emp_id_fk 
			LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status IN (1,5)) as fad 
			ON fadd.emp_id_fk=fad.emp_id_fk
			 WHERE fadd.zp_id_fk = '".$_SESSION['location']['district_id']."' 
			AND fadd.fad_year = '".date('Y')."' ORDER BY fadd.emp_first_name ";
    //print_r($Query); exit;
	$arr_emp_name=$db->fetch_table($Query);

	
	
	/*$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
									CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
								FROM prd_employee_master emp
								LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE substr(monthyear,1,4)='".date('Y')."' AND bonus_status in (3,4) AND zp_id_fk='".$_SESSION['location']['district_id']."') as bon 
								ON emp.emp_id_pk=bon.emp_id_fk 
								LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1) as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								WHERE emp.zp_id_fk = '".$_SESSION['location']['district_id']."' AND emp.emp_status=1
								ORDER BY 
								emp.emp_first_name
									"); */
}
else if($logged_user=='DA')
{
	



    $Query = "SELECT fadd.*,
		CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
		CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present	
		FROM prd_festival_advance_cron_hit as fadd
		LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' AND bonus_status in (3,4,5) AND ps_id_fk = '".$_SESSION['location']['ps_id']."') as bon 
		ON fadd.emp_id_fk=bon.emp_id_fk 
		LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status IN (1,5)) as fad 
		ON fadd.emp_id_fk=fad.emp_id_fk
		 WHERE fadd.ps_id_fk = '".$_SESSION['location']['ps_id']."' 
		AND fadd.fad_year = '".date('Y')."' ORDER BY fadd.emp_first_name ";
    //print($Query); 
	$arr_emp_name=$db->fetch_table($Query);
	
								
	/*$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
									CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
								FROM prd_employee_master emp
								LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE substr(monthyear,1,4)='".date('Y')."' AND bonus_status in (3,4) AND ps_id_fk='".$_SESSION['location']['ps_id']."') as bon 
								ON emp.emp_id_pk=bon.emp_id_fk 
								LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1) as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								WHERE emp.ps_id_fk = '".$_SESSION['location']['ps_id']."' AND emp.emp_status=1
								ORDER BY 
								emp.emp_first_name
									");*/
}
else if($logged_user=='GP')
{
    $Query ="SELECT fadd.*,
		CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
		CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present	
		FROM prd_festival_advance_cron_hit as fadd
		LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE monthyear='".$yeye."' AND bonus_status in (3,4,5) AND gp_id_fk = '".$_SESSION['location']['gp_id']."') as bon 
		ON fadd.emp_id_fk=bon.emp_id_fk 
		LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status IN (1,5)) as fad 
		ON fadd.emp_id_fk=fad.emp_id_fk
		 WHERE fadd.gp_id_fk = '".$_SESSION['location']['gp_id']."' 
		AND fadd.fad_year = '".date('Y')."' ORDER BY fadd.emp_first_name ";
	//print($Query); exit;
	$arr_emp_name=$db->fetch_table($Query);
	//print_r($arr_emp_name); exit;
								
/*
	$arr_emp_name=$db->fetch_table("SELECT 
									distinct(emp.emp_id_pk),
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_pay_in_payband,
									emp.emp_grade_pay,
									CASE WHEN (bon.emp_id_fk is not null) THEN 1 else 0 END AS bon_present,
									CASE WHEN (fad.emp_id_fk is not null) THEN 1 else 0 END AS fad_present
								FROM prd_employee_master emp
								LEFT JOIN (SELECT emp_id_fk FROM prd_employee_bonus_details WHERE substr(monthyear,1,4)='".date('Y')."' AND bonus_status in (3,4) AND gp_id_fk='".$_SESSION['location']['gp_id']."') as bon 
								ON emp.emp_id_pk=bon.emp_id_fk 
								LEFT JOIN (SELECT emp_id_fk FROM prd_festival_advance_employee_details WHERE substr(fad_monthyear,1,4)='".date('Y')."' AND festival_advance_status=1) as fad 
								ON emp.emp_id_pk=fad.emp_id_fk
								WHERE emp.gp_id_fk = '".$_SESSION['location']['gp_id']."' AND emp.emp_status=1
								ORDER BY 
								emp.emp_first_name
									");*/
									//var_dump($arr_emp_name);die;
}

//var_dump($arr_emp_name); die;
/*
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
*/
?>


<style>
	input[type="checkbox"] 
	{
		display:inline !important;
	}
</style>

<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>

<script>
	
	/*function show_employee(k)
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
	}*/
	
	function calculate_instl_amount(k,l)
	{
		
		var row_id_length=k.length;
		var row_id=k.substr(l,row_id_length);
		var instl_no=$('#instl_no'+row_id).val();
		var total_fad_amt=$('#festival_adv'+row_id).val();
		if(total_fad_amt>20000)
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
	
	function add_row(id)
	{
		var dtt = new Date(); //Grab the current Date
		dtt.setDate(1);       //Set it to the first of the month
		dtt.setHours(-1);     //Subtract an hour to yield the previous date (Last date of previous month)
		
		
		var rowCount = $('#tbl1 tr').length;
		var id_length=id.length;
		var id=id.substr(7,id_length);
       
		$("#tbl1").each(function(){
			var table = $(this);
			var n = $('tr:last td', this).length;
			var r = $('#tbl1 tr').length;
			var last_tr_id=$('#tbl1 tr:last').attr('id').substring(2,3);
			
			if('tr'+id==$('#tbl1 tr:last').attr('id'))
			{
				var tds = '<tr id="tr'+r+'">';
				tds+='<td><SELECT class="form-control upper_case emp_class" name="emp_name[]" id="emp_name'+r+'"><option value="">--Please Select--</option><?php foreach($arr_emp_name as $key){$emp_full_name=$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name']; ?> <option value="<?=$key['emp_id_fk'] ?>"<?php if($key['bon_present']=='1' || $key['fad_present']=='1'){echo 'disabled style="color:GREEN;"';}else if($key['eligibility_status'] == 0){echo 'disabled style="color:red;"';}?>><?=$emp_full_name; ?></option><?php } ?></SELECT></td>';
				
				tds+='<td><input maxlength="5" type="text" name="festival_adv[]" id="festival_adv'+r+'" onkeypress="return key_restrict(event);" onKeyUp="calculate_instl_amount(this.id,12);" autocomplete="off" value="0" size="5"  /></td>';
				tds+='<td><SELECT class="form-control upper_case emp_class" name="instl_no[]" id="instl_no'+r+'" onChange="calculate_instl_amount(this.id,8);"><option value="">--Please Select--</option><? for($i=1;$i<11;$i++){?><option value="<?=$i; ?>"><?= $i; ?></option><? }?></SELECT></td>';
				tds+='<td><span id="instl_amount_span'+r+'">0</span><input type="hidden" name="instl_amount[]" id="instl_amount'+r+'" ><input type="hidden" name="instl_amount_last[]" id="instl_amount_last'+r+'" ></td>';
				tds+=' <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row'+r+'" class="add_row3" title="Click To Add" src="../../../themes/default/image/add_row_image.png" width="20"><img onclick="return remove_row(this.id)" style="cursor:pointer;" id="remove_row'+r+'" class="remove_row3" title="Click To Remove" src="../../../themes/default/image/remove_row_image.png" width="15"></td>';
				tds += '</tr>';
				if($('tbody', this).length > 0)
				{
					n=r-1;
					$('#tbl1 tr').last().after(tds);
					$('#add_row'+n).css('display', 'none');
					$( "#tbl1 tr:odd" ).css( "background-color", "#CCE6FF" );
					$( "#tbl1 tr:even" ).css( "background-color", "#DDF7FF" );
					
					var gp_id=$('#gp'+r).val();
					var emp_id=$('#emp_name'+r).val();
					//$('tbody',this.last td).append(tds);
				}
			}
		})
	}
	
	function remove_row(id)
	{
		var last_row_id=$('#tbl1 tr:last').attr('id');
		var last_row_id_len=last_row_id.length;
		var last_row = parseInt(last_row_id.substring(2,last_row_id_len));
		
		var rowCount = $('#tbl1 tr').length;
		var tr_id=id.substr(10);
		prev_tr_id=tr_id-1;
		if(rowCount>2)
		{
			$('#tr'+tr_id).remove();
			if(last_row<=tr_id)
			{
				$('#add_row'+prev_tr_id).css('display', 'inline');
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
                <!--<th>NAME OF GP</th>-->
                <th>NAME OF EMPLOYEE</th>
                <th>FESTIVAL ADVANCE AMOUNT</th>
                <th>TOTAL INSTALMENT NUMBER</th>
                <th style="width:25%;">INSTALMENT AMOUNT</th>
                <th>ADD/REMOVE EMPLOYEE</th>
                </tr>
                
                <tr style="background-color: rgb(221, 247, 255);" id="tr1">
                    <td> 
                        <SELECT class="form-control upper_case emp_class" name="emp_name[]" id="emp_name1">
                        <option value="">--Please Select--</option>
                      
                        <? foreach($arr_emp_name as $key)
						{
							$emp_full_name=$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];
							/*
							
							$emp_basic=$key['emp_pay_in_payband']+fun_grade_pay($key['emp_grade_pay']);
							$emp_da=round(($emp_basic/100)*$da_per);
							$emp_emolument=$emp_basic+$emp_da;
							
							if($emp_emolument>$fad_min_amount && $emp_emolument<$fad_max_amount)
							{ 
								$emp_emolument_final=$emp_emolument;
							}
							else
							{ 
								$sal_archive_fetch=$db->fetch_table(" SELECT pay_payband,tch_grade_pay,da FROM prd_monthly_salary_archive_final
													WHERE emp_id_fk='".$key['emp_id_pk']."' AND delete_status='1' AND salary_monthyear='".date('Y')."03'  AND status_flag in (3,4) ");
													
								$arc_payband=$sal_archive_fetch[0]['pay_payband'];
								$arc_grade_pay=$sal_archive_fetch[0]['tch_grade_pay'];
								$arc_basic=$arc_payband+$arc_grade_pay;
								$arc_da=$sal_archive_fetch[0]['da'];
								$arc_emolument=	$arc_basic+$arc_da;
								$emp_emolument_final=$arc_emolument;
								//$emp_emolument_final= 32000;
							}*/
							//var_dump($emp_emolument_final); die;
                        ?>
                        <option value="<?=$key['emp_id_fk']; ?>" <?php if( $key['bon_present']=='1' || $key['fad_present']=='1'){echo 'disabled style="color:GREEN;"';}else if($key['eligibility_status'] == 0){echo 'disabled style="color:RED;"';}?>><?= $emp_full_name; ?></option>
                        <? }  ?>
                        </SELECT>
                    </td>
                     <td>
                    	<input maxlength="5"  type="text" name="festival_adv[]" id="festival_adv1" value="0" size="5" onkeypress="return key_restrict(event);" onKeyUp="calculate_instl_amount(this.id,12);" autocomplete="off" />
                    </td>
                    <td>
                    	<SELECT class="form-control upper_case emp_class" name="instl_no[]" id="instl_no1" onChange="calculate_instl_amount(this.id,8);">
                        	<option value="">--Please Select--</option>
						<? for($i=1;$i<11;$i++)
						{  ?>
                        	<option value="<?=$i; ?>"><?= $i; ?></option>
                        <? }  ?>
                        </SELECT>
                    </td>
                    <td>
                    	<span id="instl_amount_span1">0</span>
                        <input type="hidden" name="instl_amount[]" id="instl_amount1" >
                        <input type="hidden" name="instl_amount_last[]" id="instl_amount_last1" >
                    </td>
                    <td> 
                        <img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row1" class="add_row1" title="Click To Add" src="../../../themes/default/image/add_row_image.png" width="20">
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
    