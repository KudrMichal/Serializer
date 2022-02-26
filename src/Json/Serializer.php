<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json;

use KudrMichal\Serializer\Json\Metadata\Property;
use KudrMichal\Serializer\Json\Metadata\PropertyArray;
use KudrMichal\Serializer\Utils\NativeTypes;

class Serializer
{
	public function serialize(array|object $source, int $jsonFlags = 0): string
	{
		$jsonArray = [];

		\is_object($source)
			? $this->serializeObject($source, $jsonArray)
			: $this->serializeArray($source, $jsonArray)
		;

		return \json_encode($jsonArray, $jsonFlags);
	}


	private function serializeObject(object $object, array &$jsonArray): void
	{
		$refl = new \ReflectionClass($object);
		foreach ($refl->getProperties() as $property) {
			if ( ! $attributes = $property->getAttributes()) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($attributes as $attribute) {
				match ($attribute->getName()) {
					Property::class => $this->serializeProperty($attribute->newInstance(), $object, $jsonArray, $property),
					PropertyArray::class => $this->serializePropertyArray($attribute->newInstance(), $property->getValue($object), $jsonArray, $property),
					default => function() {},
				};
			}
		}
	}


	public function serializeArray(array $source, array &$jsonArray): void
	{
		foreach ($source as $value) {
			switch (TRUE) {
				case \is_scalar($value):
					$jsonArray[] = $value;
					break;
				case $value instanceof \DateTimeInterface:
					$jsonArray[] = $value->format(\DateTime::W3C);
					break;
				case \is_object($value):
					$newJsonArray = [];
					$this->serializeObject($value, $newJsonArray);
					$jsonArray[] = $newJsonArray;
					break;
			}
		}
	}


	private function serializeProperty(Property $attribute, object $object, array &$jsonArray, \ReflectionProperty $property): void
	{
		$name = $attribute->getName() ?? $property->getName();

		if (\is_null($property->getValue($object)) && $attribute->isIgnoringNull()) {
			return;
		}

		$propertyType = \ltrim((string) $property->getType(), '?');
		switch (TRUE) {
			case NativeTypes::isNative($propertyType):
				$jsonArray[$name] = $property->getValue($object);
				break;
			case \class_exists($propertyType):
				$newArray = [];
				$this->serializeObject($property->getValue($object), $newArray);
				$jsonArray[$name] = $newArray;
				break;
		}
	}


	private function serializePropertyArray(PropertyArray $attribute, array $items, array &$jsonArray, \ReflectionProperty $property): void
	{
		$name = $attribute->getName() ?? $property->getName();

		$jsonArray[$name] = [];

		$this->serializeArray($items, $jsonArray[$name]);
	}
}
