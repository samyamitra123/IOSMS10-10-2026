<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
if(isset($_GET['dise'])){
	$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//-----------------------------QUERY----------------------------------------------------------------------------------
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];


function salaryType($sal_type){
			$db = new database();
			$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}

$Query = "SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.part_day,sal.allowance, sal.consolidated_pay,sal.total_loan_deduction,sal.gsli,sal.consolidated_pay,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,
			tch.emp_first_name,
			tch.emp_second_name,
			tch.emp_last_name,
			tch.emp_pay_in_payband,
			tch.ropa_level,
			tch.ropa_status,
			sal.other_loan_deduction
		FROM
			prd_employee_salary_save as sal
		
		INNER JOIN 
			prd_employee_master as tch
			ON sal.emp_id_fk =tch.emp_id_pk AND sal.zp_id_fk= tch.zp_id_fk
			WHERE
				tch.emp_status in('1')	 
				AND sal.zp_id_fk = '".$_SESSION['location']['district_id']."'
				AND sal.status_flag in('1','2','3','4')  AND is_saved='1' AND sal.delete_status='1' AND salary_monthyear='".date('Ym')."' AND is_saved='1' AND requisition_type='".$requisition_type."' AND sal.ropa_status='1'
				order by tch.emp_first_name ;
	";
//print_r($Query); exit;
$tch= $db->fetch_table($Query);
	

//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->

<div class="content">
<!-- Common Back Button --->
	<?php require '../../../common_back_btns.php'; ?>	

    
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'].", ";
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'].", ";
                      }elseif(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'].", ";
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'].", ";
                    } ?></h2><h3>
			<?php   
			    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
			
                     ?></h3>
       </div>

    <div class="row" id="cont">
    <div class="content">
    <script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );	  

		  
		});
    </script>
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
    <div class="col-sm-12" style="width: 98%;">
        <h1 class="heading">View Salary Requisition</h1>
        <h2 class="heading">Salary Month Year : <?php echo date('M').','.date('Y') ?></h2>
			<div class="border"></div>
			<br>
				<?php 
         //$tch[0]['status_flag'] = 0;
				if(count($tch)){
					if($tch[0]['status_flag'] == 1){ ?>
						<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Not Finalized</strong></div>
						
					<?php }
					elseif($tch[0]['status_flag'] == 2){ ?>
						<div class="statusf">
                        <div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Finalized by DAA</strong></div>
                        </div>
						
					<?php } elseif($tch[0]['status_flag'] == 3){ ?>
						<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Forwarded by ACCOUNTANT</strong></div>
					<?php } } ?>
                 <div class="emplist">
				<div class="school">	
                <div class="table-responsive">
                
				<table width="100%">
					<tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th colspan="9">PAY & ALLOWANCE</th>
                    <th colspan="1"></th>
                    <th colspan="10">DEDUCTION</th>
                    <th>&nbsp;</th>
                    </tr>
                    <tr>
                    	<th>SL No.</th>
                    	<th>EMPLOYEE NAME</th>
                   		<th>CONSOLIDATED<br>PAY</th>
						<th>BASIC PAY</th>
						<th>LEVEL</th>
						<th>DA</th>
						<th>HRA</th>
						<th>MA</th>
                        <th>CONV<br>ALLOW</th>
                        <th>HILL ALLOWANCE</th>
                         <th>ADMINISTRATIVE <br> ALLOWANCE</th>
						<th>GROSS<br>SALARY</th>
						<th>GPF</th>
						<th>PF<br />Loan<br />RECOVERY</th>
						<th>PTax</th>
						<th>ITax</th>
                        <th>GSLI</th>
						<th>HRA DEDUCTION</th>
                        <th>OVER<br />DRAWN</th>
                        <th>FESTIVAL ADVANCE RECOVERY</th>
                        <th>TOTAL LOAN DEDUCTION</th>
                        <th>OUT OF ACCOUNT DEDUCTION</th>
                        <th>NET<br>SALARY</th>
                        
					</tr>
					<?php 
					if(count($tch)){
					$count = 1;
					$total = 0;
					foreach($tch as $key){
						  //$consolidated_pay=$key['emp_cosolidated_pay'];
						 ?>
                  	
					<tr style="text-align: right;">
						<td style="text-align: center;"><?php echo $count; ?></td>
						<td style="text-align: left; width:20px;">
						<?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?>
                        <br>
                        <span style="color:red; font-weight:bold;">
                        <?php
						echo salaryType($key['salary_type']);
						if($key['salary_type']==3){
							echo '('.$key['type_effect'].'%)';
						}
						if($key['salary_type']==4){
							echo '('.$key['type_effect'].' Day)';
						}
						?>
                        </span>
                        </td>
						<td><?php echo round($key['consolidated_pay']); ?></td>
						<td><?php echo $key['pay_payband'] ?></td>
						<td><?php echo $key['ropa_level'] ?></td>
						<td><?php echo $key['da'] ?></td>
						<td><?php echo $key['hra'] ?></td>
						<td><?php echo $key['ma'] ?></td>
						<td><?php echo $key['conv_allow'] ?></td>
						<td><?php echo $key['hill_allowance'] ?></td>
                        <td><?php echo $key['allowance'] ?></td>
						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['gross_salary'] ?></td>
						<td><?php echo $key['gpf'] ?></td>
						<td><?php echo $key['pf_loan'] ?></td>
						<td><?php echo $key['p_tax'] ?></td>
                        <td><?php echo $key['i_tax'] ?></td>
                        <td><?php echo $key['gsli'] ?></td>
						<td><?php echo $key['hra_deduction'] ?></td>
                        <td><?php echo $key['overdrawn'] ?></td>
                        <td><?php echo $key['festival_loan'] ?></td> 
                        <td><?php echo $key['total_loan_deduction'] ?></td>
                        <td><?php echo $key['other_loan_deduction'] ?></td>
						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['net'] ?>/-</td>
					</tr>
					<?php
						$total += $key['net']; 
					 	$count += 1;
					  }?>
					<tr style="text-align: right;">
						<th  colspan="22" style="text-align: right; padding:7px;">Total Amount</th>
						<th><?php echo $total; ?>/-</th>
					</tr>
                    <? } else{ ?>
                    <tr>
                    <td colspan="21" style="color:red;font-weight:bold">No Data Found</td>
                    </tr>
                    <? } ?>
                </table>
                </div>
                </div>
                
			</div>
        <div>
        
        </div>
      
    </div>
  </div>
  </div>
  </div>
  </div>
  <div class="clear"></div>
<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>


<style>
	
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		 
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 2px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 0px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 20px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 20px 10px;
}

</style>

