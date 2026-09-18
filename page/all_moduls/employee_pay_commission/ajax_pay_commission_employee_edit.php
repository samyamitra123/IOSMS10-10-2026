<div id="full_div">



<?php

ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';



if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryp = new cryptography();


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
$emp_id_pk=$cryp->decode($_GET['id'],4); 


$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$db=new database();

?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<?php

$cryptoGraph=new cryptography();



?>
  

<?php 
if (isset($msg)){
echo $msg;
echo "<br/>";
}
if(isset($error_msg)){
echo $error_msg;
echo "<br/>";
}

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

?>

<?php


		
		//if($suspended_vaidation)

$get_emp_pay_scale_details=$db->fetch_table("SELECT status, ropa_2019_effective_date, cause FROM ropa_2019_emp_pay_scale_master 
											WHERE emp_id_fk='$emp_id_pk'");

if($get_emp_pay_scale_details[0]['status']==1 || $get_emp_pay_scale_details[0]['status']==2 || $get_emp_pay_scale_details[0]['status']==3 || $get_emp_pay_scale_details[0]['status']==4)
{
	$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");
	// end of added on 09_01_2019 for bank details update
	

	$emp_data = $db->fetch_table("
								SELECT 
									emp_cosolidated_pay ,
									emp_first_name,
									emp_second_name,
									emp_last_name,
									emp_pay_band,
									gp_id_fk,
									emp_pay_in_payband,
									emp_grade_pay,
									emp_pay_scale,
									emp_bank_name ,
									emp_bank_branch ,
									emp_branch_code ,
									emp_micr_no ,
									emp_acc_no ,
									emp_ifsc_no ,
									emp_form_status,
									emp_first_join_date,
									emp_id_const,
									ropa_9_emp_pay_in_payband,
									ropa_status
								FROM prd_employee_master
								WHERE emp_id_pk = '".$emp_id_pk."'
								
		
		");
	
	
			$emp_cosolidated_pay=$emp_data[0]['emp_cosolidated_pay'];
			$emp_pay_band=$emp_data[0]['emp_pay_band'];
			
			if($emp_data[0]['ropa_status']=='1')
			{
				$emp_pay_in_payband=$emp_data[0]['ropa_9_emp_pay_in_payband'];
			}
			else
			{
			$emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
			}
			 $emp_grade_pay=$emp_data[0]['emp_grade_pay'];
			$emp_pay_scale=$emp_data[0]['emp_pay_scale'];
		
			$emp_bank_name=$emp_data[0]['emp_bank_name'];
			$emp_bank_branch=$emp_data[0]['emp_bank_branch'];
			$emp_branch_code=$emp_data[0]['emp_branch_code'];
			$emp_micr_no=$emp_data[0]['emp_micr_no']=='0'?'':$emp_data[0]['emp_micr_no'];
			$emp_acc_no=$emp_data[0]['emp_acc_no'];
			$emp_ifsc_no=$emp_data[0]['emp_ifsc_no'];
			$emp_form_status=$emp_data[0]['emp_form_status'];
			//$emp_dcrb_opt=$emp_data[0]['emp_dcrb_opt'];
			//$emp_dcrb_option=$emp_data[0]['emp_dcrb_option'];
			//$annual_increment_status=$emp_data[0]['promotion_status'];
			$emp_first_join_date=$emp_data[0]['emp_first_join_date'];
			$emp_id_const=$emp_data[0]['emp_id_const'];
			$emp_name= $emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'];
			 
	
	?>


    
    <center><h1 style="color:#932203;">UPDATE EMPLOYEE BASIC PAY DETAILS</h1></center>
    <center> <h4 style="color:#932203; alignment-adjust:middle">(With reference to Employee's Service Book) </h4></center>
	
	<?php 
		/*if($get_emp_pay_scale_details[0]['status'] == '2'){ 
		
		?>
		<div class="downpdf" style="margin-left:89%"><a href="<?= $config['base_url']?>page/all_moduls/employee_pay_commission/pdf_pay_fixation.php?id=<?php echo $_GET['id']; ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>
	
	<?php	}*/
	?>
   

   
		<?php
        $db = new database();
        $get_r2019_epsm_for_pd=$db->fetch_table("SELECT ropa_2019_effective_date,edit_status FROM ropa_2019_emp_pay_scale_master 
        WHERE emp_id_fk = '".$emp_id_pk."'
        ");
        $ropa_2019_effective_date_for_pd=$get_r2019_epsm_for_pd[0]['ropa_2019_effective_date'];
        $ropa_2019_effective_date_year_for_pd=explode("-",$ropa_2019_effective_date_for_pd);
         $ropa_2019_effective_date_year_for_pd=$ropa_2019_effective_date_year_for_pd[2] -1; 
        
        $get_last_saved_year_for_pd=$db->fetch_table("SELECT MIN(year) as min_year from prd_emp_basic_pay_details_before_2019
        WHERE emp_id_fk='".$emp_id_pk."'");
		
		///////////////Added New
	$get_last_saved_year_for_pd_basic_data=$db->fetch_table("SELECT pay_band, grade_pay FROM prd_emp_basic_pay_details_before_2019
										WHERE emp_id_fk='".$emp_id_pk."' AND year='".$get_last_saved_year_for_pd[0]['min_year']."'");
	if(count($get_last_saved_year_for_pd_basic_data) > 0)
	{
		$newly_added_field_cnt_year=$get_last_saved_year_for_pd[0]['min_year']-1;
		$_SESSION['ppb_'.$newly_added_field_cnt_year]=$get_last_saved_year_for_pd_basic_data[0]['pay_band'];
		$_SESSION['gp_'.$newly_added_field_cnt_year]=$get_last_saved_year_for_pd_basic_data[0]['grade_pay'];
	}
	///////////////Added New
        if ($ropa_2019_effective_date_year_for_pd == $get_last_saved_year_for_pd[0]['min_year'])
        {
        ?>
        
        <?php /*?><div class="downpdf" align="right"><a href="<?= $config['base_url']?>page/intra_mad/ddo/ropa_2019/pdf_employe_ropa_2019_details.php?id=<?php echo $cryptoGraph->encode($emp_id_pk,4) ?>"><img src="<?= $config['base_url'] ?>themes/default/image/pdf_download.png"/></a></div>	
        </br><?php */?>
        <?php } ?>
        <?php 
        if(isset($msg)){
        echo $msg;
        echo "<br/>";
        }
        if(isset($error_msg)){
        echo $error_msg;
        echo "<br/>";
        }
        
        ?>
        
        <input type="hidden" id="user" name="user" value="<?=$user; ?>" />
        <input type="hidden" id="emp_id_pk" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>"/>
        
        
        
        <div class="col-sm-12">
        
        <div class="row mb-3">
						<div class="col-sm-3">
							<label for="inputPassword3" class="control-label" style="color: #246a8e;">EMPLOYEE NAME</label>
						</div>
                        
                        <div class="col-sm-3">
							<?=$emp_name;?>
						</div>
                        
                        <div class="col-sm-3">
							<label for="inputPassword3" class="control-label" style="color: #246a8e;">EMPLOYEE ID</label>
						</div>
                        
                        <div class="col-sm-3">
							<?=$emp_id_const;?>
						</div>
        </div>
        
        <!-- Basic Details for 2019-->
        <div class="col-sm-12 festival_advance_design" style="margin-bottom: 10px;">
        <center><h4 style="color:#03371b;">BASIC PAY DETAILS AS ON <?php echo '31-12-2019'; ?></h4></center> 
       
                    <div class="row mb-3">
						<div class="col-sm-2">
							<label for="inputPassword3" class="control-label" style="color: #246a8e;">First Joining in Service<span class="star_color">*</span></label>
						</div>
						<div class="col-sm-2">
							<input class="form-control" type="text" style="background-color:#d3d3d3;" disabled="disabled" value="<?=dateshow($emp_first_join_date)?>" />
						</div>
						<div class="col-sm-2">
							<label for="inputPassword3" class="control-label" style="color: #246a8e;">Pay in Pay Band<span class="star_color">*</span></label>
						</div>
						<div class="col-sm-2">
							<?php //$municipality_id_fk = substr($_SESSION['user_info']['stake_user'],0,7);    ?>
							
							<input type="text" class="form-control" name="ppb_2020" readonly="readonly" id="ppb_2020" style="background-color:#d3d3d3;cursor:no-drop;" placeholder="Pay in Pay Band" autocomplete="off" value="<? if(!empty($emp_pay_in_payband)){ echo $emp_pay_in_payband; }else{ echo $pay_in_payband;} ?>" onKeyPress="return keyRestrict(event,'0123456789');" maxlength="5" > 
						</div>	
						
						<div class="col-sm-2">
							<label for="inputPassword3" class="control-label" style="color: #246a8e;">Grade Pay<span class="star_color">*</span></label>
						</div>
						<div class="col-sm-2">
							<?
								$db = new database();
								//echo ("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND payband_code='$emp_pay_band' AND grade_code='$emp_grade_pay' order by grade_code ");die;
								$arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND payband_code='$emp_pay_band' AND grade_code='$emp_grade_pay' order by grade_code ");
							?>
							<!--<select class="form-control gradepay" style="background-color:#d3d3d3;cursor:no-drop; -webkit-appearance: none;" name="gp_2020" id="gp_2020" >
							<? 
							//foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
							<option value="<?= $key['grade_amount']; ?>" <? if($emp_grade_pay==$key['grade_code'] || $grade_pay==$key['grade_code']){ echo "selected";}?>><?= $key['grade_amount']; ?></option>
							<? //} ?>
							</select>-->
							
							<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="gp_2020" name="gp_2020" value="<?=$arr[0]['grade_amount'];?>" />
							
						</div>
                    </div>
				<?php
                $_SESSION['ppb_2019']=$emp_pay_in_payband;
                $get_grade_pay_value=$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master 
                WHERE grade_code='".$emp_grade_pay."'");
                $_SESSION['gp_2019']=$get_grade_pay_value[0]['grade_amount'];
                ?>
                    
        
                <div class="row mb-3">
                                <?
                                $db = new database();
                                
                                $ropa_2019_effective_date = $get_emp_pay_scale_details[0]['ropa_2019_effective_date'];
                                $cause = $get_emp_pay_scale_details[0]['cause'];
                                $arr2 = $db->fetch_table("select id_pk, rules from ropa_2019_scenario WHERE id_pk = '".$cause."'");
                                $ropa_2019_effective_reason = $arr2[0]['rules'];
                                $ropa_2019_effective_reason_id = $arr2[0]['id_pk'];
                                ?>
                                <div class="col-sm-12">
                                </div>
                  </div>
                        
    
                <div class="row mb-3">
						<div class="col-sm-2">
                            <label for="inputPassword3" class="control-label" style="color: #246a8e;">Pay Fixation Date<span class="star_color">*</span></label>
						</div>
						<div class="col-sm-2">
                            <input class="form-control" id="" type="text" style="background-color:#d3d3d3;" disabled="disabled" value="<?= $ropa_2019_effective_date ?>" />
						</div>
						<div class="col-sm-2">
                            <label for="inputPassword3" class="control-label" style="color: #246a8e;">Reason<span class="star_color">*</span></label>
						</div>
						<div class="col-sm-6">
                            <input class="form-control" type="text" style="background-color:#d3d3d3;" disabled="disabled" value="<?= $ropa_2019_effective_reason ?>" />
						</div>
						<div class="col-sm-4">
						</div>
                </div>
    </div>  
      <!-- End of Basic Details for 2019-->
   
    
    <!-- Basic Details for 2019-->
	<?php
	
	$get_r2019_epsm=$db->fetch_table("SELECT ropa_2019_effective_date FROM ropa_2019_emp_pay_scale_master 
										WHERE emp_id_fk = '".$emp_id_pk."'
										");
	$ropa_2019_effective_date=$get_r2019_epsm[0]['ropa_2019_effective_date'];
	$ropa_2019_effective_date_year=explode("-",$ropa_2019_effective_date);
	 $ropa_2019_effective_date_year=$ropa_2019_effective_date_year[2]; 
	
	$curr_yr=2019;
	
	
	$join_year_gap=($curr_yr-$ropa_2019_effective_date_year);
	$exp_ropa_2019_effective_date=explode("-",$ropa_2019_effective_date);
	$exp_ropa_2019_effective_date_year=$exp_ropa_2019_effective_date[2];
	
	if(strtotime($ropa_2019_effective_date) < strtotime('01-07-'.$exp_ropa_2019_effective_date_year)) 
	{
		$join_year_gap=$join_year_gap+1;
		$ropa_2019_effective_date_year=$ropa_2019_effective_date_year-1;
	}
	else if(strtotime($ropa_2019_effective_date) >= strtotime('01-07-'.$exp_ropa_2019_effective_date_year))
	{
		$join_year_gap=$join_year_gap;
		$ropa_2019_effective_date_year=$ropa_2019_effective_date_year;
	}
	
	
	
	
	$get_last_saved_year=$db->fetch_table("SELECT MIN(year) as min_year from prd_emp_basic_pay_details_before_2019
											WHERE emp_id_fk='".$emp_id_pk."'");
	if($get_last_saved_year[0]['min_year']!='')
	{
		
		 $year_to_show=$get_last_saved_year[0]['min_year']-1; 
	}
	else
	{
		
		$year_to_show=2019;
	}
	
	
	 if($ropa_2019_effective_reason_id != '7' && $ropa_2019_effective_reason_id != '8' && $ropa_2019_effective_reason_id != '9' && $ropa_2019_effective_reason_id != '10')
		{ 
	
	for($i=$join_year_gap; $i>=0; $i--)
	{
		
		
	?> 
    <form class="form-horizontal"  id="form_<?=$curr_yr?>" method="post" action="emp_basic_pay_details_update.php" onsubmit="return valid_code();"> 
    
		<?php
      
       
        
        
        
         if(strtotime($ropa_2019_effective_date) >= strtotime('01-07-'.$curr_yr))
	  {
			$ason_date=$ropa_2019_effective_date; 
			if($ason_date==$ropa_2019_effective_date)
			{
				$ason_date=date('d-m-Y', strtotime('+1 day',strtotime($ropa_2019_effective_date)));
			}
	  }
	  else
	  {
			$ason_date='01-07-'.$curr_yr;  
	  }
	  
	  //var_dump($ropa_2019_effective_reason_id); die;
	 
	  ?>
        
  <div  align="justify"class="col-sm-12  festival_advance_design" <?php if($curr_yr<$year_to_show){echo 'style="display:none;"';} ?> id="year_<?=$curr_yr?>" >
    <center><h4 class="heading" style="color: #e75d1e;">INCREMENTAL OR PROMOTIONAL CHANGE WEF 
		<?php
        if($curr_yr==2019)
        { 
        //$to_date=date('d-m').'-'.($curr_yr);
		//$to_date=date('d-m').'-'.'2020';
		$to_date='31-12-2019';
        echo $ason_date.' TO '.$to_date;
        }
        else
        { 
       
        if(strtotime($ropa_2019_effective_date) < strtotime('01-07-'.$curr_yr))
        {
        $current_year_plus_one=$curr_yr+1;
        $to_date=cal_days_in_month(CAL_GREGORIAN, 6, $current_year_plus_one).'-06-'.$current_year_plus_one;
        echo $ason_date.' TO '.$to_date;
        
        }
        else
        {
        $current_year_plus_one=$curr_yr+1;
        $to_date=cal_days_in_month(CAL_GREGORIAN, 6, $current_year_plus_one).'-06-'.$current_year_plus_one;
        echo $ason_date.' TO '.$to_date;  
        }
        }
        ?>
                    
                    
                    
    </h4></center> 
            
                    <div class="col-sm-12" align="right" >
                    <?php
                    if($get_last_saved_year[0]['min_year']==$curr_yr && $get_emp_pay_scale_details[0]['status']==1)
                    {
                    ?>
                    
                    <a href="javascript:void(0);" onclick="return delete_ropa_data('<?=$cryp->encode('yr',4)?>', '<?=$_GET['id']?>','<?=$curr_yr?>')" > <i class="fa fa-trash fa-2x" aria-hidden="true"  style="color:red "></i></a>
                    <?php
                    }	
                    ?>
                    
                    </div>
          

	
              <div class="row mb-3">
              <div class="col-sm-2">
					<label for="inputPassword3" class="control-label" style="color: #246a8e;">Date<span class="star_color">*</span></label>
                  </div>
                  <div class="col-sm-4">
					<label for="inputPassword3" class="control-label" style="color: #246a8e;">Annual / Promotional Increment<span class="star_color">*</span></label>
                  </div>
                  
                  <div class="col-sm-3">
					<label for="inputPassword3" class="control-label" style="color: #246a8e;">Increment Type<span class="star_color">*</span></label>
                  </div>
                  <div class="col-sm-3">
					<label for="inputPassword3" class="control-label" style="color: #246a8e;">Previous Grade Pay<span class="star_color">*</span></label>
                  </div>
              </div>
	  
	  <?php
	  //Added new for previous saved data
											
	  $select_saved_inc_data=$db->fetch_table("SELECT * FROM prd_emp_increment_details_before_2019 
											WHERE emp_id_fk='".$emp_id_pk."' AND effective_year='".$curr_yr."' 
											AND status in('1') ORDER BY increment_dtls_id_pk ASC");
											
											/*if($select_saved_inc_data=='')
											{
												$select_saved_inc_data='0';
											}
											else
											{
												$select_saved_inc_data=$select_saved_inc_data;
											}*/
											
	  $select_saved_pay_data=$db->fetch_table("SELECT * FROM prd_emp_basic_pay_details_before_2019 
											WHERE emp_id_fk='".$emp_id_pk."' AND year='".$curr_yr."'
											ORDER BY pay_dtls_id_pk ASC");
	  
	  
	  //Added new for previous saved data
	   
	  $current_yr_count=0;
	  
	  //Added new for previous saved data
	  if(count($select_saved_inc_data)>0)
	  {
			 $select_saved_inc_data=$select_saved_inc_data; 
	  }
	  else
	  {
			  $select_saved_inc_data=array(array()); 
	  }
	  
	  foreach($select_saved_inc_data as $saved_inc_data)
	  {
		  $current_yr_count++;
	  //Added new for previous saved data
	  
	 
	  ?>
	 <input type="hidden" name="field_cnt_<?=$curr_yr?>" id="field_cnt_<?=$curr_yr?>" value="<?=$current_yr_count?>" />
		<div class="row mb-3">
         <div class="col-sm-2">
				<input  <?php if( isset($saved_inc_data['effective_date']) || $select_saved_pay_data[0]['year']==$curr_yr){echo 'disabled style="background-color:#d3d3d3;"';}else{ ?>readonly style="background-color:#FFF;cursor:pointer;"<?php }?> type="text" class="form-control disable_year_<?=$curr_yr?>" id="incr_date_<?=$curr_yr.'_'.$current_yr_count?>" placeholder="DD-MM-YYYY" name="incr_date_<?=$curr_yr.'_'.$current_yr_count?>" onchange="select_incre_param(<?=$curr_yr?>,<?=$current_yr_count?>)" value="<?php if(isset($saved_inc_data['effective_date'])){echo $saved_inc_data['effective_date'];} ?>" onclick="change_css(id);">
		  </div>
		  <div class="col-sm-4">
				<select class="form-control disable_year_<?=$curr_yr?>" id="increment_name_<?=$curr_yr.'_'.$current_yr_count?>" name="increment_name_<?=$curr_yr.'_'.$current_yr_count?>" onchange="select_incre_param(<?=$curr_yr?>,<?=$current_yr_count?>)" <?php if(isset ($saved_inc_data['increment_dtls']) || $select_saved_pay_data[0]['year']==$curr_yr){echo 'disabled style="background-color:#d3d3d3;"';}?> onclick="change_css(id);" >
					<option value="">-Please Select-</option>
					<option <?php if (isset ($saved_inc_data['increment_dtls'])&& $saved_inc_data['increment_dtls']==1){echo 'selected';}?> class="annual_<?=$curr_yr?>" value="1">Annual Increment</option>
					<option <?php if(isset ($saved_inc_data['increment_dtls'])&& $saved_inc_data['increment_dtls']==2){echo 'selected';}?> class="promotion_<?=$curr_yr?>" value="2">Promotion</option>
					<option <?php if(isset ($saved_inc_data['increment_dtls'])&& $saved_inc_data['increment_dtls']==3){echo 'selected';}?> class="cas_<?=$curr_yr?>" value="3">CAS</option>
				</select>
		  </div>
		 
		  <div class="col-sm-3">
				<select <?php if(isset($saved_inc_data['increment_type'] )|| $select_saved_pay_data[0]['year']==$curr_yr){echo 'disabled style="background-color:#d3d3d3;"';}?> class="form-control disable_year_<?=$curr_yr?>" id="incr_type_<?=$curr_yr.'_'.$current_yr_count?>" name="incr_type_<?=$curr_yr.'_'.$current_yr_count?>" onchange="select_incre_param(<?=$curr_yr?>,<?=$current_yr_count?>)" onclick="change_css(id);" >
					<option value="">-Please Select-</option>
					<option <?php if(isset($saved_inc_data['increment_type']) && $saved_inc_data['increment_type']==1){echo 'selected';}?> class="single_<?=$curr_yr?>" id="single_<?=$curr_yr.'_'.$current_yr_count?>" value="1">Single Increment</option>
					<option <?php if (isset($saved_inc_data['increment_type']) && $saved_inc_data['increment_type']==2){echo 'selected';}?> class="double_<?=$curr_yr?>" id="double_<?=$curr_yr.'_'.$current_yr_count?>" value="2">Double Increment</option>
				</select>
		  </div>
				<div class="col-sm-3">
                        <?
						//echo ("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND gm.grade_code <='".$emp_grade_pay."' order by grade_code");die;
                        $arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk AND gm.grade_code <='".$emp_grade_pay."' order by grade_code");
                        ?>
                        
                        <select <?php if(isset($saved_inc_data['previous_grade_pay'])!=0 && (isset($saved_inc_data['increment_dtls'])==2 || isset($saved_inc_data['increment_dtls'])==3)){echo 'disabled style="background-color:#d3d3d3;"';}else if(isset($saved_inc_data['previous_grade_pay'])==0 && isset($saved_inc_data['increment_dtls'])==1){echo 'style="display:none;"';}else if((isset($saved_inc_data['increment_dtls'])!=1 || isset($saved_inc_data['increment_dtls'])!=2 || isset($saved_inc_data['increment_dtls'])!=3) && ($select_saved_pay_data[0]['year']==$curr_yr)){echo 'disabled style="background-color:#d3d3d3;"';}?> class="form-control gradepay disable_year_<?=$curr_yr?>" name="grade_pay_<?=$curr_yr.'_'.$current_yr_count?>" id="grade_pay_<?=$curr_yr.'_'.$current_yr_count?>" onchange="select_incre_param(<?=$curr_yr?>,<?=$current_yr_count?>)" onclick="change_css(id);" >

                        <option value="">-Please Select-</option>
                        <? foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
                        <option <?php if(isset($saved_inc_data['previous_grade_pay']) && $saved_inc_data['previous_grade_pay']==$key['grade_amount']){echo 'selected';}?> value="<?= $key['grade_amount']; ?>"><?= $key['grade_amount']; ?></option>
                        <? } ?>
					</select>
				</div>
	    </div>
			
	<?php
	//Added new for previous saved data
			//$current_yr_count++;
			$current_yr_count_arr[$curr_yr]=$current_yr_count;
	  }
	//Added new for previous saved data
	?>  
              <div id="current_gradepay_div_<?=$curr_yr?>">
                    
              </div>
	  
              <div id="add_incr_<?=$curr_yr?>">
              
              </div>
	  
              <div class="row mb-3 add_spouse">
					<?php
                    if($select_saved_pay_data[0]['year']!=$curr_yr)
                    {
						
                    ?>
                        <div class="col-sm-1" style="display:" id="add_more_fd_hide_<?=$curr_yr?>">
                            <?php /*?><a style="text-align:left;" id="add_more_fd_<?=$curr_yr?>" class="btn btn-success disable_year_<?=$curr_yr?>" title="Add more" onclick="return dynInput_incr_type(<?=$curr_yr?>);">+</a><?php */?>
                            <a style="text-align:left;" id="add_more_fd_<?=$curr_yr?>" class="btn btn-success disable_year_<?=$curr_yr?>" title="Add more" onclick="return dynInput_incr_type(<?=$curr_yr?>,'<?=$ason_date?>','<?=$to_date?>');">+</a>
                       </div>
                    <?php
                    }
                    ?>
              </div>
	  
	  <center><h4 style="color:#03371b;">BASIC PAY DETAILS AS ON <?=date("d-m-Y", strtotime('-1 day', strtotime($ason_date)))?></h4></center> 
            <div class="row mb-3">
                    <div class="col-sm-2">
						<label for="inputPassword3" class="control-label" style="color: #246a8e;">Pay in Pay Band<span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-2">
                    <?php if ($select_saved_pay_data[0]['edit_status']!='99') { ?>
                    
          
                    <input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="ppb_<?=$curr_yr?>" placeholder="Pay in Pay Band" name="ppb_<?=$curr_yr?>" value="<?=$select_saved_pay_data[0]['pay_band']?>" />
                    <?php } else { ?>
           <input type="text" class="form-control" id="ppb_<?=$curr_yr?>" placeholder="Pay in Pay Band" name="ppb_<?=$curr_yr?>" value="<?=$select_saved_pay_data[0]['pay_band']?>" />
           <?php } ?>
                    </div>
                    <div class="col-sm-2">
						<label for="inputPassword3" class="control-label" style="color: #246a8e;">Grade Pay<span class="star_color">*</span></label>
                    </div>
                    <div class="col-sm-2">
						<input readonly="readonly" style="background-color:#d3d3d3;cursor:no-drop;" type="text" class="form-control" id="gp_<?=$curr_yr?>" placeholder="Grade Pay" name="gp_<?=$curr_yr?>" value="<?=$select_saved_pay_data[0]['grade_pay']?>" />
                    </div>
                   
                    
                    <div class="col-sm-4" style="color: #03371b;">
                    
						<input <?php if($select_saved_pay_data[0]['year']==$curr_yr){echo "checked disabled";}?> type="checkbox"  autocomplete="off" class="disable_year_<?=$curr_yr?>" value="1" id="found_correct_<?=$curr_yr?>" name="found_correct_<?=$curr_yr?>"   onclick="return open_new_year(<?=$curr_yr?>,<?=$current_yr_count?>,'<?=$cryp->encode($ason_date,4)?>','<?=$cryp->encode($to_date,4)?>')" />
						Pay in Pay Band and Grade Pay found correct as on <?=date("d-m-Y", strtotime('-1 day', strtotime($ason_date)))?>
						<label for="found_correct_<?=$curr_yr?>" ><span style="margin: 0 4px 0 0;"></span></label>
                    
                    </div>
                    
                    
                     <div class="col-sm-2" style="color: #791454; margin-left:35%;">
            <?php if ($select_saved_pay_data[0]['edit_status']=='99') { ?>
      
            <a href="javascript:void(0);" class="btn btn-info" onclick="return basic_correction('<?=$cryp->encode('seg',4)?>','<?=$cryp->encode($emp_id_pk,4)?>','<?=$curr_yr?>');">SUBMIT</a>
            <?php } ?>
            </div>
               </div>
           </div>
            </form>
            
       
	     
	
	 
      
     
	<!-- End of Basic Details for 2019-->
    
    <script>
	$(function() {
		
		$( "#incr_date_<?=$curr_yr?>_<?=$current_yr_count?>").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd-mm-yy',
			minDate:'<?=$ason_date?>',
			maxDate:'<?=$to_date?>'	
		});
	});
	</script>
	
	<?php
		$curr_yr--;
	
	
}

}
	else{ ?>
		<div class="row mb-3">
			<div class="col-sm-12" style="margin-left:40%;">
				<input id="submit_revoke" style="margin-top: 20px;" type="submit" value="Revoke" class="btn btn-success" onClick="show_revoke();"/>
			</div>
		</div>
	<?php  //return false(); 
	
	}
	?>


            <div class="row mb-3">
                <div class="col-sm-12" align="center" style="margin-bottom: 10px;">
                <?php 
                
                ?>
                
                <button type="submit" class="btn btn-info" <?php if($ropa_2019_effective_date_year!=$get_last_saved_year[0]['min_year']){?>style="display:none;" <?php } ?> id="calculate_ropa_2019" onclick="return calculate_ropa_2019();" >Calculate Revised Pay Structure under ROPA 2019</i></button>
                <?php
                // }
                ?>
                </div>
            </div>
	  
	  
            <div class="row mb-3" id="ropa_pay_2019">
            
            </div>
      </div>
     
      


<div class="clear"></div>
    

  


	

  <?php  @pg_close($con); ?>
  
    <style>
  .loan_id
  	{
	  width:800px;
	  border:1px solid #000000;
	  padding:9px;
	  margin-bottom:5px;
	 }
  .delete_one
  	{
	  float:right;
	  }
  </style>
  
  <?php
	$get_ropa_master=$db->fetch_table("SELECT status FROM ropa_2019_emp_pay_scale_master
														WHERE emp_id_fk='$emp_id_pk'");
	if($get_ropa_master[0]['status']==2 || $get_ropa_master[0]['status']==3 || $get_ropa_master[0]['status']==4)
	{
	?>
	<script>
		
		
		var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
		var emp_first_join_year="<?=$ropa_2019_effective_date_year?>"; 
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_ropa_2019_calculation.php?emp_id_pk='+emp_id_pk+'&emp_first_join_year='+emp_first_join_year, function(data){
			
			$("#calculate_ropa_2019").attr('disabled','disabled');
			$("#calculate_ropa_2019").css('background-color','#d3d3d3');
			$("#ropa_pay_2019").html(data);
			$("#ropa_2019_finalize").attr('disabled','disabled');
			$("#ropa_2019_finalize").css('background-color','#d3d3d3');
		});
	</script>
	<?php
	}
	?>
    
     <script>
	$(document).ready(function(){
		//$("#loader_id").html('<img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />');
		
		var year_to_show='<?=$year_to_show?>';
		var year_to_show_prev=Number(year_to_show)+Number(1);
		$("#ppb_"+year_to_show).val($("#ppb_"+year_to_show_prev).val());
		$("#gp_"+year_to_show).val($("#gp_"+year_to_show_prev).val());
	});
	</script>
    
     <script>
	function delete_ropa_data(to_be_deleted, emp_id_pk, curr_yr)
	{
		//alert(emp_id_pk);
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?emp_id_pk='+emp_id_pk+'&update='+to_be_deleted+'&curr_yr='+curr_yr, function(data){
			//alert(data);
			if(data.trim()==1)
			{
				//alert(data);
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
				 	$("#full_div").html(data);
		   		});
			}
		});
	}
	
	
	function show_revoke()
	{
		$('#sent_revoke').modal('show');
	}


	function revoke(){
		
		var update = '<?=$cryptoGraph->encode('revoke',4);?>';
		var flag = '1';
		var emp_id_pk = $('#emp_id_pk').val();
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?emp_id_pk='+emp_id_pk+'&update='+update+'&flag='+flag, function(data){
			if(data.trim()==1)
			{
				//alert(data);
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
					$("#full_div").html(data);
					$("#sent_revoke").modal("hide")
				});
			}
		});	
	}
	
	
	</script>
    
     <script>
	function final_found_correct()
	{
		
		if($("#final_found_correct:checked").val()==1)
		{
			$("#ropa_2019_finalize").show();
		}
		else
		{
			$("#ropa_2019_finalize").hide();
		}
	}
	</script>
    <script>
	function basic_correction(field_type,emp_id_pk,curr_yr)
	{
		//alert(1234);
		var ppb_year=$("#ppb_"+curr_yr).val();
		//alert(ppb_year);
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_correction.php?emp_id_pk='+emp_id_pk+'&curr_yr='+			curr_yr+'&field_type='+field_type+'&ppb='+ppb_year, function(data)
		{
			//alert(data);
			if(data.trim()==1)
			{
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
					$("#full_div").html(data);
				});
			}
		});
	}
	
	function basic_correction_final(field_type,emp_id_pk)
	{
		//alert(1234);
		var bp_01_01_2020_manually=$("#bp_01_01_2020_manually").val();
		var lvl_01_01_2020=$("#level_2020_manually").val();
		//alert(ppb_year);
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_correction.php?emp_id_pk='+emp_id_pk+'&lvl_01_01_2020='+			lvl_01_01_2020+'&field_type='+field_type+'&bp_01_01_2020_manually='+bp_01_01_2020_manually, function(data)
		{
			//alert(data);
			if(data.trim()==1)
			{
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
					$("#full_div").html(data);
				});
			}
			else
			{
				alert(data.trim());
			}
		});
	}
	
	
	function ins_finz_ropa_data()
	{
		//alert(4);
		var user1=$('#user').val();	
		//alert()
		var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
		var emp_id='<?=$emp_id_pk;?>';
		var update='<?=$cryptoGraph->encode('finz',4)?>';
		var effective_date_ff='<?=isset($effective_date_ff)?>';
				
		var basic=$("#bp_01-01-2020").val();
		var lvl=$("#lvl_01-01-2020").val();
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?emp_id_pk='+emp_id_pk+'&update='+update+'&basic='+basic+'&level='+lvl+'&effective_date_ff='+effective_date_ff, function(data){
		//alert(data);
			//alert(data);
			if(data.trim()=='success')
			{
				//alert("#a_"+emp_id);
			if(user1=='zp')
			{
				//alert(1111111111111);
			$("#a_"+emp_id).html('<span style="color:#660066;font-weight:bold">FORWARDED TO ACCOUNTANT</span>');
			}
			else if(user1=='gp' || user1=='ps')
			{
				//alert(22222222222222);
				$("#a_"+emp_id).html('<span style="color:#660066;font-weight:bold">PAY FIXATION FINALIZED</span>');
			}
			
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
					$("#full_div").html(data);
					
				});
			}
			else
			{
				alert(data.trim());
			}
		});	
	}
	
	
	function calculate_ropa_2019()
	{
		
		var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
		var emp_first_join_year="<?=isset($ropa_2019_effective_date_year);?>"; 
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_ropa_2019_calculation.php?emp_id_pk='+emp_id_pk+'&emp_first_join_year='+emp_first_join_year, function(data){
			//alert(data);
			$("#calculate_ropa_2019").attr('disabled','disabled');
			$("#calculate_ropa_2019").css('background-color','#d3d3d3');
			$("#ropa_pay_2019").html(data);
		});
	}
	
	</script>
    
   
    
    <script>
	var annual_increment_given=0;
	var double_increment_given=0;
	
	function open_new_year(curr_yr,current_yr_count,ason_date,to_date)
	{
		//alert(6);
		
		var emp_first_join_year="<?=$ropa_2019_effective_date_year?>";
		
		if($("#found_correct_"+curr_yr+":checked").val()==1)
		{
			var new_curr_yr=(Number(curr_yr)-Number(1));
			var curr_sal_yr=Number(curr_yr)+Number(1);
			var curr_sal_ppb=$("#ppb_"+curr_sal_yr).val();
			var curr_sal_gp=$("#gp_"+curr_sal_yr).val();
			var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
			var emp_id_const='<?=$cryptoGraph->encode($emp_id_const,4)?>';
			var update='<?=$cryptoGraph->encode('inc',4)?>';
			
			$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?curr_sal_ppb='+curr_sal_ppb+'&curr_sal_gp='+curr_sal_gp+'&field_cnt_year='+curr_yr+'&emp_id_pk='+emp_id_pk+'&emp_id_const='+emp_id_const+'&update='+update+'&ason_date='+ason_date+'&to_date='+to_date,$("#form_"+curr_yr).closest("form").serialize(), function(data){
				if(data.trim()==1)
				{
					
					$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
						$("#full_div").html(data);
					});
				}
				else
				{
					
					$("#found_correct_"+curr_yr).prop('checked', false);
					alert(data.trim());		
				}
			});
		}
	}
	
	function select_incre_param(curr_yr,current_yr_count)
	{
		
		//alert(123);
		var increment_name=$("#increment_name_"+curr_yr+"_"+current_yr_count).val();
		var incr_type=$("#incr_type_"+curr_yr+"_"+current_yr_count).val();
		//alert(increment_name);
		if(increment_name==1)
		{
			$("#single_"+curr_yr+"_"+current_yr_count).attr('selected', true);
			$("#grade_pay_"+curr_yr+"_"+current_yr_count).hide();
			
			
			$(".annual_"+curr_yr).hide();
			annual_increment_given++;
			//double_increment_given++;
		}
		/*else if(increment_name==2 || increment_name==3)
		{
			//alert(789);
			//$("#incr_date_"+curr_yr+"_"+current_yr_count).val('');
			//$("#incr_type_"+curr_yr+"_"+current_yr_count).val('01-07-'+curr_yr);
			//$("#double_"+curr_yr+"_"+current_yr_count).show();
			//$("#single_"+curr_yr+"_"+current_yr_count).attr('selected', false);
			//$("#grade_pay_"+curr_yr+"_"+current_yr_count).show();
			
			if(incr_type==2)
			{
				$(".double_"+curr_yr).hide();
				$(".annual_"+curr_yr).hide();
				double_increment_given++;
			}
		}*/
		
		
		/////////////////////////Validations/////////////////////////////////
		/*if($("#increment_name_"+curr_yr+"_"+current_yr_count).val()=='')
		{
			alert("Please Select Annual / Promotional Increment.");
			$("#increment_name_"+curr_yr+"_"+current_yr_count).focus();
			return false;
		}
		else if($("#incr_date_"+curr_yr+"_"+current_yr_count).val()=='')
		{
			alert("Please Select Date.");
			$("#incr_date_"+curr_yr+"_"+current_yr_count).focus();
			return false;
		}
		else if($("#incr_type_"+curr_yr+"_"+current_yr_count).val()=='')
		{
			alert("Please Select Increment Type.");
			$("#incr_type_"+curr_yr+"_"+current_yr_count).focus();
			return false;
		}
		else if($("#increment_name_"+curr_yr+"_"+current_yr_count).val()!=1 && $("#grade_pay_"+curr_yr+"_"+current_yr_count).val()=='')
		{
			alert("Please Select Previous Grade Pay.");
			$("#grade_pay_"+curr_yr+"_"+current_yr_count).focus();
			return false;
		}*/
		/////////////////////////Validations/////////////////////////////////
		
		
		var curr_sal_yr=Number(curr_yr)+Number(1);
		var curr_sal_ppb=$("#ppb_"+curr_sal_yr).val();
		var curr_sal_gp=$("#gp_"+curr_sal_yr).val();
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation.php?curr_sal_ppb='+curr_sal_ppb+'&curr_sal_gp='+curr_sal_gp+'&field_cnt_year='+curr_yr,$("#form_"+curr_yr).closest("form").serialize(), function(data){
			
			$("#current_gradepay_div_"+curr_yr).html(data);
			var ppb_gp=$("#ppbgp_"+curr_yr).val().split("-");
			//$("#ppb_"+curr_yr).val(new_data[0]);
			//$("#gp_"+curr_yr).val(new_data[1]);
			$("#ppb_"+curr_yr).val(ppb_gp[0]);
			$("#gp_"+curr_yr).val(ppb_gp[1]); 
			//Added on 28-11-2019 for validation
			//alert(ppb_gp[0]);
			//alert(ppb_gp[1]);
			//alert(ppb_gp[2]);
			//return false;
			//alert(data);
			//return false;
			if(ppb_gp[2]=='QRVMSZlRJhnVGFUP')
			{             
				//alert(11);
				$("#ppb_"+curr_yr).prop('readonly', false);
				$("#ppb_"+curr_yr).val('');
				$("#ppb_"+curr_yr).css('background-color', '#FFFFFF');
				$("#ppb_"+curr_yr).css('cursor', 'auto');
			}
			else
			{
				//alert(12);
				$("#ppb_"+curr_yr).prop('readonly', true);
				$("#ppb_"+curr_yr).css('background-color', '#d3d3d3');
				$("#ppb_"+curr_yr).css('cursor', 'no-drop');
				//Added on 28-11-2019 for validation
			}
			
		});
	}
	
	
	function change_css(id){
		//alert(id);
		$("#"+id).css("background-color", "lemonchiffon"); 
	}
	
	
	
	function pre_basic(curr_yr,current_yr_count)
	{
		//alert(8);
		
		var curr_sal_yr=Number(curr_yr)+Number(1);
		var curr_sal_ppb=$("#ppb_"+curr_sal_yr).val();
		var curr_sal_gp=$("#gp_"+curr_sal_yr).val();
			
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation.php?curr_sal_ppb='+curr_sal_ppb+'&curr_sal_gp='+curr_sal_gp+'&field_cnt_year='+curr_yr,$("#form_"+curr_yr).closest("form").serialize(), function(data){
			
			var new_data=data.split("-");
			//alert(new_data[2]);
			$("#ppb_"+curr_yr).val(new_data[0]);
			$("#gp_"+curr_yr).val(new_data[1]);
			//alert(data.1);
		});
		//alert(1234);
	}
	</script>
    <script>
	<?php if(isset($current_yr_count_arr[2019])){?>var current_yr_count_2019=<?=$current_yr_count_arr[2019]?>;<?php }?>
	<?php if(isset($current_yr_count_arr[2018])){?>var current_yr_count_2018=<?=$current_yr_count_arr[2018]?>;<?php }?>
	<?php if(isset($current_yr_count_arr[2017])){?>var current_yr_count_2017=<?=$current_yr_count_arr[2017]?>;<?php }?>
	<?php if(isset($current_yr_count_arr[2016])){?>var current_yr_count_2016=<?=$current_yr_count_arr[2016]?>;<?php }?>
	<?php if(isset($current_yr_count_arr[2015])){?>var current_yr_count_2015=<?=$current_yr_count_arr[2015]?>;<?php }?>
	
	function dynInput_incr_type(curr_yr,ason_date,to_date)
	{
		
		if(curr_yr==2015)
		{
			var current_yr_count=current_yr_count_2015;
			current_yr_count_2015++;
		}
		else if(curr_yr==2016)
		{
			var current_yr_count=current_yr_count_2016;
			current_yr_count_2016++;
		}
		else if(curr_yr==2017)
		{
			var current_yr_count=current_yr_count_2017;
			current_yr_count_2017++
		}
		else if(curr_yr==2018)
		{
			var current_yr_count=current_yr_count_2018;
			current_yr_count_2018++
		}
		else if(curr_yr==2019)
		{
			var current_yr_count=current_yr_count_2019;
			current_yr_count_2019++
		}
		
		current_yr_count++;
		//alert('1234');
		//var wrapper = $("#add_relation");
		if(Number(annual_increment_given) > 0)
		{
			var display_annual="style=display:none;";
		}
		else
		{
			var display_annual="";
		}
		
		if(Number(double_increment_given) > 0)
		{
			var display_double="style=display:none;";
		}
		else
		{
			var display_double="";
		}
		
		var wrapper = $("#add_incr_"+curr_yr);
		
		$(wrapper).append('<div class="row mb-3">'+
		'<div class="col-sm-2">'+
				'<input placeholder="DD-MM-YYYY" style="" type="text" class="form-control disable_year_'+curr_yr+'" id="incr_date_'+curr_yr+'_'+current_yr_count+'" name="incr_date_'+curr_yr+'_'+current_yr_count+'" onchange="select_incre_param('+curr_yr+','+current_yr_count+')" onclick="change_css(id);" />'+
		  '</div>'+
		  '<div class="col-sm-4">'+
				'<select class="form-control disable_year_'+curr_yr+'" id="increment_name_'+curr_yr+'_'+current_yr_count+'" name="increment_name_'+curr_yr+'_'+current_yr_count+'" onchange="select_incre_param('+curr_yr+','+current_yr_count+')" onclick="change_css(id);" >'+
					'<option value="">-Please Select-</option>'+
					'<option '+display_annual+' '+display_double+' class="annual_'+curr_yr+'" value="1">Annual Increment</option>'+
					'<option class="promotion_'+curr_yr+'" value="2">Promotion</option>'+
					'<option class="cas_'+curr_yr+'" value="3">CAS</option>'+
				'</select>'+
		  '</div>'+
		  
		  '<div class="col-sm-3">'+
				'<select class="form-control disable_year_'+curr_yr+'" id="incr_type_'+curr_yr+'_'+current_yr_count+'" name="incr_type_'+curr_yr+'_'+current_yr_count+'" onchange="select_incre_param('+curr_yr+','+current_yr_count+')" onclick="change_css(id);" >'+
					'<option value="">-Please Select-</option>'+
					'<option class="single_'+curr_yr+'" id="single_'+curr_yr+'_'+current_yr_count+'" value="1">Single Increment</option>'+
					'<option '+display_double+' class="double_'+curr_yr+'" id="double_'+curr_yr+'_'+current_yr_count+'" value="2">Double Increment</option>'+
				'</select>'+
		  '</div>'+
		  '<div class="col-sm-3">'+
				<?
				$arr = $db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master gm,prd_payband_master pm where pm.payband_id_pk=gm.payband_id_fk and gm.grade_code <='".$emp_grade_pay."' order by grade_code");
				?>
				'<select class="form-control gradepay disable_year_'+curr_yr+'" name="grade_pay_'+curr_yr+'_'+current_yr_count+'" id="grade_pay_'+curr_yr+'_'+current_yr_count+'" onchange="select_incre_param('+curr_yr+','+current_yr_count+')" onclick="change_css(id);" >'+
				'<option value="">-Please Select-</option>'+
				<? foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
				'<option value="<?= $key['grade_amount']; ?>"><?= $key['grade_amount']; ?></option>'+
				<? } ?>
				'</select>'+
		  '</div>'+
	  '</div>');
	  
	  $("#field_cnt_"+curr_yr).val(current_yr_count);

		
		$(function() 
		{
		$( "#incr_date_"+curr_yr+'_'+current_yr_count).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'dd-mm-yy',
				
				minDate: ason_date,
				maxDate: to_date
				
				
			});
		});
	}
	</script>
    <?php  @pg_close($con); ?>
	<style>
	  .festival_advance_design
		{
		  width:817px;
		  border:1px solid #000000;
		  padding:4px;
		  margin-bottom:10px;
		 }
	  .delete_one
		{
		  float:right;
		}
		  
	  
	</style>
<?php
}
else
{
	
	$emp_data = $db->fetch_table("SELECT emp_first_join_date FROM prd_employee_master WHERE emp_id_pk = '".$emp_id_pk."'
								");
								
	$emp_first_join_date=$emp_data[0]['emp_first_join_date'];
	
	$emp_first_join_date_reverse=date('d-m-Y', strtotime($emp_data[0]['emp_first_join_date']));

	//var_dump($emp_first_join_date_reverse); die;
	if(strtotime($emp_first_join_date_reverse) < strtotime('01-01-2016'))
	{
		$emp_first_join_date_reverse='01-01-2016';
	}
	
	$emp_first_join_year=substr($emp_first_join_date,0,4);
	
	if($emp_first_join_year < 2016)
	{
		//echo 1; die;
		$emp_first_join_year=2016;
		$effective_date_ff=$emp_first_join_year.'-'.substr($emp_first_join_date,5,7);	
		$effective_date_ff=date("01-01-Y", strtotime($effective_date_ff));
		$senario_not=2;
		$selected=1;
	}
	else if($emp_first_join_year >= 2016)
	{
		//echo 2; die;
		$effective_date_ff=date("d-m-Y", strtotime($emp_first_join_date));
		$senario_not=1;
		$selected=2;
	}
?>
	<div class="row" id="cont">
		  <div class="col-lg-12 col-md-8 col-sm-8"> 
          <div class="col-sm-12">
          <center><h3 style="color: #e75d1e;">DATE OPTED FOR ROPA 2019 PAY FIXATION</h3></center>
           <br />
           <h5>***Please change Date & Reason in case of Employee is not present in duty on 01-01-2016. </h5>
          <br />
          		<div class="col-sm-12 festival_advance_design" style="padding-top: 10px;">
                	<div class="row mb-3">
						<div class="col-sm-2">
                        	<label for="inputPassword3" class="control-label" style="color: #246a8e;"> Pay Fixation Date<span class="star_color">*</span></label>
                            
                        </div>
                        <div class="col-sm-4">
                        	<input class="form-control" placeholder="DD-MM-YYYY" type="text" id="ropa_2019_date" name="ropa_2019_date" value="<?=$effective_date_ff?>"   <?php // if ($selected == '2'){ echo 'readonly=readonly style=background-color:#d3d3d3;cursor:no-drop;pointer-events:none;'; }else{ echo 'readonly style=background-color:#FFF;cursor:pointer;';} ?> onkeydown="return false;" />
                        </div>
                        
                        <div class="col-sm-2">
                        	<label for="inputPassword3" class="control-label" style="color: #246a8e;">Reason<span class="star_color">*</span></label>
                        </div>
                        <div class="col-sm-4" style="margin-left: -65px;">
                        	<select class="form-control" name="ropa_2019_reason" id="ropa_2019_reason"  <?php //if ($selected == '2'){ echo 'readonly=readonly style=background-color:#d3d3d3;cursor:no-drop;;pointer-events:none;'; } ?> >
                            	<option value="">-Please Select-</option>
                                
                                 <?php
								 //var_dump($emp_first_join_date_reverse); die;
								 //if ($selected == '2')
								if ($emp_first_join_date_reverse <= '01-01-2016')
								{
									$get_ropa_2019_scenario=$db->fetch_table("SELECT id_pk, rules, status FROM ropa_2019_scenario 
															WHERE status in(1,3,4) ORDER BY id_pk ASC");
									foreach($get_ropa_2019_scenario as $scenario)
									{
									?>
										<option  <?php if($selected==$scenario['status']){echo 'selected'; }?> value="<?=$cryp->encode($scenario['id_pk'],4)?>"><?=$scenario['rules']?></option>
									
									<?php
									} 
								}
								elseif($emp_first_join_date_reverse > '01-01-2016'){
									//var_dump($emp_first_join_date_reverse); die;
									?>
									
									
									<?php
									
									$get_ropa_2019_scenario=$db->fetch_table("SELECT id_pk, rules, status FROM ropa_2019_scenario 
															WHERE status in(2,4) ORDER BY id_pk DESC");
									foreach($get_ropa_2019_scenario as $scenario)
									{
									?>
										<option <?php if($selected==$scenario['status']){echo 'selected'; }?> value="<?=$cryp->encode($scenario['id_pk'],4)?>"><?=$scenario['rules']?></option>
									
									<?php
									}
								}
								?>
                            </select>
                        </div>
                  	</div>

                    <div class="row mb-3">
                        <div class="col-sm-12" style="margin-left:40%;">
                       			 <input id="submit_date_reason" style="margin-top: 20px;" type="submit" value="SUBMIT" class="btn btn-info" />
                        </div>
                    </div>
                </div>
          </div>
          </div>
    </div>
    
    <style>
  	.festival_advance_design
	{
	  /*width:802px;*/
	  border:1px solid #000000;
	  padding:3px;
	  margin-bottom:5px;
	}
	h3.heading
	{
		color:#b73387;
		font-weight:bold;	
	}
	</style>
    
    
    <?php 
	//var_dump($emp_first_join_date_reverse); die;
		if(strtotime($emp_first_join_date_reverse) <= strtotime('25-09-2019')){
			//echo 34543; die;
			$emp_end_date_reverse='25-09-2019';
		}
		else if(strtotime($emp_first_join_date_reverse) > strtotime('25-09-2019')){
			//echo 999; die;
			$emp_end_date_reverse= '31-12-2019';
		}
	
	?>
    
    <script>
	$(function() {
		
		$("#ropa_2019_date").datepicker({
			//alert(11);
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd-mm-yy',
			
			minDate:'<?=$emp_first_join_date_reverse?>',
			//maxDate:'+0D'
			maxDate:'<?=$emp_end_date_reverse ?>'
		});
	});
	</script>
    
    <script>
	$("#ropa_2019_date").change(function(){
		
			
		var ropa_date=$("#ropa_2019_date").val();
		var ropa_reason=$("#ropa_2019_reason").val();
		//if(ropa_date!='01-01-2016' && ropa_reason=='QRVMSZlRJhnVGFUP')
		//{
			$("option:selected").prop("selected", false);
		//}
	});
	
	$("#submit_date_reason").click(function(){
		
		var ropa_date=$("#ropa_2019_date").val();
		var ropa_reason=$("#ropa_2019_reason").val();
		
		var update='<?=$cryp->encode('r2019_epsm',4)?>';
		var emp_id_pk='<?=$cryp->encode($emp_id_pk,4)?>';
		
		
		
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?ropa_date='+ropa_date+'&ropa_reason='+ropa_reason+'&update='+update+'&emp_id_pk='+emp_id_pk, function(data){
			//alert(data);
			if(data.trim()=='1')
			{
				//alert(1234);
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
				 	$("#full_div").html(data);
		   		});
			}
			else
			{
			alert(data.trim());		
			}
		});
	});
	</script>
	
	 <script>
	/*function ins_finz_ropa_data()
	{
		//alert(4);
		var user1=$('#user').val();	
		//alert()
		var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
		var emp_id='<?=$emp_id_pk;?>';
		var update='<?=$cryptoGraph->encode('finz',4)?>';
		var effective_date_ff='<?=$effective_date_ff?>';
				
		var basic=$("#bp_01-01-2020").val();
		var lvl=$("#lvl_01-01-2020").val();
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_basic_pay_calculation_update.php?emp_id_pk='+emp_id_pk+'&update='+update+'&basic='+basic+'&level='+lvl+'&effective_date_ff='+effective_date_ff, function(data){
		//alert(data);
			//alert(data);
			if(data.trim()=='success')
			{
				//alert("#a_"+emp_id);
			if(user1=='zp')
			{
				//alert(1111111111111);
			$("#a_"+emp_id).html('<span style="color:#660066;font-weight:bold">FORWARDED TO ACCOUNTANT</span>');
			}
			else if(user1=='gp' || user1=='ps')
			{
				//alert(22222222222222);
				$("#a_"+emp_id).html('<span style="color:#660066;font-weight:bold">PAY FIXATION FINALIZED</span>');
			}
			
				$.post('<?=$config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+emp_id_pk, function(data){
					$("#full_div").html(data);
					
				});
			}
			else
			{
				alert(data.trim());
			}
		});	
	}*/
	
	
	/* function calculate_ropa_2019()
	{
		
		var emp_id_pk='<?=$cryptoGraph->encode($emp_id_pk,4)?>';
		var emp_first_join_year="<?=isset($ropa_2019_effective_date_year);?>"; 
		
		
		$.get('<?php echo $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_ropa_2019_calculation.php?emp_id_pk='+emp_id_pk+'&emp_first_join_year='+emp_first_join_year, function(data){
			//alert(data);
			$("#calculate_ropa_2019").attr('disabled','disabled');
			$("#calculate_ropa_2019").css('background-color','#d3d3d3');
			$("#ropa_pay_2019").html(data);
		});
	}*/
	</script>
<?php

}

?>

  

  
  
  