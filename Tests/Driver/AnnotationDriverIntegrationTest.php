<?php

namespace PUGX\PreExecuteControllerBundle\Tests\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use PUGX\PreExecuteControllerBundle\Driver\AnnotationDriver;
use PUGX\PreExecuteControllerBundle\Tests\Stub\FilteredController;

class AnnotationDriverIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->reader = new AnnotationReader();

        $this->driver = new AnnotationDriver($this->reader);

        $this->controller = new FilteredController();

        $this->reflectionObject = new \ReflectionObject($this->controller);

        $this->filterable = $this->getMockBuilder('PUGX\PreExecuteControllerBundle\Annotation\PreExecute')
            ->disableOriginalConstructor()->getMock();
    }

    public function testIfControlledIsAnnotatedThenFiltersWasReturned()
    {
        $filterables = $this->driver->getFilterables($this->controller, 'myFirstAction');
        $this->assertEquals(2, count($filterables));
        $this->assertInstanceOf('PUGX\PreExecuteControllerBundle\Model\FilterableInterface', $filterables[0]);
    }
} 