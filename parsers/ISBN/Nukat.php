<?php
/**
 * Nukat parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Nukat extends Parser {

	protected $website;

	private $title;
	private $lastNames = array();
	private $firstNames = array();
	private $date;
	private $publisher;
	private $place;
	private $source;
	private $ISBN;
	
	/**
	 * Array consisting of errors reported on the way
	 * @var array
	 */
	private $errors = array();

    /**
     * Constructor for objects of class isbnDB
     */
	public function __construct($ISBN)
	{
		$ini = parse_ini_file('./parsers/ISBN/Nukat.ini');
		$u1 = ((strlen($ISBN)==13)?'6000':'7');
		$data = @file_get_contents($address = $ini['url'].'?search=KEYWORD&function=CARDSCR&pos=1&u1='.$u1.'&t1='.$ISBN);
		if (!$data)
		{
			$this->errors[]= array('base-disabled','NUKAT');
		}
		if (strpos($data, 'Brak wyników wyszukiwania') === false)
		{
			$data = explode('<form name="searchdata">', $data);
			$data = explode("\n</form>", $data[1]);
			$data = explode("\n", $data[0]);
			$array = array();
			foreach ($data as $line)
			{
				preg_match('/name="(.*?)" value="(.*?)" \/>$/', $line, $temp);
				$array[$temp[1]] = $temp[2];
			}
			
			$this->title = str_replace('/', '', $array['title']) . ' ' . substr($array['subtitle'],0,-2);

			preg_match('/(.*?), (.*?)$/', $array['author'], $author);
			$this->lastNames[] = $author[1];
			$this->firstNames[] = $author[2];

			preg_match('/(\d+)/',$array['publication_date'],$date);
			$this->date = $date[1];

			$this->publisher = str_replace('Wydaw. ', 'Wydawnictwo ', substr($array['publisher'],0,-1));

			$this->place = substr($array['publication_place'],0,-2);

			$this->source = $address;

			$this->ISBN = $ISBN;
		}
		else
		{
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
						'__names' => Array(
										'last' => $this->lastNames,
										'first' => $this->firstNames
									),
						'title' => $this->title,
						'date' => $this->date,
						'publisher' => $this->publisher,
						'location' => $this->place,
						'isbn' => $this->ISBN,
						'__sourceurl' => $this->source // The only non-existant in {{Cite book}} field. Consists of source URL where the info was taken from
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
