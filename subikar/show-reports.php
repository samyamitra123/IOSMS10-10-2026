<table width="100%" border="1" style="text-align: center;" >
	<thead>
		<th>Sl No</th>
		<th>District Name</th>
		<th>Inter District Transfer</th>
		<th>District Transfer</th>
	</thead>
	<?php foreach($Reports as $key=>$report):
         $outsideDistrict = $OutDistrictReports[$key];
		?>
	<tr>
	  <td><?php echo ($key)?></td>
	  <td><?php echo $report['district_name']; ?></td>
	  <td>
	  	<p>
	  		<?php if($report['applications'] > 0){ ?>
	  		Application Submitted:<?php echo $report['applications']; ?>
	  		<?php } else { echo '-'; }?>	
	  	</p>
	  	<p><?php echo $report['approvedby']; ?></p>
	  	<p><?php echo $report['rejectedby']; ?></p>
	  	<p><?php echo $report['pendingby']; ?></p>
	  	
	  </td>
	  <td>	  	
	  	<p>
	  		<?php if($outsideDistrict['applications'] > 0){ ?>
	  		Application Submitted:<?php echo $outsideDistrict['applications']; ?>
	  		<?php } else { echo '-'; }?>	  		

	  	</p>
	  	<p><?php echo $outsideDistrict['approvedby']; ?></p>
	  	<p><?php echo $outsideDistrict['rejectedby']; ?></p>
	  	<p><?php echo $outsideDistrict['pendingby']; ?></p>
	  </td>

	</tr>
<?php endforeach; ?>
</table>