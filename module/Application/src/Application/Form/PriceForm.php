<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PriceForm extends Form
{
    public function __construct($name = null)
    {
        // ignore the name passed
        parent::__construct('price');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'year',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Jaar',
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Prijs',
            ),
        ));
        $this->add(array(
            'name' => 'unit',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Eenheid',
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
