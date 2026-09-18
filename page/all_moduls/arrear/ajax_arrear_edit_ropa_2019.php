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

$emp_type=$_GET['emp_type'];

if(isset($_GET['id'])){
	$emp_id_pk=$cryp->decode($_GET['id'],4);
}

if(isset($_GET['gp_id'])){
	$dise=$cryp->decode($_GET['gp_id'],4);
}


$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);


$logged_user=$_SESSION['user_info']['stake_abbr'];

$db = new database();

$arr_gp_name=$db->fetch_table("select gp_name,gp_id_pk from prd_location_master_gp gp inner join prd_location_master_block blk on gp.block_id_fk=blk.block_id_pk where blk.block_code='".$str."' order by gp_name");

$get_column=$db->fetch_table("SELECT * from ropa_2019");

//var_dump($get_column[0]); die;

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

?>


<style>
input[type="checkbox"] {
	display:inline !important;
}
</style>

<script type="application/javascript" src="<?php echo $config['base_url'] ?>themes/default/js/commonfunc.js"></script>
 
<script>
	$( "#arrear_to_date2").attr('disabled','disabled');
	$( "#arrear_fm_date2").attr('disabled','disabled');
	
	$(document).ready(function() 
	{
		var dt = new Date(); //Grab the current Date
		dt.setDate(1);       //Set it to the first of the month
		dt.setHours(-1);     //Subtract an hour to yield the previous date (Last date of previous month)
		
		$( "#arrear_to_date2").attr('disabled','disabled');
		$( "#arrear_fm_date2").attr('disabled','disabled');
		
	/*************************************** Changed By ANJAN For datepicker Start ******************************************************************/
	
		$( "#arrear_to_date2").datepicker({
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
			//yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy',
			minDate: new Date(2019, 12, 1),
			maxDate: dt
		});
		
		$( "#arrear_fm_date2").datepicker({
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
			//yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy',
			minDate: new Date(2019, 12, 1),
			maxDate: dt
		});	
	
	});


</script>

<script>

	
	function show_employee(k)
	{
		var row_id_length=k.length;
		var row_id=k.substr(2,row_id_length);
		var gp_id=$('#'+k).val();
		$.ajax({
			type: "POST",
			url: "get_employee_ropa_2019.php",
			data:'gp_id='+gp_id,
			success: function(data)
			{
				$("#emp_name"+row_id).html(data);
				show_datepicker(k,1);
			}
		});	
	}
	
	/*function show_basic(k)
	{ 
		var row_id_length=k.length;
		var row_id=k.substr(9,row_id_length);
		var level=$('#'+k).val();
		$.ajax({
			type: "POST",
			url: "get_basic_ropa_2019.php",
			data:'level='+level,
			success: function(data)
			{ 
				$("#pay_in_band"+row_id).html(data);
				//show_datepicker(k,1);
			}
		});	
	}*/
	
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
			tds+='<td><SELECT class="form-control upper_case gp_class" name="gp[]" id="gp'+r+'" onChange="show_employee(this.id),show_datepicker(this.id,1),show_arrear('+r+',0);"><option value="">--Please Select--</option><?php foreach($arr_gp_name as $key){?> <option value="<?=$key['gp_id_pk'] ?>" ><?=$key['gp_name']; ?></option><?php } ?></SELECT></td>';
			tds+='<td><SELECT class="form-control upper_case emp_class" name="emp_name[]" id="emp_name'+r+'" onChange="show_datepicker(this.id,2),show_arrear('+r+',0);cons_pay_validation(this.id);"><option value="">--Please Select--</option></SELECT></td>';
			tds+='<td><input type="text" class="form-control from_date_class" name="arrear_fm_date[]" id="arrear_fm_date'+r+'"  placeholder="DD-MM-YYYY" autocomplete="off" onChange="show_arrear('+r+',0);"/></td>';
			tds+='<td><input type="text" class="form-control to_date_class" name="arrear_to_date[]" id="arrear_to_date'+r+'"  placeholder="DD-MM-YYYY" autocomplete="off" onChange="show_arrear('+r+',0);"/></td>';
			tds+='<td><input type="text" class="form-control days_class" name="working_days[]" id="working_days'+r+'" readonly autocomplete="off"/></td>';
			tds+='<td><input maxlength="5"  type="text" name="consolidated_pay[]" id="consolidated_pay'+r+'" value="0"  size="5" onChange="show_arrear('+r+',0);" onkeypress="return key_restrict(event);" autocomplete="off"/></td>';
			tds+='<td><SELECT class="form-control upper_case gp_class" name="grade_pay[]" id="grade_pay'+r+'" ><option value="">--Please Select--</option><? foreach($get_column[0] as $key1=>$value1){?><option value="<?=$key1 ?>" ><?=$key1; ?></option><? } ?></SELECT></td>';
			tds+='<td><input type="hidden" name="basic[]" id="basic'+r+'" value=""/><input maxlength="5" type="text" name="pay_in_band[]" id="pay_in_band'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" /></td>';
			tds+='<td><input maxlength="5"  type="text" name="da[]" id="da'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off"  /></td>';
			tds+='<td><input maxlength="5"  type="text" name="hra[]" id="hra'+r+'"  value="0"size="5" onkeypress="return key_restrict(event);" autocomplete="off" /></td>';
			tds+='<td><input maxlength="5"  type="text" name="ma[]" id="ma'+r+'"  value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" /></td>';
			tds+='<td><input maxlength="5"  type="text" name="conv_allow[]" id="conv_allow'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off"/></td>';
			tds+='<td><input maxlength="5"  type="text" name="hill_allow[]" id="hill_allow'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off"/></td>';
			tds+='<td><input maxlength="5"  type="text" name="interim_relief[]" id="interim_relief'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" disabled /></td>';
			tds+='<td><input  maxlength="5" type="text"  name="gross[]" id="gross'+r+'" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off"/></td>';
			tds+='<td><input maxlength="5" type="text"  name="gpf[]" id="gpf'+r+'" onkeypress="return key_restrict(event);" autocomplete="off" value="0" size="4"  /></td>';	
			tds+=' <td><input maxlength="5" type="text"  name="p_tax[]" id="p_tax'+r+'" onkeypress="return key_restrict(event);" autocomplete="off" value="0" size="5" /></td>';
			tds+='<td><input maxlength="5" type="text"   name="i_tax[]" id="i_tax'+r+'" onkeypress="return key_restrict(event);" autocomplete="off" value="0" size="5"  /></td>';
			tds+='<td><input maxlength="4" type="text"   name="gsli[]" id="gsli'+r+'" value="0" size="4" onkeypress="return key_restrict(event);" autocomplete="off" /></td>';
			tds+='<td><input maxlength="5" type="text"  name="net[]" id="net'+r+'" value="0" size="6" onkeypress="return key_restrict(event);" autocomplete="off"/></td>';
			tds+=' <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row'+r+'" class="add_row3" title="Click To Add" src="../../../themes/default/image/add_row_image.png" width="20"><img onclick="return remove_row(this.id)" style="cursor:pointer;" id="remove_row'+r+'" class="remove_row3" title="Click To Remove" src="../../../themes/default/image/remove_row_image.png" width="15"></td>';
			tds += '</tr>';
			if($('tbody', this).length > 0)
			{
				
				$('#tbl1 tr').last().after(tds);
				$( "#tbl1 tr:odd" ).css( "background-color", "#CCE6FF" );
				$( "#tbl1 tr:even" ).css( "background-color", "#DDF7FF" );

				$( "#arrear_fm_date"+r).datepicker({
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
					//yearRange: "-100:+0",
					dateFormat: 'dd-mm-yy',
					minDate: new Date(2019, 12, 1),
					maxDate: dtt  
				});
				$( "#arrear_to_date"+r).datepicker({
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
					//yearRange: "-100:+0",
					dateFormat: 'dd-mm-yy',
					minDate: new Date(2019, 12, 1),
					maxDate: dtt  
				});
				
				var gp_id=$('#gp'+r).val();
				var emp_id=$('#emp_name'+r).val();	
				if(gp_id!="" && emp_id!="")
				{
					$( "#arrear_fm_date"+r).removeAttr('disabled','disabled');
					$( "#arrear_to_date"+r).removeAttr('disabled','disabled');
				}
				else
				{
					$( "#arrear_fm_date"+r).attr('disabled','disabled');
					$( "#arrear_to_date"+r).attr('disabled','disabled');
				}
				//$('tbody',this.last td).append(tds);
			}
		}
		})
		
		
	}
	
	
	function same_month_check(k,l)
	{
		var len=k.length;
		var row_id=k.substr(l,len);
		
		var from_date= $('#arrear_fm_date'+row_id).val();
		var arr_from=from_date.split('-');
		
		var from_month=arr_from[1];
		var from_year=arr_from[2];
		
		var to_date= $('#arrear_to_date'+row_id).val();
		var arr_to=to_date.split('-');
		
		var to_month=arr_to[1];
		var to_year=arr_to[2];
		
		if(from_date!="" && to_date!="")
		{
			if(from_year!=to_year || from_month!=to_month)
			{
				alert("User should select same month and same year");
				$('#arrear_fm_date'+row_id).val('');
				$('#arrear_to_date'+row_id).val('');
			}
			else
			{
				alert(from_year+'  '+to_year+'  '+from_month+'  '+to_month);
			}
		}
	}
	
	function remove_row(id)
	{
		var rowCount = $('#tbl1 tr').length;
		var tr_id=id.substr(10);
		if(rowCount>2)
		{
			$('#tr'+tr_id).remove();
		}
	}
	
	function show_datepicker(k,l)
	{
		var dat = new Date(); //Grab the current Date
		dat.setDate(1);       //Set it to the first of the month
		dat.setHours(-1);     //Subtract an hour to yield the previous date (Last date of previous month)
		
		var length=k.length;
		if(l==1)
		{
			var row_id=k.substr(2,length);
		}
		else
		{
			var row_id=k.substr(8,length);
		}
		
		var gp_id=$('#gp'+row_id).val();
		var emp_id=$('#emp_name'+row_id).val();	
		if(gp_id!="" && emp_id!="")
		{
			$( "#arrear_to_date"+row_id).removeAttr('disabled','disabled');
			$( "#arrear_fm_date"+row_id).removeAttr('disabled','disabled');
			$( "#arrear_to_date2").datepicker({
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
				maxDate: dat  
			});
			
			$( "#arrear_fm_date2").datepicker({
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
				maxDate: dat  
			});	
		}
		else
		{
			$( "#arrear_to_date"+row_id).attr('disabled','disabled');
			$( "#arrear_fm_date"+row_id).attr('disabled','disabled');
		}
	}
	
	
	
	
	function show_arrear(k,l)
	{
		if(l==0)
		{
			var gp_id=$('#gp'+k).val();
			var emp_id=$('#emp_name'+k).val();
			var arrear_to_date=$('#arrear_to_date'+k).val();
			var arrear_fm_date=$('#arrear_fm_date'+k).val();
			
			var h=k;
		}
		else 
		{
			var length=k.length;
			if(l==2)
			{
				var row_id=k.substr(2,length);
			}
			else if(l==8)
			{
				var row_id=k.substr(8,length);
			}
			else if(l==14)
			{
				var row_id=k.substr(14,length);
			}
			var gp_id=$('#gp'+row_id).val();
			var emp_id=$('#emp_name'+row_id).val();
			var arrear_to_date=$('#arrear_to_date'+row_id).val();
			var arrear_fm_date=$('#arrear_fm_date'+row_id).val();
			
			var h=row_id;
		}
		
		
		var arr_from=arrear_fm_date.split('-');
		
		var from_month=arr_from[1];
		var from_year=arr_from[2];
		
		
		var arr_to=arrear_to_date.split('-');
		
		var to_month=arr_to[1];
		var to_year=arr_to[2];
		
		if(arrear_fm_date!="" && arrear_to_date!="")
		{
	/************************************* Changed By ANJAN 23/06/2021 ***********************************/		
			/*	
			if(from_year!=to_year || from_month!=to_month)
			{
				alert("User should select same month and same year");
				//$('#arrear_fm_date'+h).val('');
				$('#arrear_to_date'+h).val('');
			}
			else
			{ /*

/***************************************** END **************************************/	
			
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
						$('#working_days'+h).val(result[0]);
						//								$('#pay_in_band'+h).val(result[1]);
						//								$('#grade_pay'+h).val(result[2]);
						//								$('#da'+h).val(result[3]);
						//								$('#hra'+h).val(result[4]);
						//								$('#ma'+h).val(result[5]);
						//								$('#conv_allow'+h).val(result[6]);
						//								$('#hill_allow'+h).val(result[7]);
						//								$('#interim_relief'+h).val(result[8]);
						//								$('#gross'+h).val(result[9]);
						//								$('#net'+h).val(result[9]);						
						//								$('#consolidated_pay'+h).val(result[10]);
						//								$('#basic'+h).val(result[11]);
						//								
						//								$('#gpf'+h).removeAttr('style');
						//								$('#gpf'+h).removeAttr('readonly');
						//								$('#pf_loan'+h).removeAttr('style');
						//								$('#pf_loan'+h).removeAttr('readonly');
						//								$('#p_tax'+h).removeAttr('style');
						//								$('#p_tax'+h).removeAttr('readonly');
						//								$('#i_tax'+h).removeAttr('style');
						//								$('#i_tax'+h).removeAttr('readonly');
						//								$('#overdrawn'+h).removeAttr('style');
						//								$('#overdrawn'+h).removeAttr('readonly');
						}
					});
				}
			//}
		}
	}
	
	/*function checkunique(event)
	{	
	
	
	
	}*/
	
	
	function cons_pay_validation(k)
	{
		emp_id=$('#'+k).val();
		var length=k.length;
		var row_id=k.substr(8,length);
		$.post('<?= $config['base_url'] ?>page/all_moduls/arrear/get_employee_desig.php?emp_id='+emp_id, function(data){
			
			if(data.trim()=='1120' || data.trim()=='1124' || data.trim()=='1125')
			{
				$('#pay_in_band'+row_id).prop('readonly',true);
				$('#pay_in_band'+row_id).css('background-color','#EEE');
				$('#grade_pay'+row_id).prop('disabled',true);
				$('#grade_pay'+row_id).css('background-color','#EEE');
				$('#grade_pay'+row_id).val('0');
				$('#da'+row_id).prop('readonly',true);
				$('#da'+row_id).css('background-color','#EEE');
				$('#hra'+row_id).prop('readonly',true);
				$('#hra'+row_id).css('background-color','#EEE');
				$('#ma'+row_id).prop('readonly',true);
				$('#ma'+row_id).css('background-color','#EEE');
				$('#conv_allow'+row_id).prop('readonly',true);
				$('#conv_allow'+row_id).css('background-color','#EEE');
				$('#hill_allow'+row_id).prop('readonly',true);
				$('#hill_allow'+row_id).css('background-color','#EEE');
				$('#interim_relief'+row_id).prop('readonly',true);
				$('#interim_relief'+row_id).css('background-color','#EEE');
				$('#gpf'+row_id).prop('readonly',true);
				$('#gpf'+row_id).css('background-color','#EEE');
				$('#pf_loan'+row_id).prop('readonly',true);
				$('#pf_loan'+row_id).css('background-color','#EEE');
				$('#i_tax'+row_id).prop('readonly',true);
				$('#i_tax'+row_id).css('background-color','#EEE');
				//$('#festival_adv'+row_id).prop('readonly',true);
				//$('#festival_adv'+row_id).css('background-color','#EEE');
				$('#gsli'+row_id).prop('readonly',true);
				$('#gsli'+row_id).css('background-color','#EEE');
				
				$('#consolidated_pay'+row_id).prop('readonly',false);
				$('#consolidated_pay'+row_id).css('background-color','#FFF');
			}
			else
			{
				$('#pay_in_band'+row_id).prop('readonly',false);
				$('#pay_in_band'+row_id).css('background-color','#FFF');
				$('#grade_pay'+row_id).prop('disabled',false);
				$('#grade_pay'+row_id).css('background-color','#FFF');
				$('#da'+row_id).prop('readonly',false);
				$('#da'+row_id).css('background-color','#FFF');
				$('#hra'+row_id).prop('readonly',false);
				$('#hra'+row_id).css('background-color','#FFF');
				$('#ma'+row_id).prop('readonly',false);
				$('#ma'+row_id).css('background-color','#FFF');
				$('#conv_allow'+row_id).prop('readonly',false);
				$('#conv_allow'+row_id).css('background-color','#FFF');
				$('#hill_allow'+row_id).prop('readonly',false);
				$('#hill_allow'+row_id).css('background-color','#FFF');
				$('#interim_relief'+row_id).prop('readonly',false);
				$('#interim_relief'+row_id).css('background-color','#FFF');
				$('#gpf'+row_id).prop('readonly',false);
				$('#gpf'+row_id).css('background-color','#FFF');
				$('#pf_loan'+row_id).prop('readonly',false);
				$('#pf_loan'+row_id).css('background-color','#FFF');
				$('#i_tax'+row_id).prop('readonly',false);
				$('#i_tax'+row_id).css('background-color','#FFF');
				$('#gsli'+row_id).prop('readonly',false);
				$('#gsli'+row_id).css('background-color','#FFF');
				//$('#festival_adv'+row_id).prop('readonly',false);
				//$('#festival_adv'+row_id).css('background-color','#FFF');
				
				$('#consolidated_pay'+row_id).prop('readonly',true);
				$('#consolidated_pay'+row_id).css('background-color','#EEE');
			}
			
		});
		
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
       
<form id="form_arrear" method="post" action="ajax_arrear_submit_ropa_2019.php" enctype="multipart/form-data" > 
<input type="hidden" name="zp_emp_type" id="zp_emp_type" value="<?php echo  $emp_type;  ?>" >
<input type="hidden" name="sec_tok[]" id="sec_tok2" value="<?=$enc_token?>" />
<input type="hidden" name="basic[]" id="basic2" value="<?php echo $basic; ?>" />
<input type="hidden" name="ropa_status" id="ropa_status" value="<?php echo '1'; ?>" />
    <div class="school">
        <div class="table-responsive">
            <table width="140%" id="tbl1">
                <tr id="top_header">
                    <th colspan="5">&nbsp;</th>
                    <th colspan="9" align="center"><strong>PAY & ALLOWANCE</strong></th>
                    <th colspan="1" align="center"></th>
                    <th colspan="5" align="center"><strong>DEDUCTION</strong></th>
                    <th>&nbsp;</th>
                   
                </tr>
                
                <tr id="base_header">
                    <th>NAME OF GP</th>
                    <th>NAME OF EMPLOYEE</th>
                    <th>FROM DATE</th>
                    <th>TO DATE</th>
                    <th>WORKING DAYS</th>
                    <th>CONSOLIDATED<br>PAY</th>
					<th>LEVEL</th>
                    <th>BASIC PAY</th>
                    <th>D.A(<?php echo $da_per; ?>%)</th>
                    <th>H.R.A(<?php echo $hra_per; ?>%)</th>
                    <th>M.A</th>
                    <th>CONV<br>ALLOW</th>
                    <th>HIll AllOW<span  style="font-size:9px;">(min 15%)</span></th>
                    <th>Interim Relief</th>
                    <th>GROSS<br>SALARY</th>
                    <th>GPF<br><span  style="font-size:9px;">(min 6%)</span></th>
                    <!--<th>PF LOAN</th>-->
                    <th>P.TAX</th>
                    <th>I.TAX</th>
                      <th>GSLI</th>
                    <!--<th>FESTIVAL ADVANCE RECOVERY</th>-->
                    
                    <th>NET SALARY</th>
                    <th>ADD/REMOVE EMPLOYEE</th>
                </tr>
                <tr style="background-color: rgb(221, 247, 255);" id="tr2">
                    <td>
                        <SELECT class="form-control upper_case gp_class" name="gp[]" id="gp2" onChange="show_employee(this.id),show_datepicker(this.id,1),show_arrear(this.id,2);">
                        <option value="">--Please Select--</option>
                        <? 
                        foreach($arr_gp_name as $key){
                        ?>
                        <option value="<?=$key['gp_id_pk'] ?>" ><?=$key['gp_name']; ?></option>
                        <? } ?>
                        </SELECT>
                    </td>
                    <td> 
                        <SELECT class="form-control upper_case emp_class" name="emp_name[]" id="emp_name2" onChange="show_datepicker(this.id,2),show_arrear(this.id,8);cons_pay_validation(this.id);">
                        <option value="">--Please Select--</option>
                        </SELECT>
                    </td>
                    <td>
                    	<input type="text" class="form-control from_date_class" name="arrear_fm_date[]" id="arrear_fm_date2" placeholder="DD-MM-YYYY" autocomplete="off" onChange="show_arrear(this.id,14);"/>
                    </td>
                    <td>     
                    	<input type="text" class="form-control to_date_class" name="arrear_to_date[]" id="arrear_to_date2"   placeholder="DD-MM-YYYY" autocomplete="off" onChange="show_arrear(this.id,14);"/>
                    </td>
                    <td>
                    	<input type="text" class="form-control days_class" name="working_days[]" id="working_days2" readonly autocomplete="off"/>
                    </td>
                    <td> 
                    	<input maxlength="5" type="text" name="consolidated_pay[]" id="consolidated_pay2" onkeypress="return key_restrict(event);" size="5" value="0" autocomplete="off" />
                    </td>
					
					<td>
                        <!--<SELECT class="form-control upper_case gp_class" name="grade_pay[]" id="grade_pay2" onChange="show_basic(this.id);">-->
                        <SELECT class="form-control upper_case gp_class" name="grade_pay[]" id="grade_pay2" >
                        <option value="">--Please Select--</option>
                        <? 
                        foreach($get_column[0] as $key1=>$value1){
                        ?>
							<option value="<?=$key1 ?>" ><?=$key1; ?></option>
                        <? } ?>
                        </SELECT>
                    </td>
					<!--<td>
                    	<input maxlength="5"  type="text" name="grade_pay[]" id="grade_pay2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off" />
                    </td>-->
                    <td>
                        <input maxlength="5" type="text" name="pay_in_band[]" id="pay_in_band2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off" />
                    </td>
                    
					<!--<td> 
                        <SELECT class="form-control upper_case emp_class" name="pay_in_band[]" id="pay_in_band2" >
							<option value="">--Please Select--</option>
                        </SELECT>
                    </td>-->
                    
                    <td>
                    	<input maxlength="5"  type="text" name="da[]" id="da2" onkeypress="return key_restrict(event);" size="5" value="0" autocomplete="off"  />		
                   	</td>
                    <td>
                    	<input maxlength="5" type="text" name="hra[]" id="hra2" onkeypress="return key_restrict(event);" size="5" value="0" autocomplete="off" />
                    </td>
                    <td>
                    	<input maxlength="5"  type="text" name="ma[]" id="ma2" onkeypress="return key_restrict(event);" size="5" value="0" autocomplete="off" />
                    </td>
                    <td>
                    	<input maxlength="5"  type="text" name="conv_allow[]" id="conv_allow2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off"/>
                    </td>
                    <td>
                    	<input maxlength="5"  type="text" name="hill_allow[]" id="hill_allow2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off"/>
                   </td>
                    <td>
                    	<input maxlength="5" type="text" name="interim_relief[]" id="interim_relief2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off" disabled />
					</td>
                    <td>
                    	<input  maxlength="5" type="text"  name="gross[]" id="gross2" onkeypress="return key_restrict(event);" value="0" size="5" autocomplete="off" />
                    </td>
                    <td>
                    	<input maxlength="5"  type="text" name="gpf[]" id="gpf2" autocomplete="off" value="0"size="4"   onkeypress="return key_restrict(event);" autocomplete="off" />
                    </td> 
                    
                    <td>
                    	<input maxlength="5"  type="text" name="p_tax[]" id="p_tax2" autocomplete="off"  value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" />
                    </td>
                    <td>
                    	<input maxlength="5"  type="text" name="i_tax[]" id="i_tax2" autocomplete="off" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" />
                    </td>
                    
                    <td>
                        <input maxlength="4"  type="text" name="gsli[]" id="gsli2" autocomplete="off" value="0" size="6"  onkeypress="return key_restrict(event);" autocomplete="off" />
                    </td>
                    <!--<td>
                    	<input maxlength="5"  type="text" name="festival_adv[]" id="festival_adv2" autocomplete="off" value="0" size="5" onkeypress="return key_restrict(event);" autocomplete="off" />
                    </td>-->
                    <td>
                    	<input maxlength="5" type="text"  name="net[]" id="net2" value="0" size="6" onkeypress="return key_restrict(event);" autocomplete="off"/>
                    </td>
                    <td> 
                    	<img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row2" class="add_row2" title="Click To Add" src="../../../themes/default/image/add_row_image.png" width="20">
                    </td>
                </tr>
            </table>
        </div>
    </div>
<input type="hidden" name="total_row" id="total_row" />
<br>
<center><input type="submit" class="btn btn-info" id="submit_arrear" value="Submit" ></center>
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
		var last_row = parseInt(last_row_id.substring(2,last_row_id_len))-1;
		$('#total_row').val(last_row);
		
		var emparray = new Array();
		$('#tbl1 tr').each(function() 
		{
			if($(this).attr('id')!='top_header' && $(this).attr('id')!='base_header')
			{
				var id = $(this).attr('id');
				var len=$(this).attr('id').length;
				//alert(len);
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
				if($('#arrear_fm_date'+new_id).val()=='')
				{
					alert('Please select arrear from date.');
					$('#arrear_fm_date'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if($('#arrear_to_date'+new_id).val()=='')
				{
					alert('Please select arrear to date.');
					$('#arrear_to_date'+new_id).focus();
					event.preventDefault();
					return false;
				}
				
				var from_date= $('#arrear_fm_date'+new_id).val();
				var to_date= $('#arrear_to_date'+new_id).val();
				
				var to_date_split=to_date.split('-');
				var from_date_split=from_date.split('-');
				
				var firstDate = new Date(from_date_split[2],from_date_split[1],from_date_split[0]);
				var secondDate = new Date(to_date_split[2],to_date_split[1],to_date_split[0]);
				
				if(firstDate>secondDate)
				{
					alert("To Date should be greater than From Date... Please choose valid date for Arrear");
					$('#arrear_fm_date'+new_id).val("DD-MM-YYYY");
					$('#arrear_to_date'+new_id).val("DD-MM-YYYY");
					$('#working_days'+new_id).val(" ");
					event.preventDefault();
					return false;
				}
				if($('#working_days'+new_id).val()=='')
				{
					alert('Please enter working days.');
					$('#working_days'+new_id).focus();
					event.preventDefault();
					return false;
				}
				if($('#consolidated_pay'+new_id).val()=='')
				{
					alert('Please Enter Consolidated Pay Ammount.');
					$('#consolidated_pay'+new_id).focus();
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
		
		/*if(results!="")
		{
			alert("Arrear Can Not be Submitted Due To Duplicate Entry");
			return false;
		}
		else
		{
			return true;
		}*/
	});
 });
 
function key_restrict(e1)
{
	return keyRestrict(e1,'0123456789');
}
 
 </script>
    