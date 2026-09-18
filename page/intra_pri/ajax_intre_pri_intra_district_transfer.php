
<?php
//echo 11;die;
session_start();
error_reporting(0);
  
ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
//echo 23;die;
 if($_SERVER['HTTP_REFERER']==''){
 	header("Location:dashboard_intra_pri.php");
}
// else{
// 	header("Location:dashboard_intra_pri.php");
// }

//$cryptoGraph=new cryptography();

//echo 225;die;

  $tch_emp_id = $_POST['tch_emp_id'];
  $tch_fname = $_POST['tch_fname'];
  $tch_mname = $_POST['tch_mname'];
  if($tch_mname==""){$tch_mname1="null";}else{ $tch_mname1= $tch_mname;} 
  $tch_lname =  $_POST['tch_lname'];
  $tch_dob = $_POST['tch_dob'];
  $drpSex = $_POST['drpSex'];
  $vice_desig = $_POST['vice_desig'];
  $Name_of_GP_or_PS_posted_id = $_post['Name_of_GP_or_PS_posted_id'];
  $Appointment_MEMO = $_POST['Appointment_MEMO'];
  $Date_of_first_Posting = $_POST['Date_of_first_Posting'];
  $Appointment_Authorization = $_POST['Appointment_Authorization'];
  $date_of_notification_employee_notice = $_POST['date_of_notification_employee_notice'];
  
  
  	var_dump($Name_of_GP_or_PS_posted_id);die;
  	//var_dump($date_of_notification_employee_notice);die;

echo "INSERT INTO intra_pri_9008
						( posting,
							emp_first_name,
							emp_second_name,
							emp_last_name,
							emp_dob
							)
							VALUES
								('".$tch_emp_id."',
								'".$tch_fname."',
								'".$tch_mname1."',
								'".$tch_lname."',
								'".$tch_dob."',
								'".$drpSex."',
								'".$vice_desig."',
								'".$Name_of_GP_or_PS_posted_id."',
								'".$Appointment_MEMO."',
								'".$Date_of_first_Posting."',
								'".$Appointment_Authorization."',
								'".$date_of_notification_employee_notice."')";die;
  

?>