<?php

class PMID_parser extends InputCheck
{
	static public function checkInput($input)
	{
		return (is_numeric($input) && $input > 0 && (int)$input == $input);
	}

	static public function listParsers()
	{
		$return = array('International' => array(
							array(
								'value' => 'PMID',
								'label' => 'PubMED'
							)
				));
		return array($return,array('PMID'));
	}
}


