<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Property
{
	private ?string $name;
	private bool $ignoreNull;
	private string $dateFormat;
	/**
	 * @var callable|null
	 */
	private $callable;

	public function __construct(
		?string $name = NULL,
		bool $ignoreNull = FALSE,
		string $dateFormat = 'd.m.Y h:i:s',
		?callable $callable = NULL,
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

	public function isIgnoringNull(): bool
	{
		return $this->ignoreNull;
	}

	public function getDateFormat(): string
	{
		return $this->dateFormat;
	}

	public function getCallable(): ?callable
	{
		return $this->callable;
	}
}
