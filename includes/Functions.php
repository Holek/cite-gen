<?php
/**
 * Main citation template generator functions.
 *
 * @addtogroup Main
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

/**
 * Looks for desired input variable inside $_GET array.
 * @return object
 * @param string $var 
 * @param object $default[optional]
 */
function get($var, $default = null) # still not exactly what it was supposed to be
{
	if (isset($_GET[$var]))
	{
		return $_GET[$var];
	}
	else
	{
		return $default;
	}
}
/**
 * Removes possible vulnerabilities from arrays by modifying
 * given array ($array) and directory ($dir), in which filenames
 * (values of $array) should consist only of letters, numbers,
 * underscores and minus signs. Besides that, returned array
 * consists only of filenames of files that actually exist.
 * @param array $array
 * @param string $dir
 */
function removeDeadEntries( &$array, $dir )
{
	for ($i=0;$i<count($array);$i++)
	{
		for ($j=$i+1;$j<count($array);$j++)
		{
			if ($array[$i] == $array[$j])
			{
				unset($array[$j]);
				$j--;
			}
		}
	}
	$newArray = array();
	foreach ( $array as $entry )
	{
		if ( preg_match( '/^[a-zA-Z0-9-_]+$/', $entry ) )
		{
			if ( is_file('./'.$dir.'/'.$entry.'.php') )
			{
				$newArray[] = $entry;
			}
		}
	}
	$array = $newArray;
}

/**
 * Creates an array if given data is not an array already.
 * @return array
 * @param object $data
 */
function createArray($data)
{
	if (!is_array($data) && !is_null($data))
	{
		$data = explode('|',$data);
	}
	return $data;
}

/**
 * Creates a <select> tag with desired data. If user wants to have option groups, $data has to be a two-dimensional array.
 * @return string|boolean
 * @param string $name Name of the <select> form
 * @param array $data Data to put inside the form
 * @param array $selected[optional] Initially selected options
 * @param string $first[optional] First group to show up
 * @param object $groups[optional] Groups created with <optgroup>
 * @param int $size[optional] Vertical size of the <select> form
 * @param boolean $mutliple[optional] Enables multiple choices to be selected
 */
function createSelect($name, $data, $selected = array(), $first = null, $groups = false, $size = 1, $multiple = false)
{
	if (!count($data))
	{
		return false;
	}
	$select = '<select name="'.$name.(($multiple)?'[]':'').'" id="'.$name.'"';
	if ($multiple != false)
	{
		$select .= ' multiple="multiple"';
	}
	if (is_int($size))
	{
		$select .= ' size="'.$size.'"';
	}
	$select .= '>';

	if ($groups != false)
	{
		if ($first && $data[$first])
		{
			$select .= '<optgroup label="'.getMessage($first).'">';
			foreach ($data[$first] as $option)
			{
				if (is_array($option)) // $option['value'] & $option['label']
				{
					$select .= '<option value="'.$option['value'].'"';
					if (in_array($option['value'], $selected)) 
					{
						$select .= ' selected="selected"';
					}
					$select .= '>'.$option['label'].'</option>';
				}
				else // $option = value
				{
					$select .= '<option';
					if (in_array($option, $selected)) 
					{
						$select .= ' selected="selected"';
					}
					$select .= '>'.$option.'</option>';
				}
			}
			$select .= '</optgroup>';
			unset($data[$first],$label);
		}
		foreach ($data as $label => $optgroup)
		{
			$select .= '<optgroup label="'.getMessage($label).'">';
			foreach ($optgroup as $option)
			{
				if (is_array($option)) // $option['value'] & $option['label']
				{
					$select .= '<option value="'.$option['value'].'"';
					if (in_array($option['value'], $selected))
					{
						$select .= ' selected="selected"';
					}
					$select .= '>'.$option['label'].'</option>';
				}
				else // $option = value
				{
					$select .= '<option value="'.$option.'"';
					if (in_array($option, $selected)) 
					{
						$select .= ' selected="selected"';
					}
					$select .= '>'.getMessage($option).'</option>';
				}
			}
			$select .= '</optgroup>';
		}
	}
	else
	{
		foreach ($data as $option)
		{
			if (is_array($option)) // $option['value'] & $option['label']
			{
				$select .= '<option value="'.$option['value'].'"';
				if (in_array($option['value'], $selected)) 
				{
					$select .= ' selected="selected"';
				}
				$select .= '>'.$option['label'].'</option>';
			}
			else // $option = value
			{
				$select .= '<option value="'.$option.'"';
				if (in_array($option, $selected)) 
				{
					$select .= ' selected="selected"';
				}
				$select .= '>'.getMessage($option).'</option>';
			}
		}
	}

	$select .= '</select>';
	return $select;
}

/**
 * Checks whether articles in $titles_array exist on particular wiki before including author links in produced template
 * Modifies the array on the fly, removing non-existant articles from the array. Array has to be in format:
 * Array
 * (
 *     [article title1] => id1
 *     [article title2] => id2
 *     [article title3] => id3
 *     (etc.)
 * )
 * @param object $titles_array
 */
function areArticles(&$titles_array)
{
	if (count($titles_array))
	{
		global $error;
		
		$authors_sql = '';
		foreach ($titles_array as $title => $id)
		{
			$authors_sql .= ' OR `page_title` = "' . mysql_real_escape_string(str_replace(' ', '_', $title)) . '"';
		}
		$query = 'SELECT `page_title`  FROM page WHERE ('. substr($authors_sql,3) . ') AND `page_namespace` = 0;';
		$result = mysql_query($query);
		
		$titles = array();
		while ($row = mysql_fetch_row($result))
		{
			$author = str_replace('_', ' ',$row[0]);
			$titles[$author] = $titles_array[$author];
		}
	
		$titles_array = $titles;
	}
}

/**
 * I18N message getter.
 * @param string $id
 * @param string $s1 [optional]
 * @param string $s2 [optional]
 * @param string $s3 [optional]
 * @return string
 */
function getMessage($id, $s1 = '',$s2 = '',$s3 = '')
{
	global $messages, $scriptLanguage, $wgLanguageNames;
	if (isset($wgLanguageNames[$id]))
	{
		return $wgLanguageNames[$id];
	}
	else if (isset($messages[$scriptLanguage]) && isset($messages[$scriptLanguage]['ts-citegen-'.$id]))
	{
		return sprintf($messages[$scriptLanguage]['ts-citegen-'.$id], $s1, $s2, $s3);
	}
	else
	{
		return (($s1)?$s1:$id);
	}
}

/**
 * Prepares language array to use in templates
 * @return array
 */
function prepareLanguageArray()
{
	global $messages, $scriptLanguage;
	$langAry = array();
	foreach($messages[$scriptLanguage] as $key => $message) {
		// strip "ts-citegen-" prefix from translation keys for templates
		// length of ts-citegen- equals 11
		$langAry[substr($key, 11)] = $message;
	}
	return $langAry;
}

/**
 * Function for writing debug info from within various classes.
 * @param string $message
 */
function debugWrite($message)
{
	global $debug;
	$debug .= $message."\n";
}
