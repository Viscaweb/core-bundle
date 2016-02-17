<?php

namespace Visca\Bundle\CoreBundle\Form\Type\Bootstrap;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DropDownWithButtonGroupType.
 */
class DropDownWithButtonGroupType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'selected_choice_label' => '-',
            'disabled' => false,                // Is the element disabled?
            'show_left_right_arrows' => true,    // Display left/right arrows? < [ box ] >
            'button_class' => '',    // Display left/right arrows? < [ box ] >
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('selected_choice_label', $options['selected_choice_label'])
            ->setAttribute('show_left_right_arrows', $options['show_left_right_arrows'])
            ->setAttribute('disabled', $options['disabled'])
            ->setAttribute('button_class', $options['button_class']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['selected_choice_label'] = $options['selected_choice_label'];
        $view->vars['show_left_right_arrows'] = $options['show_left_right_arrows'];
        $view->vars['disabled'] = $options['disabled'];
        $view->vars['button_class'] = $options['button_class'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bootstrap_drop_down_with_button_group';
    }
}
