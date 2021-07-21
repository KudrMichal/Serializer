<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack;

use KudrMichal\XmlSerialize\Metadata as XML;

class ResponsePackItem
{
	/**
	 * @XML\ElementArray(name="listIntParam", itemName="intParamDetail", type="KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\IntParamDetail")
	 * @var IntParamDetail[]
	 */
	private array $parameters;


	/**
	 * @return IntParamDetail[]
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}
}
