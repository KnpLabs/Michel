<?php

namespace spec\AppBundle\Provider\Package;

use AppBundle\Provider\Package\Strategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use AppBundle\Provider\Package;

class StrategySpec extends ObjectBehavior
{
    function let(Package $provider1, Package $provider2, Package $provider3)
    {
        $this->beConstructedWith([$provider1, $provider2, $provider3]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Strategy::class);
    }

    function it_will_return_null_if_no_provider_provides_a_package($provider1, $provider2, $provider3)
    {
        $provider1->get('foo/bar')->willReturn(null);
        $provider2->get('foo/bar')->willReturn(null);
        $provider3->get('foo/bar')->willReturn(null);

        $this->get('foo/bar')->shouldReturn(null);
    }

    function it_will_return_the_first_returned_package_if_a_provider_provides_a_package($provider1, $provider2, $provider3, \AppBundle\Entity\Package $package)
    {
        $provider1->get('foo/bar')->willReturn(null);
        $provider2->get('foo/bar')->willReturn($package);
        $provider3->get('foo/bar')->shouldNotBeCalled();

        $this->get('foo/bar')->shouldReturn($package);
    }
}
