<?php
/**
 * Internationalisation file for Cite book template generator.
 *
 * @addtogroup Languages
 * @copyright © 2009 Michał Połtyn
 * @source http://translatewiki.net/w/i.php?title=Special%3ATranslate&task=export-to-file&group=ext-citebook-gen
 * @license GNU General Public Licence 2.0 or later
 */

$rtlLanguages = array('ar','arc','dv','fa','he','kk','ks','mzn','ps','sd','ug','ur','ydd','yi');

$messages = array();


/** Message documentation (Message documentation)
 * @author Holek
 */
$messages['qqq'] = array(
	'Title' => 'Generator title',
	
	// Button
	'Send' => 'Send button',
	
	// Input
	'Input-title' => 'Input secton',
	'Input-text' => 'Input section description',
	'Option-append-author-link' => 'Appends the author wikilinks into the template',
	'Option-append-newlines' => 'Appends new lines after each parameter',
	'Option-add-references' => 'Adds <ref> tags around citing templates',
	'Option-add-list' => 'Creates a wikilist of citing templates',
	
	// Output
	'Output-title' => 'Output section',
	'Output-select-disclaimer' => 'Disclaimer about output templates',
	'Wrong-input' => '"%s" is an unidentified input.',
	
	// Options
	'Parsers' => 'Parsers',
	'Skins' => 'Output',
	'Skin-skins' => 'Skins',
	'Skin-outputformat' => 'Output format',
	
	'Template-lang' => 'Template language',
	
	// Sources
	'Sources-title' => 'Sources section title',
	'Sourcer-text' => 'An explanation test for sources section',
	
	// Sidebar-related messages
	'Sidebar-title' => 'Shortened title used for mini-generator',
	'Sidebar-text' => 'An explanation text used in the sidebar.',
	
	'Sidebar-add-Firefox' => 'Caption of generator addition to Firefox\'s sidebar',
	'Sidebar-add-Opera' => 'Caption of generator addition to Opera\'s Hotlist',
	'Sidebar-add-IE-Mac' => 'Caption of generator addition to Mac IE\'s Page Holder',
	'Sidebar-add-IE-Mac-details' => 'Details on generator addition to Mac IE\'s Page Holder',

	// Portlet messages
	'Tools' => 'Tools portlet section',
	'Other-languages' => 'Other languages section',

	'Save-it' => 'Link to itself/current query',
	
	// Error messages
	'Errors-title' => 'Errors section title',
	'Unavailable-API' => 'Error message: Wikimedia API is unavailable',
	'Unavailable-SQL' => 'Error message: Toolserver database is unavailable. %s is an error message',
	'base-disabled' => 'Error message: A book database is unavailable. <tt>%s</tt> is the name of the database.'
	

);


/** English (English)
 * @author Holek
 * @author Wpedzich
 */
$messages['en'] = array(
	'Title' => 'Citation templates generator',
	
	// Button
	'Send' => 'Send',
	
	// Input
	'Input-title' => 'Input',
	'Input-text' => 'This is a citation template generator. Using it, you can quickly fill in the quotation templates in various language editions of Wikipedia. Please fill in the data (%s) in the fields below, and the script will try to complete the templates. Remember, it does not matter in which fields you will put the input data. Script will automatically match the correct template to given input.',
	'Option-append-author-link' => 'Append the author wikilinks into the template',
	'Option-append-newlines' => 'Append new lines after each parameter',
	'Option-add-references' => 'Add <ref> tags around citing templates',
	'Option-add-list' => 'Create a wikilist of citing templates',
	
	// Output
	'Output-title' => 'Result',
	'Output-select-disclaimer' => 'Remember that choosing a template language does not guarantee that specific template is available in your language. This field lists available languages of every supported template. Ie. it may display French, because only {{Cite book}} is supported.',
	'Wrong-input' => '%s: not identified as a correct input.',
	
	// Settings
	'Parsers' => 'Parsers',
	'Skins' => 'Output',
	'Skin-skins' => 'Skins',
	'Skin-outputformat' => 'Output format',
	
	'Template-lang' => 'Template language',
	
	// Sources
	'Sources-title' => 'Sources',
	'Sourcer-text' => 'Below the list of used sources is available.',
	
	// Sidebar-related messages
	'Sidebar-title' => 'Citations generator',
	'Sidebar-text' => 'This version of the generator uses the setup found on the full version of this generator. Ie. if you want to change the language version of the template, make the change using the generator itself. The settings are saved with the use of cookies.',
	
	'Sidebar-add-Firefox' => 'Add to the sidebar',
	'Sidebar-add-Opera' => 'Add to the Hotlist',
	'Sidebar-add-IE-Mac' => 'Add to the Page Holder',
	'Sidebar-add-IE-Mac-details' => 'Once the page has loaded, open your Page Holder, click \'Add\' then use the Page Holder Favorites button to store it as a Page Holder Favourite.',
	
	// Portlet messages
	'Tools' => 'Tools',
	'Other-languages' => 'Other languages',
	
	'Save-it' => 'Current query',
	
	// Error messages
	'Errors-title' => 'Errors',
	'Unavailable-API' => 'Error: Wikimedia API is unavailable',
	'Unavailable-SQL' => 'Error: Toolserver database is unavailable. MySQL produced: %s',
	'base-disabled' => 'Error: %s database is unavailable.'
	

);



/** Polish (Polski)
 * @author Holek
 */
$messages['pl'] = array(
	'Title' => 'Generator szablonów cytowania',
	
	// Button
	'Send' => 'Wyślij',
	
	// Input
	'Input-title' => 'Dane wejściowe',
	'Input-text' => 'To jest generator szablonów cytowania. Za pomocą tego narzędzia możesz szybko uzupełnić różne szablony cytowania dostępne w różnych edycjach językowych Wikipedii. Wpisz w polach poniżej odpowiednie dane (%s), a skrypt postara się wypełnić odpowiednie szablony według danych wejściowych. Pamiętaj, że nie ma znaczenia, w których polach, co wpiszesz. Skrypt automatycznie dopasowuje szablony do wprowadzonych danych.',
	'Option-append-author-link' => 'Dołączaj linki do artykułów o odpowiednich autorach',
	'Option-append-newlines' => 'Umieszczaj nowe linie po każdym parametrze',
	'Option-add-references' => 'Umieść szablony pomiędzy tagami <ref></ref>',
	'Option-add-list' => 'Dodaj wikilistę do szablonów cytowania',
	
	// Output
	'Output-title' => 'Rezultat',
	'Output-select-disclaimer' => 'Pamiętaj: wybierając konkretny język skrypt nie gwarantuje, że wszystkie szablony są gotowe do użytku w danym języku. To pole wyświetla listę języków z każdego obsługiwanego szablonu. Na przykład może być w nim dostępny język francuski, ponieważ skrypt obsługuje jedynie francuski odpowiednik {{Cytuj książkę}}.',
	'Wrong-input' => '%s: nie zidentyfikowano.',

	// Options
	'Parsers' => 'Bazy',
	'Skins' => 'Forma prezentacji',
	'Skin-skins' => 'Skórki',
	'Skin-outputformat' => 'Dla botów',
	
	'Template-lang' => 'Język szablonu',
	
	// Sources
	'Sources-title' => 'Źródła',
	'Sourcer-text' => 'Poniżej podane są strony, z których korzystano przy pobieraniu informacji o książkach. Każdy z linków przenosi ',

	// Sidebar-related messages
	'Sidebar-title' => 'Generator cytowań',
	'Sidebar-text' => 'Ta wersja generatora wykorzystuje ustawienia stosowane przez pełną wersję generatora. Jeżeli chcesz np. zmienić język szablonu, zmień go tamże. Ustawienia zapisywane są za pomoca ciasteczek.',
	
	'Sidebar-add-Firefox' => 'Dodaj do panelu bocznego',
	'Sidebar-add-Opera' => 'Dodaj do panelu Opery',
	'Sidebar-add-IE-Mac' => 'Dodaj do Page Holdera',
	'Sidebar-add-IE-Mac-details' => 'Gdy strona zostanie załadowana, otwórz Page Holder, naciśnij "Dodaj" i użyj przycisku Ulubionych Page Holdera, aby zapisać generator w panelu.',
	
	// Portlet messages
	'Tools' => 'Narzędzia',
	'Other-languages' => 'W innych językach',
	
	'Save-it' => 'Samowywołanie (zapisz tę stronę)',
		
	// Error messages
	'Errors-title' => 'Błędy',
	'Unavailable-API' => 'Błąd: API Wikipedii jest niedostępne',
	'Unavailable-SQL' => 'Błąd: Dostęp do bazy danych SQL niemożliwy',
	'base-disabled' => 'Błąd: Baza %s jest niedostępna'
	

);

?>