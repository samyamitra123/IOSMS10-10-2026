<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/


$db = new database();

//--------------------------GP LEVEL----------------------------------//
if (strlen($_SESSION['user_info']['stake_user']) == 10) 
{
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	$block_gp_2009 = $db->fetch_table("
				SELECT count(*) FROM prd_employee_salary_save
				WHERE status_flag in('2','3') and delete_status='1' and is_saved='1' AND ropa_status='2'
				AND gp_id_fk ='".$_SESSION['location']['gp_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
			
			if($block_gp_2009[0]['count']>0)
			{
					$_SESSION['blocked_privilege']['0201'] = "FALSE";
					
					//$_SESSION['blocked_privilege']['0202'] = "FALSE";
					
			} 
			else 
			{
					unset($_SESSION['blocked_privilege']['0201']);
					
					//unset($_SESSION['blocked_privilege']['0202']);
			}
			
			$block_gp_2019 = $db->fetch_table("
				SELECT count(*) FROM prd_employee_salary_save
				WHERE status_flag in('2','3') and delete_status='1' and is_saved='1' AND ropa_status='1'
				AND gp_id_fk ='".$_SESSION['location']['gp_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
			//echo $_SESSION['user_info']['stake_user'];exit;
			if($block_gp_2019[0]['count']>0)
			{
					$_SESSION['blocked_privilege']['0211'] = "FALSE";
					
					//$_SESSION['blocked_privilege']['0202'] = "FALSE";
					
			} 
			else 
			{
					unset($_SESSION['blocked_privilege']['0211']);
					
					//unset($_SESSION['blocked_privilege']['0202']);
			}	

	$gp_requi= $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag='3' AND is_saved=1 AND delete_status=1
			AND gp_id_fk ='".$_SESSION['location']['gp_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
	
	
	if($gp_requi[0]['count']>0)
			{
			       unset($_SESSION['blocked_privilege']['0207']);
			 } 
			else 
			{
			        $_SESSION['blocked_privilege']['0207'] = "FALSE";
			}
	
	$bill_status=$db->fetch_table("SELECT * FROM prd_block_bill_details WHERE block_code='".substr($_SESSION['user_info']['stake_user'],0,7)."' AND salary_monthyear='".date('Ym')."'");
	
	
	if(isset($bill_status))
	{
		//echo $_SESSION['user_info']['stake_user'];exit;
		$emp_count = $db->fetch_table("SELECT 
													count(emp.emp_id_pk),
													count(sal.emp_id_fk)
												FROM
													prd_employee_master emp
												LEFT JOIN
													(SELECT emp_id_fk,gp_id_fk FROM prd_employee_salary_save WHERE salary_monthyear='".date('Ym')."' AND status_flag=3 AND gp_id_fk='".$_SESSION['location']['gp_id']."') sal
												ON
													emp.emp_id_pk=sal.emp_id_fk AND CAST(emp.gp_id_fk as CHARACTER VARYING)=sal.gp_id_fk
												
												WHERE
													emp.gp_id_fk='".$_SESSION['location']['gp_id']."' AND emp.emp_status=1 ");
													// echo "gfsgdgf";exit;


	}
	

	//echo $_SESSION['user_info']['stake_user'];exit;
}
else if (strlen($_SESSION['user_info']['stake_user']) == 7) 
{
	$block_gp = $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag = 3 
			AND block_code ='".$_SESSION['location']['block_code']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
			
			if($block_gp[0]['count']>0)
			{
				unset($_SESSION['blocked_privilege']['08']);
				unset($_SESSION['blocked_privilege']['0801']);
				unset($_SESSION['blocked_privilege']['0607']);
				
			} 
			else 
			{
				$_SESSION['blocked_privilege']['08'] = "FALSE";
				$_SESSION['blocked_privilege']['0801'] = "FALSE";
				$_SESSION['blocked_privilege']['0607'] = "FALSE";
			}
			
			
			$block_gp_ropa = $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag = 3 
			AND block_code ='".$_SESSION['location']['block_code']."' AND salary_monthyear='".date('Ym')."' and  ropa_status='2' and requisition_type='1001'");
			
			if($block_gp_ropa[0]['count']>0)
			{
					
					unset($_SESSION['blocked_privilege']['0804']);
					
					//$_SESSION['blocked_privilege']['0202'] = "FALSE";
					
			} 
			else 
			{
					$_SESSION['blocked_privilege']['0804'] = "FALSE";
					
					//unset($_SESSION['blocked_privilege']['0202']);
			}				
}
else if (strlen($_SESSION['user_info']['stake_user']) == 8) 
{
	$block_gp = $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag='3' AND is_saved=1 AND delete_status=1
			AND ps_id_fk ='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
			
			if($block_gp[0]['count']>0)
			{
			        unset($_SESSION['blocked_privilege']['34']);
				unset($_SESSION['blocked_privilege']['3401']);
				unset($_SESSION['blocked_privilege']['3205']);
				unset($_SESSION['blocked_privilege']['3206']);
				unset($_SESSION['blocked_privilege']['3304']);
				
			} 
			else 
			{
			        $_SESSION['blocked_privilege']['34'] = "FALSE";
				$_SESSION['blocked_privilege']['3401'] = "FALSE";
				$_SESSION['blocked_privilege']['3206'] = "FALSE";
				$_SESSION['blocked_privilege']['3205'] = "FALSE";
				$_SESSION['blocked_privilege']['3304'] = "FALSE";
				
			}
			
			
		$ps_2009 = $db->fetch_table("
		SELECT count(*) FROM prd_employee_salary_save
		WHERE status_flag in('3') and delete_status='1' and is_saved='1'
		AND ps_id_fk ='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001' AND ropa_status = '2'");
		if($ps_2009[0]['count']>0)
		{
			unset($_SESSION['blocked_privilege']['3408']);
		
		} 
		else 
		{
		$_SESSION['blocked_privilege']['3408'] = "FALSE";
		}

			$ps_requi_2009 = $db->fetch_table("
		SELECT count(*) FROM prd_employee_salary_save
		WHERE status_flag in('2','3') and delete_status='1' and is_saved='1'
		AND ps_id_fk ='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001' AND ropa_status = '2'");
   if($ps_requi_2009[0]['count']>0)
			{
					$_SESSION['blocked_privilege']['3201'] = "FALSE";
			} 
			else 
			{
				       unset($_SESSION['blocked_privilege']['3201']);
			}
			
			
			$ps_requi_2019 = $db->fetch_table("
		SELECT count(*) FROM prd_employee_salary_save
		WHERE status_flag in('2','3') and delete_status='1' and is_saved='1'
		AND ps_id_fk ='".$_SESSION['location']['ps_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001' AND ropa_status = '1'");
   if($ps_requi_2019[0]['count']>0)
			{
					$_SESSION['blocked_privilege']['3211'] = "FALSE";
					
					 
			} 
			else 
			{
				       unset($_SESSION['blocked_privilege']['3211']);
					 
					 
			}

			$ps_profile=$db->fetch_table("
			SELECT ps.ps_status FROM prd_location_master_panchayat_samiti ps
			INNER JOIN psemp_ps_profile prof
			ON ps.ps_id_pk=prof.ps_id_fk
			WHERE ps.ps_id_pk='".$_SESSION['location']['ps_id']."'");
	if(count($ps_profile)=='0' || $ps_profile[0]['ps_status']!='2')
	{
		$_SESSION['blocked_privilege']['3102'] = "FALSE";
		$_SESSION['blocked_privilege']['3104'] = "FALSE";
		$_SESSION['blocked_privilege']['3105'] = "FALSE";
		
	}
	else
	{
		unset($_SESSION['blocked_privilege']['3102']);
		unset($_SESSION['blocked_privilege']['3104']);
		unset($_SESSION['blocked_privilege']['3105']);
		
	}
	//echo $ps_profile[0]['ps_status'];die;
	if($ps_profile[0]['ps_status']=='1' || $ps_profile[0]['ps_status']=='2' ||$ps_profile[0]['ps_status']=='7' )
	{
		
			$_SESSION['blocked_privilege']['3101'] = "FALSE";
		
	}
	else
	{
		unset($_SESSION['blocked_privilege']['3101']);
		
	}

	$supp_salary_lock_check=$db->fetch_table("
												SELECT count(emp_id_fk) total_lock_supp FROM prd_employee_salary_save WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND status_flag='3' AND delete_status='1' AND is_saved='1' AND salary_monthyear='".date('Ym')."' AND requisition_type='1003'
											");
											
	if($supp_salary_lock_check[0]['total_lock_supp']=='0')
	{
		$_SESSION['blocked_privilege']['3404'] = "FALSE";
		$_SESSION['blocked_privilege']['3405'] = "FALSE";
		$_SESSION['blocked_privilege']['3406'] = "FALSE";
	}
	else
	{
		unset($_SESSION['blocked_privilege']['3404']);
		unset($_SESSION['blocked_privilege']['3405']);
		unset($_SESSION['blocked_privilege']['3406']);
	}

}

else if (strlen($_SESSION['user_info']['stake_user']) == 4) 
{

// FOR ZP Profile (ZP Start)

	$zp_profile=$db->fetch_table("
			SELECT dt.zp_status FROM prd_location_master_district dt
			INNER JOIN zpemp_zp_profile prof
			ON dt.district_id_pk=prof.district_id_fk
			WHERE dt.district_id_pk='".$_SESSION['location']['district_id']."'");
	if(count($zp_profile)=='0' || $zp_profile[0]['zp_status']!='1')
	{
		//$_SESSION['blocked_privilege']['6002'] = "FALSE";
		$_SESSION['blocked_privilege']['6003'] = "FALSE";
		$_SESSION['blocked_privilege']['6004'] = "FALSE";
		$_SESSION['blocked_privilege']['6005'] = "FALSE";
	}
	else
	{
		//unset($_SESSION['blocked_privilege']['6002']);
		unset($_SESSION['blocked_privilege']['6003']);
		unset($_SESSION['blocked_privilege']['6004']);
		unset($_SESSION['blocked_privilege']['6005']);
	}
	//echo $ps_profile[0]['ps_status'];die;
	//if($zp_profile[0]['zp_status']=='1' || $ps_profile[0]['zp_status']=='2' ||$zp_profile[0]['ps_status']=='7' )
	
	
	//if($zp_profile[0]['zp_status']=='1' || $zp_profile[0]['zp_status']=='2' || $zp_profile[0]['zp_status']=='4' || $zp_profile[0]['zp_status']=='6')
	if($zp_profile[0]['zp_status']=='1')
	{
		$_SESSION['blocked_privilege']['6001'] = "FALSE";
	}
	else
	{
		unset($_SESSION['blocked_privilege']['6001']);
	}
	
	
	$salary_requisition_menu_block= $db->fetch_table("
				SELECT count(*) FROM prd_employee_salary_save
				WHERE status_flag in('2','3','4') and delete_status='1' and is_saved='1'
				AND zp_id_fk ='".$_SESSION['location']['district_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
   if($salary_requisition_menu_block[0]['count']>0)
			{
					$_SESSION['blocked_privilege']['6903'] = "FALSE";
			} 
			else 
			{
				       unset($_SESSION['blocked_privilege']['6903']);
			}
			
			
			
			/************************************************* Changed By ANJAN 22-11-2019 ******************************************/
	
		
	$gp_requi_zp= $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag='4' AND is_saved=1 AND delete_status=1
			AND zp_id_fk ='".$_SESSION['location']['district_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type='1001'");
	
	
	if($gp_requi_zp[0]['count']>0)
			{
			      // unset($_SESSION['blocked_privilege']['7304']);
			       unset($_SESSION['blocked_privilege']['7010']);
			       unset($_SESSION['blocked_privilege']['6910']);
			       unset($_SESSION['blocked_privilege']['6911']);
			       unset($_SESSION['blocked_privilege']['7203']);
			} 
			else 
			{
			       // $_SESSION['blocked_privilege']['7304'] = "FALSE";
			        $_SESSION['blocked_privilege']['7010'] = "FALSE";
			        $_SESSION['blocked_privilege']['6910'] = "FALSE";
			        $_SESSION['blocked_privilege']['6911'] = "FALSE";
			        $_SESSION['blocked_privilege']['7203'] = "FALSE";
			}
			
	/************************************************** 22-11-2019 *****************************************************/	

		
		/************************************************* Changed By ANJAN 11-12-2019 ******************************************/
	
	$requi_zp= $db->fetch_table("
			SELECT count(*) FROM prd_employee_salary_save
			WHERE status_flag ='4' AND is_saved=1 AND delete_status=1
			AND zp_id_fk ='".$_SESSION['location']['district_id']."' AND salary_monthyear='".date('Ym')."' and requisition_type !='1001'
			AND requisition_type ='1003'");
	
	
	if($requi_zp[0]['count']>0)
			{
			       unset($_SESSION['blocked_privilege']['7304']);
			       
			} 
			else 
			{
			        $_SESSION['blocked_privilege']['7304'] = "FALSE";
			        
			}
	
/************************************************** END 11-12-2019 *****************************************************/	



}
?>