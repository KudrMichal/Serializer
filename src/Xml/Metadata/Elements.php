<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Elements
{
	private ?string $name;
	private ?string $type;


	public function __construct(?string $name = NULL, ?string $type = NULL)
	{
		$this->name = $name;
		$this->type = $type;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getType(): ?string
	{
		return $this->type;
	}
}
