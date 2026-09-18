<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
require '../../../page_visite.php';

$cryp = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
				$db = new database();
		if($_GET['circle_id']){
				pg_query("begin"); 
				/*$dise_arr = $db->fetch_table("select 
				distinct(s.school_dise_code) as dise
				from 
					ehrms_dise_location_master_school s
				inner join ehrms_dise_teacher_primary p
				on s.school_dise_code=p.schcd and p.status=1
				and s.circle_id_fk ='".$_GET['circle_id']."'");
				foreach($dise_arr as $key){*/
				$upd = $db->update("
				UPDATE ehrms_teacher_salary_save_primary
					SET status_flag = 2
				WHERE
					circle_code = '".$_SESSION['user_info']['stake_user']."'
					AND status_flag = 1 AND category_id=1;
				");
				//}
				if($upd){
					pg_query("commit"); 
					$_SESSION['msg']= '<div id="sucess">Salary requisitions has been sent to DPSC successfully.</div>';
					header('Location:../school_salary_list.php');
					exit(0);
				}else{
					pg_query("rollback"); 
					$_SESSION['msg']= '<div id="error">Something going wrong. Please try again.</div>';
					header('Location:../school_salary_list.php');
					exit(0);
				}
		}else{
			$_SESSION['msg']= '<div id="error">Something going wrong. Please try again.</div>';
			header('Location:../school_salary_list.php');
			exit(0);
		}

?>