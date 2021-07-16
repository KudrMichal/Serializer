<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Exception;

class SerializeException extends \Exception
{
	public static function elementContainsArray(string $property): self
	{
		return new self('Element property ' . $property . ' cannot be array. Use "ElementArray" instead!');
	}
}
