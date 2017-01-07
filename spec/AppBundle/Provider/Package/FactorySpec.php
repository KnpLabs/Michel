<?php

namespace spec\AppBundle\Provider\Package;

use AppBundle\Entity\Package;
use AppBundle\Provider\Package\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_must_return_a_package()
    {
        $package = $this->get('foo/bar');
        $package->shouldHaveType(Package::class);
        $package->getName()->shouldReturn('foo/bar');
    }
}
