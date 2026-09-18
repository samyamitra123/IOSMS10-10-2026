	<?php

    

    session_start();

   //var_dump($_SESSION);

    /*

    echo "<pre>";

    print_r($_SESSION);

    echo "<pre>"; die;*/

    error_reporting(0);

    $time_token=time();

    $_SESSION['security_token']=$time_token;

    $enc_token=md5('369'.$time_token);

    $session = $_SESSION;

    //---------------------------- LIBRARY INCLUDE ----------------------------

    //copy this two lines to every page

    header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");

    header("Pragma: no-cache");

    

    require_once '../../includes/config/config.php';

    require_once '../../includes/config/database.config.php';

    require_once '../../includes/library/database.class.php';

    require_once '../../includes/library/cryptography.class.php';

    $common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

    //------------------------------------------------------- HEADER --------------------------------------------------------------

    require '../layout/header.php';

    //---------------------------------- MENU -------------------------------------------------------------------------------------

    require '../layout/menu.php';

    //-----------------------------Business Logic----------------------------------------------------------------------------------

    

    if($_GET['confirm'] == 'success'){

    $msg='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';

    }else if($_GET['confirm'] == 'false'){

    $msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';

    }

    function dateshow($dateval)

    {	

    

    //echo $dateval;

    $date=substr($dateval,0,10);

    //return $date;

    $datearr=explode('-',$date);

    $dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];

    return $dob=='--'?'':$dob;

    }

   

    

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

	

	function desig_code($val)

    {

    

    //echo 222; 

    $db=new database();

    

	

	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('1114','1115','1116','1117','1118','1119','1120','1122','1123','9001','9002','9003','9004','9005','9006','9007','9008','9009','9010','9011')  where code='".$val."'order by code");

    

    return $arr[0]['description'];

	return $arr[0]['code'];																		

                                                    

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

    

    

    $data=$db->fetch_table("

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

	premature_date,

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

    emp_id_const='".$_SESSION['emp_id_const']."'");

    //print_r($data); exit;

	//date_submision_inquery

	$application_date=$data[0]['application_date'];

	 $date_submision_inquery=$data[0]['date_submision_inquery']; 

	$divorce_decree=$data[0]['divorce_decree'];

    $divorce_decree_remarks=$data[0]['divorce_decree_remarks'];

	$candidate_unfit_cer=$data[0]['candidate_unfit_cer'];

    $candidate_unfit_cer_remarks=$data[0]['candidate_unfit_cer_remarks'];

	$candidate_p_roforma=$data[0]['candidate_p_roforma'];

    $candidate_p_roforma_remarks=$data[0]['candidate_p_roforma_remarks'];

	

	$employee_type=$data[0]['employee_type'];

    $application_id=$data[0]['application_id'];

    $emp_id_const=$data[0]['emp_id_const'];

    $nomine_details=$data[0]['nomine_details']; 

    

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

	$premature_date=$data[0]['premature_date'];

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

	

	$name_designation_first=$data[0]['name_designation_first'];

    $name_designation_second=$data[0]['name_designation_second'];

    $name_designation_third=$data[0]['name_designation_third'];

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

	$vist_sport_remarks=$data[0]['vist_sport_remarks'];

	$vist_sport=$data[0]['vist_sport'];

	$candidate_favour=$data[0]['candidate_favour'];

	$candidate_favour_remarks=$data[0]['candidate_favour_remarks'];

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

	$arr_de = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='3' order by code");

    

    

    ?>

    <div class="content">

    <? require 'common_back_btns_intra_pri.php'; ?>

    <div class="welcome_msg">

		<?php 

		$db = new database();

		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");

		

		//var_dump($officer_name);

		?>

		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>

		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>

    </div>

    

    

    <div class="row" id="cont">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12" style=" width: 95%; margin-left: 2%">

    <h1 class="heading">PROPOSAL FOR EMPLOYMENT ON COMPASSIONATE GROUND APPLICATION</h1>

    <div class="message"></div>

    <div class="border"></div>

    </br>

    <?

    

    if($_SESSION['msg']){

    echo $_SESSION['msg'];

    

    unset($_SESSION['msg']);

    

    }

    ?>  

    

    

    <?php 

    if($msg){

    echo $msg;

    echo "<br/>";

    }

    if($error_msg){

    echo $error_msg;

    }

    ?>

 

    <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>

    <div id="form_show" class="dashcontenr" style="height:auto;"> 

    <form class="form-horizontal" id="first_form" method="post" action="intra_pri_cg_profile_submit.php#cont1" onsubmit="return valid_code();">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    

    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partA",4); ?>" />

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-2 control-label" id="iosms" >Registered iOSMS</label>

    <div class="col-sm-1">

    <input type="checkbox" id="iosms_registered" name="iosms_registered" onClick="return ser();" >

    <label for="iosms_registered"><span></span></label>

    </div>

    <!--<label for="inputPassword3" class="col-sm-2 control-label" id="non_iosms" >Non Registered iOSMS</label>-->

    <div class="col-sm-1">

    <!--<input type="checkbox" id="non_iosms_registered" name="non_iosms_registered"  >

    <label for="non_iosms_registered"><span></span></label>-->

    </div>

    

    <label for="inputEmail3" class="col-sm-3 control-label" id="search" style="display:none">Search<span class="star_color">*</span></label>

    <div class="col-sm-2">

    <input type="text" class="form-control upper_case" id="search1"  name="search1" placeholder="EMPLOYEE ID"  onkeyup="return empplyee_details_fetch(this.value,'<?php echo $crypto->encode(1,4); ?>')" value="<?php echo isset($emp_id_const)?$emp_id_const:''; ?>"  maxlength="12" style="display:none">

    </div>

    

    </div>

    <?php if($application_id!=''){ ?>

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">APPLICATION ID: </label>

    <div class="col-sm-3">

    <p class="text-muted" style="font-size:18px;font-weight:700;font-family: "MS Serif", "New York", serif;"><?php echo $application_id; ?></p>

    </div>

    </div>

    

    <?php  }?>

    

    

    

    

    <div class="row" id="cont">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12">

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

    <div class="col-sm-7">

    <input type="text" class="form-control upper_case" id="proposal"  name="proposal"  readonly="readonly" placeholder="proposal" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"  autocomplete="off" value="<?= $proposal ?>">

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

    

    

    <input type="hidden" class="form-control upper_case" id="vice_desig"  readonly="readonly" name="vice_desig" value="<?=$data[0]['emp_desig']?>">

    

    <input type="text" class="form-control upper_case" id="vice_desig_des"  readonly="readonly" name="vice_desig_des" value="<?=$arr_desig_d[0]['description']?>">

   

    

   

    

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">Date of First Joining in Service<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" name="first_join_date"   readonly="readonly" id="first_join_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$emp_first_join_date=='0001-01-01' ? '' : dateshow($emp_first_join_date);?>">

    </div>

    </div>

    

    

    <div class="row mb-3" id="gp_description_div" style="display:none;">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <input type="hidden" class="form-control upper_case" id="gp_id_fk"  name="gp_id_fk"  value="<?php echo$gp_id_fk; ?>" style="display:none">

    <input type="text" class="form-control upper_case" id="gp_description" readonly="readonly" name="gp_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>   

    <div class="row mb-3" id="block_description_div" style="display:none;">

    <label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <input type="text" class="form-control upper_case" id="block_description" readonly="readonly" name="block_description" placeholder="" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>              

    

    <?php if($data[0]['gp_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In GP<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <input type="hidden" class="form-control upper_case" id="gp_id_fk"  name="gp_id_fk"  value="<?php echo $gp_id_fk;?>">

    <input type="text" class="form-control upper_case" id="gp_description"  readonly="readonly" name="gp_description" placeholder="" autocomplete="off"  value="<?php echo  code_gp($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>

    <div class="row mb-3" id="block_description_div">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Block Name<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <input type="text" class="form-control upper_case" id="block_description" readonly="readonly" name="block_description" placeholder="" autocomplete="off"  value="<?php echo  code_block($gp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>  

    

    

    

    <?php }?>

    

    

    <div class="row mb-3">

    <!--<label for="inputEmail3" class="col-sm-3 control-label">District<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" id="district"  readonly="readonly" name="district" placeholder="District" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php echo  $id[0]['district_name'];?>">-->

    </div></div>

    

    

    <div class="row mb-3" id="ps_description_div" style="display:none;">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="hidden" class="form-control upper_case" id="ps_id_fk"  name="ps_id_fk"  value="<?php echo $ps_id_fk;?>" style="display:none">

    <input type="text" class="form-control upper_case" id="ps_description" readonly="readonly" name="ps_description" placeholder="Last Pay Drawn" autocomplete="off"  value="" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>

    

    <?php if($data[0]['ps_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In PS<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="hidden" class="form-control upper_case" id="ps_id_fk"  name="ps_id_fk"  value="<?php echo $ps_id_fk;?>">

    <input type="text" class="form-control upper_case" id="ps_description" readonly="readonly" name="ps_description" placeholder="" autocomplete="off"  value="<?php echo  code_ps($ps_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">

    

    </div>

    </div>

    

    <?php }?>

    

    

    

    <div class="row mb-3" id="zp_description_div" style="display:none;">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="hidden" class="form-control upper_case" id="zp_id_fk"  name="zp_id_fk"  value="<?php echo $zp_id_fk;?>" style="display:none">

    <input type="text" class="form-control upper_case" id="zp_description" readonly="readonly" name="zp_description" placeholder="Last Pay Drawn" autocomplete="off"  value="<?php echo  code_district($zp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>

    

    

    <?php if($data[0]['zp_id_fk']!='0' && $data[0]['part_a_from_status']=='1'){?>

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Place Of Posting In ZP<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="hidden" class="form-control upper_case" id="zp_id_fk"  name="zp_id_fk"  value="<?php echo $zp_id_fk;?>">

    <input type="text" class="form-control upper_case" id="zp_description" readonly="readonly" name="zp_description" placeholder="" autocomplete="off"  value="<?php echo  code_district($zp_id_fk);?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    </div>

    

    <?php }?>

    

    

    

    <div class="row mb-3" >

    <label for="inputPassword3" class="col-sm-3 control-label">GROUP<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <?

    $db = new database();

    $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and code like '63%' and code!='100' and code!='105' and code!='106' order by code");

    ?>

    <select class="form-control" name="group"  readonly="readonly" id="group">

    <option value="">-Please Select-</option>

    <? foreach($arr as $key){ echo $key['code']. '<br />'; ?>

    <option value="<?=$key['code']?>" <? if($group==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">EMPLOYEE TYPE<span class="star_color">*</span></label>

     <div class="col-sm-3">

    <?

    $db = new database();

    $arr = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='2'  order by code");

    ?>

    <select class="form-control" name="employee_type"   id="employee_type" onChange="return employee_typ_f(this.value);">

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

    <input type="text" class="form-control upper_case" id="last_pay_drawn"   readonly="readonly" name="last_pay_drawn" placeholder="Last Pay Drawn" autocomplete="off"  value="<?= $last_pay ?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    <!-- <label for="inputPassword3" class="col-sm-3 control-label">Family Details With Relation<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" id="f_relation" name="f_relation" placeholder="Family Relation" autocomplete="off" value="" >

    </div>-->

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Nomine Details If Any</label>

    <div class="col-sm-3">

    

    <?php if($data[0]['part_a_from_status']=='0' || $data[0]['part_a_from_status']==''){?>

    <input type="text" class="form-control upper_case" id="nomine_details" name="nomine_details" placeholder="NOMINE DETAILS" autocomplete="off" value="<?php echo $nomine_details;?>">

    <?php }else{?>

    <input type="text" class="form-control upper_case" id="nomine_details"   name="nomine_details" placeholder="NOMINE DETAILS" autocomplete="off" value="<?php echo $nomine_details;?>">

    <?php }?>

    </div>

    </div>

    

   <?php if($employee_type=='1993')

   {?>

   <div class="row mb-3">

	   <label for="inputPassword3" class="col-sm-3 control-label" >Date of Death<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" name="Death_date" id="Death_date" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">

    </div>

    </div>

  <?php }

   else

   {?>

  <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label" id="Death_date_date" style="display:none">Date of Death<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" format="DD-MM-YYYY" class="form-control" name="Death_date" id="Death_date"  style="display:none"  readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($death_date);?>">

    </div>

    </div>

    <?php }?>

    

    

    <?php if($employee_type=='1994')

   {?>

   <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label" id="premature_date_date">Premature retirement date<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" name="premature_date" id="premature_date" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($premature_date);?>">

    </div>

    

    </div>

   <?php }

   else

   {?>

   <div class="row mb-3">

   <label for="inputPassword3" class="col-sm-3 control-label" id="premature_date_date" style="display:none">Premature retirement date<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" name="premature_date" id="premature_date" style="display:none" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$death_date=='0001-01-01' ? '' : dateshow($premature_date);?>">

    </div>

    </div>

   <?php }?>

    

    

    

    <?php 

    //$db=new database();

    if($_SESSION['emp_id_const'] != '')

    {

    	$Query = "SELECT family_name,family_age,family_quali,family_dependent,family_relation,id_pk

        from intra_pri_cg_relation_master_submit

        WHERE

        employee_id='".$_SESSION['emp_id_const']."'";



        $data_relastion=$db->fetch_table($Query);

     }

	

	

   

	

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

    <input type="hidden" id="id_pk" name="id_pk[]" value="<?php echo $item['id_pk']; ?>" />

    <input type="text"  class="form-control upper_case" id="family_name0" name="family_name[]" placeholder="NAME" autocomplete="off" value="<?php echo $item['family_name'];  ?>" >

    </td>

    

    <td>Family Member Age</td>

    <td>

    <input type="text" class="form-control upper_case" id="family_age0" maxlength="2" name="family_age[]" placeholder="AGE" autocomplete="off" value="<?php echo $item['family_age']?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </td>

    

    <td>Family Member Qualification</td>

    <td>

    

    <select class="form-control upper_case" id="family_quali0" name="family_quali[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

        

        <? foreach($arr_desig as $key){ $key['code']. '<br />'; ?>

    <option value="<?= $key['code']; ?>" <? if($item['family_quali']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

    

    <td>Family Member Relationship</td>

    <td>

    <select class="form-control upper_case" id="family_relation0" name="family_relation[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

         <? foreach($arr_relation as $key){ $key['code']. '<br />'; ?>

        <option value="<?= $key['code']; ?>" <? if($item['family_relation']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

    

   <td>Family Member Dependent on Deceased Employee</td>

    <td>

    <select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

         <? foreach($arr_de as $key){ $key['code']. '<br />'; ?>

        <option value="<?= $key['code']; ?>" <? if($item['family_dependent']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

   <!-- <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row0" class="add_row0" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"></td>-->

    <?php  }} else { ?>



	

	<tr id="tr0">

    

     

    <td>Family Member Name</td>

    <td>

    <input type="text"  class="form-control upper_case" id="family_name0" name="family_name[]" placeholder="NAME" autocomplete="off" value="<?php echo $item['family_name'];  ?>" >

    </td>

    

    <td>Family Member Age</td>

    <td>

    <input type="text" class="form-control upper_case" id="family_age0" maxlength="2" name="family_age[]" placeholder="AGE" autocomplete="off" value="<?php echo $item['family_age']?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </td>

    

    <td>Family Member Qualification</td>

    <td>

    

    <select class="form-control upper_case" id="family_quali0" name="family_quali[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

        

        <? foreach($arr_desig as $key){ $key['code']. '<br />'; ?>

    <option value="<?= $key['code']; ?>" <? if($item['family_quali']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

    

    <td>Family Member Relationship</td>

    <td>

    <select class="form-control upper_case" id="family_relation0" name="family_relation[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

         <? foreach($arr_relation as $key){ $key['code']. '<br />'; ?>

        <option value="<?= $key['code']; ?>" <? if($item['family_relation']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

    

    <td>Family Member Dependent on Deceased Employee</td>

    <td>

    <select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" autocomplete="off" value="">

        <option>---- Please Select--- </option>

         <? foreach($arr_de as $key){ $key['code']. '<br />'; ?>

        <option value="<?= $key['code']; ?>" <? if($item['family_dependent']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </td>

	

    <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row0" class="add_row0" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"></td>

    </tr>

    <?php }?>

    </table> 

    </div>

    <?php 

    $data[0]['part_a_from_status'] = (count($data_relastion) <= 0)?0:$data[0]['part_a_from_status'];

    //print($data[0]['part_a_from_status']); exit;

    if($data[0]['part_a_from_status']=='0'|| $data[0]['part_a_from_status']=='' )

         {

    ?>

    

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <button type="submit" class="btn btn-info">PROPOSAL SAVE</button>

    </div>

    </div>

    <? }

    else{?>

    <?php if($data[0]['part_a_from_status']=='1' && $data[0]['status']=='0' )

    {?>

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <a id="edit1"  onclick="edit('<?php echo $crypto->encode("partA_edit",4); ?>');" class="btn btn-warning">EDIT & UPDATE</a>

    </div>

    </div>

    

    <?php 

          } 

         }

    ?>

 </form>                   

    <!------------------------------------------------ PART A closed ------------------------------------------- -->  

     <!------------------------------------------------ PART B START ------------------------------------------- -->  

    

    

    <?php if($data[0]['part_a_from_status']=='1' && count($data_relastion) > 0)

          {



        ?>  

    

    <div class="row" id="cont1">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12">

    <h1 class="heading"> PART B( Applicant Deatils )</h1>

    <div class="border"></div>  

    </br>

    <form class="form-horizontal" id="second_form" method="post" action="intra_pri_cg_profile_submit.php#cont2" onsubmit="return valid_code();">

    

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partB",4); ?>" />    

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Name Of the Applicant<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <?

    $db = new database();

    $arr = $db->fetch_table("select family_name from intra_pri_cg_relation_master_submit where family_age>='18' AND family_name !='' AND employee_id='".$_SESSION['emp_id_const']."' order by family_name");

    ?>

    <select class="form-control upper_case"  name="applicant_name"  id="applicant_name">

    <option value="">-Please Select-</option>

    <? foreach($arr as $key){ echo $key['family_name']. '<br />'; ?>

    <option   value="<?=strtoupper($key['family_name'])?>" <? if(strtoupper($applicant_name)==strtoupper($key['family_name'])){ echo  "selected";}?>><?= strtoupper($key['family_name']); ?></option>

    <? } ?>

    </select>

    </div>



    

    <label for="inputPassword3" class="col-sm-3 control-label">Relation With Employee<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    <?

    $db = new database();

    

    $arr = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='1' order by code");

    ?>

    <select class="form-control"  name="relation_employee"  id="relation_employee">

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

    

    

    <input type="text" class="form-control" name="applicant_birth" id="applicant_birth"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$applicant_birth=='0001-01-01' ? '' : dateshow($applicant_birth);?>">

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">Gender<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <?

    $db = new database();

    $arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");

    ?>

    <select class="form-control" name="drpSex" id="drpSex">

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

    <select name="nationality" style="width:100%;" id="nationality" class="form-control">

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

    <input type="text" class="form-control upper_case" autocomplete="off" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Mobile Number" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');" value="<?= $applicant_mobile_no?>" >

    </div>

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Religion<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <?php

    $db = new database();

    $arr = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='16' and length(code)='3' ORDER BY description");

    ?>

    <select class="form-control" name="religion" id="religion" >

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

    <select class="form-control" name="applicant_quali" id="applicant_quali">

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

    <select class="form-control" name="applicant_cast" id="applicant_cast">

    <option value="">-Please Select-</option>

    <? foreach($arr as $key){ $key['code']. '<br />'; ?>

    <option value="<?= $key['code']; ?>" <? if($applicant_cast==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>

    <? } ?>

    </select>

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">Whether Differently Able <span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="differently_able" id="differently_able" onChange="return stateDetailsView(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($differently_able=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($differently_able=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    </div>

    

    

      

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether application has been submitted in Prescribed Proforma in terms of 251-Emp dated 03.12.2013 and Subsequent relevant Order(Yes/No)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_p_roforma" id="candidate_p_roforma" onChange="return candidateproforma(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_p_roforma=='1' || $candidate_p_roforma=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_p_roforma=='0' || $candidate_p_roforma=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_p_roforma=='1' || $candidate_p_roforma==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_p_roforma_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_p_roforma_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_p_roforma_remarks" id="candidate_p_roforma_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_p_roforma_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_p_roforma_remarks_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_p_roforma_remarks_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_p_roforma_remarks" id="candidate_p_roforma_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_p_roforma_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    <?php if($employee_type=='1994'){?>

       <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether Medically 'Unfit Certificate' has been submitted from the Competent Authority (in case of premature retirement due to permanent . incapacitation)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_unfit_cer" id="candidate_unfit_cer" onChange="return candidateunfitcer(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_unfit_cer=='1' || $candidate_unfit_cer=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_unfit_cer=='0' || $candidate_unfit_cer=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_unfit_cer=='1' || $candidate_unfit_cer==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_unfit_cer_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_unfit_cer_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_unfit_cer_remarks" id="candidate_unfit_cer_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_unfit_cer_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_unfit_cer_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_unfit_cer_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_unfit_cer_remarks" id="candidate_unfit_cer_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_unfit_cer_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    <?php }?>

    

    

    

         <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether Divorce Decree' has been submitted by the applicant who obtained such decree before or after the death of the ex-employee ( in case of divorced daughter)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="divorce_decree" id="divorce_decree" onChange="return divorcedecree(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($divorce_decree=='1' || $divorce_decree=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($divorce_decree=='0' || $divorce_decree=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($divorce_decree=='1' || $divorce_decree==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="divorce_decree_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="divorce_decree_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="divorce_decree_remarks" id="divorce_decree_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $divorce_decree_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="divorce_decree_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="divorce_decree_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="divorce_decree_remarks" id="divorce_decree_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $divorce_decree_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    

    

    

    

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Date of submission of Proforma application<span class="star_color">*</span></label>

    <div class="col-sm-3">

    

    

    <input type="text" class="form-control" name="application_date" id="application_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$application_date=='0001-01-01' ? '' : dateshow($application_date);?>">

    </div></div>

    

    

    <h3 class="heading"> APPLICANT  RESIDENTIAL ADDRESS</h3>

    <div class="border"></div>

    </br>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-2 control-label">State<span class="star_color">*</span></label>

    <div class="col-sm-4">

        <select class="form-control" name="present_address_state" id="present_address_state">

            <option value="">-Please Select-</option>

            <option value="32" <? if($present_address_state=='32'){ echo 'selected';}?>>West Bengal</option>

        </select>

    </div>

    <label for="inputPassword3" class="col-sm-2 control-label">Police Station <span class="star_color">*</span></label>

    <div class="col-sm-4">

        <input type="text" class="form-control upper_case" autocomplete="off" placeholder="POLICE STATION" name="present_police_station" id="present_police_station" value="<?=$present_police_station?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">

    </div>

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-2 control-label">House No.</label>

    <div class="col-sm-4">

        <input type="text" class="form-control upper_case"  autocomplete="off" placeholder="HOUSE NO" name="present_house_no" id="present_house_no" value="<?=$present_house_no;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz/(-) ');">		

    </div>

    <label for="inputPassword3" class="col-sm-2 control-label">Street</label>

    <div class="col-sm-4">

        <input type="text" class="form-control upper_case"  autocomplete="off" name="present_street" placeholder="STREET" id="present_street"  value="<?=$present_street;?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">

    </div>

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-2 control-label">Town/ Village <span class="star_color">*</span></label>

    <div class="col-sm-4">

        <input type="text" class="form-control upper_case" autocomplete="off" placeholder="TOWN/VILLAGE" name="present_town_vill" id="present_town_vill"  value="<?=$present_town_vill?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">

    </div>

    <label for="inputPassword3" class="col-sm-2 control-label">Post Office <span class="star_color">*</span></label>

    <div class="col-sm-4">

        <input type="text" class="form-control upper_case" autocomplete="off" placeholder="POST OFFICE" name="present_post_office" id="present_post_office" value="<?=$present_post_office?>" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');">

    </div>

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-2 control-label">PIN <span class="star_color">*</span></label>

    <div class="col-sm-4">

        <input type="text" class="form-control" autocomplete="off" maxlength="6" placeholder="PIN" name="present_pin" id="present_pin" value="<?=$present_pin?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    <label for="inputPassword3" class="col-sm-2 control-label" id="present_wb_dist_label" <? if($present_address_state=='32' || $present_address_state=='others'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>District <span class="star_color">*</span></label>

    <div class="col-sm-4" id="present_wb_dist_field" <? if($present_address_state=='32'){?>style="display:block;"<? }else{?>style="display:none;"<? }?>>

        <?php

            $db=new database();

            $district_fetch=$db->fetch_table("select * from prd_location_master_district where state_id_fk='1'");

        ?>

        <select name="present_city_district" id="present_city_district" class="form-control">

            <option value="" selected="selected">-Please Select-</option>

            <?php foreach($district_fetch as $dist_dtls){ ?>	

            <option value="<? echo $dist_dtls['district_id_pk']?>" <? if($present_city_district==$dist_dtls['district_id_pk']){ echo "selected";}?>><? echo $dist_dtls['district_name'];?></option>

            <?php	} ?>

        </select> 

    </div>

    

    </div>

    

    

    

    <?php if(($data[0]['part_a_from_status']=='1') && ($data[0]['part_b_from_status']=='0') && count($data_relastion) > 0){?>

    

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <button type="submit" class="btn btn-info">PROPOSAL SAVE</button>

    </div>

    </div>

    <? }

    else{?>

    

    <?php if($data[0]['part_b_from_status']=='1' && $data[0]['status']=='0')

    {?>



    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <a id="edit1"  onclick="edit('<?php echo $crypto->encode("partB_edit",4); ?>');" class="btn btn-warning">EDIT & UPDATE</a>

    </div>

    </div>

    

    <?php }}?>

    

    

    <? }?>

   

   </form>

    <!------------------------------------------------ PART B Closed ------------------------------------------- --> 

    

    

    <?php if( $data[0]['part_a_from_status']=='1' && $data[0]['part_b_from_status']=='1' && count($data_relastion) > 0){?> 

    

    <div class="row" id="cont2">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12">

    <h1 class="heading"> PART C( Financial Statement as per Office Record & Applicant )</h1>

    <div class="border"></div>

    </br>

    

    <form class="form-horizontal" id="third_form" method="post" action="intra_pri_cg_profile_submit.php#cont3" onsubmit="return valid_code();">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partC",4); ?>" />  

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Family Pension(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="family_pension" id="family_pension" placeholder="FAMILY PENSION"  onKeyPress="return keyRestrict(event,'0123456789');" onkeyup="return calculation();" value="<?=$family_pension?>">

    </div>

    

    </div>

    

    

    <div class="row" id="cont">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12">

    <h3 class="heading"> LUMP SUM TERMIAL DUES</h3>

    <div class="border"></div>

    </br>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Death Gratuity(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="death_gratuity" id="death_gratuity" placeholder="DEATH GRATUTITY"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$death_gratuity?>">

    </div>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Group Insurance(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="group_insurance" id="group_insurance" placeholder="GROUP INSURANCE"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$group_insurance?>">

    </div>

    </div>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Encashment Of Leave(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="encashment_leave" id="encashment_leave" placeholder="ENCASHMENT OF LEAVE"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$encashment_leave?>">

    </div>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Disclosed Source of Income(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="any_payment" id="any_payment" placeholder="ANY OTHER PAYMENT"  onKeyPress="return keyRestrict(event,'0123456789');"   onkeyup="return calculation();" value="<?=$any_payment?>">

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

    <input type="text" class="form-control upper_case" autocomplete="off" name="calculation_hospitalization" id="calculation_hospitalization" placeholder="Expenses incurred on account of hospitalization"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$calculation_hospitalization?>">

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

    <input type="text" class="form-control upper_case" autocomplete="off" name="move_immovable" id="move_immovable" placeholder="Monthly income from Other movable Or immovable"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$move_immovable?>">

    </div>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Monthly income from dependant of the ex-employee if any(in RS)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off"  name="income_dependant_employee" id="income_dependant_employee" placeholder="Monthly income from dependant of the ex-employee"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$income_dependant_employee?>">

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

    <input type="text" class="form-control upper_case" id="last_pay_drawn_partc"   readonly="readonly" name="last_pay_drawn_partc" placeholder="Last Pay Drawn" autocomplete="off"  value="<?= $last_pay ?>" onKeyPress="return keyRestrict(event,'0123456789');">

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">Percentage Of total monthly income in realtion to Gross Salary(%)<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off"  readonly="readonly" name="percentage_monthly_income" id="percentage_monthly_income" placeholder="Monthly income from dependant of the ex-employee"  onKeyPress="return keyRestrict(event,'0123456789');"  onkeyup="return calculation();" value="<?=$percentage_monthly_income?>">

    </div>

    </div>

    

    

    

    <?php if(($data[0]['part_b_from_status']=='1') && ($data[0]['part_c_from_status']=='0') && count($data_relastion) > 0){?>

    

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <button type="submit" class="btn btn-info">PROPOSAL SAVE</button>

    </div>

    </div>

    <? }

    else{?>

     <?php if($data[0]['part_c_from_status']=='1' && $data[0]['status']=='0')

    {?>

   <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <a id="edit1"  onclick="edit('<?php echo $crypto->encode("partC_edit",4); ?>');" class="btn btn-warning">EDIT & UPDATE</a>

    </div>

    </div>

    

    <?php } }?>

    

    

    <?php }?>

    

    </form>

    <!------------------------------------------------ PART B closed ------------------------------------------- --> 

      <!------------------------------------------------ PART C START ------------------------------------------- --> 

    <?php if( $data[0]['part_a_from_status']=='1' && $data[0]['part_b_from_status']=='1' && $data[0]['part_c_from_status']=='1' && count($data_relastion) > 0){?>    

    <div class="row" id="cont3">

    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 

    <div class="col-sm-12">

    <h1 class="heading"> PART D( Details of 3-Men E.C Report )</h1>

    <div class="border"></div>

    </br>

   

    <h5 class="heading"> Name Of Officials appointed on members of 3-Men E.C</h5>

    <div class="border"></div>

    </br>

     <!------------------------------------------------ PART C CLOSED ------------------------------------------- --> 

          <!------------------------------------------------ PART D START ------------------------------------------- --> 

    <form class="form-horizontal" id="fourth_form" method="post" action="intra_pri_cg_profile_submit.php#cont4" onsubmit="return valid_code();">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partD",4); ?>" /> 

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">1st Office Member OF E.C<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_officer_first" id="name_officer_first" placeholder="FirstName Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_first?>">

    </div>

    

    <label for="inputPassword3" class="col-sm-3 control-label">1st Office Member OF E.C Designation<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_designation_first" id="name_designation_first" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_first?>">

    </div>

    

    </div>

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">2nd Office Member OF E.C<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_officer_second" id="name_officer_second" placeholder="Second Name Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_second?>">

    </div>

    <label for="inputPassword3" class="col-sm-3 control-label">2st Office Member OF E.C Designation<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_designation_second" id="name_designation_second" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_second?>">

    </div>

    </div>

    

    <div class="row mb-3">

    

    

    

    <label for="inputPassword3" class="col-sm-3 control-label">3rd Office Member OF E.C<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_officer_third" id="name_officer_third" placeholder="Third Name Of Officer"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_officer_third?>">

    </div>

    

    <label for="inputPassword3" class="col-sm-3 control-label">3rd Office Member OF E.C Designation<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control upper_case" autocomplete="off" name="name_designation_third" id="name_designation_third" placeholder="Designation"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz .');" value="<?=$name_designation_third?>">

    </div>

    </div>

    

    

    <div class="border"></div>

    </br>

    

    <div class="row mb-3" >

        <label for="inputPassword3" class="col-sm-3 control-label">Memo no of formation of 3 MEN E.C<span class="star_color">*</span></label>

        <div class="col-sm-3">

        <input  type="text" class="form-control" name="memo_no_ec" id="memo_no_ec" placeholder="MEMO NUMBER" value="<?php echo $memo_no_ec;?>" autocomplete="off" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz/(-) ');"  >

        </div>

        <label for="inputPassword3" class="col-sm-3 control-label"> Memo Date of formation of 3 MEN E.C<span class="star_color">*</span></label>

        <div class="col-sm-3">

        <input type="text" class="form-control" id="memo_date_ec" name="memo_date_ec"  value="<?php if(count($arr)==0){echo  $memo_date_ec="";}else { echo  dateshow($memo_date_ec);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />

        </div>

    </div>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Date of inquery<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" id="date_inquery" name="date_inquery"  value="<?php if(count($arr)==0){echo  $date_inquery="";}else { echo  dateshow($date_inquery);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />

    </div>

    </div>

    

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether the Recommedation by the enquiry committe has been unanimous<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_enquiry_recommedation" id="candidate_enquiry_recommedation" onChange="return candidateenquiryrecommedation(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_enquiry_recommedation=='1' || $candidate_enquiry_recommedation=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_enquiry_recommedation=='0' || $candidate_enquiry_recommedation=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_enquiry_recommedation=='1' || $candidate_enquiry_recommedation==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_enquiry_recommedation_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_enquiry_recommedation_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_enquiry_recommedation_remarks" id="candidate_enquiry_recommedation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_enquiry_recommedation_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_enquiry_recommedation_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_enquiry_recommedation_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_enquiry_recommedation_remarks" id="candidate_enquiry_recommedation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_enquiry_recommedation_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    

    

      <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether the Applicant submited PART I and PART II application<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_part_from" id="candidate_part_from" onChange="return candidatepartfrom(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_part_from=='1' || $candidate_part_from=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_part_from=='0' || $candidate_part_from=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_part_from=='1' || $candidate_part_from==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_part_from_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_part_from_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_part_from_remarks" id="candidate_part_from_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_part_from_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_part_from_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_part_from_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_part_from_remarks" id="candidate_part_from_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_part_from_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    

     

      <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether the 3-MEN E.C Visit Applicant location<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="vist_sport" id="vist_sport" onChange="return vistsport(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($vist_sport=='1' || $vist_sport=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($vist_sport=='0' || $vist_sport=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($vist_sport=='1' || $vist_sport==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="vist_sport_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="vist_sport_remarks" id="vist_sport_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $vist_sport_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_part_from_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="vist_sport_remarks" id="vist_sport_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $vist_sport_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

 

 

 

 

 

 <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether Recommended for the Employment in favour of Applicant <span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_favour" id="candidate_favour" onChange="return candidatefavour(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_favour=='1' || $candidate_favour=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_favour=='0' || $candidate_favour=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_favour=='1' || $candidate_favour==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_favour_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_favour_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_favour_remarks" id="candidate_favour_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_favour_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="vist_sport_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_favour_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_favour_remarks" id="candidate_favour_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_favour_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

 

 

 

 

 

 





    

    

    

     <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Date of Submission inquery report<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <input type="text" class="form-control" id="date_submision_inquery" name="date_submision_inquery"  value="<?php if(count($arr)==0){echo  $date_submision_inquery="";}else { echo  dateshow($date_submision_inquery);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />

    </div>

    </div>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Comments Of Controlling Officer<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <textarea class="form-control upper_case" autocomplete="off" name="comment_officer" id="comment_officer" placeholder="Observationr"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $comment_officer;?></textarea>

    </div>

    </div>

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Is any relaxation of rule etc. required<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_any_relaxation" id="candidate_any_relaxation" onChange="return candidateanyrelaxation(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_any_relaxation=='1' || $candidate_any_relaxation=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_any_relaxation=='0' || $candidate_any_relaxation=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_any_relaxation=='1' || $candidate_any_relaxation==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_any_relaxation_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_any_relaxation_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_any_relaxation_remarks" id="candidate_any_relaxation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_any_relaxation_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_any_relaxation_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_any_relaxation_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_any_relaxation_remarks" id="candidate_any_relaxation_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_any_relaxation_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    <div class="row mb-3">

    <div class="col-sm-3"></div>

    <div class="col-sm-3" id="age_check" style="display:none">

    

    

    <label for="inputPassword3" class="col-sm-2 control-label" id="check_age" >Age</label>

    <input type="checkbox" id="check_age_value" name="check_age_value" value="" >

    

    <label for="check_age_value"><span></span></label>



    

    <label for="inputPassword3" class="col-sm-3 control-label" id="check_age" >Educational & Qualification</label>

    <input type="checkbox" id="check_education_value" name="check_education_value"  value="" >

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

    

    

    <input type="checkbox"  <?php if($check_age_value==1){echo "checked";}?> id="check_age_value" name="check_age_value" value="1" >

    <label for="check_age_value"><span></span></label>

    <?php }?>

    <?php if($check_education_value==1){?>

    <label for="inputPassword3" class="col-sm-3 control-label" id="check_age" >Educational & Qualification</label>

    

    <input type="checkbox"  <?php if($check_education_value==1){echo "checked";}?> id="check_education_value" name="check_education_value" value="1" >

    

    <label for="check_education_value"><span></span></label>

    <?php }?>

    </div>

    

    

    </div>

    

    <?php }?>

     

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether the candidate fulfils the requirement of Recruitment Rules for the Post <span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_fulfil_rules" id="candidate_fulfil_rules" onChange="return candidatefulfilrules(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_fulfil_rules=='1' || $candidate_fulfil_rules=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_fulfil_rules=='0' || $candidate_fulfil_rules=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_fulfil_rules=='1' || $candidate_fulfil_rules==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_no_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_fulfil_no_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_fulfil_remarks" id="candidate_fulfil_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_no_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_fulfil_no_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_fulfil_remarks" id="candidate_fulfil_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Summary sheet duly authenticated by HOO<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="authenticat_hoo" id="authenticat_hoo" onChange="return authenticathoo(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($authenticat_hoo=='1' || $authenticat_hoo=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($authenticat_hoo=='0' || $authenticat_hoo=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($authenticat_hoo=='1' || $authenticat_hoo==''){

    

    ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="authenticat_hoo_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="authenticat_hoo_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="authenticat_hoo_remarks" id="authenticat_hoo_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $authenticat_hoo_remarks;?></textarea>

    </div>

    

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="authenticat_hoo_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="authenticat_hoo_div" >

    <textarea class="form-control upper_case" autocomplete="off" name="authenticat_hoo_remarks" id="authenticat_hoo_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $authenticat_hoo_remarks;?></textarea>

    </div>

    

    <?php }?>

    </div>

    

    

    

    

    

    

    

    

    

    

    

    

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether a clear Vacancy on Examted category is available as per 100 pointer Roster vide Notification No. 50-EMP dated 01.03.2011<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="clear_vacany_roster" id="clear_vacany_roster" onChange="return clearvacanyroster(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($clear_vacany_roster=='1' || $clear_vacany_roster=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($clear_vacany_roster=='0' || $clear_vacany_roster=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    <?php if($clear_vacany_roster=='1' || $clear_vacany_roster==''){?>

    <label for="inputPassword3" class="col-sm-3 control-label" id="clear_vacany_roster_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="clear_vacany_roster_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="clear_vacany_roster_remarks" id="clear_vacany_roster_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $clear_vacany_roster_remarks;?></textarea>

    </div>

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="clear_vacany_roster_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="clear_vacany_roster_div">

    <textarea class="form-control upper_case" autocomplete="off" name="clear_vacany_roster_remarks" id="clear_vacany_roster_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $clear_vacany_roster_remarks;?></textarea>

    </div>

    <?php }?>

    </div>

    

    <div class="row mb-3">

    <label for="inputPassword3" class="col-sm-3 control-label">Whether the applicant has fulfiled all the criteria as laid down in Notification No.251-EMP dated 03.12.2013 read with 26-EMP dated 01.03.2016<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <select class="form-control" name="candidate_fulfil_all" id="candidate_fulfil_all" onChange="return candidatefulfilall(this.value);">

    <option value="">Please Select</option>

    <option value="1" <? if($candidate_fulfil_all=='1' || $candidate_fulfil_all=='1') { echo "selected"; } ?>>YES</option>

    <option value="0" <? if($candidate_fulfil_all=='0' || $candidate_fulfil_all=='0') { echo "selected"; } ?>>NO</option>

    </select>

    </div>

    

    <?php if($candidate_fulfil_all=='1' || $candidate_fulfil_all==''){?>

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_all_lable"style="display:none">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_fulfil_all_div" style="display:none" >

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_fulfil_all_remarks" id="candidate_fulfil_all_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_all_remarks;?></textarea>

    </div>

    <?php }else{  ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label" id="candidate_fulfil_all_lable">Remarks<span class="star_color">*</span></label>

    <div class="col-sm-3" id="candidate_fulfil_all_div">

    <textarea class="form-control upper_case" autocomplete="off" name="candidate_fulfil_all_remarks" id="candidate_fulfil_all_remarks" placeholder="Remarks"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $candidate_fulfil_all_remarks;?></textarea>

    </div>

    </div>

    <?php }?>

    

     <div class="row mb-3"></div>

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Coments of the Appointment authority<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <textarea class="form-control upper_case" autocomplete="off"   name="note" id="note" placeholder=""  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $note;?></textarea>

    </div>

    </div>

    

    <!-- <div class="row mb-3"></div>-->

    <!--<div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">BRIEF NOTE<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <textarea class="form-control upper_case" autocomplete="off"  name="note" id="note" placeholder="BRIF NOTE"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $note;?></textarea>

    </div>

    </div>

    

    <div class="row mb-3">

    

    <label for="inputPassword3" class="col-sm-3 control-label">OBSERVATION<span class="star_color">*</span></label>

    <div class="col-sm-3">

    <textarea class="form-control upper_case" autocomplete="off" name="histroy" id="histroy" placeholder="OBSERVATION"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz(-) ');"><?php echo $histroy;?></textarea>

    </div>

    </div>-->

    

    

    <?php if(($data[0]['part_b_from_status']=='1') && ($data[0]['part_c_from_status']=='1') &&  ($data[0]['part_d_from_status']=='0')){?>

    

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <button type="submit" class="btn btn-info">PROPOSAL SAVE</button>

    </div>

    </div>

    <? }

    else{?>

     <?php if($data[0]['part_d_from_status']=='1' && $data[0]['status']=='0')

    {?>

    <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">

    <div class="col-sm-offset-5 col-sm-7">

    <a id="edit1"  onclick="edit('<?php echo $crypto->encode("partD_edit",4); ?>');" class="btn btn-warning">EDIT & UPDATE</a>

    </div>

    </div>

    

    <?php }}?>

   

    

    <?php }?>

    </form>

          <!------------------------------------------------ PART D closed ------------------------------------------- --> 

    <div class="border"></div>

    </br>

     <div class="row" id="cont4">

  

    <?php if($data[0]['part_a_from_status']=='1' && $data[0]['part_b_from_status']=='1' && $data[0]['part_c_from_status']=='1' && $data[0]['part_d_from_status']=='1')

    {

		$db=new database();

		

		$arr_file_1 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 1 and application_id='".$application_id."'"); ?>

		

		

    <h5 class="heading"> Document to be Uploaded</h5>

    </br>

    

   <?php if($employee_type=='1993'){?>

    <div class="row mb-3" id="cont4">

    

    <label for="inputPassword3" class="col-sm-3 control-label">Attached Death Certificate Of Deceased Employee <span class="star_color">*</span></label>

    <div class="col-sm-2">

    

   <?php  if($arr_file_1[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="death_cert"  id="death_cert" onchange="return file_upload(this.id,'<?php echo $crypto->encode("1",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');"  >

    

   <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:7px"><?php echo substr($arr_file_1[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("1",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    

    <?php }?>

    

    

    

    

    

    

    <?php 

    $db=new database();

		

		$arr_file_2 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 2 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Application For appoinment on Compassionate ground <span class="star_color">*</span></label>

    <div class="col-sm-2">

      <?php  if($arr_file_2[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="applicaton_cgound"  id="applicaton_cgound" onchange="return file_upload(this.id,'<?php echo $crypto->encode("2",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');">

     <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:7px"><?php echo substr($arr_file_2[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

    

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("2",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    

    

    

    

    

     <div class="row mb-3">

    <?php 

    $db=new database();

		

		$arr_file_19 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 19 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Photo Of Applicant<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_19[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="f_statement"  id="f_statement"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("19",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    

      <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:7px"><?php echo substr($arr_file_19[0]['file_name'],6) ;  ?></label>

    <?php  }?>

     

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("19",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    

    

    

    

    

    

    

    

    

    <?php 

    $db=new database();

		

		$arr_file_18 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 18 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Annexure A & Annexure B<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_18[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="f_statement"  id="f_statement"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("18",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    

      <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:7px"><?php echo substr($arr_file_18[0]['file_name'],6) ;  ?></label>

    <?php  }?>

     

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("18",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    

    

    

    

    <div class="row mb-3">

    <?php 

    $db=new database();

		

		$arr_file_3 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 3 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Financial Statement by applicant<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_3[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="f_statement"  id="f_statement"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("3",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    

      <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:7px"><?php echo substr($arr_file_3[0]['file_name'],6) ;  ?></label>

    <?php  }?>

     

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("3",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    

    <?php 

    $db=new database();

		

		$arr_file_4 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 4 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">3-Men E.C Report <span class="star_color">*</span></label>

    <div class="col-sm-2">

    <?php  if($arr_file_4[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="menec_cgound"  id="menec_cgound" onchange="return file_upload(this.id,'<?php echo $crypto->encode("4",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    <?php  }else{?>

<label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_4[0]['file_name'],6) ;  ?></label>

<?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("4",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

   

    

    <div class="row mb-3">

    

    <?php 

    $db=new database();

		

		$arr_file_5 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 5 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Noc in form Of Affidavit<span class="star_color">*</span></label>

    <div class="col-sm-2">

    <?php  if($arr_file_5[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="noc_statement"  id="noc_statement"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("5",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

     <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_5[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("5",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

     <?php 

    $db=new database();

		

		$arr_file_6 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 6 and application_id='".$application_id."'"); ?>

    <label for="inputPassword3" class="col-sm-3 control-label">Age Proof Of Applicant <span class="star_color">*</span></label>

    <div class="col-sm-2">

    <?php  if($arr_file_6[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="age_proof"  id="age_proof"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("6",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

     <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_6[0]['file_name'],6) ;  ?></label>

     <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("6",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    

    <div class="row mb-3">

    <?php 

    $db=new database();

		

		$arr_file_7 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 7 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Educational & Qualification(Highest)<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_7[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="education_statement"  id="education_statement"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("7",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_7[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("7",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

     <?php 

    $db=new database();

		

		$arr_file_8 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 8 and application_id='".$application_id."'"); ?>

    <label for="inputPassword3" class="col-sm-3 control-label">ID proof(Aadhaar/Voter Card etc.) <span class="star_color">*</span></label>

    <div class="col-sm-2">

    <?php  if($arr_file_8[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="id_proof"  id="id_proof"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("8",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

        <?php  }else{?>



    <label for="show" class="alert alert-info" style="text-align:center; width: 200px;  padding:5px"><?php echo substr($arr_file_8[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("8",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    

    

    

    

    <div class="row mb-3">

    <?php 

    $db=new database();

		

		$arr_file_11 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 11 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">PPO Order</label>

    <div class="col-sm-2">

     <?php  if($arr_file_11[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="pp_o"  id="pp_o"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("11",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_11[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("11",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

     <?php 

	 

	 if($applicant_cast!='101')

	 {

    $db=new database();

		

		$arr_file_16 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 16 and application_id='".$application_id."'"); ?>

    <label for="inputPassword3" class="col-sm-3 control-label">Caste Certificate <span class="star_color">*</span></label>

    <div class="col-sm-2">

    <?php  if($arr_file_16[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="cast_cer"  id="cast_cer"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("16",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

        <?php  }else{?>



    <label for="show" class="alert alert-info" style="text-align:center; width: 200px;  padding:5px"><?php echo substr($arr_file_16[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

     <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("16",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

   <?php  }?>

   </div>

    

    

    <div class="row mb-3">

    <?php 

    $db=new database();

		

		$arr_file_17 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 17 and application_id='".$application_id."'"); ?>

    

    <label for="inputPassword3" class="col-sm-3 control-label">Legal heir Certificate<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_17[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="legal_cer"  id="legal_cer"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("17",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

    <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_17[0]['file_name'],6) ;  ?></label>

    <?php  }?>

    </div>

  <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("17",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    </div>

    </div>

    

    

 

    

    

    <?php if($calculation_hospitalization!=''){?>

    <div class="row mb-2">

    <?php 

    $db=new database();

		

		$arr_file_9 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 9 and application_id='".$application_id."'"); ?>

        

    <label for="inputPassword3" class="col-sm-3 control-label">Expenses incurred on account of hospitalization<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_9[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="expenses_hospitalization"  id="expenses_hospitalization"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("9",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

      <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_9[0]['file_name'],6) ;  ?></label>

     <?php  }?>

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("9",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    <? }?>

    

    

    

    

        <?php if($employee_type=='1994'){?>

    <div class="row mb-2">

    <?php 

    $db=new database();

		

		$arr_file_10 = $db->fetch_table("select file_name from intra_pri_file_upload where emp_id_const = '".$_SESSION['emp_id_const']."' AND status = '1' AND flag= 10 and application_id='".$application_id."'"); ?>

        

    <label for="inputPassword3" class="col-sm-3 control-label">Unfit Certificate<span class="star_color">*</span></label>

    <div class="col-sm-2">

     <?php  if($arr_file_10[0]['file_name']==''){?>

    <input type="file" class="form-control upper_case" autocomplete="off" name="unfit_certificate"  id="unfit_certificate"  onchange="return file_upload(this.id,'<?php echo $crypto->encode("10",4); ?>','<?php echo $crypto->encode($application_id,4); ?>','<?php echo $crypto->encode($emp_id_const,4); ?>');" >

      <?php  }else{?>

    <label for="show" class="alert alert-info" style="text-align:center; width: 200px; padding:5px"><?php echo substr($arr_file_10[0]['file_name'],6) ;  ?></label>

     <?php  }?>

    </div>

    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("10",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>

    </div>

    <? }?>

    <?php 

         if($application_id != '')

         {

              $app_firstIdQuery = "SELECT ipm.stake_user_code, ipm.officer_id_const FROM intra_pri_forwarding as ipf

                              LEFT JOIN intra_pri_master as ipm ON ipf.from_officer_id_const = ipm.officer_id_const

                              WHERE application_id='".$application_id."' order by forwarding_id_pk asc LIMIT 1 offset 0 ";

              $app_firstId = $db->fetch_table($app_firstIdQuery);

              //$app_firstOfficer = $app_firstId[0]['officer_id_const'];

              $app_firstId = $app_firstId[0]['officer_id_const'];  

              //echo  $app_firstId;

              if($app_firstId == $session['user_info']['officer_id_const'])  

                {       

    ?>

    <div class="col-sm-12" style="text-align: center; margin-top:10px;">

       <input type="hidden" name="applicationId" value="<?php echo $application_id?>"> 

       <input type="button" name="update" class="updatecga btn btn-primary" value="Update Compassionate">

    </div>

    <?php 

                }

          } 

     ?>

    <div class="row mb-3" style="margin-left: 41%;">

		<div class="col-sm-offset-5 col-sm-7">

        <?php if($data[0]['status']=='0'){ ?>

      

		<a type="button" id="submit5" name="submit5" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal"> SUBMIT & PREVIEW </a>

        <? }?>

        

        

		</div>

	</div>



 <?php }?>

 <?php if($data[0]['status']=='1' && count($data_relastion) > 0){ ?>

     <div class="row mb-3" style="margin-left: 20%;">

    <div class="col-sm-offset-5 col-sm-7" style='padding-left: 23%;'>

    

    <!-- <label for="inputPassword3" class="col-sm-3 control-label">Check List</label>-->

    <!--<a href="<?= $config['base_url']?>page/intra_pri/pdf_employe_cg.php?emp_id_const=<?php echo $crypto->encode($emp_id_const,4) ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a>-->

    

    

    

    </div>

	</div>

     <div class="row mb-3" style="margin-left: 20%;">

    <div class="col-sm-offset-5 col-sm-7" style='padding-left: 23%;'>

    <label for="inputPassword3" class="col-sm-12 control-label">Download PDF</label> <br />

    <a href="<?= $config['base_url']?>page/intra_pri/pdf_cg.php?emp_id_const=<?php echo $crypto->encode($emp_id_const,4) ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a>

<?php }?>



</div>

	</div>

    <div class="clear"></div>

    </div>

    </div>

    </div>

    </div>

    </div>

    <? require '../layout/footer.php'; ?>

    <script>

    $(document).ready(function(){

    if($('#form_show').css("visibility")=="hidden"){

    $('#form_show').removeClass("invisible").css('height', 'auto');

    }

    });

    

	

					



    

    $(function() {

    /*	$( "#first_join_date" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy' 

    });*/

    

    /*$( "#Death_date" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy' 

    });*/

	$( "#date_submision_inquery" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy',

    //minDate:dateToday	 

    });

	

	

	$( "#application_date" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy',

    //minDate:dateToday	 

    });

	

	$( "#premature_date" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy',

    //minDate:dateToday	 

    });

	

	

    $( "#date_inquery" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy',

    //minDate:dateToday	 

    });

    

    $( "#memo_date_ec" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy',

    //minDate:dateToday	 

    });

    $( "#applicant_birth" ).datepicker({

    changeMonth: true,

    changeYear: true,

    yearRange: "-100:+0",

    dateFormat: 'dd-mm-yy' 

    });

    });

    

    function valid_code()

    {

    /*if($('#tch_fname').val()==''){

    alert('Please fill out this field.');

    $('#tch_fname').focus();

    return false;

    }

    else if($('#vice_desig').val()==''){

    alert('Please Enter Designation.');

    $('#vice_desig').focus();

    return false;

    }

    if($('#first_join_date').val()==''){

    alert('Please Enter Date of First Joining.');

    $('#first_join_date').focus();

    return false;

    }

    

    else if($('#stake_lvl_select').val()==''){

    alert('Please Select Place Of Posting.');

    $('#stake_lvl_select').focus();

    return false;

    }

    

    else if($('#group').val()==''){

    alert('Please Select Group.');

    $('#group').focus();

    return false;

    }

    else if($('#last_pay_drawn').val()==''){

    alert('Please enter Last Pay Drawn.');

    $('#last_pay_drawn').focus();

    return false;

    }

    else if($('#f_relation').val()==''){

    alert('Please fill out this field.');

    $('#f_relation').focus();

    return false;

    }

    else if($('#Death_date').val()==''){

    alert('Please Enter Death of Date.');

    $('#Death_date').focus();

    return false;

    }

    else if($('#applicant_name').val()==''){

    alert('Please Enter Applicant Name.');

    $('#applicant_name').focus();

    return false;

    }

    else if($('#relation_employee').val()==''){

    alert('Please Enter Relation With Employee.');

    $('#relation_employee').focus();

    return false;

    }

    else if($('#applicant_birth').val()==''){

    alert('Please Enter Applicant Deate Of Birth.');

    $('#applicant_birth').focus();

    return false;

    }

    else if($('#drpSex').val()==''){

    alert('Please Select Applicant Gender.');

    $('#drpSex').focus();

    return false;

    }

    else if($('#nationality').val()==''){

    alert('Please Select Nationality.');

    $('#nationality').focus();

    return false;

    }*/

    

    /*else if($('#nomine_details').val()==''){

    alert('Please Enter Nomine Deatails.');

    $('#nomine_details').focus();

    return false;

    }*/

    

    /*else if($('#applicant_mobile_no').val()!='' && $('#applicant_mobile_no').val().length!='10'){

    alert('Please Enter 10  Digit Applicant Mobile Number.');

    $('#mobile_no').focus();

    return false;

    }

    else if($('#religion').val()==''){

    alert('Please Select Religion.');

    $('#religion').focus();

    return false;

    }

    else if($('#applicant_quali').val()==''){

    alert('Please Select Applicant Qualification.');

    $('#applicant_quali').focus();

    return false;

    }

    else if($('#cast').val()==''){

    alert('Please Select Applicant CAST.');

    $('#cast').focus();

    return false;

    }

    else if($('#differently_able').val()==''){

    alert('Please Select Differently Able.');

    $('#differently_able').focus();

    return false;

    }*/

    

    if(document.getElementById('email').value!=''){

    if(document.getElementById('email').value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){

    alert("Please Enter A Valid Email Id.");

    document.getElementById('email').focus();			

    return false;

    }

    }

    }

    

    

    

    function empplyee_details_fetch(emp_id_const,flage)

    {

    //alert(emp_id_const);
    //alert(flage);

    //console.log();

    $empCodeValid = (emp_id_const.length >= 12)?1:0;

    //alert($empCodeValid);

    if($empCodeValid == 1)

      {  
            //alert(flage);
            $.post('<?= $config['base_url'] ?>page/intra_pri/ajax_cg_fetch_master.php?emp_id_const='+emp_id_const+ '&flage='+flage, function(data){

            

            console.log(data);

            var result = $.parseJSON(data);

            //alert(result.emp_first_name);

           

        	//return false;

            if(result[15]==1)

        	{

        		 location. reload();

        	}

            $("#tch_fname").val(result.emp_first_name);

            $("#tch_mname").val(result.emp_second_name);

            $("#tch_lname").val(result.emp_last_name);

            $("#vice_desig_des").val(result.designation);

            $("#vice_desig").val(result.emp_desig);

        	

        	//$("#vice_desig_des").val(result.emp_desig_first_app);
            

            

            

            
            var firstJoiningDate = result.emp_first_join_date;
            var dateAr = firstJoiningDate.split('-');

            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

            

            $("#first_join_date").val(newDate);

            $("#group").val(result.emp_group);

            
            var emp_termination_date = result.emp_termination_date;
            var dateAr1 = emp_termination_date.split('-');

            var newDate1 = dateAr1[2] + '-' + dateAr1[1] + '-' + dateAr1[0];

            

            $("#Death_date").val(newDate1);

            $("#last_pay_drawn").val(result.lastpaydrawn);

            if(result.gp_id_fk != null && result.gp_id_fk > 0)

            {

            

            $('#gp_description_div').show();

            $("#gp_description").val(result.gp_name);

            $("#gp_id_fk").val(result.gp_id_fk);

            

            $('#block_description_div').show(result.block_name);

            $("#block_description").val(result.block_name);

            $('#ps_description_div').hide();

            $('#zp_description_div').hide();

            }

            

            if(result.ps_id_fk != null && result.ps_id_fk > 0)

            {

            

            $('#ps_description_div').show();

            $("#ps_description").val(result.block_name);

            $("#ps_id_fk").val(result.ps_id_fk);

            

            $('#gp_description_div').hide();

            $('#zp_description_div').hide();

            }

            

            if(result.zp_id_fk!=null && result.zp_id_fk > 0)

            {

            

            $('#zp_description_div').show();

            $("#zp_description").val(result.district_name);

            $("#zp_id_fk").val(result.zp_id_fk);

            $('#gp_description_div').hide();

            $('#ps_description_div').hide();

            

            }

            $("#district").val(result.zp_id_fk);

            

        	$("#proposal").val("PROPOSAL FOR COMPASSIONATE GROUND APPLICATION IN RESPECT OF i.r.o "+result.emp_first_name+" "+result.emp_second_name+" "+result.emp_last_name);

            });

       }     

    }

    

    

    

	

	

	

	

   function ser()

    {

		//alert(11);

    if(document.getElementById('iosms_registered').checked==true)

    {

    $('#search').show();

   $('#search1').show();

    

    }

    else

   {

   $('#employed_details').val('');

$('#spouse_pay').val('');

    

$('#search').val('');

    $('#search').hide();

    $('#search1').hide();

    

    }

    }

    // Subikar ...

 $('.updatecga').click(function(e){

   // e.preventDefault();

    var formA = $('#first_form').serializeArray();

    //console.log(formA);

    var formB = $('#second_form').serializeArray();

    var formC = $('#third_form').serializeArray();

    var formD = $('#fourth_form').serializeArray();



    $.post('<?= $config['base_url'] ?>page/intra_pri/ajax_update.php',

        {formA:formA,formB:formB,formC:formC,formD:formD, appId:'<?php echo $application_id; ?>',case:'updatecga'}

        , function(data){

           var returnData = JSON.parse(data);

           if(returnData.success == 1)

             {

                console.log(returnData);

                $('.message').html(returnData.msg);

                window.scrollTo(0,0);

               // window.reload();

             }

        });

 });   



    

    function calculation()

    {

    

    

    

    //var death_gratuity = $('#death_gratuity').val();

    //alert(death_gratuity);

    

    if($('#death_gratuity').val()=='')

    {

    //alert(11);

    var death_gratuity = 0;

    }

    else

    {

    //alert(12);

    var death_gratuity = $('#death_gratuity').val();

    }

    

    if($('#group_insurance').val()=='')

    {

    //alert(11);

    var group_insurance = 0;

    }

    else

    {

    //alert(12);

    var group_insurance = $('#group_insurance').val();

    }

    

    if($('#encashment_leave').val()=='')

    {

    //alert(11);

    var encashment_leave = 0;

    }

    else

    {

    //alert(12);

    var encashment_leave = $('#encashment_leave').val();

    }

    

    if($('#any_payment').val()=='')

    {

    //alert(11);

    var any_payment = 0;

    }

    else

    {

    //alert(12);

    var any_payment = $('#any_payment').val();

    }

    

    if($('#family_pension').val()=='')

    {

    //alert(11);

    var family_pension = 0;

    }

    else

    {

    //alert(12);

    var family_pension = $('#family_pension').val();

    }

    

    if($('#calculation_hospitalization').val()=='')

    {

    //alert(11);

    var calculation_hospitalization = 0;

    }

    else

    {

    //alert(12);

    var calculation_hospitalization = $('#calculation_hospitalization').val();

    }

    

    if($('#move_immovable').val()=='')

    {

    //alert(11);

    var move_immovable = 0;

    }

    else

    {

    //alert(12);

    var move_immovable = $('#move_immovable').val();

    }

    if($('#income_dependant_employee').val()=='')

    {

    //alert(11);

    var income_dependant_employee = 0;

    }

    else

    {

    //alert(12);

    var income_dependant_employee = $('#income_dependant_employee').val();

    }

    

    

    

    

    

    

    

    //var any_payment = $('#any_payment').val();

    

    

    //var total_lumsum = parseInt(death_gratuity)+parseInt(group_insurance);

    var total_lumsum = parseInt(death_gratuity)+parseInt(group_insurance)+parseInt(encashment_leave)+parseInt(any_payment);

    console.log('Total Lumsum:'+total_lumsum);

    console.log('Calculation Hospitalization:'+calculation_hospitalization);

    

    var monthly_consider_income = parseInt(total_lumsum)-parseInt(calculation_hospitalization);

    var interest=Math.round(((monthly_consider_income*8)/100)/12);

    console.log('Interest:'+interest);

    $('#total_lumsum').val(total_lumsum);

    $('#caluculation_expen').val(monthly_consider_income);

    

    $('#interest_calculation').val(interest);

    

    var total_income = parseInt(family_pension)+parseInt(interest)+parseInt(move_immovable)+parseInt(income_dependant_employee);

    //alert(death_gratuity);

    $('#total_income').val(total_income);

    

    var gross = $('#last_pay_drawn_partc').val();

    

    

    

    percentage_monthly_income=Math.round(((total_income/gross)*100));

    //alert(percentage_monthly_income);

    $('#percentage_monthly_income').val(percentage_monthly_income);

    

    //total_income

    //return false;

    }

    

    

    

    

    function add_row(id)

    {

    

    

    

    var id_length=id.length;

    

    var id=id.substr(7,id_length);

    //alert(id);		

    $("#tbl_family").each(function(){

    

    

    //var table = $(this);

    //var n = $('tr:last td', this).length;

    //alert($('#tbl_family tr').length);

    var r = $('#tbl_family tr').length;

    //var last_tr_id=$('#tbl_family tr:last').attr('id').substring(2,3);

    if('tr'+id==$('#tbl_family tr:last').attr('id'))

    { 

    var tds = '<tr id="tr'+r+'">';

    tds+='<td>Family Member Name</td>';

    tds+='<td><input type="text" class="form-control upper_case" id="family_name'+r+'" name="family_name[]" placeholder="NAME" autocomplete="off" value=""></td>';

    tds+='<td>Family Member Age</td>';

    tds+='<td><input type="text" class="form-control upper_case" maxlength="2" id="family_age'+r+'" name="family_age[]" placeholder="AGE" autocomplete="off" value="" onKeyPress="return keyRestrict(event,0123456789);"></td>';

    tds+='<td>Family Member Qualification</td>';

    

    tds+='<td><select class="form-control upper_case" id="family_quali0" name="family_quali[]" autocomplete="off" value=""><option>---- Please Select--- </option><option>---- Please Select--- </option><? foreach($arr_desig as $key){ $key['code']. '<br />'; ?><option value="<?= $key['code']; ?>" <? if($applicant_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option><? } ?></select></td>';

    

    tds+='<td>Family Member Relationship</td>';

    tds+='<td><select class="form-control upper_case" id="family_relation0" name="family_relation[]" placeholder="AGE" autocomplete="off" value=""><option>---- Please Select--- </option><? foreach($arr_relation as $key){ $key['code']. '<br />'; ?><option value="<?= $key['code']; ?>" <? if($applicant_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option><? } ?></select></td>';

	tds+='<td>Family Member Dependent on Deceased Employee</td>';

	tds+='<td><select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" placeholder="AGE" autocomplete="off" value=""><option>---- Please Select--- </option><? foreach($arr_de as $key){ $key['code']. '<br />'; ?><option value="<?= $key['code']; ?>" <? if($applicant_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option><? } ?></select></td>';

	

	  //tds+='<td>Family Member Dependent on Deceased Employee</td>';

//	tds+='<td><select class="form-control upper_case" id="family_dependent0" name="family_dependent[]" autocomplete="off" value=""><option>---- Please Select--- </option>

//	 <option value="<?=1;?>">YES</option></select></td>';

		

	

	

	

    

    tds+=' <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row'+r+'" class="add_row3" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"><img onclick="return remove_row(this.id)" style="cursor:pointer;" id="remove_row'+r+'" class="remove_row3" title="Click To Remove" src="../../themes/default/image/remove_row_image.png" width="15"></td>';

    tds += '</tr>';

    

    if($('tbody', this).length > 0)

    {

    

    $('#tbl_family tr').last().after(tds);

    //$( "#tbl1 tr:odd" ).css( "background-color", "#CCE6FF" );

    //$( "#tbl1 tr:even" ).css( "background-color", "#DDF7FF" );

    }

    

    }

    })

    

    

    }

    

    

    function remove_row(id)

    {

    

    //alert(11);

    //$('#add_incr').remove();

    

    var rowCount = $('#tbl_family tr').length;

    var tr_id=id.substr(10);

    //alert(tr_id);

    if(rowCount>1)

    {

    $('#tr'+tr_id).remove();

    }

    

    }

    

	

	function employee_typ_f(type){

		

		//alert(type);

	if(type=='1993'){

	$('#Death_date_date').show();

	$('#Death_date').show();

	$('#premature_date_date').hide();

	$('#premature_date').hide();

	

	

	}else{

	$('#Death_date_date').hide();

	$('#Death_date').hide();

	

	$('#premature_date_date').show();

	$('#premature_date').show();

	

	

	

	}

	}

    //location.reload();

    

    function candidatefulfilrules(type){

    if(type=='0'){

    $('#candidate_fulfil_no_lable').show();

    $('#candidate_fulfil_no_div').show();

    $('#candidate_fulfil_remarks').show();

    }else{

    $('#candidate_fulfil_no_lable').hide();

    $('#candidate_fulfil_no_div').hide();

    $('#candidate_fulfil_remarks').hide();

    }

    }

	

	 function candidateunfitcer(type){

    if(type=='0'){

    $('#candidate_unfit_cer_lable').show();

    $('#candidate_unfit_cer_div').show();

    $('#candidate_unfit_cer_remarks').show();

    }else{

    $('#candidate_unfit_cer_lable').hide();

    $('#candidate_unfit_cer_div').hide();

    $('#candidate_unfit_cer_remarks').hide();

    }

    }

	

	 function divorcedecree(type){

    if(type=='0'){

    $('#divorce_decree_lable').show();

    $('#divorce_decree_div').show();

    $('#divorce_decree_remarks').show();

    }else{

    $('#divorce_decree_lable').hide();

    $('#divorce_decreer_div').hide();

    $('#divorce_decree_remarks').hide();

    }

    }

	

	

	function candidatepartfrom(type){

	if(type=='0'){	

	$('#candidate_part_from_lable').show();

    $('#candidate_part_from_div').show();

    $('#candidate_part_from_remarks').show();

    }else{

    $('#candidate_part_from_lable').hide();

    $('#candidate_part_from_div').hide();

    $('#candidate_part_from_remarks').hide();

    }

		

	}

	

	function vistsport(type){

	if(type=='0'){	

	$('#vist_sport_lable').show();

    $('#vist_sport_div').show();

    $('#vist_sport_remarks').show();

    }else{

    $('#vist_sport_lable').hide();

    $('#vist_sport_div').hide();

    $('#vist_sport_remarks').hide();

    }

		

	}

	

	function authenticathoo(type){

	if(type=='0'){	

	$('#authenticat_hoo_lable').show();

    $('#authenticat_hoo_div').show();

    $('#authenticat_hoo_remarks').show();

    }else{

    $('#authenticat_hoo_lable').hide();

    $('#authenticat_hoo_div').hide();

    $('#authenticat_hoo_remarks').hide();

    }

		

	}

	

	

	

	

	function candidatefavour(type){

	if(type=='0'){	

	$('#candidate_favour_lable').show();

    $('#candidate_favour_div').show();

    $('#candidate_favour_remarks').show();

    }else{

    $('#candidate_favour_lable').hide();

    $('#candidate_favour_div').hide();

    $('#candidate_favour_remarks').hide();

    }

		

	}

	

	

	

	

	 function candidateproforma(type){

    if(type=='0'){

    $('#candidate_p_roforma_lable').show();

    $('#candidate_p_roforma_div').show();

    $('#candidate_p_roforma_remarks').show();

    }else{

    $('#candidate_p_roforma_lable').hide();

    $('#candidate_p_roforma_div').hide();

    $('#candidate_p_roforma_remarks').hide();

    }

    }

    

    function clearvacanyroster(type){

    if(type=='0'){

    $('#clear_vacany_roster_lable').show();

    $('#clear_vacany_roster_div').show();

    $('#clear_vacany_roster_remarks').show();

    }else{

    $('#clear_vacany_roster_lable').hide();

    $('#clear_vacany_roster_div').hide();

    $('#clear_vacany_rosterl_remarks').hide();

    }

    }

	

	

    function candidatefulfilall(type){

    if(type=='0'){

    $('#candidate_fulfil_all_lable').show();

    $('#candidate_fulfil_all_div').show();

    $('#candidate_fulfil_all_remarks').show();

    }else{

    

    $('#candidate_fulfil_all_lable').hide();

    $('#candidate_fulfil_all_div').hide();

    $('#candidate_fulfil_all_remarks').hide();

    }

    }

    

    function candidateenquiryrecommedation(type){

    if(type=='0'){

    $('#candidate_enquiry_recommedation_lable').show();

    $('#candidate_enquiry_recommedation_div').show();

    $('#candidate_enquiry_recommedation_remarks').show();

    }else{

    

    $('#candidate_enquiry_recommedation_lable').hide();

    $('#candidate_enquiry_recommedation_div').hide();

    $('#candidate_enquiry_recommedation_remarks').hide();

    }

    }

    

    function candidateanyrelaxation(type){

    $('#age_check').show();

    

    if(type=='0'){

    //alert(44);

    $('#candidate_any_relaxation_lable').show();

    $('#candidate_any_relaxation_div').show();

    $('#candidate_any_relaxation_remarks').show();

    $('#age_check').hide();

    $('#age_check_new').hide();

    }else{

    

    

    $('#age_check').show();

    $('#age_check_new').hide();

    $('#candidate_any_relaxation_lable').hide();

    $('#candidate_any_relaxation_div').hide();

    $('#candidate_any_relaxation_remarks').hide();

    

    //alert(222);

    

    $('#check_age_value').click(function(){

    if($(this).is(':checked')){

    

    $('#check_age_value').val(<?='1'?>)

    

    }else{

    

    $('#check_age_value').val(<?='0';?>)

    }

    

    

    })

    

    $('#check_education_value').click(function(){

    if($(this).is(':checked')){

    

    $('#check_education_value').val(<?='1'?>)

    

    }else{

    

    $('#check_education_value').val(<?='0';?>)

    }

    

    

    })

    }

    

    

    

    

    }

    

    

    

    function file_upload(k,f,app_id,emp_id){

    

    //alert(f);

    

    

    

    var property = document.getElementById(k).files[0];

    var image_name = property.name;

    var image_extension = image_name.split('.').pop().toLowerCase();

    

	

	

	

	

    if(jQuery.inArray(image_extension,['pdf']) == -1 && f!='QRVMSZlRWBjVxEUP'){

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

        //alert(data);

          

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

          if(data== '8'){

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

          

          if(data== '9'){

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

		  if(data== '10'){

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

		  

		  if(data== '11'){

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

		  if(data== '16'){

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

		  if(data== '17'){

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

		  if(data== '18'){

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

		  if(data== '19'){

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

	

	

	$(document).on("click","#submit5",function() {

// alert(8888);

	var emp_id_const = '<?php echo $_SESSION['emp_id_const']; ?>';

    console.log(emp_id_const);  //$("#emp_id_const").val();

  if(emp_id_const != '')

     {

          $.ajax({

              url : 'ajax_preview_cg_form.php',

              type : 'POST',

              data : { "emp_id_const" : emp_id_const

        				

        				},

                success : function(response) { 

        		

        		

        		//alert(response);

        			//var result1 = $.parseJSON(response);

        			

        			$(".mbody").html(response);

        			

                },

                error:function(){

                  alert('Server Error');

                }

              });

      }

      else

      {

          alert('Employee ID is not selected');

      }

});





	/*$(document).on("click","#submit6",function() {

	//alert(111); 

	

	//var id=$("#emp_id").val();

	$('#finalize').modal('toggle');

	

	

	

	});*/

	

	

	

	 function edit(k){

	

	

	var edit_id=$("#edit_id").val(k);

	$('#edit').modal('toggle');

	

	

	};

	

	

	/*

	function final_save(k,val){

		

		//alert(1);

		$.post('<?= $config['base_url'] ?>page/intra_pri/intra_pri_final_submit.php?form_flage='+val+'&app_id='+k, function(data){

			

			alert(data);

			return false;

			

		//$("#employee_id").html(data);

		

		//$("#employee_id_ps").html(data);

			

		});

	

	//alert(111);

	//false;

	

	

	}

	*/

	

	 function del(k,l){

	

	var delete_f=$("#delete_f").val(k);

	var delete_id=$("#delete_id").val(l);

	

	$('#delet').modal('toggle');

	

	

	};

    

    

    

    </script>

    

    

    <div class="modal fade" id="gpprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<style>

.modal-backdrop fade in{

	height:auto !important;

}

.message{text-align: center;

    color: #fb0b0b;

    font-size: 22px;

    font-weight: 500;}

</style>

  <div class="modal-dialog modal-lg">

    <div class="modal-content" style="width: 160%; margin-left: -30%;">

      <div class="modal-header">

	  <h4 class="modal-title" id="myModalLabel">COMPASSIONATE GROUND APPLICATION </h4>

        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        

      </div>

      <div class="modal-body"> 

      <div class="mbody"> 

      </div>

      </div>

      <div class="modal-footer">

        <!--<button type="button" class="btn btn-success" > FORWARD</button>-->

        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>

      </div>

    </div>

  </div>

</div>







 <!---------------------------------------Final submit modal start--------------------------->

    <div class="modal fade bs-example-modal-sm" id="finalize" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

   		 <div class="modal-dialog modal-sm">

   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">

    			<div class="modal-header">

    				<h4 class="modal-title" id="myModalLabel">COMPASSIONATE GROUND APPLICATION</h4>

    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    

   				 </div>

    <div class="modal-body"> 

    <!--<form action="" method="post">-->

    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Submit Employee  Compassionate Ground Application Profile ?</strong></p>

    </div>

    <div class="modal-footer">

    

    <div class="btn-group">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

  

     <input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("final",4); ?>" /> 

     

    <input type="button"  onclick="final_save('<?php echo $crypto->encode($application_id,4); ?>','<?= $crypto->encode("final",4); ?>');"name="submit" value="YES" class="btn btn-success finalize" />

    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      

    </div>

    </div>

    </div>

    

    <!--</form>-->

    </div>

    </div>

   

<!---------------------------------------Final submit modal End--------------------------->





    <div class="modal fade bs-example-modal-sm" id="edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

   		 <div class="modal-dialog modal-sm">

   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">

    			<div class="modal-header">

    				<h4 class="modal-title" id="myModalLabel">COMPASSIONATE GROUND APPLICATION</h4>

    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    

   				 </div>

    <div class="modal-body"> 

    <form action="intra_pri_cg_profile_submit.php" method="post">

    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Edit Employee Compassionate Ground Application Profile ?</strong></p>

    </div>

    <div class="modal-footer">

    

    <div class="btn-group">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    <input type="hidden" id="edit_id" name="edit_id" />

    <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />

    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      

    </div>

    </div>

    </div>

    

    </form>

    </div>

    </div>

    

    

    

     <div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

   		 <div class="modal-dialog modal-sm">

   			 <div class="modal-content" style="width: 110%; margin-left: -25%;">

    			<div class="modal-header">

    				<h4 class="modal-title" id="myModalLabel">COMPASSIONATE GROUND APPLICATION</h4>

    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    

   				 </div>

    <div class="modal-body"> 

    <form action="intra_pri_cg_profile_submit.php" method="post">

    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>

    </div>

    <div class="modal-footer">

    

    <div class="btn-group">

    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />

    

    <input type="hidden" id="delete_id" name="delete_id" />

    <input type="hidden" id="delete_f" name="delete_f" />

    <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />

    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      

    </div>

    </div>

    </div>

    

    </form>

    </div>

    </div>