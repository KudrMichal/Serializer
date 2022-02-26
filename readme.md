KudrMichal XML/JSON object mapper
===========

kudrmichal/serializer is a PHP object xml/json mapper for PHP 8.0+


Requirements
------------

kudrmichal/serializer requires PHP 8.0 or higher.

Installation
------------

To install the latest version of `kudrmichal/serializer` use [Composer](https://getcomposer.org).

```
$ composer require kudrmichal/serializer
```

Usage
------------

Let's create two test classes

```
class Test
{
	#[JSON\Property(name:"testInt")]
	private int $testInteger;

	#[JSON\Property]
	private string $testString;

	#[JSON\Property]
	private bool $testBoolean;

	#[JSON\PropertyArray]
	private array $testArray;

	#[JSON\Property]
	private TestObject $testObject;
	
	#[JSON\PropertyArray(type:TestObject::class)]
	private array $testObjectsArray;

	public function getTestInteger(): int
	{
		return $this->testInteger;
	}

	public function getTestString(): string
	{
		return $this->testString;
	}

	public function isTestBoolean(): bool
	{
		return $this->testBoolean;
	}

	public function getTestArray(): array
	{
		return $this->testArray;
	}

	public function getTestObject(): TestObject
	{
		return $this->testObject;
	}
	
	/**
	 * @return TestObject[]
	 */
	public function getTestObjectsArray(): array
	{
		return $this->testObjectsArray;
	}
}


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


	public function __construct(int $testObjectInt, string $testObjectString, bool $testObjectBoolean, array $testObjectArray)
	{
		$this->testObjectInt = $testObjectInt;
		$this->testObjectString = $testObjectString;
		$this->testObjectBoolean = $testObjectBoolean;
		$this->testObjectArray = $testObjectArray;
	}


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

```

JSON string serializing to PHP object

```

$json = <<<JSON
{
  "testInt": 10,
  "testString": "string test",
  "testBoolean": true,
  "testArray": [1,2,3,4],
  "testObject": {
    "testObjectInt": 11,
    "testObjectString": "object string test",
    "testObjectBoolean": false,
    "testObjectArray": [5,6,7,8]
  },
  "testObjectsArray": [
    {
      "testObjectInt": 12,
      "testObjectString": "array object string test",
      "testObjectBoolean": false,
      "testObjectArray": [10,11,12]
    },
    {
      "testObjectInt": 13,
      "testObjectString": "array object string test 2",
      "testObjectBoolean": true,
      "testObjectArray": [13,14,15]
    }
  ]
}
JSON;	

$deserializer = new Deserializer();
$test = $deserializer->deserialize(Test::class, $json);

$test instanceof Test // true
```

PHP object serializing to JSON string

```
$object = new Test(
    10,
    'string test',
    TRUE,
    [1,2,3,4],
    [4,3,2,1],
    [
        new TestObject(12, "array object string test", false, [10,11,12]),
        new TestObject(13, "array object string test 2", true, [13,14,15]),
    ],
    new TestObject(11, 'object string test', FALSE, [5,6,7,8]),
);

$serializer = new \KudrMichal\Serializer\Json\Serializer();
$json = $serializer->serialize($object);
```