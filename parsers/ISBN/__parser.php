<?php

class ISBN_parser extends InputCheck {
	static public function checkInput($input) {
		$input = ISBN_parser::prepareInput($input);
		return (
		// ISBN-10
		    ((strlen($input) == 10) &&
		    ((($input[0]    //  (1 * x1
		     +(2*$input[1]) // + 2 * x2
		     +(3*$input[2]) // + 3 * x3
		     +(4*$input[3]) // + ...
		     +(5*$input[4])
		     +(6*$input[5])
		     +(7*$input[6])
		     +(8*$input[7]) // + ...
		     +(9*$input[8]) // + 9 * x9)
			 )%11) ==                // mod 11 == x10
			 (($input[9]=='X')?10:$input[9])))
			||
		// ISBN-13
			((strlen($input) == 13) &&
				(((10-           // (10 -
		    (($input[0]      //  (1 * x1
		     +(3*$input[1])  // + 3 * x2
		     +(  $input[2])  // + 1 * x3
		     +(3*$input[3])  // + ...
		     +(  $input[4])
		     +(3*$input[5])
		     +(  $input[6])
		     +(3*$input[7])  // + ...
		     +(  $input[8])  // + 1 * x9
		     +(3*$input[9])  // + 3 * x10
		     +(  $input[10]) // + 1 * x11
		     +(3*$input[11]) // + 3 * x12)
			 )%10))%10               // mod 10) == x13 // 0 replaces 10
			 == $input[12])))
		);
	}
	
	static public function listParsers() {
		global $templateLanguage,$scriptLanguage;
		$parsersClass = opendir('./parsers/ISBN');
		$availableParsers = array();
		$defaultParsers = array();
		while(!is_bool($file = readdir($parsersClass))) {
			if(substr($file, -4) == '.ini') {
				$ini = parse_ini_file('./parsers/ISBN/'.$file,true);
				if ($ini['translations'][$scriptLanguage]) {
					$parserTitle = $ini['translations'][$scriptLanguage];
				} else {
					$parserTitle = $ini['summary']['title'];
				}
				$availableParsers[getMessage($ini['summary']['main_language'])][] = array('value'=>substr($file,0,-4),'label'=>$parserTitle);
				
				if ($ini['summary']['main_language'] == $templateLanguage || $ini['summary']['main_language'] == 'International') {
					$defaultParsers = array_unshift( $defaultParsers, substr($file,0,-4) );
				}
				else if (in_array($templateLanguage, createArray($ini['summary']['also_in']))) {
					$defaultParsers[] = substr($file,0,-4);
				}
				unset($ini,$parserTitle);
			}
		}
		closedir($parsersClass);
		ksort($availableParsers);
		return array($availableParsers,$defaultParsers);
	}
	
	static public function prepareInput($input) {
		return str_replace(array('-','x'),array('','X'),$input);
	}
}
?>
