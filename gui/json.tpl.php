<?php
/**
 * Citation templates' generator.
 *
 * @addtogroup Skins
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */
$final_array = array(
	'query' => $this->input,
	'response' => array(),
	'paramlist' => $this->settings
	);
$i = 0;
foreach ($this->bookshelf as $book)
{
	$final_array['response'][] = array(
			'source' => $this->sources[$i][0],
			'title' => $this->sources[$i][1],
			'book' => $book
			);
	$i++;
}

if (isset($_GET['uid']) && preg_match('/^jsonp[0-9]*?$/', $_GET['uid'])) {
	echo $_GET['uid'] . '(' . json_encode($final_array) . ')';
} else {
	echo json_encode($final_array);
}
?>
