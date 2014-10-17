<?php

namespace Majes\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaPickerTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('mediapicker'));
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
        if (array_key_exists('mediapicker', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $mediapicker = $options['mediapicker'];
            } else {
                $mediapicker = null;
            }

            // set an "image_url" variable that will be available when rendering this field
            $view->vars['mediapicker'] = $mediapicker;
        }else
            $view->vars['mediapicker'] = null;
    }
}
