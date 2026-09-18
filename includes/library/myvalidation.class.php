<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Validation {
	//var $blank_exp='/[([\<])([^\>]{1,})*([\>])]/i';
	
    function __construct() {
       
    }
    
    // validation integer or not
    public function int_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_INT)) 		//integer type validation
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation boolean or not
    public function bool_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_BOOLEAN))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation float or not
    public function float_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_FLOAT))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation IP Address or not
    public function ip_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_IP))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation URL or not
    public function url_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_URL))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
	
	public function blank_validation($value){				//blank validation for input box
		if(!filter_var($value, $blank_exp)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function blank_select($value){					//blank validation for drop down list
		if($value=="" || $value==NULL){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_validation($value, $arr_exp){	//validation to match given pattern
		foreach($arr_exp as $key){
			if(!preg_match($value, $key)){
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}
	public function pattern_math_chcarecter($value){					//pattern validation charecter
		if(preg_match('/^[\sA-Za-z ]*$/', $value) ==''){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_match_alphanumeric($value){					//pattern validation alphanumeric
		if(preg_match('/^[\sA-Za-z0-9- ]*$/', $value) ==''){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_match_email($value){					//pattern email
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_number($value){					//pattern integer
		if(preg_match("/[^0-9]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_match_csf($value){					//pattern CSF
		if(preg_match("/[-!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_match_thi($value){					//Treasury head insert,only - / allowed
		if(preg_match("/[!$%^&*()_+|#~=`{}\[\]:\";'<>?,.]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
		public function pattern_match_webmaster($value){					//webmaster(only [](). allowed)
		if(preg_match("/[!$%^&*()_+|#~=`{}\[\]:\";'<>?]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function pattern_match_webmaster2($value){					//webmasteronly ,(comma) allowed
		if(preg_match("/[-!$%^&*()_+|~=`{}\[\]:\";'<>?.\/]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function date_match1($value){                       //Date validation
		if(strlen($value)!=10){
			return FALSE;
		}else{
			$date_arr=explode('-', $value);
			$count_arr=count($date_arr);
			if($count_arr!=3){
				return FALSE;
			}else {
			  $day = $date_arr[0];
			  $month = $date_arr[1];
			  $year = $date_arr[2];
			  if(checkdate($month, $day, $year)){
				  return TRUE;
			  }else{
				  return FALSE;
			  }
			}
		}
	}
	
	
public function date_match($value)
{
	if(preg_match("/[(((((0[1-9])|(1\d)|(2[0-8]))-((0[1-9])|(1[0-2])))|((31-((0[13578])|(1[02])))|((29|30)-((0[1,3-9])|(1[0-2])))))-((20[0-9][0-9]))|(29-02-20(([02468][048])|([13579][26]))))]/i" , $value))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

 public function pattern_number2($value){					//for IP validation
		if(preg_match("/[^0-9.]/i", $value)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	
	public function code_match($tcode,$code){                // whether code exsist in code master or not checking      
$flag=0;
foreach ($code as $key) {
		if($key['code'] == $tcode){
				$flag=1;
		}
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 
 public function payband_match($tcode,$code){                // whether code exsist in code master or not checking      
$flag=0;
foreach ($code as $key) {
		if($key['payband_code'] == $tcode){
				$flag=1;
		}
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 
  public function zp_desig_match($tcode,$code){                // whether code exsist in code master or not checking      
$flag=0;
foreach ($code as $key) {
		if($key['designation_id'] == $tcode){
				$flag=1;
		}
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 
 public function district_match($tcode,$code){                // whether code exsist in code master or not checking      
$flag=0;
foreach ($code as $key) {
		if($key['district_id_pk'] == $tcode){
				$flag=1;
		}
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 
public function pay_scale_match($tcode,$code){                // whether code exsist in pay scale master or not checking      
$flag=0;
foreach ($code as $key) {
		if(trim($key['payscale_code']) == $tcode){
				$flag=1;
		}
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 
public function grade_pay_match($grade_pay,$code){                // whether code exsist in grade pay master or not checking      
$flag=0;
$i=0;
foreach ($code as $item) {
		if(trim($item['grade_code'])==$grade_pay){
				$flag=1;
				break;
		}
		$i++;
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
 }
 

 
 public function valid_bank($bank,$code){				// whether bank_code exsist in bank master or not checking 
$flag=0;
$i=0;
foreach ($code as $item) {
		if(trim($item['bank_code'])==$bank){
				$flag=1;
				break;
		}
		$i++;
	}
	if($flag==1){
		return TRUE;
	}
	else{
		return FALSE;
	}
	 
 }
 
 public function pan_pattern_match($value){
	 if(!preg_match("/^([a-zA-Z]){3}([P]){1}([a-zA-Z]){1}([0-9]){4}([a-zA-Z]){1}?$/",$value)){
		 return FALSE;
	 }
	 else{
		 return TRUE;
	 }
 }
 
  public function valid_age($dob){
	 if (time() < strtotime('+18 years', strtotime($dob))) {
   	 	return FALSE; 
	}
	else{
		return TRUE;
	}
 }

}

$validator = new Validation();

//$validator->permission_validation('asasassaa', array('/','_'))
?>
