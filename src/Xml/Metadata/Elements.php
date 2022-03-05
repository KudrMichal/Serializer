<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Elements
{
	private ?string $name;
	private ?string $type;
	/**
	 * @var callable|null
	 */
	private $callable;


	public function __construct(
		?string $name = NULL,
		?string $type = NULL,
		?callable $callable = NULL)
	{
		$this->name = $name;
		$this->type = $type;
		$this->callable = $callable;
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
}
