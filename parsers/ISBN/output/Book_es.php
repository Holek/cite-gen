<?php
/**
 * Spanish {{Cita libro}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright (C) 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */


class Book_es extends Template {

	private $template;
	private $unusedParameters = array(); 
	public $settings;
	private $template_transition_array = array(
	//{{cite book
		'last' => 'apellidos',
		'first' => 'nombre',
		'authorlink' => 'enlaceautor',
		'coauthors' => 'coautores',
		'others' => 'otros',
		'title' => 'título',
		'url' => 'url',
		'format' => 'formato',
		'accessdate' => 'fechaacceso',
		'edition' => 'edición',
		'series' => '',
		'volume' => 'volumen',
		'date' => 'fecha',
		'origyear' => '',
		'year' => 'año',
		'month' => 'mes',
		'publisher' => 'editorial',
		'location' => 'ubicación',
		'language' => 'idioma',
		'isbn' => 'isbn',
		'ISBN' => 'isbn',
		'bibcode' => '',
		'id' => 'id',
		'page' => 'página',
		'pages' => 'páginas',
		'chapter' => 'capítulo',
		'chapterurl' => 'urlcapítulo',
		'quote' => 'cita',
		'ref' => '',
		'postscript' => ''
	);

	public function __construct($book,$settings=Array())
	{
		$this->settings = array(
							'append-author-link' => false,
							'append-newlines' => false,
							'add-references' => false,
							'add-list' => false
						);
		foreach ($settings as $option => $value)
		{
			$this->settings[$option] = $value;
		}
		$this->template = '{{Cita libro '.(($this->settings['append-newlines'])?"\r\n":' ');
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
		$this->appendParameter('last',$array['last'][0]);
		$this->appendParameter('first',$array['first'][0]);
		if ($settings['append-author-link'] == true)
		{
			$articles_array[$array['first'][0].' '.$array['last'][0]] = 1;
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			$this->appendParameter('authorlink',$array['first'][0].' '.$array['last'][0]);
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