<?php

namespace Majes\CoreBundle\Form\Language;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class LanguageTokenType extends AbstractType
{

    private $_lang;
    public function __construct($lang){
        $this->_lang = $lang;
    }

    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majes\CoreBundle\Entity\LanguageToken',
            'csrf_protection' => false,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder->add('token', 'text', array(
            'required' => true,
            'constraints' => array(
                new NotBlank()
            )));

        $builder->add('translation', new LanguageTranslationType($this->_lang));

    }

    public function getName()
    {
        return 'languagetokentype';
    }
}
