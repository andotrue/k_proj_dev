<?php

namespace Machigai\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname','text', array('label'=>' ' ))
	  ->add('confirm', 'submit', array('label'=>'登録内容確認'))
	  ->add('register', 'submit', array('label'=>'登録'))
	  ->add('ammend', 'button', array('label'=>'修正'))
	  ->getForm();
    }

    public function getName()
    {
        return 'MachigaiGamebundle_userytype';
    }
}
