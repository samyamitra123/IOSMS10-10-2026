<?php
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
$emp_data=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
/*foreach($emp_data as $item){
	print_r($item);
}
*/
$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}
function fun_payband($val){
		$db = new database();
		$data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
		return $data[0]['payband_name'];
	}
function fun_grade_pay($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $dist_data2[0]['grade_amount'];
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

function fun_payscale($val){
		$db = new database();
		$data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
		return $data[0]['payscale_range'];
	}

function fun_emp_type($val){
		$db = new database();
		$emp_type = @$db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."';");
		return $emp_type[0]['description'];
	}
?>
<div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_zp/aeo/pdf_employe_details.php?id=<?php echo $_GET['id'] ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>
<div id="accordion">
       <h3 id="att" onclick=""> Primary Profile </h3>
        <div id="attraction">
        	<table border="0" width="100%">
				<? if($emp_data[0]['emp_id_const']!='0'){ ?>
                <tr>
					<td  class="text_r" >Employee ID :</td>
					<td colspan="3"><?php echo  $emp_data[0]['emp_id_const']; ?></td>
				</tr>
                 <? } ?>
                <tr>
					
                    <td  class="text_r">Employee Type :</td>
					<td colspan="3"><?php echo  fun_emp_type($emp_data[0]['zp_emp_type']) ; ?></td>
                    
				</tr>
                	
                    <tr>
                    <td  class="text_r" >Name :</td>
					<td><?php echo  $emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'] ; ?></td>
					<td  class="text_r">Date of Birth :</td>
					<td><?php echo  date_frmt($emp_data[0]['emp_dob']) ; ?></td>
					
				</tr>
              
				<tr>
                    <td class="text_r">Sex :</td>
					<td><?php
					echo fun_common($emp_data[0]['emp_sex'],$code_data);
					 ?></td>
					<td class="text_r">Caste :</td>
					<td><?= fun_common($emp_data[0]['emp_caste'],$code_data); ?></td>
				</tr>
                <tr>
                	<td class="text_r">Voter ID :</td>
					<td><?php echo strtoupper($emp_data[0]['emp_voter_id']);  ?></td>
					<td class="text_r">Aadhaar ID :</td>
					<td><?= $emp_data[0]['emp_aadhar_no'] ?></td>
				
                </tr>
                 <tr>
                    <td class="text_r">PAN No :</td>
					<td><?php echo $emp_data[0]['emp_pan_no'];?></td>
				<td class="text_r">Educational Qualification :</td>
					<td><?php
					echo fun_common($emp_data[0]['emp_edu_quali'],$code_data);
					 ?></td>
                </tr>
				</table>
        </div>
        <h3 id="att" onclick=""> Professional Profile </h3>
        <div id="attraction">
        <table border="0" width="100%">
				<tr>
                	<td  class="text_r" >Notification number of confirming the Appointment :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['notification_no']; ?></td>
					<td  class="text_r" >Designation :</td>
					<td style="text-transform:uppercase;"><?php echo fun_common( $emp_data[0]['emp_desig'],$code_data); ?></td>
                    
				</tr>
				
				<tr>
               	 	<td class="text_r">Date of First Joining in service :</td>
					<td><?php echo  date_frmt($emp_data[0]['emp_first_join_date']) ; ?></td>
                	<td  class="text_r">Date of Confirmation in Service Applicable :</td>
                 	<td><?php
					echo fun_common($emp_data[0]['conf_dt_flag'],$code_data);
					 ?></td>
                     
                 </tr>
                 <tr>
					<td class="text_r">Date of Confirmation in Service :</td>
					<td><?php
					echo date_frmt($emp_data[0]['emp_conf_join_date']);
					 ?></td>
					<td class="text_r" style="font-size:13px;">Date of Joining in the Present Post :</td>
					<td><?= date_frmt($emp_data[0]['emp_join_prsnt_post_date']); ?></td>
				</tr>
                <tr>
					<td class="text_r" style="font-size:13px;">Date of Joining in the Present Office :</td>
					<td><?php
					echo date_frmt($emp_data[0]['emp_join_prsnt_office_date']);
					 ?></td>
					<td class="text_r" style="font-size:13px;">Date of Retirement/Termination :</td>
					<td><?= date_frmt($emp_data[0]['emp_retirement_date']); ?></td>
				</tr>
                <?php if($emp_data[0]['emp_desig']!='1120'){ ?> 
                <tr>
					<td class="text_r">Whether on Deputation :</td>
					<td><?php
					echo fun_common($emp_data[0]['emp_status_deputation'],$code_data);
					 ?></td>
					<td class="text_r">Employee Group:</td>
					<td><? echo fun_common($emp_data[0]['emp_group'],$code_data); ?></td>
				</tr>
                <?php } ?>
                <tr>
					<td class="text_r">Designation at First Appointment :</td>
					<td style="text-transform:uppercase;"><?php
				
					if($emp_data[0]['emp_desig_first_app']=='1114' ||$emp_data[0]['emp_desig_first_app']=='1115'|| $emp_data[0]['emp_desig_first_app']=='1118')
						{
						echo 'GP&nbsp;'. fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
						}
						else
						{
						echo fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
						}
					 ?></td>
                     <td class="text_r">Date of Next Increment :</td>
					<td><?php echo date_frmt($emp_data[0]['emp_next_increment_date']);?></td>
					
				</tr>
                <tr>
					
					<td class="text_r">Amount of Increment  <span class="star_color">(On Basic Pay)</span>:</td>
					<td><?php echo $emp_data[0]['emp_next_increment_amount'];?></td><td class="text_r"></td><td>&nbsp;</td>
				</tr>
				</table>
        
        </div>
        <h3 id="att" onclick="">Salary Profile </h3>
        <div id="attraction">
                <table border="0" width="100%">
				<?php if($emp_data[0]['emp_desig']=='1120'){ ?> 
                <tr>
					<td  class="text_r" >Consolidated Pay :</td>
					<td colspan="3"><?php echo $emp_data[0]['emp_cosolidated_pay']; ?></td>
				</tr>
                <?php } else { ?>
				<tr>
					<td  class="text_r">Pay Band :</td>
					<td><?php echo  fun_payband($emp_data[0]['emp_pay_band']) ; ?></td>
                    <td class="text_r">Pay Scale :</td>
					<td><?= fun_payscale($emp_data[0]['emp_pay_scale']); ?></td>
					
				</tr>
				<tr>
					<td class="text_r">Pay in Pay Band</td>
					<td><?php echo  $emp_data[0]['emp_pay_in_payband'] ; ?></td>
                    <td class="text_r">Grade Pay :</td>
					<td><?php echo fun_grade_pay($emp_data[0]['emp_grade_pay']);?></td>
					
				</tr>
                <?php } ?>
                <tr>
					<td class="text_r">Bank Name :</td>
					<td><?php echo fun_bank($emp_data[0]['emp_bank_name']);?></td>
					<td class="text_r">Branch Name :</td>
					<td style="text-transform:uppercase;"><?= $emp_data[0]['emp_bank_branch'] ?></td>
				</tr>
                <tr>
					<td class="text_r">Branch Code :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_branch_code'];?></td>
					<td class="text_r">MICR Code:</td>
					<td style="text-transform:uppercase;"><? echo $emp_data[0]['emp_micr_no']; ?></td>
				</tr>
                <tr>
					<td class="text_r">Account No :</td>
					<td><?php echo $emp_data[0]['emp_acc_no'];?></td>
					<td class="text_r">IFSC Code :</td>
					<td><?php echo $emp_data[0]['emp_ifsc_no'];?></td>
				</tr>
				</table>
        </div>
        <h3 id="att" onclick=""> Personal Profile </h3>
        <div id="attraction">
        <table border="0" width="100%">
				<tr>
					<td  class="text_r" >Father’s Name :</td>
					<td colspan="3"><?php echo $emp_data[0]['emp_father_name']; ?></td>
				</tr>
                <tr>
					<td  class="text_r" >Mother’s Name :</td>
					<td colspan="3"><?php echo $emp_data[0]['emp_mother_name']; ?></td>
				</tr>
				<tr>
					<td  class="text_r">Religion :</td>
					<td><?php echo  fun_common($emp_data[0]['emp_religion'],$code_data) ; ?></td>
					<td class="text_r">Mother Tongue</td>
					<td><?php echo  fun_common($emp_data[0]['emp_mother_tongue'],$code_data) ; ?></td>
				</tr>
				<tr>
					<td class="text_r">Marital status :</td>
					<td><?php echo fun_common($emp_data[0]['emp_marital_status'],$code_data);?></td>
					<td class="text_r"></td>
					<td></td>
				</tr>
                <?php if($emp_data[0]['emp_marital_status']!='242'){ ?> 
                <tr>
					<td  class="text_r" >Spouse Name :</td>
					<td colspan="3"><?php echo $emp_data[0]['emp_spouse_name']; ?></td>
				</tr>
                <?php } if($emp_data[0]['emp_desig']!='1120'){ 
				
				if($emp_data[0]['emp_marital_status']!='242'){
				?> 
                
                <tr>
					<td class="text_r">Whether spouse is employed :</td>
					<td><?php echo fun_common($emp_data[0]['emp_spouse_job_status'],$code_data);?></td><td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
					<td class="text_r">Employment Details :</td>
					<td style="text-transform:uppercase;"><?= $emp_data[0]['emp_spouse_details'] ?></td>
			
					<td class="text_r">Spouse pay :</td>
					<td><?php echo $emp_data[0]['emp_spouse_pay'];?></td>
                    </tr>
                    <tr>
					<td class="text_r">Spouse HRA:</td>
					<td><? echo $emp_data[0]['emp_spouse_hra']; ?></td>
                    <td class="text_r"></td><td></td>
					</tr>
			
                <?php } ?>
                <tr>
                	<td class="text_r">Wheather Spouse is being Provided any Accomodation by Employer</td>
                    <td><? if($emp_data[0]['emp_accommodation']=="1") { echo "YES"; } else { echo "NO";} ?></td>
                <?php if($emp_data[0]['zp_emp_type'] == 365 || $emp_data[0]['zp_emp_type'] == 367){ ?>
					<td class="text_r">Opted for enrolment in WB Health Scheme:</td>
					<td><? echo fun_common($emp_data[0]['spouse_medical_allowance'],$code_data); ?></td>
                <?php } else { ?>
                	<td class="text_r"></td>
					<td></td>
                <?php }?>    
					</tr>
              
                <tr>
					<td class="text_r">Residential Status :</td>
					<td><?php echo fun_common($emp_data[0]['emp_spouse_res'],$code_data);?></td>
					<td class="text_r">Housing Scheme :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_spouse_house_schm'];?></td>
				</tr>
                <? } ?>
                <tr>
					<!--<td class="text_r">PAN No :</td>
					<td><?php echo $emp_data[0]['emp_pan_no'];?></td>-->
					<td class="text_r">Blood Group :</td>
					<td><?php echo fun_common($emp_data[0]['emp_blood_grp'],$code_data);?></td>
                    <td class="text_r"></td>
					<td></td>
				</tr>
                <tr>
					<td class="text_r">Height :</td>
					<td><?php echo $emp_data[0]['emp_height']=='0'?'---':$emp_data[0]['emp_height'];?></td>
					<td class="text_r">Identification Mark :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_idf_mark'];?></td>
				</tr>
                <tr>
					<td class="text_r">Whether Differently Abled :</td>
					<td><?php echo fun_common($emp_data[0]['emp_diff_able'],$code_data);?></td>
					<? if($emp_data[0]['emp_diff_able']=='1'){ ?>
                    <td class="text_r">Status of Disability :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_disable_status'];?></td>
                    
                    <tr>
                    <td class="text_r">Whether Eligible For Conveyance Allowance :</td>
                    
                    <td style="text-transform:uppercase;"><? echo fun_common($emp_data[0]['conv_allow_status'],$code_data); ?></td><td class="text_r"></td><td></td>
                    
                    
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
					<td class="text_r">State :</td>
					<td><?php if($emp_data[0]['pre_state']){ echo "WEST BENGAL"; } else { echo "OTHERS"; }; ?></td>
					
					<td class="text_r">Police Station :</td>
					<td><?php echo $emp_data[0]['emp_pre_ps']; ?></td>
				</tr>
				
				<tr>
					<td class="text_r">House No. :</td>
					<td><?php echo $emp_data[0]['emp_pre_house_no']; ?></td>
					
					<td class="text_r">Street :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_pre_street_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r">Town/ Village :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_pre_vill']; ?></td>
					
					<td class="text_r">Post Office :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_pre_post']; ?></td>
				</tr>
				<tr>
					<td class="text_r">PIN :</td>
					<td><?php echo $emp_data[0]['emp_pre_pin']; ?></td>
					
					<td class="text_r">District :</td>
					<td><?php echo fun_dist($emp_data[0]['emp_pre_dist']); ?></td>
				</tr>
			</table>
			
			<h2 class="head_contact">Permanent Address</h2>
			<table border="0" width="100%">
				<tr>
					<td class="text_r">State :</td>
					<td><?php if($emp_data[0]['per_state']){ echo "WEST BENGAL"; } else { echo "OTHERS"; }; ?></td>
					
					<td class="text_r">Police Station :</td>
					<td><?php echo $emp_data[0]['emp_per_ps']; ?></td>
				</tr>
				<tr>
					<td class="text_r">House No. :</td>
					<td><?php echo $emp_data[0]['emp_per_house_no']; ?></td>
					
					<td class="text_r">Street :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_per_street_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r">Town/ Village :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_per_vill']; ?></td>
					
					<td class="text_r">Post Office :</td>
					<td style="text-transform:uppercase;"><?php echo $emp_data[0]['emp_per_post']; ?></td>
				</tr>
				<tr>
					<td class="text_r">PIN :</td>
					<td><?php echo $emp_data[0]['emp_per_pin']; ?></td>
					
					<td class="text_r">District :</td>
					<td><?php echo fun_dist($emp_data[0]['emp_per_dist']); ?></td>
				</tr>
			</table>
			
			<h2 class="head_contact">Contact Details</h2>
			<table border="0" width="100%">
				<tr>
					<td class="text_r">Land Tel. No :</td>
					<td><?php if($emp_data[0]['emp_land_no'] == "0"){ echo "---"; } else {echo $emp_data[0]['emp_land_no'];} ; ?></td>
					
					<td class="text_r">Mobile No. :</td>
					<td><?php echo $emp_data[0]['emp_mobile_no']=='0'?'-----':$emp_data[0]['emp_mobile_no']; ?></td>
				</tr>
				<tr>
					<td class="text_r">Email Id :</td>
					<td><?php if($emp_data[0]['emp_mail_id'] == ""){ echo "---"; } else {echo $emp_data[0]['emp_mail_id'];} ; ?></td>
					
					<td class="text_r">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

			</table>
        </div>
                                            
        </div>
       <input type="hidden" name="emp_id" id="emp_id" value=<?php echo $cryptoGraph->encode($emp_data[0]['emp_id_pk'],4); ?> />
       <input type="hidden" name="emp_status" id="emp_status" value=<?php echo $emp_data[0]['emp_status']; ?> />
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