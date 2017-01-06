<?php

namespace AppBundle\Hydrator\Package;

use AppBundle\Hydrator\Package;
use AppBundle\Entity as Model;
use AppBundle\Provider as Provider;

class RequirementDev implements Package
{
    private $provider;

    public function __construct(Provider\Package $provider)
    {
        $this->provider = $provider;
    }

    public function hydrate(Model\Package $package, $packageInfo)
    {
        if (!isset($packageInfo['require-dev'])) {
            return ;
        }

        foreach ($packageInfo['require-dev'] as $requirement => $version) {
            if (false !== strpos($requirement, '/')) {
                $dependency = $this->provider->get($requirement);
                if (false === $package->getMyDevRequirements()->contains($dependency)) {
                    $package->getMyDevRequirements()->add($dependency);
                }
            }
        }
    }
}