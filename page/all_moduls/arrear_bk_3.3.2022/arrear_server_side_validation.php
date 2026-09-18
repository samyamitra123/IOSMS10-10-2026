<?	
			/*if($validator->blank_select($tchcd) == FALSE || $validator->pattern_number($tchcd) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee Code.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($emp_id_pk) == FALSE || $validator->pattern_number($emp_id_pk) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($schcd) == FALSE || $validator->pattern_number($schcd) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong GP Code.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($tchname) == FALSE || $validator->pattern_match_csf($tchname) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee Name.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($bankname) == FALSE || $validator->pattern_match_csf($bankname) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Bank Name.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($accountno) == FALSE || $validator->pattern_number($accountno) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Account Number.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($bank_ifsc) == FALSE || $validator->pattern_match_csf($bank_ifsc) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Bank IFSC Number.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			if($validator->blank_select($code) == FALSE || $validator->pattern_match_csf($code) == FALSE){
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Salary Source.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($pay_in_band) == FALSE || $validator->pattern_number($pay_in_band) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Pay in Pay Band.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($consolidated_pay) == FALSE || $validator->pattern_number($consolidated_pay) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Consolidated Pay.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($grade_pay) == FALSE || $validator->pattern_number($grade_pay) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Grade Pay.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($da) == FALSE || $validator->pattern_number($da) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid DA.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($hra) == FALSE || $validator->pattern_number($hra ) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid HRA.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($ma) == FALSE || $validator->pattern_number($ma) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid MA.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($conv_allow) == FALSE || $validator->pattern_number($conv_allow) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Conveyance Allowance.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			
			else if(($validator->blank_select($gpf) == FALSE || $validator->pattern_number($gpf) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>GPF amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($pf_loan) == FALSE || $validator->pattern_number($pf_loan) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>PF loan amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($p_tax) == FALSE || $validator->pattern_number($p_tax) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>PTAX amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($i_tax) == FALSE || $validator->pattern_number($i_tax) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>ITAX amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($gsli) == FALSE || $validator->pattern_number($gsli) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>GSLI amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($gross) == FALSE || $validator->pattern_number($gross) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Gross amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			
			else if($deduction=="yes_ovd1")
			{
			     if($cooperative_loan==1) 
						{
					if(($validator->blank_select($cooperative_loan_val) == FALSE || $validator->pattern_match_csf($cooperative_loan_val) == FALSE))
						   {
						$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Co-operative Loan Recovery amount is not valid.<strong></div>';
						header('location:emp_sal_requisition.php'.$query_string);
						exit(0);
					        }
						
						}
						
				if($hbl==1) 
						{
					if(($validator->blank_select($hbl_val) == FALSE || $validator->pattern_match_csf($hbl_val) == FALSE))
						   {
						$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Festival Advance Recovery amount is not valid.<strong></div>';
						header('location:emp_sal_requisition.php'.$query_string);
						exit(0);
					        }
						
						}
						
								
			     if($festival==1) 
						{
					if(($validator->blank_select($festival_val) == FALSE || $validator->pattern_match_csf($festival_val) == FALSE))
						   {
						$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Festival Advance Recovery amount is not valid.<strong></div>';
						header('location:emp_sal_requisition.php'.$query_string);
						exit(0);
					        }
						
						}			
						
						
			}
			
			else if(($validator->blank_select($net) == FALSE || $validator->pattern_number($net) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Net amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($overdrawn) == FALSE || $validator->pattern_number($overdrawn) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Overdrawn amount is not valid.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($validator->blank_select($salary_type) == FALSE || $validator->pattern_number($salary_type) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please choose valid salary type.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if(($part_day!='' && ($validator->blank_select($part_day) == FALSE || $validator->pattern_number($part_day) == FALSE)))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid part day.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			else if($is_overdrawn=='yes_ovd'){
			
			
			 if(($cause_msg=='' && ($validator->blank_select($cause_msg) == FALSE || $validator->pattern_match_csf($cause_msg) == FALSE)))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			}
			else if($salary_type=='2')
             {
		
		if(($part_salary_cause_msg=='' || ($validator->blank_select($part_salary_cause_msg) == FALSE || $validator->pattern_match_csf($part_salary_cause_msg) == FALSE)))
			{
				
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause for part salary.<strong></div>'; 
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
		
	}
	else if($salary_type=='8')
             {
		
		if(($no_salary_cause_msg=='' || ($validator->blank_select($no_salary_cause_msg) == FALSE || $validator->pattern_match_csf($no_salary_cause_msg) == FALSE)))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause for no salary.<strong></div>'; 
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
		
	}
			
	    	else if(($validator->blank_select($interim_relief) == FALSE || $validator->pattern_number($interim_relief) == FALSE))
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Interim Relief.<strong></div>';
				header('location:emp_sal_requisition.php'.$query_string);
				exit(0);
			}
			
		*/ ?>