<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

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
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Naam',
            ),
        ));
        $initials = new Element\Text('initials');
        $initials
            ->setLabel('Initialen')
            ->setAttributes(array(
                'class' => 'initials',
                'style' => 'width:80px;',
                'size'  => '5',
            ));
        $this->add($initials);
//        $this->add(array(
//            'name' => 'initials',
//            'type' => 'text',
//            'attributes' => array(
//                'type'  => 'text',
//                'size' => 10,
//            ),
//            'options' => array(
//                'label' => 'Initialen',
//            ),
//        ));
        $this->add(array(
            'name' => 'gender',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Geslacht',
                'empty_option' => 'Kies geslacht',
                'value_options' => array(
                    'M' => 'Man',
                    'V' => 'Vrouw',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'street',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Straat',
            ),
        ));
        $this->add(array(
            'name' => 'housenumber',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Huisnummer',
            ),
        ));
        $this->add(array(
            'name' => 'postalcode',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Postcode',
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Plaats',
            ),
        ));
        $this->add(array(
            'name' => 'country',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Land',
                'value_options' => array(
                    'NL' => 'Nederland',
                    'DE' => 'Duitsland',
                    'GB' => 'Engeland',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'language',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Taal',
                'value_options' => array(
                    'NL' => 'Nederlands',
                    'DE' => 'Duits',
                    'EN' => 'Engels',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));
        $this->add(array(
            'name' => 'telephone',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Telefoon',
            ),
        ));
        $this->add(array(
            'name' => 'shipname',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Scheepsnaam',
            ),
        ));
        $this->add(array(
            'name' => 'debinr',
            'type'  => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Debiteurnummer',
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
