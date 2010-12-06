<?php
/*  Author: Ben de Groot -- http://www.stijlstek.nl/
    Copyright (C) 2004 Ben de Groot

    This part of the code is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2 of the License, or (at your option) any later version.

    This file is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this file; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA */
$charset = 'UTF-8';
$mime = 'text/html';  // fall-back mime-type, IE needs text/html
$quirksmode = '<!-- Quirksmode dla IE -->
'; 
function htmlize($buffer) {
    return (preg_replace('!\s*/>!', '>', $buffer));
}
if(stristr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')) {
    if(preg_match('/application\/xhtml\+xml;q=0(\.[1-9]+)/i',$_SERVER['HTTP_ACCEPT'],$matches)) {
        $xhtml_q = $matches[1];
        if(preg_match('/text\/html;q=0(\.[1-9]+)/i',$_SERVER['HTTP_ACCEPT'],$matches)) {
            $html_q = $matches[1];
            if($xhtml_q >= $html_q) {
            $mime = 'application/xhtml+xml';
            }
        }
    } else {
    $mime = 'application/xhtml+xml';
    }
}
if(stristr($_SERVER['HTTP_USER_AGENT'],'W3C_Validator')     ||
   stristr($_SERVER['HTTP_USER_AGENT'],'W3C_CSS_Validator') ||
   stristr($_SERVER['HTTP_USER_AGENT'],'WDG_Validator')) {
    $mime = 'application/xhtml+xml';
}
if($mime == 'application/xhtml+xml') {
    $docstart = '<?xml version="1.0" encoding="'.$charset.'" ?'.'>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$scriptLanguage.'">
';
} else {
    ob_start('htmlize');
    $docstart = $quirksmode.'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="'.$scriptLanguage.'" class="lang-'.$scriptLanguage.'">
';
}
header('Content-Type: '.$mime.';charset='.$charset);
header('Vary: Accept');
?>
