<?php

namespace Supervisord\SupervisorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateConfigCommand
 * @package Supervisord\SupervisorBundle\Command
 */
class GenerateConfigCommand extends ContainerAwareCommand
{
    /**
     * Command Configuration
     */
    protected function configure()
    {
        $this
            ->setName('supervisor:gen')
            ->addArgument('name', InputArgument::REQUIRED, 'Programm name')
            ->addArgument('cmd', InputArgument::IS_ARRAY, 'Symfony command')
            ->addOption('count', null, InputOption::VALUE_OPTIONAL, 'numproc')
            ->setDescription('run supervisor instance')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $supervisor = $this->getContainer()->get('supervisor.service');
        $supervisor->genProgrammConf($input->getArgument('name'), array(
            'name' => $input->getArgument('name'),
            'command' => join(' ', $input->getArgument('cmd')),
            'numprocs' => $input->getOption('count')?:null,
        ));
    }
}
