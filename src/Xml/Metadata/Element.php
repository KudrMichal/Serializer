<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

/**
 * @Annotation
 */
class Element
{
	public ?string $name = NULL;
	public bool $ignoreNull = FALSE;
	public string $dateFormat = 'd.m.Y h:i:s';
}
