<?php
/**
 * ISBNdb.com parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class isbnDB extends ISBNBaseParser {

	public function fetch($ISBN)
	{
		// To connect to ISBNdb.com database
		// you have to obtain you own key
		// at http://isbndb.com/
		$pass = parse_ini_file('/home/'.get_current_user().'/.pass');
		$url = @file_get_contents('http://isbndb.com/api/books.xml?access_key='.$pass['ISBNDB'].'&results=authors&index1=isbn&value1='.$ISBN);
		unset($pass);
		if (!$url)
		{
			$this->errors[]= array('base-disabled','ISBNdb.com');
		}
		$p = xml_parser_create();

		xml_parse_into_struct($p,$url,$results,$index);
		xml_parser_free($p);

		$isbndb_error = $results[$index[ERRORMESSAGE][0]][value];
		$this->title = (($results[$index[TITLELONG][0]][value])?$results[$index[TITLELONG][0]][value]:$results[$index[TITLE][0]][value]);
		if ($this->title)
		{
			$this->publisher = $results[$index[PUBLISHERTEXT][0]][value];
			preg_match('/(.*?)(;|:) (.*?)(;|,) (c|\[|cop\. |p|\[ca |)([0-9]{4})(\.|\]|)/', $this->publisher, $temp);
			$this->publisher = ($temp[3])?$temp[3]:$this->publisher;
			$this->place = $temp[1];
			$this->date = $temp[6];

			$authors = array();
			for ($i=0;$i<count($index[PERSON]);$i++)
			{
				$authors[] = $results[$index[PERSON][$i]][value];
			}

			$i=0;
			foreach ($authors as $author_temp) {
				preg_match('/(.+?)(, | )(.+)/', $author_temp, $temp);
				switch($temp[2])
				{
					case ', ':
						$this->firstNames[$i] = $temp[3];
						$this->lastNames[$i] = $temp[1];
						break;
					case ' ':
						$this->firstNames[$i] = $temp[1];
						$this->lastNames[$i] = $temp[3];
						break;
				}
				$i++;
			}
			$this->ISBN = $ISBN;

			$this->refname = $results[$index[BOOKDATA][0]][attributes][BOOK_ID];
			$this->source = 'http://isbndb.com/d/book/'.$results[$index[BOOKDATA][0]][attributes][BOOK_ID].'.html';
		}
		else
		{
			if ($isbndb_error != '')
			{
				$this->errors[] = array('','ISBNdb error: '.$isbndb_error);
			}
			$this->title = false;
		}
	}

	/**
	 * Book info getter
	 *
	 * @return     array
	 */
	public function getOutput()
	{
		// Array consisting of values, which keys are named
		// after English Wikipedia {{Cite book}} template,
		// with one exception: array '__names' consisting of
		// subarrays 'last' and 'first'.
		// These are equivalents of 'last' and 'first' fields,
		// and these are arrays; some Wikipedias
		// (ie. Polish) are using multiple author fields.
		// In that case each last name in array has first name
		// at the same index as it (ie. $this->lastNames[3]
		// has to correspond with $this->firstNames[3];
		// it has to be the same author)
		
		// Here you can also sort which fields are meant to be shown first
		// at the generated template. Simply the first one goes first. ;)
		return array(
			'__names' => array(
					'last' => $this->lastNames,
					'first' => $this->firstNames
				),
			'title' => $this->title,
			'date' => $this->date,
			'publisher' => $this->publisher,
			'location' => translateWithInterwiki($this->place,'en'),
			'isbn' => $this->ISBN,
			'__sourceurl' => $this->source,
			'__refname' => $this->refname
		);
	}
}
?>
