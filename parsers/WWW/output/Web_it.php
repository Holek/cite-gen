<?php

class Web_it extends Template
{
	private $unusedParameters = array(); 
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


	private $template_transition_array = array(
	//{{cite web
		'last' => 'cognome',
		'first' => 'nome',
		'author' => 'autore',
		'title' => 'titolo',
		'url' => 'url',
		'accessdate' => 'accesso',
		'work' => 'opera',
		'publisher' => 'editore',
		'date' => 'data',
		'language' => 'lingua',
	);
	
	/**
	 * Creates a template out of the input array
	 * @param array $input Input array with all 
	 * @param array $settings Given user settings
	 */
    function __construct($input,$settings) {
		$this->settings = array(
							'append-newlines' => false,
							'add-references' => true
						);
		foreach ($settings as $option => $value)
		{
			$this->settings[$option] = $value;
		}
		$this->template = '{{Cita web '.(($this->settings['append-newlines'])?"\r\n":' ');
		$this->appendParameter('author');
		foreach ($input as $key => $value)
		{
			if (strpos($key,'__') === false)
			{
				$this->appendParameter($key,$value);
			}
		}
		$this->appendParameter('publisher');
		$this->appendParameter('date');
		$this->appendParameter('accessdate',date('j-m-Y'));
		$this->template .= '}}';

		if ($this->settings['add-references'])
		{
			$this->template = '<ref'.((strlen($input['__refname']))?' name="'.$input['__refname'].'"':'').'>'.$this->template.'</ref>';
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
		return ((count($this->unusedParameters))?$this->unusedParameters:false);
	}
}
?>
