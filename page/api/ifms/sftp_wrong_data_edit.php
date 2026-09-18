<?php
set_time_limit(0);
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';


$crypto = new cryptography();
$db = new database();

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

$wrong_employee_data_fetch=$db->fetch_table("  SELECT
													emp_id_pk,
													gp_id_fk,
													emp_first_name,
													emp_second_name,
													emp_last_name, 
													emp_bank_name,
													emp_acc_no,
													emp_ifsc_no,
													emp_id_const 
												FROM prd_employee_master emp
												INNER JOIN prd_sftp_benf_failure_details benf
												ON emp.emp_id_pk=benf.emp_id_fk
												WHERE benf.active_status='1' AND benf.response_from='9'");

?>

<script>
	function edit_individual(k)
	{
		$('#indi_edit').modal('show');
		$.post('<?= $config['base_url'] ?>page/api/ifms/ajax_bank_view.php?id='+k, function(data){
		  //$(".empshow").html(data);
		  });
		
	}

</script>  
    
<div class="school">
    <div class="table-responsive">
        <table width="100%">
            <tr>
            	<th>NAME OF GP</th>
                <th>NAME OF EMPLOYEE</th>
                <th>EMPLOYEE ID</th>			
                <th>BANK NAME</th>
                <th>ACCOUNT NUMBER</th>
                <th> IFSC CODE</th>
                <th>ACTION</th>
            </tr>
            <?php 
			if(count($wrong_employee_data_fetch)>0)
			{
				foreach($wrong_employee_data_fetch as $key)
				{ ?>
                    <tr>
                    	<td><?php echo fun_gp($key['gp_id_fk']); ?></td>
                        <td><?php echo $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name']; ?></td>
                        <td><?php echo $key['emp_id_const']; ?></td>
                        <td><?php echo fun_bank($key['emp_bank_name']); ?></td>                                                             
                        <td><?php echo $key['emp_acc_no']; ?></td>
                        <td><?php echo $key['emp_ifsc_no']; ?></td>
                        <td><a id="<?php echo $crypto->encode($key['emp_id_fk'],4); ?>" onClick="edit_individual(this.id)" ><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view"></a></td>
                   </tr>
				<?php 
				} 
			}
			else
			{?>
            	<tr><td colspan="7" style="color:red;font-weight:bold">No Data Found</td></tr>
			<?php 
			} ?>
        </table>
    </div>
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
	  /*$(".show a").click(function() {	
	  var link = $(this).attr('id');
	  $.post('<?= $config['base_url'] ?>page/api/ifms/ajax_bank_view.php?id='+link, function(data){
	  $(".empshow").html(data);
	  });
	  });*/
	});
</script>
    
    
