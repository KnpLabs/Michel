<?php
namespace AppBundle\Hydrator\Package;

use AppBundle\Entity as Model;
use AppBundle\Hydrator\Package;

class Scalar implements Package
{
    public function hydrate(Model\Package $package, $packageInfo)
    {
        $package->setDescription($packageInfo['description']);
    }
}