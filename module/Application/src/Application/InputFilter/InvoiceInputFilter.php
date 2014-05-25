<?php

namespace Application\InputFilter;

use Zend\InputFilter\InputFilter;

class InvoiceInputFilter extends InputFilter
{
    public function init()
    {
        // Filename
        $this->add(array(
            'name' => 'id',
            'required' => false,
            'filters' => array(
            ),
            'validators' => array(
            ),
        ));

        // Filename
        $this->add(array(
            'name' => 'filename',
            'required' => true,
            'filters' => array(
            ),
            'validators' => array(
            ),
        ));

        // Year
        $this->add(array(
            'name' => 'year',
            'required' => true,
            'filters' => array(
            ),
            'validators' => array(
            ),
        ));
    }
}
