<?php
/**
 * French {{Cite book}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Book_fr extends Template {

	private $template;
	private $unusedParameters = array(); 
	public $settings;
	private $template_transition_array = array(
	//{{cite book
		'title' => 'titre',
		'url' => 'url',
		'date' => 'année',
		'year' => 'année',
		'month' => 'mois',
		'publisher' => 'éditeur',
		'location' => 'lieu',
		'language' => 'langue',
		'isbn' => 'isbn',
		'ISBN' => 'isbn',
		'page' => 'passage',
		'pages' => 'passage',
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
		$this->template = '{{ouvrage '.(($this->settings['append-newlines'])?"\r\n":' ');
		foreach ($book as $key => $value)
		{
			if ($key == 'isbn')
			{
				$this->appendParameter($key,ISBN_output::modifyISBN($value));
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
		$this->appendParameter('pages');
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
		for($i = 1;$i<=count($array['last']);$i++)
		{
			$this->appendParameter('nom'.$i,$array['last'][$i-1]);
			$this->appendParameter('prénom'.$i,$array['first'][$i-1]);
			if ($settings['append-author-link'] == true)
			{
				$articles_array[$array['first'][$i-1].' '.$array['last'][$i-1]] = $i;
			}
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			foreach ($articles_array as $author_link => $i)
			{
				$this->appendParameter('lien auteur'.$i,$author_link);
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
