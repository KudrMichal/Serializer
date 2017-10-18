<?php

namespace KudrMichal\Xml\Tests\Serialization;

include __DIR__ . "/../../bootstrap.php";
include __DIR__ . "/classes/TestClass.php";
include __DIR__ . "/classes/TestSubClass.php";
include __DIR__ . "/classes/TestSubClass2.php";
include __DIR__ . "/classes/TestDeserializationClass.php";
include __DIR__ . "/classes/TestDeserializationSubClass.php";

use KudrMichal\Annotations\Reader;
use KudrMichal\Xml\Serialization\Serializer;
use KudrMichal\Xml\Tests\Serialization\Classes\TestClass;
use KudrMichal\Xml\Tests\Serialization\Classes\TestDeserializationClass;
use KudrMichal\Xml\Tests\Serialization\Classes\TestDeserializationSubClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * Class SerializerTest
 *
 * Serialization a deserialization test
 *
 * @author Michal
 * @package KudrMichal\Xml\Tests\Serialization
 */
class SerializerTest extends TestCase {

    const TEST_CLASS_XML_FILE = "TestDeserializationClass.xml";

    /** @var Serializer */
    private $serializer;

    /**
     * SerializerTest constructor.
     */
    public function __construct() {
        $this->serializer = new Serializer(new Reader());
    }

    /**
     * Test object to xml string serialization
     * @return void
     */
    public function testSerialization() {

        $testClass = new TestClass();
        $xml = $this->serializer->serialize($testClass);

        Assert::true($xml instanceof \DOMDocument);

        $xPath = new \DOMXPath($xml);

        //$xml->formatOutput = true;
        file_put_contents("TestClass.xml", $xml->saveXML());

        Assert::same(12, $xPath->query("/TestClass/*")->length);
        Assert::true($xPath->evaluate("boolean(/TestClass/@attr)"));
        Assert::true($xPath->evaluate("boolean(/TestClass[@attr='attributeTest'])"));
        Assert::true($xPath->evaluate("boolean(/TestClass/numTest)"));
        Assert::same(123, intval($xPath->evaluate("string(/TestClass/numTest/text())")));
        Assert::true($xPath->evaluate("boolean(/TestClass/stringTest)"));
        Assert::same("stringTest", $xPath->evaluate("string(/TestClass/stringTest/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/nullTest)"));
        Assert::same('', $xPath->evaluate("string(/TestClass/nullTest/text())"));
        Assert::false($xPath->evaluate("boolean(/TestClass/ignoreNullTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/datetimeTest)"));
        Assert::same("2017-10-01 10:20:30", $xPath->evaluate("string(/TestClass/datetimeTest/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/TestSubElement)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/TestSubElement/test)"));
        Assert::same("abc", $xPath->evaluate("string(/TestClass/TestSubElement/test/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/assocArrayTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/assocArrayTest/key1)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/assocArrayTest/key2)"));
        Assert::same("key1Value", $xPath->evaluate("string(/TestClass/assocArrayTest/key1/text())"));
        Assert::same("key2Value", $xPath->evaluate("string(/TestClass/assocArrayTest/key2/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/unassociativeArrayTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/unassociativeArrayTest/value)"));
        Assert::same(3, $xPath->query("/TestClass/unassociativeArrayTest/value")->length);
        Assert::same("value1", $xPath->evaluate("string(/TestClass/unassociativeArrayTest/value[1]/text())"));
        Assert::same("value2", $xPath->evaluate("string(/TestClass/unassociativeArrayTest/value[2]/text())"));
        Assert::same("value3", $xPath->evaluate("string(/TestClass/unassociativeArrayTest/value[3]/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/objectArrayTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/objectArrayTest/TestClass)"));
        Assert::same(2, $xPath->query("/TestClass/objectArrayTest/TestClass")->length);
        Assert::same("abc", $xPath->evaluate("string(/TestClass/objectArrayTest/TestClass[1]/test/text())"));
        Assert::same("abc", $xPath->evaluate("string(/TestClass/objectArrayTest/TestClass[2]/test/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/keyNamedObjectArrayTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/keyNamedObjectArrayTest/TestSubElement)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/keyNamedObjectArrayTest/TestSubElement/test)"));
        Assert::same("abc", $xPath->evaluate("string(/TestClass/keyNamedObjectArrayTest/TestSubElement/test/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/keyNamedObjectArrayTest/TestSubClass2)"));
        Assert::same("xyz", $xPath->evaluate("string(/TestClass/keyNamedObjectArrayTest/TestSubClass2/t2/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/classNamedObjectArrayTest)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/classNamedObjectArrayTest/TestSubElement)"));
        Assert::true($xPath->evaluate("boolean(/TestClass/classNamedObjectArrayTest/TestSubElement/test)"));
        Assert::same("abc", $xPath->evaluate("string(/TestClass/classNamedObjectArrayTest/TestSubElement/test/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/classNamedObjectArrayTest/TestSubClass2)"));
        Assert::same("xyz", $xPath->evaluate("string(/TestClass/classNamedObjectArrayTest/TestSubClass2/t2/text())"));
        Assert::true($xPath->evaluate("boolean(/TestClass/sibling)"));
        Assert::same(2, $xPath->query("/TestClass/sibling")->length);
        Assert::same("siblingText", $xPath->evaluate("string(/TestClass/sibling[1]/text())"));
        Assert::same("siblingText2", $xPath->evaluate("string(/TestClass/sibling[2]/text())"));



        //Assert::true(FALSE);

    }

    /**
     * Test xml string to object deserialization
     * @return void
     */
    public function testDeserialization() {
        /** @var TestDeserializationClass */
        $object = $this->serializer->deserialize(file_get_contents(self::TEST_CLASS_XML_FILE), TestDeserializationClass::class);
//var_dump($object);
        Assert::true($object instanceof TestDeserializationClass);
        Assert::true(is_int($object->intTest));
        Assert::same(123, $object->intTest);
        Assert::true(is_string($object->stringTest));
        Assert::same("stringTest", $object->stringTest);
        Assert::true(is_bool($object->boolTest));
        Assert::true($object->boolTest);
        Assert::true(is_bool($object->boolTest2));
        Assert::true($object->boolTest2);
        Assert::true($object->dateTest instanceof \DateTime);
        Assert::same("2017-10-01 10:20:30", $object->dateTest->format("Y-m-d h:i:s"));
        Assert::true(is_array($object->dates));
        Assert::same(2, count($object->dates));
        foreach($object->dates as $date) {
            Assert::true($date instanceof \DateTime);
        }

        Assert::true($object->subClass instanceof TestDeserializationSubClass);
        Assert::same("abc", $object->subClass->test);

        Assert::same("test", $object->emptyTest);
        Assert::same("", $object->emptyTest2);

        Assert::true(is_array($object->subClasses));
        Assert::same(2, count($object->subClasses));

        Assert::same("abc", $object->subClasses[0]->test);
        Assert::same("xyz", $object->subClasses[1]->test);

        Assert::true(is_array($object->floatArray));
        Assert::true(is_float($object->floatArray[0]));
        Assert::true(is_float($object->floatArray[1]));
        Assert::true(is_float($object->floatArray[2]));
        Assert::same(1.1, $object->floatArray[0]);
        Assert::same(1.2, $object->floatArray[1]);
        Assert::same(1.3, $object->floatArray[2]);

        Assert::true(is_array($object->siblings));
        Assert::true(is_int($object->siblings[0]));
        Assert::true(is_int($object->siblings[1]));
        Assert::true(is_int($object->siblings[2]));
        Assert::same(1, $object->siblings[0]);
        Assert::same(2, $object->siblings[1]);
        Assert::same(3, $object->siblings[2]);
        //Assert::true(FALSE);
    }

}

(new SerializerTest())->run();