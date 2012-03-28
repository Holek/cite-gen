<?php
/**
 * Citation template generator.
 *
 * @addtogroup Main
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 * @TODO: php, phpfm, wddx, wddxfm, rawfm, txt, txtfm, dbg, dbgfm
 * * rewrite settings
 * * check cache throughly
 */
define('DATABASE_LINK',1);

// Error handling & debugging
$DEBUG = 0;

// Very important message, use only in emergencies!
$veryImportantMessage = '';

$HOME = getenv("HOME");
// Config - do not fail if file doesn't exist
@include('./LocalSettings.php');

$debug = '';
require_once('./includes/Error.php');
$error = new Error();

// Functions, abstract classes
require_once('./includes/Functions.php');
require_once('./includes/abstractClasses.php');
require_once('./parsers/ISBN/ISBNBaseParser.php');

// I18N files
require_once('./messages/Names.php');
if (!include_once('./messages/allMessages.php'))
{
	echo '<!doctype html>

<h1>Citation generator fatal error</h1>
<p>The citation generator requires <a href="http://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/ToolserverTools/HolekCiteGen/allMessages.php">messages/allMessages.php</a> file. This file can be obtained from MediaWiki\'s SVN by checking out <tt>/mediawiki/trunk/extensions/ToolserverTools/HolekCiteGen</tt> folder:</p>

<p>Assuming you are in Citation generator\'s root folder, use these commands to quickly set it up:</p>

<pre>cd messages
svn checkout http://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/ToolserverTools/HolekCiteGen/ .</pre>

<p>For more information about translations, please refer to <a href="README">the README file</a>.</p>';
	exit();
}
unset($messages['qqq']); // delete message documentation out of possible languages

// URL query array
$query = array();
parse_str($_SERVER['QUERY_STRING'],$query);

// Language
$scriptLanguage = get('scriptlang', (($_COOKIE['languages']['script'] && preg_match( '/^[a-z-]+$/', $_COOKIE['languages']['script']))?$_COOKIE['languages']['script']:substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2)));

$rootLanguage = false;
if ( preg_match( '/^([a-z]+)-[a-z-]+$/', $scriptLanguage, $matches) ) {
	$rootLanguage = $matches[1];
	unset($matches);
}

if (isset($messages[$scriptLanguage]) && !isset($messages[$scriptLanguage]['ts-citegen-Title'])) {
	$scriptLanguage='en'; // if title for generator is not set, don't show that language yet
}

$templateLanguage = get('citelang', (($_COOKIE['languages']['cite'] && preg_match( '/^[a-z-]+$/', $_COOKIE['languages']['cite']))?$_COOKIE['languages']['cite']:$scriptLanguage));

$query['scriptlang'] = $scriptLanguage;
setcookie('languages[script]',$scriptLanguage,time()+60*60*24*30);
setcookie('languages[cite]',$templateLanguage,time()+60*60*24*30);

//////////////
// Settings //
//////////////
$availableSettings = array(
	'append-author-link',
	'append-newlines',
	'add-references',
	'add-list'
);
$settings = get('s', array());

$newSettings = array();
foreach ($availableSettings as $setting)
{
	if (($settings[$setting] != 0 && $settings[$setting] != false && strtolower($settings[$setting]) != 'false') || strtolower($settings[$setting]) == 'on')
	{
		$newSettings[$setting] = true;
	}
	else
	{
		$newSettings[$setting] = false;
	}
}
$settings = $newSettings;
unset($newSettings);
$query['s'] = $settings;
foreach ($query['s'] as $setting => $value)
{
	if (!$value)
	{
		unset($query['s'][$setting]);
	}
}

///////////////////
// Database link //
///////////////////
include('database.php');
if ($settings['append-author-link'] == true)
{
	$mysqli = dbconnect($templateLanguage.'wiki-p');
}

////////////////////
// Parsers' setup //
////////////////////

$parsersDirectory = opendir('./parsers');
$availableParsers = array();
$defaultParsers = array();
$selectedParsers = array();
while(!is_bool($parserDirectory = readdir($parsersDirectory)))
{
	if (is_dir('./parsers/'.$parserDirectory) && !in_array($parserDirectory, array('.','..','.svn')))
	{
		include ('./parsers/'.$parserDirectory.'/__parser.php');
		$debug .= "Including /parsers/$parserDirectory/__parser.php ...\n";
		$parser = call_user_func($parserDirectory.'_parser::listParsers');
		$availableParsers[$parserDirectory] = $parser[0];
		$defaultParsers[$parserDirectory] = $parser[1];
		$selectedParsers[$parserDirectory] = createArray(get($parserDirectory));
		if (count($selectedParsers[$parserDirectory]) == 0 || (count($selectedParsers[$parserDirectory]) == 1 && $selectedParsers[$parserDirectory][0] == ''))
		{
			$selectedParsers[$parserDirectory] = $defaultParsers[$parserDirectory];
		}
		removeDeadEntries($selectedParsers[$parserDirectory], 'parsers/'.$parserDirectory);
	}
}
closedir($parsersDirectory);

// Input check
$input = get('input');
$inputArray = createArray($input);
$newInputArray = array();
$inputMessages = array();
$usedParsers = array();

for ($i=0; $i<count($inputArray); $i++)
{
	$wi = true; // wi = wrong input
	foreach(array_keys($availableParsers) as $parser)
	{
		if (call_user_func($parser.'_parser::checkInput',$inputArray[$i]))
		{
			$newInputArray[] = array('parser'=>$parser,'data'=>call_user_func($parser.'_parser::prepareInput',$inputArray[$i]));
			$usedParsers[$parser] = true;
			$wi = false;
		}
	}
	if ($wi)
	{
		$inputMessages[] = getMessage('Wrong-input',$inputArray[$i]);
	}
}

//////////////////////
// Creating selects //
//////////////////////

$selects = array();

// Including parsers
$usedParsers = array_keys($usedParsers);
$parsers = array();
$outputTemplates = array();
$outputTemplatesLang = array();

foreach (array_keys($availableParsers) as $parserDirectory)
{
	// Listing all available output languages
	include_once ('./parsers/'.$parserDirectory.'/output/__template.php');
	$debug .= "Including /parsers/$parserDirectory/output/__template.php ...\n";
	foreach (call_user_func($parserDirectory.'_output::getParserList') as $lang)
	{
		$outputTemplatesLang[$lang] = true;
	}
}
$outputTemplatesLang = array_keys($outputTemplatesLang);
sort($outputTemplatesLang);
$selects['output'] = createSelect('citelang', $outputTemplatesLang, array($templateLanguage)); // Creating citelang select

// Including parsers - final stage
foreach ($usedParsers as $parserDirectory)
{
	$parsers[$parserDirectory] = createArray(get($parserDirectory,$selectedParsers[$parserDirectory]));
	foreach($parsers[$parserDirectory] as $parser)
	{
		include_once('./parsers/'.$parserDirectory.'/'.$parser.'.php');
		$debug .= "Including /parsers/$parserDirectory/$parser.php ...\n";
	}
	
	// Choosing output templates
	$outputTemplates[$parserDirectory] = call_user_func_array($parserDirectory.'_output::getOutputParserClass',array($templateLanguage));
	include_once('./parsers/'.$parserDirectory.'/output/'.$outputTemplates[$parserDirectory].'.php');
	$debug .= "Including /parsers/$parserDirectory/output/".$outputTemplates[$parserDirectory].".php ...\n";

}

// Creating parser selects
foreach ($availableParsers as $parser => $array)
{
	if (count($array) > 1)
	{
		$selects['parsers'][$parser] = createSelect($parser, $availableParsers[$parser], $selectedParsers[$parser], getMessage($scriptLanguage), true, 6, true);
	}
}

////////////////////////
// Parsing input data //
////////////////////////
$bookshelf = array();
$sources = array();

foreach($newInputArray as $inputEntry)
{
	foreach($selectedParsers[$inputEntry['parser']] as $parserClass)
	{
		$parserRaw = new $parserClass($inputEntry['data']);
		if ($parserRaw->getTitle())
		{
			$debug .= "Using $parserClass for \"".$inputEntry['data']."\": Got title \"".$parserRaw->getTitle()."\"\n";
			$templateRaw = $parserRaw->getOutput();

			$template = $outputTemplates[$inputEntry['parser']];
			$parser = new $template($templateRaw,$settings);
			$bookshelf[] = $parser->__toString();
			$sources[] = array(0 => $templateRaw['__sourceurl'], 1 => $templateRaw['title'], 2 => $inputEntry);

			unset($parserRaw,$templateRaw,$template);
			break;
		} else {
			$debug .= "$parserClass does not seem to have entry for \"".$inputEntry['data']."\"\n";
		}
	}
}

////////////////////////////
// Savant template system //
////////////////////////////

require_once( './includes/Savant3.php' );
$gui = './gui';
$savant = new Savant3(array('template_path' => $gui));


// List available templates and check whether the one given exists
$template = get('template', (($_COOKIE['template'] && preg_match( '/^[a-z-]+$/', $_COOKIE['template'])?$_COOKIE['template']:'monobook')));

$dh = opendir($gui);
$availableSkins = array();
$isTemplate = false;
$mimetype = null;
while(!is_bool($file = readdir($dh)))
{
	if(substr($file, -4) == ".ini")
	{
		$ini = parse_ini_file($gui.'/'.$file);

		if (substr($file,0,-4) == $template)
		{
			if (!$isTemplate)
			{
				$isTemplate = true;
			}
			if ($ini['category'] == 'skins' && !$ini['dontstore'])
			{
				setcookie('template',$template,time()+60*60*24*30);
			}
			if ($ini['mimetype'])
			{
				$mimetype = $ini['mimetype'];
			}
		}
		$availableSkins['Skin-'.$ini['category']][] = array('value'=>substr($file,0,-4),'label'=>$ini['name']);
		if ($ini['gatherinfo']) {
			define('GATHERINFO', true);
		}
		unset($ini);
	}
}
if (!$isTemplate)
{
	$template = 'monobook';
	setcookie('template',$template,time()+60*60*24*30);
}
closedir($dh);
$selects['skins'] = createSelect('template', $availableSkins, array($template), 'Skin-skins', true, 1, false);

// Savant display
$savant->availableParsers = array_keys($availableParsers);
$savant->availableSettings = $availableSettings;
$savant->availableLanguages = prepareAvailableLanguages();
$savant->settings = $settings;

$savant->bookshelf = $bookshelf;
$savant->inputMessages = $inputMessages;
$savant->sources = $sources;

$savant->errors = $error->output_errors();

$savant->input = $inputArray;

$savant->lang = prepareLanguageArray();
$savant->languages = $wgLanguageNames;
$savant->scriptLanguage = $scriptLanguage;
$savant->templateLanguage = $templateLanguage;

$savant->query = $query;

$savant->selects = $selects;

$savant->veryImportantMessage = $veryImportantMessage;
$savant->debug = ( ($DEBUG) ? $debug : "" );
if (isset($mimetype)) {
	header('Content-Type: '.$mimetype.';charset=UTF-8');
}
$savant->direction = ((in_array($scriptLanguage,$rtlLanguages))?'rtl':'ltr');

$savant->display($template.'.tpl.php');

