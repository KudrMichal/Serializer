<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class ParameterSettings
{
	#[XML\ElementArray(name:"parameterList", itemName:"parameterListItem", type:"KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ParameterListItem")]
	private array $parameterListItems;


	/**
	 * @return ParameterListItem[]
	 */
	public function getParameterListItems(): array
	{
		return $this->parameterListItems;
	}
}
