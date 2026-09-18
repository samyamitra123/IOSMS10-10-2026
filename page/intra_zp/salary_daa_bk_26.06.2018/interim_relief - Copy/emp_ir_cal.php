<?
session_start();
require '../../../../includes/config/config.php';

require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
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
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
<?
$db=new database();
$arr=$db->fetch_table("SELECT 
							emp_id_pk,
							emp_id_const,
							emp_first_name,
							emp_second_name,
							emp_last_name,
							emp_status,
							interim_relief,
							emp_system_code 
						FROM 
							prd_employee_master 
						WHERE 
							emp_status='1' AND zp_id_fk='".$_SESSION['location']['district_id']."' 
						ORDER BY emp_id_const");

$ir_saved = $db->fetch_table("SELECT 
									emp_id_fk,
									ir_status,
									interim_relief,
									unlock_req
								FROM 
									psemp_interim_relief
								WHERE
									ir_status IN ('1','2','3','4') AND delete_status=1 AND zp_id_fk='".$_SESSION['location']['district_id']."'
												
							");
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
<? require '../../../../page/common_back_btns.php'; ?>
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
            <? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
              ?></h3>
</div>
<div class="row" id="cont">
<div class="content">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
<div class="col-sm-12">
<h1 class="heading"> EMPLOYEE INTERIM RELIEF DETAILS</h1>
<div class="border"></div>
</br>
</br>
<div class="border_val"></div>
<div id="session_msg_print">
	<?
         if($_SESSION['msg'])
         {
         echo $_SESSION['msg'];
         unset($_SESSION['msg']);
          }
    ?>  
</div>
<?php 
	if($msg)
    {
	echo $msg;
	echo "<br/>";
	echo "<br/>";
    }
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
    <table width="100%">
    <tr>
    <th>Serial No.</th>
    <th>Employee ID</th>
    <th>Employee Name</th>
    <th>IR</th>
    <th>Edit</th>
    <th>Save</th>
     <th>Send</th>
   <th>Unlock Request</th>

    </tr>
<? $cnt=1; if(count($arr))
{
	foreach($arr as $item)
	{
		
		?>
		<input type="hidden" id="id<?=$cnt; ?>" name="id" value="<?=($item['emp_id_pk']);?>"  />
		<input type="hidden" id="ename<?=$cnt; ?>" name="ename" value="<?=( $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']);?>"  />
		<input type="hidden" id="emp_id_const<?=$cnt; ?>" name="id" value="<?=($item['emp_id_const']);?>"  />
        <tr>
        <td id="show"><?= $cnt;?></td>
        <td><?= $item['emp_id_const']=='0'?'':$item['emp_id_const']?></td>
        <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>	
		<?php 
		if($ir_saved)
		{
			foreach($ir_saved as $key)
			{
				
				if($item['emp_id_pk']==$key['emp_id_fk'])
				{ 
					$interim_amount=$key['interim_relief'];
					break;
				}
				else
				{
					$interim_amount=$item['interim_relief'];
				}
			}
		}
		else
		{
			$interim_amount=$item['interim_relief'];
		}
		?>
		<td><?php echo $interim_amount;?></td>

        <?php
		$saved = 0;
		for($i =0 ; $i < count($ir_saved) ; $i++)
		{
		
			if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && ($ir_saved[$i]['ir_status']=='2' || $ir_saved[$i]['ir_status']=='3'|| $ir_saved[$i]['ir_status']=='4' && ($ir_saved[$i]['unlock_req']=='0'||$ir_saved[$i]['unlock_req']=='1')))
			{  
				$saved = 1; ?>
				<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" style="opacity:0.5;" alt="Edit"></td>
				<td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Save"></td>
                
				
		<?php }
		 }
		if($saved == 0)
		{ ?>
            <td>
                <div class="edit_id<?=$cnt?>">
                	<a data-toggle="modal" onClick="submit_ir(<?=$cnt;?>);" style="cursor:pointer; "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" ></a>
                </div>
            </td>
		   
            <td class="save" id="save">
                <div class="save_id<?=$cnt?>">
               <a id="ir_save&<? echo $cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cnt; ?>" onClick="save_ir(this.id);"><img id="img_id<?=$cnt?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="save"></a>
                </div>
            </td>
		
	<?php } 
		
		$send_val = 0;
		for($i =0 ; $i < count($ir_saved) ; $i++)
		{
			if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && $ir_saved[$i]['ir_status']=='2')
			{ 
				$send_val=1; ?>  
                <td class="send" id="send">
                    <div class="sent_id<?=$cnt?>">
                    	<a id="ir_send&<? echo $cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cnt; ?>" onClick="send_ir(this.id);"><img id="img_id<?=$cnt?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/send.png" alt="send"></a>
                    </div>
                </td>
		   
			
<?php   	}
		}
		if($send_val == 0)
		{  ?>
            <td>
                <div class="sent_disable<?=$cnt?>">
                <img width="25" src="<?= $config['base_url'];?>themes/default/image/send_disable.png" alt="send" />
                </div>
            </td>
		
	<?php } ?>
          <?php
		$unlock_req = 0;
		for($i =0 ; $i < count($ir_saved) ; $i++)
		{
		
			if($item['emp_id_pk']==$ir_saved[$i]['emp_id_fk'] && ($ir_saved[$i]['ir_status']=='4' && $ir_saved[$i]['unlock_req']=='0'))
			{  
				$unlock_req = 1; ?>
				<td id="unlock_req" class="unlock_req"><a id="<? echo $cryptoGraph->encode($item['emp_id_pk'],4) ?>" onClick="show_unlock(this.id);"> <i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color:#EF5350;"></i> </a></td>
				
		<?php }
		 }
	     if($unlock_req == 0)
		{  ?>
            <td >
                
		   
		    	
			<i class="fa fa-unlock-alt fa-2x" aria-hidden="true" style="color:#F8BBD0;"></i>
               
           
            </td>
		
		<?php } ?>
	    
	    
	    
	    
	    
	    
	    
		
		</tr>
		 
<? $cnt+=1; 
	}
} 
else 
{ ?>
    <tr>
    	<td colspan="8" style="color:red;font-weight:bold">No Data Found</td>
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
function submit_ir(k)
{
	var emp_id=$('#id'+k).val();
	$('#unlock_emp_id').val(emp_id);
	var emp_name=$('#ename'+k).val();
	$('#emp_name').html(emp_name+"'s");
	$('#ir_emp_name').val(emp_name);
	var emp_id_const=$('#emp_id_const'+k).val();
	$('#emp_id_const').html(emp_id_const);
	$('#edit').modal('show');
	var show = $(this).attr('show');
}

function sent_save(k)
{
	var emp_id=$('id'+k).val();
	$('#save').val(emp_id);

}
function show_unlock(k)
{
    //alert(k);
	$('#sent_eo').modal('show');
	$('#emp_id_pk').val(k);
}


function save_ir(k)
{
	
	var res = k.split("&");
	var emp_id_pk = res[1];
	var row_count=res[2];
	var emp_full_name=$('#ename'+row_count).val();
	
	$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/interim_relief/ajax_save_ir.php?postemp='+emp_id_pk+'&emp_full_name='+emp_full_name, function(data){
			var result = $.parseJSON(data);
			if(result[0]==1)
			{           
				$('#session_msg_print').html('');
				$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>The Interim Relief of '+result[1]+' has been successfully saved.</strong></div>');
				$('.save_id'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="save">');
				$('.edit_id'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" style="opacity:0.5;" alt="Edit">');
				$('.sent_disable'+row_count).html('<a id="ir_send&'+emp_id_pk+'&'+row_count+'" onClick="send_ir(this.id);"><img id="img_id'+row_count+'" width="25" src="<?= $config['base_url'] ?>themes/default/image/send.png" alt="send"></a>');
			}
	});
	
}

function send_ir(k)
{
	var res = k.split("&");
	var emp_id_pk=res[1];
	var row_count=res[2];
	var emp_full_name=$('#ename'+row_count).val();
	
	$.post('<?= $config['base_url'] ?>page/intra_zp/salary_daa/interim_relief/ajax_send_ir.php?postemp='+emp_id_pk+'&emp_full_name='+emp_full_name, function(data){
	var result = $.parseJSON(data);
			if(result[0]==1)
			{           
				$('#session_msg_print').html('');
				$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>The Interim Relief of '+result[1]+' has been successfully send.</strong></div>');
				$('.save_id'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="save">');
				$('.edit_id'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" style="opacity:0.5;" alt="Edit">');
				$('.sent_id'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/send_disable.png" alt="Send">');
				$('.sent_disable'+row_count).html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/send_disable.png" alt="Send">');
			}
		});
}	    

</script>
<!----------------------------------------------------------MODAL START-------------------------------------------------->
<form name="unlock_emp" id="unlock_emp" action="employee_ir_submit.php" method="post">
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="hed">INTERIM RELIEF SUBMISSION</h4>
                    <br>
                    <div class="row">
                        <div class="col-sm-12 heading">
                        <label id="emp_name"></label> (<label id="emp_id_const"></label>) <label>Interim Relief</label>
                        </div>
                    </div>
                </div>
                <div class="modal-body"> 
                    <div class="form-group">
                        <div class="col-sm-4 "></div>
                        <div class="col-sm-4 ">
                            <input type="text" id="ir_submit"  class="form-control" name="ir_submit" placeholder="IR SUBMIT" autocomplete="off" />
                            <input type="hidden" name="ir_emp_name" id="ir_emp_name"/>
                        </div>
                        <div class="col-sm-4 "></div>
                    </div>
                    <div style="height:40px;"></div>
                    <div class="form-group">
                    	<div class="col-sm-3" style="margin-left:45%;">
                        <input type="submit" name="submit" value="SUBMIT" class="btn btn-success"  alt="center">
                        </div>
                    </div>
                    <div style="height:20px;"></div>
	 
                </div>
                <div class="modal-footer" aligin="center">
                    <div class="btn-group" >
                    <input type="hidden" id="unlock_emp_id" name="unlock_emp_id" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!----------------------------------------------------------Unlock Req Modal-------------------------------------------------->

<form method="post" action="ir_unlock.php" > 
<div class="modal fade bs-example-modal-sm" id="sent_eo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Send</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Send IR Unlock Request to Accountant?</strong></p>
      <input type="hidden" id="emp_id_pk" name="emp_id_pk"/>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="sendtoeo" id="sendtoeo" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
</form>