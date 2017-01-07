<?php

namespace spec\AppBundle\Hydrator;

use AppBundle\Hydrator\Package;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScalarSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Package::class);
    }

    function it_must_hydrate_a_package_with_scalar_info(\AppBundle\Entity\Package $package)
    {
        $packageInfo = [
            'name' => 'foo/bar',
            'description' => 'this is foo/bar',
            'require' => [
                'foo1/bar',
            ],
            'require-dev' => [
                'foo1/bar-dev',
            ]
        ];

        $package->setDescription('this is foo/bar')->shouldBeCalled();

        $this->hydrate($package, $packageInfo);
    }
}