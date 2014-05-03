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
