<?php

namespace Majes\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaxCharTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'textarea';
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('maxchar', 'count'));
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
        if (array_key_exists('maxchar', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $maxchar = $options['maxchar'];
            } else {
                $maxchar = true;
            }

            // set an "image_url" variable that will be available when rendering this field
            $view->vars['maxchar'] = $maxchar;
        }else
            $view->vars['maxchar'] = null;
    }
}
