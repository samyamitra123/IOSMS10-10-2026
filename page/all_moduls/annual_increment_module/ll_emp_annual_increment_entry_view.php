<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';


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



if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Establishment)')
{
	$logged_user='zpdae';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		
$cryptoGraph=new cryptography();		
			
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

$db=new database();

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

function fun_grade_pay($val){
		$db = new database();
		$grade_amount = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
		return $grade_amount[0]['grade_amount'];
	}

$desig_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	");


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

function round_up_ten($input,$basic)
{
	
	
	//$input='1308';
	
	//$basic='43600';
	 $number2 = $input+$basic;
	 $round_of = substr($number2,-2);
	 
	if($round_of>=51)
	{
	$number3 =( $input+$basic);
	$number = ceil($number3 / 100) * 100;
	}
	else 
	{
	
	$number3 =( $input+$basic);
	$round_of_ten = substr($number3,-2);
	//return $number = ceil($number3 / 100) * 100;
	$number = $number3-$round_of_ten;
	}
		
return $number;
}






/*function round_up_ten($input,$basic)
{
	
	
	if($round_of>=51)
	{
	$number3 =( $input+$basic);
	$number = ceil($number3 / 100) * 100;
	}
	else 
	{
	
	$number3 =( $input+$basic);
	$round_of_ten = substr($number3,-2);
	//return $number = ceil($number3 / 100) * 100;
	$number = $number3-$round_of_ten;
	}
	
	
	
	//return $input; //////////// 1308
	
	$input='1308';
	 $a=$input%100; 
	if($a==0)
	{ 
	return 1;
		
		$whole=floor($input);
		$decimal=$input-$whole;
		
		if($decimal<1)
		{
			return 2;
			$number=$whole;
		}
		else
		{
			return 3;
			
			$number1 = ($input / 100) * 100;
			$number = $number1+$basic;
		}
	}
	else
	{
		
		
		$number1 = ($input / 100) * 100;
		$number2 = $number1+$basic;
		//$number1 = $input ;
		
		$round_of = substr($number2,-2);
		
		//$increment = ($number1 / 100) ;
		
		if($round_of>51)
		{
			$number3 =ceil( $number1+$basic);
			$number = ($number3 / 100) * 100;
		}
		else 
		{
			
			$number3 =ceil( $number1+$basic);
			$number = ceil($number3 / 100) * 100;
		}
		
		//$whole=floor($increment);
		//$decimal=$increment-$whole;
		//$increment = ceil($number / 100) * 100;
		//return $number;
	}
	return $number;

}*/

/*function round_up_ten($input)
{
	$a=$input%10;/////////// prv year round off
	if($a==0)
	{
		$whole=floor($input);
		$decimal=$input-$whole;
		
		if($decimal<1)
		{
			$number=$whole;
		}
		else
		{
			$number = ceil($input / 10) * 10;
		}
	}
	else
	{
		$number = ceil($input / 10) * 10;
	}
	return $number;

}*/

function date_frmt($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL){
		return "01-01-0001";
	}
	else{
		$old=explode("-",$original_date);
        $new=$old[2]."-".$old[1]."-".$old[0];
		return $new;
	}
}

function get_financial_year($date_time)
{
	$date1=explode('-',$date_time);
	$month=$date1[1];
	$year=$date1[0]; 
	if($month=='01' || $month=='02' || $month=='03')
	{
		$p_year=($year-1);
		$c_year=$year;
	}
	else 
	{
		$p_year=$year;
		$c_year=$year+1;
	}
	return $p_year.$c_year;
}

//echo round_up_ten(480.90); die;

$financial_year=get_financial_year(date('Y-m-d'));

//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------




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
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
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
	padding: 5px 10px;
}

</style>



<?php

if($logged_user=='GP')
{
	$sent_to_name='BDO';
}
else if($logged_user=='DA')
{
	$sent_to_name='EO';
}
else if($logged_user=='zpdaa')
{
	$sent_to_name='Accountant';
}
else if($logged_user=='zpdae')
{
	$sent_to_name='Secreatary';
}

if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success')
	{
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}
	else if($_GET['confirm'] == 'false')
	{
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}

if(isset($_GET['lock'])){
	if($cryptoGraph->decode($_GET['lock'],4) == 'sent')
	{
		$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Promotion Details Has Been Sent To '.$sent_to_name.' Successfully...</strong></div>';
	}
	else if($cryptoGraph->decode($_GET['lock'],4) == 'failed')
	{
		$msg='<div class="alert alert-danger" style="text-align:center">Employee Promotion Details Has Not Been Sent To '.$sent_to_name.'. Please Try Again... </strong></div>';
	}
}	
?>
<script>
	$(document).ready(function(){
		$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
	
	});
		

</script>
<script type="text/javascript">
$(document).ready(function() 
{
	//alert(11);
	$("#example").DataTable({
		
		
		"aLengthMenu": [[2, 3, 5, -1], [2, 3,5, "All"]],
        "iDisplayLength": 5,
"bFilter": true,
	  	"bSort": false,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});

	
	
  });
  

</script>
 
    
<?php
$db=new database();

if($logged_user == 'GP')
{
	
	$arr=$db->fetch_table("SELECT
								distinct(emp.emp_id_pk), 
								emp.emp_first_name, 
								emp.emp_second_name, 
								emp.emp_last_name, 
								emp.emp_desig, 
								emp.emp_status,
								emp.gp_id_fk,
								emp.ropa_status,
								emp.emp_first_join_date, 
								emp.emp_id_const,
								emp.ropa_level,
								emp.zp_emp_type,
								emp.emp_cosolidated_pay,
								emp.emp_pay_in_payband,
								emp.emp_grade_pay,
								emp.ropa_level,
								emp.increment_count,
								anu.status,
								anu.zp_forward_status,
								anu.annual_increment_amount,
								prom.delete_status,
								prom.approval_status,
								prom.increment_type,
								prom.effective_date 
							FROM 
								prd_employee_master emp
							LEFT JOIN
								 (SELECT distinct(emp_id_fk),status,annual_increment_amount,zp_forward_status FROM prd_employee_annual_increment_details WHERE gp_id_fk='".$_SESSION['location']['gp_id']."' AND status!='0' AND financial_year='".$financial_year."') as anu
							ON
								emp.emp_id_pk=anu.emp_id_fk
							LEFT JOIN
								(SELECT distinct(emp_id_fk),delete_status,approval_status,increment_type,effective_date FROM prd_employee_promotion_details WHERE gp_id_fk='".$_SESSION['location']['gp_id']."' AND delete_status = '1' AND increment_type in('1','2') and promotion_effective_status='1') as prom
							ON
								emp.emp_id_pk=prom.emp_id_fk
								
							WHERE 
								emp.emp_status in(1) 
								AND emp.emp_cosolidated_pay='0' AND emp.ropa_status='1'
								AND emp.gp_id_fk='".$_SESSION['location']['gp_id']."'
								ORDER BY emp_id_pk DESC");

}
else if($logged_user == 'DA')
{

	$arr=$db->fetch_table("SELECT 
								distinct(emp.emp_id_pk),
								emp.emp_first_name, 
								emp.emp_second_name, 
								emp.emp_last_name, 
								emp.emp_desig, 
								emp.emp_status,
								emp.ps_id_fk,
								emp.emp_first_join_date, 
								emp.emp_id_const,
								emp.ropa_status,
								emp.emp_cosolidated_pay,
								emp.emp_pay_in_payband,
								emp.emp_grade_pay,
								emp.zp_emp_type,
								emp.ropa_level,
								emp.increment_count,
								anu.status,
								anu.zp_forward_status,
								anu.annual_increment_amount,
								prom.delete_status,
								prom.approval_status,
								prom.increment_type,
								prom.effective_date 
							FROM 
								prd_employee_master emp
							LEFT JOIN
								 (SELECT distinct(emp_id_fk),status,annual_increment_amount,zp_forward_status FROM prd_employee_annual_increment_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND status!='0' AND financial_year='".$financial_year."') as anu
							ON
								emp.emp_id_pk=anu.emp_id_fk
							LEFT JOIN
								(SELECT distinct(emp_id_fk),delete_status,approval_status,increment_type,effective_date FROM prd_employee_promotion_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND delete_status = '1' AND increment_type in('1','2')and promotion_effective_status='1') as prom
							ON
								emp.emp_id_pk=prom.emp_id_fk
								
							WHERE 
								emp.emp_status in(1) 
								AND emp.emp_cosolidated_pay='0' AND emp.ropa_status='1'
								AND emp.ps_id_fk='".$_SESSION['location']['ps_id']."'  
								ORDER BY emp_id_pk DESC");

}
else if($logged_user == 'zpdaa' || $logged_user == 'zpdae')
{
	
	$arr=$db->fetch_table("SELECT 
								distinct(emp.emp_id_pk),
								emp.emp_first_name, 
								emp.emp_second_name, 
								emp.emp_last_name, 
								emp.emp_desig, 
								emp.emp_status,
								emp.zp_id_fk,
								emp.emp_first_join_date, 
								emp.emp_id_const,
								emp.emp_cosolidated_pay,
								emp.ropa_status,
								emp.emp_pay_in_payband,
								emp.emp_grade_pay,
								emp.ropa_level,
								emp.zp_emp_type,
								emp.increment_count,
								anu.status,
								anu.zp_forward_status,
								anu.annual_increment_amount,
								prom.delete_status,
								prom.approval_status,
								prom.increment_type,
								prom.effective_date 
							FROM 
								prd_employee_master emp
							LEFT JOIN
								 (SELECT distinct(emp_id_fk),status,annual_increment_amount,zp_forward_status FROM prd_employee_annual_increment_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND status!='0'  AND financial_year='".$financial_year."') as anu
							ON
								emp.emp_id_pk=anu.emp_id_fk
							LEFT JOIN
								(SELECT distinct(emp_id_fk),delete_status,approval_status,increment_type,effective_date FROM prd_employee_promotion_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND delete_status = '1' AND increment_type in('1','2') and promotion_effective_status='1') as prom
							ON
								emp.emp_id_pk=prom.emp_id_fk
								
							WHERE 
								emp.emp_status in(1) 
								AND emp.emp_cosolidated_pay='0' AND emp.ropa_status='1'
								AND emp.zp_id_fk='".$_SESSION['location']['district_id']."' 
								AND emp.emp_desig!='1120' 
								ORDER BY emp_id_pk DESC");

}



?> 
    
<div class="content">
	<? require '../../../page/common_back_btns.php'; ?>
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'];
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'];
        } elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        <? 
		
		if(!empty($_SESSION['location']['block_name'])){
			echo $_SESSION['location']['block_name'].", ".$_SESSION['location']['district_name'];
		}
		else if(!empty($_SESSION['location']['state_name'])){
			echo $_SESSION['location']['state_name'].", ".$_SESSION['location']['district_name'];
		}
		 
        ?></h3>
    </div>
    
    <center> 
    	<br/>
    	<h1 class="heading">ANNUAL INCREMENT DETAILS </h1>
        <div class="border"></div>
		<?  
        if(isset($_SESSION['msg']))
        {
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
        }
        ?>
        <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
        </div>
        <br />
    </center>
    
    
    
    <?php
		if(isset($_SESSION['msg']))
		{
			echo $_SESSION['msg'];
			unset($_SESSION['school_msg']);
		} 
		if(!empty($_GET['msg']))
		{
			echo $cryptoGraph->decode($_GET['msg'],4);
			echo "<br/>";
			echo "<br/>";
		}
		if(!empty($msg)){
			echo $msg;
		}
    ?>
    
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="emplist">
                        <div class="school">
                            <div class="table-responsive">
                               <!-- <table class="table-responsive" style="width:100%;">-->
                                <table width="100%" id="example">
                                    <thead>
                                    <tr>
                                        <th>Serial No.</th>
                                        <th>Employee Name</th>
                                        <th>Employee ID</th>
                                        <th>Designation</th>
                                        <th>Effective Date</th>
                                        <th>Check Status</th>
                                        <th>Level</th>
                                        <th>Increment Basic</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                   </tr>
                                    </thead>
                                
									<?php $cnt=1; if(count($arr) )
                                    {
                                        foreach($arr as $item)
                                        {
											$status=$item['status'];
                                            //$chk_start_date = '01-01-'.date('Y');
											$chk_start_date = '02-01-'.date('Y');
                                            $chk_start_date_tmestamp = strtotime($chk_start_date);
                                            $chk_end_date = '30-06-'.date('Y');
                                            $chk_end_date_tmestamp = strtotime($chk_end_date);
                                            $promotion_effective_date = date_frmt($item['effective_date']); 
                                            $promotion_effective_date_timestamp = strtotime($promotion_effective_date);
											$current_date_timestamp=strtotime(date('Y-m-d'));
											$emp_joining_date=$item['emp_first_join_date'];
											$emp_joining_date_timestamp = strtotime($emp_joining_date);
											
                                            if(($promotion_effective_date_timestamp >= $chk_start_date_tmestamp) && ($promotion_effective_date_timestamp<=$chk_end_date_tmestamp))
                                            {
                                            	$promotion_condition=1;
                                            }
                                            else
                                            {
                                            	$promotion_condition=0;
                                            }
											
											$day_diff = $current_date_timestamp - $emp_joining_date_timestamp;
											$num_days=floor($day_diff/(60*60*24));
											
											if($num_days<180)
											{
												$joining_condition=1;
											}
											else
											{
												$joining_condition=0;
											}
											if($item['emp_cosolidated_pay']=='0' && $item['ropa_status']=='1' )
											{
												
									/*************************** Changed By ANJAN 20.05.2020 START *********************************************/
									
												/// new changes $grade_pay= fun_grade_pay($item['emp_grade_pay']);
												$pay_in_pay_band=$item['emp_pay_in_payband'];
												$ropa_level=$item['ropa_level'];
												
												
								if($item['zp_emp_type']=='' || $item['zp_emp_type']=='0' || $item['zp_emp_type']=='367')
								{
								
								$ropa_table='ropa_2019'; 
								
								}
								else 
								{
								
								$ropa_table='ropa_2019_ll';
								
								}

									$fetch_level = $db->fetch_table("SELECT level FROM ".$ropa_table." WHERE $ropa_level = '".$pay_in_pay_band."' ");
									
									
									$level_data = (int)$fetch_level[0]['level'];
									
									if($level_data=='0'|| $level_data=='' )
									{
										
									
									$prv_fetch_offset= $db->fetch_table(" SELECT $ropa_level FROM ".$ropa_table." where $ropa_level  is not null ORDER BY $ropa_level DESC ");
																	
									$last_cell_basic= $prv_fetch_offset[0][$ropa_level]; //////////// table last cell///////
									$last_prv_cell_basic=$prv_fetch_offset[1][$ropa_level]; //////////// table last 1 cell///////
									
									 $last_basic_difference=$last_cell_basic-$last_prv_cell_basic; 
									 $increment_amount = 0;///////////// table check////////
									}
									else
									{
								$fetch_offset= $db->fetch_table(" SELECT $ropa_level FROM ".$ropa_table." ORDER BY $ropa_level ASC OFFSET $level_data ROWS FETCH NEXT 1 ROWS only ");
								
								
								
								$prv_fetch_offset= $db->fetch_table(" SELECT $ropa_level FROM ".$ropa_table." where $ropa_level  is not null ORDER BY $ropa_level DESC ");
								
								
								$increment_amount = $fetch_offset[0][$ropa_level];///////////// table check////////
								
								$last_cell_basic= $prv_fetch_offset[0][$ropa_level]; //////////// table last cell///////
								$last_prv_cell_basic=$prv_fetch_offset[1][$ropa_level]; //////////// table last 1 cell///////
								
								$last_basic_difference=$last_cell_basic-$last_prv_cell_basic; 
									}
												
								/********************************************************** END *************************************************/
								
										if(($increment_amount== NULL ||$increment_amount=='0') && $pay_in_pay_band<='200999'  && $item['zp_emp_type']=='366' )
												{
													
													//echo $pay_in_pay_band;
													$basic=$item['emp_pay_in_payband'];
													 //$increment_amount_prev = ($basic/100)*3;  
													$increment_amount=$basic+$last_basic_difference;
													 $checking=$increment_amount;
													
													if($checking>'201000' )
													{
													
														$increment_amount=201000;
														$increment_condisation='1';
													}
													else if($item['increment_count']>='6')
													{
													
														$increment_amount=$basic;
														$increment_condisation='0';
													}
													else
													{
														
														//$increment_amount = round_up_ten($increment_amount_prev,$basic) ;
														$increment_amount=$increment_amount;
														$increment_condisation='1';
													}
													
													
													
												}
									else if (($increment_amount== NULL ||$increment_amount=='0') && $pay_in_pay_band>='201000'  && $item['zp_emp_type']=='366')
												{
													
													$basic=$item['emp_pay_in_payband'];
													$increment_amount=$basic;
													$increment_condisation='0';
												
												}
												else
												{
													
													 $increment_amount = $fetch_offset[0][$ropa_level]; 
													$increment_condisation='0';
												}
								
											}
											else  
											{
												//$basic=$item['emp_cosolidated_pay'];
												
												$basic=$item['emp_pay_in_payband'];
												$increment_amount=$basic;
												//$increment_amount_prev = ($basic/100)*3;
												//$increment_amount = round_up_ten($increment_amount_prev) ;
											}
											
											if($joining_condition=='0' && $promotion_condition=='0')
											{
												if($item['status']=='1' && $item['zp_forward_status']=='0')
												{
													$show_status='<span style="color:#FA8072;font-weight:bold">WAITING FOR ACCEPTANCE </span>';
												}
												else if($item['status']=='2' ) 
												{
													$show_status='<span style="color:#FA8072;font-weight:bold">ACCEPTED</span>';
												}
												else if($item['status']=='3' && $item['zp_forward_status']=='0') 
												{
													$show_status='<span style="color:#FA8072;font-weight:bold">REJECTED</span>';
												}
												else if( $item['status']=='1' && $item['zp_forward_status']=='1') 
												{
													$show_status='<span style="color:#FA8072;font-weight:bold">FORWARDED</span>';
												}
												else
												{
													$show_status='<span style="color:#FA8072;font-weight:bold">APPLICABLE</span>';
												}
											}
                                            else
                                            {
                                            	$show_status='<span style="color:#FA8072;font-weight:bold">N/A</span>';
                                            }
											?>
                                            
                                            <tr>
                                                <td><?= $cnt;?></td>
                                                <input type="hidden" id="emp<?=$cnt;?>" name="emp" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4);?>" />
                                                <input type="hidden" id="annu_status<?=$cnt;?>" name="annu_status" value="<?=$status;?>" />
                                                <input type="hidden" id="increment_con<?=$cnt;?>" name="increment_con" value="<?=$increment_condisation;?>" />
                                                
                                                <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                                <td><?= $item['emp_id_const']?></td>
                                                <td><?php if($logged_user=='zpdaa' || $logged_user=='zpdae'){ echo fun_desig($item['emp_desig'],$desig_data);}else{ echo fun_common($item['emp_desig'],$code_data);} ?></td>
                                             
                                                <td align="center"><span><?php echo '01-07-'.date('Y'); ?></span></td>
                                                
                                                <td>
                                                    <input type="checkbox" class="case" id="check<?php echo $cnt; ?>" name="check" value="1" <?php if($promotion_condition=='1' || $joining_condition=='1' || $item['status']=='1' || $item['status']=='2'){ echo 'disabled';} ?> onClick="send_active(this.id);"/>
                                                    <label for="check<?php echo $cnt; ?>"><span></span></label>
                                                </td>
                                                
                                                <td><?=strtoupper($item['ropa_level'])?></td>
                                                <td>
                                                	<div <?php if($item['status']=='1' || $item['status']=='2' || $item['status']=='3') { ?> style="display:none;" <?php } else { ?> style="display:block;" <?php } ?> id="increment_deactive<?=$cnt;?>" >
                                                    	<span style="color:red;">0</span>
                                                    </div>
                                                    <div <?php if($item['status']=='') { ?> style="display:none;" <?php } else { ?> style="display:block;" <?php } ?> id="increment_active<?=$cnt;?>" >
                                                    	<span style="color:#1D21CD;"><?php if($item['annual_increment_amount']!=""){echo $item['annual_increment_amount'];} else { echo $increment_amount; } ?></span>
                                                    	<input type="hidden" id="increment_amount<?=$cnt;?>" value="<?=$cryptoGraph->encode($increment_amount,4)?>" />
                                                    </div>
                                                </td>
                                                <td><?php echo $show_status; ?></td>
                                                <td >
													 <div style="display:none;" id="send_enable<?=$cnt;?>">
                                                    	<a onClick="send_increment(<?=$cnt;?>);"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit" /></a>
                                                    </div>
                                                    <div style="display:block;" id="send_disable<?=$cnt;?>">
                                                    	<img width="25" src="<?= $config['base_url'];?>themes/default/image/send_disable.png" alt="Edit" />
                                                    </div>
                                                </td> 
                                            </tr>
                                            
                                            <?php $cnt+=1; 
                                        }
                                    } 
                                    else 
                                    { ?>
                                    <tr>
                                    	<td colspan="10" style="color:red;font-weight:bold">No Data Found</td>
                                    </tr>
                                    <? } ?>
                                
                                </table>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>


<?php require '../../../page/layout/footer.php'; ?>






<script>
function send_increment(k)
{
	var emp_id=$('#emp'+k).val();
	$('#emp_id_annu').val(emp_id);
	var increment_amt=$('#increment_amount'+k).val();
	$('#inc_amt_annu').val(increment_amt);
	var increment_status=$('#annu_status'+k).val();
	$('#inc_annu_status').val(increment_status);
	var increment_condi=$('#increment_con'+k).val();
	$('#increment_condisation').val(increment_condi);
	
	$('#increment').modal('show');
}


function send_active(k)
{
	id_length=k.length;
	row_id=k.substr(5,id_length);
	if($('#check'+row_id).is(':checked')==true)
	{
		$('#send_enable'+row_id).show();
		$('#send_disable'+row_id).hide();
		$('#increment_active'+row_id).show();
		$('#increment_deactive'+row_id).hide();
	}
	else
	{
		$('#send_enable'+row_id).hide();
		$('#send_disable'+row_id).show();
		$('#increment_active'+row_id).hide();
		$('#increment_deactive'+row_id).show();
	}
		
	
}
</script>

<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>

<form name="increment_salary" id="increment_salary" action="ll_emp_annual_increment_submission.php" method="post">
<div class="modal fade bs-example-modal-sm" id="increment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">CONFIRMATION</h4>
      </div>
 <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Send Annual Increment request ?</strong></p>
    
       <input type="hidden" id="emp_id_annu" name="emp_id_annu" />
        <input type="hidden" id="inc_amt_annu" name="inc_amt_annu" />
        <input type="hidden" id="inc_annu_status" name="inc_annu_status" />
         <input type="hidden" id="increment_condisation" name="increment_condisation" />
        <input type="hidden" id="finan_yr" name="finan_yr" value="<?php echo $cryptoGraph->encode($financial_year,4); ?>" />
       
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
      
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 </form>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->
