<?

$key3="basicDtls";
$basicDtls = array("empNm"=>35, "empId"=>37, "gender"=>43,"religion"=>43,"dob"=>43,
"doj"=>43,
"isActive"=>43,"salBillPrep"=>43,"retDt"=>43,"gpfCpf"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$basicDtls_array= array( $key3=> $basicDtls);
//echo $basicDtls_array_jeson=json_encode($basicDtls_array);
//$key1 => $basicDtls






$key4="otherDtls";
$otherDtls = array("empType"=>35, "mobile"=>37, "email"=>43,"aadhaar"=>43,"pan"=>43,
"maritalStatus"=>43,
"oldGpfAccNo"=>43,"cpfRefDt"=>43,"cpfRefTresNm"=>43,"cpfRefAmt"=>43,"partyCode"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$otherDtls_array= array( $key4=> $otherDtls);
 //echo $otherDtls_array_jeson=json_encode($otherDtls_array);
//$key1 => $basicDtls



$key5="relationDtls";
$relationDtls = array("fatherNm"=>35, "motherNm"=>37, "spouseNm"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
 $relationDtls_array= array( $key5=> $relationDtls);
//echo $relationDtls_array_jeson=json_encode($relationDtls_array);
//$key1 => $basicDtls


$key6="addrDtls";
$addrDtls = array("presStreet"=>35, "presCity"=>37, "presDist"=>43,"presState"=>43,"presPin"=>43,
"permStreet"=>43,
"permCity"=>43,"permDist"=>43,"permState"=>43,"permPin"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$addrDtls_array= array( $key6=> $addrDtls);
 //echo $addrDtls_array_jeson=json_encode($addrDtls_array);
 
 
$key7="exitSerDtls";
$exitSerDtls = array("terType"=>35, "terDt"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$exitSerDtls_array= array( $key7=> $exitSerDtls);
 //echo $exitSerDtls_array_jeson=json_encode($exitSerDtls_array);
 
 
 $key8="payInfoDtls";
$payInfoDtls = array("ropa"=>35, "ropaWef"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$payInfoDtls_array= array( $key8=> $payInfoDtls);
 //echo $payInfoDtls_array_jeson=json_encode($payInfoDtls_array);
 
 
 $key9="payAllowDtls";
$payAllowDtls = array("basicPay"=>35, "basicPayWef"=>37, "gradePay"=>37, "gradePayWef"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$payAllowDtls_array= array( $key9=> $payAllowDtls);
 //echo $payAllowDtls_array_jeson=json_encode($payAllowDtls_array);


$key10="benfDtls";
$benfDtls = array("ifsc"=>35, "accNo"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$benfDtls_array= array( $key10=> $benfDtls);
 //echo $benfDtls_array_jeson=json_encode($benfDtls_array);




$all_array= array( $key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key6=> $addrDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls);
 echo $all_array_jeson=json_encode($all_array);

die;






session_start();

require 'includes/config/config.php';
require 'includes/config/database.config.php';
require 'includes/library/database.class.php';
require 'includes/library/cryptography.class.php';





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
require 'page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require 'page/layout/menu.php';
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
<? require 'page/common_back_btns.php'; ?>
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

<a onClick="value_pass_test('<?php echo $cryptoGraph->encode($item['emp_id_fk'],4); ?>','<?php echo $cryptoGraph->encode('GP',4); ?>','<?php echo $cryptoGraph->encode($item['flag'],4);?>','<?php echo $cryptoGraph->encode('push',4); ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_epension.png"  class="img-responsive" style="width:10%; margin: auto; cursor:pointer; float: left; " /></a>
	
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>

<script>
	
			
				function value_pass_test(finYear,stateCode,trainingName,status)
			{
			//alert(11);
			//return false;
			$.post('<?= $config['base_url'] ?>page/all_moduls/ePension/test_api.php?finYear='+finYear+'&stateCode='+stateCode+'&trainingName='+trainingName+'&status='+status, function (data) {
			
			alert(data);
			
			false; 
			
			});
			
			
			
			}
			
			
			
			
</script>





 <? require 'page/layout/footer.php' ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>

