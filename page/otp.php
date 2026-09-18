<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

require_once '../includes/config/config.php';
require_once '../includes/config/database.config.php';
require_once'../includes/library/database.class.php';
require_once '../includes/library/cryptography.class.php';
//require_once '../../all_function/fun_store/zp_ps_gp_function.php';


session_start();


function send_sms($message_text,$numbers){

    $url = 'https://enterprise.cloudsvas.com/api/sendsms?route=Transactional&senderid=SSTECH&message='.$message_text.'&mobilenumber='.$numbers.'&userid=demo&password=demo';
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    if(curl_exec($curl) === false){
        
        $return_data = array('status' => 0,'msg' => curl_error($curl));
        
    }
    else{

        $return_data = array('status' => 1,'msg' => 'Message Sent Successfully');

    }
    curl_close($curl);
    return $return_data;
}


$db = new database();
			
			
                $emp_id=$_POST['emp_id']; 
                $mob_number=$_POST['mob_number']; 
                $action=$_POST['action']; 
				//var_dump($action); die;
				
				$db = new database();
				$check=$db->fetch_table(" SELECT emp_id_const, emp_mobile_no FROM prd_employee_login WHERE emp_id_const='" . $emp_id . "' AND emp_mobile_no= '".$mob_number."'");
				
			if($action == 'send_otp'){
				if($check[0]['emp_id_const']=='' && $check[0]['emp_mobile_no'] =='')
				{
					
					
						 
					$mobile_number_db = $db->fetch_table(" SELECT emp_id_const,emp_mobile_no, emp_status, gp_id_fk, ps_id_fk, zp_id_fk FROM prd_employee_master WHERE emp_id_const='" . $emp_id . "' AND emp_mobile_no= '".$mob_number."'");
					//$mobile_number = '91'.$mobile_number_db[0]['emp_mobile_no']; 
					$mobile_number = substr_replace($mobile_number_db[0]['emp_mobile_no'],XXXXXXXX,0,8); 
					
					if($mobile_number_db[0]['zp_id_fk']== '')
					{
						$mobile_number_db[0]['zp_id_fk'] =0;
						
					}
				   // var_dump($mobile_number_db[0]['emp_id_const']); die;
					/*$apiKey = urlencode('NjI0YTc4NzM0ZTU1Mzg3NzY0MzQ2ODMzNDI1NzRmNjg=');
					$Textlocal = new Textlocal(false, false, $apiKey);
					//$numbers=array(918420953988);
				   $numbers = array(
						$mobile_number
					);
					//print_r( $numbers); die;
					 $sender =  urlencode('TXTLCL'); */
					//$otp = rand(100000, 999999);
					//$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
					//$otp =  substr(str_shuffle($str_result), 0, 4);
					//$otp='123456';
					$otp='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
					$hashedOTP = hash('sha256', $otp);
					
					
					//$_SESSION['user_info']['session_otp'] = $hashedOTP;
					//$message = rawurlencode('This is your message'.'-'.$otp); 
					
					$numbers = urlencode("$mobile_number_db[0]['emp_mobile_no']");
					$message_text=rawurlencode("Dear Anjan, your iOSMS employee login PIN is:".$otp);
					//$result = send_sms($message_text,$numbers);
					
					//var_dump($hashedOTP); die;
					//echo $mobile_number_db[0]['emp_mobile_no'].'------'.$mob_number; die;
					if(($mobile_number_db[0]['emp_id_const'] != "" || $mobile_number_db[0]['emp_id_const'] != NULL) && $mobile_number_db[0]['emp_mobile_no'] == $mob_number){
						
						
					//echo 44; die;
					$db = new database();
						/*$insert_otp = $db->insert("
							INSERT INTO
								prd_employee_login (
									emp_id_const,
									otp,
									otp_date,
									emp_status,
									emp_mobile_no,
									gp_id_fk,
									ps_id_fk,
									zp_id_fk,
									stake_level_id_fk
								)
								VALUES (
									'".$emp_id."',
									'".$hashedOTP."',
									now(),
									'".$mobile_number_db[0]['emp_status']."',
									'".$mobile_number_db[0]['emp_mobile_no']."',
									'".$mobile_number_db[0]['gp_id_fk']."',
									'".$mobile_number_db[0]['ps_id_fk']."',
									'".$mobile_number_db[0]['zp_id_fk']."',
									'66'
									);
						");*/
						
						//$message = 'An PIN has been sent to your mobile number.'; 
						$message = 'Please Enter your PIN/ Password.'; 
						echo json_encode(array($message,2));
						 }
						 else
						 { 
							//echo 777; die;
							/*
							$db = new database();

							$update_otp = $db->update("UPDATE prd_employee_login 
							SET otp_date = now(), otp = '".$hashedOTP."', emp_status='".$mobile_number_db[0]['emp_status']."', gp_id_fk='".$mobile_number_db[0]['gp_id_fk']."', ps_id_fk= '".$mobile_number_db[0]['ps_id_fk']."', zp_id_fk= '".$mobile_number_db[0]['zp_id_fk']."'
							WHERE emp_id_const='".$emp_id."'");	*/
							
							$message = 'Please enter correct Employee ID and Phone Number.'; 
							echo json_encode(array($message,3));
							
							//echo $otp;
							//echo $mobile_number_db[0]['emp_mobile_no'];
						 }
				}
				else{
					//echo 12222; die;
					$message = 'Please enter your Password.'; 
					$otp='123456';
					echo json_encode(array($message,1));
				}
			}
			
			else if($action == 'forget_PIN'){
				
				$mobile_number_db = $db->fetch_table(" SELECT emp_id_const,emp_mobile_no, emp_status, gp_id_fk, ps_id_fk, zp_id_fk FROM prd_employee_master WHERE emp_id_const='" . $emp_id . "' AND emp_mobile_no= '".$mob_number."'");
					//$mobile_number = '91'.$mobile_number_db[0]['emp_mobile_no']; 
					$mobile_number = substr_replace($mobile_number_db[0]['emp_mobile_no'],XXXXXXXX,0,8); 
					
					if($mobile_number_db[0]['zp_id_fk']== '')
					{
						$mobile_number_db[0]['zp_id_fk'] =0;
						
					}
				   // var_dump($mobile_number_db[0]['emp_id_const']); die;
					/*$apiKey = urlencode('NjI0YTc4NzM0ZTU1Mzg3NzY0MzQ2ODMzNDI1NzRmNjg=');
					$Textlocal = new Textlocal(false, false, $apiKey);
					//$numbers=array(918420953988);
				   $numbers = array(
						$mobile_number
					);
					//print_r( $numbers); die;
					 $sender =  urlencode('TXTLCL'); */
					//$otp = rand(100000, 999999);
					//$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
					//$otp =  substr(str_shuffle($str_result), 0, 4);
					$otp='654321';
					$hashedOTP = hash('sha256', $otp);
					
					$numbers = urlencode("$mobile_number_db[0]['emp_mobile_no']");
					$message_text=rawurlencode("Dear Anjan, your iOSMS employee login PIN is:".$otp);
					//$result = send_sms($message_text,$numbers);
					
					


				//echo '<pre>';print_r($result);die;	

					//$_SESSION['user_info']['session_otp'] = $hashedOTP;
					//$message = rawurlencode('This is your message'.'-'.$otp); 
					
					//var_dump($result); die;
					
							$db = new database();

							$update_otp = $db->update("UPDATE prd_employee_login 
							SET otp_date = now(), otp = '".$hashedOTP."', emp_status='".$mobile_number_db[0]['emp_status']."', gp_id_fk='".$mobile_number_db[0]['gp_id_fk']."', ps_id_fk= '".$mobile_number_db[0]['ps_id_fk']."', zp_id_fk= '".$mobile_number_db[0]['zp_id_fk']."'
							WHERE emp_id_const='".$emp_id."'");	
							
							$message = 'An PIN has been sent to your mobile number.'; 
							echo json_encode(array($result,2));
			}
?>