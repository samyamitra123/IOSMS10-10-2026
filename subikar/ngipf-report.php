<?php 
    include_once('master.php');
    global $db;
    $Query = "UPDATE prd_employee_master
    SET gpf_acc_no = prm.pfaccno
    FROM prd_gpf_request_master prm
    WHERE prd_employee_master.emp_id_const = prm.emp_id_const
    AND prm.status = 1 AND prm.pfaccno != ''
    AND prd_employee_master.gpf_acc_no = '0';"; // Optmized with above Query
    $db->update($Query);    
    $stake = $_GET['stake'];
    $task = isset($_GET['task'])?1:0;
    switch($stake)
      {
          case 'gp':
          {
                    $Query = "
                      WITH emp_counts AS (
                            SELECT
                                block_id,
                                COUNT(*) AS emp_count,
                                COUNT(CASE WHEN gpf_acc_no != '0' THEN 1 END) AS gpf_count,
                                COUNT(CASE WHEN gpf_acc_no = '0' THEN 1 END) AS non_gpf_count
                            FROM
                                prd_employee_master
                            WHERE
                                emp_status = 1
                                AND ropa_status =1
                                AND emp_cosolidated_pay=0
                                AND emp_status_deputation=0
                            GROUP BY
                                block_id
                        ),
                        non_gpf_emps AS (
                            SELECT
                                block_id,
                                array_to_string(array_agg(emp_id_const::text), ', ') AS emp_ids
                            FROM
                                prd_employee_master
                            WHERE
                                gpf_acc_no = '0'
                                AND emp_status = 1
                                AND ropa_status =1
                                AND emp_cosolidated_pay=0
                                AND emp_status_deputation=0
                            GROUP BY
                                block_id
                        )
                        SELECT
                            lmd.district_name,
                            lmb.block_name,
                            lmb.block_code,
                            COALESCE(ec.emp_count, 0) AS emp_count,
                            COALESCE(ec.gpf_count, 0) AS gpf_count,
                            COALESCE(ec.non_gpf_count, 0) AS non_gpf_count,
                            nemp.emp_ids
                        FROM
                            prd_location_master_block lmb
                        LEFT JOIN
                            emp_counts ec
                        ON
                            lmb.block_id_pk = ec.block_id
                        LEFT JOIN
                            prd_location_master_district lmd
                        ON
                            lmb.district_id_fk = lmd.district_id_pk
                        LEFT JOIN
                            non_gpf_emps nemp
                        ON
                            lmb.block_id_pk = nemp.block_id
                        WHERE
                            lmb.lgd > 0
                        ORDER BY
                            lmd.district_name,
                            lmb.block_name;


                    ";            
            break;
          }
          case 'ps':
          {
                    $Query = "
                WITH emp_counts AS (
                    SELECT
                        ps_id_fk,
                        COUNT(*) AS emp_count,
                        COUNT(CASE WHEN gpf_acc_no != '0' THEN 1 END) AS gpf_count,
                        COUNT(CASE WHEN gpf_acc_no = '0' THEN 1 END) AS non_gpf_count
                    FROM
                        prd_employee_master
                    WHERE
                        emp_status = 1
                        AND ropa_status =1
                        AND emp_cosolidated_pay=0
                        AND emp_status_deputation=0
                    GROUP BY
                        ps_id_fk
                ),
              non_gpf_emps AS (
                    SELECT
                        ps_id_fk,
                        array_to_string(array_agg(emp_id_const::text), ', ') AS emp_ids
                    FROM
                        prd_employee_master
                    WHERE
                        gpf_acc_no = '0'
                        AND emp_status = 1
                        AND ropa_status =1
                        AND emp_cosolidated_pay=0
                        AND emp_status_deputation=0
                    GROUP BY
                        ps_id_fk
                )                
                SELECT
                    lmd.district_name,
                    lmb.ps_name,
                    lmb.ps_code,
                    COALESCE(ec.emp_count, 0) AS emp_count,
                    COALESCE(ec.gpf_count, 0) AS gpf_count ,
                    COALESCE(ec.non_gpf_count, 0) AS non_gpf_count,
                    nemp.emp_ids                    
                FROM
                    prd_location_master_panchayat_samiti lmb
                LEFT JOIN
                    emp_counts ec
                ON
                    lmb.ps_id_pk = ec.ps_id_fk

                LEFT JOIN
                    prd_location_master_district lmd
                ON
                    lmb.district_id_fk = lmd.district_id_pk
                LEFT JOIN
                    non_gpf_emps nemp
                ON
                    lmb.ps_id_pk = nemp.ps_id_fk
                WHERE
                    lmb.ps_status =2
                ORDER BY
                    lmd.district_name,
                    lmb.ps_id_pk;                    
                    ";            
            break;
          }  
          case 'zp':
          {
                $Query = "
                WITH emp_counts AS (
                    SELECT
                        zp_id_fk,
                        COUNT(*) AS emp_count,
                        COUNT(CASE WHEN gpf_acc_no != '0' THEN 1 END) AS gpf_count,
                        COUNT(CASE WHEN gpf_acc_no = '0' THEN 1 END) AS non_gpf_count                         
                    FROM
                        prd_employee_master
                    WHERE
                        emp_status = 1 
                        AND ropa_status =1
                        AND emp_cosolidated_pay=0
                        AND emp_status_deputation=0
                    GROUP BY
                        zp_id_fk
                ),
               non_gpf_emps AS (
                    SELECT
                        zp_id_fk,
                        array_to_string(array_agg(emp_id_const::text), ', ') AS emp_ids
                    FROM
                        prd_employee_master
                    WHERE
                        gpf_acc_no = '0'
                        AND emp_status = 1
                        AND ropa_status =1
                        AND emp_cosolidated_pay=0
                        AND emp_status_deputation=0
                    GROUP BY
                        zp_id_fk
                )                  
                SELECT
                    lmb.district_name,
                    lmb.district_code,
                    COALESCE(ec.emp_count, 0) AS emp_count,
                    COALESCE(ec.gpf_count, 0) AS gpf_count,
                    COALESCE(ec.non_gpf_count, 0) AS non_gpf_count,
                    nemp.emp_ids 
                FROM
                    prd_location_master_district lmb
                LEFT JOIN
                    emp_counts ec
                ON
                    lmb.district_id_pk = ec.zp_id_fk
                LEFT JOIN
                    non_gpf_emps nemp
                ON
                    lmb.district_id_pk = nemp.zp_id_fk
                WHERE
                    lmb.lgd > 0;
                    ";            
            break;
          }                   
      }

    global $BlockReport,$newEmployeeReport;
    $BlockReport =$db->fetch_obj($Query);
    //print_r($BlockReport); exit;
    $empInArray = array();
    foreach($BlockReport as $item)
    {
        $empInArray[] = $item->emp_ids;
    }
    $empInArray = implode(',',$empInArray);
    $empInArray = explode(',',$empInArray);
    $newEmpInArray = array();
    $EmpNotGenratedInArray = array();
    foreach($empInArray as $emp)
    {
       if($emp != '')
        { 
            $newEmpInArray[] = "'".ltrim($emp)."'"; 
            $EmpNotGenratedInArray[] = ltrim($emp);
        }
    }
    $newEmpInArray = implode(',',$newEmpInArray);
    $Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const IN (".$newEmpInArray.")";
    $empReport =$db->fetch_obj($Query);
    $newEmployeeReport = array();
    $empAlreadySentPendingWithError = array();
    foreach($empReport as $report)
    {
        $empTemp = array();
        //$empTemp['status'] 
        if($report->status == 2)
        {
             $empTemp['status'] = 'Pending TCS';
        }
        else
        {
            $empTemp['status'] = 'Rejected';
            $response = json_decode($report->full_response);
            //print_r($report->full_response); exit;
            $empTemp['error'] = $response->resp->errDesc;
        }
        $newEmployeeReport[$report->emp_id_const] = $empTemp;
        $empAlreadySentPendingWithError[] = $report->emp_id_const;
    }
    $empDiff = array_diff($EmpNotGenratedInArray,$empAlreadySentPendingWithError);
    if($task ==1)
    {
        foreach($empDiff as $emp)
        {
            $Query = "SELECT req_id_pk from prd_ngipf_request_cron WHERE emp_id_const='".$emp."'";
            $reqData = $db->fetch_table($Query);
            if(!isset($reqData[0]['req_id_pk']))
            {
              $Query = "SELECT emp_id_pk from prd_employee_master 
                        WHERE emp_id_const = '".$emp."'";
              $EmployeeData = $db->fetch_table($Query);
              $emp_id = $EmployeeData[0]['emp_id_pk'];                 
              $Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$emp_id.",'".$emp."',0,'".$stake."')";
              //print_r($Query); 
              $db->insert($Query);
            }            
        }
        echo "Done";
        exit;
    }
   // print_r($newEmployeeReport); 
    //print_r($EmpNotGenratedInArray); 
    //print_r($empDiff); 
    //exit;
    include_once('template/'.$stake.'-ngipf.php');
    //print_r(json_encode($BlockReport));
?>



