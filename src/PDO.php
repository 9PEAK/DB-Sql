<?php

namespace Peak\DB;

class PDO
{


	static function postgress ($db, $usr, $pwd, $host='localhost', $port=null, $option=[])
	{
		try {
			$db = 'pgsql:host='.$host.';dbname='.$db.(isset($port) ? ';port='.$port : '');
			return new \PDO($db, $usr, $pwd, $option);
		} catch (\PDOException $e) {
			return $e;
		}

	}


	static function mysql ($db, $usr, $pwd, $host='localhost', $port=null, $option=[])
	{
		try {
			$db = 'mysql:host='.$host.';dbname='.$db.(isset($port) ? ';port='.$port : '');
			return new \PDO($db, $usr , $pwd, $option);
		} catch (\PDOException $e) {
			return $e;
		}
	}


	static function sqlite ($db, $version=3)
	{
		while ($version) {
			try {
				return new \PDO('sqlite' . $version . ':' . $db);
			} catch (\PDOException $e) {
				$version--;
			}
		}

		return $e;
	}


}
