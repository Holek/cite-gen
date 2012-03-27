<?php
/**
 * {{Cite book}} generator.
 *
 * @addtogroup Includes
 * @author Marcin Cieślak
 * @copyright © 2012 Marcin Cieślak
 * @license GNU General Public Licence 2.0 or later
 */

abstract class ISBNBaseParser extends Parser {
        protected $lastNames = array();
        protected $firstNames = array();
        protected $date;
        protected $publisher;
        protected $place;
        protected $source;
        protected $ISBN;

	
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
			'location' => translateWithInterwiki($this->place, $this->getSiteLanguage()),
			'isbn' => $this->ISBN,
			'__sourceurl' => $this->source
		);
	}

	abstract protected function getSiteLanguage();
}
