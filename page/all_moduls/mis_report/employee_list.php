<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

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


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";


//Self variable


//---------------------------------- HEADER -----------------------------------------------------------------------------------
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
 $user_id=$_SESSION['user_info']['stake_user']; 
$db=new database();

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
    <!--<h2>WELCOME TO EMPLOYEE LOGIN</h2>-->
	<?php 
	$db = new database();
	$emp_name = $db->fetch_table("
                                SELECT 
								emp_first_name, emp_second_name, emp_last_name
								FROM prd_employee_master 
                                WHERE emp_id_const = '".$_SESSION['user_info']['stake_user']."'
                                ");
	//var_dump($emp_name);
	?>
	<h2><b>WELCOME <?php echo $emp_name[0]["emp_first_name"].' '.$emp_name[0]["emp_second_name"].' '.$emp_name[0]["emp_last_name"]; ?></b></h2>
	<h3>EMPLOYEE ID: <?php echo $_SESSION['user_info']['stake_user']; ?></h3>
    </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EMPLOYEE DETAILS </h1>
<div class="border"></div>
<div class="emplist" style="width:98%">
<div class="school">
	
    

        
            <?php 
			
			
			
			 function fun_month($month_code){
	 
	 //echo $month_code; die;
	 $month_array=array("01"=>"January","02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July" , "08"=>"August" ,"09"=>"September", "10"=>"October" ,"11"=>"November", "12"=>"December");
	foreach ($month_array as $key=>$value) {
		if($key == $month_code){
				return $value;
				break;
		}

	}
 } 
            $db=new database();
           //$user_id='PE2016014359';
		   $user_id=$_SESSION['user_info']['stake_user'];
		 
            $arr_data = $db->fetch_table("SELECT 
								emp.emp_id_pk,
								emp.emp_id_const,
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name ,
								final.salary_monthyear
								FROM prd_employee_master emp
								INNER JOIN prd_monthly_salary_archive_final as final ON final.emp_id_fk=emp.emp_id_pk 
								WHERE  emp.emp_id_const in('" .$user_id. "') 
								order by final.archive_final_pk DESC LIMIT 1
								
								" );	
								

						
											
            ?>
            <div class="table-responsive">
            <table width="100%">
                <tr style="background-color: rgb(221, 247, 255);">
                    <th>Sl No.</th>
                    <th>Employee Name</th>
                    <th>Employee Id</th>
                    <th>Salary Last Pay</th>
                    <th>Download</th>
                    <!--<th>Action</th>-->
                    
                    
                </tr>
                <?php 
				if(count($arr_data)>0)
				{
					$cnt=1;
					//echo count($item); die;
					foreach($arr_data as $item)
					{ 
					
			/*if($item['emp_pension_status']=='0')
			{
			$status='<span style="color:#660066;font-weight:bold">PROFILE  NOT SENT</span>';
			}
			else if($item['emp_pension_status']=='2')
			{
			$status='<span style="color:green;font-weight:bold">PROFILE SENT SUCCESS</span>';
			}
			
			else if($item['emp_pension_status']=='3') 
			{
			$status='<span style="color:RED;font-weight:bold">EMPLOYEE DATA ERROR</span>';
			}*/

					/*if($item['reason']=='1991')
					{
						$retirement_date=$item['emp_retirement_date'];
					}
					else
					{
						$retirement_date=$item['emp_termination_date'];
					}*/
					
									
$salary_monthyear=$item['salary_monthyear'];
 
 $last_month=substr($salary_monthyear,-2);
 $last_year=substr($salary_monthyear,0,4);

								
					
					
				?>
                <tr>
                    <td><?= $cnt?></td>
                    <td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name'] ?></td>
                     <td><?= $item['emp_id_const'] ?></td>
                      <td><?= fun_month($last_month).', '. $last_year ?></td>
                    
                   
                     <td class="view1"><a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-bs-toggle="modal" data-bs-target="#modal">
<img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>

                              
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
		$.post('<?= $config['base_url'] ?>page/all_moduls/mis_report/ajax_emp_view.php?id='+link, function(data){
			//alert(data);
				  $(".mbody").html(data);
			  	});
		});
		});
	
		
</script>


<!-----------------------------------------------------------------MODAL END---------------------------------------------------->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
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
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->


<? require '../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>

