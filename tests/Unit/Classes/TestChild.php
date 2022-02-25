<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Tests\Unit\Classes;

use KudrMichal\Serializer\Xml\Metadata as XML;

/**
 * @XML\Document(name="test")
 */
class TestChild
{
	/**
	 * @XML\Element
	 */
	private string $childName = 'child1';


	public function getChildName(): string
	{
		return $this->childName;
	}
}
