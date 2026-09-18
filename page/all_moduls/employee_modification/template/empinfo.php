
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
		<div class="col-lg-12 col-md-12 col-sm-12">
			<label class="label">New Basic</label>
			<input type="text" class="form-control" name="newbasic" value="" id="newbasic" >
		</div>	
		<div class="col-lg-12 col-md-12 col-sm-12">
			<label class="label">Upload Supported Document (only pdf not more then 1mb)</label>
			<input type="file" class="form-control" name="newbasicUpload" id="newbasicUpload" />
		</div>	
		<!--<div class="col-lg-12 col-md-12 col-sm-12">
			<label class="label">Endorse By</label>
			<select name="endorseby" id="endorseby" class="form-control" onchange="endorsefunc(this)">
				<option value="">Select Endorser</option>
				<?php /*foreach( $data['otherEmployeeData'] as $empData){ 

                    $empData['emp_second_name'] = (rtrim($empData['emp_second_name']) != '')?$empData['emp_second_name'].' ':'';
                    $emp_name = $empData['emp_first_name'].' '.$empData['emp_second_name'].$empData['emp_last_name'];*/
					?>
					<option value="<?php //echo $empData['emp_id_const']?>" data-name="<?php //echo $emp_name; ?>" data-desig="<?php //echo $empData['desig_name']; ?>" ><?php //echo $emp_name; ?></option>
				<?php /* } */?>	
			</select>
		</div>-->	
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="row">
				<!--<div class="col-lg-4 col-md-4 col-sm-4">
				<label class="label">Employee Id</label>
				<input type="text" class="form-control" name="employee_id" id="employee_id" readonly />
			    </div>-->			
				<div class="col-lg-6 col-md-6 col-sm-12">
				<label class="label">Submitted By</label>
				<input type="text" class="form-control" name="submittedby" id="submittedby" value="<?php echo $data['headMember']; ?>" readonly />
			    </div>
				<div class="col-lg-6 col-md-6 col-sm-12">
				<label class="label">Location</label>
				<input type="text" class="form-control" name="submittedbydesig" id="submittedbydesig"  value="<?php echo $data['desig']; ?>" readonly />
			    </div>
		    </div>
		</div>			
		<div class="col-lg-12 col-md-12 col-sm-12">
			<input type="hidden" name="empId" id="emp_id_const" data-empid="<?php echo $data['emp_id_const']?>">
			<input type="hidden" name="action" class="action" data-func="workflow">
			<div class="sbutton">
			<input type="submit" style="width:200px; margin: 0 auto;" class="form-control btn btn-sm btn-primary " name="newbasicupdate" value="Request" onclick="SubmitChangeRequest();"></input>
		    </div>
		    <div class="prcessbtn">
		    	 <button type="submit" class="form-control btn btn-sm btn-primary " style='width: auto;'  name="newbasicupdate" ><span class="spinner-grow spinner-grow-sm"></span> Request Procession Please Wait...</input>
		    </div>	
		</div>	
	    </div>
    </div>
 <script type="text/javascript">
 	$('.prcessbtn').hide();
 	/*function endorsefunc(thisobj){
 		var empId = thisobj.value
 		var name  = $('#endorseby').children('option:selected').data('name');
 		var desig = $('#endorseby').children('option:selected').data('desig');
 		$('#employee_id').val(empId);
 		$('#submittedby').val(name);
 		$('#submittedbydesig').val(desig);
 	}*/
	function SubmitChangeRequest()
	 {
	 		var property = document.getElementById('newbasicUpload').files[0];
	 		//alert(property);
	 		var basic = $('#newbasic').val();
	 		///alert(basic);
	 		var submittedby = $('#submittedby').val();
	 		var submittedbydesig = $('#submittedbydesig').val();
	 		if(property == undefined)
	 		{
	 			alert("Plese Upload Supported Document");
	 			return false;
	 		}
	 		if(basic == '' || basic == 0)
	 		{
	 			alert("Basic cannot be empty");
	 			return false;	 			
	 		}
	 		if(submittedby == '' || submittedby == 0 || submittedby.length < 6)
	 		{
	 			alert("Submitted By cannot be empty");
	 			return false;	 			
	 		}	 		
	 		if(submittedbydesig == '' || submittedbydesig == 0 || submittedbydesig.length < 3)
	 		{
	 			alert("Submitted By Designation cannot be empty");
	 			return false;	 			
	 		}	 
			var image_name = property.name;

			var image_extension = image_name.split('.').pop().toLowerCase();
			if($.inArray(image_extension,['pdf']) == -1){
			  alert("Only Pdf file allowed to upload");
			  return false;
			}
			$('.sbutton').hide('slow');
			$('.prcessbtn').show('slow');
            var form_data = new FormData();
			form_data.append("file",property);			
			form_data.append('emp_id_const',$('#emp_id_const').data('empid'));
			form_data.append('newbasic',basic);
			//form_data.append('submit_id',$('#endorseby').val());
			form_data.append('submittedby',$('#submittedby').val());
			form_data.append('submittedbydesig',$('#submittedbydesig').val());
			form_data.append('action',$('.action').data('func'));
			  $.ajax({
				  url : 'ajax_empDetails.php',
				  type : 'POST',
				  data:form_data,
				  contentType:false,
				  cache:false,
				  processData:false,
					success : function(response) {
						//console.log(response); 
						var response = JSON.parse(response);
			            if(response.status == 'error')
			            {
			              $('.error-msg').html(response.data);
						  $('.sbutton').show('slow');
						  $('.prcessbtn').hide('slow');			              
			            }
			            else if(response.status == 'success')
			            {
			            	$('.employeeInfochange').html(response.data);
			                window.location.reload();
			            }	
					  
					},
					error:function(){
					  alert('Server Error');
					}
				  });		

	 } 	

 </script> 