<?php

namespace PUGX\PreExecuteControllerBundle\Driver;

use Doctrine\Common\Annotations\Reader;
use PUGX\PreExecuteControllerBundle\Model\DriverInterface;

class AnnotationDriver implements DriverInterface
{
    protected $reader;

    protected $annotationClass = "PUGX\PreExecuteControllerBundle\Annotation\PreExecute";

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $controller
     * @param string $method
     *
     * @return array of FilterableInterface
     */
    public function getFilterables($controller, $method)
    {
        $filterables = array();

        $reflectionObject = new \ReflectionObject($controller);

        $annotationClass = $this->reader->getClassAnnotation($reflectionObject, $this->annotationClass);
        if (null !== $annotationClass) {
            $filterables[] = $annotationClass;
        }

        $reflectionMethod = $reflectionObject->getMethod($method);
        $annotationMethod = $this->reader->getMethodAnnotation($reflectionMethod, $this->annotationClass);
        if (null !== $annotationMethod) {
            $filterables[] = $annotationMethod;
        }

        return $filterables;
    }
} 