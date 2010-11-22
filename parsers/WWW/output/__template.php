<?php

class WWW_output
{
	static final public function getOutputParserClass($lang)
	{
		if (file_exists('./parsers/WWW/output/Web_'.$lang.'.php'))
		{
			return 'Web_'.$lang;
		}
		else
		{
			return 'Web_en';
		}
	}
	static final public function getParserList()
	{
		$parserArray = array();
		$dh = opendir('./parsers/WWW/output');
		while(!is_bool($file = readdir($dh)))
		{
			if (preg_match('/Web_([a-zA-Z0-9\-]+)\.php/s',$file,$match))
			{
				$parserArray[] = $match[1];
			}
		}
		return $parserArray;
	}
}

?>