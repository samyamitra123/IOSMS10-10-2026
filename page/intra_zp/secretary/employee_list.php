<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../all_function/fun_store/zp_ps_gp_function.php';

if($_SERVER['HTTP_REFERER']=='')
{
	header("Location:../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$cryptoGraph=new cryptography();

$fun_store=new zp_ps_gp_class();

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
	.school table th
	{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
	.school table
	{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
	.school
	{
		background-color: #FFFFFF;
		border-radius: 8px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
		padding: 10px;
	}
	.school .title h2
	{
		color: #FFF;
		text-align: center;
		padding: 0px;
		margin: 0px;
		background-color: #0D8BBD;
		border-radius: 8px;
		-moz-border-radius: 8px;
	}
	.school .action .ui-widget
	{
		font-size: 11px;
	}
	.school .action
	{
		text-align: center;
	}
	.school .action .ui-button .ui-button-text
	{
		padding: 5px 10px;
	}
</style>

<?php
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
</script>

<?
$db=new database();

$arr=$db->fetch_table("SELECT 
								emp_first_name,
								emp_second_name,
								emp_last_name,
								emp_desig,
								emp_status,
								emp_id_pk,
								emp_system_code,
								emp_form_status,
								emp_id_const,
								emp_unlock_status 
								FROM prd_employee_master 
								WHERE emp_form_status='5' AND zp_id_fk='".$_SESSION['location']['district_id']."' order by emp_first_name");

$desig_data = $db->fetch_table("
									SELECT designation_id, designation_name
									FROM zpemp_emp_desig_master");


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
                    <h1 class="heading">EMPLOYEE LIST FOR APPROVAL</h1>
                    <div class="border"></div>
                    </br>
                    </br>
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
                    ?>
                    <div class="msg"></div>
                    <div class="emplist" >
                        <div class="school">
                            <div class="table-responsive">
                                <table width="100%">
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>View</th>
                                </tr>
                                <? $cnt=1; 
								if(count($arr))
                                {
									foreach($arr as $item)
									{
										if($item['emp_status']=='6')
										{
											$status='<span style="color:#660066;font-weight:bold">WAITING FOR FORWORD</span>';
										}
										else if($item['emp_status']=='3')
										{
											$status='<span style="color:#6273e6;font-weight:bold">WAITING FOR APPROVAL</span>';
										}
										else if($item['emp_status']=='5')
										{
											$status='<span style="color:red;font-weight:bold">REJECTED FROM SECRETARY</span>';
										}
										else if($item['emp_status']=='7')
										{
											$status='<a value="'.$item['emp_id_pk'].'" id="show_rsn'.$cnt.'" onclick="show_reason('.$cnt.')" class="reason_view" data-bs-toggle="modal" data-bs-target="#reject_reason" style="cursor:pointer;"><span style="color:RED;font-weight:bold">REJECTED BY AEO</span></a>';
										}
										else if($item['emp_status']=='10') 
										{
											if($item['emp_unlock_status']=='0')
											{ 
												$status='<span style="color:#b96c1c;font-weight:bold">WAITING FOR SEND TO SECRETARY</span>';
											}
											else if($item['emp_unlock_status']=='4')
											{
												$status='<span style="color:RED;font-weight:bold">PROFILE WAITING TO EDIT</span>';
											}
										}
										else if($item['emp_status']=='1') 
										{
											if($item['emp_unlock_status']=='0')
											{ 
												$status='<span style="color:green;font-weight:bold">APPROVED</span>';
											}
											else if($item['emp_unlock_status']=='1')
											{ 
												$status='<span style="color:green;font-weight:bold">UNLOCK REQUEST WAITING FOR FORWARD</span>';
											}
											else if($item['emp_unlock_status']=='2')
											{ 
												$status='<span style="color:green;font-weight:bold">UNLOCK REQUEST FORWARDED</span>';
											} 
											else if($item['emp_unlock_status']=='3')
											{ 
												$status='<span style="color:red;font-weight:bold">UNLOCK REQUEST REJECTED BY AEO</span>';
											}  
										}
										
										
										if($item['emp_status']=='7')
										{
											$db=new database();
											$arr_reason=$db->fetch_table("SELECT 
																				reason
																				FROM psemp_employee_profile_update_status 
																				WHERE emp_id_fk='".$item['emp_id_pk']."' 
																				ORDER BY sl_no desc limit 1");
																				$reject_reason= $arr_reason[0]['reason'];
											?>
											<input type="hidden" id="rsn_view<?php echo $cnt; ?>" value="<?php echo $reject_reason; ?>" />
										<? 
										}
										?>
										<tr>
                                            <td><?= $cnt;?></td>
                                            <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
                                            <td><?=  $fun_store->fun_desig($item['emp_desig'],$desig_data); ?></td>
                                            <td><?= $status; ?></td>
                                            <td class="view">
                                            <a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#myModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                                            </td>
										</tr>
										<? $cnt+=1; 
									}
								} 
                                else 
                                { ?>
                                    <tr>
                                    <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
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

<? require '../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>


<script>
	$(document).ready(function()
	{
		$(".view a").click(function() 
		{
			var link = $(this).attr('id');
			
			$(".mbody").load('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_emp_view.php?id='+link, function(responseTxt,statusTxt,xhr)
			{
				var status= $('#emp_status').val();
				if(status=='6')
				{
					$(".finz_but").show();
					$(".rej_but").show();
					$("#reason_lbl").show();
					$("#reason_reject").show();
					//$("#rej").val(status);
				}
				else if (status=='7'  )
				{
					$(".finz_but").hide();
					$(".rej_but").show();
					$("#reason_lbl").hide();
					$("#reason_reject").hide();
				}
				else
				{
					$(".finz_but").hide();
					$(".rej_but").hide();
				}
			});
		});
		
		$(".forward").click(function()
		{
			var link1=$("#emp_id").val();
			$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_forward_reject.php?id='+link1+'&flag=forward', function(data){
				$('#myModal').modal('toggle');
				$('#forward').modal('toggle');
				$('.msg').html(data);
			});
		});
		
		$(".reject").click(function()
		{
			var link2=$("#emp_id").val();
			var emp_stat= $("#emp_status").val();
			var reason=$('#reason_reject').val();
			//var rej=$("#rej").val(status);
			if(reason=="" && emp_stat!='7')
			{
				alert("Please Insert Valid Reason");
			}
			else
			{
				$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_forward_reject.php?id='+link2+'&flag=reject&reject_reason='+reason+'&emp_status='+emp_stat,function(data)
				{
					$('#myModal').modal('hide');
					$('#reject').modal('show');
					$('.msg').html(data);
				});
			}
		});
	});
	
	
	function show_reason(k)
	{
		var shw_rsn_id=$('#rsn_view'+k).val();
		$('#rsn_bdy').html(shw_rsn_id);
	}	

	function unlock_action(k)
	{
		var unlock=('<?= $cryptoGraph->encode('unlock',4);?>');
		var reject=('<?= $cryptoGraph->encode('reject',4);?>');
		
		//alert(k);
		
		var arr=k.split('&');
		
		if(arr[0]=='unlock')
		{
			$('#unlock_r').modal('show');
			$('#unlock_show').show();
			$('#reject_show').hide();
			$('#flag').val(unlock);
			$('#emp_unlock_id').val(arr[1]);
		}
		else if(arr[0]=='reject')
		{
			$('#unlock_r').modal('show');
			$('#unlock_show').hide();
			$('#reject_show').show();
			$('#flag').val(reject);
			$('#emp_unlock_id').val(arr[1]);
		}
	}
</script>
<style>
.modal-backdrop fade in{
height:auto 0;
}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  
                </div>
            <div class="modal-body"> 
                <div class="mbody"> 
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                <a class="btn btn-success finz_but" data-bs-toggle="modal" data-bs-target="#forward"><span id="unlock_for_span" >Unlock Request </span>Forward</a>
                <a class="btn btn-danger rej_but" data-bs-toggle="modal" data-bs-target="#reject"><span id="unlock_rej_span" >Unlock Request </span>Reject</a>  
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
                </div>
            </div>
        </div>
    </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<div class="modal fade bs-example-modal-sm" id="forward" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style=" width: max-content; margin-left: -32%;">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">Employee Approve</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              
            </div>
            <div class="modal-body"> 
            	<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Forward The Employee Profile ?</strong></p>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <a class="btn btn-success forward">YES</a> 
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-example-modal-sm" id="reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style=" width: max-content; margin-left: -32%;">
            <div class="modal-header">
             <h4 class="modal-title" id="myModalLabel">Employee Reject</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body"> 
                <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure You Want To Reject The Employee Profile ?</strong></p>
                <label for="message-text" class="control-label" id="reason_lbl" style="display:none;">Reason<span class="star_color">*</span>:</label>
                <textarea class="form-control" id="reason_reject" name="reason_reject" draggable="false" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');" style="display:none;"></textarea>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <a class="btn btn-success reject">YES</a> 
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                </div>
            </div>
        </div>
    </div>
</div>

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

<form name="unlock_request" id="unlock_request" action="unlock_reject_request_employee.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="unlock_r" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">UNLOCK REQUEST</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To <span id="unlock_show" style="display:none;"> Forward Unlock</span><span id="reject_show" style="display:none;">Reject Unlock</span>  Request ?</strong></p>
                    <input type="hidden" id="flag" name="flag" />
                    <input type="hidden" id="emp_unlock_id" name="emp_unlock_id" />
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

