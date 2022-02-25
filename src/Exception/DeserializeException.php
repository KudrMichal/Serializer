<?php declare(strict_types=1);

namespace KudrMichal\XmlSerialize\Exception;

class DeserializeException extends \Exception
{
	public static function classNotFound(string $class): self
	{
		return new self("Class $class not found");
	}


	public static function documentMissing(): self
	{
		return new self("Root object not set");
	}
}
