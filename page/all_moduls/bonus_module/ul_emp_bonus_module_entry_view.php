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
function fun_bank($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
	}

$arr=$db->fetch_table("SELECT 
							emp.emp_first_name, 
							emp.emp_second_name, 
							emp.emp_last_name, 
							emp.emp_desig, 
							emp.emp_bank_name,
							emp.emp_acc_no,
							emp.emp_ifsc_no,
							emp.emp_id_const, 
							emp.emp_id_pk, 
							emp_bon.bonus_amount, 
							emp_bon.bonus_status,
							bon_type.bonus_category,
							emp_bon.bonus_name
							FROM prd_employee_master emp 
							INNER JOIN prd_employee_bonus_details emp_bon on emp_bon.emp_id_fk=emp.emp_id_pk AND emp_bon.ps_id_fk=emp.ps_id_fk
							INNER JOIN prd_bonus_type_details bon_type on bon_type.bonus_type_id_pk=emp_bon.bonus_type_id_fk 
							WHERE emp.$stake_id  AND emp_bon.bonus_status in (3,4) AND emp_bon.delete_status=1 
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
                        	<?php 
				//echo $logged_user.'--'.$logged_user;
				if($bonus_status!="" && $bonus_status!=4 && $logged_user!='BDO')
							{?> 
                                <div class="entry_bonus" align="right">
                                    <a class="btn btn-success" onClick="lock_bon();" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Bonus Lock </a>
                                    <?php if($logged_user=='EO'){?>
                                    <!--<a class="btn btn-danger " data-bs-toggle="modal" onClick="bonus_unlock('</div>/?= $cryptoGraph->encode($_SESSION['location']['ps_id'],4);?>','<//?=$cryptoGraph->encode("EO",4);?>');"><i class="fa fa-unlock" aria-hidden="true">&nbsp;</i>Unlock</a>-->
                                    
                                    <a class="btn btn-danger" onClick="ulock('<?= $cryptoGraph->encode($_SESSION['location']['ps_id'],4);?>','<?=$cryptoGraph->encode("EO",4);?>');" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Unlock </a>
                                    <?php }?>
                                   <?php if($logged_user=='zpacc'){?>
                                    <!--<a class="btn btn-danger " data-bs-toggle="modal" onClick="bonus_unlock('</div>/?= $cryptoGraph->encode($_SESSION['location']['district_id'],4);?>','<//?=$cryptoGraph->encode("ZP",4);?>');"><i class="fa fa-unlock" aria-hidden="true">&nbsp;</i>Unlock</a>-->
                                    
                                    <a class="btn btn-danger" onClick="ulock('<?= $cryptoGraph->encode($_SESSION['location']['district_id'],4);?>','<?=$cryptoGraph->encode("ZP",4);?>');" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Unlock </a>
                                    
                                    
                               <?php }?>
                                </div>
                           	<?php }    
							else if($bonus_status!="" && $bonus_status==4 && $logged_user!='BDO')                                       
							{  if($logged_user !='zpacc'){?>
                            	<div class="entry_bonus" align="right">
									<a class="btn btn-danger" data-bs-toggle="modal" onClick="bonus_unlock('unlock');">UNLOCK</a>
                                    <a class="btn btn-success" href="bg_bonus_text_file_all.php" style="font-weight:400; font-size:14px;"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Bonus Bill Generate </a>
                                </div>
							<?php } } ?>
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
                                        <th>Bank Name</td>
                                        <th>Account No</td>
                                        <th>IFSC Code</td>
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
                                        <td><?php if($logged_user=='zpacc'){ echo fun_desig($item['emp_desig'],$desig_data);}else{ echo fun_common($item['emp_desig'],$code_data);} ?></td>
                                        <td><?php echo fun_common($item['bonus_category'],$code_data); ?></td>
                                        <td><?php echo fun_common($item['bonus_name'],$code_data); ?></td>
                                        <td><?php echo $item['bonus_amount']; ?></td>
                                        
                                         <td><?php echo fun_bank($item['emp_bank_name']); ?></td>
                                                        <td><?php echo $item['emp_acc_no']; ?></td>
                                                        <td><?php echo $item['emp_ifsc_no']; ?></td>
                                       
                                       <!-- <td>
										<?php if($item['bonus_status']==3)
                                        { ?>
                                             <a class="btn btn-sm btn-danger" id="reject&<?php echo $cryptoGraph->encode($item['emp_id_pk'],4).'&'.$item['emp_id_pk'];?>" onClick="reject_bon(this.id);"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp; Reject</a>
										<?php }
                                        else
                                        {
                                        	echo '<span style="color:#3D8274;">LOCKED</span>';
										} ?>
                                        </td> -->     
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
                     <?php
                echo $status='<span style="color:RED;font-weight:bold">*** You may check and verify Employee Bank Details.</span>'; 
				?>
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
		
		/*$(".finalize").click(function(){
			var link1=$("#emp_id").val();
			$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/u_ajax_bonus_module_finalize_reject.php?id='+link1+'&flag=<?=$cryptoGraph->encode("finalize",4) ?>', function(data){
				$('#myModal').modal('toggle');
				$('#finalize').modal('toggle');
				$('.msg').html(data);
			});
		});
		
		$(".reject").click(function(){
			var link2=$("#emp_id").val();
			$.post('<?= $config['base_url'] ?>page/all_moduls/bonus_module/u_ajax_bonus_module_finalize_reject.php?id='+link2+'&flag=<?=$cryptoGraph->encode("reject",4) ?>', function(data){
				$('#myModal').modal('toggle');
				$('#reject').modal('toggle');
				$('.msg').html(data);
			});
		
		});*/
		
	});


	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
	
	function lock_bon()
	{
		$('#lock_modal').modal('show');
	}
	
	function reject_bon(k)
	{
		var arr=k.split('&');
		var enc_emp_id=arr[1];
		$('#reject_modal').modal('show');
		$('#rej_emp_id').val(enc_emp_id);
	}
	function bonus_unlock(k,m)
	{
		//alert(m);
		var id=k;
		var stake=m;
		$('#flag').val(id);
		$('#stake').val(m);
		$('#lock').modal('show');
	}
	function ulock(k,m)
	{
		//alert(k);
		var id=k;
		var stake=m;
		$('#flag_new').val(id);
		$('#stake_new').val(m);
		//$('#lock').modal('show');
		$('#ulock').modal('show');
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

<form action="ul_employee_individual_bonus_lock_reject.php" method="post" >
    <input type="hidden" name="enc_gp_id" id="enc_gp_id" value="<?php echo $_GET['id']; ?>" />
    <div class="modal fade bs-example-modal-sm" id="lock_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Lock All Employee's Bonus Details for Bonus Bill Generation?</strong></p>
          </div>
          <div class="modal-footer">
          <div class="btn-group">
            <button type="submit" name="lock_submit" id="lock_submit" class="btn btn-success">YES</button>
            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
          </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="modal fade bs-example-modal-sm" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This Employee Bonus Details?</strong></p>
          </div>
          <div class="modal-footer">
          <div class="btn-group">
          	<input type="hidden" name="rej_emp_id" id="rej_emp_id" />
            <button type="submit" name="reject_submit" id="reject_submit" class="btn btn-success">YES</button>
            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
          </div>
          </div>
        </div>
      </div>
    </div>
</form>

<form name="unlock" id="unlock" action=" <?php if($bonus_status==3){?>ul_employee_individual_bonus_lock_reject.php <?php }elseif($bonus_status==4){ ?>bonus_gp_ps_zp_unlock.php <?php } ?>" method="post">
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




<form name="unlock" id="unlock" action=" <?php if($bonus_status==3){?>ul_employee_individual_bonus_lock_reject.php <?php }elseif($bonus_status==4){ ?>bonus_gp_ps_zp_unlock.php <?php } ?>" method="post">
    
    <div class="modal fade bs-example-modal-sm" id="ulock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">BONUS UNLOCK</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i>Do You Want To Unlock Bonus Details?</strong></p>
          <input type="hidden" id="flag_new" name="flag_new"/>
           <input type="hidden" id="stake_new" name="stake_new"/>
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



