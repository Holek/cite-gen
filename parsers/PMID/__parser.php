<?php


class PMID_parser extends InputCheck
{
	static public function checkInput($input)
	{
		if (is_numeric($input) && $input > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	static public function listParsers()
	{
		$return = array(
					'International' => array(
											array(
												'value' => 'PMID',
												'label' => 'PubMED'
											)
										)
					);
		return array($return,array('PMID'));
	}
}

?>