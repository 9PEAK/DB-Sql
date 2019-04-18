<?php

namespace Peak\Plugin\DB\SQL;

use Peak\Plugin\DB\Core;
use Peak\Plugin\DB\Query;

trait Insert
{


	/**
	 * 构造insert语句字段部分
	 * @param $dat array 插入的字段
	 * */
	static function insertKey (array $dat)
	{
		return count($dat)==count($dat,1) ? '('.join ( ',' , array_keys($dat) ).')' : self ::{__FUNCTION__}($dat[0]);
	}



	/**
	 * 构造insert语句值的部分
	 * @param $dat array 值，一维或二维数组
	 * */
	static function insertVal (array $dat) {

		if (count($dat)==count($dat,1) ) {
			// 绑定并生成sql语句
			$n = Core::setParam($dat);
			$dat = array_fill (0 , $n , '?' ) ;
			$dat = join ( ',' , $dat ) ;
			$dat = '('.$dat.')' ;
		} else {
			foreach ( $dat as &$v ) {
				$v = self::{__FUNCTION__}($v) ;
			}
			$dat = join ( ',', $dat );
		}

		return $dat ;
	}



	/**
	 * 生成insert语句
	 * @param $tb string 表名
	 * @param $dat array 插入的新增数据
	 * */
	static function insert ($tb , array $dat=[])
	{
		$sql = 'insert into '.$tb ;
		if ($dat) {
			$sql.= self::insertKey($dat);
			$sql.= 'values';
			$sql.= self::insertVal($dat);
		}
		return $sql;
	}



	/**
	 * 生成replace语句
	 * @param $tb string 表名
	 * @param $dat array 覆盖的重置数据
	 * */
	static function replace ( $tb , array $dat=[] )
	{
		$sql = 'replace into '.$tb ;
		if ($dat) {
			$sql.= self::insertKey($dat);
			$sql.= 'values';
			$sql.= self::insertVal($dat);
		}
		return $sql;
	}



}
