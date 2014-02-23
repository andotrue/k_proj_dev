<?php

namespace Machigai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MachigaiAdminBundle:Default:index.html.twig');
    }

    public function logoutAction()
    {
        return $this->render('MachigaiAdminBundle:Default:index.html.twig');
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // ログインエラーがあれば、ここで取得
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('MachigaiAdminBundle:Default:login.html.twig', array(
            // ユーザによって前回入力された username
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    public function loginCheckAction()
    {

    }

}
