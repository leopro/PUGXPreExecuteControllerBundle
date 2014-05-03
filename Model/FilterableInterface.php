<?php

namespace PUGX\PreExecuteControllerBundle\Model;


interface FilterableInterface
{
    /**
     * @return array
     */
    public function getFilters();

    /**
     * @return string
     */
    public function getRedirectionRoute();
}