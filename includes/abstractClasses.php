<?php
/**
 * {{Cite book}} generator.
 *
 * @addtogroup Includes
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */


/**
 * Abstract class InputCheck for pre-parsing purposes. We don't want ie. to check ISBN against PMID 
 */
abstract class InputCheck
{
	/**
	 * Checks the input against given parsers parameters
	 * @param string $input
	 * @return boolean
	 */
	static function checkInput($input)
	{
		return false;
	}
	
	/**
	 * Returns an array with parsers' filenames
	 * @return array
	 */
	static function listParsers()
	{
		return array();
	}
	
	/**
	 * Prepares input before parsing
	 * @param object $input
	 * @return 
	 */
	static function prepareInput($input)
	{
		return $input;
	}
}
/**
 * Abstract class Parser used in input parsers
 */
abstract class Parser
{
        protected $title = false;
        protected $errors = array();

	public function __construct($input) {
		$this->fetch($input);
	}

	abstract public function fetch($identifier); 

	public function getTitle() {
		return $this->title;
	}
	abstract public function getOutput();

	public function getErrors() {
		return $this->errors;
	}
}

/**
 * Abstract class Template used in output parsers
 */
abstract class Template
{
	/**
	 * Creates a template out of the input array
	 * @param array $input Input array with all 
	 * @param array $settings Given user settings
	 */
    function __construct($input,$settings) {}
	/**
	 * Returns the template
	 * @return string
	 */
    function __toString() {}
	/**
	 * Returns parameters in the input array which haven't been used
	 * @return array
	 */
	function unusedParameters() {}
}

/**
 * Abstract class Cache for caching options
 */
abstract class Cache
{
	/**
	 * Adds a value into cache system
	 * @param string $id
	 * @param object $var 
	 */
	public function add($id,$var) {}
	/**
	 * Retrieves a value from a cache system
	 * @param string $id
	 * @return object
	 */
	public function fetch($id) {}
	/**
	 * Saves the current cache state and, if applicable, performs maintainance of the cache system
	 * @return 
	 */
	public function save() {}
}

?>
