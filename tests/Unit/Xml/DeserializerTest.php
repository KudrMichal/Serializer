<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Xml;

class DeserializerTest extends \PHPUnit\Framework\TestCase
{

	public function testDeserializePohodaParams(): void
	{
		$doc = new \DOMDocument();
		$doc->load(__DIR__ . '/Data/parametry.xml');

		$deserializer = new \KudrMichal\Serializer\Xml\Deserializer();

		/** @var \KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ResponsePack $responsePack */
		$responsePack = $deserializer->deserialize($doc, \KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ResponsePack::class);

		$this->assertTrue($responsePack instanceof \KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ResponsePack);
		$this->assertCount(1, $responsePack->getResponsePackItems());
		$responsePackItem = $responsePack->getResponsePackItems()[0];
		$this->assertTrue($responsePackItem instanceof \KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ResponsePackItem);

		$this->assertCount(7, $responsePackItem->getParameters());
		$nfc = $responsePackItem->getParameters()[0];
		$this->assertSame(1, $nfc->getIntParam()->getId());
		$this->assertSame('NFC', $nfc->getIntParam()->getName());
		$this->assertSame('booleanValue', $nfc->getIntParam()->getParameterType());
		$this->assertSame('', $nfc->getIntParam()->getDescription());
		$this->assertNull($nfc->getIntParam()->getParameterSettings());

		$ram = $responsePackItem->getParameters()[6];
		$this->assertSame(8, $ram->getIntParam()->getId());
		$this->assertSame('Operační paměť', $ram->getIntParam()->getName());
		$this->assertSame('listValue', $ram->getIntParam()->getParameterType());
		$this->assertSame('RAM', $ram->getIntParam()->getDescription());
		$this->assertInstanceOf(\KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ParameterSettings::class, $ram->getIntParam()->getParameterSettings());
		$this->assertCount(4, $ram->getIntParam()->getParameterSettings()->getParameterListItems());
		$this->assertSame(1, $ram->getIntParam()->getParameterSettings()->getParameterListItems()[0]->getId());
		$this->assertSame('16 GB', $ram->getIntParam()->getParameterSettings()->getParameterListItems()[0]->getName());
		$this->assertSame('', $ram->getIntParam()->getParameterSettings()->getParameterListItems()[0]->getDescription());
		$this->assertSame(1, $ram->getIntParam()->getParameterSettings()->getParameterListItems()[0]->getSequence());
	}

	/**
	 * @dataProvider getDomDocument
	 */
	public function testDeserialize(\DOMDocument $document): void
	{
		$deserializer = new \KudrMichal\Serializer\Xml\Deserializer();

		/** @var \KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test $test */
		$test = $deserializer->deserialize($document, \KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test::class);

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