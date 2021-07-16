<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize;

use KudrMichal\XmlSerialize\Metadata\Attribute;
use KudrMichal\XmlSerialize\Metadata\Element;
use KudrMichal\XmlSerialize\Metadata\ElementArray;

class Deserializer
{

	private \Doctrine\Common\Annotations\AnnotationReader $annotationReader;


	public function __construct(\Doctrine\Common\Annotations\AnnotationReader $annotationReader)
	{
		$this->annotationReader = $annotationReader;
	}


	public function deserialize(\DOMDocument $xml, string $class): object
	{
		if ( ! \class_exists($class)) {
			//throw
		}

		$refl = new \ReflectionClass($class);

		/** @var \KudrMichal\XmlSerialize\Metadata\Document $document */
		if ($document = $this->annotationReader->getClassAnnotation($refl, \KudrMichal\XmlSerialize\Metadata\Document::class)) {
			//throw
		}

		if ($xml->documentElement->tagName !== $document->name) {
			//throw
		}

		$object = $refl->newInstanceWithoutConstructor();

		$this->deserializeObject($xml->documentElement, $object);

		return $object;
	}


	private function deserializeObject(\DOMElement $element, $object): void
	{
		$refl = new \ReflectionClass($object);

		foreach ($refl->getProperties() as $property) {
			if ( ! $annotations = $this->annotationReader->getPropertyAnnotations($property)) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($annotations as $annotation) {
				switch (TRUE) {
					case $annotation instanceof Element:
						$this->deserializeElement($annotation, $element, $object, $property);
						break;
					case $annotation instanceof Attribute:
						$this->deserializeAttribute($annotation, $element, $object, $property);
						break;
					case $annotation instanceof ElementArray:
						$this->deserializeElementArray($annotation, $element, $object, $property);
						break;
				}
			}
		}
	}


	private function deserializeElement(Element $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$elements = $parentElement->getElementsByTagName($annotation->name ?? $property->getName());
		if ( ! $elements->count()) {
			//throw
		}

		if ($elements->count() > 1) {
			//throw
		}

		/** @var \DOMElement $element */
		$element = $elements->item(0);

		$type = \ltrim((string) $property->getType(), '?');

		switch (TRUE) {

			case $type === \DateTimeInterface::class:
			case $type === \DateTimeImmutable::class:
			case $type === \DateTime::class:
			case $type === 'bool':
			case $type === 'float':
			case $type === 'string':
			case $type === 'int':
			case $type === NULL:
				$property->setValue($object, $this->castValue($type, $element->textContent));
				break;
			case \class_exists($type):
				$elementObject = (new \ReflectionClass($type))->newInstanceWithoutConstructor();
				$this->deserializeObject($element, $elementObject);
				$property->setValue($object, $elementObject);
				break;
		}
	}


	private function deserializeAttribute(Attribute $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$attribute = $parentElement->getAttribute($annotation->name ?? $property->getName());
		$type = (string) $property->getType();

		$property->setValue($object, $this->castValue($type, $attribute));
	}


	private function deserializeElementArray(ElementArray $annotation, \DOMElement $parentElement,	object $object,	\ReflectionProperty $property): void
	{
		$items = $parentElement->getElementsByTagName($annotation->itemName);

		$values = [];

		/** @var \DOMNode $item */
		foreach ($items as $item) {
			$values[] = $this->castValue((string) $annotation->type, $item->textContent);
		}

		$property->setValue($object, $values);
	}


	private function castValue(string $type, string $value): float|bool|int|string|\DateTimeImmutable|\DateTime
	{
		switch (TRUE) {
			case $type === \DateTimeInterface::class:
			case $type === \DateTimeImmutable::class:
				return new \DateTimeImmutable($value);
			case $type === \DateTime::class:
				return new \DateTime($value);
			case $type === 'bool':
				return \boolval($value);
			case $type === 'float':
				return \floatval($value);
			case $type === 'string':
			case $type === '':
				return \strval($value);
			case $type === 'int':
				return \intval($value);
		}
	}

}
