<?
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
	
	function reject_gp_fad(k)
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
	
</script>
<?
$db=new database();
				
$arr=$db->fetch_table("
						SELECT 
							gp.gp_id_pk,
							gp.gp_name,
							gp.gp_code,
							count(distinct(fad.emp_id_fk)) as total_emp_sent,
							COUNT(CASE WHEN (fad.festival_advance_status='3') THEN fad.emp_id_fk END) as not_locked,
							COUNT(CASE WHEN (fad.festival_advance_status='4') THEN fad.emp_id_fk END) as locked
						FROM prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						INNER JOIN prd_employee_master as emp 
							ON emp.gp_id_fk=gp.gp_id_pk 
						INNER JOIN prd_festival_advance_employee_details as fad
							ON fad.emp_id_fk=emp.emp_id_pk
						WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%' AND substr(fad.fad_monthyear,1,4)='".date('Y')."' 
							AND fad.festival_advance_status in ('3','4')
						GROUP BY gp.gp_id_pk,gp.gp_name,gp.gp_code

				");
		
/*for($i=0;$i<count($arr);$i++)
{
	if($arr[$i]['locked']==0)
	{
		$lock_all_gp='FALSE';
		$all_gp_id.=$arr[$i]['gp_id_pk'];
			
		if($i!=count($arr)-1)
		{
			$all_gp_id=$all_gp_id.",";
		}
	}
	else if($arr[$i]['locked']>0)
	{
		$lock_all_gp='TRUE';
		break;
	}
}

$enc_all_gp_id=$cryptoGraph->encode($all_gp_id,4);*/


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

$all_gp_id = 0;

for($k=0;$k<count($arr);$k++)
{
	$all_gp_id.=$arr[$k]['gp_id_pk'];
		
	if($k!=count($arr)-1)
	{
		$all_gp_id=$all_gp_id.",";
	}
}
//var_dump($all_gp_id); die;
$enc_all_gp_id=$cryptoGraph->encode($all_gp_id,4);

$all_emp_id=$db->fetch_table(" SELECT emp_id_fk 
								FROM prd_festival_advance_employee_details fad 
								INNER JOIN prd_employee_master emp 
								ON fad.emp_id_fk=emp.emp_id_pk 
								WHERE emp.gp_id_fk in (".$all_gp_id.") AND substr(fad.fad_monthyear,1,4)='".date('Y')."' 
							AND fad.festival_advance_status in ('3','4')");

$all_emp_id_str = "";
for($j=0;$j<count($all_emp_id);$j++)
{
	$all_emp_id_str.=$all_emp_id[$j]['emp_id_fk'];
		
	if($j!=count($all_emp_id)-1)
	{
		$all_emp_id_str=$all_emp_id_str.",";
	}
}

$enc_all_emp_id_str=$cryptoGraph->encode($all_emp_id_str,4);

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
        <? echo $_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
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
                    	<?php
						if(count($arr)>0)
						{ 
							if($lock_all_gp=='FALSE')
							{ ?>
								<div class="entry_bonus" align="right">
									<a class="btn btn-success" onClick="lock_gp_bonus();" style="font-weight:400; font-size:14px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Festival Advance Lock </a>
								</div>
							<?php 
							}
							else
							{?>
								<div class="entry_bonus" align="right">
									<a class="btn btn-danger" data-bs-toggle="modal" onClick="fad_unlock('unlock');">UNLOCK</a>
									<!--<a class="btn btn-success" href="bg_fad_text_file.php?all_emp_id=<?php //echo $enc_all_emp_id_str; ?>" style="font-weight:400; font-size:14px;"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp; Festival Advance Bill Generate </a>-->
									<a class="btn btn-success" href="fad_text_file.php?all_emp_id=<?php echo $enc_all_emp_id_str; ?>" style="font-weight:400; font-size:14px;"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp; Festival Advance Bill Generate </a>
								</div>
							<?php }
						}?>
                        <div class="table-responsive">
                            <table width="100%">
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Gram Panchayat Name</th>
                                    <th>Gram Panchayat Code</th>
                                    <th>Total Employee Festival Advance Sent</th>
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
                                                    <a class="btn btn-sm btn-danger" id="rej_bon&<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>" onClick="reject_gp_fad(this.id)">Reject</a>
                                                    <a class="btn btn-sm btn-success" href="<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/ul_emp_festival_advance_list.php?id=<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>">View Festival Advance</a>
                                                <?php }
                                                else
                                                {
                                                	echo '<span  style="color:red">LOCKED</span>';
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<? require '../../../page/layout/footer.php'; ?>

<form action="ul_gp_individual_fad_lock_reject.php" method="post" name="gp_bonus_reject_form" id="gp_bonus_reject_form">
    <div class="modal fade bs-example-modal-sm" id="lock_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: max-content; margin-left: -78%;">
         <div class="modal-header">
		 <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
          </div>
          <div class="modal-body"> 
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Lock All GP's Festival Advance Details for Festival Advance Bill Generation?</strong></p>
          </div>
          <div class="modal-footer">
          <div class="btn-group">
              <input type="hidden" name="all_gp_id" id="all_gp_id" value="<?php echo $enc_all_gp_id;?>"  />
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
          <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This GP's Festival Advance Details?</strong></p>
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

