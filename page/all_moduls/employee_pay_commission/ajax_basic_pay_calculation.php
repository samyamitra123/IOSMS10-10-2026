<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

$cryp = new cryptography();
$db = new database();
error_reporting(0);
?>

<?php
//Added on 28-11-2019 for validation
/*function round_of_ten($pre_emp_pay_in_payband,$pre_emp_grade_pay)
{
	$increment_amount = ((($pre_emp_pay_in_payband + $pre_emp_grade_pay) * 3)/100);
	
	$check_inc_amt=explode(".",$increment_amount);
	if($check_inc_amt[1] != '')
	{
		$last_digit=$check_inc_amt[0]%10;
		if($last_digit >= 1)
		{
			$increment_amount=$check_inc_amt[0]-$last_digit;
			$increment_amount=$increment_amount+10;
		}
		else
		{
			$increment_amount=$check_inc_amt[0]-$last_digit;
		}
	}
	else
	{
		$last_digit=$increment_amount%10;
		if($last_digit >= 1)
		{
			$increment_amount=$increment_amount-$last_digit;
			$increment_amount=$increment_amount+10;
		}
		else
		{
			$increment_amount=$increment_amount-$last_digit;
		}
	}
	return $increment_amount;
}*/
//Added on 28-11-2019 for validation


// Revarse calculation of basic pay
$field_cnt_year=$_GET['field_cnt_year'];
$field_cnt=$_GET['field_cnt_'.$field_cnt_year];
//echo strtotime($_GET['incr_date_'.$field_cnt_year.'_1']);
$date_arr=array();
$date_arr1=array();
$arr_index=0;
for($field=1; $field<=$field_cnt; $field++)
{
	//echo 1234;
	$date_arr1['index']=$field;
	//$date_arr1['date']=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
	$date_arr1['date']=date("d/m/Y h:i:s", strtotime($_GET['incr_date_'.$field_cnt_year.'_'.$field]));
	$date_arr[$arr_index]=$date_arr1;
	$arr_index++;
}

//echo $date_arr[0]['index'];
usort($date_arr, function ($a, $b) {
    $dateA = DateTime::createFromFormat('d/m/Y H:i:s', $a['date']);
    $dateB = DateTime::createFromFormat('d/m/Y H:i:s', $b['date']);
    // ascending ordering, use `<=` for descending
    //return $dateA >= $dateB;
	return $dateB >= $dateA;
});
//print_r($date_arr);
//for($field=1; $field<=$field_cnt; $field++)
$field_cal=1;
foreach($date_arr as $data_fields) 
{
	$field=$data_fields['index'];
	$increment_name=$_GET['increment_name_'.$field_cnt_year.'_'.$field];
	$incr_date=$_GET['incr_date_'.$field_cnt_year.'_'.$field];
	$incr_type=$_GET['incr_type_'.$field_cnt_year.'_'.$field];
	$grade_pay=$_GET['grade_pay_'.$field_cnt_year.'_'.$field];
	
	/*$field_cnt_year_plus_one=($field_cnt_year+1);
	$grade_pay_for_calculation=$_GET['gp_'.$field_cnt_year_plus_one];*/
	
	if($field_cal==1)
	{
		$now_pay_band=$_GET['curr_sal_ppb'];
		$now_grade_pay=$_GET['curr_sal_gp'];	
	}
	else
	{
		$now_pay_band=$new_ppb;
		$now_grade_pay=$new_gp;
	}
	
	// IF ANNUAL INCREMENT
	if ($increment_name=='1')
	{
		//echo 1234; die;
		$now_basic_pay = $now_pay_band + $now_grade_pay;
		$basic_pay = $now_basic_pay / 1.03 ;
		$basic_pay_new = $basic_pay;
		$basic_pay = explode(".",$basic_pay);
		$b_p_reminder = $basic_pay[0] % 10 ;
		if (($basic_pay_new % 10) < 9)
		{
		$new_basic_pay = $basic_pay[0] - $b_p_reminder;	
		}
		//else if (($basic_pay_new % 10) >= 9)
		else
		{
		$new_basic_pay = $basic_pay[0] - $b_p_reminder + 10;	
		}
		$new_ppb = $new_basic_pay - $now_grade_pay;
		$new_gp = $now_grade_pay;
	}
	// END OF IF ANNUAL INCREMENT
	
	// IF PROMOTION WITH DOUBLE INCREMENT
	if ($increment_name == '2' || $increment_name == '3')
	{
		if ($incr_type == '2')
		{
			$previous_grade_pay = $grade_pay;
			$basic_pay_with_pre_grade_pay = $now_pay_band + $previous_grade_pay;
			$pre_basic_pay_with_single_de = $basic_pay_with_pre_grade_pay / 1.03;
			$pre_basic_pay_with_single_de_new = $pre_basic_pay_with_single_de;
			$pre_basic_pay_with_single_de = explode(".",$pre_basic_pay_with_single_de);
			$b_p_reminder = $pre_basic_pay_with_single_de[0] % 10 ;
			
			if (($pre_basic_pay_with_single_de_new % 10) < 9)
			{			
			$new_pre_basic_pay_with_single_de = $pre_basic_pay_with_single_de[0] - $b_p_reminder;	
			}
			else
			{
			$new_pre_basic_pay_with_single_de = $pre_basic_pay_with_single_de[0] - $b_p_reminder + 10;	
			}
			$pre_basic_pay_with_double_de = $new_pre_basic_pay_with_single_de / 1.03 ;
			$pre_basic_pay_with_double_de_new = $pre_basic_pay_with_double_de;
			$pre_basic_pay_with_double_de = explode(".",$pre_basic_pay_with_double_de);
			$b_p_reminder = $pre_basic_pay_with_double_de[0] % 10 ;
			if (($pre_basic_pay_with_double_de_new % 10) < 9)
			{
			$new_pre_basic_pay_with_double_de = $pre_basic_pay_with_double_de[0] - $b_p_reminder;
			}
			else
			{
			$new_pre_basic_pay_with_double_de = $pre_basic_pay_with_double_de[0] - $b_p_reminder + 10;	
			}
			$new_ppb = $new_pre_basic_pay_with_double_de - $previous_grade_pay;
			$new_gp = $previous_grade_pay;
			
			
			///////////////////////For minimum basic pay on a grade pay//////////////////////////
			
			
			$min_basic=$db->fetch_table("SELECT ropa_2009_min_pay from prd_dise_gradepay_master WHERE grade_amount='".$now_grade_pay."'");
			$min_bp_with_gp = $min_basic[0]['ropa_2009_min_pay'];
			
			$now_basic_for_calculation=$_GET['curr_sal_ppb']+$_GET['curr_sal_gp']; 
			//$min_bp_without_gp = $min_bp_with_gp - $new_gp;
			if (($min_bp_with_gp == ($now_basic_for_calculation)) && ($now_grade_pay!=1700))
			{
			
				$_SESSION['edit_pay_band']=$edit_pay_band=$cryp->encode(1,4); 
				
				$_SESSION['max_pay_inpayband']=($min_bp_with_gp-$now_grade_pay); 
				$find_min_gradepay=$db->fetch_table("SELECT ropa_2009_min_pay from prd_dise_gradepay_master WHERE grade_amount='".$new_gp."'");
				// $new_gp."--".$find_min_gradepay[0]['ropa_2009_min_pay']; 
				//echo $new_gp;
				$_SESSION['min_pay_inpayband']=($find_min_gradepay[0]['ropa_2009_min_pay']-$new_gp); 
			}
			else
			{ 
			
				//$new_ppb = $min_bp_without_gp;
				$_SESSION['edit_pay_band']=$edit_pay_band=$cryp->encode(0,4);	
			}
			
			/////////////////////end of For minimum basic pay on a grade pay///////////////////////////
		}
		// END OF IF PROMOTION WITH DOUBLE INCREMENT
		
		// IF PROMOTION WITH SINGLE INCREMENT
		if ($incr_type == '1')
		{
			$previous_grade_pay = $grade_pay;
			$basic_pay_with_pre_grade_pay = $now_pay_band + $previous_grade_pay;
			$pre_basic_pay_with_single_de = $basic_pay_with_pre_grade_pay / 1.03 ;
			$pre_basic_pay_with_single_de_new = $pre_basic_pay_with_single_de;
			$pre_basic_pay_with_single_de = explode(".",$pre_basic_pay_with_single_de);
			$b_p_reminder = $pre_basic_pay_with_single_de[0] % 10 ;
			if (($pre_basic_pay_with_single_de_new % 10) < 9)
			{
			$new_pre_basic_pay_with_single_de = $pre_basic_pay_with_single_de[0] - $b_p_reminder;
			}
			else
			{
			$new_pre_basic_pay_with_single_de = $pre_basic_pay_with_single_de[0] - $b_p_reminder + 10;	
			}
			$new_ppb = $new_pre_basic_pay_with_single_de - $previous_grade_pay;
			$new_gp = $previous_grade_pay;
			
			//Added on 28-11-2019 for validation
			/*echo $payinpayband_after_increment_new=(round_of_ten($new_ppb,$new_gp)+$new_ppb); 
			
			if($now_pay_band > $payinpayband_after_increment_new)
			{
				$edit_pay_band=$cryp->encode(1,4);
			}
			else
			{
				$edit_pay_band=$cryp->encode(0,4);
			}*/
			
			///////////////////////For minimum basic pay on a grade pay//////////////////////////
			$min_basic=$db->fetch_table("SELECT ropa_2009_min_pay from prd_dise_gradepay_master WHERE grade_amount='".$now_grade_pay."'");
			$min_bp_with_gp = $min_basic[0]['ropa_2009_min_pay'];
			
			$now_basic_for_calculation=$_GET['curr_sal_ppb']+$_GET['curr_sal_gp'];
			//$min_bp_without_gp = $min_bp_with_gp - $new_gp;
			if (($min_bp_with_gp == ($now_basic_for_calculation)) && ($now_grade_pay!=1700))
			{
				//$new_ppb = $new_ppb;
				$_SESSION['edit_pay_band']=$edit_pay_band=$cryp->encode(1,4);
				$_SESSION['max_pay_inpayband']=($min_bp_with_gp-$now_grade_pay);
				$find_min_gradepay=$db->fetch_table("SELECT ropa_2009_min_pay from prd_dise_gradepay_master WHERE grade_amount='".$new_gp."'");
				 $_SESSION['min_pay_inpayband']=($find_min_gradepay[0]['ropa_2009_min_pay']-$new_gp); 
			}
			else
			{ 
				//$new_ppb = $min_bp_without_gp;
				$_SESSION['edit_pay_band']=$edit_pay_band=$cryp->encode(0,4);	
			}
			
			
			/*if($_SERVER['REMOTE_ADDR']=='10.26.32.137')
			{
				echo $min_bp_with_gp; 
			}*/
			
			/////////////////////end of For minimum basic pay on a grade pay///////////////////////////
			//Added on 28-11-2019 for validation
		}
	}
	// END OF IF PROMOTION WITH SINGLE INCREMENT
	//echo $new_basic_pay;
	// End of revarse calculation of basic pay
?>
		<input type="hidden" value="<?=$now_grade_pay?>" id="current_grade_pay_<?=$field_cnt_year?>_<?=$field?>" name="current_grade_pay_<?=$field_cnt_year?>_<?=$field?>" />
<?php
	$field_cal++;
}
/*$updated_data=array( 
    "ppb_2019"=>$new_ppb, 
    "gp_2019"=>$new_gp); */
	
//echo json_encode($updated_data);

//echo $new_ppb.'-'.$new_gp;

$_SESSION['ppb_'.$field_cnt_year]=$new_ppb;
$_SESSION['gp_'.$field_cnt_year]=$new_gp;
?>
<input type="hidden" value="<?=$new_ppb.'-'.$new_gp.'-'.$edit_pay_band?>" id="ppbgp_<?=$field_cnt_year?>" name="ppbgp_<?=$field_cnt_year?>" />
