<?php

namespace KudrMichal\Xml\Exceptions;

/**
 * Class XmlException
 *
 * Base Xml package exception
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Exceptions
 */
class XmlException extends \Exception {

}

/**
 * Class IllegalArgumentException
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Exceptions
 */
class IllegalArgumentException extends XmlException {

    /**
     * @param string $given
     * @return IllegalArgumentException
     */
    public static function objectExpected(string $given) : IllegalArgumentException {
        return new static("Object expected! $given given");
    }

}

/**
 * Class SerializeException
 *
 * Base exception for serializing/deserializing
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Exceptions
 */
class SerializeException extends XmlException {

}

/**
 * Class ObjectUnserializableException
 *
 * Object cannot be serialized or xml cannot be deserialized into object. Probably missing XML-Serializable annotation
 * Or array has mixed content etc.
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Exceptions
 */
class UnserializableException extends SerializeException {

    /**
     * @param $className
     * @return UnserializableException
     */
    public static function objectUnserializable($className) : UnserializableException {
        return new static("Class $className is unserializable. Annotation XML-Serializable is missing!");
    }

    /**
     * Try to serialize non scalar property into xml attribute
     * @param string $property
     * @return UnserializableException
     */
    public static function nonScalarAttribute(string $property) : UnserializableException {
        return new static("Non scalar value of property $property cannot be serialized into element attribute!");
    }

    /**
     * Some annotations expect only array
     * @param string $class
     * @param string $property
     * @return UnserializableException
     */
    public static function nonArrayUnserializable(string $class, string $property) : UnserializableException {
        return new static("$class::$property must contain array!");
    }

    /**
     * Element or attribute not found in xml
     * @param string $class
     * @param string $property
     * @return UnserializableException
     */
    public static function elementNotFound(string $class, string $property = NULL) {
        return new static("Element/attribute for property " . ($property ? $class."::".$property : $class) . " not found!");
    }

    /**
     * Target class not found
     * @param string $class
     * @return UnserializableException
     */
    public static function classNotFound(string $class) {
        return new static("Class $class not found");
    }

    /**
     * @param string $class
     * @param string $property
     * @param string $type
     * @return static
     */
    public static function illegalTargetType(string $class, string $property, string $type) {
        return new static("Cannot use target type $type in property $class::$property");
    }

}

/**
 * Class IllegalAnnotationException
 *
 * E.g. illegal combination of annotations, non scalar value with XML-Attribute etc.
 *
 *  @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Exceptions
 */
class IllegalAnnotationException extends SerializeException {



}




