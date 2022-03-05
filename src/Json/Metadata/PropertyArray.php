<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PropertyArray
{
	/**
	 * @serialize
	 * @deserialize
	 *
	 * Json property name. If null, php object property name is used
	 */
	private ?string $name;

	/**
	 * @deserialize
	 *
	 * PHP type for json array items
	 */
	private ?string $type;

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
		string $name = NULL,
		string $type = NULL,
		string $dateFormat = 'd.m.Y h:i:s',
		?callable $callable = NULL,
	)
	{
		$this->name = $name;
		$this->type = $type;
		$this->dateFormat = $dateFormat;
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

	public function getDateFormat(): string
	{
		return $this->dateFormat;
	}

	public function getCallable(): ?callable
	{
		return $this->callable;
	}
}
