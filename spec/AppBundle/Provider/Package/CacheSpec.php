<?php

namespace spec\AppBundle\Provider\Package;

use AppBundle\Provider\Package;
use AppBundle\Provider\Package\Cache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CacheSpec extends ObjectBehavior
{
    function let(Package $provider)
    {
        $this->beConstructedWith($provider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Cache::class);
    }

    function it_always_serves_the_same_package_but_requests_just_once($provider, \AppBundle\Entity\Package $package)
    {
        $provider->get('foo/bar')->willReturn($package)->shouldBeCalledTimes(1);
        $this->get('foo/bar')->shouldReturn($package);
        $this->get('foo/bar')->shouldReturn($package);
    }


}
