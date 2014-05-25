<?php
namespace Application\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;
use Zend\InputFilter\InputInterface;

class FormElementWw extends FormElement
{
    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormElement
     */
    public function __invoke(ElementInterface $element = null, InputInterface $filter = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $filter);
    }

    public function render(ElementInterface $element, InputInterface $filter = null)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        if ($element instanceof \Application\Form\Element\StaticText) {
            $helper = $renderer->plugin('formElementStaticText');
            return $helper($element);
        }

        if ($element instanceof \Zend\Form\Element\Submit) {
            $element->setAttribute('class', 'btn btn-success');
        }

        if ($element instanceof \Zend\Form\Element\Date) {
            $element->setAttribute('class', 'form-control');
        }
        if ($element instanceof \Zend\Form\Element\Text) {
            $element->setAttribute('class', 'form-control');
            if (!is_null($filter) && $filter->isRequired()) {
                $element->setAttribute('required', true);
            }
            if (!is_null($filter) && !$filter->isValid() ) {

            }
        }

        if ($element instanceof \Zend\Form\Element\Select) {
            $element->setAttribute('class', trim($element->getAttribute('class') . ' form-control'));
        }

        return parent::render($element);
    }
}
