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
$emp_id_pk = $cryptoGraph->decode($_GET['id'],4);


$emp_data=$db->fetch_table("select mmd.code, mmd.description, mem.* from prd_employee_master as mem,
							prd_dise_code_master as mmd where mem.emp_id_pk='".$emp_id_pk."' 
							and CAST(mmd.code as integer) = mem.emp_desig");


$Employee_ID=$emp_data[0]['emp_id_const']; 

$chairman_agreement = "Certified that all the information furnished herewith have duely been verified with 
documents/Govt. Orders and found correct with the manual calculation on ROPA 2019.";
$eo_signature = "EO/FO’s Signature with date";
$eo_signature_upper = "_________________________________";
$incumbent_signature = "Signature of the incumbent with date";
$incumbent_signature_upper = "____________________________________________";
//$incumbent_signature_name = $emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'];
$incumbent_signature_name = '('.' '.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].' '.')';
$chairman_signature = "Signature of the Chairman with date";
$chairman_signature_upper = "__________________________________________";	
	    		
			    		

					
					
define("_MPDF_TEMP_PATH", '../../../locker/temp/');
include ('../../../includes/third-party/mpdf/mpdf.php');
$stylesheet='';
$mpdf=new mPDF();
$mpdf->WriteHTML($stylesheet,1);

//Header and footer
$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
//PDF header
$mpdf->WriteHTML('
					
					
					<table>
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
							
								<div class="logo" style="text-align: center;"><img width="30" src="../../../themes/default/image/ashoka.jpg" /></div>
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $_SESSION['location']['ps_name'] .'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
						</tr>
					</table>
					
					');
					

//ROPA 2019 BASIC PAY DETAILS					
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">ROPA 2019 BASIC PAY DETAILS</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: top;">',2);
						$emp_id_fk=$cryptoGraph->decode($_GET['id'],4);
						//$ropa_2019_effective_year= $_GET['emp_first_join_year'];
						
						$select_saved_pay_data=$db->fetch_table("SELECT * FROM prd_emp_basic_pay_details_before_2019 
														WHERE emp_id_fk='".$emp_id_fk."' AND year=(SELECT MIN(year) FROM
														prd_emp_basic_pay_details_before_2019 WHERE emp_id_fk='".$emp_id_fk."')");
						
						$pay_band_on_effective_date=$select_saved_pay_data[0]['pay_band']; 
						$grade_pay_on_effective_date=$select_saved_pay_data[0]['grade_pay'];
						
						$basic_pay_on_effective_date = $pay_band_on_effective_date + $grade_pay_on_effective_date;
						
						$percentage_basic_pay_2016=$db->fetch_table("select fitment_factor from prd_admin_paychange where flag='TRUE' and ropa_year='2019'");
						$percentage_of_basic = $percentage_basic_pay_2016[0]['fitment_factor'];
						$new_basic_pay_on_effective_date = round($basic_pay_on_effective_date * $percentage_of_basic) ; //***********************************  round of
					
						$arr1=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
						$level_on_effective_date = 'level'.$arr1[0]['level'];
						
						$arr2=$db->fetch_table("select ".$level_on_effective_date." from ropa_2019 where ".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."' LIMIT 1");
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
						$arr10 = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master where grade_code='$emp_grade_pay'");
						$emp_grade_pay_amt=$arr10[0]['grade_amount'];
																	
					$mpdf->WriteHTML('<tr><td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">
				
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Employee Name :</td>
					<td>'.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'</td>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Designation :</td>
					<td>'.$emp_data[0]['description'].'</td>
				
				</tr>
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Employee ID :</td>
					<td>'.$emp_data[0]['emp_id_const'].'</td>
				</tr>
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Pay Fixation Date :</td>
					<td>'.$ropa_2019_effective_date.'</td>
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Reason :</td>
					<td colspan="2">'.$ropa_2019_effective_reason.'</td>
					
				</tr> 
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">Pay In Pay Band :</td>
					<td>'.$emp_pay_in_payband.'</td>
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Grade Pay :</td>
					<td>'.$emp_grade_pay_amt.'</td>
					<td></td>
				</tr> 
				
				
				<tr>
					<td  class="col3"> </td>
					<td> </td>
				
					<td class="col3">  </td>
					<td> </td>
					<td> </td>
				</tr> 
					<br/>
					
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">As on '.$ropa_2019_effective_date_n.' :</td>
				
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Basic Pay :</td>
					<td>'.$basic_last_2016.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Level :</td>
					<td>'.$level_last_2016.'</td>
				</tr> 
',2);
					
		$emp_id_fk=$cryptoGraph->decode($_GET['id'],4);											
																	
		$arr3=$db->fetch_table("select * from prd_emp_increment_details_before_2019 where emp_id_fk='".$emp_id_fk."' ORDER BY increment_dtls_id_pk DESC");
		
		$count = 0;
		foreach($arr3 as $data_of_effective_year) 
		{	
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
				$arr4=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				
				$new_basic_pay = $arr4[0][$new_level]; 
				$new_level = $new_level;
			}
		
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '1')
			{

				$arr5=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				$new_basic_pay = $arr5[0][$new_level]; 
				$new_level = $new_level;
				
				
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];
				
				
				
				$arr7=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				$new_basic_pay = $arr7[0][$new_level]; 
				$new_level = $new_level;

					
			}
		
		
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '2')
			{
				$arr8=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				$new_basic_pay = $arr8[0][$new_level]; 
				$new_level = $new_level;
				
				$arr9=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				$new_basic_pay = $arr9[0][$new_level]; 
				$new_level = $new_level;
				
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];
				
				

				$arr10=$db->fetch_table("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
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
					$inc_dtls = 'Single Increment';
				}
				else if($data_of_effective_year['increment_dtls']!=1 && $data_of_effective_year['increment_type']==2)
				{
					$inc_dtls = 'Double Increment';
				}
						
			$mpdf->WriteHTML('<tr><td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">As on '.$new_promotion_increment_date.' After '.$inc_type.''.$inc_dtls.' :</td>
				
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Basic Pay :</td>
					<td>'.$new_basic_pay.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Level :</td>
					<td>'.$new_level.'</td>
				</tr> 
',2);
				
				
				$count++;
				
				}
				
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
			
			
			$mpdf->WriteHTML('<tr><td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">
				<tr>
					<td  class="col3" style="color: #1F6377;font-weight: bold;width: 300px;font-family: "Arial Narrow";font-style: italic; font-weight: bold; ">As on 01-01-2020  :</td>
				
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Basic Pay :</td>
					<td>'.$nb_pay.'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 100px;font-family: "Arial Narrow";font-style: italic;">Level :</td>
					<td>'.$nb_lvl.'</td>
				</table>
						
						<hr />
						<!--<table border="0" width="100%" style="font-size: 11px;vertical-align: top; margin-top: 20%;">
						
						<tr>
					

						<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic; text-align: right;" colspan="5">'.$incumbent_signature_upper.'<br/> '.$incumbent_signature.' <br/>'.$incumbent_signature_name.'
						</td>
						</tr>
						</table>-->
						
						<!--<table border="0" width="100%" style="font-size: 11px;vertical-align: top; margin-top: 5%;">
							<tr>
								<td colspan="8" ></td>
							</tr>
							<tr>
								<td colspan="8"></td>
							</tr>
							<tr>
								<td colspan="8"></td>
							</tr>
							
							<tr>
								<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic;">'.$chairman_agreement.'</td>
							</tr>
							
						</table>-->

						<!--<table border="0" width="100%" style="font-size: 11px;vertical-align: top; margin-top: 20%;">
						
						<tr>
						<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic;" colspan="6">'.$eo_signature_upper.'<br/> '.$eo_signature.' </td>
								
						<td style="font-size:14.5px; color: #1F6377;font-weight: bold; font-style: italic; text-align: right;" colspan="6">'.$chairman_signature_upper.'<br/> '.$chairman_signature.' </td>
						
					</tr>
				</table>-->

				',2);
if($query[0]['ps_status']=='1')
{
	$mpdf->WriteHTML('<p style="color:#8a6d3b;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Waiting For Approval.</p>');	
}
else if($query[0]['ps_status']=='2')
{
	$mpdf->WriteHTML('<p style="color: #3C763D;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Approved.</p>');		
}
else if($query[0]['ps_status']=='3')
{
	$mpdf->WriteHTML('<p style="color:#A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Profile Rejected [ Reject Reason : '.$reject_reason.']</p>');		
}
else if($query[0]['ps_status']=='7')
{
	$mpdf->WriteHTML('<p style="color:#a94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Waiting For Unlock</p>');		
}
else if($query[0]['ps_status']=='8')
{
	$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center"> Profile Unlocked For Edit</p>');		
}
else if($query[0]['ps_status']=='0' )
{
	$mpdf->WriteHTML('<p style="color: #A94442;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Profile Not Sent</p>');		
}
else if(!$query)
{
	$mpdf->WriteHTML('<p style="color: red;font-weight: bold;font-family: "Arial Narrow";font-style: italic;text-align:center">Profile Not Submitted</p>');
}

				
@pg_close($con);

//$mpdf->Output('PAY FIXATION.pdf','D');
$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','D');								
?>
					
					





		
			
	
