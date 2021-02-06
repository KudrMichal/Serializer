<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\DI;

class XmlSerializeExtension extends \Nette\DI\CompilerExtension
{
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$readerDefinition = $builder->addDefinition($this->prefix('annotationReader'))
			->setFactory(\Doctrine\Common\Annotations\AnnotationReader::class)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('serializer'))
			->setFactory(\KudrMichal\XmlSerialize\Serializer::class, [$readerDefinition])
		;
	}
}
