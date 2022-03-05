<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ElementArray
{
	private ?string $name = NULL;
	private ?string $itemName = NULL;
	private ?string $type = NULL;
	/**
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
