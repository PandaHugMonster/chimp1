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

class Classifier extends Node {
	
	const XML_NAME_RU = 'Классификатор';
	const XML_NAME_EN = 'Classifier';
	
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
		'Владелец' => [
			'type' => 'class',
			'class' => '\org\pandacorp\commerceml\models\Owner',
			'name' => 'owner', 
			'method' => 'setOwner', 
			'required' => true
		],
		'Группы' => [
			'alias' => 'Группа',
			'type' => 'class[]',
 			'class' => '\org\pandacorp\commerceml\models\Group',
			'name' => 'groups', 
			'method' => 'addGroup',
			'required' => false
		],
		'Свойства' => [
			'alias' => 'Свойство',
			'type' => 'class[]',
			'class' => '\org\pandacorp\commerceml\models\Property',
			'name' => 'properties', 
			'method' => 'addProperty', 
			'required' => false
		]
	];
	
	protected $name;
	protected $owner;
	protected $groups = [];
	protected $properties = [];
	
	public function addProperty($property) {
		$this->properties[] = $property;
	}
	public function getProperties() {
		return $this->properties;
	}
	
	public function addGroup($group) {
		$this->groups[] = $group;
	}
	public function getGroups() {
		return $this->groups;
	}
	
	public function setOwner($owner) {
		$this->owner = $owner;
	}
	public function getOwner() {
		return $this->owner;
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
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU, true);
	}
}