<?php 
  $encode_drn = $crypto->encode($variable['drn_number'], 4);
?>
<span class="ngipfAction_<?php echo $variable['drn_number']; ?>">
<a href="javascript:void(0);" onclick="SendNgipf('<?php echo $encode_drn; ?>','<?php echo $variable['drn_number']; ?>')"> <?php echo $variable['bill_no']; ?>
</a> Only Debug:[<a href="https://priemp.wbprd.gov.in/page/api/gpf/salary.php?drn=<?php echo $encode_drn; ?>&legacy=1&debug=1" target="_blank">debug</a>]
</span>
<span class="hide-status ngipfafterAction_<?php echo $variable['drn_number']; ?>">
	<?php echo $variable['bill_no']; ?></span>
<span class="ngipfmsg_<?php echo $variable['drn_number']; ?>"></span>
<br />
(<?php echo $variable['drn_number']; ?>) 