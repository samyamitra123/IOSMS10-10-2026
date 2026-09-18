<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../page_visite.php';
//require 'includes/library/session.class.php';

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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//-----------------------------QUERY----------------------------------------------------------------------------------
$db = new database();
function salaryType($sal_type){
			$db = new database();
			$arr = $db->fetch_table("select salary_type from ehrms_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}
$tch= $db->fetch_table("
									SELECT 
										sal.*,
										sal_fix.*,
										tch.tchname,
										tch.basic_pay
									FROM
										ehrms_teacher_salary_save as sal
									
									INNER JOIN 
										ehrms_dise_teacher as tch
										ON sal.tchcd = tch.tchcd AND sal.schcd=tch.schcd
									INNER JOIN 
										ehrms_monthly_salary_table_fix as sal_fix
										ON sal_fix.tchcd=sal.tchcd AND sal_fix.schcd=sal.schcd
										WHERE
												sal.schcd = '".$_SESSION['user_info']['stake_user']."'
											AND tch.schcd = '".$_SESSION['user_info']['stake_user']."'
											AND sal_fix.salary_monthyear= '".date('Ym')."'
											AND tch.basic_pay != ''
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 OR sal.status_flag = 4)
										ORDER BY tch.rank ASC;
		
								");
//echo "<pre>";
//print_r($tch);
//echo "</pre>";
//------------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->

<div class="content">
<!-- <script type="text/javascript" src="themes/default/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<div class="mainContent float_l" style="width:1000px">
<!-- Common Back Button --->
	<?php require '../../../common_back_btns.php'; ?>	
  <div class="dashboard-main"> 
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
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
                      <h3><?php
					  if(isset($_SESSION['location']['school_name'])){
                          echo $_SESSION['location']['school_name'];
                      } elseif(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    }
                      ?></h3>
       </div>
    <style>

    	.emplist{
			background-color:#FFFFFF;
    		padding: 10px;
    		border-radius: 10px;
    		-moz-border-radius: 10px;
    		margin-top: 10px;
    		font-family: "Franklin Gothic Book";

    	}
    	.emplist table {
			border-collapse:collapse;
		}
		.emplist table, th, td {
			border: 1px solid #93D1E3;

		}
		th, td {
			padding: 5px;	
		}
		th{
			background: #339BB9;
			color: #fff;
			font-size: 14px;
			padding: 2px;
		}
		.page_title{
			text-align: center;
			text-transform: uppercase;
			color: #006666;
			
		}
		table{
			border-radius: 5px;
			-moz-border-radius: 5px;
			overflow: hidden;
		}
		.statusf{
			text-align: center;
			color: #72A545;
			font-weight: bold;
		}
		.statusn{
			text-align: center;
			color: #FF8A11;
			font-weight: bold;
		}
		#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
    </style>
    <script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );		  

		  
		});
    </script>
    <div class="dashcontenr"> 
        <div class="page_title">
        <h1>View Salary Requisition</h1>
        <h2>Salary Month Year : <?php echo date('M').','.date('Y') ?></h2>
        </div>
			<div class="border"></div>
			<br>
				<?php if(count($tch)){
					if($tch[0]['status_flag'] == 1){ ?>
						<div id="error">Requisition Not finalized</div>
						
					<?php }
					elseif($tch[0]['status_flag'] == 2){ ?>
						<div class="statusf">
                        <div id="sucess">Requisition finalized by HOI</div>
                        </div>
						
					<?php } elseif($tch[0]['status_flag'] == 3){ ?>
						<div id="sucess">Requisition finalized by HOI</div>
					<?php } elseif($tch[0]['status_flag'] == 4){ ?>
						<div id="sucess">Requisition finalized by HOI</div>
					<?php }?>
				<div class="emplist">	
				<table width="100%">
					<tr>
                    	<th>SL No.</th>
                    	<th>TEACHER NAME</th>
						<th>PAY IN<br>BAND</th>
						<th>GRADE<br />PAY</th>
						<th>DA</th>
						<th>HRA</th>
						<th>MA</th>
                        <th>CONV<br>ALLOW</th>
                        <th>HILL<br>ALLOW</th>
						<th>CPF</th>
						<th>GROSS</th>
						<th>GPF</th>
						<th>PF<br />Loan</th>
                        <th>CPF<br />DEDUCT</th>
						<th>P<br />Tax</th>
						<th>I<br />Tax</th>
                        <th>I<br /> Other Deduction</th>
                       
                        <th>NET<br>SALARY</th>
					</tr>
					<?php $count = 1;
					$total = 0;
					foreach($tch as $key){ ?>
					<tr style="text-align: right;">
						<th style="text-align: center;"><?php echo $count; ?></th>
						<td style="text-align: left;">
						<?php echo $key['tchname'] ?>
                        <br>
                        <span style="color:#FB1607; font-size:11px;">
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
						<td><?php echo $key['pay_payband'] ?></td>
						<td><?php echo $key['tch_grade_pay'] ?></td>
						<td><?php echo $key['da'] ?></td>
						<td><?php echo $key['hra'] ?></td>
						<td><?php echo $key['ma'] ?></td>
						<td><?php echo $key['conv_allow'] ?></td>
						<td><?php echo $key['hill_allowance'] ?></td>
						<td><?php echo $key['cpf']; ?></td>
						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['gross_salary'] ?></td>
						<td><?php echo $key['gpf'] ?></td>
						<td><?php echo $key['pf_loan'] ?></td>
						<td><?php echo $key['cpf_deduct'] ?></td>
						<td><?php echo $key['p_tax'] ?></td>
                        <td><?php echo $key['i_tax'] ?></td>
						<td style="background-color: #AEC4DE; color: #fff;"><?php echo $key['net'] ?>/-</td>
					</tr>
					<?php
						$total += $key['net']; 
					 	$count += 1;
					  }?>
					<tr style="text-align: right;">
						<th  colspan="16" style="text-align: right; padding:7px;">Total Amount</th>
						<th><?php echo $total; ?>/-</th>
					</tr>
				</table>
				<?php } else{ ?>
					No data found
				<?php } ?>
			
			</div>
        <div>
        
        </div>
      
    </div>
  </div>
</div>
<br clear="all">
<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
