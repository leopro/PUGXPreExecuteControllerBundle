<?php

namespace PUGX\PreExecuteControllerBundle\Subscriber;

use PUGX\PreExecuteControllerBundle\Event\FilterableEvent;
use PUGX\PreExecuteControllerBundle\Model\DriverInterface;;
use PUGX\PreExecuteControllerBundle\Model\FilterableInterface;
use PUGX\PreExecuteControllerBundle\Model\PreExecuteEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use PUGX\PreExecuteControllerBundle\Model\FilterableControllerInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * It handles filters configured on FilterableControllerInterface controllers,
 * dispatching an event called pugx.filter_controller.{filter_name} for each filter found
 *
 * @package PUGX\PreExecuteControllerBundle\Service
 */
class PreExecuteSubscriber implements EventSubscriberInterface
{
    protected $eventDispatcher;

    protected $driver;

    /**
     * @param DriverInterface           $driver
     * @param EventDispatcherInterface  $eventDispatcher
     */
    public function __construct(DriverInterface $driver, EventDispatcherInterface $eventDispatcher)
    {
        $this->driver = $driver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController'
        );
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controllers = $event->getController();
        if (!is_array($controllers)) {
            return;
        }

        $controller = $controllers[0];
        $method     = $controllers[1];

        if (!$controller instanceof FilterableControllerInterface) {
            return;
        }

        $filterables = $this->driver->getFilterables($controller, $method);
        foreach ($filterables as $filterable) {
            $this->dispatch($filterable, $event);
        }
    }

    /**
     * @param FilterableInterface   $filterable
     * @param FilterControllerEvent $filterControllerEvent
     */
    protected function dispatch(FilterableInterface $filterable, FilterControllerEvent $filterControllerEvent)
    {
        $filters            = $filterable->getFilters();
        $redirectionRoute   = $filterable->getRedirectionRoute();
        $filterEvent        = new FilterableEvent($filterControllerEvent, $redirectionRoute);

        foreach ($filters as $filter) {
            $eventName = PreExecuteEvents::PRE_EXECUTE . '.' . trim($filter);
            $this->eventDispatcher->dispatch($eventName, $filterEvent);
        }
    }
}
