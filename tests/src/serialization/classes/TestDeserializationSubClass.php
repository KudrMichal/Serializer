<?php

namespace KudrMichal\Xml\Tests\Serialization\Classes;

/**
 * Class TestDeserializationSubClass
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Tests\Serialization\Classes
 * @XML-Serializable testSubClass
 */
class TestDeserializationSubClass {

    /**
     * @XML-Element
     * @var string
     */
    public $test;

}