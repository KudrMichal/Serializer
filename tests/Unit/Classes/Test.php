<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Tests\Unit\Classes;

use KudrMichal\XmlSerialize\Metadata as XML;

/**
 * @XML\Document(name="test")
 */
class Test
{
	/**
	 * @XML\Element(name="name")
	 */
	private string $name = "jatrovka";

	/**
	 * @XML\Attribute(name="age")
	 */
	private int $age = 20;

	/**
	 * @XML\ElementArray(name="nicknames", itemName="nickname")
	 */
	private array $nicknames = ['jouda', 'lulin'];

	/**
	 * @XML\Element
	 */
	private TestChild $testChild;

	/**
	 * @XML\Element(dateFormat="Y-m-d")
	 */
	private \DateTime $birthday;


	public function __construct()
	{
		$this->testChild = new TestChild();
		$this->birthday = new \DateTime('2020-01-01');
	}
}
