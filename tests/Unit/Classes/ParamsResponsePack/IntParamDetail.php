<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack;

use KudrMichal\XmlSerialize\Metadata as XML;

class IntParamDetail
{
	/**
	 * @XML\Element
	 */
	private IntParam $intParam;


	public function getIntParam(): IntParam
	{
		return $this->intParam;
	}
}
