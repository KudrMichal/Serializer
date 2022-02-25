<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Unit\Classes\ParamsResponsePack;

use KudrMichal\Serializer\Xml\Metadata as XML;

class IntParam
{
	public const BOOLEAN = 'booleanValue';
	public const NUMBER = 'numberValue';
	public const LIST = 'listValue';

	/**
	 * @XML\Element
	 */
	private int $id;

	/**
	 * @XML\Element
	 */
	private string $name;

	/**
	 * @XML\Element
	 */
	private ?string $description = NULL;

	/**
	 * @XML\Element
	 */
	private string $parameterType;

	/**
	 * @XML\Element
	 */
	private ?string $note;

	/**
	 * @XML\Element
	 */
	private ?ParameterSettings $parameterSettings = NULL;


	public function getId(): int
	{
		return $this->id;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getDescription(): ?string
	{
		return $this->description;
	}


	public function getParameterType(): string
	{
		return $this->parameterType;
	}


	public function getNote(): ?string
	{
		return $this->note;
	}


	public function getParameterSettings(): ?ParameterSettings
	{
		return $this->parameterSettings;
	}
}
