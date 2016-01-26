<?php
// src/Majes/CoreBundle/Form/User/Myaccount.php
namespace Majes\CoreBundle\Form\Language;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

class LanguageImportType extends AbstractType
{


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('csv', 'file', array(
            'required' => false,
            'mapped' => false,
            'label' => 'Csv file'));


    }

    public function getName()
    {
        return 'languageImport';
    }
}
