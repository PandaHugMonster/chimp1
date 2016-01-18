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
// 			'class' => '\org\pandacorp\commerceml\models\Classifier',
			'name' => 'owner', 
			'method' => 'setOwner', 
			'required' => false
		],
		'Группы' => [
			'type' => 'class',
// 			'class' => '\org\pandacorp\commerceml\models\Classifier',
			'name' => 'groups', 
			'method' => 'setGroups', 
			'required' => false
		],
		'Свойства' => [
			'type' => 'class',
// 			'class' => '\org\pandacorp\commerceml\models\Classifier',
			'name' => 'properties', 
			'method' => 'setProperties', 
			'required' => false
		]
	];
	
	protected $id;
	protected $name;
	protected $owner;
	protected $groups;
	protected $properties;
	
	public function setProperties($properties) {
		$this->properties = $properties;
	}
	public function getProperties() {
		return $this->properties;
	}
	
	public function setGroups($groups) {
		$this->groups = $groups;
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
	
	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	
	public function __toString() {
		return 'Тег: ' . self::XML_NAME_RU . 
			";\n\tИд: " . $this->getId() . 
			";\n\tНаименование: " . $this->getName();
	}
	
	protected function obtainFromXmlReader($xml, $const) {
		$i = 0;
		$nodeName = $xml->name;
		while ($xml->read()) {
			$i++;
			$nodeName = $xml->name;
			if ($nodeName == $const) {
				if ($xml->nodeType == \XMLReader::ELEMENT) {
					
// 					$attrs = [];
// 					if ($xml->hasAttributes)
// 						while ($xml->moveToNextAttribute())
// 							$attrs[$xml->name] = $xml->value;
// 					$this->checkRequiredFields($fields);
	
					if (!$xml->isEmptyElement) {
						list($tag, $content) = $this->readFields($xml);
						if (!empty($this->availableFields[$tag]) && $this->availableFields[$tag]['type'] == 'string') {
							$method = $this->availableFields[$tag]['method'];
							$this->$method($content);
						}
						
					}
// 						foreach ($this->availableFields as $name => $item) {
// 							if ($item['type'] == 'string') {
// 								$cls = $item['class'];
// 								$obj = new $cls($xml);
// 								$setter = $item['method'];
// 								$this->$setter($obj);
// 							} elseif ($item['type'] == 'class' && !empty($item['class'])) {
// 								$cls = $item['class'];
// 								$obj = new $cls($xml);
// 								$setter = $item['method'];
// 								$this->$setter($obj);
// 							}
// 						}
						
				} elseif ($xml->nodeType == \XMLReader::END_ELEMENT) {
					break;
				}
			}
		}
		
		echo "\n".$this."\n";
	}
	private function readFields($xml) {
		$i = 0;
		while ($xml->read()) {
			$i++;
			$nodeName = $xml->name;
			if ($xml->nodeType == \XMLReader::ELEMENT) {
				if (!$xml->isEmptyElement) {
					if ($this->availableFields[$nodeName]['type'] == 'string') {
						return [$nodeName, $xml->expand()->nodeValue];
					}
				}
			} elseif ($xml->nodeType == \XMLReader::END_ELEMENT) {
				break;
			}
		}
		return [$nodeName, $xml->expand()->nodeValue];
	}
	
	public function __construct($xml) {
		$this->obtainFromXmlReader($xml, self::XML_NAME_RU);
	}
}