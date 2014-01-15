<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GameController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MachigaiGameBundle:Game:index.html.twig');   
    }
    public function selectAction()
    {
        $user = $this->getUser();
        $histories = null;
        $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
                                    order by p.gameStatus desc')
                ->getResult();

    	return $this->render('MachigaiGameBundle:Game:select.html.twig',array('user'=>$user,'questions'=>$questions,'histories'=>$histories));
    }
    public function sortQuestionsAction($sort){
        $user = $this->getUser();
        $userId = $user->getId();
        $histories = null;
  
        $questions = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->findAll();
  
        switch ($sort) {
            case 'DESC':
            case 'ASC' :
                $questions = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:Question')
                ->findBy(array(),array('createdAt'=>$sort));
                break;
            case 'suspended':
                $pre_histories = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:PlayHistory')
                ->getSuspended($userId);

                $histories = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p 
                                    left join  p.question q 
                                    left join p.user u 
                                    where u.id = :id and p.gameStatus = 3 
                                    order by q.id asc')
                ->setParameter('id', $user->getId())
                ->getResult();
                break;
            case 'notCleared':
                $pre_histories = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:PlayHistory')
                ->getNotCleared($userId);

                $histories = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p 
                                    left join  p.question q 
                                    left join p.user u 
                                    where u.id = :id and p.gameStatus = 2 
                                    order by q.id asc')
                ->setParameter('id', $user->getId())
                ->getResult();
                break;
            default:
                break;
        }
        $list = $this->makeList($histories);
        return $this->render('MachigaiGameBundle:Game:select.html.twig',array('user'=>$user,'questions'=>$questions,'histories'=>$histories));
    }
    public function makeList($histories){
        $list = array();
        for ($i=0; $i < count($histories); $i++) { 
            $history = $histories[$i];
            $question = $history->getQuestion();
            $data = array(  'gameStatus' => $history->getGameStatus(),
                            'questionId' => $question->getId()
                        );
            $list[$i] = $data;
       }
        return $list;
    }
    public function getPlayHistory()
    {
        $history = array('1' => array( '1' => array( 'qcode'=>'1' )));
        return json_encode($history);
    }

    public function playAction($id)
    {
    	return $this->render('MachigaiGameBundle:Game:play.html.twig');	
    }
    public function downloadAction($level,$qcode,$type)
    {
        $types = array('xml' => '.xml', 'first' => '_1.png', 'second' => '_2.png');
        $format = $types[$type];
        $file = dirname(__FILE__).'/../Resources/questions/'.$level.'/'. $qcode . '/MS'. sprintf('%05d',$qcode). $format;

        $response = new BinaryFileResponse($file);
//        $response->prepare($request);
        if ($type == 'xml'){
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('charset', 'UTF-8');
        }else{
            $response->headers->set('Content-Type', 'image/png');
        }
        return  $response->send();
    }

    public function finishAction()
    {
	return $this->render('MachigaiGameBundle:Game:finish.html.twig');	
    }

    public function clearAction()
    {
	return $this->render('MachigaiGameBundle:Game:clear.html.twig');	
    }

    public function failAction()
    {
	return $this->render('MachigaiGameBundle:Game:fail.html.twig');	
    }

}
