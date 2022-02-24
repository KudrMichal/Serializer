<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class ListIntParam
{
	/**
	 * @XML\Attribute
	 */
	private \DateTimeImmutable $dateTimeStamp;

	/**
	 * @XML\Attribute
	 */
	private \DateTimeImmutable $dateValidFrom;

	/**
	 * @XML\Elements(name="intParamDetail", type="KudrMichal\Serializer\Unit\Classes\ParamsResponsePack\IntParamDetail")
	 * @var IntParamDetail[]
	 */
	private array $parameters;


	public function getDateTimeStamp(): \DateTimeImmutable
	{
		return $this->dateTimeStamp;
	}


	public function getDateValidFrom(): \DateTimeImmutable
	{
		return $this->dateValidFrom;
	}


	public function getParameters(): array
	{
		return $this->parameters;
	}
}
