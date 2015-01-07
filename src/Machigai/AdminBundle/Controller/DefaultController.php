<?php

namespace Machigai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Machigai\GameBundle\Entity\Regist;

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
            
                $logger = $this->get('logger');
                $logger->info('---------------------- PUSH通知開始 ----------------------');
            
		$request = $this->getRequest();
		$message = $request->request->get('message');

		$mode = $request->request->get('mode');
		
		$key = "AIzaSyCWYgtdcZXCBAeJY9Z41LrnKAP0hpZMxpA";
		
		$url = 'https://android.googleapis.com/gcm/send';
		
		$em = $this->getDoctrine()->getManager();

		$regs = null;
		$regRepo = $em->getRepository('MachigaiGameBundle:Regist');
		
		if($mode == "1"){

			// 全体
			$regs = $regRepo->findAll();
			
		} else if ($mode == "2"){
			
			// 会員
			$query = $regRepo->createQueryBuilder("r")
				->where('r.user_id != 0')
			->getQuery();
		
			$regs = $query->getResult();			
			
		} else if ($mode == "3"){

			// 会員
			$query = $regRepo->createQueryBuilder("r")
				->where('r.user_id = 0')
			->getQuery();
		
			$regs = $query->getResult();			
		}
                $logger->info('送信対象件数： ' . count($regs));
                
		
		foreach($regs as $reg){
                        $logger->info('送信開始 --------------');
                        $logger->info('userId : ' . $reg->getUserId());
			$data = array(
				'data.message' => $message,
				'collapse_key' => "1",
				'registration_id' => $reg->getCode(),
			);
			
			$data = http_build_query($data, "", "&");	
			
			$headers = array(
				'Authorization: key=' .$key,
				"Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
			);
			$options = array('http' => array(
				'method' => 'POST',
				'content' => $data,
				'header' => implode("\r\n", $headers),
                                'ignore_errors' => true
			));
			$response = file_get_contents($url, false, stream_context_create($options));

                        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
                        $status_code = $matches[1];
                        
                        $logger->info('http status : ' . $status_code);

                        if($status_code != '200'){
                            $logger->info('再送信');
                            // ステータスコードが200以外の場合はリトライ
                            $response = file_get_contents($url, false, stream_context_create($options));
                            preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
                            $status_code = $matches[1];
                            if($status_code != '200'){
                                $logger->info('再送信失敗');
                            } else {
                                $logger->info('再送信成功');
                            }
                        } else {
                            $logger->info('送信完了');
                        }
                        $logger->info('送信完了 --------------');

			usleep(500000);
		}
                $logger->info('---------------------- PUSH通知完了 ----------------------');
		
		return $this->redirect($this->generateUrl('push_alert'));
	}
}
