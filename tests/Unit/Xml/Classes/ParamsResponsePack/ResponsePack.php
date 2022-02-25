<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

#[XML\Document(name:"responsePack")]
class ResponsePack
{
	#[XML\Elements(name:"responsePackItem", type:"KudrMichal\Serializer\Unit\Xml\Classes\ParamsResponsePack\ResponsePackItem")]
	private array $paramsResponsePackItems;


	public function getVersion(): string
	{
		return $this->version;
	}


	public function getId(): string
	{
		return $this->id;
	}


	public function getState(): string
	{
		return $this->state;
	}


	public function getProgramVersion(): string
	{
		return $this->programVersion;
	}


	public function getIco(): string
	{
		return $this->ico;
	}


	public function getKey(): string
	{
		return $this->key;
	}


	public function getNote(): string
	{
		return $this->note;
	}


	/**
	 * @return ResponsePackItem[]
	 */
	public function getResponsePackItems(): array
	{
		return $this->paramsResponsePackItems;
	}
}
