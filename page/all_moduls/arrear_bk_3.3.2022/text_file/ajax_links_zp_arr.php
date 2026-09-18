<?php

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../all_function/fun_store/zp_ps_gp_function.php';

//require '../../page_visite.php';


$k = strtotime("first day of last month");
$arr = date("Y-m-d", $k);

/* $month_ini = new DateTime("first day of last month");
  $arr=$month_ini->format('Y-m-d'); // 2012-02-01
  echo $arr; */

$month_arr = explode('-', $arr);
$salary_monthyear = $month_arr[0] . $month_arr[1];
$db = new database();
$fun_store = new zp_ps_gp_class();
/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
$logged_user=$_SESSION['user_info']['stake_abbr']; 
if($logged_user=='EO')
{
	$party_code = '007'; die;
}
else if($logged_user=='FC&CAO')
{
    $party_code = '008'; 
}

function ifms_error_description_generate($code) 
{
    $db = new database();
    $err_desc_fetch = $db->fetch_table(" SELECT description FROM prd_ifms_response_code_master WHERE code='" . $code . "' ");
    return $err_desc_fetch[0]['description'];
}
?>

<style>
    #sucess{
	background-color: green;
	border: medium none salmon;
	border-radius: 8px;
	box-shadow: 2px 3px 3px #7d7c7d;
	color: #ffffff;
	font-family: Verdana,Geneva,sans-serif;
	font-size: 13px;
	padding: 8px;
	text-align:center;
	font-weight:bold;
    }
    #error{
	background-color: red;
	border: medium none salmon;
	border-radius: 8px;
	box-shadow: 2px 3px 3px #7d7c7d;
	color: #ffffff;
	font-family: Verdana,Geneva,sans-serif;
	font-size: 13px;
	padding: 8px;
	text-align:center;
	font-weight:bold;
    }
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
    $(document).ready(function () {
        $("tr:odd").css("background-color", "#CCE6FF");
        $("tr:even").css("background-color", "#DDF7FF");
        //$( ".modal fade in" ).css( "height", "1000px" );
    });
</script>

<?php

$crypto = new cryptography();

if (
	!isset($_SESSION['user_info']['stake_user']) || !isset($_SESSION['user_info']['stake_level']) || !isset($_SESSION['user_info']['flag'])
) {
    header('Location: ' . $config['base_url'] . "page/login.php");
    exit;
}

function date_frmt_change($original_date) 
{
    if ($original_date == "0001-01-01" || $original_date == '1970-01-01' || $original_date == NULL || $original_date == "") 
	{
		return NULL;
    } 
	else 
	{
		return $newDate = date("Y-m-d", strtotime($original_date));
    }
}

//$monthyear = $crypto->decode($_REQUEST['bill_report_year'], 4) . $crypto->decode($_REQUEST['bill_report_month'], 4);



$db = new database();

$zp_profile_fetch=$db->fetch_table("
										SELECT pl_code,ddo_code FROM zpemp_zp_profile WHERE district_id_fk='".$_SESSION['location']['district_id']."'
								");
								
$pl_operator_code=$zp_profile_fetch[0]['pl_code'];
$treasury_code=substr($zp_profile_fetch[0]['ddo_code'],0,3);





$bill_type=$crypto->decode($_POST['emp_type'],4); 
 $requisition_type=$crypto->decode($_POST['requisition_type'],4); 
 $monthyear = $crypto->decode($_POST['bill_report_year'], 4); 
 $bill_serial_no=$_POST['bill_serial_no'];

/*$requisition = $db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type = $requisition[0]['code'];
*/

$drn_checking = $db->fetch_table("
										SELECT * FROM prd_block_bill_details 
										WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
										AND bill_no = '" . $_POST['bill'] . "'
										AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
										AND status='1' AND zp_emp_type='".$bill_type."' AND bill_serial_no='". $bill_serial_no."'
										");

if ($drn_checking[0]['drn_number'] == "") 
{
   $drn_number = $fun_store->drn_generation($party_code);
} 
else 
{
	$drn_number=$drn_checking[0]['drn_number'];
	
    $sftp_details_fetch = $db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_sending_status,sftp_benf_response_status FROM prd_sftp_benf_upload_response WHERE bill_id_fk='" . $drn_checking[0]['block_bill_pk'] . "' AND active_status in ('1','2') ");
	
	$payment_failure_details=$db->fetch_table(" SELECT count(*) as total_count_fail
												FROM prd_sftp_benf_failure_details fail 
												INNER JOIN prd_sftp_benf_upload_response benf
												ON fail.sftp_benf_id_fk=benf.sftp_benf_id_pk
												WHERE benf.bill_id_fk='" . $drn_checking[0]['block_bill_pk'] . "' AND benf.active_status='1'
												AND fail.response_from='10' AND fail.active_status in('1','2','3')");

}

if ($monthyear != date('Ym')) 
{
    ?>
    <div class="alert alert-danger" style="width: 28%;margin-left: 43%;text-align: center;"><strong>Please Select Current Month and Year</strong></div>
    <?php
} 

else if(!ctype_alpha($treasury_code) || !ctype_digit($pl_operator_code)) 
{
	echo '<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Wrong PL Opertaor Code or Wrong Treasury Code. Please update Zilla Parishad Profile.</strong></div>';
}

else 
{
    $check_dpsc_bill = $db->fetch_table("
											SELECT count(*) FROM prd_block_bill_details 
											WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
											AND status='1' AND zp_emp_type='".$bill_type."'
											");

    $check_dpsc_bill_exist = $db->fetch_table("
												SELECT count(*) FROM prd_block_bill_details 
												WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
												AND bill_no = '" . $_POST['bill'] . "'
												AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
												AND status='1' AND zp_emp_type='".$bill_type."'
											");
    




    $total_benf = $db->fetch_table("SELECT count(distinct(emp.emp_id_pk)) as total_benf
									FROM prd_employee_master emp 
									INNER JOIN prd_employee_arrear sal 
									ON sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk
									WHERE 
									trim(sal.salary_monthyear)='".date('Ym')."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='4'
									AND sal.is_saved='1'
									AND sal.zp_id_fk='".$_SESSION['location']['district_id']."' 
									AND sal.requisition_type='" . $requisition_type . "'
									AND sal.zp_emp_type='".$bill_type."'
									");


    $encode_drn = $crypto->encode($drn_number, 4);
    $encode_requsition = $crypto->encode($requisition_type, 4);
	$encode_emp_type = $crypto->encode($bill_type, 4);


    if ($check_dpsc_bill[0]['count'] == 0) 
	{
		//pg_query("begin");
	
	
	//$fetch = $db->fetch_table("SELECT now()");
		
		
		

		
			
			$insert_salary_bill = $db->insert("
												INSERT INTO prd_block_bill_details (
												block_code,
												bill_no,
												status,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												requisition_type,
												drn_number,
												ps_id_fk,
												zp_id_fk,
												zp_emp_type,bill_serial_no
												)
												VALUES(
												'0',
												'" . $_POST['bill'] . "',
												'1',
												'" . date_frmt_change($_POST['bill_date']) . "',
												now(),
												'" . $_SESSION['user_agent']['USER_IP'] . "',
												'" . $monthyear . "',
												'" . $requisition_type . "',
												'" . $drn_number . "',
												0,
												'".$_SESSION['location']['district_id']."',
												'".$bill_type."','".$bill_serial_no."'
												);
												");
	
	
	
		
		
		
		if ($insert_salary_bill==TRUE)
		{
		
	
		
				$update_arrear_salary=$db->update("UPDATE prd_employee_arrear arr
											SET 
											salary_monthyear='".$monthyear."',
											status_flag='3',
											bill_id_fk=bill.block_bill_pk,
											bill_serial_no='".$bill_serial_no."'
											FROM
											prd_block_bill_details bill 
											WHERE arr.status_flag=2 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.zp_id_fk = '".$_SESSION['location']['district_id']."' AND bill.bill_no='".$_POST['bill']."' AND bill_entry_time='".date_frmt_change($_POST['bill_date'])."' ");
					
			
			
			?>
			<script>
			//	    	$('#send_bill_sum_id').click(function(){
			//	   
			//		$('#page-load').show();
			//
			//   success:function(result){
			//       $('#page-load').hide();  
			//   }
			//});
			</script>
			
			
			
			
			<a href='<?php echo $config['base_url'] ?>page/all_moduls/arrear/text_file/ajax_links_ifms_upload_details.php' class="btn btn-success">Next</a>
			<?php
			
		} 
	
		else 
		{
			//pg_query("rollback");
			?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
			<?php
		}
	} 
	else 
	{
		if ($check_dpsc_bill_exist[0]['count'] == 1 && $drn_checking) 
		{
		?>
		
		
		<?php
		} 
		else 
		{
		?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is allready inserted for this month</strong></div>
		<?php
		}
	}
}
?>

<script>
//**************************************************************


	$(document).ready(function () 
	{
		//     $('#page-load').show();
		//        $('#page-load').delay(1000).fadeOut();
		//      });
	});
	
	function value_pass(encode_requsition, drn_number, emp_type,monthyear)
	{
		$('#page-load').show();
		
		var requsition = encode_requsition;
		var drn_no = drn_number;		
		$.post('<?= $config['base_url'] ?>page/intra_zp/salary_fcncao/text_file/xml_file.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type, function (data) {
		
		});
		
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/index.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type+'&monthyear='+monthyear, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
			if (data.trim() == 'Success')
			{
			        $('#status_bill_sum_send').css('color', '#008e76');
				$('#status_bill_sum_send').text(data);
				$('#upload_image').hide();
				$('#send_benf').show();
				$('#send_bill_sum_id_disable').show();
				$('#send_bill_sum_id').hide();
				$('#edit_action_first_row').hide();
				$('#edit_action_first_row2').hide();
				$('#bill_status_row').show();
			} 
			else
			{
			        $('#status_bill_sum_send').css('color', '#c62828');
				$('#status_bill_sum_send').text(data);
				$('#edit_action_first_row').show();
				$('#edit_action_first_row2').hide();
			}
		});
		
	}
	
	//**************************************************************
	
	function value_pass_sftp(encode_requsition, drn_number, emp_type,monthyear)
	{
		//$('#page-load').show();
		var requsition = encode_requsition;
		var drn_no = drn_number;
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/sftp_index.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type+'&monthyear='+monthyear, function (data) {
			//alert(data);
			$("#status1").html(data);
			$('#page-load').delay(500).fadeOut();
			if (data.trim() == '2')
			{
				$("#status1").html("SFTP Connection Fails");
			}
			if (data.trim() == '0')
			{
				$("#status1").html("File Uploading Fails");
			}
			if (data.trim() == '1')
			{
				$("#status1").html("Upload Confirmation Pending");
				$('#upload_image').show();
				$('#send_benf').hide();
			}
		});
	}
	
	//**************************************************************
	
	function value_pass_view(encode_requsition, drn_number,monthyear)
	{
		$('#page-load').show();
		var requsition = encode_requsition;
		var drn_no = drn_number;
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/bill_status_check.php?bill_type=' + requsition + '&drn_no=' + drn_no+'&monthyear='+monthyear, function (data) {
			$('#page-load').delay(500).fadeOut();
			if (data)
			{
			   
			        $('#view_bill_status').css('color', '#ff8f00');
				$('#view_bill_status').text(data);
				
			}
		});
	}
	
	//**************************************************************
	
	function benf_status_check()
	{
		var user='zp';
		$('#page-load').show();
		$('#refresh_icon_static').hide();
		$('#refresh_icon_dynamic').show();
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/prd_ifms_cron_job.php?user='+user, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
			//$('#status1').html(data); 
			location.reload();
		});
	}
	
	//**************************************************************
	
	function done_file_check()
	{
		var user='zp';
		$('#page-load').show();
		/*$('#refresh_icon_static').hide();
		$('#refresh_icon_dynamic').show();*/
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/dot_done_ifms_cron_job.php?user='+user, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
			location.reload();
			//$('#status1').html(data); 
		});
	}
	
	//**************************************************************
	
	function sftp_edit(benf_id)
	{
		$('#page-load').show();
		$('#page-load').delay(500).fadeOut();
		$('#myModal').modal('show');
		
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/sftp_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
			$("#mbody").html(data);
			$("#empshow").html(data);
			$("#success").hide();
			$("#failed").hide();
		});
	}
	
	//**************************************************************
	
	function payment_edit(benf_id)
	{
		$('#page-load').show();
		$('#page-load').delay(500).fadeOut();
		$('#myModal').modal('show');
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/epayment_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
			$("#mbody").html(data);
			$("#empshow").html(data);
			
				/*if($('#msg').html('<div class="alert alert-success" style="text-align:center"><strong> Employee updation has been successfully finalized... </strong></div>'););
			{
				$('#edit_action_first_row4').show();
			}
			else
			{
				$('#edit_action_first_row3').hide();
			}*/
			
		});
	}
</script>


<!----------------------------------------------------------------- Employee MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 1380px;margin-left: -25.5%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onClick="location.reload();">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
            </div>
            <div class="modal-body"> 
                <div id="mbody"> 
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal" onClick="location.reload();">Close</button>  
            </div>
        </div>
    </div>
</div>
<!----------------------------------------------------------------- Employee MODAL End---------------------------------->
<!-----------------------------------------------------------------Bank Details MODAL Start---------------------------------->

<div class="modal fade" id="indi_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Bank Details</h4>
            </div>
            <div class="modal-body" style="background-color:#e0decb;"> 
                <div class="empshow" style="height:300px;"> 
                </div>
            </div>
            <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div> 
