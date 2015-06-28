<?php

namespace Kanahei\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RankingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', array('class' => 'KanaheiGameBundle:User', 'property' => 'id'))
            ->add('year')
            ->add('month')
            ->add('level')
            ->add('rank')
            ->add('clearTime')
            ->add('bonusPoint')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kanahei\GameBundle\Entity\Ranking'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kanahei_gamebundle_Ranking';
    }
}
