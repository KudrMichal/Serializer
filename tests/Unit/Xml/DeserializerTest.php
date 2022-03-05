<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Xml;

use KudrMichal\Serializer\Unit\Xml\Classes\TestObject;
use KudrMichal\Serializer\Xml\Deserializer;

class DeserializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getDomDocument
	 */
	public function testDeserialize(\DOMDocument $doc): void
	{
		/** @var \KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test $test */
		$test = (new Deserializer())->deserialize($doc, \KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test::class);

		$this->assertSame(321, $test->getTestInt());
		$this->assertSame(123, $test->getTestAttributeInteger());
		$this->assertTrue($test->getTestBoolean());
		$this->assertSame('321', $test->getTestString());
		$this->assertSame(['a', 'b', 'c'], $test->getTestStringArrayAdapter());
		$this->assertSame([1, 2, 3], $test->getTestArray());
		$this->assertSame([3, 2, 1], $test->getTestNestedArray());
		$this->assertInstanceOf(TestObject::class, $test->getTestObject());
		$this->assertSame(9, $test->getTestObject()->getTestObjectInt());
		$this->assertSame(10, $test->getTestObject()->getTestObjectAttributeInt());
		$this->assertSame('test', $test->getTestObject()->getTestObjectString());
		$testObjects = $test->getTestObjectNestedArray();
		$this->assertSame(5, $testObjects[0]->getTestObjectInt());
		$this->assertNull($testObjects[0]->getTestObjectAttributeInt());
		$this->assertSame('missing', $testObjects[0]->getTestObjectString());
		$this->assertSame(6, $testObjects[1]->getTestObjectInt());
		$this->assertNull($testObjects[1]->getTestObjectAttributeInt());
		$this->assertSame('true', $testObjects[1]->getTestObjectString());
	}


	public function getDomDocument(): array
	{
		$doc = new \DOMDocument();
		$doc->load(__DIR__ . '/Data/test.xml');

		return [[$doc]];
	}
}
