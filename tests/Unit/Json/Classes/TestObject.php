<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Json\Classes;

use KudrMichal\Serializer\Json\Metadata as JSON;

class TestObject
{
	#[JSON\Property]
	private int $testObjectInt;

	#[JSON\Property]
	private string $testObjectString;

	#[JSON\Property]
	private bool $testObjectBoolean;

	#[JSON\PropertyArray]
	private array $testObjectArray;


	public function getTestObjectInt(): int
	{
		return $this->testObjectInt;
	}

	public function getTestObjectString(): string
	{
		return $this->testObjectString;
	}

	public function isTestObjectBoolean(): bool
	{
		return $this->testObjectBoolean;
	}

	/**
	 * @return int[]
	 */
	public function getTestObjectArray(): array
	{
		return $this->testObjectArray;
	}
}
