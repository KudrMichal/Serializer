<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Xml\Classes;

class TestArrayStringAdapter
{
	public static function convert(array|string $value): array|string
	{
		return \is_string($value) ? \explode(':', $value) : \implode(':', $value);
	}
}
