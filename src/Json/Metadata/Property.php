<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Property
{
	/**
	 * @serialize
	 * @deserialize
	 *
	 * Json property name. If null, php object property name is used
	 */
	private ?string $name;

	/**
	 * @serialize
	 *
	 * If true, json property is not created, if php object value is null
	 */
	private bool $ignoreNull;

	/**
	 * @serialize
	 */
	private string $dateFormat;

	/**
	 * @serialize
	 * @deserialize
	 *
	 * Callable for custom value conversion
	 *
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
