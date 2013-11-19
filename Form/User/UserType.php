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

class UserType extends AbstractType
{

	protected $session;

	public function __construct(Session $session){
        $this->session = $session;
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
    	$resolver->setDefaults(array(
    	    'data_class' => 'Majes\CoreBundle\Entity\User\User',
    	    'csrf_protection' => false,
    	));
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder->add('media', 'file', array(
            'required' => false,
            'mapped' => false,
            'label' => 'Avatar',
            'image_path' => 'webPath'));

        $builder->add('firstname', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));
        
        $builder->add('lastname', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));
        
        $builder->add('email', 'email', array(
        	'required' => true,
        	'constraints' => array(
       		    new Email()
       		)));

        $builder->add('is_active', 'checkbox', array(
        	'required' => false));

        $builder->add('wysiwyg', 'checkbox', array(
            'required' => false));

		$langs = $this->session->get('langs');
		$locale_array = array();
		foreach($langs as $lang){
			$locale_array[$lang->getLocale()] = $lang->getName();
		}
        //Push langs
        $builder->add('locale', 'choice', array(
        	'choices' => $locale_array,
        	'required' => true));

        $builder->add('password', 'repeated', array(
        	'type' => 'password',
        	'required' => false,
        	'first_name' => 'password',
        	'second_name' => 'password_confirm'
        	));
    }

    public function getName()
    {
        return 'usertype';
    }
}