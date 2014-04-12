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
	
	public function pushAlertAction()
	{
        return $this->render('MachigaiAdminBundle:Default:push_alert.html.twig');
	}
	
	public function pushAlertCreateAction()
	{
		$request = $this->getRequest();
		$message = $request->request->get('message');

		$key = "AIzaSyCWYgtdcZXCBAeJY9Z41LrnKAP0hpZMxpA";
		
		$url = 'https://android.googleapis.com/gcm/send';
		
        $em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('MachigaiGameBundle:User')->findBy(array(
			'regist_id' => null
		));
		
		foreach($users as $user){
			$data = array(
				'data.message' => $message,
				'collapse_key' => "1",
				'registration_id' => $user->getRegistId(),
			);
			$headers = array(
				'Authorization: key=' .$key,    
			);
			$options = array('http' => array(
				'method' => 'POST',
				'content' => http_build_query($data),
				'header' => implode("\r\n", $headers),
			));
			file_get_contents($url, false, stream_context_create($options));
			
			sleep(1);
		}
	}
}
