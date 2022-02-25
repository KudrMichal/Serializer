<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class ParameterListItem
{
	#[XML\Element]
	private int $id;

	#[XML\Element]
	private string $name;

	#[XML\Element]
	private string $description;

	#[XML\Element]
	private int $sequence;


	public function getId(): int
	{
		return $this->id;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getDescription(): string
	{
		return $this->description;
	}


	public function getSequence(): int
	{
		return $this->sequence;
	}
}
