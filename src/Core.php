<?php

namespace Peak\DB;

class Core
{

	static function connect ($type='mysql')
	{
		Query::pdo(Connector::init($type));
	}

//	static $debug_on = true ;
//	static $debug_error = [] ;


	/* sql查询错误返回
	 * @param
	 * @result: 返回报错值
	 */
//	static function error () {
//		$res = self :: $sth->errorInfo();
//		$res[0] *= 1 ;
//		return $res ;
//	}


	use \Peak\Plugin\Debuger\Base;




	protected static $statement_param = [];

	/**
	 * 设置绑定查询参数
	 * @param $dat string 加入查询的变量 默认为空 清空查询值
	 * @return int 加入变量的总数。
	 * */
	static function setParam (array $param=[])
	{
		$n = count(self::$statement_param);
		foreach ($param as $k=>$v) {
			is_array($v) ? self::{__FUNCTION__}(self::$statement_param, $v) : self::$statement_param[]=$v;
		}
		$param || self::$statement_param=[];

		$n = count(self::$statement_param)-$n;
		return $n>0 ? $n : 0;
	}


	/**
	 * 执行SQL查询
	 * @param $sql string 查询语句
	 * @return object PDO::Statement
	 * */
	final protected static function query ($sql)
	{
		$param = self::$statement_param;
		self::setParam([]);
		return Query::exec($sql, $param) ?: self::debug(Query::debug());
	}


	###### 高级应用

	/**
	 * 新增
	 * @param $sql string 查询语句
	 * @param $lastId bool 是否返回自增id值
	 * @return mixed 成功失败返回boolean值，如果$lastId为true，则返回int
	 * */
	static function create ($sql, $lastId=false)
	{
		$sth = self::query($sql);
		if ($sth) {
			if ($lastId) {
				return self::createdId();
			}
		}
		return (bool)$sth;
	}


	/**
	 * 返回新插入的自增值
	 * */
	static function createdId ()
	{
		return Query::pdo()->lastInsertId() ;
	}



	/**
	 * 查询
	 * @param $sql string 查询语句
	 * @param $single bool 是否返回单行数据
	 * @param $type mixed int类型，返回数组型数据，数字作键；string，同前，但以字段名字作键；null，返回对象型数据
	 * */
	static function read ($sql, $single=true, $type='assoc')
	{
		$sth = self::query($sql);
		if (!$sth) return false;

		if (is_string($type)) {
			$type = \PDO::FETCH_ASSOC;
		} else if (is_int($type)) {
			$type = \PDO::FETCH_NUM;
		} else if (is_null($type)) {
			$type = \PDO::FETCH_OBJ;
		}
		return $single ? $sth->fetch($type) : $sth->fetchAll($type);
	}


	static function update ($sql)
	{
		return (bool)self::query($sql);
	}


	static function delete ($sql)
	{
		return (bool)self::query($sql);
	}





	// 执行delete语句
//	static function delete ( $table , $where ) {
//
//		$sql = 'delete from '.$table ;
//		$sql.= ' where ' .self::where_and($where) ;
//
//		return self ::query ( $sql ) ;
//
//	}


	static function transact (\Closure $func)
	{
		self::transaction();
		try {
			$res = $func();
			self::transaction(1);
			return $res;
		} catch (\Exception $e) {
			self::transaction(-1);
			return $e;
		}
	}

	/**
	 * 表事务
	 * @param $step int 0或''开始，1或'+'提交，-1或'-'回滚。
	 * */
	static function transaction ($step=0)
	{
		$pdo = Query::pdo();
		switch ($step ) {
			case 0:
				echo $pdo->getAttribute(\PDO::ATTR_AUTOCOMMIT) ,'<br>' ;
				$pdo->beginTransaction() ;
				$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
				echo $pdo->getAttribute(\PDO::ATTR_AUTOCOMMIT);
				break;

			case '':
				self::{__FUNCTION__}(0);
				break;

			case 1:
				$pdo->commit() ;
				$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1) ;
				break ;

			case '+':
				self::{__FUNCTION__}(1);
				break;

			case -1:
				$pdo->rollBack() ;
				$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1) ;
				break ;

			case '-':
				self::{__FUNCTION__}(-1);
				break;
		}


	}


	// 关闭事务


////////////////// 不常用部分 未测试  /////////////////////////


	// lbs距离计算
	static function sql_lbs ( $lng_k , $lng_v , $lat_k , $lat_v ) {
//		$sql = ' ROUND(6378.138*2*ASIN(SQRT(POW(SIN(( '.$lat_v.' * PI()/180-'.$lat_k.'*PI()/180)/2),2)+COS( '.$lat_v.' *PI()/180)*COS( '.$lat_k.'*PI()/180)*POW(SIN(( '.$lng_v.' * PI()/180-'.$lng_k.'*PI()/180)/2),2)))*1000) ' ;
		$sql = '(2 * 6378.137* ASIN(SQRT(POW(SIN(PI()*( '.$lat_v.'- '.$lat_k.')/360),2)+COS(PI()*'.$lng_v.'/180)* COS('.$lat_k.' * PI()/180)*POW(SIN(PI()*( '.$lng_v.' -'. $lng_k .')/360),2))))' ;
		return $sql ;
	}



	// lbs距离条件
	static function sql_lbs_where ( $lng_k , $lng_v , $lat_k , $lat_v ) {
		$sql = ' '.$lng_k.' between '.$lng_v.' - 0.5 and '.$lng_v.' + 0.5  AND '.$lat_k.' between '.$lat_v.' - 0.5 and '.$lat_v.' + 0.5 ' ;
		return $sql ;
	}



	// 执行select count(*) 查询
	static function count ( $tb , $where=null ) {
		$res = self::select ( $tb , 'count(*)' , $where ) ;
		return $res['count(*)'] ;
	}


	// 组织select语句
	static function sqlSelect ( $tb , $field='*' , $where=null ) {

		$field = is_array($field) ? join( ',' , $field ) : $field ;
		$tb = is_array($tb) ? join( ',' , $tb ) : $tb ;
		$where = self::where_and($where) ;

		$sql = 'select '.$field.' from '.$tb;
		$sql.= $where ? ' where ' .$where : ' ' ;

		return $sql ;
	}



	static function affect ($sql ) {
		$res = self::query ($sql);
		return self::$sth->rowCount();
	}

}
