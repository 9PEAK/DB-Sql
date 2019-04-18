<?php

namespace Peak\Plugin\DB;

include '../../vendor/autoload.php';

Connector::configDb('test', 'root', '');
Connector::configHost();
Connector::configOption();
Core::connect();

$sql = 'select * from 9peak_content ';
$sql.= 'where '.SQL\Where::equal('id', 1);

$res = Core::read($sql, true, null);
print_r($res);
//echo $res->category_id;
echo '<br>';


$sql = 'update 9peak_content set ';
$sql.= SQL\Update::set([
//	'category_id' =>  $res->category_id+1,
	'summary' => '你好呀 中国'
]);
Core::update($sql);



