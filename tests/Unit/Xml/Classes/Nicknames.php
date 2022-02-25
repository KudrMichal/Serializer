<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Tests\Unit\Xml\Classes;

use KudrMichal\Serializer\Xml\Metadata as XML;

class Nicknames
{
	#[XML\Elements(name:"nickname")]
	private array $nicknames = ['hola', 'ahoj'];
}
