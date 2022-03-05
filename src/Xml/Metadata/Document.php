<?php declare(strict_types = 1);

namespace KudrMichal\Serializer\Xml\Metadata;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Document
{
	public const VERSION_1_0 = '1.0';

	public const ENCODING_UTF_8 = 'utf-8';
	public const ENCODING_UTF_16 = 'utf-16';
	public const ENCODING_ISO_10646_UCS_2 = 'iso-10646-ucs-2';
	public const ENCODING_ISO_10646_UCS_4 = 'iso-10646-ucs-4';
	public const ENCODING_ISO_8859_1 = 'iso-8859-1';
	public const ENCODING_ISO_8859_2 = 'iso-8859-2';
	public const ENCODING_ISO_8859_3 = 'iso-8859-3';
	public const ENCODING_ISO_8859_4 = 'iso-8859-4';
	public const ENCODING_ISO_8859_5 = 'iso-8859-5';
	public const ENCODING_ISO_8859_6 = 'iso-8859-6';
	public const ENCODING_ISO_8859_7 = 'iso-8859-7';
	public const ENCODING_ISO_8859_8 = 'iso-8859-8';
	public const ENCODING_ISO_8859_9 = 'iso-8859-9';
	public const ENCODING_ISO_2022_JP = 'iso-2022-jp';
	public const ENCODING_SHIFT_JIS = 'shift_jis';

	/**
	 * @serialize
	 * @deserialize
	 *
	 * Xml root element tag name. If null, php object property name is used
	 */
	private ?string $name;

	/**
	 * @serialize
	 *
	 * Xml declaration version
	 */
	private string $version;

	/**
	 * @serialize
	 *
	 * Xml declaration encoding, if '', encoding is omitted
	 */
	private string $encoding;

	/**
	 * @serialize
	 *
	 * Xml declaration standalone, if null, standalone is omitted
	 */
	private ?bool $standalone;


	public function __construct(
		?string $name = NULL,
		string $version = self::VERSION_1_0,
		string $encoding = '',
		?bool $standalone = NULL
	)
	{
		$this->name = $name;
		$this->version = $version;
		$this->encoding = $encoding;
		$this->standalone = $standalone;
	}


	public function getName(): ?string
	{
		return $this->name;
	}


	public function getVersion(): string
	{
		return $this->version;
	}


	public function getEncoding(): ?string
	{
		return $this->encoding;
	}

	public function getStandalone(): ?bool
	{
		return $this->standalone;
	}
}
