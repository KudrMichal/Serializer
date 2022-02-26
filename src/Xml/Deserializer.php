<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml;

use KudrMichal\Serializer\Utils\NativeTypes;
use KudrMichal\Serializer\Xml\Metadata\Document;
use KudrMichal\Serializer\Xml\Metadata\Attribute;
use KudrMichal\Serializer\Xml\Metadata\Element;
use KudrMichal\Serializer\Xml\Metadata\ElementArray;
use KudrMichal\Serializer\Xml\Metadata\Elements;

class Deserializer
{
	public function deserialize(\DOMDocument $xml, string $class): object
	{
		if ( ! \class_exists($class)) {
			\KudrMichal\Serializer\Xml\Exception\DeserializeException::classNotFound($class);
		}

		$refl = new \ReflectionClass($class);

		if ( ! $documents = $refl->getAttributes(Document::class)) {
			throw \KudrMichal\Serializer\Xml\Exception\DeserializeException::documentMissing();
		}

		/** @var Document $document */
		$document = reset($documents);

		$object = $refl->newInstanceWithoutConstructor();

		$this->deserializeObject($xml->documentElement, $object);

		return $object;
	}


	private function deserializeObject(\DOMElement $element, $object): void
	{
		$refl = new \ReflectionClass($object);
		foreach ($refl->getProperties() as $property) {
			if ( ! $annotations = $property->getAttributes()) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($annotations as $annotation) {
				match ($annotation->getName()) {
					Element::class => $this->deserializeElement($annotation->newInstance(), $element, $object, $property),
					Attribute::class => $this->deserializeAttribute($annotation->newInstance(), $element, $object, $property),
					ElementArray::class => $this->deserializeElementArray($annotation->newInstance(), $element, $object, $property),
					Elements::class => $this->deserializeElements($annotation->newInstance(), $element, $object, $property)
				};
			}
		}
	}


	private function deserializeElement(Element $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$elements = $this->getElementsByTagName($parentElement, $annotation->getName() ?? $property->getName());

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
			case NativeTypes::isNative($type):
				$property->setValue($object, NativeTypes::cast($type, $element->textContent));
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
		$attribute = $parentElement->getAttribute($annotation->getName() ?? $property->getName());
		$type = (string) $property->getType();

		$property->setValue($object, NativeTypes::cast($type, $attribute));
	}


	private function deserializeElementArray(ElementArray $annotation, \DOMElement $parentElement,	object $object,	\ReflectionProperty $property): void
	{
		$arrayParent = $this->getElementsByTagName($parentElement, $annotation->getName() ?? $property->getName());

		if ( ! $arrayParent) {
			return;
		}

		$arrayParent = \reset($arrayParent);

		$items = $this->getElementsByTagName($arrayParent, $annotation->getItemName());

		$values = [];

		$type = $annotation->getType();

		/** @var \DOMElement $item */
		foreach ($items as $item) {
			switch (TRUE) {
				case NativeTypes::isNative($type):
					$values[] = NativeTypes::cast((string) $annotation->getType(), $item->textContent);
					break;
				case \class_exists($type):
					$elementObject = (new \ReflectionClass($type))->newInstanceWithoutConstructor();
					$this->deserializeObject($item, $elementObject);
					$values[] = $elementObject;
					break;
			};
		}

		$property->setValue($object, $values);
	}


	public function deserializeElements(Elements $annotation, \DOMElement $parentElement, object $object, \ReflectionProperty $property): void
	{
		$items = $this->getElementsByTagName($parentElement, $annotation->getName());

		$values = [];

		$type = $annotation->getType();

		/** @var \DOMElement $item */
		foreach ($items as $item) {
			switch (TRUE) {
				case NativeTypes::isNative($type):
					$values[] = NativeTypes::cast((string) $annotation->getType(), $item->textContent);
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
