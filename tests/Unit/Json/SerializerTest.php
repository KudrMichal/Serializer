<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Json;

use KudrMichal\Serializer\Json\Metadata\Property;
use KudrMichal\Serializer\Unit\Json\Classes\Test;
use KudrMichal\Serializer\Unit\Json\Classes\TestObject;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getObject
	 */
	public function testSerialize(object $object): void
	{
		$serializer = new \KudrMichal\Serializer\Json\Serializer();

		$json = $serializer->serialize($object);

		$expected = \file_get_contents(__DIR__ . '/Data/test.json');

		$this->assertSame(\json_encode(\json_decode($expected)), $json);
	}


	public function getObject(): array
	{
		$object = new Test(
			10,
			'string test',
			TRUE,
			[1,2,3,4],
			[4,3,2,1],
			[
				new TestObject(12, "array object string test", false, [10,11,12]),
				new TestObject(13, "array object string test 2", true, [13,14,15]),
			],
			new TestObject(11, 'object string test', FALSE, [5,6,7,8]),
		);

		return [[$object]];
	}
}
