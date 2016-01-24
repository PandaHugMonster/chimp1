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

class Group extends Node {
	
	const XML_NAME_RU = 'Группа';
	const XML_NAME_EN = 'Group';
	
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
		'Группы' => [
			'alias' => 'Группа',
			'type' => 'class[]',
 			'class' => '\org\pandacorp\commerceml\models\Group',
			'name' => 'groups', 
			'method' => 'addGroup',
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
	
	public function addGroup($group) {
		$this->groups[] = $group;
	}
	public function getGroups() {
		return $this->groups;
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