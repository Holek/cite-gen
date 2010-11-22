<?php
/**
 * ISBNdb.com parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class PMID extends Parser {

	private $title;
	private $lastNames = array();
	private $firstNames = array();
	private $journal;
	private $volume;
	private $issue;
	private $pages;
	private $month;
	private $year;
	private $doi;
	private $source;
	private $PMID;
	private $refname;

	/**
	 * Array consisting of errors reported on the way
	 * @var array
	 */
	private $errors = array();

    /**
     * Constructor for objects of class isbnDB
     */
	public function __construct($PMID)
	{
		$this->source = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id='.$PMID.'&retmode=xml';
		$url = file_get_contents($this->source);
		$p = @xml_parser_create();

		@xml_parse_into_struct($p,$url,$results,$index);
		@xml_parser_free($p);

		if ($results[$index[ARTICLETITLE][0]][value])
		{
			$this->PMID = $results[$index[PMID][0]][value];
			$this->title = $results[$index[ARTICLETITLE][0]][value];
			$this->journal = $results[$index[MEDLINETA][0]][value];
			$this->volume = $results[$index[VOLUME][0]][value];
			$this->issue = $results[$index[ISSUE][0]][value];
			$this->pages = $results[$index[MEDLINEPGN][0]][value];
			for ($i=0; $i<count($index[LASTNAME]); $i++)
			{
				$this->lastNames[] = $results[$index[LASTNAME][$i]][value];
				$this->firstNames[] = $results[$index[INITIALS][$i]][value].((strpos($results[$index[INITIALS][$i]][value],'.')===false)?'.':'');
			}
			for ($i=0; $i < count($index[ARTICLEID]);$i++)
			{
				if ($results[$index[ARTICLEID][$i]][attributes][IDTYPE] == 'doi')
				{
					$this->doi = $results[$index[ARTICLEID][$i]][value];
					break;
				}
			}
			for ($i=0;$i<count($index[MONTH]);$i++)
			{
				if ($results[$index[MONTH][$i]][level] == 8)
				{
					$this->month = $results[$index[MONTH][$i]][value];
					break;
				}
			}
			for ($i=0;$i<count($index[YEAR]);$i++)
			{
				if ($results[$index[YEAR][$i]][level] == 8)
				{
					$this->year = $results[$index[YEAR][$i]][value];
					break;
				}
			}
			$this->refname = $this->lastNames[0].'-'.$this->year;
		}
		else
		{
			$this->errors[] = array('PMID-error','PMID error: '.$results[$index[ERROR][0]][value]);
			$this->title = false;
		}
	}

    /**
     * Title getter. Returns book title if found, FALSE if not found.
     * 
     * @return     mixed
     */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Book info getter
	 *
	 * @return     array
	 */
	public function getOutput()
	{
		// Array consisting of values, which keys are named
		// after English Wikipedia {{Cite journal}} template,
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
						'__names' => Array(
										'last' => $this->lastNames,
										'first' => $this->firstNames
									),
						'title' => $this->title,
						'journal' => $this->journal,
						'volume' => $this->volume,
						'issue' => $this->issue,
						'pages' => $this->pages,
						'month' => $this->month,
						'year' => $this->year,
						'doi' => $this->doi,
						'PMID' => $this->PMID,
						'__refname' => $this->refname,
						'__sourceurl' => 'http://www.ncbi.nlm.nih.gov/pubmed/'.$this->PMID.'?dopt=Abstract'
					);
	}

    /**
     * Returns $errors.
     * @see isbnDB::$errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
?>
