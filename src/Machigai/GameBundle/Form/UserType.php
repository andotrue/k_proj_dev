<?php

namespace Machigai\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	  ->add('nickname', 'text')
	  ->add('currentPoint', 'text')
	  ->add('MailAddress', 'email')
	  ->getForm();
    }

    public function getName()
    {
        return 'MachigaiGamebundle_userytype';
    }
}
