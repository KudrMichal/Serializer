<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Xml\Classes;

use KudrMichal\Serializer\Unit\Xml\Classes\TestArrayStringAdapter;
use KudrMichal\Serializer\Unit\Xml\Classes\TestObject;
use KudrMichal\Serializer\Xml\Metadata as XML;

#[XML\Document(
	name:"test",
	encoding: XML\Document::ENCODING_ISO_8859_1,
	standalone: true,
	namespaces: [
		'typ' => 'http://test.cz',
	],
)]
class Test
{
	#[XML\Element(name:"testInteger", prefix: 'typ')]
	private int $testInt;

	#[XML\Attribute(name:"testAttributeInt")]
	private int $testAttributeInteger;

	#[XML\Element]
	private string $testString;

	#[XML\Element(callable: [TestArrayStringAdapter::class, 'convert'])]
	private array $testStringArrayAdapter;

	#[XML\Element]
	private bool $testBoolean;

	#[XML\Element(dateFormat: "Y-m-d")]
	private \DateTimeImmutable $testDate;

	#[XML\Elements(name: "testArrayItem", type: "int")]
	private array $testArray;

	#[XML\ElementArray(type:"int", itemName: "testNestedArrayItem")]
	private array $testNestedArray;

	#[XML\Element]
	private TestObject $testObject;

	#[XML\ElementArray(type: TestObject::class, itemName: "testObject")]
	private array $testObjectNestedArray;


	public function __construct(
		int $testInt,
		int $testAttributeInteger,
		string $testString,
		array $testStringArrayAdapter,
		bool $testBoolean,
		\DateTimeImmutable $testDate,
		array $testArray,
		array $testNestedArray,
		TestObject $testObject,
		array $testObjectNestedArray
	)
	{
		$this->testInt = $testInt;
		$this->testAttributeInteger = $testAttributeInteger;
		$this->testString = $testString;
		$this->testStringArrayAdapter = $testStringArrayAdapter;
		$this->testBoolean = $testBoolean;
		$this->testDate = $testDate;
		$this->testArray = $testArray;
		$this->testNestedArray = $testNestedArray;
		$this->testObject = $testObject;
		$this->testObjectNestedArray = $testObjectNestedArray;
	}


	public function getTestInt(): int
	{
		return $this->testInt;
	}

	public function getTestAttributeInteger(): int
	{
		return $this->testAttributeInteger;
	}

	public function getTestString(): string
	{
		return $this->testString;
	}

	public function getTestStringArrayAdapter(): array
	{
		return $this->testStringArrayAdapter;
	}

	public function getTestBoolean(): bool
	{
		return $this->testBoolean;
	}

	public function getTestDate(): \DateTimeImmutable
	{
		return $this->testDate;
	}

	public function getTestArray(): array
	{
		return $this->testArray;
	}

	public function getTestNestedArray(): array
	{
		return $this->testNestedArray;
	}

	public function getTestObject(): TestObject
	{
		return $this->testObject;
	}

	/**
	 * @return TestObject[]
	 */
	public function getTestObjectNestedArray(): array
	{
		return $this->testObjectNestedArray;
	}
}
