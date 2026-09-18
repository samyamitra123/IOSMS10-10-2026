<?php
set_time_limit(0);
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');

$crypto = new cryptography();
$db=new database();	

///// Pervious Month Year ////////
$k=strtotime("first day of last month");
$prev_monthyr = date("Ym",$k); 
/////////////////////////////

$current_monthyr=date('Ym');

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

function file_name_list($arr,$checker,$total_length_without_ext)
{
	$required_arr=array();
	
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],$total_length_without_ext,1)==$checker)
		{
			array_push($required_arr,substr($arr[$k],0,$total_length_without_ext));
		}
	}
	return $required_arr;
}

function file_name($arr,$checker,$file_name,$total_length_without_ext)
{
	if($total_length_without_ext!='31')
	{
		for($k=0;$k<count($arr);$k++)
		{
			if(substr($arr[$k],$total_length_without_ext,1)==$checker && substr($arr[$k],0,$total_length_without_ext)==$file_name)
			{
				$req_file_name=$arr[$k];
			}
		}
	}
	else
	{
		$req_file_name=array();
		for($k=0;$k<count($arr);$k++)
		{
			if(substr($arr[$k],$total_length_without_ext,1)==$checker && substr($arr[$k],0,$total_length_without_ext)==$file_name)
			{
				array_push($req_file_name,$arr[$k]);
			}
		}
	}
	return $req_file_name;
}

function payment_file_list_db_fetch($file_name_without_ext,$monyr,$party)
{
	
	$payment_without_ext=$party.$file_name_without_ext;
	$db=new database();
	$name_list_arr=array();
	
	$fetch_name_list=$db->fetch_table(" SELECT payment_file_name FROM prd_monthly_salary_archive_final WHERE substr(payment_file_name,1,31)='".$payment_without_ext."' AND salary_monthyear='".$monyr."' AND lock_status=0 ");	
	
	foreach($fetch_name_list as $key=>$value)
	{
		array_push($name_list_arr,$value['payment_file_name']);
	}
	return $name_list_arr;
}



set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');

include('Net/SFTP.php');


///////////////////////////////////////////////////////////////////////  ZP START ///////////////////////////////////////////////////////////////////////

if($_GET['user']!='gp' && $_GET['user']!='ps')
{
	$sftp_zp=sftp_login($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp']);
	if($sftp_zp!=0)
	{
		/*$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_002/';
		$payment_file_name_list =  $sftp_zp->nlist($remote_directory_005);
		print_r($payment_file_name_list);die;*/
		$bill_details_fetch_zp=$db->fetch_table(" SELECT 
												bill.block_bill_pk,
												bill.salary_monthyear,
												bill.drn_number,
												sftp.sftp_benf_id_pk,
												sftp.sftp_benf_file_name,
												sftp.sftp_benf_response_status 
											FROM prd_block_bill_details bill
											INNER JOIN prd_sftp_benf_upload_response sftp
											ON bill.block_bill_pk=sftp.bill_id_fk
											WHERE bill.salary_monthyear in ('201809','201810','201811') AND bill.status='1' AND zp_id_fk!='0'
											AND sftp.active_status='1' AND sftp.sftp_benf_sending_status='4' 
											AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
			
		if(count($bill_details_fetch_zp)!='0')
		{									
			
			$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_002/'; // changes needed as per department //
			$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_003/';
			$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_005/';
			
			$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_002/';
			$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/';
			$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_005_2/';
			
			
			$ack_file_name_list = $sftp_zp->nlist($remote_directory_002);
			$non_ack_file_name_list = $sftp_zp->nlist($remote_directory_003);
			$payment_file_name_list =  $sftp_zp->nlist($remote_directory_005);
			$wrong_data_file_name_list = $sftp_zp->nlist($remote_directory_003);
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);
			//print_r($payment_data_file_arr);die;
			$a=1;
			for($i=0;$i<count($bill_details_fetch_zp);$i++)
			{
				
				////////////////////////////////////////////////////////////////// BEFORE GETTING ACK FILE  /////////////////////////////////////////////////////////////
				
				/*if($bill_details_fetch_zp[$i]['sftp_benf_response_status']=='')
				{
						
				 $filename_without_ext=substr($bill_details_fetch_zp[$i]['sftp_benf_file_name'],0,28); 
					
					////////////////////////////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					
					if(in_array("ACK".$bill_details_fetch_zp[$i]['sftp_benf_file_name'],$ack_file_name_list))
					{
						$ack_file_name="ACK".$bill_details_fetch_zp[$i]['sftp_benf_file_name'];
						//////////////////////////////////////// DOWNLOADING ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						if($sftp_zp->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
						{
							$ack_arry=simplexml_load_file($local_directory_002.$ack_file_name);
							$ack_json  = json_encode($ack_arry);
							$ack_configData = json_decode($ack_json, true);
							$update_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																SET ifms_reference_number='".$ack_configData['IFMS_REF_NO']."',
																sftp_benf_response_status='5',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_zp[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_zp[$i]['block_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_zp[$i]['block_bill_pk']."',
															5,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							
							if($update_ack_response && $insert_track)
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
							
						 $non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						if($sftp_zp->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
						{
							$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
							$non_ack_json  = json_encode($non_ack_arry);
							$non_ack_configData = json_decode($non_ack_json, true);
							
							$update_non_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																	SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
																	sftp_benf_response_status='6',
																	sftp_benf_response_recieve_time='now()'
																	WHERE sftp_benf_file_name='".$bill_details_fetch_zp[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_zp[$i]['block_bill_pk']."'
																	AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_zp[$i]['block_bill_pk']."',
															6,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							
							if($update_non_ack_response && $insert_track)
							{
								echo "WRONG FORMAT ADDED";
							}
							else
							{
								echo "WRONG FORMAT NOT ADDED";
							}
						}
					}
					
					////////////////////////////////////////////// WRONG DATA FILE CHECKING  /////////////////////////////////////////////		 
					
					else if(in_array($filename_without_ext,$wrong_data_file_arr))
					{
						
						$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING WRONG DATA FILE AND DATA READING /////////////////////////////////////////////////
						
						if($sftp_zp->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
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
										 $ins_sql.="('".$bill_details_fetch_zp[$i]['block_bill_pk']."','".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$j]['emp_id_pk']."','".$value3[0]."','".$value3[1]."','".$value3[2]."','".$value3[3]."','".$value3[4]."','".$value3[5]."','".$key3."','".$value3[6]."','".$value3[7]."','".$value3[8]."','','9','now();','".$_SERVER['REMOTE_ADDR']."','555','1')"; 
										
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
																SET sftp_benf_response_status='7',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_zp[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_zp[$i]['block_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
																
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_zp[$i]['block_bill_pk']."',
															7,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");
							
							if($update_wrdt_response && $ins_sql!="" && $insert_track)
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
					
					else
					{
						echo "No response got from IFMS";
					}
				}*/
				
				///////////////////////////////////////////////// AFTER GETTING ACK FILE  ///////////////////////////////////////////////
				
				
				//else 
				if($bill_details_fetch_zp[$i]['sftp_benf_response_status']=='5' || $bill_details_fetch_zp[$i]['sftp_benf_response_status']=='8')
				{
					
					$filename_without_ext=substr($bill_details_fetch_zp[$i]['sftp_benf_file_name'],0,28);
					$drn_number=$bill_details_fetch_zp[$i]['drn_number'];
					$file_monthyear=$bill_details_fetch_zp[$i]['salary_monthyear'];
					$cnt_s=1;$cnt_f=1;
					$all_emp_id_arr=array();
					$emp_pay_succes_arr=array();
					$emp_pay_failure_arr=array();	
					//print_r($payment_file_name_list);die;
					$payment_data_file_arr=file_name($payment_file_name_list,'_','008'.$filename_without_ext,31);
					/*if(count($payment_data_file_arr)==1)
					{
						echo "<pre>";
						print_r($payment_data_file_arr);
					}*/
					$existing_payment_file_list=payment_file_list_db_fetch($filename_without_ext,$file_monthyear,'008');
					echo "<pre>";
					print_r($payment_data_file_arr);
					echo "================================================ <br/>";
					print_r($existing_payment_file_list);
					echo "************************************************ <br/>";
					$aa=array_diff($payment_data_file_arr,$existing_payment_file_list);
					print_r($aa);
					echo "+++++++++++++++++++++++++++++++++++++++++++++++++ ".$a."<br/>";
					//$payment_data_file_name='008'.$filename_without_ext.'_'.$next_payment_sequence.'.xml';
					
					//$a=array_diff($payment_data_file_arr,$existing_payment_file_list);
					//echo "<pre>";
					//print_r($a);
					//////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////
					$a++;
					for($p=0;$p<count($aa);$p++)
					{
						if($sftp_zp->get($remote_directory_005.$aa[$p], $local_directory_005.$aa[$p]))
						{	
								echo 1234;echo "<br/>";
								/*$payment_arry=simplexml_load_file($local_directory_005.$aa[$p]);
								$payment_json  = json_encode($payment_arry);
								$payment_configData = json_decode($payment_json, true);
								print_r($payment_configData);die;*/
								//echo $drn=$payment_configData['DRN'];
								
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
}

///////////////////////////////////////////////////////////////////////  ZP END ///////////////////////////////////////////////////////////////////////


?>