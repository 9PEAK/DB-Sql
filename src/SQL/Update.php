<?php

namespace Peak\DB\SQL;

use Peak\DB\Core;

trait Update
{


	static function update ($tb , array $dat=[]) {
		$sql = 'update '.$tb .' set ';
		$dat && $sql.=self::set($dat) ;
		return $sql;
	}




	/**
	 * 构造SET语句
	 * @param $dat array 需要设置参数 key=>val的形式
	 * @param $glue string 连接符
	 * @return string sql语句
	 * */
	static function set (array $dat)
	{
		if ($dat) {
			$n = Core::setParam($dat);
			foreach ($dat as $k=>&$v) {
				$v = $k.'=?';
			}
			$dat = join( ',' , $dat );
			return ' '.$dat.' ';
		}
	}



}