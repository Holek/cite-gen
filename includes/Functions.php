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
		global $error,$mysqli;
		
		$authors_sql = '';
		foreach ($titles_array as $title => $id)
		{
			$authors_sql .= ' OR `page_title` = "' . $mysqli->real_escape_string(str_replace(' ', '_', $title)) . '"';
		}
		$query = 'SELECT `page_title`  FROM page WHERE ('. substr($authors_sql,3) . ') AND `page_namespace` = 0;';
		$result = $mysqli->query($query);
		
		$titles = array();
		while ($row = $result->fetch_row())
		{
			$author = str_replace('_', ' ',$row[0]);
			$titles[$author] = $titles_array[$author];
		}
	
		$titles_array = $titles;
	}
}

/**
 * Translates a page name from one language to another using interwiki links.
 * @param string $article
 * @return string
 */
function translateWithInterwiki($article,$lang) {
	global $templateLanguage;
	if ($templateLanguage == $lang) return $article;
	global $transSQL,$tl;
	if (!isset($transSQL)) $transSQL = array();
	if (!is_a($transSQL[$lang],'MySQLi')) {
		$transSQL[$lang] = dbconnect($lang . 'wiki_p');
		$tl = $transSQL[$lang]->real_escape_string($templateLanguage);
	}
	$tarticle = $transSQL[$lang]->real_escape_string($article);
	$sql = <<<SQL
SELECT ll1.ll_title AS trans1, ll2.ll_title AS trans2 FROM page AS p1
  LEFT JOIN pagelinks
    ON p1.page_is_redirect = 1
      AND pl_from = p1.page_id
    LEFT JOIN page AS p2
      ON pl_namespace = 0
        AND pl_namespace = p2.page_namespace
        AND pl_title = p2.page_title
      LEFT JOIN langlinks AS ll1
        ON ll1.ll_from = p1.page_id
        AND ll1.ll_lang = "{$tl}"
        LEFT JOIN langlinks AS ll2
          ON ll2.ll_from = p2.page_id
          AND ll2.ll_lang = "{$tl}"
  WHERE p1.page_namespace = 0
    AND p1.page_title = "{$tarticle}"
SQL;
	$res = $transSQL[$lang]->query($sql);
	$row = $res->fetch_assoc();
	if (is_null($row)) {
		return $article;
	} else if (isset($row['trans1'])) {
		return $row['trans1'];
	} else if (isset($row['trans2'])) {
		return $row['trans2'];
	}
	return $article;
}

/**
 * I18N message getter.
 * @param string $id
 * @return string
 */
function getMessage($id)
{
	global $messages, $scriptLanguage, $rootLanguage, $wgLanguageNames;
	if (isset($wgLanguageNames[$id]))
	{
		return $wgLanguageNames[$id];
	}
	else if (isset($messages[$scriptLanguage]) && (isset($messages[$scriptLanguage]['ts-citegen-'.$id]) || ($rootLanguage && isset($messages[$rootLanguage]) && isset($messages[$rootLanguage]['ts-citegen-'.$id])) || isset($messages['en']['ts-citegen-'.$id])))
	{
		$args = func_get_args();
		array_shift($args);
		$message = (isset($messages[$scriptLanguage]['ts-citegen-'.$id]) ? $messages[$scriptLanguage]['ts-citegen-'.$id] :
				(($rootLanguage && isset($messages[$rootLanguage]) && isset($messages[$rootLanguage]['ts-citegen-'.$id]))
				? $messages[$rootLanguage]['ts-citegen-'.$id]
				: $messages['en']['ts-citegen-'.$id] ) );
		if (count($args)) {
			array_unshift($args, $message);
			return call_user_func_array('sprintf',$args);
		} else {
			return $message;
		}
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
	$notEnglish = $scriptLanguage !== 'en';
	$langAry = array();
	foreach($messages['en'] as $key => $message) {
		// strip "ts-citegen-" prefix from translation keys for templates
		// length of ts-citegen- equals 11
		// also doing magic for not fully translated languages in TranslateWiki
		$key_short = substr($key, 11);
		$langAry[$key_short] = getMessage($key_short);
	}
	return $langAry;
}

/**
 * Prepares available languages to show
 * @return array
 */
function prepareAvailableLanguages()
{
	global $messages;
	$langAry = array();
	foreach ($messages as $lang => $mess) {
		if (isset($mess['ts-citegen-Title'])) {
			$langAry[] = $lang;
		}
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

function handleMetaRedirect($data)
{
	$data = preg_replace("$<!--.*-->$", "", $data);
	$grp = array();
	if( preg_match('#<meta +http-equiv="refresh" +content="(.*)"#siu', $data, $grp) ) {
		if( isset( $grp[1] ) ) {
			$items = explode( ";", $grp[1]);
			foreach( $items as $item ) {
				$keyval = explode( "=", $item, 2 );
				if ( trim( strtolower( $keyval[0] ) ) === "url" ) {
					return trim($keyval[1]);
				}
			}
		}
	}
	return null;
}
