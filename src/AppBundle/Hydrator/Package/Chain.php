<?php
namespace AppBundle\Hydrator\Package;

use AppBundle\Entity as Model;
use AppBundle\Hydrator\Package;

class Chain implements Package
{
    private $hydrators;

    public function __construct(array $hydrators)
    {
        $this->hydrators = $hydrators;
    }

    public function hydrate(Model\Package $package, $packageInfo)
    {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->hydrate($package, $packageInfo);
        }
    }
}