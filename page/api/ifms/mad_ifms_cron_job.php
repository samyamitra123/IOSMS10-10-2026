<?php
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
include('../../../includes/library/ifms_functions.php');

$crypto = new cryptography();
$db=new database();	

///// Pervious Month Year ////////
$k=strtotime("first day of last month");
$prev_monthyr = date("Ym",$k); 
/////////////////////////////

$current_monthyr=date('Ym');

//die('1234');
$code_data = $db->fetch_table("SELECT code, description
							FROM mad_dise_code_master;");

function countdim($array)
{
    if (is_array(reset($array)))
    {
        $return = countdim(reset($array)) + 1;
    }

    else
    {
        $return = 1;
    }

    return $return;
}

function date_format_change($dateval)
{	
	$date=substr($dateval,0,10);
	if($date=='')
	{
		return '0001-01-01';
	}
	if($date=='01/01/1900')
	{
		return '0001-01-01';
	}
	$datearr=explode('/',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob;
}

function file_name_list($arr,$checker)
{
	$required_arr=array();
	
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],28,1)==$checker)
		{
			array_push($required_arr,substr($arr[$k],0,28));
		}
	}
	return $required_arr;
}

function file_name($arr,$checker,$file_name)
{
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],28,1)==$checker && substr($arr[$k],0,28)==$file_name)
		{
			$req_file_name=$arr[$k];
		}
	}
	return $req_file_name;
}


function file_name_list_payment($arr,$checker)	//Modified new
{
	$required_arr=array();
	
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],31,1)==$checker)
		//if(substr($arr[$k],30,1)==$checker)
		{
			array_push($required_arr,substr($arr[$k],0,31));
			//array_push($required_arr,substr($arr[$k],0,30));
		}
	}
	return $required_arr;
}	

function file_name_payment($arr,$checker,$file_name)	//Modified new
{
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],31,1)==$checker && substr($arr[$k],0,31)==$file_name)
		//if(substr($arr[$k],30,1)==$checker && substr($arr[$k],0,30)==$file_name)
		{
			$req_file_name=$arr[$k];
		}
	}
	return $req_file_name;
}

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

/*$ack_success_bill_id_arr=array();
$ack_success_sftp_id_arr=array();

$non_ack_success_bill_id_arr=array();
$non_ack_success_sftp_id_arr=array();

$payment_success_bill_id_arr=array();
$payment_success_sftp_id_arr=array();

$wrong_data_success_bill_id_arr=array();
$wrong_data_success_sftp_id_arr=array();*/

//$bill_sl_no=$crypto->decode($_GET['bill_sl_no'],4); 
$bill_month=$crypto->decode($_POST['bill_report_month'],4);
$bill_year=$crypto->decode($_POST['bill_report_year'],4);
//$municipality_id_fk=substr($_SESSION['user_info']['stake_user'],0,7);

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');		//For Production Server
//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib0.3.0');

include('Net/SFTP.php');

$_SESSION['ifms_msg']='';
///////////////////////////////////////////////////////////////////////  GP START ///////////////////////////////////////////////////////////////////////
if($_POST['user']=='mun')
{
	$sftp_gp=sftp_login($config['sftp_ip'],$config['sftp_user_name'],$config['sftp_user_password']);
	if($sftp_gp!=0)
	{
		
		$bill_details_fetch_gp=$db->fetch_table(" SELECT 
													bill.municipality_bill_pk,
													bill.municipality_id_fk,
													sftp.sftp_benf_id_pk,
													sftp.sftp_benf_file_name,
													sftp.sftp_benf_response_status, 
													bill.salary_monthyear,
													bill.requisition_type
												FROM mad_municipality_bill_details bill
												INNER JOIN mad_sftp_benf_upload_response sftp
												ON bill.municipality_bill_pk=sftp.bill_id_fk
												WHERE bill.salary_monthyear in('".$bill_year.$bill_month."') AND bill.status='1' AND sftp.active_status='1' 
												AND sftp.sftp_benf_sending_status='4' 
												AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
		
		if(count($bill_details_fetch_gp)!='0')
		{
		
			//$remote_directory_002 = '/apps/ePaymentFiles/gen010/ePayment_Files_002/'; // changes needed as per department //
			$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen010/ePayment_Files_002/';
			//$remote_directory_002 = '/iosmsmun/ePayment_Files_002/';
			//$remote_directory_003 = '/apps/ePaymentFiles/gen010/ePayment_Files_003/';
			$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen010/ePayment_Files_003/';
			//$remote_directory_003 = '/iosmsmun/ePayment_Files_003/';
			//$remote_directory_005 = '/apps/ePaymentFiles/gen010/ePayment_Files_005/';
			$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen010/ePayment_Files_005/';
			//$remote_directory_005 = '/iosmsmun/ePayment_Files_005/';
			
			//echo $local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsmun/ePayment_Files_002/';
			$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsmun/ePayment_Files_002/';			//For Production Server
			//$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/mad/readwrite/xml_file/ifmsmun/ePayment_Files_002/';
			$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsmun/ePayment_Files_003/';			//For Production Server
			//$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/mad/readwrite/xml_file/ifmsmun/ePayment_Files_003/';
			$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsmun/ePayment_Files_005/';			//For Production Server
			//$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/mad/readwrite/xml_file/ifmsmun/ePayment_Files_005/';
			
			//$sftp_gp->get($remote_directory_002.'ACKNPC0000470101607201807000010.xml', $local_directory_002.'ACKNPC0000470101607201807000010.xml');
			//echo 'OK';
			
			
			
			$ack_file_name_list = $sftp_gp->nlist($remote_directory_002);
			/*echo "<br>//////002<br>";
			print_r($ack_file_name_list); //die;*/
			$non_ack_file_name_list = $sftp_gp->nlist($remote_directory_003);
			$payment_file_name_list =  $sftp_gp->nlist($remote_directory_005);
			$wrong_data_file_name_list = $sftp_gp->nlist($remote_directory_003);
			/*echo "<br>//////003<br>";
			print_r($non_ack_file_name_list);
			echo "<br>//////005<br>";
			print_r($payment_file_name_list); */
			
			
			//$sftp_gp->get($remote_directory_005.'010COC0000050102610201801000042_00012.xml', $local_directory_005.'010COC0000050102610201801000042_00012.xml');
			
			
			/*echo "<br>//////005<br>";
			echo "<pre>";
			print_r($payment_file_name_list); die;*/
			
			
			//$sftp_gp->get($remote_directory_002.'ACKNPC0000470100708201802000018.xml', $local_directory_002.'ACKNPC0000470100708201802000018.xml');
			//$sftp_gp->get($remote_directory_002.'ACKNPC0000470100708201802000019.xml', $local_directory_002.'ACKNPC0000470100708201802000019.xml');
			//$sftp_gp->get($remote_directory_002.'ACKNPC0000470100708201803000019.xml', $local_directory_002.'ACKNPC0000470100708201803000019.xml');
			//$sftp_gp->get($remote_directory_003.'NPC0000470100708201802000019D_534.xml', $local_directory_003.'NPC0000470100708201802000019D_534.xml');
			
			
			//die;
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_');
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D');
			
			
			$payment_file_arr=file_name_list_payment($payment_file_name_list,'_');	//Modified new
			
			for($i=0;$i<count($bill_details_fetch_gp);$i++)
			{
				/////////////////////////////////////MUNICIPALITY NAME & SALARY TYPE////////////////////////////////////
				$get_municipality_name_by_id=$db->fetch_table("SELECT municipality_name FROM mad_location_master_municipality WHERE 
																block_code='".$bill_details_fetch_gp[$i]['municipality_id_fk']."'");
				$mun_name_text=$get_municipality_name_by_id[0]['municipality_name'];
				$sal_type_text=fun_common($bill_details_fetch_gp[$i]['requisition_type'],$code_data);
				/////////////////////////////////////MUNICIPALITY NAME & SALARY TYPE////////////////////////////////////
				
				////////////////////////////////////////////////////////////////// BEFORE GETTING ACK FILE  /////////////////////////////////////////////////////////////
				
				if($bill_details_fetch_gp[$i]['sftp_benf_response_status']=='')
				{
					$filename_without_ext=substr($bill_details_fetch_gp[$i]['sftp_benf_file_name'],0,28);		//changed for TESTING on 14_06_2018
					//echo $filename_without_ext;
					////////////////////////////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					//echo "ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name'];
					if(in_array("ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name'],$ack_file_name_list))
					{
						$ack_file_name="ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name'];
						//////////////////////////////////////// DOWNLOADING ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						if($sftp_gp->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
						{
							$ack_arry=simplexml_load_file($local_directory_002.$ack_file_name);
							$ack_json  = json_encode($ack_arry);
							$ack_configData = json_decode($ack_json, true);
							
							$update_ack_response=$db->update("UPDATE mad_sftp_benf_upload_response
																SET ifms_reference_number='".$ack_configData['IFMS_REF_NO']."',
																sftp_benf_response_status='5',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
																
							$insert_track=$db->insert("INSERT INTO mad_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['municipality_bill_pk']."',
															5,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							
							/*if($update_ack_response && $insert_track)
							{
								echo "<p style='font-weight:bold;color:#008000;'>IFMS Reference No. Generated</p>";
							}
							else
							{
								echo "<p style='font-weight:bold;color:#FA8072;'>IFMS Reference No. Not Generated</p>";
							}*/
							if($update_ack_response && $insert_track)
							{
								$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#008000;'>IFMS Reference No. Generated of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
							}
							else
							{
								$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>IFMS Reference No. Not Generated of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
							}
						}
					}
					
					//////////////////////////////////////////////////////////// NON-ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					else if(in_array($filename_without_ext,$non_ack_file_arr))
					{
						$non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext);
						
						//////////////////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						if($sftp_gp->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
						{
							$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
							$non_ack_json  = json_encode($non_ack_arry);
							$non_ack_configData = json_decode($non_ack_json, true);
							
							$update_non_ack_response=$db->update("UPDATE mad_sftp_benf_upload_response
																	SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
																	sftp_benf_response_status='6',
																	sftp_benf_response_recieve_time='now()'
																	WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																	AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
																	
							$insert_track=$db->insert("INSERT INTO mad_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
															6,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							
							if($update_non_ack_response && $insert_track)
							{
								$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Format Error of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
							}
							else
							{
								$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Format Error of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
							}
						}
					}
					////////////////////////////////////////////////////////////////// WRONG DATA FILE CHECKING  ///////////////////////////////////////////////////////////		 
					
					else if(in_array($filename_without_ext,$wrong_data_file_arr))
					{
						$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext);
						if($sftp_gp->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
						{
							$wrdt_arry=simplexml_load_file($local_directory_003.$wrong_data_file_name);
							$wrdt_json  = json_encode($wrdt_arry);
							$wrdt_configData = json_decode($wrdt_json, true);
							$all_emp_id_arr=array();
							$emp_wrdt_failure_arr=array();
							
							foreach($wrdt_configData as $key=>$value)
							{
								
								if(countdim($value)==1)
								{
									array_push($all_emp_id_arr,"'".$value['ID']."'");
									
									$emp_wrdt_failure_arr[$value['ID']]=array(trim($value['IFMS_REF_NO']),trim($value['BENF_NAME']),trim($value['ACCOUNT_NO']),trim($value['IFSC_CODE']),trim($value['MOBILE_NO']),trim($value['AMOUNT']),trim($value['ORDER_NO']),trim($value['UNIQUE_ID']),trim($value['REASON']));
									break;	
								}
								else
								{
									foreach($value as $key2=>$value2)
									{
										array_push($all_emp_id_arr,"'".$value2['ID']."'");
										
										$emp_wrdt_failure_arr[$value2['ID']]=array(trim($value2['IFMS_REF_NO']),trim($value2['BENF_NAME']),trim($value2['ACCOUNT_NO']),trim($value2['IFSC_CODE']),trim($value2['MOBILE_NO']),trim($value2['AMOUNT']),trim($value2['ORDER_NO']),trim($value2['UNIQUE_ID']),trim($value2['REASON']));
									
									}
								}
							}
						//}
						
							$all_emp_id_str=implode(",",$all_emp_id_arr);
							$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM mad_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
							
							$cnt_w=0;$ins_sql='';$emp_id_wrdt_list='';
							for($j=0;$j<count($emp_id_fetch);$j++)
							{
								foreach($emp_wrdt_failure_arr as $key3=>$value3)
								{
									if($emp_id_fetch[$j]['emp_id_const']==$key3)
									{
										$ins_sql.="('".$bill_details_fetch_gp[$i]['municipality_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$j]['emp_id_pk']."','".$value3[0]."','".$value3[1]."','".$value3[2]."','".$value3[3]."','".$value3[4]."','".$value3[5]."','".$key3."','".$value3[6]."','".$value3[7]."','".$value3[8]."','','9','now();','".$_SERVER['REMOTE_ADDR']."','555','1')";
										
										$emp_id_wrdt_list.=$emp_id_fetch[$j]['emp_id_pk'];
										
										if($cnt_w!=count($emp_wrdt_failure_arr)-1)
										{
											$ins_sql=$ins_sql.",";
											$emp_id_wrdt_list=$emp_id_wrdt_list.",";
										}
										$cnt_w++;
									}
								}
							}
							
							//pg_query("BEGIN");
							
							$update_wrdt_response=$db->update("UPDATE mad_sftp_benf_upload_response
																SET sftp_benf_response_status='7',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
																
							
							$insert_track=$db->insert("INSERT INTO mad_ifms_transaction_tracking
																(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
																VALUES
																('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
																'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
																7,
																'now()',
																'".$_SERVER['REMOTE_ADDR']."')");
												
								
							//if($ins_sql!="")

							
							//if($update_wrdt_response && $ins_sql!="" && $insert_track)
							if($update_wrdt_response)
							{
							
								$insert_benf_failure_details=$db->insert("INSERT INTO mad_sftp_benf_failure_details
																			(
																			bill_id_fk,
																			sftp_benf_id_fk,
																			emp_id_fk,
																			ifms_ref_no,
																			benf_name,
																			account_no,
																			ifsc_code,
																			mobile_no,
																			amount,
																			id,
																			order_no,
																			unique_id,
																			reason,
																			payment_utr_no,
																			response_from,
																			entry_time,
																			entry_ip,
																			client_type,
																			active_status
																			)
																			VALUES 
																			".$ins_sql);
																			
																			
								if($insert_benf_failure_details)
								{
									//pg_query("COMMIT");
									$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Wrong Data Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
								}
								else
								{
									//pg_query("ROLLBACK");
									$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Wrong Data Not Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
								}	
							}
						}
					}
					else
					{
						$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>No Response From IFMS of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
					}
				}
				
				////////////////////////////////////////////////////////////////// AFTER GETTING ACK FILE  /////////////////////////////////////////////////////////////
				
				//for sftp (payment success data) update again also after getting success payment data start
				//else if($bill_details_fetch_gp[$i]['sftp_benf_response_status']=='5' || $bill_details_fetch_gp[$i]['sftp_benf_response_status']=='8')
				//for sftp (payment success data) update again also after getting success payment data end
				else if($bill_details_fetch_gp[$i]['sftp_benf_response_status']=='5')
				{
					$filename_without_ext=substr($bill_details_fetch_gp[$i]['sftp_benf_file_name'],0,28);		//changed for testing on 14_06_2018
					$cnt_s=1;$cnt_f=1;
					$all_emp_id_arr=array();
					$emp_pay_succes_arr=array();
					$emp_pay_failure_arr=array();
					$file_monthyear=$bill_details_fetch_gp[$i]['salary_monthyear']; 
					
					$party_code='010';	//Modified new
					//$party_code='10';	//Modified new
					$filename_without_ext_payment=$party_code.$filename_without_ext;	//Modified new
					////////////////////////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////////////////////
					
					//if(in_array($bill_details_fetch_gp[$i]['sftp_benf_file_name'],$payment_file_name_list))	
					if(in_array($filename_without_ext_payment,$payment_file_arr))			//Modified new
					{	
						//////////////////////////////////////// DOWNLOADING PAYMENT FILE AND DATA READING /////////////////////////////////////////////////
						$payment_file_name=file_name_payment($payment_file_name_list,'_',$filename_without_ext_payment);	//Modified new
						
						//if($sftp_gp->get($remote_directory_005.$bill_details_fetch_gp[$i]['sftp_benf_file_name'], $local_directory_005.$bill_details_fetch_gp[$i]['sftp_benf_file_name']))
						if($sftp_gp->get($remote_directory_005.$payment_file_name, $local_directory_005.$payment_file_name))	//Modified new
						{	
							//$payment_arry=simplexml_load_file($local_directory_005.$bill_details_fetch_gp[$i]['sftp_benf_file_name']);
							$payment_arry=simplexml_load_file($local_directory_005.$payment_file_name);		//Modified new
							$payment_json  = json_encode($payment_arry);
							//print_r($payment_json); die;
							$payment_configData = json_decode($payment_json, true);
							
							//echo "<pre>";
							//print_r($payment_configData);
							
							
							
							//foreach($payment_configData as $key=>$value)	//Commented for testing purpose on 04_06_2019
							//{												//Commented for testing purpose on 04_06_2019
								//echo '1234'.'<br>'; 
								//$ifms_number=$payment_configData['IFMS_REF_NO'];
								$drn=$payment_configData['DRN'];
								$voucher_no=$payment_configData['voucherNo'];
								$voucher_dt=date_format_change($payment_configData['voucherDate']);
								//$token_no=$payment_configData['tokenNo'];
								//$token_dt=date_format_change($payment_configData['tokenDate']);
								$token_no=0;
								$token_dt=0;
								
								//foreach($value['BENEFICIARY_DETAIL'] as $key2=>$value2)
								//{
									//echo countdim($payment_configData['beneficiaryDetail']); 
								if(countdim($payment_configData['beneficiaryDetail'])>1)		//value of countdim($payment_configData['beneficiaryDetail']) is 2;
								{
									foreach($payment_configData['beneficiaryDetail'] as $key2=>$value2)
									{
										//echo '1234'.'<br>';
										//echo "<pre>";
										//print_r($payment_configData['beneficiaryDetail']);
										array_push($all_emp_id_arr,"'".$value2['refBenfId']."'");
										if($value2['status']=='Success')
										{
											//$emp_pay_succes_arr[$value2['refBenfId']]=$value2['paymentDt'];
											$emp_pay_succes_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['utrNo']));
										}
										else if($value2['status']=='Failed')
										{
											/*$emp_pay_failure_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['accountNumber']),trim($value2['ifscCode']),trim($value2['amount']),trim($value2['refOrdernoDt']),trim($value2['referenceNo']),trim($value2['utrNo'],trim($value2['reason'])));*/
											$emp_pay_failure_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['accountNumber']),trim($value2['ifscCode']),trim($value2['amount']),trim($value2['refOrdernoDt']),trim($value2['referenceNo']),trim($value2['utrNo']),trim($value2['reason']));
										}
									}
								}
								else
								{
									array_push($all_emp_id_arr,"'".$payment_configData['beneficiaryDetail']['refBenfId']."'");
									if($payment_configData['beneficiaryDetail']['status']=='Success')
									{
										//$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=$payment_configData['beneficiaryDetail']['paymentDt'];
										$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array(trim($payment_configData['beneficiaryDetail']['paymentDt']),trim($payment_configData['beneficiaryDetail']['utrNo']));
									}
									else if($payment_configData['beneficiaryDetail']['status']=='Failed')
									{
										$emp_pay_failure_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array(trim($payment_configData['beneficiaryDetail']['paymentDt']),trim($payment_configData['beneficiaryDetail']['accountNumber']),trim($payment_configData['beneficiaryDetail']['ifscCode']),trim($payment_configData['beneficiaryDetail']['amount']),trim($payment_configData['beneficiaryDetail']['refOrdernoDt']),trim($payment_configData['beneficiaryDetail']['referenceNo']),trim($payment_configData['beneficiaryDetail']['utrNo']),trim($payment_configData['beneficiaryDetail']['reason']));
									
									}
									//break;
								}
								//}
								
								
								
							//}											//Commented for testing purpose on 04_06_2019
							//die;
							////////////////////////done
							$all_emp_id_str=implode(",",$all_emp_id_arr);
							//Commented for testing purpose on 04_06_2019
							//$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM mad_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
							//Commented for testing purpose on 04_06_2019
							
							
							//echo $all_emp_id_str; die;
							//echo "<pre>";
							//print_r($emp_id_fetch); die;
							$cnt_f=0;$cnt_s=0;$ins_sql='';$emp_id_failure_list='';$emp_id_success_list='';
							
							
							//Commented for testing purpose on 04_06_2019
							//for($h=0;$h<count($emp_id_fetch);$h++)
							//{
							//Commented for testing purpose on 04_06_2019
								foreach($emp_pay_failure_arr as $key3=>$value3)
								{
									//echo $key3;
									$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM mad_employee_master WHERE emp_id_const in ('".$key3."')");
									//print_r($value3);
									//if($emp_id_fetch[$h]['emp_id_const']==$key3)
									if($emp_id_fetch[0]['emp_id_const']==$key3)
									{
										/*$ins_sql.="('".$bill_details_fetch_gp[$i]['municipality_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$h]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."','".$value3[4]."','".$key3."','".$value3[7]."','".$value3[6]."','10',now(),'".$_SERVER['REMOTE_ADDR']."','555','1')";*/
										/*$ins_sql.="('".$bill_details_fetch_gp[$i]['municipality_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$h]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."','".$key3."','".$value3[4]."','','".$value3[7]."','".$value3[6]."','10',now(),'".$_SERVER['REMOTE_ADDR']."','555','1')";*/
										$ins_sql.="('".$bill_details_fetch_gp[$i]['municipality_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[0]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."','".$key3."','".$value3[4]."','','".$value3[7]."','".$value3[6]."','10',now(),'".$_SERVER['REMOTE_ADDR']."','555','1')";
									
										//$emp_id_failure_list.=$emp_id_fetch[$h]['emp_id_pk'];
										$emp_id_failure_list.=$emp_id_fetch[0]['emp_id_pk'];
										
										if($cnt_f!=count($emp_pay_failure_arr)-1)
										{
											$ins_sql=$ins_sql.",";
											$emp_id_failure_list=$emp_id_failure_list.",";
										}
										
										//$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
										$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[0]['emp_id_pk']." THEN to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
										//$upd_string_failure1.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN '".$value3[1]."' ";
										//$upd_string_failure1.="WHEN emp_id_fk = ".$emp_id_fetch[0]['emp_id_pk']." THEN '".$value3[1]."' ";
										$upd_string_failure1.="WHEN emp_id_fk = ".$emp_id_fetch[0]['emp_id_pk']." THEN '".$value3[6]."' ";
										$cnt_f++;
									}
									
								}
								
								
								foreach($emp_pay_succes_arr as $key4=>$value4)
								{
									$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM mad_employee_master WHERE emp_id_const in ('".$key4."')");
									//echo '1234 <br>';
									//if($emp_id_fetch[$h]['emp_id_const']==$key4)
									if($emp_id_fetch[0]['emp_id_const']==$key4)
									{
										//$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value4[0])."','YYYY-MM-DD') ";
										$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[0]['emp_id_pk']." THEN to_date('".date_format_change($value4[0])."','YYYY-MM-DD') ";
										//$upd_string_success1.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN '".$value4[1]."' ";
										$upd_string_success1.="WHEN emp_id_fk = ".$emp_id_fetch[0]['emp_id_pk']." THEN '".$value4[1]."' ";
										//$emp_id_success_list.=$emp_id_fetch[$h]['emp_id_pk'];
										$emp_id_success_list.=$emp_id_fetch[0]['emp_id_pk'];
										if($cnt_s!=count($emp_pay_succes_arr)-1)
										{
											$emp_id_success_list=$emp_id_success_list.",";
										}
										$cnt_s++;
									}
								}
							//}
							//Commented for testing purpose on 04_06_2019
							
							/*$update_payment_response=$db->update("UPDATE mad_sftp_benf_upload_response
																	SET sftp_benf_response_status='8',
																	sftp_benf_response_recieve_time=now(),
																	voucher_no='".$voucher_no."',
																	voucher_date='".$voucher_dt."',
																	token_no='".$token_no."',
																	token_date=now()
																	WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																	AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");*/
																	
							//Commented for testing purpose on 04_06_2019										
							$update_payment_response=$db->update("UPDATE mad_sftp_benf_upload_response
																	SET sftp_benf_response_status='8',
																	sftp_benf_response_recieve_time=now(),
																	voucher_no='".$voucher_no."',
																	voucher_date='".$voucher_dt."',
																	token_no='".$token_no."',
																	token_date=now()
																	WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																	AND sftp_benf_sending_status='4' 
																	AND (sftp_benf_response_status='5' OR sftp_benf_response_status='8')");
																	
							$insert_track=$db->insert("INSERT INTO mad_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['municipality_bill_pk']."',
															8,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							//Commented for testing purpose on 04_06_2019
							
							//$update_payment_response=$insert_track=1;
							
							if($update_payment_response && $insert_track)
							{
								//echo $upd_string_failure; die;
								if($ins_sql!="" && $upd_string_failure!="")
								{
									/*echo " INSERT INTO mad_sftp_benf_failure_details
																				(
																				bill_id_fk,
																				sftp_benf_id_fk,
																				emp_id_fk,
																				ifms_ref_no,
																				benf_name,
																				account_no,
																				ifsc_code,
																				mobile_no,
																				amount,
																				id,
																				order_no,
																				unique_id,
																				reason,
																				payment_utr_no,
																				response_from,
																				entry_time,
																				entry_ip,
																				client_type,
																				active_status
																				)
																				VALUES 
																				".$ins_sql;*/
									//die;
									pg_query("BEGIN");
									//Commented for testing purpose on 04_06_2019
									$insert_benf_failure_details=$db->insert(" INSERT INTO mad_sftp_benf_failure_details
																				(
																				bill_id_fk,
																				sftp_benf_id_fk,
																				emp_id_fk,
																				ifms_ref_no,
																				benf_name,
																				account_no,
																				ifsc_code,
																				mobile_no,
																				amount,
																				id,
																				order_no,
																				unique_id,
																				reason,
																				payment_utr_no,
																				response_from,
																				entry_time,
																				entry_ip,
																				client_type,
																				active_status
																				)
																				VALUES 
																				".$ins_sql);
									//$insert_benf_failure_details=1;
									//Commented for testing purpose on 04_06_2019
																				
									
									/*$update_salary_save_details_f=$db->update(" UPDATE mad_employee_salary_save SET
																				payment_status=12,
																				payment_date= CASE ".$upd_string_failure." END
																				WHERE salary_monthyear='".date('Ym')."' 
																				AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																				AND emp_id_fk in (".$emp_id_failure_list.") ");*/
									if($bill_details_fetch_gp[$i]['requisition_type']=='1001')
									{
																				
										/*$update_arc_final_details_f=$db->update(" UPDATE mad_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status is null");*/
																					
										
										$update_arc_final_details_f=$db->update(" UPDATE mad_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_failure_list.")");
										
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1004')
									{
										/*$update_arc_final_details_f=$db->update(" UPDATE mad_bonus_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3' 
																					AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status is null");*/
										$update_arc_final_details_f=$db->update(" UPDATE mad_bonus_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3' 
																					AND emp_id_fk in (".$emp_id_failure_list.")");
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1005')
									{
										/*$update_arc_final_details_f=$db->update(" UPDATE mad_festival_advance_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3' 
																					AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status is null");*/
										$update_arc_final_details_f=$db->update(" UPDATE mad_festival_advance_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3' 
																					AND emp_id_fk in (".$emp_id_failure_list.")");
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1003')
									{	
										/*$update_arc_final_details_f=$db->update(" UPDATE mad_employee_arrear_details SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE delete_status='2' 
																					AND status_flag='4'
																					AND is_saved='1' 
																					AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																					AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status is null");*/
										$update_arc_final_details_f=$db->update(" UPDATE mad_employee_arrear_details SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END,
																					utr_no= CASE ".$upd_string_failure1." END
																					WHERE delete_status in('0','2') 
																					AND status_flag='4'
																					AND is_saved='1' 
																					AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																					AND emp_id_fk in (".$emp_id_failure_list.")");
										
									}
																				
									
									if($insert_benf_failure_details && $update_arc_final_details_f)
									{
										pg_query("COMMIT");
										$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Failed Data Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
									}
									else
									{
										pg_query("ROLLBACK");
										$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Failed Data Not Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
									}
								}
								
								//echo $upd_string_success; die;
								if($upd_string_success!='')
								{
									/*$update_salary_save_details_s=$db->update(" UPDATE mad_employee_salary_save SET
																				payment_status=11,
																				payment_date= CASE ".$upd_string_success." END
																				WHERE salary_monthyear='".date('Ym')."' 
																				AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																				AND emp_id_fk in (".$emp_id_success_list.") ");*/
									if($bill_details_fetch_gp[$i]['requisition_type']=='1001')
									{						
										/*$update_arc_final_details_s=$db->update(" UPDATE mad_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_success_list.") 
																					AND (payment_status is null OR payment_status='12')");*/
																					
										//Commented for testing purpose on 04_06_2019
										$update_arc_final_details_s=$db->update(" UPDATE mad_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='4' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_success_list.")");
										//Commented for testing purpose on 04_06_2019
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1004')
									{
										/*$update_arc_final_details_s=$db->update(" UPDATE mad_bonus_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3'
																					AND emp_id_fk in (".$emp_id_success_list.") 
																					AND (payment_status is null OR payment_status='12')");*/
										$update_arc_final_details_s=$db->update(" UPDATE mad_bonus_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3'
																					AND emp_id_fk in (".$emp_id_success_list.")");
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1005')
									{
										/*$update_arc_final_details_s=$db->update(" UPDATE mad_festival_advance_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3'
																					AND emp_id_fk in (".$emp_id_success_list.") 
																					AND (payment_status is null OR payment_status='12')");*/
										$update_arc_final_details_s=$db->update(" UPDATE mad_festival_advance_entry_sal SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE monthyear='".$file_monthyear."' 
																					AND delete_status='1' 
																					AND status='3'
																					AND approval_status='3'
																					AND emp_id_fk in (".$emp_id_success_list.")");
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1003')
									{
										/*$update_arc_final_details_s=$db->update(" UPDATE mad_employee_arrear_details SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE delete_status='2' 
																					AND status_flag='4'
																					AND is_saved='1'
																					AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																					AND emp_id_fk in (".$emp_id_success_list.") 
																					AND (payment_status is null OR payment_status='12')");*/
										$update_arc_final_details_s=$db->update(" UPDATE mad_employee_arrear_details SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_no= CASE ".$upd_string_success1." END
																					WHERE delete_status='2' 
																					AND status_flag='4'
																					AND is_saved='1'
																					AND bill_id_fk='".$bill_details_fetch_gp[$i]['municipality_bill_pk']."'
																					AND emp_id_fk in (".$emp_id_success_list.")");
									}
									
									
									if($update_arc_final_details_s)
									{
										pg_query("COMMIT");
										$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#008000;'>Success Data Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
									}
									else
									{
										pg_query("ROLLBACK");
										$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Success Data Not Inserted of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
									}
								}
							}
						}
					}
					else
					{
						$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>No Response From IFMS of ".$mun_name_text." (".$bill_details_fetch_gp[$i]['municipality_id_fk'].") of ".$sal_type_text.", </p><br>";
					}
				}
			}
		}
		else
		{
			$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>File Upload Failed.</p><br>";
		}
	}
	else
	{
		$_SESSION['ifms_msg'].="<p style='font-weight:bold;color:#FA8072;'>Connection Failed.</p><br>";
	}
}

?>
<script>
	window.location.href="<?=$config['base_url'] ?>page/intra_mad/webmaster/mad_ifms_cron_job_form.php";
</script>