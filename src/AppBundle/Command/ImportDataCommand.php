<?php

namespace AppBundle\Command;

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
    private $em;

    protected function configure()
    {
        $this
            ->setName('import:data')
            ->setDescription('Import data from packagist')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->cleanUpDB();
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
            if (500 === $iTest) {
                break;
            }
            // Leave Packagist alone!
            if (0 === $iTest%250) {
                sleep(10);
            }
            $iTest++;

            $this->setData($package);

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
    private function setData($package)
    {
        $json = @file_get_contents(self::PACKAGE_INFO_API.$package.'.json');
        if (false === $json) {
            return null;
        }
        $response = json_decode($json);
        $packageInfos = $response;

        //@TODO: if more RAM, add dependencies and vendor to an array to avoid DB requests
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

                $vendorName = explode('/', $info->name)[0];
                $vendorInDB = $this->em->getRepository('AppBundle:Vendor')->findOneByName($vendorName);
                if (1 === count($vendorInDB)) {
                    $package->setVendor($vendorInDB);
                } else {
                    $vendor = new Vendor();
                    $vendor->setName($vendorName);
                    $package->setVendor($vendor);
                }

                if (isset($info->require)) {
                    foreach ($info->require as $name => $version) {
                        $dependencyInDB = $this->em->getRepository('AppBundle:Dependency')->findOneBy([
                            'name' => $name,
                            'version' => $version,
                            'isDev' => false,
                        ]);
                        if (1 === count($dependencyInDB)) {
                            $package->addDependency($dependencyInDB);
                        } else {
                            $dependency = new Dependency();
                            $dependency->setName($name);
                            $dependency->setVersion($version);
                            $dependency->setIsDev(false);
                            $package->addDependency($dependency);
                        }
                    }
                }
                if (isset($info->{'require-dev'})) {
                    foreach ($info->{'require-dev'} as $name => $version) {
                        $dependencyInDB = $this->em->getRepository('AppBundle:Dependency')->findOneBy([
                            'name' => $name,
                            'version' => $version,
                            'isDev' => true,
                        ]);
                        if (1 === count($dependencyInDB)) {
                            $package->addDependency($dependencyInDB);
                        } else {
                            $dependency = new Dependency();
                            $dependency->setName($name);
                            $dependency->setVersion($version);
                            $dependency->setIsDev(true);
                            $package->addDependency($dependency);
                        }
                    }
                }

                $this->em->persist($package);
                $this->em->flush();
            }
        }
    }

    private function cleanUpDB()
    {
        $entities = $this->em->getRepository('AppBundle:Dependency')->findAll();
        foreach ($entities as $entity) {
            $this->em->remove($entity);
        }
        $entities = $this->em->getRepository('AppBundle:Package')->findAll();
        foreach ($entities as $entity) {
            $this->em->remove($entity);
        }
        $entities = $this->em->getRepository('AppBundle:Vendor')->findAll();
        foreach ($entities as $entity) {
            $this->em->remove($entity);
        }
        $this->em->flush();
    }
}
