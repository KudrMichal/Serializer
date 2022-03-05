<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Elements
{
	/**
	 * @serialize
	 * @deserialize
	 *
	 * Xml element tag name. If null, php object property name is used
	 */
	private ?string $name;

	/**
	 * @deserialize
	 *
	 * PHP type for xml element children
	 */
	private ?string $type;

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
		?string $type = NULL,
		?callable $callable = NULL,
		?string $prefix = NULL,
	)
	{
		$this->name = $name;
		$this->type = $type;
		$this->callable = $callable;
		$this->prefix = $prefix;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getType(): ?string
	{
		return $this->type;
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
