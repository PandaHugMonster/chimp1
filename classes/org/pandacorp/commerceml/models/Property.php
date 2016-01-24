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

class Property extends Node {
	
	const XML_NAME_RU = 'Свойство';
	const XML_NAME_EN = 'Property';
	
	protected $availableFields = [
		'Ид' => [
			'type' => 'string',
			'name' => 'id', 
			'method' => 'setId', 
			'required' => true
		],
		'Наименование' => [
			'type' => 'string',
			'name' => 'name', 
			'method' => 'setName', 
			'required' => true
		],
		'ТипЗначений' => [
			'type' => 'string',
			'name' => 'type', 
			'method' => 'setType', 
			'required' => true
		],
		'ВариантыЗначений' => [
			'alias' => 'Справочник',
			'type' => 'class[]',
 			'class' => '\org\pandacorp\commerceml\models\Dictionary',
			'name' => 'values', 
			'method' => 'addValue',
			'required' => false
		],
	];
	
	protected $name;
	
	public function setName($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function getType() {
		return $this->type;
	}
	
	public function addValue($value) {
		$this->values[] = $value;
	}
	public function getValues() {
		return $this->values;
	}
	
	public function __toString() {
		return 'Тег: ' . self::XML_NAME_RU . 
			"; Ид: " . $this->getId() . 
			"; Наименование: " . $this->getName();
	}
	protected function init($xml) {
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU, true);
	}
}