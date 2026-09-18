<?php
set_time_limit(0);
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';


$crypto = new cryptography();
$db = new database();

 $benf_id=$crypto->decode($_GET['benf_id'],4); 

function fun_gp($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT gp_name FROM prd_location_master_gp where gp_id_pk='".$val."';");
	return $dist_data2[0]['gp_name'];
}

function fun_bank($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
	return $dist_data2[0]['bank_name'];
}

$total_updated=0;$cnt_f=0;

$wrong_employee_data_fetch=$db->fetch_table("  SELECT
													emp.emp_id_pk,
													emp.gp_id_fk,
													emp.emp_first_name,
													emp.emp_second_name,
													emp.emp_last_name, 
													emp.emp_bank_name,
													emp.emp_acc_no,
													emp.emp_ifsc_no,
													emp.emp_id_const,
													benf.reason,
													benf.active_status 
												FROM prd_employee_master emp
												INNER JOIN prd_sftp_benf_failure_details benf
												ON emp.emp_id_pk=benf.emp_id_fk
												WHERE benf.active_status in ('1','2','3') AND benf.response_from='10' 
												AND emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND benf.sftp_benf_id_fk='".$benf_id."'
												");
												
for($i=0;$i<count($wrong_employee_data_fetch);$i++)
{
	if($wrong_employee_data_fetch[$i]['active_status']=='2')
	{
		$total_updated=$total_updated+1;
		$emp_id_failure_list.=$wrong_employee_data_fetch[$i]['emp_id_pk'];
								
		if($cnt_f!=count($wrong_employee_data_fetch)-1)
		{
			$emp_id_failure_list=$emp_id_failure_list.",";
		}
		$cnt_f++;
	}
}
 $enc_emp_id_failure_list=$crypto->encode($emp_id_failure_list,4); 
?>

<script>
	function edit_individual(k)
	{
		$('#indi_edit').modal('show');
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/ajax_bank_epayment_view.php?id='+k, function(data){
		  $(".empshow").html(data);
		  });
		
	}
	function finalize_all_emp()
	{
		var all_id='<?php echo $enc_emp_id_failure_list; ?>';
		var benf_id='<?php echo $_GET['benf_id']; ?>';
		 
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/ajax_finz_all_epayment.php?all_id='+all_id+'&benf_id='+benf_id, function(data){
			if(data=='1')
			{
				$('.edit_data').attr('style', 'opacity: 0.4');
				$('#msg').html('<div class="alert alert-success" style="text-align:center"><strong> Employee updation has been successfully finalized... </strong></div>');
				$('#finz').hide();
			}
			else if(data=='0')
			{
				$('#msg').html('<div class="alert alert-danger" style="text-align:center"><strong> Employee finalization fails!!! </strong></div>');
				
			}
				
		  });
	}
	
</script>  
    
<div class="school">
<div id="msg">
	<?php if($wrong_employee_data_fetch[0]['active_status']=='3')
    {
        echo '<div class="alert alert-success" style="text-align:center"><strong> Employee updation has been successfully finalized... </strong></div>';	
    }?>
</div>
    <div class="table-responsive">
        <table width="100%">
            <tr>
                <th>NAME OF EMPLOYEE</th>
                <th>EMPLOYEE ID</th>			
                <th>BANK NAME</th>
                <th>ACCOUNT NUMBER</th>
                <th>IFSC CODE</th>
                <th>REASON</th>
                <th>ACTION</th>
            </tr>
            <?php 
			if(count($wrong_employee_data_fetch)>0)
			{
				foreach($wrong_employee_data_fetch as $key)
				{ ?>
                    <tr id="">
                        <td><?php echo $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name']; ?></td>
                        <td><?php echo $key['emp_id_const']; ?></td>
                        <td><?php echo fun_bank($key['emp_bank_name']); ?></td>                                                             
                        <td><?php echo $key['emp_acc_no']; ?></td>
                        <td><?php echo $key['emp_ifsc_no']; ?></td>
                        <td style="color:#E86769; font-weight:bold;"><?php echo $key['reason']; ?></td>
                        <td>
                        <?php if($key['active_status']!='3')
						{ ?>
                        <a id="<?php echo $crypto->encode($key['emp_id_pk'],4); ?>" onClick="edit_individual(this.id)" ><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" class="edit_data"></a>
                        <?php }
						else
						{ ?>
                        	<img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" style="opacity:0.4;">
                        <?php } ?>
                        &nbsp;
                        <?php if($key['active_status']=='1') { ?>
                        <img id="dis<?php echo $key['emp_id_pk']; ?>" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" height="25" width="25" alt="edited" style="opacity:0.4;"/>
                        <img id="en<?php echo $key['emp_id_pk']; ?>" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" height="25" width="25" alt="edited" title="Edited" style="display:none;"/>
                         <?php } else if($key['active_status']=='2' || $key['active_status']=='3') { ?>
                         	<img id="en<?php echo $key['emp_id_pk']; ?>" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" height="25" width="25" alt="edited" title="Edited"/>
                         <?php } ?>
                        </td>
                   </tr>
				<?php 
				} 
			}
			else
			{?>
            	<tr><td colspan="8" style="color:red;font-weight:bold">No Data Found</td></tr>
			<?php 
			} ?>
        </table>
    </div>
   	<?php if(count($wrong_employee_data_fetch)==$total_updated)
	{ ?>
        <div class="col-md-6 col-md-offset-5" style="padding-top:6px;">
            <button type="button" class="btn btn-success" id="finz" onClick="finalize_all_emp();">Finalize</button>     
        </div>
    <?php } ?>
</div>




<!--<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bank Details</h4>
      </div>
      <div class="modal-body"> 
      <div class="empshow"> 
      </div>
      </div>
    </div>
  </div>
</div>-->
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

<script>
	$(document).ready(function(){
	  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
	  $( "tr:even" ).css( "background-color", "#DDF7FF" );
	 /* $(".show a").click(function() {	
	  var link = $(this).attr('id');
	  $.post('<?= $config['base_url'] ?>page/api/ifms/ajax_bank_view.php?id='+link, function(data){
	  $(".empshow").html(data);
	  });
	  });*/
	});
</script>
    
    
