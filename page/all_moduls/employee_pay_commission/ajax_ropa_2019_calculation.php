<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
 


require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

$cryp = new cryptography();
$db = new database();
error_reporting(0);
?>


<?php
$stake_level= $_SESSION['user_info']['stake_abbr'];
if($stake_level=="EO")
{
	
	 $user='ps';
}
else if($stake_level=="BDO")
{
	
	
	 $user='gp';
}
else if($stake_level=="DEALING ASSISTANT (Account)")
{
	 
	 $user='zp';
}



		$emp_id_fk=$cryp->decode($_GET['emp_id_pk'],4);
		//$emp_id_pk=$_GET['emp_id_pk'];
		$ropa_2019_effective_year= $_GET['emp_first_join_year'];
		
		
		$select_saved_pay_data=$db->fetch_table("SELECT * FROM prd_emp_basic_pay_details_before_2019 
  										WHERE emp_id_fk='".$emp_id_fk."' AND year=(SELECT MIN(year) FROM
										prd_emp_basic_pay_details_before_2019 WHERE emp_id_fk='".$emp_id_fk."')");
		
		 $pay_band_on_effective_date=$select_saved_pay_data[0]['pay_band']; 
		 $grade_pay_on_effective_date=$select_saved_pay_data[0]['grade_pay'];
		
 		$basic_pay_on_effective_date = $pay_band_on_effective_date + $grade_pay_on_effective_date;
		
		$percentage_basic_pay_2016=$db->fetch_table("select fitment_factor from prd_admin_paychange where flag='TRUE' and ropa_year='2019' ");
		$percentage_of_basic = $percentage_basic_pay_2016[0]['fitment_factor'];
		//echo $basic_pay_on_effective_date; 
		$new_basic_pay_on_effective_date = round($basic_pay_on_effective_date * $percentage_of_basic) ;  //***********************************  round of
		$arr1=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 		$level_on_effective_date = 'level'.$arr1[0]['level'];
		
		$get_emp_joining_date=$db->fetch_table("select emp_first_join_date,zp_emp_type from prd_employee_master where emp_id_pk ='".$emp_id_fk."'");
		
		$get_ropa_cause=$db->fetch_table("SELECT cause FROM ropa_2019_emp_pay_scale_master
													WHERE emp_id_fk='".$emp_id_fk."'");
		//echo $get_emp_joining_date[0]['zp_emp_type']; die;
		if($get_emp_joining_date[0]['zp_emp_type']=='' || $get_emp_joining_date[0]['zp_emp_type']=='0' || $get_emp_joining_date[0]['zp_emp_type']=='367')
		{
			
		 $ropa_table='ropa_2019'; 
		 $ropa_mem='10485/PN/O/III/2E-34/2019, Dated: 24.12.2019'; 
		}
		else 
		{
			
		 $ropa_table='ropa_2019_ll';
		 $ropa_mem='5562-F, Dated: 25.09.2019';  
		}
		
		if(strtotime($get_emp_joining_date[0]['emp_first_join_date']) >= strtotime('01-01-2016') && $get_ropa_cause[0]['cause']=='2')
		{
			//echo 11; die;
			/*echo ("select ".$level_on_effective_date." 
									from ropa_2019 order by ".$level_on_effective_date." ASC LIMIT 1");*/
									
								if($pay_band_on_effective_date>='7910' && $level_on_effective_date=="level9")
								{
								$arr2=$db->fetch_table("select ".$level_on_effective_date." 
					from ".$ropa_table." where ".$level_on_effective_date."='29800' ");
									
								}
								else
								{
					
					$arr2=$db->fetch_table("select ".$level_on_effective_date." 
					from ".$ropa_table." order by ".$level_on_effective_date." ASC LIMIT 1");
									
								}
		}
		////////////////////////////
		else
		{
			//echo 12; die;
			
			$arr2=$db->fetch_table("select ".$level_on_effective_date." from ".$ropa_table." where ".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."'
			order by ".$level_on_effective_date." ASC LIMIT 1");
		}
		
		
		
		//$arr2=$db->fetch_table("select ".$level_on_effective_date." from ropa_2019 where ".$level_on_effective_date.">='".$new_basic_pay_on_effective_date."' LIMIT 1");
		$ropa_2019_basic_pay_on_effective_date = $arr2[0][$level_on_effective_date];
		$basic_last_2016=$ropa_2019_basic_pay_on_effective_date;
		$level_last_2016=$level_on_effective_date;
		
		
$get_ropa_master=$db->fetch_table("SELECT status,ropa_2019_effective_date,edit_status,sent_status FROM ropa_2019_emp_pay_scale_master
													WHERE emp_id_fk='$emp_id_fk'");
?>
<div class="row">
      <div class="col-sm-12" align="right">
<?php													
if(($get_ropa_master[0]['status']==4 && $get_ropa_master[0]['edit_status']=='0' && $user=='zp' && $get_ropa_master[0]['sent_status']=='2') || ($get_ropa_master[0]['status']==2 && $user=='gp' && $get_ropa_master[0]['edit_status']=='0')  || ($get_ropa_master[0]['status']==2 && $user=='ps' && $get_ropa_master[0]['edit_status']=='0'))
{
?>
	  <a href="javascript:void(0);"  onclick="return delete_ropa_data('<?=$cryp->encode('fi',4)?>', '<?=$_GET['emp_id_pk']?>', '')">  <i class="fa fa-trash fa-2x" aria-hidden="true"  style="color:red "></i></a>
<?php
}
?>
	  </div>
</div>


	 <div class="col-sm-12 festival_advance_design" style="display:inline !important; margin-left: 0% !important;"> 
      <div class="row ">
	  <div class="col-sm-4">
      
      		<label for="inputPassword3"  style="color: #246a8e;" class="control-label">As on <?=$get_ropa_master[0]['ropa_2019_effective_date']?> :<span class="star_color"></span></label>
      </div>
	  <div class="col-sm-2">
      		<label for="inputPassword3" style="color: #246a8e;" class="control-label">Basic Pay<span class="star_color"></span></label>
      </div>
      <div class="col-sm-2">
      		<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="bp_01-01-<?=$ropa_2019_effective_year?>" placeholder="Basic" name="bp_01-01-<?=$ropa_2019_effective_year?>" value="<?=$basic_last_2016?>" />
      </div>
      <div class="col-sm-2">
      		<label for="inputPassword3" style="color: #246a8e;" class="control-label">Level<span class="star_color"></span></label>
      </div>    
      <div class="col-sm-2">
      		<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="lvl_01-01-<?=$ropa_2019_effective_year?>" placeholder="Level" name="lvl_01-01-<?=$ropa_2019_effective_year?>" value="<?=$level_last_2016?>" />
      </div>
	  </div>
      
      </div>
<?php		
		$arr3=$db->fetch_table("select * from prd_emp_increment_details_before_2019 where emp_id_fk='".$emp_id_fk."' ORDER BY increment_dtls_id_pk DESC");
		//echo "select * from mad_emp_increment_details_before_2019 where emp_id_fk='".$emp_id_fk."' ORDER BY effective_date ASC";
		
		$count = 0;
		foreach($arr3 as $data_of_effective_year) 
		{	
			$promotion_increment_dtls=$data_of_effective_year['increment_dtls'];
			$promotion_increment_type=$data_of_effective_year['increment_type'];
			$promotion_increment_date=$data_of_effective_year['effective_date'];
			
			if ($count==0)
			{
				 $new_level = $level_last_2016; 
				$new_basic_pay = $basic_last_2016;
			}
			else
			{
				$new_level = $new_level; 
				$new_basic_pay = $new_basic_pay;	
			}
		// IF ANNUAL INCREMENT
			if($promotion_increment_dtls=='1' && $promotion_increment_type == '1')
			{
				
				//echo ("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				//echo ("select ".$new_level." from ropa_2019 where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				//echo ("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."'  order by ".$new_level." ASC LIMIT 1");die;
				$arr4=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."'  order by ".$new_level." ASC LIMIT 1");
				
				$new_basic_pay = $arr4[0][$new_level]; 
				$new_level = $new_level;
			}
		
		// IF PROMOTION OR CAS + SINGLE INCREMENT
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '1')
			{
//echo ("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' LIMIT 1");
				// for single increment
				$arr5=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr5[0][$new_level]; 
				$new_level = $new_level;
				
				
				/////////////// 07_11_2019 for current grade pay level
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				 $new_level = 'level'.$arr6[0]['level'];
				
				
				//////////////// end 07_11_2019 for level change
			//echo ("select ".$new_level." from ropa_2019 where ".$new_level.">='".$new_basic_pay."' LIMIT 1");
				//echo ("select ".$new_level." from ".$ropa_table." where ".$new_level.">='".$new_basic_pay."' LIMIT 1");die;
				$arr7=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">='".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr7[0][$new_level]; 
				$new_level = $new_level;

					
			}
		
		
		// IF PROMOTION OR CAS + DOUBLE INCREMENT
			else if(($promotion_increment_dtls=='2' || $promotion_increment_dtls=='3') && $promotion_increment_type == '2')
			{
				
				// for single increment
				$arr8=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr8[0][$new_level]; 
				$new_level = $new_level;
				
				// for double increment
				$arr9=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">'".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr9[0][$new_level]; 
				$new_level = $new_level;
				
				/////////////// 07_11_2019  for level of current grade pay (promotion)
				
				$grade_pay_on_effective_date=$data_of_effective_year['current_grade_pay'];
				$arr6=$db->fetch_table("select level from prd_dise_gradepay_master where grade_amount='".$grade_pay_on_effective_date."'");
 				$new_level = 'level'.$arr6[0]['level'];
				
				
				/////////////// 07_11_2019  for level change (promotion)

				$arr10=$db->fetch_table("select ".$new_level." from ".$ropa_table." where ".$new_level.">='".$new_basic_pay."' order by ".$new_level." ASC LIMIT 1");
				$new_basic_pay = $arr10[0][$new_level]; 
				$new_level = $new_level;

			}
			
	  $promotion_increment=explode("-",$promotion_increment_date);
	  $promotion_increment_year=$promotion_increment[2];
	  if($promotion_increment_year==2015)
	  {
		  $promotion_increment_year=2016;
	  }
	  $new_promotion_increment_date=$promotion_increment[0].'-'.$promotion_increment[1].'-'.$promotion_increment_year;
?>
	<div class="col-sm-12 festival_advance_design" style="display:inline !important;  margin-left: 0% !important;">

      <div class="row">
	  <div class="col-sm-4">
      		<label for="inputPassword3"  style="color: #246a8e;" class="control-label">As on <?=$new_promotion_increment_date?> After 
			<? 
				if($data_of_effective_year['increment_dtls']==1)
				{
					echo 'Annual Increment';
				}
				else if($data_of_effective_year['increment_dtls']==2)
				{
					echo 'Promotion with ';
				}
				else if($data_of_effective_year['increment_dtls']==3)
				{
					echo 'CAS with ';
				}
				
				if($data_of_effective_year['increment_dtls']!=1 && $data_of_effective_year['increment_type']==1)
				{
					echo 'Single Increment';
				}
				else if($data_of_effective_year['increment_dtls']!=1 && $data_of_effective_year['increment_type']==2)
				{
					echo 'Double Increment';
				}
			?>
            :<span class="star_color"></span></label>
      </div>
	  <div class="col-sm-2">
      		<label for="inputPassword3"  style="color: #246a8e;" class="control-label">Basic Pay<span class="star_color"></span></label>
      </div>
      <div class="col-sm-2">
      		<input readonly="readonly"  style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="bp_<?=$promotion_increment_date?>" placeholder="Basic" name="bp_<?=$promotion_increment_date?>" value="<?=$new_basic_pay?>" />
      </div>
      <div class="col-sm-2">
      		<label for="inputPassword3"  style="color: #246a8e;" class="control-label">Level<span class="star_color"></span></label>
      </div>
      <div class="col-sm-2">
      		<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="lvl_<?=$promotion_increment_date?>" placeholder="Level" name="lvl_<?=$promotion_increment_date?>" value="<?=$new_level?>" />
      </div>
      </div>
    </div>
<?php
		
		$count++;
		
		}
		
	$get_edited_data=$db->fetch_table("SELECT basic_pay,edit_status level FROM ropa_2019_emp_pay_scale_master
									WHERE emp_id_fk='$emp_id_fk' AND edit_status='1'");
									
	if($get_edited_data[0]['basic_pay']!='' || $get_edited_data[0]['basic_pay']!=0)
	{
		$basic_last_2016=$new_basic_pay=$get_edited_data[0]['basic_pay'];
		$basic_last_2016=$new_level=$get_edited_data[0]['level'];
	}
	
	if($new_basic_pay){$_SESSION['basic_2020']=$new_basic_pay;}else{$_SESSION['basic_2020']=$basic_last_2016;}
	if($new_level){$_SESSION['level_2020']=$new_level;}else{$_SESSION['level_2020']=$level_last_2016;}
	//$_SESSION['level_2020']=$new_gp;
?>
	 <div class="col-sm-12 festival_advance_design" style="display:inline !important;  margin-left: 0% !important;"> 

      <div class="row">
	  <div class="col-sm-4">
      		<label for="inputPassword3" style="color: #246a8e;" class="control-label"><b>As on 01-01-2020 :</b><span class="star_color"></span></label>
      </div>
	  <div class="col-sm-2">
      		<label for="inputPassword3"  style="color: #246a8e;" class="control-label"><b>Basic Pay</b><span class="star_color"></span></label>
      </div>
      <div class="col-sm-2">
      <?php if ($get_ropa_master[0]['edit_status'] != '99') { ?>
      		
           <input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="bp_01-01-2020" placeholder="Basic" name="bp_01-01-2020" value="<? if($new_basic_pay){echo $new_basic_pay;}else{echo $basic_last_2016;}?>" />
           <?php } else { ?>
      <input type="text" class="form-control" id="bp_01_01_2020_manually" placeholder="Basic" name="bp_01_01_2020_manually" value="<? if($new_basic_pay){echo $new_basic_pay;}else{echo $basic_last_2016;}?>" />
      <?php } ?>
      </div>
      
      <div class="col-sm-2">
      		<label for="inputPassword3" style="color: #246a8e;" class="control-label"><b>Level</b><span class="star_color"></span></label>
      </div>
      <div class="col-sm-2">
      <?php if ($get_ropa_master[0]['edit_status'] != '99') { //echo $new_level; die;?>
      		<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="lvl_01-01-2020" placeholder="Level" name="lvl_01-01-2020" value="<? if($new_level){echo $new_level;}else{echo $level_last_2016;}?>" />
            <?php } else { ?>
      <div class="col-sm-2">
		<?
        $db = new database();
        $arr = $db->fetch_table("select level from prd_dise_gradepay_master order by level");
    	//echo $level_last_2016; die;
        ?>
      <select class="form-control level" style="width: 102px; margin-left: -16px;"  name="level_2020_manually" id="level_2020_manually" >
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ "level".$key['level']. '<br />'; ?>
       <option value="<?= $cryp->encode("level".$key['level'],4); ?>"<? if("level".$key['level'] == $new_level || "level".$key['level'] == $level_last_2016){ echo "selected='selected'";}?>><?= "level".$key['level']; ?></option>
       <? } ?>
      
      </select>
      <?php } ?>
      </div>
      </div>
      </div>
      
      
      <div class="row">
     <div class="col-sm-2" style="color: #791454; margin-left:85%; top: 10px;">
          <?php if ($get_ropa_master[0]['edit_status']=='99') { ?>
				<?php /*?><input id="submit_date_reason" style="" type="submit" value="SUBMIT" class="btn btn-info" /><?php */?>
                <a href="javascript:void(0);" class="btn btn-info" onclick="return basic_correction_final('<?=$cryp->encode('lst',4)?>','<?=$cryp->encode($emp_id_fk,4)?>');">SUBMIT</a>
                <?php } ?>
	   </div>
      </div>
      <div class="row">
      		<b><div class="col-sm-12" align="center" style="margin-top:12px; color:#03371b;">
                     
                    <input <?php if($get_ropa_master[0]['status']=='2' || $get_ropa_master[0]['status']=='3' || $get_ropa_master[0]['status']=='4'){echo 'style="background-color:#d3d3d3;" disabled checked';}?>   name="final_found_correct"  id="final_found_correct" value="1" type="checkbox"  autocomplete="off"  onclick="return final_found_correct();"  /> 
                    <label for="final_found_correct" ><span style="margin: 0 4px 0 0;"></span></label> All the above informations are found correct as per manual calculation on ROPA 2019 </br> Please Refer Memorandum No: <?php echo $ropa_mem; ?>
                    
                     
                     
            </div>
            </b>
      </div>
      
     <div class="row">
      		<div class="col-sm-12" align="center" style="margin-top:12px;">
            		<button <?php if($get_ropa_master[0]['status']=='2' || $get_ropa_master[0]['status']=='3' || $get_ropa_master[0]['status']=='4'){echo 'style="background-color:#d3d3d3;" disabled checked';}else if($get_ropa_master[0]['status']=='1'){echo 'style="display:none;"';}?> id="ropa_2019_finalize" class="btn btn-info" onclick="return ins_finz_ropa_data();">SUBMIT</button>
            </div>
      </div>
      
      <div class="row">
      		<div class="col-sm-12" align="center" style="margin-top:12px;">
            		<?php if(
					($get_ropa_master[0]['status']=='2' && $get_ropa_master[0]['sent_status']=='0')  && $user=='zp')
					{
						echo '<div class="alert alert-success" style="text-align:center"><strong>Basic Pay Details For ROPA 2019 Has Been Updated Successfully & Sent To ACCOUNTANT</strong></div>';
						} else if
					
						($get_ropa_master[0]['status']=='2'   && ($user=='ps' || $user=='gp'))
						{echo '<div class="alert alert-success" style="text-align:center"><strong>Basic Pay Details For ROPA 2019 Has Been Updated Successfully.</strong></div>';
						}?>
            </div>
      </div>
<?php
 

?>



<?php if(($get_ropa_master[0]['status']==2 && $user=='gp' && $get_ropa_master[0]['edit_status']=='0')  || ($get_ropa_master[0]['status']==2 && $user=='ps' && $get_ropa_master[0]['edit_status']=='0') )


{?>
<div class="row">
	<div class="col-sm-12" align="center" style="margin-top:12px;">
		<a class="btn btn-success"  id="approve" onClick="send_action('approve', '<?=$cryp->encode($emp_id_fk,4)?>' );">FINAL SUBMIT</a>
		 
	</div>
</div>
<?php }?>

<?php if(($get_ropa_master[0]['status']==3 && $user=='gp' && $get_ropa_master[0]['edit_status']=='0' )  || ($get_ropa_master[0]['status']==3 && $user=='ps' && $get_ropa_master[0]['edit_status']=='0' )  )


{?>
<div class="row">
	<div class="col-sm-12" align="center" style="margin-top:12px;">
		<a class="btn btn-danger"  id="approve" onClick="send_action1('unlock', '<?=$cryp->encode($emp_id_fk,4)?>' );">UNLOCK</a>
		 
	</div>
</div>
<?php }?>








