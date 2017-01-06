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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageList = $this->getContainer()->get('app.packagist.client')->getPackagesList();
        $nbPackages = count($packageList);

        $progress = new ProgressBar($output, $nbPackages);
        $progress->start();

        foreach ($packageList as $packageName) {
            $package = $this->getContainer()->get('app.provider.package')->get($packageName);

            if (null !== $package) {
                if (false !== strpos($package->getName(), '/')) {
                    $packageInfo = $this->getContainer()->get('app.packagist.client')->getPackageInfo($package->getName());
                    $this->getContainer()->get('app.hydrator.package')->hydrate($package, $packageInfo);
                }
            }
            $this->em->persist($package);
            $this->em->flush();

            $progress->advance();
        }
        $progress->finish();
        $output->writeln('');
    }
}