<?php

namespace KudrMichal\Xml\Tests\Serialization\Classes;

/**
 * Class TestDeserializaionClass
 *
 * Class for deserialization testing
 *
 * @author Michal
 * @package KudrMichal\Xml\Tests\Serialization\Classes
 * @XML-Serializable
 */
class TestDeserializationClass {

    /**
     * @XML-Attribute attr
     * @XML-TargetType string
     * @var string
     */
    private $attrTest;

    /**
     * @XML-Element integerTest
     * @XML-TargetType integer
     * @var int
     */
    public $intTest;

    /**
     * @XML-Element
     * @XML-TargetType string
     * @var string
     */
    public $stringTest;

    /**
     * @XML-Element
     * @XML-TargetType DateTime
     * @var \DateTime
     */
    public $dateTest;

    /**
     * @XML-Element dates
     * @XML-TargetType DateTime[]
     * @var \DateTime[]
     */
    public $dates;

    /**
     * @XML-Element
     * @XML-TargetType boolean
     * @var bool
     */
    public $boolTest;

    /**
     * @XML-Element
     * @XML-TargetType boolean
     * @var bool
     */
    public $boolTest2;

    /**
     * @XML-Element testSubClass
     * @XML-TargetType \KudrMichal\Xml\Tests\Serialization\Classes\TestDeserializationSubClass
     * @var TestDeserializationSubClass
     */
    public $subClass;

    /**
     * @XML-Element
     * @XML-IgnoreEmpty
     * @var string
     */
    public $emptyTest = "test";

    /**
     * @XML-Element
     * @var string
     */
    public $emptyTest2 = "test";

    /**
     * @XML-Element testSubClasses
     * @XML-TargetType \KudrMichal\Xml\Tests\Serialization\Classes\TestDeserializationSubClass[]
     * @var TestDeserializationSubClass[]
     */
    public $subClasses;

    /**
     * @XML-Element
     * @XML-TargetType float[]
     * @var float[]
     */
    public $floatArray;

    /**
     * @XML-Elements sibling
     * @XML-TargetType integer[]
     * @var int[]
     */
    public $siblings;

    /**
     * @XML-Elements subClass
     * @XML-TargetType \KudrMichal\Xml\Tests\Serialization\Classes\TestDeserializationSubClass2[]
     * @var TestDeserializationSubClass[]
     */
    public $elements;



}