<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Attribute
{
	private ?string $name;
	private bool $ignoreNull;
	private ?string $dateFormat;

	public function __construct(?string $name = NULL, bool $ignoreNull = FALSE, ?string $dateFormat = 'd.m.y')
	{
		$this->name = $name;
		$this->ignoreNull = $ignoreNull;
		$this->dateFormat = $dateFormat;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function isIgnoreNull(): bool
	{
		return $this->ignoreNull;
	}

	public function getDateFormat(): ?string
	{
		return $this->dateFormat;
	}
}
