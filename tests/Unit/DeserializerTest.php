<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Tests\Unit;

class DeserializerTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @dataProvider getDomDocument
	 */
	public function testDeserialize(\DOMDocument $document): void
	{
		$deserializer = new \KudrMichal\XmlSerialize\Deserializer(new \Doctrine\Common\Annotations\AnnotationReader());

		/** @var \KudrMichal\XmlSerialize\Tests\Unit\Classes\Test $test */
		$test = $deserializer->deserialize($document, \KudrMichal\XmlSerialize\Tests\Unit\Classes\Test::class);

		$this->assertSame('jatrovka 2', $test->getName());
		$this->assertSame(10, $test->getAge());
		$this->assertSame(['jouda 2', 'lulin 2'], $test->getNicknames());
		$this->assertSame('deserialized child', $test->getTestChild()->getChildName());
		$this->assertSame('2020-01-01', $test->getBirthday()->format('Y-m-d'));
	}


	public function getDomDocument(): array
	{

		$doc = new \DOMDocument();
		$doc->loadXML(
			"<?xml version=\"1.0\"?>
	<test age=\"10\">
		<name>jatrovka 2</name>
		<nicknames>
			<nickname>jouda 2</nickname>
			<nickname>lulin 2</nickname>
		</nicknames>
		<testChild>
			<childName>deserialized child</childName>
		</testChild>
		<birthday>2020-01-01</birthday>
	</test>"
		);

		return [[$doc]];
	}
}
