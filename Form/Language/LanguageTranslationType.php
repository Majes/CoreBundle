<?php 

namespace Majes\CoreBundle\Form\Language;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class LanguageTranslationType extends AbstractType
{
    public function __construct(){}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majes\CoreBundle\Entity\LanguageTranslation',
            'csrf_protection' => false,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('catalogue', 'text', array(
            'required' => true,
            'constraints' => array(
                new NotBlank()
            )));

        
      
        $builder->add('translation', 'textarea', array(
            'required' => false));

     
    }

    public function getName()
    {
        return 'languagetranslationtype';
    }
}