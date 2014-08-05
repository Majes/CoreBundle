<?php 
namespace Majes\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class ListBoxItemType extends AbstractType
{

	protected $session;

	public function __construct(Session $session){
        $this->session = $session;
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
    	$resolver->setDefaults(array(
    	    'csrf_protection' => false
    	));
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('key', 'hidden', array(
            'attr' => array('disabled' => 'disabled'),
        	));

        $builder->add('slug', 'text', array(
            'required' => false,
            ));
        
        $builder->add('value', 'text', array(
        	'required' => true,
        	'constraints' => array(
       		    new NotBlank()
       		)));

    }

    public function getName()
    {
        return 'listboxitemtype';
    }
}