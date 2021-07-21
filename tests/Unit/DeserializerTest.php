<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Tests\Unit;

class DeserializerTest extends \PHPUnit\Framework\TestCase
{

	public function testDeserializePohodaParams(): void
	{
		$doc = new \DOMDocument();
		$doc->load(__DIR__ . '/Data/parametry.xml');

		$deserializer = new \KudrMichal\XmlSerialize\Deserializer(new \Doctrine\Common\Annotations\AnnotationReader());

		/** @var \KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\ResponsePack $responsePack */
		$responsePack = $deserializer->deserialize($doc, \KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\ResponsePack::class);

		$this->assertTrue($responsePack instanceof \KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\ResponsePack);
		$this->assertCount(1, $responsePack->getResponsePackItems());
		$responsePackItem = $responsePack->getResponsePackItems()[0];
		$this->assertTrue($responsePackItem instanceof \KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\ResponsePackItem);
		$listItem = $responsePackItem->getListIntParam();
		$this->assertCount(7, $listItem->getParameters());
		$nfc = $listItem->getParameters()[0];
		$this->assertSame(1, $nfc->getId());
		$this->assertSame('NFC', $nfc->getName());
		$this->assertSame('booleanValue', $nfc->getParameterType());
		$this->assertSame('', $nfc->getDescription());
	}

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
