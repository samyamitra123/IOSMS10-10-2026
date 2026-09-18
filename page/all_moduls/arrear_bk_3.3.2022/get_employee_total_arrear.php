<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

function func_gradepay($val)
{
	$db = new database();
	$arr = $db->fetch_table("select grade_amount from prd_dise_gradepay_master where grade_code='$val'");
	return $arr[0]['grade_amount'];
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function salaryType($sal_type)
{
	$db = new database();
	$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
	return $arr[0]['salary_type'];
}
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getEmpAmount($type,$dise,$emp_id_pk)
{
	$db = new database();
	//echo "select emp_pay_band from prd_employee_master where empcd='".$empcd."' AND gp_id_fk='".$dise."'";exit;
	if($type == 'pay_in_pay_band'){
	$arr = $db->fetch_table("select emp_pay_in_payband from prd_employee_master where emp_id_pk='".$emp_id_pk."' AND gp_id_fk='".$dise."'");
		if($arr[0]['emp_pay_in_payband']){
			return $arr[0]['emp_pay_in_payband'];
		}else{
			return 0;
		}
	}
	
	
	if($type == 'grade_pay'){
	//$arr = $db->fetch_table("select emp_grade_pay from prd_employee_master where empcd='".$empcd."' AND gp_id_fk='".$dise."'");
	$arr = $db->fetch_table("select emp_grade_pay,grade_amount from prd_employee_master as emp
	INNER JOIN prd_dise_gradepay_master as gd 
	ON trim(emp.emp_grade_pay)=gd.grade_code
	where emp_id_pk='".$emp_id_pk."' AND gp_id_fk='".$dise."'");
		if($arr[0]['grade_amount']){
			return $arr[0]['grade_amount'];
		}else{
			return 0;
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function getEmpConsolidated($type,$dise,$emp_id_pk){
	//echo "select emp_cosolidated_pay from prd_employee_master where empcd='".$empcd."' AND gp_id_fk='".$dise."'";exit;
	$db = new database();
	if($type == 'consolidated_pay'){
		
	$arr = $db->fetch_table("select emp_cosolidated_pay from prd_employee_master where emp_id_pk='".$emp_id_pk."' AND gp_id_fk='".$dise."'");
		if($arr[0]['emp_cosolidated_pay']){
			return $arr[0]['emp_cosolidated_pay'];
		}else{
			return 0;
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function getAmount($dise,$emp_id_pk,$type,$basic)
{
			$db = new database();
			if($type=='cpf'){
				$arr = $db->fetch_table("select cpf from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['cpf']){
					return $arr[0]['cpf'];
				}else{
					return 0;
				}
			}
			if($type=='gpf'){
				$arr = $db->fetch_table("select gpf from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['gpf']){
					return $arr[0]['gpf'];
				}else{
					$gpf_amt = ($basic/100)*6;
					return round($gpf_amt);
				}
			}
			if($type=='pfl'){
				$arr = $db->fetch_table("select pf_loan from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['pf_loan']){
					return $arr[0]['pf_loan'];
				}else{
					return 0;
				}
			}
			if($type=='itax'){
				$arr = $db->fetch_table("select i_tax from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['i_tax']){
					return $arr[0]['i_tax'];
				}else{
					return 0;
				}
			}
			if($type=='ovd'){
				$arr = $db->fetch_table("select overdrawn from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['overdrawn']){
					return $arr[0]['overdrawn'];
				}else{
					return 0;
				}
			}
			if($type=='gsli'){
				$arr = $db->fetch_table("select gsli from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['gsli']){
					return $arr[0]['gsli'];
				}else{
					return 0;
				}
			}
			if($type=='conv'){
				$arr = $db->fetch_table("select conv_allow from prd_employee_salary_save where status_flag=1 AND delete_status=1  AND emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' AND gp_id_fk='".$dise."'");
				if($arr[0]['conv_allow']){
					return $arr[0]['conv_allow'];
				}else{
					return 0;
				}
			}
		}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                
function empPtax($amount)
{
	$db = new database();
	$arr = $db->fetch_table("select ptax_amount from prd_ptax_deduction as amnt inner join prd_ptax_order_file as file on amnt.ptax_order_id_fk=file.ptax_orderfile_pk where amnt.mn_amount <= '$amount' and amnt.mx_amount >= '$amount' AND file.active_status='1'");
	return $arr[0]['ptax_amount'];
}
		


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




function count_total_month($from, $to) 
{
    $month_in_year = 12;
    $date_from = getdate(strtotime($from));
    $date_to = getdate(strtotime($to));
    return ($date_to['year'] - $date_from['year']) * $month_in_year -
        ($month_in_year - $date_to['mon']) +
        ($month_in_year - $date_from['mon']);
}


function get_month_details($start_date,$end_date) 
{
	$months = array();
	$i=0;
	
	$d=count_total_month($start_date,$end_date);
	if($d==0)
	{
		$months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), 'total_days'=> (int)date('t', strtotime($start_date)), 'remaining_days' => ((int)date('d', strtotime($end_date)) - (int)date('d', strtotime($start_date)))+1,);
	}
	else
	{
		while (strtotime($start_date) <= strtotime($end_date)) 
		{
			if($i==0)
			{
				$months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), 'total_days'=> (int)date('t', strtotime($start_date)), 'remaining_days' => ((int)date('t', strtotime($start_date)) - (int)date('j', strtotime($start_date)))+1,);
				
				
			}
			elseif($i==$d)
			{
				$months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), 'total_days'=> (int)date('t', strtotime($start_date)), 'remaining_days' => (int)date('j', strtotime($end_date)),);
			}
			else
			{
				$months[] = array('year' => date('Y', strtotime($start_date)), 'month' => date('m', strtotime($start_date)), 'total_days'=> (int)date('t', strtotime($start_date)), 'remaining_days' => (int)date('t', strtotime($start_date)),);
			}
			$start_date = date('d M Y', strtotime($start_date.'+ 1 month'));
			$i++;
		}
	}
	
	return $months;
}

function get_emp_arrear($emp_arr)
{
	$month_details=$emp_arr[0];
	$emp_payband=$emp_arr[1];
	$emp_gradepay=$emp_arr[2];
	$emp_da=$emp_arr[3];
	$emp_hra=$emp_arr[4];
	$emp_ma=$emp_arr[5];
	$emp_conv=$emp_arr[6];
	$emp_hill=$emp_arr[7];
	$emp_ir=$emp_arr[8];
	$emp_gross=$emp_arr[9];
	$emp_consolidated_pay=$emp_arr[10];
	
	$total_working_days=0;$total_payband=0;$total_gradepay=0;$total_da=0;$total_hra=0;$total_ma=0;$total_conv=0;$total_hill=0;$total_ir=0;$total_gross=0;$total_consolidated_pay=0;$total_basic=0;
	
	foreach($month_details as $key)
	{
		$total_working_days=$total_working_days+$key['remaining_days'];
		$total_payband=$total_payband+(($emp_payband/$key['total_days'])*$key['remaining_days']);
		$total_gradepay=$total_gradepay+(($emp_gradepay/$key['total_days'])*$key['remaining_days']);
		$total_da=$total_da+(($emp_da/$key['total_days'])*$key['remaining_days']);
		$total_hra=$total_hra+(($emp_hra/$key['total_days'])*$key['remaining_days']);
		$total_ma=$total_ma+(($emp_ma/$key['total_days'])*$key['remaining_days']);
		$total_conv=$total_conv+(($emp_conv/$key['total_days'])*$key['remaining_days']);
		$total_hill=$total_hill+(($emp_hill/$key['total_days'])*$key['remaining_days']);
		$total_ir=$total_ir+(($emp_ir/$key['total_days'])*$key['remaining_days']);
		$total_gross=$total_gross+(($emp_gross/$key['total_days'])*$key['remaining_days']);
		$total_consolidated_pay=$total_consolidated_pay+(($emp_consolidated_pay/$key['total_days'])*$key['remaining_days']);
	}
	$total_basic=$total_payband+$total_gradepay;
	$arrear_details=array($total_working_days,round($total_payband),round($total_gradepay),round($total_da),round($total_hra),round($total_ma),round($total_conv),round($total_hill),round($total_ir),round($total_gross),round($total_consolidated_pay),round($total_basic));
	
	return $arrear_details;
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$gp_id=$_POST['gp_id'];
$emp_id=$_POST['emp_id'];

$arrear_to=explode('-',$_POST['arrear_to_date']);
$arrear_to_date=$arrear_to[2].'-'.$arrear_to[1].'-'.$arrear_to[0];

$arrear_fm=explode('-',$_POST['arrear_fm_date']);
$arrear_fm_date=$arrear_fm[2].'-'.$arrear_fm[1].'-'.$arrear_fm[0];


//echo $gp_id." ".$emp_id." ".$arrear_to_date." ".$arrear_fm_date; die;

$cryptoGraph=new cryptography();

$db=new database();



$tch = $db->fetch_table("SELECT 
										tch.emp_id_pk,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_system_code,
										tch.emp_pay_in_payband,
										tch.emp_grade_pay,
										tch.emp_pay_band,
										tch.emp_spouse_hra,
										tch.emp_diff_able,
										tch.emp_spouse_res,
										tch.emp_retirement_date,
										tch.emp_id_pk,
										tch.empcd,
										tch.gp_id_fk,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_spouse_hra,
										tch.emp_pan_no,
										tch.emp_first_join_date,
										tch.emp_id_const,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status
									FROM
										prd_employee_master as tch
									WHERE
											tch.gp_id_fk = '".$gp_id."'
											AND tch.emp_id_pk = '".$emp_id."'
											
								");
							

						
$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM prd_admin_paychange
							WHERE flag = 't'
						");
						
						
$da_per = $paychange[0]['paychange_da']; 
$max_ma = $paychange[0]['paychange_ma'];
$hra_per = $paychange[0]['paychange_hra'];
$cpf_per = $paychange[0]['paychange_cpf'];
$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
$hill_allowance_per = $paychange[0]['hill_allowance'];
				
				
				
///////////////////////////////////////////////////////////////////////////////// PAY & ALLOAWANCE PART CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					

				
$tchname =$tch[0]['emp_first_name'].' '. $tch[0]['emp_second_name'].' '. $tch[0]['emp_last_name'] ;
$empcd = $tch[0]['empcd'];
$emp_id_pk=$tch[0]['emp_id_pk'];

$emp_bank_name=$tch[0]['emp_bank_name'];
$emp_acc_no=$tch[0]['emp_acc_no'];
$emp_ifsc_no=$tch[0]['emp_ifsc_no'];  
$emp_pan_no=$tch[0]['emp_pan_no'];       
$emp_id_const=$tch[0]['emp_id_const'];        
			
		
	   
//---------- Start Gpf=0 before retirement--------------------
$retirement_date=$tch[0]['emp_retirement_date'];

$date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date))); 	

$emp_first_join_date=$tch[0]['emp_first_join_date'];
$emp_first_join_match_date=date('Y-m-30', strtotime('+12 month',strtotime($emp_first_join_date)));	
//----------- End Gpf=0 before retirement ---------------------
				
	   	
						  
$pay_in_band =  $tch[0]['emp_pay_in_payband'];
$grade_pay = getEmpAmount('grade_pay',$gp_id,$emp_id_pk);
$basic = $pay_in_band+$grade_pay;
$da = ($basic/100)*$da_per;
$interim_relief = $tch[0]['interim_relief'];

$consolidated_pay=0;
	    
		          
///////////////////////////////////////////////////////////////////////////////// HRA CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					
	
					
if($tch[0]['emp_spouse_res']=='251')
{
	$hra = 0; 
}
else
{
	if($tch[0]['emp_spouse_hra']=='0' ||$tch[0]['emp_spouse_hra']=='' || !$tch[0]['emp_spouse_hra'])
	{
		$hra_emp = ($basic/100)*$hra_per;
		if($hra_emp > 6000)
		{
			$hra = 6000;
		}
		else
		{
			$hra = round($hra_emp);
		}
	}
	else if($tch[0]['emp_spouse_hra'] >= 6000)
	{
		$hra = 0;
	}
	else if($tch[0]['emp_spouse_hra'] < 6000)
	{
		$hra_emp = ($basic/100)*$hra_per;
		if($hra_emp >= 6000)
		{
			$valid_hra  = (6000-$tch[0]['emp_spouse_hra']);
			$hra = round($valid_hra);
		}
		else
		{
			$mix_hra = $hra_emp+$tch[0]['emp_spouse_hra'];
			if($mix_hra > 6000)
			{
				if($tch[0]['emp_spouse_hra'] > $hra_emp)
				{
					$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
					if($valid_hra>$hra_emp)
					{
						$valid_hra=$hra_emp;
					}
					$hra = round($valid_hra);
				}
				else if($tch[0]['emp_spouse_hra'] <= $hra_emp)
				{
					$valid_hra = 6000-$tch[0]['emp_spouse_hra'];
					if($valid_hra>$hra_emp)
					{
						$valid_hra=$hra_emp;
					}
					$hra = round($valid_hra);
				}
			}
			else
			{
				$hra = round($hra_emp);
			}
		}
	}
}


///////////////////////////////////////////////////////////////////////////////// MA CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					
							
					
if($tch[0]['spouse_medical_allowance']=='1')
{
	$ma = 0;
}
else
{
	$ma = $max_ma;
}
					


///////////////////////////////////////////////////////////////////////////////// CONV ALLOW CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					

					
if($tch[0]['emp_diff_able']=='1')
{
	if($tch[0]['conv_allow_status']==1)
	{
		$cal_ma=round(($basic*5)/100);
		if($cal_ma>=400)
		{
			$conveyance_allowance = $conveyance_allowance_max;
		}
		else
		{
			$conveyance_allowance=round($cal_ma);
		}
	}
	else
	{
		$conveyance_allowance = 0;
	}
}
else
{
	$conveyance_allowance = 0;
}
				

///////////////////////////////////////////////////////////////////////////////// HILL ALLOW CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					

					
if($state10=='3219')
{
	$hill_p = ($pay_in_band/100)*$hill_allowance_per;
	$hill_g = ($grade_pay/100)*$hill_allowance_per;
	$hill_allowance_amt = $hill_p+$hill_g;
	if($hill_allowance_amt > 1500)
	{
		$hill_allowance = 1500;
	}
	else
	{
		$hill_allowance = $hill_allowance_amt;
	}
}
else
{
	$hill_allowance = 0;
}


///////////////////////////////////////////////////////////////////////////////// GROSS CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					


$gross_salary = round($pay_in_band+$grade_pay+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$hill_allowance);
					
					


///////////////////////////////////////////////////////////////////////////////// DEDUCTION PART CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					


///////////////////////////////////////////////////////////////////////////////// GPF CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					


if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
{
	$gpf=0;
}	
else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
{
	$gpf=0;
}
else
{
	if($sal_save[0]['gpf']!=0)
	{
		$gpf=$sal_save[0]['gpf'];	
	}
	else
	{
		$gpf = getAmount($gp_id,$empcd,'gpf',$basic);
	}
}


///////////////////////////////////////////////////////////////////////////////// PF LOAN CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					

				
if($sal_save[0]['pf_loan'])
{
	$pfl=$sal_save[0]['pf_loan'];
}
else
{
	$pfl = getAmount($gp_id,$empcd,'pfl','');
}
				
///////////////////////////////////////////////////////////////////////////////// PTAX CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					
					
				

if($tch[0]['emp_diff_able']=='1')
{
	$ptax = 0;
}
else
{
	$ptax = empPtax($gross_salary);
}
					
///////////////////////////////////////////////////////////////////////////////// ITAX CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					


if($sal_save[0]['i_tax'])
{
	$itax=$sal_save[0]['i_tax'];
}
else
{
	$itax = getAmount($gp_id,$empcd,'itax','');
}
					
				
$overdrawn=0;
$total_deduct = $gpf+$pfl+$ptax+$itax;


///////////////////////////////////////////////////////////////////////////////// NET CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////					


$net_salary = $gross_salary-$total_deduct;
	
if($tch[0]['emp_desig']=='1120' || $tch[0]['emp_desig']=='1124' || $tch[0]['emp_desig']=='1125')
{
	$consolidated_pay=$tch[0]['emp_cosolidated_pay'];	
	$pay_in_band = 0;
	$grade_pay = 0;
	$basic = 0;
	$bas=$consolidated_pay+$grade_pay;
	$da = 0;
	$interim_relief=0;
	$hra = 0;
	$ma = 0;
	$conveyance_allowance = 0;
	$hill_allowance = 0;
	$gpf = 0;
	$pfl = 0;
	$cpf_deduct = 0;
	$gross_salary = round($bas+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
	if($tch[0]['emp_diff_able']=='1')
	{
		$ptax = 0;
	}
	else
	{
		$ptax = empPtax($gross_salary);
	}
	$max_ptax = empPtax($gross_salary);
	$itax = 0;
	$gsli =0;
	$overdrawn = getAmount($dise,$tch[0]['emp_id_pk'],'ovd','');
	$total_deduct = $gpf+$pfl+$ptax+$itax+$overdrawn;
	$net_salary = $gross_salary-$total_deduct;
}


$month_details=get_month_details($arrear_fm_date,$arrear_to_date);

//print_r($month_details);die;
$emp_arr=array($month_details);
//$emp_arr=array($month_details,$pay_in_band,$grade_pay,$da,$hra,$ma,$conveyance_allowance,$hill_allowance,$interim_relief,$gross_salary,$consolidated_pay);


$aaa=get_emp_arrear($emp_arr);
echo json_encode($aaa);



