<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ObjectownershipForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('objectownership');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Naam',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'fromDate',
            'attributes' => array(
                'type'  => 'date',
            ),
            'options' => array(
                'label' => 'Vanaf datum',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'toDate',
            'attributes' => array(
                'type'  => 'date',
            ),
            'options' => array(
                'label' => 'Tot datum',
            ),
        ));
        $this->add(array(
            'name' => 'type',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Type',
                'empty_option' => 'Kies Type',
                'value_options' => array(
                    '1' => 'Box',
                    '2' => 'Stroomverbruik',
                    '3' => 'Winterstalling',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
