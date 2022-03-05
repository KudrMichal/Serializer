<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ElementArray
{
	/**
	 * @serialize
	 * @deserialize
	 *
	 * Xml element tag name. If null, php object property name is used
	 */
	private ?string $name = NULL;

	/**
	 * @serialize
	 * @deserialize
	 *
	 * Xml element children tag name.
	 */
	private ?string $itemName = NULL;

	/**
	 * @deserialize
	 *
	 * PHP type for xml element children
	 */
	private ?string $type = NULL;

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
		?string $itemName = NULL,
		?string $type = NULL,
		?callable $callable = NULL
	)
	{
		$this->name = $name;
		$this->itemName = $itemName;
		$this->type = $type;
		$this->callable = $callable;
	}


	public function getName(): ?string
	{
		return $this->name;
	}


	public function getItemName(): ?string
	{
		return $this->itemName;
	}


	public function getType(): ?string
	{
		return $this->type;
	}

	public function getCallable(): ?callable
	{
		return $this->callable;
	}
}
