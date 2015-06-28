<?php

namespace Kanahei\AdminBundle\Form;

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
            ->add('distributedFrom')
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
            'data_class' => 'Kanahei\GameBundle\Entity\Question'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kanahei_gamebundle_question';
    }
}
