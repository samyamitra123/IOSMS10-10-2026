<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once 'ngipfsalary.class.php';
$redirectUrl = $_SERVER['HTTP_REFERER'];
$crypto = new cryptography();
$Ngipf = new NGIPFSALARY_API();
global $db,$ngipfStatus;
$db = new database();
/*$Query = "UPDATE prd_employee_master
SET gpf_acc_no = (
    SELECT pfaccno
    FROM prd_gpf_request_master
    WHERE prd_gpf_request_master.emp_id_const = prd_employee_master.emp_id_const
    AND prd_gpf_request_master.status = 1
)
WHERE EXISTS (
    SELECT 1
    FROM prd_gpf_request_master
    WHERE prd_gpf_request_master.emp_id_const = prd_employee_master.emp_id_const
    AND prd_gpf_request_master.status = 1
);
";
$db->update($Query);*/

$Query = "SELECT subscriber_id, drn_number from prd_gpf_subscriber_master WHERE ifms_ref_no = '0' LIMIT 100 OFFSET 0";
$subscriber = $db->fetch_obj($Query);
foreach($subscriber as $item)
{
                   $data = array (
                          'drnNumber' =>$item->drn_number
                       );
                   $get_data = $Ngipf->StatusCheckAPI('GET', 'https://wbifms.gov.in/webfd/billStatus.html', $data);
                   $BillData =simplexml_load_string($get_data);
                   //print_r($BillData); exit;
                   $ifmsRefNo = $BillData->IFMS_REF_NO; 
                   $Query = "update prd_gpf_subscriber_master SET ifms_ref_no='".$ifmsRefNo."' WHERE subscriber_id=".$item->subscriber_id;
                   $db->update($Query);     
}
$Query = "SELECT COUNT(subscriber_id) as subscribercount from prd_gpf_subscriber_master WHERE ifms_ref_no = '0'";
$subscriberCount = $db->fetch_obj($Query);
echo "Done Left:".$subscriberCount[0]->subscribercount;
?>