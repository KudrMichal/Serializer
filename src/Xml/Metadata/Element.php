<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Element
{
	/**
	 * @serialize
	 * @deserialize
	 *
	 * Xml element tag name. If null, php object property name is used
	 */
	private ?string $name;

	/**
	 * @serialize
	 *
	 * If true, xml element is not created, if php object value is null
	 */
	private bool $ignoreNull;

	/**
	 * @serialize
	 */
	private string $dateFormat;

	/**
	 * @serialize
	 * if true, value is surrounded by CData section
	 * @deserialize
	 * If true, CData section is removed from element value
	 */
	private bool $cdata;

	/**
	 * @serialize
	 * @deserialize
	 *
	 * Callable for custom value conversion
	 *
	 * @var callable|null
	 */
	private $callable;

	/**
	 * @serialize
	 *
	 * Namespace prefix
	 */
	private ?string $prefix;


	public function __construct(
		?string $name = NULL,
		bool $ignoreNull = FALSE,
		string $dateFormat = 'd.m.Y h:i:s',
		bool $cdata = FALSE,
		?callable $callable = NULL,
		?string $prefix = NULL
	)
	{
		$this->name = $name;
		$this->ignoreNull = $ignoreNull;
		$this->dateFormat = $dateFormat;
		$this->cdata = $cdata;
		$this->callable = $callable;
		$this->prefix = $prefix;
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

	public function getPrefix(): ?string
	{
		return $this->prefix;
	}
}
