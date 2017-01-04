<?php

namespace AppBundle\Provider\Package;

use AppBundle\Provider\Package;

class Cache implements Package
{
    private $provider;
    private $packages;

    public function __construct(Package $provider)
    {
        $this->provider = $provider;
        $this->packages = [];
    }

    public function get($name)
    {
        if (false === array_key_exists($name, $this->packages)) {
            $this->packages[$name] = $this->provider->get($name);
        }

        return $this->packages[$name];
    }
}
