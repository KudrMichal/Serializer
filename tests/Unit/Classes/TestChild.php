<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Tests\Unit\Classes;

use KudrMichal\XmlSerialize\Metadata as XML;

/**
 * @XML\Document(name="test")
 */
class TestChild
{
	/**
	 * @XML\Element
	 */
	private string $childName = 'child1';
}
