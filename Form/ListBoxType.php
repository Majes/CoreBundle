<?php
namespace Majes\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class ListBoxType extends AbstractType
{

	protected $session;

	public function __construct(Session $session){
        $this->session = $session;
    }

	public function configureOptions(OptionsResolverInterface $resolver)
	{
    	$resolver->setDefaults(array(
    	    'data_class' => 'Majes\CoreBundle\Entity\ListBox',
    	    'csrf_protection' => false,
    	));
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('name', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));

        $builder->add('content', 'collection', array(
        	'type' => new ListBoxItemType($this->session),
        	'prototype'=>true,
        	'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'attr' => array('class' => 'list-group col-lg-11'),
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));

    }

    public function getName()
    {
        return 'listboxtype';
    }
}
