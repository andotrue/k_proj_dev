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
        $questions = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->findAll();
        $histories = null;
    	return $this->render('MachigaiGameBundle:Game:select.html.twig',array('user'=>$user,'questions'=>$questions,'histories'=>$histories));
    }
    public function sortQuestionsAction($sort){
        $user = $this->getUser();
        $userId = $user->getId();
        $histories = null;
        if($sort != "DESC" and $sort != "ASC"){
            if($sort == "suspended"){
                $pre_histories = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:PlayHistory')
                ->getSuspended($userId);
                        $histories = array();
                            foreach ($pre_histories as $history) {
                                $histories[] = $history->getQuestion()->getId();
                            }
                
/*$questions = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery('SELECT q from MachigaiGameBundle.Question q left join  q.playHistories p join p.user u where u.Id = :id and p.suspendedIime is not null order by q.id asc;')->setParameter('id', $user->getId());
*/
            }elseif($sort == "notCleared"){
                $pre_histories = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:PlayHistory')
                ->getNotCleared($userId);
                        $histories = array();
                        if(!empty($pre_histories)){
                        foreach ($pre_histories as $history) {
                            $histories[] = $history->getQuestion()->getId();
                        }
                    }
            }
        }else{
            $questions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:Question')
            ->findBy(array(),array('createdAt'=>$sort));
        }

        $questions = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->findAll();
        return $this->render('MachigaiGameBundle:Game:select.html.twig',array('user'=>$user,'questions'=>$questions,'histories'=>$histories));
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
