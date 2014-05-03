PUGXPreExecuteControllerBundle
=============

This bundle lets you add your own filters and hooks before a controller is executed.

It's still in beta.

Documentation
-------------

### 1. Installation

**Using composer**

Add the following lines in your composer.json:

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:leopro/PUGXPreExecuteControllerBundle.git"
        }
    ],
    "require": {
        "pugx/pre-execute-controller-bundle": "dev-master"
    }
}
```

### 2. Enable the bundle

Enable the bundle in the kernel:

``` php
<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new PUGX\PreExecuteControllerBundle\PUGXPreExecuteControllerBundle(),
    );
}
```

### 2. Usage

``` php
<?php

namespace PUGX\PreExecuteControllerBundle\Tests\Stub;

use PUGX\PreExecuteControllerBundle\Annotation\PreExecute;
use PUGX\PreExecuteControllerBundle\Model\FilterableControllerInterface;

/**
 * @PreExecute(filters="filter_event_class_one, filter_event_class_two")
 */
class FilteredController implements FilterableControllerInterface
{
    /**
    * @PreExecute(filters="filter_event_method")
    */
    public function myFirstAction()
    {

    }

    public function mySecondAction()
    {

    }
}
```

Now create your own listener/subscriber for events:

- pugx.pre_execute.filter_event_class_one
- pugx.pre_execute.filter_event_class_two
- pugx.pre_execute.filter_event_method

For example:

```

my_filter:
      class:     PUGX\PreExecuteControllerBundle\Listener\FilterEventClassOneListener
      arguments: ["@router"]
      tags:
          - { name: kernel.event_listener, event: pugx.pre_execute.filter_event_class_one, method: filter }
```

``` php
<?php

namespace PUGX\PreExecuteControllerBundle\Listener;

use PUGX\PreExecuteControllerBundle\Event\FilterableEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class FilterEventClassOneListener implements FilterableControllerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
    public function filter(FilterableEvent $event)
    {
        $myOperation = false;
        
        if (!$myOperation) {
            $url = $this->router->generate($event->getRedirectionRoute());
            $response = new RedirectResponse($url);

            $event->getFilterControllerEvent()->setController(function() use ($response) {
                return $response;
            });
        }
    }
}
```

