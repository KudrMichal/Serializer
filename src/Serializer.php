<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize;

class Serializer
{
	private \Doctrine\Common\Annotations\AnnotationReader $annotationReader;


	public function __construct(\Doctrine\Common\Annotations\AnnotationReader $annotationReader)
	{
		$this->annotationReader = $annotationReader;
	}


	/**
	 * @param object $object
	 */
	public function serialize($object): \DOMDocument
	{
		$doc = new \DOMDocument();

		$reflection = new \ReflectionClass($object);

		/** @var \KudrMichal\XmlSerialize\Metadata\Document $root */
		$root = $this->annotationReader->getClassAnnotation($reflection, \KudrMichal\XmlSerialize\Metadata\Document::class);

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
					case $annotation instanceof \KudrMichal\XmlSerialize\Metadata\Element:
						$this->serializeElement($annotation, $property, $object, $element, $doc);
						continue 2;
					case $annotation instanceof \KudrMichal\XmlSerialize\Metadata\Attribute:
						$this->serializeAttribute($annotation, $property, $object, $element);
						continue 2;
					case $annotation instanceof \KudrMichal\XmlSerialize\Metadata\ElementArray:
						$this->serializeElementArray($annotation, $property, $object, $element, $doc);
						continue 2;
				}
			}
		}
	}


	private function serializeElement(\KudrMichal\XmlSerialize\Metadata\Element $annotation, \ReflectionProperty $property,	$object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $doc->createElement($annotation->name ?? $property->getName());
		$value = $property->getValue($object);

		if ($value === NULL && $annotation->ignoreNull) {
			return;
		}

		switch (TRUE) {
			case \is_array($value):
				throw \KudrMichal\XmlSerialize\Exception\SerializeException::elementContainsArray($property->getName());
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


	private function serializeAttribute(\KudrMichal\XmlSerialize\Metadata\Attribute $annotation, \ReflectionProperty $property,	$object, \DOMElement $parentElement): void
	{
		$value = $property->getValue($object);

		if ($value === NULL && $annotation->ignoreNull) {
			return;
		}

		$parentElement->setAttribute($annotation->name ?? $property->getName(), (string) $value);
	}


	private function serializeElementArray(\KudrMichal\XmlSerialize\Metadata\ElementArray $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $doc->createElement($annotation->name ?? $property->getName());
		if ( ! \is_array($values = $property->getValue($object))) {
			throw \KudrMichal\XmlSerialize\Exception\SerializeException::elementContainsArray($property->getName());
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
}
