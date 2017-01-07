<?php

namespace spec\AppBundle\Provider\Package;

use AppBundle\Entity\Package;
use AppBundle\Provider\Package\Doctrine;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineSpec extends ObjectBehavior
{
    function let(ObjectRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Doctrine::class);
    }

    function it_returns_null_if_not_in_repository($repository)
    {
        $repository->findOneBy(['id' => 'foo/bar'])->willReturn(null);

        $this->get('foo/bar')->shouldReturn(null);
    }

    function it_returns_the_package_if_in_repository($repository, Package $package)
    {
        $repository->findOneBy(['id' => 'foo/bar'])->willReturn($package);

        $this->get('foo/bar')->shouldReturn($package);
    }


}
