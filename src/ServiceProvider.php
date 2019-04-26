<?php

namespace Peak\DB;

trait ServiceProvider
{

	static function peakDb ($type='mysql', $name='default', array $config=[])
	{
		\Peak\DB\Connector::config($type, $name, @[
			'db' => $config['db'],
			'usr' => $config['usr'],
			'pwd' => $config['pwd'],
			'host' => $config['host'],
			'port' => $config['port'],
			'option' => $config['option'],
		]);

	}



	static function peakDbLaravel ($connection='mysql', $name='default')
	{
		$connection = config('database.connections.'.$connection);
		if ($connection) {
			self::peakDb(
				$connection['driver'],
				$name,
				@[
					'db' => $connection['database'],
					'usr' => $connection['username'],
					'pwd' => $connection['password'],
					'host' => $connection['host'],
					'port' => $connection['port'],
					'option' => $connection['options'],
				]
			);
		}

	}



}
