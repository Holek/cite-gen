<?php

class Journal_en extends Template
{
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
	 * Creates a template out of the input array
	 * @param array $input Input array with all 
	 * @param array $settings Given user settings
	 */
    function __construct($input,$settings) {
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
		$this->template = '{{Cite journal '.(($this->settings['append-newlines'])?"\r\n":' ');
		foreach ($input as $key => $value)
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
		$this->template .= '| '.$key.' = '.$value.(($this->settings['append-newlines'])?"\r\n":' ');
	}

	/**
	 * Appends authors with their links if applicable
	 * @param array $array
	 */
	private function getAuthors($array)
	{
		for($i = 1;$i<=count($array['last']) && $i <= 9;$i++)
		{
			$this->appendParameter('last'.$i,$array['last'][$i-1]);
			$this->appendParameter('first'.$i,$array['first'][$i-1]);
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