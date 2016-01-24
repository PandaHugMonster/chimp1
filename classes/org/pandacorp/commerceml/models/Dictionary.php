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

class Dictionary extends Node {
	
	const XML_NAME_RU = 'Справочник';
	const XML_NAME_EN = 'Dictionary';
	
	protected $availableFields = [
		'ИдЗначения' => [
			'type' => 'string',
			'name' => 'id', 
			'method' => 'setId', 
			'required' => true
		],
		'Значение' => [
			'type' => 'string',
			'name' => 'value', 
			'method' => 'setValue', 
			'required' => true
		]
	];
	
	protected $name;
	
	public function setValue($value) {
		$this->value = $value;
	}
	public function getValue() {
		return $this->value;
	}
	public function __toString() {
		return 'Тег: ' . self::XML_NAME_RU . 
			"; ИдЗначения: " . $this->getId() . 
			"; Значение: " . $this->getValue();
	}
	protected function init($xml) {
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU, true);
	}
}