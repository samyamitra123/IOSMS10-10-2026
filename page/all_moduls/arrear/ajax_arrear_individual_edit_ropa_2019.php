<?php
ob_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();


require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../includes/library/cryptography.class.php';
$cryp = new cryptography();

$logged_user=$_SESSION['user_info']['stake_abbr'];

//var_dump($_GET['emp_type']); die;
if(isset($_GET['emp_type'])){
 $zp_type=$_GET['emp_type'];
 $emp_type=$cryp->decode($zp_type,4);
}
 
if($logged_user=='BDO')
{
$str=$_SESSION['location']['block_code'];
$state10=substr($str,0,4);
}
else
{
$str=0;
$state10=0;
}
 $emp_id_pk=$cryp->decode($_GET['emp_id'],4);
 $dise=$cryp->decode($_GET['gp_id'],4); 

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


function find_desig($id)
{
	$db = new database();
	$desig_fetch=$db->fetch_table("SELECT emp_desig FROM prd_employee_master WHERE emp_id_pk='".$id."'");
	return $desig_fetch[0]['emp_desig'];
}

if($logged_user=='BDO')
{
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
										gsli,
										net,
										working_days,
										arrear_to_date,
										arrear_from_date,ropa_status,
										ropa_level
										 FROM prd_employee_arrear WHERE emp_id_fk='".$emp_id_pk."' AND gp_id_fk='".$dise."' AND status_flag='1' AND delete_status='1' 
										 AND ropa_status='1' AND salary_monthyear is null

	");
}
else if($logged_user=='EO')
{
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
											gsli,
											net,
											working_days,
											arrear_to_date,
											arrear_from_date,ropa_status,
											ropa_level
										 FROM prd_employee_arrear WHERE emp_id_fk='".$emp_id_pk."' AND ps_id_fk='".$_SESSION['location']['ps_id']."' AND status_flag='1' AND delete_status='1' AND ropa_status='1' AND salary_monthyear is null

	");
}

else if($logged_user=='FC&CAO')
{

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
											gsli,
											net,
											zp_emp_type,
											working_days,
											arrear_to_date,
											arrear_from_date,ropa_status,
											ropa_level, allowance
										 FROM prd_employee_arrear WHERE emp_id_fk='".$emp_id_pk."' AND zp_emp_type='".$emp_type."' AND zp_id_fk='".$_SESSION['location']['district_id']."' AND status_flag='1' AND delete_status='1' AND ropa_status='1' AND salary_monthyear is null

	");
}

if($emp_type == '366'){ $get_column=$db->fetch_table("SELECT * from ropa_2019_ll ");	}
else{ $get_column=$db->fetch_table("SELECT * from ropa_2019 "); }

$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
						   entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
						   paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
												FROM prd_admin_paychange
												WHERE flag = 't' AND ropa_year='2019'
						");
$da_per = $paychange[0]['paychange_da']; 
$max_ma = $paychange[0]['paychange_ma'];
$hra_per = $paychange[0]['paychange_hra'];
$cpf_per = $paychange[0]['paychange_cpf'];
$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
$hill_allowance_per = $paychange[0]['hill_allowance'];


$emp_desig=find_desig($emp_id_pk);

if((($emp_desig=='9012' || $emp_desig=='9013' || $emp_desig=='9014' || $emp_desig=='9015' || $emp_desig=='9016' || $emp_desig=='9017') && $logged_user=='EO') || (($emp_desig=='1120' || $emp_desig=='1124' &&  $emp_desig!='1125') && $logged_user=='BDO') || ($emp_desig=='1'  && $logged_user=='FC&CAO'))
{
	$condition_var='readonly style="background-color:#EEE;"';
	$condition_var_dis ='disabled style="background-color:#EEE;"';
}
else
{
	$condition_var='';
	$condition_var_dis ='';
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
				//alert(data);
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
	
	function count_days(id)
	{ 	
		//alert(id);	
		var i;
		var total_row=$('#total_row').val();
		for(i=1;i<=total_row;i++)
		{  
			var oneDay = 24*60*60*1000;
			var to_date= $('#arrear_to_date'+i).val();
			var from_date= $('#arrear_fm_date'+i).val();
			var to_date_split=to_date.split('-');
			var from_date_split=from_date.split('-');
			
			var secondDate = new Date(to_date_split[2],to_date_split[1],to_date_split[0]);
			var firstDate = new Date(from_date_split[2],from_date_split[1],from_date_split[0]);
			
			
			if(firstDate>secondDate)
			{
				alert("To Date should be greater than From Date... Please choose valid date for Arrear");
				$('#arrear_to_date'+i).val("DD-MM-YYYY");
				//$('#arrear_fm_date'+i).val("DD-MM-YYYY");
				$('#working_days'+i).val("");
			}
			
		/************************************* Changed By ANJAN 23/06/2021 ***********************************/		
			/*		
			else if((from_date_split[2]!=to_date_split[2] || from_date_split[1]!= to_date_split[1]) && to_date != '')
			{
				alert("User should select same month and same year");
				$('#arrear_to_date'+i).val("");
				//$('#arrear_fm_date'+i).val("");
				$('#working_days'+i).val("");
				
			}
			/*

/***************************************** END **************************************/	

			else
			{
				var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
				if(isNaN(diffDays))
				{
					$('#working_days'+i).val(0);
				}
				else
				{
					$('#working_days'+i).val(diffDays+1);
				}
			}
		}
	}
	
	
	
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
					var gsli=$('#gsli').val();
					//var ovd=$('#overdrawn').val();
					//var festival_adv=$('#festival_adv').val();
					
					var total_deduction=parseInt(gpf)+parseInt(pf_loan)+parseInt(ptax)+parseInt(itax)+parseInt(gsli);
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
	input[type=text], textarea
	{
		padding: 2px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		border: 1px solid #3E4255;
	}
</style>
              
<div class="border_val"></div>
<form id="form_arrear_individual" method="post" action="individual_arrear_submit_ropa_2019.php" enctype="multipart/form-data" > 
<input type="hidden" name="zp_emp_type" id="zp_emp_type" value="<?php echo  $cryp->encode($emp_type,4);  ?>" >
<input type="hidden" name="sec_tok[]" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" name="basic[]" id="basic" value="<?php echo $basic; ?>" />
<input type="hidden" name="ropa_status" id="ropa_status" value="<?php echo '1'; ?>" />
    
    <div class="school">
        <div class="table-responsive">
            <table >
				<?php 
                if($logged_user=='BDO')
                {
                ?>
                    <tr>
                        <td style="text-align:left; font-weight:bold; font-size:16px;">GP NAME:</td>
                        <td>&nbsp;</td>
                        <td style="text-align:left; font-size:16px;">
                        <b><?php echo get_gp_name($dise); ?></b>
                        </td>
                    </tr>
                <?php
                }
                ?>
                
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
            <table width="120%" id="tbl1">
                <tr id="top_header">
                    <th colspan="3" align="center"></th>                     
                    <?php if($logged_user=='FC&CAO'){ ?><th colspan="10" align="center"><strong>PAY & ALLOWANCE</strong></th> <?php } else {?>
                    <th colspan="9" align="center"><strong>PAY & ALLOWANCE</strong></th> <?php } ?>
                    <th colspan="1" align="center"></th>
                    <th colspan="4" align="center"><strong>DEDUCTION</strong></th>
                    <th colspan="2">&nbsp;</th>
                </tr>
                
                <tr id="base_header">
                    <th style="width:180px;">FROM DATE</th>
                    <th style="width:180px;">TO DATE</th>
                    <th>WORKING DAYS</th>
                    <th>CONSOLIDATED<br>PAY</th>
                    <th>LEVEL</th>
                    <th>BASIC PAY</th>
                    <th>D.A(<?php echo $da_per; ?>%)</th>
                    <th>H.R.A(<?php echo $hra_per; ?>%)</th>
                    <th>M.A</th>
                    <th>CONV<br>ALLOW</th>
                 <?php   if($logged_user=='FC&CAO' && $emp_desig == '31' || $emp_desig =='32'){ ?> <th> ADMINISTRATIVE <br> ALLOWANCE</th> <?php } ?>
                    <th>HIll AllOW<span style="font-size:9px;">(min 15%)</span></th>
                    <th>Interim Relief</th>
                    <th>GROSS<br>SALARY</th>
                    <th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
                    <!--<th>PF LOAN</th>-->
                    <th>P.TAX</th>
                    <th>I.TAX</th>
                    
                     
                       <th>GSLI</th>
                     
                    <!--<th>FESTIVAL ADVANCE RECOVERY</th>-->
                    
                    <th>NET SALARY</th>
                    <th>ACTION</th>
                </tr>
                <?php $cnt=1;
                if(count($emp_arrear_fetch))
				{ 
					foreach($emp_arrear_fetch as $item)
					{?>
                    
                        <input type="hidden" id="arrear_id<?= $cnt ?>" name="arrear_id[]" value="<?php echo $cryp->encode($item['arrear_id_pk'],4);?>" />
                        <input type="hidden" id="gp" name="gp[]" value="<?php echo $item['gp_id_fk'];?>" />
                        <input type="hidden" id="emp_name" name="emp_id_fk[]" value="<?php echo $item['emp_id_fk'];?>" />
                        <input maxlength="5" type="hidden" name="pf_loan[]" id="pf_loan" autocomplete="off" value="0" size="5"  onkeypress="return keyRestrict(event,'0123456789');"   />
                        <tr style="background-color: rgb(221, 247, 255);" id="tr<?=$cnt?>">
                            <td>
                            <input type="text" class="form-control" name="arrear_fm_date[]" id="arrear_fm_date<?=$cnt ?>"  value="<?php echo dateshow($item['arrear_from_date']);?>" placeholder="DD-MM-YYYY" autocomplete="off" onChange="count_days(this.id),show_arrear();"/>
                            </td>
                            <td>
                            <input type="text" class="form-control" name="arrear_to_date[]" id="arrear_to_date<?=$cnt ?>"  value="<?php echo dateshow($item['arrear_to_date']);?>" placeholder="DD-MM-YYYY" autocomplete="off" onChange="count_days(this.id),show_arrear();"/>
                            </td>
                            <td>
                            <input type="text" class="form-control" name="working_days[]" id="working_days<?= $cnt ?>" value="<?php echo $item['working_days'];?>" readonly autocomplete="off"/>
                            </td>
                            <td > 
                            <input maxlength="6" type="text" name="consolidated_pay[]" id="consolidated_pay<?=$cnt ?>" value="<?php echo round($item['consolidated_pay']); ?>" size="6" <?php if($emp_desig!='9012' && $emp_desig!='9013' && $emp_desig!='9014' && $emp_desig!='9015' && $emp_desig!='9016' && $emp_desig!='9017'&& $emp_desig!='1120' &&  $emp_desig!='1124' &&  $emp_desig!='1125' && $emp_desig!='1'){echo 'readonly style="background-color:#EEE;"';} ?> />
                            </td>
							<!--<td><input maxlength="5"  type="text" name="grade_pay[]" id="grade_pay<?=$cnt ?>" value="<?php echo $item['ropa_level']; ?>"  size="5" <?php echo $condition_var;?> /></td>-->
							
							<td width="10%" >
								<SELECT class="form-control upper_case gp_class" name="grade_pay[]" id="grade_pay<?=$cnt ?>" <?php echo $condition_var_dis;?> >
								<option value="">--Please Select--</option>
								<? 
								foreach($get_column[0] as $key1=>$value1){
								?>
									<option value="<?=$key1 ?>" <? if($item['ropa_level']==$key1){ echo "selected";}?>><?=$key1; ?></option>
								<? } ?>
								</SELECT>
							</td>
							
                            <td>
                            <input maxlength="6"  type="text" name="pay_in_band[]" id="pay_in_band<?=$cnt ?>" value="<?php echo round($item['pay_payband']); ?>"  size="6" <?php echo $condition_var;?> />
                            </td>
                            
                            <td><input maxlength="6"  type="text" name="da[]" id="da<?=$cnt ?>" value="<?php echo round($item['da']); ?>" size="6"<?php echo $condition_var;?>  /></td>
                            <td><input maxlength="6" type="text" name="hra[]" id="hra<?=$cnt ?>"  value="<?php echo round($item['hra']); ?>"size="6" <?php echo $condition_var;?> /></td>
                            <td><input maxlength="6"  type="text" name="ma[]" id="ma<?=$cnt ?>"  value="<?php echo round($item['ma']); ?>"size="6" <?php echo $condition_var;?> /></td>
                            <td><input maxlength="6"  type="text" name="conv_allow[]" id="conv_allow<?=$cnt ?>"  value="<?php echo round($item['conv_allow']); ?>" size="6" <?php echo $condition_var;?> /></td>
							 <?php   if($logged_user=='FC&CAO' && $emp_desig == '31' || $emp_desig =='32'){ ?> <td><input maxlength="6"  type="text" name="allow[]" id="allow<?=$cnt ?>"  value="<?php echo round($item['allowance']); ?>" size="6" <?php echo $condition_var;?> /></td> <?php } ?>
                            <td><input maxlength="6"  type="text" name="hill_allow[]" id="hill_allow<?=$cnt ?>"  value="<?php echo round($item['hill_allowance']); ?>" size="6" <?php echo $condition_var;?> /></td>
                            <td><input maxlength="6"  type="text" name="interim_relief[]" id="interim_relief<?=$cnt ?>" value="<?php echo round($item['interim_relief']); ?>" size="6" <?php echo $condition_var;?>  /></td>
                            <td><input  maxlength="6" type="text"  name="gross[]" id="gross<?=$cnt ?>" value="<?php echo round($item['gross_salary']); ?>"size="6" /></td>
                            <td><input maxlength="6" type="text" name="gpf[]" id="gpf<?=$cnt ?>" autocomplete="off" value="<?php echo $item['gpf'];?>" size="6"   onkeypress="return keyRestrict(event,'0123456789');" <?php echo $condition_var;?> /></td> 
                            <td><input maxlength="6" type="text" name="p_tax[]" id="p_tax<?=$cnt ?>" autocomplete="off"  onkeypress="return keyRestrict(event,'0123456789');" value="<?php echo $item['p_tax'];?>" size="6"   /></td>
                            <td><input maxlength="6" type="text" name="i_tax[]" id="i_tax<?=$cnt ?>" autocomplete="off" value="<?php echo $item['i_tax'];?>" size="6"  onkeypress="return keyRestrict(event,'0123456789');" <?php echo $condition_var;?> /></td>
                            
                            
                          
                            
                            <td><input maxlength="4" type="text" name="gsli[]" id="gsli<?=$cnt ?>" autocomplete="off" value="<?php echo $item['gsli'];?>" size="4"  onkeypress="return keyRestrict(event,'0123456789');" <?php echo $condition_var;?> /></td>
                         
                            
                            <td><input maxlength="6" type="text"  name="net[]" id="net<?=$cnt ?>" value="<?php echo round($item['net']); ?>"size="6"  /></td>
                            <td><a id="del<?= $cnt ?>" onClick="del_arrear(this.id);"><i class="fa fa-trash fa-2x" aria-hidden="true" style="color:red"></i></a></td>
                        </tr>
                        <? $cnt+=1; 
					} 
                } 
				else 
				{ ?>
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
		$('#form_arrear_individual').submit(function(event) 
		{
			if($('#gp').val()=='')
			{
				alert('Please select GP.');
				$('#gp').focus();
				event.preventDefault();
				return false;
			}
			if($('#emp_name').val()=='')
			{
				alert('Please select employee.');
				$('#emp_name').focus();
				event.preventDefault();
				return false;
			}
			if($('#arrear_fm_date').val()=='')
			{
				alert('Please select arrear from date.');
				$('#arrear_fm_date').focus();
				event.preventDefault();
				return false;
			}
			if($('#arrear_to_date').val()=='')
			{
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
			if($('#working_days').val()=='')
			{
				alert('Please enter working days.');
				$('#working_days').focus();
				event.preventDefault();
				return false;
			}
		});
	});
</script>
