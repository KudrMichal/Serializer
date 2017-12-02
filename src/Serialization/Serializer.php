<?php

namespace KudrMichal\Xml\Serialization;

use KudrMichal\Annotations\AnnotationCollection;
use KudrMichal\Annotations\Reader;
use KudrMichal\Xml\Exceptions\ArrayUnserializableException;
use KudrMichal\Xml\Exceptions\IllegalAnnotationException;
use KudrMichal\Xml\Exceptions\IllegalArgumentException;
use KudrMichal\Xml\Exceptions\ObjectUnserializableException;
use KudrMichal\Xml\Exceptions\UnserializableException;
use KudrMichal\Xml\Exceptions\XmlException;

/**
 * Class Serializer
 *
 * Serialization objects to XML strings and deserialization XML strings to objects
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Xml\Serialization
 * @todo cache reflection
 */
class Serializer {

    /**
     * Class annotation for detecting, if class can be serialized
     * Bidirectional
     * @const string
     */
    const XML_SERIALIZABLE = "XML-Serializable";

    /**
     * Bidirectional property annotation
     * Property == XML element
     * Specifiing element name is optional. If no element name specified, property name is used
     * @const string
     */
    const XML_ELEMENT = "XML-Element";

    /**
     * Bidirectional property annotation
     * Property == XML attribute
     * Specifiing attribute name is optional. If no attribute name specified, property name is used
     * @const string
     */
    const XML_ATTRIBUTE = "XML-Attribute";

    /**
     * Bidirectional property annotation
     * Serialize array into same level without creating parent element.
     * E.g. if class Files contains array $files, XML-Element creates something like that...
     * <Files>
     *   <files>
     *     <file>...</file>
     *     <file>...</file>
     *   </files>
     * <Files>
     *
     * with same class, this annotation enables this  ...
     * <Files>
     *   <file>...</file>
     *   <file>...</file>
     * <Files>
     * @const string
     * @todo
     */
    const XML_ELEMENTS = "XML-Elements";

    /**
     * Bidirectional property annotation
     * Convert element/property value to/from \DateTime
     * Accept one parametr, which contains date format, e.g. Y-m-d
     * Can be used with XML-Element or XML-Attribue annotation
     * @const string
     */
    const XML_DATETIME = "XML-DateTime";

    /**
     * Serialize only annotation
     * Property skipped if null
     * @const string
     */
    const XML_IGNORE_NULL = "XML-IgnoreNull";

    /**
     * Deserialize only annotation
     * We need specify, what we want to create from xml element
     * Supported values:
     *  full class name, e.g. \KudrMichal\Xml\Tests\Serialization\Classes\TestClass
     *  array containing non objects, e.g. []
     *  array containing objects, e.g. \KudrMichal\Xml\Tests\Serialization\Classes\TestClass[]
     *  integer
     *  integer[]
     *  boolean
     *  boolean[]
     *  string
     *  string[]
     *  float
     *  float[]
     *  DateTime
     *  DateTime[]
     * @const string
     */
    const XML_TARGET_TYPE = "XML-TargetType";

    /**
     * Serialize only annotation
     * If we want to serialize array, we can specify subelement names, e.g. array with numeric keys. We have
     * unassociative array $names, which contains names for example. We want to create something like that...
     * <names>
     *  <name>somebody</name>
     *  <name>somebody 2</name>
     *  .....
     * </names>
     * @const string
     * @todo
     */
    const XML_ARRAY_ELEMENT = "XML-ArrayElement";

    /**
     * Serialize only annotation
     * If we want to serialize array which contains objects and XML-ArrayItem not specified,
     * we have to choose element name from array key or object class annotation
     * Possible values:
     *  - key
     *  - class
     * @const string
     */
    const XML_ARRAY_OBJECT_NAME = "XML-ArrayObjectName";

    /**
     * Deserialize only annotation
     * Ignore empty element
     * @const string
     */
    const XML_IGNORE_EMPTY = "XML-IgnoreEmpty";


    /**
     * Default datetime format, id not specified
     * @const string
     */
    const DEFAULT_DATETIME_FORMAT = \DateTime::W3C;

    /**
     * Default xml encoding, if not specified
     * @const string
     */
    const DEFAULT_XML_ENCODING = "UTF8";

    /**
     * Default xml version, if not specified
     * @const string
     */
    const DEFAULT_XML_VERSION = "1.0";

    /** @var Reader */
    private $annotationReader;


    /**
     * Serializer constructor.
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader) {
        $this->annotationReader = $annotationReader;
    }

    /**
     * Serialize object to XML DOMDocument. Object class must contain XML-Serializable annotation
     * @param object $source
     * @param string $encoding
     * @param string $version
     * @return \DOMDocument
     * @throws IllegalAnnotationException
     * @throws IllegalArgumentException
     * @throws UnserializableException
     */
    public function serialize($source, $encoding = self::DEFAULT_XML_ENCODING, $version = self::DEFAULT_XML_VERSION) : \DOMDocument {

        //is object?
        $className = get_class($source);
        if (!$className) {
            throw IllegalArgumentException::objectExpected(gettype($source));
        }
        //is object serializable?
        if (!$this->annotationReader->hasClassAnnotation($className, self::XML_SERIALIZABLE)) {
            throw UnserializableException::objectUnserializable($className);
        }

        //get root element name from annotation
        $classAnnotation = $this->annotationReader->getClassAnnotation($className, self::XML_SERIALIZABLE);

        //root element name from annotation value. If root name not specified, use class short name
        $rootElementName = $classAnnotation ?: $this->_getClassShortName($className);

        //create xml document
        $doc = new \DOMDocument($version, $encoding);
        //and root element
        $rootElement = $doc->appendChild($doc->createElement($rootElementName));
        $this->_serializeObject($rootElement, $source);
        return $doc;
    }

    /**
     * Serialize object to XML string
     * @param object $source
     * @param string $encoding
     * @param string $version
     * @return string
     * @throws IllegalAnnotationException
     * @throws IllegalArgumentException
     * @throws UnserializableException
     */
    public function serializeToString(object $source, $encoding = self::DEFAULT_XML_ENCODING, $version = self::DEFAULT_XML_VERSION) : string {
        return $this->serialize($source, $encoding, $version)->saveXML();
    }

    /**
     * Deserialize XML string into object
     * @param string $xml
     * @param string $className
     * @param string $encoding
     * @param string $version
     * @return mixed
     * @throws UnserializableException
     * @todo XML-Elements
     */
    public function deserialize(string $xml, string $className, $encoding = self::DEFAULT_XML_ENCODING, $version = self::DEFAULT_XML_VERSION) {
        if (!$this->annotationReader->hasClassAnnotation($className, self::XML_SERIALIZABLE)) {
            throw UnserializableException::objectUnserializable($className);
        }

        //target class not found
        if (!class_exists($className)) {
            throw UnserializableException::classNotFound($className);
        }

        $dom = new \DOMDocument($version, $encoding);
        $dom->loadXML($xml);
        $object = (new \ReflectionClass($className))->newInstanceWithoutConstructor();

        $this->_deserializeObject($dom->documentElement, $object);

        return $object;

    }

    /**
     * @param \DOMElement $element
     * @param $object
     * @throws UnserializableException
     * @return mixed
     */
    private function _deserializeObject(\DOMElement $element, $object) {
        //get root element name
        $className = get_class($object);
        var_dump($className);
        $elementName = $this->annotationReader->getClassAnnotation($className, self::XML_SERIALIZABLE);
        if (is_null($elementName)) {
            $elementName = $this->_getClassShortName($className);
        }
        var_dump($element->nodeName);
        var_dump($elementName);
        if ($element->nodeName != $elementName) {
            throw UnserializableException::elementNotFound($elementName);
        }

        $reflection = new \ReflectionClass($className);

        //look for XML annotated properties
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            $annotations = $this->annotationReader->getPropertyAnnotations($className, $propertyName);

            //skip static and not xml annotated property
            if ($property->isStatic() || !$this->isXmlAnnotated($annotations)) {
                continue;
            }

            //make private and protected accessible
            if (!$property->isPublic()) {
                $property->setAccessible(TRUE);
            }

            switch (TRUE) {
                //look for sub element
                case isset($annotations[self::XML_ELEMENT]): {
                    $elementName = $annotations[self::XML_ELEMENT] ?: $propertyName;
                    $elements = [];
                    foreach($element->childNodes as $el) {
                        if ($el instanceof \DOMElement && $el->nodeName == $elementName) {
                            $elements[] = $el;
                        }
                    }
                    if (count($elements) != 1) {
                        //todo exception
                        continue;
                    }

                    //ignore empty element if XML-IgnoreEmpty specified
                    if (isset($annotations[self::XML_IGNORE_EMPTY]) && empty($elements[0]->nodeValue)) {
                        continue;
                    }

                    //use target type
                    if (isset($annotations[self::XML_TARGET_TYPE])) {
                        switch($annotations[self::XML_TARGET_TYPE]) {
                            case "integer": {
                                $property->setValue($object, intval($elements[0]->nodeValue));
                                break;
                            }
                            case "integer[]": {
                                $ints = [];
                                foreach($elements[0]->childNodes as $child) {
                                    if ($child instanceof \DOMElement) {
                                        $ints[] = intval($child->nodeValue);
                                    }
                                }
                                $property->setValue($object, $ints);
                                break;
                            }
                            case "float": {
                                $property->setValue($object, floatval($elements[0]->nodeValue));
                                break;
                            }
                            case "float[]": {
                                $floats = [];
                                foreach($elements[0]->childNodes as $child) {
                                    if ($child instanceof \DOMElement) {
                                        $floats[] = floatval($child->nodeValue);
                                    }
                                }
                                $property->setValue($object, $floats);
                                break;
                            }
                            case "boolean": {
                                $property->setValue($object, boolval($elements[0]->nodeValue));
                                break;
                            }
                            case "boolean[]":
                                $bools = [];
                                foreach($elements[0]->childNodes as $child) {
                                    if ($child instanceof \DOMElement) {
                                        $bools[] = boolval($child->nodeValue);
                                    }
                                }
                                $property->setValue($object, $bools);
                                break;
                            case "string": {
                                $property->setValue($object, $elements[0]->nodeValue);
                                break;
                            }
                            case "string[]": {
                                $strings = [];
                                foreach($elements[0]->childNodes as $child) {
                                    if ($child instanceof \DOMElement) {
                                        $strings[] = $child->nodeValue;
                                    }
                                }
                                $property->setValue($object, $strings);
                                break;
                            }
                            case "DateTime": {
                                $property->setValue($object, new \DateTime($elements[0]->nodeValue));
                                break;
                            }
                            case "DateTime[]": {
                                $dates = [];
                                foreach($elements[0]->childNodes as $child) {
                                    if ($child instanceof \DOMElement) {
                                        $dates[] = new \DateTime($child->nodeValue);
                                    }
                                }
                                $property->setValue($object, $dates);
                                break;
                            }
                            case "[]": {
                                //todo
                                break;
                            }
                            default: {
                                if (preg_match("/(?<class>[\\w\\\\]+)\\[\\]/", $annotations[self::XML_TARGET_TYPE], $matches)) {
                                    if (!class_exists($matches['class'])) {
                                        throw UnserializableException::classNotFound($matches['class']);
                                    }

                                    $newObjects = [];
                                    foreach($elements[0]->childNodes as $child) {
                                        if ($child instanceof \DOMElement) {
                                            $newObjects[] = $newObject = (new \ReflectionClass($matches['class']))->newInstanceWithoutConstructor();
                                            $this->_deserializeObject($child, $newObject);
                                        }
                                    }
                                    $property->setValue($object, $newObjects);
                                } else {
                                    if (!class_exists($annotations[self::XML_TARGET_TYPE])) {
                                        throw UnserializableException::classNotFound($annotations[self::XML_TARGET_TYPE]);
                                    }
                                    $newObject = (new \ReflectionClass($annotations[self::XML_TARGET_TYPE]))->newInstanceWithoutConstructor();
                                    $this->_deserializeObject($elements[0], $newObject);
                                    $property->setValue($object, $newObject);
                                }
                                break;
                            }
                        }
                    }
                    //targetType not specified. The same as targetType string
                    else {
                        $property->setValue($object, $elements[0]->nodeValue);
                    }

                    break;
                }
                //look for attribute
                case isset($annotations[self::XML_ATTRIBUTE]): {
                    $attrName = $annotations[self::XML_ATTRIBUTE] ?: $propertyName;
                    $attribute = $element->getAttribute($attrName);
                    //check type
                    if (isset($annotations[self::XML_TARGET_TYPE])) {
                        switch ($annotations[self::XML_TARGET_TYPE]) {
                            case "integer": {
                                $property->setValue($object, intval($attribute));
                                break;
                            }
                            case "string": {
                                $property->setValue($object, $attribute);
                                break;
                            }
                            case "float": {
                                $property->setValue($object, floatval($attribute));
                                break;
                            }
                            case "boolean": {
                                $property->setValue($object, boolval($attribute));
                                break;
                            }
                            default: {
                                //other types are not supported with XML-Attribute
                                throw UnserializableException::illegalTargetType($className, $propertyName, $annotations[self::XML_TARGET_TYPE]);
                            }
                        }
                    } else {
                        $property->setValue($object, $attribute);
                    }
                    break;
                }
                case isset($annotations[self::XML_ELEMENTS]): {

                    $elementName = $annotations[self::XML_ELEMENTS] ?: $propertyName;
                    var_dump($elementName);
                    $values = [];

                    foreach($element->childNodes as $el) {
                        if (!$el instanceof \DOMElement || $el->nodeName != $elementName) {
                            continue;
                        }

                        //check type
                        if (isset($annotations[self::XML_TARGET_TYPE])) {
                            switch ($annotations[self::XML_TARGET_TYPE]) {
                                case "integer[]": {
                                    $values[] = intval($el->nodeValue);
                                    break;
                                }
                                case "string[]": {
                                    $values[] = $el->nodeValue;
                                    break;
                                }
                                case "float[]": {
                                    $values[] = floatval($el->nodeValue);
                                    break;
                                }
                                case "boolean[]": {
                                    $values[] = boolval($el->nodeValue);
                                    break;
                                }
                                default: {
                                    if (preg_match("/(?<class>[\\w\\\\]+)\\[\\]/", $annotations[self::XML_TARGET_TYPE], $matches)) {

                                        if (!class_exists($matches['class'])) {
                                            throw UnserializableException::classNotFound($matches['class']);
                                        }
                                        $newObject = (new \ReflectionClass($matches['class']))->newInstanceWithoutConstructor();

                                        $this->_deserializeObject($el, $newObject);
                                        $values[] = $newObject;
                                    } else {
                                        //non array types are not supported for XML-Elements
                                        throw UnserializableException::illegalTargetType($className, $propertyName, $annotations[self::XML_TARGET_TYPE]);
                                    }
                                    break;
                                }
                            }
                        } else {
                            $values[] = $el->nodeValue;
                        }

                    }

                    $property->setValue($object, $values);

                    break;
                }
            }
        }

        return $object;

    }




    /**
     * Recursive method, which creates child elements from object
     * @param \DOMElement $element
     * @param $object
     * @return \DOMElement
     * @throws IllegalAnnotationException
     * @throws UnserializableException
     */
    private function _serializeObject(\DOMElement $element, $object) : \DOMElement {

        $reflection = new \ReflectionClass(get_class($object));
        $className = $reflection->getName();

        //object must contain XML-Serializable annotation
        if (!$this->annotationReader->hasClassAnnotation($className, self::XML_SERIALIZABLE)) {
            throw UnserializableException::objectUnserializable($className);
        }

        //loop over all properties
        foreach($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyAnnotations = $this->annotationReader->getPropertyAnnotations($className, $propertyName);
            //property can be not accesible
            if (!$property->isPublic()) {
                $property->setAccessible(TRUE);
            }
            //get property value
            $propertyValue = $property->getValue($object);
            //if property is NULL and has XML-IgnoreNull annotation, then skip
            if (is_null($propertyValue) && isset($propertyAnnotations[self::XML_IGNORE_NULL])) {
                continue;
            }

            //if null, then use empty string
            $propertyValue = $propertyValue ?: "";
            //element or attribute?
            switch(TRUE) {
                case isset($propertyAnnotations[self::XML_ATTRIBUTE]):
                    //non scalar value cannot be serialized into attribute
                    if (!is_scalar($propertyValue)) {
                        throw UnserializableException::nonScalarAttribute("$className::$propertyName");
                    }
                    //if attribute name not specified, use property name
                    $attributeName = $propertyAnnotations[self::XML_ATTRIBUTE] ?: $propertyName;
                    $element->setAttribute($attributeName, $property->getValue($object));
                    break;
                case $this->annotationReader->hasPropertyAnnotation($className, $propertyName, self::XML_ELEMENT): {
                    //if element name not specified, use property name
                    $elementName = $propertyAnnotations[self::XML_ELEMENT] ?: $propertyName;
                    //XML-Element supports scalars, arrays and objects
                    switch (TRUE) {
                        case is_scalar($propertyValue): {
                            $element->appendChild($element->ownerDocument->createElement($elementName, $propertyValue));
                            break;
                        }
                        case is_array($propertyValue): {
                            $arrayElement = $element->appendChild($element->ownerDocument->createElement($elementName));
                            $this->_serializeArray($arrayElement, $propertyValue, $propertyAnnotations);
                            break;
                        }
                        case $propertyValue instanceof \DateTime: {
                            $format = isset($propertyAnnotations[self::XML_DATETIME]) ? $propertyAnnotations[self::XML_DATETIME] : self::DEFAULT_DATETIME_FORMAT;
                            $element->appendChild($element->ownerDocument->createElement($elementName, $propertyValue->format($format)));
                            break;
                        }
                        //if object, then recursion
                        case is_object($propertyValue): {
                            $newObjectClass = get_class($propertyValue);
                            $newObjectClassAnnotation = $this->annotationReader->getClassAnnotation($newObjectClass, self::XML_SERIALIZABLE);
                            //get class short name
                            $newObjectElementName = $newObjectClassAnnotation ?: $this->_getClassShortName($propertyValue);
                            //create new element and go again
                            $newObjectElement = $element->appendChild($element->ownerDocument->createElement($newObjectElementName));
                            $this->_serializeObject($newObjectElement, $propertyValue);
                            break;
                        }
                    }
                    break;
                }
                case $this->annotationReader->hasPropertyAnnotation($className, $propertyName, self::XML_ELEMENTS): {
                    //only array accepted
                    if (is_array($propertyValue)) {
                        $this->_serializeArray($element, $propertyValue, $propertyAnnotations);
                    } else {
                        throw UnserializableException::nonArrayUnserializable($className, $propertyName);
                    }
                    break;
                }
            }
        }
        return $element;
    }

    /**
     * Creates child elements from array
     * The most complicated procedure
     * Arrays can be associative and unassociative
     * Unassociative arrays need specify element name
     * Arrays can contain array, objects, scalars and all together
     * etc.
     * @param \DOMElement $element
     * @param array $array
     * @param AnnotationCollection $annotations
     * @throws UnserializableException
     * @return \DOMElement
     */
    private function _serializeArray(\DOMElement $element, array $array, AnnotationCollection $annotations = NULL) {

        //force specified child name by XML-ArrayElement annotation
        $forceElementName = !is_null($annotations) && isset($annotations[self::XML_ARRAY_ELEMENT]) ? $annotations[self::XML_ARRAY_ELEMENT] : NULL;

        foreach($array as $key => $item) {
            switch(TRUE) {
                case is_scalar($item): {
                    $element->appendChild($element->ownerDocument->createElement($forceElementName ?: $key, $item));
                    break;
                }
                case is_array($item): {
                    $newElement = $element->appendChild($element->ownerDocument->createElement($forceElementName ?: $key));
                    $this->_serializeArray($newElement, $item);
                    break;
                }
                case is_object($item): {
                    //name by key or XML-ArrayElement annotation
                    $newName = $forceElementName ?: $key;
                    //if name by object, then change name by object
                    if (isset($annotations[self::XML_ARRAY_OBJECT_NAME])) {
                        $objectClass = get_class($item);
                        if ($this->annotationReader->hasClassAnnotation($objectClass, self::XML_SERIALIZABLE)) {
                            $objectAnnotation = $this->annotationReader->getClassAnnotation($objectClass, self::XML_SERIALIZABLE);
                            //use XML-Serializable annotation as element name if value is specified
                            $newName = $objectAnnotation ?: $this->_getClassShortName($objectClass);
                        } else {
                            throw UnserializableException::objectUnserializable($objectClass);
                        }
                    }

                    $newElement = $element->appendChild($element->ownerDocument->createElement($newName));
                    $this->_serializeObject($newElement, $item);
                    break;
                }
            }
        }

        return $element;
    }


    /**
     * Extract class short name from fullname
     * @param string $class
     * @return string
     */
    private function _getClassShortName($class) {
        if (is_object($class)) {
            $class = get_class($class);
        }
        return substr(strrchr($class, "\\"), 1);
    }

    /**
     * @param AnnotationCollection $annotations
     * @return bool
     */
    private function isXmlAnnotated(AnnotationCollection $annotations) {
        return isset($annotations[self::XML_ELEMENT]) || isset($annotations[self::XML_ELEMENTS]) || isset($annotations[self::XML_ATTRIBUTE]);
    }

    
}