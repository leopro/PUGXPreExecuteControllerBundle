<?php

namespace PUGX\PreExecuteControllerBundle\Event;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\Event;

class FilterableEvent extends Event
{
    protected $filterControllerEvent;
    protected $redirectionRoute;

    public function __construct(FilterControllerEvent $filterControllerEvent, $redirectionRoute = null)
    {
        $this->filterControllerEvent = $filterControllerEvent;
        $this->redirectionRoute      = $redirectionRoute;
    }

    public function getFilterControllerEvent()
    {
        return $this->filterControllerEvent;
    }

    public function getRedirectionRoute()
    {
        return $this->redirectionRoute;
    }
}
