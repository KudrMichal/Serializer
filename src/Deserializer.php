<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize;

use KudrMichal\XmlSerialize\Metadata\Attribute;
use KudrMichal\XmlSerialize\Metadata\Element;
use KudrMichal\XmlSerialize\Metadata\ElementArray;
use KudrMichal\XmlSerialize\Metadata\Elements;

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
					case $annotation instanceof Elements:
						$this->deserializeElements($annotation, $element, $object, $property);
						break;
				}
			}
		}
	}


	private function deserializeElement(Element $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$elements = $this->getElementsByTagName($parentElement, $annotation->name ?? $property->getName());

		if ( ! count($elements)) {
			return;
		}

		if (count($elements) > 1) {
			return;
		}

		/** @var \DOMElement $element */
		$element = $elements[0];

		$type = \ltrim((string) $property->getType(), '?');

		switch (TRUE) {
			case $this->isNative($type):
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
		$arrayParent = $this->getElementsByTagName($parentElement, $annotation->name ?? $property->getName());

		if (count($arrayParent) > 1) {

		}

		if ( ! $arrayParent) {
			return;
		}

		$arrayParent = \reset($arrayParent);

		$items = $this->getElementsByTagName($arrayParent, $annotation->itemName);

		$values = [];

		$type = $annotation->type;

		/** @var \DOMElement $item */
		foreach ($items as $item) {
			switch (TRUE) {
				case $this->isNative($type):
					$values[] = $this->castValue((string) $annotation->type, $item->textContent);
					break;
				case \class_exists($type):
					$elementObject = (new \ReflectionClass($type))->newInstanceWithoutConstructor();
					$this->deserializeObject($item, $elementObject);
					$values[] = $elementObject;
					break;
			}
		}

		$property->setValue($object, $values);
	}


	public function deserializeElements(Elements $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$items = $this->getElementsByTagName($parentElement, $annotation->name);

		$values = [];

		$type = $annotation->type;

		/** @var \DOMElement $item */
		foreach ($items as $item) {
			switch (TRUE) {
				case $this->isNative($type):
					$values[] = $this->castValue((string) $annotation->type, $item->textContent);
					break;
				case \class_exists($type):
					$elementObject = (new \ReflectionClass($type))->newInstanceWithoutConstructor();
					$this->deserializeObject($item, $elementObject);
					$values[] = $elementObject;
					break;
			}
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


	private function isNative(string $type): bool
	{
		$type = \ltrim($type, '?');

		return \in_array(
			$type,
			[
				'bool',
				'int',
				'float',
				'string',
				'',
				\DateTimeInterface::class,
				\DateTimeImmutable::class,
				\DateTime::class,
			]
		);
	}


	private function getElementsByTagName(\DOMElement $parent, string $tagName): array
	{
		$elements = [];

		foreach ($parent->childNodes as $child) {
			if ($child instanceof \DOMElement && $child->localName === $tagName) {
				$elements[] = $child;
			}
		}

		return $elements;
	}
}
