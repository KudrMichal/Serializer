<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Json\Classes;

use KudrMichal\Serializer\Json\Metadata as JSON;

class Test
{
	#[JSON\Property(name:"testInt")]
	private int $testInteger;

	#[JSON\Property]
	private string $testString;

	#[JSON\Property]
	private bool $testBoolean;

	#[JSON\PropertyArray]
	private array $testArray;

	#[JSON\PropertyArray(name:"testReversedArray", type:"int")]
	private array $testArray2;

	#[JSON\PropertyArray(type:"KudrMichal\Serializer\Unit\Json\Classes\TestObject")]
	private array $testObjectsArray;

	#[JSON\Property]
	private TestObject $testObject;


	public function getTestInteger(): int
	{
		return $this->testInteger;
	}

	public function getTestString(): string
	{
		return $this->testString;
	}

	public function isTestBoolean(): bool
	{
		return $this->testBoolean;
	}

	public function getTestArray(): array
	{
		return $this->testArray;
	}

	public function getTestArray2(): array
	{
		return $this->testArray2;
	}

	/**
	 * @return TestObject[]
	 */
	public function getTestObjectsArray(): array
	{
		return $this->testObjectsArray;
	}

	public function getTestObject(): TestObject
	{
		return $this->testObject;
	}
}
