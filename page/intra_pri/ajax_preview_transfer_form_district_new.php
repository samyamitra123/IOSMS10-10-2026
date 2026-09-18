<?php
   $app_no = $_POST['app_no'];
   $user_stack = $_SESSION['user_info']['stake_user_code'];
   $Query = "SELECT * from intra_pri_district_transfer WHERE application_id='".$app_no."'";
   $applicationData = $db->fetch_obj($Query);
   //print_r($applicationData);
?>
<div class="main-table">
	<div class="emplist">
	  <div class="school">
	    <div class="table-responsive">
				<table id="myTable" width="110%">
					<thead>
					<th>Serial No.</th>
					<th>Employee ID</th>
					<th>Employee Name</th>
				 	<th>Designation</th>
					<th>Block Name where posted</th>
					<th>GP Name where posted</th>
					<th>PS Name where posted</th>
					<th>Date of joining in Present Office</th>
					<th>Transfer TO Block</th>
					<th>Transfer TO GP</th>
					<th>Transfer TO PS</th>
					<th>Reason</th>
					<th>Document Upload</th>
				  <th>Status</th>
				  <?php if($user_stack=="DED"){ ?>
					<th style=" width:15%">Action</th>
					<?php }?>
					</thead>
					<tbody>
						<?php foreach($applicationData as $key=>$applicationItem):
             $applicationItem->emp_second_name = ($applicationItem->emp_second_name == '')?' ':' '.$applicationItem->emp_second_name.' ';
						?>
					<tr>
						 <td><?php echo ($key+1); ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_first_name.$applicationItem->emp_second_name.$applicationItem->emp_last_name; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
						 <td><?php echo $applicationItem->emp_id_const; ?></td>
					</tr>	
					  <?php endforeach; ?>					
					</tbody>

				</table>
			</div>
		</div>
	</div>
</div>