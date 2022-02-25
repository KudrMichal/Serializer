<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json\Exception;

class DeserializeException extends \Exception
{
	public static function classNotExists(string $class): self
	{
		return new self("Class $class not found");
	}

	public static function invalidJson(): self
	{
		throw new self("Invalid json");
	}

	public static function jsonKeyNotFound(string $key): self
	{
		throw new self("Key $key not found");
	}

	public static function expectsArray(string $name): self
	{
		throw new self("Property $name expects array");
	}
}