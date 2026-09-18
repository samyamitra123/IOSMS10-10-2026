<?php
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();

$db=new database();

$emp_data=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
/*foreach($emp_data as $item){
	print_r($item);
}
*/
$arr=$db->fetch_table("select* from prd_stake_epension_employee_profile where emp_id_fk='".$cryptoGraph->decode($_REQUEST['id'],4)."'  ");
	$momo_no= $arr[0]['first_momo_no'];
	$wef_date=$arr[0]['first_momo_wef_date'];
	$stake=$arr[0]['stake_level_id_fk']; 
	$status= $arr[0]['status'];
	$present_memo_no=$arr[0]['present_memo_no'];
	$present_momo_date=$arr[0]['presnt_memo_wef_date'];
$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");
function AppoitmentType($appType)
 {
     if($appType == 'D')
     	return 'Direct';
     else if($appType == 'P')
     	return 'Promoted';
     else
     	return 'Not Inserted';

 }
function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}
function code_master($dateval)
{
	$db=new database();
	 $arr = $db->fetch_table("SELECT code, description, code_master_id_pk
																		FROM prd_dise_code_master WHERE code ='".$dateval."' AND length(code)=3
																		");
	return $arr[0]['description'];																	
																		
}
function fun_payband($val){
		$db = new database();
		$data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
		return $data[0]['payband_name'];
	}
function fun_payscale($val){
		$db = new database();
		$data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
		return $data[0]['payscale_range'];
	}
function fun_dist($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
		return $dist_data2[0]['district_name'];
	}
function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
	}
function date_frmt($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL){
		return "---";
	}
	else{
		$old=explode("-",$original_date);
        $new=$old[2]."-".$old[1]."-".$old[0];
		return $new;
	}
}
function fun_grade_pay($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $dist_data2[0]['grade_amount'];
	}	
	
	function dist_name($dateval)
{	$db=new database();
    $arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district where district_id_pk='".$dateval."'");
	return $arr[0]['district_name'];
}
function ps_name($dateval)
{	$db=new database();
	$arr=$db->fetch_table("select ps_id_pk,ps_name,ps_code from prd_location_master_panchayat_samiti where ps_code='".$dateval."'");
	return $arr[0]['ps_name'];
}


function block_name($dateval)
{	$db=new database();
	$arr=$db->fetch_table("select block_id_pk,block_name,block_code from prd_location_master_block where block_id_pk='".$dateval."'");
	return $arr[0]['block_name'];
}

function gp_name($dateval)
{	$db=new database();
     $arr=$db->fetch_table("select gp_id_pk,gp_name,gp_code from prd_location_master_gp where gp_code='".$dateval."'");
	return $arr[0]['gp_name'];
}

?>
<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_ps/da/ps_emp/pdf_employe_details.php?id=<?php echo $_GET['id'] ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>
<div id="accordion">
       <h3 id="att" onclick=""> Primary Profile </h3>
        <div id="attraction">
        	<table border="0" width="100%">
				<? if($emp_data[0]['emp_id_const']!='0'){ ?>
                <tr>
					<td  class="text_r" width="25%" >Employee ID :</td>
					<td colspan="3" width="25%"><?php echo  $emp_data[0]['emp_id_const']; ?></td>
				</tr>
                 <? } ?>
                <tr>
					<td  class="text_r" width="25%" >Name :</td>
					<td colspan="3" width="25%"><?php echo  $emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'] ; ?></td>
				</tr>
				<tr>
					<td  class="text_r" width="25%">Date of Birth :</td>
					<td width="25%"><?php echo  date_frmt($emp_data[0]['emp_dob']) ; ?></td>
					<td class="text_r" width="25%">Sex :</td>
					<td width="25%"><?php
					echo fun_common($emp_data[0]['emp_sex'],$code_data);
					 ?></td>
				</tr>
				<tr>
					<td class="text_r" width="25%">Caste :</td>
					<td width="25%"><?= fun_common($emp_data[0]['emp_caste'],$code_data); ?></td>
					<td class="text_r" width="25%">Voter ID :</td>
					<td width="25%"><?php echo strtoupper($emp_data[0]['emp_voter_id']);  ?></td>
				</tr>
                <tr>
					<td class="text_r" width="25%">Aadhaar ID :</td>
					<td width="25%"><?= $emp_data[0]['emp_aadhar_no'] ?></td>
				<td class="text_r" width="25%">Educational Qualification :</td>
					<td width="25%"><?php
					echo fun_common($emp_data[0]['emp_edu_quali'],$code_data);
					 ?></td>
                </tr>
                
				</table>
        </div>
        <h3 id="att" onclick=""> Professional Profile </h3>
        <div id="attraction">
        <table border="0" width="100%">
				<tr>
					<td  class="text_r" width="25%" >Designation :</td>
					<td style="text-transform:uppercase;" width="25%"><?php echo fun_common( $emp_data[0]['emp_desig'],$code_data); ?></td>
                    <td class="text_r" width="25%">Date of First Joining in service :</td>
					<td width="25%"><?php echo  date_frmt($emp_data[0]['emp_first_join_date']) ; ?></td>
				</tr>
				
				<tr>
                 <td  class="text_r" width="25%">Date of Confirmation in Service Applicable :</td>
                 <td width="25%"><?php
					echo fun_common($emp_data[0]['conf_dt_flag'],$code_data);
					 ?></td>
                     <td class="text_r" width="25%">&nbsp;</td>
					<td width="25%">&nbsp;</td>
                 </tr>
                 <tr>
					<td class="text_r" width="25%">Date of Confirmation in Service :</td>
					<td width="25%"><?php
					echo date_frmt($emp_data[0]['emp_conf_join_date']);
					 ?></td>
					<td class="text_r" style="font-size:13px;" width="25%">Date of Joining in the Present Post :</td>
					<td width="25%"><?= date_frmt($emp_data[0]['emp_join_prsnt_post_date']); ?></td>
				</tr>
                <tr>
					<td class="text_r" style="font-size:13px;" width="25%">Date of Joining in the Present Office :</td>
					<td width="25%"><?php
					echo date_frmt($emp_data[0]['emp_join_prsnt_office_date']);
					 ?></td>
					<td class="text_r" style="font-size:13px;" width="25%">Date of Retirement/Termination :</td>
					<td width="25%"><?= date_frmt($emp_data[0]['emp_retirement_date']); ?></td>
				</tr>
                <?php if($emp_data[0]['emp_desig']!='9012' && $emp_data[0]['emp_desig']!='9013' && $emp_data[0]['emp_desig']!='9014' && $emp_data[0]['emp_desig']!='9015' && $emp_data[0]['emp_desig']!='9016' && $emp_data[0]['emp_desig']!='9017'){ ?> 
                <tr>
					<td class="text_r" width="25%">Whether on Deputation :</td>
					<td width="25%"><?php
					echo fun_common($emp_data[0]['emp_status_deputation'],$code_data);
					 ?></td>
					<td class="text_r" width="25%">Employee Group:</td>
					<td width="25%"><? echo fun_common($emp_data[0]['emp_group'],$code_data); ?></td>
				</tr>
                <?php } ?>
                <tr>
                
                
                <tr>
					<td class="text_r" width="25%">Present Joining Memo no <span class="star_color">(Memo No. of joining in the present post in present office)</span>:</td>
					<td width="25%"><?php
					echo $present_memo_no;
					 ?></td>
					<td class="text_r" style="font-size:13px;" width="25%">Present Joining Memo Date :</td>
					<td width="25%"><?= date_frmt($present_momo_date); ?></td>
				</tr>
                
                
                <tr>
					<td class="text_r" width="25%">First Joining Memo no:</td>
					<td width="25%"><?php
					echo $momo_no;
					 ?></td>
					<td class="text_r" style="font-size:13px;" width="25%">First Joining Memo Date:</td>
					<td width="25%"><?= date_frmt($wef_date); ?></td>
				</tr>
                
                <tr>
					<td class="text_r" width="25%">First Joining In:</td>
					<td width="25%"><?php
					echo code_master($stake);
					 ?></td>
					<td class="text_r" style="font-size:13px;" width="25%"></td>
					<td width="25%"></td>
				</tr>
                
                
                <?php if($stake=='557')
 {
	 
	 $display= "style='display:yes;'";
	 $display1= "style='display:none;'";
	 $display2= "style='display:none;'";
	 $district_id=$arr[0]['district_id_fk']; 
	  $display1= "style='display:none;'";
 }
 else if($stake=='556')
 {
	 $display1= "style='display:yes;'";
	 $display2= "style='display:none;'";
	 $ps_code=$arr[0]['ps_code'];
	 $district_id=$arr[0]['district_id_fk'];
	 //$display= "style='display:none;'";
 }
 else if($stake=='555')
 {
	 $display2= "style='display:yes;'";
	 $display= "style='display:none;'";
	 $display1= "style='display:none;'";
	 $block_id_fk=$arr[0]['block_id_fk'];
	 $district_id=$arr[0]['district_id_fk'];
	 $gp_code= $arr[0]['gp_code'];
 }
 else
 {
	$display3= "style='display:none;'";
	$display2= "style='display:none;'";
	$display= "style='display:none;'";
	$display1= "style='display:none;'";

 }
 ?>
                
				<?php if($stake=='557')
                {?>
                <tr>
                    <td class="text_r" id="district_show" width="25%" >District:</td>
                    <td width="25%" id="district_id" ><?= dist_name($district_id); ?></td>
                    <td class="text_r" style="font-size:13px;"  width="25%"></td>
                    <td width="25%" id="ps_code" ></td>
                </tr>
                <?php }?>
                <?php if($stake=='556')
                {?>
                <tr>
                    <td class="text_r" id="district_show" width="25%" >District:</td>
                    <td width="25%" id="district_id" ><?= dist_name($district_id); ?></td>
                    <td class="text_r" id="ps_show" style="font-size:13px;"  width="25%">PS</td>
                    <td width="25%" id="ps_code" ><?= ps_name($ps_code); ?></td>
                </tr>
                
                <?php }?>
                
                <?php if($stake=='555')
                {?>
                
                <tr>
                    <td class="text_r" id="district_show_gp" width="25%" >District:</td>
                    <td width="25%" id="district_gp" > <?= dist_name($district_id); ?></td>
                    <td class="text_r" id="block_show" style="font-size:13px;"  width="25%" >BLOCK</td>
                    <td width="25%" id="block_id" ><?= block_name($block_id_fk); ?></td>
                </tr>
                
                <tr>
                    <td class="text_r" id="gp_show" width="25%" >GP:</td>
                    <td width="25%" id="gp_code" ><?= gp_name($gp_code); ?></td>
                    <td class="text_r">Appointment Type</td>
                    <td width="25%" ><?php echo AppoitmentType($emp_data[0]['recruitment_type']); ?></td>
                </tr>
                
                <?php }?>
					<td class="text_r" width="25%">Designation at First Appointment :</td>
					<td style="text-transform:uppercase;" width="25%"><?php
				
					if($emp_data[0]['emp_desig_first_app']=='1114' ||$emp_data[0]['emp_desig_first_app']=='1115'|| $emp_data[0]['emp_desig_first_app']=='1118')
						{
						echo 'GP&nbsp;'. fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
						}
						else
						{
						echo fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
						}
					 ?></td>
                     <td class="text_r" width="25%">Date of Next Increment :</td>
					<td width="25%"><?php echo date_frmt($emp_data[0]['emp_next_increment_date']);?></td>
					
				</tr>
                <tr>
					
					<td class="text_r" width="25%">Amount of Increment  <span class="star_color">(On Basic Pay)</span>:</td>
					<td width="25%"><?php echo $emp_data[0]['emp_next_increment_amount'];?></td>
					<td class="text_r">NGIPF Account No</td>
					<td><?php echo ($emp_data[0]['gpf_acc_no'] == '')?'-':$emp_data[0]['gpf_acc_no'];?></td>					
				</tr>
				</table>
        
        </div>
        <h3 id="att" onclick="">Salary Profile </h3>
        <div id="attraction">
                <table border="0" width="100%">
				<?php if($emp_data[0]['emp_desig']=='9012' || $emp_data[0]['emp_desig']=='9013' || $emp_data[0]['emp_desig']=='9014' || $emp_data[0]['emp_desig']=='9015' || $emp_data[0]['emp_desig']=='9016' || $emp_data[0]['emp_desig']=='9017'){ ?> 
                <tr>
					<td  class="text_r" width="25%" >Consolidated Pay :</td>
					<td colspan="3" width="25%" ><?php echo $emp_data[0]['emp_cosolidated_pay']; ?></td>
				</tr>
                <?php } else { 
				
				if($emp_data[0]['ropa_status']=='1' ||$emp_data[0]['emp_first_join_date']>='2020-01-01' )
				{?>
					
					<tr>
					<td  class="text_r" width="25%">Level :</td>
					<td width="25%"><?php echo  $emp_data[0]['ropa_level'] ; ?></td>
                    <td class="text_r" width="25%">Basic Pay :</td>
					<td width="25%"><?= $emp_data[0]['emp_pay_in_payband']; ?></td>
					
				</tr>
				<?php }
				else
				{
				
				?>
				<tr>
					<td  class="text_r" width="25%">Pay Band :</td>
					<td width="25%"><?php echo  fun_payband($emp_data[0]['emp_pay_band']) ; ?></td>
                    <td class="text_r" width="25%">Pay Scale :</td>
					<td width="25%"><?= fun_payscale($emp_data[0]['emp_pay_scale']); ?></td>
					
				</tr>
				<tr>
					<td class="text_r" width="25%">Pay in Pay Band</td>
					<td width="25%"><?php echo  $emp_data[0]['emp_pay_in_payband'] ; ?></td>
                    <td class="text_r" width="25%">Grade Pay :</td>
					<td width="25%"><?php echo fun_grade_pay($emp_data[0]['emp_grade_pay']);?></td>
					
				</tr>
                <?php }} ?>
                <tr>
					<td class="text_r" width="25%">Bank Name :</td>
					<td width="25%"><?php echo fun_bank($emp_data[0]['emp_bank_name']);?></td>
					<td class="text_r" width="25%">Branch Name :</td>
					<td style="text-transform:uppercase;" width="25%"><?= $emp_data[0]['emp_bank_branch'] ?></td>
				</tr>
                <tr>
					<td class="text_r" width="25%">Branch Code :</td>
					<td style="text-transform:uppercase;" width="25%"><?php echo $emp_data[0]['emp_branch_code'];?></td>
					<td class="text_r" width="25%">MICR Code:</td>
					<td style="text-transform:uppercase;" width="25%"><? echo $emp_data[0]['emp_micr_no']; ?></td>
				</tr>
                <tr>
					<td class="text_r" width="25%">Account No :</td>
					<td width="25%"><?php echo $emp_data[0]['emp_acc_no'];?></td>
					<td class="text_r" width="25%">IFSC Code :</td>
					<td width="25%"><?php echo $emp_data[0]['emp_ifsc_no'];?></td>
				</tr>
				</table>
        </div>
        <h3 id="att" onclick=""> Personal Profile </h3>
        <div id="attraction">
        <table border="0" width="100%">
				<tr>
					<td  class="text_r" width="25%" >Father’s Name :</td>
					<td colspan="3" width="25%"><?php echo $emp_data[0]['emp_father_name']; ?></td>
				</tr>
                <tr>
					<td  class="text_r" width="25%">Mother’s Name :</td>
					<td colspan="3" width="25%"><?php echo $emp_data[0]['emp_mother_name']; ?></td>
				</tr>
				<tr>
					<td  class="text_r" width="25%">Religion :</td>
					<td width="25%"><?php echo  fun_common($emp_data[0]['emp_religion'],$code_data) ; ?></td>
					<td class="text_r" width="25%">Mother Tongue</td>
					<td width="25%"><?php echo  fun_common($emp_data[0]['emp_mother_tongue'],$code_data) ; ?></td>
				</tr>
				<tr>
					<td class="text_r" width="25%">Marital status :</td>
					<td width="25%"><?php echo fun_common($emp_data[0]['emp_marital_status'],$code_data);?></td>
					<td class="text_r" width="25%"></td>
					<td width="25%"></td>
				</tr>
                <?php if($emp_data[0]['emp_marital_status']!='242'){ ?> 
                <tr>
					<td  class="text_r" width="25%" >Spouse Name :</td>
					<td colspan="3" width="25%"><?php echo $emp_data[0]['emp_spouse_name']; ?></td>
				</tr>
                <?php } if($emp_data[0]['emp_desig']!='9012' && $emp_data[0]['emp_desig']!='9013' && $emp_data[0]['emp_desig']!='9014' && $emp_data[0]['emp_desig']!='9015' && $emp_data[0]['emp_desig']!='9016' && $emp_data[0]['emp_desig']!='9017'){ 
				
				if($emp_data[0]['emp_marital_status']!='242'){
				?> 
                
                <tr>
					<td class="text_r" width="25%">Whether spouse is employed :</td>
					<td width="25%"><?php echo fun_common($emp_data[0]['emp_spouse_job_status'],$code_data);?></td><td width="25%">&nbsp;</td><td width="25%">&nbsp;</td>
                    </tr>
                    <tr>
					<td class="text_r" width="25%">Employment Details :</td>
					<td style="text-transform:uppercase;" width="25%"><?= $emp_data[0]['emp_spouse_details'] ?></td>
			
					<td class="text_r" width="25%">Spouse pay :</td>
					<td width="25%"><?php echo $emp_data[0]['emp_spouse_pay'];?></td>
                    </tr>
                    <tr>
					<td class="text_r" width="25%">Spouse HRA:</td>
					<td width="25%"><? echo $emp_data[0]['emp_spouse_hra']; ?></td>
                    <td class="text_r" width="25%"></td><td width="25%"></td>
					</tr>
			
                <?php } ?>
                <tr>
					<td class="text_r" width="25%">Spouse Opted for enrolment in WB Health Scheme / Employee Opted for enrolment in Swasthya Sathi:</td>
					<td width="25%"><? echo fun_common($emp_data[0]['spouse_medical_allowance'],$code_data); ?></td>
                    <td class="text_r" width="25%"></td><td width="25%"></td>
					</tr>
              
                <tr>
					<td class="text_r" width="25%">Residential Status :</td>
					<td width="25%"><?php echo fun_common($emp_data[0]['emp_spouse_res'],$code_data);?></td>
					<td class="text_r" width="25%">Spouse Housing Scheme :</td>
					<td style="text-transform:uppercase;" width="25%"><?php echo $emp_data[0]['emp_spouse_house_schm'];?></td>
				</tr>
                <? } ?>
                <tr>
					<td class="text_r" width="25%">PAN No :</td>
					<td width="25%"><?php echo $emp_data[0]['emp_pan_no'];?></td>
					<td class="text_r" width="25%">Blood Group :</td>
					<td width="25%"><?php echo fun_common($emp_data[0]['emp_blood_grp'],$code_data);?></td>
				</tr>
                <tr>
					<td class="text_r" width="25%">Height :</td>
					<td width="25%"><?php echo $emp_data[0]['emp_height']=='0'?'---':$emp_data[0]['emp_height'];?></td>
					<td class="text_r" width="25%">Identification Mark :</td>
					<td style="text-transform:uppercase;" width="25%"><?php echo $emp_data[0]['emp_idf_mark'];?></td>
				</tr>
                <tr>
					<td class="text_r" width="25%">Whether Differently Abled :</td>
					<td width="25%"><?php echo fun_common($emp_data[0]['emp_diff_able'],$code_data);?></td>
					<? if($emp_data[0]['emp_diff_able']=='1'){ ?>
                    <td class="text_r" width="25%">Status of Disability :</td>
					<td style="text-transform:uppercase;" width="25%"><?php echo $emp_data[0]['emp_disable_status'];?></td>
                    
                    <tr>
                    <td class="text_r" width="25%">Whether Eligible For Conveyance Allowance :</td>
                    
                    <td style="text-transform:uppercase;" width="25%"><? echo fun_common($emp_data[0]['conv_allow_status'],$code_data); ?></td><td class="text_r" width="25%"></td><td width="25%"></td>
                    
                    
                    <? } 
					
					else{ ?>
                    <td></td>
                    <td></td>
                    <? } ?>
				</tr>
				</table>
        </div>
       <h3 id="att" onclick=""> Contact Profile </h3>
        <div id="attraction">
                <h2 class="head_contact">Present Address</h2>
			<table border="0" width="100%">
				<tr>
					<td class="text_r"  width="25%">State :</td>
					<td  width="25%"><?php if($emp_data[0]['pre_state']){ echo "WEST BENGAL"; } else { echo "OTHERS"; }; ?></td>
					
					<td class="text_r"  width="25%">&nbsp;</td>
					<td  width="25%">&nbsp;</td>
				</tr>
				
				<tr>
					<td class="text_r"  width="25%">House No. :</td>
					<td  width="25%"><?php echo $emp_data[0]['emp_pre_house_no']; ?></td>
					
					<td class="text_r" width="25%">Street :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_pre_street_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">Town/ Village :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_pre_vill']; ?></td>
					
					<td class="text_r"  width="25%">Post Office :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_pre_post']; ?></td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">PIN :</td>
					<td  width="25%"><?php echo $emp_data[0]['emp_pre_pin']; ?></td>
					
					<td class="text_r"  width="25%">District :</td>
					<td  width="25%"><?php echo fun_dist($emp_data[0]['emp_pre_dist']); ?></td>
				</tr>
			</table>
			<h2 class="head_contact">Permanent Address</h2>
			<table border="0" width="100%">
				<tr>
					<td class="text_r"  width="25%">State :</td>
					<td  width="25%"><?php if($emp_data[0]['per_state']){ echo "WEST BENGAL"; } else { echo "OTHERS"; }; ?></td>
					
					<td class="text_r"  width="25%">&nbsp;</td>
					<td  width="25%">&nbsp;</td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">House No. :</td>
					<td  width="25%"><?php echo $emp_data[0]['emp_per_house_no']; ?></td>
					
					<td class="text_r"  width="25%">Street :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_per_street_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">Town/ Village :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_per_vill']; ?></td>
					
					<td class="text_r"  width="25%">Post Office :</td>
					<td style="text-transform:uppercase;"  width="25%"><?php echo $emp_data[0]['emp_per_post']; ?></td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">PIN :</td>
					<td  width="25%"><?php echo $emp_data[0]['emp_per_pin']; ?></td>
					
					<td class="text_r"  width="25%">District :</td>
					<td  width="25%"><?php echo fun_dist($emp_data[0]['emp_per_dist']); ?></td>
				</tr>
			</table>
			
			<h2 class="head_contact">Contact Details</h2>
			<table border="0" width="100%">
				<tr>
					<td class="text_r"  width="25%">Land Tel. No :</td>
					<td  width="25%"><?php if($emp_data[0]['emp_land_no'] == "0"){ echo "---"; } else {echo $emp_data[0]['emp_land_no'];} ; ?></td>
					
					<td class="text_r"  width="25%">Mobile No. :</td>
					<td  width="25%"><?php echo $emp_data[0]['emp_mobile_no']=='0'?'-----':$emp_data[0]['emp_mobile_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r"  width="25%">Email Id :</td>
					<td  width="25%"><?php if($emp_data[0]['emp_mail_id'] == ""){ echo "---"; } else {echo $emp_data[0]['emp_mail_id'];} ; ?></td>
					
					<td class="text_r" width="25%">&nbsp;</td>
					<td width="25%">&nbsp;</td>
				</tr>

			</table>
        </div>                    
                                   
      
      
       </div>


       <style>
					
					ul.mynav {
						list-style-type: none;
						margin: 0;
						padding: 0;
					}
					ul.mynav a:link, ul.mynav a:visited {
						display: block;
						font-weight: bold;
						color: #FFFFFF;
						background-color: #3BAAE3;
						/*padding: 4px;*/
						margin:2px;
						border-radius:3px;
						-moz-border-radius3px;
						text-decoration: none;
						text-transform: uppercase;
						height:35px;
						padding:8px 0px 1px 27px;
					}
					ul.mynav a:hover, ul.mynav a:active {
						background-color: #1988c1;
					}
					ul.mynav img{
						vertical-align: middle;
						padding-right: 10px;
					}
					.ui-accordion .ui-accordion-content{
						margin: 0px;
						padding: 10px;
					}
					.ui-widget {
					    font-size:14px;
					}
					.accordion .mynav ul li a img{
						border: 0px;
					}
					.accordion .mynav ul li a{
							height:60px;
							padding-left:10px;
					}
					.accordion h2{
						margin: 0px;
						padding-top: 10px;
						font-size: 16px;
					}
					
				</style>
                
                <script>
	$(function() {
		$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			collapsible: true,
			//active: true
		});
		//table row color
	    $( "tr:odd" ).css( "background-color", "#CCE6FF");
		$( "tr:even" ).css( "background-color", "#DDF7FF" );	
		//$("#att").trigger("click");
	});
</script>
<style>
td,tr{
	font-size: 14px;
	font-family: "calibri";
}
.text_r{
	/*text-align: right;*/
	font-weight:bold;
	width:30%;
	line-height:2.1
}
.text_l{
	text-align: left;
}
h2.head_contact{
	color: #FFF;
	text-transform: uppercase;
	text-align: center;
	background-color: #7DB2E2;
	margin: 1px;
	border-radius: 5px;
	-moz-border-radius: 5px;
	font-size:16px;
}

</style>