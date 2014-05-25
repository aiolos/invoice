<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InvoiceForm extends Form implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function init()
    {
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'year',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Jaar',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'filename',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Bestandsnaam',
            ),
        ));
        // Submit
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Factuur aanmaken',
                'id' => 'submitbutton',
                'tabindex' => 10,
            ),
        ));
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}