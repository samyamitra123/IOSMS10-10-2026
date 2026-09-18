<?php 
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
$db = new database();
   $post = $_POST;
   //print_r($post); exit;
   if($post['case'] == 'updatecga')
   	 {
        $Query = "SELECT column_name from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='intra_pri_cg_profile_master'";
        $Fields = $db->fetch_table($Query);
        $formA = GetFormData($post['formA']);	
        $formB = GetFormData($post['formB']);
        $formC = GetFormData($post['formC']);
        $formD = GetFormData($post['formD']);
       // print_r($formD);
       // print_r($Fields);
        $UpdateQueryArray = array(
                                    // Form A Update Section 
                                    'emp_first_name'=>$formA['tch_fname'],
                                    'emp_second_name'=>$formA['tch_mname'],
                                    'emp_last_name'=>$formA['tch_lname'],
                                    'emp_desig'=>$formA['vice_desig'],
                                    'emp_first_join_date'=>formatDate($formA['first_join_date']),
                                    'employee_type'=>$formA['employee_type'],
                                    'last_pay_drawn'=>$formA['last_pay_drawn'],
                                    'nomine_details'=>$formA['nomine_details'],
                                    'death_date'=>formatDate($formA['Death_date']),
                                    'premature_date'=>formatDate($formA['premature_date']),
                                    // Form B Update Section 
                                    'applicant_name'=>$formB['applicant_name'],
                                    'relation_employee'=>$formB['relation_employee'],
                                    'applicant_birth'=>formatDate($formB['applicant_birth']),
                                    'applicant_sex'=>$formB['drpSex'],
                                    'nationality'=>$formB['nationality'],
                                    'applicant_mobile_no'=>$formB['applicant_mobile_no'],
                                    'applicant_religion'=>$formB['religion'],
                                    'applicant_quali'=>$formB['applicant_quali'],
                                    'applicant_cast'=>$formB['applicant_cast'],
                                    'differently_able'=>$formB['differently_able'],
                                    'candidate_p_roforma'=>$formB['candidate_p_roforma'],
                                    'candidate_p_roforma_remarks'=>$formB['candidate_p_roforma_remarks'],
                                    'divorce_decree'=>$formB['divorce_decree'],
                                    'divorce_decree_remarks'=>$formB['divorce_decree_remarks'],
                                    'application_date'=>formatDate($formB['application_date']),
                                    'present_address_state'=>$formB['present_address_state'],
                                    'present_police_station'=>$formB['present_police_station'],
                                    'present_house_no'=>$formB['present_house_no'],
                                    'present_street'=>$formB['present_street'],
                                    'present_town_vill'=>$formB['present_town_vill'],
                                    'present_post_office'=>$formB['present_post_office'],
                                    'present_pin'=>$formB['present_pin'],
                                    'present_district'=>$formB['present_city_district'],
                                    // Form C Update Section 
                                    'family_pension'=>$formC['family_pension'],
                                    'death_gratuity'=>$formC['death_gratuity'],
                                    'group_insurance'=>$formC['group_insurance'],
                                    'encashment_leave'=>$formC['encashment_leave'],
                                    'any_payment'=>$formC['any_payment'],
                                    'total_lumsum'=>$formC['total_lumsum'],
                                    'calculation_hospitalization'=>$formC['calculation_hospitalization'],
                                    'caluculation_expen'=>$formC['caluculation_expen'],
                                    'interest_calculation'=>$formC['interest_calculation'],
                                    'move_immovable'=>$formC['move_immovable'],
                                    'income_dependant_employee'=>$formC['income_dependant_employee'],
                                    'total_income'=>$formC['total_income'],
                                    //'last_pay_drawn_partc'=>$formC['last_pay_drawn_partc'],
                                    'percentage_monthly_income'=>$formC['percentage_monthly_income'],
                                    // Form D Update Section 
                                    'name_officer_first'=>$formD['name_officer_first'],
                                    'name_designation_first'=>$formD['name_designation_first'],
                                    'name_officer_second'=>$formD['name_officer_second'],
                                    'name_designation_second'=>$formD['name_designation_second'],
                                    'name_officer_third'=>$formD['name_officer_third'],
                                    'name_designation_third'=>$formD['name_designation_third'],
                                    'memo_no_ec'=>$formD['memo_no_ec'],
                                    'memo_date_ec'=>formatDate($formD['memo_date_ec']),
                                    'date_inquery'=>formatDate($formD['date_inquery']),
                                    'candidate_enquiry_recommedation'=>$formD['candidate_enquiry_recommedation'],
                                    'candidate_enquiry_recommedation_remarks'=>$formD['candidate_enquiry_recommedation_remarks'],
                                    'candidate_part_from'=>$formD['candidate_part_from'],
                                    'candidate_part_from_remarks'=>$formD['candidate_part_from_remarks'],
                                    'vist_sport'=>$formD['vist_sport'],
                                    'vist_sport_remarks'=>$formD['vist_sport_remarks'],
                                    'candidate_favour'=>$formD['candidate_favour'],
                                    'candidate_favour_remarks'=>$formD['candidate_favour_remarks'],
                                    'date_submision_inquery'=>formatDate($formD['date_submision_inquery']),
                                    'comment_officer'=>$formD['comment_officer'],
                                    'candidate_any_relaxation'=>$formD['candidate_any_relaxation'],
                                    'candidate_any_relaxation_remarks'=>$formD['candidate_any_relaxation_remarks'],
                                    'candidate_fulfil_rules'=>$formD['candidate_fulfil_rules'],
                                    'candidate_fulfil_remarks'=>$formD['candidate_fulfil_remarks'],
                                    'authenticat_hoo'=>$formD['authenticat_hoo'],
                                    'authenticat_hoo_remarks'=>$formD['authenticat_hoo_remarks'],
                                    'clear_vacany_roster'=>$formD['clear_vacany_roster'],
                                    'clear_vacany_roster_remarks'=>$formD['clear_vacany_roster_remarks'],
                                    'candidate_fulfil_all'=>$formD['candidate_fulfil_all'],
                                    'candidate_fulfil_all_remarks'=>$formD['candidate_fulfil_all_remarks'],
                                    'note'=>$formD['note'],



                                    
                                 );
        $UpdateSql = updateQueryString($UpdateQueryArray);
        if($post['appId'] != '')
           {
                $sqlQuery = "SELECT emp_id_const from  intra_pri_cg_profile_master WHERE application_id='".$post['appId']."'";
                $empDataCg = $db->fetch_table($sqlQuery);            
                $sqlQuery = "UPDATE intra_pri_cg_profile_master SET ".$UpdateSql." WHERE application_id='".$post['appId']."'";
                $db->update($sqlQuery);
               // print_r($sqlQuery); exit;
                foreach($formA['id_pk'] as $key=>$relation)
                {
                    if($relation > 0)
                    {
                        $relationQuery = array(
                                                 'family_name'=>$formA['family_name'][$key],
                                                 'family_age'=>$formA['family_age'][$key],
                                                 'family_quali'=>$formA['family_quali'][$key],
                                                 'family_relation'=>$formA['family_relation'][$key],
                                                 'family_dependent'=>$formA['family_dependent'][$key]

                                              );
                        $UpdateSql = updateQueryString($relationQuery);
                        $sqlQuery = "UPDATE intra_pri_cg_relation_master_submit SET ".$UpdateSql." WHERE id_pk='".$relation."'";
                        $db->update($sqlQuery);
                    }
                    else
                    {
                      // Subikar 
                    $query="INSERT INTO intra_pri_cg_relation_master_submit
                                        (
                                            family_name,
                                            family_age,
                                            family_quali,
                                            family_relation,
                                            employee_id,
                                            family_dependent,
                                            status
                                            )
                                            VALUES 
                                                ('".$formA['family_name'][$key]."',
                                                '".$formA['family_age'][$key]."',
                                                '".$formA['family_quali'][$key]."',
                                                '".$formA['family_relation'][$key]."',
                                                '".$empDataCg[0]['emp_id_const']."',
                                                '".$formA['family_dependent'][$key]."',
                                                '1'
                                                )"; 
                    //print_r($query); exit;                            
                             $db->insert($query);                        
                    }
                }
                echo json_encode(array('success'=>1,'msg'=>'Updated Successfully'));
           }

   	 }
function formatDate($date)
    {
       $date = date('Y-m-d',strtotime($date));
       return $date;
    }
function updateQueryString($UpdateQueryArray)
    {
        $QueryStringArray = Array();
        foreach($UpdateQueryArray as $key=>$value) 
        {
            if($value == '') { continue; }
            $QueryStringArray[] = $key."='".$value."'";
        }
        return implode(', ',$QueryStringArray); 
    }
function GetFormData($formData)
  {
  	 $postData = array();
     foreach($formData as $form)
     {   
          if(strpos($form['name'], '[]'))
          {
               $keyName = str_replace('[]', '', $form['name']);
               $postData[$keyName][] = $form['value'];
          }
          else
           $postData[$form['name']] = $form['value'];
     }
     return $postData;
  }
?>