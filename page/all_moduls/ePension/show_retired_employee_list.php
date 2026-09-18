<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
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
$emp_status = 0;

/*$db=new database();	
$arrrr=$db->fetch_table("
SELECT gp_id_pk
from prd_location_master_gp gp
inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
WHERE CAST(b.block_code AS text) like '3299001'  GROUP BY gp_id_pk order by gp_name");

//echo "Hello Wiorld";
//print_r($arrrr); die;
foreach($arrrr as $ar){
	//echo $ar;die();
	$str.=','."'". $ar['gp_id_pk']."'";
//$deb[] = $ar['gp_id_pk'];
}
echo $trimmed_str =  trim($str,','); die;*/
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
    
    <script type="text/javascript">
$(document).ready(function() 
{
	//alert(11);
	$("#example").DataTable({
		
		
		"aLengthMenu": [[2, 3, 5, -1], [2, 3,5, "All"]],
        "iDisplayLength": 5,
"bFilter": true,
	  	"bSort": false,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});

	
	
  });
  

</script>
<?

$user_id=$_SESSION['user_info']['stake_user']; 
$db=new database();
//var_dump($user_id); die;
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
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EMPLOYEE LIST FOR RETIREMENT EDITION</h1>
<div class="border"></div>
<div class="emplist">
<div class="school">
	<?php if(strlen($user_id)=='10')
    { ?>
        <div class="table-responsive">
            <?php  
            $db=new database();
           
		  
            $arr_data = $db->fetch_table("SELECT 
                                        pen.emp_first_name, 
										pen.emp_id_fk,
										pen.reason,
										pen.flag,
										pen.emp_termination_date,
                                        pen.emp_second_name, 
                                        pen.emp_last_name, 
                                        pen.emp_retirement_date, 
                                        gp.gp_name, 
                                        pen.emp_id_const 
                                        FROM prd_location_master_gp gp  
                                        INNER JOIN 
                                        prd_pension_employee pen 
                                        ON gp.gp_id_pk=pen.gp_id_fk 
                                        WHERE pen.emp_pension_status in('6','0','2','3') AND gp.gp_code='" .$user_id. "' AND pen.flag IN('N','M') AND pen.reason In('1991','1993')" );
            ?>
            
            <table width="100%" id="example">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Employee Name</th>
                    <th>Employee Retirement Date</th>
                    <th>Employee Id</th>
                    <th>Action</th>
                    <th>Status Check</th>
                </tr>
                 </thead>
                <?php 
				if(count($arr_data)>0)
				{
					$cnt=1;
					//echo count($item); die;
					foreach($arr_data as $item)
					{ 
					
					if($item['reason']=='1991')
					{
						$retirement_date=$item['emp_retirement_date'];
					}
					else
					{
						$retirement_date=$item['emp_termination_date'];
					}
				?>
                <tr>
                    <td><?= $cnt?></td>
                    <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
                    <td><?= date("d-m-Y", strtotime($retirement_date)); ?></td>
                    <td><?= $item['emp_id_const'] ?></td>
                      <td style=' padding-left: 7%;'>


					<a onClick="value_pass('<?php echo $cryptoGraph->encode($item['emp_id_fk'],4); ?>','<?php echo $cryptoGraph->encode('GP',4); ?>','<?php echo $cryptoGraph->encode($item['flag'],4);?>','<?php echo $cryptoGraph->encode('push',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a>
                   &nbsp;&nbsp;
        <a onClick="value_pass_ack('<?php echo $cryptoGraph->encode($item['emp_id_const'],4); ?>','<?php echo $cryptoGraph->encode('pull',4); ?>');" ><img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/download_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a></td>
					
                     <td class="view1">
                   <a href=""  id="<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>" data-toggle="modal" data-target="#modal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                    </td>

                    
                </tr>
            	<?php $cnt++;
					}
				}
				else
				{ ?> <tr>
                    <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
                    </tr>
                
					
		   <?php }  ?> 
			
                
            </table>
        </div>
    <?php } 
    else if(strlen($user_id)=='7')
    { ?>
    <div class="row">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <div class="emplist">
                        <div class="school">
        <div class="table-responsive">
            <?php  
            $db=new database();
		
            $arr_data = $db->fetch_table("SELECT 
                                        pen.emp_first_name, 
                                        pen.emp_second_name, 
                                        pen.emp_last_name, 
										pen.emp_id_fk,
										pen.gp_id_fk,
										pen.reason,
										pen.flag,
										pen.emp_termination_date,
                                        pen.emp_retirement_date, 
                                        gp.gp_name, 
                                        pen.emp_id_const 
                                        FROM 
                                        prd_location_master_block blk
                                        INNER JOIN
                                        prd_location_master_gp gp 
                                        ON blk.block_id_pk=gp.block_id_fk 
                                        INNER JOIN 
                                        prd_pension_employee pen 
                                        ON gp.gp_id_pk=pen.gp_id_fk 
                                        WHERE pen.emp_pension_status in('6','0','2','3') AND blk.block_code='" .$user_id. "' AND pen.flag IN('N','M') AND pen.reason In('1991','1993')  " );
            ?>
            
            <table width="100%" id="example">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Gram Panchayat Name</th>
                    <th>Employee Name</th>
                    <th>Employee Retirement Date</th>
                    <th>Employee Id</th>
                    <th style= 'width: 20%'>Action</th>
                    <th>Status Check</th>
                </tr>
                </thead>
                <?php 
				if(count($arr_data)>0)
				{
					$cnt=1;
					foreach($arr_data as $item)
					{ 
						if($item['reason']=='1991')
					{
						$retirement_date=$item['emp_retirement_date'];
					}
					else
					{
						$retirement_date=$item['emp_termination_date'];
					}
					
					?>
                    <tr>
                        <td><?= $cnt?></td>
                        <td><?= $item['gp_name'] ?></td>
                        <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
                        <td><?= date("d-m-Y", strtotime($retirement_date));?></td>
                        <td><?= $item['emp_id_const'] ?></td>
                       
                        <td style=' padding-left: 7%;'>
					<a onClick="value_pass('<?php echo $cryptoGraph->encode($item['emp_id_fk'],4); ?>','<?php echo $cryptoGraph->encode('GP',4); ?>','<?php echo $cryptoGraph->encode($item['flag'],4);?>','<?php echo $cryptoGraph->encode('push',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a>
                   
        <a onClick="value_pass_ack('<?php echo $cryptoGraph->encode($item['emp_id_const'],4); ?>','<?php echo $cryptoGraph->encode('pull',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/download_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a>
					</td>
                    <td class="view2">
                   <a href=""  id="<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>" data-toggle="modal" data-target="#modal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                    </td>

                   <!--<a style="padding-left:14px;" href="<?= $config['base_url']?>page/intra_prd/block/edit_emp_epension/edit_employee.php?emp_id_pk=<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>&gp_id=<?= $cryptoGraph->encode($item['gp_id_fk'],4); ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>-->
                    </tr>
				<?php $cnt++; 
				}
			 } 
			else
			{ ?> <tr>
				<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
				</tr>
				
	   <?php }  ?> 
            </table>
        </div>
             </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } 

   else if(strlen($user_id)=='8')
    {?>
        <div class="table-responsive">
            <?php  
            $db=new database();
       
										
            $arr_data = $db->fetch_table("SELECT 
                                        pen.emp_first_name,
                                        pen.emp_second_name, 
                                        pen.emp_last_name, 
										pen.emp_id_fk,
										pen.reason,
										pen.flag,
										pen.emp_termination_date,
                                        pen.emp_retirement_date, 
                                        ps.ps_name, 
                                        pen.emp_id_const 
                                        FROM prd_location_master_panchayat_samiti ps  
                                        INNER JOIN 
                                        prd_pension_employee pen 
                                        ON ps.ps_id_pk=pen.ps_id_fk 
                                        WHERE pen.emp_pension_status in('6','0','2','3') AND ps.ps_id_pk='".$_SESSION['location']['ps_id']."' AND pen.flag IN('N','M') AND pen.reason In('1991','1993')" );
            ?>
            
            <table width="100%" id="example">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Employee Name</th>
                    <th>Employee Retirement Date</th>
                    <th>Employee Id</th>
                    <th>Action</th>
                     <th>Status Check</th>
                </tr>
                </thead>
                <?php 
				if(count($arr_data)>0)
				{
					$cnt=1;
					foreach($arr_data as $item)
					{
					if($item['reason']=='1991')
					{
						$retirement_date=$item['emp_retirement_date'];
					}
					else
					{
						$retirement_date=$item['emp_termination_date'];
					}
						 ?>
					<tr>
						<td><?= $cnt?></td>
						<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
						 <td><?= date("d-m-Y", strtotime($retirement_date));?></td>
						<td><?= $item['emp_id_const'] ?></td>
                          

					<td style=' padding-left: 7%;'><a onClick="value_pass('<?php echo $cryptoGraph->encode($item['emp_id_fk'],4); ?>','<?php echo $cryptoGraph->encode('PS',4); ?>','<?php echo $cryptoGraph->encode($item['flag'],4);?>','<?php echo $cryptoGraph->encode('push',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a>
                    </br>
        <a onClick="value_pass_ack('<?php echo $cryptoGraph->encode($item['emp_id_const'],4); ?>','<?php echo $cryptoGraph->encode('pull',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/download_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a></td>
					
                      <td class="view3">
                   <a href=""  id="<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>" data-toggle="modal" data-target="#modal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                    </td>

					
                    <!--<?php if( $_SESSION['user_info']['stake_abbr']=='EO')
                     {?>
<a style="padding-left:14px;" href="<?= $config['base_url']?>page/intra_ps/eo/ps_emp_epension/employee_edit_details.php?emp_id=<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>"><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" /></a>
					 <? }?>-->
</td>
                        
					</tr>
					<?php $cnt++;
					}
				} 
                else
				{ ?> 
                    <tr>
                    <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
                    </tr>
		   <?php }  ?> 
            </table>
            </table>
        </div>
    <?php }
    
    
    
     else if(strlen($user_id)=='4')
    {?>
        <div class="table-responsive">
            <?php  
            $db=new database();
       
	   
	   
	   
            $arr_data = $db->fetch_table("SELECT 
                                       pen.emp_first_name,
                                        pen.emp_second_name, 
                                        pen.emp_last_name, 
										pen.emp_id_fk,
										pen.reason,
										pen.flag,
										pen.emp_termination_date,
                                        pen.emp_retirement_date, 
                                        zp.district_name, 
                                        pen.emp_id_const,
										pen.emp_id_fk
                                        FROM prd_location_master_district zp
                                        INNER JOIN 
                                        prd_pension_employee pen 
                                       	ON zp.district_id_pk=pen.zp_id_fk  
                                        WHERE emp_pension_status in('6','0','2','3') AND zp.district_id_pk='".$_SESSION['location']['district_id']."' AND pen.flag in('N','M') AND pen.reason In('1991','1993')" );	
	   
            ?>
            
             <table width="100%" id="example">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Employee Name</th>
                    <th>Employee Retirement Date</th>
                    <th>Employee Id</th>
                    <th>Action</th>
                    <th>Status Check</th>
                </tr>
                </thead>
                <?php 
				if(count($arr_data)>0)
				{
					$cnt=1;
					foreach($arr_data as $item)
					{
					if($item['reason']=='1991')
					{
						$retirement_date=$item['emp_retirement_date'];
					}
					else
					{
						$retirement_date=$item['emp_termination_date'];
					}
						 ?>
					<tr>
						<td><?= $cnt?></td>
						<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
						<td><?= date("d-m-Y", strtotime($retirement_date));?></td>
						<td><?= $item['emp_id_const'] ?></td>
                       <td style=' padding-left: 7%;'>
					<a onClick="value_pass('<?php echo $cryptoGraph->encode($item['emp_id_fk'],4); ?>','<?php echo $cryptoGraph->encode('ZP',4); ?>','<?php echo $cryptoGraph->encode($item['flag'],4);?>','<?php echo $cryptoGraph->encode('push',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a>
                    </br>
        <a onClick="value_pass_ack('<?php echo $cryptoGraph->encode($item['emp_id_const'],4); ?>','<?php echo $cryptoGraph->encode('pull',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/download_epension.png"  class="img-responsive" style="width:25%; margin: auto; cursor:pointer; float: left; " /></a></td>
					
                      <td class="view4">
                   <a href=""  id="<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>" data-toggle="modal" data-target="#modal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a>
                    </td>

					
                    <!--<?php if( $_SESSION['user_info']['stake_abbr']=='AEO')
                     {?>
<a style="padding-left:14px;" href="<?= $config['base_url']?>page/intra_zp/aeo/zp_emp_epension/employee_edit_details.php?emp_id=<?= $cryptoGraph->encode($item['emp_id_fk'],4);?>"><img src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" width="25" alt="view" /></a>
					 <? }?>-->
</td>
					</tr>
					<?php $cnt++;
					}
				} 
                else
				{ ?> 
                    <tr>
                    <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
                    </tr>
		   <?php }  ?> 
            </table>
            </table>
        </div>
    <?php } ?>
    
  
    
    
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>

<script>
		$(document).ready(function(){
		$(".view1 a").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/ajax_emp_retirement_error_code.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		
		$(".view2 a").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/ajax_emp_retirement_error_code.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		
		$(".view3 a ").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/ajax_emp_retirement_error_code.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		
		
		
		
		$(".view4 a").click(function() {	
        var link1 = $(this).attr('href');
		var link=$(this).attr('id');
		//alert(link1);
		//alert('<?= $config['base_url'] ?>page/intra_prd/gp/ajax_emp_view.php?id='+link);
		$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/ajax_emp_retirement_error_code.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		});
		
		
		
			function value_pass(emp_id,user,flag,f)
			{
			
			$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/epension_api.php?emp_id='+emp_id+'&user='+user+'&flag='+flag+'&f='+f, function (data) {
			
			alert(data);
			
			false; 
			
			});
			
			
			
			}
			
			function value_pass_ack(emp_id_const,f)
			{
			
			$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/epension_api.php?emp_id_const='+emp_id_const+'&f='+f, function (data) {
			
			alert(data);
			
			false; 
			
			});
			
			
			
			}
			
			
</script>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Error Deatails</h4>
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->




<? require '../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>

