<?php

namespace PUGX\PreExecuteControllerBundle\Model;

abstract class AbstractFilterable implements FilterableInterface
{
    private $filters;
    private $redirectionRoute = null;

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }
            $this->$key = $value;
        }

        if (!isset($this->filters)) {
            throw new \InvalidArgumentException('Please set a least one filter');
        }
    }

    public function getFilters()
    {
        return explode(",", $this->filters);
    }

    public function getRedirectionRoute()
    {
        return $this->redirectionRoute;
    }
}
