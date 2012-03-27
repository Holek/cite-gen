<?php
/**
 * {{Cite book}} generator.
 *
 * @addtogroup Includes
 * @author Marcin Cieślak
 * @copyright © 2012 Marcin Cieślak
 * @license GNU General Public Licence 2.0 or later
 */

abstract class ISBNBaseParser extends Parser {
        protected $lastNames = array();
        protected $firstNames = array();
        protected $date;
        protected $publisher;
        protected $place;
        protected $source;
        protected $ISBN;
}
