<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
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
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$cryp = new cryptography();
$emp_id_pk=$cryp->decode($_GET['id'],4); 

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('371371371'.$time_token);

$db = new database();
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$db=new database();

$current_monthyear = date("Ym");



?>
<style>
    .form-horizontal .control-label {
	text-align:left;
	}
</style>

<?php

$cryptoGraph=new cryptography();

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
	


?>
 
<div class="col-sm-12">
    <div class="emplist">
        <div class="school">
        <center><h1 class="heading">BONUS FOR THE YEAR OF <?php echo (date("Y")-1).'-'.date("Y"); ?></h1></center>
        <div class="border"></div>
            <div id="sess_msg">
            </div>

       <form class="form-horizontal bonus_design"  style="padding-top:10px;" id="loginForm" method="post" action="ll_bonus_module_insert_details_submit.php" onsubmit="return valid_code();">
            
              <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>"/>
                <div class="form-group">
                    <div class="col-sm-3" >
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
                    </div>

                    <div class="col-sm-3" >
                    <label for="inputPassword3" class="control-label">Bonus Year <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                    <select class="form-control" name="bonusyear" id="bonusyear" >
                    <option value="">-Select Year-</option>
                    <?php 
                    for($i = date('Y') ; $i < date('Y') + 1; $i++){
                    echo "<option>$i</option>";
                    }
                    ?>
                    </select>
                    </div>  
                </div>
                
                <div class="form-group">
                    <div class="col-sm-3" >
                    <label for="inputPassword3" class="control-label">Bonus Category <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                        <select class="form-control" name="bonus_category" id="bonus_category" onChange="show_bonus_name();">
							<?php
								$db = new database();
								$arr = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='16' and length(code)='3' ORDER BY description");
                            ?>
                            <option value="">-Please Select-</option>
                            <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
                            <option value="<?php echo $key['code']; ?>" <?php if($key['code']!='161' && $key['code']!='167'){echo "disabled style='color:#DF5255'"; }?>><?php echo $key['description']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label">Bonus Amount <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" >
                        <input class="form-control"  type="text" id="bonus_amount" name="bonus_amount" placeholder="Bonus Amount" autocomplete="off" onkeypress="return keyRestrict(event,'0123456789');" maxlength="5"/>
                    </div>
                </div>
                <div class="form-group" id="bonus_name_div" style="display:none;">
                    <div class="col-sm-3" >
                        <label for="inputPassword3" class="control-label">Bonus Name <span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-3" id="bonus_name_selection_div">
                     	
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12" align="center">
                    	<button type="submit" class="btn btn-info" >SAVE & CONTINUE </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
        
<div class="clear"></div>
<!--<p style="border-top:1px dashed #27769F; text-align:center;"></p>-->
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  
<script>
	
	function show_bonus_name()
	{
		$('#bonus_name_div').show();
		var bonus_cat=$('#bonus_category').val();
		if(bonus_cat!="")
		{
			$("#bonus_name_div").show();
			$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/ll_ajax_bonus_name_fetch.php?id='+bonus_cat, function(data){
				$("#bonus_name_selection_div").html(data);
			});
		}
		else
		{
			$("#bonus_name_div").hide();
		}
		
		
	}
	function valid_code()
	{
		if($('#bonusmonth').val()=='')
		{
			alert('Please Select Bonus Month.');
			$('#bonusmonth').focus();
			return false;
		}	
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

<?php  @pg_close($con); ?>
<style>
	.bonus_design
	{
		border:1px solid #000000;
		padding:9px;
		margin-top:5px;
	}
	.delete_one
	{
		float:right;
	}
</style>