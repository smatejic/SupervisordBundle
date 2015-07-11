<?php

namespace Supervisord\SupervisorBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class ControlCommand
 * @package Supervisord\SupervisorBundle\Command
 */
class ControlCommand extends ContainerAwareCommand
{
    /**
     * Command Configuration
     */
    protected function configure()
    {
        $this
            ->setName('supervisor:command')
            ->addArgument(
                'cmd',
                InputArgument::IS_ARRAY,
                'supervisorCtl command'
            )
            ->setDescription('execute supervisorCtl command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->getContainer()
            ->get('supervisor.service')
            ->execute($input->getArgument('cmd'));

        echo $result->getOutput();
    }
}
