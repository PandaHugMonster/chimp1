#!/bin/php
<?php
define('_HERE', dirname(__FILE__));

define('MIME_ZIP', 'application/zip');
define('MIME_XML', 'application/xml');

// Если есть необходимость, автоподгрузку классов можно отключить закоментировава
// или удалив эту строку. К примеру если вы используете какой-то фреймворк, который уже
// реализовывает возможности подгрузки классов
include_once 'autoloader.php';

// Test

use org\pandacorp\commerceml\models\CommerceML;


function pd($d = "") {
	print_r($d); die;
}
function generateGUID() {
	$guid = '';
	for ($i = 0; $i != 16; ++$i) {
		$guid .= chr(mt_rand(0, 255));
	}
	$guid = bin2hex($guid);
	
	foreach ([20, 16, 12, 8] as $pos)
		$guid = substr_replace($guid, '-', $pos, 0);

	return $guid;
}
function mimeType($filename) {
	return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename); 
}
function logging($data) {
	print_r($data);
	echo "\n";
}
function maximumMemoryUsage() {
	return round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB';
}
function finalizeLog($data, $correctness, $usage) {
	logging('Массив: ');
	logging($data);
	logging('Максимально памяти выделено: ' . $usage);
	logging('Документ верен: ' . $correctness);
}

// function getDataFields($xml) {
// 	$fields = [];
	
// 	while ($xml->read()) {
// 		if ($xml->nodeType == XMLReader::ELEMENT) {
// 			$nodeName = $xml->name;
// // 			$attrs = [];
				
// // 			if ($xml->hasAttributes) {
// // 				while ($xml->moveToNextAttribute()) {
// // 					$attrs[$xml->name] = $xml->value;
// // 				}
// // 			}
				
// // 			foreach ($treeClassesAvailable as $key => $cls) {
// // 				if ($key == $nodeName) {
// // 					$item = new $cls($attrs);
// // 					echo "\n\n".$item."\n\n";
// // 				}
// // 			}
			
// 			if (in_array($nodeName, ['Ид', 'Наименование'])) {
// 				$xml->read();
// 				$fields[$nodeName] = $xml->value;
// 			}
// 		}
// 	}
// 	return $fields;
// }

function buildTheTree($xml, $treeClassesAvailable) {
	$tree = [];
	
	$opennedTag = null;
	while ($xml->read()) {
		$nodeName = $xml->name;
	 	if ($xml->nodeType == XMLReader::END_ELEMENT) {
			return $tree;
		} elseif ($xml->nodeType == XMLReader::ELEMENT) {
			$opennedTag = $nodeName; 
// 			if ($nodeName == 'Классификатор') {
				
// 			} elseif ($nodeName == 'КоммерческаяИнформация') {
				
				
// 				foreach ($treeClassesAvailable as $key => $cls) {
// 					if ($key == $nodeName) {
// 						$item = new $cls($attrs);
// 						echo "\n\n".$item."\n\n";
// 					}
// 				}
// 			}
		} 
	}
	
	return $tree;
}

$treeClassesAvailable = [
	'КоммерческаяИнформация' => '\org\pandacorp\commerceml\models\CommerceML',
	'Классификатор' => '\org\pandacorp\commerceml\models\Classifier'
];

$xsdfilename = _HERE.'/CML208.XSD';

$filename = _HERE.'/webdata/import0_1.xml';
// $filename = _HERE.'/webdata/offers0_1.xml';
// $filename = _HERE.'/webdata.zip';

$data = null;

//*

if (mimeType($filename) == MIME_XML) {
	logging('Обработка данных');
	
	$xml = new \XMLReader();
	$xml->open($filename);
	$correctness = $xml->setSchema($xsdfilename);
	
	if ($correctness) {
		$cml = new CommerceML($xml);
	}
	
	$xml->close();
	
} else {
	throw new Exception('Wrong file mime');
}
/*/
$xml = simplexml_load_file($filename);

logging($xml->asXML());
//*/

finalizeLog($tree = null, $correctness, maximumMemoryUsage());