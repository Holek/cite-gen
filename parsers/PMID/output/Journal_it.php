<?php
/**
 * Italian {{Cite journal}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Journal_it extends Template {

	private $template;
	private $unusedParameters = array(); 
	public $settings;
	private $template_transition_array = array(
	//{{cite journal
		'last' => 'cognome',
		'first' => 'nome',
		'authorlink' => 'linkautore',
		'title' => 'titolo',
		'year' => 'anno',
		'month' => 'mese',
		'issue' => 'numero',
		'pages' => 'pagine',
		'journal' => 'rivista',
	);

	public function __construct($book,$settings=Array())
	{
		$this->settings = array(
							'append-author-link' => true,
							'append-newlines' => false,
							'add-references' => false,
							'add-list' => false
						);
		foreach ($settings as $option => $value)
		{
			$this->settings[$option] = $value;
		}
		$this->template = '{{Cita pubblicazione '.(($this->settings['append-newlines'])?"\r\n":' ');
		foreach ($book as $key => $value)
		{
			if ($key == 'PMID')
			{
				$this->appendParameter('id','PMID '.$value);
			}
			elseif (strpos($key,'__') === false)
			{
				$this->appendParameter($key,$value);
			}
			elseif($key == '__names')
			{
				$this->getAuthors($value);
			}
		}
		$this->template .= '}}';
		if ($this->settings['add-references'])
		{
			$this->template = '<ref'.((strlen($book['__refname']))?' name="'.$book['__refname'].'"':'').'>'.$this->template.'</ref>';
		}
		if ($this->settings['add-list'])
		{
			$this->template = '* '.$this->template;
		}
	}
	
	/**
	 * Appends another parameter to the template.
	 * @param string $key Template key
	 * @param string $value[optional] Key value
	 */
	private function appendParameter($key, $value='')
	{
		if ($this->template_transition_array[$key] != '')
		{
			$this->template .= '| '.$this->template_transition_array[$key].' = '.$value.(($this->settings['append-newlines'])?"\r\n":' ');
		}
		elseif (!$this->template_transition_array[$key])
		{
			$this->template .= '| '.$key.' = '.$value.(($this->settings['append-newlines'])?"\r\n":' ');
		}
		else
		{
			$this->unusedParameters[$key] = $value;
		}
	}
	
	/**
	 * Appends authors with their links if applicable
	 * @param array $array
	 */
	private function getAuthors($array)
	{
		$author_link_array = array();
		$this->appendParameter('last',$array['last'][0]);
		$this->appendParameter('first',$array['first'][0]);
		if ($settings['append_author_link'] == true)
		{
			$articles_array[$array['first'][0].' '.$array['last'][0]] = 0;
		}
		$coauthors = '';
		for($i = 1;$i<count($array['last']) && $i <= 10;$i++)
		{
			if ($settings['append_author_link'] == true)
			{
				$articles_array[$array['first'][$i].' '.$array['last'][$i]] = $i;
			}
			$coauthors .= '; '.$array['first'][$i].' ' .$array['last'][$i];
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			foreach ($articles_array as $name => $id)
			{
				switch ($id)
				{
					case 0:
						$this->appendParameter('authorlink',$name);
						break;
					default:
						$coauthors = str_replace('; '.$name, '; [['.$name.']]', $coauthors);
						break;
				}
			}
		}
		$this->appendParameter('coauthors',substr($coauthors,2));
	}
	
	/**
	 * Returns the template
	 * @return string
	 */
	public function __toString()
	{
		return $this->template;
	}
	
	/**
	 * Returns the unused parameters in the template. False if there are no unused parameters.
	 * @return mixed
	 */
	public function unusedParameters()
	{
		return ((count($this->unusedParameters))?$this->unusedParameters:false);
	}
}
?>
