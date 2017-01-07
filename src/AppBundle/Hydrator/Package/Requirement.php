<?php

namespace AppBundle\Hydrator\Package;

use AppBundle\Hydrator\Package;
use AppBundle\Entity as Model;
use AppBundle\Provider as Provider;

class Requirement implements Package
{
    private $provider;

    public function __construct(Provider\Package $provider)
    {
        $this->provider = $provider;
    }

    public function hydrate(Model\Package $package, $packageInfo)
    {
        if (!isset($packageInfo['require'])) {
            return ;
        }

        foreach ($packageInfo['require'] as $requirement => $version) {
            if (false !== strpos($requirement, '/')) {
                $dependency = $this->provider->get($requirement);
                if (false === $package->getMyRequirements()->contains($dependency)) {
                    $package->getMyRequirements()->add($dependency);
                }
            }
        }
    }
}