<?php

namespace Peak\DB;

class Connector
{

	use \Peak\Plugin\Debuger\Base;

	protected static $connection = [
		'mysql' => [
			'default' => [
				'db' => null,
				'usr' => null,
				'pwd' => null,
				'host' => null,
				'port' => null,
				'option' => [],
			]
		],

		'pgsql' => [
			'default' => [
				'db' => null,
				'usr' => null,
				'pwd' => null,
				'host' => null,
				'port' => null,
				'option' => [],
			]
		],

		'sqlite' => [
			'default' => [
				'db' => null,
				'option' => [],
			]
		],
	];



	/**
	 * 设置获取连接
	 * */
	static function config ($type='mysql', $name='default', array $config=[])
	{
		if (@$conn=&self::$connection[$type]) {
			if ($config) {
				$conn[$name] = $config;
				return true;
			} else {
				return @$conn[$name];
			}
		}

		return self::debug('暂不支持"'.$type.'"型数据库。');
	}



	/**
	 * 初始化pdo
	 * */
	static function pdo ($type='mysql', $name='default')
	{

		if ($conn=self::config($type, $name)) {
			if (@$conn['pdo'] && $conn['pdo'] instanceof \PDO) {
				return $conn['pdo'];
			}
			switch ($type) {
				case 'pgsql' :
					$conn['pdo'] = @PDO::postgress(
						$conn['db'],
						$conn['usr'],
						$conn['pwd'],
						$conn['host'],
						$conn['port'],
						$conn['option'] ?: [
							\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
						]
					);
					break;

				case 'mysql' :
					$conn['pdo'] = @PDO::mysql(
						$conn['db'],
						$conn['usr'],
						$conn['pwd'],
						$conn['host'],
						$conn['port'],
						$conn['option'] ?: [
							\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
						]
					);
					break;

				case 'sqlite' :
					$conn['pdo'] = PDO::sqlite($conn['db']);
					break;
			}

			if ($conn['pdo'] instanceof \PDO) {
				// 重置Config配置为PDO对象
				self::config($type, $name, $conn);
				return $conn['pdo'];
			}

			return (bool)self::debug($conn['pdo']->getMessage());

		}
		return self::debug() ? false : (bool)self::debug('数据库连接"'.$type.'.'.$name.'"未配置。');
	}


}
