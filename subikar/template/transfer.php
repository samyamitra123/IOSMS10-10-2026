<?php //$master->header(); ?>
<h3 class="pageHeading"><?php echo $PageTitle; ?></h3>
<table width="100%" class="maintable">
	<thead>
		<th>Sl No</th>
		<th>District Name</th>
		<th>Application</th>
	</thead>
	<?php 
   $counter = 1;
	foreach($district as $key=>$report):
         
		?>
	<tr>
	  <td><?php echo ($counter)?></td>
	  <td><?php echo $report; ?></td>
	  <td><?php echo isset($districtApplication[$key]['count'])?$districtApplication[$key]['count']:0; ?></td>
	</tr>
<?php 
$counter++;
endforeach; ?>
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