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

	public function fetchURL( $url, $params ) {
		if ( strpos( $url, "?" ) > 0 ) {
			$sep = "&";
		} else {
			$sep = "?";
		}
		$data = @file_get_contents($address = $url . $sep . $params);
		return $data;
	}

	public function fetch($ISBN)
	{
		global $debug;
		$ini = parse_ini_file('./parsers/ISBN/Nukat.ini');
		$u1 = ((strlen($ISBN)==13)?'6000':'7');
		$params = "search=KEYWORD&function=CARDSCR&pos=1&u1=" . $u1 . "&t1=" . $ISBN;
		$url = $ini['url'];

		$data = $this->fetchURL( $url, $params );
		$redirect = handleMetaRedirect( $data );
		if( $redirect ) {
			$data = $this->fetchURL( $redirect, $params );
		}
		
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

	protected function getSiteLanguage() {
		return 'pl';
	}
}

?>
