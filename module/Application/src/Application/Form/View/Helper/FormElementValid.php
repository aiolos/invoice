<?php
namespace Application\Form\View\Helper;

use Zend\View\Helper\AbstractHelper as BaseAbstractHelper;
use Zend\InputFilter\InputInterface;
use Zend\Form\FormInterface;

class FormElementValid extends BaseAbstractHelper
{
    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormElement
     */
    public function __invoke(FormInterface $form = null, InputInterface $filter = null)
    {
        if (is_null($form)) {
            return $this;
        }

        return $this->render($form, $filter);
    }

    public function render(FormInterface $form = null, InputInterface $filter = null)
    {
        if (!$form->hasValidated()) {
            return '';
        }

        if ($filter->isValid()) {
            return 'has-success';
        } else {
            return 'has-error';
        }
    }
}
