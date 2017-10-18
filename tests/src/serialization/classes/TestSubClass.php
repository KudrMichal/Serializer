<?php

namespace KudrMichal\Xml\Tests\Serialization\Classes;

/**
 * Class TestSubClass
 *
 * @author Michal Kudr
 * @package KudrMichal\Xml\Tests\Serialization\Classes
 *
 * @XML-Serializable TestSubElement
 */
class TestSubClass {

    /**
     * @XML-Element
     * @var string
     */
    private $test = "abc";

}