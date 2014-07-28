<?php 
namespace Majes\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class HostType extends AbstractType
{

	protected $session;

	public function __construct(Session $session){
        $this->session = $session;
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
    	$resolver->setDefaults(array(
    	    'data_class' => 'Majes\CoreBundle\Entity\Host',
    	    'csrf_protection' => false,
    	));
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('title', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));
        
        $builder->add('url', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));
        

        $builder->add('is_multilingual', 'checkbox', array(
        	'required' => false));

    }

    public function getName()
    {
        return 'hosttype';
    }
}