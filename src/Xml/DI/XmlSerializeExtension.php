<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\DI;

class SerializerExtension extends \Nette\DI\CompilerExtension
{
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$readerDefinition = $builder->addDefinition($this->prefix('annotationReader'))
			->setFactory(\Doctrine\Common\Annotations\AnnotationReader::class)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('serializer'))
			->setFactory(\KudrMichal\Serializer\Xml\Serializer::class, [$readerDefinition])
		;

		$builder->addDefinition($this->prefix('deserializer'))
			->setFactory(\KudrMichal\Serializer\Xml\Deserializer::class, [$readerDefinition])
		;
	}
}
