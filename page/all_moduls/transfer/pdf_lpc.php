<?php
//echo "dafssf";exit;
//echo 88; die;
//error_reporting(E_ALL);        // Report all errors
//ini_set('display_errors', 1);  // Show errors on page
//ini_set('display_startup_errors', 1);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
             || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$_SESSION['HTTP_REFERER'] = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
//require 'includes/library/session.class.php';
//echo $_SERVER['HTTP_REFERER'];exit;
//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$cryptoGraph=new cryptography();
$db=new database();

$logged_user=$_SESSION['user_info']['stake_abbr'];
$emp_id=$cryptoGraph->decode($_POST['enc_emp_id'],4);
$gp_id=$cryptoGraph->decode($_POST['enc_gp_id'],4);

$cur_date=$_POST['cur_date'];

$newdate = strtotime ( '-1 month' , strtotime ($cur_date));
$newdate = date ( 'Y-m-d' , $newdate);
$newdate_exp=explode('-',$newdate);
 $lpc_monthyear= $newdate_exp['0'].$newdate_exp['1'];
 $lpc_month=$newdate_exp['1'];
$lpc_year=$newdate_exp['0'];
// echo $lpc=date('F', strtotime($lpc_month));die;
//echo "affdf9";exit;
  
 function fun_lpc($month_code){
	 
	 //echo $month_code; die;
	 $month_array=array("01"=>"January","02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July" , "08"=>"August" ,"09"=>"September", "10"=>"October" ,"11"=>"November", "12"=>"December");
	foreach ($month_array as $key=>$value) {
		if($key == $month_code){
				return $value;
				break;
		}

	}
 }
 //echo fun_lpc($lpc_month)."&nbsp;".$lpc_year; die;
//------------------------------------------------------------------------------------------------------------
if($logged_user=='BDO')
{
	//echo "affdf1";exit;
	$salary_monthyear=date("Ym",strtotime("-1 months")); 
	//echo "SELECT transfer_date,lpc_number FROM prd_employee_transfer WHERE emp_id_fk='".$emp_id."' AND gp_id_fk='".$gp_id."'";exit;
	$emp_transfer_date_fetch=$db->fetch_table("SELECT transfer_date,lpc_number FROM prd_employee_transfer WHERE emp_id_fk='".$emp_id."' AND gp_id_fk='".$gp_id."'");
	$transfer_date_exp=explode('-',$emp_transfer_date_fetch[0]['transfer_date']);
	$transfer_monthyear= $transfer_date_exp['0'].$transfer_date_exp['1'];
	
	if ($emp_transfer_date_fetch[0]['lpc_number'] == '0' || 
    $emp_transfer_date_fetch[0]['lpc_number'] == '' ) 
	{
		$lpc_number=rand(100000,999999);
		//echo $lpc_number;exit;
	}
	else
	{
		$lpc_number=$emp_transfer_date_fetch[0]['lpc_number'];
	}
	
	//echo $lpc_monthyear;exit;
}

if($logged_user=='EO')
{


$db=new database();



	$salary_monthyear=date("Ym",strtotime("-1 months")); 
 
	$emp_transfer_date_fetch=$db->fetch_table("SELECT transfer_date,lpc_number FROM prd_employee_transfer WHERE emp_id_fk='".$emp_id."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
	$transfer_date_exp=explode('-',$emp_transfer_date_fetch[0]['transfer_date']);
	$transfer_monthyear= $transfer_date_exp['0'].$transfer_date_exp['1'];
	
	if($emp_transfer_date_fetch[0]['lpc_number']=='0')
	{
		$lpc_number=rand(100000,999999);
	}
	else
	{
		$lpc_number=$emp_transfer_date_fetch[0]['lpc_number'];
	}
	
	
	 
}
 

if($logged_user=='AEO')
{

	$salary_monthyear=date("Ym",strtotime("-1 months")); 
	//echo "SELECT transfer_date,lpc_number FROM prd_employee_transfer WHERE emp_id_fk='".$emp_id."' AND zp_id_fk='".$_SESSION['location']['district_id']."'"; die;
	$emp_transfer_date_fetch=$db->fetch_table("SELECT transfer_date,lpc_number FROM prd_employee_transfer WHERE emp_id_fk='".$emp_id."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");
	$transfer_date_exp=explode('-',$emp_transfer_date_fetch[0]['transfer_date']);
	$transfer_monthyear= $transfer_date_exp['0'].$transfer_date_exp['1'];
	
	if($emp_transfer_date_fetch[0]['lpc_number']=='0')
	{
		 $lpc_number=rand(100000,999999); 
	}
	else
	{
		 $lpc_number=$emp_transfer_date_fetch[0]['lpc_number']; 
	}
}

pg_query('BEGIN');
/*echo "UPDATE 
							prd_employee_transfer 
						SET 
							lpc_status=1,
							lpc_number='".$lpc_number."'
						WHERE
							emp_id_fk='".$emp_id."' AND lpc_status=0 AND transfer_emp_status not in (2)
						";exit;*/
$transfer_update=$db->update("UPDATE 
							prd_employee_transfer 
						SET 
							lpc_status=1,
							lpc_number='".$lpc_number."'
						WHERE
							emp_id_fk='".$emp_id."' AND lpc_status=0 AND transfer_emp_status not in (2)
						");
						
						
if($logged_user=='BDO')
{
	//echo "affdf";exit;
	//echo "UPDATE prd_employee_master SET emp_status=2 WHERE emp_id_pk='".$emp_id."' AND gp_id_fk='".$gp_id."'";exit;
    $emp_salary_stop_update=$db->update("UPDATE prd_employee_master SET emp_status=2 WHERE emp_id_pk='".$emp_id."' AND gp_id_fk='".$gp_id."'");
	//echo "affdf666";exit;
}
else if($logged_user=='EO')
{
    $emp_salary_stop_update=$db->update("UPDATE prd_employee_master SET emp_status=2 WHERE emp_id_pk='".$emp_id."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
}
else if($logged_user=='AEO')
{
	
	$emp_salary_stop_update=$db->update("UPDATE prd_employee_master SET emp_status=2 WHERE emp_id_pk='".$emp_id."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");
}

 // echo $transfer_update;exit;	
  

if($transfer_update && $emp_salary_stop_update)
{
	
    pg_query('COMMIT');
    if($logged_user=='BDO')
    {
		//print_r($_POST);exit;
	    $db=new database();
	    $info=$db->fetch_table("select  block_code,mobile_no,pin_code, email_id,contact_no from prd_block_profile where block_code='".$_SESSION['location']['block_code']."'");

	    $contact=$info[0]['mobile_no'];
	    $pin=$info[0]['pin_code'];
	    $email=$info[0]['email_id'];

		/*echo "SELECT tr.emp_first_name,
			    tr.emp_second_name, 
			    tr.emp_last_name,
			    tr.transfer_gp_id_fk,
			    tr.transfer_block_id_fk,
			    tr.transfer_district_id_fk,
			    tr.emp_pay_band, 
			    tr.emp_pay_in_payband,
			    final.pay_payband,
			    tr.emp_pay_scale,
				tr.emp_sex,
			    final.tch_grade_pay,
				final.salary_monthyear,
			    tr.emp_grade_pay,
			    tr.transfer_date,
			    final.bank_ifsc,
			    final.accountno,
			    final.basic,
			    final.da,
			    final.hra,
			    final.ma,
				final.festival_loan,
			    final.pf_loan,
			    final.p_tax,
			    final.pf_deduct,
			     final.i_tax,
			    final.interim_relief,
			    final.gsli,
				final.gpf,
			    tr.emp_id_const,
			    tr.transfer_ps_id_fk, 
			    tr.gp_id_fk, 
			    tr.transfer_date,
			    tr.emp_next_increment_date,
			    tr.emp_desig,
				final.allowance	
			    FROM prd_employee_transfer as tr
			    INNER JOIN prd_monthly_salary_archive_final as final ON final.emp_id_fk=tr.emp_id_fk where tr.gp_id_fk='".$gp_id."'  and final.emp_id_fk='".$emp_id."' and tr.transfer_emp_status='0'
				order by final.archive_final_pk DESC LIMIT 3
				";exit;*/
			    $trsfer_gp_id_fk= $db->fetch_table("SELECT tr.emp_first_name,
			    tr.emp_second_name, 
			    tr.emp_last_name,
			    tr.transfer_gp_id_fk,
			    tr.transfer_block_id_fk,
			    tr.transfer_district_id_fk,
			    tr.emp_pay_band, 
			    tr.emp_pay_in_payband,
			    final.pay_payband,
			    tr.emp_pay_scale,
				tr.emp_sex,
			    final.tch_grade_pay,
				final.salary_monthyear,
			    tr.emp_grade_pay,
			    tr.transfer_date,
			    final.bank_ifsc,
			    final.accountno,
			    final.basic,
			    final.da,
			    final.hra,
			    final.ma,
				final.festival_loan,
			    final.pf_loan,
			    final.p_tax,
			    final.pf_deduct,
			     final.i_tax,
			    final.interim_relief,
			    final.gsli,
				final.gpf,
			    tr.emp_id_const,
			    tr.transfer_ps_id_fk, 
			    tr.gp_id_fk, 
			    tr.transfer_date,
			    tr.emp_next_increment_date,
			    tr.emp_desig,
				final.allowance	
			    FROM prd_employee_transfer as tr
			    INNER JOIN prd_monthly_salary_archive_final as final ON final.emp_id_fk=tr.emp_id_fk where tr.gp_id_fk='".$gp_id."'  and final.emp_id_fk='".$emp_id."' and tr.transfer_emp_status='1'
				order by final.archive_final_pk DESC LIMIT 3
				"); 
		//print_r($trsfer_gp_id_fk); exit;
    }
    else if($logged_user=='EO')
    {
		//echo "affdf555";exit;
	    $db=new database();
	    $info=$db->fetch_table("select  mobile_no,pin_code, email,cotract_no 
	from psemp_ps_profile where ps_id_fk='".$_SESSION['location']['ps_id']."'");
	    $contact=$info[0]['mobile_no'];
	    $pin=$info[0]['pin_code'];
	    $email=$info[0]['email'];


			    $trsfer_gp_id_fk= $db->fetch_table("SELECT tr.emp_first_name,
			    tr.emp_second_name, 
			    tr.emp_last_name,
			    tr.transfer_gp_id_fk,
			    tr.transfer_block_id_fk,
			    tr.transfer_district_id_fk,
			    tr.emp_pay_band,
				tr.emp_sex,
			    final.pay_payband,
			    tr.emp_pay_in_payband, 
			    tr.emp_pay_scale,
			    final.tch_grade_pay,
				final.salary_monthyear,
			    tr.emp_grade_pay,
			    tr.transfer_date,
			    final.bank_ifsc,
			    final.accountno,
			    final.basic,
			    final.da,
			    final.hra,
			    final.ma,
				final.festival_loan,
			    final.pf_loan,
			    final.p_tax,
			    final.pf_deduct,
			     final.i_tax,
			    final.interim_relief,
			     final.gsli,
				 final.gpf,
				 final.hra_deduction,
			    tr.emp_id_const,
			    tr.transfer_ps_id_fk, 
			    tr.gp_id_fk, 
			    tr.transfer_date,
			    tr.emp_next_increment_date,
			    tr.emp_desig,
				final.allowance	
			    FROM prd_employee_transfer as tr
			    INNER JOIN prd_monthly_salary_archive_final as final ON final.emp_id_fk=tr.emp_id_fk 
		      where final.ps_id_fk='".$_SESSION['location']['ps_id']."'  and final.emp_id_fk='".$emp_id."' and tr.transfer_emp_status='0'
			  order by final.archive_final_pk DESC LIMIT 1
			  ");
    }
	
	
	else if($logged_user=='AEO')
	{
	//echo "affdf444";exit;	
	$db=new database();

	$info=$db->fetch_table("select pin_code, email,cotract_no 
	from zpemp_zp_profile where district_id_fk='".$_SESSION['location']['district_id']."'");
	$contact=$info[0]['cotract_no'];
	$pin=$info[0]['pin_code'];
	$email=$info[0]['email'];

	$trsfer_gp_id_fk= $db->fetch_table("SELECT tr.emp_first_name,
	tr.emp_second_name, 
	tr.emp_last_name,
	tr.transfer_gp_id_fk,
	tr.transfer_block_id_fk,
	tr.transfer_district_id_fk,
	tr.emp_pay_band,
	tr.emp_sex,
	final.salary_monthyear,
	final.pay_payband,
	tr.emp_pay_in_payband, 
	tr.emp_pay_scale,
	final.tch_grade_pay,
	final.total_loan_deduction,
	tr.emp_grade_pay,
	tr.transfer_date,
	final.bank_ifsc,
	final.accountno,
	final.basic,
	final.da,
	final.hra,
	final.ma,
	final.festival_loan,
	final.pf_loan,
	final.p_tax,
	final.pf_deduct,
	final.i_tax,
	final.interim_relief,
	final.gsli,
	final.hra_deduction,
	tr.emp_id_const,
	tr.transfer_ps_id_fk, 
	tr.gp_id_fk, 
	tr.transfer_date,
	tr.emp_next_increment_date,
	tr.emp_desig ,
	final.gpf,
	tr.zp_id_fk,
	final.allowance	
	FROM prd_employee_transfer as tr
	INNER JOIN prd_monthly_salary_archive_final as final ON final.emp_id_fk=tr.emp_id_fk 
	where final.zp_id_fk='".$_SESSION['location']['district_id']."'  and final.emp_id_fk='".$emp_id."' and tr.transfer_emp_status='0'
	 order by final.archive_final_pk DESC LIMIT 1
	");
	}

//echo "affdf3";exit;


	    function fun_gp($gp)
		    { 
			    $db = new database();
			    $data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
			    return $data[0]['gp_name'];
		    }
	    function fun_ps($p)
		    { 
			    $db = new database();
			    $data = $db->fetch_table("SELECT ps_name FROM   prd_location_master_panchayat_samiti  where ps_id_pk='".$p."'");
			    return $data[0]['ps_name'];
		    }
	    function fun_block($block)
		    { 
			    $db = new database();
			    $data = $db->fetch_table("select block_name,block_id_pk
			    from prd_location_master_block where block_id_pk='".$block."'");
			    return $data[0]['block_name'];
		    }
	    function fun_dis($dis)
		    { 
			    $db = new database();

    //echo("SELECT gp_name FROM  prd_location_master_gp where gp_id_pk='".$gp_id."'");die;

			    $data = $db->fetch_table("select district_code,district_name 
			    from prd_location_master_district where district_id_pk='".$dis."'");
			    return $data[0]['district_name'];
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
	function fun_desig1($dsig)
	{ 
		$db = new database();
		$data = $db->fetch_table("
		SELECT designation_id, designation_name
		FROM zpemp_emp_desig_master where designation_id='".$dsig."'");
		return $data[0]['designation_name'];
	}
	    function fun_payband($val)
		    {
			    $db = new database();
			    $data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
			    return $data[0]['payband_name'];
		    }
	    function fun_grade_pay($val)
		    {
			    $db = new database();
			    //echo("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");die;
			    $dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."'");
			    return $dist_data2[0]['grade_amount'];
		    }	
	    function fun_payscale($val)
		    {
			    $db = new database();
			    $data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
			    return $data[0]['payscale_range'];
		    }
	    function fun_dist($val)
		    {
			    $db = new database();
			    $dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
			    return $dist_data2[0]['district_name'];
		    }
	    function fun_bank($val)
		    {
			    $db = new database();
			    $dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
			    return $dist_data2[0]['bank_name'];
		    }
	    function date_frmt($original_date)
		    {
		    if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL)
			    {
				    return "---";
			    }
		    else
			    {
				    $old=explode("-",$original_date);
				    $new=$old[2]."-".$old[1]."-".$old[0];
				    return $new;
			    }
		    }	
	    function fun_state($val)
	    {
		    if($val=='32')
		    {
			    return 'WEST BENGAL';
		    }
		    else
		    {
			    return 'OTHERS';
		    }
	    }
			    $Employee_ID_val=$emp_data[0]['emp_id_const']; 
	    if($Employee_ID_val=='0')
	    {
			    $Employee_ID='';

	    }
	    else
	    {
			    $Employee_ID=$emp_data[0]['emp_id_const']; 

	    }

	    if($emp_data[0]['emp_land_no']==0)
	    {
			    $Land_Tel_No=='';

	    }
	    else
	    {

			    $Land_Tel_No==$emp_data[0]['emp_id_const'];
	    }
		
		
//echo "affdf4";exit;
	    //

	   // print_r($emp_data);
	   // exit;
	
	    //------------------------------------------------------------------------------------------------------	
	   //define('_MPDF_TEMP_PATH', __DIR__ . '/../../../locker/temp/');

		require_once __DIR__ . '/../../../includes/third-party/mpdf/vendor/autoload.php';
		//echo "ddfssfd";exit;
	    $stylesheet ='';
		//$mpdf = new Mpdf();


		$mpdf = new \Mpdf\Mpdf([
			'tempDir' => '/var/www/html/locker/temp/mpdf'
		]);
			//echo "fdfafd";exit;
		//$mpdf->WriteHTML('Hello World');
		//$mpdf->Output();
		
		if(!empty($trsfer_gp_id_fk[0])){
		
	     $trsfer_date=substr($trsfer_gp_id_fk[0]['transfer_date'],5,-3);
		 $month=$trsfer_gp_id_fk[0]['salary_monthyear']; 
		  
		  
		  
		  
		$newdate1 = date ( 'Y-m' , $month); 
		//$newdate1 =  $month;
		$newdate_exp1=explode('-',$newdate1); 
		
		$paid_month_year= $newdate_exp1['0'].$newdate_exp1['1'];
		$paid_month=$newdate_exp1['1']; 
		$paid_year=$newdate_exp['0'];
		
		
		
		$paid_month_new= substr($month,-2); 
		  // date("F", mktime(0, 0, 0, $month)); 
		  
		  //echo strtoupper(date('$month')); die;
     $prev_month = date("M",strtotime( "last month"));
    $emp_next_increment_date=date('d-m-Y', strtotime($trsfer_gp_id_fk[0]['emp_next_increment_date']));
    if($logged_user=='BDO')
    {
		
    $name=$_SESSION['location']['block_name'];
    $admin_name="Block Development Officer";
    }
    else if ($logged_user=='EO')
    {
    $name=fun_ps($_SESSION['location']['ps_id']); 
    $admin_name="Executive Officer";
    }
	else if ($logged_user=='AEO')
    {
    $name=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD'; 
  // $admin_name="Additional Executive Officer[AEO]";
	$admin_name="FC&CAO";
    }
	
	if($trsfer_gp_id_fk[0]['emp_sex']=='92')
	{
		$gen="She";
		$gen1="His/Her";
	}
	else
	{
		$gen="He";
		$gen1="His/Her";
	}
    //echo 'Previous Month--'.$prev_month;
    //echo 'Current Month--'.date('M');die;

	    $mpdf->WriteHTML($stylesheet,1);
		
	    //Header and footer
	    $mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
 if($logged_user=='BDO')
	    {
				
	    //PDF header
		
	    $mpdf->WriteHTML('


						    <table>
							    <tr>
								    <td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
								    <td class="he2" style="text-align: center;width: 480px;">
									 

									    <div class="logo" style="text-align: center;"><img width="38" src="../../../themes/default/image/ashoka.jpg" /></div>
									    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Government of West Bengal</p>
									    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Office of the Block development officer</p>
	    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">'.$name.','.$_SESSION['location']['district_name'].',PIN:'.$pin.'</p>

	    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Phone/Fax : '.$contact.'::Email :'.$email.'</p>
								    </td>	
							    </tr>

					    </table><hr>',2);
		}
		else
		{
			$mpdf->WriteHTML('


						    <table>
							    <tr>
								    <td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
								    <td class="he2" style="text-align: center;width: 480px;">
			
	    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Office of the '.$name.', '.$_SESSION['location']['district_name'].',PIN:'.$pin.'</p>

	    <p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Phone/Fax : '.$contact.'::Email :'.$email.'</p>
								    </td>	
							    </tr>

					    </table><hr>',2);
		}

							    $mpdf->WriteHTML('<table align="right"><tr><td>

									    <div align="right" style="color:blue;">LPC No. - '.$lpc_number.'</div>

								    </td>   </tr></table>
						    ',2);
		    if($logged_user=='BDO')
	    {
			//echo "fddfasfddf1";exit;	
			    if($trsfer_gp_id_fk[0]['transfer_gp_id_fk']!=0)	
		    {	
		//echo "fddfasfddf2";exit;

					    $g=fun_gp($gp_id).' under '. $_SESSION['location']['block_name'] .' proceeding on to '.fun_gp($trsfer_gp_id_fk[0]['transfer_gp_id_fk']).',under '.fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';

				    $trsfer_admin_name="Block Development Officer ,".fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';

				    $trsfer_district=fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
		    }
			    else
		    {
				    $g=fun_gp($gp_id).' under '. $_SESSION['location']['block_name'] .' proceeding on to '.fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under ,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';
				    $trsfer_admin_name="Executive Officer,".fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under ,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';

		    }
	    }


		    else if($logged_user=='EO')
		    {

				if($trsfer_gp_id_fk[0]['transfer_gp_id_fk']!=0)	
				{	
					$g=fun_ps($_SESSION['location']['ps_id']).' proceeding on to '.fun_gp($trsfer_gp_id_fk[0]['transfer_gp_id_fk']).',under '.fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';
					$trsfer_admin_name="Block Development Officer,".fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';
					$trsfer_district=fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
				}
				else
				{
					$g=fun_ps($_SESSION['location']['ps_id']).' proceeding on to '.fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';
					$trsfer_admin_name="Executive Officer,".fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';
				
				}
		    }
			
		else if($logged_user=='AEO')
		    {

				if($trsfer_gp_id_fk[0]['transfer_ps_id_fk']!=0)	
				{	
					$g=fun_dis($_SESSION['location']['district_id']).' Zilla Parishad proceeding on to '.fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under '.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';
					//$trsfer_admin_name="Block Development Officer,".fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';
					
					$trsfer_admin_name="Executive Officer,".fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'';
				}
				else if($trsfer_gp_id_fk[0]['transfer_ps_id_fk']==0 && $trsfer_gp_id_fk[0]['transfer_district_id_fk']==0)
				{
					$g= fun_dis($_SESSION['location']['district_id']).' Zilla Parishad proceeding on to Other Department.';
					$trsfer_district="Additional Executive Officer[AEO],".fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
				}
				else
				{
					$g=fun_dis($_SESSION['location']['district_id']).' Zilla Parishad proceeding on to '.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).',under,'.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).'.';
					$trsfer_district="Additional Executive Officer[AEO],".fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
				
				}
		    }	
		if($logged_user=='BDO')
	    {	$designation='erstwhile Gram Panchayat '.fun_desig($trsfer_gp_id_fk[0]['emp_desig']);
		}
		else if($logged_user=='EO')
		{
			$designation=fun_desig($trsfer_gp_id_fk[0]['emp_desig']);
		}
		else if($logged_user=='AEO')
		{
			$designation=fun_desig1($trsfer_gp_id_fk[0]['emp_desig']);
		}
		;

	    $mpdf->WriteHTML('<div class="segment">
	    <p class="department" style="font-size: 11px;margin: 0px;padding:0px;"><b>T.R.Form No.13 (See Sub-Rule(l) of T.R.4021) :</b></p>
	    <p class="department" style="font-size: 15px;" align="center"><b><u>LAST PAY CERTIFICATE</u></b></p>

     <table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
       <tr>
		    <td>1.	Last Pay Certificate of '.$trsfer_gp_id_fk[0]['emp_first_name'].' '.$trsfer_gp_id_fk[0]['emp_second_name'].' '.$trsfer_gp_id_fk[0]['emp_last_name'].','.$designation.' of '.$g.'</td></tr>
      </table>',2);
	  

	    $mpdf->WriteHTML(' 
	  <table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
		 <tr>
			    <td>2.'.$gen.' has been paid up to '.fun_lpc($paid_month_new)."&nbsp;".$lpc_year.' at the following rates In the revised Pay Band
    of Rs.'.$trsfer_gp_id_fk[0]['pay_payband'].'.00/-+ Grade Pay Rs.'.$trsfer_gp_id_fk[0]['tch_grade_pay'].'.00 ('.fun_payband($trsfer_gp_id_fk[0]['emp_pay_band']).').Scale:'.fun_payscale($trsfer_gp_id_fk[0]['emp_pay_scale']).'

			    </td>
		    </tr>
		    <tr>
			    <td>3.'.$gen.' made over the Official Charges on '.date('d-m-Y', strtotime($trsfer_gp_id_fk[0]['transfer_date'])).'.
			    </td>
		    </tr>
      </table>',2);

    $mpdf->WriteHTML('<div class="segment"><br></br>
     <table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">

	       <tr>
				    <th  align="left" color="#263238;font-size: 18px;"><u>PARTICULARS :-</u></th>
				    <th align="left" color="#263238;font-size: 18px;"><u>RATE OF DEDUCTION</u></th>
			    </tr>

			    <tr>
				<td style="width:50%">Band Pay:-Rs.'.$trsfer_gp_id_fk[0]['pay_payband'].'.00</td>
				    <td style="width:50%">A)P.F.Subscription:·Rs.'.$trsfer_gp_id_fk[0]['gpf'].'.00</td>
			    </tr>
			    <tr>
				<td style="width:50%">Grade Pay:-Rs.'.$trsfer_gp_id_fk[0]['tch_grade_pay'].'.00</td>
				    <td style="width:50%">B)Income Tax:-Rs.'.$trsfer_gp_id_fk[0]['i_tax'].'.00</td>
			    </tr>
			    <tr>
				<td style="width:50%">Basic Pay:-Rs.'.$trsfer_gp_id_fk[0]['basic'].'.00</td>
				    <td style="width:50%">C)Profession Tax:-Rs.'.$trsfer_gp_id_fk[0]['p_tax'].'.00</td>
			    </tr>
			    <tr>
				<td style="width:50%">Special Pay:- --</td>
				    <td style="width:50%">D)G.S.L.I:-Rs.'.$trsfer_gp_id_fk[0]['gsli'].'.00 </td>
			    </tr>
			    <tr>
				<td style="width:50%">Personal Pay:- -- </td>
				    <td style="width:50%">E)Loan  Recovery:- Rs.'.$trsfer_gp_id_fk[0]['pf_loan'].'.00 </td>
			    </tr>
				
			    <tr>
				<td style="width:50%">Leave Salary:- --</td>',2);
				if($logged_user=='EO' || $logged_user=='AEO')
				{
					if($trsfer_gp_id_fk[0]['hra_deduction']==0 || $trsfer_gp_id_fk[0]['hra_deduction']=='')
					{
						$mpdf->WriteHTML('<td style="width:50%">F)HRA Deduction:- --</td>',2);
					}
					else
					{
						$mpdf->WriteHTML('<td style="width:50%">F)HRA Deduction:-Rs.'.$trsfer_gp_id_fk[0]['hra_deduction'].'.00</td>',2);
					}
				}
				
				
				
				
				if($logged_user=='EO' || $logged_user=='AEO' ||$logged_user=='BDO')
				{
					if($trsfer_gp_id_fk[0]['festival_loan']==0 || $trsfer_gp_id_fk[0]['festival_loan']=='')
					{
						$mpdf->WriteHTML('<td style="width:50%">E)FESTIVAL AD:- --</td>',2);
					}
					else
					{
						$mpdf->WriteHTML('<td style="width:50%">E)FESTIVAL AD:-Rs.'.$trsfer_gp_id_fk[0]['festival_loan'].'.00</td>',2);
					}
				}
				
		    
				
   	$mpdf->WriteHTML(' </tr></table>',2);

    $mpdf->WriteHTML('<div class="segment"><br></br>
     <table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
		    <tr>
				    <th  align="left" color="#263238;font-size: 18px;"><u>ALLOWANCES:</u></th>
		       </tr>
			    <tr>
				<td style="width:50%">a) D.A./A.D.A :-Rs.'.$trsfer_gp_id_fk[0]['da'].'.00</td>
			    </tr>
			    <tr>
				<td style="width:50%">b) Medical Allowances:-Rs.'.$trsfer_gp_id_fk[0]['ma'].'.00</td>
			    </tr>
			    <tr>
				<td style="width:50%">c)H.R.A:-Rs.'.$trsfer_gp_id_fk[0]['hra'].'.00</td>
			    </tr>
			   
				
				<tr>
				<td style="width:50%">d)Administrative Allowances :-Rs.'.$trsfer_gp_id_fk[0]['allowance'].'.00</td>
			    </tr>
				<tr>
				<td style="width:50%">e)I.R:-Rs.'.$trsfer_gp_id_fk[0]['interim_relief'].'.00</td>
			    </tr><br></br><br></br>
			    <tr>
				
				
				    <td style="width:50%">4.His <b>i-OSMS</b> Employee Code No:<b>'.$trsfer_gp_id_fk[0]['emp_id_const'].'</b></td>
		   </tr>
		       <tr>
				    <td style="width:50%">5. His Permanent Salary Bank A/C No.:'.$trsfer_gp_id_fk[0]['accountno'].'.IFSC CODE:'.$trsfer_gp_id_fk[0]['bank_ifsc'].', '.$gen.' is entitled to draw as mentioned above.</td>
		    </tr>
			    <tr>
				    <td style="width:50%">6.'.$gen.' has been sanctioned --- Leave Proceeding joining time for --- days.</td>
			    </tr>
			    <tr>
				    <td style="width:50%">7.'.$gen1.' date of next Increment is:-'.$emp_next_increment_date.'</td>
			    </tr>
			    <tr>
				    <td style="width:50%">8. '.$gen.' enjoyed casual leave for Current Calendar year '.$lpc_year.' :----</td>
			    </tr>
			    <br><br><br></br></br></br>
			    <tr>
				    <td align="right"><b>'.$admin_name.'</b></td>
			    </tr>
			    <tr>
				    <td align="right"><b>'. $name .','.$_SESSION['location']['district_name'].'</b></td>
			    </tr>
		    <br><br></br></br>

			    <tr>
				    <td ><div style="float:right; text-align:right;"><b>Memo  No.:·...................../......</b></td>

			    </tr>
			    <tr>
			    <td>Date:-</td>
			    </tr>
			    <tr>
				    <td style="width:50%">Forwarded to the '.$trsfer_admin_name.' for his kind Information.</td>

			    </tr>
			    <tr>
				    <td align="right"><b>'.$admin_name.'</b></td>
			    </tr>
			    <tr>
				    <td align="right"><b>'.$name.','.$_SESSION['location']['district_name'].'</b></td>
			    </tr>

	    </table>',2);

	    $mpdf->Output($trsfer_gp_id_fk[0]['emp_first_name'].' '.$trsfer_gp_id_fk[0]['emp_second_name'].' '.$trsfer_gp_id_fk[0]['emp_last_name'].'.pdf','D');
		}
		else
		{
	//echo $_POST['enc_gp_id'];exit;
	
	pg_query('ROLLBACK');
    $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>LPC generation fails...</strong></div>';
    header('Location:employee_list.php?gp_id_fk='.$_POST['enc_gp_id']);	
		}
	
}
else
{
    pg_query('ROLLBACK');
    $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>LPC generation fails...</strong></div>';
    header('Location:employee_list.php?gp_id_fk='.$_POST['gp_id']);
}

?>