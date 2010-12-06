<?php


class WWW_parser extends InputCheck
{
	static function checkInput($input)
	{
		return preg_match('/((?:http|https)(?::\/{2}[\w]+)(?:[\/|\.]?)(?:[^\s"]*))/is', $input);
	}

	static public function listParsers()
	{
		global $templateLanguage;
		$ini = parse_ini_file('./parsers/WWW/WWW.ini');
		$title = (($ini[$templateLanguage])?$ini[$templateLanguage]:$ini['en']);
		$return = array('International' => array(
					array(
						'value' => 'WWW',
						'label' => $title
					)
				));
		return array($return,array('WWW'));
	}
}

?>
