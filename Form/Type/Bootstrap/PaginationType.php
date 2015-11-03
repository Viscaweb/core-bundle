<?php

namespace Visca\Bundle\CoreBundle\Form\Type\Bootstrap;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Visca\Bundle\CoreBundle\Util\Pagination\PaginationBuilder;

/**
 * Class PaginationType.
 */
class PaginationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'visca_core_bootstrap_pagination';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(
                [
                    'num_items_per_page',
                    'total_count',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
        $builder = new PaginationBuilder(
            5,
            $form->getData(),
            $options['num_items_per_page'],
            $options['total_count'],
            1
        );

        $view->vars = array_merge(
            $view->vars,
            $builder->getPaginationData()
        );
    }
}
