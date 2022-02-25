<?php declare(strict_types=1);

namespace KudrMichal\XmlSerialize\Unit\Classes;

use KudrMichal\XmlSerialize\Metadata as XML;

class Nicknames
{
	/**
	 * @XML\Elements(name="nickname")
	 */
	private array $nicknames = ['hola', 'ahoj'];
}
