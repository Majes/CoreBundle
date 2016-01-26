<?php

namespace Majes\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Select2TypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'choice';
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('select2'));
    }

    /**
     * Pass the image url to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('select2', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $select2 = $options['select2'];
            } else {
                $select2 = true;
            }

            // set an "image_url" variable that will be available when rendering this field
            $view->vars['select2'] = $select2;
        }else
            $view->vars['select2'] = null;
    }
}
