<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}


$cryptoGraph=new cryptography();
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
if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
	$(document).ready(function(){
	  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
	  $( "tr:even" ).css( "background-color", "#DDF7FF" );
	});
	
	function reject_gp_bonus(k)
	{
		var arr=k.split('&');
		var enc_gp_id=arr[1];
		$('#rej_gp_id').val(enc_gp_id);
		$('#reject_modal').modal('show');
	}
	function lock_gp_bonus()
	{
		$('#lock_modal').modal('show');
	}
	
	function bonus_unlock(k,m)
	{
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
<?php
$db=new database();

$current_year=date("Y");
$next_year=$current_year+1;
$prev_yrr=$current_year-1;
$fin_yr_start=$current_year.'04';
$fin_yr_end=$next_year.'03';
$lock_all_gp= "";


$arr=$db->fetch_table("
						SELECT 
							gp.gp_id_pk,
							gp.gp_name,
							gp.gp_code,
							count(distinct(emp.emp_id_pk)) as total_emp_sent,
							COUNT(CASE WHEN (emp_bon.bonus_status='3') THEN emp_bon.emp_id_fk END) as not_locked,
							COUNT(CASE WHEN (emp_bon.bonus_status='4') THEN emp_bon.emp_id_fk END) as locked
						FROM prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						INNER JOIN prd_employee_master as emp 
							ON emp.gp_id_fk=gp.gp_id_pk 
						INNER JOIN prd_employee_bonus_details as emp_bon 
							ON emp.emp_id_pk=emp_bon.emp_id_fk
						WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%'
							AND emp_bon.bonus_status in ('3','4') AND emp_bon.delete_status='1' AND emp_bon.bill_id_fk=0 
							AND emp_bon.monthyear='".$prev_yrr.$current_year."'
						GROUP BY gp.gp_id_pk,gp.gp_name,gp.gp_code 

				");
				
for($i=0;$i<count($arr);$i++)
{
	if($arr[$i]['locked']==0)
	{
		$lock_all_gp='FALSE';
	}
	else if($arr[$i]['locked']>0)
	{
		$lock_all_gp='TRUE';
		break;
	}
}

?>
<style>
	h1 {
	display: block;
	font-size: 2em;
	-webkit-margin-before: 0.67em;
	-webkit-margin-after: 0.67em;
	-webkit-margin-start: 0px;
	-webkit-margin-end: 0px;
	font-weight: bold;
	}
</style>
<div class="content">
	<?php require '../../../page/common_back_btns.php'; ?>
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
        <?php echo $_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
        ?></h3>
    </div>
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <h1 class="heading">GRAM PANCHAYAT LIST</h1>
                <div class="border"></div>
                </br>
                </br>
                <?php 
                if(isset($_SESSION['msg']))
				{
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				} 
                ?>
                <div class="emplist">
                    <div class="school">
                    	<?php if($lock_all_gp=='FALSE')
						{ ?>
                            <div class="entry_bonus" align="right">
                            	<a class="btn btn-success" onClick="lock_gp_bonus();" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Bonus Lock </a>
                            </div>
						<?php 
						}
						else if($lock_all_gp=='TRUE')
						{  ?>
                        	<div class="entry_bonus" align="right">
                            
                            
                            <a class="btn btn-danger" onClick="bon_unlock('unlock');"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Unlock </a>
                            
                            
								<!--<a class="btn btn-danger" data-bs-toggle="modal" onClick="bon_unlock('unlock');">UNLOCK</a>-->
                                 
                            	<a class="btn btn-success" href="bg_bonus_text_file_all.php" style="font-weight:400; font-size:14px;"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Bonus Bill Generate </a>
                            </div>
						<?php }?>
                        <div class="table-responsive">
                            <table width="100%">
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Gram Panchayat Name</th>
                                    <th>Gram Panchayat Code</th>
                                    <th>Total Employee Bonus Sent</th>
                                    <th>Action</th>
                                </tr>
                                <? $cnt=1; 
                                if(count($arr))
								{ 
									foreach($arr as $item)
									{ 
									?>
                                        <tr>
                                            <td><?= $cnt;?></td>
                                            <td><?= $item['gp_name']?></td>
                                            <td><?= $item['gp_code']?></td>
                                            <td><?= $item['total_emp_sent']?></td>
                                            <td>
												<?php if($item['not_locked']==$item['total_emp_sent'])
                                                { ?>
                                                    <!--<a class="btn btn-sm btn-danger" id="rej_bon&<?= $cryptoGraph->encode($item['gp_id_pk'],4)?>" onClick="reject_gp_bonus(this.id)">Reject</a>-->
                                                    <a class="btn btn-sm btn-success" href="<?= $config['base_url'] ?>page/all_moduls/bonus_module/ul_emp_bonus_module_entry_view.php?id=<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>">View Bonus</a>
                                                    
                                                     <!--<a class=" btn btn-sm btn-danger " data-bs-toggle="modal" onClick="bonus_unlock('</td>//?= $cryptoGraph->encode($item['gp_id_pk'],4);?>','<//?=$cryptoGraph->encode("BDO",4);?>');"><i class="fa fa-unlock" aria-hidden="true">&nbsp;</i>Unlock</a>-->
                                                     
                                                     <a class="btn btn-danger" onClick="ulock('<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>','<?=$cryptoGraph->encode("BDO",4);?>');" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Unlock </a>
                                                <?php }
                                                else
                                                {
                                                	echo '<span style="color:#3D8274;">LOCKED</span>';
                                                } ?>
                                            </td>
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

<form action="ul_gp_individual_bonus_lock_reject.php" method="post" name="gp_bonus_reject_form" id="gp_bonus_reject_form">
    <div class="modal fade bs-example-modal-sm" id="lock_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Lock All GP's Bonus Details for Bonus Bill Generation?</strong></p>
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
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This GP's Bonus Details?</strong></p>
          </div>
          <div class="modal-footer">
          <div class="btn-group">
            <input type="hidden" name="rej_gp_id" id="rej_gp_id" />
            <button type="submit" name="reject_submit" id="reject_submit" class="btn btn-success">YES</button>
            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
          </div>
          </div>
        </div>
      </div>
    </div>
</form>
<form name="unlock" id="unlock" action="ul_employee_individual_bonus_lock_reject.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="lock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
                 <h4 class="modal-title" id="myModalLabel">BONUS UNLOCK</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Unlock This GP's Bonus Details?</strong></p>
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



<script>

	function bon_unlock(k)
	{
		$('#lock_up_bonus').modal('show');
		
	}

</script>

<!--<form name="unlock_salary" id="unlock_salary" action="bonus_gp_ps_zp_unlock.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="lock_up_bonus" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Unlock This GP's Bonus Details?</p>
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
</form>-->


<form name="unlock" id="unlock" action="ul_employee_individual_bonus_lock_reject.php" method="post">
     <div class="modal fade bs-example-modal-sm" id="ulock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
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
    
 <form name="unlock_salary" id="unlock_salary" action="bonus_gp_ps_zp_unlock.php" method="post">
     <div class="modal fade bs-example-modal-sm" id="lock_up_bonus" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel">BONUS UNLOCK</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i>Do You Want To Unlock This GP's Bonus Details?</strong></p>
         
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

