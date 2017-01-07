<?php

namespace AppBundle\Provider\Package;

use AppBundle\Entity as Model;
use AppBundle\Provider\Package;

class Factory implements Package
{
    public function get($name)
    {
        return new Model\Package($name);
    }
}
