<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Element
{
	private ?string $name;
	private bool $ignoreNull;
	private string $dateFormat;
	private bool $cdata;
	/**
	 * @var callable|null
	 */
	private $callable;


	public function __construct(
		?string $name = NULL,
		bool $ignoreNull = FALSE,
		string $dateFormat = 'd.m.Y h:i:s',
		bool $cdata = FALSE,
		?callable $callable = NULL
	)
	{
		$this->name = $name;
		$this->ignoreNull = $ignoreNull;
		$this->dateFormat = $dateFormat;
		$this->cdata = $cdata;
		$this->callable = $callable;
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

	public function getCallable(): ?callable
	{
		return $this->callable;
	}
}
