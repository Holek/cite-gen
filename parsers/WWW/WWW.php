<?php
/**
 * WWW parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class WWW extends Parser {

	private $refname;
	private $url;

	public function fetch($url)
	{
		$fh = fopen($url,'r');
		if ($fh) // if file found
		{
			$foundTitle = false; // title checker
			$foundCharset = false; // charset checker
			$text = ''; // text storage
			while ((!$foundTitle && !$foundCharset) && !feof($fh))
			{
				$text .= $line = stream_get_line($fh, 20*1024, '</head>');
				if (stripos($line, '</title>'))
				{
					$foundTitle = true;
				}
				if (stripos($line, 'content="text/html; charset=') || preg_match('/<\?xml(.*?) encoding="/is', $line))
				{
					$foundCharset = true;
				}
			}
			fclose($fh);

			preg_match('/<title>(.*?)<\/title>/i',$text,$title); // put found title in $title[1]

			$this->title = trim(strip_tags(ereg_replace(' +', ' ', $title[1]))); // get rid of unnecessary whitespaces
			$charset = 'UTF-8'; // default charset value
			if ($foundCharset) // if found charset...
			{
				preg_match('/("|\')text\/html;\s+charset=(.*?)("|\')/is',$text,$charset); // ..find it again
				$charset = strtoupper($charset[2]); // ..store it
				if (!strlen($charset)) {
					preg_match('/<\?xml(.*?) encoding=("|\')(.*?)("|\')/is',$text,$charset);
					$charset = strtoupper($charset[3]); // ..store it
				}
				if (!in_array($charset, array('UTF-8','UTF-7','UTF-16','UTF-32','BIG-5','EUC-JP','EUC-KR','EUC-TW','JIS','ISO-2022-JP','ISO-2022-JP-MS'))) // if it's fixed-width encoding...
				{
					$replaceArray = array(array(), array()); // this is a replace array for illegal SGML characters;
					for ($i=0; $i<32; $i++)                  // produces a correct XML output
					{
						$replaceArray[0][] = chr($i);
						$replaceArray[1][] = "";
					}
					for ($i=127; $i<160; $i++)
					{
						$replaceArray[0][] = chr($i);
						$replaceArray[1][] = "";
					}
					$this->title = str_replace($replaceArray[0], $replaceArray[1], $this->title); // get rid of illegal SGML chars and HTML and PHP tags
					$this->title = iconv($charset, 'UTF-8//TRANSLIT', $this->title); // ..and convert the title accordingly (ignore weird characters)
				}
				$refname = preg_match('/((?:[a-z][a-z\.\d\-]+)\.(?:[a-z][a-z\-]+))(?![\w\.])/is',$url,$urlParts);
				$this->refname = $urlParts[1];
			}
			if ($this->title != '')
			{
				$this->title = $url;
			}
			else
			{
				$this->title = false;
			}
		}
		else
		{
			$this->errors[] = array('WWW-error',$url);
			$this->title = false;
		}
	}

	/**
	 * URL info getter
	 *
	 * @return     array
	 */
	public function getOutput()
	{
		// Here you can sort which fields are meant to be shown first
		// at the generated template. Simply the first one goes first. ;)
		return array(
				'title' => $this->title,
				'url' => $this->url,
				'__refname' => $this->refname,
				'__sourceurl' => $this->url
			);
	}
}
?>
