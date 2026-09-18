<table width="100%" border="1">
<tr>
	<th>Month</th>
	<th>Bill No</th>
	<th>PF Subscription Status</th>
</tr>

<?php foreach($variable as $item): 
      $encode_drn = $crypto->encode($item['drn_number'], 4);
	?>
<tr style="border-bottom: 1px solid #c9c9c9;">
	<td style="background-color: rgb(221, 247, 255);"><?php echo date('F',mktime(0,0,0,substr($item['salary_monthyear'],4),10)); ?></td>
	<td class="main_<?php echo $item['drn_number']; ?>">
		<?php if($item['ngipf_allowed'] && $item['ngipf_send_status'] == -1):?>
			<span class="ngipfAction_<?php echo $item['drn_number']; ?>">
			<a href="javascript:void(0);" onclick="SendNgipf('<?php echo $encode_drn; ?>','<?php echo $item['drn_number']; ?>')"> <?php echo $item['bill_no']; ?>
			</a> Only Debug:[<a href="https://priemp.wbprd.gov.in/page/api/gpf/salary.php?drn=<?php echo $encode_drn; ?>&legacy=1&debug=1" target="_blank">debug</a>]
                  </span>
                  <span class="hide-status ngipfafterAction_<?php echo $item['drn_number']; ?>">
                  	<?php echo $item['bill_no']; ?>
                  </span>

			<br />
			(<?php echo $item['drn_number']; ?>) 
		<?php else: ?>
		      <span class="ngipfAction_<?php echo $item['drn_number']; ?>">	
			<?php echo $item['bill_no']; ?><br />
			(<?php echo $item['drn_number']; ?>) <a href="javascript:void(0);" onclick="deleteDrn('<?php echo $encode_drn; ?>','<?php echo $item['drn_number']; ?>','<?php echo $item['bill_no']; ?>')">Delete</a>
			</span>
		<?php endif; ?>
	</td>
	<td id="">
		<?php if($item['ngipf_allowed'] && in_array($item['ngipf_send_status'],array(0,1) )):?>
			<a href="javascript:void(0);" onclick="CheckNgipfStatus('<?php echo $encode_drn; ?>')"> Check Status </a>
		<?php else: ?>
			<a href="javascript:void(0);" onclick="CheckNgipfStatus('<?php echo $encode_drn; ?>')" class='hide-status hide-status_<?php echo $item['drn_number']; ?>'> Check Status </a>
			<span class="ngipfmsg_<?php echo $item['drn_number']; ?>"></span>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>

</table>