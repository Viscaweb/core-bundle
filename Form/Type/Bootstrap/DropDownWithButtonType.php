<?php

namespace Visca\Bundle\CoreBundle\Form\Type\Bootstrap;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class DropDownWithButtonType.
 */
class DropDownWithButtonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
        $view->vars = array_replace(
            $view->vars,
            [
                'btn_group_classes' => ['hola']
            ]
        );
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
        return 'bootstrap_drop_down_with_button';
    }
}
