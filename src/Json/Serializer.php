<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json;

use KudrMichal\Serializer\Json\Metadata\Property;
use KudrMichal\Serializer\Json\Metadata\PropertyArray;

class Serializer
{
	public function serialize(array|object $source, int $jsonFlags = 0): string
	{
		$jsonArray = [];

		$this->serializeObject($source, $jsonArray);

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


	private function serializeProperty(Property $attribute, object $object, array &$jsonArray, \ReflectionProperty $property): void
	{
		$name = $attribute->getName() ?? $property->getName();

		$propertyType = \ltrim((string) $property->getType(), '?');
		switch (TRUE) {
			case $this->isNative($propertyType):
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
		foreach ($items as $value) {
			switch (TRUE) {
				case \is_scalar($value):
					$jsonArray[$name][] = $value;
					break;
				case $value instanceof \DateTimeInterface:
					$jsonArray[$name][] = $value->format($attribute->getDateFormat());
					break;
				case \is_object($value):
					$newJsonArray = [];
					$this->serializeObject($value, $newJsonArray);
					$jsonArray[$name][] = $newJsonArray;
					break;
			}
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

	private function castValue(?string $type, string|bool|int|float $value): float|bool|int|string|\DateTimeImmutable|\DateTime
	{
		return match ($type) {
			NULL => $value,
			\DateTimeInterface::class | \DateTimeImmutable::class => new \DateTimeImmutable($value),
			\DateTime::class => new \DateTime($value),
			'bool' => \boolval($value),
			'float' => \floatval($value),
			'string' | '' => \strval($value),
			'int' => \intval($value),
		};
	}
}
