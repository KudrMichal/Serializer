<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json;

use KudrMichal\Serializer\Json\Exception\DeserializeException;
use KudrMichal\Serializer\Json\Metadata\Property;
use KudrMichal\Serializer\Json\Metadata\PropertyArray;

class Deserializer
{
	/**
	 * @throws DeserializeException
	 */
	public function deserialize(string $class, string $json): object
	{
		if ( ! class_exists($class)) {
			throw DeserializeException::classNotExists($class);
		}

		$json = \json_decode($json);

		if ( ! $json) {
			throw DeserializeException::invalidJson();
		}

		$object = (new \ReflectionClass($class))->newInstanceWithoutConstructor();

		$this->deserializeObject($object, $json);

		return $object;
	}


	private function deserializeObject(object $object, \stdClass $json): void
	{
		$refl = new \ReflectionClass($object);
		foreach ($refl->getProperties() as $property) {
			if ( ! $attributes = $property->getAttributes()) {
				continue;
			}

			$property->setAccessible(TRUE);
			foreach ($attributes as $attribute) {
				match ($attribute->getName()) {
					Property::class => $this->deserializeProperty($attribute->newInstance(), $object, $json, $property),
					PropertyArray::class => $this->deserializePropertyArray($attribute->newInstance(), $object, $json, $property),
					default => function() {},
				};
			}
		}
	}


	private function deserializeProperty(Property $attribute, object $object, \stdClass $json, \ReflectionProperty $property): void
	{
		$name = $attribute->getName() ?? $property->getName();
		if ( ! isset($json->$name)) {
			throw DeserializeException::jsonKeyNotFound($name);
		}

		$propertyType = \ltrim((string) $property->getType(), '?');
		switch (TRUE) {
			case $this->isNative($propertyType):
				$property->setValue($object, $this->castValue($propertyType, $json->$name));
				break;
			case \class_exists($propertyType):
				$newObject = (new \ReflectionClass($propertyType))->newInstanceWithoutConstructor();
				$this->deserializeObject($newObject, $json->$name);
				$property->setValue($object, $newObject);
				break;
		}
	}


	private function deserializePropertyArray(PropertyArray $attribute, object $object, \stdClass $json, \ReflectionProperty $property): void
	{
		$name = $attribute->getName() ?? $property->getName();

		if ( ! isset($json->$name)) {
			throw DeserializeException::jsonKeyNotFound($name);
		}

		if ( ! is_array($json->$name)) {
			throw DeserializeException::expectsArray($property->getName());
		}

		$propertyType = \ltrim((string) $property->getType(), '?');
		if ($propertyType !== 'array') {
			throw DeserializeException::expectsArray($property->getName());
		}

		$values = $json->$name;
		switch (TRUE) {
			case \is_null($attribute->getType()):
				break;
			case \class_exists($attribute->getType()):
				$values = \array_map(function($value) use ($attribute) {
					$newObject = (new \ReflectionClass($attribute->getType()))->newInstanceWithoutConstructor();
					$this->deserializeObject($newObject, $value);
					return $newObject;
				}, $values);
				break;
			default:
				$values = \array_map(fn($value) => $this->castValue($attribute->getType(), $value), $values);
		}

		$property->setValue($object, $values);
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
