<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform,  post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=Salary_Data.xls');
header("Content-Transfer-Encoding: binary");
ob_start(); 
//session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';

  $crypto = new cryptography();
	$Arguments = $crypto->decode($_GET['var'],2);
	$Arguments = json_decode($Arguments,true);
	$Arguments['year'] = isset($Arguments['year'])?$Arguments['year']:'0000-0000';
  $finn_year=explode("-", $Arguments['year']); 
  $empId =isset($Arguments['userId'])?$Arguments['userId']:0; 
  //print_r($empId); exit;
  if($empId <= 0)
  {
     echo "Sorry You are not allowed. Wrong Try";
     exit;
  }


function addzero($val){
	if(strlen($val) == 1){
		return '0'.$val;
	}
	else {
		return $val;
	}
}

function date_frmt($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01'){
		return "---";
	}
	else{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}
function get_month($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01'){
		return "---";
	}
	else{
		
		return $newDate = date(" F Y", strtotime($original_date));
		//require  date('l jS \of F Y');
	}
}

function GetMonthString($n)
{
$n=(int)$n;
    $timestamp = mktime(0, 0, 0, $n);
    
    return date("F", $timestamp);
}

function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
				return $key['description'];
			}
		}
	}
	
	function fun_desig1($dsig)
	{ 
		$db = new database();
		$data = $db->fetch_table("
		SELECT designation_id, designation_name
		FROM zpemp_emp_desig_master where designation_id='".$dsig."'");
		return $data[0]['designation_name'];
	}
	function fun_desig($dsig)
		    { 
			    $db = new database();
			    /*echo("select description,code 
			    from prd_dise_code_master where code='".$dsig."'");die;*/
			    $data = $db->fetch_table("select description,code 
			    from prd_dise_code_master where code='".$dsig."'");
			    return $data[0]['description'];
		    }
function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return "--";
	}
	else{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}

function set_date($original_date){
	return substr($original_date, 0,10);
} 



	
	
		
			$db = new database();
			
			
			
	    $emp_name = $db->fetch_table("
                                SELECT 
								emp_first_name, emp_second_name, emp_last_name,emp_pan_no,emp_desig,gp_id_fk,ps_id_fk,zp_id_fk,emp_id_pk
								FROM prd_employee_master 
                                WHERE emp_id_pk = '".$empId."'
                                ");
								
								
					if($emp_name[0]['gp_id_fk']!='0')
				    {	$designation=fun_desig($emp_name[0]['emp_desig']);
					}
					else if($emp_name[0]['ps_id_fk']!='0')
					{
						$designation=fun_desig($emp_name[0]['emp_desig']);
					}
					else if($emp_name[0]['zp_id_fk']!='0')
					{
						$designation=fun_desig1($emp_name[0]['emp_desig']);
					}
			
			$tch=array();
			$tch_bonus=array();
			$tch_arrear=array();
			
			for($x=0; $x<1; $x++){
				for($y=3; $y<=14; $y++){
					
					   if($x.$y =='010'){$finn_year_month= $finn_year[0].'10';} else if($x.$y =='011'){$finn_year_month= $finn_year[0].'11';} else if($x.$y =='012'){$finn_year_month= $finn_year[0].'12';} else if($x.$y =='013'){$finn_year_month= $finn_year[1].'01';} else if($x.$y =='014'){$finn_year_month= $finn_year[1].'02';} else { $finn_year_month= $finn_year[0].$x.$y;}
					
					
					
					
			
							$tch_fetch=$db->fetch_table("SELECT salary_monthyear,basic,da,ma,hra,gross_salary,gsli,gpf,p_tax,i_tax,net,festival_loan,overdrawn,conv_allow,hill_allowance,allowance,consolidated_pay,hra_deduction
							FROM prd_monthly_salary_archive_final 
							WHERE emp_id_fk='".$emp_name[0]['emp_id_pk']."'  and delete_status='1' 
							and status_flag in('3','4') AND CAST(salary_monthyear as integer)= $finn_year_month 
							order by salary_monthyear DESC limit 1");
							$tch[] = $tch_fetch;
							
							

							
							$bonus=$db->fetch_table("SELECT bill.salary_monthyear,sal.bonus_amount
							FROM prd_employee_master AS emp
							INNER JOIN prd_employee_bonus_details AS sal ON emp.emp_id_pk= sal.emp_id_fk
							inner join prd_block_bill_details as bill on bill.block_bill_pk= sal.bill_id_fk
							WHERE emp.emp_id_pk='".$empId."'  and sal.bill_id_fk!='0' and sal.delete_status='1'
						  AND CAST(bill.salary_monthyear as integer)= $finn_year_month  
							order by bill.salary_monthyear");
							
							$tch_bonus[] = $bonus;
							
							
							$arraer=$db->fetch_table("SELECT bill.salary_monthyear,aer.gross_salary FROM
							prd_employee_arrear AS aer 
							inner join prd_block_bill_details as bill on bill.block_bill_pk= aer.bill_id_fk
							WHERE aer.emp_id_fk='".$empId."'  and aer.bill_id_fk!='0' and aer.delete_status='1'
						  AND CAST(bill.salary_monthyear as integer)= $finn_year_month  
							order by bill.salary_monthyear");
							
							$tch_arrear[] = $arraer;
			
				}
		}
			
								
$date = $tch[0]['latestupdate_time'];
$msg="";
if($tch[0][0]['status_flag'] == 1){ 
	$msg="Not finalized (Just Saved)";
}					
elseif($tch[0][0]['status_flag'] == 2){ 
	$msg="Requisition finalized by CIRCLE";
} elseif($tch[0][0]['status_flag'] == 3){
	$msg="Requisition finalized by DPSC";
} 
?>


 <? 
  ///////////////////////////////// component total////////////////////////
  $c_total_basic=$tch[0][0]['basic']+$tch[1][0]['basic']+$tch[2][0]['basic']+$tch[3][0]['basic']+$tch[4][0]['basic']+$tch[5][0]['basic']+$tch[6][0]['basic']+$tch[7][0]['basic']+$tch[8][0]['basic']+$tch[9][0]['basic']+$tch[10][0]['basic']+$tch[11][0]['basic'];
  
  $c_total_da=$tch[0][0]['da']+$tch[1][0]['da']+$tch[2][0]['da']+$tch[3][0]['da']+$tch[4][0]['da']+$tch[5][0]['da']+$tch[6][0]['da']+$tch[7][0]['da']+$tch[8][0]['da']+$tch[9][0]['da']+$tch[10][0]['da']+$tch[11][0]['da'];
  
  $c_total_ma=$tch[0][0]['ma']+$tch[1][0]['ma']+$tch[2][0]['ma']+$tch[3][0]['ma']+$tch[4][0]['ma']+$tch[5][0]['ma']+$tch[6][0]['ma']+$tch[7][0]['ma']+$tch[8][0]['ma']+$tch[9][0]['ma']+$tch[10][0]['ma']+$tch[11][0]['ma'];
  
    $c_total_hra=$tch[0][0]['hra']+$tch[1][0]['hra']+$tch[2][0]['hra']+$tch[3][0]['hra']+$tch[4][0]['hra']+$tch[5][0]['hra']+$tch[6][0]['hra']+$tch[7][0]['hra']+$tch[8][0]['hra']+$tch[9][0]['hra']+$tch[10][0]['hra']+$tch[11][0]['hra'];
  
   $c_total_gsli=$tch[0][0]['gsli']+$tch[1][0]['gsli']+$tch[2][0]['gsli']+$tch[3][0]['gsli']+$tch[4][0]['gsli']+$tch[5][0]['gsli']+$tch[6][0]['gsli']+$tch[7][0]['gsli']+$tch[8][0]['gsli']+$tch[9][0]['gsli']+$tch[10][0]['gsli']+$tch[11][0]['gsli'];
   
   $c_total_ptax=$tch[0][0]['p_tax']+$tch[1][0]['p_tax']+$tch[2][0]['p_tax']+$tch[3][0]['p_tax']+$tch[4][0]['p_tax']+$tch[5][0]['p_tax']+$tch[6][0]['p_tax']+$tch[7][0]['p_tax']+$tch[8][0]['p_tax']+$tch[9][0]['p_tax']+$tch[10][0]['p_tax']+$tch[11][0]['p_tax'];
   
   $c_total_itax=$tch[0][0]['i_tax']+$tch[1][0]['i_tax']+$tch[2][0]['i_tax']+$tch[3][0]['i_tax']+$tch[4][0]['i_tax']+$tch[5][0]['i_tax']+$tch[6][0]['i_tax']+$tch[7][0]['i_tax']+$tch[8][0]['i_tax']+$tch[9][0]['i_tax']+$tch[10][0]['i_tax']+$tch[11][0]['i_tax'];
   
     $c_total_gross=$tch[0][0]['gross_salary']+$tch[1][0]['gross_salary']+$tch[2][0]['gross_salary']+$tch[3][0]['gross_salary']+$tch[4][0]['gross_salary']+$tch[5][0]['gross_salary']+$tch[6][0]['gross_salary']+$tch[7][0]['gross_salary']+$tch[8][0]['gross_salary']+$tch[9][0]['gross_salary']+$tch[10][0]['gross_salary']+$tch[11][0]['gross_salary'];
 
 $c_total_gpf=$tch[0][0]['gpf']+$tch[1][0]['gpf']+$tch[2][0]['gpf']+$tch[3][0]['gpf']+$tch[4][0]['gpf']+$tch[5][0]['gpf']+$tch[6][0]['gpf']+$tch[7][0]['gpf']+$tch[8][0]['gpf']+$tch[9][0]['gpf']+$tch[10][0]['gpf']+$tch[11][0]['gpf'];
 
 $c_total_net=$tch[0][0]['net']+$tch[1][0]['net']+$tch[2][0]['net']+$tch[3][0]['net']+$tch[4][0]['net']+$tch[5][0]['net']+$tch[6][0]['net']+$tch[7][0]['net']+$tch[8][0]['net']+$tch[9][0]['net']+$tch[10][0]['net']+$tch[11][0]['net'];
 
 $c_total_bonus=$tch_bonus[0][0]['bonus_amount']+$tch_bonus[1][0]['bonus_amount']+$tch_bonus[2][0]['bonus_amount']+$tch_bonus[3][0]['bonus_amount']+$tch_bonus[4][0]['bonus_amount']+$tch_bonus[5][0]['bonus_amount']+$tch_bonus[6][0]['bonus_amount']+$tch_bonus[7][0]['bonus_amount']+$tch_bonus[8][0]['bonus_amount']+$tch_bonus[9][0]['bonus_amount']+$tch_bonus[10][0]['bonus_amount']+$tch_bonus[11][0]['bonus_amount'];
 
 $c_total_arrear=$tch_arrear[0][0]['gross_salary']+$tch_arrear[1][0]['gross_salary']+$tch_arrear[2][0]['gross_salary']+$tch_arrear[3][0]['gross_salary']+$tch_arrear[4][0]['gross_salary']+$tch_arrear[5][0]['gross_salary']+$tch_arrear[6][0]['gross_salary']+$tch_arrear[7][0]['gross_salary']+$tch_arrear[8][0]['gross_salary']+$tch_arrear[9][0]['gross_salary']+$tch_arrear[10][0]['gross_salary']+$tch_arrear[11][0]['gross_salary'];
 
 $c_total_festival=$tch[0][0]['festival_loan']+$tch[1][0]['festival_loan']+$tch[2][0]['festival_loan']+$tch[3][0]['festival_loan']+$tch[4][0]['festival_loan']+$tch[5][0]['festival_loan']+$tch[6][0]['festival_loan']+$tch[7][0]['festival_loan']+$tch[8][0]['festival_loan']+$tch[9][0]['festival_loan']+$tch[10][0]['festival_loan']+$tch[11][0]['festival_loan'];

 $c_total_hra_deduction=$tch[0][0]['hra_deduction']+$tch[1][0]['hra_deduction']+$tch[2][0]['hra_deduction']+$tch[3][0]['hra_deduction']+$tch[4][0]['hra_deduction']+$tch[5][0]['hra_deduction']+$tch[6][0]['hra_deduction']+$tch[7][0]['hra_deduction']+$tch[8][0]['hra_deduction']+$tch[9][0]['hra_deduction']+$tch[10][0]['hra_deduction']+$tch[11][0]['hra_deduction'];
 
 $c_total_over=$tch[0][0]['overdrawn']+$tch[1][0]['overdrawn']+$tch[2][0]['overdrawn']+$tch[3][0]['overdrawn']+$tch[4][0]['overdrawn']+$tch[5][0]['overdrawn']+$tch[6][0]['overdrawn']+$tch[7][0]['overdrawn']+$tch[8][0]['overdrawn']+$tch[9][0]['overdrawn']+$tch[10][0]['overdrawn']+$tch[11][0]['overdrawn'];
 
 $c_total_conv=$tch[0][0]['conv_allow']+$tch[1][0]['conv_allow']+$tch[2][0]['conv_allow']+$tch[3][0]['conv_allow']+$tch[4][0]['conv_allow']+$tch[5][0]['conv_allow']+$tch[6][0]['conv_allow']+$tch[7][0]['conv_allow']+$tch[8][0]['conv_allow']+$tch[9][0]['conv_allow']+$tch[10][0]['conv_allow']+$tch[11][0]['conv_allow'];
 
 $c_total_hill=$tch[0][0]['hill_allowance']+$tch[1][0]['hill_allowance']+$tch[2][0]['hill_allowance']+$tch[3][0]['hill_allowance']+$tch[4][0]['hill_allowance']+$tch[5][0]['hill_allowance']+$tch[6][0]['hill_allowance']+$tch[7][0]['hill_allowance']+$tch[8][0]['hill_allowance']+$tch[9][0]['hill_allowance']+$tch[10][0]['hill_allowance']+$tch[11][0]['hill_allowance'];
 
 $c_total_special=$tch[0][0]['allowance']+$tch[1][0]['allowance']+$tch[2][0]['allowance']+$tch[3][0]['allowance']+$tch[4][0]['allowance']+$tch[5][0]['allowance']+$tch[6][0]['allowance']+$tch[7][0]['allowance']+$tch[8][0]['allowance']+$tch[9][0]['allowance']+$tch[10][0]['allowance']+$tch[11][0]['allowance'];
 
 $c_total_conso=$tch[0][0]['consolidated_pay']+$tch[1][0]['consolidated_pay']+$tch[2][0]['consolidated_pay']+$tch[3][0]['consolidated_pay']+$tch[4][0]['consolidated_pay']+$tch[5][0]['consolidated_pay']+$tch[6][0]['consolidated_pay']+$tch[7][0]['consolidated_pay']+$tch[8][0]['consolidated_pay']+$tch[9][0]['consolidated_pay']+$tch[10][0]['consolidated_pay']+$tch[11][0]['consolidated_pay'];
 ///////////////////////////////// component total////////////////////////
 
  /////////////////////////// total/////////////////////////////
 
 $t_total_march=$tch[0][0]['basic']+$tch[0][0]['da']+$tch[0][0]['gsli']+$tch[0][0]['p_tax']+$tch[0][0]['i_tax']+$tch[0][0]['gross_salary']+$tch[0][0]['gpf']+ $tch[0][0]['net']+$tch[0][0]['hra']+$tch[0][0]['ma']+$tch_bonus[0][0]['bonus_amount']+ $tch[0][0]['overdrawn']+ $tch[0][0]['festival_loan']+$tch[0][0]['conv_allow']+$tch[0][0]['hill_allowance']+$tch[0][0]['allowance'];
 
 $t_total_april=$tch[1][0]['basic']+$tch[1][0]['da']+$tch[1][0]['gsli']+$tch[1][0]['p_tax']+$tch[1][0]['i_tax']+$tch[1][0]['gross_salary']+$tch[1][0]['gpf']+ $tch[1][0]['net']+$tch_bonus[1][0]['bonus_amount']+ $tch[1][0]['overdrawn']+ $tch[1][0]['festival_loan']+$tch[1][0]['conv_allow']+$tch[1][0]['hill_allowance']+$tch[1][0]['allowance'];
 
 $t_total_may=$tch[2][0]['basic']+$tch[2][0]['da']+$tch[2][0]['gsli']+$tch[2][0]['p_tax']+$tch[2][0]['i_tax']+$tch[2][0]['gross_salary']+$tch[2][0]['gpf']+ $tch[2][0]['net']+$tch[2][0]['hra']+$tch[2][0]['ma']+$tch_bonus[2][0]['bonus_amount']+ $tch[2][0]['overdrawn']+ $tch[2][0]['festival_loan']+$tch[2][0]['conv_allow']+$tch[2][0]['hill_allowance']+$tch[2][0]['allowance'];
 
 $t_total_june=$tch[3][0]['basic']+$tch[3][0]['da']+$tch[3][0]['gsli']+$tch[3][0]['p_tax']+$tch[3][0]['i_tax']+$tch[3][0]['gross_salary']+$tch[3][0]['gpf']+ $tch[3][0]['net']+$tch[3][0]['hra']+$tch[3][0]['ma']+$tch_bonus[3][0]['bonus_amount']+ $tch[3][0]['overdrawn']+ $tch[3][0]['festival_loan']+$tch[3][0]['conv_allow']+$tch[3][0]['hill_allowance']+$tch[3][0]['allowance'];
 
 $t_total_july=$tch[4][0]['basic']+$tch[4][0]['da']+$tch[4][0]['gsli']+$tch[4][0]['p_tax']+$tch[4][0]['i_tax']+$tch[4][0]['gross_salary']+$tch[4][0]['gpf']+ $tch[4][0]['net']+$tch[4][0]['hra']+$tch[4][0]['ma']+$tch_bonus[4][0]['bonus_amount']+ $tch[4][0]['overdrawn']+ $tch[4][0]['festival_loan']+$tch[4][0]['conv_allow']+$tch[4][0]['hill_allowance']+$tch[4][0]['allowance'];
 
 $t_total_aug=$tch[5][0]['basic']+$tch[5][0]['da']+$tch[5][0]['gsli']+$tch[5][0]['p_tax']+$tch[5][0]['i_tax']+$tch[5][0]['gross_salary']+$tch[5][0]['gpf']+ $tch[5][0]['net']+$tch[5][0]['hra']+$tch[5][0]['ma']+$tch_bonus[5][0]['bonus_amount']+ $tch[5][0]['overdrawn']+ $tch[5][0]['festival_loan']+$tch[5][0]['conv_allow']+$tch[5][0]['hill_allowance']+$tch[5][0]['allowance'];
 
 $t_total_sep=$tch[6][0]['basic']+$tch[6][0]['da']+$tch[6][0]['gsli']+$tch[6][0]['p_tax']+$tch[6][0]['i_tax']+$tch[6][0]['gross_salary']+$tch[6][0]['gpf']+ $tch[6][0]['net']+$tch[6][0]['hra']+$tch[6][0]['ma']+$tch_bonus[6][0]['bonus_amount']+ $tch[6][0]['overdrawn']+ $tch[6][0]['festival_loan']+$tch[6][0]['conv_allow']+$tch[6][0]['hill_allowance']+$tch[6][0]['allowance'];
 
 $t_total_oct=$tch[7][0]['basic']+$tch[7][0]['da']+$tch[7][0]['gsli']+$tch[7][0]['p_tax']+$tch[7][0]['i_tax']+$tch[7][0]['gross_salary']+$tch[7][0]['gpf']+ $tch[7][0]['net']+$tch[7][0]['hra']+$tch[7][0]['ma']+$tch_bonus[7][0]['bonus_amount']+ $tch[7][0]['overdrawn']+ $tch[7][0]['festival_loan']+$tch[7][0]['conv_allow']+$tch[7][0]['hill_allowance']+$tch[7][0]['allowance'];
 
 $t_total_nov=$tch[8][0]['basic']+$tch[8][0]['da']+$tch[8][0]['gsli']+$tch[8][0]['p_tax']+$tch[8][0]['i_tax']+$tch[8][0]['gross_salary']+$tch[8][0]['gpf']+ $tch[8][0]['net']+$tch[8][0]['hra']+$tch[8][0]['ma']+$tch_bonus[8][0]['bonus_amount']+ $tch[8][0]['overdrawn']+ $tch[8][0]['festival_loan']+$tch[8][0]['conv_allow']+$tch[8][0]['hill_allowance']+$tch[8][0]['allowance'];
 
 $t_total_dec=$tch[9][0]['basic']+$tch[9][0]['da']+$tch[9][0]['gsli']+$tch[9][0]['p_tax']+$tch[9][0]['i_tax']+$tch[9][0]['gross_salary']+$tch[9][0]['gpf']+ $tch[9][0]['net']+$tch[9][0]['hra']+$tch[9][0]['ma']+$tch_bonus[9][0]['bonus_amount']+ $tch[9][0]['overdrawn']+ $tch[9][0]['festival_loan']+$tch[9][0]['conv_allow']+$tch[9][0]['hill_allowance']+$tch[9][0]['allowance'];
 
 $t_total_janu=$tch[10][0]['basic']+$tch[10][0]['da']+$tch[10][0]['gsli']+$tch[10][0]['p_tax']+$tch[10][0]['i_tax']+$tch[10][0]['gross_salary']+$tch[10][0]['gpf']+ $tch[10][0]['net']+$tch[10][0]['hra']+$tch[10][0]['ma']+$tch_bonus[10][0]['bonus_amount']+ $tch[10][0]['overdrawn']+ $tch[10][0]['festival_loan']+$tch[10][0]['conv_allow']+$tch[10][0]['hill_allowance']+$tch[10][0]['allowance'];
 
 $t_total_feb=$tch[11][0]['basic']+$tch[11][0]['da']+$tch[11][0]['gsli']+$tch[11][0]['p_tax']+$tch[11][0]['i_tax']+$tch[11][0]['gross_salary']+$tch[11][0]['gpf']+ $tch[11][0]['net']+$tch[11][0]['hra']+$tch[11][0]['ma']+$tch_bonus[11][0]['bonus_amount']+ $tch[11][0]['overdrawn']+ $tch[11][0]['festival_loan']+$tch[11][0]['conv_allow']+$tch[11][0]['hill_allowance']+$tch[11][0]['allowance'];
 
 $t_total_com=$c_total_basic+$c_total_da+$c_total_ma+$c_total_hra+$c_total_gsli+$c_total_ptax+$c_total_itax+$c_total_gross+$c_total_gpf+$c_total_net+$c_total_bonus+$c_total_festival+$c_total_over+$c_total_conv+$c_total_hill+$c_total_special;
 
 /////////////////////////// total/////////////////////////////
 ?>


    <table width="100">
    <tr>
    <th colspan="15" style="text-align:center;"><br />STATEMENT OF SALARY INCOME OF <?php echo $emp_name[0]['emp_first_name'].' '.$emp_name[0]['emp_second_name'].' '.$emp_name[0]['emp_last_name']?><br /></th>
    </tr>
    <tr>
    <th colspan="3"><br />F.Y:<?= $finn_year[0].'-'.$finn_year[1]?><br /></th>
    <th colspan="6"><br />DESIGNATION:<?php echo $designation; ?><br /></th>
    
    <th colspan="9"><br />PAN:<?= $emp_name[0]['emp_pan_no']?><br /></th>
    </tr>
    <tr>    
    </tr>
    </table>

		
	
    <table width="100" border="1">
   <tr>
    <th scope="col">COMPONENT</th>
    <th scope="col">CURRENCY</th>
    <th scope="col">MARCH <?= $finn_year[0];?></th>
    <th scope="col">APRIL <?= $finn_year[0];?></th>
    <th scope="col">MAY <?= $finn_year[0];?></th>
    <th scope="col">JUNE <?= $finn_year[0];?></th>
    <th scope="col">JULY<?= $finn_year[0];?></th>
    <th scope="col">AUGUST<?= $finn_year[0];?></th>
    <th scope="col">SEPTEMBER<?= $finn_year[0];?></th>
    <th scope="col">OCTOBER<?= $finn_year[0];?></th>
    <th scope="col">NOVEMBER<?= $finn_year[0];?></th>
    <th scope="col">DECEMBER<?= $finn_year[0];?></th>
    <th scope="col">JANUARY<?= $finn_year[1];?></th>
    <th scope="col">FEBRUARY<?= $finn_year[1];?></th>
    <th scope="col"><b>COMPONENT TOTAL</b></th>
    <!--<th scope="col">ARREAR</th>
    <th scope="col">GRAND TOTAL</th>-->
    
   </tr>
   
   <?php  if($emp_name[0]['emp_desig']=='9012' || $emp_name[0]['emp_desig']=='9013' || $emp_name[0]['emp_desig']=='9014' || $emp_name[0]['emp_desig']=='9015' || $emp_name[0]['emp_desig']=='9016' || $emp_name[0]['emp_desig']=='9017' || $emp_name[0]['emp_desig']=='1120' || $emp_name[0]['emp_desig']=='1124' || $emp_name[0]['emp_desig']=='1125' || $emp_name[0]['emp_desig']=='1'){?>
     <tr>
		<th scope="col">CONSOLITED PAY</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[1][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[2][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[3][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[4][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[5][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[6][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[7][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[8][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[9][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[10][0]['consolidated_pay'];?></td>
		<td><?php echo $tch[11][0]['consolidated_pay'];?></td>
		<td><b><?php echo $c_total_conso;?></b></td>
		<!--<td></td>
		<td></td>-->
    </tr>
   
  
   
   <?php }else{?>
   
   <tr>
		<th scope="col">BP</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['basic'];?></td>
		<td><?php echo $tch[1][0]['basic'];?></td>
		<td><?php echo $tch[2][0]['basic'];?></td>
		<td><?php echo $tch[3][0]['basic'];?></td>
		<td><?php echo $tch[4][0]['basic'];?></td>
		<td><?php echo $tch[5][0]['basic'];?></td>
		<td><?php echo $tch[6][0]['basic'];?></td>
		<td><?php echo $tch[7][0]['basic'];?></td>
		<td><?php echo $tch[8][0]['basic'];?></td>
		<td><?php echo $tch[9][0]['basic'];?></td>
		<td><?php echo $tch[10][0]['basic'];?></td>
		<td><?php echo $tch[11][0]['basic'];?></td>
		<td><b><?php echo $c_total_basic;?></b></td>
		<!--<td></td>
		<td></td>-->
     
  </tr>
  <tr>
		 <th scope="col">DA</th>
		 <td>RS</td>
		 <td><?php echo $tch[0][0]['da'];?></td>
		 <td><?php echo $tch[1][0]['da'];?></td>
		 <td><?php echo $tch[2][0]['da'];?></td>
		 <td><?php echo $tch[3][0]['da'];?></td>
		 <td><?php echo $tch[4][0]['da'];?></td>
		 <td><?php echo $tch[5][0]['da'];?></td>
		 <td><?php echo $tch[6][0]['da'];?></td>
		 <td><?php echo $tch[7][0]['da'];?></td>
		 <td><?php echo $tch[8][0]['da'];?></td>
		 <td><?php echo $tch[9][0]['da'];?></td>
		 <td><?php echo $tch[10][0]['da'];?></td>
		 <td><?php echo $tch[11][0]['da'];?></td>
		 <td><b><?php echo $c_total_da;?></b></td>
		 <!--<td></td>
		 <td></td>-->
       
  </tr>
  
   <tr>
		<th scope="col">HRA</th>
			<td>RS</td>
			<td><?php echo $tch[0][0]['hra'];?></td>
			<td><?php echo $tch[1][0]['hra'];?></td>
			<td><?php echo $tch[2][0]['hra'];?></td>
			<td><?php echo $tch[3][0]['hra'];?></td>
			<td><?php echo $tch[4][0]['hra'];?></td>
			<td><?php echo $tch[5][0]['hra'];?></td>
			<td><?php echo $tch[6][0]['hra'];?></td>
			<td><?php echo $tch[7][0]['hra'];?></td>
			<td><?php echo $tch[8][0]['hra'];?></td>
			<td><?php echo $tch[9][0]['hra'];?></td>
			<td><?php echo $tch[10][0]['hra'];?></td>
			<td><?php echo $tch[11][0]['hra'];?></td>
			<td><b><?php echo $c_total_hra;?></b></td>

        </tr>
        
        <tr>
			<th scope="col">MA</th>
			<td>RS</td>
			<td><?php echo $tch[0][0]['ma'];?></td>
			<td><?php echo $tch[1][0]['ma'];?></td>
			<td><?php echo $tch[2][0]['ma'];?></td>
			<td><?php echo $tch[3][0]['ma'];?></td>
			<td><?php echo $tch[4][0]['ma'];?></td>
			<td><?php echo $tch[5][0]['ma'];?></td>
			<td><?php echo $tch[6][0]['ma'];?></td>
			<td><?php echo $tch[7][0]['ma'];?></td>
			<td><?php echo $tch[8][0]['ma'];?></td>
			<td><?php echo $tch[9][0]['ma'];?></td>
			<td><?php echo $tch[10][0]['ma'];?></td>
			<td><?php echo $tch[11][0]['ma'];?></td>
			<td><b><?php echo $c_total_ma;?></b></td>

        </tr>
       
        <tr>
			<th scope="col">CONVEYANCE ALLOWANCE</th>
			<td>RS</td>
			<td><?php echo $tch[0][0]['conv_allow'];?></td>
			<td><?php echo $tch[1][0]['conv_allow'];?></td>
			<td><?php echo $tch[2][0]['conv_allow'];?></td>
			<td><?php echo $tch[3][0]['conv_allow'];?></td>
			<td><?php echo $tch[4][0]['conv_allow'];?></td>
			<td><?php echo $tch[5][0]['conv_allow'];?></td>
			<td><?php echo $tch[6][0]['conv_allow'];?></td>
			<td><?php echo $tch[7][0]['conv_allow'];?></td>
			<td><?php echo $tch[8][0]['conv_allow'];?></td>
			<td><?php echo $tch[9][0]['conv_allow'];?></td>
			<td><?php echo $tch[10][0]['conv_allow'];?></td>
			<td><?php echo $tch[11][0]['conv_allow'];?></td>

			<td><b><?php echo $c_total_conv;?></b></td>

        </tr>
        
        <tr>
			<th scope="col">HILL ALLOWANCE</th>
			<td>RS</td>
			<td><?php echo $tch[0][0]['hill_allowance'];?></td>
			<td><?php echo $tch[1][0]['hill_allowance'];?></td>
			<td><?php echo $tch[2][0]['hill_allowance'];?></td>
			<td><?php echo $tch[3][0]['hill_allowance'];?></td>
			<td><?php echo $tch[4][0]['hill_allowance'];?></td>
			<td><?php echo $tch[5][0]['hill_allowance'];?></td>
			<td><?php echo $tch[6][0]['hill_allowance'];?></td>
			<td><?php echo $tch[7][0]['hill_allowance'];?></td>
			<td><?php echo $tch[8][0]['hill_allowance'];?></td>
			<td><?php echo $tch[9][0]['hill_allowance'];?></td>
			<td><?php echo $tch[10][0]['hill_allowance'];?></td>
			<td><?php echo $tch[11][0]['hill_allowance'];?></td>
			<td><b><?php echo $c_total_hill;?></b></td>

        </tr>
		
		<tr>
			<th scope="col">SPECIAL ALLOWANCE</th>
			<td>RS</td>
			<td><?php echo $tch[0][0]['allowance'];?></td>
			<td><?php echo $tch[1][0]['allowance'];?></td>
			<td><?php echo $tch[2][0]['allowance'];?></td>
			<td><?php echo $tch[3][0]['allowance'];?></td>
			<td><?php echo $tch[4][0]['allowance'];?></td>
			<td><?php echo $tch[5][0]['allowance'];?></td>
			<td><?php echo $tch[6][0]['allowance'];?></td>
			<td><?php echo $tch[7][0]['allowance'];?></td>
			<td><?php echo $tch[8][0]['allowance'];?></td>
			<td><?php echo $tch[9][0]['allowance'];?></td>
			<td><?php echo $tch[10][0]['allowance'];?></td>
			<td><?php echo $tch[11][0]['allowance'];?></td>
			<td><b><?php echo $c_total_special;?></b></td>

        </tr>
		
  <?php }?>
  
  <tr>
     <th scope="col">GROSS</th>
     <td>RS</td>
      <td><?php echo $tch[0][0]['gross_salary'];?></td>
     <td><?php echo $tch[1][0]['gross_salary'];?></td>
     <td><?php echo $tch[2][0]['gross_salary'];?></td>
     <td><?php echo $tch[3][0]['gross_salary'];?></td>
     <td><?php echo $tch[4][0]['gross_salary'];?></td>
     <td><?php echo $tch[5][0]['gross_salary'];?></td>
     <td><?php echo $tch[6][0]['gross_salary'];?></td>
     <td><?php echo $tch[7][0]['gross_salary'];?></td>
     <td><?php echo $tch[8][0]['gross_salary'];?></td>
     <td><?php echo $tch[9][0]['gross_salary'];?></td>
     <td><?php echo $tch[10][0]['gross_salary'];?></td>
     <td><?php echo $tch[11][0]['gross_salary'];?></td>
     <td><b><?php echo $c_total_gross;?></b></td>
     <!--<td></td>
     <td></td>-->
     
  </tr>
  <tr>
     <th scope="col">GSLI</th>
      <td>RS</td>
      <td><?php echo $tch[0][0]['gsli'];?></td>
     <td><?php echo $tch[1][0]['gsli'];?></td>
     <td><?php echo $tch[2][0]['gsli'];?></td>
     <td><?php echo $tch[3][0]['gsli'];?></td>
     <td><?php echo $tch[4][0]['gsli'];?></td>
     <td><?php echo $tch[5][0]['gsli'];?></td>
     <td><?php echo $tch[6][0]['gsli'];?></td>
     <td><?php echo $tch[7][0]['gsli'];?></td>
     <td><?php echo $tch[8][0]['gsli'];?></td>
     <td><?php echo $tch[9][0]['gsli'];?></td>
     <td><?php echo $tch[10][0]['gsli'];?></td>
     <td><?php echo $tch[11][0]['gsli'];?></td>
     <td><b><?php echo $c_total_gsli;?></b></td>
     <!--<td></td>
     <td></td>-->
  </tr>
  <tr>
     <th scope="col">GPF</th>
     <td>RS</td>
      <td><?php echo $tch[0][0]['gpf'];?></td>
     <td><?php echo $tch[1][0]['gpf'];?></td>
     <td><?php echo $tch[2][0]['gpf'];?></td>
     <td><?php echo $tch[3][0]['gpf'];?></td>
     <td><?php echo $tch[4][0]['gpf'];?></td>
     <td><?php echo $tch[5][0]['gpf'];?></td>
     <td><?php echo $tch[6][0]['gpf'];?></td>
     <td><?php echo $tch[7][0]['gpf'];?></td>
     <td><?php echo $tch[8][0]['gpf'];?></td>
     <td><?php echo $tch[9][0]['gpf'];?></td>
     <td><?php echo $tch[10][0]['gpf'];?></td>
     <td><?php echo $tch[11][0]['gpf'];?></td>
     <td><b><?php echo $c_total_gpf;?></b></td>
     <!--<td></td>
     <td></td>-->
     
  </tr>
  <tr>
     <th scope="col">IT</th>
     <td>RS</td>
      <td><?php echo $tch[0][0]['i_tax'];?></td>
     <td><?php echo $tch[1][0]['i_tax'];?></td>
     <td><?php echo $tch[2][0]['i_tax'];?></td>
     <td><?php echo $tch[3][0]['i_tax'];?></td>
     <td><?php echo $tch[4][0]['i_tax'];?></td>
     <td><?php echo $tch[5][0]['i_tax'];?></td>
     <td><?php echo $tch[6][0]['i_tax'];?></td>
     <td><?php echo $tch[7][0]['i_tax'];?></td>
     <td><?php echo $tch[8][0]['i_tax'];?></td>
     <td><?php echo $tch[9][0]['i_tax'];?></td>
     <td><?php echo $tch[10][0]['i_tax'];?></td>
     <td><?php echo $tch[11][0]['i_tax'];?></td>
     <td><b><?php echo $c_total_itax;?></b></td>
     <!--<td></td>
     <td></td>-->
  </tr>
  <tr>
     <th scope="col">PT</th>
      <td>RS</td>
      <td><?php echo $tch[0][0]['p_tax'];?></td>
     <td><?php echo $tch[1][0]['p_tax'];?></td>
     <td><?php echo $tch[2][0]['p_tax'];?></td>
     <td><?php echo $tch[3][0]['p_tax'];?></td>
     <td><?php echo $tch[4][0]['p_tax'];?></td>
     <td><?php echo $tch[5][0]['p_tax'];?></td>
     <td><?php echo $tch[6][0]['p_tax'];?></td>
     <td><?php echo $tch[7][0]['p_tax'];?></td>
     <td><?php echo $tch[8][0]['p_tax'];?></td>
     <td><?php echo $tch[9][0]['p_tax'];?></td>
     <td><?php echo $tch[10][0]['p_tax'];?></td>
     <td><?php echo $tch[11][0]['p_tax'];?></td>
     <td><b><?php echo $c_total_ptax;?></b></td>
     <!--<td></td>
     <td></td>-->
   </tr>
       <tr>
		<th scope="col">OVERDRAWN</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['overdrawn'];?></td>
		<td><?php echo $tch[1][0]['overdrawn'];?></td>
		<td><?php echo $tch[2][0]['overdrawn'];?></td>
		<td><?php echo $tch[3][0]['overdrawn'];?></td>
		<td><?php echo $tch[4][0]['overdrawn'];?></td>
		<td><?php echo $tch[5][0]['overdrawn'];?></td>
		<td><?php echo $tch[6][0]['overdrawn']; ?></td>
		<td><?php echo $tch[7][0]['overdrawn'];?></td>
		<td><?php echo $tch[8][0]['overdrawn'];?></td>
		<td><?php echo $tch[9][0]['overdrawn'];?></td>
		<td><?php echo $tch[10][0]['overdrawn'];?></td>
		<td><?php echo $tch[11][0]['overdrawn'];?></td>
		<td><b><?php echo $c_total_over;?></b></td>

     
     
  </tr>
        <tr>
		<th scope="col">FESTIVAL</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['festival_loan'];?></td>
		<td><?php echo $tch[1][0]['festival_loan'];?></td>
		<td><?php echo $tch[2][0]['festival_loan'];?></td>
		<td><?php echo $tch[3][0]['festival_loan'];?></td>
		<td><?php echo $tch[4][0]['festival_loan'];?></td>
		<td><?php echo $tch[5][0]['festival_loan'];?></td>
		<td><?php echo $tch[6][0]['festival_loan']; ?></td>
		<td><?php echo $tch[7][0]['festival_loan'];?></td>
		<td><?php echo $tch[8][0]['festival_loan'];?></td>
		<td><?php echo $tch[9][0]['festival_loan'];?></td>
		<td><?php echo $tch[10][0]['festival_loan'];?></td>
		<td><?php echo $tch[11][0]['festival_loan'];?></td>
		<td><b><?php echo $c_total_festival;?></b></td>

     
     
  </tr>
  
  <tr>
		<th scope="col">HRA DEDUCTION</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['hra_deduction'];?></td>
		<td><?php echo $tch[1][0]['hra_deduction'];?></td>
		<td><?php echo $tch[2][0]['hra_deduction'];?></td>
		<td><?php echo $tch[3][0]['hra_deduction'];?></td>
		<td><?php echo $tch[4][0]['hra_deduction'];?></td>
		<td><?php echo $tch[5][0]['hra_deduction'];?></td>
		<td><?php echo $tch[6][0]['hra_deduction']; ?></td>
		<td><?php echo $tch[7][0]['hra_deduction'];?></td>
		<td><?php echo $tch[8][0]['hra_deduction'];?></td>
		<td><?php echo $tch[9][0]['hra_deduction'];?></td>
		<td><?php echo $tch[10][0]['hra_deduction'];?></td>
		<td><?php echo $tch[11][0]['hra_deduction'];?></td>
		<td><b><?php echo $c_total_hra_deduction;?></b></td>
		<!--<td></td>
		<td></td>-->
     
     
  </tr>    
  </tr>
   <tr>
     <th scope="col">NET</th>
      <td>RS</td>
      <td><?php echo $tch[0][0]['net'];?></td>
     <td><?php echo $tch[1][0]['net'];?></td>
     <td><?php echo $tch[2][0]['net'];?></td>
     <td><?php echo $tch[3][0]['net'];?></td>
     <td><?php echo $tch[4][0]['net'];?></td>
     <td><?php echo $tch[5][0]['net'];?></td>
     <td><?php echo $tch[6][0]['net'];?></td>
     <td><?php echo $tch[7][0]['net'];?></td>
     <td><?php echo $tch[8][0]['net'];?></td>
     <td><?php echo $tch[9][0]['net'];?></td>
     <td><?php echo $tch[10][0]['net'];?></td>
     <td><?php echo $tch[11][0]['net'];?></td>
     <td><b><?php echo $c_total_net;?></b></td>
    <!-- <td></td>
     <td></td>-->
  </tr>
  <tr>
     <th scope="col">OUT OF </br>
     ACCOUNT DEDUCTION
     </th>
  </tr>
  
  <tr>
     <th scope="col">ARREAR GROSS</th>
	 <td>RS</td>
      <td><?php echo $tch_arrear[0][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[1][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[2][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[3][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[4][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[5][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[6][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[7][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[8][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[9][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[10][0]['gross_salary'];?></td>
     <td><?php echo $tch_arrear[11][0]['gross_salary'];?></td>
     <td><b><?php echo $c_total_arrear;?></b></td>
  </tr>
  
   <tr>
     <th scope="col">BONUS</th>
	 <td>RS</td>
     
     <td><?php echo $tch_bonus[0][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[1][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[2][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[3][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[4][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[5][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[6][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[7][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[8][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[9][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[10][0]['bonus_amount'];?></td>
		 <td><?php echo $tch_bonus[11][0]['bonus_amount'];?></td>
         <td><b><?php echo $c_total_bonus;?></b></td>
  </tr>

      
     
     </th>
      </tr>

     <tr>
     <th scope="col">DDO TAN:
     </th>
     </tr>
     
      
 
   
  </table>
  
 
  
<table>
<tr>
<tr></tr>
<tr></tr>
<th colspan="9" style="text-align:right; border:none;"><br /><br /><br />Generated On: <?php date_default_timezone_set('Asia/Calcutta'); 
$date = date('d/m/Y h:i:s a', time());  echo $date;  ?> </th>
</tr>
</table>





