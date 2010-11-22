<?php
/**
 * Error handler.
 *
 * @addtogroup Main
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

class Error {
	/**
	 * Stores the errors
	 * @var Array
	 */
	private $errors;

    /**
     * Constructor for objects of class isbnDB
     */
	public function __construct()
	{
		$this->errors = array();
	}
	
	/**
	 * Reports the error
	 * @param int $errorCode Error numeric code
	 * @param string $errorDesc[optional] Error description if undefined error code
	 */
	public function report($errorCode, $errorDesc = '')
	{
		$this->errors[] = array(0 => $errorCode, 1 => $errorDesc);
	}
	
	/**
	 * Report multiple errors at once
	 * @param array $array
	 */
	public function report_array($array)
	{
		$this->errors = array_merge($this->errors, $array);
	}

    /**
     * Returns errors array
     * @return array
     */
	public function output_errors()
	{
		$errors_array = array();
		foreach ($this->errors as $error)
		{
			if ($messages[$scriptLanguage][$error[0]]) // if there's a message in the given language ...
			{
				$errors_array[] = getMessage($error[0],$error[1]); // ... push the error message as the parameter ...
			}
			else
			{
				$errors_array[] = $error[1]; // ... else just print the error message
			}
		}
		return $errors_array;
	}
}
?>