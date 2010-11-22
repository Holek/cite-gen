<?php
/**
 * English {{Cite book}} template parser.
 *
 * @addtogroup Templates
 * @author Michał "Hołek" Połtyn
 * @copyright (C) 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Book_en extends Template {
	/**
	 * The template itself
	 * @var string
	 */
	private $template;
	/**
	 * Settings array
	 * @var array
	 */
	public $settings;

	/**
	 * Constructor, creates the template
	 * @param array $book
	 * @param array $settings
	 */
	public final function __construct($book,$settings)
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
		$this->template = '{{Cite book '.(($this->settings['append-newlines'])?"\r\n":' ');
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
		$this->template .= '| '.$key.' = '.$value.(($this->settings['append-newlines'])?"\r\n":' ');
	}
	
	/**
	 * Appends authors with their links if applicable
	 * @param array $array
	 */
	private function getAuthors($array)
	{
		$author_string = '';
		$author_link_array = array();
		for($i = 1;$i<=count($array['last']);$i++)
		{
			$this->appendParameter('last'.$i,$array['last'][$i-1]);
			$this->appendParameter('first'.$i,$array['first'][$i-1]);
			if ($this->settings['append-author-link'] == true)
			{
				$articles_array[$array['first'][$i-1].' '.$array['last'][$i-1]] = $i;
			}
		}
		if (count($articles_array))
		{
			areArticles($articles_array);
			foreach ($articles_array as $author_link => $i)
			{
				$this->appendParameter('author'.$i.'-link',$author_link);
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
	 * There are no unused parameters in this template
	 * @return boolean
	 */
	function unusedParameters() {
		return false;
	}
}
?>