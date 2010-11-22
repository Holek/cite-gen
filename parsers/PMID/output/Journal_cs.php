<?php
/**
 * Czech {{Cite journal}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Journal_cs extends Template {

	private $template;
	private $unusedParameters = array(); 
	public $settings;
	private $template_transition_array = array(
	//{{cite journal
		'last' => 'příjmení',
		'first' => 'jméno',
		'coauthors' => 'spoluautoři',
		'title' => 'titul',
		'year' => 'rok',
		'month' => 'měsíc',
		'volume' => 'ročník',
		'issue' => 'číslo',
		'pages' => 'strany',
		'journal' => 'periodikum',
		'PMID' => 'pmid'
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
		$this->template = '{{Citace periodika '.(($this->settings['append-newlines'])?"\r\n":' ');
		foreach ($book as $key => $value)
		{
			if (strpos($key,'__') === false)
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
		for($i = 1;$i<=count($array['last']) && $i<=3;$i++)
		{
			$this->appendParameter('příjmení'.(($i==1)?'':$i),$array['last'][$i-1]);
			$this->appendParameter('jméno'.(($i==1)?'':$i),$array['first'][$i-1]);
			if ($settings['append-author-link'] == true)
			{
				$articles_array[$array['first'][$i-1].' '.$array['last'][$i-1]] = $i;
			}
		}
		if (count($array['last'])>3)
		{
			$this->appendParameter('coauthors','et al.');
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			foreach ($articles_array as $author_link => $i)
			{
				$this->appendParameter('odkaz na autora'.(($i==1)?'':$i),$author_link);
			}
		}
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
