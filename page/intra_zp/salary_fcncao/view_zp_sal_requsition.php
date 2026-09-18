<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
if(isset($_GET['dise'])){
	$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

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
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//-----------------------------QUERY----------------------------------------------------------------------------------
$employee_type=$crypto->decode($_GET['emp_type'],4); 
$requisition_type=$crypto->decode($_GET['requisition_type'],4);
$db = new database();
/*
$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/


function salaryType($sal_type){
			$db = new database();
			$arr = $db->fetch_table("select salary_type from prd_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}
$tch= $db->fetch_table("SELECT sal.ps_id_fk,tch.emp_status, sal.empcd, 
       sal.bankname, sal.accountno, sal.basic, sal.da, sal.hra, sal.ma, sal.cpf, sal.pf_loan, sal.p_tax, 
       sal.i_tax, sal.net, sal.bank_ifsc, sal.sal_source, sal.spl_pay, sal.pf_deduct, sal.code, 
       sal.emp_salary_id_pk, sal.spl_alo, sal.status_flag, sal.salary_monthyear,sal.consolidated_pay, 
       sal.category_id, sal.block_code, sal.emp_id_fk, sal.pay_payband, sal.tch_grade_pay, 
       sal.hill_allowance, sal.gpf, sal.cpf_deduct, sal.gross_salary, sal.is_saved, sal.conv_allow, 
       sal.overdrawn, sal.salary_type, sal.cause, sal.part_day, sal.total_loan_deduction,sal.gsli,sal.consolidated_pay,sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.hbl_loan,festival_loan,sal.interim_relief,sal.hra_deduction,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_pay_in_payband,
										tch.emp_id_pk
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as tch
										ON sal.emp_id_fk =tch.emp_id_pk AND sal.zp_id_fk= tch.zp_id_fk
										WHERE
											tch.emp_status in('1')	 
											AND sal.zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND sal.zp_emp_type='".$employee_type."'
											AND sal.status_flag in('3','4') AND is_saved='1' AND sal.delete_status='1' AND salary_monthyear='".date('Ym')."'  AND requisition_type='".$requisition_type."' order by tch.emp_first_name ;
								");
								
//echo "<pre>";
//print_r($tch);
//echo "</pre>";
//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->

<div class="content">
<!-- Common Back Button --->
	<?php require '../../common_back_btns.php'; ?>	

    <!--		<script>
		  $(document).ready(function() {
		  	
		    $( "#datepicker" ).datepicker({
		    	changeMonth: true,
            	changeYear: true,
		    	
		    });
		  });
		  </script>
		  <p>TEST JQ UI: <input type="text" id="datepicker"></p>-->
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
		
		function loan_show(id){
			
			//alert(id);
			var id = id;
			//alert(link);
			$('#myModal').modal('show');
			//var link = $(this).attr('id');
			//alert(link);
			//var link1= $("#gp_id").val();
			$.post('<?= $config['base_url'] ?>page/intra_zp/salary_fcncao/ajax_emp_loan_details_vew.php?id='+id, function(data){
				//alert(data);
				$("#loanmbody").html(data);
			});
		}
		 
			
		
		
		 
		
		// end of for loan details
    </script>
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
    <div class="col-sm-12">
        <h1 class="heading">View Salary Requisition</h1>
        <h2 class="heading">Salary Month Year : <?php echo date('M').','.date('Y') ?></h2>
			<div class="border"></div>
			<br>
				<?php if(count($tch)){
					if($tch[0]['status_flag'] == 3){ ?>
						<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Locked by FC&CAO </strong></div>
						
					<?php }}?>
					
                 <div class="emplist">
				<div class="school">	
                <div class="table-responsive">
                
				<table width="100%">
					<tr>
                    <th></th>
                    <th></th>
                    <th colspan="7">PAY & ALLOWANCE</th>
                    <th colspan="1"></th>
                    <th colspan="8">DEDUCTION</th>
                   <th></th>
                    </tr>
                    <tr>
                    	<th>SL No.</th>
                    	<th>EMPLOYEE NAME</th>
<!--                        <th>CONSOLIDATED<br>PAY</th>-->
						<th>PAY IN<br>PAY BAND</th>
						<th>GRADE<br />PAY</th>
						<th>DA</th>
						<th>HRA</th>
						<th>MA</th>
                        <th>CONV<br>ALLOW</th>
                        <!--<th>HILL<br>ALLOW</th>
						<th>CPF</th>-->
                        <th>HILL ALLOWANCE</th>
                         <!--<th>INTERIM RELIEF</th>-->
						<th>GROSS<br>SALARY</th>
						<th>GPF</th>
						<!--<th>PF<br />Loan<br />RECOVERY</th>-->
                        <!--<th>CPF<br />DEDUCT</th>-->
						<th>PTax</th>
						<th>ITax</th>
                        <th>GSLI</th>
			   			<th>HRA DEDUCTION</th>
                        <th>OVER<br />DRAWN</th>
                    <!--     <th>Co-operative Loan Recovery</th>
                         <th>HBL Recovery</th>-->
                         <th>FESTIVAL ADVANCE RECOVERY</th>
                          <th>TOTAL LOAN DEDUCTION</th>
<!--                         <th>Less Drawal</th>-->
                        <th>NET<br>SALARY</th>
                        
					</tr>
					<?php 
					if(count($tch)){
					$count = 1;
					$total = 0;
					foreach($tch as $key){ ?>
					<tr style="text-align: right;">
						<td style="text-align: center;"><?php echo $count; ?></td>
						<td style="text-align: left;">
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
						<!--<td><?php echo $key['consolidated_pay'] ?></td>-->
						<td><?php echo $key['pay_payband'] ?></td>
						<td><?php echo $key['tch_grade_pay'] ?></td>
						<td><?php echo $key['da'] ?></td>
						<td><?php echo $key['hra'] ?></td>
						<td><?php echo $key['ma'] ?></td>
						<td><?php echo $key['conv_allow'] ?></td>
						<td><?php echo $key['hill_allowance'] ?></td>
                       
						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['gross_salary'] ?></td>
						<td><?php echo $key['gpf'] ?></td>
						
						<td><?php echo $key['p_tax'] ?></td>
                        <td><?php echo $key['i_tax'] ?></td>
                        <td><?php echo $key['gsli'] ?></td>
						<td><?php echo $key['hra_deduction'] ?></td>
                        <td><?php echo $key['overdrawn'] ?></td>
                        
                        
                      
                        
                        
                         
                           <td><?php echo $key['festival_loan'] ?></td> 
                           <td class="loan">
                           
                            <a  onClick="loan_show(this.id);" id="<?php echo $crypto->encode($key['emp_id_pk'],4) ?> "><?php echo $key['total_loan_deduction'] ?></a>
						  
                           </td> 

						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['net'] ?>/-</td>
					</tr>
					<?php
						$total += $key['net']; 
					 	$count += 1;
					  }?>
					<tr style="text-align: right;">
						<th  colspan="18" style="text-align: right; padding:7px;">Total Amount</th>
						<th><?php echo $total; ?>/-</th>
					</tr>
                    <? } else{ ?>
                    <tr>
                    <td colspan="19" style="color:red;font-weight:bold">No Data Found</td>
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
require '../../../page/layout/footer.php';
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
<!--For total loan deduction-->
<div class="modal fade bs-example-modal-lg-loan" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 850px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="loanmbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>

<!--End of For total loan deduction-->

