<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Salary Reports</title>
<link rel="stylesheet" href="template/assets/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="template/assets/css/dataTables.bootstrap4.min.css" type="text/css" />

</head>

<body>
<div class="message"><?php echo $_GET['msg']; ?></div>
<form name="BlockData" action="salary-report.php" method="POST">
<select name="district">
	<option>Select District</option>
<?php foreach($this->district as $district): ?>
   <option value="<?php echo $district['district_id_pk']; ?>" <?php echo ($dis ==  $district['district_id_pk'])?'selected':''; ?> ><?php echo $district['district_name']; ?></option>
<?php endforeach; ?>
   <option value="all">ALL District</option>
</select>
<select name="year">
	<option value="">Select Year</option>
   <?php for($year=2012; $year<=date('Y'); $year++) :?>
   	<option value="<?php echo $year; ?>" <?php echo ($postYear ==  $year)?'selected':''; ?>><?php echo $year; ?></option>
   <?php endfor; ?>
</select>
<select name="month">
	<option value="">Select Month</option>
   <?php foreach($months as $key=>$month) :?>
   	<option value="<?php echo $key; ?>" <?php echo ($key ==  $postMonth)?'selected':''; ?>><?php echo $month; ?></option>
   <?php endforeach; ?>
</select>
<select name="pri_type">
	<option value="">Select Pri Type</option>
   <?php foreach($priType as $key=>$pri) :?>
   	<option value="<?php echo $pri; ?>" <?php echo ($pri ==  $pri_type)?'selected':''; ?>><?php echo $pri; ?></option>
   <?php endforeach; ?>
</select>
<?php //print_r($this->psInfo); ?>
<input type="submit" name="Search" value="Search">
<hr />
<?php echo ($this->blockSalary != '')?json_encode($this->blockSalary):'No Data Found'; ?>
</body>
<script>
	function callAllBlocks(obj)
	  {
	  	window.location.href = 'salary-report.php?dis='+(obj.value);
	  }
  
</script>


<script src="template/assets/js/jquery-3.5.1.js"></script>
<script src="template/assets/js/jquery.dataTables.min.js"></script>
<script src="template/assets/js/dataTables.bootstrap4.min.js"></script>
<script src="template/assets/js/bootstrap.min.js"></script>

</html>