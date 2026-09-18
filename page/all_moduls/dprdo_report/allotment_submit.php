<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require_once '../../../includes/library/myvalidation.class.php';


	
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

/*
$logged_user=$_SESSION['user_info']['stake_abbr']; 	
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

*/

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}



			$cryptoGraph=new cryptography();
			$sec_time_token=$_POST['sec_tok'];
			$session_token=$_SESSION['security_token'];
			$enc_session=md5('369'.$session_token);
			$from_month = $cryptoGraph->decode($_POST['from_month'],3);
			$to_month = $cryptoGraph->decode($_POST['to_month'],3);
			$rep_year = $cryptoGraph->decode($_POST['rep_year'],3);
			$allotment_order_number = $_POST['allotment_order_number'];
			$allotment_number = $_POST['allotment_number'];
			$allotment_amount = $_POST['allotment_amount'];
			


$explode_year = explode('-',$rep_year);
//var_dump($year); die;

if($to_month < $from_month){
	$from_monthyear= $explode_year[0].$from_month;
	$to_monthyear= $explode_year[1].$to_month;
}
else{
	$from_monthyear= $explode_year[0].$from_month;
	$to_monthyear= $explode_year[0].$to_month;
	
}

//var_dump($from_monthyear); 
//var_dump($to_monthyear); die;

if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('location:report_management.php');
	exit;
}
else{

	
			$report_data_array = array();
			$db = new database();
			
													
				$insert_allotment_data = $db->insert("INSERT INTO prd_commissioner_allotment (
													from_month,
													to_month,
													allotment_year,
													allotment_amount,
													allotment_order_number,
													allotment_number,
													date,
													ip
													)
													VALUES (
													'".$from_month."',
													'".$to_month."',
													'".$rep_year."',
													'".$allotment_amount."',
													'".$allotment_order_number."',
													'".$allotment_number."',
													'now()',
													'".$_SERVER['REMOTE_ADDR']."'
													)")	;	
					
					/*
					$report_data = $db->fetch_table(" select district.district_name, district.district_id_pk, district.district_code, COUNT(block.block_code) as block_count 
									from prd_location_master_district as district 
									INNER JOIN prd_location_master_block as block ON district.district_id_pk = block.district_id_fk 
									GROUP BY district.district_code, district.district_name, district.district_id_pk");
									
			

			
			$report_data = $db->fetch_table("select district.district_name, district.district_id_pk, district.district_code, COUNT(block.block_code) as block_count, sum(archive.net) as net 
from prd_location_master_district as district 
INNER JOIN prd_location_master_block as block ON district.district_id_pk = block.district_id_fk 
INNER JOIN prd_monthly_salary_archive_final as archive ON block.block_code= CAST(archive.block_code AS integer) 
WHERE archive.salary_monthyear='202110' AND archive.delete_status='1' AND archive.status_flag='3' AND archive.is_saved='1' AND archive.salary_type!='8' AND archive.block_code!='3299001' 
GROUP BY district.district_code, district.district_name, district.district_id_pk, archive.net");
			*/
					
					
			
					$report_data = $db->fetch_table("select district.district_name ,district.district_id_pk,COUNT(block.block_code)as block_count,
					district.district_code,sum(net) as total_net 
					FROM prd_monthly_salary_archive_final archive
					INNER JOIN prd_location_master_block block ON block.block_code= CAST(archive.block_code AS integer) 
					inner join prd_location_master_district district on block.district_id_fk=district.district_id_pk
					WHERE CAST (salary_monthyear as integer) >= $from_monthyear AND CAST (salary_monthyear as integer) <= $to_monthyear
			AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' AND archive.block_code!='3299001'
			GROUP BY district.district_name,district.district_id_pk,district.district_code
			order by district.district_name	");
			
			
			
			}
	
?>

<script>
$(document).ready(function() {
	
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );

});


function disburs_com_calculate(key){ 
		//var disburs_amount = document.getElementById('disburs_amount').innerText ;
		var disburs_amount = $('#disburs_amount_'+key).val();
		var expance_amount = $('#expance_amount_'+key).val();
		var excess_amount = parseInt(disburs_amount)-parseInt(expance_amount);
		//alert(disburs_amount);
		$('#total_amount_'+key).val(excess_amount);
}


function sub_allotment_form_submit(key){
	
			//var sec_tok = $('#sec_tok').val();
			var expance_amount = $('#expance_amount_'+key).val();
			//var block_count = $('#block_count').val();
			var disburs_amount = $('#disburs_amount_'+key).val();
			var district_id_pk = $('#district_id_pk_'+key).val();
			var prev_remain_amount = $('#prev_remain_amount_'+key).val();
			var total_amount = $('#total_amount_'+key).val();
			//alert(disburs_amount);
			if($('#disburs_amount_'+key).val()=="")
			{
				alert("Please Enter Disburs Amount!");
				$('#disburs_amount_'+key).focus();
				return false;
			}
			
			else if(disburs_amount!="")
			{ //alert('hiiii');
				//$.post('<?= $config['base_url'] ?>page/all_moduls/dprdo_report/sub_allotment_submit.php?expance_amount='+expance_amount+'&disburs_amount='+disburs_amount+'&district_id_pk='+district_id_pk+'&prev_remain_amount='+prev_remain_amount+'&total_amount='+total_amount, 
				
				$.post("sub_allotment_submit.php",
				  {
					expance_amount: expance_amount,
					disburs_amount: disburs_amount,
					district_id_pk: district_id_pk,
					prev_remain_amount: prev_remain_amount,
					total_amount: total_amount
				  },
				 //alert(data); 
				function(data){
					//$('#bill_rep').prop('readonly',true);
					alert('Sub Allotment Entered successfully !');
				});
				
	
}
}
</script>
	
		
<div class="emplist">
	<div class="school">
		<div class="table-responsive">
			<table width="100%">
				<tr>
					<th>District Name</th>
					<!--<th>Number of Blocks</th>-->
					<th>Expense Amount</th>
					<th>Previous Remaining Amount</th>
					<th>Disburs Amount</th>
					<th>Remaining Amount</th>
					<th>Action</th>
				</tr>
			<? 

			//$cnt=1;
			//var_dump($report_data); 
			if(count($report_data)){ foreach($report_data as $key=>$item){
			//$total_amount = $item[0]['salary_gross_amount']+ $item[0]['arrear_gross_amount']+ $item[0]['bonus_amount']+ $item[0]['festival_amount'];
			?>
				<tr>
					
					<!--<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
					<input type="hidden" name="allotment_year" id="allotment_year" value="<?=$rep_year?>" />
					<input type="hidden" name="allotment_amount" id="allotment_amount" value="<?=$allotment_amount?>" />
					<input type="hidden" name="allotment_order_number" id="allotment_order_number" value="<?=$allotment_order_number?>" />
					<input type="hidden" name="allotment_number" id="allotment_number" value="<?=$allotment_number?>" />
					<input type="hidden" name="from_month" id="from_month" value="<?=$from_month?>" />
					<input type="hidden" name="to_month" id="to_month" value="<?=$to_month?>" />-->
					<input type="hidden" name="district_id_pk_<?=$key;?>" id="district_id_pk_<?=$key;?>" value="<?php echo $item['district_id_pk']; ?>" />
					<td id="district_name_<?=$key;?>" name="district_name_<?=$key;?>" ><?php echo $item['district_name']; ?></td>
					<!--<td id="block_count" name="block_count" ><?php echo $item['block_count']; ?></td>-->
					<td><input type="text" id="expance_amount_<?=$key;?>" name="expance_amount_<?=$key;?>" value='<?php echo $item['total_net']; ?>' disabled ></td>
					<td><input type="text" id="prev_remain_amount_<?=$key;?>" name="prev_remain_amount_<?=$key;?>" value='2000' disabled ></td>
					<td><input type="text" id="disburs_amount_<?=$key;?>" name="disburs_amount_<?=$key;?>" onkeyup="disburs_com_calculate('<?php echo $key; ?>');"></td>
					<td><input type="text" id="total_amount_<?=$key;?>" name="total_amount_<?=$key;?>" disabled ></td>
					<td><button class="btn btn-success col-sm-offset-5 col-sm-4" type="submit" name="bill_rep_district_<?=$key;?>" id="bill_rep_district_<?=$key;?>" onclick="sub_allotment_form_submit('<?php echo $key; ?>');">Save</button>
						<button class="btn btn-danger col-sm-offset-5 col-sm-4" type="reset" name="bill_rep_reset_district_<?=$key;?>" id="bill_rep_reset_district_<?=$key;?>" >Reset</button></td>
				
					
						
				</tr>
			<? //$cnt+=1; 
				} ?>
			<? } else { ?>
				<tr>
				<td colspan="21" style="color:red;font-weight:bold">No Data Found</td>
				</tr>
			<? } ?>
			</table>


		</div>
	</div>
</div>
