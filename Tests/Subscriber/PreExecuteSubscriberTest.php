<?php

namespace PUGX\PreExecuteControllerBundle\Tests\Service;

use PUGX\PreExecuteControllerBundle\Event\FilterableEvent;
use PUGX\PreExecuteControllerBundle\Subscriber\PreExecuteSubscriber;
use PUGX\PreExecuteControllerBundle\Tests\Stub\FilteredController;

class PreExecuteSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->driver = $this->getMockBuilder('PUGX\PreExecuteControllerBundle\Model\DriverInterface')
            ->disableOriginalConstructor()->getMock();

        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()->getMock();

        $this->subscriber = new PreExecuteSubscriber($this->driver, $this->dispatcher);


        $this->controller = new FilteredController();

        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterControllerEvent')
            ->disableOriginalConstructor()->getMock();

        $this->filterable = $this->getMockBuilder('PUGX\PreExecuteControllerBundle\Model\FilterableInterface')
            ->disableOriginalConstructor()->getMock();

        $this->filterEvent = new FilterableEvent($this->event);
    }

    public function testIfControllerHasFiltersThenEventsWasDispatched()
    {
        $this->event->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(array($this->controller, 'myFirstAction')));

        $this->driver->expects($this->once())
            ->method('getFilterables')
            ->with($this->controller, 'myFirstAction')
            ->will($this->returnValue(array($this->filterable, $this->filterable)));

        $this->filterable->expects($this->exactly(2))
            ->method('getFilters')
            ->will($this->onConsecutiveCalls(
                array('filter_event_class_one', 'filter_event_class_two'),
                array('filter_event_method')
            ));

        $this->filterable->expects($this->exactly(2))
            ->method('getRedirectionRoute')
            ->will($this->onConsecutiveCalls(
                null,
                null
            ));

        $this->dispatcher->expects($this->exactly(3))
            ->method('dispatch')
            ->with($this->logicalOr(
                'pugx.pre_execute.filter_event_class_one', $this->filterEvent,
                'pugx.pre_execute.filter_event_class_two', $this->filterEvent,
                'pugx.pre_execute.filter_event_method', $this->filterEvent
            ));

        $this->subscriber->onKernelController($this->event);
    }
}
