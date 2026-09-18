
	<h2 class="head-employee"><?php echo $data['emp_first_name']?> <?php echo $data['emp_second_name']?> <?php echo $data['emp_last_name']?> INFORMATION</h2>
	<div class="emp-info-box">
		<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Name</label>
			<?php echo $data['emp_first_name']?> <?php echo $data['emp_second_name']?> <?php echo $data['emp_last_name']?>
		</div>	
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Date Of Birth</label>
			<?php echo $data['emp_dob']?>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">First Joining Date</label>
			<?php echo $data['emp_first_join_date']?>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Aadhar ID</label>
			<?php echo $data['emp_aadhar_no']?> 
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Voter ID</label>
			<?php echo $data['emp_voter_id']?> 
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Current Basic</label>
			<?php echo $data['emp_pay_in_payband']?> 
		</div>
	</div>
    </div>

   <h2 class="head-employee"><?php echo $data['emp_first_name']?> <?php echo $data['emp_second_name']?> <?php echo $data['emp_last_name']?> BASIC CHANGE REQUEST</h2>
   <div class="error error-msg"></div>
	<div class="emp-info-box">
		<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<label class="label">New Basic</label>
			<?php echo $data['basic']?> 
		</div>	
		<div class="col-lg-6 col-md-6 col-sm-6">
			<label class="label">Supported Document</label>
			<a href="download.php?load=<?php echo $cryptoGraph->encode($data['ech_id'],4) ?>" target="_blank">Download Document</a>
		</div>	
		<!--<div class="col-lg-3 col-md-3 col-sm-3">
			<label class="label">Employee Id </label>
			<?php //echo $data['submit_id']?> 
		</div>	-->		
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Submitted By </label>
			<?php echo $data['submittedby']?> 
		</div>	
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Location</label>
			<?php echo $data['submittedbydesig']?> 
		</div>	
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label">Submitted On </label>
			<?php echo $data['submitted_on']?> 
		</div>	
		<?php if($data['ddo_active'] == 1):?>	
		<div class="col-lg-12 col-md-12 col-sm-12 text-center" style="color:#059704;">
			<label class="label"><i class="fa fa-check"></i> Verified by FC&CAO</label>
		</div>	
		<?php endif; ?>				
		<?php if($data['status'] != 0):?>	
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label"><?php echo ($data['status'] == 1)?'Approved':'Rejected' ?> By </label>
			<?php echo $data['verifiedby']?> 
		</div>	
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label"><?php echo ($data['status'] == 1)?'Approved':'Rejected' ?> By Desigation </label>
			<?php echo $data['verified_desig']?> 
		</div>		
		<div class="col-lg-4 col-md-4 col-sm-4">
			<label class="label"><?php echo ($data['status'] == 1)?'Approved':'Rejected' ?> On </label>
			<?php echo $data['verified_on']?> 
		</div>	
		<?php endif; ?>	
	
		<?php if(in_array($this->userInfo['stake_abbr'],array('FC&CAO')) && $data['ddo_active'] == 0): ?>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
				<label class="label">Forwarded By</label>
				<input type="text" class="form-control" name="submittedby" id="submittedby" value="<?php echo $data['officer_name'] ?>" readonly/>
			    </div>
				<div class="col-lg-6 col-md-6 col-sm-6">
				<label class="label">Forwarded Designation</label>
				<input type="text" class="form-control" name="submittedbydesig" id="submittedbydesig" value="<?php echo $data['officer_desig'] ?>" readonly/>
			    </div>
		    </div>
		</div>				
		<div class="col-lg-12 col-md-12 col-sm-12 text-center" style="margin-top:20px;">
			<input type="hidden" name="empId" id="emp_id_const" data-empid="<?php echo $data['ech_id']?>">
			<input type="hidden" name="action" class="action" data-func="workflowapprove">
			<input type="submit" style="width:200px; margin: 0 auto;" class="form-control btn btn-sm btn-primary " name="newbasicupdate" data-status='1' value="Forward" onclick="ApproveChange(this);" />
			<input type="submit" style="width:200px; margin: 0 auto;" class="form-control btn btn-sm btn-primary" name="newbasicupdate" data-status='2' value="Reject" onclick="ApproveChange(this);" />
		</div>			
		<?php endif; ?>
		<?php if(in_array($this->userInfo['stake_abbr'],array('BDO','EO','AEO')) && $data['status'] == 0): ?>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
				<label class="label">Approved By</label>
				<input type="text" class="form-control" name="submittedby" id="submittedby" value="<?php echo $data['officer_name'] ?>" />
			    </div>
				<div class="col-lg-6 col-md-6 col-sm-6">
				<label class="label">Approvar Designation</label>
				<input type="text" class="form-control" name="submittedbydesig" id="submittedbydesig" value="<?php echo $data['officer_desig'] ?>" />
			    </div>
		    </div>
		</div>			
		<div class="col-lg-12 col-md-12 col-sm-12">
			<input type="hidden" name="empId" id="emp_id_const" data-empid="<?php echo $data['ech_id']?>">
			<input type="hidden" name="action" class="action" data-func="workflowapprove">
			<input type="submit" style="width:200px; margin: 0 auto;" class="form-control btn btn-sm btn-primary " name="newbasicupdate" data-status='1' value="Approve" onclick="ApproveChange(this);" />
			<input type="submit" style="width:200px; margin: 0 auto;" class="form-control btn btn-sm btn-primary" name="newbasicupdate" data-status='2' value="Reject" onclick="ApproveChange(this);" />
		</div>	
		<?php endif; ?>
		<script type="text/javascript">
             function ApproveChange(thisobj)			
               {
               	  var employeeId = $('#emp_id_const').data('empid');
               	 // console.log(employeeId);
               	  var action = $('.action').data('func');
               	  var status = $(thisobj).data('status');
               	  //alert(status);
               	  var submittedby = $('#submittedby').val();
               	  var submittedbydesig = $('#submittedbydesig').val();
               	  if(submittedby != '' && submittedbydesig != '')
               	  {
			            $.post(
			                'ajax_empDetails.php',
			                 {empId:employeeId,action:action, submittedby:submittedby, submittedbydesig:submittedbydesig,status:status} 
			                , function(data){
			                   //alert(data);
			                 $('.employeeInfo').html(data);
			                 window.location.reload();
			               });                	  	
               	  }
               	 else 
               	  {
               	  	 alert("Submited By and Designaton must be filled");
               	  } 
               }
		</script>
	    
	    </div>
    </div>