<?php

namespace Supervisord\SupervisorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class GenerateBaseCommand
 * @package Supervisord\SupervisorBundle\Command
 */
class GenerateBaseCommand extends ContainerAwareCommand
{
    /**
     * Default Configuration
     */
    protected function configure()
    {
        $this
            ->setName('supervisor:init')
            ->setDescription('Create base config file supervisor');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSystem = new Filesystem();
        $kernelDir = $this->getContainer()->getParameter('kernel.root_dir');

        /* Twig as first class citizen read more at Symfony docs */
        $conf = $this->getContainer()
            ->get('twig')
            ->render(
                '@SupervisordSupervisor/Supervisor/supervisord.conf.twig',
                []
            );

        $fileSystem->dumpFile("{$kernelDir}/supervisord.conf", $conf);
        $fileSystem->mkdir("{$kernelDir}/supervisor");
    }
}
