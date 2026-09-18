<?php 
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

$db=new database();
$post = $_GET;
//print($post['type']); exit;
//$monthYear = date('Ym');
$monthYear = (isset($_GET['month']) && $_GET['month'] != '')?'2025'.$_GET['month']:date('Ym');
//print($monthYear); exit;
switch ($post['type']) {
	    case 'ps':
	    $Query = "SELECT ps_id_pk as id ,ps_name as name from prd_location_master_panchayat_samiti WHERE ps_code=".$post['login_id'];
	    $LoginData = $db->fetch_table($Query);
	    $LoginData = $LoginData[0];
	   // print_r($LoginData); exit
	    if($LoginData['id']>0)
	    {
	    	$Query = "SELECT emp_id_fk, oid from prd_employee_salary_save WHERE ps_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."' order by oid asc";
	    	//print_r($Query); exit;
	    	$SalaryLastData = $db->fetch_table($Query);
	    	$NewData = array();
	    	foreach($SalaryLastData as $slData)
	    	{
	    		$NewData[$slData['emp_id_fk']] = "'".$slData['oid']."'";
	    	}
	    	if(in_array($post['action'], array('lock','unlock','ulock')))
	    	{
	    		$Query = "UPDATE prd_employee_salary_save SET status_flag='0', delete_status='0' WHERE  ps_id_fk=".$LoginData['id']." AND salary_monthyear='".$monthYear."'";
	    		$db->update($Query);
	    		//print_r($Query); exit;
	    	}

	    	//print_r($NewData); exit;
		
	    	//print($Query); exit;
	    }
	    //print_r($LoginData);
		// code...
		break;
	    case 'zp':
	    $Query = "SELECT district_id_pk as id ,district_name as name from prd_location_master_district WHERE district_code=".$post['login_id'];

	    //print_r($Query); exit;
	    $LoginData = $db->fetch_table($Query);
	    $LoginData = $LoginData[0];
	    if($LoginData['id']>0)
	    {
	    	//$Query = "SELECT DISTINCT(emp_id_fk), oid from prd_employee_salary_save WHERE zp_id_fk=".$LoginData['id']." AND salary_monthyear='".$monthYear."'";
	    	$Query = "SELECT emp_id_fk, oid from prd_employee_salary_save WHERE zp_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."' order by oid asc";
	    	//print($Query); exit;
	    	$SalaryLastData = $db->fetch_table($Query);
	    	$NewData = array();
	    	foreach($SalaryLastData as $slData)
	    	{
	    		$NewData[$slData['emp_id_fk']] = "'".$slData['oid']."'";
	    	}
	    	if(in_array($post['action'], array('lock','unlock','ulock')))
	    	{
	    		$Query = "UPDATE prd_employee_salary_save SET status_flag='0', delete_status='0' WHERE  zp_id_fk=".$LoginData['id']." AND salary_monthyear='".$monthYear."'";
	    		$db->update($Query);
	    		//print_r($Query); exit;
	    	}	    	

	    }

		// code...
		break;	
	    case 'block':
	    $Query = "SELECT block_id_pk as id, block_code ,block_name as name from prd_location_master_block WHERE block_code=".$post['login_id'];
	    $LoginData = $db->fetch_table($Query);
	    $LoginData = $LoginData[0];
	    if($LoginData['id']>0)
	    {
	    	//$Query = "SELECT DISTINCT(emp_id_fk), oid from prd_employee_salary_save WHERE block_code='".$LoginData['block_code']."' AND salary_monthyear='".$monthYear."'";
	    	$Query = "SELECT emp_id_fk, oid from prd_employee_salary_save WHERE block_code='".$LoginData['block_code']."' AND salary_monthyear='".$monthYear."' order by oid asc";
	    	//print_r($Query); exit;
	    	$SalaryLastData = $db->fetch_table($Query);
	    	$NewData = array();
	    	foreach($SalaryLastData as $slData)
	    	{
	    		$NewData[$slData['emp_id_fk']] = "'".$slData['oid']."'";
	    	}
	    	if(in_array($post['action'], array('lock','unlock','ulock')))
	    	{
	    		$Query = "UPDATE prd_employee_salary_save SET status_flag='0', delete_status='0' WHERE block_code=".$LoginData['block_code']." AND salary_monthyear='".$monthYear."'";
	    		$db->update($Query);
	    		//print_r($Query); exit;
	    	}		    	
	    }

		// code...
		break;		
	    case 'gp':
	    $Query = "SELECT gp_id_pk as id, gp_code ,gp_name as name from prd_location_master_gp WHERE gp_code=".$post['login_id'];
	    $LoginData = $db->fetch_table($Query);
	    //print_r($LoginData);exit;
	    $LoginData = $LoginData[0];
	    if($LoginData['id']>0)
	    {
	    	//$Query = "SELECT DISTINCT(emp_id_fk), oid from prd_employee_salary_save WHERE gp_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."'";
	    	$Query = "SELECT emp_id_fk, oid from prd_employee_salary_save WHERE gp_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."' order by oid asc";
	    	$SalaryLastData = $db->fetch_table($Query);
	      //print_r($Query); exit;
	    	$NewData = array();
	    	foreach($SalaryLastData as $slData)
	    	{
	    		$NewData[$slData['emp_id_fk']] = "'".$slData['oid']."'";
	    	}
	    	if(in_array($post['action'], array('lock','unlock','ulock')))
	    	{
	    		$Query = "UPDATE prd_employee_salary_save SET status_flag='0', delete_status='0' WHERE gp_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."'";
	    		$db->update($Query);
	    		//print_r($Query); exit;
	    	}		    	

	    }

		// code...
		break;	
	

}
	    	if($post['action'] == 'unlock')
	    	{
	    		if(count($NewData) > 0)
	    		{

		    		$Query = "UPDATE prd_employee_salary_save SET status_flag='1', delete_status='1' WHERE oid IN (".implode(', ',$NewData).")";
		    		//print_r($Query); exit;
		    		//zp_id_fk='".$LoginData['id']."' AND salary_monthyear='".$monthYear."'";
		    		$db->update($Query);
		    		$msg = "Bill Unlocked Successfully";
	    	  }
	    		//print_r($Query);
	    	}
	    	if($post['action'] == 'lock' || $post['action'] == 'ulock' || $post['action'] == 'slock')
	    	{
	    		//print_r($NewData); exit;
	    		if(count($NewData) > 0)
	    		{	   
	    		  $statusflag = ($post['action'] == 'lock')?4:2;  
	    		  $statusflag = ($post['action'] == 'slock')?3:$statusflag; 

		    		$Query = "UPDATE prd_employee_salary_save SET status_flag='".$statusflag."', delete_status='1', is_saved=1 WHERE oid IN (".implode(', ',$NewData).")";
		    		$db->update($Query);
		    		//$Query = "UPDATE prd_monthly_salary_archive_final SET status_flag='4', delete_status='1', is_saved=1 WHERE oid IN (".implode(', ',$NewData).")";
		    		//$db->update($Query);	    		
		    		$msg = "Bill Locked Successfully";
	    	 }
	    		//print_r($Query);
	    	}	    

    	 $Query = "SELECT emp.emp_first_name, emp.emp_second_name, emp.emp_last_name,emp.gpf_acc_no,empss.oid,empss.* from prd_employee_salary_save as empss
            LEFT JOIN prd_employee_master as emp ON empss.emp_id_fk = emp.emp_id_pk
	    	WHERE empss.oid IN (".implode(', ',$NewData).")";
	    	$SalaryData = $db->fetch_table($Query);
//print_r($Query);
//print_r($SalaryData); 
	    	$grossSalary = 0;
	    	$netSalary = 0;
	    	$sl = 1;
?>
<div class="row" id="cont">
  <div class="content">
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
      <div class="col-sm-12">
		 <h1 class="heading"> Unlock Bill</h1>
		 <h3><?php echo $Label.' '.$LoginData['name']; ?></h3>
		 <h4><?php echo $msg; ?></h4>
			<div class="border"></div>
            </br>
            </br>
                <div class="emplist">
                  <div class="school">
                      <div class="table-responsive">
<table>
	<thead>
		<th>Sl.</th>
		<th>Name</th>
		<th>Status Flag</th>
		<th>Delete Status</th>
		<th>Basic</th>
    <th>DA</th>
    <th>HRA</th>
    <th>MA</th>
    <th>CONV ALLOW</th>    
    <th>HILL AllOW</th>    
    <th>GROSS SALARY</th>    
    <th>GPF</th>    
    <th>PF LOAN RECOVERY</th>    
    <th>PTax</th>    
    <th>GSLI</th>    
    <th>FESTIVAL ADVANCE RECOVERY</th>  
    <th>Net Salary</th>
    <th>Action</th>       
	</thead>
	<?php foreach($SalaryData as $value):?>
		<tr class="sal<?php echo $value['oid']; ?>">
			<td><?php echo $sl; ?></td>
			<td>
				<?php echo $value['emp_first_name'].' '.$value['emp_second_name'].' '.$value['emp_last_name']; ?>
			  <p>(<?php echo $value['emp_id_fk']; ?>)</p>
			  <p>(<?php echo $value['gpf_acc_no']; ?>)</p>
		  </td>
			<td><?php echo $value['status_flag']; ?></td>
			<td><?php echo $value['delete_status']; ?></td>
			<td><?php echo $value['basic']; ?></td>
			<td><?php echo $value['da']; ?></td>
			<td><?php echo $value['hra']; ?></td>
			<td><?php echo $value['ma']; ?></td>
			<td><?php echo $value['conv_allow']; ?></td>
			<td><?php echo $value['hill_allowance']; ?></td>
			<td><?php echo $value['gross_salary']; ?></td>
			<td><?php echo $value['gpf']; ?></td>
			<td><?php echo $value['pf_loan']; ?></td>
			<td><?php echo $value['p_tax']; ?></td>
			<td><?php echo $value['gsli']; ?></td>
			<td><?php echo $value['festival_loan']; ?></td>
			<td><?php echo $value['net']; ?></td>
			<td><a href="javascript:DeleteRow('<?php echo $value['oid']; ?>')">Del</a></td>
		</tr>
    <?php 
 	    	$grossSalary += $value['gross_salary'];
	    	$netSalary += $value['net'];   
	    	$sl++;
  endforeach; ?>
     <tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $grossSalary; ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $netSalary ?></td>
			<td></td>     	
     </tr>
    <tr>
    	<td colspan="3">
    		<input type="button" name="Unlock" value="unlock" onclick="onlockSalary('<?php echo $post['login_id']; ?>', '<?php echo $post['type']?>', 'unlock');">
    		<input type="button" name="lock" value="lock" onclick="onlockSalary('<?php echo $post['login_id']; ?>', '<?php echo $post['type']?>','ulock');">
    		<input type="button" name="lock" value="Salary Lock" onclick="onlockSalary('<?php echo $post['login_id']; ?>', '<?php echo $post['type']?>','slock');">    		
    		<input type="button" name="lock" value="Final Lock" onclick="onlockSalary('<?php echo $post['login_id']; ?>', '<?php echo $post['type']?>','lock');">
    	</td>
    </tr>
</table>
            </div>
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
 <div class="clear"></div>