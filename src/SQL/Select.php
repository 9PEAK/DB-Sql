<?php

namespace Peak\DB\SQL;

trait Select
{


	protected static function if_ ($field, $compare, $case, $false, $true=null, $as=null)
	{
		$sql = 'if (';
		$sql.= $field.$compare.(is_numeric($case) ? $case : '\''.$case.'\''). ',';
		$sql.= (isset($true) ? $true : $field).',';
		$sql.= $false;
		$sql.= ')';
		$as && $sql.='as '.$as;
		return $sql;
	}

	/**
	 * sql查询null字段定义默认值
	 * @param $field string 字段名
	 * @param $case string|int 条件值
	 * @param $false mixed 条件不成立时的值
	 * @param $true mixed 条件成立时的结果，默认null表示使用数据库中存储的值
	 * @param $as 查询结果字段别名
	 * */
	static function ifEqual ($field, $case, $false, $true=null, $as=null)
	{
		return self::if_($field, '=', $case, $false, $true, $as);
	}


	static function ifLess ($field, $case, $false, $true=null, $as=null)
	{
		return self::if_($field, '<', $case, $false, $true, $as);
	}


	static function ifLessEqual ($field, $case, $false, $true=null, $as=null)
	{
		return self::if_($field, '<=', $case, $false, $true, $as);
	}


	static function ifMore ($field, $case, $false, $true=null, $as=null)
	{
		return self::if_($field, '>', $case, $false, $true, $as);
	}

	static function ifMoreEqual ($field, $case, $false, $true=null, $as=null)
	{
		return self::if_($field, '>=', $case, $false, $true, $as);
	}


	/**
	 * sql查询null字段定义默认值
	 * @param $field string 字段名
	 * @param $value 字段为null的时候的指定值
	 * @param $as 查询结果字段别名
	 * @return sring sql语句
	 * */
	static function ifNull ($field, $value=null, $as=null)
	{
		$sql = 'ifnull (';
		$sql.= '\''.$field.'\','.(is_null($value)||is_numeric($value) ? $value : '\''.$value.'\'');
		$sql.= ')';
		$as && $sql.='as '.$as;
		return $sql;
	}


	/**
	 * 生成select语句case then部分
	 * @param $field string 字段名
	 * @param $condition array 条件，key为条件，val为值
	 * @param $as 查询结果字段别名
	 * @return sring sql语句
	 * */
	static function caseThenOfSimple ($field, array $condition, $as=null)
	{
		$sql = 'case ';
		foreach ($condition as $key=>$val) {
			$sql.= 'when ';
//			$sql.= $field.'=\''.$key.'\'';
			$sql.= $field.'='.(is_numeric($key) ? $key : '\''.$key.'\'');
			$sql.= ' then \''.$val.'\'';
		}
		$sql.= ' end';
		$as && $sql = '('.$sql.') as '.$as;
		return $sql;
	}


}
