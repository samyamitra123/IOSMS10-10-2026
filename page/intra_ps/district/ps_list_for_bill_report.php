<?

//echo print_r($_GET);
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

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
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
		
		function bill_details_view(k)
		{
			var bill_id=k;
			var ps_name=$('#ps_name'+k).text().trim();
			var bill_det=$('#bill_detail'+k).val();
			var arr=bill_det.split('&');
			var bill_no=arr[0];
			var bill_date=arr[1];
			$('#bill_details_modal').modal('show');
			/*$('#bill_details_div').html('<p style="text-align:center;font-weight: bold;">PS Name : '+ps_name+'<br/>Bill Number : '+bill_no+'<br/>Bill Date : '+bill_date+'</p>');*/
			$('#bill_details_div').html('<div class="school"><div class="table-responsive"><table width="100%"><tr><th>PS NAME</th><th>BILL NUMBER.</th><th>BILL DATE</th></tr><tr style="background-color:#DDF7FF;"><td>'+ps_name+'</td><td>'+bill_no+'</td><td>'+bill_date+'</td></tr></div></div>');
		}
		
			
		
</script>
<?

$db=new database();

$rep_month=$cryptoGraph->decode($_POST['rep_month'],3);
$rep_year=$cryptoGraph->decode($_POST['rep_year'],3);

$rep_monthyr=$rep_year.$rep_month;

$district_id_pk=$db->fetch_table('select district_id_pk from prd_location_master_district 
							where district_code='.$_SESSION['location']['district_code'].'');

$bill_report_fetch=$db->fetch_table("
										SELECT 
											ps.ps_id_pk,
											ps.ps_name,
											bill.block_bill_pk,
											bill.bill_no,
											bill.bill_entry_time
										FROM
											prd_location_master_panchayat_samiti AS ps
										LEFT JOIN
											(SELECT block_bill_pk,ps_id_fk,bill_no,bill_entry_time FROM prd_block_bill_details WHERE salary_monthyear='".$rep_monthyr."' AND status='1' AND requisition_type='1001') AS bill
										ON
											ps.ps_id_pk=bill.ps_id_fk
										WHERE ps.district_id_fk='".$district_id_pk[0]['district_id_pk']."'
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
	<? require '../../../page/common_back_btns.php'; ?>
    <div class="welcome_msg">
        <h2>WELCOME: <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        </h2> <h3>
        <? if(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'].",".$_SESSION['location']['state_name'];
        }
        ?></h3>
    </div>
    <div class="row" id="cont">
    	<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
            	<h1 class="heading">PANCHAYAT SAMITI LIST</h1>
            	<div class="border"></div>
                </br>
                </br>
				<?php 
                if(isset($_SESSION['block_msg1'])){
                echo $_SESSION['block_msg1'];
                unset($_SESSION['block_msg1']);
                }
                ?>
                <div class="emplist">
                    <div class="school">
                        <div class="downpdf" style="margin-left:90%"><a href="<?= $config['base_url']?>page/intra_ps/district/excel_ps_bill_details.php?district_id=<?php echo $district_id_pk[0]['district_id_pk']; ?>&rep_month=<?php echo $_POST['rep_month'];?>&rep_year=<?php echo $_POST['rep_year'];?>"><img src="<?= $config['base_url'] ?>themes/default/image/excel_download.png"/></a></div>
                        <br>
                        <div class="table-responsive">
                            <table width="100%">
                                <tr>
                                    <th>SERIAL NO.</th>
                                    <th>PANCHAYAT SAMITI NAME</th>
                                    <th colspan="2" style="width:10%;">BILL GENERATED</th>
                                </tr>
                            <? $cnt=1;
                            if(count($bill_report_fetch))
							{ 
								foreach($bill_report_fetch as $item)
								{ 
									if($item['block_bill_pk']!='')
									{
										$status='<p class="text-primary" style="font-weight:bold">YES</p>';
									}
									else
									{
										$status='<p class="text-warning" style="font-weight:bold">NO</p>';
									}
									
									
									?>
									<tr>
									<td>
										<?= $cnt;?>
                                    </td>
									<td id="ps_name<?= $cnt;?>">
										<?= $item['ps_name']?>
                                    </td>
									<td>
										<?= $status?>
									</td>
                                    <?php if($item['block_bill_pk']!='')
									{ ?>
                                    	<td>
                                    		<input type="hidden" name="bill_detail" id="bill_detail<?= $cnt;?>" value="<?php echo $item['bill_no'].'&'.dateshow($item['bill_entry_time']); ?>" />
                                            <a id="<?= $cnt;?>" onClick="bill_details_view(this.id);" ><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                                    	</td>
									<?php 
									}
									else
									{ ?>
                                    	<td>&nbsp;</td>
									<?php
									}
									?>
									</tr>
									<? $cnt+=1; 
								}  
							} 
							else 
							{ ?>
                                <tr>
                                <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
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

<div class="clear"></div>

<? require '../../../page/layout/footer.php'; ?>




<div class="modal fade" id="bill_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bill Details</h4>
      </div>
      <div class="modal-body"> 
      <div class="mbody" id="bill_details_div"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>    
      </div>
    </div>
  </div>
</div>
