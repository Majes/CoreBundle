<?php

namespace Majes\CoreBundle\Form\Language;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class LanguageTranslationType extends AbstractType
{
    private $_lang;
    public function __construct($lang){
        $this->_lang = $lang;
    }

    public function configureOptions(OptionsResolver $resolver)
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
            'required' => true));

        $builder->add('locale', 'hidden', array('empty_data' => $this->_lang));


    }

    public function getName()
    {
        return 'languagetranslationtype';
    }
}
