<?php

/**
 * 
 */
class XML {
	
	function __construct() {
		
	}
	function make_stake($array, $path){
		//print_r($array);
		$xml =  new DOMDocument("1.0","UTF-8");
		$container = $xml->createElement('container');
		$container = $xml->appendChild($container);
		foreach ($array as $key => $value) {
			
			
			$row = $xml->createElement('level');
			$row = $container->appendChild($row);
			
			$stake = $xml->createElement('id',$value['stake_level_id_pk']);
			$stake = $row->appendChild($stake);
			
			$stake = $xml->createElement('type',$value['stake_level_type_id_fk']);
			$stake = $row->appendChild($stake);
			
			$abbreviation = $xml->createElement('abbr',$value['stake_level_abbreviation']);
			$abbreviation = $row->appendChild($abbreviation);
			
			$description= $xml->createElement('desc',$value['stake_level_description']);
			$description = $row->appendChild($description);
			
			
			
			
		}
		$xml->formatOutput = TRUE;
		$string_value = $xml->saveXML();
		$xml->save($path. 'stake_level.xml');
	}
	
	function make_stake_type($array, $path){
		//print_r($array);
		$xml =  new DOMDocument("1.0","UTF-8");
		$container = $xml->createElement('container');
		$container = $xml->appendChild($container);
		foreach ($array as $key => $value) {
			
			
			$row = $xml->createElement('level_type');
			$row = $container->appendChild($row);
			
			$id = $xml->createElement('type',$value['stake_level_type_id_pk']);
			$id = $row->appendChild($id);
			
			$name = $xml->createElement('name',$value['stake_level_type_name']);
			$name = $row->appendChild($name);
			
			
		}
		$xml->formatOutput = TRUE;
		$string_value = $xml->saveXML();
		$xml->save($path. 'stake_level_type.xml');
	}
}


?>