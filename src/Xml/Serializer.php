<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml;

use KudrMichal\Serializer\Xml\Metadata\Document;
use KudrMichal\Serializer\Xml\Metadata\Attribute;
use KudrMichal\Serializer\Xml\Metadata\Element;
use KudrMichal\Serializer\Xml\Metadata\ElementArray;

class Serializer
{
	private \Doctrine\Common\Annotations\AnnotationReader $annotationReader;


	public function __construct(\Doctrine\Common\Annotations\AnnotationReader $annotationReader)
	{
		$this->annotationReader = $annotationReader;
	}


	/**
	 * @param object $object
	 *
	 * @throws Exception\SerializeException
	 */
	public function serialize($object): \DOMDocument
	{
		$doc = new \DOMDocument();

		$reflection = new \ReflectionClass($object);

		/** @var Document $root */
		$root = $this->annotationReader->getClassAnnotation($reflection, Document::class);

		if ( ! $root) {
			throw \KudrMichal\XmlSerialize\Exception\SerializeException::documentMissingException();
		}

		$doc->appendChild($root = $doc->createElement($root->name));

		$this->serializeObject($object, $root, $doc);

		return $doc;
	}


	private function serializeObject($object, \DOMElement $element, \DOMDocument $doc): void
	{
		$refl = new \ReflectionClass($object);

		foreach ($refl->getProperties() as $property) {
			$annotations = $this->annotationReader->getPropertyAnnotations($property);
			if ( ! $annotations) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($annotations as $annotation) {
				switch (TRUE) {
					case $annotation instanceof Element:
						$this->serializeElement($annotation, $property, $object, $element, $doc);
						continue 2;
					case $annotation instanceof Attribute:
						$this->serializeAttribute($annotation, $property, $object, $element);
						continue 2;
					case $annotation instanceof ElementArray:
						$this->serializeElementArray($annotation, $property, $object, $element, $doc);
						continue 2;
					case $annotation instanceof \KudrMichal\XmlSerialize\Metadata\Elements:
						$this->serializeElements($annotation, $property, $property->getValue($object), $element, $doc);
						continue 2;

				}
			}
		}
	}


	private function serializeElement(Element $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $doc->createElement($annotation->name ?? $property->getName());
		$value = $property->getValue($object);

		if ($value === NULL && $annotation->ignoreNull) {
			return;
		}

		switch (TRUE) {
			case \is_array($value):
				throw \KudrMichal\Serializer\Xml\Exception\SerializeException::elementContainsArray($property->getName());
			case \is_scalar($value):
				$element->nodeValue = $value;
				break;
			case $value instanceof \DateTimeInterface:
				$element->nodeValue = $value->format($annotation->dateFormat);
				break;
			case \is_object($value):
				$this->serializeObject($value, $element, $doc);
				break;
		}

		$parentElement->appendChild($element);
	}


	private function serializeAttribute(Attribute $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement): void
	{
		$value = $property->getValue($object);

		if ($value === NULL && $annotation->ignoreNull) {
			return;
		}

		$parentElement->setAttribute($annotation->name ?? $property->getName(), (string) $value);
	}


	private function serializeElementArray(ElementArray $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $doc->createElement($annotation->name ?? $property->getName());
		if ( ! \is_array($values = $property->getValue($object))) {
			throw \KudrMichal\Serializer\Xml\Exception\SerializeException::elementContainsArray($property->getName());
		}

		foreach ($values as $value) {
			$itemElement = $doc->createElement($annotation->itemName);
			switch (TRUE) {
				case \is_array($value):
					throw \KudrMichal\XmlSerialize\Exception\SerializeException::elementContainsArray($property->getName());
				case \is_scalar($value):
					$itemElement->nodeValue = $value;
					break;
				case \is_object($value):
					$this->serializeObject($value, $itemElement, $doc);
					break;
			}

			$element->appendChild($itemElement);
		}

		$parentElement->appendChild($element);
	}


	private function serializeElements(\KudrMichal\XmlSerialize\Metadata\Elements $annotation, \ReflectionProperty $property, $values, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		if ( ! is_iterable($values)) {
			throw \KudrMichal\XmlSerialize\Exception\SerializeException::elementsNotIterableException($property->getName());
		}

		foreach ($values as $value) {
			$itemElement = $doc->createElement($annotation->name);
			switch (TRUE) {
				case \is_array($value):
					throw \KudrMichal\XmlSerialize\Exception\SerializeException::elementContainsArray($property->getName());
				case \is_scalar($value):
					$itemElement->nodeValue = $value;
					break;
				case \is_object($value):
					$this->serializeObject($value, $itemElement, $doc);
					break;
			}

			$parentElement->appendChild($itemElement);
		}
	}
}
