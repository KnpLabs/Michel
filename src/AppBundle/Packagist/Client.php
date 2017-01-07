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
        $json_data = $this->browser->get(self::PACKAGES_LIST_API)->getContent();
        $data = json_decode($json_data, true);

        return $data['packageNames'];
    }

    /**
     * @return array
     */
    public function getPackageInfo($packageName)
    {
        $packageInfo = null;
        $response = $this->browser->get(self::PACKAGE_INFO_API.$packageName.'.json');

        if ($response->isOk()) {
            $json_data = $response->getContent();
            $data = json_decode($json_data, true);

            $packageInfo = current($data['packages'][$packageName]);
        }

        return $packageInfo;
    }
}