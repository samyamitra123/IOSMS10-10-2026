<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once'../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';     
require_once '../../all_function/fun_store/zp_ps_gp_function.php';
include( '../../all_function/fun_store/ifms_functions.php');


$k=strtotime("first day of last month");
$arr = date("Y-m-d",$k); 
$month_arr=explode('-',$arr);
$salary_monthyear=$month_arr[0].$month_arr[1];
$db = new database();
$crypto = new cryptography();
$fun_store=new zp_ps_gp_class();
$id=$crypto->decode($_GET['id'],4); 
$drn_number= $_GET['drn_no'];
$party_code=substr($drn_number,6,-6); 

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
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
    </script>

<?php

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$Query ="SELECT bill.bill_no,bill.drn_number,bill.response_code,bill.bill_sending_status,sftp.sftp_benf_response_status,sftp.sftp_benf_sending_status,sftp_benf_file_name, bill.block_code, bill.ps_id_fk, bill.zp_id_fk
	from 
	prd_block_bill_details as bill
	left join prd_sftp_benf_upload_response as sftp 
	on bill.block_bill_pk= sftp.bill_id_fk and sftp.active_status='1' 
	where  bill.drn_number='".$drn_number."'";
	//bill.status='1' AND
//print_r($Query); exit;	
$arr=$db->fetch_table($Query);
$billInfo = $arr[0];
if($billInfo['block_code'] !=0)
	{ 
		$Query ="SELECT block_name as name, block_id_pk as id from prd_location_master_block WHERE block_code='".$billInfo['block_code']."'";
		$Label = 'Block: ';
  }
elseif($billInfo['ps_id_fk'] !=0)
{
 	$Query ="SELECT ps_name as name, ps_id_pk as id from prd_location_master_panchayat_samiti WHERE ps_id_pk='".$billInfo['ps_id_fk']."'";
 	$Label = 'Panchyat Samiti: '; 
}
elseif($billInfo['zp_id_fk'] !=0)
{
	$Query ="SELECT district_name as name, district_id_pk as id from prd_location_master_district WHERE district_id_pk='".$billInfo['zp_id_fk']."'"; 
	$Label = 'Zilla Parishad: '; 
}

$district_details = $db->fetch_table($Query);
$district_details = $district_details[0];
//print_r($district_details); exit;
//////////////////////////////////////////////////////////////////////////////


if($party_code=='007')
{
	//$remote_directory_002 = './prd/ps/ePayment_Files_002/'; // changes needed as per department //
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_002/';
	$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file/ifmsps/ePayment_Files_002/';
  	$ip=$config['sftp_ip_ps'];
	$user_id=$config['sftp_user_name_ps'];
	$password=$config['sftp_user_password_ps'];
	
}
else if($party_code=='008') 
{
	//$remote_directory_002 = './prd/zp/ePayment_Files_002/'; // changes needed as per department //
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_002/'; 
	$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file/ifmszp/ePayment_Files_002/';
	$ip=$config['sftp_ip_zp'];
	$user_id=$config['sftp_user_name_zp'];
	$password=$config['sftp_user_password_zp'];
}

else if($party_code=='006') 
{
	//$remote_directory_002 = './prd/zp/ePayment_Files_002/'; // changes needed as per department //
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_002/'; 
	$local_directory_002 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_002/';
	$ip=$config['sftp_ip_gp'];
	$user_id=$config['sftp_user_name_gp'];
	$password=$config['sftp_user_password_gp'];
}

		
if(count($arr)>0)
{	
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib0.3.0');
include('Net/SFTP.php');

	/*$sftp_login=sftp_login($ip,$user_id,$password);
	$ack_file_name_list = $sftp_login->nlist($remote_directory_002);
	if($sftp_login!='0')
	{
		
		if(in_array("ACK".$arr[0]['sftp_benf_file_name'],$ack_file_name_list))
		{
			foreach($ack_file_name_list as $item){
				
			}
			//echo $file_name=(array_values($ack_file_name_list)); die;
			 $file_exist=1; 
			 $file_name=$item;
		}
		else
		{
			 $file_exist=0; 
		}
	}*/
}
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
<?php 
$url = "http://192.168.1.254/epension/bill_check.php";

$data = array (
'drn' => $drn_number
);

$params = '';
foreach($data as $key=>$value)
$params .= $key.'='.$value;

$params = trim($params);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url); //Remote Location URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //Timeout after 10 seconds
//    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_HEADER, 0);

//We add these 2 lines to create POST request
//   curl_setopt($ch, CURLOPT_POST, count($data)); //number of parameters sent
curl_setopt($ch, CURLOPT_POSTFIELDS,"bill_status=" .$params); //parameters data

$result = curl_exec($ch);
curl_close($ch);

//print_r($result);

$xml=simplexml_load_string($result);
/*$array = json_decode(json_encode((array)$xml), true);
$array = array($xml->getName() => $array);
print_r($array);*/
$json  = json_encode($xml);
$configData = json_decode($json, true);

//print_r($configData);

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//'".$_SESSION['user_info']['stake_user']."'
$i=0;

foreach($configData['BILL_ERRORS']['BILL_ERROR_CODE'] as $key=>$val)
		{
    if($i==0){
	$bill_error_code.=$val;
    }else if($i!=0){
     $bill_error_code.=','.$val;
    }
    $i=$i+1;
}
//print_r($bill_error_code) ; die;


	
$bill_status_check=$db->fetch_table("SELECT 
		description,code
	FROM 
		prd_ifms_response_code_master 
		where code in ('".$configData['BILL_STATUS_FLAG']."')
		
	");

$bill_error=$db->fetch_table("SELECT 
		description,code
	FROM 
		prd_ifms_response_code_master 
		where 
		CAST(code as integer) in($bill_error_code)

		
	");


if($bill_status_check)
{
echo $bill_status_check[0]['description']; "<br>";
echo '[';
 foreach($bill_error as $value)
		{
     
	    echo $value['description'].',';
		}
echo ']';
}
else
{
echo 'NO Data Found';
//print_r($data);
}


?>

<div class="row" id="cont">
  <div class="content">
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
      <div class="col-sm-12">
		 <h1 class="heading"> IFMS INTEGRATION STATUS</h1>
		 <h3><?php echo $Label.' '.$district_details['name']; ?></h3>
			<div class="border"></div>
            </br>
            </br>
                <div class="emplist">
                  <div class="school">
                      <div class="table-responsive">
<table width="100%">
<tr>
<th>Bill No.</th>
<th>BILL SUMMARY SENDING</th>
<th>BENEFICIARY FILE SENDING</th>
<?php if($id=='ben_check')
{ ?>
<th>BENEFICIARY FILE NAME</th>
<?php } if($id=='drn_check' || $id=='done_check'){ ?>
<th>.DONE FILE RECIVE</th>
<?php } if($id=='drn_check'){?>
<th>ACK FILE RECIVE</th>
<th>PAYMENT FILE RECIVE</th>
<? }?>
<?php if($id=='ack_check'){?>
<th>IOSMS ACK FILE NAME</th>
<th>IFMS ACK FILE NAME</th>
<? }?>
</tr>
<? if(count($arr)){ foreach($arr as $item)
{?>
<tr>
<td><?= $item['bill_no'];?></td>
<td><?php if($item['bill_sending_status']==2){ echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';}?></td>
<td><?php if($item['sftp _benf_sending_status']==3||$item['sftp_benf_sending_status']==4){echo'<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
<?php if($id=='ben_check')
{ ?>
<td><?php if($item['sftp_benf_sending_status']==4 || $item['sftp_benf_sending_status']==3){echo '<span style="color:green;font-weight:bold">'.$item['sftp_benf_file_name'].'</span>';}else{echo '<span style="color:red;font-weight:bold">N/A</span>';} ?></td>
<?php }else if($id=='drn_check' || $id=='done_check') {?>
<td><?php if($item['sftp_benf_sending_status']==4){echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
<?php } if($id=='drn_check'){?>
<td><?php if($item['sftp_benf_response_status']==5 ||$item['sftp_benf_response_status']==8){echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
<td><?php if($item['sftp_benf_response_status']==8){echo '<span style="color:green;font-weight:bold">YES</span>';}else{echo '<span style="color:red;font-weight:bold">NO</span>';} ?></td>
<? }?>
<?php if($id=='ack_check'){?>
<td><?php if($item['sftp _benf_sending_status']==3||$item['sftp_benf_sending_status']==4){echo '<span style="color:green;font-weight:bold">'."ACK".$item['sftp_benf_file_name'].'</span>';}else{echo '<span style="color:red;font-weight:bold">N/A</span>';} ?></td>
<td><?php if($file_exist==1){echo '<span style="color:green;font-weight:bold">'.$file_name.'</span>';}else{echo '<span style="color:red;font-weight:bold">N/A</span>';} ?></td>
<? } ?>
</tr>
<tr><td colspan="6">
	<?php //if($item['bill_sending_status'] !=2 && $item['bill_sending_status'] !=3){  ?>
     <a href="javascript:void(0)" onClick="DeleteBill('<?php echo $drn_number; ?>');">Delete Bill</a>
  <?php //} ?>

</td></tr>
<? $cnt+=1; }} else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
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
 <div class="clear"></div>






