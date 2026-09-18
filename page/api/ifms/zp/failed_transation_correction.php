<?php
set_time_limit(0);
session_start();
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
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

/*$drn_check_reference=$db->fetch_table("select bill.block_bill_pk,ifms_id_pk,bill.salary_monthyear,ref.drn_number,ref.block_bill_fk from prd_block_bill_details bill
			inner join prd_ifms_bill_reference ref on bill.block_bill_pk=ref.block_bill_fk
			where 
			bill.salary_monthyear= '201805' 
			and ref.drn_number='".$configData['DRN']."' 
			and bill_sending_status='2' 
			and status='1' 
			and bill.block_code='3299001'");*/

			
	$bill_gen=$db->fetch_table("select block.drn_number,
	    ref.drn_number,ref.ifms_id_pk
from 
 prd_block_bill_details block
			inner join prd_ifms_bill_reference ref on block.block_bill_pk=ref.block_bill_fk and block.salary_monthyear=ref.salary_monthyear
			where 
			block.block_code='3299001'
			and block.requisition_type='1001'
			and block.salary_monthyear='201805'
			and ref.bill_sending_status='2'
			and block.status='1'
			");	


$drn_check_reference=$db->fetch_table("select bill.beneficiary_id,
    bill.emp_id_fk,
    bill.account_no,
    bill.ifs_code,
    bill.current_account_no,
    bill.current_ifsc_code,
	chk.drn_number,
	chk.ifms_ref_no
 from prd_ifms_bill_status_check chk
 inner join prd_ifms_bill_status_check_employee_benf bill on chk.status_check_id_pk=bill.status_check_id_fk and bill.confirm_status='2'
 
			where 
			   chk.check_status='2'   
			 and chk.drn_number='".$bill_gen[0]['drn_number']."'
			");
// where block_code='".$_SESSION['location']['block_code']."'"

$m=0;
foreach($drn_check_reference as $checking_benf_id)
		{
  if($m==0){  
 
	  $benf_id_check1.=$checking_benf_id['beneficiary_id'];
	  $benf_id_check.="'".$benf_id_check1."'";
  }else{
$id_benf=$checking_benf_id['beneficiary_id'];
$id_benf_a="'".$id_benf."'";
       $benf_id_check.=",".$id_benf_a;
  }

    $m=$m+1;
	
	
}


$failed_account_checking=$db->fetch_table("select drn_no,reference_no,acc_no,ifsc_code,current_account_no,current_ifsc_code from prd_ifms_payment_status_success_failure
			where 
			drn_no='".$bill_gen[0]['drn_number']."'
			and success_failed_status='2'
			 and id not in (".$benf_id_check." )
			");


		
			
		$xml =  new DOMDocument("1.0","UTF-8");
		$container = $xml->createElement('FAILED_TRANSACTION_CORRECTION');
		$container = $xml->appendChild($container);
		
		$row = $xml->createElement('DRN',$bill_gen[0]['drn_number']);
		$row = $container->appendChild($row);
		
		$ifms = $xml->createElement('IFMS_REF_NO',$drn_check_reference[0]['ifms_ref_no']);
		$ifms = $container->appendChild($ifms);
		
		$name = $xml->createElement('MODE','I');
		$name = $container->appendChild($name);
		
		
		foreach($drn_check_reference as $checking_id)
		{
		     
          
                $_SESSION["account_no"] =$checking_id['account_no'];
				$_SESSION["ifs_code"] = $checking_id['ifs_code'];
				$_SESSION["current_account_no"] = $checking_id['current_account_no'];
				$_SESSION["current_ifsc_code"] = $checking_id['current_ifsc_code'];
                $_SESSION["drn_number"] = $checking_id['drn_no'];
           
    
           
        //return $arr;

			
			
			
			
		$ben = $xml->createElement('BENEFICIARY_DETAILS');
		$ben = $container->appendChild($ben);
	
		$account = $xml->createElement('PREV_ACCT_NO',$checking_id['account_no']);
		$account  = $ben->appendChild($account);
		
		$ifsc = $xml->createElement('PREV_IFSC_CODE',$checking_id['ifs_code']);
		$ifsc  = $ben->appendChild($ifsc);
		
		$account1 = $xml->createElement('CURR_ACCT_NO',$checking_id['current_account_no']);
		$account1  = $ben->appendChild($account1);
		
		$ifsc1 = $xml->createElement('CURR_IFSC_CODE',$checking_id['current_ifsc_code']);
		$ifsc1  = $ben->appendChild($ifsc1);
		}
		
		foreach($failed_account_checking as $checking_failed)
		{
			
			 $_SESSION["account_no"] =$checking_failed['account_no'];
				$_SESSION["ifs_code"] = $checking_failed['ifs_code'];
				$_SESSION["current_account_no"] = $checking_failed['current_account_no'];
				$_SESSION["current_ifsc_code"] = $checking_failed[0]['current_ifsc_code'];
				 $_SESSION["drn_number"] = $checking_failed['drn_number'];
                $_SESSION["beni_id"] = $checking_failed['current_ifsc_code'];
		$ben = $xml->createElement('BENEFICIARY_DETAILS');
		$ben = $container->appendChild($ben);
	
		$account = $xml->createElement('PREV_ACCT_NO',$checking_failed['account_no']);
		$account  = $ben->appendChild($account);
		
		$ifsc = $xml->createElement('PREV_IFSC_CODE',$checking_failed['ifs_code']);
		$ifsc  = $ben->appendChild($ifsc);
		
		$account1 = $xml->createElement('CURR_ACCT_NO',$checking_failed['current_account_no']);
		$account1  = $ben->appendChild($account1);
		
		$ifsc1 = $xml->createElement('CURR_IFSC_CODE',$checking_failed['current_ifsc_code']);
		$ifsc1  = $ben->appendChild($ifsc1);
		}
		
		
		header('Content-Type: text/xml');
		$input_xml =$xml->saveXML();
	
		$url = "http://192.168.1.254/epension/failed_transation.php"; 
		
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Following line is compulsary to add as it is:
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		"xmlRequest=" . $input_xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		 $data = curl_exec($ch);
		curl_close($ch);
		
		//convert the XML result into array
		//$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
		//$array_data = simplexml_load_string($data);
		

		echo $data;
		
		$arry=simplexml_load_string($data);
		$json  = json_encode($arry);
		$configData = json_decode($json, true);
		print_r($configData);
		
		if($configData['RESPONSE']==0)
		{ 
			
			$update_bill_status=$db->update("UPDATE prd_ifms_bill_status_check_employee_benf SET
													confirm_status='3',response_code='".$configData['RESPONSE']."'
													where 
													beneficiary_id IN($benf_id_check)
													and confirm_status='2'
													");
									
			$update_bill_status=$db->update("UPDATE prd_ifms_payment_status_success_failure SET
			success_failed_status='3',response_code='0'
			where 
			id IN($benf_id_check)
			and success_failed_status='2'
			");
											
		}
		else
		{
			$update_bill_status=$db->update("UPDATE prd_ifms_bill_status_check_employee_benf SET
													 confirm_status='4',response_code='".$configData['RESPONSE']."'
													where 
													beneficiary_id IN($benf_id_check)
													and confirm_status='2'
													");
									
			$update_bill_status=$db->update("UPDATE prd_ifms_payment_status_success_failure SET
		   success_failed_status='4',response_code='".$configData['RESPONSE']."'
			where 
			id IN($benf_id_check)
			and success_failed_status='2'
			");
		}
		
		//convert the XML result into array
		//$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
		//$array_data = simplexml_load_string($data);
		

		
		
?>