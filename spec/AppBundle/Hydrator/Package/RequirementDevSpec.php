<?php

namespace spec\AppBundle\Hydrator\Package;

use AppBundle\Hydrator\Package\RequirementDev;
use AppBundle\Provider\Package;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequirementDevSpec extends ObjectBehavior
{
    function let(Package $provider)
    {
        $this->beConstructedWith($provider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequirementDev::class);
    }

    function it_must_hydrate_a_package_with_requirements_dev($provider, \AppBundle\Entity\Package $package, Collection $requirements_dev)
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

        $provider->get('foo1/bar-dev')->willReturn($package);
        $package->getMyDevRequirements()->willReturn($requirements_dev);
        $requirements_dev->contains($package)->willReturn(false);
        $requirements_dev->add($package)->shouldBeCalled();

        $this->hydrate($package, $packageInfo);
    }
}