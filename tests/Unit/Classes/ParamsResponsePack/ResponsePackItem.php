<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack;

use KudrMichal\XmlSerialize\Metadata as XML;

class ResponsePackItem
{
	/**
	 * @XML\Element(name="listIntParam")
	 */
	private ListIntParam $listIntParam;


	public function getListIntParam(): ListIntParam
	{
		return $this->listIntParam;
	}
}
