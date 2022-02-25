<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getObject
	 */
	public function testSerialize(\KudrMichal\Serializer\Tests\Unit\Classes\Test $test): void
	{
		$serializer = new \KudrMichal\Serializer\Xml\Serializer();

		$document = $serializer->serialize($test);

		$expected = "<?xml version=\"1.0\"?>
<test age=\"20\"><name>jatrovka</name><nicknames><nickname>jouda</nickname><nickname>lulin</nickname></nicknames><nestedNicknames><nickname>hola</nickname><nickname>ahoj</nickname></nestedNicknames><testChild><childName>child1</childName></testChild><birthday>2020-01-01</birthday></test>
";

		$this->assertSame($expected, $document->saveXML());
	}


	public function getObject(): array
	{
		return [[new \KudrMichal\Serializer\Tests\Unit\Classes\Test()]];
	}
}
