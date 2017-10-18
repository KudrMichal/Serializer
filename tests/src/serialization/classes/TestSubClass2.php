<?php

namespace KudrMichal\Xml\Tests\Serialization\Classes;

/**
 * Class TestSubClass2
 *
 * @author Michal Kudr
 * @package KudrMichal\Xml\Tests\Serialization\Classes
 *
 * @XML-Serializable
 */
class TestSubClass2 {

    /**
     * @XML-Element t2
     * @var string
     */
    private $test2 = "xyz";

}