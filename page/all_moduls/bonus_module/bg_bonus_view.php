<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
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
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
$cryptoGraph=new cryptography();


//Page variables
$common['title'] = "P&RD | Govt. of West Bengal ";

//Self variable




//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

//$crypto = new cryptography();

?>
<?php
if(isset($_GET['lock'])){
if ($cryptoGraph->decode($_GET['lock'],4) == 'locked'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Unlocked Successfully.</strong></div>';
}else if($cryptoGraph->decode($_GET['lock'],4) == 'failed'){
	$msg='<div class="alert alert-danger" style="text-align:center">Employee Bonus Details Has Not Been Unlocked. Please Try Again...</strong></div>';
}
}

$db=new database();

$current_monthyear = date("Ym");
$current_year = date("Y");
$prev_yrr=$current_year-1;
$next_year=$current_year+1;

if($logged_user=='zpacc' || $logged_user=='zpddo')
{
 $stake_id='zp_id_fk='.$_SESSION['location']['district_id'];
}
else if($logged_user=='EO')
{
 $stake_id='ps_id_fk='.$_SESSION['location']['ps_id'];
}
else if($logged_user=='BDO')
{
 $stake_id='gp_id_fk='.$cryptoGraph->decode($_GET['id'],4);

}

$arr=$db->fetch_table("SELECT 
							emp.emp_first_name, 
							emp.emp_second_name, 
							emp.emp_last_name, 
							emp.emp_desig, 
							emp.emp_id_const, 
							emp.emp_id_pk, 
							emp_bon.bonus_amount, 
							emp_bon.bonus_status,
							bon_type.bonus_category,
							emp_bon.bonus_name
							FROM prd_employee_master emp 
							INNER JOIN prd_employee_bonus_details emp_bon on emp_bon.emp_id_fk=emp.emp_id_pk AND emp_bon.zp_id_fk=emp.zp_id_fk
							INNER JOIN prd_bonus_type_details bon_type on bon_type.bonus_type_id_pk=emp_bon.bonus_type_id_fk 
							WHERE emp.$stake_id  AND emp_bon.bonus_status in (4) AND emp_bon.delete_status=1 
							AND emp_bon.monthyear='".$prev_yrr.$current_year."'
							ORDER BY emp_id_pk DESC");
//comneted by debjit error code
//$bonus_type_id=$arr[0]['bonus_type_id_pk'];
$bonus_status=$arr[0]['bonus_status'];

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	");

function fun_common($tcode, $code)
{
	foreach ($code as $key) 
	{
		if($key['code'] == $tcode)
		{
				return $key['description'];
		}
	}
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

<div class="content">
	<? require '../../../page/common_back_btns.php'; ?>
    
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
			echo $_SESSION['location']['gp_name'].", ";
        }elseif(isset($_SESSION['location']['block_name'])) {
			echo $_SESSION['location']['block_name'];
        }elseif(isset($_SESSION['location']['ps_name'])) {
			echo $_SESSION['location']['ps_name'].", ";
        }elseif(isset($_SESSION['location']['district_name'])) {
			echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
			echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        <?php if($_SESSION['user_info']['stake_abbr']=='GP'){ ?>
        <?php echo $_SESSION['location']['block_name'].", " .$_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }else{
        
        echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        }
        ?></h3>
    </div>
    
    <center>
        <h1 class="heading">BONUS DETAILS</h1>
        <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
        </div>
    </center>
    <?php
    if(isset($_SESSION['msg']))
    {
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
    } 
    if(!empty($_GET['msg']))
	{
		echo $cryptoGraph->decode($_GET['msg'],4);
		echo "<br/>";
		echo "<br/>";
    }
    //echo $msg;	
    ?>
    <div class="msg"></div>
    
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="emplist" style="width:98%;">
                        <div class="school">
                        	
                            	<div class="entry_bonus" align="right">
									<a class="btn btn-danger" data-toggle="modal" onClick="bonus_unlock('unlock');">UNLOCK</a>
                                </div>
							
                            <table class="table-responsive" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Serial No.</th>
                                        <th>Employee Name</th>
                                        <th>Employee ID</th>
                                        <th>Designation</th>
                                        <th>Bonus Category</th>
                                        <th>Bonus Name</th>
                                        <th>Bonus Amount</th>
                                      	<!--<th>Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $cnt=1; 
								if(count($arr) > 0)
								{
									foreach($arr as $item)
									{?>
									<tr>
                                        <td><?php echo $cnt;?></td>
                                        <td><?php echo $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                        <td><?php echo $item['emp_id_const']; ?></td>
                                        <td><?php if($logged_user=='zpddo'){ echo fun_desig($item['emp_desig'],$desig_data);}else{ echo fun_common($item['emp_desig'],$code_data);} ?></td>
                                        <td><?php echo fun_common($item['bonus_category'],$code_data); ?></td>
                                        <td><?php echo fun_common($item['bonus_name'],$code_data); ?></td>
                                        <td><?php echo $item['bonus_amount']; ?></td>
                                       
                                        
									</tr>
									<? $cnt+=1; 
									}
								} 
								else 
								{ ?>
                                    <tr>
                                    <td colspan="9" style="color:red;font-weight:bold">No Data Found</td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>


<? require '../../../page/layout/footer.php'; ?>


<!-----------------------------------------------------------------MODAL Start---------------------------------->
          

<script>


	$(document).ready(function()
	{
		$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
		$( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		$("#wait").css("display","none");
		$("#dialog-confirm").css("display", "none");
		$("#saving").css("display", "none");	
		
	});


	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
	
	
	
	function bonus_unlock(k,m)
	{
		$('#lock').modal('show');
	}
	
</script>


    
<style>
.odd{
	background-color:#FFFFFF;
	}
.even{
	background-color:#DDF7FF;
	}
</style> 

<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>



<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<form name="unlock" id="unlock" action="bonus_gp_ps_zp_unlock.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="lock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">BONUS UNLOCK</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Unlock Bonus Details?</strong></p>
                    <input type="hidden" id="flag" name="flag"/>
                    <input type="hidden" id="stake" name="stake"/>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="unlock_submit"  id="unlock_submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>



