<?php 
require '../../../includes/config/config.php';
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
<script src="<?php echo $config['base_url']; ?>themes/default/js/jquery-3.6.0.min.js"></script>
</head>
<body>
	<div class="container employee-details">
		<h3>Activate Pension Employee.</h3>
        <form name="checkEmployee" action="employee.php" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId" id="employeeId" placeholder="Enter Employee ID">
    	<input type="submit" name="checkemployee" id="checkemployee" value="Check Employee">
	   </form>
	</div>
    <br /><br /><br />
	<div class="container employee-pension-details">
		<h3>Check Employee Pension Status</h3>
        <form name="checkEmployee" action="employee.php" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId" id="pemployeeId" placeholder="Enter Employee ID">
    	<input type="submit" name="checkemployeepension" id="checkemployeepension" value="Check Employee">
	   </form>
	</div>
	<br /><br /><br />
	<div class="container employee-termination-details">
		<h3>Update Employee Details</h3>
        <form name="updateEmployee" action="employee.php" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId" id="temployeeId" placeholder="Enter Employee ID">
		<input type="date" name="employeeTermination" id="employeeTermination" placeholder="Termination Date">
    	<input type="submit" name="updateemployee" id="updateemployee" value="Update Termination Employee">
	   </form>
	</div>
	<br /><br /><br />
	<div class="container employee-festival-advance-details">
		<h3>Employee Festival Advance Status</h3>
        <form name="updateEmployee" action="employee.php" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId" id="festemployeeId" placeholder="Enter Employee ID">
		
    	<input type="submit" name="festivaladvemployee" id="festivaladvemployee" value="Festival Advance Check">
	   </form>
	</div>
	<div class="container employee-ap-details">
		<h3>Employee Increment Promotion Reset</h3>
        <form name="updateEmployee" action="" method="POST">

		<label>Enter Employee ID</label>
		<input type="text" name="employeeId" id="apemployeeId" placeholder="Enter Employee ID">
		
    	<input type="submit" name="apemployee" id="apemployee" value="Reset">
	   </form>
	</div>
	<div class="container employee-ngipf-details">
		<h3>Employee NGIPF Check</h3>
        <form name="updateEmployee" action="" method="POST">

		<label>Enter Employee ID</label>
		  <input type="text" name="employeeId" id="ngipfemployeeId" placeholder="Enter Employee ID">
		  <select name="reqtype" id="reqtype">
		  	<option value="main">Main</option>
		  	<option value="sub">Sub</option>
		  </select>
    	<input type="submit" name="ngipfemployee" id="ngipfemployee" value="Search">
	   </form>
	</div>		 
</body>
<script type="text/javascript">
$(document).ready(function()
{

$('#ngipfemployee').click(function(e){
	      e.preventDefault();
	      var reqtype = $('#reqtype').val();
	      console.log(reqtype);
			  $.ajax({
				type: 'POST',
				url: 'incemployee.php',
				data: {employeeId: $('#ngipfemployeeId').val(),action:'ngipfview',reqtype:reqtype},
				success: function(data)
				{
				   $(".employee-ngipf-details").html(data);
				}
				});
	});

$('#apemployee').click(function(e){
	      e.preventDefault();
			  $.ajax({
				type: 'POST',
				url: 'incemployee.php',
				data: {employeeId: $('#apemployeeId').val(),action:'show'},
				success: function(data)
				{
				//location.reload(); 
					//console.log(data);
				$(".employee-ap-details").html(data);
				}
				});
	});

	$('#updateemployee').click(function(e){
	e.preventDefault();
	console.log($('#employeeTermination').val());
    $.ajax({
	type: 'POST',
	url: 'employee_submit.php',
	data: {employeeId: $('#temployeeId').val(),empTermination:$('#employeeTermination').val(), updatetermination:1},
	success: function(data)
	{
	//location.reload(); 
	$(".employee-termination-details").html(data);
	}
	});
	});
    
	$('#checkemployee').click(function(e) {
	e.preventDefault();
	$.ajax({
	type: 'POST',
	url: 'employee_submit.php',
	data: {employeeId: $('#employeeId').val(), checkemployee:1},
	success: function(data)
	{
	//location.reload(); 
	$(".employee-details").html(data);
	}
	});
	});

	$('#checkemployeepension').click(function(e) {
	e.preventDefault();
	$.ajax({
	type: 'POST',
	url: 'employee_submit.php',
	data: {employeeId: $('#pemployeeId').val(), checkemployeepension:1},
	success: function(data)
	{
	
	  $(".employee-pension-details").html(data);
	}
	});
	});

	$('#festivaladvemployee').click(function(e) {
	e.preventDefault();
	$.ajax({
	type: 'POST',
	url: 'employee_submit.php',
	data: {employeeId: $('#festemployeeId').val(), festivaladvemployee:1},
	success: function(data)
	{
	
	  $(".employee-festival-advance-details").html(data);
	}
	});
	});	


});	
		function transferReset()
		  {
      	var employeeId = $('#employeeId').val();
				$.ajax({
				type: 'POST',
				url: 'transferReset.php',
				data: {employeeId:employeeId,action:'reset'},
				success: function(data)
				{
				  // console.log(data);
				  $(".employee-details").html(data);
				}
				}); 		  	
		  }
    function resetap()
      {
      	var employeeId = $('#paemployeeId').val();
				$.ajax({
				type: 'POST',
				url: 'incemployee.php',
				data: {employeeId:employeeId,action:'reset'},
				success: function(data)
				{
				  // console.log(data);
				  $(".employee-ap-details").html(data);
				}
				});      	
      }

    function changefestAdvStatus(festAdvStatus)
      {
      	        var employeeId = $('#festadvemployeeId').val();
      	        var festAdvId = $('#festadvId').val();
      	        console.log(employeeId);
      	        console.log(festAdvStatus);
				$.ajax({
				type: 'POST',
				url: 'employee_submit.php',
				data: {festAdvStatus: festAdvStatus,employeeId:employeeId,festAdvId:festAdvId,changefestadvstatus:1},
				success: function(data)
				{
				   console.log(data);
				  $(".employee-festival-advance-details").html(data);
				}
				});      	
      }

    function changeMEpension(pension_details_id)
      {
				$.ajax({
				type: 'POST',
				url: 'employee_submit.php',
				data: {pension_details_id: pension_details_id,changemstatus:1},
				success: function(data)
				{
				
				  $(".employee-pension-details").html(data);
				}
				});      	
      }
    function deleteEpension(pension_details_id)
      {
				$.ajax({
				type: 'POST',
				url: 'employee_submit.php',
				data: {pension_details_id: pension_details_id,deletestatus:1},
				success: function(data)
				{
				
				  $(".employee-pension-details").html(data);
				}
				});      	
      }      
	function changeStatus()
	 {
	    var employeeId = $('#employeeId').val();
		$.ajax({
		type: 'POST',
		url: 'employee_submit.php',
		data: {employeeId: employeeId, changestatus:1},
		success: function(data)
		{
		//location.reload(); 
		$(".employee-details").html(data);
		}
		});
	 }
	function changePensionStatus()
	 {
	    var employeeId = $('#employeeId').val();
		$.ajax({
		type: 'POST',
		url: 'employee_submit.php',
		data: {employeeId: employeeId, changePensionstatus:1},
		success: function(data)
		{
		//location.reload(); 
		$(".employee-details").html(data);
		}
		});
	 }	 
</script>	
</html>