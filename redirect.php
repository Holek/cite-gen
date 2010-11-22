<?php
/**
 * Citation template generator.
 *
 * @addtogroup Main
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

$query = array();
parse_str($_SERVER['QUERY_STRING'],$query);

// Clean query array
foreach ($query as $key => $value)
{
	if (is_array($value))
	{
		foreach ($value as $vKey => $vValue)
		{
			if ($vValue == '')
			{
				unset ($query[$key][$vKey]);
			}
		}
	}
	else
	{
		if ($value == '')
		{
			unset ($query[$key]);
		}
	}
}

header('Status: 301');
header('Location: http://'.$_SERVER['SERVER_NAME'].str_replace('redirect.php','index.php',$_SERVER['SCRIPT_NAME']).'?'.http_build_query($query));
exit;
?>