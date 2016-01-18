<?php
// Просто функция автоподгрузки классов
// Структура каталогово и пространств имён 
// должно совпадать (принцип как в Java)
spl_autoload_register(function ($class) {
	$class = str_replace('\\', '/', $class);
	include_once _HERE.'/classes/'.$class.'.php'; 
});