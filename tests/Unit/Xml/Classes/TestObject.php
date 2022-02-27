<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Xml\Classes;

use KudrMichal\Serializer\Xml\Metadata as XML;

class TestObject
{
	#[XML\Element(name:"testInteger")]
	private int $testObjectInt;

	#[XML\Attribute(name:"testAttributeInt", ignoreNull: true)]
	private ?int $testObjectAttributeInt;

	#[XML\Element(ignoreNull: true)]
	private ?string $testObjectString;


	public function __construct(int $testObjectInt, ?int $testObjectAttributeInt = NULL, ?string $testObjectString = NULL)
	{
		$this->testObjectInt = $testObjectInt;
		$this->testObjectAttributeInt = $testObjectAttributeInt;
		$this->testObjectString = $testObjectString;
	}


	public function getTestObjectInt(): int
	{
		return $this->testObjectInt;
	}

	public function getTestObjectAttributeInt(): ?int
	{
		return $this->testObjectAttributeInt;
	}

	public function getTestObjectString(): ?string
	{
		return $this->testObjectString;
	}
}
