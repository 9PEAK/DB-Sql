<?php

namespace Peak\DB;

trait Connector
{


	private static $config = [
		'db' => null,
		'usr' => null,
		'pwd' => null,
		'host' => null,
		'port' => null,
		'option' => [],
	];

	static function configDb ($name, $usr=null, $pwd=null)
	{
		self::$config['db'] = $name;
		self::$config['usr'] = $usr;
		self::$config['pwd'] = $pwd;
	}


	static function configHost ($host='localhost', $port=null)
	{
		self::$config['host'] = $host;
		self::$config['port'] = $port;
	}

	static function configOption (array $option=[
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
	])
	{
		self::$config['option'] = $option;
	}


	static function init ($type='mysql')
	{
		switch ($type ) {
			case 'pgsql' :
				return self::postgress();

			case 'mysql' :
				return self::mysql();

			case 'sqlite' :
				return self::sqlite();
		}

	}





	protected static function postgress ()
	{
		$config =& self::$config;
		$config['port'] = $config['port'] ? ';port='.$config['port'] : '' ;
		return new \PDO('pgsql:host='.$config['host'].';dbname='.$config['db'].$config['port'] , $config['usr'] , $config['pwd'], $config['option']);
	}


	protected static function mysql ()
	{
		$config =& self::$config;
		$config['port'] = $config['port'] ? ';port='.$config['port'] : '' ;
		return new \PDO('mysql:host='.$config['host'].';dbname='.$config['db'].$config['port'] , $config['usr'] , $config['pwd'], $config['option']);
	}


	protected static function sqlite ($version=3)
	{
		if ($version) {
			try{
				return new \PDO('sqlite'.$version.':'.self::$config['db']);
			} catch (\PDOException $e) {
				return self::{__FUNCTION__}(self::$config['db'], --$version);
			}
		}

		// 无法连接数据库

	}


}
