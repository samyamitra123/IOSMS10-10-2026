<?php

//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

$crypto = new cryptography();

$block_code = $_SESSION['user_info']['stake_user'];

//Permission --------------------------------------------------------------------------------------------------------------------------------------------
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//Functions --------------------------------------------------------------------------------------------------------------------------------------------


function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	if($date==''){
		return '01/01/1900';
	}
	if($date=='0001-01-01'){
		return '01/01/1900';
	}
	$datearr=explode('-',$date);
	if($datearr['0']=='0001'){
		$yr = '1900';
	}else{
		$yr = $datearr['0'];
	}
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$yr;
	return $dob=='01/01/1900'?'':$dob;
}
function dateshow_slash1($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	if($date==''){
		return '01/01/1900';
	}
	if($date=='0001-01-01'){
		return '01/01/1900';
	}
	$datearr=explode('-',$date);
	if($datearr['2']=='0001'){
		$yr = '1900';
	}else{
		$yr = $datearr['2'];
	}
	$dob= $datearr['0'].'/'.$datearr['1'].'/'.$yr;
	return $dob=='01/01/1900'?'':$dob;
}

function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return "--";
	}
	else{
		return $newDate = date("d/m/Y", strtotime($original_date));
	}
}
function date_frmt($original_date){

		return $newDate = date("M#Y", strtotime($original_date));
}
function set_date($original_date){
	return substr($original_date, 0,10);
}
function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

function find_catagory($code)
{
	$db = new database();
	$code = strlen($code)==2 ? "11".$code : "110".$code; 
	$category=$db->fetch_table("select description from prd_dise_code_master where code = '".$code."'");	
	return $category[0]['description'];
}

function find_location_district($code)
{
	$db = new database();
	$district=$db->fetch_table("select district_name from prd_location_master_district where district_id_pk = '".$code."'");	
	return $district[0]['district_name'];
}

function find_location_state($code)
{
	$db = new database();
	$state=$db->fetch_table("select state_name from prd_location_master_state where state_code = '".$code."'");	
	return $state[0]['state_name'];
}

function last_day($year,$month)
{
	$a_date = $year."-".$month."-01";
	
	return date("t", strtotime($a_date));
}

//echo is_leap_year(2001);
//Generate Tect	----------------------------------------------------------------------------------------------------------------------------------------
//echo "<pre>";
//print_r($_REQUEST);
$block_code = $_SESSION['user_info']['stake_user'];
//echo $block_code;
$mo = $_GET['mo'];
$ye = $_GET['ye'];
$so = $crypto->decode($_GET['so'], 4);
//$bill = $crypto->decode($_GET['bill'],4);
$bill=isset($_GET['bill'])?$_GET['bill']:'';
//echo $bill;
$yemo = $ye.$mo;
//echo last_day($ye, $mo);
//echo "<br />".$mo ."<br />". $ye."<br />". $so;
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='428'");
$requisition_type=$requisition[0]['code'];


$code_data = $db->fetch_table("
		SELECT code, description
		FROM prd_dise_code_master;
	
");

$arr5 = $db->fetch_table("
	select *
	from 
		prd_head_code
	where
		type_status='1'
");

$arr3 = $db->fetch_table("
	select * 
	from
		prd_head_details
	where
		block_code='".$_SESSION['user_info']['stake_user']."'
");

$arr2 = $db->fetch_table("
	SELECT
		bill_no,
		bill_entry_time,
		salary_monthyear
	FROM prd_block_bill_details
	WHERE block_code ='".$_SESSION['user_info']['stake_user']."' AND
		salary_monthyear = '".$ye.$mo."' AND
		bill_no = '".$bill."' AND requisition_type='".$requisition_type."' AND status='1'
");

//echo count($arr2);


/*$arr4 = $db->fetch_table("Select treasury_block_code from prd_dise_admin where block_code = '".$_SESSION['user_info']['stake_user']."'");
		$block_code=$arr4[0]['treasury_block_code'];
		
		$block_code_array=explode("-",$block_code);
		$block_code_name=$block_code_array[0].$block_code_array[1].$block_code_array[2];
		$block_code_slash=$block_code_array[0]."/".$block_code_array[1]."/".$block_code_array[2];
	*/
/*	$dpsc_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$_SESSION['user_info']['stake_user']."'");
	
		
		$dpsc_code_new=$dpsc_sql[0]['ddo_code'];
		$dpsc_code_array=explode("-",$dpsc_code_new);
			foreach($dpsc_code_array as $in=>$data)
		$dpsc_code.=$data;
		$dpsc_code_slash=$dpsc_code;*/
		
		
		$arr4 = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$_SESSION['user_info']['stake_user']."'");
		$block_code=$arr4[0]['ddo_code']; 
		//echo $dpsc_code;
		 $block_code_array=explode("-",$block_code);
		  $block_code_name=$block_code_array[0].$block_code_array[1].$block_code_array[2];
		  $block_code_array1=substr($block_code_name, 0, 3);
		  $block_code_array2=substr($block_code_name, 3, 3);
		  $block_code_array3=substr($block_code_name, 6, 3);
		 $block_code_slash=$block_code_array1."/".$block_code_array2."/".$block_code_array3;
		
		$block_ddo_code=$arr4[0]['ddo_code'];
		//echo $dpsc_code;
		
		$block_ddo_code_array=explode("-",$block_ddo_code);
		$block_ddo_code_name=$block_ddo_code_array[0].$block_ddo_code_array[1].$block_ddo_code_array[2];
		$block_ddo_code_slash=$block_ddo_code_array[0]."/".$block_ddo_code_array[1]."/".$block_ddo_code_array[2];
		
		
if(count($arr2)==0){
	echo "Bill Not found";
	exit();
}
/*
$arr=$db->fetch_table("
	SELECT	treasury_block_code,emp.*,
        save.*,gp.gp_code,gp.gp_name
	FROM prd_location_master_state as state
	INNER JOIN prd_location_master_district as district
		ON state.state_id_pk= district.state_id_fk
	INNER JOIN prd_location_master_block as block
		ON district.district_id_pk= block.district_id_fk
	INNER JOIN prd_location_master_gp as gp
		ON block.block_id_pk =  gp.block_id_fk
	INNER JOIN prd_employee_master as emp
		ON emp.gp_id_fk =gp.gp_id_pk
	INNER JOIN prd_monthly_salary_archive_final as save
		ON CAST(save.empcd AS text)=CAST(emp.empcd AS text) and CAST(save.gp_id_fk AS text)=CAST(emp.gp_id_fk AS text)
	INNER JOIN prd_dise_admin as admin 
	    ON admin.block_code=save.block_code

		WHERE substring(CAST(gp.gp_code AS text),0,8) like '".$_SESSION['user_info']['stake_user']."%' 
		AND emp.emp_status = 1 AND save.status_flag='3' 
		AND save.salary_monthyear = '".$yemo."'
	    AND save.delete_status='1' ORDER BY emp.gp_id_fk ASC 
		
		");*/
echo "
	SELECT	treasury_block_code,
emp.*, 
bonus.*,
gp.gp_code,
gp.gp_name 
FROM prd_location_master_gp as gp 
inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk 
left join prd_employee_bonus_details bonus ON bonus.emp_id_fk=emp.emp_id_pk 
INNER JOIN prd_dise_admin as admin ON admin.block_code='3299001' 
WHERE substring(CAST(gp.gp_code AS text),0,8) like '3299001%' AND (emp.emp_status='1' OR emp.emp_status='9') AND bonus.bonus_status='5' AND bonus.monthyear = '20182019' AND bonus.delete_status='1' 
ORDER BY emp.gp_id_fk ASC
		
		"; die;
		
$arr=$db->fetch_table("
	SELECT	treasury_block_code,emp.*,
        save.*,gp.gp_code,gp.gp_name
	FROM    prd_location_master_gp as gp 
	inner join   prd_employee_master emp
                on gp.gp_id_pk=emp.gp_id_fk 
      left join prd_employee_salary_save save 
      ON save.emp_id_fk=emp.emp_id_pk
	INNER JOIN prd_dise_admin as admin 
	
	    ON admin.block_code=save.block_code

		WHERE substring(CAST(gp.gp_code AS text),0,8) like '".$_SESSION['user_info']['stake_user']."%' 
		AND (emp.emp_status='1' OR emp.emp_status='9') AND 
		save.status_flag='3' 
		AND save.salary_monthyear = '".$yemo."'
	    AND save.delete_status='1'
		 AND requisition_type='".$requisition_type."' 
	    ORDER BY emp.gp_id_fk ASC 
		
		");
print_r($arr);die;

$content = NULL;
$pf_type = NULL;
$spous_stat = NULL;
$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
$content_ern=NULL;
$content_ded=NULL;
$content_ern=NULL;
$content_sub =NULL;
$block_code_name =NULL;
$emp_catagry =NULL;

$schtype_code=$arr5[0]['demand_no']."-".$arr5[0]['maj_head']."-".$arr5[0]['s_maj_head']."-".$arr5[0]['minor_head']."-".$arr5[0]['plan_head']."-".$arr5[0]['schm_head']."-".$arr5[0]['vot_ch']."-".$arr5[0]['dtl_head'];
//$schtype_code='N';
if(count($arr3)>0 || $arr4>0){
	$itax_head=$arr3[0]['it_maj_head']."-".$arr3[0]['it_s_maj_head']."-".$arr3[0]['it_minor_head']."-".$arr3[0]['it_schm_head']."-".$arr3[0]['it_dtl_head'];
	$ptax_head=$arr3[0]['pt_maj_head']."-".$arr3[0]['pt_s_maj_head']."-".$arr3[0]['pt_minor_head']."-".$arr3[0]['pt_schm_head']."-".$arr3[0]['pt_dtl_head'];
	$gpf_head=$arr3[0]['gpf_maj_head']."-".$arr3[0]['gpf_s_maj_head']."-".$arr3[0]['gpf_minor_head']."-".$arr3[0]['gpf_schm_head']."-".$arr3[0]['gpf_dtl_head'];
		
	
} else {
	//echo "Problem to get head details";
	$_SESSION['head_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry!!! Salary head details not found</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/block/text_file.php');
	exit(1);
}
//echo $itax_head;
//echo $ptax_head;
//echo $gpf_head;


foreach ($arr as $key) {
	//-------------------------------------------------------------
	
	
	
	
	if($key['ma']){
			$ma_opt='YES';
		}else{
			$ma_opt='NO';
		}
		if($key['emp_father_name']){
			$fath_name_temp=$key['emp_father_name'];
			$fath_name=substr($fath_name_temp,0,49);
		}else{
			$fath_name = 'N';
		}
		if($key['emp_mother_name']){
			$mother_name_temp=$key['emp_mother_name'];
			$mother_name=substr($mother_name_temp,0,49);
		}else{
			$mother_name = 'N';
		}
		if($key['emp_spouse_name']){
			$spouse_name_temp=$key['emp_spouse_name'];
			
			$spouse_name=substr($spouse_name_temp,0,49);
		}else{
			$spouse_name='N';
		}
		if($key['emp_first_name']){
			$emp_name_join=$key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'];
			$emp_name = str_pad(substr($emp_name_join,0,40),40,' ');
		}else{
			$emp_name = 'N';
		}
		if($key['slno']){
			$emp_sl = str_pad(substr($key['slno'],0,3),3,' ');//slno field not present in prd_employee_master table
		}else{
			$emp_sl = '00';
		}
		if($key['emp_pay_band']){
			$emp_basic_pay = str_pad(substr($key['emp_pay_band'],0,30),30,' ');
		}else{
			$emp_basic_pay = '00';
		}
		if($key['basic']){
			$emp_basic = str_pad($key['basic'],6,' ',STR_PAD_LEFT);
		}else{
			$emp_basic = '00';
		}
		if(($key['cpf']!=0 or $key['cpf']!='') && $key['basic']!=0 ){
			$pf_type='C.P.F';
			}
		if(($key['cpf']==0 or $key['cpf']=='') && $key['basic']!=0){
			$pf_type='G.P.F';
		}
		if($key['emp_group']){
			if($key['emp_group']=='635'){
				$emp_gr = 'O';
			}else{
				$tch_emp_gr = substr(fun_common($key['emp_group'],$code_data),3);
			}
		}else{
			$emp_gr = 'N';
		}
		if($key['emp_prev_form_date']){ //this field is not present in prd_employee_master table
			$emp_prev_form_date=$key['emp_prev_form_date'];
			}
		else{
			$emp_prev_form_date='01/01/1900';
		}
		if($key['emp_next_increment_amount']){
				$emp_incr_amt = str_pad($key['emp_next_increment_amount'],6,' ',STR_PAD_LEFT);    
			}else{
				$emp_incr_amt = '00';
			}
		if($key['emp_vacancy_status']){//this field is not present in prd_employee_master table
				$emp_vac_st = substr(fun_common($key['emp_vacancy_status'],$code_data),0,1);
			}else{
				$emp_vac_st = 'N';
			}
		if($key['emp_spouse_job_status']=='1')
			$spous_stat='Y';
			else
			$spous_stat='N';
		if($key['emp_spouse_res']){
				$res_stat = str_pad(substr(fun_common($key['emp_spouse_res'],$code_data),0,15),15,' ');
			}else{
				$res_stat = 'N';
			}
		if($key['emp_spouse_house_schm']){
			$house_sch = $key['emp_spouse_house_schm'];
		}else{
			$house_sch ='N';
		}	
		if($key['emp_sex']){
				$sex = str_pad(fun_common('9'.$key['emp_sex'],$code_data),6,' ');
			}else{
				$sex = 'N';
		}
		if($key['emp_marital_status']){
				$marital_stat = fun_common($key['emp_marital_status'],$code_data);
			}else{
				$marital_stat = 'N';
			}
		if($key['emp_religion']){
				$emp_relig = str_pad(fun_common($key['emp_religion'],$code_data),9,' ');
			}else{
				$emp_relig = 'N';
			}
		if($key['emp_pre_vill'] && $key['emp_pre_post']){
			$address1=	$key['emp_pre_house_no']
					.$key['emp_pre_street_no']
					.$key['emp_pre_vill']
					.$key['emp_pre_post'].' '
					.$key['emp_pre_pin'].' '
					.find_location_district($key['emp_pre_dist']).' '
					.find_location_state($key['pre_state']);
		}else{
			$address1 = 'N';
		}
		if($key['emp_per_vill'] && $key['emp_per_post']){
			$address2=	$key['emp_per_house_no']
					.$key['emp_per_street_no']
					.$key['emp_per_vill']
					.$key['emp_per_post'].' '
					.$key['emp_per_pin'].' '
					.find_location_district($key['emp_per_dist']).' '
					.find_location_state($key['per_state']);
					
		}else{
			$address2 = 'N';
		}
		if($key['emp_pan_no']){
			$emp_pan = $key['emp_pan_no'];
		}else{
			$emp_pan = 'N';
		}
		if($key['emp_diff_able']=='1')
			$handi_stat='YES';
			else
			$handi_stat='NO';	
			
		if($key['pf_code']){//not present
			$pf_code = $key['pf_code'];
		}else{
			$pf_code = '00';
		}
		if($key['hra']){
				$emp_hra = str_pad($key['hra'],8,' ');
			}else{
				$emp_hra = '00';
			}	
			
		if($key['emp_grade_pay']){
				$emp_grade_pay1 = str_pad($key['emp_grade_pay'],5,' ',STR_PAD_LEFT);
				$emp_grade_pay = str_replace("/-","",$emp_grade_pay1);
			}else{
				$emp_grade_pay = '00';
			}
			
		/*if($key['emp_dcrb']=='1'){  //emp_dcrb field not present in prd_employee_master table
			$pen_opt='DCRB';
		}else{
			$pen_opt='N';
		}
		
		
		
			
		
		if($key['emp_vacancy_status']=='271'){
			$emp_catagry='Regular';
		}else{
			$emp_catagry='Not Regular';
		}
		
		if($key['emp_mobile_no']){
			$mobile_no = $key['emp_mobile_no'];
		}else{
			$mobile_no = '0000000000';
		}
		if($key['emp_mail_id']){
			$mail_id = $key['emp_mail_id'];
		}else{
			$mail_id = 'na@na.com';
		}
		
		
		
		
		if($key['employee_group']){
			if($key['employee_group']=='635'){
				$tch_emp_gr = 'O';
			}else{
				$tch_emp_gr = substr(fun_common($key['employee_group'],$code_data),3);
			}
		}else{
			$tch_emp_gr = 'N';
		}
		
			
		
		
		
		
		if($key['tchcd']){
				$tchcd = substr($key['tchcd'],0,9);
			}else{
				$tchcd = 'N';
			}
		
		
	if($key['emp_bank_micr']){
				$micr_no = str_pad(substr($key['emp_bank_micr'],0,9),9,' ');
			}else{
				$micr_no = 'N';
			}	
			if($key['caste']){
				$emp_caste = str_pad(substr(fun_common('10'.$key['caste'],$code_data),0,3),10,' ');
			}else{
				$emp_caste = 'N';
			}	
			if($key['tch_approval_no'] && $key['tch_approval_date']){
				$tch_app_rec = str_pad(substr($key['tch_approval_no'].dateshow_slash($key['tch_approval_date']),0,25),25,' ');
			}else{
				$tch_app_rec = 'N';
			}	
			if($key['emp_proff_quali']){
				$prof_qual = str_pad(substr(fun_common($key['emp_proff_quali'],$code_data),0,20),21,' ');
			}else{
				$prof_qual = 'N';
			}
			if($key['emp_quali']){
				$emp_qual = str_pad(substr(fun_common($key['emp_quali'],$code_data),0,20),20,' ');
			}else{
				$emp_qual = 'N';
			}	*/
	
	
	
	//-------------------------------------------------------------
	
$content.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
				.substr($key['salary_monthyear'],0,4)."#"
				.str_pad(substr($block_code_array1."/".$block_code_array2."/".$block_code_array3,0,11),11,' ')."#"
				.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
				.str_pad(substr(($key['gp_name']),0,40),40,' ')."#N"
				."#"
				.substr($financial_year,0,10)."#"
				.$emp_name."#"
				.$emp_sl."#"
				.str_pad(substr(find_catagory($key['category']),0,40),40,' ')."#"
				."2009#"
				.$emp_basic_pay."#"
				.'N'."#"
				.$emp_basic."#"
				.str_pad($pf_type,10,' ')."#N"
				."#"
				.$tch_emp_gr."#"
				.dateshow_slash($key['emp_dob'])."#"
				.dateshow_slash1($emp_prev_form_date)."#"
				.dateshow_slash($key['emp_first_join_date'])."#"
				.dateshow_slash($key['emp_next_increment_date'])."#"
				.$emp_incr_amt."#"
				.$emp_vac_st."#N"
				."#"
				.'S'."#"
				.$spous_stat."#"
				.$res_stat."#"
				.str_pad('N',30,' ')."#"
				.str_pad(substr($house_sch,0,1),1,' ')."#"
				.$sex."#"
				.$marital_stat."#"
				.$emp_relig."#"
				.str_pad(substr($address1,0,40),40,' ')."#"
				.str_pad(substr($address2,0,20),20,' ')."#"
				.substr($tch_pan,0,10)."#"
				.str_pad(substr('Bank Account',0,20),20,' ')."#"
				.str_pad(substr($key['bankname'],0,20),20,' ')."#"
				.str_pad($key['accountno'],10,' ')."#"
				.'Y'."#"
				.'1'."#"
				.str_pad(substr($arr2[0]['bill_no'],0,10),10,' ')."#"
				.dateshow_slash(substr($arr2[0]['bill_entry_time'],0,10))."#"
				."N#"
				.dateshow_slash(substr($key['emp_retirement_date'],0,10))."#"
				.str_pad('N',2,' ')."#"
				.str_pad($handi_stat,3,' ')."#01/01/1900"
				."#"
				.str_pad("N",3,' ')."#"
				.str_pad($pf_code,15,' ')."#"
				.$tchcd."#"
				."01/01/1900#"
				.$emp_hra."#"
				.str_pad("N",3,' ')."#"
				.$tch_grade_pay."#"
				.str_pad('0',5,' ',STR_PAD_LEFT)."#"
				.str_pad($ma_opt,3,' ')."#"
				.str_pad(substr($fath_name,0,30),30,' ')."#"
				.str_pad(substr($mother_name,0,30),30,' ')."#"
				.str_pad(substr($spouse_name,0,30),30,' ')."#"
				.str_pad(substr($emp_name,0,30),30,' ')."#"
				/*.str_pad($pen_opt,10,' ')."#"
				.str_pad(substr(find_catagory($key['category']),0,40),40,' ')."#"
				.$micr_no."#"
				.str_pad($key['bank_ifsc'],11,' ')."#"
				.str_pad($key['accountno'],20,' ')."#"
				.str_pad($emp_catagry,12,' ')."#"
				.$emp_caste."#"
				.$tch_app_rec."#"
				.$prof_qual."#"
				.$emp_qual."#"
				.str_pad(substr($key['schcd'].$key['tchcd'],0,17),17,' ')."#"
				.str_pad($mobile_no,11,' ')."#"
				.str_pad($mail_id,20,' ')."##".*/."\r\n";
				
						 $content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['basic_head']."#"
						.str_pad($key['basic'],6,' ',STR_PAD_LEFT)."#ERN##"."\r\n";
								
			  			 $content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['grade_pay_head']."#"
						.str_pad($emp_grade_pay,6,' ',STR_PAD_LEFT)
						."#ERN##"."\r\n";
						
						
						 $content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['da_head']."#"
						.str_pad($key['da'],6,' ',STR_PAD_LEFT)."#ERN##"."\r\n";
						
						$content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['hra_head']."#"
						.str_pad($key['hra'],6,' ',STR_PAD_LEFT)."#ERN##"."\r\n";
						
						$content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['ma_head']."#"
						.str_pad($key['ma'],6,' ',STR_PAD_LEFT)."#ERN##"."\r\n";
						
						$content_ern.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.$arr2[0]['bill_no']."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$schtype_code."#"
						.$arr3[0]['oth_head']."#"
						.str_pad($key['spl_pay'],6,' ',STR_PAD_LEFT)."#ERN##"."\r\n";
						
						$content_ded.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.str_pad($arr2[0]['bill_no'],10,' ')."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$ptax_head."#"
						.str_pad($key['p_tax'],6,' ',STR_PAD_LEFT)."#DED##"."\r\n";
						
						$content_ded.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.str_pad($arr2[0]['bill_no'],10,' ')."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$itax_head."#"
						.str_pad($key['i_tax'],6,' ',STR_PAD_LEFT)."#DED##"."\r\n";
						
						$content_sub.= 	date("M",strtotime(substr($key['salary_monthyear'],0,4)."-".substr($key['salary_monthyear'],4)."-"."01"))."#"
						.substr($key['salary_monthyear'],0,4)."#"
						.$block_code_array1."/".$block_code_array2."/".$block_code_array3."#"
						.str_pad($arr2[0]['bill_no'],10,' ')."#"
						.dateshow_slash($arr2[0]['bill_entry_time'])."#"
						.str_pad(substr($key['gp_code']."/".$key['code'],0,20),20,' ')."#"
						.$gpf_head."#"
						.str_pad($key['pf_deduct'],8,' ',STR_PAD_LEFT)."#SUB##"."\r\n";
	
}
//File Generate ----------------------------------------------------------------------------------------------------------------------------------------
$bill_date_array=  str_replace('/','',date_frmt_change($arr2[0]['bill_entry_time']));

echo $path = '../../../../readwrite/text_file/'.substr($arr2[0]['salary_monthyear'],4).substr($arr2[0]['salary_monthyear'],0,4).'31'.$block_ddo_code_name.str_replace('/','',$arr2[0]['bill_no']).$bill_date_array.'_Personnel.txt';
    $file = fopen($path,"w");
     fwrite($file,$content);
	 fwrite($file,$content_ern);
	 fwrite($file,$content_ded);
	 fwrite($file,$content_sub);
	
	
	fclose($file);
//Force Download ---------------------------------------------------------------------------------------------------------------------------------------
//$filename=substr($bill_det['salary_monthyear'],4).substr($bill_det['salary_monthyear'],0,4)."31".$dpsc_code_name.$billno.$billdate."_Personnel.txt";

	if (file_exists($path)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($path));
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($path));
	    ob_clean();
	    flush();
	    readfile($path);
	    exit;
	}
?>