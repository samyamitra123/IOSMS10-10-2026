<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
session_start();

require '../includes/config/config.php';
require '../includes/config/database.config.php';
require '../includes/library/database.class.php';
require '../includes/library/cryptography.class.php';

 $treasury_code_data=$_POST['id1']; 
if($treasury_code_data=="")
{
	echo '<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Treasury Code.</strong></div>';
}
else
{
 
 $db=new database();
 $treasury_code=$db->update("UPDATE psemp_ps_profile set
												treasury_code='$treasury_code_data'	
											 WHERE
												 ps_id_fk='".$_SESSION['location']['ps_id']."'");
												 
 $check=$db->fetch_table("SELECT treasury_code FROM psemp_ps_profile WHERE ps_id_fk='".$_SESSION['location']['ps_id']."'");
			
		if(count($treasury_code)!=0)	
			{
				
				 echo '<div class="alert alert-success" style="text-align:center;"><strong>Treasury Code Submitted Successfully...</strong></div>';
				
			}
			else
			{
				echo '<div class="alert alert-danger" style="text-align:center;"><strong>Treasury Code Submission Fails!!!!!</strong></div>';
			}
			

}


?>
