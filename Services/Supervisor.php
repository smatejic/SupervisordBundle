<?php

namespace Supervisord\SupervisorBundle\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class Supervisor
 * @package Supervisord\SupervisorBundle\Services
 */
class Supervisor
{
    /** @var array[] */
    protected $configuration;

    /**
     * @var
     */
    private $appDir;

    /**
     * @var
     */
    private $name;

    /**
     * @var TwigEngine
     */
    private $twig;

    /**
     * @param array[]    $config
     * @param string     $appDir
     * @param string     $name
     * @param TwigEngine $twig
     */
    public function __construct($config, $appDir, $name, \Twig_Environment $twig)
    {
        $this->configuration = $config;
        $this->appDir = $appDir;
        $this->name = "-i {$name}";
        $this->twig = $twig;
    }

    /**
     * Generate Configuration File from Command
     *
     * @param string  $fileName file in app/supervisor dir
     * @param array[] $vars     array(name,command,[numprocs])
     * @param bool    $template string non default template
     */
    public function genProgrammConf($fileName, $vars, $template = false)
    {
        $filesystem = new Filesystem();
        $template = $template?:
            '@SupervisordSupervisorBundle/Resources/views/Supervisor/programm.conf.twig';
        $content = $this->twig->render($template, $vars);
        $filesystem->dumpFile(
            "{$this->appDir}/supervisor/{$fileName}.conf",
            $content
        );
    }

    /**
     * Execute and run any command on server supervisor with supervisorctl
     *
     * @param string $cmd supervisorctl command
     *
     * @return Process result after it is finished
     */
    public function execute($cmd)
    {
        $process = new Process("supervisorctl {$cmd}");
        $process->setWorkingDirectory($this->appDir);
        $process->run();
        $process->wait();

        return $process;
    }

    /**
     * Reload and Update Supervisord process
     */
    public function reloadAndUpdate()
    {
        $this->execute('reread');
        $this->execute('update');
    }

    /**
     * Start Supervisor
     */
    public function start()
    {
        if ($this->isStarted()) {
            $process = new Process(
                "supervisord -c {$this->appDir}/supervisord.conf"
            );
            $process->setWorkingDirectory($this->appDir);
            $process->start();
        }
    }

    /**
     * Check if the Supervisor is already running
     * @return bool
     */
    protected function isStarted()
    {
        /* Check Status */
        $result = $this->execute('status')->getOutput();
        if (preg_match('#(sock no such file)|(refused)#', $result)) {
            return false;
        }

        return true;
    }
}
