<?php $master->header(); ?>
<h3 class="pageHeading"><?php echo $PageTitle; ?></h3>
<table width="100%" class="maintable">
	<thead>
		<th>Sl No</th>
		<th>District Name</th>
		<th colspan="4">
		<table width="100%">
		<th width="25%" class="tabclassth">Block Name</th>
		<th width="25%" class="tabclassth">ZP</th>
		<th width="25%" class="tabclassth">Profile Submitted</th>
		<th width="25%">Application Movement</th>
		</table>
	  </th>
	</thead>
	<?php foreach($Reports as $key=>$report):
         
		?>
	<tr>
	  <td><?php echo ($key)?></td>
	  <td><?php echo $report['district_name']; ?></td>
	  <td colspan="4">

	  <?php if($report['blocks'] != ''){ ?>
      <table width="100%">
      	<?php 
      	foreach($report['blocks'] as $block) { ?>
      	<tr>
      		<td width="25%" class="tabclass"><?php echo $block['block_name']?></td>
      		<td width="25%" class="tabclass">-</td>
      		<td width="25%" class="tabclass"><?php echo $block['applications']?></td>
      		<td width="25%" class="notabclass">
			  	<p><?php echo $block['approvedby']; ?></p>
			  	<p><?php echo $block['rejectedby']; ?></p>
			  	<p><?php echo $block['pendingby']; ?></p>      			
      		</td>
      	</tr>
      <?php 
        } 
        ?>
        </table>
      <?php } 
	        else if(is_array($report['zp'])) {
        	$zp = $report['zp'];
      	 ?>
      	 <table width="100%" >
      	<tr>
      		<td width="25%" class="tabclass">-</td>
      		<td width="25%" class="tabclass"><?php echo $zp['block_name']?></td>
      		<td width="25%" class="tabclass"><?php echo $zp['applications']?></td>
      		<td width="25%" class="notabclass">
			  	<p><?php echo $zp['approvedby']; ?></p>
			  	<p><?php echo $zp['rejectedby']; ?></p>
			  	<p><?php echo $zp['pendingby']; ?></p>      			
      		</td>
      	</tr>
      </table>
      <?php 
        $zp = '';
       }  ?>  
     	
	  </td>
	</tr>
<?php endforeach; ?>
</table>
<style type="text/css">
	.tabclass{border-left: 0px !important;
    border-bottom: 0px!important;
    border-top: 0px !important;}
   .maintable, .maintable td {border: 1px solid #c6c6c6; text-align: center;}
   .notabclass{border: 0px !important;}
   .maintable th{background-color: #916401;
    color: #fff;}
    .tabclassth{border-right: 1px solid #c6c6c6;}
    .pageHeading{text-align: center; color: #916401; border-bottom: 1px solid #000;text-transform: uppercase;width: 240px;margin: 0 auto;margin-bottom: 10px;padding-bottom: 5px; }
</style>