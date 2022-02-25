<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class IntParamDetail
{
	#[XML\Element]
	private IntParam $intParam;


	public function getIntParam(): IntParam
	{
		return $this->intParam;
	}
}
