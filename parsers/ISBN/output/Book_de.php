<?php
/**
 * German {{Literatur}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Book_de extends Template {

	private $template;
	private $unusedParameters = array(); 
	public $settings;
	private $template_transition_array = array(
	//{{Literatur
		'author' => 'Autor',
		'title' => 'Titel',
		'url' => 'Online',
		'date' => 'Jahr',
		'year' => 'Jahr',
		'month' => 'Monat',
		'publisher' => 'Verlag',
		'location' => 'Ort',
		'isbn' => 'ISBN',
		'doi' => 'DOI',
		'page' => 'Seiten',
		'pages' => 'Seiten',
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
		$this->template = '{{Literatur '.(($this->settings['append-newlines'])?"\r\n":' ');
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
		$author_string = '';
		$author_link_array = array();
		for($i = 0;$i<count($array['last']);$i++)
		{
			$author_string .= $array['first'][$i].' '.$array['last'][$i].', ';
			if ($settings['append-author-link'] == true)
			{
				$articles_array[$array['first'][$i].' '.$array['last'][$i]] = $i;
			}
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			foreach ($articles_array as $author_link => $i)
			{
				$author_string = str_replace($author_link.', ', '[['.$author_link.']], ',$author_string);
			}
		}
		$this->appendParameter('author',substr($author_string,0,-2));
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