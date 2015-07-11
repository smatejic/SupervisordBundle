<?php

namespace Supervisord\SupervisorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Supervisord\SupervisorBundle\Command
 */
class StartCommand extends ContainerAwareCommand
{
    /**
     * Command Configuration
     */
    protected function configure()
    {
        $this
            ->setName('supervisor:start')
            ->setDescription('start supervisor instance');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('supervisor.service')->run();
    }
}
