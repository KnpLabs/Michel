<?php

namespace spec\AppBundle\Hydrator\Package;

use AppBundle\Hydrator\Package;
use AppBundle\Hydrator\Package\Chain;
use AppBundle\Entity as Model;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChainSpec extends ObjectBehavior
{
    function let(Package $hydrator1, Package $hydrator2, Package $hydrator3)
    {
        $this->beConstructedWith([$hydrator1, $hydrator2, $hydrator3]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Chain::class);
    }

    function it_will_hydrate_a_package($hydrator1, $hydrator2, $hydrator3, \AppBundle\Entity\Package $package)
    {
        $packageInfo = [
            'name' => 'foo/bar',
            'description' => 'this is foo/bar',
            'require' => [
                'foo1/bar' => 'version',
            ],
            'require-dev' => [
                'foo1/bar-dev' => 'version',
            ]
        ];

        $hydrator1->hydrate($package, $packageInfo)->shouldBeCalled();
        $hydrator2->hydrate($package, $packageInfo)->shouldBeCalled();
        $hydrator3->hydrate($package, $packageInfo)->shouldBeCalled();

        $this->hydrate($package, $packageInfo);
    }
}