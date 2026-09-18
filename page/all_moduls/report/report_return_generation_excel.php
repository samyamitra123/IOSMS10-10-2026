<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$crypto = new cryptography();

	 //$stake=$_POST['stake'];
	 $f_year=$crypto->decode($_POST['f_year'],3); 
	  $type=$_POST['stake'];
	
	//$district_id_pk_arr=$_POST['arr1'];
	//print_r($district_id_pk_arr);die;
	//$count_dist=count($district_id_pk_arr);
	//$count_dist=$count_dist-1;
	//$dist_i=0;
	//$new_dist_id=array();
	/*while($dist_i <= $count_dist)
	{
		$dist_id=$crypto->decode($district_id_pk_arr[$dist_i],4);
		$new_dist_id[$dist_i]=$dist_id;
		$dist_i++;
	}*/
	//print_r($new_dist_id); die;
	
	///////////////////Panchatsamity start///////////////////////////
	
	//$district_id_pk_arr=$_POST['arr2'];
	
	//$municipality_code=$cryptoGraph->decode($municipality_code_arr[$i],4);
	
	 
	 //$count_dist=count($district_id_pk_arr);
	//$count_dist=$count_dist-1;
	//$dist_i=0;
	//$new_dist_id=array();
	
	//die;
	//print_r($new_ps_id); die;
function dbdate($caldate){
	if($caldate=="" || $caldate=="0001-01-01"){
			$redate="0001-01-01";
	}else{
			$tmp=explode("-",$caldate);
			$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
	}
  	return  $redate;
}

        $fin_year_1=$crypto->decode($_POST['year'],4);
		$fin_year=$crypto->decode($_POST['year'],4);
		$fin_year=explode("-",$fin_year); 
		
		 //$district=$crypto->decode($_POST['emp_district'],4); 
		 $district=$_POST['emp_district'];
		
		
		$start_month=$_POST['From_Date'];
		$start_new_month=substr($start_month,3,2);
		$start_year=substr($start_month,6,4);
		 $start_monthyear=$start_year.$start_new_month;

		$end_month=$_POST['To_Date'];
		$end_new_month=substr($end_month,3,2);
		$end_year=substr($end_month,6,4);
		 $end_monthyear=$end_year.$end_new_month; 
		 
		 
		 $start_year=$fin_year[0];
		$end_year=$fin_year[1];
		
		if($type==2)
		{
			//echo 11; die;
		$municipality_code_arr=$_POST['arr2'];
		$count_municipality_code=count($municipality_code_arr);
		$count_municipality_code=$count_municipality_code-1;
		}
		else if($type==1)
		{
			//echo 12; die;
		$municipality_code_arr=$_POST['arr3'];
		//print_r($municipality_code_arr); die;
		$count_municipality_code=count($municipality_code_arr);
		$count_municipality_code=$count_municipality_code-1;
		}
		else if($type==3)
		{
			//echo 12; die;
		 $municipality_code_arr=$_POST['zp_name'];
		//print_r($municipality_code_arr); die;
		//$count_municipality_code=count($municipality_code_arr);
		//$count_municipality_code=$count_municipality_code-1;
		}
		
		
		
		$i=0;
		//$month_array=array("04","05","06","07","08","09","10","11","12","01","02","03");
		$monthyr_array=array(array('04',$start_year, 'APRIL'),
						   array('05',$start_year, 'MAY'),
						   array('06',$start_year, 'JUNE'),
						   array('07',$start_year, 'JULY'),
						   array('08',$start_year, 'AUGUST'),
						   array('09',$start_year, 'SEPTEMBER'),
						   array('10',$start_year, 'OCTOBER'),
						   array('11',$start_year, 'NOVEMBER'),
						   array('12',$start_year, 'DECEMBER'),
						   array('01',$end_year, 'JANUARY'),
						   array('02',$end_year, 'FEBRUARY'),
						   array('03',$end_year, 'MARCH'));
		 
		 
	$db  = new database();
// $monthyear='201907';
 


	/*if($type==2)
	{
		$district_id_pk_arr=$_POST['arr2'];
		
		//$municipality_code=$cryptoGraph->decode($municipality_code_arr[$i],4);
		
		
		$count_dist=count($district_id_pk_arr);
		$count_dist=$count_dist-1;
		$dist_i=0;
		$new_dist_id=array();
		$condisation1='ps.ps_name';
		$condisation2='ps_id_fk';
		$condisation3="left join prd_location_master_panchayat_samiti as ps";
		$condisation4="on ps.ps_id_pk= CAST(salary.ps_id_fk as integer )";
		$condisation5=" ps.ps_id_pk=";
		
	}
	else if($type==1)
	{
		
		$district_id_pk_arr=$_POST['arr1'];
		
		//$municipality_code=$cryptoGraph->decode($municipality_code_arr[$i],4);
		
		
		$count_dist=count($district_id_pk_arr);
		$count_dist=$count_dist-1;
		$dist_i=0;
		$new_dist_id=array();
		$condisation1='block.block_name';
		$condisation2='block_code';
		$condisation3="left join prd_location_master_block as block";
		$condisation4="on block.block_code= CAST(salary.block_code as integer )";
		
		
		
	}
	
	else if($type==3)
	{
		
		$district_id_pk_arr=$_POST['arr1'];
		
		//$municipality_code=$cryptoGraph->decode($municipality_code_arr[$i],4);
		
		
		$count_dist=count($district_id_pk_arr);
		$count_dist=$count_dist-1;
		$dist_i=0;
		$new_dist_id=array();
		//$condisation1='block.block_name';
		//$condisation2='block_code';
		//$condisation3="left join prd_location_master_block as block";
		//$condisation4="on block.block_code= CAST(salary.block_code as integer )";
		
		
		
	}*/

	
		
		
		
	//die;
	
       //echo  count($arr)  ;die;        
				  
	
	
	
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);  
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename=Financial_wise_salary_Report.xls');
	header("Content-Transfer-Encoding: binary");
	?>
        <table width="100" border="2">
        	<tr>
               <th colspan="16" style="text-align:center;"><h3><?=strtoupper('Report on disbursement of salaries');?><br /><?='for '.$fin_year_1;?></h3></th>
            </tr>
            <tr>
            <th scope="col">SL NO</th>
            <th scope="col">DISTRICT NAME</th>
           <?php if($type==2)
		{?>
            <th scope="col">NAME OF PS</th>
            <?php }else if($type==1){?>
            <th scope="col">NAME OF BLOCK</th>
            <?php }else if($type==3){?>
            <th scope="col">NAME OF ZP</th
            
            
            ><?php }?>
            <?php /*?><?php if($start_year != 2018){ ?><?php */?>
            <?php
			foreach($monthyr_array as $monthyr)
			{
			?>
            	<th scope="col">
                    <table border="1">
                        <tr>
                            <td colspan="2"><b><?=$monthyr[2]?>, <?=$monthyr[1]?></b></td>
                        </tr>
                        <tr>
                            <td>GROSS</td><td>NET</td>
                        </tr>
                        
                    </table>
                    
            	</th>
            <?php
			}
			?>
            
            <th scope="col">
                <table border="1">
                    <tr>
                        <td rowspan="2"><b>TOTAL GROSS</b></td><td rowspan="2"><b>TOTAL NET</b></td>
                    </tr>
                </table>
            </th>
            </tr>
           
<?php

    
     
	
	$count = 1;
	$mun_gross_total=0;
    $mun_net_total=0;
	while($i <=$count_municipality_code)
	{
		if($type==2 || $type==1)
		{
		 $municipality_code=$crypto->decode($municipality_code_arr[$i],4);
		}
		else if($type==3)
		{
			 $municipality_code=$municipality_code_arr; 
		}
		//$sql1="SELECT ps_name, ";
		//echo $municipality_code;
		
		
		if($type==2)
		{
	$municipality_list=$db->fetch_table("SELECT ps_id_pk, ps_name FROM prd_location_master_panchayat_samiti where ps_id_pk='".$municipality_code."'
										ORDER BY ps_name ASC");
		$sql1="SELECT";
		}
		else if($type==1)
		{
			
			$municipality_list=$db->fetch_table("SELECT block_code, block_name FROM prd_location_master_block where block_code='".$municipality_code."'
										ORDER BY block_name ASC");
										
			$sql1="SELECT";							
		}
		
		else if($type==3)
		{
			
			$municipality_list=$db->fetch_table("SELECT   district_id_pk,district_name  from prd_location_master_district where  district_id_pk='".$municipality_code."'
										ORDER BY district_name ASC");
										
			$sql1="SELECT";							
		}
		
		
		
		$sql2='';
		$count_monthyr=count($monthyr_array);
		$mnthyr_inc=1;
		foreach($monthyr_array as $monthyr)
		{
			if($type==2)
		{
			$sql2.="(SELECT sum(gross_salary) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(ps_id_fk AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_gross, ";
			
			$sql2.="(SELECT sum(net) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(ps_id_fk AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_net";
		}
		else if($type==1)
		{
			
			$sql2.="(SELECT sum(gross_salary) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(block_code AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_gross, ";
			
			$sql2.="(SELECT sum(net) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(block_code AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_net";
			
		}
		
		else if($type==3)
		{
			
			$sql2.="(SELECT sum(gross_salary) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(zp_id_fk AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='4' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_gross, ";
			
			$sql2.="(SELECT sum(net) AS gross_salary from prd_monthly_salary_archive_final WHERE salary_monthyear='".$monthyr[1].$monthyr[0]."' AND 				CAST(zp_id_fk AS integer) in(".$municipality_code.") AND delete_status='1' AND status_flag='4' AND is_saved='1' AND salary_type!='8' ) as ".$monthyr[2]."_net";
			
		}
		
		
		
		
			if($count_monthyr > $mnthyr_inc)
			{
				$sql2.=", ";
			}
			else
			{
				$sql2.=" ";
			}
			
			$mnthyr_inc++;
		}
			if($type==2)
		{
		$sql3="FROM prd_monthly_salary_archive_final mmsaf
			INNER JOIN prd_location_master_panchayat_samiti mlmm
			ON mlmm.ps_id_pk=mmsaf.ps_id_fk 
			WHERE CAST(mmsaf.ps_id_fk AS integer) in(".$municipality_code.")
			AND (salary_monthyear>='".$monthyr_array[0][1].$monthyr_array[0][0]."' AND salary_monthyear<='".$monthyr_array[11][1].$monthyr_array[11][0]."')
			AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' 
			GROUP BY ps_name";
		}
		else if($type==1)
		{
		$sql3="FROM prd_monthly_salary_archive_final mmsaf
			INNER JOIN prd_location_master_block mlmm
			ON mlmm.block_code= CAST(mmsaf.block_code AS integer) WHERE CAST(mmsaf.block_code AS integer) in(".$municipality_code.")
			AND (salary_monthyear>='".$monthyr_array[0][1].$monthyr_array[0][0]."' AND salary_monthyear<='".$monthyr_array[11][1].$monthyr_array[11][0]."')
			AND delete_status='1' AND status_flag='3' AND is_saved='1' AND salary_type!='8' 
			GROUP BY block_name";
		}
		
		else if($type==3)
		{
		$sql3="FROM prd_monthly_salary_archive_final mmsaf
			INNER JOIN prd_location_master_district mlmm
			ON mlmm.district_id_pk= CAST(mmsaf.zp_id_fk AS integer) WHERE CAST(mmsaf.zp_id_fk AS integer) in(".$municipality_code.")
			AND (salary_monthyear>='".$monthyr_array[0][1].$monthyr_array[0][0]."' AND salary_monthyear<='".$monthyr_array[11][1].$monthyr_array[11][0]."')
			AND delete_status='1' AND status_flag='4' AND is_saved='1' AND salary_type!='8' 
			GROUP BY district_name";
		}
		
		
		$sql_query=$sql1.$sql2.$sql3;
		$district1=$db->fetch_table("SELECT district_id_pk, district_name FROM prd_location_master_district WHERE district_id_pk='".$district."'
										ORDER BY district_name ASC");
       // $sql_query;
		$get_salary_details=$db->fetch_table("".$sql_query."");
		
		
			
?>
		<tr>
		<td><?='<b>'.$count.'</b>'?></td>
        <td><?='<b>'.$district1[0]['district_name'].'</b>'?></td>
       <?php if($type==2){?>
		<td><?='<b>'.$municipality_list[0]['ps_name'].'</b>'?></td>
        <?php }else if($type==1){?>
        <td><?='<b>'.$municipality_list[0]['block_name'].'</b>'?></td>
        <?php }else if($type==3){?>
        <td><?='<b>'.$municipality_list[0]['district_name'].'</b>'?></td>
        <?php }?>
        
<?php	
$mun_gross_total=0;
		$mun_net_total=0;
		foreach($monthyr_array as $monthyr)
		{
?>       <?php $full_gross_salary=($get_salary_details[0]["".strtolower($monthyr[2])."_gross"]);
				$total_gross_salary=$total_gross_salary+$full_gross_salary;
                
                $full_net_salary=($get_salary_details[0]["".strtolower($monthyr[2])."_net"]);
				$total_net_salary=$total_gross_salary+$full_gross_salary;?>
			<td>
            <table border="1">
            	<tr>
                	<td><?=$get_salary_details[0]["".strtolower($monthyr[2])."_gross"]?></td>
                    <td><?=$get_salary_details[0]["".strtolower($monthyr[2])."_net"]?></td>
                    
                    
                    <?php
					$mnth_gross["".strtolower($monthyr[2]).""]+=$get_salary_details[0]["".strtolower($monthyr[2])."_gross"];
					$mnth_net["".strtolower($monthyr[2]).""]+=$get_salary_details[0]["".strtolower($monthyr[2])."_net"];?>
                    
                </tr>
                
            </table>
            </td>
            
   <?php $mun_gross_total+=$get_salary_details[0]["".strtolower($monthyr[2])."_gross"];
			$mun_net_total+=$get_salary_details[0]["".strtolower($monthyr[2])."_net"];?>

		<?php }
			$grand_total_gross+=$mun_gross_total;
			$grand_total_net+=$mun_net_total;?>
			
			<td>
            <table border="1">
            	<tr>
                	<td><b><?php if($mun_gross_total > 0){echo $mun_gross_total;}?></b></td>
                    <td><b><?php if($mun_net_total > 0){echo $mun_net_total;}?></b></td>
                </tr>
            </table>
            </td>
			
			
	<?php		
		$count++;
		$i++;								
	}
	//die;
?>
		</tr>
        <tr>
        	<td colspan="3" style="text-align:center;"><b>GRAND TOTAL</b></td>
<?php
			foreach($monthyr_array as $monthyr)
			{
?>
				<td>
				<table border="1">
					<tr>
						<td><b><?php if($mnth_gross["".strtolower($monthyr[2]).""] > 0){echo $mnth_gross["".strtolower($monthyr[2]).""];}?></b></td>
						<td><b><?php if($mnth_net["".strtolower($monthyr[2]).""] > 0){echo $mnth_net["".strtolower($monthyr[2]).""];}?></b></td>
					</tr>
				</table>
				</td>
<?php
			}
?>
            <td>
                <table border="1">
                    <tr>
                        <td><b><?php if($grand_total_gross > 0){echo $grand_total_gross;}?></b></td>
                        <td><b><?php if($grand_total_net > 0){echo $grand_total_net;}?></b></td>
                    </tr>
                </table>
            </td>
        </tr>
     </table>