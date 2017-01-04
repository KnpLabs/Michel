<?php
namespace AppBundle\Provider;
use AppBundle\Entity as Model;

interface Package
{
    public function get($name);
}