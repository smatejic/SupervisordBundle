<?php

namespace Supervisord\SupervisorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SupervisordSupervisorBundle:Default:index.html.twig', array('name' => $name));
    }
}
