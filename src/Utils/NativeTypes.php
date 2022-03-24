<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Utils;

class NativeTypes
{
	private const INT = 'int';
	private const STRING = 'string';
	private const BOOLEAN = 'bool';
	private const FLOAT = 'float';

	private const SUPPORTED_TYPES = [
		self::BOOLEAN,
		self::INT,
		self::FLOAT,
		self::STRING,
		\DateTimeInterface::class,
		\DateTimeImmutable::class,
		\DateTime::class,
	];


	public static function isNative(string $type): bool
	{
		$type = \ltrim($type, '?');

		if (empty($type)) {
			return TRUE; //no type specified
		}

		return \in_array($type,self::SUPPORTED_TYPES);
	}


	public static function cast(?string $type, string|bool|int|float $value): float|bool|int|string|\DateTimeImmutable|\DateTime
	{
		return match ($type) {
			\DateTimeInterface::class, \DateTimeImmutable::class => new \DateTimeImmutable($value),
			\DateTime::class => new \DateTime($value),
			'bool' => \is_string($value) ? \strtolower($value) === 'true' : \boolval($value),
			'float' => \floatval($value),
			'string' => \strval($value),
			'int' => \intval($value),
			default => $value
		};
	}
}
