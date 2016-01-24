<?php
/**
 * The core Node of CommerceML's chimp1 program
 * 
 * @author PandaHugMonster <ivan.ponomarev.pi@gmail.com>
 * @license The MIT License (MIT)
 *
 */
namespace org\pandacorp\commerceml;

/**
 * Abstract Node
 * 
 * Is the base for any XML element. CommerceML standard
 * 
 * @abstract
 * @version Node:0.0.1
 */
abstract class Node {
	
	abstract protected function init($xml);
	
	protected $id;
	public function hasId() {
		return !empty($this->availableField('Ид')) && !empty($this->id);
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	
	/**
	 * 
	 * @var ObjectsRegister $register
	 */
	protected $register;
	protected $children;
	protected $attributes;
	protected $fields;
	
	protected $availableAttributes = [];
	protected $availableFields = [];
	
	protected $dateTimeFormat = 'd:m:Y H:i:s';
	
	public function getAttribute($name, $ns = null) {
		if ($this->hasAttributes()) {
			$attrname = empty($ns)?$name:$ns.':'.$name;
			if (!empty($this->attributes[$attrname]))
				return $this->attributes[$attrname];
		}
		
		return null;
	}
	
	public function getAttributes() {
		return $this->attributes;
	}

	public function hasAttributes() {
		return !empty($this->getAttributes());
	}
	public function getFields() {
		return $this->fields;
	}

	public function hasFields() {
		return !empty($this->getFields());
	}
	
	public function getChildren() {
		return $this->children;
	}
	
	public function hasChildren() {
		return !empty($this->getChildren());
	}
	
	public function setDateTimeFormat($fmt = null) {
		$this->dateTimeFormat = $fmt;
	}
	public function getDateTimeFormat() {
		return $this->dateTimeFormat;
	}
	
	protected function dateFieldProcessGet($date) {
		if (empty($this->dateTimeFormat))
			return $date;
		else
			return date($this->dateTimeFormat, $date);
	}
	protected function dateFieldProcessSet($date) {
		if (is_string($date))
			return strtotime($date);
		elseif (is_int($date))
			return $date;
	}
	
	private function checkVariableArrayProps($array, $list, $type = 'item') {
		foreach ($array as $name => $properties) {
			if ($properties['required'] && empty($list[$name])) {
				throw new \Exception('No required ' . $type . ': ' . $name); die;
			} elseif (!empty($list[$name])) {
				$method = $properties['method'];
				$this->$method($list[$name]);
			}
		}
	}
	
	protected function checkRequiredAttributes($attributes) {
		$this->checkVariableArrayProps($this->availableAttributes, $attributes, 'attribute');
	}
	protected function checkRequiredFields($fields) {
		$this->checkVariableArrayProps($this->availableFields, $fields, 'field');
	}
	
	/**
	 *
	 * @param \XMLReader $xml
	 */
	protected function obtainFromXmlReader($xml, $const) {
		do {
			$nodeName = $xml->name;
			$nodeType = $xml->nodeType;
			
			if (!$this->checkParentEnd($nodeName, $const))
				continue;
					
			if ($this->checkXmlTypeElement($nodeType)) {
				$this->processAttributes($xml);
				$this->processFields($xml, $const, $nodeName);
			}
			break;
		} while ($xml->next());
		
		echo "\n".$this."\n";
	}
	
	protected function checkParentEnd($parentName, $const, $nodeName = null) {
		$s = $parentName == $const;
		if (!$s && !empty($nodeName))
			foreach ($this->availableFields as $key => $array) {
				if (!empty($array['alias']) && $array['alias'] == $const)
					$s = $nodeName == $key;
			}
		
		return $s;
	}
	
	protected function processAttributes($xml) {
		$attrs = [];
		if ($xml->hasAttributes)
			while ($xml->moveToNextAttribute())
				$attrs[$xml->name] = $xml->value;
	
			$xml->moveToElement();
			$this->checkRequiredAttributes($attrs);
	}
	
	private function splitTypeArray($type) {
		$isArray = strpos($type, '[]');
		$s = strstr($type, '[]', true);
		$s = $s === false?$type:$s;
		
		return [$s, $isArray];
	}
	
	protected function checkXmlTypeElement($nodeType) {
		return $nodeType == \XMLReader::ELEMENT;
	}
	protected function checkXmlTypeEnd($nodeType) {
		return $nodeType == \XMLReader::END_ELEMENT;
	}
	
	protected function processFields($xml, $const, $parentName = null) {
		$xml->read();
		do {
			$nodeName = $xml->name;
			$nodeType = $xml->nodeType;
			
			if ($this->checkXmlTypeEnd($nodeType) && $this->checkParentEnd($parentName, $const, $nodeName))
				break;
			
			if ($this->checkXmlTypeElement($nodeType) && $this->hasFieldName($nodeName)) {
				list($type, $isArray) = $this->splitTypeArray($this->availableField($nodeName)['type']);
				
				switch ($type) {
					case 'class': $this->processFieldClass($xml, $isArray); break;
					case 'string': $this->processFieldString($xml, $isArray); break;
				}
			}
			
		} while ($xml->next());
	}
	
	protected function processFieldString($xml, $isArray = false) {
		$nodeName = $xml->name;
		$method = $this->availableField($nodeName)['method'];
		$val = $xml->readString();
		$this->$method($val);
	}
	protected function processFieldClass($xml, $isArray = false) {
		$nodeName = $xml->name;
		
		if (!$xml->isEmptyElement && $this->hasFieldClass($nodeName)) {
			if ($isArray) {
				$xml->read();
				
				do {
					if ($this->checkXmlTypeEnd($xml->nodeType))
						break;
					
					$this->processFieldClass($xml);
				} while ($xml->next());
			} else {
				$cls = $this->availableField($nodeName)['class'];
				$obj = new $cls($this->register, $xml);
				$setter = $this->availableField($nodeName)['method'];
				$this->$setter($obj);
			}
		}
	}
	
	public function setObjectsRegister($reg) {
		$this->register = $reg;
	}
	public function getObjectsRegister() {
		return $this->register;
	}
	
	public function __construct($reg, $xml) {
		$this->setObjectsRegister($reg);
		
		$this->init($xml);
		
		if ($this->hasId())
			$this->register->objects[$this->id] = $this;
		else 
			$this->register->objects[] = $this;
	}
	
	public function availableField($name, $withAlias = true) {
		if (!empty($this->availableFields[$name]))
			return $this->availableFields[$name];
		else if ($withAlias) 
			foreach ($this->availableFields as $key => $val)
				if (!empty($val['alias']) && $val['alias'] == $name)
					return $val;
		return null;
	}
	public function hasFieldName($name, $withAlias = true) {
		return !empty($this->availableField($name, $withAlias));
	}
	public function hasFieldClass($name) {
		return !empty($this->availableField($name)['class']);
	}
}