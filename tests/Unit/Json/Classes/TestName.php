<?php declare(strict_types=1);

namespace KudrMichal\Serializer\Unit\Json\Classes;

class TestName
{
	public function __construct(private string $firstname, private string $lastname) {}

	public function getFirstname(): string
	{
		return $this->firstname;
	}

	public function getLastname(): string
	{
		return $this->lastname;
	}
}
