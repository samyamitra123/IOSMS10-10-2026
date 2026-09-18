<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>HOO Code Bulk Updater</title>
<link rel="stylesheet" href="template/assets/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="template/assets/css/dataTables.bootstrap4.min.css" type="text/css" />

</head>

<body>
<div class="message"><?php echo $_GET['msg']; ?></div>
<form name="BlockData" action="bdohoo.php" method="POST">
<select name="district" onchange="callAllBlocks(this);">
	<option>Select District</option>
<?php foreach($this->district as $district): ?>
   <option value="<?php echo $district['district_id_pk']; ?>" <?php echo ($dis ==  $district['district_id_pk'])?'selected':''; ?> ><?php echo $district['district_name']; ?></option>
<?php endforeach; ?>
</select>
<?php //print_r($this->BlockSalaryStatus); ?>
<table id="example" class="table table-striped table-bordered">
	<thead>
		<th>Sl</th>
		<th>PS Action</th>
		<th>Block Action</th>
		<th>PS Salary Action</th>
		<th>GP Prev Sal</th>	
		<th>PS PF Subs</th>	
		<th>GP PF Subs</th>	
		<!--<th>PS Prev Sal</th>	
		<th>ZP Prev Sal</th>-->
		<th>Block Name</th>
		<th>Block Code</th>
		<th>BDO DDO Code.</th>
		<th>BDO Operator Code PF.</th>
		<th>BDO T Code PF.</th>
		<th>BDO Hoo Code</th>
		<th>PS pl code pf</th>
		<th>PS Treasury Code</th>
		<th>PS Hoo Code</th>
	</thead>
<?php 
//echo "<pre>";print_r($this->psInfo); exit;
 $slNo = 1;
foreach($this->bdoblockInfo as $key=>$bdoblock): ?>
	<tr>
		<td>
			<input type="hidden" name="block_code[]" value="<?php echo $bdoblock['block_code']?>">
         <input type="hidden" name="ps_id_pk[]" value="<?php echo $this->psInfo[$bdoblock['block_code']]['ps_id_pk']?>">
			<?php echo $slNo; ?>)
		</td>
		<td>
			<button type="button" class="psactive" data-psid="<?php echo $this->psInfo[$bdoblock['block_code']]['ps_id_pk']?>" data-psstatus="<?php echo $this->psInfo[$bdoblock['block_code']]['ngipf_status']; ?>">
          <?php echo ($this->psInfo[$bdoblock['block_code']]['ngipf_status'] == 1)?'InActive':'Active'; ?>
			</button>
		</td>
		<td>	
			<button type="button" class="blockactive" data-blockstatus="<?php echo $this->BlockNgipfStatus[$bdoblock['block_code']]; ?>" data-blockcode="<?php echo $bdoblock['block_code'] ?>" >
          <?php echo ($this->BlockNgipfStatus[$bdoblock['block_code']] == 1)?'InActive':'Active'; ?>
			</button>
		</td>

		<td>
			<button type="button" class="pssalactive" data-psid="<?php echo $this->psInfo[$bdoblock['block_code']]['ps_id_pk']?>" data-psstatus="<?php echo $this->psInfo[$bdoblock['block_code']]['salary_status']; ?>">
          <?php echo ($this->psInfo[$bdoblock['block_code']]['salary_status'] == 1)?'InActive':'Active'; ?>
			</button>
		</td>

		<td>	
			<button type="button" class="blockprevsalactive" data-blockprevstatus="<?php echo $this->BlockPrevSalaryStatus[$bdoblock['block_code']]; ?>" data-blockcode="<?php echo $bdoblock['block_code'] ?>" >
          <?php echo ($this->BlockPrevSalaryStatus[$bdoblock['block_code']] == 1)?'InActive':'Active'; ?>
			</button>
		</td>
		<td>	
			<button type="button" class="pspfsubscription" data-pfsubsstatus="<?php echo $this->psPfSubscriptionStatus[$bdoblock['block_code']]; ?>" data-psid="<?php echo $bdoblock['block_code'] ?>" >
          <?php echo ($this->psPfSubscriptionStatus[$bdoblock['block_code']] == 1)?'InActive':'Active'; ?>
			</button>
		</td>
		<td>	
			<button type="button" class="gppfsubscription" data-pfsubsstatus="<?php echo $this->gpPfSubscriptionStatus[$bdoblock['block_code']]; ?>" data-psid="<?php echo $bdoblock['block_code'] ?>" >
          <?php echo ($this->gpPfSubscriptionStatus[$bdoblock['block_code']] == 1)?'InActive':'Active'; ?>
			</button>
		</td>		
		<td><?php echo $this->BlockName[$bdoblock['block_code']]; ?> </td>
		<td><?php echo $bdoblock['block_code']; ?> </td>
		<td><input type="text" name="ddo_code[]" value="<?php echo $bdoblock['ddo_code']?>"></td>
		<td><input type="text" name="operator_code_pf[]" value="<?php echo $bdoblock['operator_code_pf']?>"></td>
		<td><input type="text" name="t_code_pf[]" value="<?php echo $bdoblock['t_code_pf']?>"></td>
		
		<td><input type="text" name="hoo_code[]" value="<?php echo $bdoblock['hoo_code']?>"></td>
		<td><input type="text" name="pl_code_pf[]" value="<?php echo $this->psInfo[$bdoblock['block_code']]['pl_code_pf']?>"></td>
		<td><input type="text" name="treasury_code[]" value="<?php echo $this->psInfo[$bdoblock['block_code']]['treasury_code']?>"></td>
		<td><input type="text" name="pshoo_code[]" value="<?php echo $this->psInfo[$bdoblock['block_code']]['hoo_code']?>"></td>
	</tr>
<?php 
$slNo++;
endforeach; ?>
<tr><td colspan="7"><input type="submit" name="save" value="Save Me"></td></tr>
</table>
</body>

<script src="template/assets/js/jquery-3.5.1.js"></script>
<script src="template/assets/js/jquery.dataTables.min.js"></script>
<script src="template/assets/js/dataTables.bootstrap4.min.js"></script>
<script src="template/assets/js/bootstrap.min.js"></script>

<script>
	function callAllBlocks(obj)
	  {
	  	window.location.href = 'bdohoo.php?dis='+(obj.value);
	  }
$(document).ready(function () {
   // $('#example').DataTable();
    
    $('.psactive').click(function(){
    	var status = $(this).data('psstatus');
    	var psid = $(this).data('psid');
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,psid:psid, action:'updatengipfstatusps'} 
					, function(data){
						var response = JSON.parse(data);
						 console.log(response.msg); 
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('psstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    });
   $('.blockactive').click(function(){
    	var status = $(this).data('blockstatus');
    	var blockcode = $(this).data('blockcode');
    	//console.log(blockcode);
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,blockcode:blockcode, action:'updatengipfstatusblock'} 
					, function(data){
						var response = JSON.parse(data);
						//console.log(data); 
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('blockstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    });  
    $('.pssalactive').click(function(){
    	var status = $(this).data('psstatus');
    	var psid = $(this).data('psid');
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,psid:psid, action:'updatesalstatusps'} 
					, function(data){
						var response = JSON.parse(data);
						 console.log(response.msg); 
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('psstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    });      
   $('.blocksalactive').click(function(){
    	var status = $(this).data('blockstatus');
    	var blockcode = $(this).data('blockcode');
    	//console.log(blockcode);
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,blockcode:blockcode, action:'updatesalstatusblock'} 
					, function(data){
						var response = JSON.parse(data);
						console.log(data); 
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('blockstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    }); 
   $('.blockprevsalactive').click(function(){
    	var status = $(this).data('blockstatus');
    	var blockcode = $(this).data('blockcode');
    	//console.log(blockcode);
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,blockcode:blockcode, action:'blockprevsalactive'} 
					, function(data){
						var response = JSON.parse(data);
						console.log(data); 
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('blockstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    }); 
   $('.pspfsubscription').click(function(){
    	var status = $(this).data('pfsubsstatus');
    	var psid = $(this).data('psid');
    	//console.log(blockcode);
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,psid:psid, action:'pspfsubscription'} 
					, function(data){
						console.log(data); 
						var response = JSON.parse(data);
						
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('pfsubsstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    }); 
   $('.gppfsubscription').click(function(){
    	var status = $(this).data('pfsubsstatus');
    	var psid = $(this).data('psid');
    	//console.log(blockcode);
    	var curData = $(this);
    	$.post(
    		     'bdohoo.php',
              {status:status,psid:psid, action:'gppfsubscription'} 
					, function(data){
						console.log(data); 
						var response = JSON.parse(data);
						
                   if(response.status == 'success')
                   {
					         curData.html(response.msg);
					         curData.data('pfsubsstatus',response.statustxt);
                   }
                   else
                   {
                   	alert("Something went wrong. Please try again");
                   } 	
			    }); 
    });                  
});	  
</script>
</html>