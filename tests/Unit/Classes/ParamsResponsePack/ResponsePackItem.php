<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class ResponsePackItem
{
	#[XML\ElementArray(name:"listIntParam", itemName:"intParamDetail", type:"KudrMichal\Serializer\Unit\Classes\ParamsResponsePack\IntParamDetail")]
	private array $parameters;


	/**
	 * @return IntParamDetail[]
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}
}
