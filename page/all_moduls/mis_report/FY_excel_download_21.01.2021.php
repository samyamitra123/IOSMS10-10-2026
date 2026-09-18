<?

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
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/myvalidation.class.php';

//$crypto = new cryptography();


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


 $sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);

 $finn_year=explode("-", $_POST['finn_year']); 
//$emp_name=$_POST['emp_name'];
	
	
	if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'emp_FY_report.php';
	exit;
}
else{
		if($validator->blank_select($_POST['finn_year']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Financial Year Of Report.</strong></div>';
			include 'emp_FY_report.php';
			exit;
		}
		else
		{
			
			
			$db = new database();
			
	/*		echo ("SELECT sal.salary_monthyear,sal.basic,sal.da,sal.ma,sal.gross_salary,sal.gsli,sal.gpf,sal.p_tax,sal.i_tax,sal.net,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pan_no
			FROM prd_employee_master AS emp
			INNER JOIN prd_monthly_salary_archive_final AS sal ON emp.emp_id_pk= sal.emp_id_fk
			WHERE emp.emp_id_const='".$_SESSION['user_info']['stake_user']."'  and sal.delete_status='1' 
			and emp.emp_status in('1','9') 
			
			and sal.status_flag in('3','4') AND CAST(sal.salary_monthyear as integer) BETWEEN $finn_year[0]03 and $finn_year[1]04
			
			order by sal.salary_monthyear
			"
			
			
			); die*/;
			
			$tch=array();
			$tch_bonus=array();
			
			for($x=0; $x<1; $x++){
				for($y=3; $y<=14; $y++){
					
					if($x.$y =='010'){$finn_year_month= $finn_year[0].'10';} else if($x.$y =='011'){$finn_year_month= $finn_year[0].'11';} else if($x.$y =='012'){$finn_year_month= $finn_year[0].'12';} else if($x.$y =='013'){$finn_year_month= $finn_year[1].'01';} else if($x.$y =='014'){$finn_year_month= $finn_year[1].'02';} else { $finn_year_month= $finn_year[0].$x.$y;}
					
			
			$tch_fetch=$db->fetch_table("SELECT sal.salary_monthyear,sal.basic,sal.da,sal.ma,sal.gross_salary,sal.gsli,sal.gpf,sal.p_tax,sal.i_tax,sal.net,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pan_no
			FROM prd_employee_master AS emp
			INNER JOIN prd_monthly_salary_archive_final AS sal ON emp.emp_id_pk= sal.emp_id_fk
			WHERE emp.emp_id_const='".$_SESSION['user_info']['stake_user']."'  and sal.delete_status='1' 
			and emp.emp_status in('1','9') 
			and sal.status_flag in('3','4') AND CAST(sal.salary_monthyear as integer)= $finn_year_month 
			order by sal.salary_monthyear");
			
			$tch[] = $tch_fetch;
			
						$bonus=$db->fetch_table("SELECT bill.salary_monthyear,sal.bonus_amount,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name
			FROM prd_employee_master AS emp
			INNER JOIN prd_employee_bonus_details AS sal ON emp.emp_id_pk= sal.emp_id_fk
			inner join prd_block_bill_details as bill on bill.block_bill_pk= sal.bill_id_fk
			WHERE emp.emp_id_const='".$_SESSION['user_info']['stake_user']."'  and sal.bill_id_fk!='0' and sal.delete_status='1'
			and emp.emp_status in('1','9') AND CAST(bill.salary_monthyear as integer)= $finn_year_month  
			order by bill.salary_monthyear");
			
			$tch_bonus[] = $bonus;
				}
		}
	}
}


//print_r($tch);
//-------------------------------------------------------QUERY-----------------------------------------------------------------
 
	//$monthyear = $crypto->decode($_GET['ye'],3). $crypto->decode($_GET['mo'],3);
	
	
	//$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	//$requisition_type=$requisition[0]['code'];

	/*$db = new database();
    $code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	                              ");
	*/
							


								
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
  
   $c_total_gsli=$tch[0][0]['gsli']+$tch[1][0]['gsli']+$tch[2][0]['gsli']+$tch[3][0]['gsli']+$tch[4][0]['gsli']+$tch[5][0]['gsli']+$tch[6][0]['gsli']+$tch[7][0]['gsli']+$tch[8][0]['gsli']+$tch[9][0]['gsli']+$tch[10][0]['gsli']+$tch[11][0]['gsli'];
   
   $c_total_ptax=$tch[0][0]['p_tax']+$tch[1][0]['p_tax']+$tch[2][0]['p_tax']+$tch[3][0]['p_tax']+$tch[4][0]['p_tax']+$tch[5][0]['p_tax']+$tch[6][0]['p_tax']+$tch[7][0]['p_tax']+$tch[8][0]['p_tax']+$tch[9][0]['p_tax']+$tch[10][0]['p_tax']+$tch[11][0]['p_tax'];
   
   $c_total_itax=$tch[0][0]['i_tax']+$tch[1][0]['i_tax']+$tch[2][0]['i_tax']+$tch[3][0]['i_tax']+$tch[4][0]['i_tax']+$tch[5][0]['i_tax']+$tch[6][0]['i_tax']+$tch[7][0]['i_tax']+$tch[8][0]['i_tax']+$tch[9][0]['i_tax']+$tch[10][0]['i_tax']+$tch[11][0]['i_tax'];
   
     $c_total_gross=$tch[0][0]['gross_salary']+$tch[1][0]['gross_salary']+$tch[2][0]['gross_salary']+$tch[3][0]['gross_salary']+$tch[4][0]['gross_salary']+$tch[5][0]['gross_salary']+$tch[6][0]['gross_salary']+$tch[7][0]['gross_salary']+$tch[8][0]['gross_salary']+$tch[9][0]['gross_salary']+$tch[10][0]['gross_salary']+$tch[11][0]['gross_salary'];
 
 $c_total_gpf=$tch[0][0]['gpf']+$tch[1][0]['gpf']+$tch[2][0]['gpf']+$tch[3][0]['gpf']+$tch[4][0]['gpf']+$tch[5][0]['gpf']+$tch[6][0]['gpf']+$tch[7][0]['gpf']+$tch[8][0]['gpf']+$tch[9][0]['gpf']+$tch[10][0]['gpf']+$tch[11][0]['gpf'];
 
 $c_total_net=$tch[0][0]['net']+$tch[1][0]['net']+$tch[2][0]['net']+$tch[3][0]['net']+$tch[4][0]['net']+$tch[5][0]['net']+$tch[6][0]['net']+$tch[7][0]['net']+$tch[8][0]['net']+$tch[9][0]['net']+$tch[10][0]['net']+$tch[11][0]['net'];
 ///////////////////////////////// component total////////////////////////
 
  /////////////////////////// total/////////////////////////////
 
 $t_total_march=$tch[0][0]['basic']+$tch[0][0]['da']+$tch[0][0]['gsli']+$tch[0][0]['p_tax']+$tch[0][0]['i_tax']+$tch[0][0]['gross_salary']+$tch[0][0]['gpf']+ $tch[0][0]['net'];
 $t_total_april=$tch[1][0]['basic']+$tch[1][0]['da']+$tch[1][0]['gsli']+$tch[1][0]['p_tax']+$tch[1][0]['i_tax']+$tch[1][0]['gross_salary']+$tch[1][0]['gpf']+ $tch[1][0]['net'];
 
 $t_total_may=$tch[2][0]['basic']+$tch[2][0]['da']+$tch[2][0]['gsli']+$tch[2][0]['p_tax']+$tch[2][0]['i_tax']+$tch[2][0]['gross_salary']+$tch[2][0]['gpf']+ $tch[2][0]['net'];
 
 $t_total_june=$tch[3][0]['basic']+$tch[3][0]['da']+$tch[3][0]['gsli']+$tch[3][0]['p_tax']+$tch[3][0]['i_tax']+$tch[3][0]['gross_salary']+$tch[3][0]['gpf']+ $tch[3][0]['net'];
 
 $t_total_july=$tch[4][0]['basic']+$tch[4][0]['da']+$tch[4][0]['gsli']+$tch[4][0]['p_tax']+$tch[4][0]['i_tax']+$tch[4][0]['gross_salary']+$tch[4][0]['gpf']+ $tch[4][0]['net'];
 $t_total_aug=$tch[5][0]['basic']+$tch[5][0]['da']+$tch[5][0]['gsli']+$tch[5][0]['p_tax']+$tch[5][0]['i_tax']+$tch[5][0]['gross_salary']+$tch[5][0]['gpf']+ $tch[5][0]['net'];
 
 $t_total_sep=$tch[6][0]['basic']+$tch[6][0]['da']+$tch[6][0]['gsli']+$tch[6][0]['p_tax']+$tch[6][0]['i_tax']+$tch[6][0]['gross_salary']+$tch[6][0]['gpf']+ $tch[6][0]['net'];
 $t_total_oct=$tch[7][0]['basic']+$tch[7][0]['da']+$tch[7][0]['gsli']+$tch[7][0]['p_tax']+$tch[7][0]['i_tax']+$tch[7][0]['gross_salary']+$tch[7][0]['gpf']+ $tch[7][0]['net'];
 
 $t_total_nov=$tch[8][0]['basic']+$tch[8][0]['da']+$tch[8][0]['gsli']+$tch[8][0]['p_tax']+$tch[8][0]['i_tax']+$tch[8][0]['gross_salary']+$tch[8][0]['gpf']+ $tch[8][0]['net'];
 $t_total_dec=$tch[9][0]['basic']+$tch[9][0]['da']+$tch[9][0]['gsli']+$tch[9][0]['p_tax']+$tch[9][0]['i_tax']+$tch[9][0]['gross_salary']+$tch[9][0]['gpf']+ $tch[9][0]['net'];
 $t_total_janu=$tch[10][0]['basic']+$tch[10][0]['da']+$tch[10][0]['gsli']+$tch[10][0]['p_tax']+$tch[10][0]['i_tax']+$tch[10][0]['gross_salary']+$tch[10][0]['gpf']+ $tch[10][0]['net'];
 $t_total_feb=$tch[11][0]['basic']+$tch[11][0]['da']+$tch[11][0]['gsli']+$tch[11][0]['p_tax']+$tch[11][0]['i_tax']+$tch[11][0]['gross_salary']+$tch[11][0]['gpf']+ $tch[11][0]['net'];
 
 $t_total_com=$c_total_basic+$c_total_da+$c_total_gsli+$c_total_ptax+$c_total_itax+$c_total_gross+$c_total_gpf+$c_total_net;
 
 /////////////////////////// total/////////////////////////////
 
 //echo $g_total_basic; die;
 //$cnt=1; 
/*$gross=$net=0;

$g_total_da=$tch[0]['basic']
$tot_conv_allow=$tot_payband=$tot_basic=$tot_da=$tot_hra=$tot_ma=$tot_ca=$tot_ha=$tot_cpf=$tot_gross=$tot_gpf=$tot_pfloan=$tot_cpfdeduct=$tot_ptax=$tot_itax=$tot_od=$tot_gsli=$tot_net=0;*/?>


<table width="100">
   <tr>
      <th colspan="17" style="text-align:center;"><br /><br />STATEMENT OF SALARY INCOME OF <?php echo $tch[0]['emp_first_name'].' '.$tch[0]['emp_second_name'].' '.$tch[0]['emp_last_name']?>(<?= $finn_year[0].'-'.$finn_year[1]?>).... <br /><br /></th>
   </tr>
   </table>
   <table width="100" >
   <tr colspan="17">
    <th></th>
    
    <th >F.Y:<?= $finn_year[0].'-'.$finn_year[1]?></th>
    <th></th>
   <th></th>
   <th></th>
   <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th style=" text-align:left;">PAN:<?= $tch[0][0]['emp_pan_no']?></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    
   </tr>
   <tr>
     
   </tr>
    </table>
	
		<!--<tr>
			<?php foreach($tch as $key=> $val){ ?>
			<th scope="col"> <?php var_dump($val); //echo $val['salary_monthyear'];?></th>
		<?php	}?>
		</tr>
		
		<tr>
			<th scope="col">BP</th>
			<th scope="col">RS</th>
			<?php foreach($tch as $key=> $val){ ?>
			<th scope="col"> <?php echo $val['basic'];?></th>
		<?php	}?>
		</tr>
		<tr>
			<th scope="col">DA</th>
			<th scope="col">RS</th>
			<?php foreach($tch as $key=> $val){ ?>
			<th scope="col"> <?php echo $val['da'];?></th>
			<?php	}?>
		</tr>
		<tr>
			<th scope="col">GROSS</th>
			<th scope="col">RS</th>
			<?php foreach($tch as $key=> $val){ ?>
			<th scope="col"> <?php echo $val['gross_salary'];?></th>
			<?php	}?>
		</tr>
		<tr>
			<th scope="col">GISLI</th>
			<th scope="col">RS</th>
			<?php foreach($tch as $key=> $val){ ?>
			<th scope="col"> <?php echo $val['gsli'];?></th>
			<?php	}?>
		</tr>-->
		
			
		
	
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
    <th scope="col">COMPONENT TOTAL</th>
    <th scope="col">ARREAR</th>
    <th scope="col">GRAND TOTAL</th>
    
   </tr>
  
   <tr>
   
   
		<th scope="col">BP</th>
		<td>RS</td>
		<td><?php echo $tch[0][0]['basic'];?></td>
		<td><?php echo $tch[1][0]['basic'];?></td>
		<td><?php echo $tch[2][0]['basic'];?></td>
		<td><?php echo $tch[3][0]['basic'];?></td>
		<td><?php echo $tch[4][0]['basic'];?></td>
		<td><?php echo $tch[5][0]['basic'];?></td>
		<td><?php echo $tch[6][0]['basic']; ?></td>
		<td><?php echo $tch[7][0]['basic'];?></td>
		<td><?php echo $tch[8][0]['basic'];?></td>
		<td><?php echo $tch[9][0]['basic'];?></td>
		<td><?php echo $tch[10][0]['basic'];?></td>
		<td><?php echo $tch[11][0]['basic'];?></td>

		<td><?php echo $c_total_basic;?></td>
		<td></td>
		<td></td>
     
     
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
		 <td><?php echo $c_total_da;?></td>
		 <td></td>
		 <td></td>
       
  </tr>
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
     <td><?php echo $c_total_gross;?></td>
     <td></td>
       <td></td>
     
  </tr>
  <tr>
     <th scope="col">GISLI</th>
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
     <td><?php echo $c_total_gsli;?></td>
     <td></td>
       <td></td>
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
     <td><?php echo $c_total_gpf;?></td>
     <td></td>
       <td></td>
     
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
     <td><?php echo $c_total_itax;?></td>
       <td></td>
       <td></td>
     
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
     <td><?php echo $c_total_ptax;?></td>
       <td></td>
       <td></td>
    
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
     <td><?php echo $c_total_net;?></td>
       <td></td>
       <td></td>
     
  </tr>
  <tr>
     <th scope="col">OUT OF </br>
     ACCOUNT DEDUCTION
     </th>
  </tr>
  <tr>
     <th scope="col">SALARY ECS/ </br>
     BANK
     </th>
  </tr>
   <tr>
     <th scope="col">BONUS</th>
	 <td>RS</td>
	 <?php foreach($tch_bonus as $key=> $val){ ?>
			<td scope="col"> <?php echo $val[0]['bonus_amount'];?></td>
			<?php	}?>
  </tr>
  <tr>
     <th scope="col">TOTAL:
     <td>RS</td>
      <td><?php echo $t_total_march;?></td>
      <td><?php echo $t_total_april;?></td>
      <td><?php echo $t_total_may;?></td>
      <td><?php echo $t_total_june;?></td>
      <td><?php echo $t_total_july;?></td>
      <td><?php echo $t_total_aug;?></td>
      <td><?php echo $t_total_sep;?></td>
      <td><?php echo $t_total_oct;?></td>
      <td><?php echo $t_total_nov;?></td>
      <td><?php echo $t_total_dec;?></td>
      <td><?php echo $t_total_janu;?></td>
      <td><?php echo $t_total_feb;?></td>
     <td><?php echo $t_total_com;?></td>
        <td></td>
      
     
     </th>
      </tr>
     <tr>
     <th scope="col">TV NO.& DATE:
     </th>
     </tr>
     <tr>
     <th scope="col">DDO CODE:
     </th>
     </tr>
     <tr>
     <th scope="col">DDO TAN:
     </th>
     </tr>
     
      
 
   
  </table>
  
 
  
<!--<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th colspan="21" style="text-align:right; border:none;"><br /><br /><br />Panchayat Samiti OF </th>
</tr>
</table>-->

<!--<tr style="border-color:#3E9B96;">
	<td colspan="26" style="color:red;font-weight:bold;border-color:#3E9B96; text-align:center;">No Data Found</td>
</tr>
<table>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr>
<th colspan="21" style="text-align:right; border:none;"><br /><br /><br />Panchayat Samiti OF </th>
</tr>
</table>-->



