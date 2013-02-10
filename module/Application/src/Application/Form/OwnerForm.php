<?php
namespace Application\Form;

use Zend\Form\Form;

class OwnerForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('owner');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Naam',
            ),
        ));
        $this->add(array(
            'name' => 'gender',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Geslacht',
            ),
        ));
        $this->add(array(
            'name' => 'street',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Straat',
            ),
        ));
        $this->add(array(
            'name' => 'housenumber',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Huisnummer',
            ),
        ));
        $this->add(array(
            'name' => 'postalcode',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Postcode',
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Plaats',
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Land',
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