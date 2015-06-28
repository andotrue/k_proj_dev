<?php

namespace Kanahei\AdminBundle\Classes;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Pager
{

    var $entityManager;
    var $serviceContainer;

    var $inc;
    var $offset;
    var $count;
    var $is_next = true;
    var $path;

    public function __construct($serviceContainer, $entityManager)
    {
        $this->serviceContainer = $serviceContainer;
        $this->entityManager = $entityManager;

        $this->offset = $this->serviceContainer->get('request')->get('offset');
        if(!$this->offset) $this->offset = 0;
        if(!preg_match('/^\d{1,}$/', $this->offset)) $this->offset = 0;

    }
    public function setPath($path){
        $this->path = $path;
    }
    public function setInc($inc){
        $this->inc = $inc;
    }
    public function getRepository($namespace, $where = array(), $orderby = array()){

        $entities = $this->entityManager->getRepository($namespace)->findBy(
            $where,
            $orderby,
            $this->inc,
            $this->offset
        );
        $this->count = count($entities);
        $this->is_next = $this->count >= $this->inc ? true : false;

        return $entities;

    }
    public function getParameters(){

        return array(
            'next' => $this->offset + $this->inc,
            'prev' => $this->offset - $this->inc,
            'current' => $this->offset,
            'is_next' => $this->is_next,
            'inc' => $this->inc,
            'count' => $this->count,
            'path' => $this->path
        );
    }
}