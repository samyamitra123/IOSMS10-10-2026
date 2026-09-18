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

 $filename = $crypto->decode($_GET['sftp_benf_file_name'],4);
$drn_number = $crypto->decode($_GET['drn_number'],4);
$new_name= substr($filename,0,-4);
 $monthyear= $crypto->decode($_GET['monthyear'],4);
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
	for($k=0;$k<count($arr);$k++)
	{
		if(substr($arr[$k],$total_length_without_ext,1)==$checker && substr($arr[$k],0,$total_length_without_ext)==$file_name)
		{
			$req_file_name=$arr[$k];
		}
	}
	return $req_file_name;
}

function payment_sequence_find($file_name_without_ext,$monyr,$party)
{
	
	$payment_without_ext=$party.$file_name_without_ext;
	$db=new database();
	
	$fetch_max_seq=$db->fetch_table(" SELECT max(substr(payment_file_name,33,5)) as max_serial FROM prd_monthly_salary_archive_final WHERE substr(payment_file_name,1,31)='".$payment_without_ext."' AND salary_monthyear='".$monyr."' AND lock_status=0 ");	
	
	if($fetch_max_seq[0]['max_serial']=='')
	{
		$next_serial='00001';
	}
	else
	{
		$max_serial=$fetch_max_seq[0]['max_serial'];
		$next_digit=$max_serial+1;
		$next_serial=str_pad($next_digit,5,'0',STR_PAD_LEFT);
	}
	return $next_serial;
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


//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');

//include('Net/SFTP.php');


////////////////////////////////////////////////////////////////  GP START ///////////////////////////////////////////////////////

if($_GET['user']!='ps' && $_GET['user']!='zp')
{
	
	//$sftp_gp=sftp_login($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp']);
	$party_code='006';
	$sftp_gp='1';
	if($sftp_gp!=0)
	{
		
		$bill_details_fetch_gp=$db->fetch_table(" SELECT 
												bill.block_bill_pk,
												bill.salary_monthyear,
												bill.drn_number,
												sftp.sftp_benf_id_pk,
												sftp.sftp_benf_file_name,
												sftp.sftp_benf_response_status 
											FROM prd_block_bill_details bill
											INNER JOIN prd_sftp_benf_upload_response sftp
											ON bill.block_bill_pk=sftp.bill_id_fk
											WHERE bill.salary_monthyear in ('".$monthyear."') AND bill.status='1' AND block_code!='0' 
											AND sftp.active_status='1' AND sftp.sftp_benf_sending_status='4' and sftp.sftp_benf_file_name='".$filename."'
											AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
		
		if(count($bill_details_fetch_gp)!='0')
		{
		
			/*$remote_directory_002 = '/apps/ePaymentFiles/gen006/ePayment_Files_002/'; // changes needed as per department //
			$remote_directory_003 = '/apps/ePaymentFiles/gen006/ePayment_Files_003/';
			$remote_directory_005 = '/apps/ePaymentFiles/gen006/ePayment_Files_005/';*/
			
			
			
			/*$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_002/';
			$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_003/';
			$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_005/';*/
			
			
			
			$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_002/';
			$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_003/';
			$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_005/';
			
			
			/*$ack_file_name_list = $sftp_gp->nlist($remote_directory_002);
			$non_ack_file_name_list = $sftp_gp->nlist($remote_directory_003);
			$payment_file_name_list =  $sftp_gp->nlist($remote_directory_005);
			$wrong_data_file_name_list = $sftp_gp->nlist($remote_directory_003);*/
			
			$non_ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_003/');
			$payment_file_name_list =  scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_005/');
			$wrong_data_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_003/');
			$ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_002/');
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			//$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);
			
			$payment_file_arr=file_name_list_payment($payment_file_name_list,'_');	//Modified new
			
			
			for($i=0;$i<count($bill_details_fetch_gp);$i++)
			{
				
				///////////////////////////////////// BEFORE GETTING ACK FILE  ///////////////////////////////////
				
				if($bill_details_fetch_gp[$i]['sftp_benf_response_status']=='')
				{
					
					 $filename_without_ext=substr($bill_details_fetch_gp[$i]['sftp_benf_file_name'],0,28); 
					 
					 
					
					 
					 ////////////////////////////////////  weservice call statrt/////////////////////////////////
				$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_002/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_zip/';
				$username = 'gen006'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'gengraWb!fm506';
				$password = hash("sha512", $password_hash);
				$fileType = 'ACKF';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen006';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_ack = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
				
				//print_r($ben_upload_ack); die;
				//$ben_upload_ack='1';
				if($ben_upload_ack==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'ACKF',
								now(),
								'".$response."',
								'".$response."'
								)");
				//$ben_upload_ack='1';
				//print_r($ben_upload_ack); die;
				if($ben_upload_ack!='1')
				{
					
					
					
								
					//echo 12; die;
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_zip/';
					$username = 'gen006'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'gengraWb!fm506';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRMF';
					//$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen006';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_format = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					
					
					if($ben_upload_wrong_format==1)
					{
					$response=1;
					
					}
					else
					{
					$response=2;
					}
					
					$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
					VALUES (
					'".$drn_number."',
					'".$monthyear."',
					'".$request_id."',
					'WRMF',
					now(),
					'".$response."',
					'".$response."'
					)");
					
					
				
				//var_dump($ben_upload_wrong_format); die;
				
				//$ben_upload_wrong_format='1';
				if($ben_upload_wrong_format!='1')
				{
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_zip/';
					$username = 'gen006'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'gengraWb!fm506';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen006';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_data = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					
					if($ben_upload_wrong_data==1)
					{
					$response=1;
					
					}
					else
					{
					$response=2;
					}
					
					$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
					VALUES (
					'".$drn_number."',
					'".$monthyear."',
					'".$request_id."',
					'WRND',
					now(),
					'".$response."',
					'".$response."'
					)");
				}
				}
				
				
					
				//var_dump($ben_upload_ack); die;
				
				/*if($ben_upload_ack=='1')
				{
				
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
				VALUES (
				'".$drn_number."',
				'202007',
				'".$request_id."',
				'ACKF',
				now(),
				'".$ben_upload_ack."',
				'".$ben_upload_ack."'
				)");
				}*/
				////////////////////////////////////  weservice call end/////////////////////////////////
				
				
					
					///////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////
					//echo "ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name']; die;
					if(in_array("ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name'],$ack_file_name_list))
					{
						
						$ack_file_name="ACK".$bill_details_fetch_gp[$i]['sftp_benf_file_name']; 
						
						///////////////////////////////// DOWNLOADING ACK FILE AND DATA READING //////////////////////////////////////
						
						/*if($sftp_gp->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
						{
*/							
							$ack_arry=simplexml_load_file($local_directory_002.$ack_file_name);
							$ack_json  = json_encode($ack_arry);
							$ack_configData = json_decode($ack_json, true);
							
							
							$update_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																SET ifms_reference_number='".$ack_configData['IFMS_REF_NO']."',
																sftp_benf_response_status='5',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_gp[$i]['block_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
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
						//}
					}
					
					/////////////////////////////////////////////// NON-ACK FILE CHECKING  /////////////////////////////////////////
					
					else if(in_array($filename_without_ext,$non_ack_file_arr))
					{
						
						$non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext,28);
						
						///////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING ///////////////////////////////
						
						/*if($sftp_gp->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
						{*/
							$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
							$non_ack_json  = json_encode($non_ack_arry);
							$non_ack_configData = json_decode($non_ack_json, true);
							
							$update_non_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																	SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
																	active_status=2,
																	sftp_benf_response_status='6',
																	sftp_benf_response_recieve_time='now()'
																	WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_gp[$i]['block_bill_pk']."'
																	AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
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
						//}
					}
					
					////////////////////////////////////////////////// WRONG DATA FILE CHECKING  //////////////////////////////////////////		 
					
					else if(in_array($filename_without_ext,$wrong_data_file_arr))
					{
						
						$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext,28);
						/*if($sftp_gp->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
						{*/
						
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
						$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
						$cnt_w=0;$ins_sql='';$emp_id_wrdt_list='';
						for($j=0;$j<count($emp_id_fetch);$j++)
						{
							foreach($emp_wrdt_failure_arr as $key3=>$value3)
							{
								if($emp_id_fetch[$j]['emp_id_const']==$key3)
								{
									$ins_sql.="('".$bill_details_fetch_gp[$i]['block_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$j]['emp_id_pk']."','".$value3[0]."','".$value3[1]."','".$value3[2]."','".$value3[3]."','".$value3[4]."','".$value3[5]."','".$key3."','".$value3[6]."','".$value3[7]."','".$value3[8]."','','9','now();','".$_SERVER['REMOTE_ADDR']."','555','1')";
									
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
						
						pg_query("BEGIN");
						
						$update_wrdt_response=$db->update("UPDATE prd_sftp_benf_upload_response
															SET sftp_benf_response_status='7',
															sftp_benf_response_recieve_time='now()'
															WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
															AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
															AND bill_id_fk='".$bill_details_fetch_gp[$i]['block_bill_pk']."'
															AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
															
						$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
															7,
															'now()',
															'".$_SERVER['REMOTE_ADDR']."')");	
							
						if($ins_sql!="")
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
								
						}
						if($update_wrdt_response && $insert_benf_failure_details && $insert_track)
						{
							pg_query("COMMIT");
							echo "Wrong data successfully inserted.";
						}
						else
						{
							pg_query("ROLLBACK");
							echo "Wrong data insertion has been failed.";
						}
						
					}
					
					else
					{
						echo "No response got from IFMS";
					}
				}
				
				////////////////////////////////////////// AFTER GETTING ACK FILE  //////////////////////////////////////
				
				else if($bill_details_fetch_gp[$i]['sftp_benf_response_status']=='5' || $bill_details_fetch_gp[$i]['sftp_benf_response_status']=='8')
				{
					
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_005/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_zip/';
				$username = 'gen006'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'gengraWb!fm506';
				$password = hash("sha512", $password_hash);
				$fileType = 'SUFA';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen006';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_payment_file = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					
					
					
					if($ben_upload_payment_file==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'SUFA',
								now(),
								'".$response."',
								'".$response."'
								)");
								
					$filename_without_ext=substr($bill_details_fetch_gp[$i]['sftp_benf_file_name'],0,28);
					$drn_number=$bill_details_fetch_gp[$i]['drn_number'];
					$file_monthyear=$bill_details_fetch_gp[$i]['salary_monthyear'];
					$cnt_s=1;$cnt_f=1;
					$all_emp_id_arr=array();
					$emp_pay_succes_arr=array();
					$emp_pay_failure_arr=array();	
					//$next_payment_sequence=payment_sequence_find($filename_without_ext,$file_monthyear,'006');
					
					//$payment_data_file_name='006'.$filename_without_ext.'_'.$next_payment_sequence.'.xml';
					
					$party_code='006';	//Modified new
					//$party_code='10';	//Modified new
					 $filename_without_ext_payment=$party_code.$filename_without_ext;
					
					//////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////
					
					
					if(in_array($filename_without_ext_payment,$payment_file_arr))
					{
					  $payment_file_name=file_name_payment($payment_file_name_list,'_',$filename_without_ext_payment);  
						
						
						/////////////////////////////////// DOWNLOADING PAYMENT FILE AND DATA READING ///////////////////////////////////////
						
						/*if($sftp_gp->get($remote_directory_005.$payment_file_name, $local_directory_005.$payment_file_name))
						{*/	
						
							
							
							$payment_arry=simplexml_load_file($local_directory_005.$payment_file_name);
							$payment_json  = json_encode($payment_arry);
							print_r($payment_json); die;
							$payment_configData = json_decode($payment_json, true);
							
							$drn=$payment_configData['DRN'];
							 $voucher_no=$payment_configData['voucherNo']; 
							$voucher_dt=date_format_change($payment_configData['voucherDate']);
							$token_no=$payment_configData['tokenNo'];
							$token_dt=date_format_change($payment_configData['tokenDate']);
							
							
							if(countdim($payment_configData['beneficiaryDetail'])>1)
							{
								//echo countdim($payment_configData['beneficiaryDetail']); die;
								foreach($payment_configData['beneficiaryDetail'] as $key2=>$value2)
								{
									
									array_push($all_emp_id_arr,"'".$value2['refBenfId']."'");
									if($value2['status']=='Success')
									{
										
										//$emp_pay_succes_arr[$value2['refBenfId']]=$value2['paymentDt'];
					 $emp_pay_succes_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['utrNo'])); 
									}
									else if($value2['status']=='Failure')
									{
										$emp_pay_failure_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['accountNumber']),
										trim($value2['ifscCode']),trim($value2['amount']),trim($value2['refOrdernoDt']),trim($value2['referenceNo']),
										trim($value2['utrNo']),trim($value2['reason']));
									}
								}
							}
							else
							{
								echo 222; die;
								array_push($all_emp_id_arr,"'".$payment_configData['beneficiaryDetail']['refBenfId']."'");
								
								if($payment_configData['beneficiaryDetail']['status']=='Success')
								{
									echo 1111; die;
									//$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=$payment_configData['beneficiaryDetail']['paymentDt'];
									$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array($payment_configData['beneficiaryDetail']['paymentDt'],$payment_configData['beneficiaryDetail']['utrNo']);
								}
								else if($payment_configData['beneficiaryDetail']['status']=='Failure')
								{
									$emp_pay_failure_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array(trim($payment_configData['beneficiaryDetail']['paymentDt']),trim($payment_configData['beneficiaryDetail']['accountNumber']),trim($payment_configData['beneficiaryDetail']['ifscCode']),trim($payment_configData['beneficiaryDetail']['amount']),trim($payment_configData['beneficiaryDetail']['refOrdernoDt']),trim($payment_configData['beneficiaryDetail']['referenceNo']),trim($payment_configData['beneficiaryDetail']['utrNo']),trim($payment_configData['beneficiaryDetail']['reason']));
								}
								
							}
							$all_emp_id_str=implode(",",$all_emp_id_arr);
							
							$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
							
							$cnt_f=0;$cnt_s=0;$ins_sql='';$emp_id_failure_list='';$emp_id_success_list='';
							
							for($h=0;$h<count($emp_id_fetch);$h++)
							{
								foreach($emp_pay_succes_arr as $key4=>$value4)
								{		// For PAYMENT Success
									
									if($emp_id_fetch[$h]['emp_id_const']==$key4)
									{ 
									//echo 12; die;
										/*$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." 
										THEN to_date('".date_format_change($value4)."','YYYY-MM-DD') ";*/
										
										$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value4[0])."','YYYY-MM-DD') ";
										$upd_utrno_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN '".$value4[1]."' ";
										
										$emp_id_success_list.=$emp_id_fetch[$h]['emp_id_pk'];
										if($cnt_s!=count($emp_pay_succes_arr)-1)
										{
											$emp_id_success_list=$emp_id_success_list.",";
										}
										$cnt_s++;
									}
								}
								
								foreach($emp_pay_failure_arr as $key3=>$value3)
								{	// For PAYMENT Failure
									if($emp_id_fetch[$h]['emp_id_const']==$key3)
									{
										$ins_sql.="('".$bill_details_fetch_gp[$i]['block_bill_pk']."','".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
										'".$emp_id_fetch[$h]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."',

										'".$key3."','".$value3[4]."','','".$value3[7]."','".$value3[6]."','10','now();','".$_SERVER['REMOTE_ADDR']."',
										'555','1')";
										
										$emp_id_failure_list.=$emp_id_fetch[$h]['emp_id_pk'];
										
										if($cnt_f!=count($emp_pay_failure_arr)-1)
										{
											$ins_sql=$ins_sql.",";
											$emp_id_failure_list=$emp_id_failure_list.",";
										}
										
										$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN 
															to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
										$cnt_f++;
									}
								}
								
							}
							
							//if($drn_number==$drn)
							//{
								$update_payment_response=$db->update("UPDATE prd_sftp_benf_upload_response
																		SET sftp_benf_response_status='8',
																		sftp_benf_response_recieve_time='now()',
																		voucher_no='".$voucher_no."',
																		voucher_date='".$voucher_dt."',
																		token_no='".$token_no."',
																		token_date='".$token_dt."'
																		WHERE sftp_benf_file_name='".$bill_details_fetch_gp[$i]['sftp_benf_file_name']."'
																		AND sftp_benf_id_pk='".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."'
																		AND bill_id_fk='".$bill_details_fetch_gp[$i]['block_bill_pk']."'
																		AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");
								
								
								$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
																(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
																VALUES
																('".$bill_details_fetch_gp[$i]['sftp_benf_id_pk']."',
																'".$bill_details_fetch_gp[$i]['block_bill_pk']."',
																8,
																'now()',
																'".$_SERVER['REMOTE_ADDR']."')");	
								
								if($update_payment_response && $insert_track)
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
										
										/*$update_salary_save_details_f=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
										AND emp_id_fk in (".$emp_id_failure_list.") ");*/
										if($bill_details_fetch_gp[$i]['requisition_type']=='1002' )
										{									
										
										$update_arc_final_details_f=$db->update(" UPDATE prd_employee_arrear SET
										payment_file_name='".$payment_file_name."',
										payment_status=12,
										payment_date= CASE ".$upd_string_failure." END
										WHERE salary_monthyear='".$file_monthyear."' 
										AND status_flag='3' AND is_saved='1' AND delete_status='1' 
										AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
										}
										else if($bill_details_fetch_gp[$i]['requisition_type']=='1001' || $bill_details_fetch_gp[$i]['requisition_type']=='1003' )
										{
										$update_arc_final_details_f=$db->update(" UPDATE prd_monthly_salary_archive_final SET
										payment_file_name='".$payment_file_name."',
										payment_status=12,
										payment_date= CASE ".$upd_string_failure." END
										WHERE salary_monthyear='".$file_monthyear."' 
										AND status_flag='3' AND is_saved='1' AND delete_status='1' 
										AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
										}
										
										//if($insert_benf_failure_details && $update_salary_save_details_f && $update_arc_final_details_f)
										if($insert_benf_failure_details && $update_arc_final_details_f)
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
										pg_query("BEGIN");
										
										/*$update_salary_save_details_s=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_success_list.") ");*/
									if($bill_details_fetch_gp[$i]['requisition_type']=='1002' )
									{
											
																			
										$update_arc_final_details_s=$db->update(" UPDATE prd_employee_arrear SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_number= CASE ".$upd_utrno_success." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
											
																					AND emp_id_fk in (".$emp_id_success_list.") AND payment_status='0'");
									}
									else if($bill_details_fetch_gp[$i]['requisition_type']=='1001' || $bill_details_fetch_gp[$i]['requisition_type']=='1003' )
									{
										
										echo (" UPDATE prd_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_number= CASE ".$upd_utrno_success." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
											
																					AND emp_id_fk in (".$emp_id_success_list.") AND payment_status is null");die;
										$update_arc_final_details_s=$db->update(" UPDATE prd_monthly_salary_archive_final SET
																					payment_file_name='".$payment_file_name."',
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END,
																					utr_number= CASE ".$upd_utrno_success." END
																					WHERE salary_monthyear='".$file_monthyear."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
											
																					AND emp_id_fk in (".$emp_id_success_list.") AND payment_status is null");
									}
										
										//if($update_salary_save_details_s && $update_arc_final_details_s)
										if($update_arc_final_details_s)
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
					//}
					
					
					else
					{
						echo "No response got from IFMS";

					}
				}
			}
		}
		else
		{
			echo "No files have been uploaded";
		}
	}
	else
	{
		echo "Connection Fails";
	}
}

///////////////////////////////////////////////////////////////////////  GP END ///////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////  PS START ///////////////////////////////////////////////////////////////////////

if($_GET['user']!='gp' && $_GET['user']!='zp')
{
	$party_code='007';
	$sftp_ps='1';
	//$sftp_ps=sftp_login($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps']);
	if($sftp_ps!=0)
	{
	
		$bill_details_fetch_ps=$db->fetch_table(" SELECT 
												bill.block_bill_pk,
												bill.salary_monthyear,
												bill.drn_number,
												sftp.sftp_benf_id_pk,
												sftp.sftp_benf_file_name,
												sftp.sftp_benf_response_status 
											FROM prd_block_bill_details bill
											INNER JOIN prd_sftp_benf_upload_response sftp
											ON bill.block_bill_pk=sftp.bill_id_fk
											WHERE bill.salary_monthyear in ('".$monthyear."') AND bill.status='1' AND ps_id_fk!='0'
											AND sftp.active_status='1' AND sftp.sftp_benf_sending_status='4' and sftp.sftp_benf_file_name='".$filename."'
											AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
			
		if(count($bill_details_fetch_ps)!='0')
		{									
			
			/*$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_002/'; // changes needed as per department //
			$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_003/';
			$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_005/';*/
			
			$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_002/';
			$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_003/';
			$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_005/';
			
			
			
			
			$non_ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_003/');
			$payment_file_name_list =  scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_005/');
			$wrong_data_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_003/');
			$ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_002/');
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			//$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);
			
			$payment_file_arr=file_name_list_payment($payment_file_name_list,'_');	//Modified new
			
			/*$ack_file_name_list = $sftp_ps->nlist($remote_directory_002);
			$non_ack_file_name_list = $sftp_ps->nlist($remote_directory_003);
			$payment_file_name_list =  $sftp_ps->nlist($remote_directory_005);
			$wrong_data_file_name_list = $sftp_ps->nlist($remote_directory_003);
			//print_r($payment_file_name_list);die;
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);*/
			
			
			for($i=0;$i<count($bill_details_fetch_ps);$i++)
			{
				
				////////////////////////////////////////////////////////////////// BEFORE GETTING ACK FILE  /////////////////////////////////////////////////////////////
				
				if($bill_details_fetch_ps[$i]['sftp_benf_response_status']=='')
				{
						
					 $filename_without_ext=substr($bill_details_fetch_ps[$i]['sftp_benf_file_name'],0,28); 
					
					
					
					
						 ////////////////////////////////////  weservice call statrt/////////////////////////////////
				$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_002/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_zip/';
				$username = 'gen007'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'genpanWb!fm507';
				$password = hash("sha512", $password_hash);
				$fileType = 'ACKF';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen007';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_ack = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
				
				
				
				if($ben_upload_ack==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'ACKF',
								now(),
								'".$response."',
								'".$response."'
								)");
				//$ben_upload_ack='1';
				
				if($ben_upload_ack!='1')
				{
					//echo 12; die;
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_zip/';
					$username = 'gen007'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'genpanWb!fm507';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRMF';
					//$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen007';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_format = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					if($ben_upload_wrong_format==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'WRMF',
								now(),
								'".$response."',
								'".$response."'
								)");
				
				//var_dump($ben_upload_wrong_format); die;
				if($ben_upload_wrong_format!='1')
				{
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_zip/';
					$username = 'gen007'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'genpanWb!fm507';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen007';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_data = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					
					
					if($ben_upload_wrong_data==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'WRND',
								now(),
								'".$response."',
								'".$response."'
								)");
				}
				}
					
					
					
					
					
					
					////////////////////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////////////
					
					if(in_array("ACK".$bill_details_fetch_ps[$i]['sftp_benf_file_name'],$ack_file_name_list))
					{
						
						$ack_file_name="ACK".$bill_details_fetch_ps[$i]['sftp_benf_file_name'];
						//////////////////////////////////////// DOWNLOADING ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						/*if($sftp_ps->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
						{*/
							$ack_arry=simplexml_load_file($local_directory_002.$ack_file_name);
							$ack_json  = json_encode($ack_arry);
							$ack_configData = json_decode($ack_json, true);
							
							
							$update_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																SET ifms_reference_number='".$ack_configData['IFMS_REF_NO']."',
																sftp_benf_response_status='5',
																sftp_benf_response_recieve_time='now()'
																WHERE sftp_benf_file_name='".$bill_details_fetch_ps[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_ps[$i]['block_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_ps[$i]['block_bill_pk']."',
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
						//}
					}
					
					//////////////////////////////////////////////////////////// NON-ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					
					else if(in_array($filename_without_ext,$non_ack_file_arr))
					{
						//echo 66;die;	
						 $non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						/*if($sftp_ps->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
						{*/
							$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
							$non_ack_json  = json_encode($non_ack_arry);
							$non_ack_configData = json_decode($non_ack_json, true);
							
							$update_non_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																	SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
																	active_status=2,
																	sftp_benf_response_status='6',
																	sftp_benf_response_recieve_time='now()'
																	WHERE sftp_benf_file_name='".$bill_details_fetch_ps[$i]['sftp_benf_file_name']."'
																	AND sftp_benf_id_pk='".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."'
																	AND bill_id_fk='".$bill_details_fetch_ps[$i]['block_bill_pk']."'
																	AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
							
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_ps[$i]['block_bill_pk']."',
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
						/*}*/
					}
					
					
					///////////////////////////////////////////////////// WRONG DATA FILE CHECKING  ///////////////////////////////////////////////		 
					
					else if(in_array($filename_without_ext,$wrong_data_file_arr))
					{
						
						$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING WRONG DATA FILE AND DATA READING /////////////////////////////////////////////////
						
						/*if($sftp_ps->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
						{*/
								
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
										 $ins_sql.="('".$bill_details_fetch_ps[$i]['block_bill_pk']."','".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."','".$emp_id_fetch[$j]['emp_id_pk']."','".$value3[0]."','".$value3[1]."','".$value3[2]."','".$value3[3]."','".$value3[4]."','".$value3[5]."','".$key3."','".$value3[6]."','".$value3[7]."','".$value3[8]."','','9','now();','".$_SERVER['REMOTE_ADDR']."','555','1')"; 
										
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
																WHERE sftp_benf_file_name='".$bill_details_fetch_ps[$i]['sftp_benf_file_name']."'
																AND sftp_benf_id_pk='".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."'
																AND bill_id_fk='".$bill_details_fetch_ps[$i]['block_bill_pk']."'
																AND sftp_benf_sending_status='4' AND sftp_benf_response_status is null");
																
							$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
															(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
															VALUES
															('".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."',
															'".$bill_details_fetch_ps[$i]['block_bill_pk']."',
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
						/*}*/
					}
					
					else
					{
						echo "No response got from IFMS";
					}
				}
				
				
				////////////////////////////////////////// AFTER GETTING ACK FILE  //////////////////////////////////////
				
				
				else if($bill_details_fetch_ps[$i]['sftp_benf_response_status']=='5' || $bill_details_fetch_ps[$i]['sftp_benf_response_status']=='8')
				{
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_005/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_zip/';
				$username = 'gen007'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'genpanWb!fm507';
				$password = hash("sha512", $password_hash);
				$fileType = 'SUFA';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen007';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_payment_file = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					
					
						if($ben_upload_payment_file==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'SUFA',
								now(),
								'".$response."',
								'".$response."'
								)");
					
					$filename_without_ext=substr($bill_details_fetch_ps[$i]['sftp_benf_file_name'],0,28);
					$drn_number=$bill_details_fetch_ps[$i]['drn_number'];
					$file_monthyear=$bill_details_fetch_ps[$i]['salary_monthyear'];
					$cnt_s=1;$cnt_f=1;
					$all_emp_id_arr=array();
					$emp_pay_succes_arr=array();
					$emp_pay_failure_arr=array();	
					//$next_payment_sequence=payment_sequence_find($filename_without_ext,$file_monthyear,'007');
					
					//$payment_data_file_name='007'.$filename_without_ext.'_'.$next_payment_sequence.'.xml';
					
					$party_code='007';	//Modified new
					//$party_code='10';	//Modified new
					 $filename_without_ext_payment=$party_code.$filename_without_ext;
					
					//////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////
					
					
					if(in_array($filename_without_ext_payment,$payment_file_arr))
					{
					$payment_file_name=file_name_payment($payment_file_name_list,'_',$filename_without_ext_payment);  
						//print_r($payment_file_name);
						/////////////////////////////////// DOWNLOADING PAYMENT FILE AND DATA READING ///////////////////////////////////////
						
						/*if($sftp_ps->get($remote_directory_005.$payment_file_name, $local_directory_005.$payment_file_name))
						{*/	
							
							$payment_arry=simplexml_load_file($local_directory_005.$payment_file_name);
							$payment_json  = json_encode($payment_arry);
							$payment_configData = json_decode($payment_json, true);
							
							$drn=$payment_configData['DRN'];
							$voucher_no=$payment_configData['voucherNo'];
							$voucher_dt=date_format_change($payment_configData['voucherDate']);
							$token_no=$payment_configData['tokenNo'];
							$token_dt=date_format_change($payment_configData['tokenDate']);
							
							
							if(countdim($payment_configData['beneficiaryDetail'])>1)
							{
								foreach($payment_configData['beneficiaryDetail'] as $key2=>$value2)
								{
									array_push($all_emp_id_arr,"'".$value2['refBenfId']."'");
									if($value2['status']=='Success')
									{
										//$emp_pay_succes_arr[$value2['refBenfId']]=$value2['paymentDt'];
										$emp_pay_succes_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['utrNo']));
									}
									else if($value2['status']=='Failure')
									{
										$emp_pay_failure_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['accountNumber']),
										trim($value2['ifscCode']),trim($value2['amount']),trim($value2['refOrdernoDt']),trim($value2['referenceNo']),
										trim($value2['utrNo']),trim($value2['reason']));
									}
								}
							}
							else
							{
								array_push($all_emp_id_arr,"'".$payment_configData['beneficiaryDetail']['refBenfId']."'");
								
								if($payment_configData['beneficiaryDetail']['status']=='Success')
								{
									//$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=$payment_configData['beneficiaryDetail']['paymentDt'];
									$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array($payment_configData['beneficiaryDetail']['paymentDt'],$payment_configData['beneficiaryDetail']['utrNo']);
								}
								else if($payment_configData['beneficiaryDetail']['status']=='Failure')
								{
									$emp_pay_failure_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array(trim($payment_configData['beneficiaryDetail']['paymentDt']),trim($payment_configData['beneficiaryDetail']['accountNumber']),trim($payment_configData['beneficiaryDetail']['ifscCode']),trim($payment_configData['beneficiaryDetail']['amount']),trim($payment_configData['beneficiaryDetail']['refOrdernoDt']),trim($payment_configData['beneficiaryDetail']['referenceNo']),trim($payment_configData['beneficiaryDetail']['utrNo']),trim($payment_configData['beneficiaryDetail']['reason']));
								}
								
							}
							$all_emp_id_str=implode(",",$all_emp_id_arr);
							
							$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
							
							$cnt_f=0;$cnt_s=0;$ins_sql='';$emp_id_failure_list='';$emp_id_success_list='';
							
							for($h=0;$h<count($emp_id_fetch);$h++)
							{
								foreach($emp_pay_succes_arr as $key4=>$value4)
								{		// For PAYMENT Success
									
									if($emp_id_fetch[$h]['emp_id_const']==$key4)
									{ 
										/*$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." 
										THEN to_date('".date_format_change($value4)."','YYYY-MM-DD') ";*/
										
										$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value4[0])."','YYYY-MM-DD') ";
										$upd_utrno_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN '".$value4[1]."' ";
										
										$emp_id_success_list.=$emp_id_fetch[$h]['emp_id_pk'];
										if($cnt_s!=count($emp_pay_succes_arr)-1)
										{
											$emp_id_success_list=$emp_id_success_list.",";
										}
										$cnt_s++;
									}
								}
								
								foreach($emp_pay_failure_arr as $key3=>$value3)
								{	// For PAYMENT Failure
									if($emp_id_fetch[$h]['emp_id_const']==$key3)
									{
										$ins_sql.="('".$bill_details_fetch_ps[$i]['block_bill_pk']."','".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."',
										'".$emp_id_fetch[$h]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."',
										'".$key3."','".$value3[4]."','','".$value3[7]."','".$value3[6]."','10','now();','".$_SERVER['REMOTE_ADDR']."',
										'555','1')";
										
										$emp_id_failure_list.=$emp_id_fetch[$h]['emp_id_pk'];
										
										if($cnt_f!=count($emp_pay_failure_arr)-1)
										{
											$ins_sql=$ins_sql.",";
											$emp_id_failure_list=$emp_id_failure_list.",";
										}
										
										$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN 
															to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
										$cnt_f++;
									}
								}
								
							}
						
							
								$update_payment_response=$db->update("UPDATE prd_sftp_benf_upload_response
																		SET sftp_benf_response_status='8',
																		sftp_benf_response_recieve_time='now()',
																		voucher_no='".$voucher_no."',
																		voucher_date='".$voucher_dt."',
																		token_no='".$token_no."',
																		token_date='".$token_dt."'
																		WHERE sftp_benf_file_name='".$bill_details_fetch_ps[$i]['sftp_benf_file_name']."'
																		AND sftp_benf_id_pk='".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."'
																		AND bill_id_fk='".$bill_details_fetch_ps[$i]['block_bill_pk']."'
																		AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");
								
								
								$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
																(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
																VALUES
																('".$bill_details_fetch_ps[$i]['sftp_benf_id_pk']."',
																'".$bill_details_fetch_ps[$i]['block_bill_pk']."',
																8,
																'now()',
																'".$_SERVER['REMOTE_ADDR']."')");	
								
								if($update_payment_response && $insert_track)
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
										
										/*$update_salary_save_details_f=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_failure_list.") ");*/
									if($bill_details_fetch_ps[$i]['requisition_type']=='1002' )
									{									
									
									$update_arc_final_details_f=$db->update(" UPDATE prd_employee_arrear SET
									payment_file_name='".$payment_file_name."',
									payment_status=12,
									payment_date= CASE ".$upd_string_failure." END
									WHERE salary_monthyear='".$file_monthyear."' 
									AND status_flag='3' AND is_saved='1' AND delete_status='1' 
									AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
									}
									else if($bill_details_fetch_ps[$i]['requisition_type']=='1001' || $bill_details_fetch_ps[$i]['requisition_type']=='1003')
									{									
									
									$update_arc_final_details_f=$db->update(" UPDATE prd_monthly_salary_archive_final SET
									payment_file_name='".$payment_file_name."',
									payment_status=12,
									payment_date= CASE ".$upd_string_failure." END
									WHERE salary_monthyear='".$file_monthyear."' 
									AND status_flag='3' AND is_saved='1' AND delete_status='1' 
									AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
									}
									
										
										//if($insert_benf_failure_details && $update_salary_save_details_f && $update_arc_final_details_f)
										if($insert_benf_failure_details && $update_arc_final_details_f)
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
										pg_query("BEGIN");
										
										/*$update_salary_save_details_s=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_success_list.") ");*/
										
										if($bill_details_fetch_ps[$i]['requisition_type']=='1002' )
										{											
										$update_arc_final_details_s=$db->update(" UPDATE prd_employee_arrear SET
										payment_file_name='".$payment_file_name."',
										payment_status=11,
										payment_date= CASE ".$upd_string_success." END,
										utr_number= CASE ".$upd_utrno_success." END
										WHERE salary_monthyear='".$file_monthyear."' 
										AND status_flag='3' AND is_saved='1' AND delete_status='1' 
										AND emp_id_fk in (".$emp_id_success_list.") AND payment_status='0'");
										}
										else if($bill_details_fetch_ps[$i]['requisition_type']=='1001')
										{
											$update_arc_final_details_s=$db->update(" UPDATE prd_monthly_salary_archive_final SET
										payment_file_name='".$payment_file_name."',
										payment_status=11,
										payment_date= CASE ".$upd_string_success." END,
										utr_number= CASE ".$upd_utrno_success." END
										WHERE salary_monthyear='".$file_monthyear."' 
										AND status_flag='3' AND is_saved='1' AND delete_status='1' 
										AND emp_id_fk in (".$emp_id_success_list.") AND payment_status is null");
										}
										
										//if($update_salary_save_details_s && $update_arc_final_details_s)
										if($update_arc_final_details_s)
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
							/*}*/
							
						}
					}
					else
					{
						echo "No response got from IFMS";

					}
			}
		}
	}
	else
	{
		echo "Connection Fails";
	}
}

///////////////////////////////////////////////////////////////////////  PS END ///////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////  ZP START ///////////////////////////////////////////////////////////////////////

if($_GET['user']!='gp' && $_GET['user']!='ps')
{
	$party_code='008';
	//$sftp_zp=sftp_login($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp']);
	$sftp_zp='1';
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
											WHERE bill.salary_monthyear in ('".$monthyear."') AND bill.status='1' AND zp_id_fk!='0'
											AND sftp.active_status='1' AND sftp.sftp_benf_sending_status='4' and sftp.sftp_benf_file_name='".$filename."' 
											AND (sftp.sftp_benf_response_status not in ('6','7') or sftp.sftp_benf_response_status is null)");
			
		if(count($bill_details_fetch_zp)!='0')
		{									
			
			/*$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_002/'; // changes needed as per department //
			$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_003/';
			$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_005/';*/
			
			$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_002/';
			$local_directory_003 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/';
			$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_005/';
			
			
			
			$non_ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/');
			$payment_file_name_list =  scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_005/');
			$wrong_data_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/');
			$ack_file_name_list = scandir($_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_002/');
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			//$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);
			
			$payment_file_arr=file_name_list_payment($payment_file_name_list,'_');
			
			/*$ack_file_name_list = $sftp_zp->nlist($remote_directory_002);
			$non_ack_file_name_list = $sftp_zp->nlist($remote_directory_003);
			$payment_file_name_list =  $sftp_zp->nlist($remote_directory_005);
			$wrong_data_file_name_list = $sftp_zp->nlist($remote_directory_003);
			
			$non_ack_file_arr=file_name_list($non_ack_file_name_list,'_',28);
			$wrong_data_file_arr=file_name_list($wrong_data_file_name_list,'D',28);
			$payment_data_file_arr=file_name_list($payment_file_name_list,'_',31);*/
			
			
			for($i=0;$i<count($bill_details_fetch_zp);$i++)
			{
				
				////////////////////////////////////////////////////////////////// BEFORE GETTING ACK FILE  /////////////////////////////////////////////////////////////
				
				if($bill_details_fetch_zp[$i]['sftp_benf_response_status']=='')
				{
						
				 $filename_without_ext=substr($bill_details_fetch_zp[$i]['sftp_benf_file_name'],0,28); 
					
					////////////////////////////////////////////////////////////////// ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					
						 
					 ////////////////////////////////////  weservice call statrt/////////////////////////////////
				$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_002/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_zip/';
				$username = 'gen008'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'genzilWb!fm508';
				$password = hash("sha512", $password_hash);
				$fileType = 'ACKF';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen008';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_ack = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
				
				if($ben_upload_ack==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'ACKF',
								now(),
								'".$response."',
								'".$response."'
								)");
				//$ben_upload_ack='1';
				
				if($ben_upload_ack!='1')
				{
					//echo 12; die;
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_zip/';
					$username = 'gen008'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'genzilWb!fm508';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRMF';
					//$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen008';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_format = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
				
				if($ben_upload_wrong_format==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'WRMF',
								now(),
								'".$response."',
								'".$response."'
								)");
				if($ben_upload_wrong_format!='1')
				{
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_003/';
					$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_zip/';
					$username = 'gen008'; 
					//$password_hash = 'Api@123';
					//$password_hash = 'gn@lldp+Api@123';
					$password_hash = 'genzilWb!fm508';
					$password = hash("sha512", $password_hash);
					$fileType = 'WRND';
					$flag='2';
					$zipped_name= $new_name.".zip"; 
					$party_code_api='gen008';
					
					$request_id=request_id_generation($party_code);
					
					
					$ben_upload_wrong_data = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
					
					if($ben_upload_wrong_data==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'WRND',
								now(),
								'".$response."',
								'".$response."'
								)");
				}
				}
					
					
					
				
					
					if(in_array("ACK".$bill_details_fetch_zp[$i]['sftp_benf_file_name'],$ack_file_name_list))
					{
						$ack_file_name="ACK".$bill_details_fetch_zp[$i]['sftp_benf_file_name'];
						//////////////////////////////////////// DOWNLOADING ACK FILE AND DATA READING /////////////////////////////////////////////////
						
						/*if($sftp_zp->get($remote_directory_002.$ack_file_name, $local_directory_002.$ack_file_name))
						{*/
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
						/*}*/
					}
					
					//////////////////////////////////////////////////////////// NON-ACK FILE CHECKING  /////////////////////////////////////////////////////////////
					
					else if(in_array($filename_without_ext,$non_ack_file_arr))
					{
							
						 $non_ack_file_name=file_name($non_ack_file_name_list,'_',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING NON-ACK FILE AND DATA READING /////////////////////////////////////////////////
						/*
						if($sftp_zp->get($remote_directory_003.$non_ack_file_name, $local_directory_003.$non_ack_file_name))
						{*/
							$non_ack_arry=simplexml_load_file($local_directory_003.$non_ack_file_name);
							$non_ack_json  = json_encode($non_ack_arry);
							$non_ack_configData = json_decode($non_ack_json, true);
							
							$update_non_ack_response=$db->update("UPDATE prd_sftp_benf_upload_response
																	SET sftp_benf_response_reason='".$non_ack_configData['REASON']."',
																	active_status=2,
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
						/*}*/
					}
					
					////////////////////////////////////////////// WRONG DATA FILE CHECKING  /////////////////////////////////////////////		 
					
					else if(in_array($filename_without_ext,$wrong_data_file_arr))
					{
						
						$wrong_data_file_name=file_name($wrong_data_file_name_list,'D',$filename_without_ext,28);
						
						//////////////////////////////////////// DOWNLOADING WRONG DATA FILE AND DATA READING /////////////////////////////////////////////////
						
						/*if($sftp_zp->get($remote_directory_003.$wrong_data_file_name, $local_directory_003.$wrong_data_file_name))
						{*/
								
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
						/*}*/
					}
					
					else
					{
						echo "No response got from IFMS";
					}
				}
				
				///////////////////////////////////////////////// AFTER GETTING ACK FILE  ///////////////////////////////////////////////
				
				
				else if($bill_details_fetch_zp[$i]['sftp_benf_response_status']=='5' || $bill_details_fetch_zp[$i]['sftp_benf_response_status']=='8')
				{
					
					$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_005/';
				$local_directory_zip = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_zip/';
				$username = 'gen008'; 
				//$password_hash = 'Api@123';
				//$password_hash = 'gn@lldp+Api@123';
				$password_hash = 'genzilWb!fm508';
				$password = hash("sha512", $password_hash);
				$fileType = 'SUFA';
				$flag='2';
				$zipped_name= $new_name.".zip"; 
				$party_code_api='gen008';
				
				$request_id=request_id_generation($party_code);
				
				
				$ben_upload_payment_file = call_pull($filename, $local_directory,$local_directory_zip, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);
				
				if($ben_upload_payment_file==1)
				{
					$response=1;
					
				}
				else
				{
					$response=2;
				}
				
				$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'SUFA',
								now(),
								'".$response."',
								'".$response."'
								)");
					
					$filename_without_ext=substr($bill_details_fetch_zp[$i]['sftp_benf_file_name'],0,28);
					$drn_number=$bill_details_fetch_zp[$i]['drn_number'];
					$file_monthyear=$bill_details_fetch_zp[$i]['salary_monthyear'];
					$cnt_s=1;$cnt_f=1;
					$all_emp_id_arr=array();
					$emp_pay_succes_arr=array();
					$emp_pay_failure_arr=array();	
					//$next_payment_sequence=payment_sequence_find($filename_without_ext,$file_monthyear,'008');
					
					//$payment_data_file_name='008'.$filename_without_ext.'_'.$next_payment_sequence.'.xml';
					
					$party_code='008';	//Modified new
					//$party_code='10';	//Modified new
					 $filename_without_ext_payment=$party_code.$filename_without_ext; 
					
					
					//////////////////////////////////////////////// PAYMENT FILE CHECKING  /////////////////////////////////////////////
					
					
					if(in_array($filename_without_ext_payment,$payment_file_arr))
					{
						  $payment_file_name=file_name_payment($payment_file_name_list,'_',$filename_without_ext_payment);  
						 
						// print_r($payment_file_name) ;
						
						/////////////////////////////////// DOWNLOADING PAYMENT FILE AND DATA READING ///////////////////////////////////////
						
						/*if($sftp_zp->get($remote_directory_005.$payment_file_name, $local_directory_005.$payment_file_name))
						{*/	
							
							$payment_arry=simplexml_load_file($local_directory_005.$payment_file_name);
							$payment_json  = json_encode($payment_arry);
							$payment_configData = json_decode($payment_json, true);
							
							$drn=$payment_configData['DRN'];
							$voucher_no=$payment_configData['voucherNo'];
							$voucher_dt=date_format_change($payment_configData['voucherDate']);
							$token_no=$payment_configData['tokenNo'];
							$token_dt=date_format_change($payment_configData['tokenDate']);
							
							
							if(countdim($payment_configData['beneficiaryDetail'])>1)
							{
								foreach($payment_configData['beneficiaryDetail'] as $key2=>$value2)
								{
									array_push($all_emp_id_arr,"'".$value2['refBenfId']."'");
									if($value2['status']=='Success')
									{
										//$emp_pay_succes_arr[$value2['refBenfId']]=$value2['paymentDt'];
										$emp_pay_succes_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['utrNo']));
									}
									else if($value2['status']=='Failure')
									{
										$emp_pay_failure_arr[$value2['refBenfId']]=array(trim($value2['paymentDt']),trim($value2['accountNumber']),
										trim($value2['ifscCode']),trim($value2['amount']),trim($value2['refOrdernoDt']),trim($value2['referenceNo']),
										trim($value2['utrNo']),trim($value2['reason']));
									}
								}
							}
							else
							{
								array_push($all_emp_id_arr,"'".$payment_configData['beneficiaryDetail']['refBenfId']."'");
								
								if($payment_configData['beneficiaryDetail']['status']=='Success')
								{
									//$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=$payment_configData['beneficiaryDetail']['paymentDt'];
									$emp_pay_succes_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array($payment_configData['beneficiaryDetail']['paymentDt'],$payment_configData['beneficiaryDetail']['utrNo']);
								}
								else if($payment_configData['beneficiaryDetail']['status']=='Failure')
								{
									$emp_pay_failure_arr[$payment_configData['beneficiaryDetail']['refBenfId']]=array(trim($payment_configData['beneficiaryDetail']['paymentDt']),trim($payment_configData['beneficiaryDetail']['accountNumber']),trim($payment_configData['beneficiaryDetail']['ifscCode']),trim($payment_configData['beneficiaryDetail']['amount']),trim($payment_configData['beneficiaryDetail']['refOrdernoDt']),trim($payment_configData['beneficiaryDetail']['referenceNo']),trim($payment_configData['beneficiaryDetail']['utrNo']),trim($payment_configData['beneficiaryDetail']['reason']));
								}
								
							}
							
							$all_emp_id_str=implode(",",$all_emp_id_arr);
							
							$emp_id_fetch=$db->fetch_table("SELECT emp_id_pk,emp_id_const FROM prd_employee_master WHERE emp_id_const in (".$all_emp_id_str.")");
							
							$cnt_f=0;$cnt_s=0;$ins_sql='';$emp_id_failure_list='';$emp_id_success_list='';
							
							for($h=0;$h<count($emp_id_fetch);$h++)
							{
								foreach($emp_pay_succes_arr as $key4=>$value4)
								{		// For PAYMENT Success
									
									if($emp_id_fetch[$h]['emp_id_const']==$key4)
									{ 
										/*$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." 
										THEN to_date('".date_format_change($value4)."','YYYY-MM-DD') ";*/
										
										$upd_string_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN to_date('".date_format_change($value4[0])."','YYYY-MM-DD') ";
										$upd_utrno_success.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN '".$value4[1]."' ";
										
										$emp_id_success_list.=$emp_id_fetch[$h]['emp_id_pk'];
										if($cnt_s!=count($emp_pay_succes_arr)-1)
										{
											$emp_id_success_list=$emp_id_success_list.",";
										}
										$cnt_s++;
									}
								}
								
								foreach($emp_pay_failure_arr as $key3=>$value3)
								{	// For PAYMENT Failure
									if($emp_id_fetch[$h]['emp_id_const']==$key3)
									{
										$ins_sql.="('".$bill_details_fetch_zp[$i]['block_bill_pk']."','".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."',
										'".$emp_id_fetch[$h]['emp_id_pk']."','".$value3[5]."','','".$value3[1]."','".$value3[2]."','0','".$value3[3]."',
										'".$key3."','".$value3[4]."','','".$value3[7]."','".$value3[6]."','10','now();','".$_SERVER['REMOTE_ADDR']."',
										'555','1')";
										
										$emp_id_failure_list.=$emp_id_fetch[$h]['emp_id_pk'];
										
										if($cnt_f!=count($emp_pay_failure_arr)-1)
										{
											$ins_sql=$ins_sql.",";
											$emp_id_failure_list=$emp_id_failure_list.",";
										}
										
										$upd_string_failure.="WHEN emp_id_fk = ".$emp_id_fetch[$h]['emp_id_pk']." THEN 
															to_date('".date_format_change($value3[0])."','YYYY-MM-DD') ";
										$cnt_f++;
									}
								}
								
							}
							
							//if($drn_number==$drn)
							//{
								$update_payment_response=$db->update("UPDATE prd_sftp_benf_upload_response
																		SET sftp_benf_response_status='8',
																		sftp_benf_response_recieve_time='now()',
																		voucher_no='".$voucher_no."',
																		voucher_date='".$voucher_dt."',
																		token_no='".$token_no."',
																		token_date='".$token_dt."'
																		WHERE sftp_benf_file_name='".$bill_details_fetch_zp[$i]['sftp_benf_file_name']."'
																		AND sftp_benf_id_pk='".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."'
																		AND bill_id_fk='".$bill_details_fetch_zp[$i]['block_bill_pk']."'
																		AND sftp_benf_sending_status='4' AND sftp_benf_response_status='5'");
								
								
								$insert_track=$db->insert("INSERT INTO prd_ifms_transaction_tracking
																(sftp_benf_id_fk,bill_id_fk,status,entry_time,entry_ip) 
																VALUES
																('".$bill_details_fetch_zp[$i]['sftp_benf_id_pk']."',
																'".$bill_details_fetch_zp[$i]['block_bill_pk']."',
																8,
																'now()',
																'".$_SERVER['REMOTE_ADDR']."')");	
								
								if($update_payment_response && $insert_track)
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
										
										/*$update_salary_save_details_f=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=12,
																					payment_date= CASE ".$upd_string_failure." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_failure_list.") ");*/
												if($bill_details_fetch_zp[$i]['requisition_type']=='1002' )
												{											
												
												$update_arc_final_details_f=$db->update(" UPDATE prd_employee_arrear SET
												payment_file_name='".$payment_file_name."',
												payment_status=12,
												payment_date= CASE ".$upd_string_failure." END
												WHERE salary_monthyear='".$file_monthyear."' 
												AND status_flag='3' AND is_saved='1' AND delete_status='1' 
												AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
												}
												else if($bill_details_fetch_zp[$i]['requisition_type']=='1001' || $bill_details_fetch_zp[$i]['requisition_type']=='1003' )
												{
												$update_arc_final_details_f=$db->update(" UPDATE prd_monthly_salary_archive_final SET
												payment_file_name='".$payment_file_name."',
												payment_status=12,
												payment_date= CASE ".$upd_string_failure." END
												WHERE salary_monthyear='".$file_monthyear."' 
												AND status_flag='4' AND is_saved='1' AND delete_status='1' 
												AND emp_id_fk in (".$emp_id_failure_list.") AND payment_status!=11");
												}
										
										//if($insert_benf_failure_details && $update_salary_save_details_f && $update_arc_final_details_f)
										if($insert_benf_failure_details && $update_arc_final_details_f)
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
										pg_query("BEGIN");
										
										/*$update_salary_save_details_s=$db->update(" UPDATE prd_employee_salary_save SET
																					payment_status=11,
																					payment_date= CASE ".$upd_string_success." END
																					WHERE salary_monthyear='".date('Ym')."' 
																					AND status_flag='3' AND is_saved='1' AND delete_status='1' 
																					AND emp_id_fk in (".$emp_id_success_list.") ");*/
									if($bill_details_fetch_zp[$i]['requisition_type']=='1002' )
									{
									
									$update_arc_final_details_s=$db->update(" UPDATE prd_employee_arrear SET
									payment_file_name='".$payment_file_name."',
									payment_status=11,
									payment_date= CASE ".$upd_string_success." END,
									utr_number= CASE ".$upd_utrno_success." END
									WHERE salary_monthyear='".$file_monthyear."' 
									AND status_flag='3' AND is_saved='1' AND delete_status='1' 
									AND emp_id_fk in (".$emp_id_success_list.") AND payment_status='0'");
									}
									else if($bill_details_fetch_zp[$i]['requisition_type']=='1001' || $bill_details_fetch_zp[$i]['requisition_type']=='1003' )
									{
										$update_arc_final_details_s=$db->update(" UPDATE prd_monthly_salary_archive_final SET
									payment_file_name='".$payment_file_name."',
									payment_status=11,
									payment_date= CASE ".$upd_string_success." END,
									utr_number= CASE ".$upd_utrno_success." END
									WHERE salary_monthyear='".$file_monthyear."' 
									AND status_flag='4' AND is_saved='1' AND delete_status='1' 
									AND emp_id_fk in (".$emp_id_success_list.") AND payment_status is null");
									}
										
										//if($update_salary_save_details_s && $update_arc_final_details_s)
										if($update_arc_final_details_s)
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
							//}
							
						/*}*/
					}
					
					
					else
					{
						echo "No response got from IFMS";

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