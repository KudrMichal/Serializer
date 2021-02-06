<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Tests\Unit;

require_once __DIR__ . '/Classes/Test.php';
require_once __DIR__ . '/Classes/TestChild.php';

class SerializerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider getObject
	 */
	public function testSerialize(\KudrMichal\XmlSerialize\Tests\Unit\Classes\Test $test): void
	{
		$serializer = new \KudrMichal\XmlSerialize\Serializer(new \Doctrine\Common\Annotations\AnnotationReader());

		$document = $serializer->serialize($test);

		$expected = "<?xml version=\"1.0\"?>
<test age=\"20\"><name>jatrovka</name><nicknames><nickname>jouda</nickname><nickname>lulin</nickname></nicknames><testChild><childName>child1</childName></testChild><birthday>2020-01-01</birthday></test>
";
	}


	public function getObject(): array
	{
		return [[new \KudrMichal\XmlSerialize\Tests\Unit\Classes\Test()]];
	}
}
