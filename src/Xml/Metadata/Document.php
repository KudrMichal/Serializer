<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Document
{
	private ?string $name = NULL;
	private string $version;
	private ?string $encoding;


	public function __construct(?string $name = NULL, string $version = '1.0', ?string $encoding = 'utf-8')
	{
		$this->name = $name;
		$this->version = $version;
		$this->encoding = $encoding;
	}


	public function getName(): ?string
	{
		return $this->name;
	}


	public function getVersion(): string
	{
		return $this->version;
	}


	public function getEncoding(): ?string
	{
		return $this->encoding;
	}
}
