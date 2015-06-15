<?php

namespace Machigai\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemCode')
            ->add('name')
            ->add('itemPath')
            ->add('consumePoint')
            ->add('description')
            ->add('popularityRank')
            ->add('distributedFrom')
            ->add('distributedTo')
            ->add('category', 'entity', array('class' => 'MachigaiGameBundle:ItemCategory', 'property' => 'name'))
            ->add('group', 'entity', array('class' => 'MachigaiGameBundle:ItemGroup', 'property' => 'name'))
            //->add('platformCode')
            ->add('platformCode', 'choice', array(
            		'choices' => array('1' => 'Androidのみ', '2' => 'iOSのみ', '12' => '両方'), 
            		'empty_value' => ''
            ));
            //->add('platform', 'choice', array(
            //		'choices' => array('1' => 'Andのみ', '2' => 'iOSのみ', '12' => '両方'),
            //		//'choices' => getPlatform(),
            //		'preferred_choices' => array('2'),
            //))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Machigai\GameBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'machigai_gamebundle_item';
    }
    
}
