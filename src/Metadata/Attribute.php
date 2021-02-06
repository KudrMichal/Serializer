<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Metadata;

/**
 * @Annotation
 */
class Attribute
{
	public ?string $name = NULL;
	public ?string $dateFormat = 'd.m.Y';
}
