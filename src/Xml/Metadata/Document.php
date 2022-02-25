<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Document
{
	private ?string $name = NULL;


	public function __construct(?string $name = NULL)
	{
		$this->name = $name;
	}


	public function getName(): ?string
	{
		return $this->name;
	}
}
