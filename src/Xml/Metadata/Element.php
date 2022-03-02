<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Element
{
	private ?string $name;
	private bool $ignoreNull;
	private string $dateFormat;
	private bool $cdata;

	public function __construct(?string $name = NULL, bool $ignoreNull = FALSE, string $dateFormat = 'd.m.Y h:i:s', bool $cdata = FALSE)
	{
		$this->name = $name;
		$this->ignoreNull = $ignoreNull;
		$this->dateFormat = $dateFormat;
		$this->cdata = $cdata;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function isIgnoringNull(): bool
	{
		return $this->ignoreNull;
	}

	public function getDateFormat(): string
	{
		return $this->dateFormat;
	}

	public function isCData(): bool
	{
		return $this->cdata;
	}
}
