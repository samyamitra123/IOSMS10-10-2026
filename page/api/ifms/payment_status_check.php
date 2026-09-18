<?php
set_time_limit(0);
session_start();
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
$db = new database();

$i=1;
	
	for($j=2017;$j<=date('Y');$j++)
{
		$number='002';
		$starting_year=(date('Y')-2017);
		$c=$starting_year;
		$a = sprintf("%06d", $c);
		$drn_sequence_number=date('Ym').$number.$a ;
	
}

	$url = "http://192.168.1.254/epension/payment_check.php";

        $data = array (
            'drn' => $drn_sequence_number
            );
            
        $params = '';
        foreach($data as $key=>$value)
                    $params .= $key.'='.$value;
             
        $params = trim($params); 

        $ch = curl_init();
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Following line is compulsary to add as it is:
		 curl_setopt($ch, CURLOPT_POSTFIELDS,"payment_status=" .$params); //parameters data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		 curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = curl_exec($ch);
        curl_close($ch);
	//print_r($result) ;
       //print_r($result);
		
	$xml=simplexml_load_string($result);
	/*$array = json_decode(json_encode((array)$xml), true);
	$array = array($xml->getName() => $array);
	print_r($array);*/
		$json  = json_encode($xml);
		$configData = json_decode($json, true);
		print_r($configData);
		
	
	
	
		 
/*$data['BENEFICIARY_DETAILS'] = array('apple','banana','cherry');
$data['animals'] = array('dog', 'elephant');
 json_encode($data);
*/
$i=0;
 $configData['ERROR'];
 $configData['ERROR_MESSAGE'];
 
	 foreach($configData['BENEFICIARY_DETAILS'] as $key=>$value)
		{
					
			
			foreach($value['ACCOUNT_NO'] as $key1=>$value1)
			{
				if($value['ORDER_NO'][$i]==Array())
					{
						
						$order_no=NULL;
					}
					else
					{
						$order_no=$value['ORDER_NO'][$i];
					}
					if($value['UTR_NO'][$i]==Array())
					{
						$utr_no=NULL;
					}
					else
					{
						$utr_no=$value['UTR_NO'][$i];
					}
					if($value['REASON'][$i]==Array())
					{
						$reason=NULL;
					}
					else
					{
						$reason=$value['REASON'][$i];
					}
				
				$id_check=$db->fetch_table("select id from prd_ifms_payment_status_success_failure where payment_month_year= '".date(Ym)."' and drn_no='".$drn_sequence_number."' and id='".$value['ID'][$i]."'");
				
			//--success_failed_status=>0 for new entry---if payment_status='F' and after modified success_failed_status=>1 ---After modification data can send then success_failed_status=>2---//
				if( $id_check[0]['id']!=$value['ID'][$i])
				{
					
				$insart_payment_status=$db->insert("INSERT INTO prd_ifms_payment_status_success_failure
		(drn_no, reference_no,acc_no ,ifsc_code,amount,payment_status,id,order_no,utr_no,reason,payment_month_year,payment_date,entry_time,success_failed_status)VALUES('".$configData['DRN_NO']."','".$configData['IFMS_REF_NO']."','".$value['ACCOUNT_NO'][$i]."','".$value['IFSC_CODE'][$i]."','".$value['AMOUNT'][$i]."','".$value['PAYMENT_STATUS'][$i]."','".$value['ID'][$i]."','".$order_no."','".$utr_no."','".$reason."','".date(Ym)."',
		'".$value['PAYMENT_DATE'][$i]."',now(),'1') ");
				$i=$i+1;
				$k=1;
				}
				else
				{
					
					$update_payment_status=$db->update("UPDATE prd_ifms_payment_status_success_failure SET
													drn_no='".$configData['DRN_NO']."',
													reference_no='".$configData['IFMS_REF_NO']."',
													acc_no='".$value['ACCOUNT_NO'][$i]."',
													ifsc_code='".$value['IFSC_CODE'][$i]."',
													amount='".$value['AMOUNT'][$i]."',
													payment_status='".$value['PAYMENT_STATUS'][$i]."',
													id='".$value['ID'][$i]."',
													order_no='".$order_no."',
													utr_no='".$utr_no."',
													reason='".$reason."',
													payment_month_year='".date(Ym)."',
													payment_date='".$value['PAYMENT_DATE'][$i]."',
													entry_time=now(),
													success_failed_status='1'
													where payment_month_year= '".date(Ym)."' and drn_no='".$drn_sequence_number."' and id='".$value['ID'][$i]."'
													");
				}
				
			}
			
			if($k!='1')
			{
				$id_check=$db->fetch_table("select id from prd_ifms_payment_status_success_failure where payment_month_year= '".date(Ym)."' and drn_no='".$drn_sequence_number."' and id='".$value['ID']."'");
				
				//--success_failed_status=>0 for new entry---if payment_status='F' and after modified success_failed_status=>1 ---After modification data can send then success_failed_status=>2---//
				
						if($value['ORDER_NO']==Array())
						{
							
								$order_no=NULL;
						}
						else
						{
								$order_no=$value['ORDER_NO'];
						}
						if($value['UTR_NO']==Array())
						{
								$utr_no=NULL;
						}
						else
						{
								$utr_no=$value['UTR_NO'];
						}
						if($value['REASON']==Array())
						{
								$reason=NULL;
						}
						else
						{
								$reason=$value['REASON'];
						}
				
				if( $id_check[0]['id']!=$value['ID'])
				{
						
				$insart_payment_status=$db->insert("INSERT INTO prd_ifms_payment_status_success_failure
		(drn_no, reference_no,acc_no ,ifsc_code,amount,payment_status,id,order_no,utr_no,reason,payment_month_year,payment_date,entry_time,success_failed_status)VALUES('".$configData['DRN_NO']."','".$configData['IFMS_REF_NO']."','".$value['ACCOUNT_NO']."','".$value['IFSC_CODE']."','".$value['AMOUNT']."','".$value['PAYMENT_STATUS']."','".$value['ID']."','".$order_no."','".$utr_no."','".$reason."','".date(Ym)."',
		'".$value['PAYMENT_DATE']."',now(),'1') ");
				}
				else
				{
					
					$update_payment_status=$db->update("UPDATE prd_ifms_payment_status_success_failure SET
													drn_no='".$configData['DRN_NO']."',
													reference_no='".$configData['IFMS_REF_NO']."',
													acc_no='".$value['ACCOUNT_NO']."',
													ifsc_code='".$value['IFSC_CODE']."',
													amount='".$value['AMOUNT']."',
													payment_status='".$value['PAYMENT_STATUS']."',
													id='".$value['ID']."',
													order_no='".$order_no."',
													utr_no='".$utr_no."',
													reason='".$reason."',
													payment_month_year='".date(Ym)."',
													payment_date='".$value['PAYMENT_DATE']."',
													entry_time=now(),
													success_failed_status='1'
													 where payment_month_year= '".date(Ym)."' and drn_no='".$drn_sequence_number."' and id='".$value['ID']."'
													");
				}
				
			}
			
		}
		
			
		
		
?>