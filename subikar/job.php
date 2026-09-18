<?php 
      require 'master.php';
      global $db;
      // For OverAge Condonation
      $Query = "UPDATE intra_pri_overage_condonation_master
            SET application_status = 1
            WHERE application_status = 0
              AND application_id IN (
                SELECT DISTINCT ON (ipf.application_id) ipf.application_id
                FROM intra_pri_forwarding AS ipf
                LEFT JOIN intra_pri_master AS ipm 
                  ON ipf.from_officer_id_const = ipm.officer_id_const 
                WHERE ipf.application_id LIKE 'OAC/%' 
                  AND ipm.login_level_stake = 'STATE'
                ORDER BY ipf.application_id, ipf.submitted_on
              );";
    $db->update($Query);
    // For Compessanate Ground 
    $Query = "UPDATE intra_pri_cg_profile_master
            SET application_status = 1
            WHERE application_status = 0
              AND application_id IN (
                SELECT DISTINCT ON (ipf.application_id) ipf.application_id
                FROM intra_pri_forwarding AS ipf
                LEFT JOIN intra_pri_master AS ipm 
                  ON ipf.from_officer_id_const = ipm.officer_id_const 
                WHERE ipf.application_id LIKE 'CG/%' 
                  AND ipm.login_level_stake = 'STATE'
                ORDER BY ipf.application_id, ipf.submitted_on
              );";
    $db->update($Query);    
    echo "Done";
?>