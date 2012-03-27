<?php
/**
 * Nukat parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class DNB extends ISBNBaseParser {

	public function fetch($ISBN)
	{
		$fh = fopen($this->source = 'https://portal.d-nb.de/opac.htm?method=showFullRecord&currentPosition=0&currentResultId='.$ISBN.'%2526any','r');
		if ($fh) // if file found
		{
			$foundCOinS = false; // COinS checker
			$text = ''; // text storage
			while (!$foundCOinS && !feof($fh))
			{
				$text .= $line = stream_get_line($fh, 20*1024, 'Ende COinS');
				if (stripos($line, 'Einbindung COinS fuer Zotero'))
				{
					$foundCOinS = true;
				}
			}
			fclose($fh);
			
			if ($foundCOinS)
			{
				preg_match('/<span class="Z3988" title="(.*?)">/is',$text,$COinS);
				$COinS = htmlspecialchars_decode($COinS[1]);
				parse_str($COinS,$COinS);
				$title = explode(' : ',$COinS['rft_title']);
				$this->title = $title[0];
				$this->lastNames[] = $COinS['rft_aulast'];
				$this->firstNames[] = $COinS['rft_aufirst'];
				$this->ISBN = $COinS['rft_isbn'];
				$this->place = $COinS['rft_place'];
				$this->date = $COinS['rft_date'];
				$this->publisher = $COinS['rft_pub'];
			}
			else
			{
				$this->title = false;
			}
		}
		else
		{
			$this->errors[] = array('base-disabled','Deustche Nationalbibliothek (Berlin)');
			$this->title = false;
		}
	}

	protected function getSiteLanguage() {
		return 'de';
	}
}

?>
