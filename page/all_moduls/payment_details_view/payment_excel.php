<?

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Payment_Status_Excel.xls');
header("Content-Transfer-Encoding: binary");
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
$crypto = new cryptography();
//$crypto = new cryptography();

if (
!isset($_SESSION['user_info']['stake_user'])
| !isset($_SESSION['user_info']['stake_level'])
| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}

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

function addzero($val)
{
	if(strlen($val) == 1)
	{
		return '0'.$val;
	}
	else 
	{
		return $val;
	}
}

function fun_gp_name($gp)
{ 
	$db = new database();
	$data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
	return $data[0]['gp_name'];
}

function date_frmt($original_date)
{
	if($original_date =="0001-01-01" || $original_date =='1970-01-01')
	{
		return "---";
	}
	else
	{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}


function GetMonthString($n)
{
	$n=(int)$n;
	$timestamp = mktime(0, 0, 0, $n);
	$a=date("F", $timestamp);
	return strtoupper($a);
}

function date_frmt_change($original_date)
{
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") 
	{
		return "--";
	}
	else
	{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}

$requisition_type=$crypto->decode($_GET['requisition_type'],4);
$monthyear=$crypto->decode($_GET['monthyear'],4);
$month=substr($monthyear,4,2);
$year=substr($monthyear,0,4);

if($logged_user=='zpddo')
{
	$user_name=$_SESSION['location']['district_name'];
	$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];
}
if($logged_user=='EO')
{
	$user_name=$_SESSION['location']['ps_name'];
	$stake_clause='ps_id_fk='.$_SESSION['location']['ps_id'];
}
if($logged_user=='BDO')
{
	$user_name=$_SESSION['location']['block_name'];
	$gp_fetch=$db->fetch_table(" SELECT gp_id_pk FROM prd_location_master_gp WHERE block_id_fk='".$_SESSION['location']['block_id']."'");
	for($i=0;$i<count($gp_fetch);$i++)
	{
		$gp_id_str.="'".$gp_fetch[$i]['gp_id_pk']."'";
		if($i!=count($gp_fetch)-1)
		{
			$gp_id_str=$gp_id_str.',';
		}
	}
	$stake_clause='gp_id_fk in ('.$gp_id_str.')';
}

//-------------------------------------------------------QUERY-----------------------------------------------------------------

$db = new database();

$code_data = $db->fetch_table("
								SELECT code, description
								FROM prd_dise_code_master;
								");



$payment_data= $db->fetch_table("SELECT
									sal.gp_id_fk, 
									sal.accountno,
									sal.bank_ifsc,
									sal.net,
									sal.payment_status,
									sal.payment_date,
									sal.utr_number,
									emp.emp_id_pk,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name,
									emp.emp_id_const
								FROM
									prd_monthly_salary_archive_final as sal
								LEFT JOIN 
									prd_employee_master as emp
								ON sal.emp_id_fk =emp.emp_id_pk
								WHERE
									emp.emp_status in (1,9) AND sal.salary_monthyear='".$monthyear."' AND sal.delete_status='1' AND is_saved='1'
									AND status_flag in ('3','4') AND sal.requisition_type='".$requisition_type."' AND sal.payment_status in('11','12')
									AND sal.".$stake_clause."
									ORDER BY emp.emp_first_name");


if(count($payment_data)>0)
{?>
	<table width="200" border="1">
        <tr>
        <th colspan="10" style="text-align:center;"><br /><br />PAYMENT DETAILS FOR EMPLOYEES UNDER <?= $user_name?>  FOR THE MONTH OF <?= GetMonthString($month).", ".$year ?><br /><br /></th>
        </tr>
        <tr>
        <th scope="col">SL. NO</th>
        <?php if($logged_user=='BDO'){echo '<th scope="col">NAME OF THE GP</th>';} ?>
        <th scope="col">EMPLOYEE NAME</th>
        <th scope="col">EMPLOYEE ID</th>
        <th scope="col">ACCOUNT NUMBER</th>
        <th scope="col">IFSC CODE</th>
        <th scope="col">NET AMOUNT</th>
        <th scope="col">UTR NUMBER</th>
        <th scope="col">PAYMENT DATE</th>
        <th scope="col">PAYMENT STATUS</th>
        </tr>
        <? $cnt=1; 
        foreach ($payment_data as $key) {?>
        <tr>
        <td scope="row"><?php echo $cnt;?></td>
        <?php if($logged_user=='BDO'){echo '<td>'.$key['gp_id_fk'].'</td>';} ?>
        <td><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'];?></td>
        <td><?php echo $key['emp_id_const']; ?></td>
        <td><?php echo $key['accountno']; ?></td>
        <td><?php echo $key['bank_ifsc']; ?></td>
        <td ><?php echo $key['net']; ?></td>
        <td><?php echo $key['utr_number']; ?></td>
        <td><?php echo date_frmt_change($key['payment_date']); ?></td>
        <td><?php if($key['payment_status']=='11'){echo "SUCCESS";} else if($key['payment_status']=='12'){echo "FAILED";}?></td>
        
        </tr>
        <? 
        $cnt++; } ?>
	
	</table>
<? 
} 
?>
