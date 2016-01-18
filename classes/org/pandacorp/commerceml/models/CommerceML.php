<?php
/**
 * 
 * 
 * @author PandaHugMonster <ivan.ponomarev.pi@gmail.com>
 * @license The MIT License (MIT)
 *
 */
namespace org\pandacorp\commerceml\models;

use org\pandacorp\commerceml\Node;

class CommerceML extends Node {
	
	const XML_NAME_RU = 'КоммерческаяИнформация';
	const XML_NAME_EN = 'CommerceML';
	
	protected $availableAttributes = [
		'ВерсияСхемы' => ['name' => 'schemaVersion', 'method' => 'setSchemaVersion', 'required' => true],
		'ДатаФормирования' => ['name' => 'creationDate', 'method' => 'setCreationDate', 'required' => true],
	];
	protected $availableFields = [
		'Классификатор' => [
			'type' => 'class',
			'class' => '\org\pandacorp\commerceml\models\Classifier',
			'name' => 'classifier', 
			'method' => 'setClassifier', 
			'required' => true
		],
// 		'Каталог' => [
// 			'type' => 'class',
// 			'class' => '',
// 			'name' => 'catalogue', 
// 			'method' => 'setCatalogue', 
// 			'required' => false
// 		]
	];
	
	protected $schemaVersion;
	protected $creationDate;
	
	protected $classifier;
	protected $catalogue;
	
	public function setClassifier($value) {
		$this->classifier = $value;
	}
	public function getClassifier() {
		return $this->classifier;
	}
	public function setSchemaVersion($value) {
		$this->schemaVersion = $value;
	}
	public function setCreationDate($value) {
		$this->creationDate = $this->dateFieldProcessSet($value);
	}
	public function getSchemaVersion() {
		return $this->schemaVersion;
	}
	public function getCreationDate() {
		return $this->dateFieldProcessGet($this->creationDate);
	}
	
	public function __toString() {
		return 'Тег: ' . self::XML_NAME_RU . 
			'; ВерсияСхемы: ' . $this->schemaVersion . 
			'; ДатаФормирования: ' . $this->getCreationDate();
	}
	
	public function __construct($xml) {
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU, true);
	}
}