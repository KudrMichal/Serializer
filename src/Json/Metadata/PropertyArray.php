<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Json\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PropertyArray
{
	private ?string $name;
	private ?string $type;
	private string $dateFormat;
	/**
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
