<?php
if (!defined('DATABASE_LINK'))
{
	die('<b>{{Cite book}} generator:</b><br/><br/>Possible breach, breaking...');
}

/**
 * Connects to the desired toolserver database
 * @author River Tarnell
 * @author Michał "Hołek" Połtyn
 * @param string $database
 */
function dbconnect($database)
{
	global $error;
	// fix redundant error-reporting
	$errorlevel = ini_set('error_reporting','0');
 
	// connect
	$mycnf = parse_ini_file('/home/'.get_current_user().'/.my.cnf');
	$username = $mycnf['user'];
	$password = $mycnf['password'];
	unset($mycnf);
	$mysqli = @new mysqli( str_replace('_','-',$database) . '.db.toolserver.org', $username, $password, str_replace('-','_',$database) );
	if ($mysqli->connect_error) {
		$error->report('Unavailable-SQL',$mysqli->connect_error);
	}
	unset($username, $password);
 
	// restore error-reporting
	ini_set('error_reporting',$errorlevel);
	
	return $mysqli;
}

