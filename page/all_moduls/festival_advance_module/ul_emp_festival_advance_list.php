<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';

//require '../../../page_visite.php';

$crypto = new cryptography();

$lock_var=$crypto->encode("LOCK",4);
$unlock_var=$crypto->encode("UNLOCK",4);

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
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic---------------------------------------------------------------------------------
//-----------------------------QUERY----------------------------------------------------------------------------------

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];



if($logged_user=='EO')
{
	$Query = " SELECT
	emp.emp_id_pk,
	emp.emp_first_name,
	emp.emp_second_name,
	emp.emp_last_name,
	fad_emp.emp_id_const,
	fad_emp.festival_advance_total_amount,
	fad_emp.festival_advance_status,
	CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
	fad.festival_advance_instalment_no,
	fad.festival_advance_instalment_amount,
	fad.festival_advance_instalment_last_amount
	FROM prd_employee_master emp
	INNER JOIN prd_festival_advance_employee_details fad_emp
	ON emp.emp_id_pk=fad_emp.emp_id_fk
	INNER JOIN prd_festival_advance_entry_sal fad
	ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
	WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND substr(fad_emp.fad_monthyear,1,4)='".date('Y')."' 
	AND fad_emp.festival_advance_status in('3','4') AND fad.status='1'
	";
	//print($Query); exit;
	$fad_details=$db->fetch_table($Query);

}
else if($logged_user=='BDO')
{
	$fad_details=$db->fetch_table(" SELECT
	emp.emp_id_pk,
	emp.emp_first_name,
	emp.emp_second_name,
	emp.emp_last_name,
	fad_emp.emp_id_const,
	fad_emp.festival_advance_total_amount,
	fad_emp.festival_advance_status,
	CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
	fad.festival_advance_instalment_no,
	fad.festival_advance_instalment_amount,
	fad.festival_advance_instalment_last_amount
	FROM prd_employee_master emp
	INNER JOIN prd_festival_advance_employee_details fad_emp
	ON emp.emp_id_pk=fad_emp.emp_id_fk
	INNER JOIN prd_festival_advance_entry_sal fad
	ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
	WHERE emp.gp_id_fk='".$crypto->decode($_GET['id'],4)."' AND substr(fad_emp.fad_monthyear,1,4)='".date('Y')."' AND fad_emp.festival_advance_status in('3','4') AND fad.status='1'
	");

}
else if($logged_user=='zpacc')
{
	$fad_details=$db->fetch_table(" SELECT
	emp.emp_id_pk,
	emp.emp_first_name,
	emp.emp_second_name,
	emp.emp_last_name,
	fad_emp.emp_id_const,
	fad_emp.festival_advance_total_amount,
	fad_emp.festival_advance_status,
	CASE WHEN (fad_emp.festival_advance_status='2') THEN 1 ELSE 0 END as saved,
	fad.festival_advance_instalment_no,
	fad.festival_advance_instalment_amount,
	fad.festival_advance_instalment_last_amount
	FROM prd_employee_master emp
	INNER JOIN prd_festival_advance_employee_details fad_emp
	ON emp.emp_id_pk=fad_emp.emp_id_fk
	INNER JOIN prd_festival_advance_entry_sal fad
	ON fad_emp.festival_advance_id_pk=fad.festival_advance_id_fk
	WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND substr(fad_emp.fad_monthyear,1,4)='".date('Y')."' AND fad_emp.festival_advance_status in('3','4') AND fad.status='1'
	");

}
$all_emp_id_str = "";
for($j=0;$j<count($fad_details);$j++)
{
	$all_emp_id_str.=$fad_details[$j]['emp_id_pk'];
		
	if($j!=count($fad_details)-1)
	{
		$all_emp_id_str=$all_emp_id_str.",";
	}
}

$enc_all_emp_id_str=$crypto->encode($all_emp_id_str,4);

?>

<!--CONTENT START-->

<div class="content">
    <!-- Common Back Button --->
    <?php require '../../common_back_btns.php'; ?>	
    
    
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
                <div class="col-sm-12" style="width:98%">
                    <h1 class="heading">View Festival Advance of <?php echo date('Y');?></h1>
                    <div class="border"></div>
                    <br>
                    <?
                    if(isset($_SESSION['msg']))
                    {
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
                    }
                    ?>  
                    <div class="emplist">
                        <div class="school">
                            <div class="table-responsive">
								<?php if($fad_details[0]['festival_advance_status']=='4' && $logged_user!='BDO' )
                                { ?>
                                    <div class="bill_fad" align="right" style="padding-bottom:5px;">
									<?php if($logged_user!='zpacc'){?>
										<a class="btn btn-danger" data-toggle="modal" onClick="fad_unlock('unlock');">UNLOCK</a>
										<!--<a class="btn btn-success" href="bg_fad_text_file.php?all_emp_id=<?php //echo $enc_all_emp_id_str; ?>" style="font-weight:400; font-size:14px;">Bill Generation </a>-->
										<a class="btn btn-success" href="fad_text_file.php?all_emp_id=<?php echo $enc_all_emp_id_str; ?>" style="font-weight:400; font-size:14px;"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp; Festival Advance Bill Generate </a>
									<?php } ?>
									</div>
                                <?php } ?>
                                <table class="table-responsive" style="width:100%;">
                                    <thead>
                                        <tr>
                                        <th style="width: 5%;">SL NO.</th>
                                        <th>Employee Name</th>
                                        <th>Employee Id</th>
                                        <th style="width: 12%;">Festival Advance Amount</th>
                                        <th style="width: 10%;">Total Instalment Number</th>
                                        <th>Instalment Amount</th>
                                        <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($fad_details))
                                    { 
										$count = 1;$total_saved=0; 
										foreach ($fad_details as $key) 
										{?> 
                                            <tr>
                                                <td id="show"><?php echo $count; ?></td>
                                                <td id="emp_name"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']?></td>
                                                <td><?php echo $key['emp_id_const']; ?></td>
                                                <td><?php echo $key['festival_advance_total_amount']; ?></td>
                                                <td><?php echo $key['festival_advance_instalment_no']; ?></td>
                                                <td><?php echo $key['festival_advance_instalment_amount'];if($key['festival_advance_instalment_amount']!=$key['festival_advance_instalment_last_amount']){echo ' (Last Month Payable : '.$key['festival_advance_instalment_last_amount'].')';} ?></td>
                                                <td style="color:red"><?php if($key['festival_advance_status']=='3'){ echo 'Not Locked';}else if($key['festival_advance_status']=='4'){ echo 'Locked';} ?></td>
                                            </tr> 
                                            <?php
                                            $total_saved=$total_saved+$key['saved'];  
											$count++;
										}
                                    } 
                                    else 
                                    {?> 
                                    <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> 
                                    <?php 
                                    }?>
                                    </tbody>
                                </table>
                            </div>
                            <br />
                            
                            <?php
                            if($fad_details[0]['festival_advance_status']=='3' && $logged_user!='BDO')
                            {
                            ?>
                                <a class="btn btn-success"style="margin-left:43%" data-bs-toggle="modal"  onClick="fad_action('lock');">LOCK</a>
                                <a class="btn btn-danger" data-bs-toggle="modal" onClick="fad_action('unlock');">UNLOCK</a>
                            <?php 
                            } ?>
                        </div>
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
.button
{
padding-left:785px;
}
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
<script>

	function fad_action(k)
	{
		var lock_var='<?php echo $lock_var;?>';
		var unlock_var='<?php echo $unlock_var;?>';
		if(k=='lock')
		{
			$('#flag').val(lock_var);
			$('#lock_show').show();
			$('#unlock_show').hide();
		}
		else if(k=='unlock')
		{
			$('#flag').val(unlock_var);
			$('#lock_show').hide();
			$('#unlock_show').show();
		}
		$('#lock').modal('show');
	}

</script>

<form name="unlock_salary" id="unlock_salary" action="ul_lock_unlock_fad.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="lock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To <span id="lock_show" style="display:none;"> Lock</span><span id="unlock_show" style="display:none;">Unlock</span><strong>  Festival Advance Details ?</strong></p>
                    <input type="hidden" id="flag" name="flag"/>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>

	function fad_unlock(k)
	{
		$('#lock_up').modal('show');
	}

</script>

<form name="unlock_salary" id="unlock_salary" action="fad_gp_ps_zp_unlock.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="lock_up" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To <strong> Unlock Festival Advance Details ?</strong></p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


