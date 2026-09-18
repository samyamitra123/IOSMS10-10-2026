<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
header("Location:../../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
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
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submitted Successfully...</strong></div>';
	}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submission Fails...</strong></div>';
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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
$(document).ready(function(){
$( "tr:odd" ).css( "background-color", "#CCE6FF" );
$( "tr:even" ).css( "background-color", "#DDF7FF" );
//$( ".table:last" ).css( "border-radius", "0px 0px 5px 5px" );

$("#dialog").dialog({
			autoOpen : false,
			modal: true,
			width:900,
			opacity: 1,
			height:580,
			
			show : {
			effect : "fade",
			duration : 500
			},
			hide : {
			effect : "fade",
			duration : 500
			},
});

});

</script>

<?
$db=new database();
$arr = array();
if (isset($_GET['emp_id']))
{
	$id=$_GET['emp_id'];
}
else
{
	$id='';
}

if($id=='' || $id!='0'){
	
	

	if($id!='')
	{
		$emp_id_pk=$cryptoGraph->decode($_GET['emp_id'],4);
		$arr=$db->fetch_table("select emp_first_name,emp_status,emp_second_name,emp_last_name,emp_form_status,emp_id_pk,emp_desig,emp_status from prd_employee_master where emp_status in('4','5','10','7') AND emp_id_pk='".$emp_id_pk."' AND zp_id_fk='".$_SESSION['location']['district_id']."' order by emp_first_name");
	}
	else
	{	
	
		$db=new database();
		
		$arr=$db->fetch_table("select emp_first_name,emp_status,emp_second_name,emp_last_name,emp_id_pk,emp_form_status,emp_id_pk,emp_desig,emp_status from prd_employee_master where emp_form_status in('1','2','3','4','5') AND emp_status in('10','5','7') AND zp_id_fk='".$_SESSION['location']['district_id']."' order by emp_first_name");
	}
}

?>

<div class="content">
	<? require '../../../../page/common_back_btns.php'; ?>
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
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12" style="width:98%;">
                    <h1 class="heading">EDIT EMPLOYEE DETAILS</h1>
                    <div class="border"></div>
                    </br>
                    <div id="msg_session">
                    <?php 
						if(!empty($msg))
						{
							echo "<br/>";
							echo $msg;
							echo "<br/>";
						}
						if(isset($_SESSION['msg']))
						{
							echo "<br/>";
							echo $_SESSION['msg'];
							echo "<br/>";
							unset($_SESSION['msg']);
						}
                    ?>
                    </div>
                    <div class="emplist">
                        <div class="school">
                            <div class="table-responsive">
                                <table width="100%">
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Name</th>
                                    <th>Primary</th>
                                    <th>Professional</th>
                                    <th>Salary</th>
                                    <th>Personal</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Send To Secretary</th>
                                </tr>
                                
                                <?php $cnt=1; 
								if(count($arr))
								{
									foreach($arr as $item)
									{
										if($item['emp_status']=='10' & $item['emp_form_status']=='5')
										{
											$status='<span style="color:#660066;font-weight:bold">PROFILE NOT SENT</span>';
										}
										else if($item['emp_status']=='10')
										{
										if($item['emp_form_status']=='1' ||$item['emp_form_status']=='2' || $item['emp_form_status']=='3' || $item['emp_form_status']=='4')
										{
											$status='<span style="color:#127C89;font-weight:bold">PROFILE INCOMPLETE</span>';
										}
										}
										else if($item['emp_status']=='5')
										{
											$status='<a value="'.$item['emp_id_pk'].'" id="show_rsn'.$cnt.'" onclick="show_reason('.$cnt.')" class="reason_view" data-toggle="modal" data-target="#reject_reason" style="cursor:pointer;"><span style="color:RED;font-weight:bold">PROFILE REJECTED BY SECRETARY</span></a>';
										}
										else if($item['emp_status']=='7')
										{
											$status='<a value="'.$item['emp_id_pk'].'" id="show_rsn'.$cnt.'" onclick="show_reason('.$cnt.')" class="reason_view" data-toggle="modal" data-target="#reject_reason" style="cursor:pointer;"><span style="color:RED;font-weight:bold">PROFILE REJECTED BY AEO</span></a>';
										}
										else if($item['emp_status']=='4')
										{
											$status='<span style="color:#127C89;font-weight:bold">WAITING FOR EDIT</span>';
										}
										
										
										if($item['emp_status']=='7' || $item['emp_status']=='5')
										{
											$db=new database();
											$arr_reason=$db->fetch_table("SELECT reason 
																			FROM psemp_employee_profile_update_status 
																			WHERE emp_id_fk='".$item['emp_id_pk']."' order by sl_no desc limit 1");
											
											$reject_reason= $arr_reason[0]['reason'];
											?>
											<input type="hidden" id="rsn_view<?php echo $cnt; ?>" value="<?php echo $reject_reason; ?>" />
										
										<? }
										?>
										<tr>
                                            <td><?= $cnt; ?></td>
                                            <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                            <td>
                                                <? if($item['emp_form_status']=='1' || $item['emp_form_status']=='2' || $item['emp_form_status']=='3' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5')
                                                { ?>
                                                    <a href="profile_entry_basic_edit.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
                                                    <img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
                                                    </a>
                                                <? } ?>
                                            </td>
                                            <td>
                                                <? if($item['emp_form_status']=='2'|| $item['emp_form_status']=='3' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5')
                                                { ?>
                                                    <a href="profile_entry_prof.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
                                                    <img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
                                                    </a>
                                                <? } ?>
                                            </td>
                                            <td>
                                            <? if($item['emp_form_status']=='3'|| $item['emp_form_status']=='4' || $item['emp_form_status']=='5')
                                            { ?>
                                                <a href="profile_entry_sal.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>&desig=<?=$cryptoGraph->encode($item['emp_desig'],4) ?>">
                                                <img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
                                                </a>
                                            <? } ?>
                                            </td>
                                            <td>
                                                <? if($item['emp_form_status']=='4' || $item['emp_form_status']=='4' || $item['emp_form_status']=='5')
                                                { ?>
                                                    <a href="profile_entry_per.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>&desig=<?=$cryptoGraph->encode($item['emp_desig'],4) ?>">
                                                    <img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
                                                    </a>
                                                <? } ?>
                                            </td>
                                            <td>
                                                <? if($item['emp_form_status']=='5')
                                                { ?>
                                                    <a href="profile_entry_contact.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_pk'],4) ?>">
                                                    <img width="25" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  />
                                                    </a><? 
                                                } ?>
                                            </td>
                                            <td><?php echo $status; ?></td>
                                            <td class="sendtoddo">
                                                <? if($item['emp_form_status']=='5'){ ?><a id="<?php echo $cryptoGraph->encode($item['emp_id_pk'],4) ?>" onClick="show_confirmation(this.id);"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/></a>
                                                <? } else{ ?>
                                                <img width="25" src="<?= $config['base_url'];?>themes/default/image/send_disable.png" alt="Edit" />
                                                <?php } ?>
                                            </td>
                                            <? 
                                            if($item['emp_status']=='10')
                                            {
                                                $delete_status='<a style="cursor:pointer;" onclick="view_ps_modal('.$item['emp_id_pk'].')"><p class="text-primary" style="font-weight:bold"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></p></a>';
                                            }
                                            else 
                                            {
                                                $delete_status='<!--<a onclick="view_bdo_modal('.$item['emp_id_pk'].')">--><p class="text-primary" style="font-weight:bold">PROFILE REJECTED</p><!--</a>-->';
                                            }  
                                            
                                            ?>
                                            
										</tr>
										<?php $cnt++;
									} 
								} 
								else 
								{ ?>
                                    <tr>
                                    <td colspan="10" style="color:red;font-weight:bold">No Data Found</td>
                                    </tr>
                                <? 
								} ?>
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

<? require '../../../../page/layout/footer.php'; ?>

<script>
	
	function view_bdo_modal(emp_id_pk)
	{
		$('#bdo_modal').modal('show');
		$('#emp_id_pk').val(emp_id_pk);
		$('#status').val(0);
		$("#reason").hide();
		$("#reason_lbl").hide();
	}
	
	function view_ps_modal(emp_id_pk)
	{
		$('#bdo_modal').modal('show');
		$('#emp_id_pk').val(emp_id_pk);
		$('#status').val(1);
		$("#reason").show();
		$("#reason_lbl").show();
	}
	
	function show_confirmation(k)
	{
		$('#sent_eo').modal('show');
		$('#emp_id_ps').val(k);
	}
	
	function show_reason(k)
	{
		var shw_rsn_id=$('#rsn_view'+k).val();
		$('#rsn_bdy').html(shw_rsn_id);
	}

</script>


<div class="modal fade bs-example-modal-md" aria-hidden="true" id="bdo_modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
            </div>
            <form action="emp_delete_profile_submit.php" method="post">
                <div class="form-group">
                    <label for="message-text" class="control-label" id="reason_lbl">Reason:</label>
                    <textarea class="form-control" id="reason" name="reason" draggable="false"></textarea>
                    <input type="hidden" id="emp_id_pk" name="emp_id_pk" value="" />
                    <input type="hidden" id="status" name="status" value="" />
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <input type="submit" name="submit" value="YES" id="save" class="btn btn-primary">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<form method="post" action="send_employee_to_sec.php" > 
    <div class="modal fade bs-example-modal-sm" id="sent_eo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="width: max-content; margin-left: -45%;">
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Employee Send</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Send This Employee Profile to Secretary for Approval?</strong></p>
                    <input type="hidden" id="emp_id_ps" name="emp_id_ps"/>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="sendtoeo" id="sendtoeo" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="reject_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Employee Reject Reason</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
                <div class="mbody" > 
                <span style="color:#660066;font-weight:bold">Reason : </span><span id="rsn_bdy"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
                </div>
            </div>
        </div>
    </div>
</div>
<!---------------------------------------------MODAl------------------------------------->
<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body"> 
            	<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This BDO Profile For UNLOCK ?</strong></p>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_prd/block/unlock_bdo_profile.php">YES</a>
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button> 
                </div>
            </div>
        </div>
    </div>
</div>
