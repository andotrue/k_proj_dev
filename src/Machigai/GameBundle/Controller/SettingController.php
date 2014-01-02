<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        return $this->render('MachigaiGameBundle:Setting:index.html.twig');
    }

    public function nicknameAction()
    {
        return $this->render('MachigaiGameBundle:Setting:nickname.html.twig');
    }

    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Setting:complete.html.twig');
    }

}
