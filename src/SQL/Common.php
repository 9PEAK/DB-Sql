<?php

namespace Peak\Plugin\DB\SQL;

use Peak\Plugin\DB\Core;

trait Common
{


	/**
	 * 构造IN语句
	 * @param $key string 字段名
	 * @param $val array 字段值
	 * @param $bind bool 是否采用绑定的方式
	 * */
	static function in ($key, array $val, $bind=true) {
		$sql = $key.' in (';
		if ($bind) {
			$n = Query::bind($val);
			$val = array_fill(0, $n, '?');
		}
		$sql.= join(',', $val);
		return $sql;
	}



	/**
	 * 构造NOT IN语句
	 * @param $key string 字段名
	 * @param $val array 字段值
	 * @param $bind bool 是否采用绑定的方式
	 * */
	static function notIn ($key, array $val, $bind=true) {
		return self::in($key.' not', $val, $bind);
	}


}