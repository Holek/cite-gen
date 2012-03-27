<?php
/**
 * National Library of Poland parser.
 *
 * @addtogroup Parsers
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Warsaw extends ISBNBaseParser {

	public function fetch($ISBN)
	{
		$url = @file_get_contents('http://alpha.bn.org.pl/search~S4*pol?/i/i/,,,/marc&FF=i'.$ISBN);
		if (!$url)
		{
			$this->errors[]= array('base-disabled','National Library of Poland');
		}
		$marc = explode('<pre>', $url);
		$marc = explode('</pre>', $marc[1]);
		// Checking whether it's the book we're looking for
		if (strpos(ISBN_parser::prepareInput($marc[0]), $ISBN))
		{
			$marc = explode("\n", $marc[0]); // Useful for creating data array
	
			$data_array = array();
			$current_index = '';
			foreach ($marc as $marc_line)
			{
				if (preg_match('/^[0-9]{3}$/', substr($marc_line, 0, 3)))
				{
					$current_index = 'a' . substr($marc_line, 0, 3);
					if (is_null($data_array[$current_index]))
					{
						$data_array[$current_index] = substr($marc_line, 7);
					}
					else if (is_array($data_array[$current_index]))
					{
						array_push($data_array[$current_index], substr($marc_line, 7));
					}
					else
					{
						$data_array[$current_index] = array($data_array[$current_index], substr($marc_line, 7));
					}
				}
				else
				{
					if (is_array($data_array[$current_index]))
					{
						$data_array[$current_index][count($data_array[$current_index])-1] = $data_array[$current_index][count($data_array[$current_index])-1] . substr($marc_line, 7);
					}
					else
					{
						$data_array[$current_index] = $data_array[$current_index] . substr($marc_line, 7);
					}
				}
			}

			/*
			 * Data sheet:
			 * a020 - ISBN
			 * a100 - authors
			 * a245 - book title
			 * a250 - edition
			 * a260 - location, publisher, year
			 * a700 - editors
			 */
			// ISBN
			$this->ISBN = $ISBN;
			
			// authors
			if (is_array($data_array['a100']))
			{
				foreach($data_array['a100'] as $author)
				{
					$author = $this->getParamsArray($author);
	                preg_match('/(.*?), (.*?)$/', $author[0], $temp);
	                $this->firstNames[] = preg_replace('/([a-z])\./', '\1', $temp[2]);
	                $this->lastNames[] = $temp[1];
					unset($temp);
				}
			}
			else if (isset($data_array['a100']))
			{
				$author = $this->getParamsArray($data_array['a100']);
                preg_match('/(.*?), (.*?)$/', $author[0], $temp);
	            $this->firstNames[] = preg_replace('/([a-z])\./', '\1', $temp[2]);
                $this->lastNames[] = $temp[1];
				unset($temp);
			}
			
			// book title
			$title = $this->getParamsArray($data_array['a245']);
			$this->title = str_replace(array(' :', ' /'), array('', ''), $title[0]);

			// location, publisher, date
			$phys = $this->getParamsArray($data_array['a260']);
			$this->place = str_replace(' :', '', $phys[0]);
			$this->publisher = str_replace(',', '', $phys['b']);
			preg_match('/(\d+)/',$phys['c'],$date);
			$this->date = $date[1];

			// source url
			$this->source = 'http://alpha.bn.org.pl/search~S4*pol?/i/i/,,,/frameset&FF=i'.$ISBN;
		}
		else
		{
			$this->title = false;
		}
	}

	/**
	 * Creates parameters' array out of MARC 21 row
	 * @return array
	 * @param string $data
	 */
	private function getParamsArray($data)
	{
		$array = explode('|',$data);
		for($i=1; $i<count($array);$i++)
		{
			$array[substr($array[$i],0,1)] = substr($array[$i],1);
			unset($array[$i]);
		}
		return $array;
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
