<?php

namespace spec\AppBundle\Packagist;

use AppBundle\Packagist\Client;
use Buzz\Browser;
use Buzz\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let(Browser $browser)
    {
        $this->beConstructedWith($browser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    function it_must_return_a_package_list($browser, Response $response)
    {
        $browser->get("https://packagist.org/packages/list.json")->willReturn($response);
        $response->getContent()->willReturn(json_encode(array("packageNames" => ["foo/bar","bar/foo"])));

        $this->getPackagesList()->shouldReturn(['foo/bar', 'bar/foo']);
    }

    function it_must_return_null_if_package_doesnt_exist($browser, Response $response)
    {
        $browser->get("https://packagist.org/p/foo/bar.json")->willReturn($response);
        $response->isOk()->willReturn(false);

        $this->getPackageInfo('foo/bar')->shouldReturn(null);
    }

    function it_must_return_the_first_coming_version_of_a_named_package_info($browser, Response $response)
    {
        $browser->get("https://packagist.org/p/foo/bar.json")->willReturn($response);
        $response->isOk()->willReturn(true);
        $response->getContent()->willReturn(json_encode(array('packages' => array('foo/bar' => array('version' => ['name', 'require', 'require-dev'])))));

        $this->getPackageInfo('foo/bar')->shouldReturn(['name', 'require', 'require-dev']);
    }

}
