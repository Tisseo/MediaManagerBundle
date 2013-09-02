<?php

namespace CanalTP\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $template = 'CanalTPMediaManagerBundle:Default:index.html.twig';

        return $this->render($template);
    }
}
