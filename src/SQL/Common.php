<?php

namespace Peak\DB\SQL;

use Peak\DB\Core;

trait Common
{


	static $bind = [];


	/**
	 * 设置绑定查询参数
	 * @param $dat string 加入查询的变量
	 * @param $reset bool 是否重置绑定的值
	 * @return int 加入变量的总数
	 * */
	static function bind (array $param=[], $reset=false)
	{

		$reset && self::$bind = [];

		$n = count(self::$bind);
		foreach ($param as $k=>$v) {
			is_array($v) ? self::{__FUNCTION__}($v) : self::$bind[]=$v;
		}

		$n = count(self::$bind)-$n;
		return $n>0 ? $n : 0;
	}




	/**
	 * 构造IN语句
	 * @param $key string 字段名
	 * @param $val array 字段值
	 * @param $bind bool 是否采用绑定的方式
	 * */
	static function in ($key, array $val, $bind=true) {
		$sql = $key.' in (';
		if ($bind) {
			$n = Common::bind($val);
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