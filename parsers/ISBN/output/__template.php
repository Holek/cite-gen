<?php

class ISBN_output
{
	static final public function getOutputParserClass($lang)
	{
		if (file_exists('./parsers/ISBN/output/Book_'.$lang.'.php'))
		{
			return 'Book_'.$lang;
		}
		else
		{
			return 'Book_en';
		}
	}
	static final public function getParserList()
	{
		$parserArray = array();
		$dh = opendir('./parsers/ISBN/output');
		while(!is_bool($file = readdir($dh)))
		{
			if (preg_match('/Book_([a-zA-Z0-9\-]+)\.php/s',$file,$match))
			{
				$parserArray[] = $match[1];
			}
		}
		return $parserArray;
	}
	
	/**
	 * Modifies the ISBN according to standards
	 * @return string
	 * @param string $ISBN
	 */
	static final public function modifyISBN($ISBN) {
		$url= @file_get_contents('http://toolserver.org/gradzeichen/IsbnTextFormat?Bot-Modus&Text='.$ISBN);
		$temp = explode(' ',$url);
		if ($temp[0])
		{
			return $temp[0];
		}
		else
		{
			return $ISBN;
		}
	}

}

?>