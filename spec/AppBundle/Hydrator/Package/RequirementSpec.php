<?php

namespace spec\AppBundle\Hydrator\Package;

use AppBundle\Hydrator\Package\Requirement;
use AppBundle\Provider\Package;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequirementSpec extends ObjectBehavior
{
    function let(Package $provider)
    {
        $this->beConstructedWith($provider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Requirement::class);
    }

    function it_must_hydrate_a_package_with_requirements($provider, \AppBundle\Entity\Package $package, Collection $requirements)
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

        $provider->get('foo1/bar')->willReturn($package);
        $package->getMyRequirements()->willReturn($requirements);
        $requirements->contains($package)->willReturn(false);
        $requirements->add($package)->shouldBeCalled();

        $this->hydrate($package, $packageInfo);
    }
}