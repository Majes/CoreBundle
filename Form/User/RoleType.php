<?php 
// src/Majes/CoreBundle/Form/User/Myaccount.php
namespace Majes\CoreBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class RoleType extends AbstractType
{


	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
    	$resolver->setDefaults(array(
    	    'data_class' => 'Majes\CoreBundle\Entity\User\Role',
    	    'csrf_protection' => false,
    	));
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('role', 'text', array(
            'required' => true,
            'constraints' => array(
                new NotBlank()
            )));
        
        $builder->add('name', 'text', array(
            'required' => true,
            'constraints' => array(
                new NotBlank()
            )));

        $builder->add('bundle', 'text', array(
            'required' => false));

        $builder->add('isSystem', 'checkbox', array(
            'required' => false));
    }

    public function getName()
    {
        return 'roletype';
    }
}