<?php 
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db=new database();	
    $post = $_POST;
    $masterpass = 'prd!@#123^';
    if(isset($post['checkemployee']) && $post['employeeId'] != '' && $post['masterpass'] != '')
    {
         if($post['checkemployee'] == $masterpass)
         {  
         	$Query = "select * from prd_employee_master WHERE emp_id_const='".$post['employeeId']."'";
         	$employeeDetails = $db->fetch_table($Query);
         }
    }
?>
<html>
<head>
<title>Change Employee Status</title>
<style>
	.container{width: 360px;
    margin: 20px auto;
    border: 1px solid #c6c6c6;
    border-radius: 10px;
    padding: 20px;}
    label, input{display: block; margin: 10px}
    input[type=submit]{background-color: #0a19cb;
    color: #fff;
    width: 150px;
    height: 30px;
    border: 0px;
    border-radius: 3px;
    font-weight: 700;}
</style>
</head>
<body>
	<?php if(!isset($post['checkemployee'])){ ?>
	<div class="container">
        <form name="checkEmployee" action="" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId"placeholder="Enter Employee ID">

		<label>Enter Master Password</label>
		<input type="password" value="" name="masterpass">
        <input type="hidden" name="emp_account" value="0">
		<input type="submit" name="checkemployee" value="Check Employee">
	   </form>
	</div>
    <?php } else { 

        print_r($employeeDetails); exit;
    	?>
	<div class="container">
		<label>Enter Employee ID</label>

		<input type="password" value="" name="masterpass" autocomplete="off">
		<input type="submit" name="activateemployee" value="Activate Employee">
	</div>    	
    <?php } ?>

</body>
</html>