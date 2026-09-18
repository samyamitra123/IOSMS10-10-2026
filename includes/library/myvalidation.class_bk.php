<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Validation {
    function __construct() {
        
    }
    
    // validation integer or not
    public function int_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_INT))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation boolean or not
    public function bool_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_BOOLEAN))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation float or not
    public function float_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_FLOAT))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation IP Address or not
    public function ip_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_IP))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
    //validation URL or not
    public function url_val($value) {
        
        if(!filter_var($value, FILTER_VALIDATE_URL))
        {
            return FALSE;
        }
      else
        {
            return TRUE;
        }
    }
    
}

$validator = new Validation();
?>
