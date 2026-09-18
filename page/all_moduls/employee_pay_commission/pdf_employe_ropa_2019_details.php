<?
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
//echo 99; die;
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$crypto = new cryptography();
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$db = new database();

//$ddo_username=substr($_SESSION['user_info']['stake_user'],0,7);
//$data3=$db->fetch_table("SELECT municipality_code from mad_location_master_ddo WHERE municipality_id_fk='".$ddo_username."'");
//$ddo_login_code=$data3[0]['municipality_code'];
 $emp_id_fk = $cryptoGraph->decode($_GET['id'],4); 

$db=new database();
//$ddo_username=substr($_SESSION['user_info']['stake_user'],0,7);
//$ddo_username= $_SESSION['user_info']['stake_user'];

//$data3=$db->fetch_table("SELECT municipality_code from mad_location_master_ddo WHERE municipality_id_fk='".$ddo_username."'");
//$data3=$db->fetch_table("SELECT deo_code from mad_location_master_ddo_deo WHERE gp_code='".$ddo_username."'");
//$ddo_login_code=$data3[0]['deo_code'];
$logged_user=$_SESSION['user_info']['stake_abbr'];
//$ddo_username=substr($_SESSION['user_info']['stake_user'],0,7);
/*$data3=$db->fetch_table("SELECT municipality_code from mad_location_master_ddo WHERE municipality_id_fk='".$ddo_username."'");
$ddo_login_code=$data3[0]['municipality_code'];*/
$emp_data
=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($_GET['id'],4)."'" );
//print_r($emp_data); exit;
$Employee_ID=$emp_data[0]['emp_id_const']; 

if($emp_data[0]['zp_emp_type']=='' || $get_emp_joining_date[0]['zp_emp_type']=='0' || $get_emp_joining_date[0]['zp_emp_type']=='367')
		{
		
		  $ropa_mem='10485/PN/O/III/2E-34/2019, Dated: 24.12.2019';
		  $dep=', Panchayats & Rural Development Department, GOVT. OF WB';
		}
		else 
		{
		 
		 $ropa_mem='5562-F, Dated: 25.09.2019';
		 $dep=', Finance Department, GOVT. OF WB';
		  
		}
		
		


$chairman_agreement = "Certified that all the information furnished herewith have duely been verified with 
documents/Govt. Orders and found correct with the manual calculation on ROPA 2019.";
$eo_signature = "EO/FO’s Signature with date";
$eo_signature_upper = "_____________________________________________";
$incumbent_signature = "Signature of the incumbent with date";
$incumbent_signature_upper = "____________________________________________";
//$incumbent_signature_name = $emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'];
$incumbent_signature_name = '('.' '.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].' '.')';
$chairman_signature = "Signature of the BDO with date";
$chairman_signature_upper = "________________________________________________________________";	

//------------------------------------------------------------------------------------------------------	
define("_MPDF_TEMP_PATH", '../../../../locker/temp/');
include ('../../../../includes/third-party/mpdf/mpdf.php');


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
function code_master($dateval)
{
	$db=new database();
	 $arr = $db->fetch_table("SELECT code, description, code_master_id_pk
																		FROM prd_dise_code_master WHERE code ='".$dateval."' AND length(code)=3
																		");
	return $arr[0]['description'];																	
																		
}
function fun_payband($val){
	$db = new database();
	$data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
	return $data[0]['payband_name'];
}
function fun_grade_pay($val){
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
	return $dist_data2[0]['grade_amount'];
}	
function fun_payscale($val){
	$db = new database();
	$data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
	return $data[0]['payscale_range'];
}

function fun_ex_grade_pay($val){
	$db = new database();
	$data=$db->fetch_table("SELECT grade_pay FROM prd_emp_basic_pay_details_before_2019 
							WHERE emp_id_fk='".$val."' ORDER BY pay_dtls_id_pk DESC");
	return $data[0]['grade_pay'];
}

function fun_ex_payband($grade_pay,$emp_id){
	$db = new database();
	$get_payband_id=$db->fetch_table("SELECT payband_id_fk FROM prd_dise_gradepay_master 
							WHERE grade_amount='".$grade_pay."'"); 
	
	$get_payScale=$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master 
									WHERE payband_id_fk='".$get_payband_id[0]['payband_id_fk']."'");
									
	$get_payBand=$db->fetch_table("SELECT payband_name FROM prd_payband_master 
									WHERE payband_id_pk='".$get_payband_id[0]['payband_id_fk']."'");
									
	return $get_payBand[0]['payband_name'].' ('.$get_payScale[0]['payscale_range'].')';
}

function fun_ex_pay_pabBand($val){
	$db = new database();
	$data=$db->fetch_table("SELECT pay_band FROM prd_emp_basic_pay_details_before_2019 
							WHERE emp_id_fk='".$val."' ORDER BY pay_dtls_id_pk DESC");
	return $data[0]['pay_band'];
}	

function fun_level_pay($val){
	$db = new database();
	$data=$db->fetch_table("SELECT level FROM prd_dise_gradepay_master 
							WHERE grade_amount='".$val."'");
	$pay_level=$data[0]['level'];
	
	$select_min_max=$db->fetch_table("SELECT MIN(level".$pay_level.") as min_pay, MAX(level".$pay_level.") as max_pay
									FROM ropa_2019");
	
	//return $pay_level.' ('.$select_min_max[0]['min_pay'].'-'.$select_min_max[0]['max_pay'].')';
	return $pay_level;
}	

function fun_revised_basic($val,$level_on_effective_date){
	$db = new database();
	/*$data=$db->fetch_table("SELECT basic_pay FROM ropa_2019_emp_pay_scale_master 
							WHERE emp_id_fk='".$val."'");*/
	
	$get_ropa19_cause=$db->fetch_table("SELECT cause FROM ropa_2019_emp_pay_scale_master 
											WHERE emp_id_fk='".$val."'");
	$get_emp_joining_date=$db->fetch_table("select emp_first_join_date,zp_emp_type from prd_employee_master where emp_id_pk ='".$val."'");
	$new_basic_pay_on_effective_date=round((fun_ex_grade_pay($val)+fun_ex_pay_pabBand($val))*2.57);
	
	$get_ropa_cause=$db->fetch_table("SELECT cause FROM ropa_2019_emp_pay_scale_master
													WHERE emp_id_fk='".$val."'");
													
													
													
		$select_saved_pay_data=$db->fetch_table("SELECT * FROM prd_emp_basic_pay_details_before_2019 
  										WHERE emp_id_fk='".$val."' AND year=(SELECT MIN(year) FROM
										prd_emp_basic_pay_details_before_2019 WHERE emp_id_fk='".$val."')");
										
		$pay_band_on_effective_date=$select_saved_pay_data[0]['pay_band']; 
		 $grade_pay_on_effective_date=$select_saved_pay_data[0]['grade_pay'];												
	
	if($get_emp_joining_date[0]['zp_emp_type']=='' || $get_emp_joining_date[0]['zp_emp_type']=='0' || $get_emp_joining_date[0]['zp_emp_type']=='367')
		{
		 $ropa_table='ropa_2019'; 
		  $ropa_mem='10485/PN/O/III/2E-34/2019, Dated: 24.12.2019';
		  $dep='Finance Department, GOVT. OF WB';
		 
		}
		else 
		{
		 $ropa_table='ropa_2019_ll';
		 $ropa_mem='5562-F, Dated: 25.09.2019';
		 $dep='Panchayats & Rural Development Department, GOVT. OF WB';
		  
		}
	$lev="level";
	if(strtotime($get_emp_joining_date[0]['emp_first_join_date']) >= strtotime('01-01-2016') &&  $get_ropa_cause[0]['cause']=='2')
		{
			//$arr2=$db->fetch_table("select ".$level_on_effective_date." from ropa_2019 LIMIT 1");
			
			
			if($pay_band_on_effective_date>='7910' && $level_on_effective_date=="9")
								{
				$arr2=$db->fetch_table("select ".$lev."".$level_on_effective_date." 
					from ".$ropa_table." where ".$lev."".$level_on_effective_date."='29800' ");
									
								}
								else
								{
			
			$arr2=$db->fetch_table("select ".$lev."".$level_on_effective_date." 
									from ".$ropa_table." order by ".$lev."".$level_on_effective_date." ASC LIMIT 1");
								}
									
		}
	else
	{
		$arr2=$db->fetch_table("select level".$level_on_effective_date." from ".$ropa_table." where level".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."' ORDER BY level".$level_on_effective_date." ASC LIMIT 1");
	}
	$ropa_2019_basic_pay_on_effective_date = $arr2[0]['level'.$level_on_effective_date];
	
	//return $data[0]['basic_pay'];
	return $ropa_2019_basic_pay_on_effective_date;
} 

function fun_level_range($val){
	$db = new database();
	$select_min_max=$db->fetch_table("SELECT MIN(".$val.") as min_pay, MAX(".$val.") as max_pay
									FROM ropa_2019");
	
	return ' ('.$select_min_max[0]['min_pay'].'-'.$select_min_max[0]['max_pay'].')';
}

function fun_mul_ftmt_factor($val){
	$new_basic_pay=((fun_ex_grade_pay($val)+fun_ex_pay_pabBand($val))*2.57);
	$new_basic_pay_ex=explode(".",$new_basic_pay);
	if($new_basic_pay_ex[1]>0)
	{
		$new_basic_pay=$new_basic_pay_ex[0]+1;
	}
	
	return $new_basic_pay;
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
	
	
	if($logged_user=='BDO')
    {
    $name=$_SESSION['location']['block_name'].' BLOCK'; 
    $admin_name="Signature of the Block Development Officer";
	 
    }
    else if ($logged_user=='EO')
    {
    $name=fun_ps($_SESSION['location']['ps_id']).' PANCHAYAT SAMITI'; 
    $admin_name="Signature of the Executive Officer";
    }
	else if ($logged_user=='FC&CAO')
    {
    $name=fun_dist($_SESSION['location']['district_id']).' ZILLA PARISHAD'; 
  // $admin_name="Additional Executive Officer[AEO]";
	$admin_name="Signature of the Financial Controller & Chief Accounts Officer";
    }
	
	if($logged_user=='BDO')
	    {	$designation=fun_desig($emp_data[0]['emp_desig']);
		$emp_group=fun_desig1($emp_data[0]['emp_desig']);
		}
		else if($logged_user=='EO')
		{
			$designation=fun_desig($emp_data[0]['emp_desig']);
			$emp_group=fun_desig1($emp_data[0]['emp_desig']);
		}
		else if($logged_user=='FC&CAO')
		{
			$designation=fun_desig1($emp_data[0]['emp_desig']);
			$emp_group=fun_desig1($emp_data[0]['emp_desig']);
		}


define("_MPDF_TEMP_PATH", '../../../locker/temp/');
include ('../../../includes/third-party/mpdf/mpdf.php');
$stylesheet='';
$mpdf=new mPDF();
$mpdf->WriteHTML($stylesheet,1);

//Header and footer
$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'{PAGENO}');
//PDF header
$mpdf->WriteHTML('
					
					
					<table>
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
							
								<div class="logo" style="text-align: center;"><img width="30" src="../../../themes/default/image/ashoka.jpg" /></div>
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $name .'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					');
					
					
					$mpdf->WriteHTML('
					<table>				
					
					<tr>
						<td class="he2" style="text-align: center;width: 90%;">

								<p><font color="#79A772">Initial Pay fixation and subsequent pay revision order as per Memo No. '.$ropa_mem.' '.$dep.' </font></p>	
								<hr />
							</td>
							
						
						</tr>
					</table>',2);
					

					
	$mpdf->WriteHTML('
					<table>				
					
					<tr>
						<td class="he2" style="text-align: center;width: 90%;">

									
								<hr />
							</td>
							
						
						</tr>
					</table>',2);
					
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PERSONAL DETAILS</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">',2);
																	
					$mpdf->WriteHTML('<tr><td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">
					
					<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Name :</td>
					<td align="left">'.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'</td>
				
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic;">Employee ID :</td>
					<td>'.$Employee_ID.'</td>
					
				</tr> 
				<tr>
					<td  class="col2" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Designation :</td>
					<td>'.$designation.'</td>
				
					<td class="col2" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic;">Employee Group :</td>
					<td>'.fun_common($emp_data[0]['emp_group'],$code_data).'</td>
				</tr>
				</table>  
',2);

						$emp_id_fk=$cryptoGraph->decode($_GET['id'],4);
						$ropa_2019_effective_year= $_GET['emp_first_join_year'];
						
						$select_saved_pay_data=$db->fetch_table("SELECT * FROM prd_emp_basic_pay_details_before_2019 
														WHERE emp_id_fk='".$emp_id_fk."' AND year=(SELECT MIN(year) FROM
														prd_emp_basic_pay_details_before_2019 WHERE emp_id_fk='".$emp_id_fk."')");
														
						$get_ropa19_cause=$db->fetch_table("SELECT cause FROM ropa_2019_emp_pay_scale_master 
											WHERE emp_id_fk='".$emp_id_fk."'");
						
						$pay_band_on_effective_date=$select_saved_pay_data[0]['pay_band']; 
						$grade_pay_on_effective_date=$select_saved_pay_data[0]['grade_pay'];
						
						$basic_pay_on_effective_date = $pay_band_on_effective_date + $grade_pay_on_effective_date;
						
						$percentage_basic_pay_2016=$db->fetch_table("select fitment_factor from prd_admin_paychange where flag='TRUE' and ropa_year='2019'");
						$percentage_of_basic = $percentage_basic_pay_2016[0]['fitment_factor'];
						$new_basic_pay_on_effective_date = round($basic_pay_on_effective_date * $percentage_of_basic) ; //***********************************  round of
						$arr1=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");

						$level_on_effective_date = 'level'.$arr1[0]['level'];
						
						//$arr2=$db->fetch_table("select ".$level_on_effective_date." from ropa_2019 where ".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."' LIMIT 1");
						
						
						
						
						
						
						
						
						
						
						$get_emp_joining_date=$db->fetch_table("select emp_first_join_date,zp_emp_type from prd_employee_master where emp_id_pk ='".$emp_id_fk."'");
		$get_ropa_cause=$db->fetch_table("SELECT cause FROM ropa_2019_emp_pay_scale_master
													WHERE emp_id_fk='".$emp_id_fk."'");
		
					if($get_emp_joining_date[0]['zp_emp_type']=='' || $get_emp_joining_date[0]['zp_emp_type']=='0' || $get_emp_joining_date[0]['zp_emp_type']=='367')
					{
					$ropa_table='ropa_2019'; 
					
					}
					else 
					{
					$ropa_table='ropa_2019_ll';
					
					}
						//if(strtotime($get_emp_joining_date[0]['emp_first_join_date']) >= strtotime('01-01-2016'))
						if(strtotime($get_emp_joining_date[0]['emp_first_join_date']) >= strtotime('01-01-2016') &&  $get_ropa_cause[0]['cause']=='2')
		{
			//$arr2=$db->fetch_table("select ".$level_on_effective_date." from ropa_2019 LIMIT 1");
			
			
			if($pay_band_on_effective_date>='7910' && $level_on_effective_date=="level9")
								{
								$arr2=$db->fetch_table("select ".$level_on_effective_date." 
					from ".$ropa_table." where ".$level_on_effective_date."='29800' ");
									
								}
								else
								{
			
			$arr2=$db->fetch_table("select ".$level_on_effective_date." 
									from ".$ropa_table." order by ".$level_on_effective_date." ASC LIMIT 1");
								}
									
		}
		////////////////////////////
		else
		{
			
			
			$arr2=$db->fetch_table("select ".$level_on_effective_date." from ".$ropa_table." where ".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."' order by ".$level_on_effective_date." ASC LIMIT 1");
		}
				

						$ropa_2019_basic_pay_on_effective_date = $arr2[0][$level_on_effective_date];
						
						
						$basic_last_2016=$ropa_2019_basic_pay_on_effective_date;
						$level_last_2016=$level_on_effective_date;
						
						
						$get_ropa_master=$db->fetch_table("SELECT status,ropa_2019_effective_date FROM ropa_2019_emp_pay_scale_master
																	WHERE emp_id_fk='$emp_id_fk'");
																	
						$ropa_2019_effective_date_n = $get_ropa_master[0]['ropa_2019_effective_date'];	
						
						
						$get_emp_pay_scale_details=$db->fetch_table("SELECT status, ropa_2019_effective_date, cause FROM ropa_2019_emp_pay_scale_master 
											WHERE emp_id_fk='$emp_id_fk'");
						$ropa_2019_effective_date = $get_emp_pay_scale_details[0]['ropa_2019_effective_date'];
						$cause = $get_emp_pay_scale_details[0]['cause'];
						$arr2 = $db->fetch_table("select rules from ropa_2019_scenario WHERE id_pk = '".$cause."'");
						$ropa_2019_effective_reason = $arr2[0]['rules'];
						
						$emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
					    $emp_grade_pay=$emp_data[0]['emp_grade_pay'];									
						$arr10 = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master  where	grade_code='$emp_grade_pay'");
						$emp_grade_pay_amt=$arr10[0]['grade_amount'];

$mpdf->WriteHTML('<div class="segment">
				<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PAY DETAILS AS ON THE DATE OF OPTION </h4>
				<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">',2);
$mpdf->WriteHTML('
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Date of Option :</td>
					<td align="left">'.$get_ropa_master[0]['ropa_2019_effective_date'].'</td>
					<td colspan="2"></td>
					
				</tr>	
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Existing Pay Band :</td>
					<td align="left">'.fun_ex_payband(fun_ex_grade_pay($emp_id_fk),$emp_id_fk).'</td>
				
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic;">Existing Grade Pay :</td>
					<td>'.fun_ex_grade_pay($emp_id_fk).'</td>
					
				</tr> 
				<tr>
					<td  class="col2" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Existing Pay in Pay Band :</td>
					<td>'.fun_ex_pay_pabBand($emp_id_fk).'</td>
				</tr>
				
				<tr>
					<td colspan="2"  class="col3" style="color: #1F6377;font-weight: bold;width: 200px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Existing Basic Pay (Pay in Pay Band + Grade Pay) :</td>
					<td colspan="1" align="left">'.(fun_ex_grade_pay($emp_id_fk)+fun_ex_pay_pabBand($emp_id_fk)).'</td>
				</tr>
				
				
				</table>  
',2);
					
		

			  
//ROPA 2019 BASIC PAY DETAILS
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">FIXATION OF INITIAL PAY</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">',2);
						
																	
					$mpdf->WriteHTML('<tr><td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">
					
				<tr>
					<td colspan="3" class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Date of initial pay fixation :</td>
					<td>'.$ropa_2019_effective_date.'</td>	
				</tr> 
				<tr>
					<td colspan="3"  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Amount arrived at by multiplying existing basic pay above by 2.57 :</td>
					<td>'.((fun_ex_grade_pay($emp_id_fk)+fun_ex_pay_pabBand($emp_id_fk))*2.57).'</td>

				</tr> 
				
				
				<tr>
					<td colspan="3"  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Rounded off to next rupee :</td>
					<td>'.fun_mul_ftmt_factor($emp_id_fk).'</td>

				</tr>
				<tr>
					<td colspan="3"  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Applicable level in the Pay Matrix corresponding to the Pay Band and Grade :</td>
					<td> LEVEL '.fun_level_pay(fun_ex_grade_pay($emp_id_fk)).'</td>

				</tr>
				<tr>
					<td colspan="3"  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Revised basic pay as on the date of initial pay fixation :</td>
					<td>'.fun_revised_basic($emp_id_fk,fun_level_pay(fun_ex_grade_pay($emp_id_fk))).'</td>

				</tr>
				</table>',2);
				
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">SUBSEQUENT FIXATION OF PAY UP TO 01-01-2020</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">',2);
	  $mpdf->WriteHTML('<tr>
							<td class="col1" style="color: #1F6377;font-weight: bold;font-family: Arial Narrow;width: 100px;">Date </td>
							<td class="col1" style="color: #1F6377;font-weight: bold;font-family: Arial Narrow;width: 100px;">Events of Fixation </td>
							<td class="col1" style="color: #1F6377;font-weight: bold;font-family: Arial Narrow;width: 100px;">Pay Level in the pay matrix</td>
							<td class="col1" style="color: #1F6377;font-weight: bold;font-family: Arial Narrow;width: 100px;">Revised Basic Pay</td>
						</tr>',2);

		
					
		$emp_id_fk=$cryptoGraph->decode($_GET['id'],4);											
															
		$arr3=$db->fetch_table("select * from prd_emp_increment_details_before_2019 where emp_id_fk='".$emp_id_fk."' ORDER BY increment_dtls_id_pk DESC");
		
		$count = 0;
		foreach($arr3 as $data_of_effective_year) 
		{
			$inc_dtls='';	
			$promotion_increment_dtls=$data_of_effective_year['increment_dtls'];
			$promotion_increment_type=$data_of_effective_year['increment_type'];
			$promotion_increment_date=$data_of_effective_year['effective_date'];
			
			if ($count==0)
			{
				$new_level = $level_last_2016;
				$new_basic_pay = $basic_last_2016;
			}
			else
			{
				$new_level = $new_level;
				$new_basic_pay = $new_basic_pay;	
			}
			if($promotion_increment_dtls=='1' && $promotion_increment_type == '1')
			{
				/*
				$arr4=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr4[0][$new_level]; 
				$new_level = $new_level;*/
				
				
				$arr4=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."'  order by ".$new_level." ASC LIMIT 1");
				
				$new_basic_pay = $arr4[0][$new_level]; 
				$new_level = $new_level;
			}
		
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '1')
			{

				/*$arr5=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr5[0][$new_level]; 
				$new_level = $new_level;*/
				
				$arr5=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr5[0][$new_level]; 
				$new_level = $new_level;
				
				
				
				/*$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];*/
				
				/////////////// 07_11_2019 for current grade pay level
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				 $new_level = 'level'.$arr6[0]['level'];
				
				
				
				/*$arr7=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." LIMIT 1");
				$new_basic_pay = $arr7[0][$new_level]; 
				$new_level = $new_level;*/
				
				$arr7=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">='".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr7[0][$new_level]; 
				$new_level = $new_level;

					
			}
		
		
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '2')
			{
				/*$arr8=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr8[0][$new_level]; 
				$new_level = $new_level;
				
				$arr9=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr9[0][$new_level]; 
				$new_level = $new_level;
				
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];
				
				

				$arr10=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' ORDER BY ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr10[0][$new_level]; 
				$new_level = $new_level;*/
				
				
				
				
				// for single increment
				$arr8=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr8[0][$new_level]; 
				$new_level = $new_level;
				
				// for double increment
				$arr9=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr9[0][$new_level]; 
				$new_level = $new_level;
				
				/////////////// 07_11_2019  for level of current grade pay (promotion)
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];
				
				
				/////////////// 07_11_2019  for level change (promotion)

				$arr10=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">='".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr10[0][$new_level]; 
				$new_level = $new_level;
				
				

			}
			
			 $promotion_increment=explode("-",$promotion_increment_date);
	  $promotion_increment_year=$promotion_increment[2];
	  if($promotion_increment_year==2015)
	  {
		  $promotion_increment_year=2016;
	  }
	  $new_promotion_increment_date=$promotion_increment[0].'-'.$promotion_increment[1].'-'.$promotion_increment_year;
						
				if($data_of_effective_year['increment_dtls']==1)
				{
					$inc_type = 'Annual Increment';
				}
				else if($data_of_effective_year['increment_dtls']==2)
				{
					$inc_type = 'Promotion with ';
				}
				else if($data_of_effective_year['increment_dtls']==3)
				{
					$inc_type = 'CAS with ';
				}
				
				if($data_of_effective_year['increment_dtls']!=1 && $data_of_effective_year['increment_type']==1)
				{
					$inc_dtls = 'with Single Increment';
				}
				else if($data_of_effective_year['increment_dtls']!=1 && $data_of_effective_year['increment_type']==2)
				{
					$inc_dtls = 'with Double Increment';
				}
					//echo $new_level; die;	
			$mpdf->WriteHTML('
				<tr>
					<td class="col3">'.$new_promotion_increment_date.'</td>
					<td class="col3">'.$inc_type.' '.$inc_dtls.'</td>
					<td class="col3"> LEVEL '.substr($new_level,5,6).'</td>
					<td class="col3">'.$new_basic_pay.'</td>
				</tr> 
',2);
				
				
				$count++;
				
				}
$mpdf->WriteHTML('</table>',2);
				
				if($new_basic_pay)
				{
					$nb_pay =  $new_basic_pay;
				}
				else
				{
					$nb_pay = $basic_last_2016;
				}
				if($new_level)
				{
					$nb_lvl = $new_level;
				}
				else
				{
					$nb_lvl = $level_last_2016;
				}
			
			
			$mpdf->WriteHTML('
			<hr />
			<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">
			<tr>
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Revised Basic Pay (as on 01-01-2020) :</td>
					<td>'.$nb_pay.'</td>
					<td colspan="2">
					</td>
				</tr>
				<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Date of Next Increment after 01-01-2020 :</td>
					<td>01-07-2020</td>
					<td colspan="2">
					</td>
				</table>
						
						
						
						

						<table border="0" width="100%" style="font-size: 11px;vertical-align: top; margin-top: 10%;">
						
						<tr>
						<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic;" colspan="6"></td>
								
						<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic; text-align: right;" colspan="6">'.$chairman_signature_upper.'<br/> '.$admin_name.' </td>
						
					</tr>
				</table>

				',2);


@pg_close($con);

$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','D');
?>




		
			
	
