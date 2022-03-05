<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Xml;

use KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getObject
	 */
	public function testSerialize(Test $test): void
	{
		$document = (new \KudrMichal\Serializer\Xml\Serializer())->serialize($test);
		$expectedDoc = new \DOMDocument('1.0');
		$expectedDoc->formatOutput = FALSE;
		$expectedDoc->preserveWhiteSpace = FALSE;
		$expectedDoc->load(__DIR__ . '/Data/test.xml', LIBXML_HTML_NODEFDTD);
		$this->assertSame($expectedDoc->saveHTML(), $document->saveHTML());
	}


	public function getObject(): array
	{
		$test = new Test(
			321,
			123,
			'321',
			['a', 'b', 'c'],
			true,
			new \DateTimeImmutable('2022-02-22'),
			[1,2,3],
			[3,2,1],
			new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(9, 10, 'test'),
			[
				new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(5),
				new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(6, testObjectString: 'true'),
			]
		);

		return [[$test]];
	}
}
