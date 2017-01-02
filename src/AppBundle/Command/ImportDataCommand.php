<?php

namespace AppBundle\Command;

use AppBundle\Entity\Author;
use AppBundle\Entity\Dependency;
use AppBundle\Entity\Package;
use AppBundle\Entity\Vendor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportDataCommand extends ContainerAwareCommand
{
    const PACKAGES_LIST_API = "https://packagist.org/packages/list.json";
    const PACKAGE_INFO_API = "https://packagist.org/p/";

    protected function configure()
    {
        $this
            ->setName('import:data')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageList = $this->getPackagesList();
        $nb_packages = count($packageList);

        $progress = new ProgressBar($output, $nb_packages);
        $progress->start();

        $iTest = 0;

        foreach ($packageList as $package)
        {
            if (5 === $iTest) {
                break;
            }
            $iTest++;

            $this->setPackageInfo($package);

            $progress->advance();
        }
        $progress->finish();
        $output->writeln('');
    }

    /**
     * @return array
     */
    private function getPackagesList()
    {
        $response = json_decode(file_get_contents(self::PACKAGES_LIST_API));
        $packageList = $response->packageNames;

        return $packageList;
    }

    /**
     * @param $package
     */
    private function setPackageInfo($package)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $response = json_decode(file_get_contents(self::PACKAGE_INFO_API.$package.'.json'));
        $packageInfos = $response;

        foreach ($packageInfos as $packageInfo)
        {
            foreach ($packageInfo->$package as $info)
            {
                $package = new Package();
                $package->setName($info->name);
                $package->setDescription($info->description);
                $package->setType($info->type);
                $package->setUid($info->uid);
                $package->setVersion($info->version);
                $em->persist($package);
                $em->flush();

                var_dump($package);

                $vendor = new Vendor();
                $vendor->setName(explode('/', $info->name)[0]);
                var_dump($vendor);

                foreach ($info->authors as $package_author)
                {
                    $author = new Author();
                    $author->setName($package_author->name);
                    $author->setEmail($package_author->email);
                    @$author->setHomepage($package_author->homepage);
                    var_dump($author);
                }

                if (isset($info->require)) {
                    foreach ($info->require as $name => $version) {
                        $dependency = new Dependency();
                        $dependency->setName($name);
                        $dependency->setVersion($version);
                        $dependency->setIsDev(false);
                        var_dump($dependency);
                    }
                }
                if (isset($info->{'require-dev'})) {
                    foreach ($info->{'require-dev'} as $name => $version) {
                        $dependency = new Dependency();
                        $dependency->setName($name);
                        $dependency->setVersion($version);
                        $dependency->setIsDev(true);
                        var_dump($dependency);
                    }
                }

            }
        }
    }

}
