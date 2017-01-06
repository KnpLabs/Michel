<?php
namespace AppBundle\Hydrator;
use AppBundle\Entity as Model;

interface Package
{
    public function hydrate(Model\Package $package, $packageInfo);
}
