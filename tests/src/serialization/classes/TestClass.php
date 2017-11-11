<?php

namespace KudrMichal\Xml\Tests\Serialization\Classes;

/**
 * Class TestClass
 *
 * Foo class dor serialization testing
 *
 * @author Michal
 * @package KudrMichal\Xml\Tests\Serialization\Classes
 *
 * @XML-Serializable
 * @property-read string $attribute
 * @property-read int $numberTest
 */
class TestClass {

    /**
     * Public for acccesibility test
     * @XML-Attribute attr
     * @var string
     */
    private $attribute = "attributeTest";

    /**
     * Protected for acccesibility test
     * @XML-Element numTest
     * @var integer
     */
    protected $numberTest = 123;

    /**
     * @XML-Element
     * @var string
     */
    public $stringTest = "stringTest";

    /**
     * @XML-Element
     * @XML-IgnoreNull
     */
    public $ignoreNullTest;

    /**
     * @XML-Element
     */
    public $nullTest;

    /**
     * @XML-Element datetimeTest
     * @XML-DateTime Y-m-d h:i:s
     * @var \DateTime
     */
    public $dateTest;

    /**
     * @XML-Element
     * @var TestSubClass
     */
    public $subClass;

    /**
     * @XML-Element assocArrayTest
     * @var string[]
     */
    public $associativeArrayTest = ["key1" => "key1Value", "key2" => "key2Value"];

    /**
     * @XML-Element
     * @XML-ArrayElement value
     * @var string[]
     */
    public $unassociativeArrayTest = ["value1", "value2", "value3"];

    /**
     * @XML-Element
     * @XML-ArrayElement TestClass
     * @var TestSubClass[]
     */
    public $objectArrayTest = [];

    /**
     * Test child element name by array key
     * @XML-Element
     * @XML-ArrayObjectName key
     * @var TestSubClass[]
     */
    public $keyNamedObjectArrayTest = [];

    /**
     * Test child element name by object
     * @XML-Element
     * @XML-ArrayObjectName class
     * @var TestSubClass[]
     */
    public $classNamedObjectArrayTest = [];

    /**
     * Test serialize array to same element
     * @XML-Elements
     * @XML-ArrayElement sibling
     * @var array
     */
    public $siblingsArray = ["siblingKey" => "siblingText", "siblingKey2" => "siblingText2"];



    /**
     * TestClass constructor.
     * Creates test objects
     */
    public function __construct() {
        $this->dateTest = new \DateTime("1.10.2017 10:20:30");

        $this->subClass = new TestSubClass();

        $this->objectArrayTest[] = new TestSubClass();
        $this->objectArrayTest[] = new TestSubClass();

        $this->keyNamedObjectArrayTest = [
            "objectKey1" => new TestSubClass(),
            "objectKey2" => new TestSubClass(),
            "objectKey2" => new TestSubClass2()
        ];

        $this->classNamedObjectArrayTest = [
            "ignoredKey1" => new TestSubClass(),
            "ignoredKey2" => new TestSubClass2()
        ];




    }

    public function __get($property) {
        return $this->$property;
    }
}