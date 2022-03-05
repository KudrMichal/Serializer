<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml;

use KudrMichal\Serializer\Xml\Exception\SerializeException;
use KudrMichal\Serializer\Xml\Metadata\Document;
use KudrMichal\Serializer\Xml\Metadata\Attribute;
use KudrMichal\Serializer\Xml\Metadata\Element;
use KudrMichal\Serializer\Xml\Metadata\ElementArray;
use KudrMichal\Serializer\Xml\Metadata\Elements;

class Serializer
{

	/**
	 * @param object $object
	 *
	 * @throws Exception\SerializeException
	 * @throws \DOMException
	 */
	public function serialize($object): \DOMDocument
	{
		$reflection = new \ReflectionClass($object);
		/** @var Document $document */
		$document = $reflection->getAttributes(Document::class)[0]->newInstance();

		if ( ! $document) {
			throw SerializeException::documentMissingException();
		}

		$doc = new \DOMDocument($document->getVersion(), $document->getEncoding());
		if ( ! is_null($document->getStandalone())) {
			$doc->xmlStandalone = $document->getStandalone();
		}

		$rootName = $document->getName();
		if ($document->getNamespace()) {
			$rootName = $document->getNamespace() . ':' . $rootName;
		}

		$doc->appendChild($root = $doc->createElement($rootName));

		foreach ($document->getNamespaces() as $name => $namespace) {
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:' . $name, $namespace);
		}

		$this->serializeObject($object, $root, $doc);

		return $doc;
	}


	private function serializeObject($object, \DOMElement $element, \DOMDocument $doc): void
	{
		$refl = new \ReflectionClass($object);

		foreach ($refl->getProperties() as $property) {
			$annotations = $property->getAttributes();
			if ( ! $annotations) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($annotations as $annotation) {
				switch ($annotation->getName()) {
					case Element::class:
						$this->serializeElement($annotation->newInstance(), $property, $object, $element, $doc);
						continue 2;
					case Attribute::class:
						$this->serializeAttribute($annotation->newInstance(), $property, $object, $element);
						continue 2;
					case ElementArray::class:
						$this->serializeElementArray($annotation->newInstance(), $property, $object, $element, $doc);
						continue 2;
					case Elements::class:
						$this->serializeElements($annotation->newInstance(), $property, $property->getValue($object), $element, $doc);
						continue 2;
				}
			}
		}
	}


	private function serializeElement(Element $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $this->createElement($doc, $annotation->getName() ?? $property->getName(), $annotation->getPrefix());

		$value = $property->getValue($object);

		if ($annotation->getCallable()) {
			$value = \call_user_func($annotation->getCallable(), $value);
		}

		switch (TRUE) {
			case $value === NULL && $annotation->isIgnoringNull():
				return;
			case $annotation->isCData() && ! is_scalar($value):
				throw SerializeException::cDataOnlyScalar($property->getName());
			case \is_array($value):
				throw SerializeException::elementContainsArray($property->getName());
			case \is_scalar($value) && $annotation->isCData():
				$element->appendChild($doc->createCDATASection((string) $value));
				break;
			case \is_scalar($value):
				$element->nodeValue = $value;
				break;
			case $value instanceof \DateTimeInterface:
				$element->nodeValue = $value->format($annotation->getDateFormat());
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

		if ($annotation->getCallable()) {
			$value = \call_user_func($annotation->getCallable(), $value);
		}

		if ($value === NULL && $annotation->isIgnoreNull()) {
			return;
		}

		$parentElement->setAttribute($annotation->getName() ?? $property->getName(), (string) $value);
	}


	private function serializeElementArray(ElementArray $annotation, \ReflectionProperty $property, $object, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		$element = $this->createElement($doc, $annotation->getName() ?? $property->getName(), $annotation->getPrefix());

		if ( ! \is_array($values = $property->getValue($object))) {
			throw SerializeException::elementContainsArray($property->getName());
		}

		foreach ($values as $value) {
			if ($annotation->getCallable()) {
				$value = \call_user_func($annotation->getCallable(), $value);
			}
			$itemElement = $doc->createElement($annotation->getItemName());
			switch (TRUE) {
				case \is_array($value):
					throw SerializeException::elementContainsArray($property->getName());
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


	private function serializeElements(Elements $annotation, \ReflectionProperty $property, $values, \DOMElement $parentElement, \DOMDocument $doc): void
	{
		if ( ! is_iterable($values)) {
			throw SerializeException::elementsNotIterableException($property->getName());
		}

		foreach ($values as $value) {
			if ($annotation->getCallable()) {
				$value = \call_user_func($annotation->getCallable(), $value);
			}
			$itemElement = $doc->createElement($annotation->getName());
			switch (TRUE) {
				case \is_array($value):
					throw SerializeException::elementContainsArray($property->getName());
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


	private function createElement(\DOMDocument $doc, string $name, string $prefix = NULL): \DOMElement
	{
		$nsUri = $doc->lookupNamespaceURI($prefix);

		if ($prefix) {
			$name = $prefix . ':' . $name;
		}

		return $doc->createElementNS($nsUri, $name);
	}
}
