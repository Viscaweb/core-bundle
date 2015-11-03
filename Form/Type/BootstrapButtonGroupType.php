<?php

namespace Visca\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BootstrapButtonGroupType.
 */
class BootstrapButtonGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'toolbarWrapper' => true,
            'wrapperClass' => null,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('toolbarWrapper', $options['toolbarWrapper'])
            ->setAttribute('wrapperClass', $options['wrapperClass']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['toolbarWrapper'] = $options['toolbarWrapper'];
        $view->vars['wrapperClass'] = $options['wrapperClass'];
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bootstrap_button_group_type';
    }
}
