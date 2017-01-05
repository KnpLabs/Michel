<?php
namespace AppBundle\Packagist;
use Buzz\Browser;

class Client
{
    const PACKAGES_LIST_API = "https://packagist.org/packages/list.json";
    const PACKAGE_INFO_API = "https://packagist.org/p/";

    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return array
     */
    public function getPackagesList()
    {
        $json_response = $this->browser->get(self::PACKAGES_LIST_API)->getContent();
        $response = json_decode($json_response, true);

        return $response['packageNames'];
    }

    /**
     * @return array
     */
    public function getPackageInfo($packageName)
    {
        $packageInfo = null;
        $json_response = $this->browser->get(self::PACKAGE_INFO_API.$packageName.'.json')->getContent();
        $response = json_decode($json_response, true);

        if (!isset($response['error'])) {
            $packageInfo = current($response['packages'][$packageName]);
        }

        return $packageInfo;
    }
}