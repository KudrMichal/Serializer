<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Xml\Classes;

use KudrMichal\Serializer\Xml\Metadata as XML;

#[XML\Document(name:"test")]
class Test
{
	#[XML\Element(name:"name")]
	private string $name = "jatrovka";

	#[XML\Attribute(name:"age")]
	private int $age = 20;

	#[XML\ElementArray(name:"nicknames", itemName:"nickname", type:"string")]
	private array $nicknames = ['jouda', 'lulin'];

	#[XML\Element]
	private Nicknames $nestedNicknames;

	#[XML\Element]
	private TestChild $testChild;

	#[XML\Element(dateFormat:"Y-m-d")]
	public ?\DateTime $birthday = NULL;


	public function __construct()
	{
		$this->testChild = new TestChild();
		$this->birthday = new \DateTime('2020-01-01');
		$this->nestedNicknames = new Nicknames();
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getAge(): int
	{
		return $this->age;
	}


	public function getNicknames(): array
	{
		return $this->nicknames;
	}


	public function getTestChild(): TestChild
	{
		return $this->testChild;
	}


	public function getBirthday(): ?\DateTime
	{
		return $this->birthday;
	}
}
