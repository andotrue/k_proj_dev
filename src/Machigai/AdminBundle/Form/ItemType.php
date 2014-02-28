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
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category', 'entity', array('class' => 'MachigaiGameBundle:ItemCategory', 'property' => 'name'))
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
