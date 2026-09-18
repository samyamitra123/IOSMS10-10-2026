<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$db=new database();

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

function fun_gp($gp)
{ 
	$db = new database();
	$data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
	return $data[0]['gp_name'];
}
function fun_ps($ps)
{ 
	$db = new database();
	$data = $db->fetch_table("SELECT ps_name FROM   prd_location_master_panchayat_samiti  where ps_id_pk='".$ps."'");
	return $data[0]['ps_name'];
}
function fun_block($block)
{ 
	$db = new database();
	$data = $db->fetch_table("select block_name,block_id_pk from prd_location_master_block where block_id_pk='".$block."'");
	return $data[0]['block_name'];
}
function fun_dis($dis)
{ 
	$db = new database();
	$data = $db->fetch_table("select district_code,district_name from prd_location_master_district where district_id_pk='".$dis."'");
	return $data[0]['district_name'];
}

$arr=$db->fetch_table("select 								 
								emp_first_name,
								emp_second_name, 
								emp_last_name,
								transfer_gp_id_fk,
								transfer_block_id_fk,
								transfer_district_id_fk, 
								emp_id_const,
								transfer_ps_id_fk, 
								transfer_date,
								transfer_level,
								transfer_remarks
								FROM prd_employee_transfer
								 where prd_employee_transfer.emp_id_fk='".$_GET['id']."' AND transfer_emp_status=0
								 ");
?>


<style>
.school_reason table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school_reason table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school_reason table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school_reason table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school_reason{
	background-color: #D3C8C8;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school_reason .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school_reason .action .ui-widget{
	font-size: 11px;
}
.school_reason .action{
	text-align: center;
}
.school_reason .action .ui-button .ui-button-text{
	padding: 5px 10px;
}

</style>
<div class="school_reason">
    <div class="table-responsive"  align="center">
        <table width="100%">
            <tr>
                <th>Employee Name</th>
                <th>Transfer Level</th>
                <th style="width:10%;">Date</th>
                <?php if($arr[0]['transfer_level']=='555' || $arr[0]['transfer_level']=='556')
                {?>
                	<th>Transferred District Name</th>
                <?php 
                } 
                else if($arr[0]['transfer_level']=='557')
                {?>
                	<th>Transferred Zilla Parishad Name</th>
                <?php
                }
				if($arr[0]['transfer_level']=='555')
                {?>
                    <th>Transferred Block Name</th>
                    <th>Transferred GP Name</th>
                <?php 
				}
                else if($arr[0]['transfer_level']=='556')
                { ?>
                	<th>Transferred PS Name</th>
                <?php } ?>
                <th style="width:25%;"> Remarks </th>
            </tr>
            
            <tr style="background-color:#DDF7FF">
                <td><?= $arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name']?></td>
                <td><?= fun_common($arr[0]['transfer_level'],$code_data) ?> </td>
                <td><?= dateshow($arr[0]['transfer_date']) ?></td>
                <?php if($arr[0]['transfer_level']!='558')
				{ ?>
                	<td><?= fun_dis($arr[0]['transfer_district_id_fk']) ?> </td>
                <?php } ?>
                <?php if($arr[0]['transfer_level']=='555') {?>
                    <td><?= fun_block($arr[0]['transfer_block_id_fk']) ?> </td>
                    <td><?= fun_gp($arr[0]['transfer_gp_id_fk']) ?> </td>
                <?php }
                else if($arr[0]['transfer_level']=='556')
                { ?>
                	<td><?= fun_ps($arr[0]['transfer_ps_id_fk']) ?> </td>
                <?php } ?>
                <td><?= $arr[0]['transfer_remarks'] ?></td>
            </tr>
        </table>
    </div>
</div>
