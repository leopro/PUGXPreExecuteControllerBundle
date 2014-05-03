<?php

namespace PUGX\PreExecuteControllerBundle\Model;

interface DriverInterface
{
    /**
     * @param string $controller
     * @param string $method
     *
     * @return array of FilterableInterface
     */
    public function getFilterables($controller, $method);
} 