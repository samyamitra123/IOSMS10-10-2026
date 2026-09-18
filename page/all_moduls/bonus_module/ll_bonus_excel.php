<?
//echo $_GET['month']."  ".$_GET['year'];  die;
//$monthyear=$_GET['year'].$_GET['month']; 
//echo $monthyear; die;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Bonus_Data.xls');
header("Content-Transfer-Encoding: binary");

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
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

$db = new database();

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

$desig_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	");


function fun_desig($dcode, $code_desig)
{
	foreach ($code_desig as $key) 
	{
		if($key['designation_id'] == $dcode)
		{
			return $key['designation_name'];
		}
	}
}

function fun_dist($val)
		    {
			    $db = new database();
			    $dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
			    return $dist_data2[0]['district_name'];
		    }
			function fun_ps($p)
		    { 
			    $db = new database();
			    $data = $db->fetch_table("SELECT ps_name FROM   prd_location_master_panchayat_samiti  where ps_id_pk='".$p."'");
			    return $data[0]['ps_name'];
		    }
			
			function fun_gp($p)
		    { 
			    $db = new database();
			    $data = $db->fetch_table("SELECT gp_name FROM   prd_location_master_gp  where gp_id_pk='".$p."'");
			    return $data[0]['gp_name'];
		    }


if($logged_user=='BDO')
    {
    $name1=$_SESSION['location']['block_name'].' BLOCK'; 
    $admin_name="Signature of the Block Development Officer";
	
	
	 
    }
    else if ($logged_user=='DA' )
    {
    $name1=fun_ps($_SESSION['location']['ps_id']).' PANCHAYAT SAMITI'; 
	$name=fun_ps($_SESSION['location']['ps_id']).' PANCHAYAT SAMITI'; 
     
    }
	else if ($logged_user=='EO' )
    {
    $name1=fun_ps($_SESSION['location']['ps_id']).' PANCHAYAT SAMITI';
	$name=fun_ps($_SESSION['location']['ps_id']).' PANCHAYAT SAMITI';  
    $admin_name="Signature of the Executive Officer";
    }
	else if ($logged_user=='zpdaa' )
    {
    $name1=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD'; 
	$name=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD';
	
    }
	else if ($logged_user=='zpddo')
    {
    $name1=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD'; 
	$name=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD'; 
  
	$admin_name="Signature of the Financial Controller & Chief Accounts Officer";
    }
	else if($logged_user=='GP')
	{
		 $name1=fun_gp($_SESSION['location']['gp_id']).' GRAM PANCHAYAT'; 
		 $name=fun_gp($_SESSION['location']['gp_id']).' GRAM PANCHAYAT'; 
	}


$year=$_GET['year'];
$month=$_GET['month'];
$bon_cat=$_GET['bon_cat'];
$bon_name=$_GET['bon_name'];

$pvr_year=substr($year,0,-4);
$current_year=substr($year,-4);
$f_year=$pvr_year.'-'.$current_year;
//-------------------------------------------------------QUERY-----------------------------------------------------------------

	//$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
	$db = new database();
	
	

	
    $code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	                              ");

if($logged_user=='zpdaa' ||$logged_user=='zpddo')
{
	

	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp_bon.gp_id_fk,
											emp_bon.ps_id_fk,
											emp_bon.zp_id_fk,
											emp_bon.block_code,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											WHERE monthyear='".$year."' AND emp_bon.delete_status='1' AND bon_type.active_status!='0' 
											AND emp_bon.zp_id_fk='".$_SESSION['location']['district_id']."' AND bon_type.bonus_category='".$bon_cat."' AND emp_bon.bonus_status='5' AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)");
}
else if($logged_user=='DA' ||$logged_user=='EO')
{

 
	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp_bon.gp_id_fk,
											emp_bon.ps_id_fk,
											emp_bon.zp_id_fk,
											emp_bon.block_code,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk and bill.ps_id_fk=emp_bon.ps_id_fk
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1'  
											AND emp_bon.ps_id_fk='".$_SESSION['location']['ps_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1)  And emp_bon.bonus_status='5'
											");
}
else if($logged_user=='GP' )
{

	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp_bon.gp_id_fk,
											emp_bon.ps_id_fk,
											emp_bon.zp_id_fk,
											emp_bon.block_code,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1' 
											AND emp_bon.gp_id_fk='".$_SESSION['location']['gp_id']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1) AND emp_bon.bonus_status='5'
											");
}
	
	
	else if( $logged_user=='BDO')
{

	$bonus_details_fetch=$db->fetch_table(" SELECT 
											emp.emp_first_name,
											emp.emp_second_name,
											emp.emp_last_name,
											emp_bon.gp_id_fk,
											emp_bon.ps_id_fk,
											emp_bon.zp_id_fk,
											emp_bon.block_code,
											emp.emp_desig,
											emp.emp_id_const,
											emp_bon.emp_id_fk,
											emp_bon.bonus_amount,
											emp_bon.bonus_status,
											bon_type.active_status,
											bon_type.bonus_category,
											bon_type.bonus_name,
											bill.bill_no,emp_bon.ps_id_fk,emp_bon.gp_id_fk
											FROM prd_employee_master emp 
											INNER JOIN prd_employee_bonus_details emp_bon
											ON emp.emp_id_pk=emp_bon.emp_id_fk
											INNER JOIN prd_bonus_type_details bon_type
											ON emp_bon.bonus_type_id_fk=bon_type.bonus_type_id_pk  
											INNER JOIN prd_block_bill_details bill
											ON bill.block_bill_pk=emp_bon.bill_id_fk 
											WHERE emp_bon.monthyear='".$year."' AND emp_bon.delete_status='1' 
											AND emp_bon.block_code='".$_SESSION['location']['block_code']."' AND bon_type.bonus_category='".$bon_cat."'
											AND bon_type.delete_status='1' AND bon_type.active_status in (0,1) AND emp_bon.bonus_status='5'
											");
}							
$date = $tch[0]['latestupdate_time'];
$msg="";
if($tch[0]['status_flag'] == 1){ 
	$msg="Not finalized (Just Saved)";
}					
elseif($tch[0]['status_flag'] == 2){ 
	$msg="Requisition finalized by CIRCLE";
} elseif($tch[0]['status_flag'] == 3){
	$msg="Requisition finalized by DPSC";
} 
?>

<table class="table-responsive" style="width:100%;" border="1">
     

<th colspan="8"   style="text-align:center;">Panchayats & Rural Development Department, GOVT. OF WB (i-OSMS)</th>

<tr>
                <th colspan="8" style="text-align:center;"><br />DETAILED BONUS BILL OF  <?= $name1?> 
                 FOR THE FINANCIAL YEAR <?= $f_year?><br /><br /></th>
   </tr>
						
            <tr>
                <th style="width: 5%;">SL NO.</th>
                <?php if($logged_user=='DA' ||$logged_user=='EO'){ ?>
                 <th style="width: 10%;">PS Name</th>
                <?php } else if($logged_user=='zpdaa' ||$logged_user=='zpddo'){?>
                 <th style="width: 10%;">ZP Name</th>
                 <?php }else{?>
                 <th style="width: 10%;">GP Name</th>
                 <?php }?>
                <th style="width: 15%;">Employee Name</th>
                <th style="width: 12%;">Employee Id</th>
                <th style="width: 8%;">Designation</th>
                <th>Bonus Name</th>
                <th>Bonus Amount</th>
                <th style="width: 16%;">Status</th>
                
            </tr>
      
       
  
   
   <?php
        if(count($bonus_details_fetch))
        {
			$count = 1; 
			foreach ($bonus_details_fetch as $key) 
			{
				/*if($key['active_status']=='2')
				{
					$status='<p style="font-weight:bold;color:#656fb3;">Bill Generated</p>';
				}*/
				 
					if($key['bonus_status']=='4')
					{
						$status='<p style="font-weight:bold;color:#6CB0BF;">Locked</p>';
					}
					else if($key['bonus_status']=='3')
					{
						$status='<p style="font-weight:bold;color:#D59960;">Waiting For Lock</p>';
					}
					
					else if($key['bonus_status']=='5')
					{
						$status='<p style="font-weight:bold;color:#656fb3;">Bill Generated</p>';
					}
					
				
				?>
                <tr>
                    <td id="show"><?php echo $count; ?></td>
                    
                     <td id="show"><?php if($logged_user=='BDO'){ echo fun_gp($key['gp_id_fk']);}else{ echo $name;}; ?></td>
                    <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?> </td>
                    <td><?php echo $key['emp_id_const']; ?></td>
                    <td><?php if($logged_user=='zpdaa' || $logged_user=='zpddo'){ echo fun_desig($key['emp_desig'],$desig_data);}else{ echo fun_common($key['emp_desig'],$code_data);} ?></td>
                    <td><?php echo fun_common($key['bonus_name'],$code_data); ?></td>
                    <td><?php echo $key['bonus_amount']; ?></td>
                    <td><?php echo $status; ?></td>
                     
                </tr>
                <?php
                $count += 1 ; 
				
				$tot_gross+=$key['bonus_amount'];
			 }?>
			
			<tr style="text-align:center;font-weight:bold;">
	<td  style="text-align:right;">TOTAL</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right"><?= $tot_gross?></td>
    <td></td>
    </tr> 
		<?php }
		
		
		
        else 
        {?> <tr><td colspan="8" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
        
               
</table>


