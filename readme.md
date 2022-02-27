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

JSON Usage
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

    public function __construct(
        int $testInteger,
        string $testString,
        bool $testBoolean,
        array $testArray,		
        TestObject $testObject,
        array $testObjectsArray,
    )
    {
        $this->testInteger = $testInteger;
        $this->testString = $testString;
        $this->testBoolean = $testBoolean;
        $this->testArray = $testArray;
        $this->testObjectsArray = $testObjectsArray;
        $this->testObject = $testObject;
    }

    //getters, setters, etc.
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
    
    //getters, setters, etc.
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
    new TestObject(11, 'object string test', FALSE, [5,6,7,8]),
    [
        new TestObject(12, "array object string test", false, [10,11,12]),
        new TestObject(13, "array object string test 2", true, [13,14,15]),
    ],
);

$serializer = new \KudrMichal\Serializer\Json\Serializer();
$json = $serializer->serialize($object);
```

XML Usage
------------

Let's create another two test classes

```
use KudrMichal\Serializer\Unit\Xml\Classes\TestObject;
use KudrMichal\Serializer\Xml\Metadata as XML;

#[XML\Document(name:"test")]
class Test
{
    #[XML\Element(name:"testInteger")]
    private int $testInt;

    #[XML\Attribute(name:"testAttributeInt")]
    private int $testAttributeInteger;
    
    #[XML\Element]
    private string $testString;
    
    #[XML\Element]
    private bool $testBoolean;
    
    #[XML\Element(dateFormat: "Y-m-d")]
    private \DateTimeImmutable $testDate;
    
    #[XML\Elements(name: "testArrayItem", type: "int")]
    private array $testArray;
    
    #[XML\ElementArray(type:"int", itemName: "testNestedArrayItem")]
    private array $testNestedArray;
    
    #[XML\Element]
    private TestObject $testObject;
    
    #[XML\ElementArray(type: TestObject::class, itemName: "testObject")]
    private array $testObjectNestedArray;


    public function __construct(
        int $testInt,
        int $testAttributeInteger,
        string $testString,
        bool $testBoolean,
        \DateTimeImmutable $testDate,
        array $testArray,
        array $testNestedArray,
        TestObject $testObject,
        array $testObjectNestedArray
    )
    {
        $this->testInt = $testInt;
        $this->testAttributeInteger = $testAttributeInteger;
        $this->testString = $testString;
        $this->testBoolean = $testBoolean;
        $this->testDate = $testDate;
        $this->testArray = $testArray;
        $this->testNestedArray = $testNestedArray;
        $this->testObject = $testObject;
        $this->testObjectNestedArray = $testObjectNestedArray;
    }
    
    //getters, setters, etc.
}

use KudrMichal\Serializer\Xml\Metadata as XML;

class TestObject
{
    #[XML\Element(name:"testInteger")]
    private int $testObjectInt;
    
    #[XML\Attribute(name:"testAttributeInt", ignoreNull: true)]
    private ?int $testObjectAttributeInt;
    
    #[XML\Element(ignoreNull: true)]
    private ?string $testObjectString;
    
    public function __construct(int $testObjectInt, ?int $testObjectAttributeInt = NULL, ?string $testObjectString = NULL)
    {
        $this->testObjectInt = $testObjectInt;
        $this->testObjectAttributeInt = $testObjectAttributeInt;
        $this->testObjectString = $testObjectString;
    }

    //getters, setters, etc.
}
```

PHP object serializing to \DOMDocument


```
$test = new Test(
    321,
    123,
    '321',
    true,
    new \DateTimeImmutable('2022-02-22'),
    [1,2,3],
    [3,2,1],
    new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(9, 10, 'test'),
    [
        new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(5),
        new \KudrMichal\Serializer\Unit\Xml\Classes\TestObject(6, testObjectString: 'true'),
    ]
);

$serializer = new \KudrMichal\Serializer\Xml\Serializer();
$doc = $serializer->serialize($test);
```

XML string serializing to PHP object

```
$xml = <<<XML
<test testAttributeInt="123">
    <testInteger>321</testInteger>
    <testString>321</testString>
    <testBoolean>1</testBoolean>
    <testDate>2022-02-22</testDate>
    <testArrayItem>1</testArrayItem>
    <testArrayItem>2</testArrayItem>
    <testArrayItem>3</testArrayItem>
    <testNestedArray>
        <testNestedArrayItem>3</testNestedArrayItem>
        <testNestedArrayItem>2</testNestedArrayItem>
        <testNestedArrayItem>1</testNestedArrayItem>
    </testNestedArray>
    <testObject testAttributeInt="10">
        <testInteger>9</testInteger>
        <testObjectString>test</testObjectString>
    </testObject>
    <testObjectNestedArray>
        <testObject>
            <testInteger>5</testInteger>
        </testObject>
        <testObject>
            <testInteger>6</testInteger>
            <testObjectString>true</testObjectString>
        </testObject>
    </testObjectNestedArray>
</test>
XML;

$doc = new \DOMDocument();
$doc->loadXML($xml);

$deserializer = new \KudrMichal\Serializer\Xml\Deserializer();
$test = $deserializer->deserialize($doc, \KudrMichal\Serializer\Tests\Unit\Xml\Classes\Test::class);
```
