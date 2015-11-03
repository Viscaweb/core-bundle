<?php

namespace Visca\Bundle\CoreBundle\Exception;

use Exception;
use Symfony\Component\Form\FormInterface;

/**
 * Class InvalidFormException.
 */
class InvalidFormException extends Exception
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @param FormInterface $form
     */
    public function __construct(FormInterface $form)
    {
        $this->form = $form;

        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        $this->message = implode('; ', $errors);
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $messages = [];

        foreach ($this->form->getErrors() as $error) {
            $messages[] = $error->getMessage();
        }

        return implode('; ', $messages);
    }
}
