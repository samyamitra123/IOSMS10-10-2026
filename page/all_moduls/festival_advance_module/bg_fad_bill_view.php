<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';

$crypto = new cryptography();
if($_GET['dise']){
$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//

if (
!isset($_SESSION['user_info']['stake_user'])
| !isset($_SESSION['user_info']['stake_level'])
| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | eHRMS | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic---------------------------------------------------------------------------------
//-----------------------------QUERY----------------------------------------------------------------------------------

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

$crypto=new cryptography();
$db = new database();

?>

<script>
	var year=0;
	var month=0;
	
	function fun_individual_edit(k)
	{
		var user="<?php echo $logged_user; ?>";
		var arr=k.split('&');
		if(user=='BDO')
		{
			var enc_emp_id=arr[0];
			var enc_bill_id=arr[1];
			var enc_gp_id=arr[2];
			
			$('#fad_entry_modal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_fad_individual_edit.php?enc_emp_id='+enc_emp_id+'&enc_bill_id='+enc_bill_id+'&enc_gp_id='+enc_gp_id, function(data){
				$("#mbody").html(data);
			});
		}
		else
		{
			var enc_emp_id=arr[0];
			var enc_bill_id=arr[1];
			
			$('#fad_entry_modal').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_fad_individual_edit.php?enc_emp_id='+enc_emp_id+'&enc_bill_id='+enc_bill_id, function(data){
				$("#mbody").html(data);
			});
		}
		
	}
	
	$(document).ready(function() 
	{
		$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
		
		$('#year,#bill_no').change(function()
		{
			var year=$('#year').val();
			var bill_no=$('#bill_no').val();
			if(year!="" && bill_no!="")
			{
				$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_fad_bill_details_show.php?year='+year+'&bill_id='+bill_no, function(data){
				$("#bonus_table").html(data);
				});
			}
			else if(year!="" && bill_no=="")
			{
				$('#bonus_table').html("Please Select Bill Number.");
			}
			else if(year=="" && bill_no!="")
			{
				$('#bonus_table').html("Please Select Year.");
			}
			else
			{
				$('#bonus_table').html("Please Select Year and Bill Number.");
			}
		});
	});

</script>

<script>
	$(document).ready(function(){
		$( "tr:odd" ).css( "background-color", "#dfeaec" );
		$( "tr:even" ).css( "background-color", "#fff6" ); 
	});
	
	function show_bill(k)
	{
		$("#bill_no").html('<option value="">-Please Select-</option>');
		$.post('<?= $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_ajax_bill_no_fetch.php?year='+k, function(data){
			$("#bill_no").html(data);
		});
	}
	
	function bill_view_show(k)
	{
		var user='<?php echo $logged_user;?>';
		var arr=k.split('&');
		$('#bill_download_modal').modal('show');
		if(user=='BDO')
		{
			$('#download_bill').html('<div class="form-group"><div class="col-sm-offset-2 col-sm-7" style="width: 125%;text-align: left;"><a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/all_moduls/festival_advance_module/bg_fad_text_file/personal_gp.php?mo='+arr[1]+'&ye='+arr[2]+'&bill='+arr[0]+'"><i class="fa fa-file-text"></i> Generate Personnel Details</a></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-7"><a class="btn btn-info btn-sm" href="bg_fad_text_file/salarybill_gp.php?mo='+arr[1]+'&ye='+arr[2]+'&bill='+arr[0]+'" style="width: 150%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a></div></div><br /><div class="form-group"><div class="col-sm-offset-2 col-sm-7"><a class="btn btn-info btn-sm" href="bg_fad_text_file/xml_file_gp.php?mo='+arr[1]+'&ye='+arr[2]+'&bill='+arr[0]+'" style="width: 150%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a></div></div>');
		}
		else if(user=='EO')
		{
			$('#download_bill').html('<div class="form-group"><div class="col-sm-offset-2 col-sm-7"><a class="btn btn-info btn-sm" href="bg_fad_text_file/salarybill_ps.php?mo='+arr[1]+'&ye='+arr[2]+'&bill='+arr[0]+'" style="width: 125%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summary</a></div></div><br /><div class="form-group"><div class="col-sm-offset-2 col-sm-7"><a class="btn btn-info btn-sm" href="bg_fad_text_file/xml_file_ps.php?mo='+arr[1]+'&ye='+arr[2]+'&bill='+arr[0]+'" style="width: 125%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a></div></div>');
		}
	}
</script>

<div class="content">
	<!-- Common Back Button --->
	<?php require '../../common_back_btns.php'; ?>	
    
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
                <div class="col-sm-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-11  ">
                                <div class="offer offer-success">
                                    <div class="offer-content">
                                        <h1 class="heading">Festival Advance Bill View</h1>
                                        <div class="border"></div>
                                        </br></br>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-1"></div>
                                            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm; color:#0070A3;">Select Year<span class="star_color">*</span>:</label>
                                            <div class="col-sm-2">
                                                <select class="form-control upper_case" name="year" id="year" style="width:150px;" onChange="show_bill(this.value);">
                                                <option value="">-Please Select-</option>
                                                <?php
                                                for($i=2017; $i<=date('Y'); $i++)
                                                {
													?>
													<option value="<?php echo $i?>" <? if($i==$_REQUEST['year']){echo "selected";} ?>><?php echo $i; ?></option>
													<?php
                                                }
                                                ?>
                                                </select>		
                                            </div>
                                            <div class="col-sm-1"></div>
                                            <label for="inputPassword3" class="col-sm-2 control-label" style="margin-top:.23cm;color:#0070A3;">Select Bill<span class="star_color">*</span>:</label>
                                            <div class="col-sm-2">
                                                <select class="form-control upper_case" name="bill_no" id="bill_no" style="width:150px;" >
                                                <option value="">-Please Select-</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12" style="margin-top:25px">
                                            <div class="col-sm-5"></div>
                                            <div id="bonus_table">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div> 
                        <div style="height:10px;"></div>
                    </div> 
                </div>
            </div>
        </div> 
    </div>
</div>



<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>



<style>
	.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
		border:3px solid #fff;
	}
	.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	.school table th{
		background-color: #5B7778;
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

	.shape{    
		border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
		-ms-transform:rotate(360deg); /* IE 9 */
		-o-transform: rotate(360deg);  /* Opera 10.5 */
		-webkit-transform:rotate(360deg); /* Safari and Chrome */
		transform:rotate(360deg);
	}
	.offer{
		/*background:rgba(228, 232, 223, 0.59);*/
		background:rgba(243, 246, 240, 0.71); border:1px solid #ddd; box-shadow: 0 10px 20px rgba(148, 112, 29, 0.64); margin: 15px 0; overflow:hidden; margin-right:28px; padding-bottom:22px;padding-top:10px;
	}
	
	.shape {
		border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
	}
	.offer-radius{
		border-radius:7px;
	}
	.offer-danger {	border-color: #d9534f; }
	.offer-danger .shape{
		border-color: transparent #d9534f transparent transparent;
	}
	.offer-success {	/*border-color: #9e9fb1;*/ }
	.offer-success .shape{
		border-color: transparent #5cb85c transparent transparent;
	}
	.offer-default {	border-color: #999999; }
	.offer-default .shape{
		border-color: transparent #999999 transparent transparent;
	}
	.offer-primary {	border-color: #428bca; }
	.offer-primary .shape{
		border-color: transparent #428bca transparent transparent;
	}
	.offer-info {	border-color: #5bc0de; }
	.offer-info .shape{
		border-color: transparent #5bc0de transparent transparent;
	}
	.offer-warning {	border-color: #f0ad4e; }
	.offer-warning .shape{
		border-color: transparent #f0ad4e transparent transparent;
	}
	
	.shape-text{
		color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
		-ms-transform:rotate(30deg); /* IE 9 */
		-o-transform: rotate(360deg);  /* Opera 10.5 */
		-webkit-transform:rotate(30deg); /* Safari and Chrome */
		transform:rotate(30deg);
	}	
	.offer-content{
		padding:16px 100px 20px;
	}
	@media (min-width: 487px) {
	.container {
		max-width: 750px;
	}
	.col-sm-6 {
		width: 50%;
	}
	}
	@media (min-width: 900px) {
	.container {
		max-width: 970px;
	}
	
	}
	
	@media (min-width: 1200px) {
	.container {
		max-width: 1170px;
	}
	.col-lg-3 {
		width: 25%;
	}
	}

</style>


<div class="modal fade bs-example-modal-lg" id="fad_entry_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:800px;">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 class="modal-title" id="myModalLabel">Employee Festival Advance Details</h4>
            </div>
            <div class="modal-body"> 
                <div id="mbody"> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>      
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="bill_download_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Bill Download</h4>
            </div>
            <div class="modal-body" id="download_bill" style="height:150px;"> 
                
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>      
                </div>
            </div>
        </div>
    </div>
</div>