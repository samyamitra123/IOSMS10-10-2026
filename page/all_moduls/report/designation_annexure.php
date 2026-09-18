<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$crypto = new cryptography();

	$db  = new database();

	$arr=$db->fetch_table("SELECT designation_name
	from 
	zpemp_emp_desig_master 
	where status='1'
	order by designation_name");
	
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');


include ('../../../includes/third-party/mpdf/mpdf.php');
$stylesheet='';
$mpdf=new mPDF();

$mpdf->WriteHTML($stylesheet,1);
	
		$mpdf->WriteHTML('
		
			<h3 align="center"><u>DESIGNATION FOR ZILLA PARISHAD EMPLOYEES</u></h3>
		<br>
		<table width="100%" style="text-align: center;border: 1px solid;">
		<tr style="border:1px solid;"> 
			<th scope="col"  width="50" align="center" style="text-align: center;font-size:13px;">SL.NO.</th>
			<th scope="col" width="350" align="center" style="text-align: center;font-size:13px;">DESIGNATION NAME</th>
		</tr>');?>
	  <? $cnt=1;if(count($arr)){

		  
		foreach($arr as $key)
		{
			$designation=$key['designation_name'];
		
	 $mpdf->WriteHTML('<tr>
		<td  style="font-size:10px;">'.$cnt.'</td>
        <td  style="font-size:10px;">'.$designation.'</td>
	       </tr>');?>
	  <? $cnt++; }} else {?>
       <? $mpdf->WriteHTML('<tr>
        <td colspan="2">No Data Found</td>
        </tr>');?>
        <? } ?>
	<?php $mpdf->WriteHTML('</table>');
    $mpdf->Output('ZP DESIGNATION.pdf','D');?>
    

