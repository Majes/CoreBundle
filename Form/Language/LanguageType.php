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

class LanguageType extends AbstractType
{

    protected $session;

    public function __construct(Session $session){
        $this->session = $session;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Majes\CoreBundle\Entity\Language',
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

        $builder->add('locale', 'text', array(
            'required' => true,
            'constraints' => array(
                new NotBlank()
            )));

        $builder->add('host', 'text', array(
            'required' => false));

        $builder->add('is_active', 'checkbox', array(
            'required' => false));

    }

    public function getName()
    {
        return 'language';
    }
}
