<?php

class PMID_output
{
	static final public function getOutputParserClass($lang)
	{
		if (file_exists('./parsers/PMID/output/Journal_'.$lang.'.php'))
		{
			return 'Journal_'.$lang;
		}
		else
		{
			return 'Journal_en';
		}
	}
	static final public function getParserList()
	{
		$parserArray = array();
		$dh = opendir('./parsers/PMID/output');
		while(!is_bool($file = readdir($dh)))
		{
			if (preg_match('/Journal_([a-zA-Z0-9\-]+)\.php/s',$file,$match))
			{
				$parserArray[] = $match[1];
			}
		}
		return $parserArray;
	}
}

?>