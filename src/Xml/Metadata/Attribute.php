<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Attribute
{
	private ?string $name;
	private bool $ignoreNull;
	private ?string $dateFormat;
	/**
	 * @var callable|null
	 */
	private $callable;

	public function __construct(
		?string $name = NULL,
		bool $ignoreNull = FALSE,
		?string $dateFormat = 'd.m.y',
		?callable $callable = NULL
	)
	{
		$this->name = $name;
		$this->ignoreNull = $ignoreNull;
		$this->dateFormat = $dateFormat;
		$this->callable = $callable;
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

	public function getCallable(): ?callable
	{
		return $this->callable;
	}
}
