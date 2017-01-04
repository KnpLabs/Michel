<?php

namespace AppBundle\Provider\Package;

use AppBundle\Provider\Package;
use AppBundle\Entity as Model;

class Chain implements Package
{
    private $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function get($name)
    {
        foreach ($this->providers as $provider)
        {
            if (null !== $package = $provider->get($name)) {
                return $package;
            }
        }
    }
}
