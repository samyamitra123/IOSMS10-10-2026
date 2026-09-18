<?php


class zp_ps_gp_class
{
    /* Member variables */
	var $pro_val;
	var $uni_val;
	
	/* Member functions */
	
	/*--------ZP EMP PROFILE START ----------*/
      
	function fun_common($tcode,$code)
	{
		/*$db=new database();
		$code_data = $db->fetch_table("
									SELECT code, description
									FROM prd_dise_code_master;
									");*/
		foreach ($code as $key) 
		{
			if($key['code'] == $tcode)
			{
				return $this->pro_val=$key['description'];
			}
		}
	}
	
	function fun_payband($val)
	{
		$db = new database();
		$data = $db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
		return $data[0]['payband_name'];
	}
	function fun_payscale($val)
	{
		$db = new database();
		$data = $db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
		return $data[0]['payscale_range'];
	}
	function fun_dist($val)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
		return $dist_data2[0]['district_name'];
	}
	function fun_bank($val)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
	}
	function date_frmt($original_date)
	{
		if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL)
		{
			return "---";
		}
		else
		{
			$old=explode("-",$original_date);
			$new=$old[2]."-".$old[1]."-".$old[0];
			return $new;
		}
	}
	function fun_grade_pay($val)
	{
		$db = new database();
		$dist_data2 = $db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $dist_data2[0]['grade_amount'];
	}	
		
	function fun_emp_type($val)
	{
		$db = new database();
		$emp_type = $db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."';");
		return $emp_type[0]['description'];
	}
	
	function fun_state($val)
	{
		if($val=='32')
		{
			return 'WEST BENGAL';
		}
		else
		{
			return 'OTHERS';
		}
	}
	
	function fun_desig($dcode, $code_desig)
	{
		foreach ($code_desig as $key) 
		{
			if($key['designation_id'] == $dcode)
			{
				return $key['designation_name'];
			}
		}
	}
	
	function assign_rand_value($num) 
	{
		// accepts 1 - 36
		switch($num) 
		{
			case "1"  : $rand_value = "A"; break;
			case "2"  : $rand_value = "B"; break;
			case "3"  : $rand_value = "C"; break;
			case "4"  : $rand_value = "D"; break;
			case "5"  : $rand_value = "E"; break;
			case "6"  : $rand_value = "F"; break;
			case "7"  : $rand_value = "G"; break;
			case "8"  : $rand_value = "H"; break;
			case "9"  : $rand_value = "I"; break;
			case "10" : $rand_value = "J"; break;
			case "11" : $rand_value = "K"; break;
			case "12" : $rand_value = "L"; break;
			case "13" : $rand_value = "M"; break;
			case "14" : $rand_value = "N"; break;
			case "15" : $rand_value = "O"; break;
			case "16" : $rand_value = "P"; break;
			case "17" : $rand_value = "Q"; break;
			case "18" : $rand_value = "R"; break;
			case "19" : $rand_value = "S"; break;
			case "20" : $rand_value = "T"; break;
			case "21" : $rand_value = "U"; break;
			case "22" : $rand_value = "V"; break;
			case "23" : $rand_value = "W"; break;
			case "24" : $rand_value = "X"; break;
			case "25" : $rand_value = "Y"; break;
			case "26" : $rand_value = "Z"; break;
			case "27" : $rand_value = "0"; break;
			case "28" : $rand_value = "1"; break;
			case "29" : $rand_value = "2"; break;
			case "30" : $rand_value = "3"; break;
			case "31" : $rand_value = "4"; break;
			case "32" : $rand_value = "5"; break;
			case "33" : $rand_value = "6"; break;
			case "34" : $rand_value = "7"; break;
			case "35" : $rand_value = "8"; break;
			case "36" : $rand_value = "9"; break;
		}
		return $rand_value;
	}
	
	function get_rand_numbers($length) 
	{
		if ($length>0) 
		{
			$rand_id="";
			for($i=1; $i<=$length; $i++) 
			{
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(27,36);
				$rand_id .= $this->assign_rand_value($num);
			}
		}
		return $rand_id;
	}
	
	function get_rand_letters($length) 
	{
		if ($length>0) 
		{
			$rand_id="";
			for($i=1; $i<=$length; $i++) 
			{
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,26);
				$rand_id .= $this->assign_rand_value($num);
			}
		}
		return $rand_id;
	}
	
	function check_rand_code()
	{
	   $str=$this->get_rand_numbers(4);
	   $str2=$this->get_rand_letters(4);
	
	   $rand_code=$str2.$str;
	   $db1 = new database();
	   $code= $db1->fetch_table("SELECT emp_system_code FROM prd_employee_master WHERE emp_system_code='".$rand_code."'");
		
		if(count($code)>0)
		{
			check_rand_code();
		}
		return $rand_code;
	}
		
    
    function check_unique_emp_const_id()
	{            
		
        $db = new database();
	
    
        //$check_id=$db->fetch_table("select max(substr(emp_id_const,7,12)) as emp_id_max from prd_employee_master"); //priveous_code
        
        $check_id=$db->fetch_table("SELECT nextval('prd_emp_id_const_sqn')");
		
		$empid= "PE".date("Y");
      //  $empid= "PE".'2018';
		//$digit=$check_id['0']['emp_id_max']; //privious_code
        $sum=$check_id['0']['nextval'];
        
		//$sum=$digit+1; //privious_code
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		 $id_const=$empid.$inc; 
	
		$unique_check=$db->fetch_table("select emp_id_pk from prd_employee_master where emp_id_const='".$id_const."'");
		
		if(!empty($unique_check))
		{
           
			$this->check_unique_emp_const_id();
            
		}
		else
		{
           
			return $id_const;
		}
	}
	 
    
    
	function check_ret_unique_emp_const_id()
	{
		$db = new database();
		$check_id=$db->fetch_table("select max(substr(emp_id_const,7,12)) as emp_id_max from prd_employee_master");
		
		
		$digit=$check_id['0']['emp_id_max'];
		$sum=$digit+1;
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		$empid= date("Y").$inc;
		$id_const=$empid."PE";
	
		$unique_check=$db->fetch_table("select emp_id_pk from prd_employee_master where emp_id_const='".$id_const."'");
		
		if(!empty($unique_check))
		{
			$this->check_ret_unique_emp_const_id();
		}
		else
		{
			return $id_const;
		}
	}
    
    /*--------ZP EMP PROFILE END ----------*/
	
	
	
	function drn_generation($party_code)
	{
		//$current_year=date('Y');
		$db = new database();
		//$check_id=$db->fetch_table("SELECT max(substr(CAST(drn_number as text),10,6)) as id_max FROM prd_block_bill_details WHERE status='1' AND substr(CAST(drn_number as text),7,3)='".$party_code."'AND salary_monthyear LIKE '".$current_year."%'");
		
		//$financial_yr_start_seq=$db->fetch_table("select drn_number FROM prd_block_bill_details WHERE salary_monthyear='".date('Y')."04' AND status='1' AND substr(CAST(drn_number as text),7,3)='".$party_code."'");
		
		$get_drn_seq=$db->fetch_table("SELECT nextval('drn_no_seq') AS drn_no_seq_val;");
		
		$cur_monyr=date('Ym');
		$sum=$get_drn_seq[0]['drn_no_seq_val'];
		
		/*if($check_id['0']['id_max']=="")
		{
			$digit=0;
		}
		else if(count($financial_yr_start_seq)==0 && date('m')=='04')
		{
			$digit=0;
		}
		else
		{
			$digit=$check_id['0']['id_max'];
		}
		$sum=$digit+1;*/
		$inc=str_pad($sum,6,'0',STR_PAD_LEFT);
		 $id_drn=$cur_monyr.$party_code.$inc;
		 $unique_check=$db->fetch_table("select block_bill_pk,drn_number FROM prd_block_bill_details WHERE drn_number='".$id_drn."' AND status='1'");
		
		if(!empty($unique_check))
		{
			//$unique_check[0]['drn_number'];
			//drn_generation($party_code);
			$this->drn_generation($party_code);
		}
		else
		{
			return $id_drn;
		}
	}

}


?>