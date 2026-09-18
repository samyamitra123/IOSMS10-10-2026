<?php 
      require 'master.php';
      //$myfile = fopen("jsondata.txt", "r") or die("Unable to open file!");
      $jsonData = file_get_contents("jsondata.txt");
      fclose($myfile);
      $jsonDecodeData = json_decode($jsonData);
      $NewData = array();
      foreach($jsonDecodeData as $key=>$jdd)
      {
         $TempData = array();
         foreach($jdd as $field=>$item)
         {
            if($field == 'TREASURY_CODE')
               $TempData['treasury_code'] = $item;
            if($field == 'Block Code(i-OSMS)')
               $TempData['block_code'] = $item;
            if($field == 'TREASURY_NAME')
               $TempData['treasury_name'] = $item;
            if(rtrim($field) == 'Block Name')
               $TempData['block_name'] = $item;            
            if($field == 'GP Operator Code')
               $TempData['gp_operator_code'] = $item;
            if($field == 'GP HOA SCHEME')
               $TempData['gp_hoa_schema'] = $item;
            if($field == 'PS Operator Code')
               $TempData['ps_operator_code'] = $item;
            if($field == 'BDO HOO Code')
               $TempData['bdo_hoo_code'] = $item;
            if($field == 'PS HOO Code')
               $TempData['ps_hoo_code'] = $item;
         }
         $NewData[] = $TempData;

      }
      //$master = new Master();
      //$master->saveHooCode($NewData);
      
?>