	<?php
    error_reporting(1);
    //---------------------------- LIBRARY INCLUDE ----------------------------
    //copy this two lines to every page
   
    
    require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require_once '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
    require_once '../../includes/library/all_function.php';
   
   
   

   
    
    function code_gp($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
    return $arr[0]['gp_name'];																	
                                                    
    }
    
    function code_block($val)
    {
    
  
    $db=new database();
    
    $arr =$db->fetch_table("SELECT block_name
    FROM prd_location_master_block as b
    inner join prd_location_master_gp as gp 
    on gp.block_id_fk=b.block_id_pk
    WHERE gp.gp_id_pk='".$val."'");
    return $arr[0]['block_name'];																	
                                                    
    }
    function code_ps($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
    return $arr[0]['ps_name'];																	
                                                    
    }
    
    function code_district($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
    return $arr[0]['district_name'];																	
                                                    
    }
    
  
    
    $db=new database();
	
    $crypto = new cryptography();
    
    
    $employeeId = ($_SESSION['emp_id_const'])?$_SESSION['emp_id_const']:$_POST['emp_id_const'];
    $app =  $_POST['app_no'];
    //print_r($employeeId); exit;
	if($app != '')
	{
		$statement="application_id='".$app."'";
	}
	else
	{
		$statement="emp_id_const='".$employeeId."'";
	}
  $Query = "
    SELECT 
    emp_id_const,
    emp_first_name,
    emp_second_name,
    emp_last_name,
    emp_desig,
    emp_first_join_date,
    gp_id_fk,
    block_code,
    ps_id_fk,
    ps_code,
    zp_id_fk,
    emp_group,
    last_pay_drawn,
    death_date,
    nomine_details,
    part_a_from_status,
    part_b_from_status,
    part_c_from_status,
    applicant_name,
    relation_employee,
    applicant_birth,
    applicant_sex,
    nationality,
    applicant_mobile_no,
    applicant_religion,
    applicant_quali,
    applicant_cast,
    differently_able,
    present_address_state,
    present_house_no,
    present_street,
    present_town_vill,
    present_post_office,
    present_pin,
    present_police_station,
    present_district,
    family_pension,
    death_gratuity,
    group_insurance,
    encashment_leave,
    any_payment,
    total_lumsum,
    calculation_hospitalization,
    caluculation_expen,
    interest_calculation,
    move_immovable,
    income_dependant_employee,
    total_income,
    percentage_monthly_income,
    part_d_from_status,
    name_officer_first,
    name_officer_second,
    name_officer_third,
    memo_no_ec,
    memo_date_ec,
    date_inquery,
    comment_officer,
    candidate_fulfil_rules,
    candidate_fulfil_remarks,
    clear_vacany_roster,
    clear_vacany_roster_remarks,
    candidate_fulfil_all,
    candidate_enquiry_recommedation,
    candidate_fulfil_all_remarks,
    candidate_enquiry_recommedation_remarks,
    candidate_any_relaxation,
    candidate_any_relaxation_remarks,
    check_age_value,
    check_education_value,
    proposal,
  note,
  histroy,
  status,
  application_id,
  employee_type,
  candidate_p_roforma,
  candidate_p_roforma_remarks,
  candidate_unfit_cer,
  candidate_unfit_cer_remarks,
  divorce_decree,
  divorce_decree_remarks,
  date_submision_inquery,
  application_date,
  name_designation_first,
  name_designation_second,
  name_designation_third,
  candidate_part_from,
  candidate_part_from_remarks,
  vist_sport_remarks,
  vist_sport,
  candidate_favour,
  candidate_favour_remarks,
  authenticat_hoo,
  authenticat_hoo_remarks
    
    FROM 
    intra_pri_cg_profile_master 
    WHERE
    ".$statement."";
   // print_r($Query);
    $employee_data = $data=$db->fetch_table($Query);
    //print_r($data); exit;
	$candidate_favour=$data[0]['candidate_favour'];
	$candidate_favour_remarks=$data[0]['candidate_favour_remarks'];
    $vist_sport_remarks=$data[0]['vist_sport_remarks'];
	$vist_sport=$data[0]['vist_sport'];
    $application_date=$data[0]['application_date'];
	 $date_submision_inquery=$data[0]['date_submision_inquery']; 
	$divorce_decree=$data[0]['divorce_decree'];
    $divorce_decree_remarks=$data[0]['divorce_decree_remarks'];
	$candidate_unfit_cer=$data[0]['candidate_unfit_cer'];
    $candidate_unfit_cer_remarks=$data[0]['candidate_unfit_cer_remarks'];
	$candidate_p_roforma=$data[0]['candidate_p_roforma'];
    $candidate_p_roforma_remarks=$data[0]['candidate_p_roforma_remarks'];
	
	
	$name_designation_first=$data[0]['name_designation_first'];
    $name_designation_second=$data[0]['name_designation_second'];
    $name_designation_third=$data[0]['name_designation_third'];
	
	  $employee_type=$data[0]['employee_type'];
    $application_id=$data[0]['application_id'];
	
	  $employee_type=$data[0]['employee_type'];
    $emp_id_const=$data[0]['emp_id_const'];
    $nomine_details=$data[0]['nomine_details']; 
    $application_id=$data[0]['application_id'];
    $emp_first_name=$data[0]['emp_first_name'];
    $emp_second_name=$data[0]['emp_second_name'];
    $emp_last_name=$data[0]['emp_last_name'];
    $emp_desig=$data[0]['emp_desig'];
    $gp_id_fk=$data[0]['gp_id_fk'];
    $ps_id_fk=$data[0]['ps_id_fk'];
    $zp_id_fk=$data[0]['zp_id_fk'];
    $emp_first_join_date=$data[0]['emp_first_join_date'];
    $group=$data[0]['emp_group'];
    $last_pay=$data[0]['last_pay_drawn'];
    $death_date=$data[0]['death_date'];
    $applicant_name=$data[0]['applicant_name'];
    $applicant_relation=$data[0]['relation_employee'];
    $applicant_birth=$data[0]['applicant_birth'];
    $applicant_sex=$data[0]['applicant_sex']; 
    $nationality=$data[0]['nationality'];
    $applicant_mobile_no=$data[0]['applicant_mobile_no'];
    $applicant_religion=$data[0]['applicant_religion']; 
    $applicant_quali=$data[0]['applicant_quali'];
    $applicant_cast=$data[0]['applicant_cast'];
    $differently_able=$data[0]['differently_able']; 
   $present_address_state=$data[0]['present_address_state'];
    $present_house_no=$data[0]['present_house_no'];
    $present_street=$data[0]['present_street'];
    $present_town_vill=$data[0]['present_town_vill'];
    $present_post_office=$data[0]['present_post_office'];
    $present_pin=$data[0]['present_pin'];
    $present_police_station=$data[0]['present_police_station'];
    $present_city_district=$data[0]['present_district'];
    $family_pension=$data[0]['family_pension'];
    $death_gratuity=$data[0]['death_gratuity'];
    $group_insurance=$data[0]['group_insurance'];
    $encashment_leave=$data[0]['encashment_leave'];
    $any_payment=$data[0]['any_payment'];
    $total_lumsum=$data[0]['total_lumsum'];
    $calculation_hospitalization=$data[0]['calculation_hospitalization'];
    $caluculation_expen=$data[0]['caluculation_expen'];
    $interest_calculation=$data[0]['interest_calculation'];
    $move_immovable=$data[0]['move_immovable'];
    $income_dependant_employee=$data[0]['income_dependant_employee'];
    $total_income=$data[0]['total_income'];
    $percentage_monthly_income=$data[0]['percentage_monthly_income'];
    $name_officer_first=$data[0]['name_officer_first'];
    $name_officer_second=$data[0]['name_officer_second'];
    $name_officer_third=$data[0]['name_officer_third'];
    $memo_no_ec=$data[0]['memo_no_ec'];
    $memo_date_ec=$data[0]['memo_date_ec'];
    $date_inquery=$data[0]['date_inquery']; 
    $comment_officer=$data[0]['comment_officer']; 
    $candidate_fulfil_rules=$data[0]['candidate_fulfil_rules'];
    $candidate_fulfil_remarks=$data[0]['candidate_fulfil_remarks']; 
    //$group='634';
    $clear_vacany_roster=$data[0]['clear_vacany_roster']; 
    $clear_vacany_roster_remarks=$data[0]['clear_vacany_roster_remarks'];
    $candidate_fulfil_all=$data[0]['candidate_fulfil_all'];  
    $candidate_fulfil_all_remarks=$data[0]['candidate_fulfil_all_remarks'];
    $candidate_enquiry_recommedation=$data[0]['candidate_enquiry_recommedation'];
    $candidate_enquiry_recommedation_remarks=$data[0]['candidate_enquiry_recommedation_remarks'];
    
    $candidate_any_relaxation=$data[0]['candidate_any_relaxation'];
    $candidate_any_relaxation_remarks=$data[0]['candidate_any_relaxation_remarks']; 
    
    $check_age_value=$data[0]['check_age_value'];
    $check_education_value=$data[0]['check_education_value']; 
    $proposal=$data[0]['proposal'];
	
	 $note=$data[0]['note'];
	  $histroy=$data[0]['histroy'];
	  $candidate_part_from=$data[0]['candidate_part_from']; 
	$candidate_part_from_remarks=$data[0]['candidate_part_from_remarks'];
	$authenticat_hoo=$data[0]['authenticat_hoo']; 
	$authenticat_hoo_remarks=$data[0]['authenticat_hoo_remarks'];
	
    
    if($gp_id_fk!='0')
    {
    
    $db=new database();
    
    $id =$db->fetch_table("SELECT district_name FROM prd_location_master_district as d
    
    inner join prd_location_master_block as b on b.district_id_fk=d.district_id_pk
    inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk 
    WHERE gp.gp_id_pk='".$gp_id_fk."' ");
    
    
    
    }
    
    else if($ps_id_fk!='0')
    {
    $id =$db->fetch_table("SELECT district_name FROM prd_location_master_district as d
    
    inner join prd_location_master_panchayat_samiti as p on p.district_id_fk=d.district_id_pk
    
    WHERE p.ps_id_pk='".$ps_id_fk."' ");
    }
    
    
 	if($data[0]['zp_id_fk'] != '' && $data[0]['zp_id_fk'] != 0)
	{
	
	//echo ("select designation_id as code,designation_name as description  from zpemp_emp_desig_master where designation_id='".$data[0]['emp_desig']."' "); die;
	
	
	$db = new database();
	$arr_desig_d = $db->fetch_table("select designation_id as code,designation_name as description  from zpemp_emp_desig_master where designation_id='".$data[0]['emp_desig']."' ");
	
	}
	
	else
	{
	
	
	$db = new database();
	$arr_desig_d = $db->fetch_table("select code,description from prd_dise_code_master where code='".$data[0]['emp_desig']."'");
	}
    
    
    $db = new database();
    $arr_desig = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '12%' and code in('1210','1211','1202','1203','1204','1206','1209','1212') order by code");
    
    $arr_relation = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='1' order by code");
    
    
    ?>
    <div class="content">
    
    
    
    <div class="row" id="cont">
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
    <div class="col-sm-12" style=" width: 95%; margin-left: 2%">
    
    
    </br>
    
 
    <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
    <div id="form_show" class="dashcontenr" style="height:auto;"> 
    <form class="form-horizontal" id="first_form" method="post">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partA",4); ?>" />
    
    
 
    
 
    
   
    <h1 class="heading"> PART A( Details Of Deceased Employee )</h1>
    <div class="border"></div>
    </br>
 
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Employee ID </label>
    <div class="col-sm-3">
    <p class="text-muted" style="font-size:18px;font-weight:700;font-family: "MS Serif", "New York", serif;"><?= $emp_id_const; ?></p>
    </div>
    </div>
    
    
    <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 control-label">Proposal Name<span class="star_color">*</span></label>
    <div class="col-sm-5">
    <input type="text" class="form-control upper_case" id="proposal"   readonly="readonly" name="proposal" placeholder="proposal" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"  autocomplete="off" value="<?= $proposal ?>">
    </div></div>
    
    <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 control-label">Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="tch_fname"  readonly="readonly" name="tch_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_first_name ?>">
    </div>

    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="tch_mname"   readonly="readonly" name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_second_name ?>">
    </div>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="tch_lname"  readonly="readonly" name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_last_name ?>">
    </div>
    </div>
    
    
    <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 control-label" >Designation<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
     
    <input type="hidden" class="form-control upper_case" id="vice_desig"  readonly="readonly" name="vice_desig">
    
    <input type="text" class="form-control upper_case" id="vice_desig_des"  readonly="readonly" name="vice_desig_des" value="<?=$arr_desig_d[0]['description']?>">
   
    
   
    <?php //print("burman22345"); exit; ?>
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Date of First Joining in Service<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="first_join_date"   disabled="disabled" id="first_join_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=($emp_first_join_date=='0001-01-01') ? '' : dateshow($emp_first_join_date);?>">
    </div>
    </div>
    
    
    <div class="row mb-3" id="gp_description_div" style="display:none;">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <input type="hidden" class="form-control upper_case" id="gp_id_fk"  name="gp_id_fk"   readonly="readonly" value="" style="display:none">
    <input type="text" class="form-control upper_case" id="gp_description" disabled="disabled" name="gp_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>   
    <div class="row mb-3" id="block_description_div" style="display:none;">
    <label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <input type="text" class="form-control upper_case" id="block_description" disabled="disabled" name="block_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>              
    
    <?php if($data[0]['gp_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <input type="hidden" class="form-control upper_case" id="gp_id_fk"  name="gp_id_fk"  value="<?php echo $gp_id_fk;?>">
    <input type="text" class="form-control upper_case" id="gp_description"  disabled="disabled" name="gp_description" placeholder="" autocomplete="off"  value="<?php echo  code_gp($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>
    <div class="row mb-3" id="block_description_div">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <input type="text" class="form-control upper_case" id="block_description" disabled="disabled" name="block_description" placeholder="" autocomplete="off"  value="<?php echo  code_block($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>  
    
    
    
    <?php }

      
    ?>
    
    
    <!--<div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 control-label">District<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="district"  disabled="disabled" name="district" placeholder="District" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php //echo  $id[0]['district_name'];?>">
    </div></div>-->
    
    
    <div class="row mb-3" id="ps_description_div" style="display:none;">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="hidden" class="form-control upper_case" id="ps_id_fk"  name="ps_id_fk"  value="" style="display:none">
    <input type="text" class="form-control upper_case" id="ps_description" disabled="disabled" name="ps_description" placeholder="Last Pay Drawn" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>
    
    <?php if($data[0]['ps_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="hidden" class="form-control upper_case" id="ps_id_fk"  name="ps_id_fk"  value="<?php echo $ps_id_fk;?>">
    <input type="text" class="form-control upper_case" id="ps_description" disabled="disabled" name="ps_description" placeholder="" autocomplete="off"  value="<?php echo  code_ps($ps_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
    
    </div>
    </div>
    
    <?php }?>
    
    
    
    <div class="row mb-3" id="zp_description_div" style="display:none;">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="hidden" class="form-control upper_case" id="zp_id_fk"  name="zp_id_fk"  value="" style="display:none">
    <input type="text" class="form-control upper_case" id="zp_description" disabled="disabled" name="zp_description" placeholder="Last Pay Drawn" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>
    
    
    <?php if($data[0]['zp_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="hidden" class="form-control upper_case" id="zp_id_fk"  name="zp_id_fk"  value="<?php echo $zp_id_fk;?>">
    <input type="text" class="form-control upper_case" id="zp_description" disabled="disabled" name="zp_description" placeholder="" autocomplete="off"  value="<?php echo  code_district($zp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    </div>
    
    <?php }?>
    
    
    
    <div class="row mb-3" >
    <label for="inputPassword3" class="col-sm-3 control-label">GROUP<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and code like '63%' and code!='100' and code!='105' and code!='106' order by code");
    ?>
    <select class="form-control" name="group"  disabled="disabled" id="group">
    <option value="">-Please Select-</option>
    <?php foreach($arr as $key){ echo $key['code']. '<br />'; ?>
    <option value="<?=$key['code']?>" <? if($group==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">EMPLOYEE TYPE<span class="star_color">*</span></label>
     <div class="col-sm-3">
    <?php
     
    $db = new database();
    $arr = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='2'  order by code");
    ?>
    <select class="form-control" name="employee_type"   disabled="disabled" id="employee_type" onChange="return employee_typ_f(this.value);">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ echo $key['code']. '<br />'; ?>
    <option value="<?=$key['code']?>" <? if($employee_type==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </div>
    </div>
    
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Last Pay Drawn(GROSS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="last_pay_drawn"   disabled="disabled" name="last_pay_drawn" placeholder="Last Pay Drawn" autocomplete="off"  value="<?= $last_pay ?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
  
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Nomine Details If Any</label>
    <div class="col-sm-3">
    
    <?php if($data[0]['part_a_from_status']=='0' || $data[0]['part_a_from_status']==''){?>
    <input type="text" class="form-control upper_case" id="nomine_details"  disabled="disabled"name="nomine_details" placeholder="NOMINE DETAILS" autocomplete="off" value="<?php echo $nomine_details;?>">
    <?php }else{?>
    <input type="text" class="form-control upper_case" id="nomine_details"disabled="disabled"  name="nomine_details" placeholder="NOMINE DETAILS" autocomplete="off" value="<?php echo $nomine_details;?>">
    <?php }?>
    </div>
    </div>
    <?php if($employee_type=='1993')
   {?>
   <div class="row mb-3">
	   <label for="inputPassword3" class="col-sm-3 control-label" >Date of Death<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="Death_date" id="Death_date"    readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">
    </div>
    </div>
  <?php }
   else
   {?>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label" id="Death_date_date" style="display:none">Date of Death<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="Death_date" id="Death_date"  style="display:none"  readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">
    </div>
    </div>
    <?php }?>
    
    
    <?php if($employee_type=='1994')
   {?>
   <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label" id="premature_date_date">Premature retirement date<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="premature_date"  disabled="disabled" id="premature_date" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">
    </div>
    
    </div>
   <?php }
   else
   {?>
   <div class="row mb-3">
   <label for="inputPassword3" class="col-sm-3 control-label" id="premature_date_date" style="display:none">Premature retirement date<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="premature_date" id="premature_date" style="display:none" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">
    </div>
    </div>
   <?php }?>
    <?php 
   
    //$db=new database();
	
    $data_relastion=$db->fetch_table("
    SELECT family_name,family_age,family_quali,family_dependent,
	family_relation
	from intra_pri_cg_relation_master_submit
	WHERE
    employee_id='".$emp_id_const."'");
	
	
   
	
	?>
    <div class="table-responsive">
    <table id="tbl_family" width="120%">
    <h3 class="heading"> FAMILY DETAILS(Legal heir) </h3>
    <div class="border"></div>
    </br>
    
    <?php if(count($data_relastion)){ foreach($data_relastion as $item){?>
    <tr id="tr0">
    <td>Family Member Name</td>
    <td>
    <input type="text"  class="form-control upper_case"  disabled="disabled"id="family_name0" name="family_name[]" placeholder="NAME" autocomplete="off" value="<?php echo $item['family_name'];  ?>" >
    </td>
    
    <td>Family Member Age</td>
    <td>
    <input type="text" class="form-control upper_case" disabled="disabled" id="family_age0" maxlength="2" name="family_age[]" placeholder="AGE" autocomplete="off" value="<?php echo $item['family_age']?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </td>
    
    <td>Family Member Qualification</td>
    <td>
    
    <select class="form-control upper_case" id="family_quali0" disabled="disabled" name="family_quali[]" autocomplete="off" value="">
        <option>---- Please Select--- </option>
        
        <? foreach($arr_desig as $key){ $key['code']. '<br />'; ?>
    <option value="<?= $key['code']; ?>" <? if($item['family_quali']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </td>
    
    <td>Family Member Relationship</td>
    <td>
    <select class="form-control upper_case" id="family_relation0" disabled="disabled" name="family_relation[]" autocomplete="off" value="">
        <option>---- Please Select--- </option>
         <? foreach($arr_relation as $key){ $key['code']. '<br />'; ?>
        <option value="<?= $key['code']; ?>" <? if($item['family_relation']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </td>
    <td>Family Depen</td>
    <td>
    <select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" autocomplete="off" value="">
         <option value="">Please Select</option>
    <option value="1" <? if($item['family_dependent']=='1' || $item['family_dependent']=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($item['family_dependent']=='0' || $item['family_dependent']=='0') { echo "selected"; } ?>>NO</option>
    
    </select>
    </td>
    <?php  }} else { ?>

	
	<tr id="tr0">
    <td>Family Member Name</td>
    <td>
    <input type="text"  class="form-control upper_case"  disabled="disabled" id="family_name0" name="family_name[]" placeholder="NAME" autocomplete="off" value="<?php echo $item['family_name'];  ?>" >
    </td>
    
    <td>Family Member Age</td>
    <td>
    <input type="text" class="form-control upper_case"  disabled="disabled" id="family_age0" maxlength="2" name="family_age[]" placeholder="AGE" autocomplete="off" value="<?php echo $item['family_age']?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </td>
    
    <td>Family Member Qualification</td>
    <td>
    
    <select class="form-control upper_case" id="family_quali0"  disabled="disabled" name="family_quali[]" autocomplete="off" value="">
        <option>---- Please Select--- </option>
        
        <? foreach($arr_desig as $key){ $key['code']. '<br />'; ?>
    <option value="<?= $key['code']; ?>" <? if($item['family_quali']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </td>
    
    <td>Family Member Relationship</td>
    <td>
    <select class="form-control upper_case" id="family_relation0" disabled="disabled" name="family_relation[]" autocomplete="off" value="">
        <option>---- Please Select--- </option>
         <? foreach($arr_relation as $key){ $key['code']. '<br />'; ?>
        <option value="<?= $key['code']; ?>" <? if($item['family_relation']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </td>
    <td>Family Depen</td>
    <td>
    <select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" autocomplete="off" value="">
         <option value="">Please Select</option>
    <option value="1" <? if($item['family_dependent']=='1' || $item['family_dependent']=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($item['family_dependent']=='0' || $item['family_dependent']=='0') { echo "selected"; } ?>>NO</option>
    
    </select>
    </td>
	
    <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row0" class="add_row0" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"></td>
    </tr>
    <?php }?>
    </table> 
    </div>
    
    
    
    
    
    </form>                   
    <!------------------------------------------------ PART A closed ------------------------------------------- -->  
     <!------------------------------------------------ PART B START ------------------------------------------- -->  
    
    
   
    
    <div class="row" id="cont1">
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
    <div class="col-sm-12">
    <h1 class="heading"> PART B( Applicant Deatils )</h1>
    <div class="border"></div>  
    </br>
    <form class="form-horizontal" id="second_form" method="post">
    
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partB",4); ?>" />    
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Name Of the Applicant<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <?
    $db = new database();
    $arr = $db->fetch_table("select family_name from intra_pri_cg_relation_master_submit where family_age>='18' order by family_name");
    ?>
    <select class="form-control upper_case"  name="applicant_name"   disabled="disabled" id="applicant_name">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ echo $key['family_name']. '<br />'; ?>
    <option   value="<?=$key['family_name']?>" <? if($applicant_name==strtoupper($key['family_name'])){ echo  "selected";}?>><?= $key['family_name']; ?></option>
    <? } ?>
    </select>
    </div>

    
    <label for="inputPassword3" class="col-sm-3 control-label">Relation With Employee<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    <?
    $db = new database();
    
    $arr = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='1' order by code");
    ?>
    <select class="form-control"  name="relation_employee"  disabled="disabled"  id="relation_employee">
    <option value="" class="form-control upper_case">-Please Select-</option>
    <? foreach($arr as $key){ echo $key['code']. '<br />'; ?>
    <option  class="form-control upper_case" value="<?=$key['code']?>" <? if($applicant_relation==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    

    </div>
    </div>
    
    
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Applicant Date Of Birth<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    
    <input type="text" class="form-control" name="applicant_birth" id="applicant_birth"  disabled="disabled" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$applicant_birth=='0001-01-01' ? '' : dateshow($applicant_birth);?>">
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Gender<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
    ?>
    <select class="form-control" name="drpSex"  disabled="disabled" id="drpSex">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['code']. '<br />';?>
    <option value="<?= $key['code']; ?>" <? if($applicant_sex==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </div>
    </div>
    
    <div class="row mb-3" >
    
    <label for="inputPassword3" class="col-sm-3 control-label">Nationality<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php 
    $transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
    FROM prd_dise_code_master WHERE code IN ('559') AND length(code)=3
    ");
    ?>
    <select name="nationality" style="width:100%;" disabled="disabled" id="nationality" class="form-control">
    <option value="">---PLEASE SELECT---</option>
    <? foreach($transfer_level as $key)
    {
    
    ?>
    <option value="<?=$key['code']?>" <? if($nationality==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
    
    <? } ?>
    </select>
    </div>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Applicant Mobile No..<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case"  disabled="disabled" autocomplete="off" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $applicant_mobile_no?>" >
    </div>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Religion<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?php
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='16' and length(code)='3' ORDER BY description");
    ?>
    <select class="form-control" name="religion"  disabled="disabled" id="religion" >
    <option value="">-Please Select-</option>
    <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
    <option value="<?php echo $key['code']; ?>"<? if($applicant_religion==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
    <?php } ?>
    </select>
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Educational Qualification<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <? $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '12%' and code in('1210','1211','1202','1203','1204','1206','1209','1212') order by code");
    ?>
    <select class="form-control" name="applicant_quali" disabled="disabled"  id="applicant_quali">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['code']. '<br />'; ?>
    <option value="<?= $key['code']; ?>" <? if($applicant_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </div>
    </div>
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Caste<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <?
    $db = new database();
    $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and code like '10%' and code!='100' and code!='105' and code!='106' order by code");
    ?>
    <select class="form-control" name="applicant_cast"  disabled="disabled" id="applicant_cast">
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['code']. '<br />'; ?>
    <option value="<?= $key['code']; ?>" <? if($applicant_cast==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
    <? } ?>
    </select>
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Whether Differently Able <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="differently_able" disabled="disabled" id="differently_able" onChange="return stateDetailsView(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($differently_able=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($differently_able=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    </div>
    
    
    
      
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether application has been submitted in Prescribed Proforma in terms of 251-Emp dated 03.12.2013 and Subsequent relevant Order (Yes/No)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_p_roforma" disabled="disabled" id="candidate_p_roforma" onChange="return candidateproforma(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_p_roforma=='1' || $candidate_p_roforma=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_p_roforma=='0' || $candidate_p_roforma=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_p_roforma=='1' || $candidate_p_roforma==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_p_roforma_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_p_roforma_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled" name="candidate_p_roforma_remarks" id="candidate_p_roforma_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_p_roforma_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_p_roforma_remarks_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_p_roforma_remarks_div" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled" name="candidate_p_roforma_remarks" id="candidate_p_roforma_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_p_roforma_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
    
    
    <?php if($employee_type=='1994'){?>
       <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether Medically 'Unfit Certificate' has been submitted from the Competent Authority (in case of premature retirement due to permanent . incapacitation)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_unfit_cer" disabled="disabled" id="candidate_unfit_cer" onChange="return candidateunfitcer(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_unfit_cer=='1' || $candidate_unfit_cer=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_unfit_cer=='0' || $candidate_unfit_cer=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_unfit_cer=='1' || $candidate_unfit_cer==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_unfit_cer_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_unfit_cer_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled" name="candidate_unfit_cer_remarks" id="candidate_unfit_cer_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_unfit_cer_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_unfit_cer_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_unfit_cer_div" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled" name="candidate_unfit_cer_remarks" id="candidate_unfit_cer_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_unfit_cer_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    <?php }?>
    
    
    
         <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether Divorce Decree has been submitted by the applicant who obtained such decree before or after the death of the ex-employee ( in case of divorced daughter)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="divorce_decree" disabled="disabled" id="divorce_decree" onChange="return divorcedecree(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($divorce_decree=='1' || $divorce_decree=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($divorce_decree=='0' || $divorce_decree=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($divorce_decree=='1' || $divorce_decree==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="divorce_decree_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="divorce_decree_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" disabled="disabled" name="divorce_decree_remarks" id="divorce_decree_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $divorce_decree_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="divorce_decree_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="divorce_decree_div" >
    <textarea class="form-control upper_case" autocomplete="off" disabled="disabled"  name="divorce_decree_remarks" id="divorce_decree_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $divorce_decree_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    

    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Date of submission of Proforma application<span class="star_color">*</span></label>
    <div class="col-sm-3">
    
    
    <input type="text" class="form-control" disabled="disabled" name="application_date" id="application_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$application_date=='0001-01-01' ? '' : dateshow($application_date);?>">
    </div></div>
   
  
    <h3 class="heading">  APPLICANT  RESIDENTIAL ADDRESS</h3>
    <div class="border"></div>
    </br>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>
    <div class="col-sm-4">
        <select class="form-control" name="present_address_state"  disabled="disabled" id="present_address_state">
            <option value="">-Please Select-</option>
            <option value="32" <? if($present_address_state=='32'){ echo 'selected';}?>>West Bengal</option>
        </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Police Station <span class="star_color">*</span></label>
    <div class="col-sm-4">
        <input type="text" class="form-control upper_case"  disabled="disabled" autocomplete="off" placeholder="POLICE STATION" name="present_police_station" id="present_police_station" value="<?=$present_police_station?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
    </div>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>
    <div class="col-sm-4">
        <input type="text" class="form-control upper_case"  disabled="disabled"  autocomplete="off" placeholder="HOUSE NO" name="present_house_no" id="present_house_no" value="<?=$present_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz/(-) ');">		
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Street</label>
    <div class="col-sm-4">
        <input type="text" class="form-control upper_case"  autocomplete="off" readonly name="present_street" placeholder="STREET" id="present_street"  value="<?=$present_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
    </div>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>
    <div class="col-sm-4">
        <input type="text" class="form-control upper_case"  disabled="disabled" autocomplete="off" placeholder="TOWN/VILLAGE" name="present_town_vill" id="present_town_vill"  value="<?=$present_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>
    <div class="col-sm-4">
        <input type="text" class="form-control upper_case" disabled="disabled" autocomplete="off" placeholder="POST OFFICE" name="present_post_office" id="present_post_office" value="<?=$present_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">
    </div>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 control-label">PIN <span class="star_color">*</span></label>
    <div class="col-sm-4">
        <input type="text" class="form-control" autocomplete="off" disabled="disabled" maxlength="6" placeholder="PIN" name="present_pin" id="present_pin" value="<?=$present_pin?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label" id="present_wb_dist_label" <? if($present_address_state=='32' || $present_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>District <span class="star_color">*</span></label>
    <div class="col-sm-4" id="present_wb_dist_field" <? if($present_address_state=='32'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>
        <?php
            $db=new database();
            $district_fetch=$db->fetch_table("select * from prd_location_master_district where state_id_fk='1'");
        ?>
        <select name="present_city_district" disabled="disabled" id="present_city_district" class="form-control">
            <option value="" selected="selected">-Please Select-</option>
            <?php foreach($district_fetch as $dist_dtls){ ?>	
            <option value="<? echo $dist_dtls['district_id_pk']?>" <? if($present_city_district==$dist_dtls['district_id_pk']){ echo "selected";}?>><? echo $dist_dtls['district_name'];?></option>
            <?php	} ?>
        </select> 
    </div>
    
    </div>
    
    
  
    
    

   
    
    <!------------------------------------------------ PART B Closed ------------------------------------------- --> 
    
    
  
  
    <h1 class="heading"> PART C( Financial Statement as per Office Record & Applicant )</h1>
    <div class="border"></div>
    </br>
    
    <form class="form-horizontal" id="third_form" method="post" >
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partC",4); ?>" />  
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Family Pension(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="family_pension" id="family_pension" placeholder="FAMILY PENSION"  onKeyPress="return keyRestrict(event,'0123456789');" onkeyup="return calculation();" value="<?=$family_pension?>">
    </div>
    
    </div>
    

    <h3 class="heading"> LUMP SUM TERMIAL DUES</h3>
    <div class="border"></div>
    </br>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Death Gratuity(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly"  name="death_gratuity" id="death_gratuity" placeholder="DEATH GRATUTITY"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$death_gratuity?>">
    </div>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Group Insurance(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off"  readonly="readonly" name="group_insurance" id="group_insurance" placeholder="GROUP INSURANCE"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$group_insurance?>">
    </div>
    </div>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Encashment Of Leave(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly name="encashment_leave" id="encashment_leave" placeholder="ENCASHMENT OF LEAVE"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$encashment_leave?>">
    </div>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Disclosed Source of Income(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="any_payment" id="any_payment" placeholder="ANY OTHER PAYMENT"  onKeyPress="return keyRestrict(event,'0123456789');"   onkeyup="return calculation();" value="<?=$any_payment?>">
    </div>
    </div>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">TOTAL(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="total_lumsum" id="total_lumsum" placeholder="TOTAL"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?=$total_lumsum?>">
    </div>
    </div>
    
    <div class="border"></div>
    </br>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Expenses incurred on account of hospitalization(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="calculation_hospitalization" id="calculation_hospitalization" placeholder="Expenses incurred on account of hospitalization"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$calculation_hospitalization?>">
    </div>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Amount to be considered for monthly income(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off"  readonly="readonly" name="caluculation_expen" id="caluculation_expen" placeholder=""  onKeyPress="return keyRestrict(event,'0123456789');" value="<?=$caluculation_expen?>">
    </div>
    </div>
    
    
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Monthly Interest Calculation(@8%)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="interest_calculation" id="interest_calculation" placeholder="Interest Calculation"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?=$interest_calculation?>">
    </div>
    
    </div>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Monthly income from Other movable Or immovable(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="move_immovable" id="move_immovable" placeholder="Monthly income from Other movable Or immovable"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$move_immovable?>">
    </div>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Monthly income from dependant of the ex-employee if any(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly"  name="income_dependant_employee" id="income_dependant_employee" placeholder="Monthly income from dependant of the ex-employee"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$income_dependant_employee?>">
    </div>
    </div>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Total Monthly Income Of the Family(in RS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="total_income" id="total_income" placeholder="TOTAL"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?=$total_income?>">
    </div>
    
    
    </div>
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Last Pay Drawn(GROSS)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" id="last_pay_drawn_partc"  readonly="readonly" name="last_pay_drawn_partc" placeholder="Last Pay Drawn" autocomplete="off"  value="<?= $last_pay ?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Percentage Of total monthly income in realtion to Gross Salary(%)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off"  readonly="readonly" name="percentage_monthly_income" id="percentage_monthly_income" placeholder="Monthly income from dependant of the ex-employee"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$percentage_monthly_income?>">
    </div>
    </div>
    
    
  
    
    
   
    
    
    <!------------------------------------------------ PART B closed ------------------------------------------- --> 
      <!------------------------------------------------ PART C START ------------------------------------------- --> 

    <h1 class="heading"> PART D( Details of 3-Men E.C Report )</h1>
    <div class="border"></div>
    </br>
   
    <h5 class="heading"> Name Of Officials appointed on members of 3-Men E.C</h5>
    <div class="border"></div>
    </br>
     <!------------------------------------------------ PART C CLOSED ------------------------------------------- --> 
          <!------------------------------------------------ PART D START ------------------------------------------- --> 
    <form class="form-horizontal" id="fourth_form" method="post">
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partD",4); ?>" /> 
    <div class="row mb-3">
    
        <label for="inputPassword3" class="col-sm-3 control-label">1st Office Member OF E.C<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="name_officer_first" id="name_officer_first" placeholder="FirstName Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_first?>">
        </div>
    <label for="inputPassword3" class="col-sm-3 control-label">1st Office Member OF E.C Designation<span class="star_color">*</span></label>
    
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case"  readonly="readonly"autocomplete="off" name="name_designation_first" id="name_designation_first" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_first?>">
    </div>
    
    </div>
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">2nd Office Member OF E.C<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="name_officer_second" id="name_officer_second" placeholder="Second Name Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_second?>">
    </div>
   <label for="inputPassword3" class="col-sm-3 control-label">2st Office Member OF E.C Designation<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case"  readonly="readonly"autocomplete="off" name="name_designation_second" id="name_designation_second" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_second?>">
    </div>
    </div>
    
    <div class="row mb-3">
    
    
    
    <label for="inputPassword3" class="col-sm-3 control-label">3rd Office Member OF E.C<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case" autocomplete="off" readonly="readonly" name="name_officer_third" id="name_officer_third" placeholder="Third Name Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_third?>">
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">3rd Office Member OF E.C Designation<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control upper_case"  readonly="readonly"autocomplete="off" name="name_designation_third" id="name_designation_third" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_third?>">
    </div>
    </div>
    
    
    <div class="border"></div>
    </br>
    
    <div class="row mb-3" >
        <label for="inputPassword3" class="col-sm-3 control-label">Memo no of formation of 3 MEN E.C<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <input  type="text" class="form-control" name="memo_no_ec" id="memo_no_ec" readonly="readonly" placeholder="MEMO NUMBER" value="<?php echo $memo_no_ec;?>" autocomplete="off" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz/(-) ');"  >
        </div>
        <label for="inputPassword3" class="col-sm-3 control-label"> Memo Date of formation of 3 MEN E.C<span class="star_color">*</span></label>
        <div class="col-sm-3">
        <input type="text" class="form-control" id="memo_date_ec"  readonly="readonly" name="memo_date_ec"   value="<?php if(count($arr)==0){echo  $memo_date_ec="";}else { echo  dateshow($memo_date_ec);}?>" placeholder="DD-MM-YY"  style="background-color:#FFF;cursor:pointer;" />
        </div>
    </div>
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Date of inquery<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <input type="text" class="form-control"  id="date_inquery"   value="<?php if(count($arr)==0){echo  $date_inquery="";}else { echo  dateshow($date_inquery);}?>" placeholder="DD-MM-YY" disabled style="background-color:#FFF;cursor:pointer;" />
    </div>
    </div>
    
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether the Recommedation by the enquiry committe has been unanimous<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_enquiry_recommedation" readonly="readonly" id="candidate_enquiry_recommedation" onChange="return candidateenquiryrecommedation(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_enquiry_recommedation=='1' || $candidate_enquiry_recommedation=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_enquiry_recommedation=='0' || $candidate_enquiry_recommedation=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_enquiry_recommedation=='1' || $candidate_enquiry_recommedation==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_enquiry_recommedation_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_enquiry_recommedation_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_enquiry_recommedation_remarks" id="candidate_enquiry_recommedation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_enquiry_recommedation_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_enquiry_recommedation_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_enquiry_recommedation_div" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_enquiry_recommedation_remarks" id="candidate_enquiry_recommedation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_enquiry_recommedation_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
    
     <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether the Applicant submited PART I and PART II application<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_part_from"  disabled="disabled"id="candidate_part_from" onChange="return candidatepartfrom(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_part_from=='1' || $candidate_part_from=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_part_from=='0' || $candidate_part_from=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_part_from=='1' || $candidate_part_from==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_part_from_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_part_from_div" style="display:none" >
    <textarea class="form-control upper_case" readonly="readonly" autocomplete="off" name="candidate_part_from_remarks" id="candidate_part_from_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_part_from_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_part_from_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_part_from_div" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_part_from_remarks" id="candidate_part_from_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_part_from_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether the 3-MEN E.C Visit Applicant location<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="vist_sport" id="vist_sport" disabled="disabled" onChange="return vistsport(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($vist_sport=='1' || $vist_sport=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($vist_sport=='0' || $vist_sport=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($vist_sport=='1' || $vist_sport==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="vist_sport_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off"  readonly="readonly" name="vist_sport_remarks" id="vist_sport_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $vist_sport_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_part_from_div" >
    <textarea class="form-control upper_case" autocomplete="off"  readonly="readonly" name="vist_sport_remarks" id="vist_sport_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $vist_sport_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
 
 <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether Recommended for the Employment in favour of Applicant <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_favour" id="candidate_favour" disabled="disabled" onChange="return candidatefavour(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_favour=='1' || $candidate_favour=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_favour=='0' || $candidate_favour=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_favour=='1' || $candidate_favour==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_favour_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_favour_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" disabled="disabled" name="candidate_favour_remarks" id="candidate_favour_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_favour_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_favour_div" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled"name="candidate_favour_remarks" id="candidate_favour_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_favour_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Date of Submission inquery report<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <?php //print_r($date_submision_inquery); ?>
    <input type="text" class="form-control" id="date_submision_inquery" disabled="disabled" name="date_submision_inquery"  value="<?php if(count($arr)==0){echo  $date_submision_inquery="";}else { echo  dateshow($date_submision_inquery);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />
    </div>
    </div>
    
    
    <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Comments Of Controlling Officer<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="comment_officer" id="comment_officer" placeholder="Observationr"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $comment_officer;?></textarea>
    </div>
    </div>
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Is any relaxation of rule etc. required<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_any_relaxation" readonly="readonly" id="candidate_any_relaxation" onChange="return candidateanyrelaxation(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_any_relaxation=='1' || $candidate_any_relaxation=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_any_relaxation=='0' || $candidate_any_relaxation=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_any_relaxation=='1' || $candidate_any_relaxation==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_any_relaxation_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_any_relaxation_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_any_relaxation_remarks" id="candidate_any_relaxation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_any_relaxation_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_any_relaxation_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_any_relaxation_div" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_any_relaxation_remarks" id="candidate_any_relaxation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_any_relaxation_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    <div class="row mb-3">
    <div class="col-sm-3"></div>
    <div class="col-sm-3" id="age_check" style="display:none">
    
    
    <label for="inputPassword3" class="col-sm-2 control-label" id="check_age" >Age</label>
    <input type="checkbox" id="check_age_value" readonly="readonly" name="check_age_value" value="" >
    
    <label for="check_age_value"><span></span></label>

    
    <label for="inputPassword3" class="col-sm-3 control-label" id="check_age" >Educational & Qualification</label>
    <input type="checkbox" id="check_education_value" readonly="readonly" name="check_education_value"  value="" >
    <label for="check_education_value"><span></span></label>
    </div>
    
    </div>
    
    
    
    <?php if($candidate_any_relaxation=='1'){
    
    ?>
    
    <div class="row mb-3">
    <div class="col-sm-3"></div>
    <div class="col-sm-3" id="age_check_new">
    <?php if($check_age_value==1){?>
    <label for="inputPassword3" class="col-sm-2 control-label" id="check_age" >Age</label>
    
    
    <input type="checkbox"  <?php if($check_age_value==1){echo "checked";}?> readonly="readonly" id="check_age_value" name="check_age_value" value="1" >
    <label for="check_age_value"><span></span></label>
    <?php }?>
    <?php if($check_education_value==1){?>
    <label for="inputPassword3" class="col-sm-3 control-label" id="check_age" >Educational & Qualification</label>
    
    <input type="checkbox"  <?php if($check_education_value==1){echo "checked";}?>  readonly="readonly" id="check_education_value" name="check_education_value" value="1" >
    
    <label for="check_education_value"><span></span></label>
    <?php }?>
    </div>
    
    
    </div>
    
    <?php }?>
     
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether the candidate fulfils the requirement of Recruitment Rules for the Post <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_fulfil_rules" readonly="readonly" id="candidate_fulfil_rules" onChange="return candidatefulfilrules(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_fulfil_rules=='1' || $candidate_fulfil_rules=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_fulfil_rules=='0' || $candidate_fulfil_rules=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_fulfil_rules=='1' || $candidate_fulfil_rules==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_no_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_fulfil_no_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_fulfil_remarks" id="candidate_fulfil_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_no_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_fulfil_no_div" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_fulfil_remarks" id="candidate_fulfil_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
    
    
    
        
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Summary sheet duly authenticated by HOO<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="authenticat_hoo" id="authenticat_hoo"  disabled="disabled"onChange="return authenticathoo(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($authenticat_hoo=='1' || $authenticat_hoo=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($authenticat_hoo=='0' || $authenticat_hoo=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($authenticat_hoo=='1' || $authenticat_hoo==''){
    
    ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="authenticat_hoo_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="authenticat_hoo_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled"name="authenticat_hoo_remarks" id="authenticat_hoo_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $authenticat_hoo_remarks;?></textarea>
    </div>
    
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="authenticat_hoo_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="authenticat_hoo_div" >
    <textarea class="form-control upper_case" autocomplete="off"  disabled="disabled"name="authenticat_hoo_remarks" id="authenticat_hoo_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $authenticat_hoo_remarks;?></textarea>
    </div>
    
    <?php }?>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether a clear Vacancy on Examted category is available as per 100 pointer Roster vide Notification No. 50-EMP dated 01.03.2011<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="clear_vacany_roster" readonly="readonly" id="clear_vacany_roster" onChange="return clearvacanyroster(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($clear_vacany_roster=='1' || $clear_vacany_roster=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($clear_vacany_roster=='0' || $clear_vacany_roster=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    <?php if($clear_vacany_roster=='1' || $clear_vacany_roster==''){?>
    <label for="inputPassword3" class="col-sm-3 control-label" id="clear_vacany_roster_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="clear_vacany_roster_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="clear_vacany_roster_remarks" id="clear_vacany_roster_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $clear_vacany_roster_remarks;?></textarea>
    </div>
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="clear_vacany_roster_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="clear_vacany_roster_div">
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="clear_vacany_roster_remarks" id="clear_vacany_roster_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $clear_vacany_roster_remarks;?></textarea>
    </div>
    <?php }?>
    </div>
    
    <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether the applicant has fulfiled all the criteria as laid down in Notification No.251-EMP dated 03.12.2013 read with 26-EMP dated 01.03.2016<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="candidate_fulfil_all" readonly="readonly" id="candidate_fulfil_all" onChange="return candidatefulfilall(this.value);">
    <option value="">Please Select</option>
    <option value="1" <? if($candidate_fulfil_all=='1' || $candidate_fulfil_all=='1') { echo "selected"; } ?>>YES</option>
    <option value="0" <? if($candidate_fulfil_all=='0' || $candidate_fulfil_all=='0') { echo "selected"; } ?>>NO</option>
    </select>
    </div>
    
    <?php if($candidate_fulfil_all=='1' || $candidate_fulfil_all==''){?>
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_all_lable"style="display:none">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_fulfil_all_div" style="display:none" >
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_fulfil_all_remarks" id="candidate_fulfil_all_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_all_remarks;?></textarea>
    </div>
    <?php }else{  ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_all_lable">Remarks<span class="star_color">*</span></label>
    <div class="col-sm-3" id="candidate_fulfil_all_div">
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="candidate_fulfil_all_remarks" id="candidate_fulfil_all_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_all_remarks;?></textarea>
    </div>
    </div>
    <?php }?>
    
    
   <div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">Coments of the Appointment authority<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <textarea class="form-control upper_case" autocomplete="off"  readonly="readonly" name="note" id="note" placeholder="BRIF NOTE"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $note;?></textarea>
    </div>
    </div>
    
    <!--<div class="row mb-3">
    
    <label for="inputPassword3" class="col-sm-3 control-label">OBSERVATION<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <textarea class="form-control upper_case" autocomplete="off" readonly="readonly" name="histroy" id="histroy" placeholder="OBSERVATION"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $histroy;?></textarea>
    </div>
    </div>-->
  
   
    
    
 
    
          <!------------------------------------------------ PART D closed ------------------------------------------- --> 
   </br>
    </br>
    <h5 class="heading"> Document to be Uploaded</h5>
    </br>
    <div class="border"></div>
    </br>
    
    <?php if($employee_type=='1993'){?>
    <div class="row mb-3">
    <?php
    $db=new database();
		
		$arr_file_1 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 1 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Attached Death Certificate Of Deceased Employee <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_1[0]['flag']?>" target="_blank"><?php echo substr($arr_file_1[0]['file_name'],6); ?></a>
    
    </div>
       <?php }?>
    <?php
    $db=new database();
		
		$arr_file_2 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 2 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Application For appoinment on Compassionate ground <span class="star_color">*</span></label>
    <div class="col-sm-2">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_2[0]['flag']?>" target="_blank"><?php echo substr($arr_file_2[0]['file_name'],6); ?></a>
    </div>
    
    
    </div>
    
    
    
    <div class="row mb-3">
     <?php
    $db=new database();
		
		$arr_file_19 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 19 and application_id='".$application_id."'"); ?>
        <?php ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Photo Of Applicant<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_19[0]['flag']?>" target="_blank"><?php echo substr($arr_file_19[0]['file_name'],6); ?></a>
    </div>
    
    
    
      
     <?php
    $db=new database();
		
		$arr_file_18 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 18 and application_id='".$application_id."'"); ?>
        <?php ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Annexure A & Annexure B<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_18[0]['flag']?>" target="_blank"><?php echo substr($arr_file_18[0]['file_name'],6); ?></a>
    </div>
    </div>
    
    
    
    
    <div class="row mb-3">
     <?php
    $db=new database();
		
		$arr_file_3 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 3 and application_id='".$application_id."'"); ?>
        <?php ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Financial Statement by applicant<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_3[0]['flag']?>" target="_blank"><?php echo substr($arr_file_3[0]['file_name'],6); ?></a>
    </div>
     <?php
    $db=new database();
		
		$arr_file_4 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where  status = '1' AND flag= 4 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">3-Men E.C Report <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_4[0]['flag']?>" target="_blank"><?php echo substr($arr_file_4[0]['file_name'],6); ?></a>
    </div>
    </div>
    
    <div class="row mb-3">
    <?php
    $db=new database();
		
		$arr_file_5 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 5 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label"> Noc in form Of Affidavit<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_5[0]['flag']?>" target="_blank"><?php echo substr($arr_file_5[0]['file_name'],6); ?></a>
    </div>
    <?php
    $db=new database();
		
		$arr_file_6 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where  status = '1' AND flag= 6 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Age Proof Of Applicant <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_6[0]['flag']?>" target="_blank"><?php echo substr($arr_file_6[0]['file_name'],6); ?></a>
    </div>
    </div>
    
    <div class="row mb-3">
    <?php
    $db=new database();
		
		$arr_file_7 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 7 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Educational & Qualification(Highest)<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_7[0]['flag']?>" target="_blank"><?php echo substr($arr_file_7[0]['file_name'],6); ?></a>
    </div>
    <?php
    $db=new database();
		
		$arr_file_8 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 8 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">ID proof(Aadhaar/Voter Card etc.) <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_8[0]['flag']?>" target="_blank"><?php echo substr($arr_file_8[0]['file_name'],6); ?></a>
    </div>
    </div>
    
    
    
    <div class="row mb-3">
    <?php
    $db=new database();
		
		$arr_file_11 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 11 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">PPO Order</label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_11[0]['flag']?>" target="_blank"><?php echo substr($arr_file_11[0]['file_name'],6); ?></a>
    </div>
    <?php
	if($applicant_cast!='101')
	 {
    $db=new database();
		
		$arr_file_16 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 16 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Caste Certificate <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_16[0]['flag']?>" target="_blank"><?php echo substr($arr_file_16[0]['file_name'],6); ?></a>
    </div>
    <?php }?>
    </div>
    
    <div class="row mb-3">
    <?php
    $db=new database();
		
		$arr_file_17 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where status = '1' AND flag= 17 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Legali Certificate<span class="star_color">*</span></label>
    <div class="col-sm-3">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_17[0]['flag']?>" target="_blank"><?php echo substr($arr_file_17[0]['file_name'],6); ?></a>
    </div>
    
    </div>
    
    
    
    
    <?php if($calculation_hospitalization!=''){?>
    <div class="row mb-2">
    <?php
    $db=new database();
		
		$arr_file_9 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where  status = '1' AND flag= 9 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Expenses incurred on account of hospitalization<span class="star_color">*</span></label>
    <div class="col-sm-2">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_9[0]['flag']?>" target="_blank"><?php echo substr($arr_file_9[0]['file_name'],6); ?></a>
    </div>
    
    </div>
    <? }?>
    
     <?php if($employee_type=='1994'){?>
    <div class="row mb-2">
    <?php
    $db=new database();
		
		$arr_file_10 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where  status = '1' AND flag= 10 and application_id='".$application_id."'"); ?>
        <?php ?>
    <label for="inputPassword3" class="col-sm-3 control-label">Unfit Certificate<span class="star_color">*</span></label>
    <div class="col-sm-2">
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_10[0]['flag']?>" target="_blank"><?php echo substr($arr_file_10[0]['file_name'],6); ?></a>
    </div>
    
    </div>
    <? }?>
    
    <?php 
      $user=$_SESSION['user_info']['stake_user_code']; 
	   
	     $check=substr($user,-5);
		 $check1=substr($user,-4);
	   
	   if($check=="DPRDO")
	   {
		  // echo 222; die;
		   $user_stack="DPRDO"; 
	   }
	   
	   if($check1=="COMM")
	   {
		  // echo 222; die;
		   $user_stack="COMM"; 
	   }
	   
	   ?>
    <?php if($user_stack=="DPRDO"){?>
    <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_12 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='12' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By DPRDO E-copy<span class="star_color">*</span></label>
    
     <?php  if($arr_file_12[0]['file_name']==''){?>
    <div class="col-sm-6">  
    <input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("12",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >
    </div>
      <?php  }else{?>
    <div class="col-sm-6">    
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_12[0]['flag']?>"><?php echo substr($arr_file_12[0]['file_name'],6); ?></a>
    </div> 
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("12",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>    
    <?php  }?>
     
    

    
  </div>
  
  <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_13 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='13' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By DPRDO Check Memo:<span class="star_color">*</span></label>
    
     <?php  if($arr_file_13[0]['file_name']==''){?>
    <div class="col-sm-6">  
    <input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("13",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >
    </div>
      <?php  }else{?>
    <div class="col-sm-6">    
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_13[0]['flag']?>" target="_blank"><?php echo substr($arr_file_13[0]['file_name'],6); ?></a>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("13",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    <?php  }?>
     
    
    
    
  </div>
<?php }?>



    <?php if($user_stack=="COMM"){?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_12 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='12' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By DPRDO E-copy<span class="star_color">*</span></label>
    <div class="col-sm-6">
     <?php  if($arr_file_12[0]['file_name']==''){?>
   <!-- <input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("12",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" > -->
      No E-copy Document Upload By DPRDO
      <?php  }else{?>
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_12[0]['flag']?>" target="_blank"><?php echo substr($arr_file_12[0]['file_name'],6); ?></a>
    <?php  }?>
     
    </div>
    <!--<div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("12",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>-->
    
  </div>
  
  <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_13 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='13' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By DPRDO Check Memo:<span class="star_color">*</span></label>
    <div class="col-sm-6">
     <?php  if($arr_file_13[0]['file_name']==''){?>
    <!--<input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("13",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >-->
      No Check Memo Document Upload By DPRDO
      <?php  }else{?>
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_13[0]['flag']?>" target="_blank"><?php echo substr($arr_file_13[0]['file_name'],6); ?></a>
    <?php  }?>
     
    </div>
    <!--<div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("13",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>-->
    
  </div>
    
    
    <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_14 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='14' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By COMMISSIONER E-copy<span class="star_color">*</span></label>
    
     <?php  if($arr_file_14[0]['file_name']==''){?>
    <div class="col-sm-6">  
    <input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("14",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >
    </div>
      <?php  }else{?>
    <div class="col-sm-6">      
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_14[0]['flag']?>" target="_blank"><?php echo substr($arr_file_14[0]['file_name'],6); ?></a>
    </div>
        <div class="col-sm-6"><a id="del"  onclick="del('<?php echo $crypto->encode("14",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    <?php  }?>
     
    

    
  </div>
  
  <div class="row mb-3">
    <?php 
    $db=new database();
		
		$arr_file_15 = $db->fetch_table("select file_name,flag from intra_pri_file_upload where emp_id_const = '".$emp_id_const."' AND status = '1' AND flag='15' and application_id='".$application_id."'"); ?>
    
    <label for="inputPassword3" class="col-sm-3 control-label">Document Upload By COMMISSIONER Check Memo:<span class="star_color">*</span></label>
    
     <?php  if($arr_file_15[0]['file_name']==''){?>
    <div class="col-sm-6">  
    <input type="file" class="form-control upper_case" autocomplete="off" name="dprdo_dcoument"  id="dprdo_dcoument"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("15",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >
    </div>
      <?php  }else{?>
     <div class="col-sm-6">   
    <a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download.php?application_id=<?= $application_id?>&flag=<?=$arr_file_15[0]['flag']?>" target="_blank"><?php echo substr($arr_file_15[0]['file_name'],6); ?></a>
    </div>
        <div class="col-sm-6"><a id="del"  onclick="del('<?php echo $crypto->encode("15",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    <?php  }?>
     
    

    
  </div>
<?php }?>



    </form>
    <?php if($data[0]['status']=='0'){ ?>
    
    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
    <div class="col-sm-offset-5 col-sm-7">
    
    <a type="button" id="submit6" name="submit6"  class="btn btn-success" onclick="final_save('<?php echo $crypto->encode($application_id,4); ?>','<?= $crypto->encode("final",4); ?>');">PROPOSAL SAVED</a>
   <!--<class="btn btn-success" data-bs-toggle="modal"> SUBMIT & PREVIEW </a>-->
    </div>
    </div>
    <?php }
	
	
	
 if($data[0]['status']=='1'){ ?>
     <div class="row mb-3" style="margin-left: 20%;">
    <div class="col-sm-offset-5 col-sm-7" style='padding-left: 23%;'>
    <label for="inputPassword3" class="col-sm-4 control-label">Download PDF</label><br />
    <a href="<?= $config['base_url']?>page/intra_pri/pdf_cg.php?emp_id_const=<?php echo $crypto->encode($emp_id_const,4) ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a>
    </div>
    </div>
<?php }?>

	
    <div id="forward_div" style="display:none;">		
		<div class="row mb-3">
		<label for="inputPassword3" class="col-sm-6 col-form-label" >Forwarded To: </label>
		<div class="col-mb-3" >
		<input type="hidden" id="application_id_o" name="application_id_o" value="<?php echo $data[0]['application_id']; ?>" >
		<input type="hidden" id="service_type" name="service_type" value="<?php echo '3'; ?>" >
		<input type="hidden" id="emp_id_const" name="emp_id_const" value="<?php echo $data[0]['emp_id_const']; ?>" />
		<input type="hidden" id="sub_menu" name="sub_menu" value="<?php echo '3'; ?>" />
		<input type="hidden" id="for_app_rej_status" name="for_app_rej_status" value="" >
		
		
		  <select class="form-control" id="forwarding" name="forwarding" style='margin-left: 12%; width: auto; margin-top: -3%;'>
			<option value="" >-- Please Select --</option>
			<?php	
			
			$db = new database();
		
		//echo (" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");die;
		$officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
			 $forwarding_qury_ex = explode(',',$officer_name[0]['forwarding_user']); 
			//var_dump($forwarding_qury_ex);
		foreach($forwarding_qury_ex as $val){
			
			
		$forward_user = $db->fetch_table(" SELECT desig.* , master.officer_id_const as officer_id_const, master.officer_name as officer_name FROM intra_pri_designation_master as desig 
			INNER JOIN intra_pri_master as master ON master.stake_level_code= desig.designation_code
			WHERE master.stake_user_code = '".$val."' AND master.active_status='1' AND '4' = any( string_to_array( master.role_assign, ',' ) ) ");
					//var_dump($forward_user[0]['officer_id_const']);
			?>
				<option value="<?php echo $forward_user[0]['officer_id_const']; ?>" ><?php if($forward_user[0]['officer_id_const'] != NULL || $forward_user[0]['officer_id_const'] != ""){ echo $forward_user[0]['officer_name']."  (".$forward_user[0]['designation'].")"; }  ?></option>						
			<?php } ?>							
		  </select>
		</div>  
		</div> 


			<button type="button" class="btn btn-success" style="margin-top: -8%; margin-left: 55%; " id="submit_app" onclick="return may_be_for(this.id);" > MAY BE APPROVED </button>
			<button type="button" class="btn btn-danger"  style="margin-top: -8%;" onclick="return may_be_rej(this.id);" > MAY BE REJECTED </button>
		  
		  
		<div class="row mb-3" style="display:none; margin-left: -1%;" id="remarks_div" > 
			<label for="inputPassword3" class="col-sm-2 col-form-label" >Reason: </label>
			<div class="col-sm-6">
				<textarea type="text" class="form-control" name="remarks_res" id="remarks_res" placeholder="Reason" rows="5" cols="40" style='margin-left: -10%;' ></textarea>
				<button type="button" class="btn btn-primary btn-sm" id="submit_rej" name="submit_rej" onclick="return may_be_rej_send(this.id);" > SEND </button>
			</div>
		</div>
	</div>
	

      </div>
    
    
    
    
    
    

    <div class="clear"></div>
    </div>
    </div>
    </div>
    </div>
    </div>
    
    <script>
    $(document).ready(function(){
    if($('#form_show').css("visibility")=="hidden"){
    $('#form_show').removeClass("invisible").css('height', 'auto');
    }
    });
    

    


    
    //alert(f);
    
     function file_upload(k,f,app_id,emp_id){
    
    var property = document.getElementById(k).files[0];
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
    
    if(jQuery.inArray(image_extension,['pdf']) == -1){
    alert("Invalid PDF file");
	
	return false;
    }
    
    var form_data = new FormData();
    form_data.append("file",property);
    form_data.append('f',f);
	 form_data.append('app_id',app_id);
	 form_data.append('emp_id',emp_id);
	 
	// alert(app_id);
	 
	// return false;
    
    $.ajax({
      url : 'ajax_intra_pri_cg_file_upload.php',
      type : 'POST',
      data:form_data,
      contentType:false,
      cache:false,
      processData:false,
        success : function(data) {
       // alert(data);
          
         //return false;
          if(data== '1'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',false);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          
          if(data== '2'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',false);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          if(data== '3'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',false);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          if(data== '4'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',false);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          if(data== '5'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',false);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          
           if(data== '6'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',false);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          if(data== '7'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',false);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
          if(data== '15'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',false);
                  
          }
          
          if(data== '14'){
              location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',true);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
		  if(data== '13'){
               location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',false);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
		  if(data== '12'){
               location. reload();
            $("#death_cert").prop('disabled',true);
            $("#applicaton_cgound").prop('disabled',false);
            $("#f_statement").prop('disabled',true);
            $("#menec_cgound").prop('disabled',true);
            $("#noc_statement").prop('disabled',true);
            $("#age_proof").prop('disabled',true);
            $("#education_statement").prop('disabled',true);
            $("#id_proof").prop('disabled',true);
            $("#expenses_hospitalization").prop('disabled',true);
                  
          }
         
          
          alert('Uploaded....');
        },
        error:function(){
          alert('Server Error');
        }
      });
	 }
    
 function del(k,l,m){
	
	var delete_f=$("#delete_f").val(k);
	var delete_id=$("#delete_id").val(l);
	var emp_id=$("#empId").val(m);
	$('#delet').modal('toggle');
	
	
	};
    
	
	
		function final_save(k,val){
		
		//alert(1);
		$.post('<?= $config['base_url'] ?>page/intra_pri/intra_pri_final_submit.php?form_flage='+val+'&app_id='+k, function(data){
			
			//alert(data);
			
			if(data == 1){ 
			
				//$('#forward_div').show();
				$('#submit6').hide();
        window.location.href = "<?= $config['base_url'] ?>page/intra_pri/view_intra_pri_service.php";
				
			}
			//return false;
			
		//$("#employee_id").html(data);
		
		//$("#employee_id_ps").html(data);
			
		});
	
	//alert(111);
	//false;
	
	
	}
	
	
    </script>
	
	
	
<script>

function may_be_for(k){
	$("#remarks_div").hide();
	
	var officer_id_const = $("#forwarding").val();
	//alert(officer_id_const);
	//return false;
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_comm").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = 'MF';
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							window.location.reload();
						}
				});		
}


function may_be_rej(k){
	$("#remarks_div").show();
	$("#comments_div").hide();
	$("#for_app_rej_status").val('MR');
}



function may_be_rej_send(k){
	//$("#remarks_div").show();
	var officer_id_const = $("#forwarding").val();
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_res").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = $("#for_app_rej_status").val();	
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							window.location.reload();
						}
				});		
}



</script>


    
<div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">COMPASSIONATE GROUND APPLICATION</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
   
    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>
    </div>
    <div class="modal-footer">
    
    <div class="btn-group">
    <form action="intra_pri_cg_ecopy-delete.php" method="post">  
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    <input type="hidden" id="delete_id" name="delete_id" />
    <input type="hidden" id="delete_f" name="delete_f" />
    <input type="hidden" id="empId" name="empId" />
    <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
    </form>
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
    </div>
    </div>
    </div>
    
    
    </div>
    </div>