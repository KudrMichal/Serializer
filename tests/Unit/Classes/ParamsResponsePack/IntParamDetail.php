<?php declare(strict_types = 1);

namespace KudrMichal\XmlSerialize\Unit\Classes\ParamsResponsePack;

use KudrMichal\XmlSerialize\Metadata as XML;

class IntParamDetail
{
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
	private ?string $description;

	/**
	 * @XML\Element
	 */
	private string $parameterType;

	/**
	 * @XML\Element
	 */
	private ?string $note;


	public function getId(): ?int
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
}
