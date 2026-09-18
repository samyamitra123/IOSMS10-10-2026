<?php

/**
 * @name		Fast Cache
 * @author 		Parag Dhali
 * @version		1.0
 * @since 		29-8-2014	
 */
class cache {
	
	/**
	 * @var string
	 */
	
	/**
	 * @return 	boolean
	 * @param 	cache name, path, cache value, cache duration
	 */
	public function set($cache = NULL, $path = NULL, $value = NULL, $duration = NULL)
	{
		if($cache == NULL || $value == NULL){
			return TRUE;
		} else {
			if($this->get($cache) == NULL){
				$fcache = array(
					't'	=>	time(),
					'd'	=>	$duration,
					'v'	=>	$value
				);
				
				$fjson = json_encode($fcache);
				$jfile = fopen($path . $cache,"w");
				if(fwrite($jfile,$fjson)){
					fclose($jfile);
					return TRUE;

				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
	}
	
	/**
	 * @return 	array, False
	 * @param 	cache name, path
	 */
	public function get($cache = NULL, $path = NULL)
	{
		if($path  == null || !file_exists($path .$cache)){
			return NULL;
		} else {
			$string = file_get_contents($path .$cache);
			$json_value = json_decode($string,true);
			
			$valid = $json_value['t'] + $json_value['d'];
			
			if($valid > (int)time() || $json_value['d'] == NULL){
				return $json_value['v'];
			} else {
				return FALSE;
			}
			
			
		}
	}
	/**
	 * @return 	boolean
	 * @param 	cache name, path
	 */
	 public function delete($cache = NULL, $path = NULL)
	 {
		$file = $path . $cache;
			if(file_exists($file)){
			if (!unlink($file)){
				return FALSE;
			} else {
				return TRUE;
			}
		}
	 }
	
}
?>