<?php

namespace Peak\DB;

trait Query
{

	protected static $pdo;

	static function pdo (\PDO $pdo=null)
	{
		if ($pdo) {
			self::$pdo = $pdo;
		} else {
			return self::$pdo;
		}
	}





	static function exec ($sql, array $param=[])
	{
		$sth = self::$pdo->prepare($sql);

		foreach ($param as $i=>$val ) {
			if (is_int($val)) {
				$type = \PDO::PARAM_INT;
			} elseif (is_null($val)) {
				$type = \PDO::PARAM_NULL;
			} else {
				$type = \PDO::PARAM_STR ;
			}
			$sth->bindValue($i+1, $val , $type) ;
		}


		if (!$sth->execute()) {
			return self::debug($sth->errorInfo());
		}

		return $sth;

	}

	use \Peak\Plugin\Debuger\Base;



}
