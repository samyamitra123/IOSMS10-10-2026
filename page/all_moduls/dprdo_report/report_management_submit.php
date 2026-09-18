<?
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';


	
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


if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

			$cryptoGraph=new cryptography();
			$sec_time_token=$_POST['sec_tok'];
			$session_token=$_SESSION['security_token'];
			$enc_session=md5('369'.$session_token);
			$rep_month = $cryptoGraph->decode($_POST['rep_month'],3);
			$rep_year = $cryptoGraph->decode($_POST['rep_year'],3);

//echo $rep_month; die;

if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('location:report_management.php');
	exit;
}
else{

	
			$report_data_array = array();
			$db = new database();
			//echo (" SELECT block_code from prd_location_master_block WHERE district_id_fk='".$_SESSION['location']['district_id']."' "); die;
			$block_details = $db->fetch_table(" SELECT block_code from prd_location_master_block WHERE district_id_fk='".$_SESSION['location']['district_id']."' ");
			
				foreach($block_details as $id => $key)
				{ 
				
			
		$report_data = $db->fetch_table("select district_name, block_name, CAST(mmsaf.block_code AS integer), sum(mmsaf.gross_salary) as salary_gross_amount, 
			sum(bonus.bonus_amount) as bonus_amount, sum(arrear.gross_salary) as arrear_gross_amount, sum(festival.festival_advance_total_amount) as  festival_amount
			FROM prd_employee_salary_save mmsaf
			INNER JOIN prd_location_master_block mlmm
			ON mlmm.block_code= CAST(mmsaf.block_code AS integer) 
			inner join prd_location_master_district d
			on mlmm.district_id_fk=d.district_id_pk	

			left JOIN prd_employee_bonus_details as bonus 
			ON bonus.block_code= CAST(mlmm.block_code as integer)  
			and bonus.bill_id_fk!='0' 
			and bonus.block_code IN ('".$key["block_code"]."') and bonus.monthyear='".$rep_year.$rep_month."'
			
			left JOIN prd_employee_arrear as arrear 
			ON arrear.block_code= CAST(mlmm.block_code as character varying)  
			and arrear.bill_id_fk!='0' 
			and arrear.block_code IN ('".$key["block_code"]."') and arrear.salary_monthyear='".$rep_year.$rep_month."'

			left JOIN prd_festival_advance_employee_details as festival ON  festival.block_code= CAST(mlmm.block_code as integer) and festival.bill_id_fk!='0' and festival.block_code IN ('".$key["block_code"]."') and festival.fad_monthyear='".$rep_year.$rep_month."'
			
			WHERE mmsaf.salary_monthyear='".$rep_year.$rep_month."' AND mmsaf.delete_status='1' AND mmsaf.status_flag='1' AND mmsaf.is_saved='1' AND mmsaf.salary_type!='8' 
			AND mmsaf.block_code='".$key["block_code"]."'
			GROUP BY district_name ,block_name,CAST(mmsaf.block_code AS integer) order by district_name  ASC"); 
			
			
			////////////////////////////////debjit/////////////////////////////////////////////////////
				
				
				/*
					echo ("SELECT sum(salary.gross_salary) as salary_gross, sum(arrear.gross_salary) as arrear_gross, sum(bonus.bonus_amount) as bonus_amount, 
					sum(festival.festival_advance_total_amount) as  festival_amount
					FROM prd_employee_salary_save as salary
					INNER JOIN prd_employee_arrear as arrear ON arrear.block_code = salary.block_code where arrear.bill_id_fk!='0'
					INNER JOIN prd_employee_bonus_details as bonus ON CAST(bonus.block_code as character varying) = salary.block_code where bonus.bill_id_fk!='0'
					INNER JOIN prd_festival_advance_employee_details as festival ON CAST(festival.block_code as character varying) = salary.block_code where festival.bill_id_fk!='0'
					WHERE salary.block_code IN ('".$key["block_code"]."') AND salary.salary_monthyear ='".$rep_year.$rep_month."'"); die;
					
					
					$report_data = $db->fetch_table("SELECT sum(salary.gross_salary) as salary_gross, sum(arrear.gross_salary) as arrear_gross, sum(bonus.bonus_amount) as bonus_amount, 
					sum(festival.festival_advance_total_amount) as  festival_amount
					FROM prd_employee_salary_save as salary
					INNER JOIN prd_employee_arrear as arrear ON arrear.block_code = salary.block_code where arrear.bill_id_fk!='0'
					INNER JOIN prd_employee_bonus_details as bonus ON CAST(bonus.block_code as character varying) = salary.block_code where bonus.bill_id_fk!='0'
					INNER JOIN prd_festival_advance_employee_details as festival ON CAST(festival.block_code as character varying) = salary.block_code where festival.bill_id_fk!='0'
					WHERE salary.block_code IN ('".$key["block_code"]."') AND salary.salary_monthyear ='".$rep_year.$rep_month."'");
					
					 */
				
					$report_data_array[] =	$report_data;				 
				}
						
			
			}
	
?>

<script>
$(document).ready(function() {
	
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
});

function disburs_dprdo_calculate(){ 

		var total_amount = document.getElementById('total_amount').innerText ;
		var disburs_amount = $('#disburs_amount').val();
		var excess_amount = parseInt(total_amount)-parseInt(disburs_amount);
		//alert(excess_amount);
		$('#excess_amount').val(excess_amount);
}


</script>
	
		
<div class="emplist">
	<div class="school">
		<div class="table-responsive">
			<table width="100%">
				<tr>
					<th>Block Name</th>
					<th>Salary Amount</th>
					<th>Arrear Amount</th>
					<th>Bonus Amount</th>
					<th>Festival Advance Amount</th>
					<th>Total Amount</th>
					<th>Disburs Amount</th>
					<th>Excess Amount</th>
					
				</tr>
			<? 

			//$cnt=1;
			//var_dump($report_data_array); 
			if(count($report_data_array)){ foreach($report_data_array as $item){ 
			$total_amount = $item[0]['salary_gross_amount']+ $item[0]['arrear_gross_amount']+ $item[0]['bonus_amount']+ $item[0]['festival_amount'];
			?>
				<tr>
					
					
					<td><?php echo $item[0]['block_name']; ?></td>
					<td><?php echo $item[0]['salary_gross_amount']; ?></td>
					<td><?php echo $item[0]['arrear_gross_amount']; ?></td>
					<td><?php echo $item[0]['bonus_amount']; ?></td>
					<td><?php echo $item[0]['festival_amount']; ?></td>
					<td id="total_amount" name="total_amount"><?php echo $total_amount; ?></td>
					<td><input type="text" id="disburs_amount" name="disburs_amount" onkeyup="disburs_dprdo_calculate();" ></td>
					<td><input type="text" id="excess_amount" name="excess_amount" style="background-color: #F5F5F5" readonly></td>
					
						
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
