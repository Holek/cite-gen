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



/** English (English)
 * @author Holek

 * @author Wpedzich
 */
$messages['en'] = array(
	'ts-citegen-Title' => 'Citation templates generator',
	
	// Button
	'ts-citegen-Send' => 'Send',
	
	// Input
	'ts-citegen-Input-title' => 'Input',
	'ts-citegen-Input-text' => 'This is a citation template generator. Using it, you can quickly fill in the quotation templates in various language editions of Wikipedia. Please fill in the data (%s) in the fields below, and the script will try to complete the templates. Remember, it does not matter in which fields you will put the input data. Script will automatically match the correct template to given input.',
	'ts-citegen-Option-append-author-link' => 'Append the author wikilinks into the template',
	'ts-citegen-Option-append-newlines' => 'Append new lines after each parameter',
	'ts-citegen-Option-add-references' => 'Add <ref> tags around citing templates',
	'ts-citegen-Option-add-list' => 'Create a wikilist of citing templates',
	
	// Output
	'ts-citegen-Output-title' => 'Result',
	'ts-citegen-Output-select-disclaimer' => 'Remember that choosing a template language does not guarantee that specific template is available in your language. This field lists available languages of every supported template. Ie. it may display French, because only {{Cite book}} is supported.',
	'ts-citegen-Wrong-input' => '%s: not identified as a correct input.',
	
	// Settings
	'ts-citegen-Parsers' => 'Parsers',
	'ts-citegen-Skins' => 'Output',
	'ts-citegen-Skin-skins' => 'Skins',
	'ts-citegen-Skin-outputformat' => 'Output format',
	
	'ts-citegen-Template-lang' => 'Template language',
	
	// Sources
	'ts-citegen-Sources-title' => 'Sources',
	'ts-citegen-Sourcer-text' => 'Below the list of used sources is available.',
	
	// Sidebar-related messages
	'ts-citegen-Sidebar-title' => 'Citations generator',
	'ts-citegen-Sidebar-text' => 'This version of the generator uses the setup found on the full version of this generator. Ie. if you want to change the language version of the template, make the change using the generator itself. The settings are saved with the use of cookies.',
	
	'ts-citegen-Sidebar-add-Firefox' => 'Add to the sidebar',
	'ts-citegen-Sidebar-add-Opera' => 'Add to the Hotlist',
	'ts-citegen-Sidebar-add-IE-Mac' => 'Add to the Page Holder',
	'ts-citegen-Sidebar-add-IE-Mac-details' => 'Once the page has loaded, open your Page Holder, click \'Add\' then use the Page Holder Favorites button to store it as a Page Holder Favourite.',
	
	// Portlet messages
	'ts-citegen-Tools' => 'Tools',
	'ts-citegen-Other-languages' => 'Other languages',
	
	'ts-citegen-Save-it' => 'Current query',
	
	// Error messages
	'ts-citegen-Errors-title' => 'Errors',
	'ts-citegen-Unavailable-API' => 'Error: Wikimedia API is unavailable',
	'ts-citegen-Unavailable-SQL' => 'Error: Toolserver database is unavailable. MySQL produced: %s',
	'ts-citegen-base-disabled' => 'Error: %s database is unavailable.'
	

);


/** Message documentation (Message documentation)
 * @author Holek
 */
$messages['qqq'] = array(
	'ts-citegen-Title' => 'Generator title',
	
	// Button
	'ts-citegen-Send' => 'Send button',
	
	// Input
	'ts-citegen-Input-title' => 'Input secton',
	'ts-citegen-Input-text' => 'Input section description',
	'ts-citegen-Option-append-author-link' => 'Appends the author wikilinks into the template',
	'ts-citegen-Option-append-newlines' => 'Appends new lines after each parameter',
	'ts-citegen-Option-add-references' => 'Adds <ref> tags around citing templates',
	'ts-citegen-Option-add-list' => 'Creates a wikilist of citing templates',
	
	// Output
	'ts-citegen-Output-title' => 'Output section',
	'ts-citegen-Output-select-disclaimer' => 'Disclaimer about output templates',
	'ts-citegen-Wrong-input' => '"%s" is an unidentified input.',
	
	// Options
	'ts-citegen-Parsers' => 'Parsers',
	'ts-citegen-Skins' => 'Output',
	'ts-citegen-Skin-skins' => 'Skins',
	'ts-citegen-Skin-outputformat' => 'Output format',
	
	'ts-citegen-Template-lang' => 'Template language',
	
	// Sources
	'ts-citegen-Sources-title' => 'Sources section title',
	'ts-citegen-Sourcer-text' => 'An explanation test for sources section',
	
	// Sidebar-related messages
	'ts-citegen-Sidebar-title' => 'Shortened title used for mini-generator',
	'ts-citegen-Sidebar-text' => 'An explanation text used in the sidebar.',
	
	'ts-citegen-Sidebar-add-Firefox' => 'Caption of generator addition to Firefox\'s sidebar',
	'ts-citegen-Sidebar-add-Opera' => 'Caption of generator addition to Opera\'s Hotlist',
	'ts-citegen-Sidebar-add-IE-Mac' => 'Caption of generator addition to Mac IE\'s Page Holder',
	'ts-citegen-Sidebar-add-IE-Mac-details' => 'Details on generator addition to Mac IE\'s Page Holder',

	// Portlet messages
	'ts-citegen-Tools' => 'Tools portlet section',
	'ts-citegen-Other-languages' => 'Other languages section',

	'ts-citegen-Save-it' => 'Link to itself/current query',
	
	// Error messages
	'ts-citegen-Errors-title' => 'Errors section title',
	'ts-citegen-Unavailable-API' => 'Error message: Wikimedia API is unavailable',
	'ts-citegen-Unavailable-SQL' => 'Error message: Toolserver database is unavailable. %s is an error message',
	'ts-citegen-base-disabled' => 'Error message: A book database is unavailable. <tt>%s</tt> is the name of the database.'
	

);


/** Polish (Polski)
 * @author Holek
 */
$messages['pl'] = array(
	'ts-citegen-Title' => 'Generator szablonów cytowania',
	
	// Button
	'ts-citegen-Send' => 'Wyślij',
	
	// Input
	'ts-citegen-Input-title' => 'Dane wejściowe',
	'ts-citegen-Input-text' => 'To jest generator szablonów cytowania. Za pomocą tego narzędzia możesz szybko uzupełnić różne szablony cytowania dostępne w różnych edycjach językowych Wikipedii. Wpisz w polach poniżej odpowiednie dane (%s), a skrypt postara się wypełnić odpowiednie szablony według danych wejściowych. Pamiętaj, że nie ma znaczenia, w których polach, co wpiszesz. Skrypt automatycznie dopasowuje szablony do wprowadzonych danych.',
	'ts-citegen-Option-append-author-link' => 'Dołączaj linki do artykułów o odpowiednich autorach',
	'ts-citegen-Option-append-newlines' => 'Umieszczaj nowe linie po każdym parametrze',
	'ts-citegen-Option-add-references' => 'Umieść szablony pomiędzy tagami <ref></ref>',
	'ts-citegen-Option-add-list' => 'Dodaj wikilistę do szablonów cytowania',
	
	// Output
	'ts-citegen-Output-title' => 'Rezultat',
	'ts-citegen-Output-select-disclaimer' => 'Pamiętaj: wybierając konkretny język skrypt nie gwarantuje, że wszystkie szablony są gotowe do użytku w danym języku. To pole wyświetla listę języków z każdego obsługiwanego szablonu. Na przykład może być w nim dostępny język francuski, ponieważ skrypt obsługuje jedynie francuski odpowiednik {{Cytuj książkę}}.',
	'ts-citegen-Wrong-input' => '%s: nie zidentyfikowano.',

	// Options
	'ts-citegen-Parsers' => 'Bazy',
	'ts-citegen-Skins' => 'Forma prezentacji',
	'ts-citegen-Skin-skins' => 'Skórki',
	'ts-citegen-Skin-outputformat' => 'Dla botów',
	
	'ts-citegen-Template-lang' => 'Język szablonu',
	
	// Sources
	'ts-citegen-Sources-title' => 'Źródła',
	'ts-citegen-Sourcer-text' => 'Poniżej podane są strony, z których korzystano przy pobieraniu informacji o książkach. Każdy z linków przenosi ',

	// Sidebar-related messages
	'ts-citegen-Sidebar-title' => 'Generator cytowań',
	'ts-citegen-Sidebar-text' => 'Ta wersja generatora wykorzystuje ustawienia stosowane przez pełną wersję generatora. Jeżeli chcesz np. zmienić język szablonu, zmień go tamże. Ustawienia zapisywane są za pomoca ciasteczek.',
	
	'ts-citegen-Sidebar-add-Firefox' => 'Dodaj do panelu bocznego',
	'ts-citegen-Sidebar-add-Opera' => 'Dodaj do panelu Opery',
	'ts-citegen-Sidebar-add-IE-Mac' => 'Dodaj do Page Holdera',
	'ts-citegen-Sidebar-add-IE-Mac-details' => 'Gdy strona zostanie załadowana, otwórz Page Holder, naciśnij "Dodaj" i użyj przycisku Ulubionych Page Holdera, aby zapisać generator w panelu.',
	
	// Portlet messages
	'ts-citegen-Tools' => 'Narzędzia',
	'ts-citegen-Other-languages' => 'W innych językach',
	
	'ts-citegen-Save-it' => 'Samowywołanie (zapisz tę stronę)',
		
	// Error messages
	'ts-citegen-Errors-title' => 'Błędy',
	'ts-citegen-Unavailable-API' => 'Błąd: API Wikipedii jest niedostępne',
	'ts-citegen-Unavailable-SQL' => 'Błąd: Dostęp do bazy danych SQL niemożliwy',
	'ts-citegen-base-disabled' => 'Błąd: Baza %s jest niedostępna'
	

);

?>
