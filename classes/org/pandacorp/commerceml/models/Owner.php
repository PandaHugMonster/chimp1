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

class Owner extends Node {
	
	const XML_NAME_RU = 'Владелец';
	const XML_NAME_EN = 'Owner';
	
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
		'ПолноеНаименование' => [
			'type' => 'string',
			'name' => 'fullName', 
			'method' => 'setFullName', 
			'required' => false
		],
		'КПП' => [
			'type' => 'string',
			'name' => 'kpp', 
			'method' => 'setKPP', 
			'required' => false
		]
	];
	
	protected $name;
	protected $fullName;
	protected $kpp;
	
	public function setKPP($kpp) {
		$this->kpp = $kpp;
	}
	public function getKPP() {
		return $this->kpp;
	}
	
	public function setFullName($fname) {
		$this->fullName = $fname;
	}
	public function getFullName() {
		return $this->fullName;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}
	
	public function __toString() {
		return 'Тег: ' . self::XML_NAME_RU . 
			"; Ид: " . $this->getId() . 
			"; Наименование: " . $this->getName();
	}
	protected function init($xml) {
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU);
	}
}