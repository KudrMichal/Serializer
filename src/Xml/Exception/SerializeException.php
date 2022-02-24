<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Exception;

class SerializeException extends \Exception
{
	public static function elementContainsArray(string $property): self
	{
		return new self('Element property ' . $property . ' cannot be array. Use "ElementArray" instead!');
	}


	public static function elementsNotIterableException(string $property): self
	{
		return new self("Elements property $property excepts iterable value");
	}


	public static function documentMissingException(): self
	{
		return new self("Root object not set");
	}
}
