<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack;

use KudrMichal\XmlSerialize\Metadata as XML;

class ParameterSettings
{
	/**
	 * @XML\ElementArray(name="parameterList", itemName="parameterListItem", type="KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack\ParameterListItem")
	 */
	private array $parameterListItems;


	/**
	 * @return ParameterListItem[]
	 */
	public function getParameterListItems(): array
	{
		return $this->parameterListItems;
	}
}
