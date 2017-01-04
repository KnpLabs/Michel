<?php

namespace AppBundle\Command;

use AppBundle\Entity\Package;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Buzz;

class ImportDataCommand extends ContainerAwareCommand
{
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
        //$this->cleanUpDB();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageList = $this->getContainer()->get('app.packagist.client')->getPackagesList();
        $nb_packages = count($packageList);

        $progress = new ProgressBar($output, $nb_packages);
        $progress->start();
        $output->writeln('');

        $tempI = 0;
        foreach ($packageList as $packageName) {
            // Only use composer/composer package for MVP @TODO:remove this condition when ready
//            if ('composer/composer' !== $packageName){continue;}
            $tempI++;
            if ($tempI > 10) {
                continue;
            }

            $package = $this->getContainer()->get('app.provider.package')->get($packageName);

            if (null !== $package) {
                $this->addPackage($package);

                $this->em->persist($package);
                $this->em->flush();
            }

            $progress->advance();
        }

        $progress->finish();
        $output->writeln('');
    }

    /**
     * @param $package
     */
    private function addPackage(Package $package)
    {
        // Only if the requirement is a "package" (exclude php, etc.)
        if (false !== strpos($package->getName(), '/'))
        {
            $packageInfo = $this->getContainer()->get('app.packagist.client')->getPackageInfo($package->getName());

            if (isset($packageInfo['require']))
            {
                foreach ($packageInfo['require'] as $requirement => $version)
                {
                    if (false !== strpos($requirement, '/'))
                    {
                        $dependency = $this->getContainer()->get('app.provider.package')->get($requirement);
                        if (false === $package->getMyRequirements()->contains($dependency)) {
                            $package->getMyRequirements()->add($dependency);
                        }

                        $this->addPackage($dependency);
                    }
                }
            }

            if (isset($packageInfo['require-dev']))
            {
                foreach ($packageInfo['require-dev'] as $requirement => $version)
                {
                    if (false !== strpos($requirement, '/'))
                    {
                        $dependency = $this->getContainer()->get('app.provider.package')->get($requirement);
                        if (false === $package->getMyDevRequirements()->contains($dependency)) {
                            $package->getMyDevRequirements()->add($dependency);
                        }

                        $this->addPackage($dependency);
                    }
                }
            }
        }
    }

    private function cleanUpDB()
    {
        $entities = $this->em->getRepository('AppBundle:Package')->findAll();
        foreach ($entities as $entity)
        {
            $this->em->remove($entity);
        }
        $this->em->flush();
    }
}