<?php
namespace AppBundle\Provider\Package;

use AppBundle\Entity as Model;
use AppBundle\Provider\Package;
use Doctrine\Common\Persistence\ObjectRepository;

class Doctrine implements Package
{
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($name)
    {
        return $this->repository->findOneBy(['id' => $name]);
    }
}