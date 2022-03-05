<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Json;

class DeserializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getJson
	 */
	public function testDeserialize(string $json): void
	{
		$deserializer = new \KudrMichal\Serializer\Json\Deserializer();

		/** @var \KudrMichal\Serializer\Unit\Json\Classes\Test $test */
		$test = $deserializer->deserialize(\KudrMichal\Serializer\Unit\Json\Classes\Test::class, $json);

		$this->assertSame(10, $test->getTestInteger());
		$this->assertSame('string test', $test->getTestString());
		$this->assertSame('Michal', $test->getTestCallable()->getFirstname());
		$this->assertSame('Kudr', $test->getTestCallable()->getLastname());
		$this->assertTrue($test->isTestBoolean());
		$this->assertSame([1,2,3,4], $test->getTestArray());
		$this->assertSame([4,3,2,1], $test->getTestArray2());
		$this->assertCount(2, $test->getTestObjectsArray());
		$this->assertSame(12, $test->getTestObjectsArray()[0]->getTestObjectInt());
		$this->assertSame(13, $test->getTestObjectsArray()[1]->getTestObjectInt());
		$this->assertSame('array object string test', $test->getTestObjectsArray()[0]->getTestObjectString());
		$this->assertSame('array object string test 2', $test->getTestObjectsArray()[1]->getTestObjectString());
		$this->assertFalse($test->getTestObjectsArray()[0]->isTestObjectBoolean());
		$this->assertTrue($test->getTestObjectsArray()[1]->isTestObjectBoolean());
		$this->assertSame([10,11,12], $test->getTestObjectsArray()[0]->getTestObjectArray());
		$this->assertSame([13,14,15], $test->getTestObjectsArray()[1]->getTestObjectArray());
		$this->assertSame(11, $test->getTestObject()->getTestObjectInt());
		$this->assertSame('object string test', $test->getTestObject()->getTestObjectString());
		$this->assertFalse($test->getTestObject()->isTestObjectBoolean());
		$this->assertSame([5,6,7,8], $test->getTestObject()->getTestObjectArray());
	}


	public function getJson(): array
	{
		return [[file_get_contents(__DIR__ . '/Data/test.json')]];
	}
}
