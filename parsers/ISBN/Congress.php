<?php
/**
 * Library of Cognress parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Congress extends ISBNBaseParser {

	public function fetch($ISBN)
	{
		$url = @file_get_contents('http://z3950.loc.gov:7090/voyager?version=1.1&operation=searchRetrieve&maximumRecords=1&recordSchema=dc&query='.$ISBN);
		if (!$url)
		{
			$this->errors[]= array('base-disabled','Library of Congress');
		}
		$p = xml_parser_create();

		xml_parse_into_struct($p,$url,$results,$index);
		xml_parser_free($p);

		$this->title = $results[$index['TITLE'][0]]['value'];
		$this->title = substr($this->title,0,-3);
		if ($this->title)
		{
			$this->publisher = $results[$index['PUBLISHER'][0]]['value'];
			preg_match('/(.*?) : (.*?)(;|,)/s', $this->publisher, $temp);
			$this->publisher = ($temp[2])?$temp[2]:$this->publisher;
			$this->place = $temp[1];
			preg_match('/(\d+)/',$results[$index['DATE'][0]]['value'],$date);
			$this->date = $date[1];

			foreach ($index['CREATOR'] as $author_temp) {
				preg_match('/(.+?), (.*?)(,|$)/', $results[$author_temp]['value'], $temp);
				$this->firstNames[] = $temp[2];
				$this->lastNames[] = $temp[1];
			}
			$this->ISBN = ISBN_output::modifyISBN($ISBN);

			$this->source = 'http://catalog.loc.gov/cgi-bin/Pwebrecon.cgi?DB=local&Search_Code=GKEY^*&CNT=100&hist=1&type=quick&Search_Arg='.$ISBN;
		}
		else
		{
			if ($isbndb_error != '')
			{
				$this->errors[] = array('','LOC error: '.$isbndb_error);
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
			'__sourceurl' => $this->source
		);
	}

}
?>
