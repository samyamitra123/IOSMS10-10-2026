<?php

set_time_limit(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../includes/config/config.php';
require '../includes/config/database.config_api.php';
require '../includes/library/database.class.php';
//$db=new database();	
require_once("Rest.inc.php");

class API extends REST 
{
	public $data = "";
	//public $_allowed_ip = array('192.168.1.26' ,'192.168.1.10');
	public $_service_id = array('7458464258');
	public $_access_key = array('TRfefeJLVHTWOPSDQThjyklpx789rs');
	
	/*const DB_SERVER = "192.168.1.26";
	const DB_USER = "postgres";
	const DB_PASSWORD = "postgres";
	const DB_NAME = "prd_db";*/
	
	private $db = NULL;

public function __construct() 
{
	parent::__construct();    // Init parent contructor
//$this->dbConnect();					// Initiate Database connection
}




private function dbClose($connection) 
{
	pg_close($connection);
}

/*private function checkConnection() 
{
	$remoteIP = $this->get_remote_ip();
	if (in_array($remoteIP, $this->_allowed_ip))
	{
		return TRUE;
	} 
	else
	{
		return FALSE;
	}
}*/

private function checkServiceID($serviceID) 
{
	if (in_array($serviceID, $this->_service_id)) 
	{
		return TRUE;
	} 
	else
	{
		return FALSE;
	}
}

private function checkType($type) 
{
	if ($type == 'N' || $type == 'M')
	 {
		return TRUE;
	 } 
	else
	{
		return FALSE;
	}
}

private function checkMethod($method)
{
	if ($method == 'RETRIEVE' || $method == 'RESPONSE')
	{
		return TRUE;
	} 
	else
	{
		return FALSE;
	}
}

private function checkAccessKey($accesskey) 
{
	if (in_array($accesskey, $this->_access_key))
	{
		return TRUE;
	} 
	else
	{
		return FALSE;
	}
}

private function checkTarget($target)
{
	if ($target == 'GP' || $target == 'PS' || $target == 'ZP') 
	{
		return TRUE;
	} 
	else 
	{
		return FALSE;
	}
}

/*private function dateshow_slash($dateval) 
{
	$date = substr($dateval, 0, 10);
	if ($date == '')
	{
		return '';
	}
	if ($date == '0001-01-01') 
	{
		return '';
	}
	$datearr = explode('-', $date);
	$dob = $datearr['0'] . '-' . $datearr['1'] . '-' . $datearr['2'];
	return $dob == '' ? '' : $dob;
}*/

/*private function checkRequest($request_array) 
{
//print_r($request_array);die;

		$input_request_id = $request_array['request_id'];
		$input_service_id = $request_array['sender_id'];
		$input_target = $request_array['tarGet']; 
		$input_param = $request_array['parameter'];
		$input_post = $request_array['post_get_data'];
		$db=new database();
		$sql =$db->fetch_table( "SELECT COUNT(request_id) as cc_req FROM prd_pension_seed_request WHERE request_id=" . $input_request_id); 
		if ($db!= FALSE)
		{
			if ($sql[0]['cc_req'] > 0)
			{
				$this->dbClose($db);
				return 1;
			} 
			else
			{
				$osms_type = "";
			if ($input_target == 'GP') 
			{
			
				$osms_type = 1;
			}
			if ($input_target == 'PS') 
			{
				$osms_type = 2;
			}
			if ($input_target == 'ZP') 
			{
				$osms_type = 3;
			}
			$request_ip = $this->get_remote_ip();
			$db=new database();
			$sql_accept_request = $db->insert("INSERT INTO prd_pension_seed_request
			(request_id, request_ip, sender_id, parameter, post_get_data,osms_type)
			VALUES (" . $input_request_id . ",'" . $request_ip . "','" . $input_service_id . "','" . $input_param . "','" . $input_post . "'," . $osms_type . ")");
			
			if ($sql_accept_request ==1) 
			{
				$this->dbClose($db);
				return 0;
			} 
			else 
			{
				$this->dbClose($db);
				return 2;
			}
		}
	} 	
	else 
	{
		$this->dbClose($db);
		return 3;
	}
}*/

/*private function checkTransactionID($transaction_id) 
{
		$db=new database();
		$sql =$db->fetch_table( "SELECT COUNT(success_transaction_id) as cc_req FROM prd_pension_seed_request WHERE success_transaction_id='" . $transaction_id . "'");
		if ($db != FALSE) 
		{
			if ($sql[0]['cc_req'] > 0) 
			{
				$this->dbClose($db);
				return 0;
			} 
			else 
			{
				$this->dbClose($db);
				return 1;
			}
		} 
		else 
		{
			$this->dbClose($db);
			return 3;
		}
}*/

/*private function checkAckID($ack_id)
 {
		$db=new database();
		$sql =$db->fetch_table( "SELECT COUNT(ack_id) as cc_req FROM prd_pension_seed_request WHERE ack_id='" . $ack_id . "'");
		if ($db != FALSE) 
		{
			if ($sql[0]['cc_req'] > 0)
			{
				$this->dbClose($db);
				return 1;
			} 
			else 
			{
				$this->dbClose($db);
				return 0;
			}
		} 
		else
		{
			$this->dbClose($db);
			return 3;
		}
 }*/

/*private function findDistCode($dist_id, $state) 
{
	if ($state == '99') 
	{
		return '3200';
	} 
	else 
	{
		if ($dist_id == '')
		{
			return '3200';
		} 
		else
		{
			$db=new database();
			$sql_dist_code = $db->fetch_table("SELECT district_code FROM prd_location_master_district WHERE district_id_pk='" . $dist_id . "'");
			
			$this->dbClose($db);
			return $sql_dist_code[0]['district_code'];
		}
	}
}
	function fun_grade_pay($grade)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$grade."'");
		return $dist_data2[0]['grade_amount'];
	}	
private function findGPCode($gp,$g) 
{
	if($g=="GP")
	{
		$db=new database();
		$sql_gp_code = $db->fetch_table("SELECT gp_code FROM prd_location_master_gp WHERE gp_id_pk='" .$gp. "'");
		$this->dbClose($db);
		return $sql_gp_code[0]['gp_code'];
	}
	else if($g=="PS")
	{
	
		$db=new database();
		$sql_ps_code = $db->fetch_table("SELECT ps_code FROM prd_location_master_panchayat_samiti WHERE ps_id_pk='" .$gp. "'");
		$this->dbClose($db);
		return $sql_ps_code[0]['ps_code'];
	}
	if ($g == 'ZP') 
	{
		$db=new database();
		$sql_ps_code = $db->fetch_table("SELECT district_code FROM prd_location_master_district WHERE district_id_pk='" .$gp. "'");
		$this->dbClose($db);
		return $sql_ps_code[0]['district_code'];
		$osms_type = 3;
	}
}*/


/*private function generate_transactionID() 
{
	$Thash = time(date('YmdHis'));
	$yr = date('Y');
	$Lhash = substr(str_shuffle("QWERTYUIOPLKJHGFDSAZXCVBNM"), 0, 2);
	$prefix = "TS";
	$hash = $prefix . substr($yr, 2, 2) . $Thash . $Lhash; 
	return $hash;
}*/

private function get_method($parameter)
{
	$type = $parameter['type'];
	$med = $parameter['med'];
	$method_name = 'unknown';
	if ($type == 'N' && $med == 'RETRIEVE') 
	{
		$method_name = "servEFreshDdata";
	}
	if ($type == 'M' && $med == 'RETRIEVE')
	{
		$method_name = 'servEModifiedDdata';
	}
	if ($type == 'N' && $med == 'RESPONSE')
	{
		$method_name = "getResponseData";
	}
	if ($type == 'M' && $med == 'RESPONSE')
	{
		$method_name = "getResponseData";
	}
		return $method_name;
}

/*
* Public method for access api.
* This method dynmically call the method based on the query string
*
*/
/* public function processApi(){
$func = strtolower(trim(str_replace("/","",$_REQUEST['service'])));
if((int)method_exists($this,$func) > 0)
$this->$func();
else
$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
} */

public function processApi() 
{

	$param = trim(str_replace("/", "|", $_REQUEST['service']));
	$exat_param = explode('|', $param);
	$param_serviceID = $exat_param[0];
	$param_Type = $exat_param[1]; 
	 $param_Method = $exat_param[2];
//echo "C".$this->checkMethod($param_Method);
	if ($db=new database()== FALSE) 
	{
		$this->response('<?xml version="1.0" encoding="UTF-8"?><iOSMSResponse><errorCode>WE401</errorCode><response>
		You are not allowed to access</response></iOSMSResponse>', 401);
	} 
	else if ($this->checkServiceID($param_serviceID) == FALSE || $this->checkType($param_Type) == FALSE || $this->checkMethod($param_Method) == FALSE)
	{
		$this->response('<?xml version="1.0" encoding="UTF-8"?><iOSMSResponse><errorCode>WE600</errorCode><response>
		Parameter Mismatch</response></iOSMSResponse>', 200);
	
	}
	else
	{

		$parameter['type'] = $param_Type;
		$parameter['med'] = $param_Method;
		$func = $this->get_method($parameter);
		if ((int) method_exists($this, $func) > 0) 
		{
			$this->$func($param);
		} 
		else
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE404</errorCode><response>
			Internal Target Not Found</response></iOSMSResponse>', 404);
		}
	}
}



private function convert_html($param)
{
	
	foreach($param  as $kr=>$gr)
	{
		if(!is_array($param[$kr]))
		{
			$param[$kr]=htmlentities(trim($param[$kr]),ENT_QUOTES) ;
		}
		else if(is_array($param[$kr]))
		{
			for($j=0;$j<count($param[$kr]);$j++)
			{
			$param[$kr][$j]=htmlentities(trim($param[$kr][$j]),ENT_QUOTES) ;
		    }
	    }
	}
	return $param;
}

private function servEFreshDdata($param)
 {
	$exat_param = explode('|', $param);
	$param_serviceID = $exat_param[0]; 
	$param_Type = $exat_param[1];
	$param_Method = $exat_param[2];
	if ($this->get_request_method() != "POST")
	{
		$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE406</errorCode><response>
		Send Method Not Allowed. </response></iOSMSResponse>', 406);
	} 
	else
	{
		$input_access_key = $this->_request['accessKey']; 
		$input_target = $this->_request['tarGet'];
		$input_request_id = $this->_request['requestID'];
		
		$request_array = array();
		$request_array['request_id'] = $input_request_id;
		$request_array['sender_id'] = $param_serviceID;
		$request_array['tarGet'] = $input_target;
		$request_array['parameter'] = $param;
	 $request_array['post_get_data'] = "accessKey-" . $input_access_key . "|tarGet-" . $input_target . "|requestID-" . $input_request_id; 
	
		if ($this->checkAccessKey($input_access_key) == FALSE) 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE601</errorCode><response>
			Invalid Access Key </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkTarget($input_target) == FALSE) 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE602</errorCode><response>
			Invalid Access Target </response></iOSMSResponse>', 200);
		} 
		else if ($input_request_id == '') 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE603</errorCode><response>
			Request ID Missing </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkRequest($request_array) > 0)
	    {
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE604</errorCode><response>
			Invalid Request ID </response></iOSMSResponse>', 200);
		
		} else 
		{
		$request_param_get_data = array();
		
		$request_param['target'] = $input_target;
		$request_param['request_id'] = $input_request_id; 
		$this->get_new_xml_data($request_param);
		}
	 }
}

private function servEModifiedDdata($param) 
{
	
	$exat_param = explode('|', $param);
	$param_serviceID = $exat_param[0];
	$param_Type = $exat_param[1];
	$param_Method = $exat_param[2];
	if ($this->get_request_method() != "POST")
	{
	
		$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE406</errorCode><response>
		Send Method Not Allowed. </response></iOSMSResponse>', 406);
	} 
	else 
	{
	

		$input_access_key = $this->_request['accessKey']; 
		$input_target = $this->_request['tarGet']; 
		$input_request_id = $this->_request['requestID']; 
		
		$request_array = array();
		$request_array['request_id'] = $input_request_id;
		$request_array['sender_id'] = $param_serviceID;
		$request_array['tarGet'] = $input_target;
		$request_array['parameter'] = $param;
		$request_array['post_get_data'] = "accessKey-" . $input_access_key . "|tarGet-" . $input_target . "|requestID-" . $input_request_id;
	
		if ($this->checkAccessKey($input_access_key) == FALSE)
		{
		
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE601</errorCode><response>
			Invalid Access Key </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkTarget($input_target) == FALSE)
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE602</errorCode><response>
			Invalid Access Target </response></iOSMSResponse>', 200);
		} 
		else if ($input_request_id == '')
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE603</errorCode><response>
			Request ID Missing </response></iOSMSResponse>', 200);
		}
		else if ($this->checkRequest($request_array)> 0)
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE604</errorCode><response>
			Invalid Request ID </response></iOSMSResponse>', 200);
		} 
		else 
		{
			$request_param_get_data = array();
			$request_param['target'] = $input_target; 
			$request_param['request_id'] = $input_request_id;
			$this->get_modified_xml_data($request_param);
		}
	}
}

private function getResponseData($param) 
{
	$exat_param = explode('|', $param);
	$param_serviceID = $exat_param[0];
	$param_Type = $exat_param[1];
	$param_Method = $exat_param[2];
	if ($this->get_request_method() != "POST") 
	{
		$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE406</errorCode><response>
		Send Method Not Allowed. </response></iOSMSResponse>', 406);
	}
	else
	{
		$input_access_key = $this->_request['accessKey']; 
		$input_target = $this->_request['tarGet'];
		$input_request_id = $this->_request['requestID'];
		$input_transaction_id = $this->_request['transactionID']; 
		$input_ack_id = $this->_request['ackID'];
		$input_xml_response = trim(htmlentities($this->_request['responseDATA'])); 
		$request_array = array();
		//print_r($_REQUEST);die;
		$request_array['request_id'] = $input_request_id;
		$request_array['sender_id'] = $param_serviceID;
		$request_array['tarGet'] = $input_target;
		$request_array['parameter'] = $param;
		$request_array['post_get_data'] = "accessKey-" . $input_access_key . "|tarGet-" . $input_target . "|requestID-"
		. $input_request_id . "|transactionID-" . $input_transaction_id . "|ackID-" . $input_ack_id;
	//$request_array['response_data']=$input_xml_response;
		if ($this->checkAccessKey($input_access_key) == FALSE)
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE601</errorCode><response>
			Invalid Access Key </response></iOSMSResponse>', 200);
		} 
		else if($this->checkTarget($input_target) == FALSE) 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE602</errorCode><response>
			Invalid Access Target </response></iOSMSResponse>', 200);
		} 
		else if ($input_request_id == '')
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE603</errorCode><response>
			Request ID Missing </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkRequest($request_array)> 0) 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE604</errorCode><response>
			Invalid Request ID </response></iOSMSResponse>', 200);
		}
		else if ($input_transaction_id == '') 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE605</errorCode><response>
			Transaction ID Missing </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkTransactionID($input_transaction_id) > 0) 
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE606</errorCode><response>
			Invalid Transaction ID </response></iOSMSResponse>', 200);
		} 
		else if ($input_ack_id == '')
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE607</errorCode><response>
			ACK ID Missing </response></iOSMSResponse>', 200);
		} 
		else if ($this->checkAckID($input_ack_id) > 0)
		{
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE608</errorCode><response>
			Acknowledgement Already Received </response></iOSMSResponse>', 200);
		} 
		else 
		{
			$request_param_get_data = array();
			//print_r($_REQUEST);die;
			
			$request_param['target'] = $input_target;
			$request_param['request_id'] = $input_request_id;
			$request_param['sender_id'] = $param_serviceID;
			$request_param['transaction_id'] = $input_transaction_id;
			$request_param['ack_id'] = $input_ack_id; 
			$request_param['xml_response'] = $_REQUEST['responseDATA'];
			$request_param['parameter'] = $request_array['parameter'];
			$request_param['post_get_data'] = $request_array['post_get_data'];
			$this->put_new_xml_response($request_param);
		}
    }
}

private function get_new_xml_data($request_param) 
{
	$type = $request_param['target']; 
	$request_id = $request_param['request_id']; 
	
	/*$current_year = date('Y');
	$action_year = $current_year + 1;
	$action_month = date('m', strtotime('last month'));
	$action_month_year = $action_year . "-" . $action_month; */

	if ($type == 'GP')
	{
		$emp_pen_type = '01';
		$osms_type = 1;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('N') AND gp_id_fk!='0' ");
		//SUBSTRING(cast(mas.emp_retirement_date as text),1,7)>='2020-01'  and SUBSTRING(cast(mas.emp_retirement_date as text),1,7)<='2020-12'
		/*$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('N') AND gp_id_fk!='0' ");*/
		
	}
	if ($type == 'PS')
	 {
		$emp_pen_type = '02';
		$osms_type = 2;
		$db=new database();	
	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
			prd_pension_employee
			WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') AND flag in('N') AND emp_pension_status='0' AND ps_id_fk!='0'");
		/*$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code
		from 
			prd_pension_employee
			WHERE   ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') AND flag in('N') AND emp_pension_status='0' AND ps_id_fk!='0'");*/
		
    }
	
	if ($type == 'ZP')
	{
		$emp_pen_type = '03';
		$osms_type = 3;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_pre_ps,
  emp_per_ps,zp_id_fk,claiment_mobile_no,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)>='2020-01' and SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('N') AND zp_id_fk IS NOT NULL
		 AND zp_emp_type='367'");
		 
	 /*
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,
		relationship_incumbent,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_pre_ps,
  emp_per_ps,zp_id_fk,claiment_mobile_no,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('N') AND zp_id_fk IS NOT NULL AND  zp_emp_type='367'");*/
		
	}
	

	$this->dbClose($db);
	$total_data_count = count($sql_teacher_data); 
	$transaction_id = $this->generate_transactionID(); 
	if($total_data_count>0)
	{
	$xml = new DOMDocument("1.0", "UTF-8");
	$container = $xml->createElement('RECORDS');
	$container = $xml->appendChild($container); 
	
	$row_info = $xml->createElement('INFO');
	$row_info = $container->appendChild($row_info);
	
	$tarGet = $xml->createElement('tarGet', $type);
	$tarGet = $row_info->appendChild($tarGet);
	$requestID = $xml->createElement('requestID', $request_id);
	$requestID = $row_info->appendChild($requestID);
	$transactionID = $xml->createElement('transactionID', $transaction_id);
	$transactionID = $row_info->appendChild($transactionID);
	$transactionDate = $xml->createElement('transactionDate', date('Y-m-d m:i:s'));
	$transactionDate = $row_info->appendChild($transactionDate);
	$dataserved = $xml->createElement('totalData', $total_data_count);
	$dataserved = $row_info->appendChild($dataserved);

//}
/*else
{
	$xml = new DOMDocument("1.0", "UTF-8");
	$container = $xml->createElement('RECORDS');
	$container = $xml->appendChild($container); 
	
	$row_info = $xml->createElement('INFO');
	$row_info = $container->appendChild($row_info);
	
	$tarGet = $xml->createElement('tarGet', 'NO DATA FOUND');
	$tarGet = $row_info->appendChild($tarGet);
}*/
	$i = 0;
	$j = 1;

	$add_comma = ''; 
	foreach ($sql_teacher_data as $fetch_teacher)
	 {
		 //emp_pension_status must be 1//
		
		if ($fetch_teacher['emp_id_fk'] && $fetch_teacher['emp_pre_pin']) 
		{
		
			if ($j != $total_data_count) 
			{
				$add_comma = ",";
			}
			if ($j == $total_data_count)
			{
				$add_comma = ";";
			}
			
			
			if($fetch_teacher['reason']==1993)
			{
			
			
			$emp_name='LATE '.($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);
			}
			else
			{
				$emp_name=($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);  
			}
			//$emp_name=($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']); 
			$db=new database();
			$sql_transaction_details =$db->insert( "INSERT INTO prd_pension_transaction_details(request_id, transaction_id, emp_code, data_send_as, data_send_date,osms_type)VALUES ('". $request_id ."' ,'" . $transaction_id . "','" . $fetch_teacher['emp_id_const'] . "',
			'1','now()','" . $osms_type."')");
			if($sql_transaction_details)
			{
			$db=new database();	
			$update_penstion_status=$db->update("UPDATE prd_pension_employee SET
			emp_pension_status='1'
			WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  and  emp_id_fk='".$fetch_teacher['emp_id_fk']."' and flag IN('N','M') ");
			}
			$row = $xml->createElement('RECORD');
			$row = $container->appendChild($row);
			
			$dob = $this->dateshow_slash($fetch_teacher['emp_dob']);
			//$pres_appnt_wef_dt = $this->dateshow_slash($fetch_teacher['tch_approval_date']); 
			$retirement_date = $this->dateshow_slash($fetch_teacher['emp_retirement_date']);
			$ropa='2009'; 
			
			
			
			
			
			
			
			
		//$pres_appnt_approv_memo_dt = $this->dateshow_slash($fetch_teacher['tch_approval_date']);
			if ($osms_type == 1) 
			{
				//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($osms_type == 2) 
			{
					$tic_allowance = '0.00';
					//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
					$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
			// $first_joining_memo_date = $this->dateshow_slash($fetch_teacher['first_joining_memo_date']);
			//$tic_allowance = $fetch_teacher['tic_allowance'];
			}
			if ($osms_type == 3) 
			{
				//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($fetch_teacher['emp_religion']) 
			{
				$religion = $fetch_teacher['emp_religion'];
			} 
		
			if ($fetch_teacher['emp_group'])
			{
				$category = $fetch_teacher['emp_group'];
			}
			if ($fetch_teacher['emp_sex'] == 91)
			{
				$gender = '91'; 
			} 
			elseif ($fetch_teacher['emp_sex'] == 92) 
			{
				$gender = '92';
			} 
			elseif ($fetch_teacher['emp_sex'] == 93)
			{
				$gender = '93';
			}
			if ($fetch_teacher['pre_state'] == 'OTHERS' || $fetch_teacher['pre_state'] =='others')
			{
				$present_state = '99';
			}
			else
			{
				$present_state = $fetch_teacher['pre_state']; 
			}
			if ($fetch_teacher['per_state'] == 'OTHERS' || $fetch_teacher['per_state'] == 'others')
			{
				$permanent_state = '99';
			} 
			else 
			{
				$permanent_state = $fetch_teacher['per_state'];
			}
			
			if($fetch_teacher['reason']==1993)
			{
			
			$retire_type_code='D';
			}
			else
			{
			$retire_type_code='S';
			}
			
			if($fetch_teacher['emp_desig_first_app']==0)
			{
				$emp_desig_first_app='';
			
			}
			else
			{
				$emp_desig_first_app=$fetch_teacher['emp_desig_first_app'];
			}
		
		
		
		if($fetch_teacher['gp_id_fk']!=0 )
			{
				$id=$fetch_teacher['gp_id_fk'];
				$g="GP";
				
				
			}
			else if($fetch_teacher['ps_id_fk']!=0 )
			{
				$id=$fetch_teacher['ps_id_fk'];
				$g="PS";
				
			}
			
			else if($fetch_teacher['zp_id_fk']!='0' )
			{
				$id=$fetch_teacher['zp_id_fk'];
				$g="ZP";
				
				
			}
		   $present_memo_no1=$fetch_teacher['emp_present_memo_no'];
		   $present_memo_dt=$this->dateshow_slash($fetch_teacher['emp_present_memo_date']);
		   $first_gp_ps_zp_code=$fetch_teacher['emp_first_gp_ps_zp_code'];
		   $first_memo_no2=$fetch_teacher['emp_first_memo_no'];
		   $first_memo_dt=$this->dateshow_slash($fetch_teacher['emp_first_memo_date']);
		   
		   
		   
			$permanent_dist = $this->findDistCode($fetch_teacher['emp_per_dist'], $permanent_state); 
			$present_dist = $this->findDistCode($fetch_teacher['emp_pre_dist'], $present_state);
			$present_gp_code=$this->findGPCode($id,$g); 
			$grad= $this->fun_grade_pay($fetch_teacher['emp_grade_pay']);
			//$basic_pay_emp = ($fetch_teacher['emp_pay_in_payband'] + $grad);
			
			
			
			
		
			if(($fetch_teacher['emp_first_gp_ps_zp_code']==$present_gp_code) &&($fetch_teacher['emp_desig']!=$emp_desig_first_app))
			{
			$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
			}
			
			else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code) && ($fetch_teacher['emp_join_prsnt_office_date']==$fetch_teacher['emp_join_prsnt_post_date']))
			{
			$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
			}
			else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code)&& (strtotime($fetch_teacher['emp_join_prsnt_post_date'])>strtotime($fetch_teacher['emp_join_prsnt_office_date'])) )
			{
			$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
			}
			else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code)&& (strtotime($fetch_teacher['emp_join_prsnt_office_date'])>strtotime($fetch_teacher['emp_join_prsnt_post_date'])) )
			{
			$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
			}
			else
			{
			$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
			}
		
			
			
			$basic_pay_emp = ($fetch_teacher['emp_pay_in_payband'] );
			$emp_id = $xml->createElement('emp_id', $fetch_teacher['emp_id_const']);
			$emp_id = $row->appendChild($emp_id);
			
			$e_name = $xml->createElement('emp_name',$emp_name);
			$e_name = $row->appendChild($e_name);
			
			$m_f_gender = $xml->createElement('m_f_gender', $gender);
			$m_f_gender = $row->appendChild($m_f_gender);
			
			$emp_dob = $xml->createElement('emp_dob', $dob);
			$emp_dob = $row->appendChild($emp_dob);
			
			$marital_status = $xml->createElement('marital_status', $fetch_teacher['emp_marital_status']);
			$marital_status = $row->appendChild($marital_status);
			
			$relegion = $xml->createElement('relegion', $religion);
			$relegion = $row->appendChild($relegion);
			
			$identity_mark = $xml->createElement('identity_mark', htmlspecialchars($fetch_teacher['emp_idf_mark']));
			$identity_mark = $row->appendChild($identity_mark);
			
			
			$father_name = $xml->createElement('father_name',$fetch_teacher['emp_father_name']);
			$father_name = $row->appendChild($father_name);
			
			$dept_cd = $xml->createElement('dept_cd', '13');
			$dept_cd = $row->appendChild($dept_cd);
			
			
			$sub_div_cd = $xml->createElement('sub_dept_code',$emp_pen_type);
			$sub_div_cd= $row->appendChild($sub_div_cd);
			
			
			$retire_type = $xml->createElement('retire_type',$retire_type_code);
			$retire_type = $row->appendChild($retire_type);
			
			if($retire_type_code=='D')
			{
			$retirement_date = $xml->createElement('retirement_date', $fetch_teacher['emp_termination_date']);
			$retirement_date = $row->appendChild($retirement_date);
			}
			else
			{
			$retirement_date = $xml->createElement('retirement_date', $retirement_date);
			$retirement_date = $row->appendChild($retirement_date);
			}
			/*$ropa = $xml->createElement('ropa','2009');
			$ropa = $row->appendChild($ropa);*/
			
			$ropa = $xml->createElement('ropa','2019');
			$ropa = $row->appendChild($ropa);
			
			
			/*$band_pay = $xml->createElement('band_pay', $fetch_teacher['emp_pay_in_payband']);
			$band_pay = $row->appendChild($band_pay);
			
			$grade_pay = $xml->createElement('grade_pay', $grad);
			$grade_pay = $row->appendChild($grade_pay);*/
			
			$band_pay = $xml->createElement('band_pay', '0.00');
			$band_pay = $row->appendChild($band_pay);
			
			$grade_pay = $xml->createElement('grade_pay', '0.00');
			$grade_pay = $row->appendChild($grade_pay);
			
			$additional_grade_pay = $xml->createElement('additional_grade_pay', '0.00');
			$additional_grade_pay = $row->appendChild($additional_grade_pay);
			
			$basic_pay = $xml->createElement('basic_pay', $basic_pay_emp);
			$basic_pay = $row->appendChild($basic_pay);
			
			$basic_pay_notional = $xml->createElement('basic_pay_notional', '0.00');
			$basic_pay_notional = $row->appendChild($basic_pay_notional);
			
			$special_pay = $xml->createElement('special_pay', $tic_allowance);
			$special_pay = $row->appendChild($special_pay);
			
			$avg_pay = $xml->createElement('avg_pay', '0.00');
			$avg_pay = $row->appendChild($avg_pay);
			
			$prsnt_house_no = $xml->createElement('prsnt_house_no', $fetch_teacher['emp_pre_house_no']);
			$prsnt_house_no = $row->appendChild($prsnt_house_no);
			
			$prsnt_street_name = $xml->createElement('prsnt_street_name', $fetch_teacher['emp_pre_street_no']);
			$prsnt_street_name = $row->appendChild($prsnt_street_name);
			
			$prsnt_twn_vill_name = $xml->createElement('prsnt_twn_vill_name', $fetch_teacher['emp_pre_vill']);
			$prsnt_twn_vill_name = $row->appendChild($prsnt_twn_vill_name);
			
			$prsnt_post_office = $xml->createElement('prsnt_post_office', $fetch_teacher['emp_pre_post']);
			$prsnt_post_office = $row->appendChild($prsnt_post_office);
			
			$prsnt_police_stn = $xml->createElement('prsnt_police_stn', $fetch_teacher['emp_pre_ps']);
			$prsnt_police_stn = $row->appendChild($prsnt_police_stn);
			
			$prsnt_pin_no = $xml->createElement('prsnt_pin_no', $fetch_teacher['emp_pre_pin']);
			$prsnt_pin_no = $row->appendChild($prsnt_pin_no);
			
			$prsnt_dist = $xml->createElement('prsnt_dist', $present_dist);
			$prsnt_dist = $row->appendChild($prsnt_dist);
			
			$prsnt_state = $xml->createElement('prsnt_state', $present_state);
			$prsnt_state = $row->appendChild($prsnt_state);
			
			$permnt_house_no = $xml->createElement('permnt_house_no', $fetch_teacher['emp_per_house_no']);
			$permnt_house_no = $row->appendChild($permnt_house_no);
			
			$permnt_street_name = $xml->createElement('permnt_street_name', $fetch_teacher['emp_per_street_no']);
			$permnt_street_name = $row->appendChild($permnt_street_name);
			
			$permnt_twn_vill_name = $xml->createElement('permnt_twn_vill_name', $fetch_teacher['emp_per_vill']);
			$permnt_twn_vill_name = $row->appendChild($permnt_twn_vill_name);
			
			$permnt_post_office = $xml->createElement('permnt_post_office', $fetch_teacher['emp_per_post']);
			$permnt_post_office = $row->appendChild($permnt_post_office);
			
			$permnt_police_stn = $xml->createElement('permnt_police_stn', $fetch_teacher['emp_per_ps']);
			$permnt_police_stn = $row->appendChild($permnt_police_stn);
			
			$permnt_pin_no = $xml->createElement('permnt_pin_no', $fetch_teacher['emp_per_pin']);
			$permnt_pin_no = $row->appendChild($permnt_pin_no);
			
			$permnt_dist = $xml->createElement('permnt_dist', $permanent_dist);
			$permnt_dist = $row->appendChild($permnt_dist);
			
			$permnt_state = $xml->createElement('permnt_state', $permanent_state);
			$permnt_state = $row->appendChild($permnt_state);
			
			
			$present_gp_ps_zp_c = $xml->createElement('present_gp_ps_zp_code', $present_gp_code);
			$present_gp_ps_zp_c = $row->appendChild($present_gp_ps_zp_c);
			
			/*$present_school_dise_code = $xml->createElement('present_school_dise_code', $fetch_teacher['schcd']);
			$present_school_dise_code = $row->appendChild($present_school_dise_code);
			*/
			$present_post_held = $xml->createElement('present_post_held', $fetch_teacher['emp_desig']);
			$present_post_held = $row->appendChild($present_post_held);
			
			$pres_appnt_approv_memo_no = $xml->createElement('present_appnt_approv_memo_no', $present_memo_no1);
			$pres_appnt_approv_memo_no = $row->appendChild($pres_appnt_approv_memo_no);
			
			$pres_appnt_approv_memo_dt = $xml->createElement('present_appnt_approv_memo_dt',$present_memo_dt);
			$pres_appnt_approv_memo_dt = $row->appendChild($pres_appnt_approv_memo_dt);
			
			$present_appoint_wef_dt = $xml->createElement('present_appoint_wef_dt', $joining_date);
			$present_appoint_wef_dt = $row->appendChild($present_appoint_wef_dt);
		
			$first_gp_ps_zp_c = $xml->createElement('first_gp_ps_zp_code', $first_gp_ps_zp_code);
			$first_gp_ps_zp_c = $row->appendChild($first_gp_ps_zp_c);
			
			$first_appoint_post = $xml->createElement('first_appoint_post', $emp_desig_first_app);
			$first_appoint_post = $row->appendChild($first_appoint_post);
			
			$first_appnt_approv_memo_no = $xml->createElement('first_appnt_approv_memo_no', $first_memo_no2);
			$first_appnt_approv_memo_no = $row->appendChild($first_appnt_approv_memo_no);
			
			$first_appnt_approv_memo_dt = $xml->createElement('first_appnt_approv_memo_dt', $first_memo_dt);
			$first_appnt_approv_memo_dt = $row->appendChild($first_appnt_approv_memo_dt);
			
			$first_appoint_wef_dt = $xml->createElement('first_appoint_wef_dt', $first_joining_date);
			$first_appoint_wef_dt = $row->appendChild($first_appoint_wef_dt);
			if($retire_type_code=='D')
			{
			$mobile_no = $xml->createElement('mobile_no', $fetch_teacher['claiment_mobile_no']);
			$mobile_no = $row->appendChild($mobile_no);
			}
			else
			{
			$mobile_no = $xml->createElement('mobile_no', $fetch_teacher['emp_mobile_no']);
			$mobile_no = $row->appendChild($mobile_no);
			}
			
			$e_mail_id = $xml->createElement('e_mail_id', $fetch_teacher['emp_mail_id']);
			$e_mail_id = $row->appendChild($e_mail_id);
			
			$pan_no = $xml->createElement('pan_no', $fetch_teacher['emp_pan_no']);
			$pan_no = $row->appendChild($pan_no);
			
			$aadhar_no = $xml->createElement('aadhar_no', $fetch_teacher['emp_aadhar_no']);
			$aadhar_no = $row->appendChild($aadhar_no);
			
			
			
			/*$spuose_name = $xml->createElement('spuose_name',$fetch_teacher['emp_spouse_name']);
			$spuose_name = $row->appendChild($spuose_name);*/
			if($retire_type_code=='D')
			{
				
			$claimant_name = $xml->createElement('claimant_name',$fetch_teacher['claimant_name']);
			$claimant_name = $row->appendChild($claimant_name);
			
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent',$fetch_teacher['relationship_incumbent']);
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);
			
			
				
			/*$claimant_name = $xml->createElement('claimant_name',$fetch_teacher['claimant_name']);
			$claimant_name = $row->appendChild($claimant_name);
			
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent',$fetch_teacher['relationship_incumbent']);
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);*/
			}
			else
			{
			$claimant_name = $xml->createElement('claimant_name',$emp_name);
			$claimant_name = $row->appendChild($claimant_name);
			
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent','9');
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);
			}
			$new_modified_flag = $xml->createElement('new_modified_flag',$fetch_teacher['flag']);
			$new_modified_flag = $row->appendChild($new_modified_flag);
			
			$transaction_dt = $xml->createElement('transaction_dt', date('Y-m-d'));
			$transaction_dt = $row->appendChild($transaction_dt);
			
			$i++;
			$j++;
		} // close of if
	  }// close of foreach

			$request_array = array();
			
			$request_array['transaction_id'] = $transaction_id;
			$request_array['total_data_count'] = $total_data_count;
			$request_array['request_id'] = $request_id;
			$up_ret = $this->update_request_new_data($request_array);
			//$fetch_xml=$this->xml_data_fetch($request_array);
			$this->response($xml->saveXML(), 200);
 }
 else
{
		$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE611</errorCode><response>
		NO DATA SENT AS PER CRITERIA MENTIONED</response></iOSMSResponse>', 200);
}
}



/*$dbc = $this->dbConnect();
pg_query("BEGIN");
$sql_transaction_details_mod = rtrim($sql_transaction_details, ',');
$sql_transaction_details_mod = $sql_transaction_details_mod . ";";
$res_transaction_details = pg_query($dbc, $sql_transaction_details_mod);
$rows_count_transaction_details = pg_affected_rows($res_transaction_details);
if ($rows_count_transaction_details == $i) {
pg_query("COMMIT");
$this->dbClose($db);
} else {
pg_query("ROLLBACK");
$this->dbClose($db);
}*/
//$up_ret = $this->update_request_modified_data($request_array);

/*else {
// $this->dbClose($db);
$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE611</errorCode><response>
NO DATA SENT AS PER CRITERIA MENTIONED</response></iOSMSResponse>', 200);
}*/
private function get_modified_xml_data($request_param) 
{
	$type = $request_param['target']; 
	$request_id = $request_param['request_id']; 
	
	/*$current_year = date('Y');
	$action_year = $current_year + 1;
	$action_month = date('m', strtotime('last month'));
	$action_month_year = $action_year . "-" . $action_month; */

	if ($type == 'GP')
	{
		$emp_pen_type = '01';
		$osms_type = 1;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		relationship_incumbent,
		claiment_mobile_no,
		emp_id_const,emp_pre_ps,emp_per_ps,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('M') AND gp_id_fk!='0' ");
		
	}
	if ($type == 'PS')
	 {
		$emp_pen_type = '02';
		$osms_type = 2;
		$db=new database();	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,emp_pre_ps,emp_per_ps,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
			prd_pension_employee
			WHERE   ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') AND flag in('M') AND emp_pension_status='0' AND ps_id_fk!='0'");
		
    }
	
	if ($type == 'ZP')
	{
		$emp_pen_type = '03';
		$osms_type = 3;
		//    $field_ext = ' ,tch_date_joining_school';
		$db=new database();	
		
		$sql_teacher_data = $db->fetch_table("select
		emp_id_fk,
		gp_id_fk ,
		emp_first_name ,
		emp_second_name ,
		emp_last_name,
		emp_dob,
		emp_sex,
		emp_aadhar_no,
		emp_desig,
		emp_first_join_date,
		emp_conf_join_date,
		emp_join_prsnt_post_date,
		emp_join_prsnt_office_date,
		emp_retirement_date,
		emp_termination_date,
		emp_group,
		emp_desig_first_app,
		emp_pay_in_payband,
		emp_father_name,
		emp_religion,
		emp_marital_status,
		emp_spouse_name,
		emp_pan_no,
		emp_idf_mark,
		pre_state,
		emp_pre_house_no,
		emp_pre_street_no,
		emp_pre_vill,
		emp_pre_post,
		emp_pre_pin,
		emp_pre_dist,
		emp_pre_dist_others,
		per_state,
		emp_per_house_no,
		emp_per_street_no,
		emp_per_vill,
		emp_per_post,
		emp_per_pin,
		emp_per_dist,
		emp_per_dist_others,
		emp_mobile_no,
		emp_mail_id,
		emp_status,
		entry_ip,
		emp_grade_pay,
		emp_id_const,
		relationship_incumbent,
		claiment_mobile_no,
		ps_id_fk,update_time,flag,emp_pension_status,reason,claimant_name,relationship_incumbent,emp_pre_ps,
  emp_per_ps,zp_id_fk,claiment_mobile_no,emp_first_memo_no,emp_first_memo_date,emp_present_memo_no,emp_present_memo_date,emp_first_gp_ps_zp_code,ropa_level
		from 
		prd_pension_employee
		WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  AND emp_pension_status='0' AND flag in('M') AND zp_id_fk IS NOT NULL");
		
	}
	

	$this->dbClose($db);
	$total_data_count = count($sql_teacher_data); 
	$transaction_id = $this->generate_transactionID(); 
	if($total_data_count>0)
	{
	$xml = new DOMDocument("1.0", "UTF-8");
	$container = $xml->createElement('RECORDS');
	$container = $xml->appendChild($container); 
	
	$row_info = $xml->createElement('INFO');
	$row_info = $container->appendChild($row_info);
	
	$tarGet = $xml->createElement('tarGet', $type);
	$tarGet = $row_info->appendChild($tarGet);
	$requestID = $xml->createElement('requestID', $request_id);
	$requestID = $row_info->appendChild($requestID);
	$transactionID = $xml->createElement('transactionID', $transaction_id);
	$transactionID = $row_info->appendChild($transactionID);
	$transactionDate = $xml->createElement('transactionDate', date('Y-m-d m:i:s'));
	$transactionDate = $row_info->appendChild($transactionDate);
	$dataserved = $xml->createElement('totalData', $total_data_count);
	$dataserved = $row_info->appendChild($dataserved);

//}
/*else
{
	$xml = new DOMDocument("1.0", "UTF-8");
	$container = $xml->createElement('RECORDS');
	$container = $xml->appendChild($container); 
	
	$row_info = $xml->createElement('INFO');
	$row_info = $container->appendChild($row_info);
	
	$tarGet = $xml->createElement('tarGet', 'NO DATA FOUND');
	$tarGet = $row_info->appendChild($tarGet);
}*/
	$i = 0;
	$j = 1;

	$add_comma = ''; 
	foreach ($sql_teacher_data as $fetch_teacher)
	 {
		 //emp_pension_status must be 1//
		
		if ($fetch_teacher['emp_id_fk'] && $fetch_teacher['emp_pre_pin']) 
		{
		
			if ($j != $total_data_count) 
			{
				$add_comma = ",";
			}
			if ($j == $total_data_count)
			{
				$add_comma = ";";
			}
			
			if($fetch_teacher['reason']==1993)
			{
			
			
			$emp_name='LATE '.($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);
			}
			else
			{
				$emp_name=($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']);  
			}
			//$emp_name=($fetch_teacher['emp_first_name'].' '.$fetch_teacher['emp_second_name'].' '.$fetch_teacher['emp_last_name']); 
			$db=new database();
			$sql_transaction_details =$db->insert( "INSERT INTO prd_pension_transaction_details(request_id, transaction_id, emp_code, data_send_as, data_send_date,osms_type)VALUES ('". $request_id ."' ,'" . $transaction_id . "','" . $fetch_teacher['emp_id_const'] . "',
			'1','now()','" . $osms_type."')");
			if($sql_transaction_details)
			{
			$db=new database();	
			$update_penstion_status=$db->update("UPDATE prd_pension_employee SET
			emp_pension_status='1'
			WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993')  and  emp_id_fk='".$fetch_teacher['emp_id_fk']."' and flag IN('N','M') ");
			}
			$row = $xml->createElement('RECORD');
			$row = $container->appendChild($row);
			
			$dob = $this->dateshow_slash($fetch_teacher['emp_dob']);
			//$pres_appnt_wef_dt = $this->dateshow_slash($fetch_teacher['tch_approval_date']); 
			$retirement_date = $this->dateshow_slash($fetch_teacher['emp_retirement_date']);
			$ropa='2009'; 
		//$pres_appnt_approv_memo_dt = $this->dateshow_slash($fetch_teacher['tch_approval_date']);
			if ($osms_type == 1) 
			{
				//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($osms_type == 2) 
			{
					$tic_allowance = '0.00';
					//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
					$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
			// $first_joining_memo_date = $this->dateshow_slash($fetch_teacher['first_joining_memo_date']);
			//$tic_allowance = $fetch_teacher['tic_allowance'];
			}
			if ($osms_type == 3) 
			{
				//$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				$first_joining_date = $this->dateshow_slash($fetch_teacher['emp_first_join_date']);
				$tic_allowance = '0.00';
			}
			if ($fetch_teacher['emp_religion']) 
			{
				$religion = $fetch_teacher['emp_religion'];
			} 
		
			if ($fetch_teacher['emp_group'])
			{
				$category = $fetch_teacher['emp_group'];
			}
			if ($fetch_teacher['emp_sex'] == 91)
			{
				$gender = '91'; 
			} 
			elseif ($fetch_teacher['emp_sex'] == 92) 
			{
				$gender = '92';
			} 
			elseif ($fetch_teacher['emp_sex'] == 93)
			{
				$gender = '93';
			}
			if ($fetch_teacher['pre_state'] == 'OTHERS' || $fetch_teacher['pre_state'] =='others')
			{
				$present_state = '99';
			}
			else
			{
				$present_state = $fetch_teacher['pre_state']; 
			}
			if ($fetch_teacher['per_state'] == 'OTHERS' || $fetch_teacher['per_state'] == 'others')
			{
				$permanent_state = '99';
			} 
			else 
			{
				$permanent_state = $fetch_teacher['per_state'];
			}
			
			if($fetch_teacher['reason']==1993)
			{
			
			$retire_type_code='D';
			}
			else
			{
			$retire_type_code='S';
			}
			
			if($fetch_teacher['emp_desig_first_app']==0)
			{
				$emp_desig_first_app='';
			
			}
			else
			{
				$emp_desig_first_app=$fetch_teacher['emp_desig_first_app'];
			}
			
			if($fetch_teacher['gp_id_fk']!=0 )
			{
				$id=$fetch_teacher['gp_id_fk'];
				$g="GP";
				
				
			}
			else if($fetch_teacher['ps_id_fk']!=0 )
			{
				$id=$fetch_teacher['ps_id_fk'];
				$g="PS";
				
			}
			
			else if($fetch_teacher['zp_id_fk']!='0' )
			{
				$id=$fetch_teacher['zp_id_fk'];
				$g="ZP";
				
				
			}
		
		   $present_memo_no1=$fetch_teacher['emp_present_memo_no'];
		   $present_memo_dt=$this->dateshow_slash($fetch_teacher['emp_present_memo_date']);
		   $first_gp_ps_zp_code=$fetch_teacher['emp_first_gp_ps_zp_code'];
		   $first_memo_no2=$fetch_teacher['emp_first_memo_no'];
		   $first_memo_dt=$this->dateshow_slash($fetch_teacher['emp_first_memo_date']);
		   
		   
			$permanent_dist = $this->findDistCode($fetch_teacher['emp_per_dist'], $permanent_state); 
			$present_dist = $this->findDistCode($fetch_teacher['emp_pre_dist'], $present_state);
			$present_gp_code=$this->findGPCode($id,$g); 
			$grad= $this->fun_grade_pay($fetch_teacher['emp_grade_pay']);
			
				if(($fetch_teacher['emp_first_gp_ps_zp_code']==$present_gp_code) &&($fetch_teacher['emp_desig']!=$emp_desig_first_app))
				{
					
				$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
				}
				
				else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code) && ($fetch_teacher['emp_join_prsnt_office_date']==$fetch_teacher['emp_join_prsnt_post_date']))
				{
					
				$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				}
				else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code)&& (strtotime($fetch_teacher['emp_join_prsnt_post_date'])>strtotime($fetch_teacher['emp_join_prsnt_office_date'])) )
				{
					
				$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
				}
				else if(($fetch_teacher['emp_desig']!=$emp_desig_first_app) && ($fetch_teacher['emp_first_gp_ps_zp_code']!=$present_gp_code)&& (strtotime($fetch_teacher['emp_join_prsnt_office_date'])>strtotime($fetch_teacher['emp_join_prsnt_post_date'])) )
				{
					
				$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_office_date']);
				}
				else
				{
					
				$joining_date = $this->dateshow_slash($fetch_teacher['emp_join_prsnt_post_date']);
				}
		
			
			$basic_pay_emp = ($fetch_teacher['emp_pay_in_payband'] );
			$emp_id = $xml->createElement('emp_id', $fetch_teacher['emp_id_const']);
			$emp_id = $row->appendChild($emp_id);
			
			$e_name = $xml->createElement('emp_name',$emp_name);
			$e_name = $row->appendChild($e_name);
			
			$m_f_gender = $xml->createElement('m_f_gender', $gender);
			$m_f_gender = $row->appendChild($m_f_gender);
			
			$emp_dob = $xml->createElement('emp_dob', $dob);
			$emp_dob = $row->appendChild($emp_dob);
			
			$marital_status = $xml->createElement('marital_status', $fetch_teacher['emp_marital_status']);
			$marital_status = $row->appendChild($marital_status);
			
			$relegion = $xml->createElement('relegion', $religion);
			$relegion = $row->appendChild($relegion);
			
			$identity_mark = $xml->createElement('identity_mark', htmlspecialchars($fetch_teacher['emp_idf_mark']));
			$identity_mark = $row->appendChild($identity_mark);
			
			
			$father_name = $xml->createElement('father_name',$fetch_teacher['emp_father_name']);
			$father_name = $row->appendChild($father_name);
			
			$dept_cd = $xml->createElement('dept_cd', '13');
			$dept_cd = $row->appendChild($dept_cd);
			
			
			$sub_div_cd = $xml->createElement('sub_dept_code',$emp_pen_type);
			$sub_div_cd= $row->appendChild($sub_div_cd);
			
			
			$retire_type = $xml->createElement('retire_type',$retire_type_code);
			$retire_type = $row->appendChild($retire_type);
			
			if($retire_type_code=='D')
			{
			$retirement_date = $xml->createElement('retirement_date', $fetch_teacher['emp_termination_date']);
			$retirement_date = $row->appendChild($retirement_date);
			}
			else
			{
			$retirement_date = $xml->createElement('retirement_date', $retirement_date);
			$retirement_date = $row->appendChild($retirement_date);
			}
			/*$ropa = $xml->createElement('ropa','2009');
			$ropa = $row->appendChild($ropa);*/
			
			$ropa = $xml->createElement('ropa','2019');
			$ropa = $row->appendChild($ropa);
			
			/*$band_pay = $xml->createElement('band_pay', $fetch_teacher['emp_pay_in_payband']);
			$band_pay = $row->appendChild($band_pay);
			
			$grade_pay = $xml->createElement('grade_pay', $grad);
			$grade_pay = $row->appendChild($grade_pay);*/
			
			$band_pay = $xml->createElement('band_pay', '0.00');
			$band_pay = $row->appendChild($band_pay);
			
			$grade_pay = $xml->createElement('grade_pay', '0.00');
			$grade_pay = $row->appendChild($grade_pay);
			
			$additional_grade_pay = $xml->createElement('additional_grade_pay', '0.00');
			$additional_grade_pay = $row->appendChild($additional_grade_pay);
			
			$basic_pay = $xml->createElement('basic_pay', $basic_pay_emp);
			$basic_pay = $row->appendChild($basic_pay);
			
			$basic_pay_notional = $xml->createElement('basic_pay_notional', '0.00');
			$basic_pay_notional = $row->appendChild($basic_pay_notional);
			
			$special_pay = $xml->createElement('special_pay', $tic_allowance);
			$special_pay = $row->appendChild($special_pay);
			
			$avg_pay = $xml->createElement('avg_pay', '0.00');
			$avg_pay = $row->appendChild($avg_pay);
			
			$prsnt_house_no = $xml->createElement('prsnt_house_no', $fetch_teacher['emp_pre_house_no']);
			$prsnt_house_no = $row->appendChild($prsnt_house_no);
			
			$prsnt_street_name = $xml->createElement('prsnt_street_name', $fetch_teacher['emp_pre_street_no']);
			$prsnt_street_name = $row->appendChild($prsnt_street_name);
			
			$prsnt_twn_vill_name = $xml->createElement('prsnt_twn_vill_name', $fetch_teacher['emp_pre_vill']);
			$prsnt_twn_vill_name = $row->appendChild($prsnt_twn_vill_name);
			
			$prsnt_post_office = $xml->createElement('prsnt_post_office', $fetch_teacher['emp_pre_post']);
			$prsnt_post_office = $row->appendChild($prsnt_post_office);
			
			$prsnt_police_stn = $xml->createElement('prsnt_police_stn', $fetch_teacher['emp_pre_ps']);
			$prsnt_police_stn = $row->appendChild($prsnt_police_stn);
			
			$prsnt_pin_no = $xml->createElement('prsnt_pin_no', $fetch_teacher['emp_pre_pin']);
			$prsnt_pin_no = $row->appendChild($prsnt_pin_no);
			
			$prsnt_dist = $xml->createElement('prsnt_dist', $present_dist);
			$prsnt_dist = $row->appendChild($prsnt_dist);
			
			$prsnt_state = $xml->createElement('prsnt_state', $present_state);
			$prsnt_state = $row->appendChild($prsnt_state);
			
			$permnt_house_no = $xml->createElement('permnt_house_no', $fetch_teacher['emp_per_house_no']);
			$permnt_house_no = $row->appendChild($permnt_house_no);
			
			$permnt_street_name = $xml->createElement('permnt_street_name', $fetch_teacher['emp_per_street_no']);
			$permnt_street_name = $row->appendChild($permnt_street_name);
			
			$permnt_twn_vill_name = $xml->createElement('permnt_twn_vill_name', $fetch_teacher['emp_per_vill']);
			$permnt_twn_vill_name = $row->appendChild($permnt_twn_vill_name);
			
			$permnt_post_office = $xml->createElement('permnt_post_office', $fetch_teacher['emp_per_post']);
			$permnt_post_office = $row->appendChild($permnt_post_office);
			
			$permnt_police_stn = $xml->createElement('permnt_police_stn', $fetch_teacher['emp_per_ps']);
			$permnt_police_stn = $row->appendChild($permnt_police_stn);
			
			$permnt_pin_no = $xml->createElement('permnt_pin_no', $fetch_teacher['emp_per_pin']);
			$permnt_pin_no = $row->appendChild($permnt_pin_no);
			
			$permnt_dist = $xml->createElement('permnt_dist', $permanent_dist);
			$permnt_dist = $row->appendChild($permnt_dist);
			
			$permnt_state = $xml->createElement('permnt_state', $permanent_state);
			$permnt_state = $row->appendChild($permnt_state);
			
			
			$present_gp_ps_zp_c = $xml->createElement('present_gp_ps_zp_code', $present_gp_code);
			$present_gp_ps_zp_c = $row->appendChild($present_gp_ps_zp_c);
			
			/*$present_school_dise_code = $xml->createElement('present_school_dise_code', $fetch_teacher['schcd']);
			$present_school_dise_code = $row->appendChild($present_school_dise_code);
			*/
			$present_post_held = $xml->createElement('present_post_held', $fetch_teacher['emp_desig']);
			$present_post_held = $row->appendChild($present_post_held);
			
			$pres_appnt_approv_memo_no = $xml->createElement('present_appnt_approv_memo_no', $present_memo_no1);
			$pres_appnt_approv_memo_no = $row->appendChild($pres_appnt_approv_memo_no);
			
			$pres_appnt_approv_memo_dt = $xml->createElement('present_appnt_approv_memo_dt',$present_memo_dt);
			$pres_appnt_approv_memo_dt = $row->appendChild($pres_appnt_approv_memo_dt);
			
			$present_appoint_wef_dt = $xml->createElement('present_appoint_wef_dt', $joining_date);
			$present_appoint_wef_dt = $row->appendChild($present_appoint_wef_dt);
		
			$first_gp_ps_zp_c = $xml->createElement('first_gp_ps_zp_code', $first_gp_ps_zp_code);
			$first_gp_ps_zp_c = $row->appendChild($first_gp_ps_zp_c);
			
			$first_appoint_post = $xml->createElement('first_appoint_post', $emp_desig_first_app);
			$first_appoint_post = $row->appendChild($first_appoint_post);
			
			$first_appnt_approv_memo_no = $xml->createElement('first_appnt_approv_memo_no', $first_memo_no2);
			$first_appnt_approv_memo_no = $row->appendChild($first_appnt_approv_memo_no);
			
			$first_appnt_approv_memo_dt = $xml->createElement('first_appnt_approv_memo_dt', $first_memo_dt);
			$first_appnt_approv_memo_dt = $row->appendChild($first_appnt_approv_memo_dt);
			
			$first_appoint_wef_dt = $xml->createElement('first_appoint_wef_dt', $first_joining_date);
			$first_appoint_wef_dt = $row->appendChild($first_appoint_wef_dt);
			
			if($retire_type_code=='D')
			{
			$mobile_no = $xml->createElement('mobile_no', $fetch_teacher['claiment_mobile_no']);
			$mobile_no = $row->appendChild($mobile_no);
			}
			else
			{
			$mobile_no = $xml->createElement('mobile_no', $fetch_teacher['emp_mobile_no']);
			$mobile_no = $row->appendChild($mobile_no);
			}
			
			$e_mail_id = $xml->createElement('e_mail_id', $fetch_teacher['emp_mail_id']);
			$e_mail_id = $row->appendChild($e_mail_id);
			
			$pan_no = $xml->createElement('pan_no', $fetch_teacher['emp_pan_no']);
			$pan_no = $row->appendChild($pan_no);
			
			$aadhar_no = $xml->createElement('aadhar_no', $fetch_teacher['emp_aadhar_no']);
			$aadhar_no = $row->appendChild($aadhar_no);
			
			
			
			/*$spuose_name = $xml->createElement('spuose_name',$fetch_teacher['emp_spouse_name']);
			$spuose_name = $row->appendChild($spuose_name);*/
			if($retire_type_code=='D')
			{
				
			$claimant_name = $xml->createElement('claimant_name',$fetch_teacher['claimant_name']);
			$claimant_name = $row->appendChild($claimant_name);
			
			/*$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent','9');
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);
			*/
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent',$fetch_teacher['relationship_incumbent']);
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);
				
			/*$claimant_name = $xml->createElement('claimant_name',$fetch_teacher['claimant_name']);
			$claimant_name = $row->appendChild($claimant_name);
			
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent',$fetch_teacher['relationship_incumbent']);
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);*/
			}
			else
			{
			$claimant_name = $xml->createElement('claimant_name',$emp_name);
			$claimant_name = $row->appendChild($claimant_name);
			
			$relationship_with_incumbent = $xml->createElement('relationship_with_incumbent','9');
			$relationship_with_incumbent = $row->appendChild($relationship_with_incumbent);
			}
			$new_modified_flag = $xml->createElement('new_modified_flag',$fetch_teacher['flag']);
			$new_modified_flag = $row->appendChild($new_modified_flag);
			
			$transaction_dt = $xml->createElement('transaction_dt', date('Y-m-d'));
			$transaction_dt = $row->appendChild($transaction_dt);
			
			$i++;
			$j++;
		} // close of if
	  }// close of foreach

			$request_array = array();
			
			$request_array['transaction_id'] = $transaction_id;
			$request_array['total_data_count'] = $total_data_count;
			$request_array['request_id'] = $request_id;
			$up_ret = $this->update_request_new_data($request_array);
			//$fetch_xml=$this->xml_data_fetch($request_array);
			$this->response($xml->saveXML(), 200);
 }
 else
{
		$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE611</errorCode><response>
		NO DATA SENT AS PER CRITERIA MENTIONED</response></iOSMSResponse>', 200);
}
}

////////////////////////////////////////////////////////////////////////
private function put_new_xml_response($request_param) 
{
		//print_r($_REQUEST); die;
	
		$input_target = $request_param['target'];
		$input_request_id = $request_param['request_id'];
		$input_transaction_id = $request_param['transaction_id'];
		$input_ack_id = $request_param['ack_id'];
		$input_xml_response = $request_param['xml_response'];
		$input_sender_id = $request_param['sender_id'];
		$parameter = $request_param['parameter'];
		$post_get_data = $request_param['post_get_data'];
		$remoteIP = $this->get_remote_ip(); 
		$osms_type = "";
		if ($input_target == 'GP') 
		{
			$osms_type = 1;
		}
		if ($input_target == 'PS') {
			$osms_type = 2; 
		}
		if ($input_target == 'ZP') {
			$osms_type = 3;
		}
		$db=new database();
		pg_query("BEGIN");
		
		
		$sql_insert_response = $db->insert("INSERT INTO prd_pension_exc_response(
		request_id, request_ip, sender_id, transaction_id, 
		ack_id, parameter, post_get_data,   
		response_xmldata, response_received_status,osms_type
		)
		VALUES ('". $input_request_id ."', '" . $remoteIP . "', '" . $input_sender_id . "', '" . $input_transaction_id . "',
		'" . $input_ack_id . "', '" . $parameter . "', '" . $post_get_data . "', '" . trim($input_xml_response) . "',1 ," . $osms_type . ")");
		
		$db=new database();
		$upd_request = $db->update("UPDATE prd_pension_seed_request SET ack_received='1', ack_received_time='now()', ack_id='" . $input_ack_id . "' 
		WHERE success_transaction_id='" . $input_transaction_id . "' ");
			$db=new database();
	$xml_fetch=$db->fetch_table( "SELECT response_xmldata FROM prd_pension_exc_response WHERE transaction_id='" .$input_transaction_id . "' AND request_id='".$input_request_id."'");
	
		 $x= $xml_fetch[0]['response_xmldata'];
		 
		
		$arry=simplexml_load_string($x);
		$json  = json_encode($arry);
		$configData = json_decode($json, true);
		
		
		foreach($configData as $key=>$value)
		{
		
			if($value[1]=="")
			{
		//	echo 12;
			
				foreach($value as $x1 => $x_value) 
				{			
							
					$db=new database();
					
				$modify_error_code=$db->update("UPDATE prd_pension_employee SET
				error_code='".$value['error_code']."',response_status='".$value['status']."',capture_date='".$value['capture_date']."'
				WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and  emp_id_const='".$value['emp_id']."' and emp_pension_status='1'");
					
					$error_code=$db->fetch_table( "SELECT error_code FROM prd_pension_employee WHERE  emp_id_const='".$value['emp_id']."'");
					$error=$error_code[0]['error_code'];
					//echo $error;
					//$exampleEncoded = json_encode($error);
					//$con = json_decode($exampleEncoded, true);
					//$b=explode(",",$error);
					
					
						if($error==0)
						{
							
							$modify_emp_status=$db->update("UPDATE prd_pension_employee SET
							emp_pension_status='2'
							WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and emp_id_const='".$value['emp_id']."' and emp_pension_status='1'"
							);
						}
						else
						{
							
							$modify_emp_status=$db->update("UPDATE prd_pension_employee SET
							emp_pension_status='3'
							WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and emp_id_const='".$value['emp_id']."' and emp_pension_status='1'"
							);
						}
					//$json1  = json_encode($arry1);
					//$configData1 = json_decode($json1, true);
					/*$b= array($error_code[0]['error_code']);*/
					
			
							
								
					
					break;
					
				}
		
			}
			else
			{
				
			
				$k=0;
					foreach($value as $x1 => $x_value)
					{
						$db=new database();	
						
				$modify_error_code=$db->update("UPDATE prd_pension_employee SET
					error_code='".$x_value['error_code']."',response_status='".$x_value['status']."',capture_date='".$x_value['capture_date']."'
					WHERE  ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and emp_id_const='".$x_value['emp_id']."' and emp_pension_status='1'"
					);
					
					$error_code=$db->fetch_table( "SELECT error_code FROM prd_pension_employee WHERE  emp_id_const='".$x_value['emp_id']."'");
					$error=$error_code[0]['error_code'];
					//echo $error;
					//$exampleEncoded = json_encode($error);
					//$con = json_decode($exampleEncoded, true);
					//$b=explode(",",$error);
					
					
						if($error==0)
						{
							
							
							$modify_emp_status=$db->update("UPDATE prd_pension_employee SET
					emp_pension_status='2'
					WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and emp_id_const='".$x_value['emp_id']."' and emp_pension_status='1'"
					);
					
						}
						else
						{
							
							$modify_emp_status=$db->update("UPDATE prd_pension_employee SET
					emp_pension_status='3'
					WHERE ((SUBSTRING(cast(emp_retirement_date as text),1,7)<='".date("Y-m", strtotime("+11 months"))."') OR reason='1993') and emp_id_const='".$x_value['emp_id']."' and emp_pension_status='1'"
				);			
						}
					
												
						/*if($k==0)
						{
							$value1.="'".$x_value['emp_id']."'";
							
						}
						else
						{
							
								
							$value1.=","."'".$x_value['emp_id']."'";
							
							}
								$k=$k+1;*/
					
							//}
				
					
					}
				   
					
					
			}
		 
		}

	
		
		if ($sql_insert_response > 0 && $upd_request > 0) 
		{ 
			pg_query("COMMIT");
			$this->dbClose($db);
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE609</errorCode><response>
			Response Received</response></iOSMSResponse>', 200);
		} 
		else 
		{
			pg_query("ROLLBACK");
			$this->dbClose($db);
			$this->response('<?xml version="1.0"?><iOSMSResponse><errorCode>WE610</errorCode><response>
			Response Error. Please Try Again</response></iOSMSResponse>', 200);
		}
}

/*
* 	Encode array into JSON
*/

private function json($data) 
{
	if (is_array($data)) 
	{
		return json_encode($data);
	}
}

private function update_request_new_data($request_array) 
{
	$transaction_id = $request_array['transaction_id']; 
	$total_data_count = $request_array['total_data_count']; 
	$request_id = $request_array['request_id']; 
	$db=new database();
	$sql_update_request_status = $db->update( "UPDATE prd_pension_seed_request
	SET  
	request_served='1', request_served_time='now()',  
	success_transaction_id='" . $transaction_id . "',
	request_served_total_data='". $total_data_count ."', 
	status_remarks='DATA SEND'
	WHERE request_id='". $request_id."'"); 
	/*$res_update_request_status = pg_query($dbc, $sql_update_request_status);
	$rows_update_request_status = pg_affected_rows($res_update_request_status);*/
	$this->dbClose($db);
	//return $sql_update_request_status;
}



private function update_request_modified_data($request_array) 
 {
	$db=new database();
	 $transaction_id = $request_array['transaction_id'];
	$total_data_count = $request_array['total_data_count'];
	$request_id = $request_array['request_id'];
	$osms_type = $request_array['osms_type'];
	$employee_id_modified = $request_array['employee_id_modified'];
	$sql_update_request_status = $db->update("UPDATE prd_pension_seed_request
	SET  
	request_served='1', request_served_time='now()',  
	success_transaction_id='" . $transaction_id . "',
	request_served_total_data='". $total_data_count ."', 
	status_remarks='DATA SEND'
	WHERE request_id='". $request_id."'"); 
 }
}

// Initiiate Library

$api = new API;
$api->processApi();

?>