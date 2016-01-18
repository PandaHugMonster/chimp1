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
	protected function obtainFromXmlReader($xml, $const, $imRoot = false) {
	
		do {
			$nodeName = $xml->name;
			if ($nodeName != $const)
				continue;
	
			if ($xml->nodeType == \XMLReader::END_ELEMENT)
				break;
					
			if ($xml->nodeType == \XMLReader::ELEMENT) {
				$this->processAttributes($xml);
				$this->processFields($xml);
			}
		} while ($xml->next());
		echo "\n".$this."\n";
	}
	
	protected function processAttributes($xml) {
		$attrs = [];
		if ($xml->hasAttributes)
			while ($xml->moveToNextAttribute())
				$attrs[$xml->name] = $xml->value;
	
			$xml->moveToElement();
			$this->checkRequiredAttributes($attrs);
	}
	protected function processFields($xml) {
		$xml->read();
		
		do {
			$nodeName = $xml->name;
			
			if ($xml->nodeType == \XMLReader::END_ELEMENT)
				break;
			
			$isAvailable = in_array($nodeName, array_keys($this->availableFields));
	
			if ($xml->nodeType == \XMLReader::ELEMENT && $isAvailable) {
				switch ($this->availableFields[$nodeName]['type']) {
					case 'class': $this->processFieldClass($xml); break;
					case 'string': $this->processFieldString($xml); break;
				}
			}
			
		} while ($xml->next());
	}
	
	protected function processFieldString($xml) {
		$nodeName = $xml->name;
		$method = $this->availableFields[$nodeName]['method'];
		$val = $xml->readString();
		$this->$method($val);
	}
	protected function processFieldClass($xml) {
		$nodeName = $xml->name;
	
		if (!$xml->isEmptyElement && !empty($this->availableFields[$nodeName]['class'])) {
			$cls = $this->availableFields[$nodeName]['class'];
			$obj = new $cls($xml);
			$setter = $this->availableFields[$nodeName]['method'];
			$this->$setter($obj);
		}
	}
	
}