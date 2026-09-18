<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';


$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
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

$cryptoGraph=new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);


$db=new database();
$bonus_lock_status = "";
if($logged_user=='GP')
{
	$bonus_lock_status=$db->fetch_table(" SELECT bonus_type.bonus_type_id_pk
										FROM  prd_employee_bonus_details bonus_emp
										INNER JOIN  prd_bonus_type_details bonus_type
										ON bonus_type.bonus_type_id_pk=bonus_emp.bonus_type_id_fk AND bonus_type.gp_id_fk=bonus_emp.gp_id_fk 
										WHERE bonus_type.active_status='1' AND bonus_emp.bonus_status='4' AND bonus_emp.delete_status='1'
										AND bonus_type.gp_id_fk='".$_SESSION['location']['gp_id']."' AND bonus_type.employee_total_number=0
										GROUP BY bonus_emp.bonus_status,bonus_type.bonus_type_id_pk ");
}
else if($logged_user=='DA')
{
	$bonus_lock_status=$db->fetch_table(" SELECT bonus_type.bonus_type_id_pk
										FROM  prd_employee_bonus_details bonus_emp
										INNER JOIN  prd_bonus_type_details bonus_type
										ON bonus_type.bonus_type_id_pk=bonus_emp.bonus_type_id_fk AND bonus_type.zp_id_fk=bonus_emp.zp_id_fk 
										WHERE bonus_type.active_status='1' AND bonus_emp.bonus_status='4' AND bonus_emp.delete_status='1'
										AND bonus_type.ps_id_fk='".$_SESSION['location']['ps_id']."' AND bonus_type.employee_total_number=0
										GROUP BY bonus_emp.bonus_status,bonus_type.bonus_type_id_pk ");
}
else if($logged_user=='zpdaa')
{
	$bonus_lock_status=$db->fetch_table(" SELECT bonus_type.bonus_type_id_pk
										FROM  prd_employee_bonus_details bonus_emp
										INNER JOIN  prd_bonus_type_details bonus_type
										ON bonus_type.bonus_type_id_pk=bonus_emp.bonus_type_id_fk AND bonus_type.zp_id_fk=bonus_emp.zp_id_fk 
										WHERE bonus_type.active_status='1' AND bonus_emp.bonus_status='4' AND bonus_emp.delete_status='1'
										AND bonus_type.zp_id_fk='".$_SESSION['location']['district_id']."' AND bonus_type.employee_total_number=0
										GROUP BY bonus_emp.bonus_status,bonus_type.bonus_type_id_pk ");
}

if(count($bonus_lock_status)>0)
{
	$bonus_locked="TRUE";
}

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

$crypto=new cryptography();
$db = new database();


?>
<!--CONTENT START-->
<div class="content">
<!-- Common Back Button --->
	<?php require '../../common_back_btns.php'; ?>	
    <div class="welcome_msg">
    <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
    <?php
    if(isset($_SESSION['location']['ps_name'])) {
    echo $_SESSION['location']['ps_name'].", ";
    }elseif(isset($_SESSION['location']['block_name'])) {
    echo $_SESSION['location']['block_name'].", ";
    } elseif(isset($_SESSION['location']['district_name'])) {
    echo $_SESSION['location']['district_name'];
    } elseif(isset($_SESSION['location']['state_name'])) {
    echo $_SESSION['location']['state_name'];
    } ?></h2><h3>
    <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
    ?></h3>
    </div>
    
    <div class="row" id="cont">
        <div class="content">
			<script>
            $(document).ready(function(){
				$( "tr:odd" ).css( "background-color", "#dfeaec" );
				$( "tr:even" ).css( "background-color", "#fff6" ); 
            });
            </script>
           <div class="col-sm-12">
    <div class="emplist">
        <div class="school">
        <center><h1 class="heading">BONUS FOR THE YEAR OF <?php echo (date("Y")-1).'-'.date("Y"); ?></h1></center>
        <div class="border"></div>
            <div id="sess_msg">
            <?php  
         if(isset($_SESSION['msg']))
        {
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
        }
        ?>
            </div>
            
            <form class="form-horizontal bonus_design" id="loginForm" method="post" action="ll_bonus_module_insert_details_submit.php" onsubmit="return valid_code();" style="
    padding-top: 22px;
">
        
                <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>"/>
                <div class="form-group">
                    <!--<div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label">Bonus Month <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                        <select class="form-control"  name="bonusmonth" id="bonusmonth" >
                            <option value="">-Select Month-</option>
                            <?php
                            //Pervious months will be disabled and Desired month will be selected.
                            $months = array(January, February, March, April, May, June, July, August, September, October, November, December);
                            $newmonth = date("m") - 01;
                            for($i = $newmonth; $i < 12; $i++)
                            {
                                $j = $i+1;
                                if($j<10)
                                {
                                    $j = '0'.$j;
                                }
                                ?>
                                <option value="<?php echo $j; ?>" <?php if($monthyear == $j) { echo "selected"; }?>><?php echo $months[$i] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>-->
			 <div class="form-group ">
                   
                    <label  class="col-sm-4 col-md-offset-1 control-label" for="inputPassword3">Bonus Year <span class="star_color">*</span></label>
                    
                    <div class="col-sm-4" >
                    <select class="form-control" name="bonusyear" id="bonusyear" >
                    <option value="">-Select Year-</option>
                    <?php /*?><?php 
                    for($i = date('Y') ; $i < date('Y') + 1; $i++){
                    echo "<option>$i</option>";
                    }
                    ?><?php */?>
                     <?php  
					$current_year=date('Y');
					//$next_year=date('Y')+1;
					$start_year1=2025;
					$start_year2=2026;
					
					$i=$current_year - $start_year1;
					$i=$i+1;
					
					for($j=1;$j<=$i;$j++)
					{
						$start_ym1=$start_year1.'03';
						$start_ym2=$start_year2.'03';
						if(date('Ym') >= $start_ym1 && date('Ym') <= $start_ym2)
						{
					?>	
						<option value="<?php echo $start_year1.$start_year2?>"><?php echo $start_year1.'-'.$start_year2; ?></option>
                    <?php
						}
						else
						{
							if($start_year1!=2017)
							{
								$start_year1=$start_year1-1;
								$start_year2=$start_year2-1;
							}
					?>	
						<option value="<?php echo $start_year1.$start_year2?>"><?php echo $start_year1.'-'.$start_year2; ?></option>
                    <?php
						}
						$start_year1=$start_year1+1;
						$start_year2=$start_year2+1;
					}
					?>
                    
                    </select>
                    </div>  
                    </div>
                    <div class="form-group">
                   <!-- <div class="col-sm-3" >-->
                    <label for="inputPassword3" class=" col-sm-4 col-md-offset-1 control-label">Bonus Category <span class="star_color">*</span></label>
                    
                    <div class="col-sm-4" >
                        <select class="form-control" name="bonus_category" id="bonus_category" onChange="show_bonus_name();">
							<?php
								$db = new database();
								$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in ('167','666') ORDER BY description");
                            ?>
                            <option value="">-Please Select-</option>
                            <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
                            <option value="<?php echo $key['code']; ?>"><?php echo $key['description']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="b">
                        <label class="col-sm-4 col-md-offset-1 control-label" for="inputPassword3" id="bonus_name_div" style="display:none;">Bonus Name <span class="star_color">*</span></label>
                        <div class="col-sm-4" >
                        	<div id="bonus_name_selection_div">
                            
                        	</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 col-md-offset-1 control-label">Bonus Amount <span class="star_color">*</span></label>
                    
                   <div class="col-sm-4" >
                        <input class=" form-control"  type="text" id="bonus_amount" name="bonus_amount" placeholder="Bonus Amount" autocomplete="off" onkeypress="return keyRestrict(event,'0123456789');" maxlength="5"/>
                    </div>
                </div>
                <!--<div class="form-group" id="bonus_name_div" style="display:none;">
                    
                </div>-->
                <div class="form-group">
                    <div class="col-sm-12" align="center">
                    	<button type="submit" class="btn btn-info" >SAVE & CONTINUE </button>
                    </div>
                </div>
            </form>
        </div>
       </div>
       </div>
     </div>
<div class="clear"></div>



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>

<script>

var k ='<?php echo $bonus_locked; ?>';

/*$('.bt').click(function() {
			menu_id=this.id;
			if(menu_id=='type2' && k=='TRUE')
			{
				alert("Current Bonus has been Locked. New Bonus Entry will be possible after current bonus bill generation");
				return false;
			}
		 
		})*/
function show_bonus_name()
	{
		$('#bonus_name_div').show();
		var bonus_cat=$('#bonus_category').val();
		if(bonus_cat!="")
		{
			$("#bonus_name_div").show();
			$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ll_ajax_bonus_name_fetch.php?id='+bonus_cat, function(data){
				$("#bonus_name_selection_div").html(data);
				$("#bonus_name_selection_div").show();
			});
		}
		else
		{
			$("#bonus_name_div").hide();
			$("#bonus_name_selection_div").hide();
		}
		var bonusyear=$("#bonusyear").val();
//alert(bonusyear);
	$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ajax_bouns_fetch.php?bonusyear='+bonusyear+'&id='+bonus_cat, function(data){
	var result = $.parseJSON(data);
	var bonus_cat=result[0];
	var bonus_amount=result[1];
	$("#bonus_category").val(bonus_cat);
	$("#bonus_amount").val(bonus_amount);
	});
 }
	
		function valid_code()
	{
		/*if($('#bonusmonth').val()=='')
		{
			alert('Please Select Bonus Month.');
			$('#bonusmonth').focus();
			return false;
		}	*/
		if($('#bonusyear').val()=='')
		{
			alert('Please Select Bonus Year.');
			$('#bonusyear').focus();
			return false;
		}
		if($('#bonus_category').val()=='')
		{
			alert('Please Select Category of Bonus.');
			$('#bonus_category').focus();
			return false;
		}
		if($('#bonus_amount').val()=='')
		{
			alert('Please Enter Bonus Amount.');
			$('#bonus_amount').focus();
			return false;
		}
		if($('#bonus_amount').val()==0)
		{
			alert('Please Enter Valid Bonus Amount.');
			$('#bonus_amount').focus();
			return false;
		}
		if($('#bonus_name').val()==0)
		{
			alert('Please Enter Valid Bonus Name.');
			$('#bonus_name').focus();
			return false;
		}
	}
	
</script>


<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		border:3px solid #fff;
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #5B7778;
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



<style>
  .shape{    
    border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
	-ms-transform:rotate(360deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(360deg); /* Safari and Chrome */
	transform:rotate(360deg);
}
.offer{
	/*background:rgba(228, 232, 223, 0.59);*/
	background:rgba(243, 246, 240, 0.71); border:1px solid #ddd; box-shadow: 0 10px 20px rgba(148, 112, 29, 0.64); margin: 15px 0; overflow:hidden; margin-right:28px; padding-bottom:22px;padding-top:10px;
}

.shape {
	border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
}
.offer-radius{
	border-radius:7px;
}
.offer-danger {	border-color: #d9534f; }
.offer-danger .shape{
	border-color: transparent #d9534f transparent transparent;
}
.offer-success {	/*border-color: #9e9fb1;*/ }
.offer-success .shape{
	border-color: transparent #5cb85c transparent transparent;
}
.offer-default {	border-color: #999999; }
.offer-default .shape{
	border-color: transparent #999999 transparent transparent;
}
.offer-primary {	border-color: #428bca; }
.offer-primary .shape{
	border-color: transparent #428bca transparent transparent;
}
.offer-info {	border-color: #5bc0de; }
.offer-info .shape{
	border-color: transparent #5bc0de transparent transparent;
}
.offer-warning {	border-color: #f0ad4e; }
.offer-warning .shape{
	border-color: transparent #f0ad4e transparent transparent;
}

.shape-text{
	color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
	-ms-transform:rotate(30deg); /* IE 9 */
	-o-transform: rotate(360deg);  /* Opera 10.5 */
	-webkit-transform:rotate(30deg); /* Safari and Chrome */
	transform:rotate(30deg);
}	
.offer-content{
		padding:16px 100px 20px;
}
@media (min-width: 487px) {
  .container {
    max-width: 750px;
  }
  .col-sm-6 {
    width: 50%;
  }
}
@media (min-width: 900px) {
  .container {
    max-width: 970px;
  }

}

@media (min-width: 1200px) {
  .container {
    max-width: 1170px;
  }
  .col-lg-3 {
    width: 25%;
  }
  }


</style>



