<?php

namespace Peak\DB\SQL;

use Peak\DB\Core;

trait Where
{


	/**
	 * 原生WHERE条件(无查询编译)
	 * @aram $key string 查询字段，支持比较符尾缀，如"datetime>"，表示筛选条件为大于指定时间
	 * @param $val mixed
	 * @param $compare string 比较运算符，支持=、not like、like等。
	 * */
	static function raw ($key, $val, $compare='=')
	{
		if ($compare=='=') {
			$compare = preg_match('/[>=<!]+$/', $key) ? '' : '=';
		}
		return $key.' '.$compare.' '.$val;
	}



	/**
	 * NULL字段检索
	 * @param $key string 字段名
	 * @param $null bool null或者非null
	 * */
	static function null ($key, $null=true)
	{
		$null = $null ? '' : 'not';
		return $key. ' is '.$null.' NULL ';
	}



	/**
	 * WHERE条件单字段查询
	 * @param $key string 查询字段，支持比较符尾缀，如"datetime>"，表示筛选条件为大于指定时间
	 * @param $val mixed
	 * @return string
	 */
	static function equal ($key, $val)
	{
		if (is_null($val)) {
			return self::null($key);
		}

		Core::setParam([
			$key => $val
		]);
		return self::raw($key, '?');
	}



	/**
	 * LIKE语句
	 * @param $key string
	 * @param $val mixed
	 * @param $not bool 是否not like
	 * */
	static function like ($key, $val, $not=false)
	{
		Query::bind([
			$key => $val
		]);
		return self::raw($key, $val, $not ? 'not like' : 'like');
	}



	/**
	 * AND 条件组合
	 * @param $condition array 仅允许一维数组
	 *
	 * */
	static function and_ (array $condition)
	{
		return ' '.join( ' and ' , $condition ).' ';
	}


	/**
	 * OR 条件组合
	 * @param $condition array 仅允许一维数组
	 *
	 * */
	static function or_ (array $condition=[])
	{
		return ' '.join( ' or ' , $condition ).' ';
	}




}