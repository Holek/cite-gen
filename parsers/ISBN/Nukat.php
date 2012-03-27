<?php
/**
 * Nukat parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Nukat extends ISBNBaseParser {

	public function fetch($ISBN)
	{
		global $debug;
		$ini = parse_ini_file('./parsers/ISBN/Nukat.ini');
		$u1 = ((strlen($ISBN)==13)?'6000':'7');
		$data = @file_get_contents($address = $ini['url'].'?search=KEYWORD&function=CARDSCR&pos=1&u1='.$u1.'&t1='.$ISBN);
		if (!$data)
		{
			$this->errors[]= array('base-disabled','NUKAT');
		}
		if (strpos($data, '<!-- Bib record -->') !== false)
		{
			$array = array();
			preg_match_all(',
    <tr>
      
      <th align="center" valign="top" width="20%" >
        (.*?)
      </th>
      <td >
        (.*?)
      </td>
       
    </tr>,m', $data, $temp);
			for ($i = 0; $i < count( $temp[1] ); $i ++) {
				$array[ mb_strtolower($temp[1][$i]) ] = trim(preg_replace( array(
					'@<a[^>]*?>@siu', '@</a>@siu'), '', $temp[2][$i]
				));
			}
			$this->title = preg_replace('#/.*$#', '', $array['tytuł']);

			preg_match('#(.*?), (.*?) \(#', $array['autor'], $author);
			$this->lastNames[] = $author[1];
			$this->firstNames[] = $author[2];

			preg_match('#(.*?) +: +(.*?), (\d+)#', $array['adres wydawniczy'], $adres);
			$this->place = $adres[1];
			$this->publisher = str_replace('Wydaw. ', 'Wydawnictwo ', $adres[2]);
			$this->date = $adres[3];

			$this->source = $address . "&skin=reader";

			$this->ISBN = $ISBN;
		} else {
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
			'location' => translateWithInterwiki($this->place,'pl'),
			'isbn' => $this->ISBN,
			'__sourceurl' => $this->source
		);
	}
}

?>
