<?php
echo "Currently Not available";
exit;
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
//ini_set('display_errors',1);
header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');
$db = new database();
include( 'ngipfFunction.php');
$crypto = new cryptography();
// Data Initialization Sector....
$emp_id_pk = $crypto->decode($_GET['emp_id_pk'],4);
$stake_abbr = $_SESSION['user_info']['stake_abbr'];
$block_code = $_SESSION['location']['block_code'];
$ps_id = $_SESSION['location']['ps_id'];

if(isset($_POST['saveemployee']))
{
  $post = $_POST;
  $emp_id_pk = $post['emp_id_pk'];
  unset($post['emp_id_pk']);
  unset($post['saveemployee']);
  $Query = array();
  foreach($post as $key=>$value)
  {
    if($value != '')
      $Query[] = $key." = '".$value."'";
  }
  $Query = 'UPDATE prd_employee_master_ngipf SET '.implode(', ',$Query)." WHERE emp_id_pk=".$emp_id_pk;
  //print($Query); exit;
  $db->update($Query);
}
//var_dump($emp_id_pk); die;


$employee_dtls = $db->fetch_table("select * from prd_employee_master_ngipf WHERE emp_id_pk ='".$emp_id_pk."' ORDER BY emp_id_pk ASC");
$employee_dtls = $employee_dtls[0];
//print_r($employee_dtls); exit;
$emp_id_const = $employee_dtls['emp_id_const'];

// Check If Employee Already In NGIPF..
$Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$emp_id_const."' AND status=1";
$prevEmployeeDetails = $db->fetch_table($Query);
//print_r($prevEmployeeDetails); exit;
$status="I";
if(count($prevEmployeeDetails) > 0)
  {
      $status="M";
      $pfaccno = $prevEmployeeDetails[0]['pfaccno'];
      $prevEmployeeDetails = $prevEmployeeDetails[0]['pnrd_request'];
      
      
  }
  //print_r($prevEmployeeDetails); exit;
  $prevEmployeeDetails = json_decode($prevEmployeeDetails);
  $prevEmployeeDetails = $prevEmployeeDetails->req->empDtls;

  $spouseDtls = $prevEmployeeDetails->spouseDtls[0];

  unset($prevEmployeeDetails->spouseDtls);
  $prevEmployeeDetails->spouseDtls = $spouseDtls;
 
  $workDtls = $prevEmployeeDetails->workDtls[0];
  unset($prevEmployeeDetails->workDtls);
  $prevEmployeeDetails->workDtls = $workDtls;
 
  $payInfoDtls = $prevEmployeeDetails->payInfoDtls[0];
  unset($prevEmployeeDetails->payInfoDtls);
  $prevEmployeeDetails->payInfoDtls = $payInfoDtls;
  //print_r($prevEmployeeDetails->payAllowDtls); exit; 
  $payAllowDtls = $prevEmployeeDetails->payAllowDtls[0];

  unset($prevEmployeeDetails->payAllowDtls);
  $prevEmployeeDetails->payAllowDtls = $payAllowDtls;

  
  
  foreach($prevEmployeeDetails as $key=>$empBlock)
  {
      echo "<div class='each-block'><label><h2>".$key."</h2></label>";
      foreach($empBlock as $field => $empDetails)
      {
        echo '<p><b>'.$field .'</b>:'.$empDetails.'</p>';
      }
      echo "</div>";
  }

 // print_r($prevEmployeeDetails); exit;
  //print_r($_SERVER); 
 ?>

<div class="edit-box">
  <h4>Update Employee</h4>
  <form name="updateEmployee" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
  <ul>
    <?php 
    foreach($employee_dtls as $key=>$emp):?>
      <li>
        <label><?php echo strtoupper(str_replace('_', ' ', $key)); ?></label>
        <input type="text" name="<?php echo $key?>" value="<?php echo $emp; ?>">
      </li>
    <?php endforeach;?>   
  </ul>
  <center><input type="submit" name="saveemployee" value="SaveEmployee" /></center>
  </form>
</div>  


 <style type="text/css">
   .edit-box h4 {margin: 10px;}
   .edit-box ul {width: 100%;}
   .edit-box ul li {width: 30%; display: inline-grid; margin: 10px; }
   .edit-box ul li label{display: block;}
   .edit-box ul li input[type='text']{width: 100%;}
   .each-block{display: inline-block;
    width: 45%;
    border: 1px solid #c6c6c6;
    margin: 1%;
    padding: 10px;
    max-height: 400px;
    height: 400px; 
    overflow-y: scroll;}
 </style>