<?php

namespace Machigai\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questionNumber')
            ->add('level')
            ->add('failLimit')
            ->add('timeLimit')
            ->add('clearPoint')
            ->add('bonusPoint')
            ->add('distributedFrom',"datetime", array(
				'model_timezone' => 'UTC',
				'view_timezone' => "Asia/Tokyo"
				))
            ->add('distributedTo')
            ->add('isDelete')
            ->add('copyrightUrl')
            ->add('questionTitle')
            ->add('qcode')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Machigai\GameBundle\Entity\Question'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'machigai_gamebundle_question';
    }
}
