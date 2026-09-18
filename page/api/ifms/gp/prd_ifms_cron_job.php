<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
include( '../../all_function/fun_store/ifms_functions.php');

$crypto = new cryptography();
$db=new database();	

/*$bill_type=$crypto->decode($_GET['bill_type'],4);
$drn_number=$crypto->decode($_GET['drn_no'],4);*/

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


$bill_details_fetch=$db->fetch_table(" SELECT 
											bill.block_bill_pk,
											sftp.sftp_benf_id_pk,
											sftp.sftp_benf_file_name,
											sftp.sftp_benf_response_status 
										FROM prd_block_bill_details bill
										INNER JOIN prd_sftp_benf_upload_response sftp
										ON bill.block_bill_pk=sftp.bill_id_fk
										WHERE bill.salary_monthyear='".date('Ym')."' AND bill.status='1' AND sftp.active_status='1' 
										AND sftp.sftp_benf_sending_status='4' 
										AND (sftp.sftp_benf_response_status not in ('6','7','8') or sftp.sftp_benf_response_status is null)");
										


/*$ack_success_bill_id_arr=array();
$ack_success_sftp_id_arr=array();

$non_ack_success_bill_id_arr=array();
$non_ack_success_sftp_id_arr=array();

$payment_success_bill_id_arr=array();
$payment_success_sftp_id_arr=array();

$wrong_data_success_bill_id_arr=array();
$wrong_data_success_sftp_id_arr=array();*/

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');

include('Net/SFTP.php');

$sftp=sftp_login($config['sftp_ip'],$config['sftp_user_name'],$config['sftp_user_password']);
if($sftp!=0 && count($bill_details_fetch)>0 )
{
	
	$remote_directory_002 = './prd/ePayment_Files_002/'; // changes needed as per department //
	$remote_directory_003 = './prd/ePayment_Files_003/';
	$remote_directory_005 = './prd/ePayment_Files_005/';
	
	$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file/ePayment_Files_002/';
	$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file/ePayment_Files_003/';
	$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file/ePayment_Files_005/';
	
	
	$ack_file_name_list = $sftp->nlist($remote_directory_002);
	$non_ack_file_name_list = $sftp->nlist($remote_directory_003);
	$payment_file_name_list =  $sftp->nlist($remote_directory_005);
	$wrong_data_file_name_list = $sftp->nlist($remote_directory_003);
	
	$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_');
	$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D');
	
	
	for($i=0;$i<count($bill_details_fetch);$i++)
	{
		
		////////////////////////////////////////////////////////////////// BEFORE GETTING ACK FILE  /////////////////////////////////////////////////////////////
		
		if($bill_details_fetch[$i]['sftp_benf_response_status']=='')
		{
				
			$filename_without_ext=substr($bill_details_fetch[$i]['sftp_benf_file_name'],0,28);
			
			////////////////////////////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////////////////////
			
			if(in_array("ACK".$bill_details_fetch[$i]['sftp_benf_file_name'],$ack_file_name_list))
			{
				$ack_file_name="ACK".$bill_details_fetch[$i]['sftp_benf_file_name'];
				//////////////////////////////////////// DOWNLOADING ACK FILE AND DATA READING /////////////////////////////////////////////////
				
				if($sftp->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
				{
					$ack_arry=simplexml_load_file($local_directory_002.$ack_file_name);
					$ack_json  = json_encode($ack_arry);
					$ack_configData = json_decode($ack_json, true);
					
					
					$update_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
														SET ifms_reference_number='".$ack_configData['IFMS_REF_NO']."',
														sftp_benf_response_status='5'
														WHERE sftp_benf_file_name='".$bill_details_fetch[$i]['sftp_benf_file_name']."'
														AND sftp_benf_id_pk='".$bill_details_fetch[$i]['sftp_benf_id_pk']."'
														AND bill_id_fk='".$bill_details_fetch[$i]['block_bill_pk']."'
														AND sftp_benf_sending_status='4'");
					
					if($update_ack_response)
					{
						echo "SUCCESS IFMS GENERATION";
					}
					else
					{
						echo "NOT SUCCESS IFMS GENERATION";
					}
				}
			}
			
			//////////////////////////////////////////////////////////// NON-ACK FILE CHECKING  /////////////////////////////////////////////////////////////
			
			else if(in_array($filename_without_ext,$non_ack_file_arr))
			{
					
				$non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext);
				
				//////////////////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING /////////////////////////////////////////////////
				
				if($sftp->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
				{
					$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
					$non_ack_json  = json_encode($non_ack_arry);
					$non_ack_configData = json_decode($non_ack_json, true);
					
					$update_non_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
															SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
															sftp_benf_response_status='6'
															WHERE sftp_benf_file_name='".$bill_details_fetch[$i]['sftp_benf_file_name']."'
															AND sftp_benf_id_pk='".$bill_details_fetch[$i]['sftp_benf_id_pk']."'
															AND bill_id_fk='".$bill_details_fetch[$i]['block_bill_pk']."'
															AND sftp_benf_sending_status='4'");
					
					if($update_non_ack_response)
					{
						echo "WRONG FORMAT ADDED";
					}
					else
					{
						echo "WRONG FORMAT NOT ADDED";
					}
				}
			}
		}
		
		////////////////////////////////////////////////////////////////// AFTER GETTING ACK FILE  /////////////////////////////////////////////////////////////
		
		else if($bill_details_fetch[$i]['sftp_benf_response_status']=='5')
		{
			$filename_without_ext=substr($bill_details_fetch[$i]['sftp_benf_file_name'],0,28);
			$cnt_s=1;$cnt_f=1;
			$all_emp_id_arr=array();
			$emp_pay_succes_arr=array();
			$emp_pay_failure_arr=array();	
			////////////////////////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////////////////////
			
			if(in_array($bill_details_fetch[$i]['sftp_benf_file_name'],$payment_file_name_list))
			{
				
				//////////////////////////////////////// DOWNLOADING PAYMENT FILE AND DATA READING /////////////////////////////////////////////////
				
				if($sftp->get($remote_directory_005.$bill_details_fetch[$i]['sftp_benf_file_name'], $local_directory_005.$bill_details_fetch[$i]['sftp_benf_file_name']))
				{
						
					$payment_arry=simplexml_load_file($local_directory_005.$bill_details_fetch[$i]['sftp_benf_file_name']);
					$payment_json  = json_encode($payment_arry);
					$payment_configData = json_decode($payment_json, true);
					
					foreach($payment_configData as $key=>$value)
					{
						$ifms_number=$payment_configData['IFMS_REF_NO'];
						
						foreach($value['BENEFICIARY_DETAIL'] as $key2=>$value2)
						{
							if(countdim($value['BENEFICIARY_DETAIL'])>1)
							{
								array_push($all_emp_id_arr,"'".$value2['ID']."'");
								if($value2['PAYMENT_STATUS']=='S')
								{
									$emp_pay_succes_arr[$value2['ID']]=$value2['PAYMENT_DATE'];
								}
								else if($value2['PAYMENT_STATUS']=='F')
								{
									$emp_pay_failure_arr[$value2['ID']]=array(trim($value2['PAYMENT_DATE']),trim($value2['ACCOUNT_NO']),trim($value2['IFSC_CODE']),trim($value2['AMOUNT']),trim($value2['ORDER_NO']),trim($value2['UTR_NO']),trim($value2['REASON']));
								
								}
							}
							else
							{
								array_push($all_emp_id_arr,"'".$value['BENEFICIARY_DETAIL']['ID']."'");
								if($value['BENEFICIARY_DETAIL']['PAYMENT_STATUS']=='S')
								{
									$emp_pay_succes_arr[$value['BENEFICIARY_DETAIL']['ID']]=$value['BENEFICIARY_DETAIL']['PAYMENT_DATE'];
								}
								else if($value['BENEFICIARY_DETAIL']['PAYMENT_STATUS']=='F')
								{
									$emp_pay_failure_arr[$value['BENEFICIARY_DETAIL']['ID']]=array(trim($value['BENEFICIARY_DETAIL']['PAYMENT_DATE']),trim($value['BENEFICIARY_DETAIL']['ACCOUNT_NO']),trim($value['BENEFICIARY_DETAIL']['IFSC_CODE']),trim($value['BENEFICIARY_DETAIL']['AMOUNT']),trim($value['BENEFICIARY_DETAIL']['ORDER_NO']),trim($value['BENEFICIARY_DETAIL']['UTR_NO']),trim($value['BENEFICIARY_DETAIL']['REASON']));
								
								}
								break;
							}
						}
					}
					
					$all_emp_id_str=implode(",",$all_emp_id_arr);
					$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
					
					$cnt_f=0;$cnt_s=0;
					for($h=0;$h<count($emp_id_fetch);$h++)
					{
						foreach($emp_pay_failure_arr as $key3=>$value3)
						{
							if($emp_id_fetch[$h]['emp_id_const']==$key3)
							{
								$ins_sql.="('".$bill_details_fetch[$i]['block_bill_pk']."','".$bill_details_fetch[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$h]['emp_id_pk']."','".$ifms_number."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."','".$key3."','".$value3[4]."','','".$value3[5]."','".$value3[6]."','10','now();','".$_SERVER['REMOTE_ADDR']."','555','1')";
							
								$emp_id_failure_list.=$emp_id_fetch[$h]['emp_id_pk'];
								
								if($cnt_f!=count($emp_pay_failure_arr)-1)
								{
									$ins_sql=$ins_sql.",";
									$emp_id_failure_list=$emp_id_failure_list.",";
								}
								
								$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
								$cnt_f++;
							}
							
						}
						
						foreach($emp_pay_succes_arr as $key4=>$value4)
						{
							if($emp_id_fetch[$h]['emp_id_const']==$key4)
							{
								$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value4)."','YYYY-MM-DD') ";
								$emp_id_success_list.=$emp_id_fetch[$h]['emp_id_pk'];
								if($cnt_s!=count($emp_pay_succes_arr)-1)
								{
									$emp_id_success_list=$emp_id_success_list.",";
								}
								$cnt_s++;
							}
						}
					}
					
					$update_payment_response=$db->update("UPDATE prd_sftp_benf_upload_response
															SET sftp_benf_response_status='8'
															WHERE sftp_benf_file_name='".$bill_details_fetch[$i]['sftp_benf_file_name']."'
															AND sftp_benf_id_pk='".$bill_details_fetch[$i]['sftp_benf_id_pk']."'
															AND bill_id_fk='".$bill_details_fetch[$i]['block_bill_pk']."'
															AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");
					
					if($update_payment_response)
					{
						if($ins_sql!="" && $upd_string_failure!="")
						{
							pg_query("BEGIN");
							$insert_benf_failure_details=$db->insert(" INSERT INTO prd_sftp_benf_failure_details
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
							
							$update_salary_save_details_f=$db->update(" UPDATE prd_employee_salary_save SET
																		payment_status=12,
																		payment_date= CASE ".$upd_string_failure." END
																		WHERE salary_monthyear='".date('Ym')."' 
																		AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																		AND emp_id_fk in (".$emp_id_failure_list.") ");
							
							if($insert_benf_failure_details && $update_salary_save_details_f)
							{
								pg_query("COMMIT");
								echo "Failed data successfully inserted.";
							}
							else
							{
								pg_query("ROLLBACK");
								echo "Failed data insertion has been failed.";
							}
						}
						
						if($upd_string_success!='')
						{
							$update_salary_save_details_s=$db->update(" UPDATE prd_employee_salary_save SET
																		payment_status=11,
																		payment_date= CASE ".$upd_string_success." END
																		WHERE salary_monthyear='".date('Ym')."' 
																		AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																		AND emp_id_fk in (".$emp_id_success_list.") ");
							
							if($update_salary_save_details_s)
							{
								pg_query("COMMIT");
								echo "Success data successfully inserted.";
							}
							else
							{
								pg_query("ROLLBACK");
								echo "Success data insertion has been failed.";
							}
						}
					}
				}
			}
			
			////////////////////////////////////////////////////////////////// WRONG DATA FILE CHECKING  ///////////////////////////////////////////////////////////		 
			
			else if(in_array($filename_without_ext,$wrong_data_file_arr))
			{
				$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext);
				
				//////////////////////////////////////// DOWNLOADING WRONG DATA FILE AND DATA READING /////////////////////////////////////////////////
				
				if($sftp->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
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
					
					$all_emp_id_str=implode(",",$all_emp_id_arr);
					$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
					$cnt_w=0;
					for($j=0;$j<count($emp_id_fetch);$j++)
					{
						foreach($emp_wrdt_failure_arr as $key3=>$value3)
						{
							if($emp_id_fetch[$j]['emp_id_const']==$key3)
							{
								$ins_sql.="('".$bill_details_fetch[$i]['block_bill_pk']."','".$bill_details_fetch[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$j]['emp_id_pk']."','".$value3[0]."','".$value3[1]."','".$value3[2]."','".$value3[3]."','".$value3[4]."','".$value3[5]."','".$key3."','".$value3[6]."','".$value3[7]."','".$value3[8]."','','9','now();','".$_SERVER['REMOTE_ADDR']."','555','1')";
								
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
					
					$update_wrdt_response=$db->update("UPDATE prd_sftp_benf_upload_response
					SET sftp_benf_response_status='7'
					WHERE sftp_benf_file_name='".$bill_details_fetch[$i]['sftp_benf_file_name']."'
					AND sftp_benf_id_pk='".$bill_details_fetch[$i]['sftp_benf_id_pk']."'
					AND bill_id_fk='".$bill_details_fetch[$i]['block_bill_pk']."'
					AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");
					
					if($update_wrdt_response && $ins_sql!="")
					{
						$insert_benf_failure_details=$db->insert(" INSERT INTO prd_sftp_benf_failure_details
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
							echo "Wrong data successfully inserted.";
						}
						else
						{
							echo "Wrong data insertion has been failed.";
						}
					}
				}
			}
		}
	}
}
else
{
	echo "Connection Fails";
}
die;


?>