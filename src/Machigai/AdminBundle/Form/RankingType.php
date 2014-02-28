<?php

namespace Machigai\AdminBundle\Form;

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
            ->add('user', 'entity', array('class' => 'MachigaiGameBundle:User', 'property' => 'id'))
            ->add('year')
            ->add('month')
            ->add('level')
            ->add('rank')
            ->add('clearTime')
            ->add('bonusPoint')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Machigai\GameBundle\Entity\Ranking'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'machigai_gamebundle_Ranking';
    }
}
